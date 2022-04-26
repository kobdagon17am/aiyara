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
            <h4 class="mb-0 font-size-18"> ตรวจสอบรับเงินรายวัน </h4>
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
      $can_sentmoney = '1';
      $can_getmoney = '1';      
    }else{
      $role_group_id = \Auth::user()->role_group_id_fk;
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sU = @$menu_permit->u==1?'':'display:none;';
      $sD = @$menu_permit->d==1?'':'display:none;';
      $can_sentmoney = @$menu_permit->can_sentmoney==1?'1':'0';
      $can_getmoney = @$menu_permit->can_getmoney==1?'1':'0';         
    }

    // echo $can_sentmoney;
    // echo $can_getmoney;
    
   ?>


@if( isset($_REQUEST['fromFrontstore']) )
<!-- ############################################################# -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">


            <div class="myBorder">
              <table id="data-table" class="table table-bordered " style="width: 100%;">
                  </table>
            </div>


          <div class="myBorder">


              @if( empty(@$sRowCheck_money_daily[0]->id) )
              <form action="{{ route('backend.check_money_daily.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="fronstore_id_fk" type="hidden" value="{{@$sRow->id}}">
              @else
              <form action="{{ route('backend.check_money_daily.update', @$sRowCheck_money_daily[0]->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input name="fronstore_id_fk" type="hidden" value="{{@$sRow->id}}">
                <input name="sRowCheck_money_daily_id" type="hidden" value="{{@$sRowCheck_money_daily[0]->id}}">
              @endif
                {{ csrf_field() }}

               <span style="font-weight: bold;"><i class="bx bx-play"></i>รายการส่งเงิน</span>

                  <div class="form-group row">
                    <label for="total_money" class="col-md-3 col-form-label">ยอดเงิน :</label>
                    <div class="col-md-6">
                      <input class="form-control NumberOnly " id="total_money" name="total_money" type="text" value="{{@$sRowCheck_money_daily[0]->total_money}}" required >
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="sent_money_type" class="col-md-3 col-form-label">ประเภทการส่งเงิน :</label>
                    <div class="col-md-6">
                    <?php //echo @$sRowCheck_money_daily[0]->sent_money_type ?>
                       <select id="sent_money_type" name="sent_money_type" class="form-control select2-templating " required >
                           <option value="">Select</option>
                            <option value="1" {{@$sRowCheck_money_daily[0]->sent_money_type==1?'selected':''}} >เงินสด</option>
                            <option value="2" {{@$sRowCheck_money_daily[0]->sent_money_type==2?'selected':''}} >เงินโอน</option>
                       </select>

                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="bank" class="col-md-3 col-form-label"> ถ้าเป็นประเภทการโอน :</label>
                    <div class="col-md-6">
                      <input class="form-control" id="bank" name="bank" type="text" value="{{@$sRowCheck_money_daily[0]->bank}}"  placeholder=" กรอก บัญชีธนาคาร และ เลขที่บัญชีฯ " >
                    </div>
                  </div>


                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                  </div>
                  <div class="col-md-6 text-right">
                    <?php if($can_sentmoney=='1'){ ?>
                        <input name="sent_money" type="hidden" value="1"  >
                        <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 btnSentmoney ">
                        <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึก > ส่งเงิน
                        </button>
                    <?php } ?>
                    <?php if($can_getmoney=='1'){ ?>
                        <input name="get_money" type="hidden"  value="1" >
                        <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 btnGetmoney ">
                        <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึก > รับเงิน
                        </button>
                    <?php } ?>

                  </div>
                </div>

  </form>


            </div>

 @if( !empty(@$sRowCheck_money_daily[0]->id) )

              <form action="{{ route('backend.check_money_daily.update', @$sRowCheck_money_daily[0]->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input name="fronstore_id_fk" type="hidden" value="{{@$sRow->id}}">
                <input name="sRowCheck_money_daily_id" type="hidden" value="{{@$sRowCheck_money_daily[0]->id}}">
                <input name="approved" type="hidden" value="1">
  
                {{ csrf_field() }}


     @if( $sPermission==1 || @$menu_permit->can_approve==1 )

      @if( @$sRow->approve_status!='2' )

            <div class="myBorder">

                 <div class="form-group row">
                      <label for="" class="col-md-3 col-form-label">ผู้อนุมัติ (Admin Login) :</label>
                      <div class="col-md-6">
                         @if( empty(@sRowCheck_money_daily[0]->id) )
                          <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="approver" >
                            @else
                              <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ @$sRowCheck_money_daily[0]->approver }}" name="approver" >
                         @endif
                      </div>
                  </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">สถานะการอนุมัติ :</label>
                    <div class="col-md-3 mt-2">
                      <div class=" ">
                        @if( empty($sRowCheck_money_daily) )
                          <input type="radio" class="" id="customSwitch1" name="approve_status" value="1"  >
                        @else
                          <input type="radio" class="" id="customSwitch1" name="approve_status" value="1" {{ ( @$sRowCheck_money_daily[0]->approve_status=='1')?'checked':'' }}>
                        @endif
                          <label for="customSwitch1">อนุมัติ / Aproved</label>
                      </div>
                    </div>
                     <div class="col-md-6 mt-2">
                      <div class=" ">
                        @if( empty($sRowCheck_money_daily) )
                          <input type="radio" class="" id="customSwitch2" name="approve_status" value="5"  >
                        @else
                          <input type="radio" class="" id="customSwitch2" name="approve_status" value="5" {{ ( @$sRowCheck_money_daily[0]->approve_status=='5')?'checked':'' }}>
                        @endif
                          <label class="" for="customSwitch2">ไม่อนุมัติ / No Aproved</label>
                      </div>
                    </div>

                </div>

                        <div class="form-group row">
                          <label for="note" class="col-md-3 col-form-label">หมายเหตุ (ถ้ามี) :</label>
                          <div class="col-md-9">
                            <textarea class="form-control" rows="3" id="note" name="note" >{{ @$sRowCheck_money_daily[0]->note }}</textarea>
                          </div>
                        </div>


                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/check_money_daily") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>
                  </div>
                  <div class="col-md-6 text-right">

                    <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 ">
                    <i class="bx bx-save font-size-16 align-middle mr-1"></i>  บันทึก > การอนุมัติ
                    </button>

                  </div>
                </div>

              </form>

    @endif

              </div>
           @else
                 <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/check_money_daily") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>
                  </div>
                </div>
           @endif

  @else
         <div class="form-group mb-0 row">
          <div class="col-md-6">
            <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/check_money_daily") }}">
              <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
            </a>
          </div>
        </div>
  @endif
            </div>
        </div>
    </div> <!-- end col -->
