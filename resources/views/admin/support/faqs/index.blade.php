@extends('layouts.app')

@push('css')
    @vite(['resources/sass/pages/admin-support-center.scss'])
@endpush

@section('title', __('admin.faq_management'))

@section('content')
    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1 d-flex align-items-center">
                        <span class="material-icons-outlined text-success me-2">help_outline</span>
                        FAQ Management
                    </h4>
                    <p class="text-secondary mb-0">Manage frequently asked questions for your support center</p>
                </div>
                <button class="btn btn-success rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center" 
                        type="button" 
                        data-bs-toggle="offcanvas" 
                        data-bs-target="#faqOffcanvas">
                    <span class="material-icons-outlined me-2">add</span> Add New FAQ
                </button>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="stat-label">Total FAQs</h6>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <span class="material-icons-outlined fs-4">help_outline</span>
                        </div>
                    </div>
                    <div class="stat-value text-success">{{ number_format($faqs->count()) }}</div>
                    <div class="d-flex align-items-center mt-2 small text-success">
                        <span class="material-icons-outlined me-1" style="font-size: 16px;">trending_up</span>
                        <span>10% increase</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="stat-label">Active</h6>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <span class="material-icons-outlined fs-4">check_circle</span>
                        </div>
                    </div>
                    <div class="stat-value text-primary">{{ number_format($faqs->where('is_active', true)->count()) }}</div>
                    <div class="d-flex align-items-center mt-2 small text-primary">
                        <span class="material-icons-outlined me-1" style="font-size: 16px;">trending_up</span>
                        <span>15% increase</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="stat-label">Inactive</h6>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <span class="material-icons-outlined fs-4">cancel</span>
                        </div>
                    </div>
                    <div class="stat-value text-warning">{{ number_format($faqs->where('is_active', false)->count()) }}</div>
                    <div class="d-flex align-items-center mt-2 small text-secondary">
                        <span class="material-icons-outlined me-1" style="font-size: 16px;">remove</span>
                        <span>No change</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="admin-stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="stat-label">Categories</h6>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <span class="material-icons-outlined fs-4">category</span>
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

            {{-- FAQ List --}}
            <div class="col-12 col-xl-8">
                <div class="admin-table-card">
                    {{-- Header with Filters --}}
                    <div class="card-header bg-white border-bottom p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="fw-bold mb-0 d-flex align-items-center">
                                    <span class="material-icons-outlined text-success me-2">list_alt</span>
                                    FAQ List
                                </h4>
                            </div>
                            <span class="badge bg-light text-secondary border">{{ count($faqs) }} FAQs</span>
                        </div>

                        <form action="{{ route('admin.support.faqs.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-3 align-items-sm-center">
                            <div class="position-relative flex-grow-1">
                                <input type="text" name="search" class="form-control ps-5" 
                                    placeholder="Search FAQs by question or answer..." 
                                    value="{{ request('search') }}">
                                <span class="material-icons-outlined position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">search</span>
                            </div>
                            <div style="min-width: 160px;">
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    <option value="billing" {{ request('category') == 'billing' ? 'selected' : '' }}>Billing</option>
                                    <option value="errors" {{ request('category') == 'errors' ? 'selected' : '' }}>Errors</option>
                                    <option value="connectivity" {{ request('category') == 'connectivity' ? 'selected' : '' }}>Connectivity</option>
                                    <option value="account" {{ request('category') == 'account' ? 'selected' : '' }}>Account & Login</option>
                                    <option value="features" {{ request('category') == 'features' ? 'selected' : '' }}>Features</option>
                                </select>
                            </div>
                            <div style="min-width: 140px;">
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success px-4">
                                    <span class="material-icons-outlined me-1" style="font-size: 18px;">filter_list</span>
                                    Filter
                                </button>
                                @if(request('search') || request('category') || request('status'))
                                    <a href="{{ route('admin.support.faqs.index') }}" 
                                       class="btn btn-light border" 
                                       data-bs-toggle="tooltip" 
                                       title="Clear Filters">
                                        <span class="material-icons-outlined">close</span>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- FAQ Items --}}
                    <div class="card-body p-0">
                        @forelse($faqs as $faq)
                            <div class="border-bottom p-4 transition-200 position-relative">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <h6 class="fw-bold mb-0">{{ $faq->question }}</h6>
                                            @if($faq->is_active)
                                                <span class="badge bg-success bg-opacity-10 text-success badge-soft-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary badge-soft-secondary">Inactive</span>
                                            @endif
                                        </div>
                                        <p class="text-secondary small mb-2">{{ Str::limit($faq->answer, 120) }}</p>
                                        <div class="d-flex align-items-center gap-3 small text-secondary">
                                            <span class="d-flex align-items-center">
                                                <span class="material-icons-outlined me-1" style="font-size: 16px;">category</span>
                                                {{ $faq->category }}
                                            </span>
                                            <span class="d-flex align-items-center">
                                                <span class="material-icons-outlined me-1" style="font-size: 16px;">schedule</span>
                                                {{ $faq->updated_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-light border" 
                                                data-bs-toggle="tooltip" 
                                                title="Edit FAQ">
                                            <span class="material-icons-outlined" style="font-size: 18px;">edit</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light border text-danger" 
                                                data-bs-toggle="tooltip" 
                                                title="Delete FAQ">
                                            <span class="material-icons-outlined" style="font-size: 18px;">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-center">
                                <div class="mb-3">
                                    <span class="material-icons-outlined text-secondary" style="font-size: 64px; opacity: 0.5;">help_outline</span>
                                </div>
                                <h5 class="fw-bold text-secondary mb-2">No FAQs Found</h5>
                                <p class="text-secondary mb-4">Get started by adding frequently asked questions to help your users.</p>
                                <button class="btn btn-success rounded-pill px-4" 
                                        type="button" 
                                        data-bs-toggle="offcanvas" 
                                        data-bs-target="#faqOffcanvas">
                                    <span class="material-icons-outlined me-2">add</span> Add First FAQ
                                </button>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if(method_exists($faqs, 'hasPages') && $faqs->hasPages())
                        <div class="card-footer bg-white border-top p-4">
                            {{ $faqs->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-12 col-xl-4">

                {{-- Category Overview --}}
                <div class="card rounded-3 border shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="fw-bold mb-0 d-flex align-items-center">
                            <span class="material-icons-outlined text-success me-2">category</span>
                            FAQ Categories
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-3">
                            @php
                                $categories = [
                                    ['label' => 'Billing', 'icon' => 'payments', 'count' => 24, 'color' => 'primary'],
                                    ['label' => 'Errors', 'icon' => 'bug_report', 'count' => 18, 'color' => 'danger'],
                                    ['label' => 'Connectivity', 'icon' => 'wifi', 'count' => 15, 'color' => 'info'],
                                    ['label' => 'Account & Login', 'icon' => 'person', 'count' => 12, 'color' => 'warning'],
                                    ['label' => 'Features & Usage', 'icon' => 'settings', 'count' => 20, 'color' => 'success'],
                                ];
                            @endphp
                            @foreach($categories as $cat)
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 transition-200 hover-bg-white">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-{{ $cat['color'] }} bg-opacity-10 text-{{ $cat['color'] }} p-2 rounded-3">
                                            <span class="material-icons-outlined">{{ $cat['icon'] }}</span>
                                        </div>
                                        <span class="fw-semibold">{{ $cat['label'] }}</span>
                                    </div>
                                    <span class="badge bg-{{ $cat['color'] }} bg-opacity-10 text-{{ $cat['color'] }} rounded-pill px-3">
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
                            <button type="button" 
                                    class="btn btn-light border text-start d-flex align-items-center gap-3" 
                                    data-bs-toggle="offcanvas" 
                                    data-bs-target="#faqOffcanvas">
                                <span class="material-icons-outlined text-success">add</span>
                                <div>
                                    <div class="fw-semibold">New FAQ</div>
                                    <small class="text-secondary">Create new question</small>
                                </div>
                            </button>
                            <button type="button" class="btn btn-light border text-start d-flex align-items-center gap-3">
                                <span class="material-icons-outlined text-primary">upload</span>
                                <div>
                                    <div class="fw-semibold">Import FAQs</div>
                                    <small class="text-secondary">Upload CSV file</small>
                                </div>
                            </button>
                            <button type="button" class="btn btn-light border text-start d-flex align-items-center gap-3">
                                <span class="material-icons-outlined text-info">download</span>
                                <div>
                                    <div class="fw-semibold">Export FAQs</div>
                                    <small class="text-secondary">Download as CSV</small>
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
                                    ['icon' => 'edit', 'text' => 'FAQ "Billing" updated', 'time' => '1 hr ago', 'color' => 'primary'],
                                    ['icon' => 'check_circle', 'text' => 'FAQ "Errors" published', 'time' => '2 hrs ago', 'color' => 'success'],
                                    ['icon' => 'delete', 'text' => 'FAQ "Old FAQ" deleted', 'time' => '3 hrs ago', 'color' => 'danger'],
                                    ['icon' => 'add', 'text' => 'FAQ "New Feature" created', 'time' => '5 hrs ago', 'color' => 'success'],
                                ];
                            @endphp
                            @foreach($activities as $activity)
                                <div class="d-flex align-items-start gap-3 p-2 rounded-3 transition-200 hover-bg-light">
                                    <div class="bg-{{ $activity['color'] }} bg-opacity-10 text-{{ $activity['color'] }} p-2 rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <span class="material-icons-outlined" style="font-size: 16px;">{{ $activity['icon'] }}</span>
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
    @vite(['resources/js/pages/admin/faq.js'])
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush