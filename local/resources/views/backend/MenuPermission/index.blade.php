@extends('backend.layouts.master')
@section("content")
<div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-header card">
                    <div class="card-block">

                        <h5 class="m-b-10">กำหนดสิทธิ์การเข้าถึงข้อมูล</h5>

                    </div>
                </div>

                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                    
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        <table id="simpletable" class="table table-striped table-hover table-bordered nowrap">
                                            <thead class="theadStyle">
                                            <tr>
                                                <th>id</th>
                                                <th>txt_desc</th>
                                                <th>กำหนดสิทธิ์</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
           </div>
        </div>
    </div>
@endsection
@section("script")
    <input type="hidden" value="{{ csrf_token() }}" id="token">
    <div id="resultDelete"></div>
    <div id="resultMain"></div>
    <div id="resultModal"></div>
    <input type="hidden" value="0" id="reloadCheck">
    <input type="hidden"  value="{{url('backend/data_table/menu_permission')}}" id="getUrlTable">
    <input type="hidden"  value="{{url('backend/menu_permission')}}" id="urlResource">
    <script>
        $(function () {
            var t = $('#simpletable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "searching": false,
                "lengthChange": false,
                ajax: {
                    url:$('#getUrlTable').val(),
                    type:"POST",
                    data:{_token:$('#token').val()},
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'txt_desc', name: 'txt_desc'},
                    {data: 'id', name: 'id'},
                ],
                rowCallback: function(nRow, aData, dataIndex){
                      $('td:last-child', nRow).html(''
                        + '<a href="{{ route('backend.menu_permission.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.menu_permission.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    },

                dom: 'lBfrtip',
                "buttons": [],
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                "order": [[0, 'asc']]
            });
            t.on('draw.dt', function () {
                var PageInfo = $('#simpletable').DataTable().page.info();
                t.column(0, {page: 'current'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });

            setInterval(function () {
                var reloadCheck = $('#reloadCheck').val();
                if (reloadCheck > 0) {
                    t.ajax.reload();
                    $('#reloadCheck').val(0);
                }
            }, 1300);

        });


        function viewShow(id) {
            $.ajax({
                url: $('#urlResource').val()+'/' + id,
                data: {id: id},
                type: 'GET',
                success: function (data) {
                    $('#resultModal').html(data);
                    $("#large-Modal").modal('show');
                    $('#large-Modal').modal({backdrop: 'static', keyboard: false});
                }
            });
        }


        function valDeleteData(id) {
            var token = $('#token').val();
            $.ajax({
                url: $('#urlResource').val()+"/" + id,
                type: "POST",
                data: {_method: 'delete', _token: token},
                success: function (data) {
                    $('#resultDelete').html(data);
                    $('#reloadCheck').val(10);

                }

            });
        }


        function deleteData(id) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("Poof! Your item has been deleted!", {
                            icon: "success",
                        });
                        valDeleteData(id);
                    } else {
                        swal("Your item is safe!");
                    }
                });

        }


    </script>

@endsection
