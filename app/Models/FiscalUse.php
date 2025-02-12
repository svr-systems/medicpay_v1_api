<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiscalUse extends Model {
  use HasFactory;
  public $timestamps = false;

  static public function getItems($req) {
    $items = FiscalUse::
      join('fiscal_use_regimes', 'fiscal_use_regimes.fiscal_use_id', 'fiscal_uses.id')->
      where('fiscal_uses.active', true)->
      where('fiscal_use_regimes.fiscal_regime_id', $req->fiscal_regime_id)->
      get([
        'fiscal_uses.id',
        'fiscal_uses.name',
        'fiscal_uses.code',
      ]);

    return $items;
  }
}
