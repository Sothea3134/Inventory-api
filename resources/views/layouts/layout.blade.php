<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title')</title>
    <!-- CDN Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- Link Css File -->
    <link rel="stylesheet" href="{{asset('css/navigation.css')}}">
    @yield('css')

</head>

<body>
    <div class="nav-color" id="nav">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-4 text-start  mt-3"><a class="navbar-brand text-color" href="#">Inventory Management</a></div>
                <div class="col-sm-4 text-center  mt-3">
                    <div class="d-flex justity-content-space-around search-bar" style="background-color: #466B8E;">
                        <input type="search" class="form-control " placeholder=" Search" style=" color:#FFFFFF; border: none; background-color: #466B8E; font-size:13px; height:39px;">
                        <i class="fas fa-search" style="  margin-top: 14px; color:#FFFFFF; font-size:13px; margin-right:10px"></i>
                    </div>
                    <div id="datatable">
                    </div>
                </div>

                <div class="col-sm-4 mt-2">
                    <div class=" d-flex justify-content-end">
                        <div class="p-2 text-color mt-1">
                            <div class="notification d-flex justify-content-center align-items-center"> <i class="fas fa-plus" style="font-size: 10px;"></i></div>
                        </div>
                        <div class="p-2 text-color mt-1">
                            <div class="notification d-flex justify-content-center align-items-center"> <i class="far fa-bell" style="font-size: 14px;"></i></div>
                        </div>
                        <div class="p-2 text-color">
                            <div class='profile'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg" style="box-shadow: none;">
                <!-- Container wrapper -->
                <div class="container-fluid">
                    <!-- Toggle button -->
                    <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Collapsible wrapper -->
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left links -->
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 " id="navbar">
                            <li class="nav-item">
                                <a class="nav-link text-nav {{$active['dashboard']}} " href="/">Dashboard</a>
                            </li>
                            <li class="nav-item ml-18 ">
                                <a class="nav-link text-nav {{$active['product']}} pl-100 " href="{{url('product')}}">Product</a>
                            </li>
                            <li class="nav-item ml-18">
                                <a class="nav-link text-nav {{$active['import']}}" href="{{url('import')}}">Import Stock</a>
                            </li>
                            <li class="nav-item ml-18">
                                <a class="nav-link text-nav {{$active['customer']}}" href="{{url('customer')}}">Customer</a>
                            </li>
                            <li class="nav-item ml-18">
                                <a class="nav-link text-nav {{$active['sales']}}" href="{{url('sales')}}">Sales</a>
                            </li>
                            <li class="nav-item dropdown ml-18">
                                <a class="nav-link dropdown-toggle text-nav  {{$active['reports']}}" id="navbarDropdownMenuLink1" role="button" data-mdb-toggle="dropdown" aria-expanded="false">Reports</a>
                                <ul class="dropdown-menu text-color mt-2" aria-labelledby="navbarDropdownMenuLink1">
                                    <li>
                                        <a class="dropdown-item sub-munu-color" href="{{url('reports/product-imports')}}">Product Import Report</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item sub-munu-color" href="{{url('reports/product-sales')}}">Product Sale Report</a>
                                    </li>

                                </ul>
                            </li>
                            <li class="nav-item ml-18">
                                <a class="nav-link text-nav  {{$active['user-management']}}" href="{{url('user-management')}}">User Management</a>
                            </li>
                            <!-- Dropdown -->
                            <li class="nav-item dropdown text-color ml-18">
                                <a class="nav-link dropdown-toggle text-nav " href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                                    More
                                </a>
                                <ul class="dropdown-menu text-color" aria-labelledby="navbarDropdownMenuLink">
                                    <li>
                                        <a class="dropdown-item sub-munu-color" href="#">Action</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item sub-munu-color" href="#">Another action</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item sub-munu-color" href="#">Something else here</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <!-- Left links -->
                    </div>
                </div>
                <!-- Container wrapper -->
            </nav>
            <!-- Navbar -->
        </div>
    </div>
    @yield('content')


    <!-- Link Js File -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.js"></script>

    @yield('js')

</body>

</html>