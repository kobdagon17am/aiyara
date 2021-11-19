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
            <h4 class="mb-0 font-size-18"> ข้อมูลรายละเอียดการโอน / การอนุมัติ </h4>
        </div>
    </div>
</div>
<!-- end page title -->

  <?php 
    $sPermission = \Auth::user()->permission ;
    // $menu_id = @$_REQUEST['menu_id'];
    $menu_id = Session::get('session_menu_id');
    $role_group_id = @$_REQUEST['role_group_id'];
    if($sPermission==1){
      $sC = '';
      $sU = '';
      $sD = '';
      $sA = '';
    }else{
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      // dd($menu_permit);
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sA = @$menu_permit->can_answer==1?'':'display:none;';
      // $sApprove = @$menu_permit->can_approve==1?'':'display:none;';
    }

      //   echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;  

   ?>
<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">


            <div class="myBorder">
              <table id="data-table-transfer-list" class="table table-bordered dt-responsive" style="width: 100%;">
                  </table>
            </div>

            <div class="myBorder">
              <table id="data-table-to-transfer" class="table table-bordered dt-responsive" style="width: 100%;">
                 </table>
            </div>


              @if( empty(@$sRow) )
              <form action="{{ route('backend.transfer_warehouses.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="id" type="hidden" value="{{@$_REQUEST['list_id']}}">

              @else
              <form action="{{ route('backend.transfer_warehouses.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input name="id" type="hidden" value="{{@$_REQUEST['list_id']}}">
              @endif
                {{ csrf_field() }}
<!-- Session::get('roleApprove') -->

     <?php // echo @$sRow->approve_status ?>

      @if( @$sRow->approve_status!='2' )
<!-- div_confirm_transfer_warehouses -->
            <div class="myBorder div_confirm_transfer_warehouses ">

                 <div class="form-group row">
                      <label for="example-text-input" class="col-md-3 col-form-label"><i class="bx bx-play"></i>ผู้อนุมัติ (Admin Login) :</label>
                      <div class="col-md-6">
                        @if( empty(@$sRow) )
                          <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="approver" >
                            @else
                              <input class="form-control" type="text" value="{{ @$approver?@$approver:\Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                              <input class="form-control" type="hidden" value="{{ @$sRow->approver?@$sRow->approver:\Auth::user()->id }}" name="approver" >
                         @endif
                          
                      </div>
                  </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label"><i class="bx bx-play"></i>สถานะการอนุมัติ :</label>
                    <div class="col-md-3 mt-2">
                      <div class=" ">
                        @if( empty($sRow) )
                          <input type="radio" class="" id="customSwitch1" name="approve_status" value="1"  >
                        @else
                          <input type="radio" class="" id="customSwitch1" name="approve_status" value="1" {{ ( @$sRow->approve_status=='1')?'checked':'' }}>
                        @endif
                          <label for="customSwitch1">อนุมัติ / Aproved</label>
                      </div>
                    </div>
                     <div class="col-md-6 mt-2">
                      <div class=" ">
                        @if( empty($sRow) )
                          <input type="radio" class="" id="customSwitch2" name="approve_status" value="3"  >
                        @else
                          <input type="radio" class="" id="customSwitch2" name="approve_status" value="3" {{ ( @$sRow->approve_status=='3')?'checked':'' }}>
                        @endif
                          <label class="" for="customSwitch2">ไม่อนุมัติ / No Aproved</label>
                      </div>
                    </div>

                </div>

                        <div class="form-group row">
                          <label for="note" class="col-md-3 col-form-label"><i class="bx bx-play"></i>หมายเหตุ (ถ้ามี) :</label>
                          <div class="col-md-9">
                            <textarea class="form-control" rows="3" id="note" name="note" >{{ @$sRow->note }}</textarea>
                          </div>
                        </div>


                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/transfer_warehouses") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>
                  </div>
                  <div class="col-md-6 text-right">
                    @if( @$sRow->approve_status=='0' )  
                    <button type="submit" class="btn btn-primary btn-sm waves-effect">
                    <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                    </button>
                    @endif

                  </div>
                </div>

              </form>
              </div>
           @else
                 <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/transfer_warehouses") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>
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

