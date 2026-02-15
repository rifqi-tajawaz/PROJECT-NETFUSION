document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('fwdForm');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const est = document.getElementById('est').checked;
        const inv = document.getElementById('inv').checked;
        const fasttrack = document.getElementById('fasttrack').checked;
        const dropWan = document.getElementById('dropWan').checked;

        let script = `/ip firewall filter\n`;

        if (fasttrack) {
            script += `add chain=forward action=fasttrack-connection connection-state=established,related comment="defconf: fasttrack"\n`;
        }

        if (est) {
            script += `add chain=forward action=accept connection-state=established,related,untracked comment="defconf: accept established,related,untracked"\n`;
        }

        if (inv) {
            script += `add chain=forward action=drop connection-state=invalid comment="defconf: drop invalid"\n`;
        }

        if (dropWan) {
            script += `add chain=forward action=drop connection-state=new connection-nat-state=!dstnat in-interface-list=WAN comment="defconf: drop all from WAN not DSTNATed"\n`;
        }

        scriptOutput.innerText = script;
    });


});
