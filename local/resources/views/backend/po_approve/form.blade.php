@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ใบสั่งซื้อรออนุมัติ </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.po_approve.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.po_approve.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

                      <div class="myBorder">
                        <div class="form-group row">
                          <label for="example-text-input" class="col-md-3 col-form-label">รูปใบเสร็จ :<br>(Mouse แตะที่รูปเพื่อขยาย)</label>
                          <div class="col-md-6">


                            @IF(!empty(@$slip[0]->file))
                            <img id="imgAvatar_01" src="{{ $slip[0]->url }}/{{ @$slip[0]->file }}" width="200px" class="grow" >
                            @ELSE
                            <img id="imgAvatar_01" src="{{ asset('local/public/images/example_img.png') }}" class="grow" width="200px">
                            @ENDIF
                            
                          </div>
                        </div>
                  <!--       <div class="form-group row">
                          <label class="col-md-3 col-form-label">ตรวจสอบแล้วทำการอนุมัติ :</label>
                          <div class="col-md-8 mt-2">
                            <div class="custom-control custom-switch">
                              <input type="checkbox" class="custom-control-input" id="customSwitch" name="status_slip" value="true" {{ ( @$sRow->status_slip=='true')?'checked':'' }}>
                              <label class="custom-control-label" for="customSwitch"> อนุมัติ </label>
                            </div>
                          </div>
                        </div> -->
                        <div class="form-group mb-0 row">
                          <div class="col-md-6">
                            <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/po_approve") }}">
                              <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                            </a>
                          </div>
                          <div class="col-md-6 text-right">
                            
                            <button type="submit" name="approved" class="btn btn-primary btn-sm waves-effect font-size-16 " value='approved'>
                            <i class="bx bx-save font-size-16 align-middle mr-1"></i> อนุมัติ
                            </button> 
                            &nbsp;
                            &nbsp;
                            &nbsp;
                            <button type="submit" name="" class="btn btn-success btn-sm waves-effect font-size-16 " value='no_approved'>
                            <i class="bx bx-save font-size-16 align-middle mr-1"></i> อัพโหลดสลิปใหม่
                            </button>

                          </div>
                        </div>
                      </div>


              </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->


@endsection

@section('script')

<script>
var sU = "{{@$sU}}"; 
var sD = "{{@$sD}}";  
var orders_id = "{{@$sRow->id?@$sRow->id:0}}";
// alert(orders_id);
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
          url: '{{ route('backend.po_approve_set.datatable') }}',
           data: function ( d ) {
                  d.Where={};
                  d.Where['id'] = orders_id ;
                  oData = d;
                },
            method: 'POST',
          },
            
        columns: [
            {data: 'id', title :'PO-ID', className: 'text-center w50'},
            {data: 'code_order', title :'<center>เลขใบสั่งซื้อ </center>', className: 'text-center'},
            {data: 'price', title :'<center>ยอดชำระ </center>', className: 'text-center'},
            {data: 'pv_total', title :'<center>PV </center>', className: 'text-center'},
            {data: 'type', title :'<center>จุดประสงค์การสั่งซื้อ </center>', className: 'text-center'},
            {data: 'status_slip',   title :'<center>Status Slip</center>', className: 'text-center',render: function(d) {
              if(d=='true'){
                  return '<span class="badge badge-pill badge-soft-success font-size-16">T</span>';
              }else{
                  return '<span class="badge badge-pill badge-soft-danger font-size-16">F</span>';
              }
            }}, 
            {data: 'date', title :'<center>วันที่สั่งซื้อ </center>', className: 'text-center'},
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

