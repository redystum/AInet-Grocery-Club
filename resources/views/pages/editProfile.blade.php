@extends('layout')

@section('title', ' - Edit Profile')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-neutral-800 dark:text-neutral-100">Edit Your Profile</h1>
            <a href="{{ route('profile') }}"
               class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to Profile
            </a>
        </div>

        <!-- Profile Edit Form -->
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Photo Section -->
            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Profile Photo</h2>

                <div class="flex flex-col md:flex-row items-center gap-6">
                    <!-- Current Photo -->
                    <div class="relative">
                        <div class="w-32 h-32 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 overflow-hidden shadow-md">
                            <img id="profilePreview"
                                 src="{{ $user->photo ? asset('storage/users/' . $user->photo) : asset('storage/users/anonymous.png') }}"
                                 alt="Current Profile Photo"
                                 class="w-full h-full object-cover">
                        </div>
                        <button type="button" id="removePhoto"
                                class="absolute -top-2 -right-2 bg-white hover:bg-neutral-100 dark:bg-neutral-700 dark:hover:bg-neutral-800 text-red-500 py-1 px-3 rounded-full shadow-md transition-colors">
                            <i class="fas fa-x text-xs"></i>
                        </button>
                    </div>

                    <!-- Upload Controls -->
                    <div class="flex-1">
                        <div class="space-y-4">
                            <div>
                                <label for="photo" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    Upload new photo
                                </label>
                                <input type="file" name="photo" id="photo"
                                       class="block w-full text-sm text-neutral-500 dark:text-neutral-400
                                       file:mr-4 file:py-2 file:px-4
                                       file:rounded-lg file:border-0
                                       file:text-sm file:font-semibold
                                       file:bg-blue-50 dark:file:bg-blue-900/20 file:text-blue-700 dark:file:text-blue-400
                                       hover:file:bg-blue-100 dark:hover:file:bg-blue-900/30
                                       cursor-pointer">
                                @error('photo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                Recommended size: 500×500 pixels. Max file size: 2MB.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information Section -->
            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Personal Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Full Name *
                        </label>
                        <input type="text" name="name" id="name" required
                               class="w-full px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-700/50
                               dark:text-neutral-100 placeholder-neutral-400 dark:placeholder-neutral-500
                               @error('name') border-red-500 dark:border-red-500 @enderror"
                               value="{{ old('name', $user->name) }}">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Email Address *
                        </label>
                        <input type="email" name="email" id="email" required
                               class="w-full px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-700/50
                               dark:text-neutral-100 placeholder-neutral-400 dark:placeholder-neutral-500
                               @error('email') border-red-500 dark:border-red-500 @enderror"
                               value="{{ old('email', $user->email) }}">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Gender *
                        </label>
                        <select name="gender" id="gender" required
                                class="w-full px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                                focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-700/50
                                dark:text-neutral-100 @error('gender') border-red-500 dark:border-red-500 @enderror">
                            <option value="M" {{ old('gender', $user->gender) == 'M' ? 'selected' : '' }}>Male</option>
                            <option value="F" {{ old('gender', $user->gender) == 'F' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIF -->
                    <div>
                        <label for="nif" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            NIF Number
                        </label>
                        <input type="text" name="nif" id="nif" maxlength="9"
                               class="w-full px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-700/50
                               dark:text-neutral-100 placeholder-neutral-400 dark:placeholder-neutral-500
                               @error('nif') border-red-500 dark:border-red-500 @enderror"
                               value="{{ old('nif', $user->nif) }}">
                        @error('nif')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Delivery & Payment Section -->
            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Delivery & Payment</h2>

                <div class="space-y-6">
                    <!-- Delivery Address -->
                    <div>
                        <label for="default_delivery_address" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Default Delivery Address
                        </label>
                        <textarea name="default_delivery_address" id="default_delivery_address" rows="3"
                                  class="w-full px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                                  focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-700/50
                                  dark:text-neutral-100 placeholder-neutral-400 dark:placeholder-neutral-500
                                  @error('default_delivery_address') border-red-500 dark:border-red-500 @enderror">{{ old('default_delivery_address', $user->default_delivery_address) }}</textarea>
                        @error('default_delivery_address')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Payment Method -->
                        <div>
                            <label for="default_payment_type" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Default Payment Method
                            </label>
                            <select name="default_payment_type" id="default_payment_type"
                                    class="w-full px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                                    focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-700/50
                                    dark:text-neutral-100 @error('default_payment_type') border-red-500 dark:border-red-500 @enderror">
                                <option value="" {{ old('default_payment_type', $user->default_payment_type) == '' ? 'selected' : '' }}>None selected</option>
                                <option value="Visa" {{ old('default_payment_type', $user->default_payment_type) == 'Visa' ? 'selected' : '' }}>Visa</option>
                                <option value="PayPal" {{ old('default_payment_type', $user->default_payment_type) == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                                <option value="MB WAY" {{ old('default_payment_type', $user->default_payment_type) == 'MB WAY' ? 'selected' : '' }}>MB WAY</option>
                            </select>
                            @error('default_payment_type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Reference -->
                        <div>
                            <label for="default_payment_reference" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Payment Reference
                            </label>
                            <input type="text" name="default_payment_reference" id="default_payment_reference"
                                   class="w-full px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-700/50
                                   dark:text-neutral-100 placeholder-neutral-400 dark:placeholder-neutral-500
                                   @error('default_payment_reference') border-red-500 dark:border-red-500 @enderror"
                                   value="{{ old('default_payment_reference', $user->default_payment_reference) }}">
                            @error('default_payment_reference')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Change Section (Optional) -->
            <div class="bg-neutral-50 dark:bg-neutral-800 rounded-xl shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-neutral-800 dark:text-neutral-100 mb-4">Change Password</h2>
                <p class="text-neutral-600 dark:text-neutral-400 mb-4">Leave blank to keep current password</p>

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Current Password
                        </label>
                        <input type="password" name="current_password" id="current_password"
                               class="w-full px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-700/50
                               dark:text-neutral-100 placeholder-neutral-400 dark:placeholder-neutral-500
                               @error('current_password') border-red-500 dark:border-red-500 @enderror">
                        @error('current_password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            New Password
                        </label>
                        <input type="password" name="password" id="password" minlength="8"
                               class="w-full px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-700/50
                               dark:text-neutral-100 placeholder-neutral-400 dark:placeholder-neutral-500
                               @error('password') border-red-500 dark:border-red-500 @enderror">
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Minimum 8 characters</p>
                        @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Confirm New Password
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" minlength="8"
                               class="w-full px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-600
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-700/50
                               dark:text-neutral-100 placeholder-neutral-400 dark:placeholder-neutral-500">
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end gap-4">
                <button type="button" onclick="window.location.href='{{ route('profile') }}'"
                        class="px-6 py-3 cursor-pointer border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-200 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-3 cursor-pointer bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-md transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
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

        // Remove photo
        document.getElementById('removePhoto').addEventListener('click', function () {
            const preview = document.getElementById('profilePreview');
            preview.src = "{{ asset('storage/users/anonymous.png') }}";
            document.getElementById('photo').value = '';

            // Add hidden field to indicate photo removal
            if (!document.getElementById('remove_photo_flag')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remove_photo';
                input.id = 'remove_photo_flag';
                input.value = '1';
                document.querySelector('form').appendChild(input);
            }
        });
    </script>
@endsection
