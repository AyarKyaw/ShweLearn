<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Google\Client as GoogleClient;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme('https');
        }

        Mail::extend('gmail', function (array $config = []) {
            return new class extends AbstractTransport {
                protected function doSend(SentMessage $message): void
                {
                    $email = $message->getOriginalMessage();

                    $client = new GoogleClient();
                    $client->setClientId(config('services.gmail.client_id'));
                    $client->setClientSecret(config('services.gmail.client_secret'));
                    $client->refreshToken(config('services.gmail.refresh_token'));

                    $service = new Gmail($client);

                    $rawMessage = base64_encode($email->toString());
                    $rawMessage = str_replace(['+', '/', '='], ['-', '_', ''], $rawMessage);

                    $gmailMessage = new Message();
                    $gmailMessage->setRaw($rawMessage);

                    $service->users_messages->send('me', $gmailMessage);
                }

                public function __toString(): string
                {
                    return 'gmail_api';
                }
            };
        });
    }
}