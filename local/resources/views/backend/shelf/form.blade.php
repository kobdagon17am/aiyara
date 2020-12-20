@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> 
                {{ @$sBranchs->b_name?' สาขา > '.@$sBranchs->b_name:'' }} 
                {{ @$sWarehouse->w_name?' > '.@$sWarehouse->w_name:'' }} 
                {{ @$sZone->z_name?' > '.@$sZone->z_name:'' }} 
                {{ @$sRow->s_name?' > '.@$sRow->s_name:'' }}
            </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.shelf.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="zone_id_fk" value="{{@$sZone->id}}" >
              @else
              <form action="{{ route('backend.shelf.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input type="hidden" name="zone_id_fk" value="{{@$sZone->id}}" >
              @endif
                {{ csrf_field() }}

                <div class="form-group row">
                    <label for="s_code" class="col-md-2 col-form-label">รหัส Shelf :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->s_code }}" name="s_code" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="s_name" class="col-md-2 col-form-label">ชื่อ Shelf :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->s_name }}" name="s_name" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="s_details" class="col-md-2 col-form-label">รายละเอียด :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->s_details }}" name="s_details" required>
                    </div>
                </div>

                 <div class="form-group row">
                      <label for="s_maker" class="col-md-2 col-form-label"> ผู้ทำรายการ : </label>
                      <div class="col-md-10">

                        @if( empty(@$sRow) )
                          <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="s_maker" >
                            @else
                              <input class="form-control" type="text" value="{{@$sMaker_name[0]->name}}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ @$sRow->s_maker }}" name="s_maker" >
                         @endif
                          
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
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.zone.index') }}/{{@$sZone->id}}/edit">
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
