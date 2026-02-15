document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('ddosForm');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const syn = document.getElementById('syn').value; // e.g. "200,5"
        const conn = document.getElementById('conn').value;

        let script = `/ip firewall filter\n`;
        script += `# Connection Limit (Per IP)\n`;
        script += `add chain=input protocol=tcp connection-limit=${conn},32 action=add-src-to-address-list address-list="conn_limit" address-list-timeout=1d comment="Limit Connections"\n`;
        script += `add chain=input src-address-list="conn_limit" action=drop comment="Drop Excess Connections"\n`;

        script += `# SYN Flood Protection\n`;
        const [rate, burst] = syn.split(',').map(s => s.trim());
        script += `add chain=input protocol=tcp tcp-flags=syn connection-state=new limit=${rate}/5s,${burst}:packet action=accept comment="Accept Normal SYN"\n`;
        script += `add chain=input protocol=tcp tcp-flags=syn connection-state=new action=drop comment="Drop SYN Flood"\n`;

        scriptOutput.innerText = script;
    });


});
