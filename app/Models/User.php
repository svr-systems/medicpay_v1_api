<?php

namespace App\Models;

use App\Http\Controllers\DocMgrController;
use App\Http\Controllers\GenController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;
use DateTimeInterface;

class User extends Authenticatable {
  use HasApiTokens, HasFactory, Notifiable;
  protected function serializeDate(DateTimeInterface $date) {
    return Carbon::instance($date)->toISOString(true);
  }
  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
    'email_verified_at' => 'datetime:Y-m-d H:i:s',
  ];
  protected $hidden = ['password'];

  public static function validEmail($data, $id) {
    $rules = ['email' => 'nullable|string|min:2|max:65|regex:/.+@.+\..+/|unique:users,email,' . $id];

    return Validator::make(
      (array) $data,
      $rules,
      ['email.unique' => 'El E-mail ya ha sido registrado']
    );
  }

  public static function valid($data, $is_req = true) {
    $rules = [
      'name' => 'required|min:2|max:50',
      'surname_p' => 'required|min:2|max:25',
      'surname_m' => 'nullable|min:2|max:25',
      'avatar_doc' => 'exclude_if:avatar_doc,null|image|mimes:jpg,jpeg,png|max:2048',
      'role_id' => 'required|numeric',
      'phone' => 'nullable|string|digits:10',
      'movil_phone' => 'nullable|string|digits:10',
    ];

    if (!$is_req) {
      array_push($rules, ['active' => 'required|in:true,false,1,0']);
    }

    return Validator::make(
      $is_req ? $data->all() : (array) $data,
      $rules,
      [
        //
      ]
    );
  }

  static public function getUiid($id) {
    return 'U' . str_pad($id, 4, '0', STR_PAD_LEFT);
  }

  static public function getItems($req) {
    $items = User::
      where('id', '!=', $req->user()->id)->
      where('role_id', '!=', 3);

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
      orderBy('surname_p')->
      orderBy('surname_m')->
      get([
        'id',
        'active',
        'name',
        'surname_p',
        'surname_m',
        'email',
        'role_id'
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->uiid = User::getUiid($item->id);
      $item->full_name = GenController::getFullName($item->name, $item->surname_p, $item->surname_m);
      $item->role = Role::find($item->role_id, ['name']);
    }

    return $items;
  }

  static public function getItem($req, $id) {
    $item = User::
      find($id, [
        'id',
        'active',
        'created_at',
        'updated_at',
        'created_by_id',
        'updated_by_id',
        'email_verified_at',
        'name',
        'surname_p',
        'surname_m',
        'avatar',
        'email',
        'role_id',
        'phone',
        'movil_phone',
      ]);

    $item->uiid = User::getUiid($item->id);
    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);
    $item->full_name = GenController::getFullName($item->name, $item->surname_p, $item->surname_m);
    $item->avatar_b64 = DocMgrController::getB64($item->avatar, 'User');
    $item->avatar_doc = null;
    $item->avatar_dlt = false;
    $item->role = Role::find($item->role_id, ['name']);

    return $item;
  }

  static public function getItemAuth($id) {
    $item = User::find($id, [
      'id',
      'name',
      'surname_p',
      'surname_m',
      'avatar',
      'email',
      'role_id',
    ]);

    $item->uiid = User::getUiid($item->id);
    $item->full_name = GenController::getFullName($item->name, $item->surname_p, $item->surname_m);
    $item->avatar_b64 = DocMgrController::getB64($item->avatar, 'User');
    $item->role = Role::find($item->role_id, ['name']);

    return $item;
  }
}
