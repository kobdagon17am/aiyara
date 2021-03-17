@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style type="text/css">
	input[type=text] {color:#006622;font-weight: bold;}
</style>
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ค่าขนส่ง  </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">



                  <div class="myBorder">
  
                      <form action="{{ route('backend.shipping_cost.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        {{ csrf_field() }}


		<input type="hidden" name="business_location_id_fk" value="{{@$id}}" >


                         <div class="form-group row">
                                <label for="" class="col-md-2 col-form-label">Business Location :</label>
                                <div class="col-md-10">
                                      <select  class="form-control select2-templating " disabled="" >
                                        <option value="">-Business Location-</option>
                                        @if(@$sBusiness_location)
                                        @foreach(@$sBusiness_location AS $r)
                                        <option value="{{$r->id}}" {{ (@$r->id==@$id)?'selected':'' }} >{{$r->txt_desc}}</option>
                                        @endforeach
                                        @endif
                                      </select>
                                </div>
                            </div>
                
                <hr>


			@if( !empty($sRow) )

			
			<input type="hidden" name="save_update" value="save_update" >


			@if( !empty($shipping_cost) )

              @foreach(@$shipping_cost AS $ship_cost)

	     	 	@IF($ship_cost->shipping_type_id==1)
						
                             <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">กรณีส่งฟรี / In case of free delivery  :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ @$sRow->shipping_name }}" name="shipping_name_1" required="" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">ยอดซื้อมากกว่าหรือเท่ากับยอดนี้ ไม่มีค่าจัดส่ง / <br> No shipping charges  :</label>
                                <div class="col-md-8">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$ship_cost->purchase_amt }}" name="purchase_amt_1" required="" >
                                </div>
                            </div>
                @endif


		        @IF($ship_cost->shipping_type_id==2)
  				
  				<hr>

                             <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">เขตปริฒณฑล / Metropolitan area  :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ @$ship_cost->shipping_name }}" name="shipping_name_2" required="" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">ค่าขนส่ง / Shipping cost :</label>
                                <div class="col-md-8">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$ship_cost->shipping_cost }}" name="purchase_amt_2" required="" >
                                </div>
                            </div>

			  @endif


		        @IF($ship_cost->shipping_type_id==3)
  				
  				<hr>

                             <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">นอกเขตปริฒณฑล/ต่างจังหวัด / <br> Outside the provincial :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ @$ship_cost->shipping_name }}" name="shipping_name_3" required="" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">ค่าขนส่ง / Shipping cost :</label>
                                <div class="col-md-8">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$ship_cost->shipping_cost }}" name="purchase_amt_3" required="" >
                                </div>
                            </div>

			  @endif

		        @IF($ship_cost->shipping_type_id==4)

  				<hr>

                             <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">ส่งแบบพิเศษ / Special delivery  :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ @$ship_cost->shipping_name }}" name="shipping_name_4" required="" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">ค่าขนส่ง / Shipping cost :</label>
                                <div class="col-md-8">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$ship_cost->shipping_cost }}" name="purchase_amt_4" required="" >
                                </div>
                            </div>

               <div class="form-group row">
                    <label class="col-md-4 col-form-label">สถานะ (กรณีส่งแบบพิเศษ) :</label>
                    <div class="col-md-8 mt-2">
                      <div class="custom-control custom-switch">
                      	@if( empty($ship_cost->status_special) )
                      		<input type="checkbox" class="custom-control-input" id="customSwitch" name="status_special" value="1" >
                      	@else
                      		<input type="checkbox" class="custom-control-input" id="customSwitch" name="status_special" value="1" {{ ( @$ship_cost->status_special=='1')?'checked':'' }}>
						@endif
                          <label class="custom-control-label" for="customSwitch">เปิดใช้งาน</label>
                      </div>
                    </div>
                </div>

			  @endif



	      @endforeach 
	  @endif

    @else 

    			<input type="hidden" name="save_new" value="save_new" >

						
                             <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">กรณีส่งฟรี / In case of free delivery  :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ @$sRow->shipping_name }}" name="shipping_name_1" required="" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">ยอดซื้อมากกว่าหรือเท่ากับยอดนี้ ไม่มีค่าจัดส่ง / <br> No shipping charges  :</label>
                                <div class="col-md-8">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$ship_cost->purchase_amt }}" name="purchase_amt_1" required="" >
                                </div>
                            </div>
  				<hr>

                             <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">เขตปริฒณฑล / Metropolitan area  :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ @$ship_cost->shipping_name }}" name="shipping_name_2" required="" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">ค่าขนส่ง / Shipping cost :</label>
                                <div class="col-md-8">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$ship_cost->shipping_cost }}" name="purchase_amt_2" required="" >
                                </div>
                            </div>

  				<hr>

                             <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">นอกเขตปริฒณฑล/ต่างจังหวัด / <br> Outside the provincial :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ @$ship_cost->shipping_name }}" name="shipping_name_3" required="" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">ค่าขนส่ง / Shipping cost :</label>
                                <div class="col-md-8">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$ship_cost->shipping_cost }}" name="purchase_amt_3" required="" >
                                </div>
                            </div>

  				<hr>

                             <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">ส่งแบบพิเศษ / Special delivery  :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ @$ship_cost->shipping_name }}" name="shipping_name_4" required="" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-4 col-form-label">ค่าขนส่ง / Shipping cost :</label>
                                <div class="col-md-8">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$ship_cost->shipping_cost }}" name="purchase_amt_4" required="" >
                                </div>
                            </div>


    @endif



                              


                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                    <!--     <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/shipping_cost") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a> -->
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </div>

              </form>

      </div>           



   @if( !empty($shipping_cost) )

     @foreach(@$shipping_cost AS $ship_cost)


        @IF($ship_cost->shipping_type_id==2)


            <div class="myBorder">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.shipping_vicinity.create') }}/{{@$id}}" style="float: right;" >
                                            <i class="bx bx-plus  mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                                        </a>
                                        <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i>
                                         จังหวัดในกลุ่ม กรุงเทพฯ และปริมณฑล (Provinces in the Bangkok and suburbs ) </span>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                    @if( !empty(@$shipping_vicinity[0]->shipping_cost_id_fk) )
                                    <table id="data-table-bangkok_vicinity" class="table table-bordered dt-responsive" style="width: 100%" ></table>
                                    @endif
                                    </div>
                               </div>

                                <div class="row">
                                    <div class="col-12">
                                    	 <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/shipping_cost") }}">
					                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
					                        </a>
                                    </div>
                               </div>



                        </div>
                    </div>
                    </div> <!-- end col -->
                    </div> <!-- end row -->
                </div>
        @endif
  @endforeach            
 @endif

<?php //dd(@$shipping_vicinity[0]->shipping_cost_id_fk); ?>

        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->
@section('script')


<script>
var business_location_id_fk = "{{@$sRow->id?@$sRow->id:0}}"; //alert(business_location_id_fk);
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
var oTable;
$(function() {
    oTable = $('#data-table-bangkok_vicinity').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
            url: '{{ route('backend.shipping_vicinity.datatable') }}',
            data :{
                  business_location_id_fk:business_location_id_fk,
                },
              method: 'POST',
            },
        columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            // {data: 'province_id_fk', title :'<center>รหัสจังหวัด </center>', className: 'text-center'},
            {data: 'province_name', title :'<center>ชื่อจังหวัด </center>', className: 'text-center'},
            // {data: 'province_name_en', title :'<center>ค่าขนส่ง (บาท) </center>', className: 'text-center'},
            {data: 'id', title :'Tools', className: 'text-center w80'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){
          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

              $('td:last-child', nRow).html(''
             
                + '<a href="javascript: void(0);" data-url="{{ route('backend.shipping_vicinity.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
              ).addClass('input');
//    + '<a href="{{ route('backend.shipping_vicinity.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
          }
        }
    });

});
</script>
@endsection

@endsection

