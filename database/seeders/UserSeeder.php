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
        'name' => 'ADMINISTRADOR',
        'surname_p' => 'SISTEMA',
        'email' => 'admin@medicpay.com',
        'password' => bcrypt('MedicPay_1029*'),
        'role_id' => 1,
      ]
    ];

    User::insert($items);
  }
}
