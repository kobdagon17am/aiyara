@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')


@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> รายการสินค้าในใบ PO </h4>
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
              <form action="{{ route('backend.po_supplier_products.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.po_supplier_products.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">อ้างอิงรหัส PO : </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRowNew->po_number }}" readonly >
                        <input type="hidden" name="po_supplier_id_fk" value="{{@$sRowNew->id}}" >
                    </div>
                </div>

               <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">ชื่อสินค้า : * </label>
                  <div class="col-md-10">
                    <select name="product_id_fk" class="form-control select2-templating " disabled="" >
                      <option value="">Select</option>
                        @if(@$sProduct)
                          @foreach(@$sProduct AS $r)
                            <option value="{{$r->product_id_fk}}" {{ (@$r->product_id_fk==@$sRow->product_id_fk)?'selected':'' }} >{{$r->product_name}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                 <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">จำนวนที่สั่งซื้อ : </label>
                    <div class="col-md-3">
                        <input class="form-control" type="number" name="product_amt" value="{{ @$sRow->product_amt }}" disabled >
                    </div>
                </div>

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">หน่วยนับ : * </label>
                  <div class="col-md-10">
                    <select name="product_unit" class="form-control select2-templating " disabled="" >
                      <!-- <option value="">Select</option> -->
                        @if(@$sProductUnit)
                          @foreach(@$sProductUnit AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==4)?'selected':'' }} >{{$r->product_unit}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>


       <!--          <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.po_supplier.index') }}/{{@$sRowNew->id}}/edit" }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        
                        <button type="submit" class="btn btn-primary btn-sm waves-effect">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </div> -->

              </form>
</div>


@if( !empty($sRow) )

<div class="myBorder" >

      
        <div style="">
          <div class="form-group row">
            <div class="col-md-12">
              <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการประวัติการรับสินค้าจาก Supplier </span>
                <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.po_receive_products_get.create') }}/{{@$sRow->id}}" style="float: right;" >
                <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">บันทึกประวัติการรับสินค้า</span>
              </a>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
              </table>
            </div>
          </div>
        </div>

        <div class="form-group mb-0 row">
            <div class="col-md-6">
                  <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.po_receive.index') }}/{{@$sRowNew->id}}/edit" }}">
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

            var po_supplier_products_id_fk = "{{@$sRow->id?@$sRow->id:0}}"; console.log(po_supplier_products_id_fk);
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
                    scrollY: ''+($(window).height()-370)+'px',
                    iDisplayLength: 5,
                    ajax: {
                            url: '{{ route('backend.po_supplier_products_get.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['po_supplier_products_id_fk'] = po_supplier_products_id_fk ;
                                    oData = d;
                                  },
                              method: 'POST',
                            },
                 
                    columns: [
                        {data: 'id', title :'<center>ID', className: 'text-center w50'},
                        {data: 'time_get', title :'<center>ครั้งที่', className: 'text-center'},
                        {data: 'amt', title :'<center>จำนวนที่ได้รับ', className: 'text-center'},
                        // {data: 'product_unit_desc', title :'หน่วยนับ', className: 'text-center'},
                        {data: 'get_status', title :'<center>สถานะการรับสินค้าจาก Supplier', className: 'text-center'},
                        {data: 'id', title :'<center>Tools', className: 'text-center w80'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                      $('td:last-child', nRow).html(''
                        + '<a href="{{ route('backend.po_receive_products_get.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.po_receive_products_get.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
      
            });


            </script>

@endsection
