<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TeacherResource;
use App\Http\Requests\Teacher\StoreTeacherRequest;
use App\Http\Requests\Teacher\UpdateTeacherRequest;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Teacher::when(request('search'), function ($q) {

            return $q
                ->where('code', 'LIKE', '%' . request('search') . '%')
                ->orWhereRaw("CONCAT(first_name, ' ', last_name)  LIKE ?", '%' . request('search') . '%')
                ->orWhereRaw("CONCAT(first_name_latin, ' ', last_name_latin) LIKE ?", '%' . request('search') . '%');
        })

            ->paginate(request('per_page', 15));

        return TeacherResource::collection($data);
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
    public function store(StoreTeacherRequest $request)
    {
        $model = Teacher::create($request->validated());

        if($model) return response()->json(['message' => 'Successfully create teacher.']);

        return response()->json(['message' => 'Cannot connect to remote server.'], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        return new TeacherResource($teacher);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        return new TeacherResource($teacher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        if($teacher->update($request->validated())) return response()->json(['message' => 'Successfully update teacher.']);
        return response()->json(['message' => 'Cannot connect to remote server.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {

        $tables = DB::select("SELECT TABLE_NAME FROM information_schema.columns WHERE column_name = 'teacher_id' AND table_schema = '" . env('DB_DATABASE') . "'");

        $data = 0;

        foreach ($tables as $table) {
            $data += DB::table($table->TABLE_NAME)->where('teacher_id', $teacher->id)->get()->count();
        }

        if ($data > 0) {

            return response()->json([
                'message' => 'This teacher already used!'
            ], 422);
        } else {

            if ($teacher->delete()) {
                return response()->json([
                    'message' => 'successfully delete teacher'
                ]);
            }
            return response()->json([
                'message' => 'Cannot connect to remote server.'
            ], 500);
        }
    }
}
