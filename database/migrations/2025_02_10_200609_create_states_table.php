<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration {
  public function up() {
    Schema::create('states', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->string('name', 31);
      $table->string('abbrev', 5);
    });
  }

  public function down() {
    Schema::dropIfExists('states');
  }
}
