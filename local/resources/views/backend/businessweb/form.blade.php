@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> เว็บแนะนำธุรกิจ </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.businessweb.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="form-group row">
                     {{ csrf_field() }}

                    <label for="example-text-input" class="col-md-2 col-form-label">หัวข้อ :</label>
                    <div class="col-md-6">
                        
                    <div class="form-group row">
                        <div class="col-md-12">
                            <input class="form-control" type="text"  name="topic" required>
                        </div>
                    </div>

                    <input type="file" accept="image/*" id="image" name="image" class="form-control" OnChange="showPreview_01(this)" required >
                    <img id="imgAvatar_01" src="{{ asset('local/public/images/example_img.png') }}" class="imgProfileNisit" width="250px">
                    </div>

                </div>

                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">รายละเอียด :</label>
                        <div class="col-md-10">
                            <textarea class="form-control" id="content" name="content" rows="5" required ></textarea>
                        </div>
                    </div>

              @else
              <form action="{{ route('backend.businessweb.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                 {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">

                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">หัวข้อ :</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" value="{{ @$sRow->topic }}" name="topic" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">รูปแสดงด้านหน้า :</label>
                        <div class="col-md-6">
                            
                            <input type="file" accept="image/*" id="image" name="image" class="form-control" OnChange="showPreview_01(this)" >
                            @IF(!empty($sRow->image))
                            <img id="imgAvatar_01" src="{{ $sRow->image_path }}{{ $sRow->image }}" width="400px" >
                            @ELSE
                            <img id="imgAvatar_01" src="{{ asset('local/public/images/example_img.png') }}" class="imgProfileNisit" width="300px">
                            @ENDIF
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">รายละเอียด :</label>
                        <div class="col-md-10">
                            <textarea class="form-control" id="content" name="content" rows="5" required >{{ @$sRow->content }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">แบนเนอร์ สไลด์ :</label>
                        <div class="col-md-2">
                             <a class="btn btn-info btn-sm waves-effect" href="{{ url("backend/businessweb_banner/index") }}/{{@$sRow->id}}"><i class="fa fa-search" aria-hidden="true"></i> Banner Slide List
                        </a>
                        </div>
                        <div class="col-md-3" > จำนวนรูปภาพแบนเนอร์ = <span style="color: blue;font-weight: bold;font-size: 16px;">{{count($dsBusinessweb_banner)}}</span>
                        </div>
                    </div>

              @endif

                <div class="form-group row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/businessweb") }}">
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
