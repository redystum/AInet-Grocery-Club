<div id="toast-notification-container" class="fixed top-10 right-4 z-50"></div>

<script>
    function showToast(type = 'info', title = null, message = '', time = 5000) {
        const container = document.getElementById('toast-notification-container');

        if (title === null) {
            title = type.charAt(0).toUpperCase() + type.slice(1);
        }

        const icons = {
            success: '<div class="icon-circle bg-green-500 text-white"><i class="fas fa-check"></i></div>',
            error: '<div class="icon-circle bg-red-500 text-white"><i class="fas fa-times"></i></div>',
            warning: '<div class="icon-circle bg-yellow-500 text-white"><i class="fas fa-exclamation-triangle"></i></div>',
            info: '<div class="icon-circle bg-blue-500 text-white"><i class="fas fa-info-circle"></i></div>',
        };

        const toast = document.createElement('div');
        toast.className = `toast toast-${type} transform transition-all duration-300 ease-in-out translate-x-0 opacity-100 mb-4`;
        toast.innerHTML = `
            <div class="flex items-start">
                <div class="toast-icon">
                    ${icons[type] || icons.info}
                </div>
                <div class="ml-3">
                    <h3 class="toast-title">${title}</h3>
                    <p class="toast-message">${message}</p>
                </div>
                <button class="ml-auto toast-close" onclick="hideToast(this)">
                    <i class="fas fa-times text-gray-500 hover:text-gray-600"></i>
                </button>
            </div>
        `;

        container.appendChild(toast);

        setTimeout(() => hideToast(toast), time);
    }

    window.addEventListener('showToast', event => {
        showToast(event.detail.type, event.detail.message);
    });

    function hideToast(toastElementOrButton) {
        const toast = toastElementOrButton.closest('.toast');
        if (toast) {
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => toast.remove(), 1000);
        }
    }

    // Laravel session trigger (optional)
    @if(session('toast'))
    document.addEventListener('DOMContentLoaded', function() {
        showToast(
            '{{ session('toast')['type'] }}',
                @json(session('toast')['title']),
                @json(session('toast')['message']),
                {{ session('toast')['time'] ?? 5000 }}
        );
    });
    @endif
</script>
