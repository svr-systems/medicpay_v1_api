<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Throwable;

class RoleController extends Controller {
  public $msg_err;

  public function __construct() {
    $this->msg_err = "Error. Contacte al equipo de desarrollo";
  }
  public function index() {
    try {
      return response()->json([
        "ok" => true,
        "msg" => "Registros retornados correctamente",
        "data" => Role::getItems()
      ], 200);
    } catch (Throwable $err) {
      return response()->json([
        "ok" => false,
        "msg" => $this->msg_err,
        "err" => $err
      ], 500);
    }
  }
}
