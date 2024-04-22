<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudyClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\StudentResource;
use App\Http\Resources\StudyClassResource;
use App\Http\Requests\StoreStudyClassRequest;
use App\Http\Requests\UpdateStudyClassRequest;
use App\Models\Study;
use App\Models\StudyYear;
use Illuminate\Support\Facades\Log;
use Throwable;

class StudyClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $study_year_active = StudyYear::whereIsActive(1)->first()->id;

        $study_classes = DB::table('study_classes')
            ->join('teachers', 'teachers.id', '=', 'study_classes.teacher_id')
            ->join('rooms', 'rooms.id', '=', 'study_classes.room_id')
            ->join('grades', 'grades.id', '=', 'study_classes.grade_id')
            ->join('study_years', 'study_years.id', '=', 'study_classes.study_year_id')
            ->select(
                'study_classes.id',
                'study_classes.teacher_id',
                'study_classes.grade_id',
                'study_classes.room_id',
                'study_classes.study_year_id',
                'study_classes.name as studyclass_name',
                'rooms.name as room_name',
                'grades.name as grade_name',
                'study_years.name as studyyear_name',
                DB::raw("CONCAT(teachers.last_name,' ',teachers.first_name) AS full_name")
            )
            ->when(request('search'), function ($query) {
                return $query
                    ->where('study_classes.name', 'LIKE', '%' . request('search') . '%');;
            })
            ->where('study_years.id', request('study_year_id', $study_year_active))
            ->get();

        $study_years = StudyYear::all();

        return response()->json([
            'data' =>  $study_classes,
            'study_years' => $study_years,
            'study_year_active' =>  request('study_year_id') != 0 ? (int) request('study_year_id') : $study_year_active
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ->pluck('full_name', 'id')
        $teacher = DB::table('teachers')
            ->select('id', DB::raw("CONCAT(teachers.last_name,' ',teachers.first_name) AS full_name"))->where('status', 1)->get();
        $study_years = DB::table('study_years')
            ->select('id', 'name')->where('status', 1)->get();
        $grades = DB::table('grades')
            ->select('id', 'name')->where('status', 1)->get();
        $rooms = DB::table('rooms')
            ->select('id', 'name')->where('status', 1)->get();
        $subjects = DB::table('subjects')
            ->select('id', 'name')->where('status', 1)->get();
        return response()->json([
            "message" => "successfully create study class",
            "data" => [
                "teachers"      => $teacher,
                "study_years"   => $study_years,
                "grades"        => $grades,
                "rooms"         => $rooms,
                "subjects"      => $subjects,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudyClassRequest $request)
    {

        $teacher_exist = StudyClass::whereStudyYearId($request->study_year_id)
            ->whereTeacherId($request->teacher_id)->first();
        if ($teacher_exist) {
            return response()->json([
                'message' => 'Teacher already exist'
            ], 422);
        }

        $study_class_exist = StudyClass::where('name', '=', $request->name)
            ->where('study_year_id', '=', $request->study_year_id)
            ->first();
        if ($study_class_exist) {
            return response()->json([
                'message' => 'StudyClass already exist'
            ], 422);
        };

        $room_exist = StudyClass::whereStudyYearId($request->study_year_id)
            ->whereRoomId($request->room_id)->first();
        if ($room_exist) {
            return response()->json([
                'message' => 'Classroom already exist'
            ], 422);
        }

        DB::beginTransaction();

        try {

            $study_class = new StudyClass();
            $study_class->name = $request->name;
            $study_class->teacher_id = $request->teacher_id;
            $study_class->room_id = $request->room_id;
            $study_class->grade_id = $request->grade_id;
            $study_class->study_year_id = $request->study_year_id;
            $study_class->save();
            $study_class->subjects()->attach($request->subject_ids);

            foreach ($request->student_ids as $student_id) {
                $study = new Study();
                $study->student_id = $student_id;
                $study->study_class_id = $study_class->id;
                $study->save();
            }

            DB::commit();

            return response()->json([
                "message" => "Successfully create study class"
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'message' => 'Cannot connect to remote server.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $study_class = StudyClass::with(['studyYear', 'studies' => function ($q) {
            $q->whereHas('student', function ($q) {
                return $q
                    ->where('code', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('dob', 'LIKE', '%' . request('search') . '%')
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name)  LIKE ?", '%' . request('search') . '%')
                    ->orWhereRaw("CONCAT(first_name_latin, ' ', last_name_latin) LIKE ?", '%' . request('search') . '%')
                    ->orWhere('from', 'LIKE', '%' . request('search') . '%');
            });
        }, 'studies.student', 'teacher', 'room'])->findOrFail($id);

        $female_students = Study::where('study_class_id', $id)->whereHas('student', function ($q) {
            return $q->where('gender', '!=', 1);
        })->count();

        if ($study_class) return response()->json(['study_class' => $study_class, 'female_students' => $female_students]);
        return response()->json(['message' => 'Cannot connect to remote server.'], 500);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $study_class = StudyClass::with('subjects')->findOrFail($id);
        $teacher = DB::table('teachers')
            ->select('id', DB::raw("CONCAT(teachers.last_name,' ',teachers.first_name) AS full_name"))->where('status', 1)->get();
        $study_years = DB::table('study_years')
            ->select('id', 'name')->where('status', 1)->get();
        $grades = DB::table('grades')
            ->select('id', 'name')->where('status', 1)->get();
        $rooms = DB::table('rooms')
            ->select('id', 'name')->where('status', 1)->get();
        $subjects = DB::table('subjects')
            ->select('id', 'name')->where('status', 1)->get();
        return response()->json([
            'data' => [
                "data" => new StudyClassResource($study_class),
                "teachers"   => $teacher,
                "study_years" => $study_years,
                "grades"     => $grades,
                "rooms"      => $rooms,
                "subjects"      => $subjects,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudyClassRequest $request, string $id)
    {

        $study_class = StudyClass::findOrFail($id);

        $teacher_exist = StudyClass::whereStudyYearId($request->study_year_id)
            ->whereTeacherId($request->teacher_id)->where('id', '!=', $study_class->id)->first();

        if ($teacher_exist) {
            return response()->json([
                'message' => 'Teacher already exist'
            ], 422);
        }

        $room_exist = StudyClass::whereStudyYearId($request->study_year_id)
            ->whereRoomId($request->room_id)->where('id', '!=', $study_class->id)->first();

        if ($room_exist) {
            return response()->json([
                'message' => 'Classroom already exist'
            ], 422);
        }


        DB::beginTransaction();

        try {

            $study_class->name = $request->name;
            $study_class->teacher_id = $request->teacher_id;
            $study_class->room_id = $request->room_id;
            $study_class->grade_id = $request->grade_id;
            $study_class->study_year_id = $request->study_year_id;
            $study_class->save();
            $study_class->subjects()->sync($request->subject_ids);

            Study::where('study_class_id', $request->id)->delete();

            foreach ($request->student_ids as $student_id) {
                $study = new Study();
                $study->student_id = $student_id;
                $study->study_class_id = $study_class->id;
                $study->save();
            }

            DB::commit();

            return response()->json([
                "message" => "Successfully update study class"
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'message' => 'Cannot connect to remote server.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $study_class = StudyClass::findOrFail($id);
        if ($study_class->delete()) {
            return response()->json([
                "message" => "Successfully delete study class"
            ]);
        } else {
            return response()->json([
                'message' => 'Cannot connect to remote server.'
            ], 500);
        }
    }

    public function getGradeSubject($id)
    {
        $subjects = Grade::find($id)->subjects->pluck('id');
        return response()->json([
            'subjects' => $subjects
        ]);
    }

    public function filterStudent(Request $request)
    {

        $exists_student_year = Student::orderByDesc('students.id')
            ->leftJoin('studies', 'studies.student_id', 'students.id')
            ->join('study_classes', 'study_classes.id', 'studies.study_class_id')
            ->where('study_classes.study_year_id', $request->study_year_id)
            ->whereNotNull('studies.study_class_id')
            ->pluck('students.id');

        $data = Student::whereNotIn('id', $request->student_ids)
            ->whereNotIn('id', $exists_student_year)
            ->when(request('search'), function ($q) {

                return $q
                    ->where('code', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('dob', 'LIKE', '%' . request('search') . '%')
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name)  LIKE ?", '%' . request('search') . '%')
                    ->orWhereRaw("CONCAT(first_name_latin, ' ', last_name_latin) LIKE ?", '%' . request('search') . '%')
                    ->orWhere('from', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('per_page', 15));

        return StudentResource::collection($data);
    }

    public function findStudent(Request $request)
    {
        $student = Student::findOrFail($request->student_id);
        return response()->json($student);
    }
}
