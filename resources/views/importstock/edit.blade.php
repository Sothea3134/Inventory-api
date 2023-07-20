@extends('layouts.layout')
@section('title'){{'Import Edit'}}@endsection
@section('css')
<style>
    INPUT:not(:autofill),
    SELECT:not(:autofill),
    TEXTAREA:not(:autofill) {
        border: none;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #e3e4e8;
    }

    .form-select:focus {
        border-color: #ffffff;
        outline: 0;
        box-shadow: none;
    }

    input {
        width: 200px;
        height: 40px;
        border: none;

    }

    input[type="text"] {
        font-size: 15px;
        color: #909090;
        border: none;
    }

    input[type="text"]:focus {
        font-size: 15px;
        color: #909090;
        border: none;
    }

    label {
        text-align: end;
        color: #434343;
        border: none;

    }

    input[type="radio"] {
        border: 1px solid #909090;

    }

    input[type="search"]::placeholder {
        color: #BBB9B9;
        border: none;
    }

    .import-text-color {
        color: #9E9E9E;
    }

    .import-text-color-body {
        color: #808080;
    }

    .import-font-12 {
        font-size: 12px;
        font-weight: 400;
    }

    .import-text-color-body {
        color: #777777;
        font: 14px;
        border-radius: 0px;
        font-weight: 400;
    }

    .label-font {
        font-size: 14px;
        font-weight: 400;
        color: #000000;
    }

    .font-require {
        font-size: 10px;
    }

    .box {
        background-color: #ffffff;
    }

    .card-image {
        width: 60px;
        padding: 10px;
    }

    .image-product {
        max-width: 100%;
        height: 40px;
        object-fit: cover;
    }

    tr:hover {
        background-color: #e3e4e8;
        cursor: default;
    }
</style>
@endsection
@section('content')
<div class="container-fluid mt-3">
    <div class="container">
        <div class="row">
            <p style="font-size: 15px; margin-left:35px;"><span style="color:#4EA6D7;">Import Stock</span><span style="color: #B8B8B8; margin: 0 10px;">/</span><span style="color: #B8B8B8;"> Edit Import</span></p>
        </div>
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                @if(Session::has('success'))
                <div role="alert" class="position-fixed" style="color:#0000ff;">
                    {{Session('success')}}
                </div>
                @endif
                <div role="alert" class="position-fixed" style="color:rgb(232, 87, 87);">
                    <div class="status_error"></div>
                </div>
            </div>
            <div class="col-4"></div>
        </div>
    </div>

