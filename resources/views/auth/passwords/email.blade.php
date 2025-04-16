@extends('layout')

@section('content')
    <div class="flex min-h-full">
        <!-- Left Column - Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8">
            <div class="max-w-md mx-auto w-full">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-neutral-100 mb-2">Forgot Password?</h1>
                    <p class="text-gray-600 dark:text-neutral-400">Enter your email address to receive a password reset link</p>
                </div>

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                        Send Reset Link
                    </button>

                    <!-- Back to Login Link -->
                    <p class="text-center text-sm text-gray-600 dark:text-neutral-400">
                        Remembered your password? <a href="{{ route('login') }}"
                                                     class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">Sign
                            in</a>
                    </p>
                </form>
            </div>
        </div>

        <img src="{{ asset('assets/forgotPasswordImage.png') }}" alt="Forgot Password Image"
             class="h-screen fixed right-0 -z-10 opacity-30 lg:opacity-50 xl:opacity-100 transition-opacity min-w-fit dark:opacity-20 dark:lg:opacity-30 dark:xl:opacity-40"/>
    </div>
@endsection