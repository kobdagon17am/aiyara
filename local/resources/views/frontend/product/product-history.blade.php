      
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
			<div class="card-header">
				<h5>ประวัติการสั่งซื้อ</h5>
				{{-- <span>DataTables has most features enabled by default, so all you need to do to use it with your own ables is to call the construction function: $().DataTable();.</span> --}}
			</div>
			<div class="card-block">
				<div class="table-responsive dt-responsive">
					<table id="history" class="table table-striped table-bordered nowrap">
						<thead>
							<tr>
								<th>#</th>
								<th>เลขที่ใบเสร็จ</th>
								<th>ยอดชำระ</th>
								<th>PV</th>
								<th>จุดประสงค์การสั่งซื้อ</th>
								<th>วันที่สั่งซื้อ</th>
								<th>สถานะ</th>
								<th>#</th>
							</tr>
						</thead>
					 
					</table>
				</div>
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

		function fetch_data() {
			$('#history').DataTable({
				scrollX: true,
				scrollCollapsed: true,
				processing: true,
				serverSide: true,
				//searching: true,
				ajax: {
					url: "{{ route('dt_history') }}",
					dataType: "json",
					type: "POST",
					data: {_token:'{{ csrf_token() }}'}
				},
 
				columns:[
				{"data": "id"},
				{"data": "code_order"},
				{"data": "price"},
				{"data": "pv_total"},
				{"data": "type"},
				{"data": "date"},
				{"data": "status"},
				{"data": "action"},
				// {"data": "interesting"},
				// {"data": "course"},
				// {"data": "step"},
				// {"data": "date_create"},
				// {"data": "comment", },
				// {"data": "source"},
				// {"data": "payment_status"}
				],
				//order: [[ "0", "desc" ]],
			});
		}
	});
</script>
@endsection


