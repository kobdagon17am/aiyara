@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style type="text/css">
  input[type=text] {
    font-weight: bold;
  }

  input[type=number] {
    font-weight: bold;
  }
</style>
@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0 font-size-18"> รับสินค้าเข้า</h4>
    </div>
  </div>
</div>
<!-- end page title -->

<?php
      $sPermission = \Auth::user()->permission ;
      // $menu_id = @$_REQUEST['menu_id'];
      $menu_id = Session::get('session_menu_id');
      if($sPermission==1){
        $sC = '';
        $sU = '';
        $sD = '';
        $role_group_id = '%';
      }else{
        $role_group_id = \Auth::user()->role_group_id_fk;
        // echo $role_group_id;
        // echo $menu_id;
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        $sC = @$menu_permit->c==1?'':'display:none;';
        $sU = @$menu_permit->u==1?'':'display:none;';
        $sD = @$menu_permit->d==1?'':'display:none;';
      }
      // echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;
   ?>

<div class="row">
  <div class="col-10">
    <div class="card">
      <div class="card-body">
        @if( empty(@$sRow) )
        <form action="{{ route('backend.general_receive.store') }}" method="POST" enctype="multipart/form-data"
          autocomplete="off">
          @else
          <form action="{{ route('backend.general_receive.update', @$sRow->id ) }}" method="POST"
            enctype="multipart/form-data" autocomplete="off">
            <input name="_method" type="hidden" value="PUT">
            @endif

            <div class="myBorder">

              <div class="form-group row">
                <label for="" class="col-md-3 col-form-label"> Business Location : * </label>
                <div class="col-md-8">
                  <select id="business_location_id_fk" name="business_location_id_fk"
                    class="form-control select2-templating " required="" @if($sPermission !== 1) disabled @endif>
                    <option value="">-Business Location-</option>
                    @if(@$sBusiness_location)
                      @foreach(@$sBusiness_location AS $r)
                          <option value="{{$r->id}}" {{ (@$r->id == @$sRow->business_location_id_fk || $r->id == auth()->user()->business_location_id_fk && auth()->user()->permission !== 1)?'selected':'' }}>
                            {{$r->txt_desc}}
                          </option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="" class="col-md-3 col-form-label"> สาขา : * </label>
                <div class="col-md-8">

                  @if($sPermission==1)

                  <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating" required>
                    <option value="" selected>กรุณาเลือก Business Location ก่อน</option>
                  </select>

                  @else

                  @if( empty(@$sRow) )
                  <input type="hidden" name="branch_id_fk" value="{{@\Auth::user()->branch_id_fk}}">
                  @else
                  <input type="hidden" name="branch_id_fk" value="{{@$sRow->branch_id_fk}}">
                  @endif

                  <select class="form-control select2-templating" disabled="">
                    @if(@$sBranchs)
                    @foreach(@$sBranchs AS $r)
                    <?=$branch_id_fk=(@$sRow->branch_id_fk?@$sRow->branch_id_fk : @\Auth::user()->branch_id_fk)?>
                    <option value="{{$r->id}}" {{ ( @$r->id==$branch_id_fk) ? 'selected': ''  }}>
                      {{$r->b_name}}
                    </option>
                    @endforeach
                    @endif
                  </select>

                  @endif




                </div>
              </div>



              <div class="form-group row">
                <label for="" class="col-md-3 col-form-label"> สาเหตุที่รับเข้า : * </label>
                <div class="col-md-8">
                  <select name="product_in_cause_id_fk" class="form-control select2-templating " required>
                    <option value="">Select</option>
                    @if(@$Product_in_cause)
                    @foreach(@$Product_in_cause AS $r)
                    <option value="{{$r->id}}" {{ (@$r->id==@$sRow->product_in_cause_id_fk)?'selected':'' }}>
                      {{$r->txt_desc}}
                    </option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="" class="col-md-3 col-form-label"> สภาพสินค้าที่รับเข้า : * </label>
                <div class="col-md-8">
                  <select name="product_status_id_fk" class="form-control select2-templating " required>
                    <option value="">Select</option>
                    @if(@$Product_status)
                    @foreach(@$Product_status AS $r)
                    <option value="{{$r->id}}" {{ (@$r->id==@$sRow->product_status_id_fk)?'selected':'' }}>
                      {{$r->txt_desc}}
                    </option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="po_invoice_no" class="col-md-3 col-form-label">เลขที่ PO : </label>
                <div class="col-md-8">
                  <input class="form-control" type="text" value="{{ @$sRow->po_invoice_no }}" name="po_invoice_no">
                </div>
              </div>


              <div class="form-group row">
                <label for="delivery_person" class="col-md-3 col-form-label">ชื่อ Supplier : </label>
                <div class="col-md-8">
                  <select name="supplier_id_fk" class="form-control select2-templating ">
                    <option value="">Select</option>
                    @if(@$sSupplier)
                    @foreach(@$sSupplier AS $r)
                    <option value="{{$r->id}}" {{ (@$r->id==@$sRow->supplier_id_fk)?'selected':'' }}>
                      {{$r->txt_desc}}
                    </option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="delivery_person" class="col-md-3 col-form-label">ผู้ส่งมอบ : *</label>
                <div class="col-md-8">
                  <input class="form-control" type="text" value="{{ @$sRow->delivery_person }}" name="delivery_person"
                    required>
                </div>
              </div>


              <div class="form-group row">
                <label for="" class="col-md-3 col-form-label"> รหัสสินค้า : ชื่อสินค้า : * </label>
                <div class="col-md-8">

                  <select id="product_id_fk" name="product_id_fk" class="form-control select2-templating " required>

                    <option value="">Select</option>
                    @if(@$Products)
                    @foreach(@$Products AS $r)
                    <option value="{{@$r->product_id}}" {{ (@$r->product_id==@$sRow->product_id_fk)?'selected':'' }}>
                      {{@$r->product_code." : ".@$r->product_name}}
                    </option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="lot_number" class="col-md-3 col-form-label">Lot Number : * </label>
                <div class="col-md-8">
                  <!--           <select name="lot_number" id="lot_number" class="form-control select2-templating lot_number_select " required >
                                <option value="">-Lot Number-</option>
                                   @if(@$Check_stock)
                                      @foreach(@$Check_stock AS $r)
                                        <option value="{{@$r->lot_number}}" {{ (@$r->lot_number==@$sRow->lot_number)?'selected':'' }} >
                                          {{@$r->lot_number}}
                                        </option>
                                      @endforeach
                                    @endif
                              </select> -->

                  <!-- <input class="form-control lot_number_input " name="lot_number" required style="display: none;" > -->
                  <input type="text" class="form-control lot_number_auto " id="lot_number_auto" name="lot_number"
                    required value="{{@$sRow->lot_number}}">

                </div>
              </div>

              <div class="form-group row">
                <label for="lot_expired_date" class="col-md-3 col-form-label">วันหมดอายุ : * </label>
                <div class="col-md-3">
                  @IF(!empty(@$sRow->lot_expired_date))
                  <input class="form-control" type="text" value="{{ @$sRow->lot_expired_date }}" name="lot_expired_date"
                    id="lot_expired_date"
                    pattern="(?:19|20)\[0-9\]{2}-(?:(?:0\[1-9\]|1\[0-2\])/(?:0\[1-9\]|1\[0-9\]|2\[0-9\])|(?:(?!02)(?:0\[1-9\]|1\[0-2\])/(?:30))|(?:(?:0\[13578\]|1\[02\])-31))"
                    readonly>
                  @ELSE
                  <input class="form-control" type="date" value="{{ @$sRow->lot_expired_date }}" name="lot_expired_date"
                    id="lot_expired_date" required>
                  @ENDIF
                </div>
              </div>


              <div class="form-group row">
                <label for="amt" class="col-md-3 col-form-label">จำนวน :</label>
                <div class="col-md-3">
                  <input class="form-control" type="number" value="{{ @$sRow->amt }}" name="amt">
                </div>
              </div>

              <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">หน่วยนับ : * </label>
                <div class="col-md-3">
                  <select name="product_unit_id_fk" class="form-control select2-templating " required>
                    <option value="">Select</option>
                    @if(@$sProductUnit)
                    @foreach(@$sProductUnit AS $r)
                    <option value="{{$r->id}}" {{ (@$r->id==@$sRow->product_unit_id_fk)?'selected':'' }}>
                      {{$r->product_unit}}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>



              @if( empty(@$sRow) )

              <div class="form-group row">
                <label for="warehouse_id_fk" class="col-md-3 col-form-label"> คลัง : * </label>
                <div class="col-md-8">
                  <select id="warehouse_id_fk" name="warehouse_id_fk" class="form-control select2-templating " required>
                    <option disabled selected>กรุณาเลือกสาขาก่อน</option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="" class="col-md-3 col-form-label"> Zone : * </label>
                <div class="col-md-8">
                  <select id="zone_id_fk" name="zone_id_fk" class="form-control select2-templating " required>
                    <option disabled selected>กรุณาเลือกคลังย่อยก่อน</option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="" class="col-md-3 col-form-label"> Shelf : * </label>
                <div class="col-md-8">
                  <select id="shelf_id_fk" name="shelf_id_fk" class="form-control select2-templating " required>
                    <option disabled selected>กรุณาเลือกโซนก่อน</option>
                  </select>
                </div>
              </div>



              <div class="form-group row">
                <label for="shelf_floor" class="col-md-3 col-form-label">รับเข้าชั้นของ Shelf :</label>
                <div class="col-md-3">
                  <input class="form-control" type="number" id="shelf_floor" name="shelf_floor" required>
                </div>
              </div>


              @else


              <div class="form-group row">
                <label for="warehouse_id_fk" class="col-md-3 col-form-label"> คลัง : * </label>
                <div class="col-md-8">
                  <select id="warehouse_id_fk" name="warehouse_id_fk" class="form-control select2-templating " required>
                    <!-- <option value="">กรุณาเลือกคลังหลักก่อน</option> -->
                    @if(@$Warehouse)
                    @foreach(@$Warehouse AS $r)
                    <?php if(@$r->id==@$sRow->warehouse_id_fk){ ?>
                    <option value="{{$r->id}}" selected>
                      {{$r->w_name}}
                    </option>
                    <?php } ?>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-md-3 col-form-label"> Zone : * </label>
                <div class="col-md-8">
                  <select id="zone_id_fk" name="zone_id_fk" class="form-control select2-templating " required>
                    <!-- <option value="">กรุณาเลือกคลังย่อยก่อน</option> -->
                    @if(@$Zone)
                    @foreach(@$Zone AS $r)
                    <?php if(@$r->id==@$sRow->zone_id_fk){ ?>
                    <option value="{{$r->id}}" selected>
                      {{$r->z_name}}
                    </option>
                    <?php } ?>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="" class="col-md-3 col-form-label"> Shelf : * </label>
                <div class="col-md-8">
                  <select id="shelf_id_fk" name="shelf_id_fk" class="form-control select2-templating " required>
                    <!-- <option value="">กรุณาเลือกโซนก่อน</option> -->
                    @if(@$Shelf)
                    @foreach(@$Shelf AS $r)
                    <?php if(@$r->id==@$sRow->shelf_id_fk){ ?>
                    <option value="{{$r->id}}" selected>
                      {{$r->s_name}}
                    </option>
                    <?php } ?>
                    @endforeach
                    @endif
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="shelf_floor" class="col-md-3 col-form-label">รับเข้าชั้นของ Shelf :</label>
                <div class="col-md-3">
                  <input class="form-control" type="number" value="{{ @$sRow->shelf_floor }}" id="shelf_floor"
                    name="shelf_floor" required>
                </div>
              </div>


              @endif


              <div class="form-group row">
                @if( empty(@$sRow) )
                <label for="" class="col-md-3 col-form-label">ผู้ดำเนินการ (User Login) :</label>
                @else
                <label for="" class="col-md-3 col-form-label">ผู้ดำเนินการ :</label>
                @endif
                <div class="col-md-8">
                  @if( empty(@$sRow) )
                  <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly
                    style="background-color: #f2f2f2;">
                  <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="recipient">
                  @else
                  <input class="form-control" type="text" value="{{@$Recipient[0]->name}}" readonly
                    style="background-color: #f2f2f2;">
                  <input class="form-control" type="hidden" value="{{ @$sRow->recipient }}" name="recipient">
                  @endif

                </div>
              </div>

              <div class="form-group row">
                <label for="" class="col-md-3 col-form-label">ผู้อนุมัติ (Admin Login) :</label>
                <div class="col-md-8">
                  @if( empty(@$sRow) )
                  <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly
                    style="background-color: #f2f2f2;">
                  <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="approver">
                  @else
                  <input class="form-control" type="text" value="{{ @$Approver[0]->name }}" readonly
                    style="background-color: #f2f2f2;">
                  <input class="form-control" type="hidden" value="{{ @$sRow->approver }}" name="approver">
                  @endif

                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-form-label">สถานะการอนุมัติ :</label>
                <div class="col-md-8 mt-2">
                  <div class="custom-control custom-switch">
                    @if( empty($sRow) )
                    <input type="checkbox" class="custom-control-input" id="customSwitch" name="approve_status"
                      value="1">
                    @else
                    <input type="checkbox" class="custom-control-input" id="customSwitch" name="approve_status"
                      value="1" {{ ( @$sRow->approve_status=='1')?'checked':'' }}>
                    @endif
                    <label class="custom-control-label" for="customSwitch">อนุมัติ / Aproved</label>
                  </div>
                </div>
              </div>



              <div class="form-group mb-0 row">
                <div class="col-md-6">
                  <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/general_receive") }}">
                    <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                  </a>
                </div>
                <div class="col-md-6 text-right">

                  <input type="hidden" name="role_group_id" value="{{@$_REQUEST['role_group_id']}}">
                  <input type="hidden" name="menu_id" value="{{@$_REQUEST['menu_id']}}">

                  @if( @$sRow->approve_status!='1' )
                  <button type="submit" class="btn btn-primary btn-sm waves-effect">
                    <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                  </button>
                  @endif


                </div>
              </div>

          </form>
      </div>

    </div>
  </div>
