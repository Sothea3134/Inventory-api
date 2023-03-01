<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = 15;
        $offset = 0;

        $products = Product::where([]);

        if ($request->search) $products->where('name', 'like', "%$request->search%");

        if ($request->inventory_type) $products->where('inventory_type', $request->inventory_type);

        if ($request->limit) $limit = $request->limit;

        if ($request->offset) $offset = $request->offset;

        $products->limit($limit);

        $products->offset($offset);

        return response($products->get());
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
        $status_code = 201;
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'barcode' => 'required|max:13'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $status_code = 400;

                if ($errors->first('name')) abort($status_code, $errors);
                if ($errors->first('barcode')) abort($status_code, $errors);
            }

            $product = new Product();
            $product->name = $request->name;
            $product->image_path = $request->image_path;
            $product->barcode = $request->barcode;
            $product->price = $request->price;
            $product->qty = $request->qty;
            $product->inventory_type = $request->inventory_type;
            $product->save();

            return response()->json(null, $status_code);
        } catch (\Throwable $th) {
            return response($th->getMessage(), $status_code);
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
        $product = Product::find($id);
        return response($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $product = Product::find($id);
        $product->name = $request->name;
        $product->barcode = $request->barcode;
        $product->price = $request->price;
        $product->qty = $request->qty;
        $product->save();

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);

        return response()->json(null, 204);
    }
}
