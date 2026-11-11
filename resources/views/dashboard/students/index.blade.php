@extends('layouts.student')

@section('title', 'Dashboard | MIFFA ACADEMY')

@section('content')
<div class="p-2">
    <!-- Welcome Greeting -->
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">
            Hello, {{ auth()->user()->name ?? 'Student' }}! 👋
        </h2>
        <p class="text-muted">
            @if(isset($overallProgress) && $overallProgress > 0)
                Keep going! You've completed {{ $overallProgress }}% of your course.
            @else
                Welcome back! Check your active courses and upcoming tasks below.
            @endif
        </p>
    </div>

    <div class="row g-4">
        <!-- Main Column: Active Course & Upcoming Classes -->
        <div class="col-12 col-lg-8">

            <!-- Active Course & Progress -->
            @if(!empty($activeCourse))
                <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: #0b3281;">
                            <i class="fas fa-chart-line"></i> Course Progress: {{ $activeCourse->title }}
                        </h6>
                    </div>

                    <!-- Progress Bar -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="progress flex-grow-1" style="height: 12px; border-radius: 20px; background-color: #e9ecef;">
                            <div class="progress-bar" 
                                 role="progressbar" 
                                 style="width: {{ $overallProgress ?? 0 }}%; background-color: #ff7a00; border-radius: 20px;" 
                                 aria-valuenow="{{ $overallProgress ?? 0 }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <span class="fw-bold" style="color: #ff7a00 !important;">
                            {{ $overallProgress ?? 0 }}%
                        </span>
                    </div>

                    <!-- Unfinished / Up Next Lessons Accordion -->
                    @if(isset($unfinishedLessons) && $unfinishedLessons->count() > 0)
                        <div class="accordion border-0 mt-2" id="unfinishedLessonsAccordion">
                            <div class="accordion-item border-0 bg-light rounded-3">
                                <h2 class="accordion-header" id="headingUnfinished">
                                    <button class="accordion-button collapsed bg-light rounded-3 shadow-none py-2 px-3 fw-semibold text-dark fs-7" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapseUnfinished" 
                                            aria-expanded="false" 
                                            aria-controls="collapseUnfinished">
                                        <i class="fas fa-list-ul me-2 text-primary"></i> 
                                        Continue Learning / Up Next ({{ $unfinishedLessons->count() }})
                                    </button>
                                </h2>
                                <div id="collapseUnfinished" 
                                     class="accordion-collapse collapse show" 
                                     aria-labelledby="headingUnfinished" 
                                     data-bs-parent="#unfinishedLessonsAccordion">
                                    <div class="accordion-body p-2">
                                        <div class="list-group list-group-flush">
                                            @foreach($unfinishedLessons as $lesson)
                                                <div class="list-group-item bg-transparent px-2 py-2 d-flex align-items-center justify-content-between border-bottom-0">
                                                    <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                                        <i class="{{ $lesson->status_label === 'In Progress' ? 'fas fa-play-circle text-warning' : 'far fa-circle text-muted' }}"></i>
                                                        <span class="fw-medium text-dark text-truncate" style="font-size: 0.875rem;">
                                                            {{ $lesson->title }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge {{ $lesson->status_label === 'In Progress' ? 'bg-warning-subtle text-warning border border-warning-subtle' : 'bg-secondary-subtle text-secondary' }}" style="font-size: 0.7rem;">
                                                            {{ $lesson->status_label }}
                                                        </span>
                                                        <a href="{{ route('courses.learn', [$activeCourse->id, 'lesson' => $lesson->id]) }}" 
                                                           class="btn btn-sm btn-primary rounded-pill px-3 py-1" style="font-size: 0.75rem; background-color: #0b3281;">
                                                            Resume
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Upcoming Classes -->
            @if(!empty($upcomingClasses) && count($upcomingClasses) > 0)
                <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
                    <h6 class="fw-bold mb-4 d-flex align-items-center gap-2" style="color: #0b3281;">
                        <i class="fas fa-calendar-alt"></i> Upcoming Classes
                    </h6>
                    <div class="d-flex flex-column gap-4">
                        @foreach($upcomingClasses as $index => $class)
                            <div class="ps-3" style="border-left: 3px solid {{ $index === 0 ? '#0b3281' : '#dee2e6' }};">
                                <small class="fw-bold text-uppercase d-block mb-1" style="color: {{ $index === 0 ? '#ff7a00' : '#6c757d' }}; font-size: 0.75rem;">
                                    {{ $class->formatted_time ?? $class->scheduled_at?->format('M d, Y - h:i A') ?? 'UPCOMING' }}
                                </small>
                                <h6 class="fw-bold text-dark mb-1">{{ $class->title }}</h6>
                                <small class="text-muted d-block">{{ $class->location ?? 'Online / Main Campus' }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Global Fallback State -->
            @if(empty($activeCourse) && empty($upcomingClasses) && empty($recentHomework))
                <div class="card border-0 rounded-4 shadow-sm p-5 bg-white text-center">
                    <div class="my-3">
                        <i class="fas fa-folder-open text-muted fa-3x mb-3"></i>
                        <h5 class="fw-bold text-dark">No Active Enrolments Found</h5>
                        <p class="text-muted mb-0">You are currently not enrolled in any active courses or classes.</p>
                    </div>
                </div>
            @endif

        </div>

        <!-- Sidebar Column: Badges & Homework Marks -->
        <div class="col-12 col-lg-4">

            <!-- Achievements -->
            @if(!empty($achievements) && count($achievements) > 0)
                <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white text-center">
                    <h6 class="fw-bold text-start mb-3 d-flex align-items-center gap-2" style="color: #0b3281;">
                        <i class="fas fa-trophy"></i> Achievements
                    </h6>
                    <div class="d-flex justify-content-center gap-3 my-2">
                        @foreach($achievements as $achievement)
                            <div class="rounded-circle bg-warning-subtle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px; font-size: 1.25rem;" 
                                 title="{{ $achievement->name }}">
                                {{ $achievement->icon ?? '🏆' }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recent Homework via lesson_user -->
            <!-- Recent Homework via lesson_user -->
@if(!empty($recentHomework) && count($recentHomework) > 0)
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: #0b3281;">
            <i class="fas fa-file-signature"></i> Recent Homework
        </h6>
        <div class="list-group list-group-flush">
            @foreach($recentHomework as $lesson)
                @php
                    $score = $lesson->pivot->quiz_score ?? null;
                    $status = $lesson->pivot->status ?? ($score !== null ? 'graded' : 'submitted');
                    $file = $lesson->pivot->file_path ?? $lesson->pivot->homework_file ?? $lesson->pivot->homework_file_path ?? null;
                    $updatedAt = $lesson->pivot->updated_at ?? $lesson->pivot->created_at ?? null;
                    $courseId = $lesson->pivot->course_id ?? $activeCourse->id ?? null;
                @endphp
                <div class="list-group-item px-0 border-bottom py-2 bg-transparent">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <!-- Clickable Title pointing to the Lesson -->
                        @if($courseId)
                            <a href="{{ route('courses.learn', [$courseId, 'lesson' => $lesson->id]) }}" 
                               class="fw-semibold text-dark text-decoration-none text-truncate me-2 hover-primary" 
                               style="max-width: 170px;" 
                               title="{{ $lesson->title }}">
                                {{ $lesson->title }}
                            </a>
                        @else
                            <small class="fw-semibold text-dark text-truncate me-2" style="max-width: 170px;">
                                {{ $lesson->title }}
                            </small>
                        @endif

                        @if(!is_null($score))
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                {{ $score }}/{{ $lesson->total_marks ?? 100 }}
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                {{ ucfirst($status) }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted" style="font-size: 0.75rem;">
                            {{ $updatedAt ? \Carbon\Carbon::parse($updatedAt)->diffForHumans() : 'Recently' }}
                        </small>
                        
                        <div class="d-flex gap-2 align-items-center">
                            {{-- Submitted File Link --}}
                            @if($file)
                                <a href="{{ Storage::url($file) }}" 
                                   target="_blank" 
                                   class="text-decoration-none small text-muted" 
                                   title="View submitted file">
                                    <i class="fas fa-paperclip"></i>
                                </a>
                            @endif

                            {{-- Open Lesson Link --}}
                            @if($courseId)
                                <a href="{{ route('courses.learn', [$courseId, 'lesson' => $lesson->id]) }}" 
                                   class="text-decoration-none small fw-semibold" 
                                   style="color: #0b3281;">
                                    Go to Lesson <i class="fas fa-arrow-right fs-xs ms-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

        </div>
    </div>
</div>
@endsection