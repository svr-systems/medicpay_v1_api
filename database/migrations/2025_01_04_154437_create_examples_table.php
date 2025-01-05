<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamplesTable extends Migration {
  public function up() {
    Schema::create('examples', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->timestamps();
      $table->foreignId('created_by_id')->constrained('users');
      $table->foreignId('updated_by_id')->constrained('users');
      $table->string('name', 20);
      $table->foreignId('example_type_id')->constrained('example_types');
    });
  }

  public function down() {
    Schema::dropIfExists('examples');
  }
}
