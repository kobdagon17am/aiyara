@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ตั้งค่าการแถมสินค้า </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">

 <div class="myBorder">
      
      <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> เพิ่มรายการแถม </span>

              @if( empty($sRow) )
              <form action="{{ route('backend.giveaway.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.giveaway.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label required_star_red ">สถานที่ตั้งธุรกิจ : </label>
                    <div class="col-md-9">
                    <select name="business_location_id_fk" class="form-control select2-templating " required >
                      <option value="">-Business Location-</option>
                        @if(@$sBusiness_location)
                          @foreach(@$sBusiness_location AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->business_location_id_fk)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label required_star_red ">ชื่อการแถม : </label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" value="{{@$sRow->giveaway_name}}" name="giveaway_name" required>
                    </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-3 col-form-label required_star_red ">ประเภทการซื้อ : </label>
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
                    <label for="start_date" class="col-md-3 col-form-label required_star_red ">วันเริ่มต้น : </label>
                    <div class="col-md-3">

 					@if( empty($sRow) )
                        <input class="form-control start_date" autocomplete="off" placeholder="" required  />
                        <input type="hidden" id="start_date" name="start_date" required />
 					@else
 					<?php if(!empty(@$sRow->start_date)){
 						$ds = explode('-', @$sRow->start_date);
 						$ds_d = $ds[2];
 						$ds_m = $ds[1];
 						$ds_y = $ds[0];
 						$ds = $ds_d.'/'.$ds_m.'/'.$ds_y;
 					}else{$ds='';} ?> 					
                         <input class="form-control start_date" autocomplete="off" value="{{$ds}}"   />
                        <input type="hidden" id="start_date" name="start_date" value="{{@$sRow->start_date}}"  />
 					@endif


                    </div>
                </div>

                <div class="form-group row">
                    <label for="end_date" class="col-md-3 col-form-label required_star_red ">วันสิ้นสุด : </label>
                    <div class="col-md-3">

 					@if( empty($sRow) )
                        <input class="form-control end_date"  autocomplete="off" placeholder="" required />
                        <input type="hidden" id="end_date" name="end_date" required />
 					@else
 					<?php if(!empty(@$sRow->end_date)){
 						$de = explode('-', @$sRow->end_date);
 						$de_d = $de[2];
 						$de_m = $de[1];
 						$de_y = $de[0];
 						$de = $de_d.'/'.$de_m.'/'.$de_y;
 					}else{$de='';} ?>
                        <input class="form-control end_date"  autocomplete="off" value="{{$de}}"   />
                        <input type="hidden" id="end_date" name="end_date" value="{{@$sRow->end_date}}" />
 					@endif  


                    </div>
                </div>

   <!--                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">ประเภทการซื้อ : </label>
                    <div class="col-md-9">
                      <select name="purchase_type_id_fk" class="form-control select2-templating " required >
                        <option value="">Select</option>
                            @if(@$sPurchase_type)
                              @foreach(@$sPurchase_type AS $r)
                                <option value="{{$r->id}}" {{ (@$r->id==@$sRow->purchase_type_id_fk)?'selected':'' }} >
                                	{{$r->orders_type}}
                                </option>
                              @endforeach
                            @endif                        
                      </select>
                    </div>
                  </div> -->


                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label required_star_red ">แถมสมาชิกแบบ : </label>
                    <div class="col-md-9">
                      <select name="giveaway_member_type_id_fk" class="form-control select2-templating "  required >
                        <option value="">Select</option>
                            @if(@$sGiveaway_type)
                              @foreach(@$sGiveaway_type AS $r)
                                <option value="{{$r->id}}" {{ (@$r->id==@$sRow->giveaway_member_type_id_fk)?'selected':'' }} >
                                	{{$r->txt_desc}}
                                </option>
                              @endforeach
                            @endif                         
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label required_star_red ">จำนวนการแถมในบิลนั้น : </label>
                    <div class="col-md-9">
                      <select name="giveaway_in_bill_id_fk" class="form-control select2-templating " required >
                        <option value="">Select</option>
                            @if(@$sGiveaway_time)
                              @foreach(@$sGiveaway_time AS $r)
                                <option value="{{$r->id}}" {{ (@$r->id==@$sRow->giveaway_in_bill_id_fk)?'selected':'' }} >
                                	{{$r->txt_desc}}
                                </option>
                              @endforeach
                            @endif   
                      </select>
                    </div>
                  </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label required_star_red ">PV การซื้อขั้นต่ำ : </label>
                    <div class="col-md-3">
                        <input class="form-control " type="number" name="pv_minimum_purchase"  value="{{@$sRow->pv_minimum_purchase}}"  required  />
                    </div>
                </div>

                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label required_star_red ">ตัวเลือกการแถม : </label>
                    <div class="col-md-9">
                      <select name="giveaway_option_id_fk" class="form-control select2-templating " required >
                        <option value="">Select</option>
                            @if(@$sGiveaway_obtion)
                              @foreach(@$sGiveaway_obtion AS $r)
                                <option value="{{$r->id}}" {{ (@$r->id==@$sRow->giveaway_option_id_fk)?'selected':'' }} >
                                	{{$r->txt_desc}}
                                </option>
                              @endforeach
                            @endif                           
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">Voucher ที่แถม :  </label>
                    <div class="col-md-3">
                        <input class="form-control " type="number" name="giveaway_voucher"  value="{{@$sRow->giveaway_voucher}}" />
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
                  <label for="priority" class="col-md-3 col-form-label">Priority : </label>
                  <div class="col-md-8">
                    <select name="priority" class="form-control select2-templating "  >
                            <option value="0" {{ (@$sRow->priority==0)?'selected':'' }} >0</option>
                            <option value="1" {{ (@$sRow->priority==1)?'selected':'' }} >1</option>
                            <option value="2" {{ (@$sRow->priority==2)?'selected':'' }} >2</option>
                            <option value="3" {{ (@$sRow->priority==3)?'selected':'' }} >3</option>
                            <option value="4" {{ (@$sRow->priority==4)?'selected':'' }} >4</option>
                            <option value="5" {{ (@$sRow->priority==5)?'selected':'' }} >5</option>
                            <option value="6" {{ (@$sRow->priority==6)?'selected':'' }} >6</option>
                            <option value="7" {{ (@$sRow->priority==7)?'selected':'' }} >7</option>
                            <option value="8" {{ (@$sRow->priority==8)?'selected':'' }} >8</option>
                            <option value="9" {{ (@$sRow->priority==9)?'selected':'' }} >9</option>
                    </select>
                  </div>
                </div>


                 <div class="form-group row">
                    <label class="col-md-3 col-form-label">พิจารณาโปรอื่นหรือไม่</label>
                    <div class="col-md-8 mt-2">
                      <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input" id="another_pro" name="another_pro" value="1" {{ ( @$sRow->another_pro=='1')?'checked':'' }}>
                          <label class="custom-control-label" for="another_pro"> Y / N </label>
                      </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-md-3 col-form-label">สถานะ :</label>
                    <div class="col-md-9 mt-2">
                      <div class="custom-control custom-switch">
                        @if( empty($sRow) )
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" checked >
                        @else
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" {{ ( @$sRow->status=='1')?'checked':'' }}>
                        @endif
                          <label class="custom-control-label" for="customSwitch">เปิดใช้งาน</label>
                      </div>
                    </div>
                </div>



  
                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/giveaway") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
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
							<span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการสินค้าแถม </span>
							<a class="btn btn-info btn-sm mt-1" href="{{ route('backend.giveaway_products.create') }}/{{@$sRow->id}}" style="float: right;" >
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
                <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/giveaway") }}">
                  <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                </a>
            </div>
        </div>

			</div>

