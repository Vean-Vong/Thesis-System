<?php

namespace App\Http\Controllers;

use App\Models\StudyYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreStudyYearRequest;
use App\Http\Requests\UpdateStudyYearRequest;
use App\Http\Resources\StudyYearResource;

class StudyYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {



        $data = StudyYear::when(request('search'), function ($q) {
            return $q
                ->where('name', 'LIKE', '%' . request('search') . '%');
        })->latest()->paginate(request('per_page', 15));

        return StudyYearResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudyYearRequest $request)
    {


        $study_year = new StudyYear();
        $study_year->name  = $request->name;
        $study_year->start = $request->start;
        $study_year->end   = $request->end;
        $study_year->is_active   = $request->is_active;
        if($study_year->is_active == 1) {
            $active = StudyYear::where('is_active', 1)->first();
            $active->is_active = 0;
            $active->save();
        }
        if ($study_year->save()) {
            return response()->json([
                "message" => "insert succesfully",
            ]);
        } else {
            return response()->json([
                "message" => "insert unsuccesfully",
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {


        $study_year = DB::table('study_years')->where('id', $id)->first();
        return response()->json([
            "data" => $study_year,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudyYearRequest $request, string $id)
    {


        $study_year = StudyYear::findOrFail($id);
        $study_year->name  = $request->name;
        $study_year->start = $request->start;
        $study_year->end   = $request->end;
        $study_year->is_active   = $request->is_active;
        if($study_year->is_active == 1) {
            $active = StudyYear::where('is_active', 1)->first();
            $active->is_active = 0;
            $active->save();
        }
        if ($study_year->save()) {
            return response()->json([
                "message" => "updated succesfully",
            ]);
        } else {
            return response()->json([
                "message" => "updated unsuccesfully",
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('study_years')->where('id', $id)->delete();
        return response()->json([
            "message" => "deleted successfully",
        ]);
    }
}
