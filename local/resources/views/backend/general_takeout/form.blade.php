@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> นำสินค้าออก </h4>
        </div>
    </div>
</div>
<!-- end page title -->

  <?php
    $sPermission = \Auth::user()->permission ;
    $menu_id = @$_REQUEST['menu_id'];
    $role_group_id = @$_REQUEST['role_group_id'];
    if($sPermission==1){
      $sC = '';
      $sU = '';
      $sD = '';
      $sA = '';
    }else{
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sA = @$menu_permit->can_answer==1?'':'display:none;';
    }

      //   echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;

   ?>
<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty(@$sRow) )
              <form action="{{ route('backend.general_takeout.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.general_takeout.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

<?php //echo \Auth::user()->business_location_id_fk ; ?>
<?php //dd(@$sBusiness_location); ?>

                      <div class="myBorder">

                          <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label"> Business Location : * </label>
                            <div class="col-md-8">

                            @if($sPermission==1)

                                     <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " required="" >

                                        <option value="">-Business Location-</option>
                                        @if(@$sBusiness_location)
                                          @foreach(@$sBusiness_location AS $r)
                                          <option value="{{$r->id}}" {{ (@$r->id==@$sRow->business_location_id_fk)?'selected':'' }} >
                                            {{$r->txt_desc}}
                                          </option>
                                          @endforeach
                                        @endif

                                     </select>

                             @else

                                       @if( empty(@$sRow) )
                                         <input type="hidden" name="business_location_id_fk" value="{{@\Auth::user()->business_location_id_fk}}">
                                       @else
                                        <input type="hidden" name="business_location_id_fk" value="{{@$sRow-business_location_id_fk}}">
                                       @endif

                                      <select  class="form-control select2-templating " disabled="" >
                                         @if(@$sBusiness_location)
                                            @foreach(@$sBusiness_location AS $r)
                                            <?=$business_location=(@$sRow->business_location_id_fk?@$sRow->business_location_id_fk : @\Auth::user()->business_location_id_fk)?>
                                            <option value="{{$r->id}}" {{ ( @$r->id==$business_location) ? 'selected': ''  }} >
                                                {{$r->txt_desc}}
                                              </option>
                                            @endforeach
                                          @endif
                                      </select>

                             @endif

                            </div>
                          </div>


                          <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label"> สาขา : * </label>
                            <div class="col-md-8">


                            @if($sPermission==1)

                                  <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating" required >
                                     <option value="" selected>กรุณาเลือก Business Location ก่อน</option>
                                  </select>

                             @else

                                       @if( empty(@$sRow) )
                                         <input type="hidden" name="branch_id_fk" value="{{@\Auth::user()->branch_id_fk}}">
                                       @else
                                        <input type="hidden" name="branch_id_fk" value="{{@$sRow-branch_id_fk}}">
                                       @endif

                                      <select  class="form-control select2-templating" disabled="" >
                                         @if(@$sBranchs)
                                            @foreach(@$sBranchs AS $r)
                                            <?=$branch_id_fk=(@$sRow->branch_id_fk?@$sRow->branch_id_fk : @\Auth::user()->branch_id_fk)?>
                                            <option value="{{$r->id}}" {{ ( @$r->id==$branch_id_fk) ? 'selected': ''  }} >
                                                {{$r->b_name}}
                                              </option>
                                            @endforeach
                                          @endif
                                      </select>

                             @endif




                            </div>
                          </div>



                          <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label"> สาเหตุที่นำออก : * </label>
                            <div class="col-md-8">
                              <select name="product_out_cause_id_fk" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                  @if(@$Product_out_cause)
                                    @foreach(@$Product_out_cause AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->product_out_cause_id_fk)?'selected':'' }} >
                                        {{$r->txt_desc}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>


                          <div class="form-group row">
                            <label for="receive_person" class="col-md-3 col-form-label">ผู้รับ (นำออกไปให้ใคร) : *</label>
                            <div class="col-md-8">
                              <input class="form-control" type="text" value="{{ @$sRow->receive_person }}" name="receive_person" required >
                            </div>
                          </div>


                          <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label"> รหัสสินค้า : ชื่อสินค้า : * </label>
                            <div class="col-md-8">

                              <select id="product_id_fk" name="product_id_fk" class="form-control select2-templating " required >

                                <option value="">Select</option>
                                  @if(@$Products)
                                    @foreach(@$Products AS $r)
                                      <option value="{{@$r->product_id}}" {{ (@$r->product_id==@$sRow->product_id_fk)?'selected':'' }} >
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

                                @if( empty(@$sRow) )
                                   <select id="lot_number" class="form-control select2-templating " >
                                     <option disabled selected>กรุณาเลือกสินค้าก่อน</option>
                                  </select>
                                @else
                                  <select id="lot_number" class="form-control select2-templating " >
                                      <option {{ (@$Check_stock[0]->lot_number==@$sRow->lot_number)?'selected':'' }} >
                                        {{@$Check_stock[0]->lot_number}}
                                      </option>
                                  </select>
                                @endif

                              <input type="hidden" id="stocks_id_fk" value="{{@$Check_stock[0]->id}}" >
                              <input type="hidden" name="lot_number" id="lot_number_txt" value="{{@$sRow->lot_number}}" >
                              <input type="hidden" name="lot_expired_date" id="lot_expired_date" value="{{@$sRow->lot_expired_date}}" >

                            </div>
                          </div>


                          <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Product details : </label>
                            <div class="col-md-8">
                              <div id="div_product_details"></div>
                            </div>
                          </div>

                           <div class="form-group row">
                            <label for="amt" class="col-md-3 col-form-label">จำนวนคงคลัง :  </label>
                            <div class="col-md-3">
                              <input class="form-control" type="text" id="amt_in_stock" readonly >
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="amt" class="col-md-3 col-form-label">จำนวนที่นำออก : * </label>
                            <div class="col-md-3">
                              <input class="form-control" type="number" value="{{ @$sRow->amt }}" id="amt"  name="amt" required >
                            </div>
                          </div>


                          <div class="form-group row">
                                @if( empty(@$sRow) )
                                <label for="" class="col-md-3 col-form-label">ผู้ดำเนินการ (User Login) :</label>
                                @else
                                <label for="" class="col-md-3 col-form-label">ผู้ดำเนินการ  :</label>
                                @endif
                                <div class="col-md-8">
                                  @if( empty(@$sRow) )
                                    <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                      <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="recipient" >
                                      @else
                                        <input class="form-control" type="text" value="{{@$Recipient[0]->name}}" readonly style="background-color: #f2f2f2;" >
                                      <input class="form-control" type="hidden" value="{{ @$sRow->recipient }}" name="recipient" >
                                   @endif

                                </div>
                            </div>

                           <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label">ผู้อนุมัติ (Admin Login) :</label>
                                <div class="col-md-8">
                                  @if( empty(@$sRow) )
                                    <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                      <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="approver" >
                                      @else
                                        <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                      <input class="form-control" type="hidden" value="{{ @$sRow->approver }}" name="approver" >
                                   @endif

                                </div>
                            </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">สถานะการอนุมัติ :</label>
                    <div class="col-md-8 mt-2">
                      <div class="custom-control custom-switch">
                        @if( empty($sRow) )
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="approve_status" value="1"  >
                        @else
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="approve_status" value="1" {{ ( @$sRow->approve_status=='1')?'checked':'' }}>
                        @endif
                          <label class="custom-control-label" for="customSwitch">อนุมัติ / Aproved</label>
                      </div>
                    </div>
                </div>



                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/general_takeout") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>
                  </div>
                  <div class="col-md-6 text-right">

                      <input type="hidden" name="role_group_id" value="{{@$_REQUEST['role_group_id']}}" >
                      <input type="hidden" name="menu_id" value="{{@$_REQUEST['menu_id']}}" >

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
                   }else{
                       var layout = '<option value="" selected>- เลือกสาขา -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.b_name+'</option>';
                       });
                       $('#branch_id_fk').html(layout);
                       $('#warehouse_id_fk').html('<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
                       $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                       $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                   }
                  }
                })
           }

      });



 $('#branch_id_fk').change(function(){

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
                   }else{
                       var layout = '<option value="" selected>- เลือกคลัง -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.w_name+'</option>';
                       });
                       $('#warehouse_id_fk').html(layout);
                       $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                       $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                       $('#shelf_floor').val(1);
                   }
                  }
                })
           }else{
               $('#warehouse_id_fk').html('<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
               $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
               $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
               $('#shelf_floor').val(1);
           }

      });


       $('#warehouse_id_fk').change(function(){

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
                   }else{
                       var layout = '<option value="" selected>- เลือก Zone -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.z_name+'</option>';
                       });
                       $('#zone_id_fk').html(layout);
                       $('#shelf_id_fk').html('กรุณาเลือกโซนก่อน');
                   }
                  }
                })
           }

      });



       $('#zone_id_fk').change(function(){

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
                   }else{
                       var layout = '<option value="" selected>- เลือก Shelf -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.s_name+'</option>';
                       });
                       $('#shelf_id_fk').html(layout);
                   }
                  }
                })
           }

      });


    $('#product_id_fk').change(function(){

          $(".myloading").show();

          var product_id_fk = this.value;
          var business_location_id_fk = $("#business_location_id_fk").val();
          var branch_id_fk = $("#branch_id_fk").val();
          // alert(business_location_id_fk);
          // alert(branch_id_fk);
          $('#amt').val('');
          $('#amt_in_stock').val('');
          $('#lot_number_txt').val('');
          $('#div_product_details').html('');


           if(product_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetLotnumber') }} ",
                  method: "post",
                  data: {
                    business_location_id_fk:business_location_id_fk,
                    branch_id_fk:branch_id_fk,
                    product_id_fk:product_id_fk,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                   if(data == ''){
                       alert('ไม่พบข้อมูล Lot number !!.');
                       var layout = '<option value="" selected>- เลือก Lot number -</option>';
                       $('#lot_number').html(layout);
                   }else{
                       var layout = '<option value="" selected>- เลือก Lot number -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.lot_number+' [Expired:'+value.lot_expired_date+']</option>';
                        $('#lot_number_txt').val(value.lot_number);

                       });
                       $('#lot_number').html(layout);
                   }

                   $(".myloading").hide();


                  }
                })
           }else{
                 var layout = '<option value="" selected>- เลือก Lot number -</option>';
                 $('#lot_number').html(layout);
                 $(".myloading").hide();
           }

      });


       $('#lot_number').change(function(){

                 $(".myloading").show();

                 var id = this.value;
                 var product_id_fk = $('#product_id_fk').val();
                 // alert(lot_number+":"+product_id_fk);
                 if(id==''){
                   $(".myloading").hide();
                   $('#div_product_details').html('');
                   return false;
                 }
                 if(product_id_fk==''){
                   alert("กรุณาเลือกสินค้า ?");
                   $('#product_id_fk').select2('open');
                   $(".myloading").hide();
                   $('#div_product_details').html('');
                   return false;
                 }

                 $('#amt').val('');
                 $('#amt_in_stock').val('');

                 if(product_id_fk != ''){
                   $.ajax({
                        url: " {{ url('backend/ajaxGetAmtInStock') }} ",
                        method: "post",
                        data: {
                          id:id,
                          product_id_fk:product_id_fk,
                          "_token": "{{ csrf_token() }}",
                        },
                        success:function(data)
                        {

                         // if(data == ''){
                         //     $('#amt_in_stock').val(0);
                         // }else{
                         //     $('#amt_in_stock').val(data);
                         //     // localStorage.setItem('amt_in_stock', data);
                         // }

                         $.each(data,function(key,value){
                           $('#amt_in_stock').val(value.amt);
                           $('#div_product_details').html(
                            "วันล๊อตหมดอายุ : "+value.lot_expired_date+'<br/>'+"หน่วยนับ : "+value.product_unit+
                            '<br/>'+"Business Location : "+value.business_location+
                            '<br/>'+"สาขา : "+value.branch+
                            '<br/>'+"คลัง : "+value.w_name+
                            '<br/>'+"โซน : "+value.z_name+
                            '<br/>'+"Shelf : "+value.s_name+
                            '<br/>'+"ชั้น : "+value.shelf_floor
                            );

                           $('#lot_expired_date').val(value.lot_expired_date);

                         });

                         $(".myloading").hide();

                        }
                      })
                 }else{
                       $('#amt_in_stock').val(0);
                       $(".myloading").hide();
                 }

            });

        // if(localStorage.getItem('amt_in_stock')){
        //     $('#amt_in_stock').val(localStorage.getItem('amt_in_stock'));
        // }



         $('#amt').change(function(){

                 var amt = parseInt(this.value);
                 var amt_in_stock = parseInt($('#amt_in_stock').val());
                 if(amt>amt_in_stock){
                   alert("กรุณาตรวจสอบ จำนวนนำออก มีมากกว่า จำนวนคงคลัง ?");
                   $('#amt').val('');
                   $('#amt').focus();
                   return false;
                 }


            });


</script>

<script>
  $(document).ready(function() {
       var id = $('#stocks_id_fk').val();
       var product_id_fk = $('#product_id_fk').val();
       // alert(id+":"+product_id_fk);

       if(lot_number!='' && product_id_fk != ''){
          $.ajax({
                url: " {{ url('backend/ajaxGetAmtInStock') }} ",
                method: "post",
                data: {
                  id:id,
                  product_id_fk:product_id_fk,
                  "_token": "{{ csrf_token() }}",
                },
                success:function(data)
                {

                 $.each(data,function(key,value){
                   $('#amt_in_stock').val(value.amt);
                   $('#div_product_details').html(
                    "วันล๊อตหมดอายุ : "+value.lot_expired_date+'<br/>'+"หน่วยนับ : "+value.product_unit+
                    '<br/>'+"Business Location : "+value.business_location+
                    '<br/>'+"สาขา : "+value.branch+
                    '<br/>'+"คลัง : "+value.w_name+
                    '<br/>'+"โซน : "+value.z_name+
                    '<br/>'+"Shelf : "+value.s_name+
                    '<br/>'+"ชั้น : "+value.shelf_floor
                    );
                 });

                 $(".myloading").hide();

                }
              })
       }
  });

</script>


@endsection

