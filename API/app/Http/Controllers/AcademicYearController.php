<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\AcademicYear\StoreAcademicYearRequest;
use App\Http\Requests\AcademicYear\UpdateAcademicYearRequest;
use App\Models\AcademicYear;

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
}