<script type="text/javascript">


    var list_id = "{{@$_REQUEST['list_id']}}";

  
    var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
    var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
    var sU = "{{@$sU}}"; //alert(sU);
    var sD = "{{@$sD}}"; //alert(sD);
    var oTable;
    $(function() {
        oTable = $('#data-table-to-transfer').DataTable({
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
              url: '{{ route('backend.transfer_warehouses.datatable') }}',
              data: function ( d ) {
                  d.Where={};
                  d.Where['transfer_warehouses_code_id'] = list_id ;
                  oData = d;
                },
                 method: 'POST',
               },

           columns: [
                  {data: 'id', title :'ลำดับ', className: 'text-center w50'},
                  {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left'},
                  {data: 'lot_number', title :'<center>ล็อตนัมเบอร์ </center>', className: 'text-left'},
                  {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
                  {data: 'amt_in_warehouse', title :'<center>จำนวนที่มีในคลัง </center>', className: 'text-center'},
                  {data: 'amt', title :'<center>จำนวนที่ต้องการโอน </center>', className: 'text-center'},
                  {data: 'warehouses',   title :'<center>โอนย้ายไปที่คลัง</center>', className: 'text-center',render: function(d) {
                      if(d!='0'){
                         return d;
                       }else{
                          return  "<span style='color:red;'>* รอเลือกคลังปลายทาง </span>";
                       }
                  }},
                ],
                rowCallback: function (nRow, aData, iDisplayIndex) {
                 var info = $(this).DataTable().page.info();
                  $("td:eq(0)", nRow).html(info.start + iDisplayIndex + 1);
                },

          });
        
      });

      // alert(list_id);

      var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
      var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
      var sU = "{{@$sU}}"; //alert(sU);
      var sD = "{{@$sD}}"; //alert(sD);
      var oTable;
      $(function() {
          oTable = $('#data-table-transfer-list').DataTable({
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
                url: '{{ route('backend.transfer_warehouses_code.datatable') }}',
                 data :{
                      id:list_id,
                    },
                  method: 'POST',
                },

              columns: [
                  {data: 'tr_number', title :'รหัสใบโอน', className: 'text-center w80'},
                  {data: 'action_date', title :'<center>วันที่ดำเนินการ </center>', className: 'text-center'},
                  // {data: 'amt', title :'<center>จำนวนรายการที่โอน </center>', className: 'text-center'},
                  {data: 'action_user', title :'<center>พนักงานที่ทำการโอน </center>', className: 'text-center'},
                  {data: 'approve_status',   title :'<center>สถานะการอนุมัติ</center>', className: 'text-center w100 ',render: function(d) {
                    if(d==1){
                        return '<span class="badge badge-pill badge-soft-success font-size-16" style="color:darkgreen">อนุมัติแล้ว</span>';
                    }else if(d==2){
                        return '<span class="badge badge-pill badge-soft-danger font-size-16" style="color:grey">ยกเลิก</span>';
                    }else if(d==3){
                        return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:black">ไม่อนุมัติ</span>';
                    }else{
                        return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">รออนุมัติ</span>';
                    }
                  }},
                  {data: 'approver', title :'<center>ผู้อนุมัติ </center>', className: 'text-center'},
                  {data: 'approve_date', title :'<center>วันอนุมัติ </center>', className: 'text-center'},
                  {data: 'id',   title :'พิมพ์ใบโอน', className: 'text-center ',render: function(d) {
                      return '<center><a href="{{ URL::to('backend/transfer_warehouses/print_transfer') }}/'+d+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center>';
                  }},
                  {data: 'note',   title :'หมายเหตุ', className: 'text-center ',render: function(d) {
                      return d ;
                  }},
              ],
              rowCallback: function(nRow, aData, dataIndex){
              }
          });
          $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
            oTable.draw();
          });
      });



</script>

@endsection

