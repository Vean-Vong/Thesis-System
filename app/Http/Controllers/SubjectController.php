<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;


class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subjects = DB::table('subjects')

            ->when(!empty(request('search')), function ($query) use ($request) {
                return $query
                    ->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('detail', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json([
            "data" => $subjects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {

        $subject = new Subject();
        $subject->name   = $request->name;
        $subject->detail = $request->detail;
        if ($subject->save()) {
            return response()->json([
                "message" => "insert successfully"
            ]);
        } else {
            return response()->json([
                "message" => "insert unsuccessfully"
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $subject = DB::table('subjects')->where('id', $id)->first();
        return response()->json([
            "data" => $subject,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, string $id)
    {

        $subject = Subject::findOrFail($id);
        $subject->name   = $request->name;
        $subject->detail = $request->detail;
        if ($subject->save()) {
            return response()->json([
                "message" => "updated successfully"
            ]);
        } else {
            return response()->json([
                "message" => "updated unsuccessfully"
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        DB::table('subjects')->where('id', $id)->delete();
        return response()->json([
            "message" => "deleted successfully",
        ]);
    }
}
