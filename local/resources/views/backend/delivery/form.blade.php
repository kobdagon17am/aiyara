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
            <h4 class="mb-0 font-size-18"> การจัดส่งสินค้า </h4>
        </div>
    </div>
</div>
<!-- end page title -->

  <?php 
      $sPermission = \Auth::user()->permission ;
      $menu_id = Session::get('session_menu_id');
    if($sPermission==1){
      $sC = '';
      $sU = '';
      $sD = '';
      $sA = '';
    }else{
      $role_group_id = \Auth::user()->role_group_id_fk;
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sU = @$menu_permit->u==1?'':'display:none;';
      $sD = @$menu_permit->d==1?'':'display:none;';
      $sA = @$menu_permit->can_answer==1?'':'display:none;';
    }
   ?>
   
<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty(@$sRow) )
              <form action="{{ route('backend.delivery.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.delivery.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
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
                            <label for="delivery_slip" class="col-md-2 col-form-label">ใบจัดส่ง :</label>
                            <div class="col-md-10">
                              <input class="form-control" type="text" value="{{ @$sRow->delivery_slip }}" name="delivery_slip" >
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="receipt" class="col-md-2 col-form-label">ใบเสร็จ :</label>
                            <div class="col-md-10">
                              <input class="form-control" type="text" value="{{ @$sRow->receipt }}" name="receipt" >
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="tel" class="col-md-2 col-form-label">เบอร์โทร :</label>
                            <div class="col-md-10">
                              <input class="form-control" type="text" value="{{ @$sRow->tel }}" name="tel" >
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> จังหวัด : * </label>
                            <div class="col-md-10">
                              <select name="province_id_fk" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                  @if(@$Province)
                                    @foreach(@$Province AS $r)
                                      <option value="{{$r->code}}" {{ (@$r->code==@$sRow->province_id_fk)?'selected':'' }} >
                                        {{$r->name_th}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>


                          <div class="form-group row">
                            <label for="delivery_date" class="col-md-2 col-form-label">วันที่จัดส่ง : * </label>
                            <div class="col-md-3">
                              <input class="form-control" type="date" value="{{ @$sRow->delivery_date }}" name="delivery_date" required >
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
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/delivery") }}">
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