<!-- ############################################################# -->
@ENDIF



@if( isset($_REQUEST['fromAicash']) )
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">


            <div class="myBorder">
              <table id="data-table-aicash" class="table table-bordered " style="width: 100%;">
                  </table>
            </div>


          <div class="myBorder">


              @if( empty(@$sRowCheck_money_daily_AiCash[0]->id) )
              <form action="{{ route('backend.check_money_daily.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="add_ai_cash_id_fk" type="hidden" value="{{@$sRowAdd_ai_cash->id}}">
              @else
              <form action="{{ route('backend.check_money_daily.update', @$sRowAdd_ai_cash->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input name="add_ai_cash_id_fk" type="hidden" value="{{@$sRowAdd_ai_cash->id}}">
                <input name="sRowCheck_money_daily_AiCash" type="hidden" value="{{@$sRowCheck_money_daily_AiCash[0]->id}}">
              @endif
                {{ csrf_field() }}

               <span style="font-weight: bold;"><i class="bx bx-play"></i>รายการส่งเงิน</span>

                  <div class="form-group row">
                    <label for="total_money" class="col-md-3 col-form-label">ยอดเงิน :</label>
                    <div class="col-md-6">
                      <input class="form-control NumberOnly " id="total_money" name="total_money" type="text" value="{{@$sRowCheck_money_daily_AiCash[0]->total_money}}" required >
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="sent_money_type" class="col-md-3 col-form-label">ประเภทการส่งเงิน :</label>
                    <div class="col-md-6">
                    <?php //echo @$sRowAdd_ai_cash->sent_money_type ?>
                       <select id="sent_money_type" name="sent_money_type" class="form-control select2-templating " required >
                           <option value="">Select</option>
                            <option value="1" {{@$sRowCheck_money_daily_AiCash[0]->sent_money_type==1?'selected':''}} >เงินสด</option>
                            <option value="2" {{@$sRowCheck_money_daily_AiCash[0]->sent_money_type==2?'selected':''}} >เงินโอน</option>
                       </select>

                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="bank" class="col-md-3 col-form-label"> ถ้าเป็นประเภทการโอน :</label>
                    <div class="col-md-6">
                      <input class="form-control" id="bank" name="bank" type="text" value="{{@$sRowCheck_money_daily_AiCash[0]->bank}}"  placeholder=" กรอก บัญชีธนาคาร และ เลขที่บัญชีฯ " >
                    </div>
                  </div>


                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                  </div>
                  <div class="col-md-6 text-right">
                    <?php if($can_sentmoney=='1'){ ?>
                        <input name="sent_money" type="hidden" value="1"  >
                        <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 btnSentmoney ">
                        <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึก > ส่งเงิน
                        </button>
                    <?php } ?>
                    <?php if($can_getmoney=='1'){ ?>
                        <input name="get_money" type="hidden"  value="1" >
                        <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 btnGetmoney ">
                        <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึก > รับเงิน
                        </button>
                    <?php } ?>

                  </div>
                </div>

  </form>


            </div>

 @if( !empty(@$sRowCheck_money_daily_AiCash[0]->id) )

              <form action="{{ route('backend.check_money_daily.update', @$sRowCheck_money_daily_AiCash[0]->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input name="add_ai_cash_id_fk" type="hidden" value="{{@$sRowAdd_ai_cash->id}}">
                <input name="sRowCheck_money_daily_AiCash_id" type="hidden" value="{{@$sRowCheck_money_daily_AiCash[0]->id}}">
                <input name="approved" type="hidden" value="1">
  
                {{ csrf_field() }}


     @if( $sPermission==1 || @$menu_permit->can_approve==1 )

      @if( @$sRow->approve_status!='2' )

            <div class="myBorder">

                 <div class="form-group row">
                      <label for="" class="col-md-3 col-form-label">ผู้อนุมัติ (Admin Login) :</label>
                      <div class="col-md-6">
                        @if( empty(@sRowCheck_money_daily_AiCash[0]->id) )
                          <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="approver" >
                            @else
                              <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ @$sRowCheck_money_daily_AiCash[0]->approver }}" name="approver" >
                         @endif
                          
                      </div>
                  </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">สถานะการอนุมัติ :</label>
                    <div class="col-md-3 mt-2">
                      <div class=" ">
                        @if( empty($sRowCheck_money_daily_AiCash) )
                          <input type="radio" class="" id="customSwitch1" name="approve_status" value="1"  >
                        @else
                          <input type="radio" class="" id="customSwitch1" name="approve_status" value="1" {{ ( @$sRowCheck_money_daily_AiCash[0]->approve_status=='1')?'checked':'' }}>
                        @endif
                          <label for="customSwitch1">อนุมัติ / Aproved</label>
                      </div>
                    </div>
                     <div class="col-md-6 mt-2">
                      <div class=" ">
                        @if( empty($sRowCheck_money_daily_AiCash) )
                          <input type="radio" class="" id="customSwitch2" name="approve_status" value="5"  >
                        @else
                          <input type="radio" class="" id="customSwitch2" name="approve_status" value="5" {{ ( @$sRowCheck_money_daily_AiCash[0]->approve_status=='5')?'checked':'' }}>
                        @endif
                          <label class="" for="customSwitch2">ไม่อนุมัติ / No Aproved</label>
                      </div>
                    </div>

                </div>

                        <div class="form-group row">
                          <label for="note" class="col-md-3 col-form-label">หมายเหตุ (ถ้ามี) :</label>
                          <div class="col-md-9">
                            <textarea class="form-control" rows="3" id="note" name="note" >{{ @$sRowCheck_money_daily_AiCash[0]->note }}</textarea>
                          </div>
                        </div>


                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/check_money_daily") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>
                  </div>
                  <div class="col-md-6 text-right">

                    <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 ">
                    <i class="bx bx-save font-size-16 align-middle mr-1"></i>  บันทึก > การอนุมัติ
                    </button>

                  </div>
                </div>

              </form>

    @endif

              </div>
           @else
                 <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/check_money_daily") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>
                  </div>
                </div>
           @endif

  @else
         <div class="form-group mb-0 row">
          <div class="col-md-6">
            <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/check_money_daily") }}">
              <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
            </a>
          </div>
        </div>
  @endif
            </div>
        </div>
    </div> <!-- end col -->
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
@ENDIF



