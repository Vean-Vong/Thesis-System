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

            $exams = Study::leftJoin('exams', 'studies.student_id', 'exams.student_id')
                ->join('students', 'studies.student_id', 'students.id')
                ->select('studies.student_id', 'students.last_name', 'students.first_name','students.gender', 'studies.academic_class_id', 'exams.*')
                ->where('exams.academic_class_id', $request->academic_class_id)
                ->where('studies.academic_class_id', $request->academic_class_id)
                ->whereNull('studies.deleted_at')
                ->orderBy('students.last_name')
                ->get();

            $student_has_exams = $exams->pluck('student_id');

            $students = Study::join('students', 'studies.student_id', 'students.id')
                ->select('studies.student_id', 'students.last_name', 'students.first_name', 'students.gender')
                ->where('studies.academic_class_id', $request->academic_class_id)
                ->whereNotIn('studies.student_id', $student_has_exams)
                ->whereNull('studies.deleted_at')
                ->orderBy('students.last_name')
                ->get();

            $data = array_merge($students->toArray(), $exams->toArray());

            // $result['form'] =$data;

            $result['form'] = ExamFormResource::collection($data);

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
                        'student_id' => $exam['student_id'] ?? 0,
                        'att' => $exam['att'] ?? 0,
                        'quiz' => $exam['quiz'] ?? 0,
                        'hw' => $exam['hw'] ?? 0,
                        're' => $exam['re'] ?? 0,
                        'voc' => $exam['voc'] ?? 0,
                        'gr' => $exam['gr'] ?? 0,
                        'liu' => $exam['liu'] ?? 0,
                        'wr' => $exam['wr'] ?? 0,
                        'li' => $exam['li'] ?? 0,
                        'sp' => $exam['sp'] ?? 0,
                        'mid' => $exam['mid'] ?? 0,
                        'attitude' => $exam['attitude'] ?? 0,
                        'final' => $exam['final'] ?? 0,
                        'total' => ($exam['att'] + $exam['quiz'] + $exam['hw'] + $exam['re'] + $exam['voc']+$exam['gr']+$exam['liu']+$exam['wr']+$exam['li']+$exam['sp']+$exam['mid']+ $exam['attitude']+$exam['final'] ) ?? 0,
                        // 'avg' => ($exam['att'] + $exam['quiz'] + $exam['hw'] + $exam['re'] + $exam['voc']+$exam['gr']+$exam['liu']+$exam['wr']+$exam['li']+$exam['sp']+$exam['mid']+ $exam['attitude']+$exam['final'] )/10 ?? 0,
                        
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

        $exams = Study::leftJoin('exams', 'studies.student_id', 'exams.student_id')
                ->join('students', 'studies.student_id', 'students.id')
                ->select('studies.student_id', 'students.last_name', 'students.first_name','students.gender', 'studies.academic_class_id', 'exams.*')
                ->where('exams.academic_class_id', $request->academic_class_id)
                ->where('studies.academic_class_id', $request->academic_class_id)
                ->whereNull('studies.deleted_at')
                ->orderBy('students.last_name')
                ->get();

            $student_has_exams = $exams->pluck('student_id');

            $students = Study::join('students', 'studies.student_id', 'students.id')
                ->select('studies.student_id', 'students.last_name', 'students.first_name', 'students.gender')
                ->where('studies.academic_class_id', $request->academic_class_id)
                ->whereNotIn('studies.student_id', $student_has_exams)
                ->whereNull('studies.deleted_at')
                ->orderBy('students.last_name')
                ->get();

            $data = array_merge($students->toArray(), $exams->toArray());

            return ExamShowResource::collection($data);
    }
}
