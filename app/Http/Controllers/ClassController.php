<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\CourseClass;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Sodium\add;

class ClassController extends Controller
{
    public function terms()
    {
        $terms = Term::all();
        return $terms;
    }

    public function createTerm(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'start_date' => 'required',
            'end_date' => 'required',
        ], [
            'title.required' => 'فیلد عنوان الزامی است.',
            'start_date.required' => 'فیلد تاریخ شروع الزامی است.',
            'end_date.required' => 'فیلد تاریخ پایان الزامی است.',
        ]);

        $term = Term::query()->create([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json([$term], 201);
    }

    public function index()
    {
        return CourseClass::all();
    }

    public function createClass(Request $request)
    {
        $class = CourseClass::query()->create([
            'term_id' => $request->term_id,
            'title' => $request->title,
            'description' => $request->description,
            'assessment' => $request->assessment,
            'date' => $request->date,
            'code' => $request->code,
            'exam_date' => $request->exam_date,
            'day_time' => $request->day_time,
            'imgUrl' => $request->imgUrl,
        ]);
        return response()->json([$class], 201);
    }

    public function studentClasses(int $stdId)
    {
        $user = User::query()->where('id', $stdId)->where('role', 'student')->firstOrFail();
        $classes = $user->classes()->with(['users' => function ($query) {
            $query->wherePivot('role', 'teacher');
        }])->get();


        $result = $classes->map(function ($class) {
            $teacherName = $class->users->first() ? $class->users->first()->name : 'No teacher assigned';
            if ($class->resource != null) {
                $resources = $class->resource->resource;
            } else {
                $resources = null;
            }
            $project = $class->project;
            return [
                'id' => $class->id,
                'term_id' => $class->term_id,
                'title' => $class->title,
                'description' => $class->description,
                'assessment' => $class->assessment,
                'date' => $class->date,
                'code' => $class->code,
                'exam_date' => $class->exam_date,
                'day_time' => $class->day_time,
                'teacher_name' => $teacherName,
                'imgUrl' => $class->imgUrl,
                'resources' => $resources,
                'project' => $project
            ];
        });

        return $result;
    }

    public function getTeacher(int $stdId)
    {
        $user = User::query()->find($stdId);
        $class = $user->classes()->with(['users' => function ($query) {
            $query->wherePivot('role', 'teacher');
        }])->get();
        return $class;
    }

    public function getHomeWorks($stdId)
    {
        $user = User::query()->find($stdId);
        $classes = $user->classes()->get();
        $result = $classes->map(function ($class) {
            $homeWork = $class->homeWorks()->get();
            return [
                'class_title' => $class->title,
                'home_work' => $homeWork
            ];
        });
        return response()->json($result, 200);
    }

    public function addStudentsToClass(Request $request, $classId): \Illuminate\Http\JsonResponse
    {
        $class = CourseClass::query()->findOrFail($classId);

        $request->validate([
            'identifiers' => 'required|array',
            'identifiers.*' => 'required|string|exists:users,identifier',
        ]);

        $students = User::query()->whereIn('identifier', $request->identifiers)->get();

        foreach ($students as $student) {
            $existingAssignment = $class->users()
                ->where('user_id', $student->id)
                ->wherePivot('role', 'student')
                ->exists();

            if (!$existingAssignment) {
                $class->users()->attach($student->id, ['role' => 'student']);
            }
        }

        // Add the teacher who is assigning students to the class
        $teacher = Auth::user();
        $class->users()->attach($teacher->id, ['role' => 'teacher']);

        return response()->json(['message' => 'Students and teacher assigned to class successfully'], 200);
    }


    public function getTeachersClasses($id) {
        $user = User::query()->where('id', $id)->where('role', 'teacher')->firstOrFail();
        $classes = $user->classes()->with(['users' => function ($query) {
            $query->wherePivot('role', 'teacher');
        }])->get();

        $result = $classes->map(function ($class) {
            $students = DB::table('class_user')->where('course_class_id', $class->id)->where('role', 'student')->get();
            if ($class->resource != null) {
                $resources = $class->resource->resource;
            } else {
                $resources = null;
            }
            $project = $class->project;
            return [
                'id' => $class->id,
                'term_id' => $class->term_id,
                'title' => $class->title,
                'description' => $class->description,
                'assessment' => $class->assessment,
                'date' => $class->date,
                'code' => $class->code,
                'exam_date' => $class->exam_date,
                'day_time' => $class->day_time,
                'imgUrl' => $class->imgUrl,
                'resources' => $resources,
                'project' => $project,
            ];
        });

        return response()->json($result, 200);
    }
}
