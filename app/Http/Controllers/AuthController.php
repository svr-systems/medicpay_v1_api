<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthController extends Controller {
  public function login(Request $req) {
    try {
      if (
        !Auth::attempt([
          'email' => $req->email,
          'password' => $req->password
        ])
      ) {
        return $this->apiRsp(422, 'Datos de acceso inválidos', null);
      }

      $token = Auth::user()->createToken('authToken')->accessToken;
      $user = User::find(Auth::id(), [
        'id',
        'name',
        'email',
        'role_id',
      ]);

      $user->role = Role::find($user->role_id, ['name']);

      return $this->apiRsp(
        200,
        'Datos de acceso validos',
        [
          'token' => $token,
          'user' => $user
        ]
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }

  public function logout(Request $req) {
    try {
      $req->user()->token()->revoke();

      return $this->apiRsp(
        200,
        'Sesión finalizada correctamente'
      );
    } catch (Throwable $err) {
      return $this->apiRsp(500, null, $err);
    }
  }
}