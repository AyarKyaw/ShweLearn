@extends('layouts.master')

@section('title', $course->title . ' - Units Overview')

@section('content')
@php
    $user = auth()->user();

    $userLessons = $user
        ? $user->lessons()
               ->where('lesson_user.course_id', $course->id)
               ->get()
               ->keyBy('id')
        : collect();

    $activeUnit = $selectedUnit ?? $course->units->first();

    $allLessons = $course->units->flatMap(
        fn($u) => $u->sections->flatMap->lessons
    );

    $totalLessons = $allLessons->count();

    $completedCount = $userLessons->filter(function($lesson) {
        return $lesson->pivot->is_completed ||
               ($lesson->pivot->quiz_score >= 80);
    })->count();

    $courseProgressPercent = $totalLessons > 0
        ? round(($completedCount / $totalLessons) * 100)
        : 0;
@endphp
@push('styles')
<style>
    /* Clickable header */
    .unit-content-header {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .unit-content-header:hover {
        background-color: #f8f9fa;
    }

    /* Chevron */
    .unit-dropdown-icon {
        font-size: 0.75rem;
        color: #6c757d;
        transition: transform 0.2s ease;
    }

    .unit-content-header[aria-expanded="true"] .unit-dropdown-icon {
        transform: rotate(180deg);
    }

    /* Unit items */
    .unit-selector-item {
        color: #212529;
        border-bottom: 1px solid #f1f1f1;
        transition: background-color 0.2s ease;
    }

    .unit-selector-item:last-child {
        border-bottom: 0;
    }

    .unit-selector-item:hover {
        background-color: #f8f9fa;
    }

    .unit-selector-item.active {
        background-color: #eef5ff;
        color: #0d6efd;
    }

    /* Number circle */
    .unit-number {
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 50%;
        background: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .unit-selector-item.active .unit-number {
        background: #0d6efd;
        color: #fff;
    }
    /* =========================================================
   MOBILE BACK TO ENROLLED COURSE
========================================================= */

.mobile-back-course {
    display: none;
}

@media (max-width: 991.98px) {

    .mobile-back-course {
        display: block;
    }

    .mobile-back-course a {
        color: #0d6efd;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 8px 2px;
    }

    .mobile-back-course a:hover {
        color: #0a58ca;
    }

}
</style>
@endpush
<div class="container-fluid py-4 bg-light mt-5 pt-5 units-overview-page"
     style="min-height: 85vh; margin-top: 80px !important;">

    <div class="row g-0 rounded-4 overflow-hidden shadow-sm border bg-white mx-md-4">

        {{-- =========================================================
             SIDEBAR - DESKTOP ONLY
        ========================================================== --}}
        <div class="col-lg-3 border-end bg-white d-flex flex-column units-sidebar"
             style="min-height: 70vh;">

            {{-- Header & Overall Course Progress --}}
            <div class="p-4 bg-primary text-white flex-shrink-0">

                <h5 class="fw-bold mb-1 text-white">
                    {{ $course->title }}
                </h5>

                <div class="d-flex align-items-center justify-content-between mt-2">
                    <small class="opacity-75">
                        Course Progress
                    </small>

                    <small class="fw-bold">
                        {{ $courseProgressPercent }}%
                    </small>
                </div>

                <div class="progress mt-1"
                     style="height: 6px; background-color: rgba(255,255,255,0.25);">

                    <div class="progress-bar bg-warning"
                         role="progressbar"
                         style="width: {{ $courseProgressPercent }}%">
                    </div>

                </div>

            </div>

            {{-- Units Navigation --}}
            <div class="list-group list-group-flush overflow-auto flex-grow-1">

                @forelse($course->units as $uIndex => $unit)

                    @php
                        $isSelected = $activeUnit &&
                                       $activeUnit->id === $unit->id;

                        $unitLessons = $unit->sections->flatMap->lessons;
                        $unitTotal = $unitLessons->count();

                        $unitCompleted = $unitLessons->filter(
                            fn($l) =>
                                $userLessons->has($l->id) &&
                                (
                                    $userLessons[$l->id]->pivot->is_completed ||
                                    $userLessons[$l->id]->pivot->quiz_score >= 80
                                )
                        )->count();
                    @endphp

                    <a href="{{ route('courses.units', [$course->id, 'unit' => $unit->id]) }}"
                       class="list-group-item list-group-item-action p-3 border-bottom
                       {{ $isSelected
                            ? 'bg-primary-subtle border-start border-primary border-4 text-primary fw-bold'
                            : '' }}">

                        <div class="d-flex align-items-center justify-content-between">

                            <span class="d-block text-uppercase small text-muted"
                                  style="font-size: 0.72rem;">
                                Unit {{ $uIndex + 1 }}
                            </span>

                            @if($unitTotal > 0 && $unitCompleted === $unitTotal)

                                <i class="fas fa-check-circle text-success"
                                   title="Unit Completed"></i>

                            @endif

                        </div>

                        <div class="text-truncate mt-1"
                             style="font-size: 0.95rem;">
                            {{ $unit->title }}
                        </div>

                        <div class="d-flex align-items-center justify-content-between text-muted mt-2"
                             style="font-size: 0.75rem;">

                            <span>
                                <i class="far fa-play-circle me-1"></i>
                                {{ $unitTotal }} Lessons
                            </span>

                            <span>
                                {{ $unitCompleted }}/{{ $unitTotal }} Done
                            </span>

                        </div>

                    </a>

                @empty

                    <div class="p-4 text-center text-muted">
                        No units available.
                    </div>

                @endforelse

            </div>

        </div>


        {{-- =========================================================
             MAIN CONTENT
        ========================================================== --}}
        <div class="col-12 col-lg-9 bg-light p-3 p-md-5 units-main-content">
            {{-- Mobile Back to Enrolled Course --}}
            <div class="mobile-back-course mb-3">
                <a href="{{ route('courses.my') }}"
                class="d-inline-flex align-items-center gap-2 text-decoration-none">

                    <i class="fas fa-arrow-left"></i>

                    <span>Back to Enrolled Course</span>

                </a>
            </div>
            @if($activeUnit)
                {{-- Unit Header Banner --}}
                <div class="bg-white rounded-4 shadow-sm border mb-4 overflow-hidden">

                    {{-- Clickable Header --}}
                    <div
                        class="unit-content-header p-4 d-flex align-items-center justify-content-between"
                        data-bs-toggle="collapse"
                        data-bs-target="#unitSelectorDropdown"
                        aria-expanded="false"
                        aria-controls="unitSelectorDropdown"
                        role="button"
                    >

                        <div class="d-flex align-items-center gap-2 text-primary fw-bold small text-uppercase">

                            <i class="fas fa-layer-group"></i>

                            <span>Unit Content</span>

                        </div>

                        <div class="d-flex align-items-center gap-2">

                            <span class="text-muted small">
                                Unit {{ $course->units->search(fn($unit) => $unit->id === $activeUnit->id) + 1 }}
                            </span>

                            <i class="fas fa-chevron-down unit-dropdown-icon"></i>

                        </div>

                    </div>


                    {{-- Unit Dropdown Content --}}
                    <div class="collapse" id="unitSelectorDropdown">

                        <div class="border-top">

                            @foreach($course->units as $uIndex => $unit)

                                @php
                                    $isCurrentUnit = $activeUnit &&
                                                    $activeUnit->id === $unit->id;
                                @endphp

                                <a
                                    href="{{ route('courses.units', [
                                        $course->id,
                                        'unit' => $unit->id
                                    ]) }}"
                                    class="unit-selector-item
                                        d-flex align-items-center justify-content-between
                                        px-4 py-3 text-decoration-none
                                        {{ $isCurrentUnit ? 'active' : '' }}"
                                >

                                    <div class="d-flex align-items-center gap-3">

                                        <span class="unit-number">
                                            {{ $uIndex + 1 }}
                                        </span>

                                        <div>

                                            <div class="fw-semibold">
                                                Unit {{ $uIndex + 1 }}
                                            </div>

                                            <small class="text-muted">
                                                {{ $unit->title }}
                                            </small>

                                        </div>

                                    </div>

                                    @if($isCurrentUnit)

                                        <i class="fas fa-book-open text-primary"></i>

                                    @endif

                                </a>

                            @endforeach

                        </div>

                    </div>


                    {{-- Current Unit Information --}}
                    <div class="px-4 pb-4">

                        <h3 class="fw-bold mt-1 mb-2">
                            {{ $activeUnit->title }}
                        </h3>

                        @if($activeUnit->description)

                            <p class="text-muted mb-0">
                                {{ $activeUnit->description }}
                            </p>

                        @endif

                    </div>

                {{-- Sections --}}
                <div class="d-flex flex-column gap-4">

                    @forelse($activeUnit->sections as $sIndex => $section)

                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                            {{-- Section Header --}}
                            <div class="card-header bg-white border-bottom p-3 px-4
                                        d-flex align-items-center justify-content-between">

                                <div class="d-flex align-items-center gap-2">

                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3">
                                        Section {{ $sIndex + 1 }}
                                    </span>

                                    <h5 class="fw-bold mb-0 text-dark"
                                        style="font-size: 1.05rem;">
                                        {{ $section->title }}
                                    </h5>

                                </div>

                                <span class="text-muted small">
                                    {{ $section->lessons->count() }} Lessons
                                </span>

                            </div>


                            {{-- Lessons --}}
                            <div class="list-group list-group-flush">

                                @forelse($section->lessons as $lIndex => $item)

                                    @php
                                        $userLessonRecord = $userLessons->get($item->id);

                                        $quizScore = $userLessonRecord
                                            ? $userLessonRecord->pivot->quiz_score
                                            : null;

                                        $isCompleted = $userLessonRecord
                                            ? $userLessonRecord->pivot->is_completed
                                            : false;

                                        $hasHomework = $userLessonRecord
                                            ? !empty($userLessonRecord->pivot->homework_file_path)
                                            : false;
                                    @endphp

                                    <div class="list-group-item p-3 d-flex align-items-center
                                                justify-content-between gap-3 border-bottom-0">

                                        <div class="d-flex align-items-center gap-3 text-truncate">

                                            {{-- Status --}}
                                            <div>

                                                @if($isCompleted || $quizScore >= 80)

                                                    <i class="fas fa-check-circle text-success fs-5"
                                                       title="Completed"></i>

                                                @elseif($item->type === 'homework' && $hasHomework)

                                                    <i class="fas fa-clock text-info fs-5"
                                                       title="Homework Submitted (Pending Review)"></i>

                                                @elseif(!is_null($quizScore) && $quizScore < 50)

                                                    <i class="fas fa-exclamation-circle text-danger fs-5"
                                                       title="Needs Practice"></i>

                                                @elseif(!is_null($quizScore) &&
                                                        $quizScore >= 50 &&
                                                        $quizScore < 80)

                                                    <i class="fas fa-adjust text-warning fs-5"
                                                       title="Familiar"></i>

                                                @else

                                                    <i class="far fa-circle text-muted fs-5"
                                                       title="Not Started"></i>

                                                @endif

                                            </div>


                                            {{-- Lesson --}}
                                            <div class="text-truncate">

                                                <div class="d-flex align-items-center gap-2 mb-1">

                                                    @if($item->type === 'video')

                                                        <span class="badge bg-info-subtle text-info">
                                                            <i class="fas fa-video me-1"></i>
                                                            Video
                                                        </span>

                                                    @elseif($item->type === 'quiz')

                                                        <span class="badge bg-warning text-warning-emphasis">
                                                            <i class="fas fa-question-circle me-1"></i>
                                                            Quiz
                                                        </span>

                                                    @elseif($item->type === 'article')

                                                        <span class="badge bg-secondary-subtle text-secondary">
                                                            <i class="fas fa-file-alt me-1"></i>
                                                            Article
                                                        </span>

                                                    @elseif($item->type === 'document')

                                                        <span class="badge bg-dark-subtle text-dark">
                                                            <i class="fas fa-file-pdf me-1"></i>
                                                            Document
                                                        </span>

                                                    @elseif($item->type === 'homework')

                                                        <span class="badge bg-primary-subtle text-primary">
                                                            <i class="fas fa-file-signature me-1"></i>
                                                            Homework
                                                        </span>

                                                    @endif


                                                    @if(!is_null($quizScore))

                                                        <span class="badge rounded-pill
                                                            {{ $quizScore >= 80
                                                                ? 'bg-success-subtle text-success'
                                                                : ($quizScore >= 50
                                                                    ? 'bg-warning-subtle text-warning-emphasis'
                                                                    : 'bg-danger-subtle text-danger') }}">

                                                            Score: {{ $quizScore }}%

                                                        </span>

                                                    @endif


                                                    @if($item->type === 'homework' && $hasHomework)

                                                        <span class="badge bg-success-subtle text-success rounded-pill">

                                                            <i class="fas fa-check me-1"></i>
                                                            Submitted

                                                        </span>

                                                    @endif

                                                </div>


                                                <a href="{{ route('courses.learn', [$course->id, $item->id]) }}"
                                                   class="text-decoration-none fw-semibold text-dark
                                                   {{ $isCompleted ? 'text-secondary' : '' }}
                                                   hover-primary">

                                                    {{ $item->title }}

                                                </a>

                                            </div>

                                        </div>


                                        {{-- Action --}}
                                        <div class="flex-shrink-0">

                                            <a href="{{ route('courses.learn', [$course->id, $item->id]) }}"
                                               class="btn btn-sm btn-outline-primary rounded-pill px-3">

                                                @if($item->type === 'homework')

                                                    {{ $hasHomework
                                                        ? 'View Submission'
                                                        : 'Submit Homework' }}

                                                @else

                                                    {{ $isCompleted
                                                        ? 'Review'
                                                        : 'Start' }}

                                                @endif

                                            </a>

                                        </div>

                                    </div>

                                @empty

                                    <div class="p-3 text-center text-muted small">
                                        No lessons in this category yet.
                                    </div>

                                @endforelse

                            </div>

                        </div>

                    @empty

                        <div class="bg-white p-5 rounded-4 shadow-sm text-center text-muted border">

                            <i class="fas fa-folder-open fa-2x mb-2"></i>

                            <p class="mb-0">
                                No sections or categories found for this unit.
                            </p>

                        </div>

                    @endforelse

                </div>

            @else

                <div class="text-center py-5">

                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>

                    <h5>
                        Select a unit from the left menu to view its content.
                    </h5>

                </div>

            @endif

        </div>

    </div>
</div>


{{-- =========================================================
     MOBILE CSS
========================================================= --}}
<style>
    @media (max-width: 991.98px) {

        /*
         * Hide units sidebar completely on mobile/tablet.
         */
        .units-sidebar {
            display: none !important;
        }

        /*
         * Main content becomes full width.
         */
        .units-main-content {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
        }

        /*
         * Remove the desktop outer margin.
         */
        .units-overview-page .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
            border-radius: 0 !important;
        }
    }


    @media (max-width: 575.98px) {

        .units-overview-page {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .units-main-content {
            padding: 12px !important;
        }

        /*
         * Prevent section header from becoming too crowded.
         */
        .units-main-content .card-header {
            padding: 12px !important;
        }

        .units-main-content .card-header h5 {
            font-size: 0.95rem !important;
        }

        /*
         * Lesson rows can wrap naturally on small screens.
         */
        .units-main-content .list-group-item {
            align-items: flex-start !important;
        }

        .units-main-content .list-group-item > .flex-shrink-0 {
            flex-shrink: 0 !important;
        }

        /*
         * Make action buttons slightly smaller.
         */
        .units-main-content .btn {
            font-size: 0.78rem;
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
    }
</style>

@endsection