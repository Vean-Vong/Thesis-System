<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\AppSetting;
use App\Http\Requests\AppSetting\AppSettingRequest;

class AppSettingController extends Controller
{

    public function list()
    {

        can("access app settings", "");

        $result = ['status' => 200];

        try {
            $records = AppSetting::select('id', 'key', 'label', 'value')->get();
            $result["data"] = $records;
        } catch (Throwable $e) {
            errorLog($e->getMessage());
            $result['status'] = 201;
            $result['message'] = trans('Internal Server Error');
        }

        return response()->json($result);
    }

    public function save(AppSettingRequest $request)
    {

        can("access app settings", "");

        $result = ['status' => 200];

        try {

            foreach($request->validated()['app_settings'] as $app_setting) {
                if($app_setting['value']) {
                    AppSetting::find($app_setting['id'])->update(['value' => $app_setting['value']]);
                }
            }

            $result["message"] = trans("Successfully updated");
        } catch (Throwable $e) {
            errorLog($e->getMessage());
            $result['status'] = 201;
            $result['message'] = trans('Internal Server Error');
        }

        return response()->json($result);
    }

    public function verifySetting()
    {
        $result = ['status' => 200];

        try {
            $records = AppSetting::select('key', 'value')->get();
            $result["data"] = $records;
        } catch (Throwable $e) {
            errorLog($e->getMessage());
            $result['status'] = 201;
            $result['message'] = trans('Internal Server Error');
        }

        return response()->json($result);
    }
}