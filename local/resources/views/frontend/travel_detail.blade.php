
@extends('frontend.layouts.customer.customer_app')
@section('css')
@endsection
@section('conten')

<div class="page-header card">
 <div class="card-block">
 <a href="{{ route('travel') }}"><h4 class="text-primary"> โปรโมชั่นท่องเที่ยว</h4> </a>
    <hr>
  <div class="row mt-2">
    <div class="col-md-3">
        <img class="card-img-top img-fluid zoom" src="{{asset($data->img_url.''.$data->img_name)}}" alt="Card image cap">


    </div>
    <div class="col-md-9">
      <div class="card-block p-0">
         <h4 class="card-title m-b-5 m-t-5">{{$data->name}} </h4>
        <p class="card-text"><b class="text-primary">เริ่ม {{date('d/m/Y',strtotime($data->start_date))}} ถึง {{date('d/m/Y',strtotime($data->end_date))}}</b> </p>

        <p>{!!$data->detail!!}</p>

      </div>
    </div>
  </div>
</div>

</div>

</div>
@endsection
@section('js')
@endsection
