@extends('dashboard.layouts.master')

@section('title', 'Create Admin - Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="create-admin-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="create-admin-title">Add New Admin</h2>
                            <p class="m-card__subtitle">Create a new administrative account and assign user permissions</p>
                        </div>
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                            <i class="fa-solid fa-arrow-left"></i> Back to List
                        </a>
                    </header>

                    <div class="p-4">
                        <!-- Global Error Alert -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Please fix the following issues:</strong>
                                <ul class="mb-0 mt-2 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.admins.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <!-- Name Field -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold text-dark">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="e.g. John Doe"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email Field -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold text-dark">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="e.g. admin@miffa.org"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role Select -->
                                <div class="col-md-12">
                                    <label for="role" class="form-label fw-semibold text-dark">Admin Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select account role</option>
                                        <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin (Full Access)</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password Field -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold text-dark">Password <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="••••••••"
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password Field -->
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-semibold text-dark">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="••••••••"
                                           required>
                                </div>
                            </div>

                            <!-- Form Buttons -->
                            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="au-btn au-btn--green text-decoration-none d-inline-flex align-items-center gap-1">
                                    <i class="fa-solid fa-save"></i> Save Admin
                                </button>
                            </div>
                        </form>
                    </div>

                </section>
            </div>
        </div>
    </div>
</main>
@endsection