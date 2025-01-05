<?php

namespace App\Http\Controllers;

use App\Models\Example;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ExampleController extends Controller {
  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => Example::getItems($req)]
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
        ['item' => Example::getItem($id)]
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
      $item = Example::find($id);
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
      $is_valid = $this->isValid($req, $id);

      if ($is_valid->fails()) {
        return $this->apiRsp(422, $is_valid->errors()->first());
      }

      $store_mode = is_null($id);

      if ($store_mode) {
        $item = new Example;
        $item->created_by_id = $req->user()->id;
      } else {
        $item = Example::find($id);
      }

      $item = $this->saveItem($item, $req);

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

  public function isValid($req, $id) {
    $rules = [
      'name' => 'string|required|min:2|max:20',
      'example_type_id' => 'numeric|required',
    ];

    return Validator::make(
      $req->all(),
      $rules
    );
  }

  public function saveItem($item, $req) {
    $item->updated_by_id = $req->user()->id;
    $item->name = GenController::filter($req->name, 'U');
    $item->example_type_id = GenController::filter($req->example_type_id, 'id');
    $item->save();

    return $item;
  }
}
