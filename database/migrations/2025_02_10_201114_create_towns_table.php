<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTownsTable extends Migration {
  public function up() {
    Schema::create('towns', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->string('name', 100);
      $table->foreignId('state_id')->constrained('states');
    });
  }

  public function down() {
    Schema::dropIfExists('towns');
  }
}
