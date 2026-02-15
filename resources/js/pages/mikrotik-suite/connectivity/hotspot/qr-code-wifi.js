document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('qrForm');
    const qrContainer = document.getElementById('qrContainer');
    const placeholder = document.getElementById('placeholder');
    const qrImage = document.getElementById('qrImage');
    const displaySsid = document.getElementById('displaySsid');
    const displayPass = document.getElementById('displayPass');
    const encSelect = document.getElementById('encryption');
    const passField = document.getElementById('passField');
    const passInput = document.getElementById('password');

    // Add CSRF to axios
    if (window.axios) {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    }

    encSelect.addEventListener('change', function () {
        if (this.value === 'nopass') {
            passField.style.display = 'none';
        } else {
            passField.style.display = 'block';
        }
    });

    window.togglePass = function () {
        if (passInput.type === 'password') {
            passInput.type = 'text';
        } else {
            passInput.type = 'password';
        }
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

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
                    // Update UI
                    qrImage.src = data.qr_url;
                    displaySsid.textContent = data.ssid;

                    if (data.password) {
                        displayPass.textContent = `Password: ${data.password}`;
                    } else {
                        displayPass.textContent = 'Open Network';
                    }

                    placeholder.style.display = 'none';
                    qrContainer.style.display = 'block';
                }
            })
            .catch(error => {
                console.error(error);
                alert('An error occurred generating the QR code.');
            })
            .finally(() => {
                // Restore UI
                if (btnLabel) btnLabel.classList.remove('d-none');
                if (btnLoader) btnLoader.classList.add('d-none');
                btn.disabled = false;
            });
    });

    window.printQr = function () {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>WiFi QR Code - ${displaySsid.textContent}</title>
                <style>
                    body { font-family: sans-serif; text-align: center; padding: 50px; }
                    .box { border: 1px solid #ccc; padding: 20px; display: inline-block; border-radius: 10px; }
                    h1 { margin: 10px 0; font-size: 24px; }
                    p { font-size: 18px; color: #555; }
                    img { max-width: 100%; height: auto; }
                </style>
            </head>
            <body>
                <div class="box">
                    <img src="${qrImage.src}" width="300" height="300">
                    <h1>${displaySsid.textContent}</h1>
                    <p>${displayPass.textContent}</p>
                </div>
                <script>
                    window.onload = function() { window.print(); window.close(); }
                </script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    window.downloadQr = function () {
        // Since we are using an external image allow cross origin download might be tricky without proxy.
        // But let's try fetch blob approach.
        fetch(qrImage.src)
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = `WiFi-QR-${displaySsid.textContent}.png`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(() => alert('Could not download image directly. Right click the image and Save As.'));
    }
});
