<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function apiRsp($code, $msg, $data = null) {
    $ok = $code == 200 || $code == 201;
    $msg_err_def = 'Error. Contacte al equipo de desarrollo';

    return response()->json([
      'msg' => is_null($msg) && !$ok ? $msg_err_def : $msg,
      'data' => !$ok && !is_null($data)
        ? [
          'err' => $data,
          'trace' => $data->getTraceAsString()
        ]
        : $data,
    ], $code);
  }
}
