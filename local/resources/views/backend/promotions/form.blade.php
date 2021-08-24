@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Promotions </h4>
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
              <form action="{{ route('backend.promotions.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.promotions.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

                <div class="form-group row">
                  <label for="" class="col-md-3 col-form-label">Business Location : * </label>
                  <div class="col-md-8">
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
                  <label for="" class="col-md-3 col-form-label">ประเภทสินค้า : * </label>
                  <div class="col-md-8">
                    <select name="product_type" class="form-control select2-templating " required >
                      <option value="">Select</option>
                        @if(@$sProduct_group)
                          @foreach(@$sProduct_group AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->product_type)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-3 col-form-label">Orders Type : * </label>
                  <div class="col-md-8">
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
                
                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">รหัส : * </label>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{ @$sRow->pcode }}" name="pcode" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">ชื่อภาษาไทย : * </label>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{ @$sRow->name_thai }}" name="name_thai" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">ชื่อภาษาอังกฤษ : * </label>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{ @$sRow->name_eng }}" name="name_eng" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">ชื่อภาษาลาว : * </label>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{ @$sRow->name_laos }}" name="name_laos" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">ชื่อภาษาพม่า : * </label>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{ @$sRow->name_burma }}" name="name_burma" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">ชื่อภาษากัมพูชา : * </label>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{ @$sRow->name_cambodia }}" name="name_cambodia" required>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">รายละเอียดภาษาไทย :</label>
                    <div class="col-md-8">
                      <textarea class="form-control" id="detail_thai" name="detail_thai" rows="3"  >{{ @$sRow->detail_thai }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">รายละเอียดภาษาอังกฤษ :</label>
                    <div class="col-md-8">
                        <textarea class="form-control" id="detail_eng" name="detail_eng" rows="3"  >{{ @$sRow->detail_eng }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">รายละเอียดภาษาลาว :</label>
                    <div class="col-md-8">
                        <textarea class="form-control" id="detail_laos" name="detail_laos" rows="3"  >{{ @$sRow->detail_laos }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">รายละเอียดภาษาพม่า :</label>
                    <div class="col-md-8">
                        <textarea class="form-control" id="detail_burma" name="detail_burma" rows="3"  >{{ @$sRow->detail_burma }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">รายละเอียดภาษากัมพูชา :</label>
                    <div class="col-md-8">
                      <textarea class="form-control" id="detail_cambodia" name="detail_cambodia" rows="3"  >{{ @$sRow->detail_cambodia }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">PV :</label>
                    <div class="col-md-8">
                        <input class="form-control" type="text" value="{{ @$sRow->pv }}" name="pv" >
                    </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-3 col-form-label">หน่วยนับ : * </label>
                  <div class="col-md-8">
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
                    <label for="" class="col-md-3 col-form-label">วันเริ่มต้นการแสดง :</label>
                    <div class="col-md-3">
                        <input class="form-control" type="date" value="{{ @$sRow->show_startdate }}" name="show_startdate" required >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">วันสิ้นสุดการแสดง :</label>
                    <div class="col-md-3">
                        <input class="form-control" type="date" value="{{ @$sRow->show_enddate }}" name="show_enddate" required >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">จำนวนที่สามารถซื้อได้ทั้งหมด :</label>
                    <div class="col-md-8">
                        <input class="form-control" type="number" value="{{ @$sRow->all_available_purchase }}" name="all_available_purchase" >
                    </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-3 col-form-label">ประเภทจำนวนจำกัด : * </label>
                  <div class="col-md-8">
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
                    <label for="" class="col-md-3 col-form-label">จำนวนจำกัดต่อคน :</label>
                    <div class="col-md-8">
                        <input class="form-control" type="number" value="{{ @$sRow->limited_amt_person }}" name="limited_amt_person" >
                    </div>
                </div>


              <div class="form-group row">
                  <label for="" class="col-md-3 col-form-label">Package ขั้นต่ำที่ซื้อได้ : </label>
                  <div class="col-md-8">
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
                  <label for="" class="col-md-3 col-form-label">คุณวุฒิ reward ที่ซื้อได้ : </label>
                  <div class="col-md-8">
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
                  <label for="" class="col-md-3 col-form-label">รักษาคุณสมบัติส่วนตัว : </label>
                  <div class="col-md-8">
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
                  <label for="" class="col-md-3 col-form-label">รักษาคุณสมบัติท่องเที่ยว : </label>
                  <div class="col-md-8">
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
                  <label for="" class="col-md-3 col-form-label">aistockist : </label>
                  <div class="col-md-8">
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
                  <label for="" class="col-md-3 col-form-label">agency : </label>
                  <div class="col-md-8">
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

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">สถานะ :</label>
                    <div class="col-md-8 mt-2">
                      <div class="custom-control custom-switch">
                        @if( empty($sRow) )
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" checked >
                        @else
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" {{ ( @$sRow->status=='1')?'checked':'' }}>
                        @endif
                          <label class="custom-control-label" for="customSwitch">การใช้งาน/การแสดงผล</label>
                      </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-md-3 col-form-label"></label>
                    <div class="col-md-8 mt-2">
                      <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input" id="promotion_coupon_status" name="promotion_coupon_status" value="1" {{ ( @$sRow->promotion_coupon_status=='1')?'checked':'' }}>
                          <label class="custom-control-label" for="promotion_coupon_status"><b>promotion coupon status</b></label>
                      </div>
                    </div>
                </div>
                                
                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/promotions") }}">
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

          </div>


                @if( !empty($sRow) )
                    
                    <div class="myBorder">

                      <div style="">
                          <div class="form-group row">
                              <div class="col-md-12">
                                  <span style="font-weight: bold;padding-right: 10px;"> รายการสินค้า </span>
                                  <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.promotions_products.create') }}/{{@$sRow->id}}" style="float: right;" >
                                      <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                                  </a>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-md-12">
                                  <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                                  </table>
                              </div>
                          </div>
                      </div>


                      <div class="form-group mb-0 row">
                           <div class="col-md-6">
                              <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/promotions") }}">
                                <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                              </a>
                          </div>
                      </div>

                  </div>


                    <div class="myBorder">
                      <div style="">
                        <div class="form-group row">
                          <div class="col-md-12">
                            <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.promotions_cost.create') }}/{{@$sRow->id}}" style="float: right;">
                              <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                            </a>
                            <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> ข้อมูลราคา  </span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-12">
                            <table id="data-table-promotions-cost" class="table table-bordered dt-responsive" style="width: 100%;">
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="form-group mb-0 row">
                        <div class="col-md-6">
                          <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/promotions") }}">
                            <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                          </a>
                        </div>
                      </div>
                    </div>



                    <div class="myBorder">
                      <div style="">
                        <div class="form-group row">
                          <div class="col-md-12">
                            
                            <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.promotions_images.create') }}/{{@$sRow->id}}" style="float: right;" >
                              <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                            </a>
                            <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รูปสินค้าโปรโมชั่น  </span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-12">
                            <table id="data-table-promotion-images" class="table table-bordered dt-responsive" style="width: 100%;">
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="form-group mb-0 row">
                        <div class="col-md-6">
                          <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/promotions") }}">
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
@endsection

@section('script')

            <script>

            var promotion_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
            var oTable;

            $(function() {
                oTable = $('#data-table').DataTable({
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
                            url: '{{ route('backend.promotions_products.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['promotion_id_fk'] = promotion_id_fk ;
                                    oData = d;
                                  },
                              method: 'POST',
                            },
                 
                    columns: [
                        {data: 'id', title :'ID', className: 'text-center w50'},
                        {data: 'product_name', title :'รหัส : ชื่อสินค้า', className: 'text-left'},
                        {data: 'product_amt', title :'จำนวน', className: 'text-center'},
                        {data: 'product_unit_desc', title :'หน่วยนับ', className: 'text-center'},
                        {data: 'id', title :'Tools', className: 'text-center w80'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                      $('td:last-child', nRow).html(''
                        + '<a href="{{ route('backend.promotions_products.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.promotions_products.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger  cCancel " data-id="'+aData['id']+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });




            var promotion_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
            var oTable;

            $(function() {
                oTable = $('#data-table-promotions-cost').DataTable({
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
                            url: '{{ route('backend.promotions_cost.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['promotion_id_fk'] = promotion_id_fk ;
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
                        + '<a href="{{ route('backend.promotions_cost.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.promotions_cost.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });
              


            var promotion_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
            var oTable;

            $(function() {
                oTable = $('#data-table-promotion-images').DataTable({
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
                            url: '{{ route('backend.promotions_images.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['promotion_id_fk'] = promotion_id_fk ;
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
                        + '<a href="{{ route('backend.promotions_images.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.promotions_images.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });


      </script>

      <script>
      $(document).ready(function() {


          $(document).on('click', '.cCancel', function(event) {

            var id = $(this).data('id');
         
              if (!confirm("ยืนยัน ? เพื่อยกลบ ")){
                  return false;
              }else{
              $.ajax({
                  url: " {{ url('backend/ajaxDelPromoProduct') }} ", 
                  method: "post",
                  data: {
                    "_token": "{{ csrf_token() }}", id:id,
                  },
                  success:function(data)
                  { 
                    // console.log(data);
                    // return false;
                        Swal.fire({
                          type: 'success',
                          title: 'ทำการลบรายชื่อเรียบร้อยแล้ว',
                          showConfirmButton: false,
                          timer: 2000
                        });

                        setTimeout(function () {
                          // $('#data-table').DataTable().clear().draw();
                          location.reload();
                        }, 1500);
                  }
                });

            }

              
            });
                
      });

    </script>

@endsection
