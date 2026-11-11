@extends('layouts.student')

@section('title', 'My Homework | MIFFA ACADEMY')

@section('content')

<style>

/* =========================================================
   HOMEWORK PAGE
========================================================= */

.homework-page {
    max-width: 1400px;
    margin: 0 auto;
}


/* =========================================================
   DESKTOP TABLE
========================================================= */

.homework-table-card {
    border-radius: 18px;
    overflow: hidden;
}

.homework-table th {
    white-space: nowrap;
}

.homework-table td {
    vertical-align: middle;
}


/* =========================================================
   MOBILE CARDS
========================================================= */

.mobile-homework-list {
    display: none;
}

.mobile-homework-card {
    background: #ffffff;

    border: 1px solid #edf0f3;

    border-radius: 18px;

    padding: 16px;

    box-shadow:
        0 4px 18px rgba(0, 0, 0, 0.05);

    margin-bottom: 14px;
}


/* =========================================================
   MOBILE CARD HEADER
========================================================= */

.mobile-homework-header {
    display: flex;

    align-items: flex-start;

    gap: 12px;

    margin-bottom: 14px;
}

.mobile-homework-icon {
    width: 44px;
    height: 44px;

    min-width: 44px;

    border-radius: 12px;

    background: #0b3281;

    color: #ffffff;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 17px;
}

.mobile-homework-title {
    min-width: 0;
    flex: 1;
}

.mobile-homework-title h6 {
    font-size: 15px;

    line-height: 1.35;

    margin: 0 0 4px;

    color: #212529;

    font-weight: 700;

    word-break: break-word;
}

.mobile-homework-unit {
    font-size: 12px;

    color: #7a8188;

    line-height: 1.4;

    word-break: break-word;
}


/* =========================================================
   MOBILE INFORMATION
========================================================= */

.mobile-homework-info {
    border-top: 1px solid #edf0f3;

    padding-top: 13px;

    display: flex;

    flex-direction: column;

    gap: 11px;
}

.mobile-homework-info-row {
    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 12px;
}

.mobile-homework-info-label {
    color: #7a8188;

    font-size: 12px;

    font-weight: 600;

    display: flex;

    align-items: center;

    gap: 6px;

    flex-shrink: 0;
}

.mobile-homework-info-value {
    color: #343a40;

    font-size: 12px;

    font-weight: 600;

    text-align: right;

    word-break: break-word;
}


/* =========================================================
   MOBILE FILE
========================================================= */

.mobile-homework-file {
    display: flex;

    align-items: center;

    gap: 8px;

    max-width: 190px;

    color: #0b3281;

    text-decoration: none;

    font-size: 12px;

    font-weight: 600;

    text-align: right;
}

.mobile-homework-file span {
    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;
}

.mobile-homework-file:hover {
    color: #08245e;
}


/* =========================================================
   MOBILE STATUS
========================================================= */

.mobile-homework-status {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    max-width: 100%;

    text-align: center;
}


/* =========================================================
   MOBILE ACTION
========================================================= */

.mobile-homework-action {
    margin-top: 15px;

    padding-top: 14px;

    border-top: 1px solid #edf0f3;
}

.mobile-homework-action .btn {
    width: 100%;

    min-height: 42px;

    border-radius: 12px;

    font-size: 13px;

    font-weight: 600;
}


/* =========================================================
   MOBILE HEADER
========================================================= */

@media (max-width: 767.98px) {

    .homework-page {
        width: 100%;
    }


    .homework-page-header {
        margin-bottom: 18px !important;
    }


    .homework-page-header h2 {
        font-size: 1.35rem;

        line-height: 1.3;
    }


    .homework-page-header p {
        font-size: 12px;

        line-height: 1.5;
    }


    .homework-page-header .btn {
        width: 100%;

        min-height: 42px;

        border-radius: 12px !important;
    }


    /* Hide desktop table */

    .desktop-homework-table {
        display: none !important;
    }


    /* Show mobile cards */

    .mobile-homework-list {
        display: block;
    }


    /* Pagination */

    .pagination {
        flex-wrap: wrap;

        justify-content: center;

        gap: 3px;
    }

}


/* =========================================================
   SMALL PHONES
========================================================= */

@media (max-width: 575.98px) {

    .mobile-homework-card {
        padding: 14px;

        border-radius: 16px;
    }


    .mobile-homework-icon {
        width: 40px;
        height: 40px;

        min-width: 40px;

        font-size: 15px;
    }


    .mobile-homework-title h6 {
        font-size: 14px;
    }


    .mobile-homework-unit {
        font-size: 11px;
    }


    .mobile-homework-info-row {
        gap: 8px;
    }


    .mobile-homework-info-label {
        font-size: 11px;
    }


    .mobile-homework-info-value {
        font-size: 11px;
    }


    .mobile-homework-file {
        max-width: 155px;

        font-size: 11px;
    }

}


