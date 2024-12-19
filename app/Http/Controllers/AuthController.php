<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthController extends Controller {
  public $msg_err;

  public function __construct() {
    $this->msg_err = "Error. Contacte al equipo de desarrollo";
  }

  public function logIn(Request $req) {
    try {
      if (
        !Auth::attempt([
          "email" => $req->email,
          "password" => $req->password
        ])
      ) {
        return response()->json([
          "ok" => false,
          "msg" => "Datos de acceso inválidos",
          "err" => null
        ], 500);
      }

      $token = Auth::user()->createToken("authToken")->accessToken;
      $user = User::find(Auth::id(), [
        "id",
        "name",
        "email",
      ]);

      return response()->json([
        "ok" => true,
        "msg" => "Datos de acceso validos",
        "data" => [
          "auth" => true,
          "token" => $token,
          "user" => $user
        ]
      ], 200);
    } catch (Throwable $err) {
      return response()->json([
        "ok" => false,
        "msg" => $this->msg_err,
        "err" => $err
      ], 500);
    }
  }

  public function logOut(Request $req) {
    try {
      $req->user()->token()->revoke();

      return response()->json([
        "ok" => true,
        "msg" => "Sesión finalizada correctamente"
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