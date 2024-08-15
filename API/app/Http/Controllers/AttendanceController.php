<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use App\Models\Study;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\AcademicClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Attendance\StoreAttendanceRequest;

class AttendanceController extends Controller
{

    public function form(Request $request)
    {

        abort_if(Gate::denies('attendance_save'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $academic_class = AcademicClass::findOrFail($request->academic_class_id);

            $start_year = Carbon::createFromFormat('Y-m-d', $academic_class->academicYear->start_date)->format('Y');
            $start_month = Carbon::createFromFormat('Y-m-d', $academic_class->academicYear->start_date)->format('m');
            $year = $start_month <= $request->month ? $start_year : $start_year + 1 ;
            $total_day = Carbon::now()->year($year)->month($request->month)->daysInMonth;

            $attendances = Study::leftJoin('attendances', 'studies.student_id', 'attendances.student_id')
                ->join('students', 'studies.student_id', 'students.id')
                ->select('studies.student_id', 'attendances.date', 'students.gender', 'attendances.absent', 'attendances.id')
                ->selectRaw('CONCAT(students.last_name, " ", students.first_name) as name')
                ->where('attendances.academic_class_id', $request->academic_class_id)
                ->where('month', $request->month)
                ->orderBy('students.last_name')
                ->orderBy('attendances.date')
                ->whereNull('studies.deleted_at')
                ->whereNull('attendances.deleted_at')
                ->get();

            $student_has_attendance = $attendances->pluck('student_id');

            $students = Study::join('students', 'studies.student_id', 'students.id')
                ->select('studies.student_id', 'students.gender')
                ->selectRaw('CONCAT(students.last_name, " ", students.first_name) as name')
                ->where('studies.academic_class_id', $request->academic_class_id)
                ->whereNotIn('studies.student_id', $student_has_attendance)
                ->whereNull('studies.deleted_at')
                ->orderBy('students.last_name')
                ->get();

            $data = array_merge($attendances->toArray(), $students->toArray());

            $form = [
                'academic_class_id' => $request->academic_class_id,
                'month' => $request->month,
                'total_day' => $total_day,
                'attendances' => [],
            ];


                foreach($data as $key => $item) {
                    $form['attendances'][$item['name']]['gender'] = $item['gender'];
                    $form['attendances'][$item['name']]['number'] = $key+1;
                    $form['attendances'][$item['name']]['student_id'] = $item['student_id'];
                    for($i = 1; $i <= $total_day; $i ++) {
                        if(isset($item['absent'])) {
                            $day = Carbon::createFromFormat('Y-m-d', $item['date'])->format('d');
                            if($day == $i) {
                                $form['attendances'][$item['name']]['days'][$i]['absent'] = $item['absent'];
                                $form['attendances'][$item['name']]['days'][$i]['id'] = isset($item['id']) ? $item['id'] : null ;
                            } else if($day != $i && !isset($form['attendances'][$item['name']]['days'][$i]['absent']) ) {
                                $form['attendances'][$item['name']]['days'][$i]['absent'] = null;
                            }
                        } else {
                            $form['attendances'][$item['name']]['days'][$i]['absent'] =
                                null
                            ;
                        }
                    }
                }
                $result['form'] = $form;

        } catch(Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);

    }

    public function save(StoreAttendanceRequest $request)
    {

        abort_if(Gate::denies('attendance_save'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;
        DB::beginTransaction();
        try {

            $academic_class = AcademicClass::findOrFail($request->academic_class_id);

            $start_year = Carbon::createFromFormat('Y-m-d', $academic_class->academicYear->start_date)->format('Y');
            $start_month = Carbon::createFromFormat('Y-m-d', $academic_class->academicYear->start_date)->format('m');
            $year = $start_month <= $request->month ? $start_year : $start_year + 1 ;

            foreach($request->attendances as $attendance) {
                foreach($attendance['days'] as $key => $day) {
                    if($day['absent']) {
                        Attendance::updateOrCreate(
                            [
                                'id' => $day['id'] ?? null
                            ],
                            [
                                'academic_class_id' => $request->academic_class_id,
                                'student_id' => $attendance['student_id'],
                                'month' => $request->month,
                                'absent' => $day['absent'],
                                'date' => $year . '-' . $request->month . '-' . $key
                            ]
                        );
                    } else if(!$day['absent'] && isset($day['id'])) {
                        Attendance::findOrFail($day['id'])->delete();
                    }
                }
            }

            DB::commit();

            $result['message'] = "រក្សាទុកបានសម្រេច";

        } catch(Throwable $e) {
            $result['status'] = 201;
            Log::error($e);
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function show(Request $request)
    {

        abort_if(Gate::denies('attendance_list'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $academic_class = AcademicClass::findOrFail($request->academic_class_id);

            $start_year = Carbon::createFromFormat('Y-m-d', $academic_class->academicYear->start_date)->format('Y');
            $start_month = Carbon::createFromFormat('Y-m-d', $academic_class->academicYear->start_date)->format('m');
            $year = $start_month <= $request->month ? $start_year : $start_year + 1 ;
            $total_day = Carbon::now()->year($year)->month($request->month)->daysInMonth;

            $attendances = Study::leftJoin('attendances', 'studies.student_id', 'attendances.student_id')
                ->join('students', 'studies.student_id', 'students.id')
                ->select('studies.student_id', 'attendances.date', 'students.gender', 'attendances.absent')
                ->selectRaw('CONCAT(students.last_name, " ", students.first_name) as name')
                ->where('attendances.academic_class_id', $request->academic_class_id)
                ->where('month', $request->month)
                ->whereNull('studies.deleted_at')
                ->orderBy('students.last_name')
                ->orderBy('attendances.date')
                ->whereNull('attendances.deleted_at')
                ->orderBy('students.last_name')
                ->get();

            $student_has_attendance = $attendances->pluck('student_id');

            $students = Study::join('students', 'studies.student_id', 'students.id')
                ->select('studies.student_id', 'students.gender')
                ->selectRaw('CONCAT(students.last_name, " ", students.first_name) as name')
                ->where('studies.academic_class_id', $request->academic_class_id)
                ->whereNotIn('studies.student_id', $student_has_attendance)
                ->whereNull('studies.deleted_at')
                ->orderBy('students.last_name')
                ->get();

            $data = array_merge($attendances->toArray(), $students->toArray());

            $records = [
                'month' => $request->month,
                'total_day' => $total_day,
                'attendances' => [],
            ];


                foreach($data as $key => $item) {
                    $records['attendances'][$item['name']]['gender'] = $item['gender'];
                    $records['attendances'][$item['name']]['number'] = $key + 1;
                    $records['attendances'][$item['name']]['student_id'] = $item['student_id'];
                    for($i = 1; $i <= $total_day; $i ++) {
                        if(isset($item['absent'])) {
                            $day = Carbon::createFromFormat('Y-m-d', $item['date'])->format('d');
                            if($day == $i) {
                                $records['attendances'][$item['name']]['days'][$i]['absent'] = $item['absent'];
                            } else if($day != $i && !isset($records['attendances'][$item['name']]['days'][$i]['absent']) ) {
                                $records['attendances'][$item['name']]['days'][$i]['absent'] = null;
                            }
                        } else {
                            $records['attendances'][$item['name']]['days'][$i]['absent'] =
                                null
                            ;
                        }
                    }
                }
                $result['records'] = $records;

        } catch(Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);

    }

}
