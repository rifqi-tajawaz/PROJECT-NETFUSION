@extends('pages.documentation.layout')

@section('title', $pageTitle)

@section('dynamic_content')
    <div class="documentation-content animate-fade-in">
        {{-- Title Header --}}
        <div class="mb-5 pb-4 border-bottom">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                    <span class="material-icons-outlined">article</span>
                </div>
                <div>
                    <h1 class="fw-bold mb-0 display-6 text-dark">{{ $pageTitle }}</h1>
                </div>
            </div>

            <div class="d-flex gap-3 align-items-center text-secondary small">
                <div class="d-flex align-items-center gap-1">
                    <span class="material-icons-outlined fs-6">check_circle</span>
                    <span>Official Guide</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="material-icons-outlined fs-6">update</span>
                    <span>Last updated recently</span>
                </div>
            </div>
        </div>

        {{-- Content Body --}}
        <div class="content-body">
            {!! Str::markdown($pageContent) !!}
        </div>

        {{-- Feedback Section --}}
        <div class="mt-5 pt-5 border-top">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h6 class="fw-bold mb-1">Was this page helpful?</h6>
                    <p class="text-secondary small mb-0">Your feedback helps us improve.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-success btn-sm d-flex align-items-center gap-1">
                        <span class="material-icons-outlined fs-6">thumb_up</span> Yes
                    </button>
                    <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1">
                        <span class="material-icons-outlined fs-6">thumb_down</span> No
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        /* Scoped Markdown Styles for Dynamic Content */
        .documentation-content {
            color: var(--bs-heading-color);
            line-height: 1.7;
        }

        .content-body h1,
        .content-body h2,
        .content-body h3,
        .content-body h4 {
            color: var(--bs-heading-color);
            font-weight: 700;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .content-body h1 {
            font-size: 2.25rem;
        }

        .content-body h2 {
            font-size: 1.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--bs-border-color);
        }

        .content-body h3 {
            font-size: 1.5rem;
        }

        .content-body h4 {
            font-size: 1.25rem;
        }

        .content-body p {
            margin-bottom: 1.5rem;
            color: var(--bs-body-color);
            font-size: 1.05rem;
        }

        .content-body ul,
        .content-body ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .content-body li {
            margin-bottom: 0.5rem;
        }

        .content-body code {
            background-color: rgba(var(--bs-primary-rgb), 0.05);
            color: var(--bs-primary);
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.9em;
            font-family: 'Fira Code', monospace;
        }

        .content-body pre {
            background: #1e1e2e;
            color: #cdd6f4;
            padding: 1.5rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            overflow-x: auto;
        }

        .content-body pre code {
            background: transparent;
            color: inherit;
            padding: 0;
        }

        .content-body blockquote {
            border-left: 4px solid var(--bs-primary);
            background: rgba(var(--bs-primary-rgb), 0.05);
            padding: 1rem 1.5rem;
            border-radius: 0 0.5rem 0.5rem 0;
            margin-bottom: 1.5rem;
            font-style: italic;
        }

        .content-body img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin: 1.5rem 0;
        }

        .content-body table {
            width: 100%;
            margin-bottom: 1.5rem;
            border-collapse: collapse;
        }

        .content-body th,
        .content-body td {
            padding: 0.75rem;
            border: 1px solid var(--bs-border-color);
        }

        .content-body th {
            background-color: rgba(var(--bs-primary-rgb), 0.05);
            font-weight: 600;
        }
    </style>
@endpush