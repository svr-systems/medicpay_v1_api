<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
  public function up() {
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->timestamps();
      $table->foreignId('created_by_id')->constrained('users');
      $table->foreignId('updated_by_id')->constrained('users');
      $table->string('name', 50);
      $table->string('surname_p', 25);
      $table->string('surname_m', 25)->nullable();
      $table->string('avatar', 50)->nullable();
      $table->string('email', 65)->unique();
      $table->string('password');
      $table->timestamp('email_verified_at')->nullable();
      $table->rememberToken();
    });
  }

  public function down() {
    Schema::dropIfExists('users');
  }
}
