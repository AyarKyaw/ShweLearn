@extends('layouts.master')

@section('title', ($currentLesson ? $currentLesson->section->title : $course->title) . ' - Shwe Learn Learning Room')

@section('content')

@php

    $user = auth()->user();

    // =========================================================
    // USER LESSON PROGRESS
    // =========================================================

    $userLessons = $user
        ? $user->lessons()
            ->where('lesson_user.course_id', $course->id)
            ->get()
            ->keyBy('id')
        : collect();


    // =========================================================
    // ACTIVE SECTION / UNIT
    // =========================================================

    $activeSection = $currentLesson
        ? $currentLesson->section
        : null;

    $activeUnit = $activeSection
        ? $activeSection->unit
        : null;

    $sectionLessons = $activeSection
        ? $activeSection->lessons
        : collect();


    // =========================================================
    // SECTION PROGRESS
    // =========================================================

    $totalSectionLessons = $sectionLessons->count();

    $completedSectionCount = $sectionLessons->filter(function ($lesson) use ($userLessons) {

        $record = $userLessons->get($lesson->id);

        return $record &&
            (
                $record->pivot->is_completed ||
                $record->pivot->quiz_score >= 80
            );

    })->count();


    $sectionProgressPercent = $totalSectionLessons > 0
        ? round(($completedSectionCount / $totalSectionLessons) * 100)
        : 0;


    // =========================================================
    // LESSON NAVIGATION
    // =========================================================

    $allLessons = $course->units->flatMap(
        fn($u) => $u->sections->flatMap(
            fn($s) => $s->lessons
        )
    );


    $currentIndex = $currentLesson
        ? $allLessons->search(
            fn($item) => $item->id === $currentLesson->id
        )
        : false;


    $prevLesson = $currentIndex !== false
        ? ($allLessons[$currentIndex - 1] ?? null)
        : null;


    $nextLesson = $currentIndex !== false
        ? ($allLessons[$currentIndex + 1] ?? null)
        : null;


    // =========================================================
    // MOBILE SECTION NAVIGATION
    //
    // Only sections from the CURRENT UNIT are shown.
    //
    // Example:
    //
    // < Section 1 >
    // < Section 2 >
    // < Section 3 >
    //
    // =========================================================

    $mobileSections = $activeUnit
        ? $activeUnit->sections->values()
        : collect();


    $mobileCurrentSectionIndex = $activeSection
        ? $mobileSections->search(
            fn($section) => $section->id === $activeSection->id
        )
        : 0;


    if ($mobileCurrentSectionIndex === false) {
        $mobileCurrentSectionIndex = 0;
    }

@endphp


<div
    class="container-fluid learning-room bg-light mt-5 pt-2 pt-lg-4"
    style="min-height: 85vh;"
