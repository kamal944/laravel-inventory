<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use PDF;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Exports\ExportProducts;
class ProductController extends Controller
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
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $products = Product::all();
        return view('products.index', compact('category','products'));
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
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $this->validate($request , [
            'name_en'          => 'required|string',
            'name_ar'          => 'required|string',
            'sku'         => 'required',
            'qty'           => 'required',
            'image'         => 'required',
            'category_id'   => 'required',
        ]);

        $input = $request->all();
        $input['image'] = null;

        if ($request->hasFile('image')){
            $input['image'] = '/upload/products/'.str_slug($input['sku'], '-').'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('/upload/products/'), $input['image']);
        }

        Product::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Products Created'
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
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');
        $product = Product::find($id);
        return $product;
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
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $this->validate($request , [
            'name_en'          => 'required|string',
            'name_ar'          => 'required|string',
            'date'         => 'required',
            'qty'           => 'required',
//            'image'         => 'required',
            'category_id'   => 'required',
        ]);

        $input = $request->all();
        $produk = Product::findOrFail($id);

        $input['image'] = $produk->image;

        if ($request->hasFile('image')){
            if (!$produk->image == NULL){
                unlink(public_path($produk->image));
            }
            $input['image'] = '/upload/products/'.str_slug($input['name'], '-').'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('/upload/products/'), $input['image']);
        }

        $produk->update($input);

        return response()->json([
            'success' => true,
            'message' => 'Products Update'
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
        $product = Product::findOrFail($id);

        if (!$product->image == NULL){
            unlink(public_path($product->image));
        }

        Product::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Products Deleted'
        ]);
    }

    public function apiProducts()
    {
        $products = Product::all();

        return Datatables::of($products)
            ->addColumn('category_name', function ($product) {
                return $product->category ? $product->category->name : 'N/A';
            })
            ->addColumn('date', function ($product) {
                return $product->created_at;
            })
            ->addColumn('show_photo', function($product) {
                if ($product->image == NULL) {
                    // Get current locale
                    $currentLocale = app()->getLocale();
                    return ($currentLocale == 'ar') ? 'لا توجد صورة' : 'No Image';
                }
                return '<img class="rounded-square" width="50" height="50" src="'. url($product->image) .'" alt="">';
            })
            ->addColumn('action', function($product) {
                // Get current locale
                $currentLocale = app()->getLocale();

                // Set button text based on language
                $showText = ($currentLocale == 'ar') ? 'عرض' : 'Show';
                $editText = ($currentLocale == 'ar') ? 'تعديل' : 'Edit';
                $deleteText = ($currentLocale == 'ar') ? 'حذف' : 'Delete';

                return '<a href="#" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-eye-open"></i> ' . $showText . '</a> ' .
                    '<a onclick="editForm(' . $product->id . ')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> ' . $editText . '</a> ' .
                    '<a onclick="deleteData(' . $product->id . ')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> ' . $deleteText . '</a>';
            })
            ->rawColumns(['category_name', 'show_photo', 'action'])
            ->make(true);
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            // Perform import
            Excel::import(new ProductsImport, $request->file('file'));

            return redirect()->back()->with('success', 'Products imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }


    public function exportExcel()
    {
        return Excel::download(new ExportProducts, 'products.xlsx');
    }


    public function exportProductsAll()
    {
        $products = Product::all();
        $pdf = Pdf::loadView('products.ProductsAllPDF', compact('products'));
        return $pdf->download('products.pdf');
    }
}
