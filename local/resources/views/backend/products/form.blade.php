@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/libs/select2/select2.min.css')}}">
<style type="text/css">
    .select2-dropdown {
       font-size: 16px;
    }
</style>

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">สินค้า</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.products.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.products.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Product Code :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->product_code }}" name="product_code" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Product Name :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->product_name }}" name="product_name" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">PV :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->pv }}" name="pv" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Price :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->price }}" name="price" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">ประเภทสินค้า :</label>
                    <div class="col-md-10">
                         <select name="category_id" class="form-control select2-templating " >
                         <option value="">Select</option>
                            @if(@$dsProductType)
                                @foreach(@$dsProductType AS $r)
                                    <option value="{{$r->id}}" {{ (@$r->id==@$sRow->category_id)?'selected':'' }} >{{$r->product_type}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">รูปสินค้า (1) :</label>
                    <div class="col-md-6">
                        <input type="file" accept="image/*" id="image01" name="image01" class="form-control" OnChange="showPreview_01(this)" >
                        @IF(!empty($sRow->image01))
                        <img id="imgAvatar_01" src="{{ $sRow->img_url }}{{ $sRow->image01 }}" width="200px" > 
                        @ELSE
                        <img id="imgAvatar_01" src="{{ asset('local/public/images/example_img.png') }}" class="imgProfileNisit" width="200px"> 
                        @ENDIF
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="custom-control custom-radio custom-control-inline ">
                            <input type="radio" id="image_default_01" name="image_default" class="custom-control-input" value="1" 
                            {{ (@$sRow->image_default==1)?'checked':'' }} 
                            {{ empty(@$sRow->image_default)?'checked':'' }}
                             >
                            <label class="custom-control-label" for="image_default_01"> กำหนดให้รูปนี้เป็นรูปหลัก </label>
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">รูปสินค้า (2) :</label>
                    <div class="col-md-6">
                        <input type="file" accept="image/*" id="image02" name="image02" class="form-control" OnChange="showPreview_02(this)" >
                        @IF(!empty($sRow->image02))
                        <img id="imgAvatar_02" src="{{ $sRow->img_url }}{{ $sRow->image02 }}" width="200px" > 
                        @ELSE
                        <img id="imgAvatar_02" src="{{ asset('local/public/images/example_img.png') }}" class="imgProfileNisit" width="200px"> 
                        @ENDIF
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="custom-control custom-radio custom-control-inline ">
                            <input type="radio" id="image_default_02" name="image_default" class="custom-control-input" value="2" {{ (@$sRow->image_default==2)?'checked':'' }}>
                            <label class="custom-control-label" for="image_default_02"> กำหนดให้รูปนี้เป็นรูปหลัก </label>
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">รูปสินค้า (3) :</label>
                    <div class="col-md-6">
                        <input type="file" accept="image/*" id="image03" name="image03" class="form-control" OnChange="showPreview_03(this)" >
                        @IF(!empty($sRow->image03))
                        <img id="imgAvatar_03" src="{{ $sRow->img_url }}{{ $sRow->image03 }}" width="200px" > 
                        @ELSE
                        <img id="imgAvatar_03" src="{{ asset('local/public/images/example_img.png') }}" class="imgProfileNisit" width="200px"> 
                        @ENDIF
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="custom-control custom-radio custom-control-inline ">
                            <input type="radio" id="image_default_03" name="image_default" class="custom-control-input" value="3" {{ (@$sRow->image_default==3)?'checked':'' }} >
                            <label class="custom-control-label" for="image_default_03"> กำหนดให้รูปนี้เป็นรูปหลัก </label>
                        </div>
                    </div>
                </div>


                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/products") }}">
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
                        $('#image01').attr('src', ele.value); // for IE
                        if (ele.files && ele.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $('#imgAvatar_01').show();
                                $('#imgAvatar_01').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(ele.files[0]);
                    }
                }


                function showPreview_02(ele)
                    {
                        $('#image02').attr('src', ele.value); // for IE
                        if (ele.files && ele.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $('#imgAvatar_02').show();
                                $('#imgAvatar_02').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(ele.files[0]);
                    }
                }


                function showPreview_03(ele)
                    {
                        $('#image03').attr('src', ele.value); // for IE
                        if (ele.files && ele.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $('#imgAvatar_03').show();
                                $('#imgAvatar_03').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(ele.files[0]);
                    }
                }


        </script>

    <script src="{{ URL::asset('backend/libs/select2/select2.min.js')}}"></script>
    <script>
      $('.select2-templating').select2();
    </script>  

@endsection

@endsection
