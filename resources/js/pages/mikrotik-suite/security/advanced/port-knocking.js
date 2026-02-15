document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('knockForm');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const ports = document.getElementById('ports').value.split(',').map(p => p.trim());
        const target = document.getElementById('target').value;
        const time = document.getElementById('timeout').value;

        let script = `/ip firewall filter\n`;

        // Multi-stage knock
        ports.forEach((p, i) => {
            const isFirst = i === 0;
            const isLast = i === ports.length - 1;
            const prevList = isFirst ? '' : `src-address-list="knock${i}"`;
            const nextList = isLast ? `src-address-list="knock_success"` : `src-address-list="knock${i + 1}"`;
            const dur = isLast ? time : '10s';

            script += `add chain=input protocol=tcp dst-port=${p} action=add-src-to-address-list address-list="${isLast ? 'knock_success' : 'knock' + (i + 1)}" address-list-timeout=${dur} ${prevList ? prevList : ''} comment="Knock Step ${i + 1}"\n`;
        });

        script += `add chain=input protocol=tcp dst-port=${target} src-address-list="knock_success" action=accept comment="Allow Knocked User"\n`;
        script += `add chain=input protocol=tcp dst-port=${target} action=drop comment="Drop others"\n`;

        scriptOutput.innerText = script;
    });


});
