
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

<style>

    .myloading {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%);
        -moz-transform: translate(-50%, -50%);
        -o-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        z-index: 100;

        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid blue;
        border-right: 16px solid green;
        border-bottom: 16px solid red;
        border-left: 16px solid pink;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;

      }

      /* Safari */
      @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
      }

      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
      
</style>