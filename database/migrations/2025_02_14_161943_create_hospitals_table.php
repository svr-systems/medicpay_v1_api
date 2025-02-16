<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalsTable extends Migration {
  public function up() {
    Schema::create('hospitals', function (Blueprint $table) {
      $table->id();
      $table->boolean('active')->default(true);
      $table->timestamps();
      $table->foreignId('created_by_id')->constrained('users');
      $table->foreignId('updated_by_id')->constrained('users');
      $table->string('name', 100);
      $table->tinyInteger('fee')->unsigned();
      $table->string('logo', 50)->nullable();
      $table->string('contact', 100);
      $table->string('contact_phone', 10);
      $table->string('fiscal_constancy', 50)->nullable();
      $table->string('fiscal_code', 13)->nullable();
      $table->string('fiscal_name', 75)->nullable();
      $table->string('fiscal_zip', 5)->nullable();
      $table->foreignId('fiscal_type_id')->nullable()->constrained('fiscal_types');
      $table->foreignId('fiscal_regime_id')->nullable()->constrained('fiscal_regimes');
      $table->string('address_zip', 5);
      $table->foreignId('address_town_id')->constrained('towns');
      $table->string('address_street', 75)->nullable();
      $table->string('address_exterior', 15)->nullable();
      $table->string('address_interior', 15)->nullable();
      $table->string('address_neighborhood', 75)->nullable();
      $table->string('address_proof', 50)->nullable();
    });
  }

  public function down() {
    Schema::dropIfExists('hospitals');
  }
}
