@extends('backend.template.layouts.master-without-nav')

@section('title')
404 Error Page
@endsection

@section('body')
<body>
@endsection

@section('content')

        <div class="account-pages my-5 ">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center ">
                            <h1 class="display-2 font-weight-medium">4<i class="bx bx-buoy bx-spin text-primary display-3"></i>4</h1>
                            <!-- <h4 class="text-uppercase">Sorry, page not found </h4> -->
                            <h4 class="text-uppercase"><< ขออภัย อยู่ระหว่างการดำเนินการ >></h4>
                            <div class=" text-center">
                                <!-- <a class="btn btn-primary waves-effect waves-light" href="backend/template/index">Back to Dashboard</a> -->
                                <a class="btn btn-primary waves-effect waves-light" href="backend/index">Back to main menu</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8 col-xl-5 pt-5">
                        <div>
                            <!-- <img src="backend/images/error-img.png" alt="" class="img-fluid"> -->
                            <!-- <img src="backend/images/verification-img.png" alt="" class="img-fluid"> -->
                            <img src="backend/images/maintenance.png" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
