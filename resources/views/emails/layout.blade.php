<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-neutral-100 dark:bg-neutral-900 font-sans">
<div class="max-w-2xl mx-auto my-8 bg-white dark:bg-neutral-800 rounded-xl shadow-sm overflow-hidden">

    @yield('content')

    <footer class="text-center text-neutral-500 dark:text-neutral-400 text-sm mb-6">
        <p class="mb-2">This is an automated message. Please do not reply.</p>
        <p>© {{ now()->year }} {{ $appName }}. All rights reserved.</p>
        <div class="mt-4">
            <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-4">
                Help Center
            </a>
            <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-4">
                Privacy Policy
            </a>
        </div>
    </footer>
</div>

</body>
</html>
