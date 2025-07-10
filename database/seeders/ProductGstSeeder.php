<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;


class ProductGstSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
    
        $products = [
            'AC' => 18,
            'Security System' => 5,
            'CCTV' => 18,
            'Bank locker' => 24,
            'Sensor' => 12,
            'Tablet' => 12,
            'Laptop'  =>  18
        ];

        foreach ($products as $name => $gst) {
            Product::where('name', $name)->update([
                'gst_percentage' => $gst,
                'gst_status' => 'Included' 
            ]);
        }
    }

    }

