<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\AcademicYear\StoreAcademicYearRequest;
use App\Http\Requests\AcademicYear\UpdateAcademicYearRequest;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\Study;

class AcademicYearController extends Controller
{
    public function list(Request $request)
    {

        abort_if(Gate::denies('academic_year_list'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $academic_years = AcademicYear::filter(['search' => $request->search])->orderByDESC('is_active')->paginate($request->perPage);
            $result['data'] = $academic_years;

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);

    }

    public function store(StoreAcademicYearRequest $request)
    {
        abort_if(Gate::denies('academic_year_create'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

          AcademicYear::create($request->validated());

          $result['message'] = "រក្សាទុកបានសម្រេច";

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function show(Request $request)
    {
        abort_if(Gate::denies('academic_year_edit'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $model = AcademicYear::findOrFail($request->id);

            $result['model'] = $model;

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function update(UpdateAcademicYearRequest $request)
    {
        abort_if(Gate::denies('academic_year_edit'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $model = AcademicYear::findOrFail($request->id);

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
        abort_if(Gate::denies('academic_year_delete'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $model = AcademicYear::findOrFail($request->id);

            $tables = [
                'academic_classes'
            ];

            $delete = deleteFreshItem($tables, 'academic_year_id', 'ឆ្នាំសិក្សា', $model);

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

    public function result(Request $req){

        $result['status'] = 200;

        try {

            $data = [
                'englist'=>
                [
                    'pass' => [],
                    'fail' => [],
                    'drop' => [],
                    'awd' => [],
                    'beg' => [],
                    'end' => [],
                ],
                'computer'=>
                [
                    'pass' => [],
                    'fail' => [],
                    'drop' => [],
                    'awd' => [],
                    'beg' => [],
                    'end' => [],
                ]
            ];

            // englist
            $englist = Exam::join('academic_classes', 'academic_classes.id', 'academic_class_id')
                ->join('students', 'students.id', 'student_id')->where('academic_classes.type', 2)
                ->where('academic_year_id', $req->term_id);
            $data['englist']['fail']['total'] = $englist->where('total', '<', 50)->count();
            $data['englist']['fail']['M'] = $englist->where('students.gender', 1)->count();
            $data['englist']['fail']['F'] = $data['englist']['fail']['total'] - $data['englist']['fail']['M'];

            $englist = Exam::join('academic_classes', 'academic_classes.id', 'academic_class_id')
                ->join('students', 'students.id', 'student_id')->where('academic_classes.type', 2)
                ->where('academic_year_id', $req->term_id);
            $data['englist']['pass']['total'] = $englist->where('total', '>=', 50)->count();
            $data['englist']['pass']['M'] = $englist->where('students.gender', 1)->count();
            $data['englist']['pass']['F'] = $data['englist']['pass']['total'] - $data['englist']['pass']['M'];

            $englist = Study::join('academic_classes', 'academic_classes.id', 'academic_class_id')
                ->join('students', 'students.id', 'student_id')->where('academic_classes.type', 2)
                ->where('academic_year_id', $req->term_id);
            $data['englist']['drop']['total'] = $englist->where('studies.status', '<>', 1)->count();
            $data['englist']['drop']['M'] = $englist->where('students.gender', 1)->count();
            $data['englist']['drop']['F'] = $data['englist']['drop']['total'] - $data['englist']['drop']['M'];

            // $englist = Exam::join('academic_classes', 'academic_classes.id', 'academic_class_id')
            //     ->join('students', 'students.id', 'student_id')->where('academic_classes.type', 2)
            //     ->where('academic_year_id', $req->term_id);
            $data['englist']['awd']['total'] = 0;
            $data['englist']['awd']['M'] = 0;
            $data['englist']['awd']['F'] = $data['englist']['awd']['total'] - $data['englist']['awd']['M'];

            $englist = Study::join('academic_classes', 'academic_classes.id', 'academic_class_id')
                ->join('students', 'students.id', 'student_id')->where('academic_classes.type', 2)
                ->where('academic_year_id', $req->term_id);
            $data['englist']['beg']['total'] = $englist->count();
            $data['englist']['beg']['M'] = $englist->where('students.gender', 1)->count();
            $data['englist']['beg']['F'] = $data['englist']['beg']['total'] - $data['englist']['beg']['M'];


            $englist = Study::join('academic_classes', 'academic_classes.id', 'academic_class_id')
                ->join('students', 'students.id', 'student_id')->where('academic_classes.type', 2)
                ->where('academic_year_id', $req->term_id);
            $data['englist']['end']['total'] = $englist->where('studies.status', 1)->count();
            $data['englist']['end']['M'] = $englist->where('students.gender', 1)->count();
            $data['englist']['end']['F'] = $data['englist']['end']['total'] - $data['englist']['end']['M'];

            // computer
            $computer = Exam::join('academic_classes', 'academic_classes.id', 'academic_class_id')
                ->join('students', 'students.id', 'student_id')->where('academic_classes.type', 3)
                ->where('academic_year_id', $req->term_id);
            $data['computer']['fail']['total'] = $computer->where('total', '<', 50)->count();
            $data['computer']['fail']['M'] = $computer->where('students.gender', 1)->count();
            $data['computer']['fail']['F'] = $data['computer']['fail']['total'] - $data['computer']['fail']['M'];

            $computer = Exam::join('academic_classes', 'academic_classes.id', 'academic_class_id')
                ->join('students', 'students.id', 'student_id')->where('academic_classes.type', 3)
                ->where('academic_year_id', $req->term_id);
            $data['computer']['pass']['total'] = $computer->where('total', '>=', 50)->count();
            $data['computer']['pass']['M'] = $computer->where('students.gender', 1)->count();
            $data['computer']['pass']['F'] = $data['computer']['pass']['total'] - $data['computer']['pass']['M'];

            $computer = Study::join('academic_classes', 'academic_classes.id', 'academic_class_id')
                ->join('students', 'students.id', 'student_id')->where('academic_classes.type', 3)
                ->where('academic_year_id', $req->term_id);
            $data['computer']['drop']['total'] = $computer->where('studies.status', '<>', 1)->count();
            $data['computer']['drop']['M'] = $computer->where('students.gender', 1)->count();
            $data['computer']['drop']['F'] = $data['computer']['drop']['total'] - $data['computer']['drop']['M'];

            // $computer = Exam::join('academic_classes', 'academic_classes.id', 'academic_class_id')
            //     ->join('students', 'students.id', 'student_id')->where('academic_classes.type', 3)
            //     ->where('academic_year_id', $req->term_id);
            $data['computer']['awd']['total'] = 0;
            $data['computer']['awd']['M'] = 0;
            $data['computer']['awd']['F'] = $data['computer']['awd']['total'] - $data['computer']['awd']['M'];

            $computer = Study::join('academic_classes', 'academic_classes.id', 'academic_class_id')
                ->join('students', 'students.id', 'student_id')->where('academic_classes.type', 3)
                ->where('academic_year_id', $req->term_id);
            $data['computer']['beg']['total'] = $computer->count();
            $data['computer']['beg']['M'] = $computer->where('students.gender', 1)->count();
            $data['computer']['beg']['F'] = $data['computer']['beg']['total'] - $data['computer']['beg']['M'];


            $computer = Study::join('academic_classes', 'academic_classes.id', 'academic_class_id')
                ->join('students', 'students.id', 'student_id')->where('academic_classes.type', 3)
                ->where('academic_year_id', $req->term_id);
            $data['computer']['end']['total'] = $computer->where('studies.status', 1)->count();
            $data['computer']['end']['M'] = $computer->where('students.gender', 1)->count();
            $data['computer']['end']['F'] = $data['computer']['end']['total'] - $data['computer']['end']['M'];


            $result['data'] = $data;
            $result['term'] = AcademicYear::findOrFail($req->term_id);

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
