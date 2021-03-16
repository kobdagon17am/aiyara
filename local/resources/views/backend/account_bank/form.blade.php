@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> บัญชีธนาคาร  </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.account_bank.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.account_bank.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                      <div class="myBorder">

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">Business Location :</label>
                                <div class="col-md-9">
                                      <select name="business_location_id_fk" class="form-control select2-templating " required >
                                        <option value="">-Business Location-</option>
                                        @if(@$sBusiness_location)
                                        @foreach(@$sBusiness_location AS $r)
                                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->business_location_id_fk)?'selected':'' }} >{{$r->txt_desc}}</option>
                                        @endforeach
                                        @endif
                                      </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="txt_account_name" class="col-md-3 col-form-label">ชื่อบัญชี :</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ @$sRow->txt_account_name }}" name="txt_account_name"  >
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="txt_bank_name" class="col-md-3 col-form-label">ธนาคาร :</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ @$sRow->txt_bank_name }}" name="txt_bank_name"  >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="txt_bank_number" class="col-md-3 col-form-label">เลขบัญชี :</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ @$sRow->txt_bank_number }}" name="txt_bank_number"  >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">สถานะ :</label>
                                <div class="col-md-9 mt-2">
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
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/account_bank") }}">
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
