<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

if(!function_exists('filterMonth')) {
    function filterMonth($month)
    {
        if($month == 1) {
            return 'មករា';
        } else if($month == 2) {
            return 'កុម្ភៈ';
        } else if($month == 3) {
            return 'មីនា';
        } else if($month == 4) {
            return 'មេសា';
        } else if($month == 5) {
            return 'ឧសភា';
        } else if($month == 6) {
            return 'មិថុនា';
        } else if($month == 7) {
            return 'កក្កដា';
        } else if($month == 8) {
            return 'សីហា';
        } else if($month == 9) {
            return 'កញ្ញា';
        } else if($month == 10) {
            return 'តុលា';
        } else if($month == 11) {
            return 'វិច្ឆិកា';
        } else if($month == 12) {
            return 'ធ្នូ';
        }
    }
}


if (!function_exists('deleteFreshItem')) {
    function deleteFreshItem($tables, $fk, $name , $model)
    {
        $data = 0;

        foreach ($tables as $table) {
            $data += DB::table($table)->where($fk, $model->id)->get()->count();
        }

        $result = [];

        if ($data > 0) {
            $result['message'] = $name . "នេះបានប្រើប្រាស់រួចហើយ";
            $result['status'] = false;
        } else {
            $model->delete();
            $result['status'] = true;
            $result['message'] = "លុបបានសម្រច";
        }

        return $result;
    }
}

if(!function_exists('can')) {
    function can($permission1, $permission2)
    {
        if(!$permission2) abort_if(Gate::denies($permission1), 403, trans("You don't have permission to access this page."));
        else abort_if(Gate::denies($permission1) && Gate::denies($permission2), 403, trans("You don't have permission to access this page."));
    }
}

if(!function_exists('errorLog')) {
    function errorLog($message) {
        DB::insert("INSERT INTO error_logs (id, message, user_id, end_point, created_at) VALUES (?,?,?,?,?)", [(string) Str::uuid(), $message, auth()->id(), request()->path(), now()]);
    }
}

if(!function_exists('NotFoundResourceException')) {
    function NotFoundResourceException($id, $model = '') {
        $message = trans("Could not find ") . trans($model) . trans(' with ') . 'id = ' . $id;
        return response()->json([
            'message' => App::currentLocale() == 'en' ? $message : str_replace(' ', '', $message)
        ], 404);
    }
}
