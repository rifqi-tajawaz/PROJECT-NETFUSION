document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('pfForm');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const wan = document.getElementById('wan').value;
        const pub = document.getElementById('pubPort').value;
        const ip = document.getElementById('intIp').value;
        const intP = document.getElementById('intPort').value || pub;
        const proto = document.getElementById('proto').value;

        let script = `/ip firewall nat\n`;
        script += `add chain=dstnat action=dst-nat to-addresses=${ip} to-ports=${intP} protocol=${proto} in-interface=${wan} dst-port=${pub} comment="Port Forward ${pub}->${ip}:${intP}"`;

        scriptOutput.innerText = script;
    });


});
