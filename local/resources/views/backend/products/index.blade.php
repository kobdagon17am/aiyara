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
                <h4 class="mb-0 font-size-18"> สินค้า </h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <?php
    $sPermission = \Auth::user()->permission;
    // $menu_id = @$_REQUEST['menu_id'];
    $menu_id = Session::get('session_menu_id');
    if ($sPermission == 1) {
        $sC = '';
        $sU = '';
        $sD = '';
    } else {
        $role_group_id = \Auth::user()->role_group_id_fk;
        $menu_permit = DB::table('role_permit')
            ->where('role_group_id_fk', $role_group_id)
            ->where('menu_id_fk', $menu_id)
            ->first();
        $sC = @$menu_permit->c == 1 ? '' : 'display:none;';
        $sU = @$menu_permit->u == 1 ? '' : 'display:none;';
        $sD = @$menu_permit->d == 1 ? '' : 'display:none;';
    }
    ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <input type="text" class="form-control float-left text-center w130 myLike product_code "
                                placeholder="Product Code">
                            <input type="text" class="form-control float-left text-center w250 myLike product_name "
                                placeholder="Product Name" style="margin-left: 1%;">
                            <input type="text" class="form-control float-left text-center w250 myLike product_cat "
                                placeholder="Category" style="margin-left: 1%;">
                        </div>

                        <div class="col-4 text-right" style="{{ @$sC }}">
                            <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.products.create') }}">
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
        var sU = "{{ @$sU }}";
        var sD = "{{ @$sD }}";
        var product_code = $(".product_code").val();
        var oTable;
        $(function() {
            oTable = $('#data-table').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                processing: true,
                serverSide: true,
                scroller: true,
                scrollCollapse: true,
                scrollX: true,
                ordering: true,
                destroy: true,
                // scrollY: ''+($(window).height()-370)+'px',
                iDisplayLength: 100,
                ajax: {
                    url: '{{ route('backend.products.datatable') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_code: product_code,
                    },
                    method: 'POST',
                },

                columns: [
                  // {
                  //       data: 'id',
                  //       title: 'ID',
                  //       className: 'text-center w50'
                  //   },
                    {
                        data: 'product_code',
                        title: '<center>Product Code</center>',
                        className: 'text-center'
                    },
                    {
                        data: 'pname',
                        title: '<center>Product Name</center>',
                        className: 'text-left'
                    },
                    {
                        data: 'Categories',
                        title: '<center>Category</center>',
                        className: 'text-left'
                    },

                    {
                        data: 'price_detail',
                        title: '<center>รายละเอียดราคา</center>',
                        className: 'text-left'
                    },

                    // {
                    //     data: 'selling_price',
                    //     title: '<center>ราคาขาย</center>',
                    //     className: 'text-left'
                    // },

                    // {
                    //     data: 'member_price',
                    //     title: '<center>ราคาสมาชิก</center>',
                    //     className: 'text-left'
                    // },

                    // {
                    //     data: 'pv',
                    //     title: '<center>คะแนน</center>',
                    //     className: 'text-left'
                    // },

                    // {
                    //     data: 'txt_desc',
                    //     title: '<center>ประเทศ</center>',
                    //     className: 'text-left'
                    // },
                    {
                        data: 'status',
                        title: '<center>สถานะ</center>',
                        className: 'text-center',
                        render: function(d) {
                            return d == 1 ? '<span style="color:blue">เปิดใช้งาน</span>' :
                                '<span style="color:red">ปิด</span>';
                        }
                    },
                    {
                        data: 'id',
                        title: 'Tools',
                        className: 'text-center w60'
                    },
                ],
                rowCallback: function(nRow, aData, dataIndex) {

                    if (sU != '' && sD != '') {
                        $('td:last-child', nRow).html('-');
                    } else {

                        $('td:last-child', nRow).html('' +
                            '<a href="{{ route('backend.products.index') }}/' + aData['id'] +
                            '/edit" class="btn btn-sm btn-primary" style="' + sU +
                            '" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ' +
                            '<a href="javascript: void(0);" data-url="{{ route('backend.products.index') }}/' +
                            aData['id'] + '" class="btn btn-sm btn-danger cDelete" style="' + sD +
                            '" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                            // '<a href="{{url('')}}"></a>';
                        ).addClass('input');

                    }


                    if (aData['status'] == 0) {
                        for (var i = 1; i < 4; i++) {
                            $('td:eq( ' + i + ')', nRow).html(aData[i]).css({
                                'color': '#808080',
                                'text-decoration': 'line-through',
                                'font-style': 'italic'
                            });
                        }
                    }


                }
            });
            // $('.myLike').on('change', function(e){
            //   var product_code = $(".product_code").val();
            //   alert(product_code);
            //   oTable.draw();
            // });
        });
    </script>

    <script>
        $(document).ready(function() {

            $(document).on('change', '.myLike', function(event) {
                event.preventDefault();

                $(".myloading").show();
                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                var sU = "{{ @$sU }}";
                var sD = "{{ @$sD }}";
                var product_code = $(".product_code").val();
                var product_name = $(".product_name").val();
                var product_cat = $(".product_cat").val();
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
                        destroy: true,
                        scrollY: '' + ($(window).height() - 370) + 'px',
                        iDisplayLength: 25,

                        ajax: {
                            url: '{{ route('backend.products.datatable') }}',
                            data: {
                                _token: '{{ csrf_token() }}',
                                product_code: product_code,
                                product_name: product_name,
                                product_cat: product_cat,
                            },
                            method: 'POST',
                        },

                        columns: [{
                                data: 'id',
                                title: 'ID',
                                className: 'text-center w50'
                            },
                            {
                                data: 'product_code',
                                title: '<center>Product Code</center>',
                                className: 'text-center'
                            },
                            {
                                data: 'pname',
                                title: '<center>Product Name</center>',
                                className: 'text-left'
                            },
                            {
                                data: 'Categories',
                                title: '<center>Category</center>',
                                className: 'text-left'
                            },
                            {
                                data: 'status',
                                title: '<center>สถานะ</center>',
                                className: 'text-center',
                                render: function(d) {
                                    return d == 1 ?
                                        '<span style="color:blue">เปิดใช้งาน</span>' :
                                        '<span style="color:red">ปิด</span>';
                                }
                            },
                            {
                                data: 'id',
                                title: 'Tools',
                                className: 'text-center w60'
                            },
                        ],
                        rowCallback: function(nRow, aData, dataIndex) {

                            if (sU != '' && sD != '') {
                                $('td:last-child', nRow).html('-');
                            } else {

                                $('td:last-child', nRow).html('' +
                                    '<a href="{{ route('backend.products.index') }}/' +
                                    aData['id'] +
                                    '/edit" class="btn btn-sm btn-primary" style="' +
                                    sU +
                                    '" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ' +
                                    '<a href="javascript: void(0);" data-url="{{ route('backend.products.index') }}/' +
                                    aData['id'] +
                                    '" class="btn btn-sm btn-danger cDelete" style="' +
                                    sD +
                                    '" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                ).addClass('input');

                            }
                        }
                    });
                    // $('.myLike').on('change', function(e){
                    //   var product_code = $(".product_code").val();
                    //   alert(product_code);
                    //   oTable.draw();
                    // });
                });
                // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                setTimeout(function() {
                    $(".myloading").hide();
                }, 1500);


            });

        });
    </script>
@endsection
