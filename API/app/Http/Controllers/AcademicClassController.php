<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Study;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\CarbonPeriod;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\AcademicClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\AcademicClass\StoreAddStudentRequest;
use App\Http\Requests\AcademicClass\StoreAcademicClassRequest;
use App\Http\Requests\AcademicClass\UpdateAcademicClassRequest;
use App\Models\Level;
use App\Models\Room;
use App\Models\Time;

class AcademicClassController extends Controller
{

    public function list(Request $request)
    {

        abort_if(Gate::denies('academic_class_list'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $academic_year_id = $request->academic_year_id ?? AcademicYear::whereIsActive(1)->first()->id ?? 1;

            $academic_classes = AcademicClass::select('id', 'name', 'teacher_id', 'room_id', 'academic_year_id')
                ->with(['teacher', 'room'])
                ->filter([
                    'search' => $request->search,
                    'academic_year_id' => $academic_year_id
                ])
                ->latest()->paginate($request->perPage);

            $result['data'] = $academic_classes;
            $result['academic_years'] = AcademicYear::select('id', 'name')->orderByDESC('is_active')->get();
            $result['academic_year_id'] = $academic_year_id;

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);

    }

    public function store(StoreAcademicClassRequest $request)
    {
        abort_if(Gate::denies('academic_class_create'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

          AcademicClass::create($request->validated() + [
            'school_id' => auth()->user()->school_id
          ]);

          $result['message'] = "រក្សាទុកបានសម្រេច";

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function show(Request $request)
    {
        abort_if(Gate::denies('academic_class_edit'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $model = AcademicClass::findOrFail($request->id);

            $result['model'] = $model;

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function update(UpdateAcademicClassRequest $request)
    {
        abort_if(Gate::denies('academic_class_edit'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $model = AcademicClass::findOrFail($request->id);

            $model->update($request->validated());

            $result['message'] = "កែប្រែបានសម្រេច";

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function delete(Request $request)
    {
        abort_if(Gate::denies('academic_class_delete'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $model = AcademicClass::findOrFail($request->id);

            $tables = [
                'studies',
                'exams',
                'attendances'
            ];

            $delete = deleteFreshItem($tables, 'academic_class_id', 'ថ្នាក់រៀន', $model);

            $result['message'] = $delete['message'];

            if(!$delete['status']) {
                $result['status'] = 201;
            }

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function option(Request $request)
    {
        abort_if(Gate::denies('academic_class_create') && Gate::denies('academic_class_edit'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $result['teachers'] = Teacher::orderByDESC('name')->get();
            $result['times'] = Time::orderByDESC('time')->get();
            $result['levels'] = Level::orderByDESC('level')->get();
            $result['rooms'] = Room::orderByDESC('room')->get();
            $result['academic_years'] = AcademicYear::select('id', 'name')->orderByDESC('is_active')->get();

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function detail(Request $request)
    {
        abort_if(Gate::denies('academic_class_list'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $model = AcademicClass::with(['teacher' => function ($q) {
                $q->select('id', 'name');
            }, 'academicYear' => function ($q) {
                $q->select('id', 'name');
            }])->select('id', 'name', 'teacher_id', 'academic_year_id')->findOrFail($request->id);

            $result['model'] = $model;

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function searchStudent(Request $request)
    {
        abort_if(Gate::denies('manage_student_class_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $exists_student_year = Student::orderByDesc('students.id')
            ->leftJoin('studies', 'studies.student_id', 'students.id')
            ->join('academic_classes', 'academic_classes.id', 'studies.academic_class_id')
            ->where('academic_classes.academic_year_id', $request->academic_year_id)
            ->where('students.status', 1)
            ->whereNotNull('studies.academic_class_id')
            ->whereNull('studies.deleted_at')
            ->pluck('students.id');

            $result['data'] = Student::whereNotIn('id', $exists_student_year)
            ->filter([
                'search' => $request->search,
            ])
            ->select('id', 'code','last_name', 'first_name', 'gender','dob')
            ->latest()
            ->paginate($request->perPage);

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function addStudent(StoreAddStudentRequest $request)
    {
        abort_if(Gate::denies('manage_student_class_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        DB::beginTransaction();

        try {

            foreach($request->student_ids as $student_id) {
                Study::create([
                    'academic_class_id' => $request->academic_class_id,
                    'student_id' => $student_id
                ]);
            }

            DB::commit();

            $result['message'] = 'រក្សាទុកបានសម្រេច';

        } catch (Throwable $e) {
            DB::rollBack();
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function listStudent(Request $request)
    {

        $result['status'] = 200;


        try {

            $result['data'] = Study::where('academic_class_id', $request->academic_class_id)->whereHas('student', function ($q) use ($request) {
                $q->filter(['search' => $request->search]);
            })
                ->orderBy(Student::select('last_name')
                ->whereColumn('students.id', 'studies.student_id'))
                ->select('id', 'student_id', 'status')->get()->load(['student' => function ($q){
                    $q->select('id', 'code','last_name', 'first_name', 'gender', 'dob');
                }]);

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function removeStudent(Request $request)
    {
        abort_if(Gate::denies('manage_student_class_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;


        try {

            $model = Study::findOrFail($request->id);

            $tables = [
                'exams',
                'attendances'
            ];

            $data = 0;

            foreach ($tables as $table) {

                $data += DB::table($table)->where('student_id', $model->student_id)
                    ->join('academic_classes', 'academic_classes.id', $table .'.academic_class_id')
                    ->where('academic_classes.academic_year_id', $model->academicClass->academic_year_id)->get()->count();
            }

            if ($data > 0) {
                $result['status'] = 201;
                $result['message'] = "សិស្សនេះត្រូវបានយកទៅប្រើប្រាស់";
            } else {
                $model->delete();
                $result['message'] = 'លុបបានសម្រេច';
            }

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function moveStudent(Request $request)
    {
        abort_if(Gate::denies('manage_student_class_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;


        try {

            $model = Study::findOrFail($request->id);

            $model->status = 0;

            $model->save();

            $result['message'] = 'ផ្ទេរសិស្សបានសម្រេច';

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function getMonth(Request $request)
    {
        abort_if(Gate::denies('score_save') && Gate::denies('score_list') && Gate::denies('attendance_save') && Gate::denies('attendance_list'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $model = AcademicClass::findOrFail($request->id);


        $data = CarbonPeriod::create($model->academicYear->start_date, '1 month', $model->academicYear->end_date);

        foreach($data as $k => $item) {
            $months[$k]['id'] = (int) $item->format('m');
            $months[$k]['name'] = filterMonth((int) $item->format('m'));
        }

        return $months;

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

}
