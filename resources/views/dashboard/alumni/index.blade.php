@extends('dashboard.layouts.master')

@section('title', 'Alumni - Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="alumni-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="alumni-title">Alumni Management</h2>
                            <p class="m-card__subtitle">Manage registered alumni profiles, statuses, and credentials</p>
                        </div>
                    </header>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <form action="{{ route('admin.alumni.index') }}" method="GET" class="d-flex gap-2">
                                <div class="select-wrapper">
                                    <select class="form-select" name="filter" onchange="this.form.submit()">
                                        <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All Statuses</option>
                                        <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('filter') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ request('filter') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="table-data__tool-right d-flex gap-2">
                            <a href="{{ route('admin.alumni.create') }}" class="au-btn au-btn--green au-btn--small text-decoration-none d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add Alumni
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
                                    <th>Register No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($alumnis as $alumni)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox" name="selected_alumni[]" value="{{ $alumni->id }}">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary font-monospace">{{ $alumni->register_no }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $alumni->name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $alumni->email }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $badgeColor = match($alumni->status) {
                                                    'active' => 'success',
                                                    'inactive' => 'secondary',
                                                    'suspended' => 'danger',
                                                    default => 'warning text-dark'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badgeColor }}">
                                                {{ ucfirst($alumni->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $alumni->created_at ? $alumni->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <div class="table-data-feature justify-content-end gap-1">
                                                <a href="{{ route('admin.alumni.edit', $alumni->id) }}" class="item" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form action="{{ route('admin.alumni.destroy', $alumni->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this alumni record?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="item border-0 bg-transparent" type="submit" data-bs-toggle="tooltip" title="Delete">
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
                                            No alumni records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($alumnis, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $alumnis->appends(request()->query())->links() }}
                        </div>
                    @endif

                </section>
            </div>
        </div>
    </div>
</main>
@endsection