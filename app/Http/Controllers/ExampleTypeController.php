<?php

namespace App\Http\Controllers;

use App\Models\ExampleType;
use Throwable;

class ExampleTypeController extends Controller {
  public function index() {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        [
          'items' => ExampleType::getItems()
        ]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}
