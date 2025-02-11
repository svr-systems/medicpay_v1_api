<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
  public function run() {
    $this->call(RoleSeeder::class);
    $this->call(UserSeeder::class);
    $this->call(StateSeeder::class);
    $this->call(TownSeeder::class);
  }
}
