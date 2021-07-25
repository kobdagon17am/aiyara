@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')


@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ประวัติการสินค้าจาก Supplier </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">


<div class="myBorder">

              @if( empty(@$sRow) )
              <form action="{{ route('backend.po_supplier_products_get.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="po_supplier_products_id_fk" value="{{@$Po_supplier_products->id}}">
              @else
              <form action="{{ route('backend.po_supplier_products_get.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input type="hidden" name="po_supplier_products_id_fk" value="{{@$Po_supplier_products->id}}">
              @endif
                {{ csrf_field() }}


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">อ้างอิงรหัส PO : </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$Po_supplier->po_number }}" readonly >
                    </div>
                </div>

               <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">ชื่อสินค้า : * </label>
                  <div class="col-md-10">
                    <select  class="form-control select2-templating " disabled="" >
                      <option value="">Select</option>
                        @if(@$sProduct)
                          @foreach(@$sProduct AS $r)
                            <option value="{{$r->product_id_fk}}" {{ (@$r->product_id_fk==@$Po_supplier_products->product_id_fk)?'selected':'' }} >{{$r->product_name}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                 <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">จำนวนที่สั่งซื้อ : </label>
                    <div class="col-md-3">
                        <input class="form-control" type="number" value="{{ @$Po_supplier_products->product_amt }}" readonly >
                    </div>
                </div>


                 <div class="form-group row">
                    <label for="amt" class="col-md-2 col-form-label">จำนวนที่ได้รับ :  *  </label>
                    <div class="col-md-3">
                        <input class="form-control" type="number" name="amt" value="{{ @$sRow->amt }}" required >
                    </div>
                </div>

              <div class="form-group row">
                    <label for="created_at" class="col-md-2 col-form-label">วันที่ได้รับสินค้า : * </label>
                    <div class="col-md-2">
                          <input class="form-control" autocomplete="off" id="created_at" name="created_at" value="{{ @$sRow->created_at }}" required  />
                    </div>
                </div>

               
                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">สถานะการรับสินค้า: * </label>
                  <div class="col-md-4">
                    <select name="get_status" class="form-control select2-templating " required >
                      <option value="">Select</option>
                        @if(@$sGetStatus)
                          @foreach(@$sGetStatus AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->get_status)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.po_supplier_products.index') }}/{{@$Po_supplier_products->id}}/edit" }}">
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




            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->
@endsection

@section('script')


  <script>



            var po_supplier_products_get_id_fk = "{{@$sRow->id?@$sRow->id:0}}"; console.log(po_supplier_products_get_id_fk);
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
                                    d.Where['po_supplier_products_get_id_fk'] = po_supplier_products_get_id_fk ;
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
                        + '<a href="{{ route('backend.po_supplier_products_get.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.po_supplier_products_get.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });


            </script>



  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" />

 <script>
      $('#created_at').datetimepicker({
          value: '',
          rtl: false,
          format: 'Y-m-d H:i',
          formatTime: 'H:i',
          formatDate: 'Y-m-d',
          monthChangeSpinner: true,
          closeOnTimeSelect: true,
          closeOnWithoutClick: true,
          closeOnInputClick: true,
          openOnFocus: true,
          timepicker: true,
          datepicker: true,
          weeks: false,
          minDate: 0,
      });


</script>


@endsection
