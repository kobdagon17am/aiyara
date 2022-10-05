@extends('backend.layouts.master')

@section('title')
    Aiyara Planet
@endsection

@section('css')
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18"> นำสินค้าออกทั่วไป</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <!-- <input type="text" class="form-control float-left text-center w125 myLike" placeholder="หมายเลข PO" name="po_number"> -->
                            <!-- <input type="text" class="form-control float-left text-center w125 myLike" placeholder="ชื่อ Supplier" name="supplier_name" style="margin-left: 1%;"> -->
                        </div>

                        <div class="col-4 text-right class_btn_add ">
                            <a class="btn btn-info btn-sm mt-1 " href="{{ route('backend.general_takeout.create') }}">
                                <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD
                            </a>
                        </div>

                    </div>

                    <table id="data-table" class="table table-bordered " style="width: 100%;">
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@section('script')
    <script>
        var role_group_id = "{{ @$role_group_id ? @$role_group_id : 0 }}"; //alert(sU);
        var menu_id = "{{ @$menu_id ? @$menu_id : 0 }}"; //alert(sU);
        var sU = "{{ @$sU }}"; //alert(sU);
        var sD = "{{ @$sD }}"; //alert(sD);
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
                scrollY: '' + ($(window).height() - 370) + 'px',
                iDisplayLength: 25,
                ajax: {
                    url: '{{ route('backend.general_takeout.datatable') }}',
                    data: function(d) {
                        d.Where = {};
                        $('.myWhere').each(function() {
                            if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                                d.Where[$(this).attr('name')] = $.trim($(this).val());
                            }
                        });
                        d.Like = {};
                        $('.myLike').each(function() {
                            if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                                d.Like[$(this).attr('name')] = $.trim($(this).val());
                            }
                        });
                        d.Custom = {};
                        $('.myCustom').each(function() {
                            if ($.trim($(this).val()) && $.trim($(this).val()) != '0') {
                                d.Custom[$(this).attr('name')] = $.trim($(this).val());
                            }
                        });
                        oData = d;
                    },
                    method: 'POST'
                },

                columns: [{
                        data: 'ref_doc',
                        title: 'Ref. Code',
                        className: 'text-center w100'
                    },
                    {
                        data: 'product_name',
                        title: '<center>รหัสสินค้า : ชื่อสินค้า </center>',
                        className: 'text-left'
                    },
                    {
                        data: 'product_out_cause',
                        title: '<center>สาเหตุที่นำออก </center>',
                        className: 'text-left'
                    },
                    {
                        data: 'lot_number',
                        title: '<center>ล็อตนัมเบอร์ </center>',
                        className: 'text-left'
                    },
                    {
                        data: 'lot_expired_date',
                        title: '<center>วันหมดอายุ </center>',
                        className: 'text-center'
                    },
                    {
                        data: 'amt',
                        title: '<center>จำนวน </center>',
                        className: 'text-center'
                    },
                    {
                        data: 'recipient_name',
                        title: '<center>พนักงานที่ดำเนินการ </center>',
                        className: 'text-center'
                    },
                    {
                        data: 'pickup_date',
                        title: '<center>วันที่นำออก </center>',
                        className: 'text-center'
                    },

                    {
                        data: 'approve_status',
                        title: '<center>สถานะการอนุมัติ</center>',
                        className: 'text-center w100 ',
                        render: function(d) {
                            if (d == 1) {
                                return '<span class="badge badge-pill badge-soft-success font-size-16" style="color:darkgreen">อนุมัติแล้ว</span>';
                            } else {
                                return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:black">รออนุมัติ</span> ';
                            }
                        }
                    },
                    {
                        data: 'id',
                        title: 'Tools',
                        className: 'text-center w100'
                    },
                ],
                rowCallback: function(nRow, aData, dataIndex) {


                    var sPermission = "<?= \Auth::user()->permission ?>";
                    var sU = sessionStorage.getItem("sU");
                    var sD = sessionStorage.getItem("sD");
                    if (sPermission == 1) {
                        sU = 1;
                        sD = 1;
                    }
                    var str_U = '';
                    if (sU == '1') {
                        str_U = '<a href="{{ route('backend.general_takeout.index') }}/' + aData[
                            'id'] +
                            '/edit" class="btn btn-sm btn-primary"  ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                    }
                    var str_D = '';
                    if (sD == '1') {
                        str_D =
                            '<a href="javascript: void(0);" data-url="{{ route('backend.general_takeout.index') }}/' +
                            aData['id'] + '" data-id="' + aData['id'] +
                            '" data-table="db_general_receive" data-file="" class="btn btn-sm btn-danger remove_01 "><i class="bx bx-trash font-size-16 align-middle"></i></a>';
                        // str_D = '<a href="javascript: void(0);" data-url="{{ route('backend.account_bank.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" ><i class="bx bx-trash font-size-16 align-middle"></i></a>';
                    }

                    str_V =
                        '<a href="javascript:void(0);" class="btn btn-sm" data-toggle="tooltip" data-toggle="tooltip" data-placement="left" title="อนุมัติแล้ว ห้ามลบ" disabled style="background-color:grey;color:white;" ><i class="bx bx-trash font-size-16 align-middle"></i></a>';

                      var print = '&nbsp;<a href="{{ url('backend/general_takeout_print') }}/' + aData[
                            'id'] +
                            '" title="print" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-print font-size-16 align-middle"></i></a> ';

                    if (sU != '1' && sD != '1') {
                        $('td:last-child', nRow).html('-'+print);
                    } else if (aData['approve_status'] == '1') {
                        $('td:last-child', nRow).html(str_U + str_V + print).addClass('input');
                    } else {
                        $('td:last-child', nRow).html(str_U + str_D + print).addClass('input');
                    }



                    // if(aData['approve_status']=='1'){
                    //     // $('td:last-child', nRow).html('-');

                    //     $('td:last-child', nRow).html(''
                    //       + '<a href="{{ route('backend.general_takeout.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                    //       // + '<a href="javascript: void(0);" data-url="{{ route('backend.general_takeout.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"  ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                    //       // + '<a href="javascript: void(0);" data-url="{{ route('backend.general_takeout.index') }}/'+aData['id']+'" data-id="'+aData['id']+'" data-table="db_general_takeout" data-file="" class="btn btn-sm btn-danger remove_01 "><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                    //       + ' <a href="javascript:void(0);" class="btn btn-sm" data-toggle="tooltip" data-toggle="tooltip" data-placement="left" title="อนุมัติแล้ว ห้ามลบ" disabled style="background-color:grey;color:white;" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                    //     ).addClass('input');

                    // }else{

                    //     $('td:last-child', nRow).html(''
                    //       + '<a href="{{ route('backend.general_takeout.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                    //       // + '<a href="javascript: void(0);" data-url="{{ route('backend.general_takeout.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"  ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                    //       + ' <a href="javascript: void(0);" data-url="{{ route('backend.general_takeout.index') }}/'+aData['id']+'" data-id="'+aData['id']+'" data-table="db_general_takeout" data-file="" class="btn btn-sm btn-danger remove_01 "><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                    //     ).addClass('input');

                    // }
                }
            });
            // $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
            //   oTable.draw();
            // });
            oTable.on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        });
    </script>



    <script>
        $(document).on('click', '.remove_01', function(event) {

            var id = $(this).data('id');
            var table = $(this).data('table');
            var file = $(this).data('file');

            // alert(id+" : "+table+" : "+file);
            // return false;

            if (!confirm("Are you sure ? ")) {
                return false;
            } else {

                $.ajax({

                    type: 'POST',
                    url: " {{ url('backend/general_takeout/delete') }} ",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    },
                    success: function(data) {
                        console.log(data);
                        location.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });

            }

        });
    </script>
@endsection
