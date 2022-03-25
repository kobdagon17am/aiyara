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

                      <div class="myBorder">

                        <div class="form-group row">
                          <label for="example-text-input" class="col-md-3 col-form-label" style="color:rgb(70, 157, 214);">customer  :<br>{{@$sRow->created_at}}</label>
                          <div class="col-md-8">
                            <input class="form-control" readonly type="text" value="{{@$sRow->topics_question}}" name="details_question" >
                          </div>
                      </div>

                        <div class="form-group row">
                          <label for="example-text-input" class="col-md-3 col-form-label" style="color:rgb(70, 157, 214);">customer  :<br>{{@$sRow->created_at}}</label>
                          <div class="col-md-8">
                            <input class="form-control" readonly type="text" value="{{@$sRow->details_question}}" name="details_question" >
                          </div>
                      </div>

                        @foreach($ans_more as $ans)

                        <div class="form-group row">
                          @if(@$ans->type == 'customer')
                          <label for="example-text-input" class="col-md-3 col-form-label" style="color:rgb(70, 157, 214);">{{@$ans->type}} :<br>{{@$ans->created_at}}</label>
                          @else
                          <label for="example-text-input" class="col-md-3 col-form-label" style="color:rgb(92, 187, 116);">{{@$ans->type}} :<br>{{@$ans->created_at}}</label>
                          @endif
                          <div class="col-md-8">
                            <input class="form-control" readonly type="text" value="{{@$ans->txt_answers}}" name="details_question" >
                          </div>
                      </div>

                      @endforeach
                          <hr>
                          
                          <form action="{{url('backend/pm_anser_save')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            {{ csrf_field() }}

                      <div class="form-group row">
                        <label for="example-text-input" class="col-md-3 col-form-label">คำตอบ :</label>
                        <div class="col-md-8">
                          <input type="hidden" value="{{@$sRow->id}}" name="pm_id">
                          <input type="hidden" value="{{@$sRow->customers_id_fk}}" name="customers_id_fk">
                            <textarea class="form-control" rows="5" name="txt_answers" required style="color: blue;font-size: 16px;"></textarea>
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
