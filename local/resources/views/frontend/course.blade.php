@extends('frontend.layouts.customer.customer_app')
@section('css')
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">

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
                <div class="table-responsive dt-responsive">

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
                               <th>สถานะ</th>
                               <th>QRCODE</th>
                           </tr>
                       </thead>

                   </table>
               </div>
               <div id="modal_qr"></div>

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
<script src="{{asset('frontend/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
<!-- Custom js -->
<script src="{{asset('frontend/assets/pages/data-table/js/data-table-custom.js')}}"></script>


<script type="text/javascript">
    $(document).ready(function() {
        fetch_data();

    });

    function qrcode(id){
      $.ajax({
        url: '{{ route('modal_qr_ce')}}',
        type: 'GET',
        data: {id:id},
    })
      .done(function(data) {
        console.log("success");
        $('#modal_qr').html(data);
        $('#show_qr').modal('show');
    })
      .fail(function() {
        console.log("error");
    })
 
      
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
                    data: {_token:'{{ csrf_token() }}',order_type:order_type}
                },

                columns:[
                {"data": "id"},
                {"data": "sdate"},
                {"data": "edate"},
                {"data": "title"},
                {"data": "type"},
                {"data": "price"},
                {"data": "pv"},
                {"data": "status"},
                {"data": "qrcode"},
                ],
                //order: [[ "0", "desc" ]],
            });
}

$('#order_type').on('change',function(){
    var order_type = $(this).val();
    $('#history').DataTable().destroy();
    fetch_data(order_type);
});

</script>
@endsection


