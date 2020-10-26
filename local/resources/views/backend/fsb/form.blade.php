@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">โบนัสค่าแนะนำ</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.fsb.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.fsb.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Package :</label>
                    <div class="col-md-10">

                        <input class="form-control" type="text" value="{{ @$dsPackage->dt_package }}"  readonly style="border: 0px solid white;"  >

                    </div>
                </div>


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">G1 :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->g1 }}" name="g1" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">G2 :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->g2 }}" name="g2" required>
                    </div>
                </div><div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">G3 :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->g3 }}" name="g3" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">G4 :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->g4 }}" name="g4" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">G5 :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->g5 }}" name="g5" required>
                    </div>
                </div>


                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/fsb") }}">
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
