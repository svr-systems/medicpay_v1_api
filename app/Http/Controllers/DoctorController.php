<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorSpecialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class DoctorController extends Controller {
  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => Doctor::getItems($req)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function show(Request $req, $id) {
    try {
      return $this->apiRsp(
        200,
        'Registro retornado correctamente',
        ['item' => Doctor::getItem($req, $id)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function destroy(Request $req, $id) {
    DB::beginTransaction();
    try {
      $item = Doctor::find($id);

      if (!$item) {
        return $this->apiRsp(422, 'ID no existente');
      }

      $item->active = false;
      $item->save();

      $user = User::find($item->user_id);
      $user->active = false;
      $user->updated_by_id = $req->user()->id;
      $user->save();

      DB::commit();
      return $this->apiRsp(
        200,
        'Registro eliminado correctamente'
      );
    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }

  }

  public function restore(Request $req) {
    DB::beginTransaction();
    try {
      $item = Doctor::find($req->id);

      if (!$item) {
        return $this->apiRsp(422, 'ID no existente');
      }

      $item->active = true;
      $item->save();

      $user = User::find($item->user_id);
      $user->active = true;
      $user->updated_by_id = $req->user()->id;
      $user->save();

      DB::commit();
      return $this->apiRsp(
        200,
        'Registro activado correctamente',
        ['item' => Doctor::getItem(null, $item->id)]
      );
    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }
  }

  public function store(Request $req) {
    return $this->storeUpdate($req, null);
  }

  public function update(Request $req, $id) {
    return $this->storeUpdate($req, $id);
  }

  public function storeUpdate($req, $id) {
    DB::beginTransaction();
    try {
      //User
      $user_data = json_decode($req->user);
      $user_data->role_id = 3;
      $user_data->avatar_doc = $req['user_avatar_doc'];

      $valid = User::validEmail(['email' => GenController::filter($user_data->email, 'l')], $req->user_id);
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $valid = User::valid($user_data, false);
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $store = GenController::empty($req->user_id);

      if ($store) {
        $user = new User;
        $user->created_by_id = $req->user()->id;
        $user->updated_by_id = $req->user()->id;
        $user->password = null;
      } else {
        $user = User::find($req->user_id);
        $user->updated_by_id = $req->user()->id;
      }

      $user = UserController::saveItem($user, $user_data, false);

      //Item
      $valid = Doctor::valid($req);
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $store_mode = GenController::empty($id);

      if ($store_mode) {
        $item = new Doctor;
        $item->user_id = $user->id;
      } else {
        $item = Doctor::find($id);
      }

      $item = $this->saveItem($item, $req);

      //specialties
      $doctor_specialties = json_decode($req->doctor_specialties);
      foreach ($doctor_specialties as $data) {
        $data->doctor_id = $item->id;

        $valid = DoctorSpecialty::valid($data);
        if ($valid->fails()) {
          return $this->apiRsp(422, $valid->errors()->first());
        }

        $store = GenController::empty($data->id);

        $doctor_specialty = $store ? new DoctorSpecialty : DoctorSpecialty::find($data->id);
        $doctor_specialty = DoctorSpecialtyController::saveItem($doctor_specialty, $data);
      }

      DB::commit();
      return $this->apiRsp(
        $store_mode ? 201 : 200,
        'Registro ' . ($store_mode ? 'agregado' : 'editado') . ' correctamente',
        $store_mode ? ['item' => ['id' => $item->id]] : null
      );
    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }
  }

  public static function saveItem($item, $data) {
    $item->hospital_id = GenController::filter($data->hospital_id, 'id');
    $item->save();

    return $item;
  }

  public function storeByDoctor(Request $req) {
    DB::beginTransaction();
    try {
      //User
      $user_data = json_decode(json_encode($req->user));
      $user_data->active = true;
      $user_data->role_id = 3;

      $valid = User::validEmail(['email' => GenController::filter($user_data->email, 'l')], null);
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $valid = User::valid($user_data, false);
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $user = new User;
      $user->created_by_id = 1;
      $user->updated_by_id = 1;
      $user->password = bcrypt(GenController::trim($user_data->password));
      $user = UserController::saveItem($user, $user_data, false);

      //Item
      $valid = Doctor::valid($req);
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $item = new Doctor;
      $item->user_id = $user->id;
      $item = $this->saveItem($item, $req);

      //specialties
      $doctor_specialties = json_decode(json_encode($req->doctor_specialties));
      foreach ($doctor_specialties as $data) {
        $data->doctor_id = $item->id;

        $valid = DoctorSpecialty::valid($data);
        if ($valid->fails()) {
          return $this->apiRsp(422, $valid->errors()->first());
        }

        $doctor_specialty = new DoctorSpecialty;
        $doctor_specialty = DoctorSpecialtyController::saveItem($doctor_specialty, $data);
      }

      DB::commit();
      return $this->apiRsp(200, 'Registro realizado correctamente');
    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }
  }
}
