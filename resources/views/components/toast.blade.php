@if(session('toast'))
    <div id="toast-notification" class="fixed top-10 right-4 z-50">
        <div class="toast toast-{{ session('toast')['type'] }} transform transition-all duration-300 ease-in-out translate-x-0 opacity-100">
            <div class="flex items-start">
                <div class="toast-icon">
                    @if(session('toast')['type'] === 'success')
                        <div class="icon-circle bg-green-500 text-white">
                            <i class="fas fa-check"></i>
                        </div>
                    @elseif(session('toast')['type'] === 'error')
                        <div class="icon-circle bg-red-500 text-white">
                            <i class="fas fa-times"></i>
                        </div>
                    @elseif(session('toast')['type'] === 'warning')
                        <div class="icon-circle bg-yellow-500 text-white">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    @elseif(session('toast')['type'] === 'info')
                        <div class="icon-circle bg-blue-500 text-white">
                            <i class="fas fa-info-circle"></i>
                        </div>
                    @endif
                </div>
                <div class="ml-3">
                    <h3 class="toast-title">{{ session('toast')['title'] }}</h3>
                    <p class="toast-message">{{ session('toast')['message'] }}</p>
                </div>
                <button onclick="hideToast()" class="ml-auto toast-close">
                    <i class="fas fa-times text-gray-500 hover:text-gray-600"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => hideToast(), {{ session('toast')['time'] ?? 5000 }});
        });
        
        function hideToast() {
            const toast = document.querySelector('#toast-notification .toast');
            if (toast) {
                toast.classList.remove('translate-x-0', 'opacity-100');
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    const notification = document.getElementById('toast-notification');
                    if (notification) notification.remove();
                }, 1000);
            }
        }
    </script>
@endif