>

    <div
        class="row g-0 learning-room-card bg-white mx-0 mx-md-4 align-items-stretch"
    >


        <!-- =====================================================
             LEFT SIDEBAR
        ====================================================== -->

        <div class="col-lg-3 d-none d-lg-block border-end bg-white">


            <!-- =================================================
                 SECTION HEADER & PROGRESS
            ================================================== -->

            <div class="p-4 bg-primary text-white">

                <div class="d-flex align-items-center gap-2 mb-1 opacity-75 small">

                    <i class="fas fa-layer-group"></i>

                    <span class="text-truncate">
                        {{ $activeUnit->title ?? $course->title }}
                    </span>

                </div>


                <h5
                    class="fw-bold mb-2 text-white text-truncate"
                    title="{{ $activeSection->title ?? 'Course Lessons' }}"
                >
                    {{ $activeSection->title ?? 'Course Lessons' }}
                </h5>


                <div class="d-flex align-items-center justify-content-between mt-3">

                    <small class="opacity-75">
                        Section Progress
                    </small>

                    <small class="fw-bold">

                        {{ $completedSectionCount }}/{{ $totalSectionLessons }}

                        ({{ $sectionProgressPercent }}%)

                    </small>

                </div>


                <div
                    class="progress mt-1"
                    style="height: 6px; background-color: rgba(255,255,255,0.25);"
                >

                    <div
                        class="progress-bar bg-warning"
                        role="progressbar"
                        style="width: {{ $sectionProgressPercent }}%"
                    ></div>

                </div>

            </div>


            <!-- =================================================
                 ACTIVE SECTION LESSONS
            ================================================== -->

            <div
                class="list-group list-group-flush overflow-auto learning-sidebar-lessons"
                style="max-height: 600px;"
            >

                @forelse($sectionLessons as $index => $item)

                    @php

                        $isActive = $currentLesson &&
                            $currentLesson->id === $item->id;


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


                    <a
                        href="{{ route('courses.learn', [$course->id, $item->id]) }}"
                        class="
                            list-group-item
                            list-group-item-action
                            p-3
                            d-flex
                            align-items-center
                            gap-3
                            border-bottom

                            {{ $isActive
                                ? 'bg-primary-subtle border-start border-primary border-4 text-primary fw-bold'
                                : ''
                            }}
                        "
                    >


                        <!-- =================================================
                             STATUS ICON
                        ================================================== -->

                        <div>

                            @if(is_null($quizScore) && !$isCompleted && !$hasHomework)

                                @if($isActive)

                                    <i
                                        class="fas fa-play-circle text-primary fs-5"
                                        title="Current Lesson"
                                    ></i>

                                @else

                                    <i
                                        class="far fa-circle text-muted fs-5"
                                        title="Not Started"
                                    ></i>

                                @endif


                            @elseif($item->type === 'homework' && $hasHomework)

                                <i
                                    class="fas fa-check-circle text-success fs-5"
                                    title="Homework Submitted"
                                ></i>


                            @elseif($quizScore < 50 && !$isCompleted)

                                <i
                                    class="fas fa-exclamation-circle text-danger fs-5"
                                    title="Needs Practice ({{ $quizScore }}%)"
                                ></i>


                            @elseif($quizScore >= 50 && $quizScore < 80 && !$isCompleted)

                                <i
                                    class="fas fa-adjust text-warning fs-5"
                                    title="Familiar ({{ $quizScore }}%)"
                                ></i>


                            @else

                                <i
                                    class="fas fa-check-circle text-success fs-5"
                                    title="Completed"
                                ></i>

                            @endif

                        </div>


                        <!-- =================================================
                             LESSON DETAILS
                        ================================================== -->

                        <div class="flex-grow-1 text-truncate">

                            <div
                                class="
                                    d-flex
                                    align-items-center
                                    justify-content-between
                                "
                            >

                                <span class="d-block small text-muted">
                                    Lesson {{ $index + 1 }}
                                </span>


                                @if(!is_null($quizScore))

                                    <span
                                        class="
                                            badge
                                            rounded-pill

                                            {{ $quizScore >= 80
                                                ? 'bg-success-subtle text-success'
                                                : ($quizScore >= 50
                                                    ? 'bg-warning-subtle text-warning-emphasis'
                                                    : 'bg-danger-subtle text-danger')
                                            }}
                                        "
                                        style="font-size: 0.7rem;"
                                    >
                                        {{ $quizScore }}%
                                    </span>


                                @elseif($item->type === 'homework' && $hasHomework)

                                    <span
                                        class="
                                            badge
                                            bg-success-subtle
                                            text-success
                                            rounded-pill
                                        "
                                        style="font-size: 0.7rem;"
                                    >
                                        Submitted
                                    </span>

                                @endif

                            </div>


                            <span
                                class="
                                    text-truncate
                                    d-block

                                    {{ ($isCompleted || $hasHomework) && !$isActive
                                        ? 'text-secondary'
                                        : ''
                                    }}
                                "
                            >
                                {{ $item->title }}
                            </span>

                        </div>

                    </a>

                @empty

                    <div class="p-4 text-center text-muted">
                        No lessons in this section.
                    </div>

                @endforelse

            </div>

        </div>


        <!-- =====================================================
             MAIN CONTENT AREA
        ====================================================== -->

        <div
            class="
                col-12
                col-lg-9
                bg-light
                p-2
                p-sm-3
                p-lg-5
                d-flex
                flex-column
                justify-content-between
                learning-main-column
            "
            style="padding-bottom: 0 !important;"
        >

            @if($currentLesson)


                <!-- =================================================
                     LESSON AREA
                ================================================== -->

                <div class="learning-lesson-area">


                    <!-- =================================================
                         DESKTOP BREADCRUMB
                    ================================================== -->

                    <nav
                        aria-label="breadcrumb"
                        class="mb-2 mb-lg-4 desktop-lesson-breadcrumb"
                    >

                        <ol
                            class="
                                breadcrumb
                                bg-white
                                px-2
                                px-sm-3
                                py-2
                                rounded-3
                                border
                                shadow-sm
                                align-items-center
                                mb-0
                                text-truncate
                            "
                            style="font-size: 0.8rem;"
                        >

                            <!-- Course -->

                            <li class="breadcrumb-item">

                                <a
                                    href="{{ route('courses.my', $course->id) }}"
                                    class="
                                        text-decoration-none
                                        fw-semibold
                                        text-secondary
                                        hover-primary
                                    "
                                >

                                    <i class="fas fa-graduation-cap me-1"></i>

                                    {{ $course->title }}

                                </a>

                            </li>


                            <!-- Unit -->

                            @if($activeUnit)

                                <li class="breadcrumb-item">

                                    <a
                                        href="{{ route('courses.units', [$course->id, 'unit' => $activeUnit->id]) }}"
                                        class="
                                            text-decoration-none
                                            fw-semibold
                                            text-secondary
                                            hover-primary
                                        "
                                    >

                                        {{ $activeUnit->title }}

                                    </a>

                                </li>

                            @endif


                            <!-- Section -->

                            @if($activeSection)

                                <li class="breadcrumb-item">

                                    <a
                                        href="{{ route('courses.units', [$course->id, 'unit' => $activeUnit->id]) }}"
                                        class="
                                            text-decoration-none
                                            fw-semibold
                                            text-secondary
                                            hover-primary
                                        "
                                    >

                                        {{ $activeSection->title }}

                                    </a>

                                </li>

                            @endif


                            <!-- Current Lesson -->

                            <li
                                class="
                                    breadcrumb-item
                                    active
                                    text-primary
                                    fw-bold
                                    text-truncate
                                "
                                aria-current="page"
                                style="max-width: 180px;"
                            >

                                {{ $currentLesson->title }}

                            </li>

                        </ol>

                    </nav>


                    <!-- =================================================
                         MOBILE LESSON SELECTOR
                    ================================================== -->

                    <div class="mobile-lesson-selector mb-2">

                        <button
                            type="button"
                            class="mobile-lesson-title-button"
                            data-bs-toggle="modal"
                            data-bs-target="#mobileLessonModal"
                        >

                            <div class="mobile-lesson-title-content">

                                <i class="fas fa-book-open"></i>

                                <span>
                                    {{ $currentLesson->title }}
                                </span>

                            </div>


                            <i class="fas fa-chevron-down mobile-lesson-chevron"></i>

                        </button>

                    </div>


                    <!-- =================================================
                         MOBILE LESSON MODAL
                    ================================================== -->

                    <div
                        class="modal fade"
                        id="mobileLessonModal"
                        tabindex="-1"
                        aria-labelledby="mobileLessonModalLabel"
                        aria-hidden="true"
                    >

                        <div
                            class="
                                modal-dialog
                                modal-dialog-scrollable
                                modal-fullscreen-sm-down
                            "
                        >

                            <div class="modal-content">


                                <!-- =================================================
                                     MODAL HEADER
                                ================================================== -->

                                <div class="modal-header">

                                    <div class="min-w-0">

                                        <div
                                            class="
                                                text-primary
                                                fw-bold
                                                small
                                                text-uppercase
                                                mb-1
                                            "
                                        >
                                            Lessons
                                        </div>


                                        <h5
                                            class="
                                                modal-title
                                                fw-bold
                                                mb-0
                                                text-truncate
                                            "
                                            id="mobileLessonModalLabel"
                                        >
                                            {{ $activeUnit->title ?? $course->title }}
                                        </h5>

                                    </div>


                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                    ></button>

                                </div>


                                <!-- =================================================
                                     SECTION NAVIGATOR + LESSONS
                                ================================================== -->

                                <div class="modal-body p-0">

                                    @if($mobileSections->count())


                                        <div
                                            id="mobileSectionNavigator"
                                            class="mobile-section-navigator-container"
                                        >


                                            @foreach($mobileSections as $sIndex => $section)

                                                @php

                                                    $sectionHasCurrentLesson =
                                                        $section->lessons->contains(
                                                            'id',
                                                            $currentLesson->id
                                                        );

                                                @endphp


                                                <!-- =========================================
                                                     ONE SECTION PANEL
                                                ========================================== -->

                                                <div
                                                    class="
                                                        mobile-section-panel
                                                        {{ $sectionHasCurrentLesson
                                                            ? 'active-section-panel'
                                                            : ''
                                                        }}
                                                    "
                                                    data-section-index="{{ $sIndex }}"
                                                    style="{{ $sIndex === $mobileCurrentSectionIndex
                                                        ? ''
                                                        : 'display:none;'
                                                    }}"
                                                >


                                                    <!-- =====================================
                                                         SECTION NAVIGATION
                                                    ====================================== -->

                                                    <div class="mobile-section-navigation">


                                                        <!-- Previous Section -->

                                                        <button
                                                            type="button"
                                                            class="
                                                                mobile-section-nav-button
                                                                mobile-section-prev
                                                            "
                                                            data-target="{{ $sIndex - 1 }}"
                                                            {{ $sIndex === 0 ? 'disabled' : '' }}
                                                            aria-label="Previous section"
                                                        >

                                                            <i class="fas fa-chevron-left"></i>

                                                        </button>


                                                        <!-- Current Section -->

                                                        <div class="mobile-section-current-title">

                                                            <div class="mobile-section-small-label">

                                                                Section {{ $sIndex + 1 }}

                                                            </div>


                                                            <div
                                                                class="mobile-section-title-text"
                                                                title="{{ $section->title }}"
                                                            >

                                                                {{ $section->title }}

                                                            </div>

                                                        </div>


                                                        <!-- Next Section -->

                                                        <button
                                                            type="button"
                                                            class="
                                                                mobile-section-nav-button
                                                                mobile-section-next
                                                            "
                                                            data-target="{{ $sIndex + 1 }}"
                                                            {{ $sIndex === $mobileSections->count() - 1 ? 'disabled' : '' }}
                                                            aria-label="Next section"
                                                        >

                                                            <i class="fas fa-chevron-right"></i>

                                                        </button>

                                                    </div>


                                                    <!-- =====================================
                                                         SECTION LESSONS
                                                    ====================================== -->

                                                    <div class="mobile-section-lessons">


                                                        @forelse($section->lessons as $lesson)

                                                            @php

                                                                $isCurrentLesson =
                                                                    $currentLesson &&
                                                                    $currentLesson->id === $lesson->id;


                                                                $userLessonRecord =
                                                                    $userLessons->get($lesson->id);


                                                                $quizScore =
                                                                    $userLessonRecord
                                                                        ? $userLessonRecord->pivot->quiz_score
                                                                        : null;


                                                                $isCompleted =
                                                                    $userLessonRecord
                                                                        ? $userLessonRecord->pivot->is_completed
                                                                        : false;


                                                                $hasHomework =
                                                                    $userLessonRecord
                                                                        ? !empty(
                                                                            $userLessonRecord->pivot->homework_file_path
                                                                        )
                                                                        : false;

                                                            @endphp


                                                            <a
                                                                href="{{ route('courses.learn', [$course->id, $lesson->id]) }}"
                                                                class="
                                                                    mobile-modal-lesson
                                                                    {{ $isCurrentLesson
                                                                        ? 'current-lesson'
                                                                        : ''
                                                                    }}
                                                                "
                                                            >


                                                                <!-- Lesson Icon -->

                                                                <div class="mobile-lesson-icon">

                                                                    @if($lesson->type === 'video')

                                                                        <i class="fas fa-video"></i>

                                                                    @elseif($lesson->type === 'quiz')

                                                                        <i class="fas fa-question-circle"></i>

                                                                    @elseif($lesson->type === 'article')

                                                                        <i class="fas fa-file-alt"></i>

                                                                    @elseif($lesson->type === 'document')

                                                                        <i class="fas fa-file-pdf"></i>

                                                                    @elseif($lesson->type === 'homework')

                                                                        <i class="fas fa-file-signature"></i>

                                                                    @else

                                                                        <i class="fas fa-book"></i>

                                                                    @endif

                                                                </div>


                                                                <!-- Lesson Information -->

                                                                <div class="mobile-lesson-info">

                                                                    <div class="mobile-lesson-name">

                                                                        {{ $lesson->title }}

                                                                    </div>


                                                                    <div class="mobile-lesson-meta">

                                                                        {{ ucfirst($lesson->type) }}


                                                                        @if(!is_null($quizScore))

                                                                            <span class="ms-2">
                                                                                {{ $quizScore }}%
                                                                            </span>

                                                                        @elseif($lesson->type === 'homework' && $hasHomework)

                                                                            <span class="ms-2 text-success">
                                                                                Submitted
                                                                            </span>

                                                                        @elseif($isCompleted)

                                                                            <span class="ms-2 text-success">
                                                                                Completed
                                                                            </span>

                                                                        @endif

                                                                    </div>

                                                                </div>


                                                                <!-- Current / Arrow -->

                                                                @if($isCurrentLesson)

                                                                    <i
                                                                        class="
                                                                            fas
                                                                            fa-check-circle
                                                                            mobile-current-icon
                                                                        "
                                                                    ></i>

                                                                @else

                                                                    <i
                                                                        class="
                                                                            fas
                                                                            fa-chevron-right
                                                                            mobile-lesson-arrow
                                                                        "
                                                                    ></i>

                                                                @endif

                                                            </a>

                                                        @empty

                                                            <div class="mobile-no-lessons">

                                                                No lessons in this section.

                                                            </div>

                                                        @endforelse

                                                    </div>

                                                </div>

                                            @endforeach

                                        </div>


                                    @else

                                        <div class="p-4 text-center text-muted">

                                            No sections available.

                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         PLAYER / LESSON CONTENT
                    ================================================== -->

                    <div
                        class="
                            bg-white
                            rounded-3
                            rounded-lg-4
                            shadow-sm
                            border
                            overflow-hidden
                            lesson-content

                            {{ $currentLesson->type === 'video'
                                ? 'video-lesson-content'
                                : ''
                            }}
                        "
                    >

                        @if($currentLesson->type === 'video')

                            @include(
                                'course.partials.video',
                                ['lesson' => $currentLesson]
                            )


                        @elseif($currentLesson->type === 'quiz')

                            @include(
                                'course.partials.quiz',
                                [
                                    'lesson' => $currentLesson,
                                    'questions' => $questions ?? []
                                ]
                            )


                        @elseif($currentLesson->type === 'article')

                            @include(
                                'course.partials.article',
                                ['lesson' => $currentLesson]
                            )


                        @elseif($currentLesson->type === 'homework')

                            @include(
                                'course.partials.homework',
                                [
                                    'lesson' => $currentLesson,
                                    'userLessons' => $userLessons
                                ]
                            )

                        @endif

                    </div>

                </div>


                <!-- =================================================
                     NEXT LESSON
                ================================================== -->

                @if($nextLesson)

                    <div class="next-lesson-fixed" id="nextLessonBar">

                        <a
                            href="{{ route('courses.learn', [$course->id, $nextLesson->id]) }}"
                            class="btn btn-primary next-lesson-button"
                        >

                            <span>
                                Up next: {{ ucfirst($nextLesson->type) }}
                            </span>

                            <i class="fas fa-arrow-right ms-2"></i>

                        </a>

                    </div>

                @endif


            @else


                <!-- =================================================
                     NO LESSON
                ================================================== -->

                <div class="text-center py-5 my-auto">

                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>

                    <h5>
                        Select a lesson to begin learning.
                    </h5>

                </div>

            @endif

        </div>

    </div>

