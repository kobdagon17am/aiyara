@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> PM  </h4>
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
      // $role_group_id = \Auth::user()->role_group_id_fk;
      // echo $role_group_id;
      // echo $menu_id;
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      // $sU = @$menu_permit->u==1?'':'display:none;';
      // $sD = @$menu_permit->d==1?'':'display:none;';
      $sA = @$menu_permit->can_answer==1?'':'display:none;';

      // echo $sA;
    }

      //   echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;  

   ?>
<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.pm.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.pm.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                      <div class="myBorder">

                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label"> หมวด/แผนก : * </label>
                            <div class="col-md-8">
                              <select name="role_group_id_fk" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                  @if(@$sMainGroup)
                                    @foreach(@$sMainGroup AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->role_group_id_fk)?'selected':'' }} >{{$r->role_name}}</option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>


                          <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label"> ชื่อลูกค้า : * </label>
                            <div class="col-md-8">
                              <select name="customers_id_fk" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                  @if(@$Customer)
                                    @foreach(@$Customer AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->customers_id_fk)?'selected':'' }} >
                                        {{$r->prefix_name}}{{$r->first_name}} 
                                        {{$r->last_name}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">วันที่รับเรื่อง :</label>
                                <div class="col-md-3">
                                    <input class="form-control" type="date" value="{{ @$sRow->receipt_date }}" name="receipt_date" required >
                                </div>
                            </div>

                              <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">หัวข้อที่ลูกค้าสอบถาม :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ @$sRow->topics_question }}" name="topics_question" required >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">รายละเอียดคำถาม :</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" rows="5" name="details_question" required >{{@$sRow->details_question}}</textarea>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">คำตอบ :</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" rows="5" name="txt_answers" required style="color: blue;font-size: 16px;">{{@$sRow->txt_answers}}</textarea>
                                </div>
                            </div>

                             <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label"> Class : * </label>
                                <div class="col-md-8">
                                  <select name="level_class" class="form-control select2-templating " required >
                                    <option value="">Select</option>
                                          <option value="1" {{ (@$sRow->level_class==1)?'selected':'' }} >1</option>
                                          <option value="2" {{ (@$sRow->level_class==2)?'selected':'' }} >2</option>
                                          <option value="3" {{ (@$sRow->level_class==3)?'selected':'' }} >3</option>
                                          <option value="4" {{ (@$sRow->level_class==4)?'selected':'' }} >4</option>
                                          <option value="5" {{ (@$sRow->level_class==5)?'selected':'' }} >5</option>
                                          <option value="6" {{ (@$sRow->level_class==6)?'selected':'' }} >6</option>
                                          <option value="7" {{ (@$sRow->level_class==7)?'selected':'' }} >7</option>
                                          <option value="8" {{ (@$sRow->level_class==8)?'selected':'' }} >8</option>
                                          <option value="9" {{ (@$sRow->level_class==9)?'selected':'' }} >9</option>
                                  </select>
                                </div>
                              </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">ผู้รับเรื่อง (User Login) :</label>
                                <div class="col-md-8">

                                	@if( empty($sRow) )
                                		<input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                    	<input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="subject_recipient" >
                    									@else
                    										<input class="form-control" type="text" value="{{$subject_recipient_name}}" readonly style="background-color: #f2f2f2;" >
                                    	<input class="form-control" type="hidden" value="{{ @$sRow->subject_recipient }}" name="subject_recipient" >
									                 @endif
                                    
                                </div>
                            </div>


                          <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">ผู้ดำเนินการ(User Login):</label>
                                <div class="col-md-8">

                                  @if( empty($sRow) )
                                    <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                      <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="operator" >
                                      @else
                                        <input class="form-control" type="text" value="{{@$operator_name}}" readonly style="background-color: #f2f2f2;" >
                                      <input class="form-control" type="hidden" value="{{ @$sRow->operator }}" name="operator" >
                                   @endif
                                    
                                </div>
                            </div>


                  <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label">วันที่อัพเดตล่าสุด :</label>
                    <div class="col-md-3">
                      <input class="form-control" type="date" value="{{ @$sRow->last_update }}" name="last_update" >
                    </div>
                  </div>


                <div class="form-group row">
                    <label class="col-md-3 col-form-label">สถานะการปิดการรับเรื่อง :</label>
                    <div class="col-md-8 mt-2">
                      <div class="custom-control custom-switch">
                        @if( empty($sRow) )
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status_close_job" value="1"  >
                        @else
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status_close_job" value="1" {{ ( @$sRow->status_close_job=='1')?'checked':'' }}>
                        @endif
                          <label class="custom-control-label" for="customSwitch">ปิดการรับเรื่อง</label>
                      </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">สถานะ :</label>
                    <div class="col-md-8 mt-2">
                      <div class="custom-control custom-switch">
                        @if( empty($sRow) )
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" checked >
                        @else
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" {{ ( @$sRow->status=='1')?'checked':'' }}>
                        @endif
                          <label class="custom-control-label" for="customSwitch">เปิดใช้งาน/แสดง</label>
                      </div>
                    </div>
                </div>

                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/pm") }}">
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
@section('script')

@endsection

@endsection
