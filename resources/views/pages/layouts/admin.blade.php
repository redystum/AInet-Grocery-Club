<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ config('app.name') }}@yield('title')</title>

    <link rel="shortcut icon" href="" type="image/x-icon">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-white dark:bg-neutral-900 text-gray-900 dark:text-neutral-100">

<x-toast />

<x-admin.navbar />

<main class="lg:ml-64 pt-16 lg:pt-0 transition-all duration-300" id="mainContent">
    @yield('content')
</main>

{{--    <x-footer/>--}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const elementsToUnhide = document.getElementsByClassName('unhideOnLoad');
        while (elementsToUnhide.length > 0) {
            elementsToUnhide[0].classList.remove('unhideOnLoad');
        }
    });
</script>

@vite(['resources/js/adminNavBar.js'])
</body>

</html>