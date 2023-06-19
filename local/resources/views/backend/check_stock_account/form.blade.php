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
            <h4 class="mb-0 font-size-18"> Check Stock </h4>

                      <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/check_stock_account") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                      </a>

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

    if(isset($_REQUEST['Approve'])){
      $dis = "display:none;";
      $Approved = '1';
    }else{
      $dis = '';
      $Approved = '0';
    }

   // echo $Approved;

    // echo @Session::get('session_status_accepted_id');

   ?>



<?php

  // echo @Session::get('session_status_accepted_id');

   if(@Session::get('session_status_accepted_id')==3||@Session::get('session_status_accepted_id')==4||@Session::get('session_status_accepted_id')==5){
      @$dis_btnSave = "disabled";
   }else{
      @$dis_btnSave = "";
   }

  ?>


@IF(!empty(@$sRow))
<div class="row" style="display: none;">
@else
<div class="row" style="">
@ENDIF
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

                 <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label"> สินค้า : </label>
                        <div class="col-md-9">
                           <?php $dis03 = !empty(@$sRow->condition_product)?'disabled':'' ?>
                           <select name="product" id="product" class="form-control select2-templating " <?=$dis03?> >
                                <option value="">-PRODUCT-</option>
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


                    <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="lot_number" class="col-md-2 col-form-label"> Lot-No. : </label>
                        <div class="col-md-10">
                             <?php $dis04 = !empty(@$sRow->condition_lot_number)?'disabled':'' ?>
                             <select name="lot_number" id="lot_number" class="form-control select2-templating " <?=$dis04?> >
                                <option value="">-Lot Number-</option>
                                   @if(@$Check_stock)
                                      @foreach(@$Check_stock AS $r)
                                        <option value="{{@$r->lot_number}}" {{ (@$r->lot_number==@$sRow->condition_lot_number)?'selected':'' }} >
                                          {{@$r->lot_number}}
                                        </option>
                                      @endforeach
                                    @endif
                              </select>
                        </div>
                      </div>
                    </div>


                  </div>

                  @IF(empty(@$sRow))
                  <div class="row" >
                    <div class="col-md-12" >
                       <div class="form-group row">
                        <div class="col-md-12">
                        <center>
                          <a class="btn btn-info btn-sm btnSearch " href="#" style="font-size: 14px !important;" >
                            <i class="bx bx-search align-middle "></i> SEARCH
                          </a>
                        </div>
                        </div>
                    </div>
                  </div>
                  @ENDIF

              </div>


            </div>
          </div>
        </div>

        <form id="frm-02" action="{{ route('backend.check_stock_account.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="save_to_stock_check" value="1" >

 @IF(!empty(@$sRow))

            <span style="font-size: 14px;font-weight: bold;"><i class="bx bx-play"></i> {{@Session::get('session_RefCode')}} </span>
            @if(@$sRow->status_accepted==0||@$sRow->status_accepted==1||@$sRow->status_accepted==2||@$sRow->status_accepted==5)
                  <span style="font-size: 14px;font-weight: bold;color:red;"><i class="bx bx-play"></i> {{@Session::get('session_Status_accepted')}}  </span>
                  <span style="font-size: 14px;font-weight: bold;"> > {{@Session::get('session_Action_user')}} </span>
            @ELSE
                  <span style="font-size: 14px;font-weight: bold;color:red;"><i class="bx bx-play"></i> {{@Session::get('session_Status_accepted')}} </span>
                  <span style="font-size: 14px;font-weight: bold;"> > {{@Session::get('session_Approver')}} </span>
            @ENDIF
            <br>
            <span style="font-size: 14px;font-weight: bold;"><i class="bx bx-play"></i> {{@Session::get('session_ConditionChoose')}} </span><br>
            <span style="font-size: 14px;font-weight: bold;"><i class="bx bx-play"></i> {{@Session::get('session_ConditionNoChoose')}} </span>
 @ENDIF

            <input type="hidden" name="condition_business_location" value="{{@$sRow->condition_business_location?@$sRow->condition_business_location:0}}" >
            <input type="hidden" name="condition_branch" value="{{@$sRow->condition_branch?@$sRow->condition_branch:0}}" >
            <input type="hidden" name="condition_warehouse" value="{{@$sRow->condition_warehouse?@$sRow->condition_warehouse:0}}" >
            <input type="hidden" name="condition_zone" value="{{@$sRow->condition_zone?@$sRow->condition_zone:0}}" >
            <input type="hidden" name="condition_shelf" value="{{@$sRow->condition_shelf?@$sRow->condition_shelf:0}}" >
            <input type="hidden" name="condition_shelf_floor" value="{{@$sRow->condition_shelf_floor?@$sRow->condition_shelf_floor:0}}" >
            <input type="hidden" name="condition_product" value="{{@$sRow->condition_product?@$sRow->condition_product:0}}" >
            <input type="hidden" name="condition_lot_number" value="{{@$sRow->condition_lot_number?@$sRow->condition_lot_number:0}}" >

            {{ csrf_field() }}

                <table id="data-table-02" class="table table-bordered " style="width: 100%;">
                </table>

                  <div class="row div_btnCreate " style="display: none;" >
                        <div class="col-md-12">
                        <center>
                          <a class="btn btn-info btn-sm btnCreate " href="#" style="font-size: 14px !important;" >
                            <i class="bx bx-plus align-middle "></i> สร้างใบตรวจนับสินค้า
                          </a>
                          </center>
                        </div>
                  </div>

      </form>

          @IF(!empty(@$sRow))

                <table id="data-table-check" class="table table-bordered " style="width: 100%;">
                </table>

                         <div class="row  " style="" >
                          <div class="col-md-12" >
                             <div class="form-group row">
                              <div class="col-md-12" style="">
                              <center>

                       <form id="basic-form" action="" method="post">
                                 <div class="form-group row">
                                  <label for="cuase_desc" class="col-md-3 col-form-label">หมายเหตุ * :</label>
                                  <div class="col-md-6">
                                    <textarea class="form-control" rows="3" id="cuase_desc" name="cuase_desc" required  minlength="5" <?=@$dis_btnSave?> >{{@$sRow->cuase_desc}}</textarea>
                                  </div>
                                </div>
                        </form>
                                </center>
                              </div>
                              </div>
                          </div>
                        </div>

                        <br>

                @IF(isset($_REQUEST['Approve']))
                @ELSE
                        <div class="row  " style="" >
                          <div class="col-md-12" >
                             <div class="form-group row">
                              <div class="col-md-12" style="">
                              <center>


                             @IF($sRow->action_user==\Auth::user()->id)

                                 @if(@$sRow->status_accepted==0||@$sRow->status_accepted==1||@$sRow->status_accepted==2)
                                  <a class="btn btn-info btn-sm btnPrint " href="{{ URL::to('backend/check_stock_account/print_receipt') }}/{{@$sRow->id}}" style="font-size: 14px !important;width: 160px;" target="_blank" >
                                    <i class="bx bx-printer align-middle "></i> พิมพ์ใบตรวจนับสินค้า
                                  </a>
                                 @ENDIF
                               &nbsp;
                               &nbsp;
                               &nbsp;
                               @if(@$sRow->status_accepted==0||@$sRow->status_accepted==1||@$sRow->status_accepted==2)
                                <a class="btn btn-primary btn-sm btnOffer " href="{{ URL::to('backend/check_stock_account/') }}/{{@$sRow->id}}" style="font-size: 14px !important;width: 160px;" >
                                  <i class="bx bx-save align-middle "></i> บันทึก > ขออนุมัติ
                                </a>
                                @ENDIF

                             @ENDIF

                                </center>
                              </div>
                              </div>
                          </div>
                        </div>
                 @ENDIF

    @ENDIF

