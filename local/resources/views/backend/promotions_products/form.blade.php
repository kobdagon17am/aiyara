@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')


@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Promotions products </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.promotions_products.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.promotions_products.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">ชื่อโปรโมชั่น : </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRowNew->name_thai }}" readonly >
                        <input type="hidden" name="promotion_id_fk" value="{{@$sRowNew->id}}" >
                    </div>
                </div>

               <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">ชื่อสินค้า : * </label>
                  <div class="col-md-10">
                    <select name="product_id_fk" class="form-control select2-templating " required >
                      <option value="">Select</option>
                        @if(@$sProduct)
                          @foreach(@$sProduct AS $r)
                            <option value="{{$r->product_id_fk}}" {{ (@$r->product_id_fk==@$sRow->product_id_fk)?'selected':'' }} >{{$r->product_name}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                 <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">จำนวน : </label>
                    <div class="col-md-10">
                        <input class="form-control" type="number" name="product_amt" value="{{ @$sRow->product_amt }}" required >
                    </div>
                </div>

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">หน่วยนับ : * </label>
                  <div class="col-md-10">
                    <select name="product_unit" class="form-control select2-templating " required >
                      <option value="">Select</option>
                        @if(@$sProductUnit)
                          @foreach(@$sProductUnit AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->product_unit)?'selected':'' }} >{{$r->product_unit}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>
               

                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.promotions.index') }}/{{@$sRowNew->id}}/edit" }}">
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
@endsection

@section('script')


@endsection
