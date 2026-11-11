<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Credential Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-6 text-center border border-gray-200">
        @if(isset($status) && $status === 'not_found')
            <!-- Not Found State -->
            <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Verification Failed</h2>
            <p class="text-gray-600">{{ $message }}</p>

        @elseif($isValid)
            <!-- Active / Verified State -->
            <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full uppercase tracking-wider mb-2">Verified Credential</span>
            
            @if($alumni->image)
                <img src="{{ asset('storage/' . $alumni->image) }}" alt="{{ $alumni->name }}" class="w-24 h-24 rounded-full mx-auto my-4 object-cover border-2 border-green-500 shadow-sm">
            @endif

            <h2 class="text-xl font-bold text-gray-800">{{ $alumni->name }}</h2>
            <p class="text-sm text-gray-500 mb-4">{{ $alumni->email }}</p>

            <div class="border-t border-gray-100 pt-4 text-left space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Registration No:</span>
                    <span class="font-mono font-semibold text-gray-800">{{ $alumni->register_no ?? 'MEA-' . sprintf('%04d', $alumni->id) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Course:</span>
                    <span class="font-medium text-gray-800">{{ $alumni->course->title ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status:</span>
                    <span class="text-green-600 font-semibold capitalize">{{ $status }}</span>
                </div>
            </div>

        @else
            <!-- Suspended / Inactive State -->
            <div class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Credential Inactive</h2>
            <p class="text-sm text-gray-600">The record for <strong>{{ $alumni->name }}</strong> is currently marked as <span class="font-semibold text-yellow-600">{{ $status }}</span>.</p>
        @endif
    </div>

</body>
</html>