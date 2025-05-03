<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Filter;
use App\Models\Product;
use App\Models\Rental;
use App\Models\Report;
use App\Models\Sale;
use App\Models\Service;
use App\Models\Stock;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Utility_expenses;
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
            $result['productStock'] = Stock::sum('quantity');
            $result['filter'] = Filter::count();

            $result['totalSalesAmount'] = Sale::sum('price') + Service::sum('price') + Report::sum('cash_on_hand') + Report::sum('cash_on_bank');

            $result['totalUtility_expenses'] = Utility_expenses::sum('cost');
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}
