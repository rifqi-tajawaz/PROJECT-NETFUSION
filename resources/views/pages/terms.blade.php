@extends('layouts.guest')

@section('title')
    <title>Syarat & Ketentuan - Dashboard Tools Netara</title>
@endsection

@section('content')
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #344767;
        }

        .legal-document {
            background: #fff;
            box-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.05);
            border-radius: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Header Gradient Stripe */
        .doc-header-stripe {
            height: 8px;
            background: linear-gradient(90deg, #FF0080 0%, #FF6600 100%);
            width: 100%;
        }

        .doc-title {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #1a1a1a;
        }

        /* Typography */
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
            scroll-margin-top: 100px;
            /* Offset for sticky header if any */
        }

        .text-justify {
            text-align: justify;
            line-height: 1.8;
            color: #555;
        }

        /* Floating TOC (Table of Contents) */
        .toc-sticky {
            position: sticky;
            top: 2rem;
        }

        .nav-toc .nav-link {
            color: #8392ab;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-left: 2px solid transparent;
            transition: all 0.2s;
        }

        .nav-toc .nav-link:hover,
        .nav-toc .nav-link.active {
            color: #FF6600;
            border-left-color: #FF6600;
            background: linear-gradient(90deg, rgba(255, 102, 0, 0.05) 0%, transparent 100%);
        }

        /* Bottom Action Bar */
        .action-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
            z-index: 1000;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }

        /* Padding bottom to prevent content being hidden by action bar */
        .content-wrapper {
            padding-bottom: 80px;
        }
    </style>

    <div class="container my-5 content-wrapper">
        <div class="row">

            <!-- LEFT: Main Document Content -->
            <div class="col-lg-8 mb-5 mb-lg-0">
                <div class="legal-document">
                    <div class="doc-header-stripe"></div>

                    <div class="p-5">
                        <div class="text-center mb-5">
                            <img src="{{ URL::asset('build/images/logo1.png') }}" class="mb-4" width="100" alt="Logo">
                            <h1 class="doc-title display-6">Syarat & Ketentuan Penggunaan</h1>
                            <p class="text-muted">Terakhir diperbarui: {{ date('d F Y') }}</p>
                        </div>

                        <div
                            class="alert alert-light border border-start-0 border-end-0 border-top-0 border-bottom-0 border-primary bg-primary bg-opacity-10 rounded-3 p-4 mb-5">
                            <div class="d-flex">
                                <i class="bi bi-info-circle-fill text-dark me-3 fs-5"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Ringkasan Penting</h6>
                                    <p class="mb-0 small text-secondary">
                                        Dokumen ini adalah perjanjian mengikat antara Anda dan Dashboard Tools Netara By
                                        Tajawaz Solutions. Harap baca dengan saksama, terutama bagian <strong>Batasan
                                            Tanggung Jawab</strong> terkait risiko konfigurasi jaringan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <article>
                            <section id="section-1">
                                <h3 class="section-title">1. Pendahuluan</h3>
                                <p class="text-justify">
                                    Selamat datang di Dashboard Tools Netara By Tajawaz Solutions ("Layanan"). Dengan
                                    mengakses, mendaftar,
                                    atau menggunakan platform kami, Anda menyetujui untuk terikat oleh Syarat dan Ketentuan
                                    ini. Jika Anda tidak setuju dengan bagian mana pun dari ketentuan ini, Anda tidak
                                    diperkenankan menggunakan Layanan kami.
                                </p>
                            </section>

                            <section id="section-2">
                                <h3 class="section-title">2. Kelayakan Pengguna</h3>
                                <p class="text-justify">
                                    Layanan ini ditujukan untuk penggunaan profesional oleh administrator jaringan, teknisi
                                    IT, dan pengguna yang memiliki wewenang untuk mengelola infrastruktur jaringan. Dengan
                                    menggunakan Layanan, Anda menjamin bahwa:
                                </p>
                                <ul class="text-secondary mb-3">
                                    <li>Anda berusia minimal 18 tahun atau memiliki kapasitas hukum untuk mengikatkan diri
                                        dalam kontrak.</li>
                                    <li>Informasi yang Anda berikan saat pendaftaran adalah akurat dan terkini.</li>
                                    <li>Anda tidak dilarang oleh hukum yang berlaku untuk menerima layanan kami.</li>
                                </ul>
                            </section>

                            <section id="section-3">
                                <h3 class="section-title">3. Penggunaan yang Diizinkan</h3>
                                <p class="text-justify">
                                    Seluruh desain antarmuka, kode sumber, logo, dan konten dalam aplikasi adalah milik
                                    eksklusif Dashboard Tools Netara By Tajawaz Solutions. Anda diberikan lisensi terbatas,
                                    tidak eksklusif, dan dapat ditarik kembali untuk menggunakan Layanan sesuai dengan
                                    Ketentuan ini.
                                </p>
                                <p class="text-justify">
                                    Anda diberikan lisensi terbatas, non-eksklusif, dan tidak dapat dipindahtangankan untuk
                                    menggunakan Layanan hanya untuk tujuan pengelolaan perangkat jaringan milik Anda atau
                                    organisasi yang Anda wakili secara sah. Anda setuju untuk tidak:
                                </p>
                                <ul class="text-secondary mb-3">
                                    <li>Menggunakan Layanan untuk aktivitas apa pun yang melanggar hukum, curang, atau
                                        berbahaya.</li>
                                    <li>Mencoba mendapatkan akses tidak sah ke sistem, jaringan, atau data pengguna lain.
                                    </li>
                                    <li>Menyalin, memodifikasi, atau mendistribusikan ulang bagian mana pun dari Layanan
                                        tanpa izin tertulis.</li>
                                </ul>
                            </section>

                            <section id="section-4">
                                <h3 class="section-title">4. Konfigurasi Jaringan & Risiko</h3>
                                <p class="text-justify">
                                    Fitur inti dari Layanan kami mencakup otomatisasi konfigurasi perangkat Mikrotik
                                    (misalnya: firewall, routing, hotspot). Anda memahami dan menyetujui bahwa:
                                </p>
                                <div class="p-3 bg-light rounded border-start border-4 border-warning mb-3">
                                    <strong class="text-dark d-block mb-1">Penyangkalan Tanggung Jawab (Disclaimer)</strong>
                                    <span class="small text-muted">Kami tidak bertanggung jawab atas gangguan layanan,
                                        kerusakan perangkat keras, atau kehilangan data yang diakibatkan oleh penerapan
                                        konfigurasi melalui alat kami. Segala risiko penggunaan fitur otomatisasi sepenuhnya
                                        menjadi tanggung jawab Anda.</span>
                                </div>
                            </section>

                            <section id="section-5">
                                <h3 class="section-title">5. Privasi & Keamanan Data</h3>
                                <p class="text-justify">
                                    Kami menghargai privasi Anda. Kami menerapkan langkah-langkah keamanan industri untuk
                                    melindungi informasi akun dan log aktivitas Anda. Namun, tidak ada metode transmisi data
                                    melalui internet yang 100% aman. Anda bertanggung jawab untuk menjaga kerahasiaan kata
                                    sandi dan token akses Anda.
                                </p>
                            </section>

                            <section id="section-6">
                                <h3 class="section-title">6. Perubahan Ketentuan</h3>
                                <p class="text-justify">
                                    Kami berhak untuk mengubah atau mengganti Syarat ini sewaktu-waktu. Perubahan materi
                                    yang signifikan akan diberitahukan melalui email atau pemberitahuan di dashboard
                                    setidaknya 30 hari sebelum berlaku efektif.
                                </p>
                            </section>
                        </article>

                        <div class="mt-5 pt-5 border-top text-center text-muted">
                            <small>Akhir dari Dokumen</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Floating Navigation (Desktop Only) -->
            <div class="col-lg-4 d-none d-lg-block">
                <div class="toc-sticky">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3 text-uppercase small text-muted ls-1">Daftar Isi</h6>
                            <nav class="nav flex-column nav-toc">
                                <a class="nav-link active" href="#section-1">1. Pendahuluan</a>
                                <a class="nav-link" href="#section-2">2. Kelayakan Pengguna</a>
                                <a class="nav-link" href="#section-3">3. Penggunaan Diizinkan</a>
                                <a class="nav-link" href="#section-4">4. Risiko & Konfigurasi</a>
                                <a class="nav-link" href="#section-5">5. Privasi & Keamanan</a>
                                <a class="nav-link" href="#section-6">6. Perubahan Ketentuan</a>
                            </nav>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 bg-gradient-danger text-white overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <i class="bi bi-shield-check position-absolute top-0 end-0 opacity-25"
                                style="font-size: 5rem; margin-right: -1rem; margin-top: -1rem;"></i>
                            <h5 class="fw-bold z-index-1 position-relative">Legal & Aman</h5>
                            <p class="small opacity-75 mb-0 z-index-1 position-relative">Dokumen ini menjamin keamanan hak
                                Anda dan kami dalam menggunakan layanan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FIXED ACTION BAR -->
    <div class="action-bar shadow-lg">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="form-check d-none d-md-block">
                    <input class="form-check-input" type="checkbox" id="readConfirm" onclick="toggleAction()">
                    <label class="form-check-label text-dark fw-semibold" for="readConfirm" style="cursor: pointer;">
                        Saya telah membaca dan menyetujui Syarat & Ketentuan ini.
                    </label>
                </div>
                <!-- Mobile checkbox version -->
                <div class="form-check d-block d-md-none me-3">
                    <input class="form-check-input" type="checkbox" id="readConfirmMobile" onclick="toggleActionMobile()">
                    <label class="form-check-label text-dark small" for="readConfirmMobile">
                        Saya Setuju
                    </label>
                </div>

                <div class="d-flex gap-2">
                    <button onclick="window.close()" class="btn btn-light border fw-semibold">Tutup</button>
                    <a href="{{ route('register') }}" id="agreeBtn" class="btn btn-dark px-4 fw-bold disabled">Lanjutkan
                        Mendaftar</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Smooth scrolling for TOC
        document.querySelectorAll('.nav-toc .nav-link').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelectorAll('.nav-toc .nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');

                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        function toggleAction() {
            var chk = document.getElementById('readConfirm');
            var btn = document.getElementById('agreeBtn');
            if (chk.checked) {
                btn.classList.remove('disabled', 'btn-dark');
                btn.classList.add('btn-primary', 'btn-gradient-1');
            } else {
                btn.classList.add('disabled', 'btn-dark');
                btn.classList.remove('btn-primary', 'btn-gradient-1');
            }
        }

        function toggleActionMobile() {
            var chk = document.getElementById('readConfirmMobile');
            var btn = document.getElementById('agreeBtn');
            if (chk.checked) {
                btn.classList.remove('disabled', 'btn-dark');
                btn.classList.add('btn-primary', 'btn-gradient-1');
            } else {
                btn.classList.add('disabled', 'btn-dark');
                btn.classList.remove('btn-primary', 'btn-gradient-1');
            }
        }
    </script>
@endsection
