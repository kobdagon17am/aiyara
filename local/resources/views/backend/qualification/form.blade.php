@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">คุณวุฒินักธุรกิจ</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.qualification.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.qualification.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">ชื่อคุณวุฒิ :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->business_qualifications }}" name="business_qualifications" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">PV L/T :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->pv_lt }}" name="pv_lt" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">PV M/T :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->pv_mt }}" name="pv_mt" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Basic Active 1 :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->basic_active_1 }}" name="basic_active_1" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Basic Active 2 :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->basic_active_2 }}" name="basic_active_2" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">PS 1 :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->ps_1 }}" name="ps_1" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">PS 2 :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->ps_2 }}" name="ps_2" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Month :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->amt_month }}" name="amt_month" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">BDS 1 :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->bds_1 }}" name="bds_1" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">BDS 2 :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->bds_2 }}" name="bds_2" required>
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
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/qualification") }}">
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
