@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style type="text/css">
  .sorting_disabled {background-color: #cccccc !important;font-weight: bold;}

  .form-group {
     /*margin-bottom: 1rem; */
     margin-bottom: 0rem  !important;
  }

</style>
@endsection

@section('content')

<div class="myloading"></div>

<div class="row">
    <div class="col-md-12" style="">
        <div id="spinner_frame"
            style="display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            z-index: 9999;
            "><p align="center">
                <img src="{{ asset('backend/images/preloader_big.gif') }}">
            </p></div>
        </div>
    </div>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Check Notify </h4>
        </div>
    </div>
</div>
<!-- end page title -->
  <?php
      $sPermission = \Auth::user()->permission ;
      $menu_id = Session::get('session_menu_id');
    if($sPermission==1){
      $sC = '';
      $sU = '';
      $sD = '';
    }else{
      $role_group_id = \Auth::user()->role_group_id_fk;
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sU = @$menu_permit->u==1?'':'display:none;';
      $sD = @$menu_permit->d==1?'':'display:none;';
    }
   ?>



<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


              <div class="row" >

                 <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label">Business Location : </label>
                        <div class="col-md-9">
                          <?php $dis01 = !empty(@$sRow->condition_business_location)?'disabled':'' ?>
                         <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " required="" <?=$dis01?> >
                              <option value="">-Business Location-</option>
                              @if(@$sBusiness_location)
                                @foreach(@$sBusiness_location AS $r)
                                <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_business_location)?'selected':'' }} >
                                  {{$r->txt_desc}}
                                </option>
                                @endforeach
                              @endif
                            </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6 " >
                      <div class="form-group row">
                            <label for="branch_id_fk" class="col-md-2 col-form-label"> สาขา : </label>
                            <div class="col-md-10">

                              <?php if(!empty(@$sRow->condition_branch)){ ?>
                                  <select class="form-control select2-templating " disabled="" >
                                      @if(@$sBranchs)
                                        @foreach(@$sBranchs AS $r)
                                          <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_branch)?'selected':'' }} >
                                            {{$r->b_name}}
                                          </option>
                                        @endforeach
                                      @endif
                                  </select>
                              <?php }else{ ?>
                                  <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating " >
                                     <option disabled selected>กรุณาเลือก Business Location ก่อน</option>
                                  </select>
                              <?php } ?>

                            </div>
                          </div>
                    </div>

               </div>


                  <div class="row" >
                    <div class="col-md-6 " >
                       <div class="form-group row">
                            <label for="warehouse_id_fk" class="col-md-3 col-form-label"> คลัง : </label>
                            <div class="col-md-9">

                              <?php if(!empty(@$sRow->condition_warehouse)){ ?>
                                  <select class="form-control select2-templating " disabled="" >
                                      @if(@$Warehouse)
                                        @foreach(@$Warehouse AS $r)
                                          <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_warehouse)?'selected':'' }} >
                                            {{$r->w_name}}
                                          </option>
                                        @endforeach
                                      @endif
                                  </select>
                              <?php }else{ ?>
                                  <select id="warehouse_id_fk"  name="warehouse_id_fk" class="form-control select2-templating " required >
                                     <option disabled selected>กรุณาเลือกสาขาก่อน</option>
                                  </select>
                              <?php } ?>

                            </div>
                          </div>
                    </div>

                      <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="" class="col-md-2 col-form-label"> สินค้า : </label>
                        <div class="col-md-10">
                           <?php $dis03 = !empty(@$sRow->condition_product)?'disabled':'' ?>
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
                    </div>

                  </div>



               <div class="row" >
                    <div class="col-md-12" >
                          <center>
                          <a class="btn btn-info btn-sm btnSearch " href="#" style="font-size: 14px !important;" >
                            <i class="bx bx-search align-middle "></i> SEARCH
                          </a>
                    </div>

                  </div>

                  @IF(empty(@$sRow))
                  <div class="row" >
                    <div class="col-md-12" >
                       <div class="form-group row">

                        </div>
                    </div>
                  </div>
                  @ENDIF

              </div>




                <div class=" text-right" style="{{@$sC}}" >
                	 <div class="col-8">
                    <input type="text" class="form-control float-left text-center w300 myLikeLike " placeholder="รหัสสินค้า : ชื่อสินค้า" id="product_name">
                  </div>
                  <a class="btn btn-info btn-sm mt-1 btnSync " href="#">
                    <i class="bx bx-plus font-size-20 align-middle mr-1"></i>Sync รายการสินค้าปัจจุบันมาจากคลัง
                  </a>
                </div>

                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>

            </div>
          </div>
        </div>



            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->



