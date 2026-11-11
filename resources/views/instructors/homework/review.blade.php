@extends('layouts.teacher')

@section('title', 'Homework Review - MIFFA Teacher Portal')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="mb-1">
                        <i class="fas fa-file-alt text-primary me-2"></i>
                        Homework Review
                    </h4>

                    <p class="text-muted mb-0">
                        Review the student's submitted homework.
                    </p>

                </div>

                <a href="{{ route('teacher.homework') }}"
                   class="btn btn-outline-secondary">

                    <i class="fas fa-arrow-left me-1"></i>
                    Back

                </a>

            </div>

        </div>

    </div>


    {{-- Student / Homework Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="row">

                {{-- Student --}}
                <div class="col-md-6 mb-4">

                    <small class="text-muted d-block mb-1">
                        Student
                    </small>

                    <strong>
                        {{ $homework->student_name }}
                    </strong>

                    @if($homework->student_email)

                        <div class="text-muted small">
                            {{ $homework->student_email }}
                        </div>

                    @endif

                </div>


                {{-- Course --}}
                <div class="col-md-6 mb-4">

                    <small class="text-muted d-block mb-1">
                        Course
                    </small>

                    <strong>
                        {{ $homework->course_title }}
                    </strong>

                </div>


                {{-- Homework --}}
                <div class="col-md-6 mb-0">

                    <small class="text-muted d-block mb-1">
                        Homework
                    </small>

                    <strong>
                        {{ $homework->lesson_title }}
                    </strong>

                </div>


                {{-- Submitted --}}
                <div class="col-md-6 mb-0">

                    <small class="text-muted d-block mb-1">
                        Submitted
                    </small>

                    <strong>

                        @if($homework->submitted_at)

                            {{ \Carbon\Carbon::parse($homework->submitted_at)->format('d M Y, h:i A') }}

                        @else

                            -

                        @endif

                    </strong>

                </div>

            </div>

        </div>

    </div>


    @php

        $fileUrl = asset(
            'storage/' . $homework->homework_file_path
        );

        $extension = strtolower(
            pathinfo(
                $homework->homework_file_path,
                PATHINFO_EXTENSION
            )
        );

    @endphp


    {{-- Submitted File --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        Submitted File
                    </strong>

                    <div class="small text-muted mt-1">
                        {{ basename($homework->homework_file_path) }}
                    </div>

                </div>


                <a href="{{ $fileUrl }}"
                   target="_blank"
                   class="btn btn-primary">

                    <i class="fas fa-external-link-alt me-1"></i>
                    Open File

                </a>

            </div>

        </div>


        {{-- PDF --}}
        @if($extension === 'pdf')

            <div class="bg-light">

                <iframe
                    src="{{ $fileUrl }}"
                    title="Student Homework"
                    style="display:block;width:100%;height:800px;border:0;">
                </iframe>

            </div>


        {{-- Office files --}}
        @else

            <div class="text-center py-5 px-3">

                @if(in_array($extension, ['doc', 'docx']))

                    <i class="fas fa-file-word fa-4x text-primary mb-3"></i>

                    <h5>
                        Word Document
                    </h5>


                @elseif(in_array($extension, ['xls', 'xlsx']))

                    <i class="fas fa-file-excel fa-4x text-success mb-3"></i>

                    <h5>
                        Excel Spreadsheet
                    </h5>


                @elseif(in_array($extension, ['ppt', 'pptx']))

                    <i class="fas fa-file-powerpoint fa-4x text-danger mb-3"></i>

                    <h5>
                        PowerPoint Presentation
                    </h5>


                @else

                    <i class="fas fa-file fa-4x text-secondary mb-3"></i>

                    <h5>
                        Submitted File
                    </h5>

                @endif


                <p class="text-muted mt-3">

                    This file type cannot be displayed directly
                    inside the browser.

                </p>


                <a href="{{ $fileUrl }}"
                   target="_blank"
                   class="btn btn-primary">

                    <i class="fas fa-file-download me-1"></i>
                    Open / Download Homework

                </a>

            </div>

        @endif

    </div>

</div>

@endsection