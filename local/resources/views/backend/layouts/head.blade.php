
        @yield('css')

        <!-- App css -->
        <link href="{{ URL::asset('backend/css/bootstrap-dark.min.css')}}" id="bootstrap-dark" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('backend/css/bootstrap.min.css')}}" id="bootstrap-light" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('backend/css/app-rtl.min.css')}}" id="app-rtl" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('backend/css/app-dark.min.css')}}" id="app-dark" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('backend/css/app.min.css')}}" id="app-light" rel="stylesheet" type="text/css" />
        <!-- DataTables -->
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/libs/datatables/datatables.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/libs/toastr/toastr.min.css')}}">

        <link href="{{ URL::asset('backend/css/custom.css')}}" id="app-light" rel="stylesheet" type="text/css" />


        <style type="text/css" media="screen">
                .myBorder {
                        border: 2px solid #00ace6;border-radius: 5px;border-width: thin;padding: 10px;margin-bottom: 1%;
                }
        </style>

        <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/libs/select2/select2.min.css')}}">
        <style type="text/css">
            .select2-dropdown {
               font-size: 16px;
            }
        </style>