<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attendance\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Study;
use App\Models\StudyClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AttendanceController extends Controller
{

    public function form(StudyClass $study_class, Request $request)
    {
        $attendances = Study::join('students', 'students.id', 'studies.student_id')
            ->leftJoin('attendances', 'attendances.student_id', 'studies.student_id')
            ->where('studies.study_class_id', $study_class->id)
            ->where('attendances.date', $request->date)
            ->where('attendances.meridiem', $request->meridiem)
            ->select('attendances.id', 'attendances.absent', 'studies.student_id', DB::raw("CONCAT(students.first_name, ' ' ,students.last_name) AS full_name"),  "students.gender", "attendances.reason")
            ->get();

            $student_has_attendance = $attendances->pluck('student_id');

        $students = Study::join('students', 'studies.student_id', 'students.id')
            ->select('studies.student_id', DB::raw("CONCAT(students.first_name, ' ' ,students.last_name) AS full_name"), 'students.gender')
            ->where('studies.study_class_id', $study_class->id)
            ->whereNotIn('studies.student_id', $student_has_attendance)
            ->orderBy('students.first_name')
            ->get();

        $data = array_merge($attendances->toArray(), $students->toArray());


        return response()->json($data);
    }

    public function save(StudyClass $study_class, AttendanceRequest $request)
    {

        DB::beginTransaction();

        try {

            foreach($request->details as $attendance) {

               if(!empty(isset($attendance['absent']))) {
                    Attendance::updateOrCreate(
                        [
                            'id' => $attendance['id'] ?? null
                        ],
                        [
                            'student_id' => $attendance['student_id'],
                            'study_class_id' => $study_class->id,
                            'date' => $request->date,
                            'absent' => $attendance['absent'] ?? null,
                            'meridiem' => $request->meridiem,
                            'reason' => $attendance['reason'] ?? null,
                            'month'=> date("m",strtotime($request->date))
                        ]
                    );
               }
               if(empty(isset($attendance['absent'])) && !empty(isset($attendance['id']))) {
                    Attendance::find($attendance['id'])->delete();
               }
            }

            DB::commit();

            return response()->json(['message' => 'Successfully create attendance']);


        } catch(Throwable $e) {
            Log::error($e);
            DB::rollBack();
            return response()->json(['message' => 'Cannot connect to remote server'], 500);
        }
    }

    public function list(StudyClass $study_class, Request $request)
    {

        $data = Study::join('students', 'students.id', 'studies.student_id')
            ->leftJoin('attendances', 'attendances.student_id', 'studies.student_id')
            ->where('studies.study_class_id', $study_class->id)
            ->where('attendances.date', $request->date)
            ->where('attendances.meridiem', $request->meridiem)
            ->select('attendances.id', 'attendances.absent', 'studies.student_id', DB::raw("CONCAT(students.first_name, ' ' ,students.last_name) AS full_name"),  "students.gender", "attendances.reason")
            ->get();

        return response()->json($data);
    }

    public function report(StudyClass $study_class, Request $request)
    {

        $attendances = Attendance::where('study_class_id', $study_class->id)
            ->select('attendances.student_id', 'attendances.absent', 'attendances.month')
            ->get();

        $students = Study::where('study_class_id', $study_class->id)
            ->join('students', 'students.id', 'studies.student_id')
            ->select(DB::raw("CONCAT(students.first_name, ' ', students.last_name) as name"), 'students.gender', 'students.code', 'students.id')->get();

        $data = [];

        foreach($students as $k => $student) {
            $data[$k][] = $student->code;
            $data[$k][] = $student->name;
            $data[$k][] = $student->gender == 1 ? 'ប្រុស' : 'ស្រី';
            $data[$k][] = 0;
            $data[$k][] = 0;
            $data[$k][] = 0;
            foreach($attendances as $k2 => $attendance) {
                if(!empty($request->month)) {
                    if($student->id == $attendance->student_id) {
                        if($attendance->month == $request->month) {
                            if($attendance->absent == 1) {
                                $data[$k][3] += 1;
                            } elseif($attendance->absent == 2) {
                                $data[$k][4] += 1;
                            }
                            $data[$k][5] += 1;
                        }
                    }
                } else {
                    if($student->id == $attendance->student_id) {
                        if($attendance->absent == 1) {
                            $data[$k][3] += 1;
                        } elseif($attendance->absent == 2) {
                            $data[$k][4] += 1;
                        }
                        $data[$k][5] += 1;
                    }
                }
            }
        }

        return response()->json([
            'data' => $data,
            'month_text' => filterMonth($request->month) ?? null
        ]);

    }

}
