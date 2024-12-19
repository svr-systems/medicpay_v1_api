<?php

namespace App\Models;

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
        'email',
        'role_i'
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key;
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
        'email',
        'role_id',
      ]);

    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);
    $item->role = Role::find($item->role_id, ['name']);

    return $item;
  }
}
