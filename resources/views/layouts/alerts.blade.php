<div id="global-notification-container" class="position-fixed end-0 p-4"
    style="z-index: 1100; top: 70px; max-width: 420px; width: 100%; pointer-events: none;">
    <style>
        .custom-alert {
            pointer-events: auto;
            animation: slideInRight 0.3s cubic-bezier(0.2, 0, 0.2, 1);
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .alert-progress {
            transition: width 4s linear;
        }
    </style>

    <script>
        // Global Notification Function
        window.showNotification = function(type, message) {
            const container = document.getElementById('global-notification-container');
            if (!container) return;

            // Define styles based on type
            const styles = {
                success: {
                    bg: 'bg-success',
                    icon: 'bxs-check-circle',
                    title: 'Success',
                    duration: 4000
                },
                error: {
                    bg: 'bg-danger',
                    icon: 'bxs-x-circle',
                    title: 'Error',
                    duration: 6000
                },
                warning: {
                    bg: 'bg-warning',
                    icon: 'bxs-error',
                    title: 'Warning',
                    duration: 5000
                },
                info: {
                    bg: 'bg-info',
                    icon: 'bxs-info-circle',
                    title: 'Info',
                    duration: 4000
                }
            };

            const style = styles[type] || styles.info;
            const duration = style.duration;

            // Create Alert Element
            const alertHtml = `
                <div class="custom-alert bg-white rounded-3 shadow-lg border-0 mb-3 overflow-hidden position-relative"
                    data-duration="${duration}">
                    <div class="d-flex align-items-stretch">
                        <div class="${style.bg}" style="width: 6px;"></div>
                        <div class="p-4 d-flex align-items-start w-100">
                            <div class="${style.bg} bg-opacity-10 text-${type === 'warning' ? 'warning' : style.bg.replace('bg-', '')} rounded-circle p-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                style="width: 48px; height: 48px;">
                                <i class='bx ${style.icon} fs-3'></i>
                            </div>
                            <div class="flex-grow-1 pe-2">
                                <h6 class="fw-bold mb-1 text-dark">${style.title}</h6>
                                <p class="mb-0 text-secondary small lh-sm">${message}</p>
                            </div>
                            <button type="button" class="btn-close shadow-none"
                                onclick="this.closest('.custom-alert').remove()"></button>
                        </div>
                    </div>
                    <div class="progress bg-transparent" style="height: 3px;">
                        <div class="progress-bar ${style.bg} alert-progress" role="progressbar" style="width: 100%;"></div>
                    </div>
                </div>
            `;

            // Append to container
            container.insertAdjacentHTML('beforeend', alertHtml);

            // Initialize animation for the new alert
            const newAlert = container.lastElementChild;
            const progressBar = newAlert.querySelector('.alert-progress');

            // Animate Progress
            if (progressBar) {
                progressBar.style.transition = `width ${duration}ms linear`;
                setTimeout(() => progressBar.style.width = '0%', 100);
            }

            // Auto Dismiss
            setTimeout(() => {
                newAlert.style.transition = 'all 0.5s ease';
                newAlert.style.transform = 'translateX(100%)';
                newAlert.style.opacity = '0';
                setTimeout(() => newAlert.remove(), 500);
            }, duration);
        };

        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.custom-alert');
            alerts.forEach(alert => {
                // Existing server-side alerts initialization logic...
                const duration = parseInt(alert.getAttribute('data-duration')) || 4000;
                const progressBar = alert.querySelector('.alert-progress');

                if (progressBar) {
                    progressBar.style.transition = `width ${duration}ms linear`;
                    setTimeout(() => progressBar.style.width = '0%', 100);
                }

                setTimeout(() => {
                    alert.style.transition = 'all 0.5s ease';
                    alert.style.transform = 'translateX(100%)';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, duration);
            });
        });
    </script>
    @if(session('success'))
        <div class="custom-alert bg-white rounded-3 shadow-lg border-0 mb-3 overflow-hidden position-relative"
            data-duration="4000">
            <div class="d-flex align-items-stretch">
                <div class="bg-success" style="width: 6px;"></div>
                <div class="p-4 d-flex align-items-start w-100">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                        style="width: 48px; height: 48px;">
                        <i class='bx bxs-check-circle fs-3'></i>
                    </div>
                    <div class="flex-grow-1 pe-2">
                        <h6 class="fw-bold mb-1 text-dark">Success</h6>
                        <p class="mb-0 text-secondary small lh-sm">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="btn-close shadow-none"
                        onclick="this.closest('.custom-alert').remove()"></button>
                </div>
            </div>
            <div class="progress bg-transparent" style="height: 3px;">
                <div class="progress-bar bg-success alert-progress" role="progressbar" style="width: 100%;"></div>
            </div>
        </div>
    @endif

    {{-- ERROR --}}
    @if(session('error'))
        <div class="custom-alert bg-white rounded-3 shadow-lg border-0 mb-3 overflow-hidden position-relative"
            data-duration="6000">
            <div class="d-flex align-items-stretch">
                <div class="bg-danger" style="width: 6px;"></div>
                <div class="p-4 d-flex align-items-start w-100">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                        style="width: 48px; height: 48px;">
                        <i class='bx bxs-x-circle fs-3'></i>
                    </div>
                    <div class="flex-grow-1 pe-2">
                        <h6 class="fw-bold mb-1 text-dark">Error</h6>
                        <p class="mb-0 text-secondary small lh-sm">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="btn-close shadow-none"
                        onclick="this.closest('.custom-alert').remove()"></button>
                </div>
            </div>
            <div class="progress bg-transparent" style="height: 3px;">
                <div class="progress-bar bg-danger alert-progress" role="progressbar" style="width: 100%;"></div>
            </div>
        </div>
    @endif

    {{-- WARNING --}}
    @if(session('warning'))
        <div class="custom-alert bg-white rounded-3 shadow-lg border-0 mb-3 overflow-hidden position-relative"
            data-duration="5000">
            <div class="d-flex align-items-stretch">
                <div class="bg-warning" style="width: 6px;"></div>
                <div class="p-4 d-flex align-items-start w-100">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                        style="width: 48px; height: 48px;">
                        <i class='bx bxs-error fs-3'></i>
                    </div>
                    <div class="flex-grow-1 pe-2">
                        <h6 class="fw-bold mb-1 text-dark">Warning</h6>
                        <p class="mb-0 text-secondary small lh-sm">{{ session('warning') }}</p>
                    </div>
                    <button type="button" class="btn-close shadow-none"
                        onclick="this.closest('.custom-alert').remove()"></button>
                </div>
            </div>
            <div class="progress bg-transparent" style="height: 3px;">
                <div class="progress-bar bg-warning alert-progress" role="progressbar" style="width: 100%;"></div>
            </div>
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="custom-alert bg-white rounded-3 shadow-lg border-0 mb-3 overflow-hidden position-relative"
            data-duration="8000">
            <div class="d-flex align-items-stretch">
                <div class="bg-danger" style="width: 6px;"></div>
                <div class="p-4 d-flex align-items-start w-100">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2 d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                        style="width: 48px; height: 48px;">
                        <i class='bx bxs-message-square-error fs-3'></i>
                    </div>
                    <div class="flex-grow-1 pe-2">
                        <h6 class="fw-bold mb-1 text-dark">Please Check Inputs</h6>
                        <ul class="mb-0 text-secondary small ps-3 lh-sm mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close shadow-none"
                        onclick="this.closest('.custom-alert').remove()"></button>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.custom-alert');
            alerts.forEach(alert => {
                const duration = parseInt(alert.getAttribute('data-duration')) || 4000;
                const progressBar = alert.querySelector('.alert-progress');

                // Animate Progress
                if (progressBar) {
                    progressBar.style.transition = `width ${duration}ms linear`;
                    setTimeout(() => progressBar.style.width = '0%', 100);
                }

                // Auto Dismiss
                setTimeout(() => {
                    alert.style.transition = 'all 0.5s ease';
                    alert.style.transform = 'translateX(100%)';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, duration);
            });
        });
    </script>
</div>