@extends('dashboard.layouts.master')

@section('title', 'Courses - Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="courses-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="courses-title">Courses</h2>
                            <p class="m-card__subtitle">Manage course listings, curriculum, and pricing</p>
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
                        <div class="table-data__tool-left">
                            <form action="{{ route('admin.courses.index') }}" method="GET" class="d-flex gap-2">
                                <div class="select-wrapper">
                                    <select class="form-select" name="filter" onchange="this.form.submit()">
                                        <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All Courses</option>
                                        <option value="latest" {{ request('filter') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="table-data__tool-right d-flex gap-2">
                            <!-- Route changed to admin.units.index -->
                            <a href="{{ route('admin.units.index') }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-layer-group" aria-hidden="true"></i> View Units
                            </a>
                            <a href="{{ route('admin.courses.create') }}" class="au-btn au-btn--green au-btn--small text-decoration-none d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add Course
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
                                    <th>Code</th>
                                    <th>Course Title</th>
                                    <th>Category</th>
                                    <th>Hours</th>
                                    <th>Fee</th>
                                    <th>Created Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($courses as $course)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox" name="selected_courses[]" value="{{ $course->id }}">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary font-monospace">{{ $course->code }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $course->title }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $course->category->name ?? 'Uncategorized' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $course->hour }} hrs</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-success">
                                                {{ number_format($course->price, 0) }} MMK
                                            </span>
                                        </td>
                                        <td>{{ $course->created_at ? $course->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <div class="table-data-feature justify-content-end gap-1">
                                                <a href="{{ route('admin.courses.students', $course->id) }}" class="item" data-bs-toggle="tooltip" title="View Enrolled Students">
                                                    <i class="fa-solid fa-users text-primary"></i>
                                                </a>
                                                <!-- Links directly to Units Index filtered by Course ID -->
                                                <a href="{{ route('admin.units.index', ['course_id' => $course->id]) }}" class="item" data-bs-toggle="tooltip" title="View Units">
                                                    <i class="fa-solid fa-layer-group text-info"></i>
                                                </a>
                                                <a href="{{ route('admin.courses.edit', $course->id) }}" class="item" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');" class="d-inline">
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
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            No courses found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    @if(method_exists($courses, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $courses->appends(request()->query())->links() }}
                        </div>
                    @endif

                </section>
            </div>
        </div>
    </div>
</main>
@endsection