<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExampleType extends Model {
  use HasFactory;
  public $timestamps = false;

  static public function getItems() {
    $items = ExampleType::
      where('active', true)->
      get([
        'id',
        'name',
      ]);

    return $items;
  }
}
