@php
    $userLessonRecord = $userLessons ? $userLessons->get($lesson->id) : null;
    $homeworkFilePath = $userLessonRecord ? $userLessonRecord->pivot->homework_file_path : null;
    $submittedAt = $userLessonRecord ? $userLessonRecord->pivot->updated_at : null;
    $isCompleted = $userLessonRecord ? $userLessonRecord->pivot->is_completed : false;
    $score = $userLessonRecord ? $userLessonRecord->pivot->quiz_score : null;
    $feedback = $userLessonRecord ? $userLessonRecord->pivot->feedback : null;
@endphp

<div class="p-4 p-md-5 bg-white">
    <!-- Homework Header -->
    <div class="border-bottom pb-3 mb-4">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                <i class="fas fa-file-signature me-1"></i> Homework Assignment
            </span>
            @if($homeworkFilePath)
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                    <i class="fas fa-check-circle me-1"></i> Submitted
                </span>
            @else
                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2">
                    <i class="fas fa-exclamation-circle me-1"></i> Pending Submission
                </span>
            @endif

            @if($score !== null)
                <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-2">
                    <i class="fas fa-star me-1"></i> Score: {{ $score }} / 100
                </span>
            @endif
        </div>
        <h3 class="fw-bold text-dark mb-2">{{ $lesson->title }}</h3>
    </div>

    <!-- Homework Instructions / Content -->
    <div class="card border-0 bg-light rounded-4 p-4 mb-4">
        <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-info-circle me-2 text-primary"></i>Instructions</h5>
        <div class="lh-lg text-secondary">
            {!! $lesson->content ?? $lesson->description ?? 'Please read the instructions carefully and upload your completed assignment below.' !!}
        </div>

        @if(!empty($lesson->file_path))
            <div class="mt-4 pt-3 border-top">
                <span class="fw-semibold text-dark d-block mb-2">Reference / Assignment Material:</span>
                <a href="{{ Storage::url($lesson->file_path) }}" target="_blank" class="btn btn-outline-primary rounded-pill btn-sm">
                    <i class="fas fa-download me-2"></i> Download Assignment Template / Details
                </a>
            </div>
        @endif
    </div>

    <!-- Score & Instructor Feedback Box -->
    @if($score !== null || $feedback)
        <div class="card border-primary border-opacity-25 rounded-4 p-4 bg-primary-subtle bg-opacity-10 mb-4 shadow-sm">
            <h5 class="fw-bold mb-3 text-dark d-flex align-items-center justify-content-between">
                <span><i class="fas fa-award me-2 text-primary"></i>Evaluation & Results</span>
                @if($score !== null)
                    <span class="fs-4 fw-bold text-primary">{{ $score }} <span class="fs-6 text-muted">/ 100</span></span>
                @endif
            </h5>

            @if($feedback)
                <div class="bg-white p-3 rounded-3 border">
                    <span class="fw-semibold text-dark d-block mb-1"><i class="fas fa-comment-dots me-2 text-info"></i>Teacher Feedback:</span>
                    <p class="text-secondary mb-0 text-break">{{ $feedback }}</p>
                </div>
            @endif
        </div>
    @endif

    <!-- Homework Submission Area -->
    <div class="card border rounded-4 p-4 bg-white shadow-sm">
        <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-upload me-2 text-primary"></i>Your Submission</h5>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->has('homework_file'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ $errors->first('homework_file') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($homeworkFilePath)
            <!-- Existing Submission Display -->
            <div class="bg-light p-3 rounded-3 border d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle">
                        <i class="fas fa-file-alt fa-lg"></i>
                    </div>
                    <div>
                        <span class="fw-semibold text-dark d-block">Submitted File</span>
                        <small class="text-muted">Uploaded on {{ \Carbon\Carbon::parse($submittedAt)->format('M d, Y h:i A') }}</small>
                    </div>
                </div>
                <a href="{{ Storage::url($homeworkFilePath) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    <i class="fas fa-external-link-alt me-1"></i> View File
                </a>
            </div>
        @endif

        <!-- Submission Form -->
        <form action="{{ route('courses.homework.submit', [$course->id, $lesson->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="homework_file" class="form-label fw-semibold text-dark">
                    {{ $homeworkFilePath ? 'Replace Submitted File' : 'Upload Solution File' }}
                </label>
                <input class="form-control" type="file" id="homework_file" name="homework_file" required>
                <div class="form-text">Accepted formats: PDF, Word, Excel, PowerPoint (Max size: 10MB)</div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-paper-plane me-2"></i> {{ $homeworkFilePath ? 'Re-submit Homework' : 'Submit Homework' }}
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    if (
        typeof window.MIFFAAndroid !== 'undefined' &&
        window.MIFFAAndroid.isAndroidApp()
    ) {
        document
            .querySelectorAll('a[target="_blank"]')
            .forEach(function (link) {
                link.setAttribute('target', '_self');
            });
    }

});
</script>
@endpush