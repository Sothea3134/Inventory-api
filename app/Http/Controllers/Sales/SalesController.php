<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\SaleDetail;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Arr;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = DB::table('sale_details')
            ->select('sales.id', 'customers.name', 'sales.invoice_no', 'sales.sale_date', 'sales.status', DB::raw('SUM(qty) as qty'), DB::raw('SUM(qty * unit_price) as total'))
            ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->groupBy('sales.invoice_no', 'sales.sale_date', 'sales.status', 'sales.id', 'customers.name')
            ->paginate(10);
        return view('sales.index', ['sales' => $sales]);
    }

    public function previewInvoice($id)
    {
        $sales = DB::table('sale_details')
            ->select('sales.id', 'customers.name as customer_name', 'products.name as product_name', 'customers.phone', 'customers.address', 'sales.invoice_no', 'sales.sale_date', 'sales.status', 'sale_details.qty', 'sale_details.unit_price')
            ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->join('products', 'products.id', '=', 'sale_details.product_id')
            ->where('sale_id', $id)
            ->get();

        return view('sales.invoice', ['sales' => $sales]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sales.create');
    }


    public function search(Request $request)

    {
        $searchBarcode = DB::table('products')->where('barcode', '=', $request->searchBarcode)
            ->first();
        return response()->json([
            'searchBarcode' => $searchBarcode
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetchData($id)
    {
        $sales = DB::table('sale_details')
            ->select('sale_details.id', 'sale_details.sale_id', 'sale_details.product_id', 'products.name', 'products.barcode', 'sale_details.qty', 'sale_details.unit_price')
            ->join('products', 'products.id', '=', 'sale_details.product_id')
            ->where('sale_id', $id)->get();
        return response()->json([
            'sales' => $sales

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
        // dd($request->all());
        DB::beginTransaction();
        try {
            $customer = DB::table('customers')->where('name', $request->customer)->first();
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
                $data->product_id =  $saleDetail['product_id'];
                $data->qty =  $saleDetail['qty'];
                $data->unit_price =  $saleDetail['unit_price'];
                $sale->saleDetails()->save($data);

                // Udate Product Qty
                if ($saleDetail['product_id'] &&  $sale->status == "Final") {
                    $updateProductQty = Product::find($saleDetail['product_id']);
                    if ($updateProductQty->inventory_type == 'inventory') {
                        $updateProductQty->qty = $updateProductQty->qty - $saleDetail['qty'];
                    }
                    $updateProductQty->save();
                }
            }
            DB::commit();
            return redirect('sales/create')->with('success', 'Sale Product saved');
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
    public function fetchCustomerData()
    {
        $customers = DB::table('customers')
            ->get();
        return response()->json([
            'customers' => $customers
        ]);
    }
    public function fetchProduct()

    {
        $products = DB::table('products')
            ->get();
        return response()->json([
            'products' => $products

        ]);
    }
    public function storeCustomer(Request $request)
    {

        $customer = new Customer();
        $customer->name = $request->customer_name;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->save();
        return response()->json([
            'status' => '200',
            'message' => 'Customer saved'
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
        $customers = Customer::all();
        $sale = Sale::find($id);
        return view('sales.edit', ['sale' => $sale, 'customers' => $customers]);
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
            $customer = DB::table('customers')->where('name', $request->customer)->first();
            $sale = Sale::find($id);
            $sale->customer_id = $customer->id;
            $sale->sale_date = $request->sale_date;
            $sale->invoice_no = $request->invoice_no;
            $sale->status = $request->status;
            $sale->save();

            // Update Import_Details table
            $saleDetails = $request->sale_details;


            foreach ($saleDetails as $saleDetail) {
                if (Arr::has($saleDetail, 'id')) {

                    $data = SaleDetail::find($saleDetail['id']);
                    $data->product_id =  $saleDetail['product_id'];
                    $data->qty =  $saleDetail['qty'];
                    $data->unit_price =  $saleDetail['unit_price'];
                    $sale->saleDetails()->save($data);
                } else {

                    $data = new SaleDetail();
                    $data->product_id =  $saleDetail['product_id'];
                    $data->qty =  $saleDetail['qty'];
                    $data->unit_price =  $saleDetail['unit_price'];
                    $sale->saleDetails()->save($data);
                }
                // Update Product Qty
                $updateProductQty = Product::find($saleDetail['product_id']);
                if ($updateProductQty->inventory_type == 'inventory') {
                    $updateProductQty->qty = $updateProductQty->qty - $saleDetail['qty'];
                }
                $updateProductQty->save();
            }

            DB::commit();
            return redirect('sales/' . $id . '/edit')->with('success', 'Sale product updated');
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
        Sale::where('id', $id)->delete();
        return redirect('sales');
    }
}
