<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockReport;


class StockController extends Controller
{

     public function dailyReport(Request $request)
{
    $date = $request->input('date', now()->toDateString());

    $reports = StockReport::with('product')->where('report_date', $date)->get();

    return view('stock.daily-report', compact('reports', 'date'));


}

}
