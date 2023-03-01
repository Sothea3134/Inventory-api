@extends('layouts.layout')
@section('title'){{'Import List'}}@endsection
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
</style>
@endsection
@section('content')
<div class="container mt-5">
    <a class="btn btn-primary" style="background-color: #1F5A92; text-transform: none; border-radius: 0; width:145px; height:40px;" href="{{url('import/create')}}" type="button">
        <span style="font-weight: 400px; font-size:14px; color:#FFFFFF;">New Import</span>
    </a>
</div>
<div class="container" style="height: 622px;">
    <table class="table">
        <thead>
            <tr>
                <th scope="col" class="import-text-color import-font-12">Description</th>
                <th scope="col" class="import-text-color import-font-12">Import Date</th>
                <th scope="col" class="import-text-color import-font-12">Number of Product</th>
                <th scope="col" class="import-text-color import-font-12">Total</th>
                <th scope="col" class="text-center import-text-color import-font-12">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($imports as $import)
            <tr style="background-color: #FAFAFA; border-left-width:1px;border-right-width:1px;">
                <td scope="row">
                    <div class="d-flex justity-content-center">
                        <div style=" width:14px;height:14px; border-radius: 100%; margin-top:4px; border:1px solid #C4C4C4;"></div>
                        <div class="px-2 import-text-color-body">{{$import->description}}</div>

                    </div>
                </td>
                <td class="import-text-color-body">{{date('d F y',strtotime($import->date))}}</td>
                <td class="import-text-color-body">{{ $import->qty}}</td>
                <td class="import-text-color-body">{{number_format($import->total, 2, '.', ',')}}</td>
                <td>

                    @if($import->status == 'Received')
                    <div class="d-flex justify-content-evenly">
                        <div class="d-flex justify-content-evenly">
                            <div style=" background-color:  #5EDD25; width:10px;height:10px; border-radius: 100%; margin-top:6px; margin-right:5px;"></div>
                            <div class="import-text-color-body">{{$import->status}}</div>
                        </div>
                        <div class=""><i class="fas fa-ellipsis-h" style="color: #B0B0B0;" id="activity" role="button" data-mdb-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu text-color" aria-labelledby="activity">
                                <li>
                                    <a class="dropdown-item delete-confirm" href="{{$import->id}}" type="submit">Delete</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    @endif
                    @if($import->status == 'Draft')
                    <div class="d-flex justify-content-evenly">
                        <div class="d-flex ">
                            <div style=" background-color: #E65F20; width:10px;height:10px; border-radius: 100%; margin-top:6px; margin-right:5px; "> </div>
                            <div class=" import-text-color-body" style="margin-right: 30px;">{{$import->status}}</div>
                        </div>

                        <div class=""><i class="fas fa-ellipsis-h" style="color: #B0B0B0;" id="activity" role="button" data-mdb-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu text-color" aria-labelledby="activity">
                                <li>
                                    <a class="dropdown-item " href="{{url('import/'.$import->id.'/edit')}}">Update</a>
                                </li>
                                <li>
                                    <a class="dropdown-item delete-confirm" href="{{$import->id}}" type="submit">Delete</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center">
    {!! $imports->links() !!}
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

<script>
    $('.delete-confirm').on('click', function(event) {
        event.preventDefault();
        const url = $(this).attr('href');
        swal({
            title: 'Are you sure?',
            text: 'You want to delete this import.',
            icon: 'warning',
            buttons: ["Cancel", "Yes!"],
        }).then(function(value) {
            if (value) {
                $.ajax({
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: `http://127.0.0.1:8000/import/${url}`,
                    data: {
                        '_method': 'delete'
                    },
                    success: function() {
                        window.location = '/import'
                    }
                });
            }
        });
    });
</script>
@endsection