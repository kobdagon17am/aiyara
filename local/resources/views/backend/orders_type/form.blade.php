@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Orders type (ชนิดการสั่งซื้อ) </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.orders_type.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.orders_type.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">ชนิดการสั่งซื้อ :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->orders_type }}" name="orders_type" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">รายละเอียด :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->detail }}" name="detail" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">วันที่เพิ่ม :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="date" value="{{ @$sRow->date_added }}" name="date_added" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">เลือกภาษา :</label>
                    <div class="col-md-10">
                         <select name="lang_id" class="form-control select2-templating " required >
                         <option value="">Select</option>
                             <!-- <option value="{{@$r->id}}" {{ (@$r->id==@$sRow->ce_type)?'selected':'' }} >{{@$r->txt_desc}}</option> -->
                             <option value="1" {{ (@$sRow->lang_id==1)?'selected':'' }} >ไทย</option>
                             <option value="2" {{ (@$sRow->lang_id==2)?'selected':'' }} >อังกฤษ</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">สถานะ :</label>
                    <div class="col-md-10 mt-2">
                      <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" {{ ( @$sRow->status=='1')?'checked':'' }}>
                          <label class="custom-control-label" for="customSwitch">ใช้งานปกติ</label>
                      </div>
                    </div>
                </div>

                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/orders_type") }}">
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
