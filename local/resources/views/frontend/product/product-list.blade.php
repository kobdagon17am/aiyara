@extends('frontend.layouts.customer.customer_app')
@section('css')

@endsection
@section('conten')

<!-- Breadcombssss  area Start-->

@if($type!=6)
<div class="card">
  <div class="card-block" style="padding: 10px">
    <h5>@if($data['type']) {{ $data['type']->orders_type }} @else ไม่ทราบจุดประสงค์การสั่งซื้อ @endif</h5>
    <div class="row">


      <div class="col-sm-8 col-md-8 col-lg-8">

        <div class="col-sm-10 row mt-2">
         <div class="input-group input-group-button">
          <span class="input-group-addon btn btn-warning" style="background-color: #FFB64D;" id="basic-addon11" data-toggle="modal" data-target="#large-Modal">
            <span class="">Coupon Code</span>
          </span>
          <input type="text" id="coupon_code" class="form-control" placeholder="รหัสสินค้าโปรโมชั่น" required="">
          <span class="input-group-addon btn btn-warning" style="background-color: #FFB64D;" id="basic-addon12" onclick="coupon()">
            <span class="">ใช้งาน</span>
          </span>
        </div>

        <div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Coupon Code</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="card">
                  <div class="card-header">
                   {{--  <h5>Zero Configuration</h5> --}}

                 </div>
                 <div class="card-block">
                  <div class="dt-responsive table-responsive">
                    <table id="simpletable" class="table table-striped table-bordered nowrap">
                      <thead>
                        <tr>

                         <th>Code</th>
                         <th>Detail</th>
                         {{-- <th>Start</th> --}}
                         <th>Expiry</th>
                         <th>Status</th>
                         <th>Action</th>
                       </tr>
                     </thead>
                     <tbody>
                      @foreach($coupon as $value)
                      <tr>
                        <td><span class="label label-primary"><b style="color:#000">{{ $value->promotion_code }}</b></span></td>
                        <td>{{ $value->name_thai }}</td>

                        <td><label class="label label-inverse-info-border"><b>{{ date('d/m/Y',strtotime($value->pro_edate)) }}</b></label></td>

                        <td><span class="label label-success">ใช้งานได้</span></td>
                        <td><button class="btn btn-success btn-sm"  onclick="coupon('{{$value->promotion_code}}')"><i class="icofont icofont-check-circled"></i> ใช้งาน </button></td>
                      </tr>
                      @endforeach


                    </tbody>

                  </table>

                </div>
                <span class="text-danger" style="font-size: 12px">*รหัสโปรโมชั่นละ 1 ชุด สามารถส่งต่อให้สมาชิกท่านอื่นๆได้ / ไม่สามารถใช้สิทธิ์กับรายการส่งเสริมการขายอื่นๆ รวมถึงการเติม Ai-Stockist</span>
              </div>
            </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
            {{-- <a href="{{ route('profile')}}" class="btn btn-primary waves-effect waves-light ">Coupon All</a> --}}
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="row">
    <div class="card-block button-list" style="padding: 7px;margin-left: 10px;">
     @foreach($categories as $value)
     <button type="button" onclick="select_category('{{ $value->category_id }}')" class="btn btn-primary btn-sm btn-outline-primary"><font style="color:#000">{{$value->category_name}}</font>
      {{-- <span class="badge">90</span> --}}
    </button>
    @endforeach
  </div>
</div>
</div>

