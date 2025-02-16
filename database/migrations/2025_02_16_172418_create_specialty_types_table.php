<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialtyTypesTable extends Migration {
  public function up() {
    Schema::create('specialty_types', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->timestamps();
      $table->foreignId('created_by_id')->constrained('users');
      $table->foreignId('updated_by_id')->constrained('users');
      $table->string('name', 80);
    });
  }

  public function down() {
    Schema::dropIfExists('specialty_types');
  }
}
