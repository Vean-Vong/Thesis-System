<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\GradeResource;
use App\Http\Requests\Grade\StoreGradeRequest;
use App\Http\Requests\Grade\UpdataGradeRequest;
use App\Models\Subject;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Grade::when(request('search'), function ($q) {
            return $q
                ->where('name', 'LIKE', '%' . request('search') . '%');
        })->latest()->paginate(request('per_page', 15));

        return GradeResource::collection($data);
    }

    /**
     * Show the form for creating a new resourc   e.
     */
    public function create()
    {
        return response()->json([
            'subjects' => Subject::select('id', 'name')->where('status', 1)->latest()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGradeRequest $request)
    {
        $grade = new Grade();;
        $grade->name = $request->name;
        $grade->status = $request->status;
        if ($grade->save()) {
            $grade->subjects()->attach($request->subject_ids);
            return response()->json([
                "message" => "Successfully create grade"
            ]);
        } else {
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $grade = Grade::with('subjects')->findOrFail($id);
        return response()->json([
            'data' => new GradeResource($grade),
            'subjects' => Subject::select('id', 'name')->where('status', 1)->latest()->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdataGradeRequest $request, string $id)
    {
        $grade = Grade::findOrFail($id);
        $grade->name = $request->name;
        $grade->status = $request->status;
        if ($grade->update()) {
            $grade->subjects()->sync($request->subject_ids);
            return response()->json([
                "message" => "Successfully update grade"
            ]);
        } else {
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
        $grade = Grade::findOrFail($id);
        if ($grade->delete()) {
            return response()->json([
                "message" => "Successfully delete grade"
            ]);
        } else {
            return response()->json([
                'message' => 'Cannot connect to remote server.'
            ], 500);
        }
    }
}
