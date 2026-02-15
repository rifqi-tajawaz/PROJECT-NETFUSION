document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('l7Form');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const name = document.getElementById('name').value;
        const re = document.getElementById('regex').value;

        let script = `/ip firewall layer7-protocol\n`;
        script += `add name="${name}" regexp="${re}"\n`;

        scriptOutput.innerText = script;
    });


});
