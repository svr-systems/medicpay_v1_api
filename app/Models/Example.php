<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTimeInterface;

class Example extends Model {
  use HasFactory;
  protected function serializeDate(DateTimeInterface $date) {
    return Carbon::instance($date)->toISOString(true);
  }
  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];

  static public function getItems($req) {
    $items = Example::
      where('active', true)->
      orderBy('name')->
      get([
        'id',
        'name',
        'example_type_id'
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->example_type = ExampleType::find($item->example_type_id, ['name']);
    }

    return $items;
  }

  static public function getItem($id) {
    $item = Example::
      find($id, [
        'id',
        'created_at',
        'updated_at',
        'created_by_id',
        'updated_by_id',
        'name',
        'example_type_id',
      ]);

    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);
    $item->example_type = ExampleType::find($item->example_type_id, ['name']);

    return $item;
  }
}

