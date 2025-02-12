<?php

namespace App\Http\Controllers;

use App\Models\FiscalUse;
use Illuminate\Http\Request;
use Throwable;

class FiscalUseController extends Controller {
  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        [
          'items' => FiscalUse::getItems($req)
        ]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}