</div>


@push('styles')

<style>

/* =========================================================
   LEARNING ROOM
========================================================= */

.learning-room {
    width: 100%;
}


.learning-room-card {
    min-height: 80vh;
}


.learning-main-column {
    min-width: 0;
}


.learning-lesson-area {
    width: 100%;
}


/* =========================================================
   DESKTOP BREADCRUMB
========================================================= */

.learning-room .breadcrumb {
    overflow: hidden;
    white-space: nowrap;
}


.learning-room .breadcrumb-item {
    min-width: 0;
}


.learning-room .breadcrumb-item a,
.learning-room .breadcrumb-item.active {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}


/* =========================================================
   LESSON CONTENT
========================================================= */

.lesson-content {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
}


/* =========================================================
   LESSON NAVIGATION
========================================================= */

.lesson-navigation-wrapper {
    position: relative;
    margin-top: 24px;
    padding-top: 16px;
    min-height: 60px;
}


.lesson-previous-navigation {
    display: flex;
    justify-content: flex-start;
    align-items: center;
}


/* =========================================================
   NEXT LESSON
========================================================= */

.next-lesson-fixed {
    position: fixed;

    left: 0;
    right: 0;
    bottom: 0;

    z-index: 1050;

    display: flex;
    justify-content: flex-end;
    align-items: center;

    padding: 12px 24px;

    background: rgba(255, 255, 255, 0.96);

    border-top: 1px solid #dee2e6;

    box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.08);

    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}


