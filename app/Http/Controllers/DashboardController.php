<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DashboardController extends Controller
{

public function index()
{
    $year = now()->year;

    
    $monthlyData = DB::table('sale_items')
        ->join('products', 'sale_items.product_id', '=', 'products.id')
        ->selectRaw('
            MONTH(sale_items.created_at) as month,
            SUM(sale_items.price * sale_items.quantity) as revenue,
            SUM((sale_items.price - products.cost_price) * sale_items.quantity) as profit
        ')
        ->whereYear('sale_items.created_at', $year)
        ->groupBy(DB::raw('MONTH(sale_items.created_at)'))
        ->orderBy(DB::raw('MONTH(sale_items.created_at)'))
        ->get();

    
    $months = [];
    $revenue = [];
    $profit = [];

    for ($m = 1; $m <= 12; $m++) {
        $data = $monthlyData->firstWhere('month', $m);
        $months[] = date('F', mktime(0, 0, 0, $m, 1));
        $revenue[] = $data ? (float) $data->revenue : 0;
        $profit[] = $data ? (float) $data->profit : 0;
    }

    
    $totalSales = DB::table('sales')->sum('total');
    $totalProfit = DB::table('sale_items')
        ->join('products', 'sale_items.product_id', '=', 'products.id')
        ->select(DB::raw('SUM((sale_items.price - products.cost_price) * sale_items.quantity) as total_profit'))
        ->value('total_profit');
    $stockCount = DB::table('products')->sum('quantity');
    $recentActivities = DB::table('sales')->orderByDesc('created_at')->limit(5)->get();

    return view('dashboard.index', compact(
        'months', 'revenue', 'profit',
        'totalSales', 'totalProfit', 'stockCount', 'recentActivities'
    ));
}

}
