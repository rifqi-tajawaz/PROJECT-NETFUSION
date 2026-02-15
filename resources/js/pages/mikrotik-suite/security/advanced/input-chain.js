document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('inputForm');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const est = document.getElementById('acceptEst').checked;
        const inv = document.getElementById('dropInv').checked;
        const icmp = document.getElementById('acceptIcmp').checked;
        const mgmt = document.getElementById('mgmtIp').value;
        const drop = document.getElementById('dropAll').checked;

        let script = `/ip firewall filter\n`;

        if (est) script += `add chain=input action=accept connection-state=established,related,untracked comment="defconf: accept established,related,untracked"\n`;
        if (inv) script += `add chain=input action=drop connection-state=invalid comment="defconf: drop invalid"\n`;
        if (icmp) script += `add chain=input action=accept protocol=icmp comment="defconf: accept ICMP"\n`;

        if (mgmt) {
            script += `add chain=input action=accept src-address=${mgmt} comment="Allow Management"\n`;
        }

        if (drop) {
            script += `add chain=input action=drop in-interface-list=WAN comment="defconf: drop all not coming from LAN"\n`;
        }

        scriptOutput.innerText = script;
    });


});
