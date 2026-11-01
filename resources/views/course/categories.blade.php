@extends('layouts.master')

@section('title', 'Course Categories - Shwe Learn')

@section('content')
    <!-- Custom CSS for Equal Height Cards -->
    <style>
        /* Force equal height grid items */
        ul.vt-products.equal-height-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        ul.vt-products.equal-height-grid > li.product {
            display: flex;
            float: none; /* Override default float styling if present */
        }

        .course-style-one-item.equal-card {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;
        }

        .course-style-one-item.equal-card .info {
            display: flex;
            flex-direction: column;
            flex-grow: 1; /* Pushes content to fill available vertical space */
        }

        .course-style-one-item.equal-card .info p {
            flex-grow: 1; /* Pushes meta & bottom button flush to the bottom */
        }

        .course-style-one-item.equal-card .thumb img {
            width: 100%;
            height: 220px; /* Standardize image height across all cards */
            object-fit: cover; /* Maintain aspect ratio without stretching */
        }

        .dropdown-menu .dropdown-item.active i {
            color: #ffffff !important;
        }
    </style>

    <!-- Start Breadcrumb 
    ============================================= -->
    <div class="breadcrumb-area text-center bg-gray-gradient-secondary">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1>Course Categories</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="{{ url('/') }}"><i class="fas fa-home"></i> Home</a></li>
                            <li class="active">Categories</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- Start Course Filter 
    ============================================= -->
    <div class="course-filter-area default-padding">
        <div class="container">
            <div class="course-listing-contentes style-two mb-4">
                <div class="row align-items-center justify-content-between">
                    <!-- Title/Header -->
                    <div class="col-auto">
                        <h4 class="m-0">All Categories</h4>
                    </div>

                    <!-- Compact Filter Dropdown Aligned to the Right -->
                    <div class="col-auto text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-theme circle btn-sm dropdown-toggle w-auto px-3" type="button" id="courseFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="white-space: nowrap;">
                                <i class="fas fa-filter me-1"></i> Filter: {{ request('category') ? ucfirst(request('category')) : 'All Categories' }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="courseFilterDropdown" style="max-height: 300px; overflow-y: auto;">
                                <li>
                                    <a class="dropdown-item {{ !request('category') ? 'active' : '' }}" href="{{ url()->current() }}">
                                        All Categories
                                    </a>
                                </li>
                                {{-- Loop over $allCategories so options are never removed during filtering --}}
                                @if(isset($allCategories) && $allCategories->count() > 0)
                                    <li><hr class="dropdown-divider"></li>
                                    @foreach($allCategories as $cat)
                                        <li>
                                            <a class="dropdown-item {{ request('category') == ($cat->slug ?? $cat->id) ? 'active' : '' }}" 
                                            href="{{ url()->current() }}?category={{ $cat->slug ?? $cat->id }}">
                                                <i class="fas fa-folder me-2 text-primary"></i> {{ $cat->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Start Tab Content -->
                    <div class="tab-content tab-content-info" id="shop-tabContent">

                        <!-- Start Category Grid View -->
                        <div class="tab-pane fade show active" id="grid-tab" role="tabpanel" aria-labelledby="grid-tab-control">
                            {{-- Equal Height + Centered Grid Container --}}
                            <ul class="vt-products columns-3 equal-height-grid">
                                @forelse($categories as $category)
                                    @php
                                        $categoryTargetUrl = url('/courses?category=' . ($category->slug ?? $category->id));
                                    @endphp
                                    <li class="product">
                                        <div class="course-style-one-item hover-less equal-card">
                                            <div class="thumb">
                                                <a href="{{ $categoryTargetUrl }}">
                                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                                                </a>
                                            </div>
                                            <div class="info">
                                                <h4>
                                                    <a href="{{ $categoryTargetUrl }}">
                                                        {{ $category->name }}
                                                    </a>
                                                </h4>
                                                
                                                <p class="text-muted small mb-2">
                                                    {{ Str::limit($category->description ?? 'No description available for this category.', 90) }}
                                                </p>

                                                <div class="course-meta">
                                                    <ul>
                                                        <li>
                                                            <i class="fas fa-file-alt"></i> {{ $category->courses_count ?? $category->courses->count() ?? 0 }} {{ Str::plural('Course', $category->courses_count ?? 0) }}
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="bottom-meta">
                                                    <a href="{{ $categoryTargetUrl }}">
                                                        Explore Category <i class="fas fa-long-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No course categories found at the moment.</p>
                                    </div>
                                @endforelse
                            </ul>
                        </div>
                        <!-- End Category Grid View -->

                    </div>
                    <!-- End Tab Content -->

                    {{-- Conditional Pagination --}}
                    @if(isset($categories) && method_exists($categories, 'hasPages') && $categories->hasPages())
                        <nav class="woocommerce-pagination mt-60 text-center">
                            {{ $categories->withQueryString()->links() }}
                        </nav>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- End Course Filter -->
@endsection