<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $row = array_change_key_case($row, CASE_LOWER); // To handle inconsistent column casing

        $sku = $row['sku'] ?? null;

        return new Product([
            'name_en'        => $row['name_en'] ?? null,
            'name_ar'        => $row['name_ar'] ?? null,
            'sku'         => $sku,
            'qty'         => $row['qty'] ?? 0,
            'category_id' => $row['category_id'] ?? 1,
            'image'       => $sku ? "/upload/products/{$sku}.jpg" : null,
        ]);
    }
}
