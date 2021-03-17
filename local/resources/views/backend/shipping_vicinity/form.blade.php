@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> กำหนดจังหวัดในเขตปริมณฑล / Set the province in the metropolitan area  </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">

              @if( empty($sRow) )
              <form action="{{ route('backend.shipping_vicinity.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.shipping_vicinity.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                      <div class="myBorder">

                        <input name="business_location_id" type="hidden" value="{{@$id}}">
                        <input name="shipping_cost_id_fk" type="hidden" value="{{@$shipping_cost_id_fk}}">



                         <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">Business Location :</label>
                                <div class="col-md-9">
                                      <select name="business_location_id_fk" class="form-control select2-templating " disabled="" >
                                        <option value="">-Business Location-</option>
                                        @if(@$sBusiness_location)
                                        @foreach(@$sBusiness_location AS $r)
                                        <option value="{{$r->id}}" {{ (@$r->id==@$id)?'selected':'' }} >{{$r->txt_desc}}</option>
                                        @endforeach
                                        @endif
                                      </select>
                                </div>
                            </div>



                        <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label"> จังหวัด : * </label>
                            <div class="col-md-9">
                              <select name="province_id_fk" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                  @if(@$Province)
                                    @foreach(@$Province AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->province_id_fk)?'selected':'' }} >
                                        {{$r->name_th}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>


                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.shipping_cost.index') }}/{{@$id}}/edit" }}">
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
