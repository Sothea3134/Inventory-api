<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\SaleDetail;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Arr;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $sales = DB::table('sale_details')
                ->select('sales.id', 'customers.name', 'sales.invoice_no', 'sales.sale_date', 'sales.status', DB::raw('SUM(qty) as qty'), DB::raw('SUM(qty * unit_price) as total'))
                ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
                ->join('customers', 'customers.id', '=', 'sales.customer_id')
                ->groupBy('sales.invoice_no', 'sales.sale_date', 'sales.status', 'sales.id', 'customers.name')
                ->get();
            return response()->json($sales);
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
            $customer = DB::table('customers')->where('name', $request->customer['name'])->first();
            // insert Imports table
            $sale = new Sale();
            $sale->customer_id = $customer->id;
            $sale->sale_date = $request->sale_date;
            $sale->invoice_no = $request->invoice_no;
            $sale->status = $request->status;
            $sale->save();
            // insert Import_Details table
            $saleDetails = $request->sale_details;
            foreach ($saleDetails as $saleDetail) {

                $data = new SaleDetail();
                $data->product_id =  $saleDetail['product']["id"];
                $data->qty =  $saleDetail['qty'];
                $data->unit_price =  $saleDetail['unit_price'];
                $sale->saleDetails()->save($data);

                // Udate Product Qty
                if ($saleDetail['product']["id"] &&  $sale->status == "Final") {
                    $updateProductQty = Product::find($saleDetail['product']["id"]);
                    if ($updateProductQty->inventory_type == 'inventory') {
                        $updateProductQty->qty = $updateProductQty->qty - $saleDetail['qty'];
                    }
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
            $sale = Sale::with("customer", 'saleDetails', 'saleDetails.product')->where('id', $id)->first();
            return response()->json($sale);
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
            $customer = DB::table('customers')->where('name', $request->customer['name'])->first();
            $sale = Sale::find($id);
            $sale->customer_id = $customer->id;
            $sale->sale_date = $request->sale_date;
            $sale->invoice_no = $request->invoice_no;
            $sale->status = $request->status;
            $sale->save();

            if (!empty($request->sale_delete)) {
                foreach ($request->sale_delete as $saleDelete) {
                    SaleDetail::where('id', $saleDelete['id'])->delete();
                }
            }
            // Update Import_Details table
            $saleDetails = $request->sale_details;
            foreach ($saleDetails as $saleDetail) {
                if (Arr::has($saleDetail, 'id')) {

                    $data = SaleDetail::find($saleDetail['id']);
                    $data->product_id =  $saleDetail['product']['id'];
                    $data->qty =  $saleDetail['qty'];
                    $data->unit_price =  $saleDetail['unit_price'];
                    $sale->saleDetails()->save($data);
                } else {

                    $data = new SaleDetail();
                    $data->product_id =  $saleDetail['product']['id'];
                    $data->qty =  $saleDetail['qty'];
                    $data->unit_price =  $saleDetail['unit_price'];
                    $sale->saleDetails()->save($data);
                }
                // Update Product Qty
                $updateProductQty = Product::find($saleDetail['product']["id"]);
                if ($updateProductQty->inventory_type == 'inventory') {
                    $updateProductQty->qty = $updateProductQty->qty - $saleDetail['qty'];
                }
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
            Sale::where('id', $id)->delete();
            $sales = DB::table('sale_details')
                ->select('sales.id', 'customers.name', 'sales.invoice_no', 'sales.sale_date', 'sales.status', DB::raw('SUM(qty) as qty'), DB::raw('SUM(qty * unit_price) as total'))
                ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
                ->join('customers', 'customers.id', '=', 'sales.customer_id')
                ->groupBy('sales.invoice_no', 'sales.sale_date', 'sales.status', 'sales.id', 'customers.name')
                ->get();
            return response()->json([
                "sales" =>  $sales,
                'message' => "Success"
            ]);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }
}
