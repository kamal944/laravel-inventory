<?php

namespace App\Imports;

use App\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $row = array_change_key_case($row, CASE_LOWER);

        return new Customer([
            'name'      => $row['name'] ?? null,
            'address'    => $row['address'] ?? null,  // Now lowercase
            'email'     => $row['email'] ?? null,
            'phone'   => $row['telephon'] ?? null
        ]);
    }
}
