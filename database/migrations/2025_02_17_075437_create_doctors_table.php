<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration {
  public function up() {
    Schema::create('doctors', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->foreignId('user_id')->constrained('users');
      $table->foreignId('hospital_id')->constrained('hospitals');
    });
  }

  public function down() {
    Schema::dropIfExists('doctors');
  }
}
