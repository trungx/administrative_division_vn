<?php

namespace App\Imports;

use App\Models\AdministrativeUnit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AdministrativeUnitImport implements ToModel, WithStartRow
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 3;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new AdministrativeUnit([
            'l1_name'   => $row[0],
            'l1_code'  => $row[1],
            'l2_name'  => $row[2],
            'l2_code'  => $row[3],
            'l3_name'  => $row[4],
            'l3_code'  => $row[5],
            'level'  => $row[6],
            'en_name'  => $row[7],
        ]);
    }
}
