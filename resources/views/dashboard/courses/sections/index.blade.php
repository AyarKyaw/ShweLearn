@extends('dashboard.layouts.master')

@section('title', 'Sections - Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="sections-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="sections-title">Course Sections</h2>
                            <p class="m-card__subtitle">Manage sub-modules and lessons within units</p>
                        </div>
                        <a href="{{ route('admin.units.index', ['course_id' => $sections->first()?->unit?->course_id ?? request('course_id')]) }}" class="btn btn-outline-secondary btn-sm">
    <i class="fa-solid fa-arrow-left me-1"></i> Back to Units
</a>
                    </header>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Action Tool Bar -->
                    <div class="table-data__tool">
                        <div class="table-data__tool-left"></div>
                        <div class="table-data__tool-right d-flex gap-2">
                            <a href="{{ route('admin.lessons.index') }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-book-open" aria-hidden="true"></i> View All Lessons
                            </a>
                            <a href="{{ route('admin.sections.create', request()->only('unit_id')) }}" class="au-btn au-btn--green au-btn--small text-decoration-none d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add Section
                            </a>
                        </div>
                    </div>

                    <!-- Sections Table -->
                    <div class="table-responsive">
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th style="width:24px;">
                                        <label class="au-checkbox">
                                            <input type="checkbox" aria-label="Select all"><span class="au-checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Order</th>
                                    <th>Section Title</th>
                                    <th>Parent Unit</th>
                                    <th>Lessons</th>
                                    <th>Created Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sections as $section)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox" name="selected_sections[]" value="{{ $section->id }}">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border font-monospace">
                                                #{{ $section->order ?? $loop->iteration }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $section->title }}</span>
                                            @if($section->description)
                                                <small class="d-block text-muted text-truncate" style="max-width: 280px;">
                                                    {{ $section->description }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($section->unit)
                                                <span class="fw-semibold text-primary d-block">{{ $section->unit->title }}</span>
                                                @if($section->unit->course)
                                                    <small class="text-muted">{{ $section->unit->course->title }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.lessons.index', ['section_id' => $section->id]) }}" class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none">
                                                <i class="fa-solid fa-file-lines me-1"></i>
                                                {{ $section->lessons_count ?? ($section->lessons ? $section->lessons->count() : 0) }} Lessons
                                            </a>
                                        </td>
                                        <td>{{ $section->created_at ? $section->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="table-data-feature justify-content-end gap-1">
                                                <!-- Add Lesson to Section -->
                                                <a href="{{ route('admin.lessons.create', ['section_id' => $section->id]) }}" class="item" data-bs-toggle="tooltip" title="Add Lesson to Section">
                                                    <i class="fa-solid fa-plus text-success"></i>
                                                </a>

                                                <!-- Edit Section -->
                                                <a href="{{ route('admin.sections.edit', $section->id) }}" class="item" data-bs-toggle="tooltip" title="Edit Section">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>

                                                <!-- Delete Section -->
                                                <form action="{{ route('admin.sections.destroy', $section->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this section and its lessons?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="item" type="submit" data-bs-toggle="tooltip" title="Delete Section">
                                                        <i class="fa-solid fa-trash text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="fa-solid fa-folder-open fs-4 d-block mb-2"></i>
                                            No sections found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($sections, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $sections->appends(request()->query())->links() }}
                        </div>
                    @endif

                </section>
            </div>
        </div>
    </div>
</main>
@endsection