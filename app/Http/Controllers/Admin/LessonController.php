<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $sectionId = $request->input('section_id');

        $lessons = Lesson::with(['section', 'questions'])
            ->when($sectionId, function ($query) use ($sectionId) {
                return $query->where('section_id', $sectionId);
            })
            ->orderBy('order', 'asc')
            ->get();

        $selectedSection = $sectionId ? Section::with(['unit.course'])->find($sectionId) : null;

        return view('dashboard.lessons.index', compact('lessons', 'selectedSection'));
    }

    public function create(Request $request)
    {
        $sections = Section::orderBy('title')->get();
        $sectionId = $request->query('section_id');

        return view('dashboard.lessons.create', compact('sections', 'sectionId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id'                           => 'nullable|exists:courses,id',
            'section_id'                          => 'nullable|exists:sections,id',
            'title'                               => 'required|string|max:255',
            'type'                                => 'required|string|in:video,article,document,homework,quiz',
            'video_url'                           => 'nullable|url|max:255',
            'content'                             => 'nullable|string',
            'document_file'                       => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
            'homework_file'                       => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:20480',
            'order'                               => 'nullable|integer',
            'max_questions'                       => 'nullable|integer|min:1',
            'questions'                           => 'nullable|array',
            'questions.*.text'                    => 'required_if:type,quiz|nullable|string',
            'questions.*.question_text'           => 'nullable|string',
            'questions.*.type'                    => 'required_if:type,quiz|nullable|in:multiple_choice,boolean',
            'questions.*.is_correct'              => 'nullable',
            'questions.*.correct_option'          => 'nullable',
            'questions.*.hint'                    => 'nullable|string',
            'questions.*.explanation'             => 'nullable|string',
            'questions.*.options'                 => 'nullable|array',
            'questions.*.option_feedbacks'        => 'nullable|array',
            'questions.*.boolean_feedback'        => 'nullable|array',
            'questions.*.boolean_feedback.true'   => 'nullable|string',
            'questions.*.boolean_feedback.false'  => 'nullable|string',
            'questions.*.true_feedback'           => 'nullable|string',
            'questions.*.false_feedback'          => 'nullable|string',
        ]);

        // Safely retrieve course_id from request or fallback
        $courseId = $request->input('course_id');
        $sectionId = $request->input('section_id');

        if (!$sectionId && $courseId) {
            $section = Section::whereHas('unit', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })->first();

            if (!$section) {
                $unit = Unit::where('course_id', $courseId)->first();

                if (!$unit) {
                    $unit = Unit::create([
                        'course_id' => $courseId,
                        'title'     => 'General Unit',
                        'order'     => 1,
                    ]);
                }

                $section = Section::create([
                    'unit_id' => $unit->id,
                    'title'   => 'General Section',
                    'order'   => 1,
                ]);
            }

            $sectionId = $section->id;
        }

        $validated['section_id'] = $sectionId;
        $validated['order'] = $validated['order'] ?? 1;

        if ($request->hasFile('document_file')) {
            $validated['document_path'] = $request->file('document_file')->store('lessons/documents', 'public');
        }

        if ($request->hasFile('homework_file')) {
            $validated['homework_path'] = $request->file('homework_file')->store('lessons/homeworks', 'public');
        }

        try {
            DB::beginTransaction();

            $lesson = Lesson::create([
                'section_id'    => $validated['section_id'],
                'title'         => $validated['title'],
                'type'          => $validated['type'],
                'video_url'     => $validated['video_url'] ?? null,
                'content'       => $validated['content'] ?? null,
                'document_path' => $validated['document_path'] ?? ($validated['homework_path'] ?? null),
                'order'         => $validated['order'],
                'max_questions' => $validated['max_questions'] ?? null,
            ]);

            if ($lesson->type === 'quiz' && !empty($request->input('questions'))) {
                foreach ($request->input('questions') as $qData) {
                    $questionText = $qData['text'] ?? ($qData['question_text'] ?? null);

                    // Skip empty question blocks
                    if (empty($questionText)) {
                        continue;
                    }

                    $questionType = $qData['type'] ?? 'multiple_choice';
                    $isCorrectBool = filter_var($qData['is_correct'] ?? false, FILTER_VALIDATE_BOOLEAN);

                    $question = $lesson->questions()->create([
                        'question_text' => $questionText,
                        'type'          => $questionType,
                        'is_correct'    => $questionType === 'boolean' ? $isCorrectBool : false,
                        'hint'          => $qData['hint'] ?? null,
                        'explanation'   => $qData['explanation'] ?? null,
                    ]);

                    if ($questionType === 'multiple_choice' && isset($qData['options']) && is_array($qData['options'])) {
                        $selectedCorrectIndex = $qData['correct_option'] ?? 0;

                        foreach ($qData['options'] as $optIndex => $optData) {
                            $optText = is_array($optData) ? ($optData['text'] ?? '') : $optData;
                            $optFeedback = is_array($optData) 
                                ? ($optData['feedback'] ?? null) 
                                : ($qData['option_feedbacks'][$optIndex] ?? null);

                            if (!empty(trim((string)$optText))) {
                                $question->options()->create([
                                    'option_text' => $optText,
                                    'is_correct'  => ((string)$optIndex === (string)$selectedCorrectIndex),
                                    'feedback'    => $optFeedback,
                                ]);
                            }
                        }
                    } elseif ($questionType === 'boolean') {
                        $question->options()->create([
                            'option_text' => 'True',
                            'is_correct'  => $isCorrectBool,
                            'feedback'    => $qData['boolean_feedback']['true'] ?? ($qData['true_feedback'] ?? null),
                        ]);

                        $question->options()->create([
                            'option_text' => 'False',
                            'is_correct'  => !$isCorrectBool,
                            'feedback'    => $qData['boolean_feedback']['false'] ?? ($qData['false_feedback'] ?? null),
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.lessons.index', array_filter(['section_id' => $sectionId]))
                             ->with('success', 'Lesson created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to store lesson: ' . $e->getMessage()]);
        }
    }

    public function getQuestions(Lesson $lesson)
    {
        $lesson->load(['questions.options']);

        $questionsQuery = $lesson->questions;

        if ($lesson->max_questions) {
            $questionsQuery = $questionsQuery->take($lesson->max_questions);
        }

        return response()->json([
            'lesson_title'  => $lesson->title,
            'max_questions' => $lesson->max_questions,
            'questions'     => $questionsQuery->map(function ($q) {
                return [
                    'id'          => $q->id,
                    'text'        => $q->question ?? $q->title ?? $q->question_text,
                    'type'        => $q->type ?? ($q->options->isEmpty() ? 'boolean' : 'multiple_choice'),
                    'is_correct'  => $q->is_correct,
                    'hint'        => $q->hint,
                    'explanation' => $q->explanation,
                    'options'     => $q->options->map(function ($opt) {
                        return [
                            'id'         => $opt->id,
                            'text'       => $opt->option_text ?? $opt->text,
                            'is_correct' => (bool) $opt->is_correct,
                            'feedback'   => $opt->feedback,
                        ];
                    }),
                ];
            }),
        ]);
    }

    public function edit(Lesson $lesson)
    {
        $lesson->load(['questions.options']);
        $sections = Section::orderBy('title')->get();

        return view('dashboard.lessons.edit', compact('lesson', 'sections'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'section_id'                     => 'required|exists:sections,id',
            'title'                          => 'required|string|max:255',
            'type'                           => 'required|in:video,document,article,homework,quiz',
            'order'                          => 'required|integer|min:0',
            'max_questions'                  => 'nullable|integer|min:1',
            'video_url'                      => 'nullable|required_if:type,video|url|max:255',
            'content'                        => 'nullable|string',
            'document_file'                  => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
            'homework_file'                  => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:20480',
            
            // Questions
            'questions'                      => 'nullable|array',
            'questions.*.text'               => 'required_if:type,quiz|string',
            'questions.*.type'               => 'required_if:type,quiz|in:multiple_choice,boolean',
            'questions.*.hint'               => 'nullable|string',
            'questions.*.correct_option'     => 'nullable|integer',
            'questions.*.is_correct'         => 'nullable|in:0,1',
            
            // Options and Option Feedbacks (Multiple choice only)
            'questions.*.options'            => 'nullable|array',
            'questions.*.options.*'          => 'nullable|string',
            'questions.*.option_feedbacks'   => 'nullable|array',
            'questions.*.option_feedbacks.*' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $lesson, $validated) {
            $validated['video_url'] = ($validated['type'] === 'video') ? $request->input('video_url') : null;

            if ($request->hasFile('document_file')) {
                if ($lesson->document_path && Storage::disk('public')->exists($lesson->document_path)) {
                    Storage::disk('public')->delete($lesson->document_path);
                }
                $validated['document_path'] = $request->file('document_file')->store('lessons/documents', 'public');
            }

            $lesson->update([
                'section_id'    => $validated['section_id'],
                'title'         => $validated['title'],
                'type'          => $validated['type'],
                'order'         => $validated['order'],
                'max_questions' => $validated['max_questions'] ?? null,
                'video_url'     => $validated['video_url'],
                'content'       => $validated['content'] ?? null,
                'document_path' => $validated['document_path'] ?? $lesson->document_path,
            ]);

            if ($lesson->type === 'quiz') {
                $submittedQuestions = $request->input('questions', []);
                $keepQuestionIds = [];

                foreach ($submittedQuestions as $qData) {
                    $questionText = $qData['text'] ?? ($qData['question_text'] ?? '');
                    $questionType = $qData['type'] ?? 'multiple_choice';
                    $hint = $qData['hint'] ?? null;
                    $explanation = $qData['explanation'] ?? null;

                    if (!empty($qData['id'])) {
                        $question = $lesson->questions()->find($qData['id']);
                        if ($question) {
                            $question->update([
                                'question_text' => $questionText,
                                'type'          => $questionType,
                                'hint'          => $hint,
                                'explanation'   => $explanation,
                            ]);
                        }
                    } else {
                        $question = $lesson->questions()->create([
                            'question_text' => $questionText,
                            'type'          => $questionType,
                            'hint'          => $hint,
                            'explanation'   => $explanation,
                        ]);
                    }

                    if ($question) {
                        $keepQuestionIds[] = $question->id;
                        $question->options()->delete();

                        if ($questionType === 'boolean') {
                            $isCorrect = isset($qData['is_correct']) && $qData['is_correct'] == 1;
                            $question->update(['is_correct' => $isCorrect]);

                            $question->options()->create([
                                'option_text' => 'True',
                                'is_correct'  => $isCorrect,
                                'feedback'    => $qData['boolean_feedback']['true'] ?? null,
                            ]);

                            $question->options()->create([
                                'option_text' => 'False',
                                'is_correct'  => !$isCorrect,
                                'feedback'    => $qData['boolean_feedback']['false'] ?? null,
                            ]);
                        } else {
                            if (isset($qData['options']) && is_array($qData['options'])) {
                                $selectedCorrectIndex = $qData['correct_option'] ?? 0;

                                foreach ($qData['options'] as $optIndex => $optData) {
                                    $optText = is_array($optData) ? ($optData['text'] ?? '') : $optData;
                                    $optFeedback = is_array($optData) ? ($optData['feedback'] ?? null) : null;

                                    if (!empty(trim($optText))) {
                                        $question->options()->create([
                                            'option_text' => $optText,
                                            'is_correct'  => ((string)$optIndex === (string)$selectedCorrectIndex),
                                            'feedback'    => $optFeedback,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                $lesson->questions()->whereNotIn('id', $keepQuestionIds)->delete();
            } else {
                $lesson->questions()->delete();
            }
        });

        session()->forget("quiz_questions_" . auth()->id() . "_{$lesson->id}");

        return redirect()->route('admin.lessons.index', ['section_id' => $lesson->section_id])
                         ->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Lesson $lesson)
    {
        try {
            DB::beginTransaction();

            // Capture section_id before removing the record
            $sectionId = $lesson->section_id;

            // Delete uploaded file asset if present
            if ($lesson->document_path && Storage::disk('public')->exists($lesson->document_path)) {
                Storage::disk('public')->delete($lesson->document_path);
            }

            $lesson->delete();

            DB::commit();

            return redirect()->route('admin.lessons.index', array_filter(['section_id' => $sectionId]))
                             ->with('success', 'Lesson deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete lesson: ' . $e->getMessage()]);
        }
    }

    public function submissions($lessonId)
    {
        $lesson = Lesson::find($lessonId);

        if (!$lesson) {
            return redirect()->route('admin.lessons.index')
                ->withErrors(['error' => "Lesson with ID {$lessonId} was not found."]);
        }

        if ($lesson->type !== 'homework') {
            return redirect()->route('admin.lessons.index')
                ->withErrors(['error' => 'This lesson is not configured as a homework assignment.']);
        }

        // Check which columns exist in lesson_user table to prevent SQL errors
        $hasStatusColumn = Schema::hasColumn('lesson_user', 'status');
        $hasFeedbackColumn = Schema::hasColumn('lesson_user', 'feedback');

        $selectColumns = [
            'lesson_user.id as pivot_id',
            'lesson_user.user_id',
            'lesson_user.lesson_id',
            'lesson_user.homework_file_path',
            'lesson_user.quiz_score as score',
            'lesson_user.submitted_at',
            'lesson_user.is_completed',
            'lesson_user.created_at',
            'lesson_user.updated_at',
            'users.name as user_name',
            'users.email as user_email',
        ];

        if ($hasStatusColumn) {
            $selectColumns[] = 'lesson_user.status';
        }

        if ($hasFeedbackColumn) {
            $selectColumns[] = 'lesson_user.feedback';
        }

        $submissions = DB::table('lesson_user')
            ->join('users', 'lesson_user.user_id', '=', 'users.id')
            ->where('lesson_user.lesson_id', $lesson->id)
            ->whereNotNull('lesson_user.homework_file_path')
            ->select($selectColumns)
            ->orderBy('lesson_user.submitted_at', 'desc')
            ->paginate(15);

        return view('dashboard.lessons.submissions', compact('lesson', 'submissions'));
    }

    /**
     * Update grade/score and evaluation for a submission.
     */
    public function updateSubmission(Request $request, $pivotId)
    {
        $validated = $request->validate([
            'score'    => 'nullable|numeric|min:0|max:100',
            'status'   => 'nullable|string|in:pending,reviewed,graded,rejected',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $updateData = [
            'quiz_score' => $validated['score'] ?? null,
            'updated_at' => now(),
        ];

        // Safely update columns if present in schema
        if (Schema::hasColumn('lesson_user', 'status')) {
            $updateData['status'] = $validated['status'] ?? 'pending';
        }

        if (Schema::hasColumn('lesson_user', 'feedback')) {
            $updateData['feedback'] = $validated['feedback'] ?? null;
        }

        // Map status to is_completed if applicable
        if (isset($validated['status']) && $validated['status'] === 'graded') {
            $updateData['is_completed'] = 1;
        }

        DB::table('lesson_user')
            ->where('id', $pivotId)
            ->update($updateData);

        return back()->with('success', 'Homework evaluation saved successfully.');
    }
}