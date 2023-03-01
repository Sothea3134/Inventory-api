<?php

namespace App\Http\Controllers\ImportStock;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Import;
use App\Models\ImportDetail;
use Illuminate\Support\Arr;

class ImportStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $imports = DB::table('import_details')
            ->select('imports.id', 'imports.description', 'imports.date', 'imports.status', DB::raw('SUM(qty) as qty'), DB::raw('SUM(qty * unit_price) as total'))
            ->join('imports', 'imports.id', '=', 'import_details.import_id')
            ->groupBy('imports.description', 'imports.date', 'imports.status', 'imports.id')
            ->paginate(10);
        return view('importstock.index', ['imports' => $imports]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('importstock.create');
    }

    public function search(Request $request)

    {
        $searchBarcode = DB::table('products')->where('barcode', '=', $request->searchBarcode)
            ->first();
        return response()->json([
            'searchBarcode' => $searchBarcode
        ]);
    }
    public function fetchProduct()

    {
        $products = DB::table('products')
            ->where('inventory_type', 'inventory')
            ->get();
        return response()->json([
            'products' => $products

        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            // insert Imports table
            $import = new Import();
            $import->description = $request->description;
            $import->date = $request->date;
            $import->status = $request->status;
            $import->save();

            // insert Import_Details table
            $importDetails = $request->import_details;
            foreach ($importDetails as $importDetail) {

                $data = new ImportDetail();
                $data->product_id =  $importDetail['product_id'];
                $data->qty =  $importDetail['qty'];
                $data->unit_price =  $importDetail['unit_price'];
                $import->importDetails()->save($data);

                // Udate Product Qty
                if ($importDetail['product_id'] &&  $import->status == "Received") {
                    $updateProductQty = Product::find($importDetail['product_id']);
                    $updateProductQty->qty = $updateProductQty->qty + $importDetail['qty'];
                    $updateProductQty->save();
                }
            }
            DB::commit();
            return redirect('import/create')->with('success', 'Import product saved');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetchData($id)
    {
        $imports = DB::table('import_details')
            ->select('import_details.id', 'import_details.import_id', 'import_details.product_id', 'products.name', 'products.barcode', 'import_details.qty', 'import_details.unit_price')
            ->join('products', 'products.id', '=', 'import_details.product_id')
            ->where('import_id', $id)->get();
        return response()->json([
            'imports' => $imports

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $import = Import::find($id);
        return view('importstock.edit', ['import' => $import]);
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
        DB::beginTransaction();
        try {

            // Update Imports table
            $import = Import::find($id);
            $import->description = $request->description;
            $import->date = $request->date;
            $import->status = $request->status;
            $import->save();

            // Update Import_Details table
            $importDetails = $request->import_details;


            foreach ($importDetails as $importDetail) {
                if (Arr::has($importDetail, 'id')) {

                    $data = ImportDetail::find($importDetail['id']);
                    $data->product_id =  $importDetail['product_id'];
                    $data->qty =  $importDetail['qty'];
                    $data->unit_price =  $importDetail['unit_price'];
                    $import->importDetails()->save($data);
                } else {

                    $data = new ImportDetail();
                    $data->product_id =  $importDetail['product_id'];
                    $data->qty =  $importDetail['qty'];
                    $data->unit_price =  $importDetail['unit_price'];
                    $import->importDetails()->save($data);
                }
                // Update Product Qty
                $updateProductQty = Product::find($importDetail['product_id']);
                $updateProductQty->qty = $updateProductQty->qty + $importDetail['qty'];
                $updateProductQty->save();
            }

            DB::commit();
            return redirect('import/' . $id . '/edit')->with('success', 'Import product updated');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Import::where('id', $id)->delete();
        return redirect('import');
    }
}