</div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')

<script type="text/javascript">
  $('#business_location_id_fk').change(function(){
          $(".myloading").show();
          var business_location_id_fk = this.value;
          // alert(warehouse_id_fk);

           if(business_location_id_fk != ''){
             $.ajax({
                  url: " {{ url('backend/ajaxGetBranch') }} ",
                  method: "post",
                  data: {
                    business_location_id_fk:business_location_id_fk,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                   if(data == ''){
                       alert('ไม่พบข้อมูลสาขา !!.');
                       $(".myloading").hide();
                   }else{
                       var layout = '<option value="" selected>- เลือกสาขา -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.b_name+'</option>';
                       });
                       $('#branch_id_fk').html(layout);
                       $('#warehouse_id_fk').html('<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
                       $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                       $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                       $(".myloading").hide();
                   }
                  }
                })
           }else{
            $(".myloading").hide();
           }

      });



 $('#branch_id_fk').change(function(){

          $(".myloading").show();
          var branch_id_fk = this.value;
          // alert(branch_id_fk);

           if(branch_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetWarehouse') }} ",
                  method: "post",
                  data: {
                    branch_id_fk:branch_id_fk,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                   if(data == ''){
                       alert('ไม่พบข้อมูลคลัง !!.');
                       $(".myloading").hide();
                   }else{
                       var layout = '<option value="" selected>- เลือกคลัง -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.w_name+'</option>';
                       });
                       $('#warehouse_id_fk').html(layout);
                       $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                       $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                       $('#shelf_floor').val(1);
                       $(".myloading").hide();
                   }
                  }
                })
           }else{
               $('#warehouse_id_fk').html('<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
               $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
               $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
               $('#shelf_floor').val(1);
               $(".myloading").hide();
           }

      });


       $('#warehouse_id_fk').change(function(){

          $(".myloading").show();
          var warehouse_id_fk = this.value;
          // alert(warehouse_id_fk);

           if(warehouse_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetZone') }} ",
                  method: "post",
                  data: {
                    warehouse_id_fk:warehouse_id_fk,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                   if(data == ''){
                       alert('ไม่พบข้อมูล Zone !!.');
                       $(".myloading").hide();
                   }else{
                       var layout = '<option value="" selected>- เลือก Zone -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.z_name+'</option>';
                       });
                       $('#zone_id_fk').html(layout);
                       $('#shelf_id_fk').html('กรุณาเลือกโซนก่อน');
                       $(".myloading").hide();
                   }
                  }
                })
           }else{
            $(".myloading").hide();
           }

      });



       $('#zone_id_fk').change(function(){
          $(".myloading").show();
          var zone_id_fk = this.value;
          // alert(zone_id_fk);

           if(zone_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetShelf') }} ",
                  method: "post",
                  data: {
                    zone_id_fk:zone_id_fk,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                   if(data == ''){
                       alert('ไม่พบข้อมูล Shelf !!.');
                       $(".myloading").hide();
                   }else{
                       var layout = '<option value="" selected>- เลือก Shelf -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.s_name+'</option>';
                       });
                       $('#shelf_id_fk').html(layout);
                       $(".myloading").hide();
                   }
                  }
                })
           }else{
            $(".myloading").hide();
           }

      });


       $('#product_id_fk').change(function(){
        $(".myloading").show();

      //     var product_id_fk = this.value;
      //     // alert(zone_id_fk);

      //      if(product_id_fk != ''){
      //        $.ajax({
      //             url: " {{ url('backend/ajaxGetLotnumber') }} ",
      //             method: "post",
      //             data: {
      //               product_id_fk:product_id_fk,
      //               "_token": "{{ csrf_token() }}",
      //             },
      //             success:function(data)
      //             {
      //              if(data == ''){
      //                  // alert('ไม่พบข้อมูล Lot number !!.');
      //                  // var layout = '<option value="" selected>- เลือก Lot number -</option>';
      //                  // $('#lot_number').html(layout);
      //                  $('.lot_number_select').prop('required',false);
      //                  $('.lot_number_select').attr("disabled", true);
      //                  $('.lot_number_select').select2().next().hide();
      //                  $('.lot_number_input').val('');
      //                  $('.lot_number_input').show();
      //                  $('.lot_number_input').prop('required',true);
      //                  $('.lot_number_input').focus();
      //              }else{
      //                  var layout = '<option value="" selected>- เลือก Lot number -</option>';
      //                  $.each(data,function(key,value){
      //                   layout += '<option value='+value.lot_number+'>'+value.lot_number+'</option>';
      //                  });
      //                  $('#lot_number').html(layout);
      //                  $('.lot_number_input').prop('required',false);
      //                  $('.lot_number_input').hide();
      //              }
      //             }
      //           })
      //      }

         $('#lot_number_auto').val('');
          setTimeout(function(){
             $('#lot_number_auto').focus();
              $(".myloading").hide();
          },1000);

      });

