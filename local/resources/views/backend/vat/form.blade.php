@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Vat  </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.vat.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.vat.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                      <div class="myBorder">


                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label">Business Location :</label>
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
                                <label for="" class="col-md-3 col-form-label">Vat (%) :</label>
                                <div class="col-md-9">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$sRow->vat }}" name="vat"  >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label">Tax (%) :</label>
                                <div class="col-md-9">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$sRow->tax }}" name="tax"  >
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="juristic_person" class="col-md-3 col-form-label">นิติบุคคล (%) :</label>
                                <div class="col-md-9">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$sRow->juristic_person }}" name="juristic_person"  >
                                </div>
                            </div>

                             <div class="form-group row">
                                <label for="aistockis_bonus" class="col-md-3 col-form-label">Ai-Stockis Bonus (%) :</label>
                                <div class="col-md-9">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$sRow->aistockis_bonus }}" name="aistockis_bonus"  >
                                </div>
                            </div>

                             <div class="form-group row">
                                <label for="agency_bonus" class="col-md-3 col-form-label">Agency Bonus (%) :</label>
                                <div class="col-md-9">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$sRow->agency_bonus }}" name="agency_bonus"  >
                                </div>
                            </div>

                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/vat") }}">
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
