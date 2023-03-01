<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Import;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
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
        try {
            $imports = DB::table('import_details')
                ->select('imports.id', 'imports.description', 'imports.date', 'imports.status', DB::raw('SUM(qty) as qty'), DB::raw('SUM(qty * unit_price) as total'))
                ->join('imports', 'imports.id', '=', 'import_details.import_id')
                ->groupBy('imports.description', 'imports.date', 'imports.status', 'imports.id')
                ->get();
            return response()->json($imports);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
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
                $data->product_id =  $importDetail['product']['id'];
                $data->qty =  $importDetail['qty'];
                $data->unit_price =  $importDetail['unit_price'];
                $import->importDetails()->save($data);

                // Udate Product Qty
                if ($importDetail['product']['id'] &&  $import->status == "Received") {
                    $updateProductQty = Product::find($importDetail['product']['id']);
                    $updateProductQty->qty = $updateProductQty->qty + $importDetail['qty'];
                    $updateProductQty->save();
                }
            }
            DB::commit();
            return response()->json(['message' => "Success"], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
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

        try {
            $import = Import::with('importDetails', 'importDetails.product')->where('id', $id)->first();
            return response()->json($import);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
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
        DB::beginTransaction();
        try {

            // Update Imports table
            $import = Import::find($id);
            $import->description = $request->description;
            $import->date = $request->date;
            $import->status = $request->status;
            $import->save();
            if (!empty($request->import_delete)) {
                foreach ($request->import_delete as $importDelete) {
                    ImportDetail::where('id', $importDelete['id'])->delete();
                }
            }
            // Update Import_Details table
            $importDetails = $request->import_details;
            foreach ($importDetails as $importDetail) {
                if (Arr::has($importDetail, 'id')) {

                    $data = ImportDetail::find($importDetail['id']);
                    $data->product_id =  $importDetail['product']['id'];
                    $data->qty =  $importDetail['qty'];
                    $data->unit_price =  $importDetail['unit_price'];
                    $import->importDetails()->save($data);
                } else {

                    $data = new ImportDetail();
                    $data->product_id =  $importDetail['product']['id'];
                    $data->qty =  $importDetail['qty'];
                    $data->unit_price =  $importDetail['unit_price'];
                    $import->importDetails()->save($data);
                }
                // Update Product Qty
                $updateProductQty = Product::find($importDetail['product']['id']);
                $updateProductQty->qty = $updateProductQty->qty + $importDetail['qty'];
                $updateProductQty->save();
            }

            DB::commit();
            return response()->json(['message' => "Success"], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
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
        try {
            Import::where('id', $id)->delete();
            $importDetails = DB::table('import_details')
                ->select('imports.id', 'imports.description', 'imports.date', 'imports.status', DB::raw('SUM(qty) as qty'), DB::raw('SUM(qty * unit_price) as total'))
                ->join('imports', 'imports.id', '=', 'import_details.import_id')
                ->groupBy('imports.description', 'imports.date', 'imports.status', 'imports.id')
                ->get();
            return response()->json([
                "import_details" => $importDetails,
                'message' => "Success"
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
