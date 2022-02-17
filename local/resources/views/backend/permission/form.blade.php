@extends('backend.layouts.master')

@section('title') Account (ผู้ดูแลระบบ) @endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">Account / Member</h4>

            @php
            $path = explode("/",Request::path());
            $path = $path[0]."/".$path[1]."/".$path[2];
            @endphp
            
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-7">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.admin.store') }}" method="POST" autocomplete="off">
                <?php @$placeholder=""; ?>
              @else
              <form action="{{ route('backend.admin.update', $sRow->id ) }}" method="POST" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <?php @$placeholder="หากต้องการเปลี่ยนแปลงให้ระบุ"; ?>
              @endif
                {{ csrf_field() }}


            @if(empty(\Auth::user()->locale_id))
                <div class="form-group row">
                    <label for="example-email-input" class="col-md-4 col-form-label">Language</label>
                    <div class="col-md-7">
                      <select class="form-control" name="locale_id">
                        @if($sLocale->count())
                          @foreach($sLocale ?? '' AS $r)
                          <option value="{{$r->locale}}" {{ (@$sRow->locale_id==$r->locale)?'selected':'' }}>{{$r->name}}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                </div>
            @endif

                <div class="form-group row">
                    <label for="example-email-input" class="col-md-4 col-form-label">Email</label>
                    <div class="col-md-7">
                        <input class="form-control" type="email" value="{{ $sRow->email??'' }}" name="email" {{ isset($sRow)?'readonly':'required' }}>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-password-input" class="col-md-4 col-form-label">Password</label>
                    <div class="col-md-7">
                        <input class="form-control" type="text" name="password" placeholder="{{@$placeholder}}">
                    </div>
                </div>
                <?php 

                    if(\Auth::user()->permission==1){
                        $dis = "";
                    }else{
                        $dis = "disabled";
                    }

                ?>
                <div class="form-group row">
                    <label for="example-text-input" class="col-md-4 col-form-label">ชื่อผู้ใช้ที่แสดงในระบบ</label>
                    <div class="col-md-7">
                        <input class="form-control" type="text" value="{{ $sRow->name??'' }}" name="name" <?=$dis?> required >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-4 col-form-label">เบอร์โทรศัพท์ :</label>
                    <div class="col-md-7">
                        <input class="form-control" type="text" value="{{ @$sRow->tel }}" name="tel" >
                    </div>
                </div>
               <div class="form-group row">
                    <label for="example-text-input" class="col-md-4 col-form-label">ตำแหน่ง :</label>
                    <div class="col-md-7">
                        <input class="form-control" type="text" value="{{ @$sRow->position }}" name="position" <?=$dis?> >
                    </div>
                </div>

                                <div class="form-group row">
                    <label for="example-text-input" class="col-md-4 col-form-label">แผนก :</label>
                    <div class="col-md-7">
                        <input class="form-control" type="text" value="{{ @$sRow->department }}" name="department" <?=$dis?> >
                    </div>
                </div>


                <div class="form-group row">
                   <label for="branch_id_fk" class="col-md-4 col-form-label"> สาขา : </label>
                        <div class="col-md-7">
                          <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating " <?=$dis?> >
                             <option value="0">Select</option>
                             @if(@$sBranchs)
                              @foreach(@$sBranchs AS $r)
                              <option value="{{$r->id}}" {{ (@$r->id==@$sRow->branch_id_fk)?'selected':'' }} >
                                {{$r->b_name}}
                              </option>
                              @endforeach
                              @endif
                          </select>
                        </div>
                </div>


 

               <div class="form-group row">
                  <label for="example-text-input" class="col-md-4 col-form-label"> ระดับสิทธิ์ในระบบ : * </label>
                  <div class="col-md-7">
                    <select name="permission" class="form-control select2-templating " required <?=$dis?> >
                      <option value="">Select</option>
                         <option value="1" {{ (@$sRow->permission==1)?'selected':'' }} >Super Admin</option>
                         <option value="0" {{ (@$sRow->permission==0)?'selected':'' }} >User </option>
                    </select>
                  </div>
                </div>


                <div class="form-group row">
                  <label for="example-text-input" class="col-md-4 col-form-label"> ระดับสิทธิ์งานขาย : * </label>
                  <div class="col-md-7">
                     <select id="position_level" name="position_level" class="form-control select2-templating " <?=$dis?> >
                             <option value="0">Select</option>
                             @if(@$position_level)
                              @foreach(@$position_level AS $r)
                              <option value="{{$r->id}}" {{ (@$r->id==@$sRow->position_level)?'selected':'' }} >
                                {{$r->txt_desc}}
                              </option>
                              @endforeach
                              @endif
                          </select>
                  </div>
                </div>

           

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-4 col-form-label"> กลุ่มสิทธิ์การเข้าถึงเมนู : * </label>
                  <div class="col-md-7">
                    <select name="role_group_id_fk" class="form-control select2-templating " required <?=$dis?> >
                      <option value="">Select</option>
                        @if(@$sRole_group)
                          @foreach(@$sRole_group AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->role_group_id_fk)?'selected':'' }} >{{$r->role_name}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Active</label>
                    <div class="col-md-8 mt-2">
                      <div class="custom-control custom-switch">
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="active" value="Y" {{ (isset($sRow) && $sRow->isActive=='Y')?'checked':'' }}  <?=$dis?> >
                          <label class="custom-control-label" for="customSwitch">ใช้งานปกติ</label>
                      </div>
                    </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label">แก้ไขโปรไฟล์</label>
                  <div class="col-md-8 mt-2">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="canEditProfile" name="can_edit_profile" value="1" {{ (isset($sRow) && $sRow->can_edit_profile)?'checked':'' }}  <?=$dis?> >
                        <label class="custom-control-label" for="canEditProfile"></label>
                    </div>
                  </div>
              </div>

                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ route('backend.admin.index') }}">
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


@endsection