.next-lesson-button {
    min-height: 48px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 24px;

    border-radius: 999px;

    font-weight: 600;

    white-space: nowrap;

    background: #0d6efd !important;

    text-decoration: none;
}


/* =========================================================
   DESKTOP ROOM BOTTOM SPACE
========================================================= */

.learning-room {
    padding-bottom: 90px !important;
}


/* =========================================================
   MOBILE SELECTOR DEFAULT
========================================================= */

.mobile-lesson-selector,
#mobileLessonModal {
    display: none;
}


/* =========================================================
   DESKTOP
========================================================= */

@media (min-width: 992px) {

    .learning-room-card {
        align-items: stretch;
    }


    .learning-room-card > .col-lg-3,
    .learning-room-card > .col-lg-9 {

        display: flex;
        flex-direction: column;
    }


    .learning-room-card > .col-lg-9 {
        min-height: 80vh;
    }


    .learning-room-card .lesson-content {
        flex: 1 1 auto;
        min-height: 0;
    }

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 767.98px) {


    /* =====================================================
       LEARNING ROOM
    ===================================================== */

    .learning-room {

        width: 100%;

        padding-left: 0 !important;
        padding-right: 0 !important;

        margin-top: 56px !important;

        padding-top: 0 !important;

        padding-bottom: 50px !important;
    }


    /* =====================================================
       FULL WIDTH LEARNING CARD
    ===================================================== */

    .learning-room-card {

        width: 100% !important;

        margin-left: 0 !important;
        margin-right: 0 !important;

        border-radius: 0 !important;

        border-left: 0 !important;
        border-right: 0 !important;

        box-shadow: none !important;
    }


    /* =====================================================
       MAIN CONTENT
    ===================================================== */

    .learning-room .col-lg-9 {

        width: 100%;

        max-width: 100%;
    }


    /* =====================================================
       DESKTOP BREADCRUMB HIDDEN
    ===================================================== */

    .desktop-lesson-breadcrumb {
        display: none !important;
    }


    /* =====================================================
       MOBILE LESSON SELECTOR
    ===================================================== */

    .mobile-lesson-selector {

        display: block;

        width: 100%;
    }


    .mobile-lesson-title-button {

        width: 100%;

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 12px;

        padding: 11px 13px;

        border: 1px solid #dee2e6;

        border-radius: 10px;

        background: #fff;

        color: #212529;

        text-align: left;

        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);

        cursor: pointer;
    }


    .mobile-lesson-title-button:active {

        background: #f8f9fa;
    }


    .mobile-lesson-title-content {

        min-width: 0;

        flex: 1;

        display: flex;

        align-items: center;

        gap: 9px;
    }


    .mobile-lesson-title-content > i {

        flex-shrink: 0;

        color: #0d6efd;

        font-size: 0.9rem;
    }


    .mobile-lesson-title-content span {

        min-width: 0;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;

        font-size: 0.88rem;

        font-weight: 600;
    }


    .mobile-lesson-chevron {

        flex-shrink: 0;

        color: #6c757d;

        font-size: 0.7rem;
    }


    /* =====================================================
       MOBILE MODAL
    ===================================================== */

    #mobileLessonModal .modal-content {

        border: 0;

        border-radius: 16px;

        overflow: hidden;
    }


    #mobileLessonModal .modal-header {

        padding: 16px;

        border-bottom: 1px solid #e9ecef;

        background: #fff;
    }


    #mobileLessonModal .modal-body {

        background: #f8f9fa;
    }


    /* =====================================================
       SECTION NAVIGATOR
    ===================================================== */

    .mobile-section-navigator-container {

        width: 100%;
    }


    .mobile-section-panel {

        width: 100%;
    }


    /* =====================================================
       SECTION HEADER WITH < >
    ===================================================== */

    .mobile-section-navigation {

        width: 100%;

        min-height: 68px;

        display: flex;

        align-items: stretch;

        background: #fff;

        border-bottom: 1px solid #e1e5e9;

        position: sticky;

        top: 0;

        z-index: 2;
    }


    .mobile-section-nav-button {

        width: 54px;

        min-width: 54px;

        display: flex;

        align-items: center;

        justify-content: center;

        border: 0;

        background: #fff;

        color: #0d6efd;

        font-size: 0.8rem;

        cursor: pointer;

        transition:
            background-color 0.15s ease,
            color 0.15s ease;
    }


    .mobile-section-nav-button:hover {

        background: #f1f5ff;

        color: #0a58ca;
    }


    .mobile-section-nav-button:active {

        background: #e7f0ff;
    }


    .mobile-section-nav-button:disabled {

        color: #ced4da;

        background: #f8f9fa;

        cursor: default;
    }


    .mobile-section-current-title {

        min-width: 0;

        flex: 1;

        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: center;

        padding: 9px 8px;

        text-align: center;

        border-left: 1px solid #edf0f2;

        border-right: 1px solid #edf0f2;
    }


    .mobile-section-small-label {

        margin-bottom: 3px;

        font-size: 0.64rem;

        line-height: 1;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.04em;

        color: #6c757d;
    }


    .mobile-section-title-text {

        width: 100%;

        font-size: 0.9rem;

        line-height: 1.3;

        font-weight: 700;

        color: #212529;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;
    }


    /* =====================================================
       SECTION LESSONS
    ===================================================== */

    .mobile-section-lessons {

        padding: 8px 10px 14px;

        background: #f8f9fa;
    }


    .mobile-modal-lesson {

        width: 100%;

        min-height: 56px;

        display: flex;

        align-items: center;

        gap: 10px;

        padding: 9px 10px;

        margin-bottom: 4px;

        border-radius: 9px;

        background: #fff;

        color: #343a40;

        text-decoration: none;

        border: 1px solid transparent;

        transition:
            background-color 0.15s ease,
            color 0.15s ease,
            border-color 0.15s ease;
    }


    .mobile-modal-lesson:last-child {

        margin-bottom: 0;
    }


    .mobile-modal-lesson:hover {

        background: #f1f5f9;

        color: #0d6efd;
    }


    /* =====================================================
       CURRENT LESSON
    ===================================================== */

    .mobile-modal-lesson.current-lesson {

        background: #eaf3ff;

        color: #0d6efd;

        border-color: #cfe2ff;

        font-weight: 600;
    }


    /* =====================================================
       LESSON ICON
    ===================================================== */

    .mobile-lesson-icon {

        width: 32px;

        height: 32px;

        min-width: 32px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 8px;

        background: #e9ecef;

        color: #6c757d;

        font-size: 0.72rem;
    }


    .mobile-modal-lesson.current-lesson
    .mobile-lesson-icon {

        background: #0d6efd;

        color: #fff;
    }


    /* =====================================================
       LESSON INFORMATION
    ===================================================== */

    .mobile-lesson-info {

        min-width: 0;

        flex: 1;
    }


    .mobile-lesson-name {

        font-size: 0.83rem;

        line-height: 1.35;

        font-weight: 600;

        overflow: hidden;

        text-overflow: ellipsis;

        display: -webkit-box;

        -webkit-line-clamp: 2;

        -webkit-box-orient: vertical;
    }


    .mobile-lesson-meta {

        margin-top: 2px;

        font-size: 0.68rem;

        color: #868e96;
    }


    /* =====================================================
       CURRENT LESSON ICON
    ===================================================== */

    .mobile-current-icon {

        flex-shrink: 0;

        color: #198754;

        font-size: 0.95rem;
    }


    /* =====================================================
       LESSON ARROW
    ===================================================== */

    .mobile-lesson-arrow {

        flex-shrink: 0;

        color: #adb5bd;

        font-size: 0.65rem;
    }


    /* =====================================================
       NO LESSONS
    ===================================================== */

    .mobile-no-lessons {

        padding: 20px 10px;

        text-align: center;

        font-size: 0.78rem;

        color: #6c757d;
    }


    /* =====================================================
       VIDEO
       TOUCH BOTH SIDES OF PHONE SCREEN
    ===================================================== */

    .video-lesson-content {

        width: calc(100% + 1rem) !important;

        max-width: none !important;

        margin-left: -0.5rem !important;

        margin-right: -0.5rem !important;

        border-left: 0 !important;

        border-right: 0 !important;

        border-radius: 0 !important;

        box-shadow: none !important;
    }


    /* =====================================================
       VIDEO PARTIAL WRAPPER
    ===================================================== */

    .video-lesson-content .video-partial-wrapper {

        padding-left: 0 !important;

        padding-right: 0 !important;
    }


    /* =====================================================
       VIDEO RATIO
    ===================================================== */

    .video-lesson-content .ratio {

        width: 100% !important;

        margin-left: 0 !important;

        margin-right: 0 !important;

        border-radius: 0 !important;
    }


    /* =====================================================
       YOUTUBE
    ===================================================== */

    .video-lesson-content iframe {

        display: block;

        width: 100% !important;

        max-width: 100% !important;

        height: 100% !important;
    }


    /* =====================================================
       LOCAL VIDEO
    ===================================================== */

    .video-lesson-content video {

        display: block;

        width: 100% !important;

        max-width: 100% !important;

        height: 100% !important;
    }


    /* =====================================================
       NORMAL LESSON CONTENT
    ===================================================== */

    .lesson-content {

        border-radius: 10px !important;
    }


    /* =====================================================
       VIDEO SQUARE EDGES
    ===================================================== */

    .video-lesson-content {

        border-radius: 0 !important;
    }


    /* =====================================================
       NORMAL BUTTONS
    ===================================================== */

    .learning-room .btn {

        min-height: 44px;
    }


    /* =====================================================
       PREVIOUS BUTTON
    ===================================================== */

    .lesson-previous-navigation {

        margin-bottom: 10px;
    }


    /* =====================================================
       NEXT LESSON
    ===================================================== */

    .next-lesson-fixed {

        left: 0;

        right: 0;

        bottom: 80px;

        padding: 10px 12px;

        justify-content: center;
    }


    /* =====================================================
       NEXT LESSON BUTTON
    ===================================================== */

    .next-lesson-button {

        width: 100%;

        min-height: 50px;

        border-radius: 12px;

        font-size: 0.95rem;
    }

}


