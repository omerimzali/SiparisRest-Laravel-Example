<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JsonCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(base_path('customers.json'));
$customers = json_decode($json, true);

foreach ($customers as $customer) {
    \App\Models\Customer::updateOrCreate(
        ['id' => $customer['id']],
        [
            'name' => $customer['name'],
            'since' => $customer['since'],
            'revenue' => $customer['revenue'],
        ]
    );
}
    }
}
