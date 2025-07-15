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
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Service;
use App\Models\Setup;
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
            $result['products'] = Product::sum('quantity');
            $result['sales'] = Sale::count();
            $result['users'] = User::count();
            $result['rental'] = Rental::count();
            $result['service'] = Service::count();
            $result['reports'] = Report::count();
            $result['productStock'] = Stock::sum('quantity');
            $result['filter'] = Filter::count();

            $result['totalSalesAmount'] = Sale::sum('price') + Service::sum('price') + Rental::sum('price') + Report::sum('cash_on_hand') + Report::sum('cash_on_bank') + Setup::sum('cash_on_hand') + Setup::sum('cash_on_bank');

            $result['totalUtility_expenses'] = Utility_expenses::sum('cost');


            // Monthly Sale Totals
            $sales = Sale::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(price) as total')
            )->groupBy(DB::raw('MONTH(date)'))->pluck('total', 'month');

            // Monthly Rental Totals
            $rentals = Rental::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(price) as total')
            )->groupBy(DB::raw('MONTH(date)'))->pluck('total', 'month');

            // Monthly Service Totals
            $services = Service::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(price) as total')
            )->groupBy(DB::raw('MONTH(date)'))->pluck('total', 'month');

            // Monthly Report Totals
            $reports = Report::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(cash_on_hand + cash_on_bank) as total')
            )->groupBy(DB::raw('MONTH(date)'))->pluck('total', 'month');

            // Monthly Setup Tables
            $setup = Setup::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(cash_on_hand + cash_on_bank) as total')
            )->groupBy(DB::raw('MONTH(created_at)'))->pluck('total', 'month');

            // Combine all into one monthly total
            $monthlySales = [];

            for ($month = 1; $month <= 12; $month++) {
                $monthlySales[$month] =
                    ($sales[$month] ?? 0) +
                    ($rentals[$month] ?? 0) +
                    ($services[$month] ?? 0) +
                    ($reports[$month] ?? 0) +
                    ($setup[$month] ?? 0);
            }

            $result['monthlySales'] = $monthlySales;
        } catch (Throwable $e) {
            $result['status'] = 201;
            $result['message'] = $e->getMessage();
        }

        return response()->json($result);
    }
}