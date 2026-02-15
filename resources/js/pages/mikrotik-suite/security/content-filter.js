document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('contentForm');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const keywords = document.getElementById('keywords').value.split('\n').filter(k => k.trim() !== '');
        const method = document.getElementById('method').value;

        let script = `/ip firewall filter\n`;

        keywords.forEach(k => {
            const match = method === 'tls' ? `tls-host="*${k.trim()}*"` : `content="${k.trim()}"`;
            const proto = method === 'tls' ? 'protocol=tcp dst-port=443' : 'protocol=tcp dst-port=80,443';

            script += `add chain=forward action=drop ${proto} ${match} comment="Block ${k.trim()}"\n`;
        });

        scriptOutput.innerText = script;
    });
});
