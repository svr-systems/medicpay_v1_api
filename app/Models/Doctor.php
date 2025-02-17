<?php

namespace App\Models;

use App\Http\Controllers\GenController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Doctor extends Model {
  use HasFactory;
  public $timestamps = false;

  public static function valid($data) {
    $rules = [
      'hospital_id' => 'required|numeric',
      'consultation_amount' => 'required|numeric',
    ];

    return Validator::make(
      $data->all(),
      $rules,
      []
    );
  }

  static public function getUiid($id) {
    return 'M' . str_pad($id, 4, '0', STR_PAD_LEFT);
  }

  static public function getItems($req) {
    $items = Doctor::
      join('users', 'users.id', 'doctors.user_id');

    switch ((int) $req->active) {
      case 0:
        $items->where('doctors.active', false);
        break;
      case 1:
        $items->where('doctors.active', true);
        break;
    }

    // switch ((int) $req->filter) {
    //   case 1:
    //     //
    //     break;
    // }

    $items = $items->
      orderBy('users.name')->
      orderBy('users.surname_p')->
      orderBy('users.surname_m')->
      get([
        'doctors.id',
        'doctors.active',
        'doctors.user_id',
        'doctors.hospital_id',
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->uiid = Doctor::getUiid($item->id);

      $item->user = User::find($item->user_id, [
        'active',
        'name',
        'surname_p',
        'surname_m',
        'email',
      ]);
      $item->user->full_name = GenController::getFullName($item->user->name, $item->user->surname_p, $item->user->surname_m);

      $item->hospital = Hospital::find($item->hospital_id, [
        'name',
        'address_town_id'
      ]);
      $item->hospital->address_town = Town::find($item->hospital->address_town_id, ['name', 'state_id']);
      $item->hospital->address_town->state = State::find($item->hospital->address_town->state_id, ['name']);

      $item->doctor_specialties_txt = '';
      $doctor_specialties = DoctorSpecialty::
        where('active', true)->
        where('doctor_id', $item->id)->
        get();

      foreach ($doctor_specialties as $doctor_specialty) {
        $specialty = Specialty::find($doctor_specialty->specialty_id, ['name']);
        $item->doctor_specialties_txt .= '.' . $specialty->name . "\n";
      }
    }

    return $items;
  }

  static public function getItem($req, $id) {
    $item = Doctor::find($id, [
      'id',
      'active',
      'user_id',
      'hospital_id',
      'consultation_amount',
    ]);

    $item->uiid = Doctor::getUiid($item->id);
    $item->user = User::getItem(null, $item->user_id);
    $item->created_at = $item->user->created_at;
    $item->updated_at = $item->user->updated_at;
    $item->created_by = $item->user->created_by;
    $item->updated_by = $item->user->updated_by;
    $item->hospital = Hospital::getItem(null, $item->hospital_id);
    $item->doctor_specialties = DoctorSpecialty::getItems($item->id);

    return $item;
  }
}
