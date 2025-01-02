<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class UserController extends Controller {
  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => User::getItems($req)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function store(Request $req) {
    return $this->storeUpdate($req, null);
  }

  public function show($id) {
    try {
      return $this->apiRsp(
        200,
        'Registro retornado correctamente',
        ['item' => User::getItem($id)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function update(Request $req, $id) {
    return $this->storeUpdate($req, $id);
  }

  public function destroy(Request $req, $id) {
    DB::beginTransaction();
    try {
      $item = User::find($id);
      $item->active = false;
      $item->updated_by_id = $req->user()->id;
      $item->save();

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

  public function storeUpdate($req, $id) {
    DB::beginTransaction();
    try {
      $req->merge(
        [
          'email' => GenController::filter($req->email, 'l'),
        ],
      );

      $is_valid = $this->isValid($req, $id);

      if ($is_valid->fails()) {
        return $this->apiRsp(422, $is_valid->errors()->first());
      }

      $store_mode = is_null($id);

      if ($store_mode) {
        $item = new User;
        $item->password = bcrypt(trim($req->password));
        $item->created_by_id = $req->user()->id;
      } else {
        $item = User::find($id);
      }

      $item = $this->saveItem($item, $req);

      DB::commit();
      return $this->apiRsp(
        $store_mode ? 201 : 200,
        'Registro ' . ($store_mode ? 'agregado' : 'editado') . ' correctamente',
        ['item' => User::getItemAuth($item->id)]
      );
    } catch (Throwable $err) {
      DB::rollback();
      return $this->apiRsp(500, null, $err);
    }
  }

  public function isValid($req, $id) {
    $rules = [
      'name' => 'string|required|min:2|max:50',
      'surname_p' => 'string|required|min:2|max:25',
      'surname_m' => 'nullable|string|min:2|max:25',
      'email' => 'string|required|min:2|max:65|unique:users' . ($id ? ',email,' . $id : ''),
      'role_id' => 'numeric|required',
      'avatar_doc' => 'exclude_if:avatar_doc,null|image|mimes:jpg,jpeg,png|max:3072',
      'password' => 'string' . ($id ? '' : '|required')
    ];

    return Validator::make(
      $req->all(),
      $rules,
      [
        'email.unique' => 'El E-mail. ya ha sido registrado',
      ]
    );
  }

  public function saveItem($item, $req) {
    $item->updated_by_id = $req->user()->id;
    $item->name = GenController::filter($req->name, 'U');
    $item->surname_p = GenController::filter($req->surname_p, 'U');
    $item->surname_m = GenController::filter($req->surname_m, 'U');
    $item->avatar = DocMgrController::save($req->avatar, $req->file('avatar_doc'), $req->avatar_dlt, 'User');
    $item->email = $req->email;
    $item->role_id = GenController::filter($req->role_id, 'id');
    $item->save();

    return $item;
  }

  public function setPassword(Request $req) {
    DB::beginTransaction();
    try {
      $item = User::find($req->id);
      $item->password = bcrypt(trim($req->password));
      $item->updated_by_id = $req->user()->id;
      $item->save();

      DB::commit();
      return $this->apiRsp(
        200,
        'Contraseña actualizada correctamente'
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}
