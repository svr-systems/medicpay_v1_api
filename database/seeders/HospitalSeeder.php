<?php

namespace Database\Seeders;

use App\Models\Hospital;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HospitalSeeder extends Seeder {
  public function run() {
    $now = Carbon::now()->format('Y-m-d H:i:s');

    $items = [
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'TEST HOSPITAL 1',
        'fee' => 10,
        'contact' => 'TEST PERSONA CONTACTO',
        'contact_phone' => '4612233445',
        'address_zip' => '38033',
        'address_town_id' => 335,
      ],
    ];

    Hospital::insert($items);
  }
}
