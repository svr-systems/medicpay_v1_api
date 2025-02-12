<?php

namespace Database\Seeders;

use App\Models\FiscalUse;
use Illuminate\Database\Seeder;

class FiscalUseSeeder extends Seeder {
  public function run() {
    $items = [
      [
        'active' => true,
        'name' => 'ADQUISICIÓN DE MERCANCÍAS',
        'code' => 'G01'
      ],
      [
        'active' => true,
        'name' => 'DEVOLUCIONES, DESCUENTOS O BONIFICACIONES',
        'code' => 'G02'
      ],
      [
        'active' => true,
        'name' => 'GASTOS EN GENERAL',
        'code' => 'G03'
      ],
      [
        'active' => true,
        'name' => 'CONSTRUCCIONES',
        'code' => 'I01'
      ],
      [
        'active' => true,
        'name' => 'MOBILIARIO Y EQUIPO DE OFICINA POR INVERSIONES',
        'code' => 'I02'
      ],
      [
        'active' => true,
        'name' => 'EQUIPO DE TRANSPORTE',
        'code' => 'I03'
      ],
      [
        'active' => true,
        'name' => 'EQUIPO DE COMPUTO Y ACCESORIOS',
        'code' => 'I04'
      ],
      [
        'active' => true,
        'name' => 'DADOS, TROQUELES, MOLDES, MATRICES Y HERRAMENTAL',
        'code' => 'I05'
      ],
      [
        'active' => true,
        'name' => 'COMUNICACIONES TELEFÓNICAS',
        'code' => 'I06'
      ],
      [
        'active' => true,
        'name' => 'COMUNICACIONES SATELITALES',
        'code' => 'I07'
      ],
      [
        'active' => true,
        'name' => 'OTRA MAQUINARIA Y EQUIPO',
        'code' => 'I08'
      ],
      [
        'active' => true,
        'name' => 'HONORARIOS MÉDICOS, DENTALES Y GASTOS HOSPITALARIOS',
        'code' => 'D01'
      ],
      [
        'active' => true,
        'name' => 'GASTOS MÉDICOS POR INCAPACIDAD O DISCAPACIDAD',
        'code' => 'D02'
      ],
      [
        'active' => true,
        'name' => 'GASTOS FUNERALES',
        'code' => 'D03'
      ],
      [
        'active' => true,
        'name' => 'DONATIVOS',
        'code' => 'D04'
      ],
      [
        'active' => true,
        'name' => 'INTERESES REALES EFECTIVAMENTE PAGADOS POR CRÉDITOS HIPOTECARIOS (CASA HABITACIÓN)',
        'code' => 'D05'
      ],
      [
        'active' => true,
        'name' => 'APORTACIONES VOLUNTARIAS AL SAR',
        'code' => 'D06'
      ],
      [
        'active' => true,
        'name' => 'PRIMAS POR SEGUROS DE GASTOS MÉDICOS',
        'code' => 'D07'
      ],
      [
        'active' => true,
        'name' => 'GASTOS DE TRANSPORTACIÓN ESCOLAR OBLIGATORIA',
        'code' => 'D08'
      ],
      [
        'active' => true,
        'name' => 'DEPÓSITOS EN CUENTAS PARA EL AHORRO, PRIMAS QUE TENGAN COMO BASE PLANES DE PENSIONES',
        'code' => 'D09'
      ],
      [
        'active' => true,
        'name' => 'PAGOS POR SERVICIOS EDUCATIVOS (COLEGIATURAS)',
        'code' => 'D10'
      ],
      [
        'active' => true,
        'name' => 'SIN EFECTOS FISCALES',
        'code' => 'S01'
      ],
      [
        'active' => true,
        'name' => 'PAGOS',
        'code' => 'CP01'
      ],
      [
        'active' => true,
        'name' => 'NÓMINA',
        'code' => 'CN01'
      ],
    ];

    FiscalUse::insert($items);
  }
}
