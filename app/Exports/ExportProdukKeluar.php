<?php

namespace App\Exports;

use App\Product_Keluar;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ExportProdukKeluar implements FromView
{
    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('product_keluar.productKeluarAllExcel', [
            'product_keluar' => $this->data
        ]);
    }
}