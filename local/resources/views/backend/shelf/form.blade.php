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
                {{ @$sWarehouse->w_name?' > '.@$sWarehouse->w_name:'' }} 
                {{ @$sSubwarehouse->w_name?' \ '.@$sSubwarehouse->w_name:'' }} 
                {{ @$sZone->w_name?' \ '.@$sZone->w_name:'' }} 
                {{ @$sRow->w_name?' \ '.@$sRow->w_name:'' }}
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
                <input type="hidden" name="w_zone_id_fk" value="{{@$sZone->id}}" >
              @else
              <form action="{{ route('backend.shelf.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input type="hidden" name="w_zone_id_fk" value="{{@$sZone->id}}" >
              @endif
                {{ csrf_field() }}

              <!--   <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">เลือก Zone :</label>
                    <div class="col-md-10">
                         <select name="w_zone" class="form-control select2-templating " >
                         <option value="">Select</option>
                            @if(@$dsZone)
                                @foreach(@$dsZone AS $r)
                                    <option value="{{@$r->id}}" {{ (@$r->id==@$sRow->w_zone_id_fk)?'selected':'' }} >{{@$r->w_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div> -->

              <!--   <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Zone :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sZone->w_name }}"readonly >
                    </div>
                </div> -->

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">ชื่อ Shelf :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->w_name }}" name="w_name" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">รหัส Shelf :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->w_code }}" name="w_code" required>
                    </div>
                </div>

               

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">วันที่สร้าง :</label>
                    <div class="col-md-3">
                        <input class="form-control" type="date" value="{{ @$sRow->w_date_created }}" name="w_date_created" required >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">รายละเอียด :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->w_details }}" name="w_details" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">ผู้ทำรายการ :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->w_maker }}" name="w_maker" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">วันที่อัพเดท :</label>
                    <div class="col-md-3">
                        <input class="form-control" type="date" value="{{ @$sRow->w_date_updated }}" name="w_date_updated" required>
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
