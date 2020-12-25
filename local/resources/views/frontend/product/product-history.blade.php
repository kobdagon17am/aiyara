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
						<select class="form-control" id="order_type" >
							<option value="">ทั้งหมด</option>
							@foreach($data as $value)
							<option value="{{ $value->group_id }}">{{ $value->orders_type }}</option>
							@endforeach
						</select>
					</div>
				

				</div>
			</div>
			<div class="card-block">
				<div class="table-responsive dt-responsive">

					<table id="history" class="table table-striped table-bordered nowrap">
						<thead>
							<tr>
								<th>#</th>
								<th>วันที่สั่งซื้อ</th>
								<th>เลขใบสั่งซื้อ</th>
								<th>TRACKING</th>
								<th>ยอดชำระ</th>
								<th>PV</th>
								<th>คงเหลือ</th>
								<th>Active</th>
								<th>จุดประสงค์การสั่งซื้อ</th>
								<th>สถานะ</th>
								<th>#</th>
							</tr>
						</thead>

					</table>
				</div>


				<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog" >
					<div class="modal-dialog modal-md" role="document">
						<form action="{{ route('upload_slip') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Upload File Slip</h4>
								</div>

								<div class="modal-body">
									<div class="form-group row">
										<div class="col-sm-12">
											<div class="form-group row">
												<div class="col-sm-10">
													<label>อัพโหลดหลักฐานการชำระเงิน <b class="text-danger">( JPG,PNG )</b> </label>
													<input type="file" name="file_slip" id="file_slip" class="form-control" required="">
													<input type="hidden" name="order_id" id="order_id" value="">
												</div>
											</div>
										</div>

									</div>
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>

									<button class="btn btn-success" type="submit" name="submit" id="submit_upload" value="upload">อัพโหลดหลักฐานการชำระเงิน</button>

								</div>

							</div>
						</form>
					</div>
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


	function fetch_data(order_type = '') {
		
		$('#history').DataTable({
				// scrollX: true,
				// scrollCollapsed: true,
				processing: true,
				serverSide: true,
				searching: true,
				ajax: {
					url: "{{ route('dt_history') }}",
					dataType: "json",
					type: "POST",
					data: {_token:'{{ csrf_token() }}',order_type:order_type}
				},

				columns:[
				{"data": "id"},
				{"data": "date"},
				{"data": "code_order"},
				{"data": "tracking"},
				{"data": "price"},
				{"data": "pv_total"},
				{"data": "banlance"},
				{"data": "date_active"},
				{"data": "type"},
				{"data": "status"},
				{"data": "action"}
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

	$('#order_type').on('change',function(){
		var order_type = $(this).val();
		$('#history').DataTable().destroy();
		fetch_data(order_type);
	});

	function upload_slip(order_id){
		$('#order_id').val(order_id);
	}

	$('#file_slip').change( function () {
		var fileExtension = ['jpg','png'];
		if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
			alert("This is not an allowed file type. Only JPG and PNG files are allowed.");
			this.value = '';
			return false;
		} 
	});
</script>
@endsection


