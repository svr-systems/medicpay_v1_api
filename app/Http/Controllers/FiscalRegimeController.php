<?php

namespace App\Http\Controllers;

use App\Models\FiscalRegime;
use Illuminate\Http\Request;
use Throwable;

class FiscalRegimeController extends Controller {
  public function index(Request $req) {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        [
          'items' => FiscalRegime::getItems($req)
        ]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}
