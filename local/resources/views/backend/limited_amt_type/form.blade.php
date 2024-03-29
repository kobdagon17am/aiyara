@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ประเภทจำนวนจำกัด </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.limited_amt_type.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.limited_amt_type.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">รายการ :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->txt_desc }}" name="txt_desc" required>
                    </div>
                </div>

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">เลือกภาษา :</label>
                  <div class="col-md-10">
                    <select name="lang_id" class="form-control select2-templating " >
                      <option value="0">Select</option>
                      @if(@$sLang)
                        @foreach(@$sLang AS $r)
                          <option value="{{$r->id}}" {{ (@$r->id==@$sRow->lang_id)?'selected':'' }} >{{$r->txt_desc}}</option>
                        @endforeach
                      @endif
                    </select>
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
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/limited_amt_type") }}">
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

        <script type="text/javascript">


                function showPreview_01(ele)
                    {
                        $('#image').attr('src', ele.value); // for IE
                        if (ele.files && ele.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $('#imgAvatar_01').show();
                                $('#imgAvatar_01').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(ele.files[0]);
                    }
                }


        </script>

@endsection

@endsection
