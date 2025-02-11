<?php

namespace App\Http\Controllers;

use App\Models\State;
use Throwable;

class StateController extends Controller {
  public function index() {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        ['items' => State::getItems()]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}
