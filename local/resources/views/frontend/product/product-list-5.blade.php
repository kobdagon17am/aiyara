@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')
@endsection

<!-- Breadcomb area Start-->
<div class="page-header card">
    <div class="card-block">
        <div class="form-group row">

        {{-- <form action="{{route('product-list-1_c_id')}}" method="post">
            @csrf
            
        </form> --}}

        <div class="col-sm-4">
          <label>หมวดสินค้า </label>
          <select class="form-control" id="category" name="category" onchange="select_category()">
            @foreach($category as $value)
            <option value="{{$value->category_id}}">{{$value->category_name}}</option>
            @endforeach
        </select>
    </div>  
{{-- 
    <div class="col-sm-4">
        <label>เลือกประเภทสินค้าด้านล้าง</label>
        <select class="form-control" name="">
            @foreach($categories as $value)
            <option value="{{$value->category_id}}">{{$value->product_type}}</option>
            @endforeach
        </select>
    </div> --}}

    <div class="col-sm-4">
        <label>รหัสสินค้าโปรโมชั่น</label>
        <input type="text" class="form-control form-control-warning" placeholder="รหัสสินค้าโปรโมชั่น" name="">
    </div>

</div>

</div>
</div>
<div class="row" id="product_list">
    @foreach($product as $value)

    <div class="col-xl-3 col-md-3 col-sm-6 col-xs-6" >
      <input type="hidden" id="item_id" value="{{$value->id}}">
      <div class="card prod-view">
        <div class="prod-item text-center">
            <div class="prod-img">
                <div class="option-hover">

                   <a href="{{route('product-detail-5',['id'=>$value->id])}}" type="button" 
                    class="btn btn-success btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon"> <i class="icofont icofont-cart-alt f-20"></i></a>
                    <a href="{{route('product-detail-5',['id'=>$value->id])}}"
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
    <a href="{{route('product-detail-5',['id'=>$value->id])}}" class="txt-muted">
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
{{-- <div class="row justify-content-end">
    <div class="col-ml-12">
        
    </div>
    {!! $product->links(); !!}
</div> --}}
</div>

@endsection
@section('js')
<script type="text/javascript">
    function select_category(){
        var category = $('#category').val();
        $.ajax({
            url: '{{route('product_list_1_select_c')}}',
            type: 'GET',
            data: {'category_id':category},
        })
        .done(function(data){
            $('#product_list').html(data);
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    }
</script>
@endsection