/* =========================================================
   VERY SMALL PHONES
========================================================= */

@media (max-width: 375px) {

    .learning-room .btn {

        font-size: 0.85rem;
    }


    .next-lesson-fixed {

        bottom: 90px;

        padding: 8px 10px;
    }


    .next-lesson-button {

        min-height: 48px;

        font-size: 0.9rem;
    }


    .mobile-lesson-title-button {

        padding: 10px 11px;
    }


    .mobile-lesson-title-content span {

        font-size: 0.82rem;
    }


    .mobile-section-nav-button {

        width: 46px;

        min-width: 46px;
    }


    .mobile-section-current-title {

        padding-left: 5px;

        padding-right: 5px;
    }


    .mobile-section-title-text {

        font-size: 0.82rem;
    }


    .mobile-section-lessons {

        padding-left: 8px;

        padding-right: 8px;
    }


    .mobile-modal-lesson {

        padding: 8px;
    }

}


/* =========================================================
   TABLET / DESKTOP
========================================================= */

@media (min-width: 768px) {

    .next-lesson-fixed {

        padding-left: 24px;

        padding-right: 24px;
    }


    .next-lesson-button {

        min-width: 160px;
    }

}

</style>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | MOBILE SECTION NAVIGATION
    |--------------------------------------------------------------------------
    |
    | The modal contains all sections from the current unit.
    |
    | Only one section is visible at a time.
    |
    | Example:
    |
    |       <   Section 1   >
    |
    |       Lesson 1
    |       Lesson 2
    |       Lesson 3
    |
    | Clicking > changes to:
    |
    |       <   Section 2   >
    |
    |       Lesson 1
    |       Lesson 2
    |
    |--------------------------------------------------------------------------
    */


    const sectionContainer =
        document.getElementById('mobileSectionNavigator');


    if (!sectionContainer) {
        return;
    }


    const sectionPanels =
        Array.from(
            sectionContainer.querySelectorAll('.mobile-section-panel')
        );


    if (!sectionPanels.length) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Initial Section
    |--------------------------------------------------------------------------
    */

    let currentSectionIndex =
        {{ $mobileCurrentSectionIndex }};


    /*
    |--------------------------------------------------------------------------
    | Show Section
    |--------------------------------------------------------------------------
    */

    function showMobileSection(index) {

        /*
        | Prevent invalid indexes
        */

        if (
            index < 0 ||
            index >= sectionPanels.length
        ) {
            return;
        }


        currentSectionIndex = index;


        /*
        |--------------------------------------------------------------------------
        | Hide / Show Sections
        |--------------------------------------------------------------------------
        */

        sectionPanels.forEach(function (panel, panelIndex) {

            if (panelIndex === currentSectionIndex) {

                panel.style.display = '';

            } else {

                panel.style.display = 'none';

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Update Previous / Next Buttons
        |--------------------------------------------------------------------------
        */

        sectionPanels.forEach(function (panel, panelIndex) {

            const previousButton =
                panel.querySelector('.mobile-section-prev');


            const nextButton =
                panel.querySelector('.mobile-section-next');


            if (previousButton) {

                previousButton.disabled =
                    panelIndex === 0;

            }


            if (nextButton) {

                nextButton.disabled =
                    panelIndex === sectionPanels.length - 1;

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Scroll Modal Body To Top
        |--------------------------------------------------------------------------
        */

        const modalBody =
            document.querySelector(
                '#mobileLessonModal .modal-body'
            );


        if (modalBody) {

            modalBody.scrollTop = 0;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Previous / Next Click
    |--------------------------------------------------------------------------
    */

    sectionContainer.addEventListener(
        'click',
        function (event) {

            const previousButton =
                event.target.closest(
                    '.mobile-section-prev'
                );


            const nextButton =
                event.target.closest(
                    '.mobile-section-next'
                );


            /*
            | Previous section
            */

            if (
                previousButton &&
                !previousButton.disabled
            ) {

                const target =
                    parseInt(
                        previousButton.dataset.target,
                        10
                    );


                if (!isNaN(target)) {

                    showMobileSection(target);

                }


                return;
            }


            /*
            | Next section
            */

            if (
                nextButton &&
                !nextButton.disabled
            ) {

                const target =
                    parseInt(
                        nextButton.dataset.target,
                        10
                    );


                if (!isNaN(target)) {

                    showMobileSection(target);

                }

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial Display
    |--------------------------------------------------------------------------
    */

    showMobileSection(
        currentSectionIndex
    );

});

</script>

@endpush

@endsection