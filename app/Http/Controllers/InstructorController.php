<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InstructorController extends Controller
{
    /**
     * Display a listing of all instructors for users/students.
     */
    public function index(Request $request)
    {
        $query = Instructor::withCount([
            'courses',
            // Counts unique students enrolled across all courses assigned via course_instructor
            'courses as students_count' => function ($query) {
                $query->join('course_user', 'courses.id', '=', 'course_user.course_id')
                      ->select(DB::raw('count(distinct(course_user.user_id))'));
            }
        ]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        $instructors = $query->paginate(9)->withQueryString();

        return view('instructors.index', compact('instructors'));
    }

    /**
     * Display the specified instructor's profile.
     */
    public function show($id)
    {
        $instructor = Instructor::with(['courses'])->findOrFail($id);

        // Fetch distinct enrolled users filtering through the course_instructor pivot
        $instructor->students_count = User::whereHas('courses.instructors', function ($query) use ($id) {
            $query->where('instructors.id', $id);
        })->distinct()->count('users.id');

        return view('instructors.show', compact('instructor'));
    }

    public function dashboard()
    {
        $teacher = Auth::guard('teacher')->user();

        // Get courses assigned to this teacher
        $courseIds = DB::table('course_instructor')
            ->where('instructor_id', $teacher->id)
            ->pluck('course_id');

        // Get submitted homework for those courses
        $homeworks = DB::table('lesson_user')
            ->join('lessons', 'lessons.id', '=', 'lesson_user.lesson_id')
            ->join('sections', 'sections.id', '=', 'lessons.section_id')
            ->join('units', 'units.id', '=', 'sections.unit_id')
            ->join('courses', 'courses.id', '=', 'units.course_id')
            ->join('users', 'users.id', '=', 'lesson_user.user_id')
            ->whereIn('units.course_id', $courseIds)
            ->where('lessons.type', 'homework')
            ->whereNotNull('lesson_user.homework_file_path')
            ->select(
                'lesson_user.id',
                'lesson_user.user_id',
                'lesson_user.lesson_id',
                'lesson_user.course_id',
                'lesson_user.homework_file_path',
                'lesson_user.is_completed',
                'lesson_user.submitted_at',
                'lessons.title as lesson_title',
                'courses.title as course_title',
                'users.name as student_name'
            )
            ->orderByDesc('lesson_user.submitted_at')
            ->get();

        return view('instructors.dashboard', [
            'teacher' => $teacher,
            'homeworks' => $homeworks,
        ]);
    }

    public function review($id)
    {
        $teacher = Auth::guard('teacher')->user();

        /*
        |--------------------------------------------------------------------------
        | Get homework submission
        |--------------------------------------------------------------------------
        */

        $homework = DB::table('lesson_user')
            ->join('lessons', 'lessons.id', '=', 'lesson_user.lesson_id')
            ->join('sections', 'sections.id', '=', 'lessons.section_id')
            ->join('units', 'units.id', '=', 'sections.unit_id')
            ->join('courses', 'courses.id', '=', 'units.course_id')
            ->join('users', 'users.id', '=', 'lesson_user.user_id')

            ->where('lesson_user.id', $id)

            /*
            | Make sure this course belongs to the logged-in teacher
            */
            ->whereExists(function ($query) use ($teacher) {
                $query->select(DB::raw(1))
                    ->from('course_instructor')
                    ->whereColumn(
                        'course_instructor.course_id',
                        'units.course_id'
                    )
                    ->where(
                        'course_instructor.instructor_id',
                        $teacher->id
                    );
            })

            ->whereNotNull('lesson_user.homework_file_path')

            ->select(
                'lesson_user.id',
                'lesson_user.user_id',
                'lesson_user.lesson_id',
                'lesson_user.course_id',
                'lesson_user.homework_file_path',
                'lesson_user.submitted_at',

                'lessons.title as lesson_title',
                'courses.title as course_title',

                'users.name as student_name',
                'users.email as student_email'
            )

            ->first();

        /*
        |--------------------------------------------------------------------------
        | Homework not found
        |--------------------------------------------------------------------------
        */

        abort_if(!$homework, 404);

        return view('instructors.homework.review', compact(
            'homework',
            'teacher'
        ));
    }

    public function homework()
    {
        $teacher = Auth::guard('teacher')->user();

        /*
        |--------------------------------------------------------------------------
        | Get courses assigned to this teacher
        |--------------------------------------------------------------------------
        */

        $courseIds = DB::table('course_instructor')
            ->where('instructor_id', $teacher->id)
            ->pluck('course_id');


        /*
        |--------------------------------------------------------------------------
        | Get submitted homework
        |--------------------------------------------------------------------------
        */

        $homeworks = DB::table('lesson_user')
            ->join('lessons', 'lessons.id', '=', 'lesson_user.lesson_id')
            ->join('sections', 'sections.id', '=', 'lessons.section_id')
            ->join('units', 'units.id', '=', 'sections.unit_id')
            ->join('courses', 'courses.id', '=', 'units.course_id')
            ->join('users', 'users.id', '=', 'lesson_user.user_id')

            ->whereIn('units.course_id', $courseIds)

            ->where('lessons.type', 'homework')

            ->whereNotNull('lesson_user.homework_file_path')

            ->select(
                'lesson_user.id',
                'lesson_user.user_id',
                'lesson_user.lesson_id',
                'lesson_user.course_id',
                'lesson_user.homework_file_path',
                'lesson_user.is_completed',
                'lesson_user.submitted_at',

                'lessons.title as lesson_title',
                'courses.title as course_title',

                'users.name as student_name',
                'users.email as student_email'
            )

            ->orderByDesc('lesson_user.submitted_at')

            ->get();


        return view('instructors.homework.index', [
            'teacher' => $teacher,
            'homeworks' => $homeworks,
        ]);
    }
}