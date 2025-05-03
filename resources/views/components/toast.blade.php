@if(session('toast'))
    <div id="toast-notification" class="fixed top-10 right-4 z-50">
        <div class="toast toast-{{ session('toast')['type'] }} transform transition-all duration-300 ease-in-out translate-x-0 opacity-100">
            <div class="flex items-start">
                <div class="toast-icon">
                    @if(session('toast')['type'] === 'success')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @elseif(session('toast')['type'] === 'error')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @elseif(session('toast')['type'] === 'warning')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    @endif
                </div>
                <div class="ml-3">
                    <h3 class="toast-title">{{ session('toast')['title'] }}</h3>
                    <p class="toast-message">{{ session('toast')['message'] }}</p>
                </div>
                <button onclick="hideToast()" class="ml-auto toast-close">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide after 3 seconds
        setTimeout(() => {
            hideToast();
        }, {{ session('toast')['time'] ?? 5000 }});

        function hideToast() {
            const toast = document.querySelector('#toast-notification .toast');
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-full', 'opacity-0');

            // Remove from DOM after animation
            setTimeout(() => {
                document.getElementById('toast-notification').remove();
            }, 1000);
        }
    </script>
@endif