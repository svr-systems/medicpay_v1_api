<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiscalUseRegimesTable extends Migration {
  public function up() {
    Schema::create('fiscal_use_regimes', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->foreignId('fiscal_use_id')->constrained('fiscal_uses');
      $table->foreignId('fiscal_regime_id')->constrained('fiscal_regimes');
    });
  }

  public function down() {
    Schema::dropIfExists('fiscal_use_regimes');
  }
}
