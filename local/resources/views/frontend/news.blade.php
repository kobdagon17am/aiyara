
@extends('frontend.layouts.customer.customer_app')
@section('css')
@endsection
@section('conten')

<div class="page-header card">
  <div class="card-block">
    <h5 class="text-primary"> {{trans('message.p_news/activity')}} </h5>
    <hr>

    @if(count($data) > 0)
    <div class="row">
      <div class="col-lg-12 col-xl-12 ">
        <div class="card card-main">
          <div class="card-block">
            @foreach($data as $value)
            <div class="row mt-2">
              <div class="col-md-3">
                <a href="{{ route('news_detail',['id'=>$value->id]) }}">
                  <img class="card-img-top img-fluid zoom" src="{{asset($value->img_url.''.$value->img_name)}}" alt="Card image cap">
                </a>

              </div>
              <div class="col-md-9">
                <div class="card-block p-0">
                  <a href="{{ route('news_detail',['id'=>$value->id]) }}"><h4 class="card-title m-b-5 m-t-5">{{$value->news_name}} </h4></a>
                  <p class="card-text"><b class="text-primary">เริ่ม {{date('d/m/Y',strtotime($value->start_date))}} ถึง {{date('d/m/Y',strtotime($value->end_date))}}</b> </p>

                  <p>{!!$value->title!!}</p>

                  <a href="{{ route('news_detail',['id'=>$value->id]) }}" class="btn btn-primary btn-round"><i class="fa fa-newspaper-o"></i> <b>View</b></a>
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
      <strong>Data is Null! </strong>: {{trans('message.p_news/activity no')}}
    </div>
    @endif

  </div>

</div>
@endsection
@section('js')
@endsection
