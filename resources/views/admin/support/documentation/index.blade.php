@extends('layouts.app')

@push('css')
    @vite(['resources/sass/pages/admin-support-center.scss'])
@endpush

@section('title', 'Documentation Management')

@section('content')
    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1 d-flex align-items-center">
                        <span class="material-icons-outlined text-primary me-2">description</span>
                        Documentation Management
                    </h4>
                    <p class="text-secondary mb-0">Manage knowledge base and documentation pages</p>
                </div>
                <a href="{{ route('admin.support.documentation.create') }}"
                    class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center">
                    <span class="material-icons-outlined me-2">add</span> Add Page
                </a>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="stat-label">Total Pages</h6>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <span class="material-icons-outlined fs-4">description</span>
                        </div>
                    </div>
                    <div class="stat-value text-primary">{{ number_format($pages->total() ?? 0) }}</div>
                    <div class="d-flex align-items-center mt-2 small text-primary">
                        <span class="material-icons-outlined me-1" style="font-size: 16px;">trending_up</span>
                        <span>8% increase</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="stat-label">Published</h6>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <span class="material-icons-outlined fs-4">publish</span>
                        </div>
                    </div>
                    <div class="stat-value text-success">{{ number_format($pages->where('is_published', true)->count()) }}
                    </div>
                    <div class="d-flex align-items-center mt-2 small text-success">
                        <span class="material-icons-outlined me-1" style="font-size: 16px;">trending_up</span>
                        <span>12% increase</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="stat-label">Drafts</h6>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <span class="material-icons-outlined fs-4">edit_note</span>
                        </div>
                    </div>
                    <div class="stat-value text-warning">{{ number_format($pages->where('is_published', false)->count()) }}
                    </div>
                    <div class="d-flex align-items-center mt-2 small text-secondary">
                        <span class="material-icons-outlined me-1" style="font-size: 16px;">trending_down</span>
                        <span>3% decrease</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="stat-label">Categories</h6>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <span class="material-icons-outlined fs-4">folder</span>
                        </div>
                    </div>
                    <div class="stat-value text-info">5</div>
                    <div class="d-flex align-items-center mt-2 small text-secondary">
                        <span class="material-icons-outlined me-1" style="font-size: 16px;">remove</span>
                        <span>Stable</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Row --}}
        <div class="row g-4">

            {{-- Documentation List --}}
            <div class="col-12 col-xl-8">
                <div class="admin-table-card">
                    {{-- Header with Filters --}}
                    <div class="card-header bg-white border-bottom p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="fw-bold mb-0 d-flex align-items-center">
                                    <span class="material-icons-outlined text-primary me-2">list_alt</span>
                                    Documentation Pages
                                </h5>
                            </div>
                            <span class="badge bg-light text-secondary border">{{ count($pages) }} pages</span>
                        </div>

                        <form action="{{ route('admin.support.documentation.index') }}" method="GET"
                            class="d-flex flex-column flex-sm-row gap-3 align-items-sm-center">
                            <div class="position-relative flex-grow-1">
                                <input type="text" name="search" class="form-control ps-5"
                                    placeholder="Search documentation by title..." value="{{ request('search') }}">
                                <span
                                    class="material-icons-outlined position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">search</span>
                            </div>
                            <div style="min-width: 160px;">
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    <option value="getting-started" {{ request('category') == 'getting-started' ? 'selected' : '' }}>Getting Started</option>
                                    <option value="network" {{ request('category') == 'network' ? 'selected' : '' }}>Network
                                    </option>
                                    <option value="security" {{ request('category') == 'security' ? 'selected' : '' }}>
                                        Security</option>
                                    <option value="api" {{ request('category') == 'api' ? 'selected' : '' }}>API</option>
                                </select>
                            </div>
                            <div style="min-width: 140px;">
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                                        Published</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <span class="material-icons-outlined me-1" style="font-size: 18px;">filter_list</span>
                                    Filter
                                </button>
                                @if(request('search') || request('category') || request('status'))
                                    <a href="{{ route('admin.support.documentation.index') }}" class="btn btn-light border"
                                        data-bs-toggle="tooltip" title="Clear Filters">
                                        <span class="material-icons-outlined">close</span>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Pages List --}}
                    <div class="card-body p-0">
                        @forelse($pages as $page)
                            <div class="border-bottom p-4 transition-200 position-relative">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <h6 class="fw-bold mb-0">{{ $page->title }}</h6>
                                            @if($page->is_published)
                                                <span class="badge bg-success bg-opacity-10 text-success">Published</span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning">Draft</span>
                                            @endif
                                        </div>
                                        <p class="text-secondary small mb-2">
                                            {{ Str::limit($page->description ?? $page->content, 120) }}</p>
                                        <div class="d-flex align-items-center gap-3 small text-secondary">
                                            <span class="d-flex align-items-center">
                                                <span class="material-icons-outlined me-1"
                                                    style="font-size: 16px;">category</span>
                                                {{ $page->category }}
                                            </span>
                                            <span class="d-flex align-items-center">
                                                <span class="material-icons-outlined me-1"
                                                    style="font-size: 16px;">schedule</span>
                                                {{ $page->updated_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.support.documentation.edit', $page->id) }}"
                                            class="btn btn-sm btn-light border" data-bs-toggle="tooltip" title="Edit Page">
                                            <span class="material-icons-outlined" style="font-size: 18px;">edit</span>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-light border text-danger"
                                            data-bs-toggle="tooltip" title="Delete Page">
                                            <span class="material-icons-outlined" style="font-size: 18px;">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-center">
                                <div class="mb-3">
                                    <span class="material-icons-outlined text-secondary"
                                        style="font-size: 64px; opacity: 0.5;">description</span>
                                </div>
                                <h5 class="fw-bold text-secondary mb-2">No Documentation Found</h5>
                                <p class="text-secondary mb-4">Create your first documentation page to help users navigate the
                                    system.</p>
                                <a href="{{ route('admin.support.documentation.create') }}"
                                    class="btn btn-primary rounded-pill px-4">
                                    <span class="material-icons-outlined me-2">add</span> Add First Page
                                </a>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if(method_exists($pages, 'hasPages') && $pages->hasPages())
                        <div class="card-footer bg-white border-top p-4">
                            {{ $pages->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-12 col-xl-4">

                {{-- Categories Overview --}}
                <div class="card rounded-3 border shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="fw-bold mb-0 d-flex align-items-center">
                            <span class="material-icons-outlined text-primary me-2">category</span>
                            Categories
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-3">
                            @php
                                $categories = [
                                    ['label' => 'Getting Started', 'icon' => 'rocket_launch', 'count' => 12, 'color' => 'success'],
                                    ['label' => 'Network', 'icon' => 'wifi', 'count' => 8, 'color' => 'info'],
                                    ['label' => 'Security', 'icon' => 'security', 'count' => 15, 'color' => 'danger'],
                                    ['label' => 'API', 'icon' => 'code', 'count' => 10, 'color' => 'warning'],
                                    ['label' => 'Billing', 'icon' => 'payments', 'count' => 6, 'color' => 'primary'],
                                    ['label' => 'Troubleshooting', 'icon' => 'build', 'count' => 20, 'color' => 'secondary'],
                                ];
                            @endphp
                            @foreach($categories as $cat)
                                <div
                                    class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 transition-200 hover-bg-white">
                                    <div class="d-flex align-items-center gap-3">
                                        <div
                                            class="bg-{{ $cat['color'] }} bg-opacity-10 text-{{ $cat['color'] }} p-2 rounded-3">
                                            <span class="material-icons-outlined">{{ $cat['icon'] }}</span>
                                        </div>
                                        <span class="fw-semibold">{{ $cat['label'] }}</span>
                                    </div>
                                    <span
                                        class="badge bg-{{ $cat['color'] }} bg-opacity-10 text-{{ $cat['color'] }} rounded-pill px-3">
                                        {{ $cat['count'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="card rounded-3 border shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="fw-bold mb-0 d-flex align-items-center">
                            <span class="material-icons-outlined text-success me-2">flash_on</span>
                            Quick Actions
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.support.documentation.create') }}"
                                class="btn btn-light border text-start d-flex align-items-center gap-3">
                                <span class="material-icons-outlined text-primary">add</span>
                                <div>
                                    <div class="fw-semibold">New Page</div>
                                    <small class="text-secondary">Create new doc</small>
                                </div>
                            </a>
                            <button type="button" class="btn btn-light border text-start d-flex align-items-center gap-3">
                                <span class="material-icons-outlined text-success">upload</span>
                                <div>
                                    <div class="fw-semibold">Import</div>
                                    <small class="text-secondary">Import markdown</small>
                                </div>
                            </button>
                            <button type="button" class="btn btn-light border text-start d-flex align-items-center gap-3">
                                <span class="material-icons-outlined text-info">download</span>
                                <div>
                                    <div class="fw-semibold">Export</div>
                                    <small class="text-secondary">Export to PDF</small>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="card rounded-3 border shadow-sm">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="fw-bold mb-0 d-flex align-items-center">
                            <span class="material-icons-outlined text-info me-2">history</span>
                            Recent Activity
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex flex-column gap-2">
                            @php
                                $activities = [
                                    ['icon' => 'edit', 'text' => 'Page "Getting Started" updated', 'time' => '1 hr ago', 'color' => 'primary'],
                                    ['icon' => 'publish', 'text' => 'Page "API Reference" published', 'time' => '2 hrs ago', 'color' => 'success'],
                                    ['icon' => 'delete', 'text' => 'Page "Old Guide" deleted', 'time' => '3 hrs ago', 'color' => 'danger'],
                                    ['icon' => 'add', 'text' => 'Page "New Feature" created', 'time' => '5 hrs ago', 'color' => 'success'],
                                ];
                            @endphp
                            @foreach($activities as $activity)
                                <div class="d-flex align-items-start gap-3 p-2 rounded-3 transition-200 hover-bg-light">
                                    <div class="bg-{{ $activity['color'] }} bg-opacity-10 text-{{ $activity['color'] }} p-2 rounded-circle"
                                        style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <span class="material-icons-outlined"
                                            style="font-size: 16px;">{{ $activity['icon'] }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small fw-semibold">{{ $activity['text'] }}</div>
                                        <div class="small text-secondary">{{ $activity['time'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush