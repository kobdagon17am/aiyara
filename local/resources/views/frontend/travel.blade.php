@extends('frontend.layouts.customer.customer_app')
@section('css')
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/assets/pages/data-table/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

@endsection
@section('conten')
{{--
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <h4 class="m-b-10">โปรโมชั่นท่องเที่ยวท่องเที่ยว</h4>

                </div>

                <div class="card-block">
                    <div class=" table-responsive">
                        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">


                        </table>
                    </div>

                </div>


            </div>
        </div>
    </div> --}}

    <div class="page-header card">
      <div class="card-block">
        <h4> {{trans('message.p_benefits travel promotion')}} [{{ Auth::guard('c_user')->user()->user_name}}] </h4>
        <hr>
        <?php

        $user_name = Auth::guard('c_user')->user()->user_name;
        $token = hash('SHA512', date('ymd').$user_name . date('H') . 'TRAVEL' );
        $url = 'https://aismart.aiyara.co.th/v3_pro_travel/?id='.$user_name.'&token='.$token;
        ?>

        <iframe src="{{$url}}"
        style="display: block;width: 100%;height: 100vh;border: none;"></iframe>

{{--
        @if(count($data) > 0)
        <div class="row">
          <div class="col-lg-12 col-xl-12 ">
            <div class="card card-main">
              <div class="card-block">
                @foreach($data as $value)
                <div class="row mt-2">
                  <div class="col-md-3">
                    <a href="{{ route('travel_detail',['id'=>$value->id]) }}">
                      <img class="card-img-top img-fluid zoom" src="{{asset($value->img_url.''.$value->img_name)}}" alt="Card image cap">
                    </a>

                  </div>
                  <div class="col-md-9">
                    <div class="card-block p-0">
                      <a href="{{ route('travel_detail',['id'=>$value->id]) }}"><h4 class="card-title m-b-5 m-t-5">{{$value->name}} </h4></a>
                      <p class="card-text"><b class="text-primary">เริ่ม {{date('d/m/Y',strtotime($value->start_date))}} ถึง {{date('d/m/Y',strtotime($value->end_date))}}</b> </p>

                      <p>{!!$value->title!!}</p>

                      <a href="{{ route('travel_detail',['id'=>$value->id]) }}" class="btn btn-primary btn-round"><i class="fa fa-newspaper-o"></i> <b>View</b></a>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        @else
        <div class="alert alert-warning border-warning">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <i class="icofont icofont-close-line-circled"></i>
          </button>
          <strong>Data is Null! </strong>: {{trans('message.p_benefits no travel pro')}}
        </div>
        @endif --}}

      </div>

    </div>

@endsection
@section('js')
    <!-- data-table js -->


    <script src="{{ asset('frontend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/data-table/js/jszip.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/data-table/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/data-table/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('frontend/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script>

@endsection
