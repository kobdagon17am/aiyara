@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-6">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Role  </h4>
            <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/role") }}">
              <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
            </a>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.role.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.role.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                     <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">ชื่อกลุ่มสิทธิ์ :</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" value="{{ @$sRow->role_name }}" name="role_name" required >
                        </div>
                    </div>


              <!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="card">
                            <div class="card-header">
                              <h5>กำหนดสิทธิ์การเข้าถึงเมนู</h5>
                              <div class="card-header-right"><i class="icofont icofont-spinner-alt-5"></i></div>
                            </div>
                            <div class="card-block">
                              <section class="task-panel tasks-widget">

                                <div class="panel-body" style="margin-left: 5%;">
                                 
                                    <input type="hidden" name="id_user" value="{{$id}}">
                                    <div class="task-content">
                                      @foreach($sMenu_All AS $row)
                                      @php
                                      //echo $id;echo ":";
                                      //echo $row->id;echo ":";
                                      $menu_admin = DB::table('role_permit')->where('role_group_id_fk',$id)->where('menu_id_fk',$row->id)->first();
                                      @endphp

                                      <div class="to-do-label">
                                        <div class="checkbox-fade fade-in-primary">

                                        <div class="row">

                                        	<div class="col-md-5">

                                          <label class="check-task" onclick="checkedMenu({{$row->id}})">

                                            <input type="hidden" name="id_menuAd[]" value="{{@$menu_admin->id}}">

                                            <input type="hidden" name="menu_id_fk[]" value="{{@$menu_admin->menu_id_fk}}">
                                            
                                              @IF($row->ref==0)
                                                <input type="checkbox" class="check_m" {!! (@$menu_admin->menu_id_fk == $row->id ? 'checked': '') !!}
		                                            class="classMenu{{$row->id}}" name="nameMenu[]" value="{{$row->id}}" id="m{{$row->id}}" >
                                              @ELSE
                                                <input type="checkbox" class="check_s" {!! (@$menu_admin->menu_id_fk == $row->id ? 'checked': '') !!}
		                                            class="classMenu{{$row->id}}" name="nameMenu[]" value="{{$row->id}}" data-id="{{$row->ref}}" id="s{{$row->ref}}" >
                                              @ENDIF
                                           

                                            <span class="cr"><i
                                            class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                            <span
                                              class="task-title-sp">
                                              @IF($row->ref==0)
                                              <span style="font-size: 16px;font-weight: bold;color: blue;"><i class="{{$row->icon}}"></i>&nbsp;&nbsp;&nbsp;{{$row->name}}</span>
                                              @ELSE
                                              &nbsp;&nbsp;&nbsp; <i class="{{$row->icon}}"></i>
                                              <span style="font-size: 14px;font-weight: bold;color: black;">{{$row->name}}</span>
                                              @ENDIF
                                            </span>

                                           </label>

                                       </div>

                                       	<div class="col-md-6">

	                                   	    @IF($row->ref!=0)
												<label class="checkbox-inline">
													<input type="hidden" value="{{@$menu_admin->c}}">
													<input type="checkbox" name="data_c[]" {!! (@$menu_admin->c == 1 ? 'checked': '') !!} value="{{$row->id}}" id="ss{{$row->ref}}" >
													สิทธ์เพิ่ม &nbsp;&nbsp;&nbsp;
												</label>
												<label class="checkbox-inline">
													<input type="hidden" value="{{@$menu_admin->u}}">
													<input type="checkbox" name="data_u[]" {!! (@$menu_admin->u == 1 ? 'checked': '') !!} value="{{$row->id}}" id="ss{{$row->ref}}" >
													สิทธ์แก้ไข &nbsp;&nbsp;&nbsp;
												</label>
												<label class="checkbox-inline">
													<input type="hidden" value="{{@$menu_admin->d}}">
													<input type="checkbox" name="data_d[]" {!! (@$menu_admin->d == 1 ? 'checked': '') !!} value="{{$row->id}}" id="ss{{$row->ref}}" >
													สิทธ์ลบ
												</label>
										   @ELSE
										       <label class="checkbox-inline" style="display: none;">
													<input type="checkbox" name="data_c[]" checked value="{{$row->id}}"  >
												</label>
												<label class="checkbox-inline" style="display: none;">
													<input type="checkbox" name="data_u[]" checked value="{{$row->id}}"   >
												</label>
												<label class="checkbox-inline" style="display: none;">
													<input type="checkbox" name="data_d[]" checked value="{{$row->id}}"  >
												</label>
	                                       @ENDIF
                                          
                                      
		  								</div>
		  								</div>
                                          
                                        </div>
                                      </div>


                                      @endforeach
                                    </div>
                            

                                </div>

                              </section>
                            </div>
                          </div>
                        </div>
                      </div>
              <!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
                  <hr>
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
                            <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/role") }}">
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

<script type="text/javascript">
    $(document).ready(function() {

        $(".check_s").click(function(){
            var id = $(this).data('id');
            // alert(id);
              if (this.checked) {
                $("input[id=m"+id+"]").prop('checked', true);
              }
        });

        $(".check_m").click(function(){
            var id = $(this).val();
            // alert(id);
              if (this.checked) {
                $("input[id=s"+id+"]").prop('checked', true);
              }else{
              	$("input[id=s"+id+"]").prop('checked', false);
              	$("input[id=ss"+id+"]").prop('checked', false);
              }
        });

    });

</script>

@endsection

@endsection
