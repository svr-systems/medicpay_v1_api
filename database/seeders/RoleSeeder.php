<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder {
  public function run() {
    $items = [
      [
        'name' => 'ADM-SIS',
      ],
      [
        'name' => 'USR-SIS',
      ],
    ];

    Role::insert($items);
  }
}
