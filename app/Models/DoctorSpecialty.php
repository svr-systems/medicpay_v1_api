<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class DoctorSpecialty extends Model {
  use HasFactory;
  public $timestamps = false;

  public static function valid($data) {
    $rules = [
      'doctor_id' => 'required|numeric',
      'specialty_id' => 'required|numeric',
      'license' => 'required|string|min:2|max:20',
    ];

    return Validator::make(
      (array) $data,
      $rules,
      []
    );
  }

  static public function getItems($doctor_id) {
    $items = DoctorSpecialty::
      where('active', true)->
      where('doctor_id', $doctor_id)->
      get();

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->specialty = Specialty::getItem(null, $item->specialty_id);
    }

    return $items;
  }
}
