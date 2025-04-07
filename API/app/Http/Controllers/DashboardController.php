<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Filter;
use App\Models\Product;
use App\Models\Rental;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class DashboardController extends Controller
{
    public function summary()
    {

        $result['status'] = 200;


        try {

            $result['customers'] = Customer::count();
            $result['employees'] = Employee::count();
            $result['products'] = Product::count();
            $result['sales'] = Sale::count();
            $result['users'] = User::count();
            $result['rental'] = Rental::count();
            $result['productStock'] = Stock::count();
            $result['filter'] = Filter::count();
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
