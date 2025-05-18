@extends('layout')

@section('title', ' - Too Many Requests')

@section('content')

    <div class="h-full relative overflow-hidden flex flex-col items-center justify-center bg-neutral-50 dark:bg-neutral-900 p-4"
         id="cookieContainer">

        <div class="mt-8 text-center text-neutral-600 dark:text-neutral-400 text-sm hidden">
            <h1 class="text-4xl font-bold text-neutral-800 dark:text-neutral-100 mb-2">
                Score: <span id="score">0</span>
            </h1>
        </div>

        <div class="max-w-md w-full bg-white dark:bg-neutral-800 rounded-xl shadow-sm p-8 text-center">

            <div class="absolute top-1/8 left-0 w-full flex justify-center hidden">
            </div>

            <div class="mx-auto w-24 h-24 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-6 relative cursor-pointer"
                 id="cookie">
                <i class="fas fa-cookie text-red-600 dark:text-red-400 text-5xl" id="cookie-icon"></i>
            </div>

            <h1 class="text-4xl font-bold text-neutral-800 dark:text-neutral-100 mb-4">429</h1>
            <h2 class="text-2xl font-semibold text-neutral-700 dark:text-neutral-300 mb-4">Too Many Requests</h2>
            <p class="text-neutral-600 dark:text-neutral-400 mb-6">
                Oops! It seems you've made too many requests in a short period of time. Are you playing cookie clicker?
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}"
                   class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-home mr-2"></i> Go to Homepage
                </a>
                <button onclick="window.history.back()"
                        class="px-6 py-3 border border-neutral-300 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-300 font-medium rounded-lg transition-colors cursor-pointer">
                    <i class="fas fa-arrow-left mr-2"></i> Go Back
                </button>
            </div>

            <div class="mt-8">
                <p class="text-neutral-600 dark:text-neutral-400 mb-3">Or try searching:</p>
                <div class="relative">
                    <input type="text"
                           class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm bg-white dark:bg-neutral-800 text-gray-900 dark:text-neutral-100 placeholder-gray-500 dark:placeholder-neutral-400"
                           placeholder="Search...">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 dark:text-neutral-500 text-sm"></i>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center text-neutral-600 dark:text-neutral-400 text-sm">
            <p>Need help? <a href="" class="text-blue-600 dark:text-blue-400 hover:underline">Contact our support
                    team</a></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cookie = document.getElementById('cookie');
            const scoreElement = document.getElementById('score');
            const scoreDiv = scoreElement.parentElement.parentElement;
            const cookieContainer = document.getElementById('cookieContainer');
            let score = 0;
            let cookiesFalling = false;

            cookie.addEventListener('click', function (e) {
                // Increase score
                score++;
                scoreElement.textContent = score;
                scoreDiv.classList.remove('hidden');

                // Create +1 element
                const plusOne = document.createElement('div');
                plusOne.textContent = '+1';
                plusOne.className = 'absolute text-white font-bold text-md select-none animate-float opacity-0';

                // Position it near the click
                const rect = cookie.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                plusOne.style.left = `${x}px`;
                plusOne.style.top = `${y}px`;

                cookie.appendChild(plusOne);

                // Trigger animation
                setTimeout(() => {
                    plusOne.classList.remove('opacity-0');
                    plusOne.classList.add('opacity-100');
                }, 10);

                // Remove after animation
                setTimeout(() => {
                    plusOne.remove();
                }, 1000);

                // Add click effect to cookie
                const icon = document.getElementById('cookie-icon');
                icon.classList.add('scale-90');
                setTimeout(() => {
                    icon.classList.remove('scale-90');
                }, 100);

                // Start falling cookies when score reaches 100
                if (score >= 100 && !cookiesFalling) {
                    startFallingCookies();
                }
            });

            function startFallingCookies() {
                cookiesFalling = true;

                function createFallingCookie() {
                    const fallingCookie = document.createElement('div');
                    fallingCookie.className = 'falling-cookie';
                    fallingCookie.style.position = 'absolute';

                    // Ensure cookies are created within the visible screen width
                    const cookieWidth = 50; // Width of the cookie
                    const maxLeft = window.innerWidth - cookieWidth;
                    fallingCookie.style.left = Math.random() * maxLeft + 'px';

                    fallingCookie.style.top = '-50px';
                    fallingCookie.style.width = `${cookieWidth}px`;
                    fallingCookie.style.height = `${cookieWidth}px`;
                    fallingCookie.style.backgroundImage = `url('{{ asset('assets/cookie.png') }}')`;
                    fallingCookie.style.backgroundSize = 'cover';
                    fallingCookie.style.zIndex = '1000';
                    fallingCookie.style.pointerEvents = 'none';
                    cookieContainer.appendChild(fallingCookie);

                    let fallInterval = setInterval(() => {
                        const currentTop = parseInt(fallingCookie.style.top.replace('px', ''));
                        if (currentTop > window.innerHeight) {
                            clearInterval(fallInterval);
                            fallingCookie.remove();
                        } else {
                            fallingCookie.style.top = currentTop + 5 + 'px';
                        }
                    }, 30);
                }

                // Create cookies at intervals
                setInterval(() => {
                    createFallingCookie();
                }, 300);
            }
        });
    </script>

    <style>
        @keyframes float {
            0% {
                transform: translateY(0);
                opacity: 1;
            }
            100% {
                transform: translateY(-50px);
                opacity: 0;
            }
        }

        .animate-float {
            animation: float 1s ease-out forwards;
        }

        @keyframes fall_ {
            0% {
                top: -50px;
                rotate: 0;
            }
            100% {
                top: 100vh;
                rotate: 360deg;
            }
        }

        .falling-cookie {
            animation: fall_ 5s linear forwards;
        }

    </style>

@endsection
