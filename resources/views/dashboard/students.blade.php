@extends('dashboard.layouts.master')

@section('title', 'Students - Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="users-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="users-title">Registered Students</h2>
                            <p class="m-card__subtitle">Manage user accounts and student enrollment profiles</p>
                        </div>
                    </header>

                    <!-- Alert Notifications -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show my-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" data-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Filter and Search Tools -->
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <form action="{{ route('admin.students.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
                                <!-- <div class="select-wrapper">
                                    <select class="form-select" name="filter" onchange="this.form.submit()">
                                        <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All Auth Types</option>
                                        <option value="google" {{ request('filter') == 'google' ? 'selected' : '' }}>Google Auth</option>
                                        <option value="standard" {{ request('filter') == 'standard' ? 'selected' : '' }}>Standard Auth</option>
                                    </select>
                                </div> -->

                                <div class="select-wrapper">
                                    <select class="form-select" name="membership_status" onchange="this.form.submit()">
                                        <option value="">All Memberships</option>
                                        <option value="member" {{ request('membership_status') == 'member' ? 'selected' : '' }}>Member</option>
                                        <option value="non-member" {{ request('membership_status') == 'non-member' ? 'selected' : '' }}>Non-Member</option>
                                    </select>
                                </div>

                                <div class="input-group" style="max-width: 280px;">
                                    <input type="text" name="search" class="form-control" placeholder="Search name, email, NRC..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th style="width:24px;">
                                        <label class="au-checkbox">
                                            <input type="checkbox" aria-label="Select all"><span class="au-checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Photo</th>
                                    <th>Student / User</th>
                                    <th>NRC / Identity</th>
                                    <th>Company & Position</th>
                                    <th>Membership</th>
                                    <th>Registered Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox" name="selected_users[]" value="{{ $user->id }}">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            @if($user->studentProfile && $user->studentProfile->passport_photo)
                                                <img src="{{ Storage::url($user->studentProfile->passport_photo) }}" class="rounded-circle object-fit-cover" width="40" height="40" alt="Photo">
                                            @else
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 14px;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                                            <small class="text-muted d-block"><a class="block-email" href="mailto:{{ $user->email }}">{{ $user->email }}</a></small>
                                            <small class="text-muted"><i class="fa-solid fa-phone me-1"></i>{{  $user->studentProfile->phone ?? 'N/A' }}</small>
                                            @if($user->google_id)
                                                <span class="badge bg-light text-dark border ms-1" style="font-size: 9px;">Google</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border fw-normal">
                                                {{ $user->studentProfile->nrc_number ?? 'No Profile' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($user->studentProfile)
                                                <div class="fw-semibold text-dark">{{ $user->studentProfile->company }}</div>
                                                <small class="text-muted">{{ $user->studentProfile->job_title }}</small>
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->studentProfile)
                                                <span class="badge {{ $user->studentProfile->membership_status === 'member' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($user->studentProfile->membership_status) }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border">Unregistered</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at ? $user->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <div class="table-data-feature justify-content-end">
                                                <button type="button" class="item" data-bs-toggle="modal" data-bs-target="#studentModal-{{ $user->id }}" data-toggle="modal" data-target="#studentModal-{{ $user->id }}" title="View Profile">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                
                                                <form action="{{ route('admin.students.destroy', $user->studentProfile->id ?? $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');" class="d-inline">
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
                                            No registered students or users found matching your query.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($users, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $users->links() }}
                        </div>
                    @endif

                </section>
            </div>
        </div>
    </div>
</main>

<!-- Student Detail Modals -->
@foreach ($users as $user)
    <div class="modal fade" id="studentModal-{{ $user->id }}" tabindex="-1" aria-labelledby="studentModalLabel-{{ $user->id }}" aria-hidden="true" style="z-index: 1065;">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="z-index: 1070;">
            <div class="modal-content border-0">
                
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="studentModalLabel-{{ $user->id }}">
                        Student Profile Details
                    </h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="d-none">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-4">
                    <!-- Standard Profile Header -->
                    <div class="mb-4">
                        <h3 class="mb-1 text-dark fw-bold">{{ $user->name }}</h3>
                        <p class="text-muted mb-2">{{ $user->email }}</p>
                        <div class="d-flex align-items-center gap-2">
                            @if($user->studentProfile)
                                <span class="badge {{ $user->studentProfile->membership_status === 'member' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($user->studentProfile->membership_status) }}
                                </span>
                            @endif
                            @if($user->google_id)
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
                                @if($user->studentProfile && $user->studentProfile->passport_photo)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#photoFullscreenModal-{{ $user->id }}" data-toggle="modal" data-target="#photoFullscreenModal-{{ $user->id }}" class="d-inline-block position-relative group">
                                        <img src="{{ Storage::url($user->studentProfile->passport_photo) }}" class="rounded border object-fit-cover shadow-sm" width="80" height="100" alt="Passport Photo" style="cursor: pointer; transition: transform 0.2s;">
                                    </a>
                                    <small class="text-muted d-block mt-1" style="font-size: 11px;"><i class="fa-solid fa-expand me-1"></i>Click image to expand</small>
                                @else
                                    <div class="fw-semibold text-muted">N/A</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Gender</label>
                            <div class="fw-semibold text-dark">{{ ucfirst($user->gender ?? $user->studentProfile->gender ?? 'N/A') }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Phone Number</label>
                            <div class="fw-semibold text-dark">{{ $user->studentProfile->phone ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">NRC / Identity Number</label>
                            <div class="fw-semibold text-dark">{{ $user->studentProfile->nrc_number ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Company / Organization</label>
                            <div class="fw-semibold text-dark">{{ $user->studentProfile->company ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Position / Job Title</label>
                            <div class="fw-semibold text-dark">{{ $user->studentProfile->job_title ?? 'N/A' }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Registered Date</label>
                            <div class="fw-semibold text-dark">{{ $user->created_at ? $user->created_at->format('M d, Y H:i A') : 'N/A' }}</div>
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
    @if($user->studentProfile && $user->studentProfile->passport_photo)
        <div class="modal fade" id="photoFullscreenModal-{{ $user->id }}" tabindex="-1" aria-labelledby="photoFullscreenLabel-{{ $user->id }}" aria-hidden="true" style="z-index: 1085;">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen-md-down modal-xl" style="z-index: 1090;">
                <div class="modal-content bg-dark border-0">
                    <div class="modal-header border-bottom-0 pb-0">
                        <span class="text-white-50 small">{{ $user->name }} - Passport Photo</span>
                        <button type="button" class="btn-close btn-close-white close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="d-none">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center p-4 d-flex align-items-center justify-content-center" style="min-height: 70vh;">
                        <img src="{{ Storage::url($user->studentProfile->passport_photo) }}" class="img-fluid rounded shadow-lg" style="max-height: 80vh; object-fit: contain;" alt="Passport Photo Fullscreen">
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