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



                    <div class="myBorder">
                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label">รูปใบเสร็จ :<br>(Mouse
                                แตะที่รูปเพื่อขยาย)</label>
                            <div class="col-md-6">


                                @if (!empty(@$slip[0]->file))
                                    <img id="imgAvatar_01" src="{{ $slip[0]->url }}/{{ @$slip[0]->file }}" width="200px"
                                        class="grow">
                                @ELSE
                                    <img id="imgAvatar_01" src="{{ asset('local/public/images/example_img.png') }}"
                                        class="grow" width="200px">
                                @ENDIF

                            </div>
                        </div>
                        <!--       <div class="form-group row">
                                      <label class="col-md-3 col-form-label">ตรวจสอบแล้วทำการอนุมัติ :</label>
                                      <div class="col-md-8 mt-2">
                                        <div class="custom-control custom-switch">
                                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status_slip" value="true" {{ @$sRow->status_slip == 'true' ? 'checked' : '' }}>
                                          <label class="custom-control-label" for="customSwitch"> อนุมัติ </label>
                                        </div>
                                      </div>
                                    </div> -->
                        <div class="form-group mb-0 row">
                            <div class="col-md-6">
                                <a class="btn btn-secondary btn-sm waves-effect" href="{{ url('backend/po_approve') }}">
                                    <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                                </a>
                            </div>
                            <div class="col-md-6 text-right">

                                {{-- <button type="submit" name="approved" class="btn btn-primary btn-sm waves-effect font-size-16 " value='approved'>
                            <i class="bx bx-save font-size-16 align-middle mr-1"></i> อนุมัติ
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
                                                        <input class="form-control" type="datetime-local" name="slip_date"
                                                            id="example-datetime-local-input" required>
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
        var sU = "{{ @$sU }}";
        var sD = "{{ @$sD }}";
        var orders_id = "{{ @$sRow->id ? @$sRow->id : 0 }}";
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
                "info": false,
                "lengthChange": false,
                "paging": false,
                scrollY: '' + ($(window).height() - 370) + 'px',
                ajax: {
                    url: '{{ route('backend.po_approve_set.datatable') }}',
                    data: function(d) {
                        d.Where = {};
                        d.Where['id'] = orders_id;
                        oData = d;
                    },
                    method: 'POST',
                },

                columns: [{
                        data: 'id',
                        title: 'PO-ID',
                        className: 'text-center w50'
                    },
                    {
                        data: 'code_order',
                        title: '<center>เลขใบสั่งซื้อ </center>',
                        className: 'text-center'
                    },
                    {
                        data: 'price',
                        title: '<center>ยอดชำระ </center>',
                        className: 'text-center'
                    },
                    {
                        data: 'pv_total',
                        title: '<center>PV </center>',
                        className: 'text-center'
                    },
                    {
                        data: 'type',
                        title: '<center>จุดประสงค์การสั่งซื้อ </center>',
                        className: 'text-center'
                    },
                    {
                        data: 'status_slip',
                        title: '<center>Status Slip</center>',
                        className: 'text-center',
                        render: function(d) {
                            if (d == 'true') {
                                return '<span class="badge badge-pill badge-soft-success font-size-16">T</span>';
                            } else {
                                return '<span class="badge badge-pill badge-soft-danger font-size-16">F</span>';
                            }
                        }
                    },
                    {
                        data: 'date',
                        title: '<center>วันที่สั่งซื้อ </center>',
                        className: 'text-center'
                    },
                ],
                rowCallback: function(nRow, aData, dataIndex) {

                }

            });
            $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
                oTable.draw();
            });
        });

    </script>
@endsection
