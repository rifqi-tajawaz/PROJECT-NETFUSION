document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('nat11Form');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const pub = document.getElementById('pubIp').value;
        const priv = document.getElementById('privIp').value;
        const wan = document.getElementById('wan').value;

        let script = `/ip firewall nat\n`;
        script += `# Incoming traffic (DST-NAT)\n`;
        script += `add chain=dstnat dst-address=${pub} action=dst-nat to-addresses=${priv} in-interface=${wan} comment="1:1 NAT ${pub}->${priv}"\n`;
        script += `# Outgoing traffic (SRC-NAT)\n`;
        script += `add chain=srcnat src-address=${priv} action=src-nat to-addresses=${pub} out-interface=${wan} comment="1:1 NAT ${priv}->${pub}"\n`;

        scriptOutput.innerText = script;
    });


});
