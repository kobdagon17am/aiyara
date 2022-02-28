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
            <h4 class="mb-0 font-size-18">  PO สั่งซื้อสินค้า </h4>
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
              <form action="{{ route('backend.po_supplier.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.po_supplier.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

    
                <div class="form-group row">
                  <label for="po_number" class="col-md-3 col-form-label">รหัสใบ PO :</label>
                  <div class="col-md-6">
                    @if( empty($sRow) )
                    <input class="form-control" type="text" name="po_number" value="{{ @$po_runno }}" readonly="">
                    @else
                    <input class="form-control" type="text" name="po_number" value="{{ @$sRow->po_number }}" readonly="">
                    @endif
                  </div>
                </div>

                      <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label"> Business Location : * </label>
                            <div class="col-md-6">
                               <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " required="" >
                              <option value="">-Business Location-</option>
                              @if(@$sBusiness_location)
                                @foreach(@$sBusiness_location AS $r)
                                <option value="{{$r->id}}" {{ (@$r->id==@$sRow->business_location_id_fk)?'selected':'' }} >
                                  {{$r->txt_desc}}
                                </option>
                                @endforeach
                              @endif
                            </select>
                            </div>
                          </div>

                        
                        <div class="form-group row">
                          <label for="" class="col-md-3 col-form-label"> สาขา : * </label>
                          <div class="col-md-6">

                              @if( empty(@$sRow) )
                                <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating " required >
                                   <option value="" selected>กรุณาเลือก Business Location ก่อน</option>
                                </select>
                              @else

                                  <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating " required >
                                    @if(@$sBranchs)
                                      @foreach(@$sBranchs AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->branch_id_fk)?'selected':'' }} >
                                        {{$r->b_name}}
                                      </option>
                                      @endforeach
                                    @endif

                                      </select>
                              @endif
                                

                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="example-text-input" class="col-md-3 col-form-label">  Supplier : * </label>
                          <div class="col-md-6">
                            <select name="supplier_id_fk" class="form-control select2-templating " required >
                              <option value="">Select</option>
                                @if(@$Supplier)
                                  @foreach(@$Supplier AS $r)
                                    <option value="{{$r->id}}" {{ (@$r->id==@$sRow->supplier_id_fk)?'selected':'' }} >
                                      {{$r->txt_desc}} 
                                    </option>
                                  @endforeach
                                @endif
                            </select>
                          </div>
                        </div>


                          <div class="form-group row">
                            <label for="po_code_other" class="col-md-3 col-form-label">เลข PO (อื่นๆ ถ้ามี) :</label>
                            <div class="col-md-6">
                              <input class="form-control" type="text" name="po_code_other" value="{{ @$sRow->po_code_other }}" >
                            </div>
                          </div>

                <div class="form-group row">
                  <label for="note" class="col-md-3 col-form-label">หมายเหตุ (ถ้ามี) :</label>
                  <div class="col-md-6">
                    <textarea class="form-control" rows="3" id="note" name="note" >{{ @$sRow->note }}</textarea>
                  </div>
                </div>


                <div class="form-group row">
                    <label for="created_at" class="col-md-3 col-form-label">วันที่สร้างใบ PO : * </label>
                    <div class="col-md-2">
                          <input class="form-control" autocomplete="off" id="created_at" name="created_at" value="{{@$sRow->created_at}}"   />
                    </div>
                </div>

                  <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">ผู้ดำเนินการ(User Login):</label>
                            <div class="col-md-6">
                              @if( empty($sRow) )
                                <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                  <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="action_user" >
                                  @else
                                    <input class="form-control" type="text" value="{{@$action_user}}" readonly >
                                  <input class="form-control" type="hidden" value="{{ @$sRow->action_user }}" name="action_user" >
                               @endif
                            </div>
                       </div>
                          
  
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">สถานะ :</label>
                    <div class="col-md-9 mt-2">
                      <div class="custom-control custom-switch">
                      
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="buy_status" value="1" {{ ( @$sRow->buy_status=='1')?'checked':'' }}>
                          <label class="custom-control-label" for="customSwitch">ดำเนินการสั่งซื้อแล้ว</label>

                      </div>
                    </div>
                </div>


                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/po_supplier") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect btnSave ">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </div>

              </form>
            </div>


 @if( !empty($sRow) )

      <div class="myBorder">
        <div style="">
          <div class="form-group row">
            <div class="col-md-12">
              <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการสินค้าในใบ PO </span>

              <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.po_supplier_products.create') }}/{{@$sRow->id}}" style="float: right;" >
                <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
              </a>

               <a href="{{ URL::to('backend/po_supplier_products/print_receipt') }}/{{@$sRow->id}}" target=_blank ><i class="bx bx-printer grow " style="font-size:26px;cursor:pointer;color:#0099cc;float: right;padding: 1%;margin-right: 1%;"></i> 
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

                <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/po_supplier") }}">
                  <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                </a>

            </div>
        </div>

      </div>

