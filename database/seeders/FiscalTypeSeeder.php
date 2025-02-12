<?php

namespace Database\Seeders;

use App\Models\FiscalType;
use Illuminate\Database\Seeder;

class FiscalTypeSeeder extends Seeder {
  public function run() {
    $items = [
      [
        'name' => 'PERSONA MORAL',
      ],
      [
        'name' => 'PERSONA FISICA',
      ],
    ];

    FiscalType::insert($items);
  }
}
