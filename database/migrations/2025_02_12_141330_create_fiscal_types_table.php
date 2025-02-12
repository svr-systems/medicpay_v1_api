<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiscalTypesTable extends Migration {
  public function up() {
    Schema::create('fiscal_types', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->string('name', 14);
    });
  }

  public function down() {
    Schema::dropIfExists('fiscal_types');
  }
}
