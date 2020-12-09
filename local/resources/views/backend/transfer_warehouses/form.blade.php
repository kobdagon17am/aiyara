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
            <h4 class="mb-0 font-size-18"> โอนสินค้าระหว่างคลัง </h4>
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
              <form action="{{ route('backend.transfer_warehouses.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.transfer_warehouses.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                      <div class="myBorder">

                          <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> รหัสรับเข้า : * </label>
                            <div class="col-md-10">
                              <select name="general_receive_id_fk" class="form-control select2-templating " disabled="" >
                                <option value="">Select</option>
                                  @if(@$receive_id)
                                    @foreach(@$receive_id AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->general_receive_id_fk)?'selected':'' }} >
                                        {{sprintf("%05d",$r->id)}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>


                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> รหัสสินค้า : * </label>
                            <div class="col-md-10">
                              <select name="product_id_fk" class="form-control select2-templating " disabled="" >
                                <option value="">Select</option>
                                  @if(@$Products)
                                    @foreach(@$Products AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->product_id_fk)?'selected':'' }} >
                                        {{$r->product_code}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>


                          <div class="form-group row">
                            <label for="amt" class="col-md-2 col-form-label">จำนวนที่ได้รับ :</label>
                            <div class="col-md-10">
                              <input class="form-control" type="number" value="{{ @$sRow->amt }}" name="amt" disabled="" >
                            </div>
                          </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> หน่วยสินค้า : * </label>
                            <div class="col-md-10">
                              <select name="product_unit_id_fk" class="form-control select2-templating " disabled="" >
                                <option value="">Select</option>
                                  @if(@$ProductsUnit)
                                    @foreach(@$ProductsUnit AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->product_unit_id_fk)?'selected':'' }} >
                                        {{$r->product_unit}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>


                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> คลังสินค้าหลัก : * </label>
                            <div class="col-md-10">
                              <select name="warehouse_id_fk" class="form-control select2-templating "  >
                                <option value="">Select</option>
                                  @if(@$Warehouse)
                                    @foreach(@$Warehouse AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->warehouse_id_fk)?'selected':'' }} >
                                        {{$r->w_name}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>


                      @if( empty(@$sRow) )
                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> คลังย่อย : * </label>
                            <div class="col-md-10">
                              <select name="subwarehouse_id_fk" class="form-control select2-templating "  >
                                    <option value="" >Select</option>
                              </select>
                            </div>
                          </div>

                          
                          <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> Zone : * </label>
                            <div class="col-md-10">
                              <select name="zone_id_fk" class="form-control select2-templating "  >
                                <option value="" >Select</option>
                              </select>
                            </div>
                          </div>


                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> Shelf : * </label>
                            <div class="col-md-10">
                              <select name="shelf_id_fk" class="form-control select2-templating "  >
                                 <option value="" >Select</option>
                              </select>
                            </div>
                          </div>


                      @else
                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> คลังย่อย : * </label>
                            <div class="col-md-10">
                              <select name="subwarehouse_id_fk" class="form-control select2-templating "  >
                                    <option value="{{@$Subwarehouse[0]->id}}" {{ (@$Subwarehouse[0]->id==@$sRow->subwarehouse_id_fk)?'selected':'' }} >
                                      {{@$Subwarehouse[0]->w_name}}
                                    </option>
                              </select>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> Zone : * </label>
                            <div class="col-md-10">
                              <select name="zone_id_fk" class="form-control select2-templating "  >
                                <option value="{{@$Zone[0]->id}}" {{ (@$Zone[0]->id==@$sRow->zone_id_fk)?'selected':'' }} >
                                      {{@$Zone[0]->w_name}}
                                    </option>
                              </select>
                            </div>
                          </div>


                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> Shelf : * </label>
                            <div class="col-md-10">
                              <select name="shelf_id_fk" class="form-control select2-templating "  >
                                 <option value="{{@$Shelf[0]->id}}" {{ (@$Shelf[0]->id==@$sRow->shelf_id_fk)?'selected':'' }} >
                                      {{@$Shelf[0]->w_name}}
                                    </option>
                              </select>
                            </div>
                          </div>

                      @endif



                          <div class="form-group row">
                                @if( empty(@$sRow) )
                                <label for="example-text-input" class="col-md-2 col-form-label">ผู้ดำเนินการ (User Login) :</label>
                                @else
                                <label for="example-text-input" class="col-md-2 col-form-label">ผู้ดำเนินการ  :</label>
                                @endif
                                <div class="col-md-10">
                                  @if( empty(@$sRow) )
                                    <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                      <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="recipient" >
                                      @else
                                        <input class="form-control" type="text" value="{{@$Recipient[0]->name}}" readonly style="background-color: #f2f2f2;" >
                                      <input class="form-control" type="hidden" value="{{ @$sRow->recipient }}" name="recipient" >
                                   @endif
                                    
                                </div>
                            </div>

                            <hr>

                           <div class="form-group row">
                                <label for="example-text-input" class="col-md-2 col-form-label">ผู้อนุมัติ (Admin Login) :</label>
                                <div class="col-md-10">
                                  @if( empty(@$sRow) )
                                    <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                      <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="approver" >
                                      @else
                                        <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                      <input class="form-control" type="hidden" value="{{ @$sRow->approver }}" name="approver" >
                                   @endif
                                    
                                </div>
                            </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label">สถานะการอนุมัติ :</label>
                    <div class="col-md-10 mt-2">
                      <div class="custom-control custom-switch">
                        @if( empty($sRow) )
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="approve_status" value="1"  >
                        @else
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="approve_status" value="1" {{ ( @$sRow->approve_status=='1')?'checked':'' }}>
                        @endif
                          <label class="custom-control-label" for="customSwitch">อนุมัติ / Aproved</label>
                      </div>
                    </div>
                </div>



                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/transfer_warehouses") }}">
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

