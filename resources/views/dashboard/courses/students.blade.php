@extends('dashboard.layouts.master')

@section('title', $course->title . ' - Enrolled Students | Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="course-students-title">
                    
                    <!-- Header Banner with Course Details -->
                    <header class="m-card__header d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back
                                </a>
                                <span class="badge bg-secondary font-monospace">{{ $course->code }}</span>
                                <span class="badge bg-info text-dark">{{ $course->category->name ?? 'Uncategorized' }}</span>
                            </div>
                            <h2 class="m-card__title" id="course-students-title">
                                Enrolled Students: <span class="text-primary">{{ $course->title }}</span>
                            </h2>
                            <p class="m-card__subtitle text-muted mb-0">
                                Total Enrolled: <strong>{{ $students->total() ?? count($students) }}</strong> students
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> Edit Course
                            </a>
                        </div>
                    </header>

                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Toolbar & Filters -->
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <form action="{{ route('admin.courses.students', $course->id) }}" method="GET" class="d-flex gap-2">
                                <div class="input-group input-group-sm" style="width: 260px;">
                                    <input type="text" name="search" class="form-control" placeholder="Search student name or email..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                                <div class="select-wrapper">
                                    <select class="form-select form-select-sm" name="status" onchange="this.form.submit()">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="table-data__tool-right">
                            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-users" aria-hidden="true"></i> View All Students
                            </a>
                        </div>
                    </div>

                    <!-- Students Table -->
                    <div class="table-responsive">
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th style="width:24px;">
                                        <label class="au-checkbox">
                                            <input type="checkbox" aria-label="Select all"><span class="au-checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Enrolled Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox" name="selected_students[]" value="{{ $student->id }}">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary font-monospace">{{ $student->student_code ?? '#' . $student->id }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $student->name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $student->email }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $student->studentProfile->phone ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            {{ $student->pivot->created_at ? \Carbon\Carbon::parse($student->pivot->created_at)->format('M d, Y') : ($student->created_at ? $student->created_at->format('M d, Y') : 'N/A') }}
                                        </td>
                                        <td>
                                            @php
                                                $status = $student->pivot->status ?? $student->status ?? 'active';
                                            @endphp
                                            @if($status === 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($status === 'active')
                                                <span class="badge bg-primary">Active</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ ucfirst($status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-data-feature justify-content-end gap-1">
                                                <!-- Open Quick Modal -->
                                                <button type="button" class="item" data-bs-toggle="modal" data-bs-target="#studentModal-{{ $student->id }}" data-toggle="modal" data-target="#studentModal-{{ $student->id }}" title="Quick Details">
                                                    <i class="fa-solid fa-address-card text-info"></i>
                                                </button>
                                                <!-- Unenroll Form -->
                                                <form action="{{ route('admin.courses.students.remove', ['course' => $course->id, 'student' => $student->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to unenroll this student?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="item" type="submit" data-bs-toggle="tooltip" title="Unenroll">
                                                        <i class="fa-solid fa-user-minus text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            No students enrolled in this course yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    @if(method_exists($students, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    @endif

                </section>
            </div>
        </div>
    </div>
</main>

<!-- Student Profile Modals -->
@foreach ($students as $student)
    <div class="modal fade" id="studentModal-{{ $student->id }}" tabindex="-1" aria-labelledby="studentModalLabel-{{ $student->id }}" aria-hidden="true" style="z-index: 1065;">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="z-index: 1070;">
            <div class="modal-content border-0">
                
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="studentModalLabel-{{ $student->id }}">
                        Student Profile Details
                    </h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="d-none">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-4">
                    <!-- Standard Profile Header -->
                    <div class="mb-4">
                        <h3 class="mb-1 text-dark fw-bold">{{ $student->name }}</h3>
                        <p class="text-muted mb-2">{{ $student->email }}</p>
                        <div class="d-flex align-items-center gap-2">
                            @if($student->studentProfile)
                                <span class="badge {{ $student->studentProfile->membership_status === 'member' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($student->studentProfile->membership_status) }}
                                </span>
                            @endif
                            @if($student->google_id)
                                <span class="badge bg-light text-dark border ms-1">Google Auth</span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4" style="opacity: 0.15;">

                    <!-- Profile Details Grid -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Passport Photo</label>
                            <div>
                                @if($student->studentProfile && $student->studentProfile->passport_photo)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#photoFullscreenModal-{{ $student->id }}" data-toggle="modal" data-target="#photoFullscreenModal-{{ $student->id }}" class="d-inline-block position-relative group">
                                        <img src="{{ Storage::url($student->studentProfile->passport_photo) }}" class="rounded border object-fit-cover shadow-sm" width="80" height="100" alt="Passport Photo" style="cursor: pointer; transition: transform 0.2s;">
                                    </a>
                                    <small class="text-muted d-block mt-1" style="font-size: 11px;"><i class="fa-solid fa-expand me-1"></i>Click image to expand</small>
                                @else
                                    <div class="fw-semibold text-muted">N/A</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Gender</label>
                            <div class="fw-semibold text-dark">{{ ucfirst($student->gender ?? $student->studentProfile->gender ?? 'N/A') }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Phone Number</label>
                            <div class="fw-semibold text-dark">{{ $student->studentProfile->phone ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">NRC / Identity Number</label>
                            <div class="fw-semibold text-dark">{{ $student->studentProfile->nrc_number ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Company / Organization</label>
                            <div class="fw-semibold text-dark">{{ $student->studentProfile->company ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Position / Job Title</label>
                            <div class="fw-semibold text-dark">{{ $student->studentProfile->job_title ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Registered Date</label>
                            <div class="fw-semibold text-dark">{{ $student->created_at ? $student->created_at->format('M d, Y H:i A') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Fullscreen Passport Photo Modal -->
    @if($student->studentProfile && $student->studentProfile->passport_photo)
        <div class="modal fade" id="photoFullscreenModal-{{ $student->id }}" tabindex="-1" aria-labelledby="photoFullscreenLabel-{{ $student->id }}" aria-hidden="true" style="z-index: 1085;">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen-md-down modal-xl" style="z-index: 1090;">
                <div class="modal-content bg-dark border-0">
                    <div class="modal-header border-bottom-0 pb-0">
                        <span class="text-white-50 small">{{ $student->name }} - Passport Photo</span>
                        <button type="button" class="btn-close btn-close-white close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="d-none">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center p-4 d-flex align-items-center justify-content-center" style="min-height: 70vh;">
                        <img src="{{ Storage::url($student->studentProfile->passport_photo) }}" class="img-fluid rounded shadow-lg" style="max-height: 80vh; object-fit: contain;" alt="Passport Photo Fullscreen">
                    </div>
                    <div class="modal-footer border-top-0 pt-0 justify-content-center">
                        <button type="button" class="btn btn-outline-light btn-sm px-4" data-bs-dismiss="modal" data-dismiss="modal">Close Preview</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection