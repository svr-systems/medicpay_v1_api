<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
  public function run() {
    $now = Carbon::now()->format('Y-m-d H:i:s');

    $items = [
      [
        'created_at' => $now,
        'updated_at' => $now,
        'created_by_id' => 1,
        'updated_by_id' => 1,
        'email_verified_at' => $now,
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
