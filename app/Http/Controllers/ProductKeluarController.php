<?php

namespace App\Http\Controllers;

use App\Category;
use App\Customer;
use App\Exports\ExportProdukKeluar;
use App\Product;
use App\Product_Keluar;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;


class ProductKeluarController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('id', 'ASC')->get();

        $customers = Customer::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $invoice_data = Product_Keluar::orderBy('id','desc')->get();
        return view('product_keluar.index', compact('products','customers', 'invoice_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id'     => 'required|exists:products,id',
            'customer_id'    => 'required|exists:customers,id',
            'qty'            => 'required|numeric|min:1',
            'date' => 'required|date|before_or_equal:today'
        ]);

        // Additional validation - check if quantity is available
        $product = Product::findOrFail($request->product_id);
        if ($product->qty < $request->qty) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient product quantity'
            ], 422);
        }

        // Create the record
        Product_Keluar::create($request->all());

        // Update product quantity
        $product->qty -= $request->qty;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => __('main.product_out_created')
        ]);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product_keluar = Product_Keluar::find($id);
        return $product_keluar;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'product_id'     => 'required',
            'customer_id'    => 'required',
            'qty'            => 'required',
            'date'           => 'required'
        ]);

        $product_keluar = Product_Keluar::findOrFail($id);
        $product_keluar->update($request->all());

        $product = Product::findOrFail($request->product_id);
        $product->qty -= $request->qty;
        $product->update();

        return response()->json([
            'success'    => true,
            'message'    => 'Product Out Updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product_Keluar::destroy($id);

        return response()->json([
            'success'    => true,
            'message'    => 'Products Delete Deleted'
        ]);
    }



    public function apiProductsOut()
    {
        $products = Product_Keluar::with(['product', 'customer'])->orderBy('id', 'desc');

        return Datatables::of($products)
            ->addColumn('products_name', function ($product) {
                // Return localized product name based on current locale
                if (!$product->product) return 'N/A';

                return app()->getLocale() === 'ar'
                    ? $product->product->name_ar
                    : $product->product->name_en;
            })
            ->addColumn('customer_name', function ($product) {
                return $product->customer ? $product->customer->name : 'N/A';
            })
            ->addColumn('action', function ($product) {
                // Using Laravel's translation system instead of inline conditions
                return '
 <a href="'.route('exportPDF.productKeluar', ['id' => $product->id]).'" class="btn btn-danger btn-xs">
        <i class="glyphicon glyphicon-file"></i> '.__('main.export_pdf').'
    </a>
    <a href="#" class="btn btn-info btn-xs">
        <i class="glyphicon glyphicon-eye-open"></i> '.__('main.show').'
    </a>
    <a onclick="editForm('.$product->id.')" class="btn btn-primary btn-xs">
        <i class="glyphicon glyphicon-edit"></i> '.__('main.edit').'
    </a>
    <a onclick="deleteData('.$product->id.')" class="btn btn-danger btn-xs">
        <i class="glyphicon glyphicon-trash"></i> '.__('main.delete').'
    </a>
   
';
            })
            ->rawColumns(['action'])
            ->make(true);
    }    public function exportProductKeluarAll()
    {
        $product_keluar = Product_Keluar::all();
        $pdf = PDF::loadView('product_keluar.productKeluarAllPDF',compact('product_keluar'));
        return $pdf->download('product_keluar.pdf');
    }



// Export single product_keluar by ID
    public function exportProductKeluar($id)
    {
        $product_keluar = Product_Keluar::with(['product', 'customer'])->findOrFail($id);

        $pdf = PDF::loadView('product_keluar.productKeluarPDF', compact('product_keluar'));
        return $pdf->download("Product_out_{$product_keluar->id}.pdf");
    }

// Export filtered product_keluar to Excel
    public function exportExcel(Request $request)
    {
        $query = Product_Keluar::with(['product', 'customer']);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $data = $query->get();

        return (new ExportProdukKeluar($data))->download('product_keluar_filtered.xlsx');
    }

// Export filtered product_keluar to PDF
    public function exportPDF(Request $request)
    {
        $query = Product_Keluar::with(['product', 'customer']);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('recent_count')) {
            $query->take($request->recent_count);
        }
        $product_keluar = $query->get();
//dd($product_keluar);
        $pdf = Pdf::loadView('product_keluar.productKeluarAllPDF', compact('product_keluar'));
        return $pdf->download('product_out_filtered.pdf');
    }
    public function exportPDFInvoice(Request $request)
    {
        $query = Product_Keluar::with(['product', 'customer'])->latest(); // Added latest() for ordering

        // Date range filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        // Product filter
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Customer filter
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Apply recent records limit if specified
        if ($request->filled('recent_count')) {
            $query->take($request->recent_count);
        }

        $product_keluar = $query->get();

        // Generate filename based on what we're exporting
        $filename = 'invoice';
        if ($product_keluar->count() === 1) {
            $filename .= $product_keluar->first()->id;
        } elseif ($request->filled('recent_count')) {
            $filename .= '_last_' . $request->recent_count . '_records';
        } elseif ($request->filled('from_date') && $request->filled('to_date')) {
            $filename .= 'invoice_from_' . $request->from_date . '_to_' . $request->to_date;
        }
        $pdf = Pdf::loadView('product_keluar.productoutInvoice', compact('product_keluar'));
        return $pdf->download("{$filename}.pdf");
    }
}
