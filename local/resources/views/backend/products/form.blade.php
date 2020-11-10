@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/libs/select2/select2.min.css')}}">
<style type="text/css">
    .select2-dropdown {
       font-size: 16px;
    }
</style>

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">สินค้า</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.products.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.products.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Product Code :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->product_code }}" name="product_code" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Category :</label>
                    <div class="col-md-10">
                         <select name="category_id" class="form-control select2-templating "  required >
                         <option value="">Select</option>
                            @if(@$dsCategory)
                                @foreach(@$dsCategory AS $r)
                                    <option value="{{@$r->id}}" {{ (@$r->id==@$sRow->category_id)?'selected':'' }} >{{@$r->category_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Orders Type :</label>
                    <div class="col-md-10">
                       @php
                        $v_orders_type_id = explode(",",@$sRow->orders_type_id);
                        @endphp
                        @foreach($dsOrders_type as $k => $r)
                        @php
                        $checked = '';
                        foreach($v_orders_type_id as $v_orders_type){
                        if($r->id==$v_orders_type) $checked = 'checked';
                        }
                        @endphp
                        <div class="checkbox-color checkbox-primary">
                            <input id="orders_type{{$r->id}}" name="orders_type[]"
                            type="checkbox" {{$checked}}
                            value="{{$r->id}}">
                            <label for="orders_type{{$r->id}}">
                                {{$r->orders_type}}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>


                 @if( !empty($sRow) )
                     <div class="form-group row">
                        <label class="col-md-2 col-form-label">สถานะ :</label>
                        <div class="col-md-10 mt-2">
                          <div class="custom-control custom-switch">
                              <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" {{ ( @$sRow->status=='1')?'checked':'' }}>
                              <label class="custom-control-label" for="customSwitch">ใช้งานปกติ</label>
                          </div>
                        </div>
                    </div>
                  @endif



                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/products") }}">
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



                @if( !empty($sRow) )

                        <hr>

                        <div style="">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <span style="font-weight: bold;padding-right: 10px;"> Product Details </span>
                                    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.products_details.create') }}/{{@$sRow->id}}">
                                        <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่มรายการ</span>
                                    </a>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <table id="data-table-product-detail" class="table table-bordered dt-responsive" style="width: 100%;">
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0 row">
                                <div class="col-md-6">
                                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/products") }}">
                                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                                    </a>
                                </div>
                        </div>


                        <hr>

                        <div style="">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <span style="font-weight: bold;padding-right: 10px;"> รูปสินค้า  </span>
                                    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.products_images.create') }}/{{@$sRow->id}}">
                                        <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่มรูปสินค้า</span>
                                    </a>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <table id="data-table-product-images" class="table table-bordered dt-responsive" style="width: 100%;">
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0 row">
                                <div class="col-md-6">
                                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/products") }}">
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
@section('script')

    <script src="{{ URL::asset('backend/libs/select2/select2.min.js')}}"></script>
    <script>
      $('.select2-templating').select2();
    </script>  


           <script>

            var product_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
            var oTable;

            $(function() {
                oTable = $('#data-table-product-detail').DataTable({
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
                            url: '{{ route('backend.products_details.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['product_id_fk'] = product_id_fk ;
                                    oData = d;
                                  },
                              method: 'POST',
                            },
                 
                    columns: [
                        {data: 'id', title :'ID', className: 'text-center w50'},
                        {data: 'product_name', title :'ชื่อสินค้า', className: 'text-center'},
                        {data: 'descriptions_txt', title :'descriptions', className: 'text-center'},
                        {data: 'id', title :'Tools', className: 'text-center w60'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                      $('td:last-child', nRow).html(''
                        + '<a href="{{ route('backend.products_details.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.products_details.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });
            </script>




           <script>

            var product_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
            var oTable;

            $(function() {
                oTable = $('#data-table-product-images').DataTable({
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
                            url: '{{ route('backend.products_images.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['product_id_fk'] = product_id_fk ;
                                    oData = d;
                                  },
                              method: 'POST',
                            },
                 
                    columns: [
                        {data: 'id', title :'ID', className: 'text-center w50'},
                        {data: 'img_path',   title :'<center>IMAGE</center>', className: 'text-center',render: function(d) {
                           return '<img src="'+d+'" width="150">';
                        }},
                        // {data: 'product_img',   title :'<center>IMAGE</center>', className: 'text-center',render: function(d) {
                        //    return '<img src="{{ url("local/public/products") }}/'+d+'" width="150">';
                        // }},
                        // {data: 'image_default', title :'กำหนดเป็นรูปหลัก', className: 'text-center'},
                        {data: 'image_default',   title :'<center>รูปหลัก</center>', className: 'text-center',render: function(d) {
                             return d==1?'Yes':'';
                        }},

                        {data: 'id', title :'Tools', className: 'text-center w60'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                      $('td:last-child', nRow).html(''
                        + '<a href="{{ route('backend.products_images.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.products_images.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });
            </script>

@endsection

@endsection
