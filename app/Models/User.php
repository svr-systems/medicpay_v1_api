<?php

namespace App\Models;

use App\Http\Controllers\DocMgrController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
    'email_verified_at' => 'datetime',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  static public function getItems($req) {
    $items = User::
      where('active', true)->
      where('id', '!=', $req->user()->id)->
      orderBy('email')->
      get([
        'id',
        'name',
        'surname_p',
        'surname_m',
        'avatar',
        'email',
        'role_id'
      ]);

    foreach ($items as $item) {
      $item->full_name = trim($item->name . ' ' . $item->surname_p . ' ' . $item->surname_m);
      $item->avatar_b64 = DocMgrController::getB64($item->avatar, "User");
      $item->role = Role::find($item->role_id, ['name']);
    }

    return $items;
  }

  static public function getItem($id) {
    $item = User::
      find($id, [
        'id',
        'created_at',
        'updated_at',
        'created_by_id',
        'updated_by_id',
        'name',
        'surname_p',
        'surname_m',
        'avatar',
        'email',
        'role_id',
      ]);

    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);
    $item->full_name = trim($item->name . ' ' . $item->surname_p . ' ' . $item->surname_m);
    $item->avatar_b64 = DocMgrController::getB64($item->avatar, "User");
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

    $item->full_name = trim($item->name . ' ' . $item->surname_p . ' ' . $item->surname_m);
    $item->avatar_b64 = DocMgrController::getB64($item->avatar, "User");
    $item->role = Role::find($item->role_id, ['name']);

    return $item;
  }
}
