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
            <h4 class="mb-0 font-size-18"> Check Stock </h4>
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


<!-- ############################################################# -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


            <div class="myBorder">
                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>
            </div>

            <div class="myBorder">

                <form action="{{ route('backend.check_money_daily.update', '') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                  <input name="_method" type="hidden" value="PUT">
                  <input name="fronstore_id_fk" type="hidden" value="{{@$sRow->id}}">
                  <input name="sRowCheck_money_daily_id" type="hidden" value="{{@$sRowCheck_money_daily[0]->id}}">
                  {{ csrf_field() }}

                  <span style="font-weight: bold;"><i class="bx bx-play"></i>รายการปรับยอดคลัง</span>

                  <div class="form-group row">
                    <label for="total_money" class="col-md-3 col-form-label">ยอดเดิม :</label>
                    <div class="col-md-6">
                      <input class="form-control NumberOnly " id="total_money" name="total_money" type="text" value="{{@$sRowCheck_money_daily[0]->total_money}}" required >
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="total_money" class="col-md-3 col-form-label">ยอดที่นับได้ :</label>
                    <div class="col-md-6">
                      <input class="form-control NumberOnly " id="total_money" name="total_money" type="text" value="{{@$sRowCheck_money_daily[0]->total_money}}" required >
                    </div>
                  </div>

 
                   <div class="form-group row">
                      <label for="note" class="col-md-3 col-form-label">หมายเหตุ (สาเหตุที่ปรับยอด) :</label>
                      <div class="col-md-6">
                        <textarea class="form-control" rows="3" id="note" name="note" >{{ @$sRowcheck_stock_account[0]->note }}</textarea>
                      </div>
                    </div>

                  <div class="form-group row">
                    <label for="action_user" class="col-md-3 col-form-label">คนตรวจ :</label>
                    <div class="col-md-6">
                      <select id="action_user" name="action_user" class="form-control select2-templating " >
                              <option value="">-Select-</option>
                              @if(@$Action_user)
                                @foreach(@$Action_user AS $r)
                                <option value="{{$r->id}}" >
                                  {{$r->name}}
                                </option>
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



              <form action="{{ route('backend.check_stock_account.update', '' ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input name="fronstore_id_fk" type="hidden" value="{{@$sRow->id}}">
                <input name="sRowcheck_stock_account_id" type="hidden" value="{{@$sRowcheck_stock_account[0]->id}}">
                <input name="approved" type="hidden" value="1">
  
                {{ csrf_field() }}


     @if( $sPermission==1 || @$menu_permit->can_approve==1 )

      @if( @$sRow->approve_status!='2' )

            <div class="myBorder">

                 <div class="form-group row">
                      <label for="" class="col-md-3 col-form-label">ผู้อนุมัติ (Admin Login) :</label>
                      <div class="col-md-6">
                         @if( empty(@sRowcheck_stock_account[0]->id) )
                          <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="approver" >
                            @else
                              <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ @$sRowcheck_stock_account[0]->approver }}" name="approver" >
                         @endif
                      </div>
                  </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">สถานะการอนุมัติ :</label>
                        <div class="col-md-3 mt-2">
                          <div class=" ">
                            @if( empty($sRowcheck_stock_account) )
                              <input type="radio" class="" id="customSwitch1" name="approve_status" value="1"  >
                            @else
                              <input type="radio" class="" id="customSwitch1" name="approve_status" value="1" {{ ( @$sRowcheck_stock_account[0]->approve_status=='1')?'checked':'' }}>
                            @endif
                              <label for="customSwitch1">อนุมัติ / Aproved</label>
                          </div>
                        </div>
                         <div class="col-md-6 mt-2">
                          <div class=" ">
                            @if( empty($sRowcheck_stock_account) )
                              <input type="radio" class="" id="customSwitch2" name="approve_status" value="5"  >
                            @else
                              <input type="radio" class="" id="customSwitch2" name="approve_status" value="5" {{ ( @$sRowcheck_stock_account[0]->approve_status=='5')?'checked':'' }}>
                            @endif
                              <label class="" for="customSwitch2">ไม่อนุมัติ / No Aproved</label>
                          </div>
                        </div>

                    </div>

                    <div class="form-group row">
                      <label for="note" class="col-md-3 col-form-label">หมายเหตุ (ถ้ามี) :</label>
                      <div class="col-md-6">
                        <textarea class="form-control" rows="3" id="note" name="note" >{{ @$sRowcheck_stock_account[0]->note }}</textarea>
                      </div>
                    </div>

                   <div class="form-group row">

                      <label for="note" class="col-md-3 col-form-label"> </label>
                      <div class="col-md-6 text-right ">
                            <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 ">
                        <i class="bx bx-save font-size-16 align-middle mr-1"></i>  บันทึก > การอนุมัติ
                        </button>
                      </div>
                    </div>

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


              </div>
           @else
                 <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/check_stock_account") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>
                  </div>
                </div>
           @endif

  @else
         <div class="form-group mb-0 row">
          <div class="col-md-6">
            <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/check_stock_account") }}">
              <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
            </a>
          </div>
        </div>

  @endif
  
            </div>
        </div>
    </div> <!-- end col -->
<!-- ############################################################# -->


</div>
<!-- end row -->

@endsection

@section('script')
<script type="text/javascript">

var id_ = "{{@$sRow->id}}"; //alert(id);
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
        // scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
            url: '{{ route('backend.check_stock.datatable') }}',
            data :{
                  id:id_,
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
            {data: 'id',
                 defaultContent: "0",   title :'<center>สถานะ</center>', className: 'text-center',render: function(d) {
                     return 'รอตรวจสอบ';
            }},

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
                    sTotal = $.fn.dataTable.render.number(',', '.', 0, '<span>&#3647;</span> ').display( sTotal );
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

