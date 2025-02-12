<?php

namespace App\Http\Controllers;

use App\Models\FiscalType;
use Throwable;

class FiscalTypeController extends Controller {
  public function index() {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        [
          'items' => FiscalType::getItems()
        ]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}
