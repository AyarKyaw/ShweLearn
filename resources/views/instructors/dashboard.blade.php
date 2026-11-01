@extends('layouts.teacher')

@section('title', 'Teacher Dashboard - Shwe Learn')

@section('content')

<div class="container-fluid">

    {{-- Welcome --}}
    <div class="mb-4">
        <h3 class="mb-1">
            Welcome back, {{ auth('teacher')->user()->name }}!
        </h3>

        <p class="text-muted mb-0">
            Review and manage student homework submissions.
        </p>
    </div>


    {{-- Homework --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <h5 class="mb-1">
                        Homework Submissions
                    </h5>

                    <p class="text-muted mb-0">
                        Review homework submitted by your students.
                    </p>
                </div>

                <a href="{{ route('teacher.homework') }}"
                   class="btn btn-primary">

                    <i class="fas fa-file-alt me-1"></i>
                    View All

                </a>

            </div>


            @forelse($homeworks as $homework)

                <div class="border rounded p-3 mb-3">

                    <div class="row align-items-center">

                        {{-- Homework information --}}
                        <div class="col-md-8">

                            <div class="d-flex align-items-center">

                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                     style="width:50px;height:50px;">

                                    <i class="fas fa-file-alt text-primary"></i>

                                </div>

                                <div>

                                    <h6 class="mb-1">
                                        {{ $homework->lesson_title }}
                                    </h6>

                                    <div class="text-muted small">
                                        {{ $homework->course_title }}
                                    </div>

                                    <div class="small mt-1">
                                        <strong>Student:</strong>
                                        {{ $homework->student_name }}
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Submission information --}}
                        <div class="col-md-2 mt-3 mt-md-0">

                            <div class="small text-muted">
                                Submitted
                            </div>

                            <div class="small">
                                {{ \Carbon\Carbon::parse($homework->submitted_at)->diffForHumans() }}
                            </div>

                        </div>


                        {{-- Review --}}
                        <div class="col-md-2 mt-3 mt-md-0 text-md-end">

                            <a href="{{ route('teacher.homework.review', $homework->id) }}"
                               class="btn btn-outline-primary btn-sm">

                                <i class="fas fa-eye me-1"></i>
                                Review

                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-3x text-muted"></i>
                    </div>

                    <h6 class="mb-1">
                        No homework submissions
                    </h6>

                    <p class="text-muted mb-0">
                        There are currently no homework submissions to review.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection