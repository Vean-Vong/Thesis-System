<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\StudentResource;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = Student::when(request('search'), function ($q) {

            return $q
                ->where('code', 'LIKE', '%' . request('search') . '%')
                ->orWhere('dob', 'LIKE', '%' . request('search') . '%')
                ->orWhereRaw("CONCAT(first_name, ' ', last_name)  LIKE ?", '%' . request('search') . '%')
                ->orWhereRaw("CONCAT(first_name_latin, ' ', last_name_latin) LIKE ?", '%' . request('search') . '%')
                ->orWhere('from', 'LIKE', '%' . request('search') . '%');
        })

            ->paginate(request('per_page', 15));

        return StudentResource::collection($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {


        $student = Student::create($request->validated());
        if ($student) {
            return response()->json([
                'message' => 'Successfully create student.'
            ]);
        }
        return response()->json([
            'message' => 'Cannot connect to remote server.'
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return new StudentResource($student);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {


        return new StudentResource($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {


        if ($student->update($request->validated())) {
            return response()->json([
                'message' => 'Successfully update student.'
            ]);
        }
        return response()->json([
            'message' => 'Cannot connect to remote server.'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {


        $tables = DB::select("SELECT TABLE_NAME FROM information_schema.columns WHERE column_name = 'student_id' AND table_schema = '" . env('DB_DATABASE') . "'");

        $data = 0;

        foreach ($tables as $table) {
            $data += DB::table($table->TABLE_NAME)->where('student_id', $student->id)->get()->count();
        }

        if ($data > 0) {

            return response()->json([
                'message' => 'This student already used!'
            ], 422);
        } else {

            if ($student->delete()) {
                return response()->json([
                    'message' => 'successfully delete student'
                ]);
            }
            return response()->json([
                'message' => 'Cannot connect to remote server.'
            ], 500);
        }
    }
}
