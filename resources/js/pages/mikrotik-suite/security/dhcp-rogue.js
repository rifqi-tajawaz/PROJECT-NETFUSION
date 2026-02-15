document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('rogueForm');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const iface = document.getElementById('iface').value;
        const mac = document.getElementById('validMac').value;
        const alert = document.getElementById('alert').value;

        let script = `/ip dhcp-server alert\n`;
        script += `add interface=${iface} valid-server-mac=${mac || '00:00:00:00:00:00'} on-alert="${alert}" disabled=no\n`;

        scriptOutput.innerText = script;
    });
});
