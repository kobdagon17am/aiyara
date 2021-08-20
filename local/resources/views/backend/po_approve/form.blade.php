@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/libs/magnific-popup/magnific-popup.min.css') }}">
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
        <div class="col-11">
            <div class="card">
                <div class="card-body">

     <div class="myBorder">
      <div class="row">
          <div class="col-12">
              <div class="card">
                  <div class="card-body">
                      <div class="row">
                        <div class="col-8">
                        </div>
                      </div>

                      <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                      </table>

                  </div>
              </div>
          </div> <!-- end col -->
      </div> <!-- end row -->
</div>

                    <div class="myBorder">
                        <div class="form-group row">

                            @if(@$slip)
                            @php 
                            @$i = 1
                            @endphp
                                @foreach(@$slip AS $r)
                                    <label for="example-text-input" class="col-md-1 col-form-label">Slip {{@$i}} </label>
                                        <div class="col-md-3">
                                            @if (!empty(@$r->file))
                                                <img id="imgAvatar_01" src="{{ $r->url }}/{{ @$r->file }}" width="200px"
                                                    class="grow">
                                            @ELSE
                                                <img id="imgAvatar_01" src="{{ asset('local/public/images/example_img.png') }}"
                                                    class="grow" width="200px">
                                            @ENDIF

                                        </div>
                            @php 
                            @$i++
                            @endphp
                                @endforeach
                            @endif
                            
                        </div>
                        <br>
                        <br>
                        <br>
                   
                        <div class="form-group mb-0 row">
                            <div class="col-md-6">
                                <a class="btn btn-secondary btn-sm waves-effect" href="{{ url('backend/po_approve') }}">
                                    <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                                </a>
                            </div>
                            <div class="col-md-6 text-right">

                                {{-- <button type="submit" name="approved" class="btn btn-primary btn-sm waves-effect font-size-16 " value='approved'>
                            <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึก
                            </button>
                            &nbsp;
                            &nbsp;
                            &nbsp; --}}

                            <button type="button" class="btn btn-primary waves-effect waves-light"
                            data-toggle="modal" data-target="#confirm">อนุมัติ</button>

                            <button type="button" class="btn btn-success btn-sm waves-effect font-size-16"
                            data-toggle="modal" data-target="#cancel">
                            <i class="bx bx-save font-size-16 align-middle mr-1"></i> อัพโหลดสลิปใหม่
                        </button>


                                <form action="{{ route('backend.po_approve.update', @$sRow->id) }}" method="POST"
                                    enctype="multipart/form-data" autocomplete="off">
                                    <input name="_method" type="hidden" value="PUT">
                                    <input name="id" value="{{ $sRow->id }}" type="hidden">

                                    {{ csrf_field() }}


                                    <div class="modal fade" id="confirm" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title mt-0" id="confirm">ยืนยันการอนุมัติใบสั่งซื้อ
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="col-md-12 mt-2 text-left">
                                                        <h5 class="font-size-14">ธนาคารที่โอนชำระ <span
                                                                class="text-danger">*</span></h5>
                                                        <select class="form-control" name="bank_name" required>
                                                            <option value="">Select</option>
                                                            <option value="ธนาคารกรุงเทพ">ธนาคารกรุงเทพ</option>
                                                            <option value="ธนาคารกรุงไทย">ธนาคารกรุงไทย</option>
                                                            <option value="ธนาคารกรุงศรีอยุธยา">ธนาคารกรุงศรีอยุธยา</option>
                                                            <option value="ธนาคารกสิกรไทย">ธนาคารกสิกรไทย</option>
                                                            <option value="ธนาคารทหารไทยธนชาต">ธนาคารทหารไทยธนชาต</option>
                                                            <option value="ธนาคารไทยพาณิชย์">ธนาคารไทยพาณิชย์</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-12 mt-2 mb-2 text-left">
                                                        <h5 class="font-size-14">วันที่สั่งซื้อในสลิป <span
                                                                class="text-danger">*</span></h5>
                                                        <input class="form-control" type="datetime-local" 
                                                            required>
                                                    </div>

                                                <div class="col-md-12 mt-2 mb-2 text-left">
                                                    <div class="row form-group " >
                                                        <div class="col-md-6 text-left">
                                                            <h5 class="font-size-14">ยอดชำระ </h5>
                                                            <input class="form-control" type="text" value="{{@$price}}" >
                                                        </div>
                                                        <div class="col-md-6 text-left">
                                                            <h5 class="font-size-14">ยอดโอน <span
                                                                class="text-danger">*</span></h5>
                                                            <input class="form-control" type="text" name="note_fullpayonetime" value="{{@$note_fullpayonetime}}" required="" > 
                                                        </div>
                                                    </div>
                                                </div>

                                                 <div class="col-md-12 mt-2 mb-2 text-left">
                                                    <div class="row form-group " >
                                                        <div class="col-md-7 text-left">
                                                            <h5 class="font-size-14">ยอดอนุมัติ (กรอกยืนยันยอดอีกครั้ง) <span
                                                                class="text-danger">*</span></h5>
                                                            </div>
                                                      <div class="col-md-5 text-left">
                                                            <input class="form-control" type="text" name="approval_amount_transfer" value="{{@$approval_amount_transfer}}" required="" >
                                                        </div>
                                                       
                                                    </div>
                                                </div>



                            <div class="col-md-12 mt-2 text-left">

                                  @IF(!empty(@$sRow->pay_with_other_bill))

                                                <h5 class="font-size-14">ชำระพร้อมบิลอื่น </h5>
                                                <input class="form-control" type="text" name=""
                                                    id="" value="{{@$sRow->pay_with_other_bill_note}}" >
                                                <br>
                                                <h5 class="font-size-14">ชำระร่วม </h5>
                                                <input class="form-control" type="text" name="" id="" value="{{@$sRow->pay_with_other_bill_note}}" >
                                  @ENDIF                 

                            @if(@$slip)
                            @php 
                            @$i = 1
                            @endphp
                                @foreach(@$slip AS $r)
                                    <br><h5>Slip {{@$i}} </h5>
                                            @if (!empty(@$r->file))
                                                <img src="{{ $r->url }}/{{ @$r->file }}"
                                                            class="img-fluid" alt="Responsive image">
                                            @ELSE
                                                <img src="{{ asset('local/public/images/example_img.png') }}"
                                                            class="img-fluid" alt="Responsive image">
                                            @ENDIF

                                        @IF($i==2)
                                                @IF(!empty(@$sRow->note_fullpayonetime_02))
                                                    <input class="form-control" type="text" name="" id="" value="{{@$sRow->note_fullpayonetime_02}}" >
                                                @endif
                                        @endif

                                        @IF($i==3)
                                                @IF(!empty(@$sRow->note_fullpayonetime_03))
                                                    <input class="form-control" type="text" name="" id="" value="{{@$sRow->note_fullpayonetime_03}}" >
                                                @endif
                                        @endif

                            @php 
                            @$i++
                            @endphp
                                @endforeach
                            @endif
                                                       
                                                </div>

                                                </div>

                                             

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" type="submit" name="approved" value='approved'
                                                        class="btn btn-primary">อนุมัติ</button>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->

                                </form>

                                <form action="{{ route('backend.po_approve.update', @$sRow->id) }}" method="POST"
                                    enctype="multipart/form-data" autocomplete="off">
                                    <input name="_method" type="hidden" value="PUT">
                                    <input name="id" value="{{ $sRow->id }}" type="hidden">

                                    {{ csrf_field() }}


                                    <div class="modal fade" id="cancel" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title mt-0" id="cancel">ยกเลิกบิลชำระ</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="form-group">
                                                        <label>Detail <span class="text-danger">*</span></label>
                                                        <div>
                                                            <textarea required="" name="detail" class="form-control"
                                                                rows="3"></textarea>
                                                        </div>
                                                    </div>

                                                    @if (!empty(@$slip[0]->file))
                                                        <img src="{{ $slip[0]->url }}/{{ @$slip[0]->file }}"
                                                            class="img-fluid" alt="Responsive image">
                                                    @ELSE
                                                        <img src="{{ asset('local/public/images/example_img.png') }}"
                                                            class="img-fluid" alt="Responsive image">
                                                    @ENDIF
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" type="submit" name="no_approved"
                                                        value='no_approved' class="btn btn-primary">อัพโหลดสลิปใหม่</button>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->




                                </form>

                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->


