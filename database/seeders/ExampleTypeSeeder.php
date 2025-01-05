<?php

namespace Database\Seeders;

use App\Models\ExampleType;
use Illuminate\Database\Seeder;

class ExampleTypeSeeder extends Seeder {
  public function run() {
    $items = [
      [
        'name' => 'EJEMPLO TIPO 1',
      ],
      [
        'name' => 'EJEMPLO TIPO 2',
      ],
    ];

    ExampleType::insert($items);
  }
}
