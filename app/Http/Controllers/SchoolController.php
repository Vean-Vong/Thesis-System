<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\SchoolResource;
use App\Http\Requests\School\StoreSchoolRequest;
use App\Http\Requests\School\UpdateSchoolRequest;

class SchoolController extends Controller
{
    public function list(Request $request)
    {

        abort_if(Gate::denies('school_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $data = School::select('id', 'khmer_name', 'code', 'short_name')
                ->filter(['search' => $request->search])
                ->OrderBy('sort')->paginate($request->perPage);

            $result['data'] = $data;
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function store(StoreSchoolRequest $request)
    {

        abort_if(Gate::denies('school_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $model = School::create($request->validated());

            if ($model) {
                $result['message'] = "បង្កើតបានសម្រេច";
            }
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function show(Request $request)
    {

        abort_if(Gate::denies('school_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $model = School::findOrFail($request->id);

            $result['model'] = new SchoolResource($model);
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }

    public function update(UpdateSchoolRequest $request)
    {

        abort_if(Gate::denies('school_access'), 403, 'អ្នកមិនអាចប្រើប្រាស់ចំណុចនេះទេ។');

        $result['status'] = 200;

        try {

            $model = School::findOrFail($request->id);

            if ($model->update($request->validated())) {
                $result['message'] = "កែប្រែបានសម្រេច";
            }
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }
        return response()->json($result);
    }
}
