

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('ipCalcForm');

    // UI Elements
    const resultContent = document.getElementById('resultContent');
    const placeholder = document.getElementById('placeholder');
    const resNetwork = document.getElementById('resNetwork');
    const resCidr = document.getElementById('resCidr');
    const resBroadcast = document.getElementById('resBroadcast');
    const resMask = document.getElementById('resMask');
    const resHosts = document.getElementById('resHosts');
    const resClass = document.getElementById('resClass');
    const resFirst = document.getElementById('resFirst');
    const resLast = document.getElementById('resLast');
    const resBinary = document.getElementById('resBinary');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Calculating...';

        axios.post(form.dataset.route, formData)
            .then(response => {
                const data = response.data;

                // Render Results
                resNetwork.textContent = data.network;
                resCidr.textContent = '/' + data.cidr;
                resBroadcast.textContent = data.broadcast;
                resMask.textContent = data.mask;
                resHosts.textContent = new Intl.NumberFormat().format(data.hosts);
                resClass.textContent = data.class;
                resFirst.textContent = data.first_ip;
                resLast.textContent = data.last_ip;

                const binaryInfo = `
IP Address: ${data.binary.ip}
Subnet Mask: ${data.binary.mask}
Network   : ${data.binary.network}
Broadcast : ${data.binary.broadcast}
                `;
                resBinary.textContent = binaryInfo.trim();

                placeholder.style.display = 'none';
                resultContent.style.display = 'block';
                resultContent.classList.add('animate__animated', 'animate__fadeIn');
            })
            .catch(error => {
                console.error('Calculation Error:', error);
                if (error.response && error.response.data && error.response.data.errors) {
                    let msg = "Validation Error:\n";
                    for (const key in error.response.data.errors) {
                        msg += `- ${error.response.data.errors[key][0]}\n`;
                    }
                    alert(msg);
                } else {
                    alert('An error occurred during calculation.');
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
    });
});