<?php //echo @Session::get('session_status_accepted_id'); echo @$sRow->status_accepted; ?>

@IF( ( isset($_REQUEST['Approve']) || @$sRow->status_accepted==3 || @$sRow->status_accepted==4 ) && @$sRow->status_accepted!=0 && @$sRow->status_accepted!=1 && @$sRow->status_accepted!=5 )

         <br>
         <form action="{{ route('backend.check_stock_account.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input name="approved" type="hidden" value="1">
                <input name="id" type="hidden" value="{{@$sRow->id}}">

                {{ csrf_field() }}

            <!-- 0=NEW,1=PENDDING,2=REQUEST,3=ACCEPTED/APPROVED,4=NO APPROVED,5=CANCELED -->
            <div class="myBorder">

                 <div class="form-group row">
                      <label for="" class="col-md-3 col-form-label">ผู้อนุมัติ/Approval (Admin Login) :</label>
                      <div class="col-md-6">
                         @if( empty(@sRow[0]->id) )
                          <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="approver" >
                            @else
                              <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ @$sRow->approver }}" name="approver" >
                         @endif
                      </div>
                  </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">สถานะการอนุมัติ / Status :</label>
                        <div class="col-md-3 mt-2">
                          <div class=" ">
                            @if( empty($sRow) )
                              <input type="radio" class="" id="customSwitch1" name="approve_status" value="3" required >
                            @else
                              <input type="radio" class="" id="customSwitch1" name="approve_status" required value="3" {{ ( @$sRow->status_accepted=='3')?'checked':'' }} <?=@$dis_btnSave?> >
                            @endif
                              <label for="customSwitch1">อนุมัติ / Aproved</label>
                          </div>
                        </div>
                         <div class="col-md-6 mt-2">
                          <div class=" ">
                            @if( empty($sRow) )
                              <input type="radio" class="" id="customSwitch2" name="approve_status" value="4" required >
                            @else
                              <input type="radio" class="" id="customSwitch2" name="approve_status" required value="4" {{ ( @$sRow->status_accepted=='4')?'checked':'' }} <?=@$dis_btnSave?> >
                            @endif
                              <label class="" for="customSwitch2">ไม่อนุมัติ / No Aproved</label>
                          </div>
                        </div>

                    </div>



                    <div class="form-group row">
                      <label for="note" class="col-md-3 col-form-label">หมายเหตุ / Note :</label>
                      <div class="col-md-6">
                        <textarea class="form-control" rows="3" id="note" name="note" required  minlength="5" <?=@$dis_btnSave?>  >{{ @$sRow->note }}</textarea>
                      </div>
                    </div>

                    <br>



                    @if( $sPermission==1 || @$menu_permit->can_approve==1 )

                        @IF(@$sRow->status_accepted!=3 && @$sRow->status_accepted!=4)

                          <div class="form-group row">
                            <label for="note" class="col-md-3 col-form-label"> </label>
                            <div class="col-md-6 text-right ">
                                  <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 ">
                              <i class="bx bx-save font-size-16 align-middle mr-1"></i>  บันทึก > การอนุมัติ
                              </button>
                            </div>
                           </div>

                        @endif

                    @endif

                   <div class="form-group mb-0 row">
                    <div class="col-md-6">
                      <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/check_stock_account") }}">
                        <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                      </a>
                    </div>
                    <div class="col-md-6 text-right">
                    </div>
                  </div>

              </form>

  @endif

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->



