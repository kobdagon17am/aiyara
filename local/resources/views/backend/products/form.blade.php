@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/libs/select2/select2.min.css')}}">
<style type="text/css">
    .select2-dropdown {
       font-size: 16px;
    }
</style>

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">สินค้า</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.products.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.products.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">Business Location : * </label>
                  <div class="col-md-10">
                    <select name="business_location" class="form-control select2-templating " required >
                      <option value="">Select</option>
                        @if(@$sBusiness_location)
                          @foreach(@$sBusiness_location AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->business_location)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Product Code :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->product_code }}" name="product_code" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Category :</label>
                    <div class="col-md-10">
                         <select name="category_id" class="form-control select2-templating "  required >
                         <option value="">Select</option>
                            @if(@$dsCategory)
                                @foreach(@$dsCategory AS $r)
                                    <option value="{{@$r->id}}" {{ (@$r->id==@$sRow->category_id)?'selected':'' }} >{{@$r->category_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Orders Type :</label>
                    <div class="col-md-10">
                       @php
                        $v_orders_type_id = explode(",",@$sRow->orders_type_id);
                        @endphp
                        @foreach($dsOrders_type as $k => $r)
                        @php
                        $checked = '';
                        foreach($v_orders_type_id as $v_orders_type){
                        if($r->id==$v_orders_type) $checked = 'checked';
                        }
                        @endphp
                        <div class="checkbox-color checkbox-primary">
                            <input id="orders_type{{$r->id}}" name="orders_type[]"
                            type="checkbox" {{$checked}}
                            value="{{$r->id}}">
                            <label for="orders_type{{$r->id}}">
                                {{$r->orders_type}}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>


                @if( !empty($sRow) )


                        <div style="">
                            <div class="form-group row">
                                <div class="col-md-12">
                                   

                                @if( count($sProducts_details)==0 )
                                    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.products_details.create') }}/{{@$sRow->id}}">
                                        <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                                    </a>
                                @endif   

                                 <span style="font-weight: bold;padding-right: 10px;"> > Product Details </span>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <table id="data-table-product-detail" class="table table-bordered dt-responsive" style="width: 100%;">
                                    </table>
                                </div>
                            </div>
                        </div>


                 @endif


                     <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">PV :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->pv }}" name="pv" >
                    </div>
                </div>

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">หน่วยนับหลัก : * </label>
                  <div class="col-md-10">
                    <select name="main_unit" class="form-control select2-templating " required >
                      <option value="">Select</option>
                        @if(@$sProduct_unit)
                          @foreach(@$sProduct_unit AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->main_unit)?'selected':'' }} >{{$r->product_unit}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">วันเริ่มต้นการแสดง :</label>
                    <div class="col-md-3">
                        <input class="form-control" type="date" value="{{ @$sRow->show_startdate }}" name="show_startdate" required >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">วันสิ้นสุดการแสดง :</label>
                    <div class="col-md-3">
                        <input class="form-control" type="date" value="{{ @$sRow->show_enddate }}" name="show_enddate" required >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">จำนวนที่สามารถซื้อได้ทั้งหมด :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="number" value="{{ @$sRow->all_available_purchase }}" name="all_available_purchase" >
                    </div>
                </div>

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">ประเภทจำนวนจำกัด : * </label>
                  <div class="col-md-10">
                    <select name="limited_amt_type" class="form-control select2-templating " required >
                      <option value="">Select</option>
                        @if(@$sLimited_amt_type)
                          @foreach(@$sLimited_amt_type AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->limited_amt_type)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">จำนวนจำกัดต่อคน :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="number" value="{{ @$sRow->limited_amt_person }}" name="limited_amt_person" >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label"></label>
                    <div class="col-md-10 mt-2">
                      <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input" id="promotion_coupon_status" name="promotion_coupon_status" value="1" {{ ( @$sRow->promotion_coupon_status=='1')?'checked':'' }}>
                          <label class="custom-control-label" for="promotion_coupon_status"><b>promotion coupon status</b></label>
                      </div>
                    </div>
                </div>

              <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">Package ขั้นต่ำที่ซื้อได้ : </label>
                  <div class="col-md-10">
                    <select name="minimum_package_purchased" class="form-control select2-templating "  >
                      <option value="">Select</option>
                        @if(@$sPackage)
                          @foreach(@$sPackage AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->minimum_package_purchased)?'selected':'' }} >{{$r->dt_package}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">คุณวุฒิ reward ที่ซื้อได้ : </label>
                  <div class="col-md-10">
                    <select name="reward_qualify_purchased" class="form-control select2-templating "  >
                      <option value="">Select</option>
                        @if(@$sQualification)
                          @foreach(@$sQualification AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->reward_qualify_purchased)?'selected':'' }} >{{$r->business_qualifications}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">รักษาคุณสมบัติส่วนตัว : </label>
                  <div class="col-md-10">
                    <select name="keep_personal_quality" class="form-control select2-templating "  >
                      <option value="">Select</option>
                        @if(@$sPersonal_quality)
                          @foreach(@$sPersonal_quality AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->keep_personal_quality)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>


                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">รักษาคุณสมบัติท่องเที่ยว : </label>
                  <div class="col-md-10">
                    <select name="maintain_travel_feature" class="form-control select2-templating "  >
                      <option value="">Select</option>
                        @if(@$sTravel_feature)
                          @foreach(@$sTravel_feature AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->maintain_travel_feature)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>


                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">aistockist : </label>
                  <div class="col-md-10">
                    <select name="aistockist" class="form-control select2-templating "  >
                      <option value="">Select</option>
                        @if(@$sAistockist)
                          @foreach(@$sAistockist AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->aistockist)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">agency : </label>
                  <div class="col-md-10">
                    <select name="agency" class="form-control select2-templating "  >
                      <option value="">Select</option>
                        @if(@$sAgency)
                          @foreach(@$sAgency AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->agency)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>



                 @if( !empty($sRow) )
                     <div class="form-group row">
                        <label class="col-md-2 col-form-label">สถานะ :</label>
                        <div class="col-md-10 mt-2">
                          <div class="custom-control custom-switch">
                              <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" {{ ( @$sRow->status=='1')?'checked':'' }}>
                              <label class="custom-control-label" for="customSwitch">การใช้งาน/การแสดงผล</label>
                          </div>
                        </div>
                    </div>
                  @endif



                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/products") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </div>

              </form>



                @if( !empty($sRow) )

                        <hr>

                        <div style="">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    
                                    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.products_images.create') }}/{{@$sRow->id}}">
                                        <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                                    </a>

                                    <span style="font-weight: bold;padding-right: 10px;"> > รูปสินค้า  </span>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <table id="data-table-product-images" class="table table-bordered dt-responsive" style="width: 100%;">
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0 row">
                                <div class="col-md-6">
                                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/products") }}">
                                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                                    </a>
                                </div>
                        </div>


                 @endif




            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->
@section('script')

    <script src="{{ URL::asset('backend/libs/select2/select2.min.js')}}"></script>
    <script>
      $('.select2-templating').select2();
    </script>  


           <script>

            var product_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
            var oTable;

            $(function() {
                oTable = $('#data-table-product-detail').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    scroller: true,
                    scrollCollapse: true,
                    scrollX: true,
                    ordering: false,
                    scrollY: ''+($(window).height()-370)+'px',
                    iDisplayLength: 1,
                    bPaginate: false,
                    info:     false,
                    ajax: {
                            url: '{{ route('backend.products_details.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['product_id_fk'] = product_id_fk ;
                                    oData = d;
                                  },
                              method: 'POST',
                            },
                 
                    columns: [
                        // {data: 'id', title :'ID', className: 'text-center w50'},
                        {data: 'product_name', title :'ชื่อสินค้า', className: 'text-center'},
                        {data: 'descriptions_txt', title :'รายละเอียด', className: 'text-center'},
                        {data: 'id', title :'แก้ไข', className: 'text-center w60'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                      $('td:last-child', nRow).html(''
                        + '<a href="{{ route('backend.products_details.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                       
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });
            </script>




           <script>

            var product_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
            var oTable;

            $(function() {
                oTable = $('#data-table-product-images').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    scroller: true,
                    scrollCollapse: true,
                    scrollX: true,
                    ordering: false,
                    scrollY: ''+($(window).height()-370)+'px',
                    iDisplayLength: 5,
                    ajax: {
                            url: '{{ route('backend.products_images.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['product_id_fk'] = product_id_fk ;
                                    oData = d;
                                  },
                              method: 'POST',
                            },
                 
                    columns: [
                        {data: 'id', title :'ID', className: 'text-center w50'},
                        {data: 'img_path',   title :'<center>IMAGE</center>', className: 'text-center',render: function(d) {
                           return '<img src="'+d+'" width="150">';
                        }},
                        // {data: 'product_img',   title :'<center>IMAGE</center>', className: 'text-center',render: function(d) {
                        //    return '<img src="{{ url("local/public/products") }}/'+d+'" width="150">';
                        // }},
                        // {data: 'image_default', title :'กำหนดเป็นรูปหลัก', className: 'text-center'},
                        {data: 'image_default',   title :'<center>รูปหลัก</center>', className: 'text-center',render: function(d) {
                             return d==1?'Yes':'';
                        }},

                        {data: 'id', title :'Tools', className: 'text-center w60'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                      $('td:last-child', nRow).html(''
                        + '<a href="{{ route('backend.products_images.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.products_images.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });
            </script>

@endsection

@endsection
