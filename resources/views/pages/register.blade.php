@extends('layout')

@section('content')
    <div class="flex min-h-screen">
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8">
            <div class="max-w-md mx-auto w-full">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-neutral-100 mb-2">Join Our Community</h1>
                    <p class="text-gray-600 dark:text-neutral-400">Create your account in just a few steps</p>
                </div>

                <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Profile Photo with Preview -->
                    <div class="flex flex-col items-center mb-6">
                        <div class="relative mb-4">
                            <div
                                class="w-32 h-32 rounded-full bg-gray-200 dark:bg-neutral-700 overflow-hidden border-4 border-white dark:border-neutral-800 shadow-lg">
                                <img id="profile-preview" src="{{ asset('storage/users/anonymous.png') }}"
                                     alt="Profile Preview" class="w-full h-full object-cover">
                            </div>
                            <label for="profile_photo"
                                   class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full cursor-pointer hover:bg-blue-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <input type="file" name="profile_photo" id="profile_photo" class="hidden"
                                       accept="image/*">
                            </label>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-neutral-400">Upload a profile photo
                            (optional)</span>
                    </div>

                    <!-- Personal Info Section -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200 border-b pb-2">Personal
                            Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name"
                                       class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Full
                                    Name*</label>
                                <input type="text" name="name" id="name" required
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500">
                            </div>

                            <div>
                                <label for="gender"
                                       class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Gender*</label>
                                <select name="gender" id="gender" required
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100">
                                    <option value="">Select...</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="not">Prefer not to say</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="email"
                                   class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Email
                                Address*</label>
                            <input type="email" name="email" id="email" required
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500">
                        </div>
                    </div>

                    <!-- Account Security Section -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200 border-b pb-2">Account
                            Security</h2>

                        <div>
                            <label for="password"
                                   class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Password*</label>
                            <input type="password" name="password" id="password" required
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500">
                            <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">Minimum 8 characters</p>
                        </div>

                        <div>
                            <label for="password_confirmation"
                                   class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Confirm
                                Password*</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500">
                        </div>
                    </div>

                    <!-- Optional Info Section (Collapsible) -->
                    <div class="space-y-4">
                        <details class="group">
                            <summary
                                class="flex items-center justify-between cursor-pointer text-gray-700 dark:text-neutral-300">
                                <h2 class="text-lg font-semibold">Additional Information (Optional)</h2>
                                <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </summary>

                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="delivery_address"
                                           class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Delivery
                                        Address</label>
                                    <input type="text" name="delivery_address" id="delivery_address"
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="nif"
                                               class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">NIF
                                            Number</label>
                                        <input type="text" name="nif" id="nif"
                                               class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500">
                                    </div>

                                    <div>
                                        <label for="payment_details"
                                               class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Payment
                                            Details</label>
                                        <input type="text" name="payment_details" id="payment_details"
                                               class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-neutral-800 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500">
                                    </div>
                                </div>
                            </div>
                        </details>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                        Create Account
                    </button>

                    <p class="text-center text-sm text-gray-600 dark:text-neutral-400">
                        Already have an account? <a href="{{ route('login') }}"
                                                    class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">Sign
                            in</a>
                    </p>
                </form>
            </div>
        </div>

        <img src="{{ asset('assets/loginImage.png') }}" alt="Login Image"
             class="h-screen fixed right-0 -z-10 opacity-30 lg:opacity-50 xl:opacity-100 transition-opacity min-w-fit dark:opacity-20 dark:lg:opacity-30 dark:xl:opacity-40"/>

    </div>

    <script>
        // Profile photo preview
        document.getElementById('profile_photo').addEventListener('change', function (e) {
            const [file] = e.target.files;
            if (file) {
                const preview = document.getElementById('profile-preview');
                preview.src = URL.createObjectURL(file);
            }
        });
    </script>
@endsection
