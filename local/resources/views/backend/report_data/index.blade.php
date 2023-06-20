@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style>
    .select2-selection {height: 34px !important;margin-left: 3px;}
</style>
@endsection

@section('content')
<div class="myloading"></div>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">รายงานต่างๆ</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-body">

            {{-- <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{url('backend/report_data/inventory')}}">
                                <i class="fas fa-file"></i>
                                <b>รายงานสินค้าคงคลัง</b>
                            </a>
                        </div>
                    </div>
            </div> --}}

            <form action="{{ url('backend/report_data/export_pdf') }}" target="_blank" method="POST" enctype="multipart/form-data" autocomplete="off">
              {{ csrf_field() }}

            <div class="row" >
                <div class="col-md-6 " >
                    <div class="form-group row">
                      <label for="" class="col-md-3 col-form-label">รายงาน : </label>
                      <div class="col-md-9">
                        <select id="report_data" name="report_data" class="form-control select2-templating " required>
                            <option value="">-เลือกรายงาน-</option>
                            <option value="inventory_in">รายงานรับเข้าสินค้าทั่วไป</option>
                            <option value="inventory_in_po">รายงานรับเข้าสินค้า PO</option>
                            {{-- <option value="inventory_in">รายงานรับเข้าสินค้า</option>
                            <option value="inventory_out">รายงานจ่ายสินค้า</option>
                            <option value="inventory_borrow">รายงานเบิก-ยืม</option>
                            <option value="inventory_claim">รายงานส่งสินค้าเคลมโรงงาน</option> --}}
                            <option value="inventory_remain">รายงานสินค้าคงเหลือ</option>
                            <option value="sale_report">รายงานการขายสินค้า</option>
                          </select>
                      </div>
                    </div>
                  </div>
            </div>

            <div class="row" >
                <div class="col-md-6 " >
                    <div class="form-group row">
                      <label for="" class="col-md-3 col-form-label">วันที่เริ่ม : </label>
                      <div class="col-md-9">
                        <input id="startDate_data" class="form-control" name="startDate_data" value="{{date('Y-m-d')}}" autocomplete="off" value="{{ @$sd }}" required />
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6 " >
                    <div class="form-group row">
                        <label for="zone_id_fk" class="col-md-2 col-form-label"> วันที่สิ้นสุด : </label>
                        <div class="col-md-10">
                            <input id="endDate_data" class="form-control" name="endDate_data" value="{{date('Y-m-d')}}" autocomplete="off" value="{{ @$ed }}" required />
                        </div>
                      </div>
                </div>
            </div>

            <div class="row" >

                <div class="col-md-6 " >
                     <div class="form-group row">
                       <label for="" class="col-md-3 col-form-label">Business Location : </label>
                       <div class="col-md-9">
                         <?php $dis01 = !empty(@$sRow->condition_business_location)?'disabled':'' ?>


                             <?php
                              // if(@\Auth::user()->permission==1){
                              ?>

                                      <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " required>
                                           <option value="">-Business Location-</option>
                                           @if(@$sBusiness_location)
                                             @foreach(@$sBusiness_location AS $r)
                                             <option value="{{$r->id}}" >
                                               {{$r->txt_desc}}
                                             </option>
                                             @endforeach
                                           @endif
                                         </select>

                             <?php
                            //  }else{
                              ?>

                                      {{-- <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " disabled >
                                       <option value="">-Business Location-</option>
                                       @if(@$sBusiness_location)
                                         @foreach(@$sBusiness_location AS $r)
                                         @IF(empty(@$sRow->condition_business_location))
                                         <option value="{{$r->id}}" {{ (@$r->id=='1')?'selected':'' }} >
                                           {{$r->txt_desc}}
                                         </option>
                                         @ELSE
                                         <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_business_location)?'selected':'' }} >
                                           {{$r->txt_desc}}
                                         </option>
                                         @ENDIF
                                         @endforeach
                                       @endif
                                     </select> --}}

                             <?php
                              // }
                             ?>





                       </div>
                     </div>
                   </div>

                   <div class="col-md-6 " >
                     <div class="form-group row">
                           <label for="branch_id_fk" class="col-md-2 col-form-label"> สาขา : </label>
                           <div class="col-md-10">

                             <?php
                              // if(@\Auth::user()->permission==1){
                                ?>
                                 <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating "  >
                                   <option value="">-เลือก Business Location ก่อน-</option>
                                 </select>
                             <?php
                            // }else{
                              ?>
                                 {{-- <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating " disabled="" >
                                    <option value="" selected>กรุณาเลือก Business Location ก่อน</option>
                                    @if(@$sBranchs)
                                       @foreach(@$sBranchs AS $r)
                                         <option value="{{$r->id}}" {{ (@$r->id==(\Auth::user()->branch_id_fk))?'selected':'' }} >
                                           {{$r->b_name}}
                                         </option>
                                       @endforeach
                                     @endif
                                 </select> --}}
                             <?php
                            //  }
                              ?>

                           </div>
                         </div>
                   </div>


             </div>


                <div class="row" >
                  <div class="col-md-6 " >
                     <div class="form-group row">
                          <label for="warehouse_id_fk" class="col-md-3 col-form-label"> คลัง : </label>
                          <div class="col-md-9">

                            <?php if(@\Auth::user()->permission==1){ ?>

                               <select id="warehouse_id_fk"  name="warehouse_id_fk" class="form-control select2-templating "  >
                                   <option disabled selected>กรุณาเลือกสาขาก่อน</option>
                                </select>

                            <?php }else{ ?>

                                <select id="warehouse_id_fk"  name="warehouse_id_fk" class="form-control select2-templating "  >
                                    <option value="" >-select-</option>
                                    @if(@$Warehouse)
                                      @foreach(@$Warehouse AS $r)
                                        <option value="{{$r->id}}"  >
                                          {{$r->w_name}}
                                        </option>
                                      @endforeach
                                    @endif
                                </select>

                            <?php } ?>


                          </div>
                        </div>
                  </div>

                  <div class="col-md-6 " >
                      <div class="form-group row">
                          <label for="zone_id_fk" class="col-md-2 col-form-label"> Zone : </label>
                          <div class="col-md-10">

                            <?php if(!empty(@$sRow->condition_zone)){ ?>
                                <select class="form-control select2-templating " disabled="" >
                                    @if(@$Zone)
                                      @foreach(@$Zone AS $r)
                                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_zone)?'selected':'' }} >
                                          {{$r->z_name}}
                                        </option>
                                      @endforeach
                                    @endif
                                </select>
                            <?php }else{ ?>
                                <select id="zone_id_fk"  name="zone_id_fk" class="form-control select2-templating "  >
                                   <option disabled selected>กรุณาเลือกคลังก่อน</option>
                                </select>
                            <?php } ?>

                          </div>
                        </div>
                  </div>
                </div>

                <div class="row" >
                  <div class="col-md-6 " >
                     <div class="form-group row">
                          <label for="shelf_id_fk" class="col-md-3 col-form-label"> Shelf : </label>
                          <div class="col-md-9">

                            <?php if(!empty(@$sRow->condition_shelf)){ ?>
                                <select class="form-control select2-templating " disabled="" >
                                    @if(@$Shelf)
                                      @foreach(@$Shelf AS $r)
                                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_shelf)?'selected':'' }} >
                                          {{$r->s_name}}
                                        </option>
                                      @endforeach
                                    @endif
                                </select>
                            <?php }else{ ?>
                                <select id="shelf_id_fk" name="shelf_id_fk" class="form-control select2-templating " >
                                  <option disabled selected>กรุณาเลือกโซนก่อน</option>
                                </select>
                            <?php } ?>

                          </div>
                        </div>
                  </div>

                  <div class="col-md-6 " >
                      <div class="form-group row">
                          <label for="shelf_floor" class="col-md-2 col-form-label"> ชั้น : </label>
                          <div class="col-md-10">
                            <?php $dis02 = !empty(@$sRow->condition_shelf_floor)?'disabled':'' ?>
                            <select id="shelf_floor" name="shelf_floor" class="form-control select2-templating " <?=$dis02?> >
                               <option value="">-select-</option>
                               <option value="1" {{ (@$sRow->condition_shelf_floor==1)?'selected':'' }} >1</option>
                               <option value="2" {{ (@$sRow->condition_shelf_floor==2)?'selected':'' }} >2</option>
                               <option value="3" {{ (@$sRow->condition_shelf_floor==3)?'selected':'' }} >3</option>
                               <option value="4" {{ (@$sRow->condition_shelf_floor==4)?'selected':'' }} >4</option>
                               <option value="5" {{ (@$sRow->condition_shelf_floor==5)?'selected':'' }} >5</option>
                               <option value="6" {{ (@$sRow->condition_shelf_floor==6)?'selected':'' }} >6</option>
                               <option value="7" {{ (@$sRow->condition_shelf_floor==7)?'selected':'' }} >7</option>
                               <option value="8" {{ (@$sRow->condition_shelf_floor==8)?'selected':'' }} >8</option>
                               <option value="9" {{ (@$sRow->condition_shelf_floor==9)?'selected':'' }} >9</option>
                               <option value="10" {{ (@$sRow->condition_shelf_floor==10)?'selected':'' }} >10</option>
                            </select>
                          </div>
                        </div>
                  </div>
                </div>

       <div class="row" >

               {{-- <div class="col-md-6 " >
                    <div class="form-group row">
                      <label for="" class="col-md-3 col-form-label"> สินค้า : </label>
                      <div class="col-md-9">
                         <php $dis03 = !empty(@$sRow->condition_product)?'disabled':'' ?>
                         <select name="product" id="product" class="form-control select2-templating " <?=$dis03?> >
                              <option value="">-รหัสสินค้า : ชื่อสินค้า-</option>
                                 @if(@$Products)
                                      @foreach(@$Products AS $r)
                                        <option value="{{@$r->product_id}}" {{ (@$r->product_id==@$sRow->condition_product)?'selected':'' }} >
                                          {{@$r->product_code." : ".@$r->product_name}}
                                        </option>
                                      @endforeach
                                    @endif
                            </select>
                      </div>
                    </div>
                  </div> --}}


                  {{-- <div class="col-md-6 " >
                    <div class="form-group row">
                      <label for="lot_number" class="col-md-2 col-form-label"> Lot-No. : </label>
                      <div class="col-md-10">
                           <php $dis04 = !empty(@$sRow->condition_lot_number)?'disabled':'' ?>
                           <select name="lot_number" id="lot_number" class="form-control select2-templating " <?=$dis04?> >
                              <option value="">-Lot Number-</option>
                                  @if(@$lot_number)
                                    @foreach(@$lot_number AS $r)
                                      <option value="{{@$r->lot_number}}" {{ (@$r->lot_number==@$sRow->condition_lot_number)?'selected':'' }} >
                                        {{@$r->lot_number}}
                                      </option>
                                    @endforeach
                                  @endif
                            </select>
                      </div>
                    </div>
                  </div> --}}

                </div>


    <div class="row" >
                  <div class="col-md-6 " >
                     <div class="form-group row" style="display:none;">
                          <label for="ref_code" class="col-md-3 col-form-label">Lot expired date : </label>
                          <div class="col-md-9 d-flex">
                            <?php
                            //  $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
                           //   $last_day_this_month  = date('Y-m-t');
                             ?>
                             <input id="start_date"  autocomplete="off" placeholder="Begin"  style="border: 1px solid grey;"  />
                             <input id="end_date"  autocomplete="off" placeholder="End"  style="border: 1px solid grey;"  />

                          </div>
                        </div>
                  </div>

                  <div class="col-md-6 " >
                      <div class="form-group row d-flex ">
                        <label for="ref_code" class="col-md-2 col-form-label">  </label>
                          <div class="col-md-10">


                        {{-- <a class="btn btn-info btn-sm btnSearch " href="#" style="font-size: 14px !important;" >
                          <i class="bx bx-file align-middle "></i> ออกรายงาน Excel
                        </a> --}}

                        <button class="btn btn-success btn-sm " type="submit" style="font-size: 14px !important;" >
                          <i class="bx bx-file align-middle "></i> ออกรายงาน PDF
                        </button>
