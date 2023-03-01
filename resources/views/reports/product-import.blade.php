@extends('layouts.layout')
@section('title'){{'Report - Product Import'}}@endsection
@section('css')
<style>
    .import-text-color {
        color: #9E9E9E;
    }

    .import-text-color-body {
        color: #777777;
        font-size: 14px;
        font-weight: 400;

    }

    .import-font-12 {
        font-size: 12px;
        font-weight: 400;
    }

    .label-font {
        font-size: 11px;
        font-weight: 600;
        color: #000000;

    }
</style>
@endsection
@section('content')
<div class="container mt-3">
    <div class="row">
        <form action="{{url('reports/filter')}}" method="POST">
            @csrf
            <div class="d-flex justify-content-between">
                <p style="font-size: 15px; margin-left:35px;"><span style="color:#4EA6D7;">Reports</span><span style="color: #B8B8B8; margin: 0 10px;">/</span><span style="color: #B8B8B8;">Product Imports</span></p>
                <div class="mb-3 row">
                    <label for="sale_date" class="col-sm-3 col-form-labe label-font d-flex align-items-center">Import Date:</label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control" data-date-format="DD/MM/YYYY" name="import_date" style="border: none;font-size: 14px;font-weight: 400;color: #B5B2B2; height:40px;" id="date" value="{{$importDate}}">
                    </div>
                    <div class="col-sm-3 d-flex justify-content-end">
                        <button class="btn btn-primary d-flex justify-content-center" style="background-color: #1F5A92; text-transform: none;  border-radius: 0; width:80px; height:40px;" type="submit">
                            <span style="font-weight: 400px; font-size:14px; color:#FFFFFF;">Filter</span>
                        </button>

                    </div>
                </div>
                <a class="btn btn-primary d-flex justify-content-center" style="background-color: #1F5A92; text-transform: none;  border-radius: 0; width:80px; height:40px;" type="button" href="{{url('reports/product-imports')}}">
                    <span style=" font-weight: 400px; font-size:14px; color:#FFFFFF;">All</span>
                </a>
            </div>
        </form>
    </div>
</div>
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th scope="col" class="import-text-color import-font-12">No.</th>
                <th scope="col" class="import-text-color import-font-12">Import Date</th>
                <th scope="col" class="import-text-color import-font-12">Product</th>
                <th scope="col" class="import-text-color import-font-12">Barcode</th>
                <th scope="col" class="import-text-color import-font-12">Quantity</th>
                <th scope="col" class="import-text-color import-font-12">Unit Price</th>
                <th scope="col" class="text-center import-text-color import-font-12">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $total = 0;
            ?>
            @foreach($reports as $report)

            <tr style="background-color: #FAFAFA; border-left-width:1px;border-right-width:1px;">
                <td class="import-text-color-body">{{$i++}}</td>
                <td class="import-text-color-body">{{date('d F y',strtotime($report->date))}}</td>
                <td class="import-text-color-body">{{ $report->product_name}}</td>
                <td class="import-text-color-body">{{ $report->barcode}}</td>
                <td class="import-text-color-body">{{ $report->qty}}</td>
                <td class="import-text-color-body">{{number_format($report->unit_price, 2, '.', ',')}}</td>
                <td class="import-text-color-body text-center">{{number_format( $report->qty * $report->unit_price, 2, '.', ',')}}</td>
            </tr>
            @php
            $total += $report->qty*$report->unit_price;
            @endphp
            @endforeach
            <tr style="background-color: #FAFAFA; border-left-width:1px;border-right-width:1px;">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="import-text-color-body text-center">Total:</td>
                <td class="import-text-color-body text-center">{{number_format($total, 2, '.', ',')}}</td>
            </tr>

        </tbody>
    </table>
</div>

@endsection

@section('js')

@endsection