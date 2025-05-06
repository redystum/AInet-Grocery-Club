@extends('layout')

@section('content')
    <div class="flex min-h-full">
        <!-- Left Column - Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8">
            <div class="max-w-md mx-auto w-full">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-neutral-100 mb-2">Welcome Back</h1>
                    <p class="text-gray-600 dark:text-neutral-400">Sign in to access your account</p>
                </div>

                @if (session('status'))
                    <div
                        class="mb-6 p-4 rounded-xl border border-green-200 dark:border-green-800/50 bg-gradient-to-br from-green-50/70 to-green-100/30 dark:from-green-900/20 dark:to-green-900/10 shadow-sm">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-0.5">
                                <i class="fas fa-check-circle text-green-500 dark:text-green-400 fa-lg"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-semibold text-green-800 dark:text-green-200">
                                    Success!
                                </h3>
                                <div class="mt-1 text-green-700 dark:text-green-300">
                                    <p>{{ session('status') }}</p>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Email
                            Address*</label>
                        <input type="email" name="email" id="email" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-neutral-700
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800
                               dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500
                               @error('email') border-red-500 dark:border-red-500 @enderror"
                               placeholder="your@email.com" value="{{ old('email') }}">
                        @error('email')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password"
                               class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Password*</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-neutral-700
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800
                               dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500
                                 @error('email') border-red-500 dark:border-red-500 @enderror"
                               placeholder="••••••••">
                        @error('password')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                        @error('email')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-end mt-1">
                            <a href="{{ route('password.request') }}"
                               class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Forgot
                                password?</a>
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" @checked(old('remember'))
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-neutral-700 rounded dark:bg-neutral-800 cursor-pointer">
                        <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-neutral-300">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                        Sign In
                    </button>

                    <!-- Registration Link -->
                    <p class="text-center text-sm text-gray-600 dark:text-neutral-400">
                        Don't have an account? <a href="{{ route('register') }}"
                                                  class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            Sign up</a>
                    </p>
                </form>

            </div>
        </div>

        <img src="{{ asset('assets/loginImage.png') }}" alt="Login Image"
             class="h-screen fixed right-0 -z-10 opacity-30 lg:opacity-50 xl:opacity-100 transition-opacity min-w-fit dark:opacity-20 dark:lg:opacity-30 dark:xl:opacity-40"/>

    </div>
@endsection
