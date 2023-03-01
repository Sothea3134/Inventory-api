<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function reportProduct(Request $request)
    {
        try {
            if ($request->url == "sale_details") {
                $reports = DB::table($request->url)
                    ->select('products.name as product_name', 'products.barcode', "sales.sale_date", 'sales.status', DB::raw("SUM($request->url.qty) as qty"), $request->url . '.unit_price')
                    ->join('sales', 'sales.id', '=', $request->url . '.sale_id')
                    ->join('products', 'products.id', '=', $request->url . '.product_id')
                    ->groupBy("sales.sale_date", 'products.name', 'products.barcode', $request->url . '.unit_price', 'sales.status')
                    ->where('sales.status', 'Final');
                if ($request->filter != 0)  $reports->where("sales.sale_date", $request->filter);
            } else {
                $reports = DB::table($request->url)
                    ->select('products.name as product_name', 'products.barcode', 'imports.date', 'imports.status', DB::raw("SUM($request->url.qty) as qty"), $request->url . '.unit_price')
                    ->join('imports', 'imports.id', '=', $request->url . '.import_id')
                    ->join('products', 'products.id', '=', $request->url . '.product_id')
                    ->groupBy("imports.date", 'products.name', 'products.barcode', $request->url . '.unit_price', 'imports.status')
                    ->where('imports.status', 'Received');
                if ($request->filter != 0)  $reports->where("imports.date", $request->filter);
            }

            return response()->json($reports->get());
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }
}