<div class="col-sm-4 col-md-4 col-lg-4">
   @if($type==1)
      <div class="card bg-c-green order-card m-b-0">
        <div class="card-block">
          <div class="row">
            <div class="col-md-5">
             <h5 class="m-b-20" style="color: #000">คะแนนสะสม</h5>

           </div>
           <div class="col-md-7">
            <h3 class="text-right" style="color: #000" >{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->pv) }} PV</span></h3>
          </div>
        </div>
        <?php
        $customer_data = \App\Helpers\Frontend::get_customer(Auth::guard('c_user')->user()->user_name);

        ?>
        <p class="m-b-0" style="font-size: 16px"><b class="f-right"><i class="fa fa-star p-2 m-b-0"></i> {{ $customer_data->qualification_name }}</b></p>
      </div>
    </div>
    @elseif($type==2)
    <div class="card bg-c-yellow order-card m-b-0">
      <div class="card-block">
        <div class="row">
          <div class="col-md-6">
           <h5 class="m-b-10" style="color: #000">คงเหลือ</h5>

         </div>
         <div class="col-md-6">
          <h3 class="text-right" style="color: #000">{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->pv_mt) }} PV</span></h3>
        </div>
      </div>

      <p style="font-size: 15px;color: #000;">สถานะรักษาคุณสมบัติรายเดือนของคุณ </p>

      @if(empty(Auth::guard('c_user')->user()->pv_mt_active) || (strtotime(Auth::guard('c_user')->user()->pv_mt_active) < strtotime(date('Ymd')) ))
      <p class="m-b-0"><span class="label label-danger" data-toggle="tooltip" data-placement="right" data-original-title="{{ date('d/m/Y',strtotime(Auth::guard('c_user')->user()->pv_mt_active)) }}" style="font-size: 14px">Not Active </span>  </p>
      @else

      <p class="m-b-0"><span class="label label-success" style="font-size: 14px">Active ถึง {{ date('d/m/Y',strtotime(Auth::guard('c_user')->user()->pv_mt_active)) }} </span>  </p>
      @endif


      {{-- <p class="m-b-0" style="color: #000">Active ถึง 14/09/2020</p> --}}
    </div>
  </div>
  @elseif($type==3)
  <div class="card bg-c-yellow order-card m-b-0">
    <div class="card-block">
      <div class="row">
        <div class="col-md-6">
         <h5 class="m-b-10" style="color: #000">คงเหลือ</h5>

       </div>
       <div class="col-md-6">
        <h3 class="text-right" style="color: #000"> <span>{{ number_format(Auth::guard('c_user')->user()->pv_tv) }} PV</span></h3>
      </div>
    </div>

    <p style="font-size: 15px;color: #000;">สถานะรักษาคุณสมบัติท่องเที่ยวของคุณ </p>

    <?php
    $pv_tv_active = Auth::guard('c_user')->user()->pv_tv_active;
    if(!empty($pv_tv_active)){
      $pv_tv_active = date('d/m/Y',strtotime(Auth::guard('c_user')->user()->pv_tv_active));
    }else{
      $pv_tv_active = '';

    }

    ?>

    @if(empty(Auth::guard('c_user')->user()->pv_tv_active) || (strtotime(Auth::guard('c_user')->user()->pv_tv_active) < strtotime(date('Ymd')) ))
    <p class="m-b-0"><span class="label label-danger" data-toggle="tooltip" data-placement="right" data-original-title="{{ $pv_tv_active }}" style="font-size: 14px">Not Active </span>  </p>
    @else

    <p class="m-b-0"><span class="label label-success" style="font-size: 14px">Active ถึง {{ date('d/m/Y',strtotime(Auth::guard('c_user')->user()->pv_tv_active)) }} </span>  </p>
    @endif
  </div>
</div>
@elseif($type==4)
<div class="card bg-c-blue order-card m-b-0">
  <div class="card-block">
    <div class="row">
      <div class="col-md-5">
        <h5 class="m-b-20" style="color: #000">Ai-Stockist</h5>


      </div>
      <div class="col-md-7">
       <h3 class="text-right" style="color: #000" >{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->pv_aistockist) }} PV</span></h3>
     </div>
   </div>

   <p class="m-b-0" {{-- style="color:#000" --}}>จำนวนคะแนนคงเหลือ</p>
 </div>
</div>
@elseif($type==5)
<div class="card bg-c-pink order-card m-b-0">
  <div class="card-block">
    <div class="row">
      <div class="col-md-6">
       <h6 class="m-b-10" style="font-size: 16px"> Ai Voucher </h6>

     </div>
     <div class="col-md-6">
      <?php
      $gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->user_name);
      ?>
      <h3 class="text-right">{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format($gv->sum_gv) }} </span></h3>
    </div>
  </div>

  <p class="m-b-0">จำนวน Ai Voucher คงเหลือ</p>
</div>
</div>
@elseif($type==6)

<div class="card bg-c-green order-card m-b-0">
  <div class="card-block">
    <div class="row">
      <div class="col-md-5">
       <h5 class="m-b-20" style="color: #000">คะแนนสะสม</h5>
     </div>
     <div class="col-md-7">
      <h3 class="text-right" style="color: #000" >{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->pv) }} PV</span></h3>
    </div>
  </div>

  <p class="m-b-0" style="font-size: 16px"><b class="f-right"><i class="fa fa-star p-2 m-b-0"></i>  BRONZE STAR AWARD ( BSA )</b></p>
</div>
</div>

@endif
</div>

</div>

</div>
</div>


@endif
<div class="page-header card">
 <div class="card-block">

  <div class="row m-t-5" id="product_list">
    @if($type != 6)
    @foreach($data['product'] as $value)
    <div class="col-xl-3 col-md-3 col-sm-6 col-xs-6" >
      <input type="hidden" id="item_id" value="{{$value->products_id}}">
      <div class="card prod-view">
        <div class="prod-item text-center">
          <div class="prod-img">
            <div class="option-hover">

             <a href="{{route('product-detail',['type'=>$type,'id'=>$value->products_id])}}" type="button"
              class="btn btn-success btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon"> <i class="icofont icofont-cart-alt f-20"></i></a>
              <a href="{{route('product-detail',['type'=>$type,'id'=>$value->products_id])}}"
                class="btn btn-primary btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon">
                <i class="icofont icofont-eye-alt f-20"></i>
              </a>
                        <!-- <button type="button" class="btn btn-danger btn-icon waves-effect waves-light hvr-bounce-in option-icon">
              <i class="icofont icofont-heart-alt f-20"></i>
            </button> -->
          </div>
          <a href="#!" class="hvr-shrink">
            <img src="{{asset($value->img_url.''.$value->product_img)}}" class="img-fluid o-hidden" alt="">
          </a>
          <!-- <div class="p-new"><a href=""> New </a></div> -->
        </div>
        <div class="prod-info">
          <a href="{{route('product-detail',['type'=>$type,'id'=>$value->products_id])}}" class="txt-muted">
            <h5 style="font-size: 15px">{{$value->product_name}}</h5>
            <p class="text-left p-2 m-b-0" style="font-size: 12px">{!!$value->title!!}</p>

          </a>
        <!--<div class="m-b-10">
        <label class="label label-success">3.5 <i class="fa fa-star"></i></label><a class="text-muted f-w-600">14 Ratings &amp;  3 Reviews</a>
      </div> -->

      <span class="prod-price" style="font-size: 20px"> {!! $value->icon !!} {{number_format($value->member_price,2)}} <b
        style="color:#00c454">@if($type==5)[0 PV]@else[{{$value->pv}} PV]@endif</b></span>
      </div>
    </div>
  </div>