@endsection

@section('script')
    <script src="{{ URL::asset('backend/libs/magnific-popup/magnific-popup.min.js') }}"></script>

    <!-- Lightbox init js -->
    <script src="{{ URL::asset('backend/js/pages/lightbox.init.js') }}"></script>

<script>
var id = "{{@$sRow->id}}";
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
        // scrollY: ''+($(window).height()-370)+'px',
        // iDisplayLength: 25,
        ajax: {
                        url: '{{ route('backend.po_approve.datatable') }}',
                        data :{
                              id:id,
                            },
                          method: 'POST',
                        },
            
        columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 'created_at', title :'<center>วันที่สั่งซื้อ </center>', className: 'text-center'},
            {data: 'customer_name', title :'<center>รหัส:ชื่อลูกค้า </center>', className: 'text-left w100 '},
            {data: 'code_order', title :'<center>เลขใบสั่งซื้อ </center>', className: 'text-center'},
            {data: 'price', title :'<center>ยอดชำระ </center>', className: 'text-center'},
            {data: 'note_fullpayonetime', title :'<center>ยอดโอน </center>', className: 'text-center'},
            {data: 'transfer_money_datetime', title :'<center>วันเวลาที่โอน </center>', className: 'text-center'},
            {data: 'pay_with_other_bill_note', title :'<center>ชำระร่วม </center>', className: 'text-center'},
            
            {data: 'status', title :'<center>สถานะ </center>', className: 'text-center'},
            {data: 'status_slip',   title :'<center>Status Slip</center>', className: 'text-center',render: function(d) {
              if(d=='true'){
                  return '<span class="badge badge-pill badge-soft-success font-size-16">T</span>';
              }else{
                  return '<span class="badge badge-pill badge-soft-danger font-size-16">F</span>';
              }
            }},            
      
        ],
        rowCallback: function(nRow, aData, dataIndex){

           
        }

    });

});
</script>

@endsection
