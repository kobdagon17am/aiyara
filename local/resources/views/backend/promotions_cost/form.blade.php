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
            <h4 class="mb-0 font-size-18"> ข้อมูลราคา </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.promotions_cost.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.promotions_cost.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">Business Location : * </label>
                  <div class="col-md-10">
                    <select name="business_location_id" class="form-control select2-templating " required >
                      <option value="">Select</option>
                        @if(@$sBusiness_location)
                          @foreach(@$sBusiness_location AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->business_location_id)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">ประเทศ : * </label>
                  <div class="col-md-10">
                    <select name="country_id" class="form-control select2-templating " required >
                      <option value="">Select</option>
                        @if(@$sCountry)
                          @foreach(@$sCountry AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->country_id)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                 <div class="form-group row">
                  <label for="example-text-input" class="col-md-2 col-form-label">สกุลเงิน : * </label>
                  <div class="col-md-10">
                    <select name="currency_id" class="form-control select2-templating " required >
                      <option value="">Select</option>
                        @if(@$sCurrency)
                          @foreach(@$sCurrency AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->currency_id)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">ราคาทุน : * </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->cost_price }}" name="cost_price" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">ราคาขาย : * </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->selling_price }}" name="selling_price" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">ราคาสมาชิก : * </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->member_price }}" name="member_price" required>
                    </div>
                </div>

                 <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">PV : * </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->pv }}" name="pv" required>
                    </div>
                </div>



                <div class="form-group row">
                    <label class="col-md-2 col-form-label">สถานะ :</label>
                    <div class="col-md-10 mt-2">
                      <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" {{ ( @$sRow->status=='1')?'checked':'' }}>
                          <label class="custom-control-label" for="customSwitch">การใช้งาน/การแสดงผล</label>
                      </div>
                    </div>
                </div>

                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/promotions_cost") }}">
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

