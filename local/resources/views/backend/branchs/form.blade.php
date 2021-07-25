@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">สาขา {{ @$sRow->b_name?' > '.@$sRow->b_name:'' }} </h4>
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
              <form action="{{ route('backend.branchs.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.branchs.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

                
                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">รหัสสาขา :</label>
                    <div class="col-md-9">
                          <select name="business_location_id_fk" class="form-control select2-templating " required >
                            <option value="">-Business Location-</option>
                            @if(@$sBusiness_location)
                            @foreach(@$sBusiness_location AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->business_location_id_fk)?'selected':'' }} >{{$r->txt_desc}}</option>
                            @endforeach
                            @endif
                          </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">รหัสสาขา :</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" value="{{ @$sRow->b_code }}" name="b_code" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">ชื่อสาขา :</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" value="{{ @$sRow->b_name }}" name="b_name" required>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label"> ที่ตั้งอยู่ในจังหวัด : * </label>
                    <div class="col-md-9">
                      <select name="province_id_fk" class="form-control select2-templating " >
                        <option value="0">Select</option>
                          @if(@$Province)
                            @foreach(@$Province AS $r)
                              <option value="{{$r->id}}" {{ (@$r->id==@$sRow->province_id_fk)?'selected':'' }} >
                                {{$r->name_th}}
                              </option>
                            @endforeach
                          @endif
                      </select>
                    </div>
                  </div>


                <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">รายละเอียด :</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" value="{{ @$sRow->b_details }}" name="b_details" required>
                    </div>
                </div>

@if( !empty($sRow) )
 				<div class="form-group row">
                    <label for="warehouse_id_fk" class="col-md-3 col-form-label">คลังจ่ายสินค้าตามใบเสร็จ * :</label>
                    <div class="col-md-9">
                          <select name="warehouse_id_fk" class="form-control select2-templating " required >
                            <option value="">-เลือกคลัง-</option>
                            @if(@$sWarehouse)
                            @foreach(@$sWarehouse AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->warehouse_id_fk)?'selected':'' }} >{{$r->w_name}}</option>
                            @endforeach
                            @endif
                          </select>
                    </div>
                </div>

 				<div class="form-group row">
                    <label for="warehouse_pack_id_fk" class="col-md-3 col-form-label">คลังจ่ายสินค้าตามใบเบิก * :</label>
                    <div class="col-md-9">
                          <select name="warehouse_pack_id_fk" class="form-control select2-templating " required >
                            <option value="">-เลือกคลัง-</option>
                            @if(@$sWarehouse)
                            @foreach(@$sWarehouse AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->warehouse_pack_id_fk)?'selected':'' }} >{{$r->w_name}}</option>
                            @endforeach
                            @endif
                          </select>
                    </div>
                </div>

  @endif
                 <div class="form-group row">
                      <label for="b_maker" class="col-md-3 col-form-label"> ผู้ทำรายการ : </label>
                      <div class="col-md-9">

                        @if( empty(@$sRow) )
                          <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="b_maker" >
                            @else
                              <input class="form-control" type="text" value="{{@$sMaker_name[0]->name}}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ @$sRow->b_maker }}" name="b_maker" >
                         @endif
                          
                      </div>
                  </div>


                <div class="form-group row">
                    <label class="col-md-3 col-form-label">สถานะ :</label>
                    <div class="col-md-9 mt-2">
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
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/branchs") }}">
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
                <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.warehouse.create') }}/{{@$sRow->id}}" style="float: right;">
                  <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                </a>
                <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> คลัง  </span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <table id="data-table-warehouse" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>
              </div>
            </div>
          <div class="form-group mb-0 row">
            <div class="col-md-6">
              <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/branchs") }}">
                <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
              </a>
            </div>
          </div>

    </div>   
 @endif

    </div>

    </div>

<!-- <input type="date" id="dt" onchange="mydate1();" />
 -->
<!--  <input type="text" id="ndt"  onclick="mydate();"  /> -->

    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')

<script>

  function mydate() {
  //alert("");
   document.getElementById("dt").hidden = false;
    document.getElementById("ndt").hidden = true;
  }

  function mydate1() {
    d = new Date(document.getElementById("dt").value);
    dt = d.getDate();
    mn = d.getMonth();
    mn++;
    yy = d.getFullYear();
    document.getElementById("ndt").value = dt + "/" + mn + "/" + yy
    document.getElementById("ndt").hidden = false;
    document.getElementById("dt").hidden = true;
  }



var branch_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
var sU = "{{@$sU}}"; 
var sD = "{{@$sD}}";
var oTable;
$(function() {
    oTable = $('#data-table-warehouse').DataTable({
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
          url: '{{ route('backend.warehouse.datatable') }}',
           data: function ( d ) {
                d.Where={};
                d.Where['branch_id_fk'] = branch_id_fk ;
                oData = d;
              },
          method: 'POST',
        },

        columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 'w_code', title :'<center>รหัสคลัง</center>', className: 'text-center'},
            {data: 'w_name', title :'<center>ชื่อคลัง</center>', className: 'text-center'},
            {data: 'w_details', title :'<center>รายละเอียด</center>', className: 'text-center'},
            {data: 'w_maker', title :'<center>ผู้ทำรายการ</center>', className: 'text-center'},
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
              + '<a href="{{ route('backend.warehouse.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
              + '<a href="javascript: void(0);" data-url="{{ route('backend.warehouse.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
            ).addClass('input');

          }

        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>

@endsection

