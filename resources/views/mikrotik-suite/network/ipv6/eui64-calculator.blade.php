@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-calculator-fill me-2 text-info"></i> EUI-64 Calculator
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Calculate IPv6 Link-Local Address from MAC Address using EUI-64 method.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-6">
                <div class="glass-card h-100 p-4">
                    <form id="euiForm">
                        <div class="mb-3">
                            <label class="form-label text-white small text-uppercase">MAC Address</label>
                            <input type="text" class="form-control font-monospace" id="mac" placeholder="00:11:22:33:44:55"
                                maxlength="17">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white small text-uppercase">Prefix (Optional)</label>
                            <input type="text" class="form-control font-monospace" id="prefix" value="fe80::"
                                placeholder="2001:db8::">
                        </div>
                        <button type="button" class="btn btn-info w-100 mb-4" onclick="calcEUI()">Calculate</button>
                    </form>

                    <div class="p-4 bg-dark bg-opacity-50 rounded-3 border border-secondary border-opacity-25 text-center">
                        <h6 class="text-white-50 small text-uppercase">Result IPv6</h6>
                        <h3 class="text-white font-monospace text-wrap text-break" id="resultIPv6">::</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calcEUI() {
            let mac = document.getElementById('mac').value.trim();
            const prefix = document.getElementById('prefix').value.trim() || 'fe80::';

            // Basic normalization
            mac = mac.replace(/[^0-9A-Fa-f]/g, '');
            if (mac.length !== 12) {
                alert("Invalid MAC address length");
                return;
            }

            // EUI-64 Logic:
            // 1. Split MAC into two halves (24 bits)
            // 2. Insert FFFE in middle
            // 3. Flip 7th bit of first byte

            let p1 = mac.substring(0, 6);
            let p2 = mac.substring(6);

            // Flip bit
            let b1 = parseInt(p1.substring(0, 2), 16);
            b1 = b1 ^ 0x02; // XOR 00000010
            let b1Hex = b1.toString(16).padStart(2, '0');

            let modifiedMac = b1Hex + p1.substring(2) + "fffe" + p2;

            // Format as IPv6 groups of 4 chars
            let groups = [];
            for (let i = 0; i < modifiedMac.length; i += 4) {
                groups.push(modifiedMac.substring(i, i + 4));
            }

            // Combine with prefix
            // Handle prefix double colon
            let full = "";
            if (prefix.endsWith('::')) {
                full = prefix + groups.join(':');
            } else if (prefix.endsWith(':')) {
                full = prefix + groups.join(':');
            } else {
                full = prefix + ":" + groups.join(':');
            }

            document.getElementById('resultIPv6').innerText = full.toLowerCase();
        }
    </script>
@endsection
