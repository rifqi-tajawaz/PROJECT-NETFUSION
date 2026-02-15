document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('hideForm');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const neigh = document.getElementById('neigh').checked;
        const bw = document.getElementById('bw').checked;
        const dns = document.getElementById('dns').checked;
        const proxy = document.getElementById('proxy').checked;

        let script = `# Hide Router Identity\n`;

        if (neigh) script += `/ip neighbor discovery-settings set discover-interface-list=none\n`;
        if (bw) script += `/tool bandwidth-server set enabled=no\n`;
        if (dns) script += `/ip dns set allow-remote-requests=no\n`;
        if (proxy) {
            script += `/ip proxy set enabled=no\n`;
            script += `/ip socks set enabled=no\n`;
            script += `/ip upnp set enabled=no\n`;
            script += `/ip cloud set ddns-enabled=no update-time=no\n`;
        }

        scriptOutput.innerText = script;
    });
});