@endsection

@section('script')

<script src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js" type="text/javascript" charset="utf-8" async defer></script>

<script type="text/javascript">

var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
var product_name = $("#product_name").val(); //alert(sU);
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
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
        destroy: true,
        // scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
            url: '{{ route('backend.stock_notify.datatable') }}',
            data :{
                  product_name:product_name,
                },
              method: 'POST',
            },

        columns: [
            {data: 'id', title :'*', className: 'text-center w10'},
            {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left w240 '},
            {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
            {data: 'warehouses', title :'<center>คลังสินค้า </center>', className: 'text-center w150 '},
            {data: 'amt', title :'<center>จำนวนคงคลังล่าสุด</center>', className: 'text-center w100 '},
            {data: 'amt_less', title :'<center>จำนวนไม่ต่ำกว่า (ชิ้น)</center>', className: 'text-center w100 ',render: function(d) {
              if(d==0){
                return '* ยังไม่ได้กำหนด';
              }else{
                return d;
              }
            }},
            {data: 'amt_day_before_expired', title :'<center>แจ้งเตือนก่อนวันหมดอายุ (วัน)</center>', className: 'text-center w100 ',render: function(d) {
              if(d==0){
                return '* ยังไม่ได้กำหนด';
              }else{
                return d;
              }
            }},
            {data: 'updated_at', title :'<center>Last updated </center>', className: 'text-center w100 '},
            {data: 'id', title :'Tools', className: 'text-center w80'},
        ],
        rowCallback: function(nRow, aData, dataIndex){

          var info = $(this).DataTable().page.info();
          $("td:eq(0)", nRow).html(info.start + dataIndex + 1);
          if(aData['amt_less']==0){
            $('td:eq(5)', nRow).html(aData[5]).css({'color':'red','font-style':'italic'});
          }
          if(aData['amt_day_before_expired']==0){
            $('td:eq(6)', nRow).html(aData[6]).css({'color':'red','font-style':'italic'});
          }

          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{

          $('td:last-child', nRow).html(''
            + '<a href="{{ route('backend.stock_notify.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
            + '<a href="javascript: void(0);" data-url="{{ route('backend.stock_notify.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
          ).addClass('input');

        }

        }
    });

});

    $('.myLikeLike').on('change', function(e){
    	var product_name = $("#product_name").val(); //alert(product_name);
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
			        destroy: true,
			        // scrollY: ''+($(window).height()-370)+'px',
			        iDisplayLength: 25,
			        ajax: {
			            url: '{{ route('backend.stock_notify.datatable') }}',
			            data :{
			                  product_name:product_name,
			                },
			              method: 'POST',
			            },

			        columns: [
			            {data: 'id', title :'*', className: 'text-center w10'},
			            {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left w240 '},
			            {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
			            {data: 'warehouses', title :'<center>คลังสินค้า </center>', className: 'text-center w150 '},
			            {data: 'amt', title :'<center>จำนวนคงคลังล่าสุด</center>', className: 'text-center w100 '},
			            {data: 'amt_less', title :'<center>จำนวนไม่ต่ำกว่า (ชิ้น)</center>', className: 'text-center w100 ',render: function(d) {
			              if(d==0){
			                return '* ยังไม่ได้กำหนด';
			              }else{
			                return d;
			              }
			            }},
			            {data: 'amt_day_before_expired', title :'<center>แจ้งเตือนก่อนวันหมดอายุ (วัน)</center>', className: 'text-center w100 ',render: function(d) {
			              if(d==0){
			                return '* ยังไม่ได้กำหนด';
			              }else{
			                return d;
			              }
			            }},
			            {data: 'updated_at', title :'<center>Last updated </center>', className: 'text-center w100 '},
			            {data: 'id', title :'Tools', className: 'text-center w80'},
			        ],
			        rowCallback: function(nRow, aData, dataIndex){

			          var info = $(this).DataTable().page.info();
			          $("td:eq(0)", nRow).html(info.start + dataIndex + 1);
			          if(aData['amt_less']==0){
			            $('td:eq(5)', nRow).html(aData[5]).css({'color':'red','font-style':'italic'});
			          }
			          if(aData['amt_day_before_expired']==0){
			            $('td:eq(6)', nRow).html(aData[6]).css({'color':'red','font-style':'italic'});
			          }

			          if(sU!=''&&sD!=''){
			              $('td:last-child', nRow).html('-');
			          }else{

			          $('td:last-child', nRow).html(''
			            + '<a href="{{ route('backend.stock_notify.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
			            + '<a href="javascript: void(0);" data-url="{{ route('backend.stock_notify.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
			          ).addClass('input');

			        }

			        }
			    });

			});
    });

