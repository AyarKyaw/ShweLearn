@extends('layouts.teacher')

@section('title', 'Homework - MIFFA Teacher Portal')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-1">
                        <i class="fas fa-file-alt text-primary me-2"></i>
                        Homework
                    </h4>

                    <p class="text-muted mb-0">
                        Review homework submitted by your students.
                    </p>

                </div>

                <a href="{{ route('teacher.dashboard') }}"
                   class="btn btn-outline-secondary">

                    <i class="fas fa-arrow-left me-1"></i>
                    Dashboard

                </a>

            </div>

        </div>

    </div>


    {{-- Homework List --}}
    @forelse($homeworks as $homework)

        <div class="card border-0 shadow-sm mb-3">

            <div class="card-body p-4">

                <div class="d-flex align-items-center">

                    {{-- File Icon --}}
                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                         style="width:50px;height:50px;">

                        @php
                            $extension = strtolower(
                                pathinfo(
                                    $homework->homework_file_path,
                                    PATHINFO_EXTENSION
                                )
                            );
                        @endphp

                        @if($extension === 'pdf')

                            <i class="fas fa-file-pdf text-danger fs-5"></i>

                        @elseif(in_array($extension, ['doc', 'docx']))

                            <i class="fas fa-file-word text-primary fs-5"></i>

                        @elseif(in_array($extension, ['xls', 'xlsx']))

                            <i class="fas fa-file-excel text-success fs-5"></i>

                        @elseif(in_array($extension, ['ppt', 'pptx']))

                            <i class="fas fa-file-powerpoint text-warning fs-5"></i>

                        @else

                            <i class="fas fa-file text-secondary fs-5"></i>

                        @endif

                    </div>


                    {{-- Homework Information --}}
                    <div class="flex-grow-1">

                        <div class="fw-semibold">
                            {{ $homework->lesson_title }}
                        </div>

                        <div class="text-muted small">
                            {{ $homework->course_title }}
                        </div>

                        <div class="mt-2 small">

                            <i class="fas fa-user text-muted me-1"></i>

                            <strong>
                                {{ $homework->student_name }}
                            </strong>

                        </div>

                        <div class="text-muted small mt-1">

                            <i class="far fa-clock me-1"></i>

                            @if($homework->submitted_at)

                                Submitted
                                {{ \Carbon\Carbon::parse($homework->submitted_at)->diffForHumans() }}

                            @else

                                Submission date unavailable

                            @endif

                        </div>

                        <div class="text-muted small mt-1">

                            <i class="fas fa-paperclip me-1"></i>

                            {{ basename($homework->homework_file_path) }}

                        </div>

                    </div>


                    {{-- Review --}}
                    <div class="ms-3">

                        <a href="{{ route('teacher.homework.review', $homework->id) }}"
                           class="btn btn-primary">

                            <i class="fas fa-eye me-1"></i>
                            Review

                        </a>

                    </div>

                </div>

            </div>

        </div>

    @empty

        {{-- Empty State --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>

                <h5>
                    No homework submissions
                </h5>

                <p class="text-muted mb-0">
                    There are currently no homework submissions
                    from your students.
                </p>

            </div>

        </div>

    @endforelse

</div>

@endsection