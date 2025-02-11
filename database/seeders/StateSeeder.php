<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder {
  public function run() {
    $items = [
      [
        'name' => 'AGUASCALIENTES',
        'abbrev' => 'AGS'
      ],
      [
        'name' => 'BAJA CALIFORNIA',
        'abbrev' => 'BC'
      ],
      [
        'name' => 'BAJA CALIFORNIA SUR',
        'abbrev' => 'BCS'
      ],
      [
        'name' => 'CAMPECHE',
        'abbrev' => 'CAMP'
      ],
      [
        'name' => 'COAHUILA DE ZARAGOZA',
        'abbrev' => 'COAH'
      ],
      [
        'name' => 'COLIMA',
        'abbrev' => 'COL'
      ],
      [
        'name' => 'CHIAPAS',
        'abbrev' => 'CHIS'
      ],
      [
        'name' => 'CHIHUAHUA',
        'abbrev' => 'CHIH'
      ],
      [
        'name' => 'CIUDAD DE MEXICO',
        'abbrev' => 'CDMX'
      ],
      [
        'name' => 'DURANGO',
        'abbrev' => 'DGO'
      ],
      [
        'name' => 'GUANAJUATO',
        'abbrev' => 'GTO'
      ],
      [
        'name' => 'GUERRERO',
        'abbrev' => 'GRO'
      ],
      [
        'name' => 'HIDALGO',
        'abbrev' => 'HGO'
      ],
      [
        'name' => 'JALISCO',
        'abbrev' => 'JAL'
      ],
      [
        'name' => 'MEXICO',
        'abbrev' => 'MEX'
      ],
      [
        'name' => 'MICHOACAN DE OCAMPO',
        'abbrev' => 'MICH'
      ],
      [
        'name' => 'MORELOS',
        'abbrev' => 'MOR'
      ],
      [
        'name' => 'NAYARIT',
        'abbrev' => 'NAY'
      ],
      [
        'name' => 'NUEVO LEON',
        'abbrev' => 'NL'
      ],
      [
        'name' => 'OAXACA',
        'abbrev' => 'OAX'
      ],
      [
        'name' => 'PUEBLA',
        'abbrev' => 'PUE'
      ],
      [
        'name' => 'QUERETARO',
        'abbrev' => 'QRO'
      ],
      [
        'name' => 'QUINTANA ROO',
        'abbrev' => 'QROO'
      ],
      [
        'name' => 'SAN LUIS POTOSI',
        'abbrev' => 'SLP'
      ],
      [
        'name' => 'SINALOA',
        'abbrev' => 'SIN'
      ],
      [
        'name' => 'SONORA',
        'abbrev' => 'SON'
      ],
      [
        'name' => 'TABASCO',
        'abbrev' => 'TAB'
      ],
      [
        'name' => 'TAMAULIPAS',
        'abbrev' => 'TAMPS'
      ],
      [
        'name' => 'TLAXCALA',
        'abbrev' => 'TLAX'
      ],
      [
        'name' => 'VERACRUZ DE IGNACIO DE LA LLAVE',
        'abbrev' => 'VER'
      ],
      [
        'name' => 'YUCATAN',
        'abbrev' => 'YUC'
      ],
      [
        'name' => 'ZACATECAS',
        'abbrev' => 'ZAC'
      ],
      [
        'name' => 'EXTRANJERO',
        'abbrev' => 'EXT'
      ]
    ];

    State::insert($items);
  }
}