</div>
<!-- end row -->

@endsection

@section('script')

<script type="text/javascript">


    var id = "{{@$sRow->id}}"; //alert(id);

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
              "lengthChange": false,
              "paging":   false,
              scrollY: ''+($(window).height()-370)+'px',
              ajax: {
                url: '{{ route('backend.check_money_daily.datatable') }}',
                data :{
                     _token: '{{csrf_token()}}',
                     id:id,
                    },
                  method: 'POST',
                },
           columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 'action_user_name', title :'<center>พนักงานขาย </center>', className: 'text-left'},
            {data: 'cash_pay', title :'<center>เงินสด </center>', className: 'text-center'},
            {data: 'aicash_price', title :'<center>Ai-cash </center>', className: 'text-center'},
            {data: 'transfer_price', title :'<center>เงินโอน </center>', className: 'text-center'},
            {data: 'credit_price', title :'<center>เครดิต </center>', className: 'text-center'},
            {data: 'fee_amt', title :'<center>ค่าธรรมเนียม </center>', className: 'text-center'},
            {data: 'shipping_price', title :'<center>ค่าขนส่ง </center>', className: 'text-center'},
            {data: 'total_price', title :'<center>รวมทั้งสิ้น </center>', className: 'text-center'},
            {data: 'approve_status',   title :'<center>สถานะ</center>', className: 'text-center w100 ',render: function(d) {
              if(d=="รออนุมัติ"){
                  return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:darkred">'+d+'</span>';
              }else if(d=="ไม่อนุมัติ"){
                  return '<span class="badge badge-pill badge-soft-danger font-size-16" style="color:red">'+d+'</span>';
              }else{
                  return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">'+d+'</span>';
              }
            }},
                ],
                rowCallback: function (nRow, aData, iDisplayIndex) {
                 var info = $(this).DataTable().page.info();
                  $("td:eq(0)", nRow).html(info.start + iDisplayIndex + 1);
                },

          });
        
      });



    var id = "{{@$sRowAdd_ai_cash->id}}";//alert(id);

    var sU = "{{@$sU}}"; //alert(sU);
    var sD = "{{@$sD}}"; //alert(sD);
    var oTable;
    $(function() {
        oTable = $('#data-table-aicash').DataTable({
        "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
              processing: true,
              serverSide: true,
              scroller: true,
              scrollCollapse: true,
              scrollX: true,
              ordering: false,
              "info":     false,
              "lengthChange": false,
              "paging":   false,
              scrollY: ''+($(window).height()-370)+'px',
              ajax: {
                url: '{{ route('backend.add_ai_cash.datatable') }}',
                data :{
                     _token: '{{csrf_token()}}',
                     id:id,
                    },
                  method: 'POST',
                },
           columns: [
           {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 'customer_name', title :'<center>ลูกค้า </center>', className: 'text-left'},
            {data: 'aicash_remain', title :'<center>ยอด Ai-Cash <br> คงเหลือล่าสุด</center>', className: 'text-center'},
            {data: 'aicash_amt', title :'<center>ยอด Ai-Cash <br>ที่เติมครั้งนี้</center>', className: 'text-center'},
            {data: 'action_user', title :'<center>พนักงาน <br> ที่ดำเนินการ </center>', className: 'text-center'},
            {data: 'pay_type_id_fk', title :'<center>รูปแบบการชำระเงิน </center>', className: 'text-center'},
            {data: 'total_amt', title :'<center>ยอดชำระเงิน </center>', className: 'text-center'},
            {data: 'action_date', title :'<center>วันที่ดำเนินการ </center>', className: 'text-center'},
            {data: 'status',   title :'<center>สถานะ</center>', className: 'text-center w100 ',render: function(d) {
              if(d=="รออนุมัติ"){
                  return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:darkred">'+d+'</span>';
              }else if(d=="ไม่อนุมัติ"){
                  return '<span class="badge badge-pill badge-soft-danger font-size-16" style="color:red">'+d+'</span>';
              }else{
                  return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">'+d+'</span>';
              }
            }},
                ],
                rowCallback: function (nRow, aData, iDisplayIndex) {
                 var info = $(this).DataTable().page.info();
                  $("td:eq(0)", nRow).html(info.start + iDisplayIndex + 1);
                },

          });
        
      });


    $(document).ready(function() {
           $(".btnSentmoney").click(function(event) {
              $("input[name=sent_money]").val(1);
              $("input[name=get_money]").val(0);
           });
           $(".btnGetmoney").click(function(event) {
              $("input[name=sent_money]").val(0);
              $("input[name=get_money]").val(1);
           });


          $('#sent_money_type').change(function(event) {
            var v = $(this).val();

            if(v=='2'){
              $("#bank").prop('required',true);
            }else{
              $("#bank").prop('required',false);
            }

          });



    });



</script>

@endsection

