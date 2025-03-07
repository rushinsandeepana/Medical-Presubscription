<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DrugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('drug_details')->insert([
            ['drug_name' => 'Paracetamol - 500mg', 'price' => 150, 'created_at' => Carbon::now()],
            ['drug_name' => 'Ibuprofen - 400mg', 'price' => 200, 'created_at' => Carbon::now()],
            ['drug_name' => 'Amoxicillin - 250mg', 'price' => 500, 'created_at' => Carbon::now()],
            ['drug_name' => 'Aspirin - 500mg', 'price' => 100, 'created_at' => Carbon::now()],
            ['drug_name' => 'Diclofenac - 100mg', 'price' => 350, 'created_at' => Carbon::now()],
            ['drug_name' => 'Loratadine - 10mg', 'price' => 250, 'created_at' => Carbon::now()],
            ['drug_name' => 'Metformin - 500mg', 'price' => 600, 'created_at' => Carbon::now()],
            ['drug_name' => 'Ciprofloxacin - 500mg', 'price' => 400, 'created_at' => Carbon::now()],
            ['drug_name' => 'Omeprazole - 20mg', 'price' => 450, 'created_at' => Carbon::now()],
            ['drug_name' => 'Amlodipine - 5mg', 'price' => 650, 'created_at' => Carbon::now()],
            ['drug_name' => 'Losartan - 50mg', 'price' => 800, 'created_at' => Carbon::now()],
            ['drug_name' => 'Salbutamol - 100mg', 'price' => 350, 'created_at' => Carbon::now()],
            ['drug_name' => 'Simvastatin - 20mg', 'price' => 1200, 'created_at' => Carbon::now()],
            ['drug_name' => 'Cetirizine - 10mg', 'price' => 220, 'created_at' => Carbon::now()],
            ['drug_name' => 'Clopidogrel - 75mg', 'price' => 700, 'created_at' => Carbon::now()],
        ]);
    }
}