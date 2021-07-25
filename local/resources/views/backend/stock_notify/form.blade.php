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
            <h4 class="mb-0 font-size-18"> EDIT NOTIFY </h4>
        </div>

    </div>
</div>
<!-- end page title -->

  <?php 
    $sPermission = \Auth::user()->permission ;
      // $menu_id = @$_REQUEST['menu_id'];
      $menu_id = Session::get('session_menu_id');
    if($sPermission==1){
      $sC = '';
      $sU = '';
      $sD = '';
      $can_sentmoney = '1';
      $can_getmoney = '1';      
    }else{
      $role_group_id = \Auth::user()->role_group_id_fk;
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sU = @$menu_permit->u==1?'':'display:none;';
      $sD = @$menu_permit->d==1?'':'display:none;';
      $can_sentmoney = @$menu_permit->can_sentmoney==1?'1':'0';
      $can_getmoney = @$menu_permit->can_getmoney==1?'1':'0';         
    }

    // echo $can_sentmoney;
    // echo $can_getmoney;
    
   ?>


<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.stock_notify.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.stock_notify.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input name="id" type="hidden" value="{{@$sRow->id}}">
              @endif
                {{ csrf_field() }}


                      <div class="myBorder">

                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label">Business Location :</label>
                                <div class="col-md-9">
                                      <select name="business_location_id_fk" class="form-control select2-templating " disabled="" >
                                        @if(@$sBusiness_location)
                                        @foreach(@$sBusiness_location AS $r)
                                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->business_location_id_fk)?'selected':'' }} >{{$r->txt_desc}}</option>
                                        @endforeach
                                        @endif
                                      </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label">สาขา :</label>
                                <div class="col-md-9">
                                      <select class="form-control select2-templating " disabled="" >
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
                                <label for="" class="col-md-3 col-form-label">คลัง :</label>
                                <div class="col-md-9">
                                   <select class="form-control select2-templating " disabled="" >
                                      @if(@$Warehouse)
                                        @foreach(@$Warehouse AS $r)
                                          <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_warehouse)?'selected':'' }} >
                                            {{$r->w_name}}
                                          </option>
                                        @endforeach
                                      @endif
                                  </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label">รหัสสินค้า : ชื่อสินค้า :</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ @$product_name }}" disabled style="background-color: #e6e6e6;" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-md-3 col-form-label">วันหมดอายุ :</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ @$sRow->lot_expired_date }}" disabled style="background-color: #e6e6e6;" >
                                </div>
                            </div>


                             <div class="form-group row">
                                <label for="amt_less" class="col-md-3 col-form-label">จำนวนคงคลังล่าสุด :</label>
                                <div class="col-md-9">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$sRow->amt }}" name="amt" disabled style="background-color: #e6e6e6;" >
                                </div>
                            </div>

                             <div class="form-group row">
                                <label for="amt_less" class="col-md-3 col-form-label">จำนวนไม่ต่ำกว่า (ชิ้น) :</label>
                                <div class="col-md-9">
                                    <input class="form-control NumberOnly " type="text" value="{{ @$sRow->amt_less>0?@$sRow->amt_less:'0' }}" name="amt_less" required="" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="amt_day_before_expired" class="col-md-3 col-form-label">แจ้งเตือนก่อนวันหมดอายุ (วัน):</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ @$sRow->amt_day_before_expired>0?@$sRow->amt_day_before_expired:'0' }}" name="amt_day_before_expired" required="" >
                                </div>
                            </div>


                            
                          <div class="form-group row">
                            <label for="note" class="col-md-3 col-form-label">หมายเหตุ :</label>
                            <div class="col-md-9">
                              <textarea class="form-control" rows="3" id="note" name="note" required="" >{{ @$sRow->note }}</textarea>
                            </div>
                          </div>


                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/stock_notify") }}">
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
