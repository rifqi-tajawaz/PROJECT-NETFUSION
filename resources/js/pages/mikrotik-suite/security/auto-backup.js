document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('backupForm');
    const scriptOutput = document.getElementById('scriptOutput');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const em = document.getElementById('email').value;
        const int = document.getElementById('interval').value;

        let script = `/system scheduler add name="AutoBackup" interval=${int} on-event="
            /system backup save name=email-backup
            /tool e-mail send to=\\"${em}\\" subject=\\"Config Backup\\" file=email-backup.backup
            "`;

        scriptOutput.innerText = script;
    });
});
