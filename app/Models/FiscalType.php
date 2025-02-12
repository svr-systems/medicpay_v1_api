<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiscalType extends Model {
  use HasFactory;
  public $timestamps = false;

  static public function getItems() {
    $items = FiscalType::
      where('active', true)->
      get([
        'id',
        'name',
      ]);

    return $items;
  }
}
