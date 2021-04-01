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
				<div class="row">
					<div class="col-md-4">
						<select class="form-control" id="status" >
							<option value="">ทั้งหมด</option>
							<option value="success">Success</option>
							<option value="cancel">Cancle</option>
						</select>
					</div>
					<div class="col">
						<div class="page-header-breadcrumb">
							<?php
							$gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
							?>
							<div class="bg-danger p-10"><i class="fa fa-gift"></i> {{-- Gift Voucher --}} <b>{{ number_format($gv->sum_gv) }} </b></div>
						</div>
					</div>


				</div>
			</div>
			<div class="card-block">
				<div class="table-responsive dt-responsive">

					<table id="giv_history" class="table table-striped table-bordered nowrap">
						<thead>
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Order</th>
								<th>Gift Voucher</th>
								<th>Status</th>
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

	});

	function fetch_data(status = '') {

		$('#giv_history').DataTable({
				// scrollX: true,
				// scrollCollapsed: true,
				processing: true,
				serverSide: true,
				searching: true,
				ajax: {
					url: "{{ route('dt_gift_order_history') }}",
					dataType: "json",
					type: "get",
					data: {status:status}
				},

				columns:[
				{"data": "id"},
				{"data": "date"},
				{"data": "order"},
				{"data": "gv"},
				{"data": "status"},
        {"data": "action"},
				],
				//order: [[ "0", "desc" ]],
			});
	}

	$('#status').on('change',function(){
		var status = $(this).val();
		$('#giv_history').DataTable().destroy();
		fetch_data(status);
	});


</script>
@endsection


