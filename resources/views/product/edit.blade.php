@extends('layouts.layout')
@section('title'){{'Product Edit'}}@endsection
@section('css')
<style>
    INPUT:not(:autofill),
    SELECT:not(:autofill),
    TEXTAREA:not(:autofill) {
        outline: none;
        border: none;

    }

    .form-control:focus {
        box-shadow: none;
        border-color: #e3e4e8;
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
        color: #000000;
        font-size: 14px;
        font-weight: 400;

    }

    input[type="radio"] {
        border: 1px solid #909090;
    }

    input[type="text"] .disabled {
        border: 1px solid black;
    }

    .form-control[type=file] {
        overflow: hidden;
        border-color: #ffffff;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: gray;
        opacity: 0.4;
    }

    .label-color {
        color: #000000;
        font-size: 14px;
        font-weight: 400;
    }

    .font-require {
        font-size: 10px;
    }
</style>
@endsection
@section('content')
<div class="container mt-3">
    <div class="container-fluid ">
        <div class="row">
            <p style="font-size: 15px; margin-left:15px;"><span style="color:#4EA6D7;">Product</span><span style="color: #B8B8B8; margin: 0 10px;">/</span><span style="color: #B8B8B8;"> Edit Product</span></p>
        </div>
        <div class="row">
            <div class="col-5"></div>
            <div class="col-3">
                @if(Session::has('success'))
                <div role="alert" class="position-fixed d-flex justify-content-between" style="color:#0000ff; ">
                    {{Session('success')}}

                </div>
                @endif
                @if(Session::has('error'))
                <div role="alert" style="text-align: center; color:#ff1a1a;">
                    {{Session('error')}}

                </div>
                @endif
            </div>
            <div class="col-4"></div>
        </div>
        <div class="container mt-5">
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-5">

                    <form action="{{url('product/'.$product->id)}}" method="POST" enctype="multipart/form-data">

                        @method('put')
                        @csrf
                        <div class="container">

                            <div class="mb-3 row">
                                <label for="name" class="col-sm-4 col-form-label label-color">Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" value="{{$product->name}}">
                                    <span class="text-danger font-require position-fixed">{{$errors->first('name')}}</span>
                                </div>
                            </div>
                            <div class=" mb-3 row">
                                <label for="inputPassword" class="col-sm-4 col-form-label label-color">Barcode:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="barcode" value="{{$product->barcode}}">
                                    <span class="text-danger font-require position-fixed">{{$errors->first('barcode')}}</span>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="price" class="col-sm-4 col-form-label label-color">Price:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="price" value="{{number_format($product->price, 2, '.', ',')}}">
                                    <span class="text-danger font-require position-fixed">{{$errors->first('price')}}</span>
                                </div>
                            </div>
                            @if($product->inventory_type == 'inventory')
                            <div class=" mb-3 row">
                                <label for="inventory" class="col-sm-4 col-form-label label-color">Inventory Type:</label>
                                <div class="col-sm-8">

                                    <div class="form-check form-check-inline mt-1">
                                        <input class="form-check-input" type="radio" name="inventory_type" value="inventory" checked disabled>
                                        <label class="form-check-label" for="inventory">Inventory</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inventory_type" value="service" disabled>
                                        <label class="form-check-label" for="inventory">Service</label>
                                    </div>
                                </div>

                            </div>
                            @endif
                            @if($product->inventory_type == 'service')
                            <div class=" mb-3 row">
                                <label for="inventory" class="col-sm-4 col-form-label label-color">Inventory Type:</label>
                                <div class="col-sm-8">

                                    <div class="form-check form-check-inline mt-1">
                                        <input class="form-check-input" type="radio" name="inventory_type" value="inventory" disabled>
                                        <label class="form-check-label" for="inventory">Inventory</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inventory_type" value="service" checked disabled>
                                        <label class="form-check-label" for="inventory">Service</label>
                                    </div>
                                </div>

                            </div>
                            @endif

                            <div class="mb-3 row">
                                <label for="stock" class="col-sm-4 col-form-label label-color">Current Stock:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="stock" disabled value="{{$product->qty}}" style="color: black;" id="stock">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="stock" class="col-sm-4 col-form-label label-color">Image:</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" name="image" id="image">
                                    <span class="text-danger font-require position-fixed">{{$errors->first('image')}}</span>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="stock" class="col-sm-4 col-form-label label-color">Preview Image:</label>
                                <div class="col-sm-8">
                                    <div class="col-md-12 mb-2 ">
                                        <img id="preview-image-before-upload" src="{{asset('images/'.$product->image_path)}}" style="max-height: 70px; text-align:center;" class="position-absolute">
                                    </div>
                                </div>
                            </div>
                            <div class=" mt-5 row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-8">
                                    <button type="submit" class="btn btn-primary" style="background-color: #1F5A92; text-transform: none;  border-radius: 0; width:142px; height:42px;">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {

        $('#image').change(function() {

            let reader = new FileReader();

            reader.onload = (e) => {

                $('#preview-image-before-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(this.files[0]);

        });
    });
</script>
@endsection