<!--
                       <a class="btn btn-info btn-sm btnStockMovement " href="#" style="font-size: 14px !important;float: right;" >
                          <i class="bx bx-cog align-middle "></i> Process Stock movement
                        </a>  -->


                       </div>
                     </div>
                  </div>


              </div>

            </form>

        </div>
    </div>
</div>

@endsection

@section('script')

<script src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js" type="text/javascript" charset="utf-8" async defer></script>

  <script>

        $(document).ready(function() {

            $(document).on('click', '.btnSearch', function(event) {
                  event.preventDefault();

                  var report_data = $('#report_data').val();
                  var startDate_data = $('#startDate_data').val();
                  var endDate_data = $('#endDate_data').val();

                  var business_location_id_fk = $('#business_location_id_fk').val();
                  var branch_id_fk = $('#branch_id_fk').val();
                  var start_date = $('#start_date').val();
                  var end_date = $('#end_date').val();

                //   var product = $('#product').val();
                //   var lot_number = $('#lot_number').val();
                  var warehouse_id_fk = $('#warehouse_id_fk').val();
                  var zone_id_fk = $('#zone_id_fk').val();
                  var shelf_id_fk = $('#shelf_id_fk').val();
                  var shelf_floor = $('#shelf_floor').val();
                  if(report_data==''){
                    alert('กรุณาเลือกรายงาน');
                    return false;
                  }

                  if(business_location_id_fk==''){
                    alert('กรุณาเลือก Business Location');
                    return false;
                  }

                  // if(branch_id_fk==''){
                  //   alert('กรุณาเลือกสาขา');
                  //   return false;
                  // }
                  $(".myloading").show();
                  $.ajax({

                            type:'POST',
                            url: " {{ url('backend/report_data/export_excel') }} ",
                            data:{ _token: '{{csrf_token()}}',
                            report_data: report_data,
                            startDate_data: startDate_data,
                            endDate_data: endDate_data,
                            business_location_id_fk: business_location_id_fk,
                            branch_id_fk: branch_id_fk,
                            start_date: start_date,
                            end_date: end_date,
                            // product: product,
                            // lot_number: lot_number,
                            warehouse_id_fk: warehouse_id_fk,
                            zone_id_fk: zone_id_fk,
                            shelf_id_fk: shelf_id_fk,
                            shelf_floor: shelf_floor,
                             },
                            success:function(data){
                                setTimeout(function(){
                                    var url='local/public/excel_files_new/'+data.path;
                                    // var url = data.path;
                                    window.open(url, 'Download');
                                    $(".myloading").hide();
                                },3000);

                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(JSON.stringify(jqXHR));
                                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                $(".myloading").hide();
                            }
                            });

            });

        });

        // $(document).on('click','.btnExportElsx',function(event){
        //             /* Act on the event */
        //             $(".myloading").show();
        //             var id = $(this).data('id');
        //             $.ajax({

        //                    type:'POST',
        //                    url: " {{ url('backend/excelExportConsignment') }} ",
        //                    data:{ _token: '{{csrf_token()}}',id:id },
        //                     success:function(data){
        //                          console.log(data);
        //                          // location.reload();
        //                          setTimeout(function(){
        //                             var url='local/public/excel_files/consignments.xlsx';
        //                             window.open(url, 'Download');
        //                             $(".myloading").hide();
        //                         },3000);

        //                       },
        //                     error: function(jqXHR, textStatus, errorThrown) {
        //                         console.log(JSON.stringify(jqXHR));
        //                         console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        //                         $(".myloading").hide();
        //                     }
        //                 });
        //         });

    </script>

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

          $("#spinner_frame").show();

          var branch_id_fk = this.value;
          // alert(warehouse_id_fk);

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
                       // alert('ไม่พบข้อมูลคลัง !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือกคลัง -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.w_name+'</option>';
                       });
                       $('#warehouse_id_fk').html(layout);
                       $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                       $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                   }

                   $("#spinner_frame").hide();

                  }
                })
           }

      });


       $('#warehouse_id_fk').change(function(){

        $("#spinner_frame").show();

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
                       // alert('ไม่พบข้อมูล Zone !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือก Zone -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.z_name+'</option>';
                       });
                       $('#zone_id_fk').html(layout);
                       $('#shelf_id_fk').html('กรุณาเลือกโซนก่อน');
                   }

                   $("#spinner_frame").hide();

                  }
                })
           }

      });


       $('#zone_id_fk').change(function(){

          $("#spinner_frame").show();

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
                       // alert('ไม่พบข้อมูล Shelf !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือก Shelf -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.s_name+'</option>';
                       });
                       $('#shelf_id_fk').html(layout);
                   }
                   $("#spinner_frame").hide();

                  }
                })
           }

      });


       $('#product').change(function(){

          var product_id_fk = this.value;
          // alert(zone_id_fk);

           if(product_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetLotnumber') }} ",
                  method: "post",
                  data: {
                    product_id_fk:product_id_fk,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                   if(data == ''){
                       // alert('ไม่พบข้อมูล Lot number !!.');
                       var layout = '<option value="" selected>- เลือก Lot number -</option>';
                       $('#lot_number').html(layout);
                   }else{
                       var layout = '<option value="" selected>- เลือก Lot number -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.lot_number+'>'+value.lot_number+'</option>';
                       });
                       $('#lot_number').html(layout);
                   }
                  }
                })
           }

      });