@endsection

@section('script')

<script src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js" type="text/javascript" charset="utf-8" async defer></script>
<script type="text/javascript">
 $(document).ready(function() {
    $('#example').DataTable( {
      "bLengthChange": false ,
      "searching": false,
        order: [[2, 'asc']],
        rowGroup: {
            startRender: null,
            endRender: function ( rows, group ) {
                var salaryAvg = rows
                    .data()
                    .pluck(5)
                    .reduce( function (a, b) {
                        return a + b.replace(/[^\d]/g, '')*1;
                    // }, 0) / rows.count();
                    }, 0) / 1 ;
                salaryAvg = $.fn.dataTable.render.number(',', '.', 0, '$').display( salaryAvg );

                var ageAvg = rows
                    .data()
                    .pluck(3)
                    .reduce( function (a, b) {
                        return a + b*1;
                    }, 0) / rows.count();

                return $('<tr/ style=" background-color:#f2f2f2 !important;">')
                    .append( '<td colspan="3">Averages for '+group+'</td>' )
                    .append( '<td>'+ageAvg.toFixed(0)+'</td>' )
                    .append( '<td/>' )
                    .append( '<td>'+salaryAvg+'</td>' );
            },
            dataSrc: 2
        }
    } );
} );
</script>


<script type="text/javascript">

