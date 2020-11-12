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

        <div class="col-lg-3 col-md-3 col-sm-6 m-t-5">
         {{--  <label>หมวดสินค้า </label> --}}
         <select class="form-control" id="category" name="category" onchange="select_category()">
            @foreach($category as $value)
            <option value="{{$value->category_id}}">{{$value->category_name}}</option>
            @endforeach
        </select>
    </div>  

    <div class="col-lg-4 col-md-4 col-sm-6 m-t-5">
        {{-- <label>รหัสสินค้าโปรโมชั่น</label> --}} 

        <div class="input-group input-group-button">
            <input type="text" class="form-control" placeholder="รหัสสินค้าโปรโมชั่น">
            <span class="input-group-addon btn btn-primary" id="check_code">
                <span class="">Check Code</span>
            </span>
        </div>
    </div>

    <div class="col-lg-5 col-md-5 col-sm-12 m-t-5">
        <button type="button" class="btn btn-warning waves-effect" data-toggle="modal" data-target="#large-Modal">Code ticket</button>

        <div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Code ticket</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                               {{--  <h5>Zero Configuration</h5> --}}
                               <span class="text-danger">*รหัสโปรโมชั่นละ 1 ชุด สามารถส่งต่อให้สมาชิกท่านอื่นๆได้ / ไม่สามารถใช้สิทธิ์กับรายการส่งเสริมการขายอื่นๆ รวมถึงการเติม AIPocket</span>
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
                                        <tr>
                                            <td><span class="label label-primary"><b style="color:#000">B1QQJTYL2</b></span></td>
                                            <td> สำหรับสั่งซื้อ Aimmura-O 5 Free 1 </td>
                                            {{-- <td>10/04/2020</td> --}}
                                            <td>12/12/2020</td>
                                            <td><span class="label label-success">ใช้งานได้</span></td>
                                            <td><button class="btn btn-success btn-sm"><i class="icofont icofont-check-circled"></i> ใช้งาน </button></td>
                                        </tr>
                                        <tr>
                                            <td><span class="label label-primary"> <b style="color:#000">B1QQJTYL2</b> </span></td>
                                            <td> สำหรับสั่งซื้อ Aimmura-O 5 Free 1 </td>
                                            {{-- <td>10/04/2020</td> --}}
                                            <td>12/12/2020</td>
                                            <td><span class="label label-danger">หมดอายุ</span></td>
                                            <td> </td>
                                        </tr>

                                        <tr>
                                            <td><span class="label label-primary"> <b style="color:#000">B1QQJTYL2</b> </span></td>
                                            <td> สำหรับสั่งซื้อ Aimmura-O 5 Free 1 </td>
                                            {{-- <td>10/04/2020</td> --}}
                                            <td>12/12/2020</td>
                                            <td><span class="label label-inverse">ถูกใช้แล้ว</span></td>
                                            <td> </td>
                                        </tr>

                                    </tbody>
                                       {{--  <tfoot>
                                            <tr>
                                                <th>Code ticket</th>
                                                <th>Detail</th>
                                                <th>Start</th>
                                                <th>Expiry</th>
                                            </tr>
                                        </tfoot> --}}
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary waves-effect waves-light ">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--     <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="row float-right m-r-10">
             

            <i class="fa fa-shopping-cart text-c-blue d-block f-50"></i> <b class="text-c-blue" style="font-size: 20px">
                {{Cart::session(1)->getTotalQuantity()}} </b> 
            </div>
       

        </div> --}}

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

                   <a href="{{route('product-detail-1',['id'=>$value->id])}}" type="button" 
                    class="btn btn-success btn-icon waves-effect waves-light m-r-15 hvr-bounce-in option-icon"> <i class="icofont icofont-cart-alt f-20"></i></a>
                    <a href="{{route('product-detail-1',['id'=>$value->id])}}"
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
    <a href="{{route('product-detail-1',['id'=>$value->id])}}" class="txt-muted">
        <h5 style="font-size: 15px">{{$value->product_name}}</h5>
        <p class="text-left p-2 m-b-0" style="font-size: 12px">{!!$value->title!!}</p> 
       
    </a> 
        <!--<div class="m-b-10">
        <label class="label label-success">3.5 <i class="fa fa-star"></i></label><a class="text-muted f-w-600">14 Ratings &amp;  3 Reviews</a>
    </div> -->
    <span class="prod-price" style="font-size: 20px"> {{ $value->icon }} {{number_format($value->member_price,2)}} <b
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
        $.ajax({
            url: '{{route('product_list_select')}}',
            type: 'GET',
            data: {'category_id':category,'type':1},
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
