<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Exports\DailySalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Models\StockReport;
use App\Models\SaleItem;
use Illuminate\Support\Facades\Log;





class ReportController extends Controller
{
        public function index(Request $request)
    {
        
        $month = $request->input('month');
        $year = $request->input('year');

        $query = DB::table('sales')
    ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
    ->selectRaw('
        YEAR(sales.created_at) as year,
        MONTH(sales.created_at) as month,
        SUM(sales.total) as total_sales,
        SUM(sale_items.quantity) as total_items
    ')
    ->groupBy(DB::raw('YEAR(sales.created_at), MONTH(sales.created_at)'))
    ->orderByDesc('year')
    ->orderByDesc('month');

        if ($month && $year) {
            $query->whereMonth('sales.created_at', $month)
                  ->whereYear('sales.created_at', $year);
        }

        $reports = $query->get();

        return view('reports.index', compact('reports'));
    }


  public function dailySalesReport(Request $request)
{
    $from = $request->input('from');
    $to = $request->input('to');

    $query = DB::table('sales')
        ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
        ->join('products', 'sale_items.product_id', '=', 'products.id')
        ->selectRaw('
            DATE(sales.created_at) AS sale_date,
            sales.customer_name,
            sales.phone,
            sales.address,
            products.name AS product,
            SUM(sale_items.quantity) AS total_quantity,
            SUM(sale_items.total) AS total_amount
        ')
        ->groupByRaw('
            DATE(sales.created_at),
            sales.customer_name,
            sales.phone,
            sales.address,
            products.name
        ')
        ->orderByRaw('DATE(sales.created_at) DESC');

    if ($from && $to) {
        $query->whereBetween(DB::raw('DATE(sales.created_at)'), [$from, $to]);
    }

    $reports = $query->get();

    return view('reports.daily', compact('reports', 'from', 'to'));
}


    public function monthlySalesReport(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $query = DB::table('sales')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->selectRaw('
                YEAR(sales.created_at) as year,
                MONTHNAME(sales.created_at) as month,
                SUM(sales.total) as total_sales,
                SUM(sale_items.quantity) as total_items
            ')
            ->groupByRaw('YEAR(sales.created_at), MONTHNAME(sales.created_at)')
            ->orderByRaw('YEAR(sales.created_at) DESC, MONTH(sales.created_at) DESC');

        if ($month && $year) {
            $query->whereYear('sales.created_at', $year)
                  ->whereMonth('sales.created_at', $month);
        }

        $reports = $query->get();

        return view('reports.monthly', compact('reports', 'month', 'year'));
    }


public function monthlyPdf(Request $request)
{
     $from = $request->input('from');
    $to = $request->input('to');

    $query = DB::table('sales')
        ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
        ->join('products', 'sale_items.product_id', '=', 'products.id')
        ->selectRaw('
            DATE(sales.created_at) AS sale_date,
            sales.customer_name,
            sales.phone,
            sales.address,
            products.name AS product,
            SUM(sale_items.quantity) AS total_quantity,
            SUM(sale_items.total) AS total_amount
        ')
        ->groupByRaw('
            DATE(sales.created_at),
            sales.customer_name,
            sales.phone,
            sales.address,
            products.name
        ')
        ->orderByRaw('DATE(sales.created_at) DESC');

    if ($from && $to) {
        $query->whereBetween(DB::raw('DATE(sales.created_at)'), [$from, $to]);
    }

    $reports = $query->get();


    $pdf = PDF::loadView('reports.monthly_pdf', compact('reports', 'from', 'to'));
    return $pdf->download("Monthly_Sales_{$from}_to_{$to}.pdf");
}


public function stockReport(Request $request)
{
    $query = StockReport::with('product');

    if ($request->filled('from') && $request->filled('to')) {
        $query->whereBetween('report_date', [$request->from, $request->to]);
     }

    $stockReports = $query->get();

    return view('reports.stock', compact('stockReports'));
}


public function profitreport(Request $request)
{
    $month = $request->input('month', now()->format('m'));
    $year = $request->input('year', now()->format('Y'));

    $profits = DB::table('sale_items')
        ->join('products', 'sale_items.product_id', '=', 'products.id')
        ->select(
            'products.name as product_name',
            DB::raw('SUM(sale_items.quantity) as quantity_sold'),
            DB::raw('SUM(sale_items.price * sale_items.quantity) as total_revenue'),
            DB::raw('SUM(products.cost_price * sale_items.quantity) as total_cost'),
            DB::raw('SUM((sale_items.price - products.cost_price) * sale_items.quantity) as total_profit')
        )
        ->whereYear('sale_items.created_at', $year)
        ->whereMonth('sale_items.created_at', $month)
        ->groupBy('products.name')
        ->orderBy('total_profit', 'desc')
        ->get();

    return view('reports.profit', compact('profits', 'month', 'year'));
}

}


