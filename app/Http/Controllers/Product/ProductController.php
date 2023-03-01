<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = DB::table('products')->paginate(10);
        return view('product.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => "required",
            'barcode' => "required",
            'price' => "required|numeric",
            'inventory_type' => "required",
            'image' => 'mimes:jpg,png,jpeg,gif,svg|max:2048',
            'qty' => "required|numeric",
        ]);
        $productBarcodeExist = DB::table('products')->where('barcode', '=', $request->barcode)->first();
        if ($productBarcodeExist) {
            return redirect('product/create')->with('error', 'Product alreary exist.');
        } else {
            $product = new Product();
            $product->name = $request->name;
            $product->barcode = $request->barcode;
            $product->price = $request->price;
            $product->inventory_type = $request->inventory_type;
            $product->qty = $request->qty;
            if ($request->hasfile('image')) {
                $file = $request->file('image');
                $extention = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extention;
                $file->move(public_path('images'), $filename);
                $product->image_path = $filename;
            }
            $product->save();
            return redirect('product/create')->with('success', 'product already saved.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        return view('product.edit', ['product' =>  $product]);
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
        $request->validate([
            'name' => "required",
            'barcode' => "required",
            'price' => "required|numeric",
            'image' => 'mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $product = Product::find($id);
        $product->name = $request->name;
        $product->barcode = $request->barcode;
        $product->price = $request->price;
        $destination = 'images/' . $product->image_path;
        if ($request->hasfile('image')) {
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move(public_path('images'), $filename);
            $product->image_path = $filename;
        }
        $product->save();
        return redirect('product/' . $product->id . '/edit')->with('success', 'Product alreary updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $destination = 'images/' . $product->image_path;
        if (File::exists($destination)) {
            File::delete($destination);
        }
        $product->delete();
        return redirect('product');
    }
}
