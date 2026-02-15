document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('masqForm');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const wan = document.getElementById('wan').value;

        let script = `/ip firewall nat\n`;
        script += `add chain=srcnat action=masquerade out-interface=${wan} comment="defconf: masquerade"\n`;

        scriptOutput.innerText = script;
    });


});
