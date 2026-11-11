@extends('dashboard.layouts.master')

@section('title', 'Admin Management - Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="admins-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="admins-title">Admin Management</h2>
                            <p class="m-card__subtitle">Manage administrative accounts, access levels, and security roles</p>
                        </div>
                    </header>

                    <!-- Session Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ $errors->first('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Toolbar & Filters -->
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <form action="{{ route('admin.admins.index') }}" method="GET" class="d-flex gap-2">
                                <div class="select-wrapper">
                                    <select class="form-select" name="role" onchange="this.form.submit()">
                                        <option value="">All Roles</option>
                                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="event_admin" {{ request('role') == 'event_admin' ? 'selected' : '' }}>Event Admin</option>
                                        <option value="finance_admin" {{ request('role') == 'finance_admin' ? 'selected' : '' }}>Finance Admin</option>
                                        <option value="support_admin" {{ request('role') == 'support_admin' ? 'selected' : '' }}>Support Admin</option>
                                        <option value="agent_admin" {{ request('role') == 'agent_admin' ? 'selected' : '' }}>Agent Admin</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="table-data__tool-right">
                            <a href="{{ route('admin.admins.create') }}" class="au-btn au-btn--green au-btn--small text-decoration-none d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add Admin
                            </a>
                        </div>
                    </div>

                    <!-- Table Data -->
                    <div class="table-responsive">
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th style="width:24px;">
                                        <label class="au-checkbox">
                                            <input type="checkbox" aria-label="Select all"><span class="au-checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Admin User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created At</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($admins as $admin)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox" name="selected_admins[]" value="{{ $admin->id }}">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold me-2" style="width: 36px; height: 36px; font-size: 13px;">
                                                    {{ strtoupper(substr($admin->name ?? $admin->email, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark d-block">{{ $admin->name ?? 'N/A' }}</span>
                                                    @if(auth('admin')->id() === $admin->id)
                                                        <span class="badge bg-light text-secondary border fs-8">You</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="block-email">{{ $admin->email }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $roleClasses = [
                                                    'super_admin'  => 'bg-danger text-white',
                                                    'course_admin' => 'bg-primary text-white',
                                                ];
                                                $badgeClass = $roleClasses[$admin->role] ?? 'bg-secondary text-white';
                                            @endphp
                                            <span class="badge {{ $badgeClass }} font-monospace text-uppercase" style="font-size: 11px;">
                                                {{ str_replace('_', ' ', $admin->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted small">{{ $admin->created_at ? $admin->created_at->format('Y-m-d') : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <div class="table-data-feature justify-content-end">
                                                <a href="{{ route('admin.admins.edit', $admin->id) }}" class="item" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                @if(auth('admin')->id() !== $admin->id)
                                                    <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin account?');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="item" type="submit" data-bs-toggle="tooltip" title="Delete">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="item opacity-50" type="button" disabled data-bs-toggle="tooltip" title="Cannot delete yourself">
                                                        <i class="fa-solid fa-trash text-muted"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            No admin users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    @if(method_exists($admins, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $admins->appends(request()->query())->links() }}
                        </div>
                    @endif

                </section>
            </div>
        </div>
    </div>
</main>
@endsection