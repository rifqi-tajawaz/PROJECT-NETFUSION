document.addEventListener('DOMContentLoaded', () => {
    window.beautify = function () {
        const input = document.getElementById('inputScript').value;
        if (!input) return;

        // Very basic RSC formatter logic
        // 1. Add newlines after semi-colons or major commands if they are on one line
        // 2. Add indentation for scopes {} (if any, though RSC is mostly linear commands)
        // 3. Add newlines before '/ip', '/system', etc if clustered.

        let formatted = input;

        // Add newline before slash commands if not already there, but simplistic
        formatted = formatted.replace(/\/([a-z0-9-]+)/g, "\n/$1");

        // Fix double newlines
        formatted = formatted.replace(/\n\s*\n/g, "\n");

        // Indent contents in {}
        // ... (complex, skipping for now, just line breaking)

        // Remove leading newline
        formatted = formatted.trim();

        document.getElementById('outputScript').innerText = formatted;
    };


});
