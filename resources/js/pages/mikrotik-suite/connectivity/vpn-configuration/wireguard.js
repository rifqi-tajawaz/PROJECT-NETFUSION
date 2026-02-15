document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('wgForm');
    const peersContainer = document.getElementById('peersContainer');
    const resultContainer = document.getElementById('resultContainer');
    const serverScript = document.getElementById('serverScript');
    const clientConfigOutput = document.getElementById('clientConfigOutput');

    // Add CSRF to axios
    if (window.axios) {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    }

    window.addPeer = function () {
        const count = peersContainer.children.length + 2; // +1 base, +1 next
        const html = `
             <div class="peer-item mb-3 animate__animated animate__fadeIn">
                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border);">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="small text-muted mb-1">Client Name</label>
                            <input type="text" class="form-control form-control-sm peer-name" name="peers[${count}][name]" value="Client-${count}">
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted mb-1">Allowed IPs (Client IP)</label>
                            <input type="text" class="form-control form-control-sm peer-ip" name="peers[${count}][ip]" value="10.10.10.${count}/32">
                        </div>
                         <div class="col-md-4">
                            <label class="small text-muted mb-1">&nbsp;</label>
                            <button type="button" class="btn btn-sm btn-danger w-100" onclick="this.closest('.peer-item').remove()">Remove</button>
                        </div>
                        <div class="col-12 mt-2">
                                <label class="small text-muted mb-1">Client Public Key (Optional)</label>
                                <input type="text" class="form-control form-control-sm peer-pubkey" name="peers[${count}][public_key]" placeholder="Paste Client Public Key here if known...">
                        </div>
                    </div>
                </div>
            </div>
        `;
        peersContainer.insertAdjacentHTML('beforeend', html);
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

        serverScript.innerText = '// Generating...';

        const route = form.getAttribute('data-route');

        // Manual form data construction to handle dynamic peers
        const formData = new FormData();
        formData.append('listen_port', document.getElementById('wgPort').value);
        formData.append('interface_name', document.getElementById('wgName').value);
        formData.append('network_address', document.getElementById('wgNetwork').value);

        const peers = document.querySelectorAll('.peer-item');
        peers.forEach((peer, index) => {
            formData.append(`peers[${index}][name]`, peer.querySelector('.peer-name').value);
            formData.append(`peers[${index}][ip]`, peer.querySelector('.peer-ip').value);
            formData.append(`peers[${index}][public_key]`, peer.querySelector('.peer-pubkey').value.trim());
        });

        axios.post(route, formData)
            .then(response => {
                const data = response.data;
                if (data.status === 'success') {
                    serverScript.innerText = data.script;
                    resultContainer.style.display = 'block';
                    window.updateClientConfigs();
                }
            })
            .catch(error => {
                console.error(error);
                let msg = 'An error occurred.';
                if (error.response && error.response.data && error.response.data.message) {
                    msg = error.response.data.message;
                } else if (error.message) {
                    msg = error.message;
                }
                serverScript.innerText = '// Error: ' + msg;
            })
            .finally(() => {
                // Restore UI
                if (btnLabel) btnLabel.classList.remove('d-none');
                if (btnLoader) btnLoader.classList.add('d-none');
                btn.disabled = false;
            });
    });

    window.updateClientConfigs = function () {
        const endpoint = document.getElementById('wgEndpoint').value || 'MY_PUBLIC_IP';
        const port = document.getElementById('wgPort').value;
        const serverPubKey = document.getElementById('serverPubKeyInput').value.trim() || 'REPLACE_WITH_SERVER_PUBLIC_KEY';

        let output = '';

        const peers = document.querySelectorAll('.peer-item');
        peers.forEach(peer => {
            const clientName = peer.querySelector('.peer-name').value;
            const clientIp = peer.querySelector('.peer-ip').value;

            const config = `[Interface]
PrivateKey = <CLIENT_PRIVATE_KEY>
Address = ${clientIp}
DNS = 8.8.8.8

[Peer]
PublicKey = ${serverPubKey}
AllowedIPs = 0.0.0.0/0
Endpoint = ${endpoint}:${port}
PersistentKeepalive = 25
`;
            output += `<h6 class="text-white mt-3">${clientName}</h6>`;
            output += `<pre class="bg-dark p-3 rounded text-warning font-monospace small">${config}</pre>`;
        });

        clientConfigOutput.innerHTML = output;
    }
});
