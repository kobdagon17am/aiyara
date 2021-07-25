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

                      <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/check_stock_account/".$_REQUEST['from_id']."/edit") }}">
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
    }else{
      $dis = '';
    }
   ?>

           <div class="myBorder">

            <?php //echo $sRow->status_accepted; ?>


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


                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>

            </div>


      <div class="myBorder">

          <form action="{{ route('backend.check_stock_account.update', @$sRow->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input name="_method" type="hidden" value="PUT">
            <input name="id" type="hidden" value="{{@$sRow->id}}">
            <input name="Adjust" type="hidden" value="1">
            <input name="from_id" type="hidden" value="{{@$_REQUEST['from_id']}}">
            {{ csrf_field() }}

            <span style="font-weight: bold;"><i class="bx bx-play"></i>รายการปรับยอดคลัง</span>

            <div class="form-group row">
              <label for="amt" class="col-md-3 col-form-label">ยอดเดิม :</label>
              <div class="col-md-6">
                <input class="form-control NumberOnly " id="amt" name="amt" type="text" value="{{@$sRow->amt}}" readonly="" >
              </div>
            </div>

            <div class="form-group row">
              <label for="amt_check" class="col-md-3 col-form-label">ยอดที่นับได้ :</label>
              <div class="col-md-6">
                <input class="form-control NumberOnly " id="amt_check" name="amt_check" type="text" value="{{@$sRow->amt_check}}" required >
              </div>
            </div>

            @IF(@$sRow->amt_diff)
            <div class="form-group row">
              <label for="amt_diff" class="col-md-3 col-form-label">ยอดต่าง :</label>
              <div class="col-md-6">
                <input class="form-control " type="text" value="{{@$sRow->amt_diff}}" readonly="" style="color:red;" >
              </div>
            </div>
            @ENDIF 

            <div class="form-group row">
              <label for="action_user" class="col-md-3 col-form-label">คนตรวจ :</label>
              <div class="col-md-6">
                <select id="action_user" name="action_user" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                          @if(@$Action_user)
                            @foreach(@$Action_user AS $r)
                              @IF(@$sRow->action_user)
                              <option value="{{$r->id}}" {{ (@$r->id==@$sRow->action_user)?'selected':'' }} >
                                {{$r->name}}
                              </option>
                              @ELSE 
                              <option value="{{$r->id}}" {{ (@$r->id==\Auth::user()->id)?'selected':'' }} >
                                {{$r->name}}
                              </option>
                              @ENDIF 
                          @endforeach
                        @endif
                      </select>
              </div>
            </div>


             <div class="form-group row">
                <label for="note" class="col-md-3 col-form-label"> </label>
                <div class="col-md-6 text-right ">
                      <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 ">
                  <i class="bx bx-save font-size-16 align-middle mr-1"></i>  บันทึก
                  </button>
                </div>
              </div>

          </form>
        </div>



@endsection


@section('script')
<script type="text/javascript">

var id = "{{@$id}}"; //alert(id);
// var id = "1"; //alert(id);
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
        "info":     false,
        "paging":   false,        
        // scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
            url: '{{ route('backend.check_stock_check_02.datatable') }}',
            data :{
                  id:id,
                },
              method: 'POST',
            },

        columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left'},
            {data: 'lot_number', title :'<center>ล็อตนัมเบอร์ </center>', className: 'text-left'},
            {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
            {data: 'amt',
                 defaultContent: "0",   title :'<center>จำนวนคงคลัง</center>', className: 'text-center',render: function(d) {
                     return d;
            }},
            {data: 'warehouses', title :'<center>คลังสินค้า </center>', className: 'text-left'},
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

                // if(sU!=''&&sD!=''){
                //     $('td:last-child', nRow).html('-');
                // }else{ 
                //       $('td:last-child', nRow).html(''
                //         + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        
                //       ).addClass('input');
                // }

           },
   
    });

});



</script>

@endsection
