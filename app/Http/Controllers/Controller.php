<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function apiRsp($status, $msg, $data = null) {
    $ok = $status == 200;
    $msg_err_def = 'Error. Contacte al equipo de desarrollo';

    return response()->json([
      'ok' => $ok,
      'msg' => is_null($msg) && !$ok ? $msg_err_def : $msg,
      'data' => !$ok && !is_null($data) ? $data->getTraceAsString() : $data,
    ], $status);
  }
}
