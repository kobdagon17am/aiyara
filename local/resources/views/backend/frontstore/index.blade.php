@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style type="text/css">

  .border-left-0 {height: 95%;}
  label { font-size: 14px;font-weight: bold !important; }

</style>

<style type="text/css">
  /* DivTable.com */
.divTable{
  display: table;
  width: 100%;
  
}
.divTableRow {
  display: table-row;
}
.divTableHeading {
  background-color: #EEE;
  display: table-header-group;
}
.divTableCell, .divTableHead {
  border: 1px solid white;
  display: table-cell;
  padding: 3px 10px;
}
.divTableHeading {
  background-color: #EEE;
  display: table-header-group;
  font-weight: bold;
}
.divTableFoot {
  background-color: #EEE;
  display: table-footer-group;
  font-weight: bold;
}
.divTableBody {
  display: table-row-group;
}
.divTH {text-align: right;}
</style>
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> จำหน่ายสินค้าหน้าร้าน   </h4>
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
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="divTable">
          <div class="divTableBody">
            <div class="divTableRow">
              <div class="divTH">
                <label for="startDate" >วันสร้างเริ่มต้น : </label>
              </div>
              <div class="divTableCell">
                <input id="startDate" class="form-control" autocomplete="off" />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="endDate" >วันสร้างสิ้นสุด : </label>
              </div>
              <div class="divTableCell">
                <input id="endDate" class="form-control" autocomplete="off" />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="" >เลขที่ใบสั่งซื้อ : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
                <input id="" name="" class="form-control"  />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
            </div>
            <div class="divTableRow">
              <div class="divTH">
                <label for="" >รหัสลูกค้า : </label>
              </div>
              <div class="divTableCell">
                <input id="" name="" class="form-control"  />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="" >ชื่อลูกค้า : </label>
              </div>
              <div class="divTableCell">
                <input id="" name="" class="form-control"  />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="" >เลขที่ใบเสร็จ : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
                <input id="" name="" class="form-control"  />
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
            </div>
            <div class="divTableRow">
              <div class="divTH">
                <label for="" >ผู้สร้าง : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
                <select name="" class="form-control select2-templating "  >
                  <option value="">Select</option>
                  @if(@$sUser)
                    @foreach(@$sUser AS $r)
                      <option value="{{$r->id}}"  >
                        {{$r->name}} 
                      </option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTH">
                <label for="" >สถานะ : </label>
              </div>
              <div class="divTableCell" style="width: 15%">
                <select name="" class="form-control select2-templating "  >
                  <option value="">Select</option>
                  @if(@$sApproveStatus)
                    @foreach(@$sApproveStatus AS $r)
                      <option value="{{$r->id}}"  >
                        {{$r->txt_desc}} 
                      </option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-primary"><i class="bx bx-search font-size-18 align-middle "></i></button>
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
            </div>
            <div class="divTableRow">
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-warning" style="color:black;float: right;"><i class="bx bx-search font-size-18 align-middle "></i> ค้นหา</button>
              </div>
              <div class="divTableCell">
                <button type="button" class="btn btn-info" style="float: left;"><i class="fa fa-refresh font-size-18 align-middle "></i></button>
              </div>
            </div>
          </div>
        </div>
        <!-- DivTable.com -->
      </div>
    </div>
  </div>
</div>

   

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="divTable">
          <div class="divTableBody">
            <div class="divTableRow">
              <div class="divTableCell" style="text-align: right;width: 50%;" >&nbsp; </div>
              <div class="divTableCell" style="text-align: right;" >
                <button type="button" class="btn btn-success" ><i class="bx bx-search font-size-18 align-middle "></i> ดูทั้งหมด</button>
              </div>
              <div class="divTableCell" style="text-align: right;" >
                <button type="button" class="btn btn-success" ><i class="bx bx-search font-size-18 align-middle "></i> เฉพาะซื้อแบบปกติ</button>
              </div>
              <div class="divTableCell" style="text-align: right;" >
                <button type="button" class="btn btn-success" ><i class="bx bx-search font-size-18 align-middle "></i> เฉพาะซื้อแบบใช้ Voucher</button>
              </div>
              <div class="divTableCell" style="text-align: right;" >
                    <a  href="{{ route('backend.frontstore.create') }}">
                <button type="button" class="btn btn-success btnAdd " ><i class="fa fa-plus font-size-18 align-middle "></i> เพิ่ม</button>
                 </a>
              </div>
            </div>
          </div>
        </div>
        <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
        </table>
        <div class="row">
          <div class="col-lg-5">
            <div class="card">
              <div class="card-body">
                
                <div class="table-responsive">
                  <table class="table table-striped mb-0">
                    
                    <thead>
                      <tr style="background-color: #f2f2f2;text-align: right;">
                        <th></th>
                        <th>รายการ</th>
                        <th>PV</th>
                        <th>จำนวนเงิน</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr style="color: red" >
                        <th scope="row">สถานะรอตรวจสอบ/รอชำระ</th>
                        <td style="text-align: right;">0 รายการ</td>
                        <td style="text-align: right;">0</td>
                        <td style="text-align: right;">0.00</td>
                      </tr>
                      <tr>
                        <th scope="row">สถานะ รอเบิก/สำเร็จ</th>
                        <td style="text-align: right;">0 รายการ</td>
                        <td style="text-align: right;">0</td>
                        <td style="text-align: right;">0.00</td>
                      </tr>
                      <tr>
                        <th scope="row">รวมทั้งหมด</th>
                        <td style="text-align: right;">0 รายการ</td>
                        <td style="text-align: right;">0</td>
                        <td style="text-align: right;">0.00</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                
              </div>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="card">
              <div class="card-body">
                
                <div class="table-responsive">
                  <table class="table table-sm m-0">
                    <thead>
                      <tr style="background-color: #f2f2f2;"><th colspan="7">
                        ADMIN AIYARA : 25/12/2020 - 25/12/2020
                      </th></tr>
                      <tr>
                        <th>พนักงานขาย</th>
                        <th>เงินสด</th>
                        <th>เงินโอน</th>
                        <th>เครดิต</th>
                        <th>รวมทั้งสิ้น</th>
                        <th>รายการ</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                      </tr>
                      <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                      </tr>
                      
                    </tbody>
                  </table>
                  
                </div>
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div> <!-- end col -->
    </div> <!-- end row -->
  </div>
