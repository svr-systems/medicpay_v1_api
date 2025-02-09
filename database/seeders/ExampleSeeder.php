<?php

namespace Database\Seeders;

use App\Models\Example;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExampleSeeder extends Seeder {
  public function run() {
    $now = Carbon::now()->format('Y-m-d H:i:s');

    $items = [
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'EJEMPLO 1',
        'example_type_id' => 1,
      ]
    ];

    Example::insert($items);
  }
}
