  
@extends('frontend.layouts.customer.customer_app')
@section('css')
<style type="text/css">

    .feed-blog li {
        position: relative;
        padding-left: 31px;
        margin-bottom: 24px;
    }
</style>


@endsection

@section('conten') 
<div class="card">
    <div class="card-header">
        <h4>รางวัลเกียรติยศ</h4>
    </div>
    <div class="card-block">
        <ul class="feed-blog">


          @foreach($data as $value)
          <?php 
          $qualification_id = Auth::guard('c_user')->user()->qualification_id;
          $customer_id = Auth::guard('c_user')->user()->id;

           ?>
          @if($value->id <= $qualification_id )
          <?php 

          $rs_check = \App\Helpers\Frontend::check_reward_history($customer_id,$value->id);
           

          ?>
            <li class="active-feed" style="margin-bottom: 25px;">
                <div class="feed-user-img">
                    <img src="{{asset('frontend/assets/images/true.png')}}" class="img-radius " alt="User-Profile-Image">
                </div> 
                <h6><span class="label label-success">Active</span> {{ $value->business_qualifications }}</h6>
                @if($rs_check)
                <p><small class="text-muted">Date {{ date('d/m/Y',strtotime($rs_check->created_at)) }}</small></p>
                @endif
            </li>
        @else
            <li class="diactive-feed" style="margin-bottom: 30px;">
                <div class="feed-user-img">
                    <img src="{{asset('frontend/assets/images/false.png')}}" class="img-radius " alt="User-Profile-Image"> 
                </div>
                <h6><span class="label label-inverse-info-border">Not Active</span> {{ $value->business_qualifications }}</h6>
            </li>

        @endif

        @endforeach

    </ul>
</div>
</div>


@endsection

@section('js')
@endsection


