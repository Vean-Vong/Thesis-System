<?php

namespace App\Http\Controllers;

use App\Http\Requests\Exam\StoreExamRequest;
use Throwable;
use App\Models\Exam;
use App\Models\Study;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\Exam\ExamFormResource;
use App\Http\Resources\Exam\ExamShowResource;

class ExamController extends Controller
{

    public function form(Request $request)
    {

        abort_if(Gate::denies('score_save'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            if($request->type != 0) {
                $semester = Exam::where('academic_class_id', $request->academic_class_id)->where('type', $request->type)->first()->semester ?? $request->semester;
            } else {
                $semester =  Exam::where('academic_class_id', $request->academic_class_id)->where('type', $request->type)->where('semester', $request->semester)->first()->semester ?? $request->semester;
            }

            $exams = Study::leftJoin('exams', 'studies.student_id', 'exams.student_id')
                ->join('students', 'studies.student_id', 'students.id')
                ->select('studies.student_id', 'students.name', 'students.dob', 'students.sex', 'm', 'k', 'sc', 's', 'studies.academic_class_id', 'exams.id')
                ->where('exams.academic_class_id', $request->academic_class_id)
                ->where('studies.academic_class_id', $request->academic_class_id)
                ->whereNull('studies.deleted_at')
                ->where('exams.type', $request->type)
                ->orderBy('students.name')
                ->when($request->type == 0, function ($q) use ($request) {
                    return $q
                        ->when($request->semester, function ($q) use ($request) {
                            return $q
                                ->where('exams.semester', $request->semester);
                        });
                })
                ->get();

            $student_has_exams = $exams->pluck('student_id');

            $students = Study::join('students', 'studies.student_id', 'students.id')
                ->select('studies.student_id', 'students.name', 'students.dob', 'students.sex')
                ->where('studies.academic_class_id', $request->academic_class_id)
                ->whereNotIn('studies.student_id', $student_has_exams)
                ->whereNull('studies.deleted_at')
                ->orderBy('students.name')
                ->get();

            $data = array_merge($students->toArray(), $exams->toArray());

            $result['form'] = [
                'academic_class_id' => $request->academic_class_id,
                'type' => $request->type,
                'semester' => $semester,
                'exams' => ExamFormResource::collection($data)
            ];



        } catch(Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);

    }

    public function save(StoreExamRequest $request)
    {
        abort_if(Gate::denies('score_save'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        DB::beginTransaction();

        try {

            foreach($request->exams as $exam) {
                Exam::updateOrCreate(
                    [
                        "id" => $exam['id'] ?? null
                    ],
                    [
                        'academic_class_id' => $request->academic_class_id,
                        'type' => $request->type,
                        'semester' => $request->semester ?? 1,
                        'student_id' => $exam['student_id'],
                        'm' => $exam['m'],
                        'k' => $exam['k'],
                        'sc' => $exam['sc'],
                        's' => $exam['s'],
                    ]
                );
            }

            DB::commit();

            $result['message'] = 'រក្សាទុកបានសម្រេច';


        } catch(Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);

    }

    public function show(Request $request)
    {

        abort_if(Gate::denies('score_list'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $exams = Student::join('studies', 'studies.student_id', 'students.id')
                ->leftJoin('exams', 'studies.student_id', 'exams.student_id')
                ->select('studies.student_id', 'students.name', 'students.dob', 'students.sex', 'm', 'k', 'sc', 's', 'studies.academic_class_id', 'exams.id')
                ->where('exams.academic_class_id', $request->academic_class_id)
                ->where('studies.academic_class_id', $request->academic_class_id)
                ->where('exams.type', $request->type)
                ->whereNull('studies.deleted_at')
                ->orderBy('students.name')
                ->when($request->type == 0, function ($q) use ($request) {
                    return $q
                        ->when($request->semester, function ($q) use ($request) {
                            return $q
                                ->where('exams.semester', $request->semester);
                        });
                })
                ->get();

            $student_has_exams = $exams->pluck('student_id');

            $students = Student::join('studies', 'studies.student_id', 'students.id')
                ->select('studies.student_id', 'students.name', 'students.dob', 'students.sex')
                ->where('studies.academic_class_id', $request->academic_class_id)
                ->whereNull('studies.deleted_at')
                ->orderBy('students.name')
                ->whereNotIn('studies.student_id', $student_has_exams)
                ->get();

            $data = array_merge($students->toArray(), $exams->toArray());

            return ExamShowResource::collection($data);
    }
}
