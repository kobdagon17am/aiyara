<?php
use App\Models\Frontend\CourseCheckRegis;
?>
@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')
@endsection
<!-- Breadcombssss  area Start-->
<div class="page-header card">
  <div class="card-block">
    <h5 class="text-primary">@if($data['type']) {{ $data['type']->orders_type }} @else ไม่ทราบจุดประสงค์การสั่งซื้อ
      @endif</h5>
    <hr>

    @foreach($data['couse_event'] as $value)

    <?php
    if($value->ce_type == 1){
      $type_name = 'COURSE';
    }elseif($value->ce_type == 2){
      $type_name = 'EVENT';
    }else{
      $type_name = '';
    }

    $check_status = CourseCheckRegis::check_register($value->id,Auth::guard('c_user')->user()->user_name);
    //dd($check_status['message']);
    ?>
    <div class="row">
      <div class="col-lg-12 col-xl-12 ">
        <div class="card card-main">
          <div class="card-block">
            <div class="row ">
              <div class="col-md-3">
                @if($check_status['status'] == 'success')
                <a href="{{route('product-detail',['type'=>$type,'id'=>$value->id])}}">
                  <img class="card-img-top img-fluid zoom" src="{{asset($value->img_url.''.$value->img_name)}}"
                    alt="Card image cap">
                </a>
                @else
                <img class="card-img-top img-fluid" src="{{asset($value->img_url.''.$value->img_name)}}"
                  alt="Card image cap">
                @endif

              </div>
              <div class="col-md-9">
                <div class="card-block p-0">
                  <?php $count_ce = \App\Helpers\Frontend::get_ce_register($value->id); ?>
                  @if($check_status['status'] == 'success')
                  <a href="{{route('product-detail',['type'=>$type,'id'=>$value->id])}}">
                    <h4 class="card-title m-b-5 m-t-5">{{$value->ce_name}} <span class="label label-success"
                        style="font-size: 12px"><i class="fa fa-users"></i> {{ $count_ce }}/{{$value->ce_max_ticket}}
                      </span></h4>
                  </a>
                  @else
                  <h4 class="card-title m-b-5 m-t-5">{{$value->ce_name}} <span class="label label-success"
                      style="font-size: 12px"><i class="fa fa-users"></i> {{ $count_ce }}/{{$value->ce_max_ticket}}
                    </span></h4>
                  @endif

                  {!! $value->title !!}

                  <span class="prod-price" style="font-size: 20px"> {{ number_format($value->ce_ticket_price,2)}} <b
                      style="color:#00c454">[{{number_format($value->pv)}} PV]</b></span>

                  <p class="card-text"><b class="text-primary">เริ่ม {{date('d/m/Y',strtotime($value->ce_sdate))}} ถึง
                      {{date('d/m/Y',strtotime($value->ce_edate))}}</b> </p>

                  @if($check_status['status'] == 'success')
                  <a href="{{route('product-detail',['type'=>$type,'id'=>$value->id])}}"
                    class="btn btn-primary btn-round"><i class="ti-shopping-cart-full"></i> ลงทะเบียน
                    <b>{{$type_name}}</b></a>
                  @else
                  @if($check_status['code'] == 'e01' || $check_status['code'] == 'e02' || $check_status['code'] ==
                  'e03')
                  <button class="btn btn-inverse btn-round" data-toggle="tooltip" data-placement="right" title=""
                    data-original-title="{{ $check_status['message'] }}"><i class="ti-shopping-cart-full"></i>
                    ใช้สิทธิ์ลงทะเบียน <b>{{$type_name}}</b> ครบแล้ว</button>
                  @else
                  <button class="btn btn-inverse btn-round" data-toggle="tooltip" data-placement="right" title=""
                    data-original-title="{{ $check_status['message'] }}"><i class="ti-shopping-cart-full"></i>
                    เงื่อนไขลงทะเบียน <b>{{$type_name}}</b> ไม่ครบ </button>
                  @endif

                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection
@section('js')
@endsection
