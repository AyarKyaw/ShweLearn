<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Error visitor log
        |--------------------------------------------------------------------------
        */

        $logPath = storage_path('logs/error-visitors.log');

        $logContent = '';

        if (File::exists($logPath)) {
            $logContent = File::get($logPath);
        }

        /*
        |--------------------------------------------------------------------------
        | Parse logs
        |--------------------------------------------------------------------------
        */

        $allEntries = $this->parseLogs($logContent);

        /*
        |--------------------------------------------------------------------------
        | Log levels
        |--------------------------------------------------------------------------
        */

        $errorLevels = [
            'ERROR',
            'CRITICAL',
            'ALERT',
            'EMERGENCY',
        ];

        $warningLevels = [
            'WARNING',
        ];

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = [
            'total' => $allEntries->count(),

            'errors' => $allEntries
                ->filter(function ($entry) use ($errorLevels) {
                    return in_array(
                        $entry['level'],
                        $errorLevels,
                        true
                    );
                })
                ->count(),

            'warnings' => $allEntries
                ->filter(function ($entry) use ($warningLevels) {
                    return in_array(
                        $entry['level'],
                        $warningLevels,
                        true
                    );
                })
                ->count(),

            /*
             * Count unique accounts by user_id.
             */
            'accounts' => $allEntries
                ->filter(function ($entry) {
                    return !empty($entry['user_id']);
                })
                ->unique(function ($entry) {
                    return (string) $entry['user_id'];
                })
                ->count(),

            /*
             * Count guest error entries.
             */
            'guests' => $allEntries
                ->filter(function ($entry) {
                    return empty($entry['user_id']);
                })
                ->count(),

            'today' => $allEntries
                ->filter(function ($entry) {
                    return $entry['date'] === now()->format('Y-m-d');
                })
                ->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $level = strtolower(
            request('level', 'all')
        );

        $visitor = strtolower(
            request('visitor', 'all')
        );

        $search = trim(
            request('search', '')
        );

        /*
        |--------------------------------------------------------------------------
        | Validate filters
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $level,
            [
                'all',
                'error',
                'warning',
            ],
            true
        )) {
            $level = 'all';
        }

        if (!in_array(
            $visitor,
            [
                'all',
                'account',
                'guest',
            ],
            true
        )) {
            $visitor = 'all';
        }

        /*
        |--------------------------------------------------------------------------
        | Apply filters
        |--------------------------------------------------------------------------
        */

        $entries = $allEntries;

        /*
        |--------------------------------------------------------------------------
        | Level filter
        |--------------------------------------------------------------------------
        */

        if ($level === 'error') {
            $entries = $entries->filter(
                function ($entry) use ($errorLevels) {
                    return in_array(
                        $entry['level'],
                        $errorLevels,
                        true
                    );
                }
            );
        }

        if ($level === 'warning') {
            $entries = $entries->filter(
                function ($entry) use ($warningLevels) {
                    return in_array(
                        $entry['level'],
                        $warningLevels,
                        true
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Visitor filter
        |--------------------------------------------------------------------------
        |
        | user_id exists -> account
        | user_id null   -> guest
        |
        */

        if ($visitor === 'account') {
            $entries = $entries->filter(
                function ($entry) {
                    return !empty($entry['user_id']);
                }
            );
        }

        if ($visitor === 'guest') {
            $entries = $entries->filter(
                function ($entry) {
                    return empty($entry['user_id']);
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $searchLower = strtolower($search);

            $entries = $entries->filter(
                function ($entry) use ($searchLower) {

                    $fields = [
                        $entry['message'] ?? '',
                        $entry['exception'] ?? '',
                        $entry['file'] ?? '',
                        $entry['url'] ?? '',
                        $entry['visitor_name'] ?? '',
                        $entry['visitor_email'] ?? '',
                        $entry['user_id'] ?? '',
                        $entry['visitor_id'] ?? '',
                        $entry['visitor_guard'] ?? '',
                        $entry['method'] ?? '',
                        $entry['ip'] ?? '',
                    ];

                    foreach ($fields as $field) {
                        if (str_contains(
                            strtolower((string) $field),
                            $searchLower
                        )) {
                            return true;
                        }
                    }

                    return false;
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Newest first
        |--------------------------------------------------------------------------
        */

        $entries = $entries
            ->sortByDesc(function ($entry) {
                return $entry['timestamp'];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Return view
        |--------------------------------------------------------------------------
        */

        return view(
            'developer.logs.index',
            [
                'entries' => $entries,
                'statistics' => $statistics,
                'level' => $level,
                'visitor' => $visitor,
                'search' => $search,
            ]
        );
    }

    /**
     * Parse Laravel log content.
     */
    private function parseLogs(string $content)
    {
        if (trim($content) === '') {
            return collect();
        }

        /*
        |--------------------------------------------------------------------------
        | Split Laravel log entries
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | [2026-09-29 10:20:30] local.ERROR: ...
        |
        */

        preg_match_all(
            '/^\[(.*?)\]\s+\w+\.(\w+):\s*(.*?)(?=^\[|\z)/ms',
            $content,
            $matches,
            PREG_SET_ORDER
        );

        return collect($matches)
            ->map(function ($match) {

                /*
                |--------------------------------------------------------------------------
                | Basic information
                |--------------------------------------------------------------------------
                */

                $timestamp = trim(
                    $match[1] ?? ''
                );

                $level = strtoupper(
                    trim(
                        $match[2] ?? 'UNKNOWN'
                    )
                );

                $rawMessage = trim(
                    $match[3] ?? ''
                );

                /*
                |--------------------------------------------------------------------------
                | USER ID
                |--------------------------------------------------------------------------
                |
                | This is the source of truth.
                |
                | "user_id":6
                |     => account
                |
                | "user_id":null
                |     => guest
                |
                */

                $userId = $this->extractContextValue(
                    $rawMessage,
                    'user_id'
                );

                /*
                |--------------------------------------------------------------------------
                | Resolve actual account
                |--------------------------------------------------------------------------
                |
                | If the log contains user_id = 6,
                | find the corresponding account from users table.
                |
                */

                $user = null;

                if (!empty($userId)) {
                    $user = User::find((int) $userId);
                }

                /*
                |--------------------------------------------------------------------------
                | Visitor type
                |--------------------------------------------------------------------------
                */

                $visitorType = !empty($userId)
                    ? 'account'
                    : 'guest';

                /*
                |--------------------------------------------------------------------------
                | Visitor information
                |--------------------------------------------------------------------------
                |
                | Prefer actual database user information.
                |
                */

                $visitorName = $user?->name;

                $visitorEmail = $user?->email;

                /*
                |--------------------------------------------------------------------------
                | Fallback for older logs
                |--------------------------------------------------------------------------
                |
                | If old logs already contain visitor_name/email,
                | use those if the account cannot be found.
                |
                */

                if (!$visitorName) {
                    $visitorName = $this->extractContextValue(
                        $rawMessage,
                        'visitor_name'
                    );
                }

                if (!$visitorEmail) {
                    $visitorEmail = $this->extractContextValue(
                        $rawMessage,
                        'visitor_email'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Visitor ID
                |--------------------------------------------------------------------------
                */

                $visitorId = $this->extractContextValue(
                    $rawMessage,
                    'visitor_id'
                );

                if (!$visitorId) {
                    $visitorId = $userId;
                }

                /*
                |--------------------------------------------------------------------------
                | Visitor guard
                |--------------------------------------------------------------------------
                */

                $visitorGuard = $this->extractContextValue(
                    $rawMessage,
                    'visitor_guard'
                );

                /*
                |--------------------------------------------------------------------------
                | HTTP method
                |--------------------------------------------------------------------------
                */

                $method = $this->extractContextValue(
                    $rawMessage,
                    'method'
                );

                if (!$method) {
                    $method = $this->extractContextValue(
                        $rawMessage,
                        'http_method'
                    );
                }

                if (!$method) {
                    if (preg_match(
                        '/\b(GET|POST|PUT|PATCH|DELETE|OPTIONS|HEAD)\s+(?:https?:\/\/[^\s]+)/i',
                        $rawMessage,
                        $methodMatch
                    )) {
                        $method = strtoupper(
                            $methodMatch[1]
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | IP
                |--------------------------------------------------------------------------
                */

                $ip = $this->extractContextValue(
                    $rawMessage,
                    'ip'
                );

                /*
                |--------------------------------------------------------------------------
                | Exception
                |--------------------------------------------------------------------------
                */

                $exception = '';

                if (preg_match(
                    '/([A-Za-z0-9_\\\\]+(?:Exception|Error|Throwable))/',
                    $rawMessage,
                    $exceptionMatch
                )) {
                    $exception = $exceptionMatch[1];
                }

                /*
                |--------------------------------------------------------------------------
                | File and line
                |--------------------------------------------------------------------------
                */

                $file = '';
                $line = '';

                if (preg_match(
                    '/(?:in|at)\s+([A-Za-z]:[^\s:]+|\/[^\s:]+):(\d+)/',
                    $rawMessage,
                    $locationMatch
                )) {
                    $file = $locationMatch[1];
                    $line = $locationMatch[2];
                }

                /*
                |--------------------------------------------------------------------------
                | URL
                |--------------------------------------------------------------------------
                */

                $url = '';

                if (preg_match(
                    '/\bhttps?:\/\/[^\s"\']+/i',
                    $rawMessage,
                    $urlMatch
                )) {
                    $url = rtrim(
                        $urlMatch[0],
                        '.,;)'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Split lines
                |--------------------------------------------------------------------------
                */

                $lines = preg_split(
                    '/\r\n|\r|\n/',
                    $rawMessage
                );

                /*
                |--------------------------------------------------------------------------
                | Readable message
                |--------------------------------------------------------------------------
                */

                $message = '';

                foreach ($lines as $lineText) {

                    $lineText = trim($lineText);

                    if ($lineText === '') {
                        continue;
                    }

                    /*
                    | Skip stack trace.
                    */

                    if (preg_match(
                        '/^#\d+\s/',
                        $lineText
                    )) {
                        continue;
                    }

                    if (str_starts_with(
                        $lineText,
                        'Stack trace:'
                    )) {
                        continue;
                    }

                    /*
                    | Skip JSON/context metadata.
                    */

                    if (
                        str_contains($lineText, '"user_id"') ||
                        str_contains($lineText, "'user_id'") ||
                        str_contains($lineText, '"visitor_type"') ||
                        str_contains($lineText, '"visitor_name"') ||
                        str_contains($lineText, '"visitor_email"') ||
                        str_contains($lineText, '"visitor_id"') ||
                        str_contains($lineText, '"visitor_guard"')
                    ) {
                        continue;
                    }

                    /*
                    | Skip metadata lines.
                    */

                    if (preg_match(
                        '/^\[?(visitor_|user_|guard|method|url|ip)/i',
                        $lineText
                    )) {
                        continue;
                    }

                    $message = $lineText;

                    break;
                }

                /*
                |--------------------------------------------------------------------------
                | Remove common prefixes
                |--------------------------------------------------------------------------
                */

                $message = preg_replace(
                    '/^(local|production|staging)\.(ERROR|CRITICAL|WARNING|INFO|DEBUG|NOTICE):\s*/i',
                    '',
                    $message
                );

                /*
                |--------------------------------------------------------------------------
                | Remove context JSON
                |--------------------------------------------------------------------------
                */

                $message = preg_replace(
                    '/\{.*?"(?:user_id|visitor_type)".*?\}/s',
                    '',
                    $message
                );

                $message = trim($message);

                if (!$message) {
                    $message = 'An application error occurred.';
                }

                /*
                |--------------------------------------------------------------------------
                | Stack trace
                |--------------------------------------------------------------------------
                */

                $trace = '';

                $traceStart = false;

                foreach ($lines as $lineText) {

                    $lineText = trim($lineText);

                    if (
                        str_starts_with(
                            $lineText,
                            '#0 '
                        ) ||
                        str_contains(
                            $lineText,
                            'Stack trace:'
                        )
                    ) {
                        $traceStart = true;
                    }

                    if ($traceStart) {
                        $trace .= $lineText . "\n";
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Display file
                |--------------------------------------------------------------------------
                */

                $displayFile = $file;

                if ($file) {

                    $normalizedFile = str_replace(
                        '\\',
                        '/',
                        $file
                    );

                    $appPosition = strpos(
                        $normalizedFile,
                        '/app/'
                    );

                    if ($appPosition !== false) {
                        $displayFile = substr(
                            $normalizedFile,
                            $appPosition + 1
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Return entry
                |--------------------------------------------------------------------------
                */

                return [
                    'timestamp' => $timestamp,

                    'date' => substr(
                        $timestamp,
                        0,
                        10
                    ),

                    'time' => substr(
                        $timestamp,
                        11
                    ),

                    'level' => $level,

                    'message' => $message,

                    'exception' => $exception,

                    'file' => $displayFile,

                    'line' => $line,

                    'url' => $url,

                    'method' => $method,

                    'ip' => $ip,

                    'trace' => trim($trace),

                    /*
                    |--------------------------------------------------------------------------
                    | User/account information
                    |--------------------------------------------------------------------------
                    */

                    'user_id' => $userId,

                    'visitor_type' => $visitorType,

                    'visitor_name' => $visitorName,

                    'visitor_email' => $visitorEmail,

                    'visitor_id' => $visitorId,

                    'visitor_guard' => $visitorGuard,

                    'raw' => $rawMessage,
                ];
            })
            ->filter(function ($entry) {
                return $entry['message'] !== '';
            })
            ->values();
    }

    /**
     * Extract a value from Laravel log context.
     *
     * Supports:
     *
     * "user_id":6
     * "user_id":null
     * "visitor_type":"account"
     * 'visitor_type' => 'account'
     */
    private function extractContextValue(
        string $content,
        string $key
    ): ?string {

        /*
        |--------------------------------------------------------------------------
        | Null value
        |--------------------------------------------------------------------------
        */

        $nullPattern =
            '/["\']'
            . preg_quote($key, '/')
            . '["\']\s*[:=]\s*null/i';

        if (preg_match(
            $nullPattern,
            $content
        )) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | String value
        |--------------------------------------------------------------------------
        */

        $pattern =
            '/["\']'
            . preg_quote($key, '/')
            . '["\']\s*[:=]\s*["\']([^"\']*)["\']/i';

        if (preg_match(
            $pattern,
            $content,
            $match
        )) {
            return trim(
                $match[1]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Numeric value
        |--------------------------------------------------------------------------
        */

        $numericPattern =
            '/["\']'
            . preg_quote($key, '/')
            . '["\']\s*[:=]\s*([0-9]+)/i';

        if (preg_match(
            $numericPattern,
            $content,
            $match
        )) {
            return trim(
                $match[1]
            );
        }

        return null;
    }
}