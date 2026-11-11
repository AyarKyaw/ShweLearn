<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\Unit;
use App\Models\Lesson;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{

    const QUIZ_QUESTION_LIMIT = 6;

    public function index(Request $request)
{
    $selectedCategorySlug = $request->query('category');

    // Eager load nested categories and courses with their respective counts
    $categoriesQuery = CourseCategory::with([
        'categories.courses' => function ($query) {
            $query->withCount([
                'users',
                'units as lessons_count' => function ($subQuery) {
                    $subQuery->join('sections', 'units.id', '=', 'sections.unit_id')
                             ->join('lessons', 'sections.id', '=', 'lessons.section_id');
                }
            ]);
        }
    ]);

    if ($selectedCategorySlug) {
        $categoriesQuery->where('slug', $selectedCategorySlug);
    }

    $categories = $categoriesQuery->get();
    $courseCategory = $categories->first();
    $subCategories = $categories->pluck('categories')->flatten();

    // Fetch enrolled course IDs for authenticated user
    $enrolledCourseIds = auth()->check() 
        ? auth()->user()->courses()->pluck('courses.id')->toArray() 
        : [];

    return view('course.index', [
        'courseCategory'    => $courseCategory,
        'categories'        => $subCategories,
        'enrolledCourseIds' => $enrolledCourseIds,
    ]);
}

    public function submitHomework(Request $request, Course $course, Lesson $lesson)
    {
        // 1. Validate the file format and size
        $request->validate([
            'homework_file' => [
                'required',
                'file',
                'max:20480', // Max size: 20 MB (20480 KB)
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx', // PDF, Word, Excel, PowerPoint
            ],
        ], [
            'homework_file.required' => 'Please select a file to upload.',
            'homework_file.mimes' => 'Only PDF, Word, Excel, and PowerPoint files are allowed.',
            'homework_file.max' => 'The file size must not exceed 20 MB.',
        ]);

        $user = auth()->user();

        // 2. Locate existing submission (if re-submitting) to clean up old file
        $existingRecord = $user->lessons()
            ->where('lesson_id', $lesson->id)
            ->wherePivot('course_id', $course->id)
            ->first();

        if ($existingRecord && $existingRecord->pivot->homework_file_path) {
            Storage::disk('public')->delete($existingRecord->pivot->homework_file_path);
        }

        // 3. Store uploaded file in storage/app/public/homeworks
        $file = $request->file('homework_file');
        $filename = time() . '_' . $user->id . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('homeworks', $filename, 'public');

        // 4. Update or attach pivot record in lesson_user table
        $user->lessons()->syncWithoutDetaching([
            $lesson->id => [
                'course_id' => $course->id,
                'homework_file_path' => $filePath,
                'is_completed' => true,
                'submitted_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        return redirect()->back()->with('success', 'Homework submitted successfully!');
    }

    public function show($id)
    {
        // Eager load category, units, and nested sections directly on the course model
        $course = Course::with(['category', 'instructors', 'units.sections'])->findOrFail($id);
        

        // Fetch enrolled course IDs for authenticated user
        $enrolledCourseIds = [];
        if (auth()->check()) {
            $enrolledCourseIds = auth()->user()->courses()->pluck('courses.id')->toArray();
        }

        // Access units directly from the loaded course relationship
        $units = $course->units;

        return view('course.single', compact('course', 'enrolledCourseIds', 'units'));
    }

    public function myCourses()
    {
        $user = auth()->user();

        // Fetch user's enrolled courses with their categories
        $courses = $user ? $user->courses()->with('category')->latest()->get() : collect();

        return view('course.my-courses', compact('courses'));
    }

    public function classroom(Course $course, Lesson $lesson = null)
    {
        $user = auth()->user();
        if (!$user || !$user->courses->contains($course->id)) {
            return redirect()->route('courses.show', $course->id)->with('error', 'Please enroll to access class materials.');
        }

        // Query lessons through the course sections relationship
        $currentLesson = $lesson ?? $course->lessons()->first();
        $questions = collect();

        if ($currentLesson && $currentLesson->type === 'quiz') {
            $sessionKey = "quiz_questions_{$user->id}_{$currentLesson->id}";

            if (!session()->has($sessionKey)) {
                // Determine limit: use $lesson->max_questions if set, otherwise default to constant
                $limit = $currentLesson->max_questions ?? self::QUIZ_QUESTION_LIMIT;

                $selectedIds = \App\Models\Question::where('lesson_id', $currentLesson->id)
                    ->inRandomOrder()
                    ->limit($limit)
                    ->pluck('id')
                    ->toArray();

                session()->put($sessionKey, $selectedIds);
            }

            $questionIds = session()->get($sessionKey, []);

            $questions = \App\Models\Question::whereIn('id', $questionIds)
                ->with(['options' => fn($q) => $q->inRandomOrder()])
                ->get()
                ->sortBy(fn($q) => array_search($q->id, $questionIds))
                ->values();
        }

        return view('course.classroom', compact('course', 'currentLesson', 'questions'));
    }

    public function units(Request $request, Course $course)
    {
        // Eager-load units, sections, and lessons in a single query chain
        $course->load([
            'units' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'units.sections' => function ($query) {
                $query->orderBy('order', 'asc');
            },
            'units.sections.lessons' => function ($query) {
                $query->orderBy('order', 'asc');
            }
        ]);

        // Resolve active unit from query parameter (?unit=ID), default to the first unit
        $selectedUnitId = $request->query('unit');
        
        $selectedUnit = $selectedUnitId 
            ? $course->units->firstWhere('id', $selectedUnitId) 
            : $course->units->first();

        return view('course.units', compact('course', 'selectedUnit'));
    }

    public function submitQuiz(Request $request, Course $course, Lesson $lesson)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $lesson->load('questions.options');
        $questionId = $request->input('question_id');
        $submittedAnswer = $request->input('answer');
        
        $question = $lesson->questions->firstWhere('id', (int)$questionId);
        if (!$question) {
            return response()->json(['error' => 'Question not found.'], 404);
        }

        // 1. Evaluate answer & grab option-specific feedback
        $isCorrect = false;
        $feedback = null;

        if ($question->type === 'multiple_choice') {
            // Find the user's selected option model
            $selectedOption = $question->options->firstWhere('id', (int)$submittedAnswer);
            
            if ($selectedOption) {
                $isCorrect = (bool)$selectedOption->is_correct;
                $feedback = $selectedOption->feedback; // Grab option feedback
            }
        } elseif ($question->type === 'boolean') {
            // Cast submitted string "1"/"0" or "true"/"false" to boolean
            $userBool = filter_var($submittedAnswer, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($userBool === null) {
                $userBool = ($submittedAnswer === '1' || $submittedAnswer === 1);
            }

            if (!is_null($question->is_correct)) {
                $isCorrect = ($userBool === (bool)$question->is_correct);
            } else {
                $correctOption = $question->options->firstWhere('is_correct', true) 
                            ?? $question->options->firstWhere('is_correct', 1);
                            
                if ($correctOption) {
                    $correctBool = filter_var($correctOption->option_text, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if ($correctBool === null) {
                        $correctBool = (bool)$correctOption->is_correct;
                    }
                    $isCorrect = ($userBool === $correctBool);
                }
            }

            // Fetch feedback corresponding to the selected True or False option row
            $targetText = $userBool ? 'True' : 'False';
            $selectedBoolOption = $question->options->first(function ($opt) use ($targetText) {
                return strtolower(trim($opt->option_text)) === strtolower($targetText);
            });

            $feedback = $selectedBoolOption?->feedback;
        }

        // 2. Track progress against session subset
        $progressKey = "quiz_progress_{$user->id}_{$lesson->id}";
        $questionsKey = "quiz_questions_{$user->id}_{$lesson->id}";
        
        $progress = session()->get($progressKey, ['correct_count' => 0, 'results' => []]);
        $sessionQuestionIds = session()->get($questionsKey, []);

        // Resolve target total: precedence -> Session count -> lesson->max_questions -> constant
        $targetQuestionCount = count($sessionQuestionIds) > 0 
            ? count($sessionQuestionIds) 
            : ($lesson->max_questions ?? self::QUIZ_QUESTION_LIMIT);

        $progress['results'][$question->id] = [
            'submitted' => $submittedAnswer,
            'is_correct' => $isCorrect,
        ];

        $progress['correct_count'] = count(array_filter($progress['results'], fn($item) => $item['is_correct']));
        session()->put($progressKey, $progress);

        $currentStepIndex = (int) $request->input('step_index', 0);
        $isCompleted = ($currentStepIndex + 1) >= $targetQuestionCount;

        $summary = null;
        if ($isCompleted) {
            $percentage = $targetQuestionCount > 0 
                ? round(($progress['correct_count'] / $targetQuestionCount) * 100) 
                : 0;

            $isCompletedStatus = ($percentage >= 70);

            $tier = 'low';
            if ($percentage >= 80) {
                $tier = 'mastered';
            } elseif ($percentage >= 70) {
                $tier = 'mid';
            }

            $summary = [
                'correct_count'    => $progress['correct_count'],
                'total_questions'  => $targetQuestionCount,
                'score_percentage' => $percentage,
                'tier'             => $tier,
                'passed'           => $isCompletedStatus,
            ];

            $existingPivot = $user->lessons()->where('lesson_id', $lesson->id)->first()?->pivot;
            $finalScore = max($percentage, $existingPivot?->quiz_score ?? 0);
            $finalCompletedStatus = ($existingPivot?->is_completed ?? false) || $isCompletedStatus;

            $user->lessons()->syncWithoutDetaching([
                $lesson->id => [
                    'course_id'        => $lesson->course_id,
                    'is_completed'     => $finalCompletedStatus,
                    'progress_percent' => 100,
                    'quiz_score'       => $finalScore,
                    'completed_at'     => $existingPivot?->completed_at ?? now(),
                ]
            ]);

            session()->forget([$progressKey, $questionsKey]);
        }

        return response()->json([
            'is_correct'   => $isCorrect,
            'feedback'     => $feedback,
            'explanation'  => $question->explanation ?? null,
            'is_completed' => $isCompleted,
            'summary'      => $summary,
        ]);
    }

    // In markComplete():
    public function markComplete(Request $request, Lesson $lesson)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user->lessons()->syncWithoutDetaching([
            $lesson->id => [
                'course_id'        => $lesson->course_id,
                'is_completed'     => true,
                'progress_percent' => 100,
                'completed_at'     => now(),
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lesson marked as completed!'
        ]);
    }
}