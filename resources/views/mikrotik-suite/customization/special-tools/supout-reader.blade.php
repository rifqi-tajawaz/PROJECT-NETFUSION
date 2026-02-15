@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
            <h2 class="display-6 fw-bold mb-2 text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                <i class="bi bi-file-earmark-binary me-2 text-warning"></i> Supout Reader
            </h2>
            <p class="lead opacity-75 mb-0" style="max-width: 700px;">
                Analyze support output files (`supout.rif`) to diagnose RouterOS issues.
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="glass-card p-5 text-center">
                    <i class="bi bi-cloud-upload display-1 text-white-50 mb-4"></i>
                    <h3 class="text-white mb-3">Upload to MikroTik</h3>
                    <p class="text-white-50 mb-4">
                        The <code>.rif</code> file format is proprietary and encrypted. For accurate analysis, please use
                        the official MikroTik viewer.
                    </p>

                    <div class="d-grid gap-3 d-sm-flex justify-content-center">
                        <a href="https://mikrotik.com/client/supout" target="_blank"
                            class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-box-arrow-up-right me-2"></i> Open Official Viewer
                        </a>

                        <button class="btn btn-outline-light rounded-pill px-4" type="button" data-bs-toggle="collapse"
                            data-bs-target="#localTip">
                            How to create supout.rif?
                        </button>
                    </div>

                    <div class="collapse mt-4" id="localTip">
                        <div class="card bg-dark bg-opacity-50 border-secondary text-start p-3 rounded-3">
                            <h6 class="text-white">Via Terminal:</h6>
                            <code class="text-success">/system sup-output</code>
                            <p class="text-white-50 mt-2 small mb-0">The file will be created in the "Files" menu. Download
                                it and upload to the official tool.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
