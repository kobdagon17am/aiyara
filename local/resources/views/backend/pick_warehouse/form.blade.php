@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> การจัดเบิกสินค้า </h4>
        </div>
    </div>
</div>
<!-- end page title -->

  <?php 
    $sPermission = \Auth::user()->permission ;
    $menu_id = @$_REQUEST['menu_id'];
    $role_group_id = @$_REQUEST['role_group_id'];
    if($sPermission==1){
      $sC = '';
      $sU = '';
      $sD = '';
      $sA = '';
    }else{
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sA = @$menu_permit->can_answer==1?'':'display:none;';
    }

      //   echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;  

   ?>
<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty(@$sRow) )
              <form action="{{ route('backend.pick_warehouse.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.pick_warehouse.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                      <div class="myBorder">

                          <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> ชื่อลูกค้า : * </label>
                            <div class="col-md-10">
                              <select name="customer_id" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                  @if(@$Customer)
                                    @foreach(@$Customer AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->customer_id)?'selected':'' }} >
                                        {{$r->id}} : {{$r->prefix_name}}{{$r->first_name}} 
                                        {{$r->last_name}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="pick_warehouse_slip" class="col-md-2 col-form-label">รหัสใบเบิก :</label>
                            <div class="col-md-10">
                              <input class="form-control" type="text" value="{{ @$sRow->pick_warehouse_slip }}" name="pick_warehouse_slip" >
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="receipt" class="col-md-2 col-form-label">เลขที่ใบเสร็จ :</label>
                            <div class="col-md-10">
                              <input class="form-control" type="text" value="{{ @$sRow->receipt }}" name="receipt" >
                            </div>
                          </div>


                          <div class="form-group row">
                            <label for="receipt" class="col-md-2 col-form-label">ตรวจสอบแล้ว :</label>
                            <div class="col-md-8 mt-2">
                            <div class="custom-control custom-switch">
                              <input type="checkbox" class="custom-control-input" id="customSwitch" name="status_slip" value="true" {{ ( @$sRow->status_slip=='true')?'checked':'' }}>
                              <label class="custom-control-label" for="customSwitch"> อนุมัติ </label>
                            </div>
                          </div>
                          </div>

                          <div class="form-group row">
                            <label for="receipt" class="col-md-2 col-form-label">ตรวจสอบแล้ว :</label>
                            <div class="col-md-8 mt-2">
                            <div class="custom-control custom-switch">
                              <input type="checkbox" class="custom-control-input" id="customSwitch" name="status_slip" value="true" {{ ( @$sRow->status_slip=='true')?'checked':'' }}>
                              <label class="custom-control-label" for="customSwitch"> Accepted </label>
                            </div>
                          </div>
                          </div>
                   

                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/pick_warehouse") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>
                  </div>
                  <div class="col-md-6 text-right">
                      
                      <input type="hidden" name="role_group_id" value="{{@$_REQUEST['role_group_id']}}" >
                      <input type="hidden" name="menu_id" value="{{@$_REQUEST['menu_id']}}" >

                    <button type="submit" class="btn btn-primary btn-sm waves-effect">
                    <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                    </button>
                  </div>
                </div>

              </form>
              </div>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')


@endsection

