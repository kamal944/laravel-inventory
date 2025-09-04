<?php

namespace App\Exports;

use App\Product_Masuk;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ExportProdukMasuk implements FromView
{
    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('product_masuk.productMasukAllExcel', [
            'product_masuk' => $this->data
        ]);
    }
}

