<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\StockReport;
use Carbon\Carbon;


class GenerateDailyStockReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-daily-stock-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily stock report for each product';

    /**
     * Execute the console command.
     */
   public function handle()
    {
        $reportDate = Carbon::yesterday()->toDateString(); // For yesterday

        $products = Product::all();

        foreach ($products as $product) {
            // Calculate sold quantity yesterday
            $stockOut = $product->saleItems()
                ->whereDate('created_at', $reportDate)
                ->sum('quantity');

            // Get previous day's closing stock as opening stock (if exists)
            $previousReport = StockReport::where('product_id', $product->id)
                ->whereDate('report_date', Carbon::yesterday()->subDay())
                ->first();

            $openingStock = $previousReport ? $previousReport->closing_stock : ($product->stock + $stockOut);

            $closingStock = $product->stock;

            StockReport::updateOrCreate(
                ['product_id' => $product->id, 'report_date' => $reportDate],
                [
                    'opening_stock' => $openingStock,
                    'stock_out' => $stockOut,
                    'closing_stock' => $closingStock,
                ]
            );
        }

        $this->info("Stock report generated for $reportDate.");
    }

}
