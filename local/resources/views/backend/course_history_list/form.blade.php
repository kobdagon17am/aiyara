@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">Course / Event</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.course_event.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.course_event.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">เลือกประเภท :</label>
                    <div class="col-md-10">
                         <select name="ce_type" class="form-control select2-templating " >
                         <option value="">Select</option>
                            @if(@$dsCe_type)
                                @foreach(@$dsCe_type AS $r)
                                    <option value="{{@$r->id}}" {{ (@$r->id==@$sRow->ce_type)?'selected':'' }} >{{@$r->txt_desc}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">ชื่อกิจกรรม :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->ce_name }}" name="ce_name" required>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">สถานที่จัดงาน :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{ @$sRow->ce_place }}" name="ce_place" required>
                    </div>
                </div>           


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">จำนวนบัตรสูงสุด :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="number" value="{{ @$sRow->ce_max_ticket }}" name="ce_max_ticket" required>
                    </div>
                </div>   

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">ราคาบัตร (หน่วย: บาทไทย) :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="number" value="{{ @$sRow->ce_ticket_price }}" name="ce_ticket_price" required>
                    </div>
                </div> 


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">วันเริ่มจำหน่าย (mm/dd/yyyy) :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="date" value="{{ @$sRow->ce_sdate }}" name="ce_sdate" required >
                    </div>
                </div> 

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">วันสิ้นสุดการจำหน่าย (mm/dd/yyyy) :</label>
                    <div class="col-md-10">
                        <input class="form-control" type="date" value="{{ @$sRow->ce_edate }}" name="ce_edate" required >
                    </div>
                </div> 

  <!--               <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">คุณสมบัติของผู้จอง :</label>
                    <div class="col-md-10">
                         <select name="ce_features_booker" class="form-control select2-templating " >
                         <option value="">Select</option>
                            @if(@$dsCe_features_booker)
                                @foreach(@$dsCe_features_booker AS $r)
                                    <option value="{{@$r->id}}" {{ (@$r->id==@$sRow->ce_features_booker)?'selected':'' }} >{{@$r->txt_desc}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div> -->
                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">สมาชิก 1 คน  สามารถจองได้ (จำนวนบัตร) :</label>
                    <div class="col-md-10">
                         <select name="ce_can_reserve" class="form-control select2-templating " >
                         <option value="">Select</option>
                            @if(@$dsCe_can_reserve)
                                @foreach(@$dsCe_can_reserve AS $r)
                                    <option value="{{@$r->id}}" {{ (@$r->id==@$sRow->ce_can_reserve)?'selected':'' }} >{{@$r->txt_desc}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">การจำกัดจำนวน :</label>
                    <div class="col-md-10">
                         <select name="ce_limit" class="form-control select2-templating " >
                         <option value="">Select</option>
                            @if(@$dsCe_limit)
                                @foreach(@$dsCe_limit AS $r)
                                    <option value="{{@$r->id}}" {{ (@$r->id==@$sRow->ce_limit)?'selected':'' }} >{{@$r->txt_desc}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>


                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/course_event") }}">
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