/* =========================================================
   VERY SMALL PHONES
========================================================= */

@media (max-width: 380px) {

    .homework-page-header h2 {
        font-size: 1.2rem;
    }


    .mobile-homework-card {
        padding: 12px;
    }


    .mobile-homework-info-row {
        flex-direction: column;

        gap: 3px;
    }


    .mobile-homework-info-value {
        text-align: left;
    }


    .mobile-homework-file {
        max-width: 100%;

        text-align: left;
    }

}

</style>


<div class="homework-page">

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="homework-page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">

        <div>

            <h2 class="fw-bold text-dark mb-1">
                Homework Submissions 📝
            </h2>

            <p class="text-muted mb-0">
                View all your uploaded assignment files, track grades, and check review status.
            </p>

        </div>


        <div>

            <a
                href="{{ route('student.dashboard.courses') }}"
                class="btn btn-outline-primary rounded-pill px-4"
                style="border-color: #0b3281; color: #0b3281;"
            >

                <i class="fas fa-book me-1"></i>

                Go to Courses

            </a>

        </div>

    </div>


    @if(isset($homeworkSubmissions) && $homeworkSubmissions->count() > 0)


        <!-- =====================================================
             DESKTOP TABLE
        ====================================================== -->

        <div class="desktop-homework-table">

            <div class="card border-0 shadow-sm bg-white homework-table-card">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0 homework-table">

                        <thead class="bg-light">

                            <tr
                                class="text-secondary small text-uppercase fw-semibold"
                                style="letter-spacing: 0.5px;"
                            >

                                <th class="ps-4 py-3">
                                    Lesson & Details
                                </th>

                                <th class="py-3">
                                    Homework Type
                                </th>

                                <th class="py-3">
                                    Submitted File
                                </th>

                                <th class="py-3">
                                    Status & Grade
                                </th>

                                <th class="py-3">
                                    Submission Date
                                </th>

                                <th class="text-end pe-4 py-3">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="border-top-0">

                            @foreach($homeworkSubmissions as $lesson)

                                @php

                                    $filePath = $lesson->pivot->homework_file_path ?? '';

                                    $extension = strtolower(
                                        pathinfo($filePath, PATHINFO_EXTENSION)
                                    );


                                    switch($extension) {

                                        case 'pdf':

                                            $typeLabel = 'PDF Document';
                                            $typeBadge = 'bg-danger-subtle text-danger border-danger';
                                            $typeIcon = 'fa-file-pdf';

                                            break;


                                        case 'doc':
                                        case 'docx':

                                            $typeLabel = 'Word Document';
                                            $typeBadge = 'bg-primary-subtle text-primary border-primary';
                                            $typeIcon = 'fa-file-word';

                                            break;


                                        case 'zip':
                                        case 'rar':
                                        case '7z':

                                            $typeLabel = 'Archive (ZIP)';
                                            $typeBadge = 'bg-warning-subtle text-warning-emphasis border-warning';
                                            $typeIcon = 'fa-file-archive';

                                            break;


                                        case 'jpg':
                                        case 'jpeg':
                                        case 'png':

                                            $typeLabel = 'Image File';
                                            $typeBadge = 'bg-info-subtle text-info-emphasis border-info';
                                            $typeIcon = 'fa-file-image';

                                            break;


                                        default:

                                            $typeLabel = strtoupper($extension) ?: 'File';
                                            $typeBadge = 'bg-secondary-subtle text-secondary border-secondary';
                                            $typeIcon = 'fa-file';

                                            break;

                                    }


                                    $quizScore =
                                        $lesson->pivot->quiz_score ?? null;

                                    $isCompleted =
                                        $lesson->pivot->is_completed ?? false;


                                    $unitTitle =
                                        $lesson->section->unit->title
                                        ?? $lesson->unit->title
                                        ?? 'General Unit';

                                @endphp


                                <tr>


                                    <!-- Lesson -->

                                    <td class="ps-4 py-3">

                                        <div class="d-flex align-items-center gap-3">

                                            <div
                                                class="rounded-3 d-flex align-items-center justify-content-center text-white"
                                                style="
                                                    width: 42px;
                                                    height: 42px;
                                                    background-color: #0b3281;
                                                    flex-shrink: 0;
                                                "
                                            >

                                                <i class="fas fa-file-alt"></i>

                                            </div>


                                            <div>

                                                <h6 class="fw-bold text-dark mb-0">
                                                    {{ $lesson->title }}
                                                </h6>

                                                <small class="text-muted">

                                                    <i class="fas fa-layer-group me-1"></i>

                                                    {{ $unitTitle }}

                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    <!-- Type -->

                                    <td class="py-3">

                                        <span
                                            class="badge {{ $typeBadge }} border px-3 py-2 rounded-pill fw-semibold"
                                        >

                                            <i class="fas {{ $typeIcon }} me-1"></i>

                                            {{ $typeLabel }}

                                        </span>

                                    </td>


                                    <!-- File -->

                                    <td class="py-3">

                                        @if($filePath)

                                            <a
                                                href="{{ Storage::url($filePath) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-light border rounded-pill text-truncate px-3"
                                                style="max-width: 200px;"
                                            >

                                                <i
                                                    class="fas fa-paperclip me-1"
                                                    style="color: #0b3281;"
                                                ></i>

                                                {{ basename($filePath) }}

                                            </a>

                                        @else

                                            <span class="text-muted small">
                                                No file attached
                                            </span>

                                        @endif

                                    </td>


                                    <!-- Status -->

                                    <td class="py-3">

                                        @if(!is_null($quizScore))

                                            <span
                                                class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-2 fw-bold"
                                            >

                                                <i class="fas fa-star me-1 text-warning"></i>

                                                Score: {{ $quizScore }}%

                                            </span>

                                        @else

                                            <span
                                                class="badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-3 py-2 fw-normal"
                                            >

                                                <i class="fas fa-hourglass-half me-1"></i>

                                                Pending Review

                                            </span>

                                        @endif

                                    </td>


                                    <!-- Date -->

                                    <td class="py-3 text-muted small">

                                        <i class="far fa-clock me-1 text-secondary"></i>

                                        {{ \Carbon\Carbon::parse($lesson->pivot->updated_at)->format('M d, Y • h:i A') }}

                                    </td>


                                    <!-- Action -->

                                    <td class="text-end pe-4 py-3">

                                        @if(isset($lesson->pivot->course_id))

                                            <a
                                                href="{{ route('courses.learn', [
                                                    'course' => $lesson->pivot->course_id,
                                                    'lesson' => $lesson->id
                                                ]) }}"
                                                class="btn btn-sm text-white rounded-pill px-3 fw-semibold"
                                                style="background-color: #0b3281;"
                                            >

                                                <i class="fas fa-arrow-right me-1"></i>

                                                Go to Lesson

                                            </a>

                                        @else

                                            <span class="badge bg-light text-secondary border">
                                                Completed
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <!-- =====================================================
             MOBILE CARDS
        ====================================================== -->

        <div class="mobile-homework-list">

            @foreach($homeworkSubmissions as $lesson)

                @php

                    $filePath =
                        $lesson->pivot->homework_file_path ?? '';

                    $extension =
                        strtolower(
                            pathinfo($filePath, PATHINFO_EXTENSION)
                        );


                    switch($extension) {

                        case 'pdf':

                            $typeLabel = 'PDF Document';
                            $typeBadge = 'bg-danger-subtle text-danger border-danger';
                            $typeIcon = 'fa-file-pdf';

                            break;


                        case 'doc':
                        case 'docx':

                            $typeLabel = 'Word Document';
                            $typeBadge = 'bg-primary-subtle text-primary border-primary';
                            $typeIcon = 'fa-file-word';

                            break;


                        case 'zip':
                        case 'rar':
                        case '7z':

                            $typeLabel = 'Archive (ZIP)';
                            $typeBadge = 'bg-warning-subtle text-warning-emphasis border-warning';
                            $typeIcon = 'fa-file-archive';

                            break;


                        case 'jpg':
                        case 'jpeg':
                        case 'png':

                            $typeLabel = 'Image File';
                            $typeBadge = 'bg-info-subtle text-info-emphasis border-info';
                            $typeIcon = 'fa-file-image';

                            break;


                        default:

                            $typeLabel =
                                strtoupper($extension) ?: 'File';

                            $typeBadge =
                                'bg-secondary-subtle text-secondary border-secondary';

                            $typeIcon =
                                'fa-file';

                            break;

                    }


                    $quizScore =
                        $lesson->pivot->quiz_score ?? null;


                    $unitTitle =
                        $lesson->section->unit->title
                        ?? $lesson->unit->title
                        ?? 'General Unit';

                @endphp


                <div class="mobile-homework-card">


                    <!-- Card Header -->

                    <div class="mobile-homework-header">


                        <div class="mobile-homework-icon">

                            <i class="fas fa-file-alt"></i>

                        </div>


                        <div class="mobile-homework-title">

                            <h6>
                                {{ $lesson->title }}
                            </h6>

                            <div class="mobile-homework-unit">

                                <i class="fas fa-layer-group me-1"></i>

                                {{ $unitTitle }}

                            </div>

                        </div>


                    </div>


                    <!-- Information -->

                    <div class="mobile-homework-info">


                        <!-- Homework Type -->

                        <div class="mobile-homework-info-row">

                            <div class="mobile-homework-info-label">

                                <i class="fas fa-file"></i>

                                Type

                            </div>


                            <div class="mobile-homework-info-value">

                                <span
                                    class="badge {{ $typeBadge }} border rounded-pill px-2 py-1"
                                >

                                    <i class="fas {{ $typeIcon }} me-1"></i>

                                    {{ $typeLabel }}

                                </span>

                            </div>

                        </div>


                        <!-- Submitted File -->

                        <div class="mobile-homework-info-row">

                            <div class="mobile-homework-info-label">

                                <i class="fas fa-paperclip"></i>

                                File

                            </div>


                            <div class="mobile-homework-info-value">

                                @if($filePath)

                                    <a
                                        href="{{ Storage::url($filePath) }}"
                                        target="_blank"
                                        class="mobile-homework-file"
                                        title="{{ basename($filePath) }}"
                                    >

                                        <i
                                            class="fas fa-external-link-alt"
                                            style="color: #0b3281;"
                                        ></i>

                                        <span>
                                            {{ basename($filePath) }}
                                        </span>

                                    </a>

                                @else

                                    <span class="text-muted">
                                        No file attached
                                    </span>

                                @endif

                            </div>

                        </div>


                        <!-- Status -->

                        <div class="mobile-homework-info-row">

                            <div class="mobile-homework-info-label">

                                <i class="fas fa-chart-line"></i>

                                Status

                            </div>


                            <div class="mobile-homework-info-value">

                                @if(!is_null($quizScore))

                                    <span
                                        class="mobile-homework-status badge bg-success-subtle text-success border border-success rounded-pill px-2 py-1"
                                    >

                                        <i class="fas fa-star me-1 text-warning"></i>

                                        Score: {{ $quizScore }}%

                                    </span>

                                @else

                                    <span
                                        class="mobile-homework-status badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-2 py-1"
                                    >

                                        <i class="fas fa-hourglass-half me-1"></i>

                                        Pending Review

                                    </span>

                                @endif

                            </div>

                        </div>


                        <!-- Submission Date -->

                        <div class="mobile-homework-info-row">

                            <div class="mobile-homework-info-label">

                                <i class="far fa-clock"></i>

                                Submitted

                            </div>


                            <div class="mobile-homework-info-value">

                                {{ \Carbon\Carbon::parse($lesson->pivot->updated_at)->format('M d, Y • h:i A') }}

                            </div>

                        </div>


                    </div>


                    <!-- Action -->

                    <div class="mobile-homework-action">

                        @if(isset($lesson->pivot->course_id))

                            <a
                                href="{{ route('courses.learn', [
                                    'course' => $lesson->pivot->course_id,
                                    'lesson' => $lesson->id
                                ]) }}"
                                class="btn text-white"
                                style="background-color: #0b3281;"
                            >

                                <i class="fas fa-arrow-right me-1"></i>

                                Go to Lesson

                            </a>

                        @else

                            <div
                                class="btn btn-light border text-secondary w-100"
                                style="border-radius: 12px;"
                            >

                                <i class="fas fa-check-circle me-1"></i>

                                Completed

                            </div>

                        @endif

                    </div>


                </div>

            @endforeach

        </div>


        <!-- =====================================================
             PAGINATION
        ====================================================== -->

        @if(method_exists($homeworkSubmissions, 'links'))

            <div class="d-flex justify-content-center mt-4">

                {{ $homeworkSubmissions->links() }}

            </div>

        @endif


    @else


        <!-- =====================================================
             EMPTY STATE
        ====================================================== -->

        <div class="card border-0 rounded-4 shadow-sm p-5 bg-white text-center">

            <div class="my-4">

                <div
                    class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 80px; height: 80px;"
                >

                    <i class="fas fa-tasks text-muted fa-2x"></i>

                </div>


                <h5 class="fw-bold text-dark mb-2">
                    No Homework Submissions Found
                </h5>


                <p
                    class="text-muted mb-4 mx-auto"
                    style="max-width: 420px;"
                >

                    You haven't uploaded any homework files yet.
                    Complete your lessons and upload assignments from
                    your course classroom.

                </p>


                <a
                    href="{{ route('student.dashboard.courses') }}"
                    class="btn text-white rounded-pill px-4 py-2 fw-semibold"
                    style="background-color: #0b3281;"
                >

                    <i class="fas fa-book me-2"></i>

                    View My Courses

                </a>

            </div>

        </div>

    @endif

</div>

@endsection