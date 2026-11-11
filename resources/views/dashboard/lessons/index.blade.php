@extends('dashboard.layouts.master')

@section('title', 'Section Lessons - Shwe Learn')

@section('content')
<main class="main-content" id="main-content">
    <div class="section__content section__content--p30">
        <div class="row row-tight" style="margin-top: 16px;">
            <div class="col-md-12">
                <section class="m-card" aria-labelledby="lessons-title">
                    <header class="m-card__header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-card__title" id="lessons-title">Section Lessons</h2>
                            <p class="m-card__subtitle">
                                @if(isset($selectedSection))
                                    Showing lessons for section: <strong>{{ $selectedSection->title }}</strong>
                                    @if($selectedSection->course)
                                        <span class="text-muted">({{ $selectedSection->course->title }})</span>
                                    @endif
                                @else
                                    Manage section lessons, videos, documents, homework, and quizzes
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('admin.sections.index', ['unit_id' => $selectedSection->unit_id ?? request('unit_id')]) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Sections
                        </a>
                    </header>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Action Tool Bar -->
                    <div class="table-data__tool">
                        <div class="table-data__tool-left"></div>
                        <div class="table-data__tool-right d-flex gap-2">
                            <a href="{{ route('admin.lessons.create', request()->only('section_id')) }}" class="au-btn au-btn--green au-btn--small text-decoration-none d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add Lesson
                            </a>
                        </div>
                    </div>

                    <!-- Lessons Table -->
                    <div class="table-responsive">
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th style="width:24px;">
                                        <label class="au-checkbox">
                                            <input type="checkbox" aria-label="Select all"><span class="au-checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Order</th>
                                    <th>Lesson Title</th>
                                    <th>Section</th>
                                    <th>Type</th>
                                    <th>Details</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lessons as $lesson)
                                    <tr class="tr-shadow">
                                        <td>
                                            <label class="au-checkbox">
                                                <input type="checkbox" name="selected_lessons[]" value="{{ $lesson->id }}">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border font-monospace">
                                                #{{ $lesson->order }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $lesson->title }}</span>
                                        </td>
                                        <td>
                                            @if($lesson->section)
                                                <span class="fw-semibold text-primary d-block">{{ $lesson->section->title }}</span>
                                            @else
                                                <span class="text-muted">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($lesson->type)
                                                @case('video')
                                                    <span class="badge bg-primary"><i class="fa-solid fa-video me-1"></i> Video</span>
                                                    @break
                                                @case('document')
                                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-file-pdf me-1"></i> Document</span>
                                                    @break
                                                @case('homework')
                                                    <span class="badge bg-purple text-white" style="background-color: #6f42c1;"><i class="fa-solid fa-file-pen me-1"></i> Homework</span>
                                                    @break
                                                @case('quiz')
                                                    <span class="badge bg-info text-dark"><i class="fa-solid fa-clipboard-question me-1"></i> Quiz</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary"><i class="fa-solid fa-file-lines me-1"></i> Text</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($lesson->type === 'video' && $lesson->video_url)
                                                <a href="{{ $lesson->video_url }}" target="_blank" class="small text-decoration-none text-truncate d-block" style="max-width: 180px;">
                                                    <i class="fa-solid fa-external-link me-1"></i> Watch Link
                                                </a>
                                            @elseif($lesson->type === 'document' && $lesson->document_path)
                                                <a href="{{ Storage::url($lesson->document_path) }}" target="_blank" class="small text-decoration-none text-success">
                                                    <i class="fa-solid fa-download me-1"></i> View Document
                                                </a>
                                            @elseif($lesson->type === 'homework')
                                                @if($lesson->homework_file_path ?? $lesson->document_path ?? false)
                                                    <a href="{{ Storage::url($lesson->homework_file_path ?? $lesson->document_path) }}" target="_blank" class="small text-decoration-none text-purple" style="color: #6f42c1;">
                                                        <i class="fa-solid fa-paperclip me-1"></i> Download Template
                                                    </a>
                                                @else
                                                    <small class="text-muted"><i class="fa-solid fa-upload me-1"></i> Student Upload</small>
                                                @endif
                                            @elseif($lesson->type === 'quiz')
                                                <small class="text-muted">{{ $lesson->questions->count() }} Questions</small>
                                            @else
                                                <small class="text-muted">Text Content</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-data-feature justify-content-end gap-1">
                                                @if($lesson->type === 'homework')
                                                    <a href="{{ route('admin.lessons.submissions', $lesson->id) }}" class="item" data-bs-toggle="tooltip" title="View Student Submissions">
                                                        <i class="fa-solid fa-file-arrow-down text-primary"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="item" data-bs-toggle="tooltip" title="Edit Lesson">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form action="{{ route('admin.lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lesson?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="item" type="submit" data-bs-toggle="tooltip" title="Delete Lesson">
                                                        <i class="fa-solid fa-trash text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="fa-solid fa-book-open fs-4 d-block mb-2"></i>
                                            No lessons found for this section.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>
@endsection