</div>
@endforeach
@else
@foreach($data['couse_event'] as $value)
<div class="col-xl-3 col-md-3 col-sm-6 col-xs-6" >
  <input type="hidden" id="item_id" value="{{$value->id}}">
  <div class="card prod-view">
    <div class="prod-item text-center">
      <div class="prod-img">
        <div class="option-hover">

         <a href="{{route('product-detail',['type'=>$type,'id'=>$value->id])}}" type="button"
          class="btn btn-success btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon"> <i class="icofont icofont-cart-alt f-20"></i></a>
          <a href="{{route('product-detail',['type'=>$type,'id'=>$value->id])}}"
            class="btn btn-primary btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon">
            <i class="icofont icofont-eye-alt f-20"></i>
          </a>
                        <!-- <button type="button" class="btn btn-danger btn-icon waves-effect waves-light hvr-bounce-in option-icon">
              <i class="icofont icofont-heart-alt f-20"></i>
            </button> -->
          </div>
          <a href="#!" class="hvr-shrink">
            <img src="{{asset($value->img_url.''.$value->img_name)}}" class="img-fluid o-hidden" alt="">
          </a>
          <!-- <div class="p-new"><a href=""> New </a></div> -->
        </div>
        <div class="prod-info">
          <a href="{{route('product-detail',['type'=>$type,'id'=>$value->id])}}" class="txt-muted">
            <h5 style="font-size: 15px">{{$value->ce_name}}</h5>
            {{-- <p class="text-left p-2 m-b-0" style="font-size: 12px">{!!$value->ce_place!!}</p>
            --}}
          </a>
        <!--<div class="m-b-10">
        <label class="label label-success">3.5 <i class="fa fa-star"></i></label><a class="text-muted f-w-600">14 Ratings &amp;  3 Reviews</a>
      </div> -->

      <span class="prod-price" style="font-size: 20px"> @if($type==6) @else {!! $value->icon !!} @endif {{number_format($value->ce_ticket_price,2)}} <b
        style="color:#00c454">@if($type==5)[0 PV]
        @elseif( $type==6 )
        [{{$value->pv}} PV]
        @else [{{$value->pv}} PV]@endif</b></span>
      </div>
    </div>
  </div>
</div>
@endforeach
@endif
        {{-- <div class="row justify-content-end">
        <div class="col-ml-12">

        </div>
        {!! $product->links(); !!}
      </div> --}}
    </div>
  </div>
</div>




@endsection

@section('js')
<script type="text/javascript">
  function select_category(category_id){
    var category = category_id;

    $.ajax({
      url: '{{route('product_list_select')}}',
      type: 'GET',
      data: {'category_id':category,'type':'{{$type}}'},
    })
    .done(function(data){
      $('#product_list').html(data);
      $('#coupon_code').val('');
      // console.log("success");
    })
    .fail(function() {
      $('#coupon_code').val('');
      console.log("error");
    })
    .always(function() {
      $('#coupon_code').val('');
      //console.log("complete");
    });
  }

  function coupon(code=''){
    if(code != ''){
       //var coupon_code = "'"+code+"'";
       var coupon_code = code;
       $('#coupon_code').val(coupon_code);
       $("#large-Modal").modal('hide');
     }else{
       var coupon_code = $('#coupon_code').val();
     }


     if(coupon_code == ''){
      Swal.fire({
        icon: 'error',
        title: 'กรุณาใส่ Coupon Code',
              // text: 'Something went wrong!',
              // footer: '<a href>Why do I have this issue?</a>'
            })

    }else{

     $.ajax({
      url: '{{route('coupon')}}',
      type: 'GET',
      data: {'coupon_code':coupon_code,'type':'{{$type}}'},
    })
     .done(function(data){

      if(data['status'] == 'fail'){
       Swal.fire({
        icon: 'error',
        text: data['massage'],
              // text: 'Something went wrong!',
              // footer: '<a href>Why do I have this issue?</a>'
            })

     }else {
       $('#product_list').html(data['html']['html']);
     }

    })
     .fail(function() {
      console.log("error");
    });
   }

 }

</script>
@endsection