</div>
<div class="container mt-5 position-relative">
    <form action="{{url('import/'.$import->id)}}" method="POST">
        @method('put')
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <div class="mb-3 row">
                    <label for="name" class="col-sm-4 col-form-label label-font" id="description">Description:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="description" value="{{$import->description}}" required>

                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="name" class="col-sm-4 col-form-labe label-font">Date:</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" data-date-format="DD/MM/YYYY" name="date" style="border: none;font-size: 14px;font-weight: 400;color: #B5B2B2; " id="date" value="{{$import->date}}" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="name" class="col-sm-4 col-form-labe label-font">Status:</label>
                    <div class="col-sm-8">
                        <select class="form-select" style="font-size: 14px;font-weight: 400;color: #555; border-color: #ffffff" name="status" id="status">
                            <option @if($import->status == 'Received') selected @endif value="Received" >Received</option>
                            <option @if($import->status == 'Draft') selected @endif value="Draft">Draft</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-8">
                        <button id="save" type="submit" class="btn btn-primary" style="background-color: #1F5A92; text-transform: none;  border-radius: 0; width: 160px;height: 43px;"> <span style="font-size: 14px; font-weight:400; color:#ffffff"> Save</span></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="d-flex " style="background-color: #FAFAFA;">
                        <i class="fas fa-search" style="  margin-top: 15px; color:#B5B2B2; font-size:14px;"></i>
                        <input type="search" class="form-control search" id="search" placeholder="Search product by name and barode." style=" color:#B5B2B2 ; border: none; font-size:14px; font-weight:400;">
                    </div>
                </div>
                <div class="row">
                    <table id="product-list" class=" py-1  box shadow  rounded position-absolute " style="width: 250px; display: none;">
                    </table>
                </div>
                <div class="row">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="import-text-color import-font-12">#</th>
                                <th scope="col" class="import-text-color import-font-12">Product</th>
                                <th scope="col" class="import-text-color import-font-12">Barcode</th>
                                <th scope="col" class="import-text-color import-font-12">Quantity</th>
                                <th scope="col" class="import-text-color import-font-12">Unit Price</th>
                                <th scope="col" class="text-center import-text-color import-font-12"></th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <input type="hidden" value="{{$import->id}}" id="import_id">

                        </tbody>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        var index = 0;
        $(document).on('keyup', '#search', function() {
            let value = $(this).val().toLowerCase();
            if (value) {
                $("#product-list").css('display', '');
                $("#product-list tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            } else {
                $("#product-list").css('display', 'none');
            }


        });
        $("#search").focus(function() {
            let value = $(this).val().toLowerCase();
            if (value) {
                $("#product-list").css('display', '');
                $("#product-list tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            } else {
                $("#product-list").css('display', 'none');
            }


        });
        $("#search").focusout(function() {
            setTimeout(function() {
                $("#product-list").css("display", "none");
            }, 220);
        });
        $(document).on('click', '.tr-search', function(e) {
            e.preventDefault();
            const idBarcode = $(this).attr('id');
            const searchBarcode = $("#search-barcode" + idBarcode).text().toLowerCase();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "search",
                data: {
                    searchBarcode: searchBarcode
                },
                dataType: 'json',
                success: function(response) {
                    var rowCount = $('.row-append').length;
                    if (rowCount == 0) {
                        index++;
                        var table = '<tr id="row' + index + '" style="background-color: #FFFFFF; border-left-width:1px;border-right-width:1px; " class="row-append">\
                                            <input type="hidden" name="import_details[' + index + '][product_id]" value=" ' + response.searchBarcode.id + '">\
                                            <td class="import-text-color-body">\
                                                <div style=" width:14px;height:14px; border-radius: 100%; margin-top:9px; border:1px solid #C4C4C4;"></div>\
                                            </td>\
                                            <td class="import-text-color-body" >\
                                                <input type="text" value="' + response.searchBarcode.name + '" name="name[]" disabled id="name"  style="width: 165px; height:30px; background-color:#FFFFFF;"class="form-control" >\
                                            </td>\
                                            <td class="import-text-color-body">\
                                                <input type="text" value="' + response.searchBarcode.barcode + '" name="barcode[]" disabled id="barcode"  style="width: 112px; height:30px; background-color:#FFFFFF;"class="form-control">\
                                            </td>\
                                            <td class="import-text-color-body">\
                                                <input type="number" required value="{{old("import_details[' + index + '][qty]")}}" name="import_details[' + index + '][qty]" style="width: 77px; height:30px; border:1px solid #E0E0E0; border-radius: 0px; background-color:FFFFFF;text-align:center;" class="form-control">\
                                            </td>\
                                            <td class="import-text-color-body">\
                                                <input type="number" required value="{{old("import_details[' + index + '][unit_price]")}}" name="import_details[' + index + '][unit_price]" style="width: 77px; height:30px; border:1px solid #E0E0E0; border-radius: 0px; background-color:#FFFFFF; text-align:center;" class="form-control">\
                                            </td>\
                                            <td class="import-text-color-body">\
                                                <button type="button" class="btn btn-primary remove_row" id="' + index + '" style="background-color: #E85757; text-transform: none;  border-radius: 0; width: 90px;height:30px; padding:0px;"><span>Remove</span></button>\
                                            </td>\
                                            </tr>';
                        $('tbody').append(table);

                        $("#product-list").css('display', 'none');

                    } else {
                        const barcodes = $("td").children('#barcode');
                        let isFound = false;
                        for (let j = 0; j < barcodes.length; j++) {
                            if (barcodes[j].getAttribute('value').toLowerCase() == searchBarcode) {
                                isFound = true;
                                $("#product-list").css('display', 'none');
                                swal({
                                    title: 'Warning',
                                    text: 'You already import this product.',
                                    icon: 'warning',
                                    buttons: "Ok",
                                })
                            }
                        }
                        if (!isFound) {
                            index++;
                            var table = '<tr id="row' + index + '" style="background-color: #FFFFFF; border-left-width:1px;border-right-width:1px; " class="row-append">\
                                            <input type="hidden" name="import_details[' + index + '][product_id]" value=" ' + response.searchBarcode.id + '">\
                                            <td class="import-text-color-body">\
                                                  <div style=" width:14px;height:14px; border-radius: 100%; margin-top:9px; border:1px solid #C4C4C4;"></div>\
                                            </td>\
                                            <td class="import-text-color-body" >\
                                                <input type="text" value="' + response.searchBarcode.name + '" name="name[]" disabled id="name"  style="width: 165px; height:30px; background-color:#FFFFFF;"class="form-control">\
                                            </td>\
                                            <td class="import-text-color-body">\
                                                <input type="text" value="' + response.searchBarcode.barcode + '" name="barcode[]" disabled id="barcode"  style="width: 112px; height:30px; background-color:#FFFFFF;"class="form-control">\
                                            </td>\
                                            <td class="import-text-color-body">\
                                                <input type="number"required value="{{old("qty")}}" name="import_details[' + index + '][qty]" style="width: 77px; height:30px; border:1px solid #E0E0E0; border-radius: 0px; background-color:FFFFFF;text-align:center;" class="form-control">\
                                            </td>\
                                            <td class="import-text-color-body">\
                                                <input type="number"required value="{{old("unit_price")}}" name="import_details[' + index + '][unit_price]" style="width: 77px; height:30px; border:1px solid #E0E0E0; border-radius: 0px; background-color:#FFFFFF; text-align:center;" class="form-control">\
                                            </td>\
                                            <td class="import-text-color-body">\
                                                <button type="button" class="btn btn-primary remove_row" id="' + index + '" style="background-color: #E85757; text-transform: none;  border-radius: 0; width: 90px;height:30px; padding:0px;"><span>Remove</span></button>\
                                            </td>\
                                            </tr>';
                            $('tbody').append(table);
                            $("#product-list").css('display', 'none');
                        }

                    }
                },
            });

        });
        fetchAllProduct()

        function fetchAllProduct() {
            $.ajax({
                type: 'GET',
                url: 'http://127.0.0.1:8000/fetch-import-product',
                dataType: 'json',
                success: function(response) {
                    $.each(response.products, function(key, item) {
                        index++
                        let products = response.products;
                        let tr = '<tr class="tr-search" id="' + index + '">\
                            <td style="width:85px;">\
                                <div class="card-image d-flex justify-content-center">\
                                <img src="{{asset("")}}images/' + item.image_path + '" alt=""class="image-product">\
                                </div>\
                            </td>\
                            <td>\
                                <div style="font-size: 12px; font-weight:500;color:#1F1F39;">' + item.name + ' </div>\
                                <div id="search-barcode' + index + '" style="font-size: 12px; font-weight:400;color:#B8B8D2;">' + item.barcode + '</div>\
                            </td>\
                        </tr>';
                        $('#product-list').append(tr);
                    });
                }

            });
        }

        $(document).on('click', '.remove_row', function() {

            var row_id = $(this).attr('id');
            $('#row' + row_id).remove();

        });
        fetchdata()

        function fetchdata() {
            var id = $('#import_id').val();

            $.ajax({
                type: "GET",
                url: "fetch",
                dataType: 'json',
                success: function(response) {
                    $.each(response.imports, function(key, item) {
                        index++
                        var table = '<tr id="row' + index + '" style="background-color: #FFFFFF; border-left-width:1px;border-right-width:1px; " class="row-append">\
                                            <input type="hidden" name="import_details[' + index + '][id]" value=" ' + item.id + '">\
                                            <input type="hidden" name="import_details[' + index + '][product_id]" value=" ' + item.product_id + '">\
                                            <td class="import-text-color-body">\
                                                  <div style=" width:14px;height:14px; border-radius: 100%; margin-top:9px; border:1px solid #C4C4C4;"></div>\
                                            </td>\
                                            <td class="import-text-color-body" >\
                                                <input type="text" value="' + item.name + '" name="name[]" disabled id="name"  style="width: 165px; height:30px; background-color:#FFFFFF;"class="form-control">\
                                            </td>\
                                            <td class="import-text-color-body">\
                                                <input type="text" value="' + item.barcode + '" name="barcode[]" disabled id="barcode"  style="width: 112px; height:30px; background-color:#FFFFFF;"class="form-control">\
                                            </td>\
                                            <td class="import-text-color-body">\
                                                <input type="number" required value="' + item.qty + '" name="import_details[' + index + '][qty]"  style="width: 77px; height:30px; border:1px solid #E0E0E0; border-radius: 0px; background-color:FFFFFF;text-align:center;" class="form-control">\
                                            </td>\
                                            <td class="import-text-color-body">\
                                                <input type="number" required value="' + item.unit_price + '"  name="import_details[' + index + '][unit_price]"  style="width: 77px; height:30px; border:1px solid #E0E0E0; border-radius: 0px; background-color:#FFFFFF; text-align:center;" class="form-control">\
                                            </td>\
                                            <td class="import-text-color-body">\
                                                <button type="button" class="btn btn-primary remove_row" id="' + index + '" style="background-color: #E85757; text-transform: none;  border-radius: 0; width: 90px;height:30px; padding:0px;"><span>Remove</span></button>\
                                            </td>\
                                            </tr>';
                        $('tbody').append(table);
                    });
                }
            });
        }

        $(document).on('click', '.remove_row', function() {

            var row_id = $(this).attr('id');
            $('#row' + row_id).remove();

        });
    });
    $('#save').click(function(event) {
        if ($('#status').val() == 'Draft') {
            $('.status_error').text("Please select received on field status before save");
            event.preventDefault();
        }
    });
</script>
@endsection