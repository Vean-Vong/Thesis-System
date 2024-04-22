<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RoomResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $data = Room::when(request('search'), function ($q) {
            return $q
                ->where('name', 'LIKE', '%' . request('search') . '%');
        })->latest()->paginate(request('per_page', 15));

        return RoomResource::collection($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {

        if (Room::create($request->validated())) {
            return response()->json([
                'message' => 'Successfully create room.'
            ]);
        }
        return response()->json([
            'message' => 'Cannot connect to remote server.'
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {


        return new RoomResource($room);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {

        if ($room->update($request->validated())) {
            return response()->json([
                'message' => 'Successfully update room.'
            ]);
        }
        return response()->json([
            'message' => 'Cannot connect to remote server.'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {


        $tables = DB::select("SELECT TABLE_NAME FROM information_schema.columns WHERE column_name = 'room_id' AND table_schema = '" . env('DB_DATABASE') . "'");

        $data = 0;

        foreach ($tables as $table) {
            $data += DB::table($table->TABLE_NAME)->where('room_id', $room->id)->get()->count();
        }

        if ($data > 0) {

            return response()->json([
                'message' => 'This room already used!'
            ], 422);
        } else {

            if ($room->delete()) {
                return response()->json([
                    'message' => 'successfully delete room'
                ]);
            }
            return response()->json([
                'message' => 'Cannot connect to remote server.'
            ], 500);
        }
    }
}
