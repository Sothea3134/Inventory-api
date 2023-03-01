@extends('layouts.layout')
@section('title'){{'Sale Invoice'}}@endsection
@section('css')
<style>
    th {
        font-size: 10px;
        font-weight: 600;
        color: #9DA8BB;
        font-family: 'Open Sans', sans-serif;
    }

    td {
        font-size: 10px;
        font-weight: 400;
        color: #1F2229;
    }

    @media print {
        #nav {
            display: none !important;
        }

        #sale {
            display: none !important;
        }
    }
</style>
@endsection
@section('content')
<div class="container-fluid mt-3" id="sale">
    <div class="container">
        <div class="row">
            <div class="d-flex justify-content-between">
                <p style="font-size: 15px; margin-left:35px;"><span style="color:#4EA6D7;">Sales</span><span style="color: #B8B8B8; margin: 0 10px;">/</span><span style="color: #B8B8B8;">Preview Invoice</span></p>
                <a id="print" type="button" class="d-flex justify-content-end">
                    <div style=" background-color:#FFFFFF; width:53px;height:43px;" class="d-flex justify-content-center align-items-center">
                        <i class="fas fa-print" style="font-size:22px;color:#999999;"></i>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>
<div class="container" id="invoice">
    <div class="row">
        <div class="col-3"></div>
        <div class="col-6 p-5" style="background-color: #FBFBFB; width:720px;">
            <div class="row">
                <div class="d-flex justify-content-between">
                    <div><img src="{{asset('images/logo.jpg')}}" alt="" width="50" height="50" style="color:#FBFBFB;"></div>
                    <div style="width: 144px; text-align:end;">
                        <h1 style="font-size: 14px; font-weight:400; color:#828691; text-transform: uppercase;">CA INVENTION</h1>
                        <p style="font-size: 10px;font-weight:400; color:#828691;text-transform: uppercase;">No. 199, St.1986, Sangkat Phnom Penh Thmey, Khan Sensok, Phnom Penh, Cambodia. ​</p>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="d-flex justify-content-between">
                    <div style="width: 230px;">
                        <p style="font-size: 12px; font-weight:600; color:#1F2229; text-transform: uppercase; font-family: 'Open Sans', sans-serif;">Customer</p>
                        <div style="font-size: 10px; font-weight:400; color:#828691; text-transform: uppercase; margin-bottom: 7px;">
                            <span>{{$sales[0]->customer_name}}</span><br>
                            {{$sales[0]->address}} ​
                        </div>
                        <div style="font-size: 10px; font-weight:400; color:#828691; ">{{$sales[0]->phone}}</div>
                    </div>
                    <div style="width: 142px; text-align:end;">
                        <div style="font-size: 26px; font-weight:600; color:#1F2229; text-transform: uppercase; margin-top: -12px; margin-bottom: 6px;">Invoice</div>
                        <div class="d-flex flex-column">
                            <div style="font-size: 12px; font-weight:600; color:#1F2229; text-transform: uppercase;">Invoice No. </div>
                            <div style="font-size: 10px; font-weight:400; color:#828691; text-transform: uppercase; margin-top: -4px; margin-bottom: 7px;">{{$sales[0]->invoice_no}}</div>
                        </div>
                        <div class="d-flex flex-column">
                            <div style="font-size: 12px; font-weight:600; color:#1F2229; text-transform: uppercase;">Invoice Date </div>
                            <div style="font-size: 10px; font-weight:400; color:#828691; text-transform: uppercase; margin-top: -4px;">{{date('F j, Y', strtotime($sales[0]->sale_date)) }}</div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">DESCRPTION</th>
                            <th scope="col">QUANTITY</th>
                            <th scope="col">UNIT PRICE</th>
                            <th scope="col" class="text-end">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $total = 0;
                        ?>
                        @foreach($sales as $sale)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$sale->product_name}}</td>
                            <td>{{$sale->qty}}</td>
                            <td>{{$sale->unit_price}}</td>
                            <td class="text-end">{{$sale->qty * $sale->unit_price}}</td>
                        </tr>
                        @php
                        $total += $sale->qty * $sale->unit_price;
                        @endphp
                        @endforeach
                        <tr>
                            <td style="border: none;"></td>
                            <td style="border: none;"></td>
                            <td style="border: none;"></td>
                            <td style=" color:#9DA8BB; font-size:10px;font-weight:600; font-family: 'Open Sans', sans-serif;">SUBTOTAL</td>
                            <td class="text-end">{{$total}}</td>
                        </tr>
                        <tr>
                            <td style="border: none;"></td>
                            <td style="border: none;"></td>
                            <td style="border: none;"></td>
                            <td style=" color:#9DA8BB; font-size:10px;font-weight:600; font-family: 'Open Sans', sans-serif;">DISCOUNT 0%</td>
                            <td class="text-end">0</td>
                        </tr>
                        <tr>
                            <td style="border: none;"></td>
                            <td style="border: none;"></td>
                            <td style="border: none;"></td>
                            <td style="color:#1F2229; font-size:10px;font-weight:600; font-family: 'Open Sans', sans-serif;">TOTAL</td>
                            <td class="text-end" style="color:#0099FF; font-size:12px;font-weight:600; font-family: 'Open Sans', sans-serif;">{{$total}}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div class="row" style="margin-bottom: 0px;">
                <p style="font-size: 12px; font-weight:600; color:#1F2229; text-transform: uppercase; font-family: 'Open Sans', sans-serif;">NOTES</p>
                <p style="font-size: 10px;font-weight:400; color:#828691;">All amounts are in dollars. Please make the payment within 15 days from the issue of date of this invoice. Tax is not charged on the basis of paragraph 1 of Article 94 of the Value Added Tax Act (I am not liable for VAT).</p>
                <p style="font-size: 10px;font-weight:400; color:#828691; margin-bottom: 0px;">Thank you for you confidence in my work. <br>
                    Signiture</p>
            </div>
        </div>
        <div class="col-3"></div>
    </div>
</div>




@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $("#print").click(function() {
            window.print();
        });

    });
</script>
@endsection