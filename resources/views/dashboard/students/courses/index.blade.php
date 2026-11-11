@extends('layouts.student')

@section('title', 'My Courses | MIFFA ACADEMY')

@section('content')
<div class="p-2">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">My Enrolled Courses 📚</h2>
            <p class="text-muted mb-0">Track your active learning, completed courses, and continue your studies.</p>
        </div>
        <div>
            <a href="{{ route('courses.index') }}" class="btn btn-outline-primary rounded-pill px-4" style="border-color: #0b3281; color: #0b3281;">
                <i class="fas fa-search me-1"></i> Browse Catalog
            </a>
        </div>
    </div>

    <!-- Course Cards Grid -->
    @if($courses->count() > 0)
        <div class="row g-4">
            @foreach($courses as $course)
                @php
                    // Gather all course lesson IDs across units -> sections -> lessons
                    $allCourseLessons = $course->units
                        ? $course->units->flatMap(fn($u) => $u->sections)->flatMap(fn($s) => $s->lessons)
                        : collect();
                        
                    $totalLessonsCount = $allCourseLessons->count();

                    if ($totalLessonsCount > 0) {
                        $userLessonsMap = auth()->user()->lessons()
                            ->whereIn('lesson_id', $allCourseLessons->pluck('id'))
                            ->get()
                            ->keyBy('id');

                        $completedCount = $userLessonsMap->filter(function ($lesson) {
                            return $lesson->pivot->is_completed || ($lesson->pivot->quiz_score ?? 0) >= 80;
                        })->count();

                        $progress = round(($completedCount / $totalLessonsCount) * 100);
                    } else {
                        // Fallback to pivot or 0
                        $progress = $course->pivot->progress_percentage ?? 0;
                    }

                    $isCompleted = $progress >= 100;

                    // Resolve target lesson for "Continue" button
                    $lastLessonId = $course->pivot->last_accessed_lesson_id ?? optional($allCourseLessons->first())->id;
                @endphp

                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 rounded-4 shadow-sm bg-white overflow-hidden d-flex flex-column transition-hover">
                        <!-- Thumbnail Header -->
                        <div class="position-relative bg-light text-center" style="height: 160px; overflow: hidden;">
                            @if(!empty($course->image))
                                <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}" class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary-subtle" style="color: #0b3281;">
                                    <i class="fas fa-graduation-cap fa-3x"></i>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            <div class="position-absolute top-0 end-0 m-3">
                                @if($isCompleted)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i> Completed
                                    </span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2" style="color: #0b3281 !important;">
                                        <i class="fas fa-book-reader me-1"></i> In Progress
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="card-body p-4 d-flex flex-column flex-grow-1">
                            <span class="badge bg-light text-secondary border rounded-pill me-auto mb-2" style="font-size: 0.75rem;">
                                {{ $course->category->name ?? 'General Course' }}
                            </span>
                            
                            <h5 class="fw-bold text-dark mb-2 text-truncate-2" style="min-height: 2.8rem; line-height: 1.4;">
                                {{ $course->title }}
                            </h5>
                            
                            <p class="text-muted small mb-4 text-truncate-2 flex-grow-1" style="font-size: 0.85rem;">
                                {{ Str::limit(strip_tags($course->description), 90) }}
                            </p>

                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1 small fw-semibold">
                                    <span class="text-muted">Progress</span>
                                    <span style="color: #ff7a00;">{{ $progress }}%</span>
                                </div>
                                <div class="progress" style="height: 8px; border-radius: 20px; background-color: #e9ecef;">
                                    <div class="progress-bar" 
                                         role="progressbar" 
                                         style="width: {{ $progress }}%; background-color: #ff7a00; border-radius: 20px;" 
                                         aria-valuenow="{{ $progress }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Action Button -->
                            <div class="pt-2 border-top">
                                @if($lastLessonId)
                                    <a href="{{ route('courses.learn', [$course->id, 'lesson' => $lastLessonId]) }}" 
                                       class="btn w-100 rounded-3 fw-semibold py-2 d-flex align-items-center justify-content-center gap-2 text-white" 
                                       style="background-color: #0b3281;">
                                        @if($isCompleted)
                                            <i class="fas fa-redo small"></i> Review Course
                                        @else
                                            <i class="fas fa-play small"></i> Continue Learning
                                        @endif
                                    </a>
                                @else
                                    <a href="{{ route('courses.show', $course->id) }}" 
                                       class="btn btn-light border w-100 rounded-3 fw-semibold py-2 text-dark">
                                        View Details
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(method_exists($courses, 'links'))
            <div class="d-flex justify-content-center mt-5">
                {{ $courses->links() }}
            </div>
        @endif

    @else
        <!-- Empty State -->
        <div class="card border-0 rounded-4 shadow-sm p-5 bg-white text-center">
            <div class="my-4">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-graduation-cap text-muted fa-2x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">No Enrolled Courses Yet</h5>
                <p class="text-muted mb-4 mx-auto" style="max-width: 420px;">
                    You are not enrolled in any courses right now. Explore our course catalog to get started with your learning path!
                </p>
                <a href="{{ route('courses.index') }}" class="btn text-white rounded-pill px-4 py-2 fw-semibold" style="background-color: #0b3281;">
                    <i class="fas fa-compass me-2"></i> Explore Courses
                </a>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .transition-hover {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .transition-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.08) !important;
    }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .object-fit-cover {
        object-fit: cover;
    }
</style>
@endpush
@endsection