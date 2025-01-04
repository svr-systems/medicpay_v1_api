<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
  public function run() {
    $items = [
      [
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'name' => 'ADMIN',
        'surname_p' => 'SVR',
        'email' => 'admin@svr.mx',
        'password' => bcrypt('SVRsh_1029*'),
        'role_id' => 1,
      ]
    ];

    User::insert($items);
  }
}