@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ค่าขนส่ง  </h4>
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
                      <form action="{{ route('backend.shipping_cost.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                      @else
                      <form action="{{ route('backend.shipping_cost.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <input name="_method" type="hidden" value="PUT">
                      @endif
                        {{ csrf_field() }}


                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-2 col-form-label">Business Location :</label>
                                <div class="col-md-10">
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
                                <label for="example-text-input" class="col-md-2 col-form-label">รายการค่าขนส่ง :</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="{{ @$sRow->shipping_name }}" name="shipping_name" required="" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-2 col-form-label">ยอดซื้อ (บาท) :</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="{{ @$sRow->purchase_amt }}" name="purchase_amt"  >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-2 col-form-label">ค่าขนส่ง (บาท) :</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="number" value="{{ @$sRow->shipping_cost }}" name="shipping_cost"  >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-2 col-form-label">ประเภทเขต :</label>
                                <div class="col-md-10">
                                      <select name="shipping_type" class="form-control select2-templating " required >
                                        <option value="">-เลือก-</option>
                                        <option value="1" {{ (@$sRow->shipping_type==1)?'selected':'' }} >เขตอื่นๆ (Other areas)</option>
                                        <option value="2" {{ (@$sRow->shipping_type==2)?'selected':'' }} >เมืองหลวงและปริมลฑล (Capital and Metropolitan)</option>
                                      </select>
                                </div>
                            </div>


                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/shipping_cost") }}">
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

        @if( !empty($sRow) && @$sRow->shipping_type==2 )

            <div class="myBorder">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.shipping_vicinity.create') }}/{{@$sRow->id}}" style="float: right;" >
                                            <i class="bx bx-plus  mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                                        </a>
                                        <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i>
                                         จังหวัดในกลุ่ม กรุงเทพฯ และปริมณฑล (Provinces in the Bangkok and suburbs ) </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                    <table id="data-table-bangkok_vicinity" class="table table-bordered dt-responsive" style="width: 100%" ></table>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
        @endif

        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->
@section('script')


<script>
var shipping_cost_id_fk = "{{@$sRow->id?@$sRow->id:0}}"; //alert(shipping_cost_id_fk);
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
var oTable;
$(function() {
    oTable = $('#data-table-bangkok_vicinity').DataTable({
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
            url: '{{ route('backend.shipping_vicinity.datatable') }}',
            data :{
                  shipping_cost_id_fk:shipping_cost_id_fk,
                },
              method: 'POST',
            },
        columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            // {data: 'province_code', title :'<center>รหัสจังหวัด </center>', className: 'text-center'},
            {data: 'province_name', title :'<center>ชื่อจังหวัด </center>', className: 'text-center'},
            // {data: 'province_name_en', title :'<center>ค่าขนส่ง (บาท) </center>', className: 'text-center'},
            {data: 'id', title :'Tools', className: 'text-center w80'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){
          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

              $('td:last-child', nRow).html(''
                + '<a href="{{ route('backend.shipping_vicinity.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                + '<a href="javascript: void(0);" data-url="{{ route('backend.shipping_vicinity.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
              ).addClass('input');

          }
        }
    });

});
</script>
@endsection

@endsection

