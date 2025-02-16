<?php

namespace App\Http\Controllers;

use App\Models\FiscalRegime;
use Facturapi\Facturapi;
use stdClass;
use Throwable;

class FiscalController extends Controller {
  public static function errMsg($err) {
    $err_str = $err->getMessage();
    $err_str = (array) json_decode(substr($err_str, strpos($err_str, '{'), strpos($err_str, '}')), true);

    switch ($err_str['message']) {
      case 'Este RFC del receptor no existe en la lista de RFC inscritos no cancelados del SAT':
        $err_str = "FISCAL: RFC incorrecto";
        break;
      case 'La clave del campo RegimenFiscalReceptor debe corresponder con el tipo de persona (física o moral).':
        $err_str = "FISCAL: Régimen incorrecto";
        break;
      case "El nombre o razón social del receptor no coincide con el RFC registrado en el SAT; recuerda que con CFDI 4.0, debe ingresarse en mayúsculas y sin acentos, además ya no debes incluir el régimen societario (ej. \"S.A. de C.V.\")":
        $err_str = "FISCAL: Nombre | Razón social incorrecto, ingresar sin acentos y no incluir el régimen societario (ej. \"S.A. de C.V.\")";
        break;
      case 'El campo DomicilioFiscalReceptor del receptor, debe pertenecer al nombre asociado al RFC registrado en el campo Rfc del Receptor.':
        $err_str = "FISCAL: CP incorrecto";
        break;
      default:
        $err_str = $err_str['message'];
    }
    return $err_str;
  }

  public static function customer($item) {
    if (!isset($item->fiscal_regime)) {
      $item->fiscal_regime = FiscalRegime::find($item->fiscal_regime_id, ['code']);
    }

    return [
      'tax_id' => $item->fiscal_code,
      'legal_name' => $item->fiscal_name,
      'address' => ['zip' => $item->fiscal_zip],
      'tax_system' => $item->fiscal_regime->code
    ];
  }

  public static function customerValid($item) {
    $fapi = new Facturapi(env('FACTURAPI_KEY'));
    $rsp = new stdClass;
    $rsp->msg = null;
    $rsp->err = null;

    $customer = FiscalController::customer($item);

    try {
      $customer = $fapi->Customers->create($customer);
      $fapi->Customers->delete($customer->id);

      return $rsp;
    } catch (Throwable $err) {
      $rsp->msg = FiscalController::errMsg($err);
      $rsp->err = $err;

      return $rsp;
    }
  }

  public static function getItemGenPublic() {
    $item = new stdClass;
    $item->fiscal_code = 'XAXX010101000';
    $item->fiscal_name = 'PÚBLICO EN GENERAL';
    $item->fiscal_zip = '38015'; //Change for Configuration in DB
    $item->fiscal_regime = new stdClass;
    $item->fiscal_regime->code = '616';

    return $item;
  }
}
