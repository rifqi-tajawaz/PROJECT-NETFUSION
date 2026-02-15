<div id="premium-lock-overlay">
    <div class="premium-lock-backdrop"></div>
    <div class="premium-lock-modal">
        <div class="premium-lock-content text-center">
            <div class="mb-3">
                <i class="material-icons-outlined text-warning" style="font-size: 48px;">lock</i>
            </div>
            <h3 class="fw-bold text-white mb-2">GET FULL ACCESS</h3>
            <h5 class="fw-bold text-white mb-4">PLEASE SUBSCRIBE</h5>
            <a href="{{ route('pricing') }}" class="btn btn-warning fw-bold px-4 py-2 rounded-pill text-uppercase">
                SUBSCRIBE NOW
            </a>
        </div>
    </div>
</div>

<style>
    /* Lock Overlay Styles */
    #premium-lock-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .premium-lock-backdrop {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6); /* Darkened background */
        backdrop-filter: blur(8px); /* The Blur Effect */
    }

    .premium-lock-modal {
        position: relative;
        z-index: 10000;
        background: rgba(33, 37, 41, 0.9); /* Dark card background */
        padding: 3rem;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        max-width: 400px;
        width: 90%;
        animation: slideDown 0.4s ease-out;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Class to blur the main content */
    body.is-locked .main-wrapper,
    body.is-locked .sidebar-wrapper,
    body.is-locked .top-header {
        filter: blur(5px);
        pointer-events: none; /* Prevent clicks on background */
        user-select: none;
    }
    
    /* Ensure the overlay itself is NOT blurred */
    body.is-locked #premium-lock-overlay {
        filter: none !important;
        pointer-events: auto;
    }
</style>
