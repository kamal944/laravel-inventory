<?php

namespace App\Http\Controllers;

use App\Exports\ExportProdukMasuk;
use App\Product;
use App\Product_Masuk;
use App\Supplier;
use PDF;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class ProductMasukController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }

    public function index()
    {
        $products = Product::orderBy('id', 'ASC')->get();


        $suppliers = Supplier::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $invoice_data = Product_Masuk::all();
        return view('product_masuk.index', compact('products','suppliers','invoice_data'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id'     => 'required|exists:products,id',
            'supplier_id'    => 'required|exists:suppliers,id',
            'qty'            => 'required|numeric|min:1',
            'date'           => 'required|date|before_or_equal:today'
        ]);

        Product_Masuk::create($request->all());

        $product = Product::findOrFail($request->product_id);
        $product->qty += $request->qty;
        $product->save();

        return response()->json([
            'success'    => true,
            'message'    => __('main.product_in_created')
        ]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $product_masuk = Product_Masuk::find($id);
        return $product_masuk;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'product_id'     => 'required|exists:products,id',
            'supplier_id'    => 'required|exists:suppliers,id',
            'qty'            => 'required|numeric|min:1',
            'date'           => 'required|date|before_or_equal:today'
        ]);

        $product_masuk = Product_Masuk::findOrFail($id);
        $product_masuk->update($request->all());

        $product = Product::findOrFail($request->product_id);
        $product->qty += $request->qty;
        $product->update();

        return response()->json([
            'success'    => true,
            'message'    => __('main.product_in_updated')
        ]);
    }

    public function destroy($id)
    {
        Product_Masuk::destroy($id);

        return response()->json([
            'success'    => true,
            'message'    => __('main.product_in_deleted')
        ]);
    }

    public function apiProductsIn()
    {
        $products = Product_Masuk::with(['product', 'supplier'])->orderBy('id', 'desc'); // Fixed 'deice' to 'desc'

        return Datatables::of($products)
            ->addColumn('products_name', function ($product) {
                // Return localized product name based on current locale
                if (!$product->product) return 'N/A';

                return app()->getLocale() === 'ar'
                    ? $product->product->name_ar
                    : $product->product->name_en;
            })
            ->addColumn('supplier_name', function ($product) {
                return $product->supplier ? $product->supplier->name : 'N/A';
            })
            ->addColumn('action', function($product) {
                return
                    '<div class="btn-group">' .
                    '<a href="'.route('exportPDF.productMasuk', ['id' => $product->id]).'" class="btn btn-sm btn-danger">
                        <i class="glyphicon glyphicon-file"></i> '.__('main.export_pdf').'
                    </a>' .
                    '<a href="#" class="btn btn-sm btn-info">
                        <i class="glyphicon glyphicon-eye-open"></i> '.__('main.show').'
                    </a>' .
                    '<button onclick="editForm('.$product->id.')" class="btn btn-sm btn-primary">
                        <i class="glyphicon glyphicon-edit"></i> '.__('main.edit').'
                    </button>' .
                    '<button onclick="deleteData('.$product->id.')" class="btn btn-sm btn-danger">
                        <i class="glyphicon glyphicon-trash"></i> '.__('main.delete').'
                    </button>' .
                    '</div>';
            })
            ->rawColumns(['products_name', 'supplier_name', 'action'])
            ->make(true);
    }

    public function exportProductMasukAll()
    {
        $product_masuk = Product_Masuk::all();
        $pdf = PDF::loadView('product_masuk.productMasukAllPDF', compact('product_masuk'));
        return $pdf->download('product_masuk.pdf');
    }

    public function exportProductMasuk($id)
    {
        $product_masuk = Product_Masuk::with(['product', 'supplier'])->findOrFail($id);
        $pdf = PDF::loadView('product_masuk.productMasukPDF', compact('product_masuk'));
        return $pdf->download("Product_in_{$product_masuk->id}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $query = Product_Masuk::with(['product', 'supplier'])->latest();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Apply recent records limit if specified
        if ($request->filled('recent_count')) {
            $query->take($request->recent_count);
        }

        $data = $query->get();

        return (new ExportProdukMasuk($data))->download('product_masuk_filtered.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $query = Product_Masuk::with(['product', 'supplier'])->latest();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Apply recent records limit if specified
        if ($request->filled('recent_count')) {
            $query->take($request->recent_count);
        }

        $product_masuk = $query->get();

        // Generate filename based on what we're exporting
        $filename = 'product_in';
        if ($product_masuk->count() === 1) {
            $filename .= '_' . $product_masuk->first()->id;
        } elseif ($request->filled('recent_count')) {
            $filename .= '_last_' . $request->recent_count . '_records';
        } elseif ($request->filled('from_date') && $request->filled('to_date')) {
            $filename .= '_from_' . $request->from_date . '_to_' . $request->to_date;
        }

        $pdf = Pdf::loadView('product_masuk.productMasukAllPDF', compact('product_masuk'));
        return $pdf->download("{$filename}.pdf");
    }

    public function exportPDFInvoice_in(Request $request)
    {
        $query = Product_Masuk::with(['product', 'supplier'])->latest();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Apply recent records limit if specified
        if ($request->filled('recent_count')) {
            $query->take($request->recent_count);
        }

        $product_masuk = $query->get();

        // Generate filename based on what we're exporting
        $filename = 'invoice';
        if ($product_masuk->count() === 1) {
            $filename .= $product_masuk->first()->id;
        } elseif ($request->filled('recent_count')) {
            $filename .= '_last_' . $request->recent_count . '_records';
        } elseif ($request->filled('from_date') && $request->filled('to_date')) {
            $filename .= 'product in_from_' . $request->from_date . '_to_' . $request->to_date;
        }

        $pdf = Pdf::loadView('product_masuk.productInInvoice', compact('product_masuk'));
        return $pdf->download("{$filename}.pdf");
    }
}