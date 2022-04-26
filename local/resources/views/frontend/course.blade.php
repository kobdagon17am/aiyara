@extends('frontend.layouts.customer.customer_app')
@section('css')
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css"
    href="{{asset('frontend/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css"
    href="{{asset('frontend/assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css"
    href="{{asset('frontend/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">

@endsection
@section('conten')
<div class="row">
    <div class="col-md-12">
        <div class="card">

            {{--<div class="card-header">
                            <div class="row">
                        <div class="col-md-4">
                        <select class="form-control" id="order_type" >
                            <option value="">ทั้งหมด</option>
                            @foreach($data as $value)
                            <option value="{{ $value->group_id }}">{{ $value->orders_type }}</option>
            @endforeach
            </select>
        </div>
    </div>
</div> --}}
<div class="card-block">
    <div class="table-responsive ">
        <table id="history" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>หัวข้อ</th>
                    <th>C/E</th>
                    <th>ราคา</th>
                    <th>PV</th>
                    <th>CourseStatus</th>
                    <th>OrderStatus</th>
                    <th>QRCODE</th>
                </tr>
            </thead>

        </table>
    </div>
    <div id="modal_qr_recive"></div>

</div>
</div>
</div>
</div>
@endsection
@section('js')
<!-- data-table js -->

<script src="{{asset('frontend/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/jszip.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/pdfmake.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/vfs_fonts.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}">
</script>
<!-- Custom js -->
<script src="{{asset('frontend/assets/pages/data-table/js/data-table-custom.js')}}"></script>


<script type="text/javascript">
$(document).ready(function() {
    fetch_data();

});


function qrcode(id,type='') {
$.ajax({
        url: '{{ route('modal_qr_ce') }}',
        type: 'GET',
        data: {
            id: id,'type':type
        },
    })
    .done(function(data) {
        $('#modal_qr_recive').html(data);
        var countdown = document.getElementById("time");
        var close_modal = document.getElementById("close_modal");
        $('#show_qr').modal('show');
          //var i = $('#i').val();
          var s = $('#s').val();
          var id = $('#id').val();
          var type_qr_modal = $('#type_qr_modal').val();
          var timerId = '';
          if(type_qr_modal == 'non'){
            countdown.innerHTML = '<span class="text-danger"> QRCODE ของคุณหมดอายุ </span> 00:00 <button class="btn btn-sm btn btn-success btn-outline-success btn-icon" onclick="refresh_time('+id+',\'refresh_time\')"> <i class="icofont icofont-refresh"></i> </button>';
          }else{
            var time = s; // 30 minutes converted to 1800 seconds
            timerId = setInterval(function() {
              //var countdown = i *  s * 1000;
              time = time - 1;
              var minute = Math.floor(parseInt(time / 60));
              //console.log(minute);
              var second = parseInt(time % 60);
                minutes = minute < 10 ? "0" + minute : minute;
                seconds = second < 10 ? "0" + second : second;
                countdown.innerHTML = ' <span class="text-success"> กรุณาใช้ QRCODE นี้ภายใน </span>'+ minutes + ' : ' + seconds +' <button class="btn btn-sm btn btn-success btn-outline-success btn-icon" onclick="refresh_time('+id+',\'refresh_time\','+timerId+')"> <i class="icofont icofont-refresh"></i> </button>';

                if (second < 0) {
                  countdown.innerHTML = '<span class="text-danger"> QRCODE ของคุณหมดอายุ </span> 00:00 <button class="btn btn-sm btn btn-success btn-outline-success btn-icon" onclick="refresh_time('+id+',\'refresh_time\','+timerId+')"> <i class="icofont icofont-refresh"></i> </button>';
                }

            }, 1000);

          }

          var close_modal_html = '<button type="button" class="btn btn-default waves-effect" onclick="time_stop('+timerId+')" >Close</button>';
          close_modal.innerHTML = close_modal_html;

    })

    .fail(function() {
        console.log("error");
    })
}

function refresh_time(id,type,timerId){
clearInterval(timerId);
$('#show_qr').modal('hide');
qrcode(id,type);
}


function time_stop(timerId) {
if(timerId){
clearInterval(timerId);
}
$('#show_qr').modal('hide');
}


function fetch_data(order_type = '') {

    $('#history').DataTable({
            // scrollX: true,
            // scrollCollapsed: true,
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('dt_course') }}",
                dataType: "json",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    order_type: order_type
                }
            },

            columns: [{
                    "data": "id"
                    },
                    {
                    "data": "sdate"
                    },
                    {
                        "data": "edate"
                    },
                    {
                        "data": "title"
                    },
                    {
                        "data": "type"
                    },
                    {
                        "data": "price"
                    },
                    {
                        "data": "pv"
                    },
                    {
                        "data": "ce_status","class":'text-center'
                    },
                    {
                        "data": "status","class":'text-center'
                    },
                    {
                        "data": "qrcode","class":'text-center'
                    },
                ],
                //order: [[ "0", "desc" ]],
            });
    }

    $('#order_type').on('change', function() {
        var order_type = $(this).val();
        $('#history').DataTable().destroy();
        fetch_data(order_type);
    });
</script>
@endsection
