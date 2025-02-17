<?php

namespace App\Models;

use App\Http\Controllers\DocMgrController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Facades\Validator;

class Hospital extends Model {
  use HasFactory;
  protected function serializeDate(DateTimeInterface $date) {
    return Carbon::instance($date)->toISOString(true);
  }
  protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
  ];

  public static function validFiscal($data) {
    $rules = [
      'fiscal_code' => 'required|regex:/^[A-Za-zñÑ&]{3,4}\d{6}\w{3}$/',
      'fiscal_name' => 'required|min:2|max:75',
      'fiscal_zip' => 'required|digits:5',
      'fiscal_type_id' => 'required|numeric',
      'fiscal_regime_id' => 'required|numeric',
    ];

    return Validator::make(
      $data->all(),
      $rules,
      [
        'fiscal_code.regex' => 'Formato de RFC invalido',
        'fiscal_zip.digits' => 'El C. P. debe de contener 5 digitos'
      ]
    );
  }

  public static function valid($data) {
    $rules = [
      'name' => 'required|string|min:2|max:100',
      'fee' => 'required|integer|between:1,30',
      'logo_doc' => 'exclude_if:logo_doc,null|image|mimes:jpg,jpeg,png|max:2048',
      'contact' => 'required|string|min:2|max:100',
      'contact_phone' => 'required|string|digits:10',
      'fiscal_constancy_doc' => 'exclude_if:fiscal_constancy_doc,null|mimes:pdf|max:1024',
      'address_zip' => 'required|digits:5',
      'address_town_id' => 'required|numeric',
      'address_street' => 'nullable|min:2|max:75',
      'address_exterior' => 'nullable|min:1|max:15',
      'address_interior' => 'nullable|min:1|max:15',
      'address_neighborhood' => 'nullable|min:2|max:75',
      'address_proof_doc' => 'exclude_if:address_proof_doc,null|mimes:jpg,jpeg,png,pdf|max:1024',
    ];

    return Validator::make(
      $data->all(),
      $rules,
      []
    );
  }

  static public function getUiid($id) {
    return 'H' . str_pad($id, 3, '0', STR_PAD_LEFT);
  }

  static public function getItems($req) {
    $items = Hospital::
      where('id', '!=', 0);

    switch ((int) $req->active) {
      case 0:
        $items->where('active', false);
        break;
      case 1:
        $items->where('active', true);
        break;
    }

    // switch ((int) $req->filter) {
    //   case 1:
    //     //
    //     break;
    // }

    $items = $items->
      orderBy('name')->
      get([
        'id',
        'active',
        'name',
        'fee',
        'fiscal_code',
        'address_town_id'
      ]);

    foreach ($items as $key => $item) {
      $item->key = $key;
      $item->uiid = Hospital::getUiid($item->id);
      $item->address_town = Town::find($item->address_town_id, ['name', 'state_id']);
      $item->address_town->state = State::find($item->address_town->state_id, ['name']);
    }

    return $items;
  }

  static public function getItem($req, $id) {
    $item = Hospital::find($id);

    $item->uiid = Hospital::getUiid($item->id);
    $item->created_by = User::find($item->created_by_id, ['email']);
    $item->updated_by = User::find($item->updated_by_id, ['email']);
    $item->logo_b64 = DocMgrController::getB64($item->logo, 'Hospital');
    $item->logo_doc = null;
    $item->logo_dlt = false;
    $item->fiscal_constancy_b64 = DocMgrController::getB64($item->fiscal_constancy, 'Hospital');
    $item->fiscal_constancy_doc = null;
    $item->fiscal_constancy_dlt = false;
    $item->fiscal_type = FiscalType::find($item->fiscal_type_id, ['name']);
    $item->fiscal_regime = FiscalRegime::find($item->fiscal_regime_id, ['name', 'code']);
    $item->address_proof_b64 = DocMgrController::getB64($item->address_proof, 'Hospital');
    $item->address_proof_doc = null;
    $item->address_proof_dlt = false;
    $item->address_town = Town::find($item->address_town_id, ['name', 'state_id']);
    $item->address_town->state = State::find($item->address_town->state_id, ['name']);

    return $item;
  }

  static public function getItemsPublic() {
    $items = Hospital::
      where('active', true)->
      orderBy('name')->
      get([
        'id',
        'name',
      ]);

    return $items;
  }
}
