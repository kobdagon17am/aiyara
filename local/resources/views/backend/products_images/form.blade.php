@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-10">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Products Images </h4>

            <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.products.index') }}/{{@$sRowNew->id}}/edit" }}">
                <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
            </a>

        </div>

    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">

              @if( empty($sRow) )
              <form action="{{ route('backend.products_images.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="product_id_fk" value="{{@$sRowNew->id}}" >
              @else
              <form action="{{ route('backend.products_images.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input type="hidden" name="product_id_fk" value="{{@$sRowNew->id}}" >
                
              @endif
                {{ csrf_field() }}

                    <div class="myBorder">

                          <div class="form-group row">
                              <label for="example-text-input" class="col-md-3 col-form-label">รูปสินค้า :</label>
                              <div class="col-md-6">
                                  <input type="file" accept="image/*" id="image01" name="image01" class="form-control" OnChange="showPreview_01(this)" {{ (empty(@$sRow->product_img))?'required':'' }} >
                                  @IF(!empty(@$sRow->product_img))
                                  <img id="imgAvatar_01" src="{{ $sRow->img_url }}{{ @$sRow->product_img }}" width="300px" > 
                                  @ELSE
                                  <img id="imgAvatar_01" src="{{ asset('local/public/images/example_img.png') }}" class="imgProfileNisit" width="300px"> 
                                  @ENDIF

                              
                              </div>
                          </div>

                                   <div class="form-group row">
                                    <label class="col-md-3 col-form-label">กำหนดให้รูปนี้เป็นรูปหลัก :</label>
                                    <div class="col-md-8 mt-2">
                                      <div class="custom-control custom-switch">
                                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="image_default" value="1" {{ ( @$sRow->image_default=='1')?'checked':'' }}>
                                          <label class="custom-control-label" for="customSwitch"> เป็นรูปหลัก </label>
                                      </div>
                                    </div>
                                </div>


                      <div class="form-group mb-0 row">
                          <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.products.index') }}/{{@$sRowNew->id}}/edit" }}">
                                <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                              </a>
                          </div>
                          <div class="col-md-6 text-right">
                              <button type="submit" class="btn btn-primary btn-sm waves-effect">
                                <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                              </button>
                          </div>
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

        </script>

@endsection

@endsection
