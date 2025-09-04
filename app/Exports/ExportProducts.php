<?php

namespace App\Exports;

use App\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ExportProducts implements FromView, WithTitle, WithEvents, WithColumnWidths
{
    public function view(): View
    {
        $printDate = now()->format('Y-m-d');

        return view('products.ProductsAllExcel', [
            'products' => Product::select(
                'id',
                'name_en',
                'name_ar',
                'sku',
                'qty',
                'category_id',
                'image'
            )->orderBy('id')->get(),
            'printDate' => $printDate
        ]);
    }

    public function title(): string
    {
        return 'Products Inventory - ' . now()->format('Y-m-d');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID
            'B' => 30,  // Name
            'C' => 20,  // SKU
            'D' => 15,  // Qty
            'E' => 15,  // Category ID

            'G' => 20,  // Created At
            'H' => 20,  // Updated At
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Set print date in cell I1


                // Apply styling to header row
                $event->sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'FF4CAF50'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Apply alternating row colors
                $event->sheet->getStyle('A2:F'.$event->sheet->getHighestRow())
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => 'FFDDDDDD'],
                            ],
                        ],
                    ]);

                // Freeze the first row
                $event->sheet->freezePane('A2');

                // Auto-filter
                $event->sheet->setAutoFilter('A1:F1');

                // Set print settings
                $event->sheet->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPrintArea('A1:F' . $event->sheet->getHighestRow())
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);
            },
        ];
    }
}