var sPermission = "<?=\Auth::user()->permission?>";
var sUserID = "<?=\Auth::user()->id?>";
var sStatus_accepted = "<?=@$sRow->status_accepted?>";
var Approved = "<?=$Approved?>"; //alert(Approved);
var id = "{{@$sRow->id}}"; //alert(id);
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
var oTable;
$(function() {
    oTable = $('#data-table-check').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        // scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
            url: '{{ route('backend.check_stock_check.datatable') }}',
            data :{
                  id:id,
                },
              method: 'POST',
            },
        columns: [
            {data: 'run_code', title :'REF-CODE', className: 'text-center '},
            {data: 'product_name', title :'<center>PRODUCT </center>', className: 'text-left w250'},
            {data: 'lot_number', title :'<center>Lot number : Expired date</center>', className: 'text-left'},
            {data: 'warehouses', title :'<center>Warehouse </center>', className: 'text-left'},
            {data: 'amt', title :'<center>Balance </center>', className: 'text-center'},
            {data: 'amt_check', title :'<center>ountable amount  </center>', className: 'text-center'},
            {data: 'amt_diff',   title :'<center>Difference amount</center>', className: 'text-center ',render: function(d) {
               if(d<0 || d>0){
                   return '<span class="badge badge-pill badge-danger font-size-16" style="color:black">'+d+'</span>';
               }else{
                   return d ;
               }
            }},
            {data: 'id', title :'Tools', className: 'text-center w50'},

        ],
        rowCallback: function(nRow, aData, dataIndex){

                  if(sPermission==1){

                    if(sStatus_accepted==2||sStatus_accepted==3||sStatus_accepted==4||sStatus_accepted==5){
                        $('td:last-child', nRow).html('-');
                    }else{

                        $('td:last-child', nRow).html(''
                          +'<a href="{{ url('backend/check_stock_account/adjust') }}/'+aData['id']+'?from_id='+id+'" class="btn btn-sm btn-primary" style="" ><i class="bx bx-edit font-size-16 align-middle"></i></a>'
                        ).addClass('input');

                    }

                  }else{

                      if(sStatus_accepted==2||sStatus_accepted==3||sStatus_accepted==4||sStatus_accepted==5){
                          $('td:last-child', nRow).html('-');
                      }else{

                         if(aData['action_user_id']==sUserID){
                            $('td:last-child', nRow).html(''
                            +'<a href="{{ url('backend/check_stock_account/adjust') }}/'+aData['id']+'?from_id='+id+'" class="btn btn-sm btn-primary" style="" ><i class="bx bx-edit font-size-16 align-middle"></i></a>'
                           ).addClass('input');
                         }else{
                             $('td:last-child', nRow).html('-');
                         }

                         // $('td:last-child', nRow).html(aData['action_user_id']);

                      }

                  }

           },

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
                  var lot_number = $('#lot_number').val();
                  var warehouse_id_fk = $('#warehouse_id_fk').val();
                  var zone_id_fk = $('#zone_id_fk').val();
                  var shelf_id_fk = $('#shelf_id_fk').val();
                  var shelf_floor = $('#shelf_floor').val();

                  $("input[name=condition_business_location]").val(business_location_id_fk);
                  $("input[name=condition_branch]").val(branch_id_fk);
                  $("input[name=condition_warehouse]").val(warehouse_id_fk);
                  $("input[name=condition_zone]").val(zone_id_fk);
                  $("input[name=condition_shelf]").val(shelf_id_fk);
                  $("input[name=condition_shelf_floor]").val(shelf_floor);
                  $("input[name=condition_product]").val(product);
                  $("input[name=condition_lot_number]").val(lot_number);

                  // console.log(shelf_floor);
                  // console.log(lot_number);
                  // return false;
                        var oTable;
                        $(function() {
                                oTable = $('#data-table-02').DataTable({
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
                                      url: '{{ route('backend.check_stock.datatable') }}',
                                      data: function ( d ) {
                                          d.Where={};
                                          d.Where['business_location_id_fk'] = business_location_id_fk ;
                                          d.Where['product_id_fk'] = product ;
                                          d.Where['lot_number'] = lot_number ;
                                          d.Where['branch_id_fk'] = branch_id_fk ;
                                          d.Where['warehouse_id_fk'] = warehouse_id_fk ;
                                          d.Where['zone_id_fk'] = zone_id_fk ;
                                          d.Where['shelf_id_fk'] = shelf_id_fk ;
                                          d.Where['shelf_floor'] = shelf_floor ;
                                          oData = d;
                                          // $("#spinner_frame").hide();
                                        },
                                         method: 'POST',
                                       },
                                    columns: [
                                        {data: 'id', title :'ID', className: 'text-center w50'},
                                        {data: 'product_name', title :'<center>PRODUCT </center>', className: 'text-left'},
                                        {data: 'lot_number', title :'<center>ล็อตนัมเบอร์ </center>', className: 'text-left'},
                                        {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
                                        {data: 'amt',
                                             defaultContent: "0",   title :'<center>คงคลังล่าสุด</center>', className: 'text-center',render: function(d) {
                                                 return d;

                                          }},
                                        {data: 'warehouses', title :'<center>คลังสินค้า </center>', className: 'text-left'},
                                        // {data: 'id', title :'Tools', className: 'text-center w80'},

                                    ],
                                    order: [[1, 'asc']],
                                    rowGroup: {
                                        startRender: null,
                                        endRender: function ( rows, group ) {
                                            var sTotal = rows
                                               .data()
                                               .pluck('amt')
                                               .reduce( function (a, b) {
                                                   return a + b*1;
                                               }, 0);
                                                sTotal = $.fn.dataTable.render.number(',', '.', 0, ' ').display( sTotal );
                                            // sTotal = 2;

                                            return $('<tr/ style=" background-color:#f2f2f2 !important;">')
                                                .append( '<td colspan="4" style="text-align:center;">Total for '+group+'</td>' )
                                                .append( '<td style=" background-color:#f2f2f2 !important;font-weight: bold; "><center>'+(sTotal)+'</td>' )
                                                .append( '<td></td>' );
                                        },
                                        dataSrc: "product_name"
                                    },

                                      rowCallback: function(nRow, aData, dataIndex){

                                        $("td:eq(0)", nRow).html(aData['id']+'<input type="hidden" name="row_id[]" value="'+aData['id']+'">');

                                      //         if(sU!=''&&sD!=''){
                                      //             $('td:last-child', nRow).html('-');
                                      //         }else{
                                      //               $('td:last-child', nRow).html(''
                                      //                 + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '

                                      //               ).addClass('input');
                                      //         }

                                                   setTimeout(function(){
                                                    $(".div_btnCreate").show();
                                                    $("#spinner_frame").hide();
                                                  },2000);

                                         },

                                });

                            });

                      setTimeout(function(){
                        $("#spinner_frame").hide();
                      },1500);

            });

        });
    </script>



  <script>

        $(document).ready(function() {

            $(document).on('click', '.btnCreate', function(event) {
                  event.preventDefault();
                  $("#spinner_frame").show();
                  $("#frm-02").submit();
            });

        });



        $(document).ready(function() {


               $(document).on('click', '.btnAccept', function(event) {

                  event.preventDefault();
                  $("#spinner_frame").show();

                  $.ajax({
                    url: " {{ url('backend/ajaxAcceptCheckStock') }} ",
                    method: "post",
                    data: {
                      // business_location_id_fk:business_location_id_fk,
                      "_token": "{{ csrf_token() }}",
                    },
                    success:function(data)
                    {
                      location.reload();
                    }
                  })

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


    $('.btnOffer').click(function(e){
          e.preventDefault();

          if (!confirm("ยืนยัน เพื่อขออนุมัติ ? ")){
             return false;
          }else{

             var id = "{{@$sRow->id}}";
             var cuase_desc = $("#cuase_desc").val();
             // alert(id+cuase_desc);
             $("#cuase_desc").valid();
             // return false;
             if($("#cuase_desc").valid()==false){
                return false;
             }

             $.ajax({
                  url: " {{ url('backend/ajaxOfferToApprove') }} ",
                  method: "post",
                  data: {
                    id:id,
                    cuase_desc:cuase_desc,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                    location.reload();
                  }
                });

          }



      });



</script>


@endsection