@endif

<div class="myBorder">
<div style="">
  <div class="form-group row">
    <div class="col-md-12">
      <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> ประวัติการรับสินค้า </span>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-12">
      <table id="data-table-history" class="table table-bordered dt-responsive" style="width: 100%;">
      </table>
    </div>
  </div>
</div>
</div>


        </div>
    </div> <!-- end col -->
</div>
</div>
<!-- end row -->

@endsection

@section('script')

        <script type="text/javascript">


                function showPreview_01(ele)
                    {
                        $('#image').attr('src', ele.value); // for IE
                        if (ele.files && ele.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $('#imgAvatar_01').show();
                                $('#imgAvatar_01').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(ele.files[0]);
                    }
                }


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


  <script>

            var po_supplier_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
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
                            url: '{{ route('backend.po_supplier_products.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['po_supplier_id_fk'] = po_supplier_id_fk ;
                                    oData = d;
                                  },
                              method: 'POST',
                            },
                 
                    columns: [
                        {data: 'id', title :'ID', className: 'text-center w50'},
                        {data: 'product_name', title :'รหัส : ชื่อสินค้า', className: 'text-left'},
                        {data: 'product_amt', title :'จำนวนที่สั่งซื้อ', className: 'text-center'},
                        {data: 'product_unit_desc', title :'หน่วยนับ', className: 'text-center'},
                        {data: 'get_status', title :'สถานะการรับสินค้าจาก Supplier', className: 'text-center'},
                        {data: 'id', title :'Tools', className: 'text-center w80'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                      $('td:last-child', nRow).html(''
                        + '<a href="{{ route('backend.po_supplier_products.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.po_supplier_products.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });


            </script>

            
  <script>

    var po_supplier_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
    var oTable2;
    $(function() {
        oTable2 = $('#data-table-history').DataTable({
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
                url: '{{ route('backend.po_supplier_products_receive.datatable') }}',
                data :{
                      po_supplier_id_fk:po_supplier_id_fk,
                    },
                  method: 'POST',
                },
            columns: [
                {data: 'id', title :'<center>ID', className: 'text-center w50'},
                {data: 'action_date', title :'<center>วันที่ได้รับสินค้า', className: 'text-center'},
                {data: 'product_name', title :'<center>ชื่อสินค้า', className: 'text-center'},
                {data: 'amt_get', title :'<center>จำนวนที่ได้รับ', className: 'text-center'},
                {data: 'product_unit_desc', title :'หน่วยนับ', className: 'text-center'},
                {data: 'warehouses', title :'สินค้าอยู่ที่', className: 'text-center'},
            ],

        });

    });


    </script>



<script type="text/javascript">

       $('#business_location_id_fk').change(function(){

        $('.myloading').show();

          var business_location_id_fk = this.value;
          // alert(warehouse_id_fk);
           if(business_location_id_fk != ''){
             $.ajax({
                  url: " {{ url('backend/ajaxGetBranch') }} ",
                  method: "post",
                  data: {
                    business_location_id_fk:business_location_id_fk,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                   if(data == ''){
                       alert('ไม่พบข้อมูลสาขา !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือกสาขา -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.b_name+'</option>';
                       });
                       $('#branch_id_fk').html(layout);
                   }
                   $('.myloading').hide();
                  }
                })
           }

      });



</script>

@endsection
