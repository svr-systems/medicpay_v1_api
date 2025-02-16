<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Facades\Validator;

class SpecialtyType extends Model {
  use HasFactory;
  protected function serializeDate(DateTimeInterface $date) {
    return Carbon::instance($date)->toISOString(true);
  }
  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];

  public static function valid($data) {
    $rules = [
      'name' => 'required|string|min:2|max:80',
    ];

    return Validator::make(
      $data->all(),
      $rules,
      []
    );
  }

  static public function getUiid($id) {
    return 'ES' . str_pad($id, 3, '0', STR_PAD_LEFT);
  }

  static public function getItems($req) {
    $items = SpecialtyType::
      where('id', '!=', 0);

    switch ((int) $req->active) {
      case 0:
        $items->where('active', false);
        break;
      case 1:
        $items->where('active', true);
        break;
    }

    // switch ((int) $req->filter) {
    //   case 1:
    //     //
    //     break;
    // }

    $items = $items->
      orderBy('name')->
      get([
        'id',
        'active',
        'name',
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->uiid = SpecialtyType::getUiid($item->id);
    }

    return $items;
  }

  static public function getItem($req, $id) {
    $item = SpecialtyType::find($id);

    $item->uiid = SpecialtyType::getUiid($item->id);
    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);

    return $item;
  }
}
