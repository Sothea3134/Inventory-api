<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImportDetail;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function reportProductImport()
    {
        $importDate = '';
        $reports = DB::table('import_details')
            ->select('products.name as product_name', 'products.barcode', 'imports.date', 'imports.status', DB::raw("SUM(import_details.qty) as qty"), 'import_details.unit_price')
            ->join('imports', 'imports.id', '=', 'import_details.import_id')
            ->join('products', 'products.id', '=', 'import_details.product_id')
            ->groupBy('imports.date', 'products.name', 'products.barcode', 'import_details.unit_price', 'imports.status')
            ->where('imports.status', 'Received')
            ->get();
        return view('reports.product-import', ['reports' => $reports, 'importDate' => $importDate]);
    }
    public function reportProductSale()
    {
        $saleDate = '';
        $reports = DB::table('sale_details')
            ->select('products.name as product_name', 'products.barcode', 'sales.sale_date', 'sales.status', DB::raw("SUM(sale_details.qty) as qty"), 'sale_details.unit_price')
            ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
            ->join('products', 'products.id', '=', 'sale_details.product_id')
            ->groupBy('sales.sale_date', 'products.name', 'products.barcode', 'sale_details.unit_price', 'sales.status')
            ->where('sales.status', 'Final')
            ->get();
        return view('reports.sale-product', ['reports' => $reports, 'saleDate' => $saleDate]);
    }
    public function reportFilter(Request $request)
    {

        if ($request->import_date) {
            $importDate = $request->import_date;
            $reports = DB::table('import_details')
                ->select('products.name as product_name', 'products.barcode', 'imports.date', 'imports.status', DB::raw("SUM(import_details.qty) as qty"), 'import_details.unit_price')
                ->join('imports', 'imports.id', '=', 'import_details.import_id')
                ->join('products', 'products.id', '=', 'import_details.product_id')
                ->groupBy('imports.date', 'products.name', 'products.barcode', 'import_details.unit_price', 'imports.status')
                ->where('imports.status', 'Received')
                ->where('imports.date', $importDate)
                ->get();
            return view('reports.product-import', ['reports' => $reports, 'importDate' => $importDate]);
        } else {
            $saleDate = $request->sale_date;
            $reports = DB::table('sale_details')
                ->select('products.name as product_name', 'products.barcode', 'sales.sale_date', 'sales.status', DB::raw("SUM(sale_details.qty) as qty"), 'sale_details.unit_price')
                ->join('sales', 'sales.id', '=', 'sale_details.sale_id')
                ->join('products', 'products.id', '=', 'sale_details.product_id')
                ->groupBy('sales.sale_date', 'products.name', 'products.barcode', 'sale_details.unit_price', 'sales.status')
                ->where('sales.status', 'Final')
                ->where('sales.sale_date', $saleDate)
                ->get();
            return view('reports.sale-product', ['reports' => $reports, 'saleDate' => $saleDate]);
        }
    }
}
