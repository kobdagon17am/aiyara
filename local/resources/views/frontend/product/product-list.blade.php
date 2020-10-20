@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')
@endsection

<!-- Breadcomb area Start-->
<div class="page-header card">
    <div class="card-block">
        <div class="form-group row">
            <!--   <div class="col-sm-4">
      <label>จุดประสงค์การซื้อ </label>
      <select class="form-control" name="">
        @foreach($objective as $value)
        <option value="{{$value->ojt_id}}">{{$value->name}}</option>
        @endforeach
      </select>
  </div> -->

  <div class="col-sm-4">
    <label>เลือกประเภทสินค้าด้านล้าง</label>
    <select class="form-control" name="">
        @foreach($categories as $value)
        <option value="{{$value->category_id}}">{{$value->category_name}}</option>
        @endforeach
    </select>
</div>

<div class="col-sm-4">
    <label>รหัสสินค้าโปรโมชั่น</label>
    <input type="text" class="form-control form-control-warning" placeholder="รหัสสินค้าโปรโมชั่น" name="">
</div>

</div>

</div>
</div>

<div class="row">

    @foreach($product as $value)
    <input type="hidden" id="item_id" value="{{$value->id}}">
    <div class="col-xl-3 col-md-3 col-sm-6 col-xs-6">
        <div class="card prod-view">
            <div class="prod-item text-center">
                <div class="prod-img">
                    <div class="option-hover">

                     <a href="{{route('product-detail',['id'=>$value->id])}}" type="button" 
                        class="btn btn-success btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon"> <i class="icofont icofont-cart-alt f-20"></i></a>
                        <a href="{{route('product-detail',['id'=>$value->id])}}"
                            class="btn btn-primary btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon">
                            <i class="icofont icofont-eye-alt f-20"></i>
                        </a>
                        <!-- <button type="button" class="btn btn-danger btn-icon waves-effect waves-light hvr-bounce-in option-icon">
              <i class="icofont icofont-heart-alt f-20"></i>
          </button> -->
      </div>
      <a href="#!" class="hvr-shrink">
        @if($value->image_default == 1)
        <img src="{{asset($value->img_url.''.$value->image01)}}" class="img-fluid o-hidden" alt="">
        @elseif($value->image_default == 2)
        <img src="{{asset($value->img_url.''.$value->image02)}}" class="img-fluid o-hidden" alt="">
        @elseif($value->image_default == 3)
        <img src="{{asset($value->img_url.''.$value->image03)}}" class="img-fluid o-hidden" alt="">
        @else
        <img src="{{asset($value->img_url.''.$value->image01)}}" class="img-fluid o-hidden" alt="">
        @endif



    </a>
    <!-- <div class="p-new"><a href=""> New </a></div> -->
</div>
<div class="prod-info">
    <a href="{{route('product-detail',['id'=>$value->id])}}" class="txt-muted">
        <h5 style="font-size: 15px">{{$value->product_name}}</h5>
        <p style="margin-top: 0px;">{{$value->title}}</p>
    </a>
                    <!--           <div class="m-b-10">
            <label class="label label-success">3.5 <i class="fa fa-star"></i></label><a class="text-muted f-w-600">14 Ratings &amp;  3 Reviews</a>
        </div> -->
        <span class="prod-price"><i
            class="icofont icofont-cur-dollar"></i>฿{{number_format($value->price,2)}} <b
            style="color:#00c454">[{{$value->pv}} PV]</b></span>
        </div>
    </div>
</div>
</div>
@endforeach



</div>

<div class="row justify-content-end">
    {!! $product->links(); !!}
</div>

@endsection
@section('js')

@endsection
