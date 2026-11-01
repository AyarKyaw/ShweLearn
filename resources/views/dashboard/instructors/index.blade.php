@extends('dashboard.layouts.master')

@section('title', 'Teachers - Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="teachers-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="teachers-title">Teachers</h2>
                            <p class="m-card__subtitle">Manage teacher profiles and avatars</p>
                        </div>
                    </header>

                    <!-- Alerts for session messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-data__tool">
                        <div class="table-data__tool-left d-flex align-items-center gap-3">
                            <form action="{{ route('admin.instructors.index') }}" method="GET" class="d-flex gap-2">
                                <div class="select-wrapper">
                                    <select class="form-select" name="filter" onchange="this.form.submit()">
                                        <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All Teachers</option>
                                        <option value="latest" {{ request('filter') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                    </select>
                                </div>
                            </form>

                            <!-- Top Previous / Next Navigation Controls -->
                            @if(method_exists($instructors, 'hasPages') && $instructors->hasPages())
                                <div class="btn-group" role="group" aria-label="Page navigation">
                                    @if($instructors->onFirstPage())
                                        <button class="btn btn-outline-secondary btn-sm disabled" disabled>
                                            <i class="fa-solid fa-chevron-left me-1"></i> Previous
                                        </button>
                                    @else
                                        <a href="{{ $instructors->appends(request()->query())->previousPageUrl() }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fa-solid fa-chevron-left me-1"></i> Previous
                                        </a>
                                    @endif

                                    @if($instructors->hasMorePages())
                                        <a href="{{ $instructors->appends(request()->query())->nextPageUrl() }}" class="btn btn-outline-primary btn-sm">
                                            Next <i class="fa-solid fa-chevron-right ms-1"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm disabled" disabled>
                                            Next <i class="fa-solid fa-chevron-right ms-1"></i>
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="table-data__tool-right d-flex gap-2">
                            <a href="{{ route('admin.instructors.create') }}" class="au-btn au-btn--green au-btn--small text-decoration-none d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add Teacher
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th style="width:24px;">
                                        <label class="au-checkbox">
                                            <input type="checkbox" aria-label="Select all"><span class="au-checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Bio</th>
                                    <th>Created Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($instructors as $instructor)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox" name="selected_instructors[]" value="{{ $instructor->id }}">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            @if($instructor->image)
                                                <img src="{{ asset('storage/' . $instructor->image) }}" 
                                                     alt="{{ $instructor->name }}" 
                                                     class="rounded-circle" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fa-solid fa-user"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $instructor->name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted" title="{{ $instructor->bio }}">
                                                {{ Str::limit($instructor->bio ?? '—', 50) }}
                                            </span>
                                        </td>
                                        <td>{{ $instructor->created_at ? $instructor->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <div class="table-data-feature justify-content-end gap-1">
                                                <a href="{{ route('admin.instructors.edit', $instructor->id) }}" class="item" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form action="{{ route('admin.instructors.destroy', $instructor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this teacher?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="item" type="submit" data-bs-toggle="tooltip" title="Delete">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            No teachers found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Bottom Navigation with Previous / Next Controls and Links -->
                    @if(method_exists($instructors, 'hasPages') && $instructors->hasPages())
                        <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <div class="text-muted small">
                                Showing <span class="fw-bold">{{ $instructors->firstItem() }}</span> to <span class="fw-bold">{{ $instructors->lastItem() }}</span> of <span class="fw-bold">{{ $instructors->total() }}</span> entries
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                @if($instructors->onFirstPage())
                                    <button class="btn btn-outline-secondary btn-sm disabled" disabled>
                                        <i class="fa-solid fa-arrow-left me-1"></i> Previous
                                    </button>
                                @else
                                    <a href="{{ $instructors->appends(request()->query())->previousPageUrl() }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fa-solid fa-arrow-left me-1"></i> Previous
                                    </a>
                                @endif

                                <span class="text-muted small px-2">
                                    Page {{ $instructors->currentPage() }} of {{ $instructors->lastPage() }}
                                </span>

                                @if($instructors->hasMorePages())
                                    <a href="{{ $instructors->appends(request()->query())->nextPageUrl() }}" class="btn btn-outline-primary btn-sm">
                                        Next <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm disabled" disabled>
                                        Next <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif

                </section>
            </div>
        </div>
    </div>
</main>
@endsection