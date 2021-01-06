@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')
@endsection
<!-- Breadcombssss  area Start-->
<div class="page-header card">
  <div class="card-block">
    <h5 class="text-primary">@if($data['type']) {{ $data['type']->orders_type }} @else ไม่ทราบจุดประสงค์การสั่งซื้อ @endif</h5>
    <hr>

    @foreach($data['couse_event'] as $value)
    <div class="row">
      <div class="col-lg-12 col-xl-12 ">
        <div class="card card-main">
          <div class="card-block">
            <div class="row ">
              <div class="col-md-3">
                <a href="{{route('product-detail',['type'=>$type,'id'=>$value->id])}}">
                  <img class="card-img-top img-fluid zoom" src="{{asset($value->img_url.''.$value->img_name)}}" alt="Card image cap">
                </a>
              </div>
              <div class="col-md-9">
                <div class="card-block p-0">
                  <a href="{{route('product-detail',['type'=>$type,'id'=>$value->id])}}"><h4 class="card-title m-b-5">{{$value->ce_name}} </h4></a>

                  {!! $value->title !!}
                  <p class="card-text"><b class="text-success">เริ่ม {{date('d/m/Y',strtotime($value->ce_sdate))}} ถึง {{date('d/m/Y',strtotime($value->ce_edate))}}</b> <span class="label label-success" style="font-size: 12px"><i class="fa fa-users"></i> 15/{{$value->ce_max_ticket}} </span></p>
                  <p> <a href="{{route('product-detail',['type'=>$type,'id'=>$value->id])}}"><button class="btn btn-primary btn-round"><i class="ti-shopping-cart-full"></i> ลงทะเบียน</button></a></p>

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
