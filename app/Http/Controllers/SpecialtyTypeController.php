<?php

namespace App\Http\Controllers;

use App\Models\SpecialtyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Throwable;

class SpecialtyTypeController extends Controller {
  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => SpecialtyType::getItems($req)]
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
        ['item' => SpecialtyType::getItem($req, $id)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function destroy(Request $req, $id) {
    DB::beginTransaction();
    try {
      $item = SpecialtyType::find($id);

      if (!$item) {
        return $this->apiRsp(422, 'ID no existente');
      }

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

  public function restore(Request $req) {
    DB::beginTransaction();
    try {
      $item = SpecialtyType::find($req->id);

      if (!$item) {
        return $this->apiRsp(422, 'ID no existente');
      }

      $item->active = true;
      $item->updated_by_id = $req->user()->id;
      $item->save();

      DB::commit();
      return $this->apiRsp(
        200,
        'Registro activado correctamente',
        ['item' => SpecialtyType::getItem(null, $item->id)]
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
      $valid = SpecialtyType::valid($req);
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      $store_mode = GenController::empty($id);

      if ($store_mode) {
        $item = new SpecialtyType;
        $item->created_by_id = $req->user()->id;
        $item->updated_by_id = $req->user()->id;
      } else {
        $item = SpecialtyType::find($id);
        $item->updated_by_id = $req->user()->id;
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

  public static function saveItem($item, $data) {
    $item->name = GenController::filter($data->name, 'U');
    $item->save();

    return $item;
  }
}
