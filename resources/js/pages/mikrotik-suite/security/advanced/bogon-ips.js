document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('bogonForm');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const wan = document.getElementById('wan').value;

        let script = `/ip firewall address-list\n`;
        script += `add list=bogons address=0.0.0.0/8\n`;
        script += `add list=bogons address=10.0.0.0/8\n`;
        script += `add list=bogons address=100.64.0.0/10\n`;
        script += `add list=bogons address=127.0.0.0/8\n`;
        script += `add list=bogons address=169.254.0.0/16\n`;
        script += `add list=bogons address=172.16.0.0/12\n`;
        script += `add list=bogons address=192.0.0.0/24\n`;
        script += `add list=bogons address=192.0.2.0/24\n`;
        script += `add list=bogons address=192.168.0.0/16\n`;
        script += `add list=bogons address=198.18.0.0/15\n`;
        script += `add list=bogons address=198.51.100.0/24\n`;
        script += `add list=bogons address=203.0.113.0/24\n`;
        script += `add list=bogons address=224.0.0.0/4\n`;
        script += `add list=bogons address=240.0.0.0/4\n`;

        script += `/ip firewall filter\n`;
        script += `add chain=input in-interface=${wan} src-address-list=bogons action=drop comment="Drop Bogons Input"\n`;
        script += `add chain=forward in-interface=${wan} src-address-list=bogons action=drop comment="Drop Bogons Forward"\n`;

        scriptOutput.innerText = script;
    });


});
