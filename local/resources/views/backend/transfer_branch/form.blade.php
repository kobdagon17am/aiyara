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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


            <div class="myBorder">
              <table id="data-table-01" class="table table-bordered " style="width: 100%;">
                  </table>
            </div>

            <div class="myBorder">
              <table id="data-table-02" class="table table-bordered " style="width: 100%;">
                 </table>
            </div>


              @if( empty(@$sRow) )
              <form action="{{ route('backend.transfer_branch.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">


              @else
              <form action="{{ route('backend.transfer_branch.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                 <input name="id" type="hidden" value="{{@$sRow->id}}">
                 <input name="approve_transfer_branch_code" type="hidden" value="1">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


      @if( @$sRow->approve_status!='2' )

            <div class="myBorder div_confirm_transfer_branch ">

              <h4><i class="bx bx-play"></i> อนุมัติโอนสินค้า</h4>

                 <div class="form-group row">
                      <label for="example-text-input" class="col-md-3 col-form-label"><i class="bx bx-play"></i>ผู้อนุมัติโอนสินค้า  :</label>
                      <div class="col-md-6">
                        @if( empty(@$sRow) )
                          <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="approver" >
                            @else
                            <?php
                            $approve_name = \DB::table('ck_users_admin')->select('name')->where('id',@$sRow->approver)->first();
                            if($approve_name){
                              $approve_name = $approve_name->name;
                            }else{
                              $approve_name = \Auth::user()->name;
                            }
                            ?>
                              <input class="form-control" type="text" value="{{ $approve_name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ @$sRow->approver?@$sRow->approver:@\Auth::user()->id }}" name="approver" >
                         @endif

                      </div>
                  </div>

                  <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label"><i class="bx bx-play"></i>ผู้อนุมัติโอนสินค้าเอกสาร  :</label>
                    <div class="col-md-6">
                      @if( empty(@$sRow) )
                        <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                          <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="sub_approver" >
                          @else
                          <?php
                          $sub_approve_name = \DB::table('ck_users_admin')->select('name')->where('id',@$sRow->sub_approver)->first();
                          if($sub_approve_name){
                            $sub_approve_name = $sub_approve_name->name;
                          }else{
                            $sub_approve_name = \Auth::user()->name;
                          }
                          ?>
                            <input class="form-control" type="text" value="{{ $sub_approve_name }}" readonly style="background-color: #f2f2f2;" >
                          <input class="form-control" type="hidden" value="{{ @$sRow->sub_approver?@$sRow->sub_approver:@\Auth::user()->id }}" name="sub_approver" >
                       @endif

                    </div>
                </div>

                  <div class="form-group row">
                    <label class="col-md-3 col-form-label"><i class="bx bx-play"></i>สถานะการอนุมัติเอกสาร :</label>
                    <div class="col-md-3 mt-2">
                      <div class=" ">
                        @if( empty($sRow) )
                          <input type="radio" class="" id="customSwitch11" name="sub_approve_status" value="1" required  >
                        @else
                          <input type="radio" class="" id="customSwitch11" name="sub_approve_status" value="1" required {{ ( @$sRow->sub_approve_status=='1')?'checked':'' }}>
                        @endif
                          <label for="customSwitch11">อนุมัติ / Aproved</label>
                      </div>
                    </div>
                     <div class="col-md-6 mt-2">
                      <div class=" ">
                        @if( empty($sRow) )
                          <input type="radio" class="" id="customSwitch22" name="sub_approve_status" value="3" required >
                        @else
                          <input type="radio" class="" id="customSwitch22" name="sub_approve_status" value="3" required {{ ( @$sRow->sub_approve_status=='3')?'checked':'' }}>
                        @endif
                          <label class="" for="customSwitch22">ไม่อนุมัติ / No Aproved</label>
                      </div>
                    </div>

                </div>

                @if( @$sRow->sub_approve_status=='1')

                <div class="form-group row">
                    <label class="col-md-3 col-form-label"><i class="bx bx-play"></i>สถานะการอนุมัติโอนสินค้า :</label>
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
                          <input type="radio" class="" id="customSwitch2" name="approve_status" value="3" >
                        @else
                          <input type="radio" class="" id="customSwitch2" name="approve_status" value="3" {{ ( @$sRow->approve_status=='3')?'checked':'' }}>
                        @endif
                          <label class="" for="customSwitch2">ไม่อนุมัติ / No Aproved</label>
                      </div>
                    </div>

                </div>

                @endif

                        <div class="form-group row">
                          {{-- required_star_red  --}}
                          <label for="approve_note" class="col-md-3 col-form-label "><i class="bx bx-play"></i>หมายเหตุ :</label>
                          <div class="col-md-9">
                            <textarea class="form-control" rows="3" id="approve_note" name="approve_note" minlength="5" >{{ @$sRow->approve_note }}</textarea>
                          </div>
                        </div>


                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/transfer_branch") }}">
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
                              <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/transfer_branch") }}">
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


      var transfer_branch_code_id = "{{@$sRow->id}}";

      var oTable01;
      $(function() {
          oTable01 = $('#data-table-01').DataTable({
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
                      url: '{{ route('backend.transfer_branch_code.datatable') }}',
                      data :{
                            id:transfer_branch_code_id,
                          },
                        method: 'POST',
                      },
              columns: [
                  {data: 'tr_number', title :'รหัสใบโอน', className: 'text-center w80'},
                  {data: 'action_date', title :'<center>วันที่ดำเนินการ </center>', className: 'text-center'},
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
                      return '<center><a href="{{ URL::to('backend/transfer_branch/print_transfer') }}/'+d+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center>';
                  }},
                  {data: 'note',   title :'หมายเหตุ', className: 'text-center ',render: function(d) {
                      return d ;
                  }},
              ],
              rowCallback: function(nRow, aData, dataIndex){
              }
          });

      });


    var transfer_branch_code_id = "{{@$sRow->id}}";
    var oTable02;
    $(function() {
        oTable02 = $('#data-table-02').DataTable({
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
                      url: '{{ route('backend.transfer_branch_product.datatable') }}',
                      data :{
                            transfer_branch_code_id:transfer_branch_code_id,
                          },
                        method: 'POST',
                      },
             columns: [
                  {data: 'id', title :'ลำดับ', className: 'text-center w50'},
                  {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left'},
                  {data: 'lot_number', title :'<center>ล็อตนัมเบอร์ </center>', className: 'text-left'},
                  {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
                  {data: 'warehouses', title :'<center> คลัง </center>', className: 'text-center'},
                  {data: 'amt_in_warehouse', title :'<center> จำนวน<br>ที่มีในคลัง </center>', className: 'text-center'},
                  {data: 'amt', title :'<center>จำนวน<br>ที่ต้องการโอน </center>', className: 'text-center'},
                  {data: 'to_branch',   title :'<center>โอนย้าย<br>ไปที่สาขา</center>', className: 'text-center',render: function(d) {
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




</script>

@endsection