</script>


  <script>


        $(document).ready(function() {

            $(document).on('click', '.btnSearch', function(event) {
                  event.preventDefault();

                  $("#spinner_frame").show();

                  var business_location_id_fk = $('#business_location_id_fk').val();
                  var branch_id_fk = $('#branch_id_fk').val();
                  var warehouse_id_fk = $('#warehouse_id_fk').val();

                   if(business_location_id_fk==''){
                      $("#business_location_id_fk").select2('open');
                      $("#spinner_frame").hide();
                       return false;
                    }
                   if(branch_id_fk==''){
                      $("#branch_id_fk").select2('open');
                      $("#spinner_frame").hide();
                       return false;
                    }

                  var product = $('#product').val();
                  // return false;

                  // datatables
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
                                destroy: true,
                                // scrollY: ''+($(window).height()-370)+'px',
                                iDisplayLength: 25,
                                ajax: {
                                  url: '{{ route('backend.stock_notify.datatable') }}',
                                   data: function ( d ) {
                                    d.myWhereStock={};
                                    d.myWhereStock['business_location_id_fk'] = business_location_id_fk ;
                                    d.myWhereStock['product_id_fk'] = product ;
                                    d.myWhereStock['branch_id_fk'] = branch_id_fk ;
                                    d.myWhereStock['warehouse_id_fk'] = warehouse_id_fk ;
                                    oData = d;
                                  },
                                   method: 'POST',
                                 },
                                columns: [
                                    {data: 'id', title :'*', className: 'text-center w10'},
                                    {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left w240 '},
                                    {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
                                    {data: 'warehouses', title :'<center>คลังสินค้า </center>', className: 'text-center w150'},
                                    {data: 'amt', title :'<center>จำนวนคงคลังล่าสุด</center>', className: 'text-center w100 '},
                                    {data: 'amt_less', title :'<center>จำนวนไม่ต่ำกว่า (ชิ้น)</center>', className: 'text-center w100 ',render: function(d) {
                                      if(d==0){
                                        return '* ยังไม่ได้กำหนด';
                                      }else{
                                        return d;
                                      }
                                    }},
                                    {data: 'amt_day_before_expired', title :'<center>แจ้งเตือนก่อนวันหมดอายุ (วัน)</center>', className: 'text-center w100 ',render: function(d) {
                                      if(d==0){
                                        return '* ยังไม่ได้กำหนด';
                                      }else{
                                        return d;
                                      }
                                    }},
                                    {data: 'updated_at', title :'<center>Last updated </center>', className: 'text-center w100 '},
                                    {data: 'id', title :'Tools', className: 'text-center w80'},
                                ],
                                rowCallback: function(nRow, aData, dataIndex){

                                  var info = $(this).DataTable().page.info();
                                  $("td:eq(0)", nRow).html(info.start + dataIndex + 1);
                                  if(aData['amt_less']==0){
                                    $('td:eq(5)', nRow).html(aData[5]).css({'color':'red','font-style':'italic'});
                                  }
                                  if(aData['amt_day_before_expired']==0){
                                    $('td:eq(6)', nRow).html(aData[6]).css({'color':'red','font-style':'italic'});
                                  }

                                  if(sU!=''&&sD!=''){
                                      $('td:last-child', nRow).html('-');
                                  }else{

                                  $('td:last-child', nRow).html(''
                                    + '<a href="{{ route('backend.stock_notify.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                                    + '<a href="javascript: void(0);" data-url="{{ route('backend.stock_notify.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                  ).addClass('input');

                                }

                                }
                            });

                        });
                  // datatables


                  setTimeout(function(){
                    $("#spinner_frame").hide();
                  },2000);

            });

        });
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
                       alert('ไม่พบข้อมูลคลัง !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือกคลัง -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.w_name+'</option>';
                       });
                       $('#warehouse_id_fk').html(layout);
                       $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                       $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                   }
                  }
                })
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
                   }
                  }
                })
           }

      });



       $('.btnSync').click(function(e){

            e.preventDefault();

            $(".myloading").show();

             $.ajax({
                  url: " {{ url('backend/ajaxSyncStockToNotify') }} ",
                  method: "post",
                  data: {
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                    location.reload();
                  }
                })

      });


</script>


    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#start_date').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // minDate: today,
            // maxDate: function () {
            //     return $('#end_date').val();
            // }
        });
        $('#end_date').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                return $('#start_date').val();
            }
        });

         $('#start_date').change(function(event) {
           $('#end_date').val($(this).val());
         });

  </script>
@endsection