</script>


    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#startDate').datepicker({
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // setDate: today,
            // minDate: today,
            // maxDate: function () {
            //     return $('#endDate').val();
            // }
        });

        $('#endDate').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                // return $('#start_date').val();
            }
        });

         $('#startDate').change(function(event) {
         	if($('#endDate').val()<$(this).val()){
           		$('#endDate').val($(this).val());
       		}
         });

         $('#endDate').change(function(event) {
         	if($('#startDate').val()>$(this).val()){
           		$('#startDate').val($(this).val());
       		}
         });

        //

        $('#startDate_data').datepicker({
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // setDate: today,
            // minDate: today,
            // maxDate: function () {
            //     return $('#endDate').val();
            // }
        });

        $('#endDate_data').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                // return $('#start_date').val();
            }
        });

         $('#startDate_data').change(function(event) {
         	if($('#endDate_data').val()<$(this).val()){
           		$('#endDate_data').val($(this).val());
       		}
         });

         $('#endDate_data').change(function(event) {
         	if($('#startDate_data').val()>$(this).val()){
           		$('#startDate_data').val($(this).val());
       		}
         });

        $(document).ready(function() {

          localStorage.clear();

            // $(document).on('click', '.btnAdd', function(event) {
            //   localStorage.clear();
            // });

        });


</script>


@endsection
