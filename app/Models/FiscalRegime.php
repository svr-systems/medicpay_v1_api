<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiscalRegime extends Model {
  use HasFactory;
  public $timestamps = false;

  static public function getItems($req) {
    $items = FiscalRegime::
      where('active', true)->
      where('fiscal_type_id', $req->fiscal_type_id)->
      orWhereNull('fiscal_type_id')->
      get([
        'id',
        'name',
        'code',
      ]);

    return $items;
  }
}
