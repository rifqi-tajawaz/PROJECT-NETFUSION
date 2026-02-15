@extends('layouts.app')

@section('title', 'Add Documentation')

@section('content')
    {{-- Hero Section --}}
    <div class="card admin-header-card shadow mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1 opacity-75 small">
                            <li class="breadcrumb-item"><a href="{{ route('mikrotik-suite.dashboard') }}"
                                    class="text-secondary text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.support.documentation.index') }}"
                                    class="text-secondary text-decoration-none">Documentation</a></li>
                            <li class="breadcrumb-item active text-secondary" aria-current="page">Create</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 text-dark">Add Documentation</h4>
                    <p class="mb-0 text-secondary opacity-75">Create rich content guides and articles for users.</p>
                </div>
                <div class="d-none d-md-block">
                    <span class="material-icons-outlined text-primary opacity-25" style="font-size: 3.5rem;">post_add</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-0">
        <form action="{{ route('admin.support.documentation.store') }}" method="POST" id="docForm"
            enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                {{-- Left Column: Editor --}}
                <div class="col-lg-9">
                    <div class="admin-table-card shadow h-100 overflow-hidden d-flex flex-column">
                        {{-- Card Header: Title & Toolbar --}}
                        <div class="card-header px-4 py-3">
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                                        <i class="bx bx-edit fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">Content Editor</h6>
                                        <p class="mb-0 text-secondary small">Write using Markdown syntax</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Pro Toolbar --}}
                            <div class="d-flex flex-nowrap gap-2 bg-light rounded-3 p-2 border overflow-auto"
                                style="scrollbar-width: thin;">
                                {{-- Formatting --}}
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertMarkdown('**', '**')" title="Bold (Ctrl+B)"><i
                                            class="bx bx-bold"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertMarkdown('*', '*')" title="Italic (Ctrl+I)"><i
                                            class="bx bx-italic"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertMarkdown('~~', '~~')" title="Strikethrough"><i
                                            class="bx bx-strikethrough"></i></button>
                                </div>

                                <div class="vr my-1 text-secondary opacity-25"></div>

                                {{-- Headings --}}
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertMarkdown('# ', '')" title="Heading 1"><span
                                            class="small fw-bold">H1</span></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertMarkdown('## ', '')" title="Heading 2"><span
                                            class="small fw-bold">H2</span></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertMarkdown('### ', '')" title="Heading 3"><span
                                            class="small fw-bold">H3</span></button>
                                </div>

                                <div class="vr my-1 text-secondary opacity-25"></div>

                                {{-- Lists & Quotes --}}
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertMarkdown('- ', '')" title="Bullet List"><i
                                            class="bx bx-list-ul"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertMarkdown('1. ', '')" title="Numbered List"><i
                                            class="bx bx-list-ol"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertMarkdown('> ', '')" title="Blockquote"><i
                                            class="bx bxs-quote-left"></i></button>
                                </div>

                                <div class="vr my-1 text-secondary opacity-25"></div>

                                {{-- Insertions --}}
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertMarkdown('[', '](url)')" title="Link"><i
                                            class="bx bx-link"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="document.getElementById('imageUpload').click()" title="Upload Image"><i
                                            class="bx bx-image-add"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertTable()" title="Insert Table"><i class="bx bx-table"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertMarkdown('---', '')" title="Horizontal Rule"><i
                                            class="bx bx-minus"></i></button>
                                </div>

                                <div class="vr my-1 text-secondary opacity-25"></div>

                                {{-- Code --}}
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertMarkdown('`', '`')" title="Inline Code"><i
                                            class="bx bx-code"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0"
                                        onclick="insertMarkdown('```\n', '\n```')" title="Code Block"><i
                                            class="bx bx-code-block"></i></button>
                                </div>

                                <div class="vr my-1 text-secondary opacity-25"></div>

                                {{-- Alerts --}}
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-info border-0"
                                        onclick="insertMarkdown('> [!NOTE]\n> ', '')" title="Note Alert"><i
                                            class="bx bx-info-circle"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-warning border-0"
                                        onclick="insertMarkdown('> [!WARNING]\n> ', '')" title="Warning Alert"><i
                                            class="bx bx-error"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-success border-0"
                                        onclick="insertMarkdown('> [!TIP]\n> ', '')" title="Tip Alert"><i
                                            class="bx bx-bulb"></i></button>
                                </div>
                            </div>

                            {{-- Hidden Image Input --}}
                            <input type="file" id="imageUpload" class="d-none" accept="image/*">
                        </div>

                        {{-- Split View Editor --}}
                        <div class="card-body p-0">
                            <div class="row g-0" style="min-height: 70vh;">
                                {{-- Input Pane --}}
                                <div class="col-md-6 border-end position-relative">
                                    <textarea class="form-control border-0 h-100 p-4 font-monospace bg-white" name="content"
                                        id="markdown-input" style="resize: none; outline: none; font-size: 0.9rem;"
                                        placeholder="{{ __('admin.doc_content_placeholder') }}" required></textarea>
                                </div>

                                {{-- Preview Pane --}}
                                <div class="col-md-6 bg-light bg-opacity-50">
                                    <div class="h-100 p-4 markdown-preview overflow-auto" id="markdown-preview">
                                        <div
                                            class="d-flex flex-column align-items-center justify-content-center h-100 text-muted opacity-50">
                                            <i class="bx bx-show fs-1 mb-2"></i>
                                            <p>Live preview updates as you type</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Settings --}}
                <div class="col-lg-3">
                    <div class="sticky-top" style="top: 90px; z-index: 10;">
                        {{-- Actions Card --}}
                        <div class="admin-table-card shadow mb-4">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3 text-uppercase small text-secondary">Publishing</h6>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary rounded-3 fw-bold py-2 shadow-sm">
                                        <i class="bx bx-save me-2"></i> {{ __('admin.doc_save') }}
                                    </button>
                                    <a href="{{ route('admin.support.documentation.index') }}"
                                        class="btn btn-light rounded-3 fw-bold py-2 border">
                                        {{ __('admin.cancel') }}
                                    </a>
                                </div>
                                <hr class="my-4 opactiy-10">
                                <div class="form-check form-switch d-flex justify-content-between px-0 mb-0">
                                    <label class="form-check-label fw-medium"
                                        for="is_published">{{ __('admin.doc_publish_now') }}</label>
                                    <input class="form-check-input ms-0" type="checkbox" id="is_published"
                                        name="is_published" checked style="float: right;">
                                </div>
                            </div>
                        </div>

                        {{-- Page Meta Card --}}
                        <div class="admin-table-card shadow">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3 text-uppercase small text-secondary">{{ __('admin.ticket_info') }}
                                </h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small">{{ __('admin.doc_title') }}</label>
                                    <input type="text" class="form-control bg-light border-0" name="title" id="title-input"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small">{{ __('admin.doc_slug') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0 text-secondary">/</span>
                                        <input type="text" class="form-control bg-light border-0" name="slug"
                                            id="slug-input" placeholder="auto-generated">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small">{{ __('admin.doc_category') }}</label>
                                    <select class="form-select bg-light border-0" name="category_id" required>
                                        <option value="" disabled selected>Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label fw-bold small">{{ __('admin.doc_parent') }}</label>
                                    <select class="form-select bg-light border-0" name="parent_id">
                                        <option value="">{{ __('admin.doc_parent_placeholder') }}</option>
                                        @foreach($parents as $parent)
                                            <option value="{{ $parent->id }}" data-category="{{ $parent->category_id }}">
                                                {{ $parent->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text small text-muted mt-1">Select a page to place this inside it
                                        (creates
                                        a dropdown).</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
{{-- Simple Markdown Parser (Marked.js CDN) --}}
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
@endpush

@push('styles')
    @vite(['resources/sass/pages/admin-support-center.scss'])
@endpush

@push('scripts')
    @vite(['resources/js/pages/admin/documentation-editor.js'])
@endpush