@extends('backend.layouts.master')

@section('title') Excel Import File Sheet All @endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">Excel Import File Sheet Physician</h4>
			
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Backend</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">File</a></li>
                    <li class="breadcrumb-item active">Excel Import File Sheet Physician</li>
				</ol>
			</div>
			
		</div>
	</div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">               
				<form action="{{ url('backend/aboutfile/excel-file-sheet-physician-upload') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="upload">Import File Input Physician</label>
                                <input type="file" class="form-control-file" name="upload" id="upload" value="">
                                @if ($errors->has('upload'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('upload') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6 text-right">
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </div>

                </form>
			</div>
		</div>
	</div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('css')
<!-- bootstrap-datepicker css -->
<link href="{{ URL::asset('backend/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
@endsection

@section('script')
<!-- bootstrap-datepicker js -->
<script src="{{ URL::asset('backend/libs/bootstrap-datepicker/moment.min.js')}}"></script>    
<script src="{{ URL::asset('backend/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>    
<script src="{{ URL::asset('backend/libs/bootstrap-datepicker/bootstrap-datepicker-thai.js')}}"></script>
<script src="{{ URL::asset('backend/libs/bootstrap-datepicker/locales/bootstrap-datepicker.th.js')}}"></script>

<script>
	var oData;
	var oTable;
	$(function() {
		$(".mydateth").datepicker({			
			format:'dd-mm-yyyy',  	// กำหนดรูปแบบวันที่ ที่ใช้ เป็น 00-00-0000  				
			autoclose: true			
		});

		oTable = $('#data-table').DataTable({
			"sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
			processing: true,
			serverSide: true,
			scroller: true,
			scrollCollapse: true,
			scrollX: true,
			ordering: false,
			// scrollY: ''+($(window).height()-370)+'px',
			iDisplayLength: 25,
			ajax: {
				url: '{{ route('backend.report.recordendo.datatable') }}',
				data: function ( d ) {
					d.Where={};
					$('.myWhere').each(function() {
						if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
							d.Where[$(this).attr('name')] = $.trim($(this).val());
						}
					});
					d.Like={};
					$('.myLike').each(function() {
						if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
							d.Like[$(this).attr('name')] = $.trim($(this).val());
						}
					});
					d.Custom={};
					$('.myCustom').each(function() {
						if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
							d.Custom[$(this).attr('name')] = $.trim($(this).val());
						}
					});
					d.DateRange={};
					/* set ไว้ ต้องมีอย่างละช่องเท่านั้น ในกรณีภาษาไทย (พ.ศ.) */
					$('.myDateRangeStop').each(function(index, value) {
						if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
							rangef = $.trim($('.myDateRangeStart').eq(index).val())+" 00:00";
							rangec = " - ";
							rangel = $.trim($('.myDateRangeStop').eq(index).val())+" 00:00";
							if($.trim($('.myDateRangeStart').eq(index).val()) == ""){
								alert("กรุณากรอกวันที่เริ่ม");								
								$.trim($('.myDateRangeStop').eq(index).val(""))
								return false;
							}else{
								d.DateRange["treatment_date"] = rangef+rangec+rangel;
							}							
						}
					});
					oData = d;
					// console.log('Test : '+ JSON.stringify(oData.DateRange));
				},
				method: 'POST'
			},
			columns: [
                // {data: 'DT_RowIndex', 				title :'<center>No.</center>', 				className: 'text-center w50'},
                {data: 'reference_code', 				title :'<center>Reference Code.</center>', 	className: 'text-center w120'},
				{data: 'detail_date', 					title :'<center>Date</center>', 			className: 'text-center w80'},
				{data: 'patient_name', 					title :'<center>Patient Name</center>', 	className: 'text-left w240'},
				{data: 'hospital_name', 				title :'<center>Hospital Name</center>', 	className: 'text-left w240'},
				{data: 'physician_name', 				title :'<center>Physician Name</center>', 	className: 'text-left w240'},				
				{data: 'conclusion_equipment_charge_vat_bef', 	title :'<center>Vat</center>', 		className: 'text-right w120'},
				{data: 'conclusion_equipment_charge', 	title :'<center>Expenses</center>', 	className: 'text-right w120'},
				{data: 'conclusion_equipment_charge_vat', 	title :'<center>Expenses Vat</center>', 	className: 'text-right w120'},
			],
			rowCallback: function(nRow, aData, dataIndex){	
				$('td:eq(0)', nRow).html(''					
					+ ((aData['reference_code']) ? aData['reference_code'] : '<center>-</center>') 
					+ ''
				).addClass('input');				
			}
		});
		$('.myWhere,.myLike,.myCustom,.myDateRangeStop,#onlyTrashed').on('change', function(e){
			oTable.draw();
		});
		
		
		$('.btn-excel').on('click', function(e){
			if (confirm('ยืนยันการออกรายงาน อาจใช้เวลาสักครู่ในการสร้าง\nโปรดรอประมาณ 20-60 วืนาที ขึ้นอยู่กับจำนวนรายการที่นำมาสร้าง')) {
				$("#preloader #status").css({'display': ''});
				$("#preloader").css({'display': '','opacity': '0.5'});
				oData.excel=true;
				$.ajax({
					type: "POST",
					dataType: "json",
					url: '{{ route('backend.report.recordendo.datatable') }}',
					data: oData,
					success: function( data ) {
						$("#status").css({'display': 'none'});
						$("#preloader").css({'display': 'none'});
						if (data.redirect) {
							window.location.href = data.redirect;
						}
					}
				});
			}
		});
	});
</script>
@endsection
