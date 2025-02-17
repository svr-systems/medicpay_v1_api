<?php

namespace App\Http\Controllers;

class DoctorSpecialtyController extends Controller {
  public static function saveItem($item, $data) {
    $item->doctor_id = GenController::filter($data->doctor_id, 'id');
    $item->specialty_id = GenController::filter($data->specialty_id, 'id');
    $item->license = GenController::filter($data->license, 'U');
    $item->save();

    return $item;
  }
}
