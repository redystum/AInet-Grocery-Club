@extends('layout')

@section('content')
    <div class="flex flex-1/2 h-full">
        <div class="flex flex-col justify-center items-center w-full h-full">
            <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-neutral-100">Login</h1>
            <form action="{{ route('login') }}" method="POST" class="md:w-96 w-3/4 min-w-0">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Email</label>
                    <input type="email" name="email" id="email" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-neutral-700 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-500 dark:focus:ring-blue-600 bg-white dark:bg-neutral-800 text-gray-900 dark:text-neutral-100 placeholder-gray-500 dark:placeholder-neutral-400">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Password</label>
                    <input type="password" name="password" id="password" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-neutral-700 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-500 dark:focus:ring-blue-600 bg-white dark:bg-neutral-800 text-gray-900 dark:text-neutral-100 placeholder-gray-500 dark:placeholder-neutral-400">
                </div>
                <button type="submit"
                        class="w-full bg-blue-500 dark:bg-blue-600 text-white py-2 rounded-md hover:bg-blue-600 dark:hover:bg-blue-700 transition duration-200">
                    Login
                </button>
            </form>
        </div>

        <div class="flex-col justify-center items-center w-full hidden lg:flex"></div>

        <img src="{{ asset('assets/loginImage.png') }}" alt="Login Image"
             class="h-screen fixed right-0 -z-10 opacity-30 lg:opacity-50 xl:opacity-100 transition-opacity min-w-fit dark:opacity-20 dark:lg:opacity-30 dark:xl:opacity-40"/>
    </div>
@endsection
