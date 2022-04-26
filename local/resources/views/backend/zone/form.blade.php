@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> 
                {{ @$sBranchs->b_name?' สาขา > '.@$sBranchs->b_name:'' }} 
                {{ @$sWarehouse->w_name?' > '.@$sWarehouse->w_name:'' }} 
                {{ @$sRow->z_name?' > '.@$sRow->z_name:'' }} 
            </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">


<div class="myBorder"> 


              @if( empty($sRow) )
              <form action="{{ route('backend.zone.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="warehouse_id_fk" value="{{@$sWarehouse->id}}" >
              @else
              <form action="{{ route('backend.zone.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input type="hidden" name="warehouse_id_fk" value="{{@$sWarehouse->id}}" >
              @endif
                {{ csrf_field() }}


                <div class="form-group row">
                    <label for="z_code" class="col-md-2 col-form-label">รหัส Zone :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->z_code }}" name="z_code" required>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="z_name" class="col-md-2 col-form-label">ชื่อ Zone :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->z_name }}" name="z_name" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="z_details" class="col-md-2 col-form-label">รายละเอียด :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->z_details }}" name="z_details" required>
                    </div>
                </div>

                 <div class="form-group row">
                      <label for="z_maker" class="col-md-2 col-form-label"> ผู้ทำรายการ : </label>
                      <div class="col-md-10">

                        @if( empty(@$sRow) )
                          <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="z_maker" >
                            @else
                              <input class="form-control" type="text" value="{{@$sMaker_name[0]->name}}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ @$sRow->z_maker }}" name="z_maker" >
                         @endif
                          
                      </div>
                  </div>


                <div class="form-group row">
                    <label class="col-md-2 col-form-label">สถานะ :</label>
                    <div class="col-md-10 mt-2">
                      <div class="custom-control custom-switch">
                        @if( empty($sRow) )
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" checked >
                        @else
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" {{ ( @$sRow->status=='1')?'checked':'' }}>
                        @endif
                          <label class="custom-control-label" for="customSwitch">เปิดใช้งาน</label>
                      </div>
                    </div>
                </div>


                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.warehouse.index') }}/{{@$sWarehouse->id}}/edit">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </div>

              </form>
            </div>


       @if( !empty($sRow) )
            <div class="myBorder">   

                    <div class="form-group row">
                      <div class="col-md-12">
                        <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.shelf.create') }}/{{@$sRow->id}}" style="float: right;">
                          <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                        </a>
                        <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> Shelf  </span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-md-12">
                        <table id="data-table-shelf" class="table table-bordered " style="width: 100%;">
                        </table>
                      </div>
                    </div>
                  <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.warehouse.index') }}/{{@$sWarehouse->id}}/edit">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div>
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

<script>
    
var zone_id_fk = "{{@$sRow->id?@$sRow->id:0}}";

var sU = "{{@$sU}}"; 
var sD = "{{@$sD}}";

var oTable;
$(function() {
    oTable = $('#data-table-shelf').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.shelf.datatable') }}',
          data: function ( d ) {
                d.Where={};
                d.Where['zone_id_fk'] = zone_id_fk ;
                oData = d;
              },
          method: 'POST',
        },
        columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 's_code', title :'<center>รหัส Shelf</center>', className: 'text-center'},
            {data: 's_name', title :'<center>ชื่อ Shelf </center>', className: 'text-center'},
            {data: 's_details', title :'<center>รายละเอียด</center>', className: 'text-center'},
            {data: 's_maker', title :'<center>ผู้ทำรายการ</center>', className: 'text-center'},
            {data: 'created_at', title :'<center>วันที่สร้าง</center>', className: 'text-center'},
            {data: 'updated_at', title :'<center>วันที่อัพเดท</center>', className: 'text-center'},
            {data: 'status',   title :'<center>สถานะ</center>', className: 'text-center',render: function(d) {
               return d==1?'<span style="color:blue">เปิดใช้งาน</span>':'<span style="color:red">ปิด</span>';
            }},
            {data: 'id', title :'Tools', className: 'text-center w60'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){

          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

          $('td:last-child', nRow).html(''
            + '<a href="{{ route('backend.shelf.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
            + '<a href="javascript: void(0);" data-url="{{ route('backend.shelf.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
          ).addClass('input');

          }

        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>

<script type="text/javascript">
/*	
  var menu_id = sessionStorage.getItem("menu_id");
    window.onload = function() {
    if(!window.location.hash) {
       window.location = window.location + '?menu_id=' + menu_id + '#menu_id=' + menu_id ;
    }
  }
  */
</script>


@endsection
