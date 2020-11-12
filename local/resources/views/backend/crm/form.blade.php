@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> CRM  </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.crm.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.crm.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                      <div class="myBorder">

                             <div class="form-group row">
                                <label for="example-text-input" class="col-md-2 col-form-label">เลขใบรับเรื่อง :</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="{{ @$sRow->subject_receipt_number }}" name="subject_receipt_number"  >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-2 col-form-label">วันที่-เวลา รับเรื่อง :</label>
                                <div class="col-md-3">
                                    <input class="form-control" type="datetime" value="{{ @$sRow->receipt_date }}" name="receipt_date"  >
                                </div>
                            </div>

                              <div class="form-group row">
                                <label for="example-text-input" class="col-md-2 col-form-label">หัวข้อที่ลูกค้าแจ้ง :</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="{{ @$sRow->topics_reported }}" name="topics_reported"  >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-2 col-form-label">รายละเอียดติดต่อ :</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" rows="5" name="contact_details" >{{@$sRow->contact_details}}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-2 col-form-label">ผู้รับเรื่อง (User Login) :</label>
                                <div class="col-md-10">

                                	@if( empty($sRow) )
                                		<input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                    	<input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="subject_recipient" >
                    									@else
                    										<input class="form-control" type="text" value="{{$subject_recipient_name}}" readonly style="background-color: #f2f2f2;" >
                                    	<input class="form-control" type="hidden" value="{{ @$sRow->subject_recipient }}" name="subject_recipient" >
									                 @endif
                                    
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-2 col-form-label">ผู้ดำเนินการ :</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" value="{{ @$sRow->operator }}" name="operator"  >
                                </div>
                            </div>


                  <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">วันที่-เวลา อัพเดตล่าสุด :</label>
                    <div class="col-md-3">
                      <input class="form-control" type="datetime" value="{{ @$sRow->last_update }}" name="last_update" >
                    </div>
                  </div>


                <div class="form-group row">
                    <label class="col-md-2 col-form-label">สถานะ :</label>
                    <div class="col-md-10 mt-2">
                      <div class="custom-control custom-switch">
                        @if( empty($sRow) )
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" checked >
                        @else
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" {{ ( @$sRow->status=='1')?'checked':'' }}>
                        @endif
                          <label class="custom-control-label" for="customSwitch">เปิดใช้งาน</label>
                      </div>
                    </div>
                </div>
                  
               

                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/crm") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </div>

              </form>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->
@section('script')

@endsection

@endsection
