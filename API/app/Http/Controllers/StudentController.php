<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use Throwable;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StudentController extends Controller
{
    public function list(Request $request)
    {

        abort_if(Gate::denies('student_list'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $query = Student::filter(['search' => $request->search])->latest()->paginate($request->perPage);

            $result['data'] = $query;
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function store(StoreStudentRequest $request)
    {

        abort_if(Gate::denies('student_create'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            Student::create($request->validated());

            $result['message'] = "រក្សាទុកបានសម្រេច";
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
    public function show(Request $request)
    {

        abort_if(Gate::denies('student_edit'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $student = Student::findOrFail($request->id);

            $result['model'] = $student;
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function update(UpdateStudentRequest $request)
    {

        abort_if(Gate::denies('student_edit'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $student = Student::findOrFail($request->id);

            if($student->update($request->validated())) {
                $result['message'] = "កែប្រែបានសម្រេច";
            }

        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function delete(Request $request)
    {

        abort_if(Gate::denies('student_delete'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $model = Student::findOrFail($request->id);

            $tables = [
                'studies',
                'exams',
                'attendances'
            ];

            $delete = deleteFreshItem($tables, 'student_id', 'សិស្ស', $model);

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

}
