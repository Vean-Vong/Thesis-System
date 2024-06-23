<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teacher\StoreTeacherRequest;
use App\Http\Requests\Teacher\UpdateTeacherRequest;
use Throwable;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TeacherController extends Controller
{

    public function list(Request $request)
    {

        abort_if(Gate::denies('teacher_list'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $teachers = Teacher::select('id', 'code', 'name', 'sex', 'phone', 'dob', 'position')
                ->filter(['search' => $request->search])->latest()->paginate($request->perPage);

            $result['data'] = $teachers;
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function store(StoreTeacherRequest $request)
    {

        abort_if(Gate::denies('teacher_create'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            Teacher::create($request->validated() + ['school_id' => auth()->user()->school_id]);

            $result['message'] = "រក្សាទុកបានសម្រេច";
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
    public function show(Request $request)
    {

        abort_if(Gate::denies('teacher_edit'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $teacher = Teacher::select('id', 'code', 'name', 'name_latin', 'sex', 'dob', 'pob', 'address', 'phone', 'photo_path', 'position')->findOrFail($request->id);

            $result['model'] = $teacher;
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function update(UpdateTeacherRequest $request)
    {

        abort_if(Gate::denies('teacher_edit'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $teacher = Teacher::findOrFail($request->id);

            if($teacher->update($request->validated())) {
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

        $result['status'] = 200;

        try {

            $model = Teacher::findOrFail($request->id);

            $tables = [
                'academic_classes',
            ];

            $delete = deleteFreshItem($tables, 'teacher_id', 'គ្រូបង្រៀន', $model);

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