</div>
</div>
</div>

@endsection

@section('script')
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
<script>
var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
var oTable;
$(function() {
    oTable = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: true,
        paging:   true,
        searching: false,
        bLengthChange: false ,
        ajax: {
          url: '{{ route('backend.frontstore.datatable') }}',
          data: function ( d ) {
            oData = d;
          },
          method: 'POST'
        },

        columns: [
            {data: 'id', title :'ID', className: 'text-center w15'},
            {data: 'action_date', title :'<center>วันสร้าง </center>', className: 'text-center'},
            // {data: 'updated_at', title :'<center>วันแก้ไข </center>', className: 'text-center'},
            {data: 'purchase_type',   title :'<center>ประเภทการสั่งซื้อ</center>', className: 'text-center ',render: function(d) {
                return '<span class="badge badge-pill badge-soft-success font-size-16">'+d+'</span>';
            }},
            {data: 'customers_id_fk', title :'<center>ลูกค้า </center>', className: 'text-center'},
            {data: 'id',   title :'<center>รวม (บาท) </center>', className: 'text-center ',render: function(d) {
                return d ;
            }},
            {data: 'invoice_code',   title :'<center>รหัสใบเสร็จ</center>', className: 'text-center ',render: function(d) {
                return '<span class="badge badge-pill badge-soft-primary font-size-16">'+d+'</span>';
            }},
            {data: 'approve_status',   title :'<center>สถานะ</center>', className: 'text-center w100 ',render: function(d) {
              return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">รอจัดส่ง</span>';
            }},
            {data: 'id',   title :'ใบเสร็จ[1]', className: 'text-center w100 ',render: function(d) {
                return '<center><a href="{{ URL::to('backend/frontstore/print_receipt') }}/'+d+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a>';
            }},
            {data: 'id',   title :'ใบเสร็จ[2]', className: 'text-center w100 ',render: function(d) {
                return '<center><a href="{{ URL::to('backend/frontstore/print_receipt_02') }}/'+d+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#669999;"></i></a></center>';
            }},
            {data: 'id', title :'Tools', className: 'text-center w80'}, 
        ],
           "order": [ [ 1, 'desc' ] ],
           "columnDefs": [ {
            // { targets: 'no-sort', orderable: false }
              "targets": [0,2,6,7,8,9] ,
              "orderable": false
          } ],
        rowCallback: function(nRow, aData, dataIndex){

			$("td:eq(3)", nRow).html(aData['customer_name']);
			$("td:eq(4)", nRow).html(aData['total_price']);
			$("td:eq(8)", nRow).prop('disabled',true); 
			$("td:eq(9)", nRow).prop('disabled',true); 

	          if(sU!=''&&sD!=''){
	              $('td:last-child', nRow).html('-');
	          }else{ 

              $('td:last-child', nRow).html(''
                + '<a href="{{ route('backend.frontstore.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstore.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                
              ).addClass('input');

          }
        }
    });

// + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstore.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'

});
</script>


    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#startDate').datepicker({
            format: 'dd/mm/yyyy',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // minDate: today,
            // maxDate: function () {
            //     return $('#endDate').val();
            // }
        });

        $('#endDate').datepicker({
            format: 'dd/mm/yyyy',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                return $('#startDate').val();
            }
        });

        $('#startDate2').datepicker({
            format: 'dd/mm/yyyy',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // minDate: today,
            // maxDate: function () {
            //     return $('#endDate').val();
            // }
        });


        $(document).ready(function() {
            $(document).on('click', '.btnAdd', function(event) {
              localStorage.clear();
            });
        });

</script>

@endsection