@endif


        </div>
    </div> <!-- end col -->
</div>
</div>
<!-- end row -->

@endsection

@section('script')

        <script type="text/javascript">


                function showPreview_01(ele)
                    {
                        $('#image').attr('src', ele.value); // for IE
                        if (ele.files && ele.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $('#imgAvatar_01').show();
                                $('#imgAvatar_01').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(ele.files[0]);
                    }
                }


        </script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" />

 <script>
      $('.start_date').datetimepicker({
          value: '',
          rtl: false,
          format: 'd/m/Y',
          formatTime: 'H:i',
          formatDate: 'd/m/Y',
          monthChangeSpinner: true,
          closeOnTimeSelect: true,
          closeOnWithoutClick: true,
          closeOnInputClick: true,
          openOnFocus: true,
          timepicker: false,
          datepicker: true,
          weeks: false,
          minDate: 0,
      });

      $('.start_date').change(function(event) {
        var d = $(this).val();
        var t = d.substring(d.length - 5);
        var d = d.substring(0, 10);
        var d = d.split("/").reverse().join("-");
        $('#start_date').val(d);
      });


      $('.end_date').datetimepicker({
          value: '',
          rtl: false,
          // format: 'd/m/Y H:i',
          format: 'd/m/Y',
          formatTime: 'H:i',
          formatDate: 'd/m/Y',
          monthChangeSpinner: true,
          closeOnTimeSelect: true,
          closeOnWithoutClick: true,
          closeOnInputClick: true,
          openOnFocus: true,
          timepicker: false,
          datepicker: true,
          weeks: false,
          minDate: 0 ,
          // minDate: function () {
          //   return $('.start_date').val();
          // }
      });

      $('.end_date').change(function(event) {
        var ds = $('#start_date').val();
        var de = $('#end_date').val();
        var d = $(this).val();
        
        var t = d.substring(d.length - 5);
        var d = d.substring(0, 10);
        var d = d.split("/").reverse().join("-");
        $('#end_date').val(d);

        // alert(ds+" : "+de);
        // if(de<ds){
        //   alert("! วันสิ้นสุด ควรมีค่ามากกว่าหรือเท่ากับ วันเริ่มต้น");
        //   $(this).val('');
        //   $('#end_date').val('');
        //   return false;
        // }

      });



</script>


  <script>

            var giveaway_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
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
                            url: '{{ route('backend.giveaway_products.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['giveaway_id_fk'] = giveaway_id_fk ;
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
                        + '<a href="{{ route('backend.giveaway_products.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.giveaway_products.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });


            </script>


@endsection
