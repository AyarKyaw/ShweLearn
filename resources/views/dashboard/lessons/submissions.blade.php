@extends('dashboard.layouts.master')

@section('title', 'Homework Submissions - ' . $lesson->title . ' | Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="lesson-submissions-title">
                    
                    <!-- Header Banner with Lesson Details -->
                    <header class="m-card__header d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <a href="{{ route('admin.lessons.index', ['section_id' => $lesson->section_id]) }}" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back
                                </a>
                                <span class="badge bg-secondary">{{ $lesson->section->title ?? 'Section' }}</span>
                            </div>
                            <h2 class="m-card__title" id="lesson-submissions-title">
                                Homework Submissions: <span class="text-primary">{{ $lesson->title }}</span>
                            </h2>
                            <p class="m-card__subtitle text-muted mb-0">
                                Total Submissions: <strong>{{ method_exists($submissions, 'total') ? $submissions->total() : count($submissions) }}</strong>
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            @if($lesson->document_path)
                                <a href="{{ Storage::url($lesson->document_path) }}" target="_blank" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
                                    <i class="fa-solid fa-file-arrow-down" aria-hidden="true"></i> Download Template
                                </a>
                            @endif
                        </div>
                    </header>

                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Submissions Table -->
                    <div class="table-responsive">
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Submitted File</th>
                                    <th>Submitted Date</th>
                                    <th>Last Updated</th>
                                    <th>Score</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($submissions as $submission)
                                    @php
                                        $pivotId = $submission->pivot_id ?? $submission->id;
                                    @endphp
                                    <tr class="tr-shadow">
                                        <td>
                                            <div class="fw-bold text-dark">{{ $submission->user_name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $submission->user_email ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            @if($submission->homework_file_path)
                                                <a href="{{ Storage::url($submission->homework_file_path) }}" target="_blank" class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1">
                                                    <i class="fa-solid fa-paperclip text-primary"></i> Download Homework
                                                </a>
                                            @else
                                                <span class="text-muted">No file submitted</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at)->format('M d, Y H:i') : 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $submission->updated_at ? \Carbon\Carbon::parse($submission->updated_at)->format('M d, Y H:i') : 'N/A' }}
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $submission->score !== null ? $submission->score . ' / 100' : '—' }}</span>
                                        </td>
                                        <td>
                                            <div class="table-data-feature justify-content-end gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#gradeModal-{{ $pivotId }}">
                                                    <i class="fa-solid fa-pen-to-square me-1"></i> Grade & Feedback
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            No student homework submissions found for this lesson.
                                        </td>
                                    </tr>
                                @endempty
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    @if(method_exists($submissions, 'links'))
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $submissions->appends(request()->query())->links() }}
                        </div>
                    @endif

                </section>
            </div>
        </div>
    </div>

    <!-- Modals rendered outside of table DOM structure -->
    @foreach ($submissions as $submission)
        @php
            $feedback = $submission->feedback ?? '';
            $pivotId = $submission->pivot_id ?? $submission->id;
        @endphp
        <div class="modal fade" id="gradeModal-{{ $pivotId }}" tabindex="-1" aria-labelledby="gradeModalLabel-{{ $pivotId }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('admin.lesson-user.update', $pivotId) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="gradeModalLabel-{{ $pivotId }}">Evaluate Submission</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Score (Out of 100)</label>
                                <input type="number" name="score" class="form-control" value="{{ $submission->score }}" min="0" max="100" step="0.1">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-secondary btn" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Evaluation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</main>
@endsection