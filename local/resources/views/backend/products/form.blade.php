@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

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

        
        <div class="myBorder">        

              @if( empty($sRow) )
              <form id="frm" action="{{ route('backend.products.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form id="frm" action="{{ route('backend.products.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

                <div class="form-group row">
                    <label for="" class="col-md-2 col-form-label">Product Code :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->product_code }}" name="product_code" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-2 col-form-label">Category :</label>
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
                    <label for="" class="col-md-2 col-form-label">Orders Type :</label>
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

                <div class="form-group row">
                    <label for="" class="col-md-2 col-form-label">ราคาทุน :</label>
                    <div class="col-md-2">
                        <input class="form-control" type="text" value="{{@$dsProducts_cost->cost_price?@$dsProducts_cost->cost_price:'-ยังไม่กำหนด-'}}" readonly style="border: 0px solid white;background-color: #f2f2f2;color:black;" >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-2 col-form-label">ราคาขาย :</label>
                    <div class="col-md-2">
                        <input class="form-control" type="text" value="{{@$dsProducts_cost->selling_price?@$dsProducts_cost->selling_price:'-ยังไม่กำหนด-'}}" readonly style="border: 0px solid white;background-color: #f2f2f2;color:black;" >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-2 col-form-label">ราคาสมาชิก :</label>
                    <div class="col-md-2">
                        <input class="form-control" type="text" value="{{@$dsProducts_cost->member_price?@$dsProducts_cost->member_price:'-ยังไม่กำหนด-'}}" readonly style="border: 0px solid white;background-color: #f2f2f2;color:black;" >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-2 col-form-label">PV :</label>
                    <div class="col-md-2">
                        <input class="form-control" type="text" value="{{@$dsProducts_cost->pv?@$dsProducts_cost->pv:'-ยังไม่กำหนด-'}}" readonly style="border: 0px solid white;background-color: #f2f2f2;color:black;" >
                    </div>
                </div>
                @endif


                <div class="form-group row">
                      <label class="col-md-2 col-form-label"> </label>
                      <div class="col-md-10 mt-2">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch1" name="product_voucher" value="1" {{ ( @$sRow->product_voucher=='1')?'checked':'' }}>
                            <label class="custom-control-label" for="customSwitch1">เป็นสินค้าที่สามารถซื้อด้วย Voucher</label>
                        </div>
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


                <div class="form-group row">
                      <label class="col-md-2 col-form-label">  </label>
                      <div class="col-md-10 mt-2">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch2" name="order_by_member" value="1" {{ ( @$sRow->order_by_member=='1')?'checked':'' }}>
                            <label class="custom-control-label" for="customSwitch2">สั่งซื้อโดยสมาชิก</label>
                        </div>
                      </div>
                </div>

                <div class="form-group row">
                      <label class="col-md-2 col-form-label"> </label>
                      <div class="col-md-10 mt-2">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch3" name="order_by_staff" value="1" {{ ( @$sRow->order_by_staff=='1')?'checked':'' }}>
                            <label class="custom-control-label" for="customSwitch3">สั่งซื้อโดยพนักงาน</label>
                        </div>
                      </div>
                </div>



                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                      @if( empty($sRow) )
                         <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/products") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a> 
                         @endif
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect btnSave ">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </div>

              </form>
    </div>

                @if( !empty($sRow) )

                  <div class="myBorder">
                    <div style="">
                      <div class="form-group row">
                        <div class="col-md-12">
                          
                          @if( count($sProducts_details)==0 )
                          <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.products_details.create') }}/{{@$sRow->id}}" style="float: right;" >
                            <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                          </a>
                          @endif
                          <span style="font-weight: bold;padding-right: 10px;"> <i class="bx bx-play"></i> Product Details </span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-12">
                          <table id="data-table-product-detail" class="table table-bordered dt-responsive" style="width: 100%;">
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>

                    <div class="myBorder">
                      <div style="">
                        <div class="form-group row">
                          <div class="col-md-12">
                            
                            <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.products_images.create') }}/{{@$sRow->id}}" style="float: right;" >
                              <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                            </a>
                            <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รูปสินค้า  </span>
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
                    </div>

                    <div class="myBorder">
                      <div style="">
                        <div class="form-group row">
                          <div class="col-md-12">
                            <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.products_cost.create') }}/{{@$sRow->id}}" style="float: right;">
                              <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                            </a>
                            <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> ข้อมูลราคา  </span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-12">
                            <table id="data-table-product-cost" class="table table-bordered dt-responsive" style="width: 100%;">
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
                    </div>


                    <div class="myBorder">
                      <div style="">
                        <div class="form-group row">
                          <div class="col-md-12">
                            <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.products_units.create') }}/{{@$sRow->id}}" style="float: right;">
                              <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                            </a>
                            <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการหน่วยนับ  </span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-12">
                            <table id="data-table-product-unit" class="table table-bordered dt-responsive" style="width: 100%;">
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
                    </div>


                 @endif



            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->
@section('script')

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




            var product_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
            var oTable;

            $(function() {
                oTable = $('#data-table-product-cost').DataTable({
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
                            url: '{{ route('backend.products_cost.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['product_id_fk'] = product_id_fk ;
                                    oData = d;
                                  },
                              method: 'POST',
                            },
                    columns: [
                        {data: 'id', title :'ID', className: 'text-center w50'},
                        {data: 'business_location', title :'BUSINESS LOCATION', className: 'text-left'},
                        {data: 'country', title :'<center>ประเทศ</center>', className: 'text-center'},
                        {data: 'currency', title :'<center>สกุลเงิน</center>', className: 'text-center'},
                        {data: 'cost_price', title :'<center>ราคาทุน</center>', className: 'text-center'},
                        {data: 'selling_price', title :'<center>ราคาขาย</center>', className: 'text-center'},
                        {data: 'member_price', title :'<center>ราคาสมาชิก</center>', className: 'text-center'},
                        {data: 'pv', title :'<center>PV</center>', className: 'text-center'},
                        {data: 'status',   title :'<center>สถานะ</center>', className: 'text-center',render: function(d) {
                           return d==1?'<span style="color:blue">เปิดใช้งาน</span>':'<span style="color:red">ปิด</span>';
                        }},
                        {data: 'id', title :'Tools', className: 'text-center w60'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                      $('td:last-child', nRow).html(''
                        + '<a href="{{ route('backend.products_cost.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.products_cost.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });
              



            var product_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
            var oTable;

            $(function() {
                oTable = $('#data-table-product-unit').DataTable({
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
                            url: '{{ route('backend.products_units.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['product_id_fk'] = product_id_fk ;
                                    oData = d;
                                  },
                              method: 'POST',
                            },
                    columns: [
                        {data: 'id', title :'ID', className: 'text-center w50'},
                        {data: 'product_unit_name', title :'<center>หน่วย</center>', className: 'text-center'},
                        {data: 'converted_value', title :'<center>ค่าที่แปลง</center>', className: 'text-center'},
                        {data: 'status',   title :'<center>สถานะ</center>', className: 'text-center',render: function(d) {
                           return d==1?'<span style="color:blue">เปิดใช้งาน</span>':'<span style="color:red">ปิด</span>';
                        }},
                        {data: 'id', title :'Tools', className: 'text-center w150'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){

                      if(aData['first_unit']!=1){

                        $('td:last-child', nRow).html(''
                          + '<a href="{{ route('backend.products_units.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                          + '<a href="javascript: void(0);" data-url="{{ route('backend.products_units.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                        ).addClass('input');

                      }else{
                        $('td:last-child', nRow).html('Default value');
                      }


                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });
              


            </script>

@endsection

@endsection
