<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Throwable;

class RoleController extends Controller {
  public function index() {
    try {
      return $this->apiRsp(
        200,
        'Registros retornados correctamente',
        [
          'items' => Role::getItems()
        ]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}
