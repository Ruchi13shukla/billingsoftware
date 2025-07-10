<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Sale;


class SalesReportController extends Controller
{
        public function index(Request $request)
    {
        $reports = DB::table('sales')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->selectRaw('
                YEAR(sales.created_at) as year,
                MONTH(sales.created_at) as month_number,
                MONTHNAME(sales.created_at) as month,
                SUM(sale_items.quantity) as total_items,
                SUM(sales.total) as total_sales
            ')
            ->groupBy('year', 'month_number', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month_number', 'desc')
            ->get();

        return view('reports.sales-reports', compact('reports'));
    }


    public function productWiseReport(Request $request)
{
    $query = DB::table('sales')
        ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
        ->join('products', 'sale_items.product_id', '=', 'products.id')
        ->selectRaw('
            YEAR(sales.created_at) as year,
            MONTHNAME(sales.created_at) as month,
            products.name as product,
            sales.customer_name,
            sales.phone,
            sales.address,
            SUM(sale_items.quantity) as total_quantity,
            SUM(sale_items.total) as total_amount
        ')
        ->groupBy('year', 'month', 'products.name', 'sales.customer_name', 'sales.phone', 'sales.address')
        ->orderBy('year', 'desc')
        ->orderBy(DB::raw('MONTH(sales.created_at)'), 'desc');

    if ($request->customer_name) {
        $query->where('sales.customer_name', 'like', '%' . $request->customer_name . '%');
    }

    if ($request->product) {
        $query->where('products.name', 'like', '%' . $request->product . '%');
    }

    if ($request->from_date) {
        $query->whereDate('sales.created_at', '>=', $request->from_date);
    }

    if ($request->to_date) {
        $query->whereDate('sales.created_at', '<=', $request->to_date);
    }

    $reports = $query->get();

    return view('sales.product-wise', compact('reports'));
}


public function exportDailySalesPdf(Request $request)
{
    $from = $request->input('from');
    $to = $request->input('to');

    $query = Sale::query();

    if ($from && $to) {
        $query->whereBetween('sale_date', [$from, $to]);
    }

    $reports = $query->with('customer', 'product')->get();

    $pdf = Pdf::loadView('reports.daily_sales_pdf', compact('reports', 'from', 'to'))
              ->setPaper('a4', 'landscape');

    return $pdf->download('daily-sales-report.pdf');
}

}
