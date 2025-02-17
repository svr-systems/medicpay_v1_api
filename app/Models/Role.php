<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {
  use HasFactory;
  public $timestamps = false;

  static public function getItems($req) {
    $items = Role::
      where('id', '!=', 3)->
      where('active', true);

    if ($req->user()->role_id != 1) {
      $items->where('id', '!=', 1);
    }

    $items = $items->get([
      'id',
      'name',
    ]);

    return $items;
  }
}
