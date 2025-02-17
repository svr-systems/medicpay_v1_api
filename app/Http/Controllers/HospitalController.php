<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Throwable;

class HospitalController extends Controller {
  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => Hospital::getItems($req)]
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
        ['item' => Hospital::getItem($req, $id)]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function destroy(Request $req, $id) {
    DB::beginTransaction();
    try {
      $item = Hospital::find($id);

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
      $item = Hospital::find($req->id);

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
        ['item' => Hospital::getItem(null, $item->id)]
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
      $valid = Hospital::valid($req);
      if ($valid->fails()) {
        return $this->apiRsp(422, $valid->errors()->first());
      }

      if (!GenController::empty($req->fiscal_code)) {
        $valid = Hospital::validFiscal($req);
        if ($valid->fails()) {
          return $this->apiRsp(422, $valid->errors()->first());
        } else {
          $valid = new stdClass;
          $valid->fiscal_code = $req->fiscal_code;
          $valid->fiscal_name = $req->fiscal_name;
          $valid->fiscal_zip = $req->fiscal_zip;
          $valid->fiscal_regime_id = $req->fiscal_regime_id;

          $rsp = FiscalController::customerValid($valid);
          if (!is_null($rsp->err)) {
            return $this->apiRsp(422, $rsp->msg, $rsp->err);
          }
        }
      }

      $store_mode = GenController::empty($id);

      if ($store_mode) {
        $item = new Hospital;
        $item->created_by_id = $req->user()->id;
        $item->updated_by_id = $req->user()->id;
      } else {
        $item = Hospital::find($id);
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
    $item->logo = DocMgrController::save(
      $data->logo,
      DocMgrController::exist($data->logo_doc),
      $data->logo_dlt,
      'Hospital'
    );
    $item->fee = GenController::filter($data->fee, 'i');
    $item->contact = GenController::filter($data->contact, 'U');
    $item->contact_phone = GenController::filter($data->contact_phone, 'U');
    $item->fiscal_constancy = DocMgrController::save(
      $data->fiscal_constancy,
      DocMgrController::exist($data->fiscal_constancy_doc),
      $data->fiscal_constancy_dlt,
      'Hospital'
    );
    $item->fiscal_code = GenController::filter($data->fiscal_code, 'U');
    $item->fiscal_name = GenController::filter($data->fiscal_name, 'U');
    $item->fiscal_zip = GenController::filter($data->fiscal_zip, 'U');
    $item->fiscal_type_id = GenController::filter($data->fiscal_type_id, 'id');
    $item->fiscal_regime_id = GenController::filter($data->fiscal_regime_id, 'id');
    $item->address_proof = DocMgrController::save(
      $data->address_proof,
      DocMgrController::exist($data->address_proof_doc),
      $data->address_proof_dlt,
      'Hospital'
    );
    $item->address_zip = GenController::filter($data->address_zip, 'U');
    $item->address_town_id = GenController::filter($data->address_town_id, 'id');
    $item->address_street = GenController::filter($data->address_street, 'U');
    $item->address_exterior = GenController::filter($data->address_exterior, 'U');
    $item->address_interior = GenController::filter($data->address_interior, 'U');
    $item->address_neighborhood = GenController::filter($data->address_neighborhood, 'U');
    $item->save();

    return $item;
  }

  public function getItemsPublic() {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => Hospital::getItemsPublic()]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}
