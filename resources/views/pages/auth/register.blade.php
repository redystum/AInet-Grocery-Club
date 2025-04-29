@extends('layout')

@section('content')

    <div class="flex min-h-full">
        <!-- Left Column - Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8">
            <div class="max-w-md mx-auto w-full">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-neutral-100 mb-2">Join Our Club</h1>
                    <p class="text-gray-600 dark:text-neutral-400">Create your account in just a few steps</p>
                </div>

                @if (session('success'))
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
                                    <p>{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else

                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data"
                          class="space-y-6">
                        @csrf
                        <!-- Profile Photo with Preview -->
                        <div class="flex flex-col items-center mb-6">
                            <div class="relative mb-4">
                                <div
                                    class="group w-32 h-32 rounded-full bg-gray-200 dark:bg-neutral-700 overflow-hidden border-4 border-white dark:border-neutral-800 shadow-lg relative">
                                    <img id="profilePreview" src="{{ asset('storage/users/anonymous.png') }}"
                                         alt="Profile Preview" class="w-full h-full object-cover">
                                    <div id="removeImage"
                                         class="absolute inset-0 flex items-center justify-center bg-black/60 opacity-0 group-hover:opacity-100 rounded-full transition-opacity duration-200 cursor-pointer">
                                        <i class="fas fa-times text-white"></i>
                                    </div>
                                </div>
                                <label for="photo"
                                       class="absolute bottom-0 right-0 bg-blue-500 text-white p-1.5 rounded-full cursor-pointer hover:bg-blue-600 transition">
                                    <i class="fas fa-camera p-1"></i>
                                    <input type="file" name="photo" id="photo" class="hidden" accept="image/*">
                                </label>
                            </div>
                            <span class="text-sm text-gray-500 dark:text-neutral-400">Upload a profile photo
                                (optional)</span>
                            @error('photo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Personal Info Section -->
                        <div class="space-y-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200 border-b pb-2">Personal
                                Information</h2>

                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                <div class="md:col-span-3">
                                    <label for="name"
                                           class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Full
                                        Name*</label>
                                    <input type="text" name="name" id="name" required
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300
                                       dark:border-neutral-700 focus:ring-2 focus:ring-blue-500
                                       focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100
                                       placeholder-gray-400 dark:placeholder-neutral-500
                                       @error('name') border-red-500 dark:border-red-500 @enderror"
                                           placeholder="John Doe" value="{{ old('name') }}">
                                    @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="gender"
                                           class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Gender*</label>
                                    <select name="gender" id="gender" required
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300
                                        dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                        dark:bg-neutral-800 dark:text-neutral-100
                                        @error('gender') border-red-500 dark:border-red-500 @enderror">
                                        <option value="" disabled {{ old('gender') == '' ? 'selected' : '' }}>Select...
                                        </option>
                                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Male</option>
                                        <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="email"
                                       class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Email
                                    Address*</label>
                                <input type="email" name="email" id="email" required
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800
                                   dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500
                                   @error('email') border-red-500 dark:border-red-500 @enderror"
                                       placeholder="john.doe@email.com" value="{{ old('email') }}">
                                @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Account Security Section -->
                        <div class="space-y-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200 border-b pb-2">Account
                                Security</h2>

                            <div>
                                <label for="password"
                                       class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Password*</label>
                                <input type="password" name="password" id="password" required minlength="8"
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800
                                   dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500
                                   @error('password') border-red-500 dark:border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">Minimum 8 characters</p>
                                @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation"
                                       class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Confirm
                                    Password*</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       minlength="8"
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800
                                   dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500
                                   @error('password_confirmation') border-red-500 dark:border-red-500 @enderror">
                                @error('password_confirmation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Optional Info Section (Collapsible) -->
                        <div class="space-y-4">
                            <details class="group"
                                     @if(old('default_delivery_address') || old('nif') || old('default_payment_type')) open @endif>
                                <summary
                                    class="flex items-center justify-between cursor-pointer text-gray-700 dark:text-neutral-300">
                                    <h2 class="text-lg font-semibold">Additional Information (Optional)</h2>
                                    <i class="fas fa-chevron-down text-gray-500 group-open:rotate-180 transition-transform"></i>
                                </summary>

                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="default_delivery_address"
                                               class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Default
                                            Delivery Address</label>
                                        <input type="text" name="default_delivery_address" id="default_delivery_address"
                                               maxlength="255"
                                               class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700
                                           focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800
                                           dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500
                                           @error('default_delivery_address') border-red-500 dark:border-red-500 @enderror"
                                               placeholder="123 Main St, City, Country"
                                               value="{{ old('default_delivery_address') }}">
                                        @error('default_delivery_address')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="nif"
                                                   class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">NIF
                                                Number</label>
                                            <input type="text" name="nif" id="nif" maxlength="9"
                                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700
                                               focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800
                                               dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500
                                               @error('nif') border-red-500 dark:border-red-500 @enderror"
                                                   placeholder="123456789" value="{{ old('nif') }}">
                                            @error('nif')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="default_payment_type"
                                                   class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Default
                                                Payment Method</label>
                                            <select name="default_payment_type" id="default_payment_type"
                                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700
                                                focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800
                                                dark:text-neutral-100
                                                @error('default_payment_type') border-red-500 dark:border-red-500 @enderror">
                                                <option value=""
                                                        disabled {{ old('default_payment_type') == '' ? 'selected' : '' }}>
                                                    Select...
                                                </option>
                                                <option
                                                    value="Visa" {{ old('default_payment_type') == 'Visa' ? 'selected' : '' }}>
                                                    Visa
                                                </option>
                                                <option
                                                    value="PayPal" {{ old('default_payment_type') == 'PayPal' ? 'selected' : '' }}>
                                                    PayPal
                                                </option>
                                                <option
                                                    value="MB WAY" {{ old('default_payment_type') == 'MB WAY' ? 'selected' : '' }}>
                                                    MB Way
                                                </option>
                                            </select>
                                            @error('default_payment_type')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </details>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="terms" id="terms" required
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-neutral-700 rounded dark:bg-neutral-800"
                                    @checked(old('terms'))>
                            </div>
                            <div class="ml-3">
                                <label for="terms" class="block text-sm text-gray-700 dark:text-neutral-300">
                                    I agree to the <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank"
                                                      class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">Terms
                                        and Conditions</a>*
                                </label>
                                @error('terms')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                            Create Account
                        </button>

                        <p class="text-center text-sm text-gray-600 dark:text-neutral-400">
                            Already have an account?
                            <a href="{{ route('login') }}"
                               class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                Sign in
                            </a>
                        </p>
                    </form>
                @endif
            </div>
        </div>

        <img src="{{ asset('assets/loginImage.png') }}" alt="Login Image"
             class="h-screen fixed right-0 -z-10 opacity-30 lg:opacity-50 xl:opacity-100 transition-opacity min-w-fit dark:opacity-20 dark:lg:opacity-30 dark:xl:opacity-40"/>

    </div>

    <script>
        // Profile photo preview
        document.getElementById('photo').addEventListener('change', function (e) {
            const [file] = e.target.files;
            if (file) {
                const preview = document.getElementById('profilePreview');
                preview.src = URL.createObjectURL(file);
            }
        });

        // Remove image
        document.getElementById('removeImage').addEventListener('click', function () {
            const preview = document.getElementById('profilePreview');
            preview.src = "{{ asset('storage/users/anonymous.png') }}";
            document.getElementById('photo').value = '';
        });
    </script>
@endsection
