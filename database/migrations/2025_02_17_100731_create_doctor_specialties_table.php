<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorSpecialtiesTable extends Migration {
  public function up() {
    Schema::create('doctor_specialties', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->foreignId('doctor_id')->constrained('doctors');
      $table->foreignId('specialty_id')->constrained('specialties');
      $table->string('license', 20);
    });
  }

  public function down() {
    Schema::dropIfExists('doctor_specialties');
  }
}
