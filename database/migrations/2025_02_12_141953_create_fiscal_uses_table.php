<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiscalUsesTable extends Migration {
  public function up() {
    Schema::create('fiscal_uses', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->string('name', 90);
      $table->string('code', 4)->nullable();
    });
  }

  public function down() {
    Schema::dropIfExists('fiscal_uses');
  }
}
