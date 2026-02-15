document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('antennaForm');

    // Canvas
    const canvas = document.getElementById('fresnelCanvas');
    const ctx = canvas.getContext('2d');

    // Add CSRF to axios
    if (window.axios) {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        calculate();
    });

    const calculate = () => {
        // UI State: Loading
        const btn = form.querySelector('button[type="submit"]');
        const btnLabel = btn.querySelector('.btn-label');
        const btnLoader = btn.querySelector('.btn-loader');

        if (btnLabel) btnLabel.classList.add('d-none');
        if (btnLoader) btnLoader.classList.remove('d-none');
        btn.disabled = true;

        const route = form.getAttribute('data-route');
        const formData = new FormData(form);

        axios.post(route, formData)
            .then(response => {
                const data = response.data;
                if (data.status === 'success') {
                    document.getElementById('fresnelRadius').textContent = data.fresnel_radius_60 + ' m';
                    document.getElementById('recHeight').textContent = data.recommended_height + ' m';
                    document.getElementById('earthCurve').textContent = data.earth_curvature + ' m';

                    drawVisual(data.fresnel_full);
                }
            })
            .catch(error => {
                console.error(error);
                let msg = 'Error';
                if (error.response && error.response.data && error.response.data.message) {
                    msg = error.response.data.message;
                }
                // alert(msg); 
                // Alternatively, display error in UI, but existing UI design doesn't have an error box.
                // For now, logging to console is safe or maybe generic alert.
            })
            .finally(() => {
                // Restore UI
                if (btnLabel) btnLabel.classList.remove('d-none');
                if (btnLoader) btnLoader.classList.add('d-none');
                btn.disabled = false;
            });
    };

    const drawVisual = (radius) => {
        // Simple ellipsoid drawing
        const w = canvas.width;
        const h = canvas.height;
        ctx.clearRect(0, 0, w, h);

        // Axis
        ctx.strokeStyle = '#6c757d';
        ctx.beginPath();
        ctx.moveTo(50, h / 2);
        ctx.lineTo(w - 50, h / 2);
        ctx.stroke();

        // Fresnel Zone (Ellipse)
        // Not physically accurate scaling, just visual
        // We scale radius for visibility since real radius vs distance ratio is tiny
        ctx.fillStyle = 'rgba(255, 193, 7, 0.2)';
        ctx.strokeStyle = '#ffc107';
        ctx.beginPath();
        ctx.ellipse(w / 2, h / 2, (w - 100) / 2, radius * 10, 0, 0, 2 * Math.PI);
        ctx.fill();
        ctx.stroke();

        // Towers
        ctx.fillStyle = '#fff';
        ctx.fillRect(45, h / 2 - 20, 5, 20); // Left
        ctx.fillRect(w - 50, h / 2 - 20, 5, 20); // Right
    };

    // Initial optional calculation or wait for user input?
    // Original script auto-calculated on load using default values. Let's trigger it.
    calculate();
});
