import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {
    // Expose functions globally if needed for onclick handlers
    // Ideally we should bind events here, but for now we keep the structure compatible
    window.generateFirewall = generateFirewall;
    window.copyToClipboard = copyToClipboard;
});

function generateFirewall() {
    // Collect form data
    const form = document.getElementById('firewallForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    // Show Loading
    const output = document.getElementById('outputScript');
    output.innerText = "# Generating Best Practice Firewall Rules...\n# Please wait...";

    // Use Standard Axios global which should be available
    axios.post(document.getElementById('firewallForm').dataset.route, data)
        .then(response => {
            output.innerText = response.data.script;
        })
        .catch(error => {
            output.innerText = "# Error generating script\n" + (error.response?.data?.message || error.message);
            console.error(error);
        });
}

function copyToClipboard() {
    const text = document.getElementById('outputScript').innerText;
    navigator.clipboard.writeText(text).then(() => {
        // Optional: Show toast or feedback
        // Reusing standard 'copyText' logic if available or simpler alert
        alert('Copied to clipboard!'); // Replacing complex button DOM manipulation for stability
    });
}
