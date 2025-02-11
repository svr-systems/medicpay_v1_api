<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Town extends Model {
  use HasFactory;
  public $timestamps = false;

  static public function getItems($req) {
    $items = Town::
      where('active', true)->
      where('state_id', $req->state_id)->
      get([
        'id',
        'name',
      ]);

    return $items;
  }
}