</script>

<script type="text/javascript">
  $(document).ready(function() {
      // var product_id_fk =$('#product_id_fk').val();
      // var lot_number = "{{@$sRow->lot_number}}";
      // if(product_id_fk != ''){
      //      $('.lot_number_input').show();
      //      $('.lot_number_input').val(lot_number);
      //      $('.lot_number_select').prop('required',false);
      //      $('.lot_number_select').attr("disabled", true);
      //      $('.lot_number_select').select2().next().hide();
      // }
  });
</script>
<!-- Script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- jQuery UI -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
  $( function() {

 // Single Select
 $( "#lot_number_auto" ).autocomplete({
  source: function( request, response ) {
   // Fetch data
         var product_id_fk = $('#product_id_fk').val();
         $.ajax({
          url: " {{ url('backend/ajaxGetLotnumber2') }} ",
          method: "post",
          dataType: "json",
          data: {
            product_id_fk:product_id_fk,
            "_token": "{{ csrf_token() }}",
          },
          success:function(data){
             console.log(data);
             response( data );
          }
         });
        },
        // select: function (event, ui) {
        //    console.log(ui.item);
        //    $('#lot_number_auto').val(ui.item.value);
        //    return false;
        // },
        // focus: function(event, ui){
        //    $( "#lot_number_auto" ).val( ui.item.value );
        //    return false;
        //  },
       });


        $(document).on('change', '#lot_number_auto', function(event) {
            var this_v = $(this).val();
            // alert(this_v);
             var product_id_fk = $('#product_id_fk').val();
             $.ajax({
              url: " {{ url('backend/ajaxGetLotnumber2') }} ",
              method: "post",
              dataType: "json",
              data: {
                product_id_fk:product_id_fk,
                "_token": "{{ csrf_token() }}",
              },
              success:function(data){
                 console.log(data);
                 $.each(data, function( index, value ) {
                    if(this_v==value.value){
                      $('#lot_expired_date').val(value.lot_expired_date);
                      $('#lot_expired_date').prop('readonly',true);
                      $('#lot_expired_date').prop('type','text');
                    }else{
                      $('#lot_expired_date').val('');
                      $('#lot_expired_date').prop('readonly',false);
                      $('#lot_expired_date').prop('required',true);
                      $('#lot_expired_date').prop('type','date');
                    }
                 });
              }
             });
        });




 });
</script>
@endsection
