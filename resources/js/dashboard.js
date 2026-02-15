document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('.coverflow-track');
    // Check if element exists to avoid errors on other pages if this JS is somehow included globally
    if (!track) return;

    const cards = Array.from(document.querySelectorAll('.coverflow-card'));
    const prevBtn = document.querySelector('.nav-btn.prev');
    const nextBtn = document.querySelector('.nav-btn.next');

    if (cards.length === 0) return;

    let currentIndex = Math.floor(cards.length / 2); // Start at center

    function updateCarousel() {
        cards.forEach((card, index) => {
            // CRITICAL FIX: Clear all inline styles first so CSS classes can work
            card.style.transform = '';
            card.style.opacity = '';
            card.style.pointerEvents = '';
            card.style.display = 'block';

            // Reset classes
            card.classList.remove('active', 'prev-1', 'prev-2', 'next-1', 'next-2');

            const diff = index - currentIndex;

            if (diff === 0) {
                card.classList.add('active');
            } else if (diff === -1) {
                card.classList.add('prev-1');
            } else if (diff === 1) {
                card.classList.add('next-1');
            } else if (diff === -2) {
                card.classList.add('prev-2');
            } else if (diff === 2) {
                card.classList.add('next-2');
            } else {
                // Hide others or position far away
                if (Math.abs(diff) > 2) {
                    card.style.opacity = '0';
                    card.style.pointerEvents = 'none';
                    // Use a simple falloff for far elements so they don't glitch
                    card.style.transform = `translateX(${diff * 100}px) scale(0.5)`;
                }
            }
        });
    }

    // Controls
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            if (currentIndex < cards.length - 1) {
                currentIndex++;
                updateCarousel();
            }
        });
    }

    // Click on card to center
    cards.forEach((card, index) => {
        card.addEventListener('click', () => {
            if (currentIndex !== index) {
                currentIndex = index;
                updateCarousel();
            }
        });
    });

    // Initial Load
    updateCarousel();

    // --- TOUCH SWIPE SUPPORT ---
    let touchStartX = 0;
    let touchEndX = 0;
    const swipeThreshold = 50; // Minimum distance to trigger swipe

    const sliderContainer = document.querySelector('.perspective-container');

    if (sliderContainer) {
        sliderContainer.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        sliderContainer.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });
    }

    function handleSwipe() {
        const diff = touchEndX - touchStartX;

        if (Math.abs(diff) < swipeThreshold) return; // Ignore small taps

        if (diff > 0) {
            // Swipe Right -> Previous
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        } else {
            // Swipe Left -> Next
            if (currentIndex < cards.length - 1) {
                currentIndex++;
                updateCarousel();
            }
        }
    }
});
