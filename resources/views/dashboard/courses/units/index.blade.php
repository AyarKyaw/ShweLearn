@extends('dashboard.layouts.master')

@section('title', 'Units - Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="units-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="units-title">Course Units</h2>
                            <p class="m-card__subtitle">Manage structural units and modules across courses</p>
                        </div>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Courses
                        </a>
                    </header>

                    <!-- Alerts for session messages -->
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
                            <a href="{{ route('admin.sections.index') }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-folder-tree" aria-hidden="true"></i> View All Sections
                            </a>
                            <a href="{{ route('admin.units.create') }}" class="au-btn au-btn--green au-btn--small text-decoration-none d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add Unit
                            </a>
                        </div>
                    </div>

                    <!-- Units Table -->
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
                                    <th>Unit Title</th>
                                    <th>Parent Course</th>
                                    <th>Sections</th>
                                    <th>Created Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($units as $unit)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox" name="selected_units[]" value="{{ $unit->id }}">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border font-monospace">
                                                #{{ $unit->order ?? $loop->iteration }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $unit->title }}</span>
                                            @if($unit->description)
                                                <small class="d-block text-muted text-truncate" style="max-width: 280px;">
                                                    {{ $unit->description }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($unit->course)
                                                <a href="{{ route('admin.courses.index', ['filter' => 'all']) }}" class="text-decoration-none fw-semibold text-primary">
                                                    {{ $unit->course->title }}
                                                </a>
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.sections.index', ['unit_id' => $unit->id]) }}" class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none">
                                                <i class="fa-solid fa-folder me-1"></i>
                                                {{ $unit->sections_count ?? ($unit->sections ? $unit->sections->count() : 0) }} Sections
                                            </a>
                                        </td>
                                        <td>{{ $unit->created_at ? $unit->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="table-data-feature justify-content-end gap-1">
                                                <!-- Add Section to Unit -->
                                                <a href="{{ route('admin.sections.create', ['unit_id' => $unit->id]) }}" class="item" data-bs-toggle="tooltip" title="Add Section to Unit">
                                                    <i class="fa-solid fa-plus text-success"></i>
                                                </a>

                                                <!-- Edit Unit -->
                                                <a href="{{ route('admin.units.edit', $unit->id) }}" class="item" data-bs-toggle="tooltip" title="Edit Unit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>

                                                <!-- Delete Form -->
                                                <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this unit and all its child sections?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="item" type="submit" data-bs-toggle="tooltip" title="Delete Unit">
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
                                            <i class="fa-solid fa-layer-group fs-4 d-block mb-2"></i>
                                            No units found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($units, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $units->appends(request()->query())->links() }}
                        </div>
                    @endif

                </section>
            </div>
        </div>
    </div>
</main>
@endsection