@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style>
    @media screen and (min-width: 676px) {
        .modal-dialog {
          max-width: 1200px !important; /* New width for default modal */
        }
    }

    .select2-selection {height: 34px !important;margin-left: 3px;}
    .border-left-0 {height: 67%;}
</style>
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> โอนภายในสาขา </h4>

          <?php if(!empty(@$sTransfer_chooseAll) && count($sTransfer_chooseAll)==0){ ?>
              <button type="button" class="btn btn-primary btn-sm btnAddTransferItem " style="font-size: 14px !important;" >
               + เพิ่มรายการโอนสินค้า 
              </button>
          <?php } ?>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" style="">
        <div id="spinner_frame"
            style="display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            z-index: 9999;
            "><p align="center">
                <img src="{{ asset('backend/images/preloader_big.gif') }}">
            </p></div>
        </div>
    </div>


<!-- end page title -->
  <?php 
    
    // echo Session::get('session_menu_id');

      $sPermission = \Auth::user()->permission ;
      // $menu_id = @$_REQUEST['menu_id'];
      $menu_id = Session::get('session_menu_id');
      if($sPermission==1){
        $sC = '';
        $sU = '';
        $sD = '';
        $role_group_id = '%';
      }else{
        $role_group_id = \Auth::user()->role_group_id_fk;
        // echo $role_group_id;
        // echo $menu_id;     
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        $sC = @$menu_permit->c==1?'':'display:none;';
        $sU = @$menu_permit->u==1?'':'display:none;';
        $sD = @$menu_permit->d==1?'':'display:none;';
      }
      // echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;     
   ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

<!-- display: none; -->
   <?php if(!empty(@$sTransfer_chooseAll) && count($sTransfer_chooseAll)==0){ ?>
      <div class="myBorder divAddTransferItem " style="display: none;" >
  <?php }else{ ?>
      <div class="myBorder divAddTransferItem " >
  <?php } ?>


         <form id="frm_save_to_transfer_list" action="{{ route('backend.transfer_warehouses_code.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            {{ csrf_field() }}


                <div class="row" >
                  <div class="col-8">

                        <div class="form-group row">
                          <label for="note" class="col-md-3 col-form-label"><i class="bx bx-play"></i>สาขา :</label>
                          <div class="col-md-9">

                            <?php //echo $User_branch_id; ?>
                           
                            @if(@$User_branch_id==0)
                             <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating " >
                              <option value="">Select</option>}
                             @if(@$sBranchs)
                                @foreach(@$sBranchs AS $r)
                                <option value="{{$r->id}}" {{ (@$r->id==@$sRow->branch_id_fk)?'selected':'' }} >
                                  {{$r->b_name}}
                                </option>
                                @endforeach
                              @endif
                             </select>
                             @endif


                              @if(@$User_branch_id!=0)
                             <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating " >
                             @if(@$sBranchs)
                                @foreach(@$sBranchs AS $r)
                                @if(@$r->id==$User_branch_id)
                                <option value="{{$r->id}}" {{ (@$r->id==@$sRow->branch_id_fk)?'selected':'' }} >
                                  {{$r->b_name}}
                                </option>
                                @endif
                                @endforeach
                              @endif
                             </select>
                             @endif

                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="note" class="col-md-3 col-form-label"><i class="bx bx-play"></i>หมายเหตุ (ถ้ามี) :</label>
                          <div class="col-md-9">
                            <textarea class="form-control" rows="3" id="note" name="note" ></textarea>
                          </div>
                        </div>

                        <div class="form-group row">
                        <label for="example-text-input" class="col-md-3 col-form-label"><i class="bx bx-play"></i>รหัสสินค้า : ชื่อสินค้า </label>
                        <div class="col-md-7">
                          <select name="product" id="product" class="form-control select2-templating "  >
                            <option value="">Select</option>
                               @if(@$Products)
                                    @foreach(@$Products AS $r)
                                      <option value="{{@$r->product_id}}" {{ (@$r->product_id==@$sRow->product_id_fk)?'selected':'' }} >
                                        {{@$r->product_code." : ".@$r->product_name}}
                                      </option>
                                    @endforeach
                                  @endif
                          </select>
                        </div>

                        <div class="col-2" >
                          <a class="btn btn-info btn-sm btnSearch " href="{{ route('backend.transfer_warehouses.index') }}" style="font-size: 14px !important;" >
                            <i class="bx bx-search align-middle "></i> SEARCH
                          </a>
                        </div>

                      </div>
                  </div>

                </div>


                <table id="data-table-to-transfer" class="table table-bordered dt-responsive" style="width: 100%;">
                 </table>
                  <?php if(!empty(@$sTransfer_chooseAll) && count($sTransfer_chooseAll)!=0 && count($sTransfer_choose)==0){ ?>
                  <div class="row" style="" >
                    <div class="col-md-12 text-center divBtnSave "  >
                      <button type="submit" class="btn btn-primary btn-sm waves-effect btnSave " style="font-size: 14px !important;" >
                      <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึก สร้างใบโอน
                      </button>
                    </div>
                  </div>
                <?php } ?>

          </form>

			 </div>


          <div class="myBorder">

                  <div class="row">
                    <div class="col-12">
                      <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0 font-size-18"><i class="bx bx-play"></i> รายการใบโอน </h4>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-12 d-flex ">
                      <div class="col-md-3 ">
                        <div class="form-group row">
                          <select id="branch_id_search" name="branch_id_search" class="form-control select2-templating " >
                            <option value="">สาขา</option>
                            @if(@$sBranchs)
                            @foreach(@$sBranchs AS $r)
                            <option value="{{$r->id}}"  >
                              {{$r->b_name}}
                            </option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group row">
                          <select id="warehouse_id_search" name="warehouse_id_search" class="form-control select2-templating "  >
                            <option disabled selected >(คลัง) กรุณาเลือกสาขาก่อน</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-1">
                        <div class="form-group row">
                          <select id="status_search" name="status_search" class="form-control select2-templating " >
                            <!--   `approve_status` int(11) DEFAULT '0' COMMENT '0=รออนุมัติ,1=อนุมัติ,2=ยกเลิก,3=ไม่อนุมัติ', -->
                            <option value="" >สถานะ</option>
                            <option value="0" >รออนุมัติ</option>
                            <option value="1" >อนุมัติ</option>
                            <option value="3" >ไม่อนุมัติ</option>
                            <option value="2" >ยกเลิก</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3 d-flex  ">
                         <input id="startDate"  autocomplete="off" placeholder="วันเริ่ม"  />
                         <input id="endDate"  autocomplete="off" placeholder="วันสิ้นสุด"  />
                      </div>
                      <div class="col-md-2">
                        <div class="form-group row"> &nbsp; &nbsp;
                          <button type="button" class="btn btn-success btn-sm waves-effect btnSearchInList " style="font-size: 14px !important;" >
                          <i class="bx bx-search font-size-16 align-middle mr-1"></i> ค้น
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <table id="data-table-transfer-list" class="table table-bordered dt-responsive" style="width: 100%;">
                  </table>

          </div>


            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle"><b><i class="bx bx-play"></i>รายการสินค้า</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

         <form action="{{ route('backend.transfer_warehouses.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="save_select_to_transfer" value="1" >
            <input type="hidden" id="branch_id_select_to_transfer" name="branch_id_select_to_transfer" >
            {{ csrf_field() }}

			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<table id="data-table-choose" class="table table-bordered dt-responsive" style="width: 100%;">
                 				 </table>
							</div>
						</div>
					</div>
				</div>

				 <div class="row">
                    <div class="col-md-12 text-center  "  >
                       <button type="submit" class="btn btn-primary" style="width: 10%;" >Save</button>
                       <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-left: 1%;">Close</button>
                    </div>
                  </div>
			</div>
            <div class="modal-footer">
              <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
              <!-- <button type="submit" class="btn btn-primary">Save</button> -->
            </div>

      </form>

    </div>
  </div>
</div>


<div class="modal fade" id="setToWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="setToWarehouseModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg " role="document" style="max-width: 1000px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="setToWarehouseModalTitle"><b><i class="bx bx-play"></i>เลือกคลังปลายทาง</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{ route('backend.transfer_warehouses.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="save_set_to_warehouse" value="1" >
            <input type="hidden" id="id_set_to_warehouse" name="id_set_to_warehouse" >
            <input type="hidden" id="branch_id_set_to_warehouse" name="branch_id_set_to_warehouse" >
            {{ csrf_field() }}

      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
               
                <div class="row" >
                    <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="branch_id_fk_c" class="col-md-3 col-form-label"> สาขา : </label>
                        <div class="col-md-9">
                            <select id="branch_id_fk_c" name="branch_id_fk_c" class="form-control select2-templating " >
                             <option value="">Select</option>
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
                    </div>
                    <div class="col-md-6 " >
                      <div class="form-group row">
                            <label for="example-text-input" class="col-md-2 col-form-label"> คลัง : </label>
                            <div class="col-md-10">
                              <select id="warehouse_id_fk_c" name="warehouse_id_fk_c" class="form-control select2-templating " required >
                                 <option value="">เลือกคลัง</option>
                                 @if(@$Subwarehouse)
                                  @foreach(@$Subwarehouse AS $r)
                                  <option value="{{$r->id}}" >
                                    {{$r->w_name}}
                                  </option>
                                  @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>
                    </div>
                  </div>

                  <div class="row" >
                    <div class="col-md-6 " >
                       <div class="form-group row">
                            <label for="zone_id_fk_c" class="col-md-3 col-form-label"> Zone : </label>
                            <div class="col-md-9">
                              <select id="zone_id_fk_c" name="zone_id_fk_c" class="form-control select2-templating " required >
                                <option disabled selected>กรุณาเลือกคลังก่อน</option>
                              </select>
                            </div>
                          </div>
                    </div>
                    <div class="col-md-6 " >
                        <div class="form-group row">
                            <label for="shelf_id_fk_c" class="col-md-2 col-form-label"> Shelf : </label>
                            <div class="col-md-10">
                              <select id="shelf_id_fk_c"  name="shelf_id_fk_c" class="form-control select2-templating " required >
                                 <option disabled selected>กรุณาเลือกโซนก่อน</option>
                              </select>
                            </div>
                          </div>
                    </div>
                  </div>


              </div>
            </div>
          </div>
        </div>

         <div class="row">
          <div class="col-md-12 text-center  "  >
             <button type="submit" class="btn btn-primary" style="width: 10%;" >Save</button>
             <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-left: 1%;">Close</button>
          </div>
        </div>

      </div>
            <div class="modal-footer">
              <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
              <!-- <button type="submit" class="btn btn-primary">Save</button> -->
            </div>

      </form>

    </div>
  </div>
</div>



<div class="modal fade" id="setToWarehouseModalEdit" tabindex="-1" role="dialog" aria-labelledby="setToWarehouseModalEditTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg " role="document" style="max-width: 1000px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="setToWarehouseModalEditTitle"><b><i class="bx bx-play"></i>เลือกคลังปลายทาง</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

         <form action="{{ route('backend.transfer_warehouses.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="save_set_to_warehouse_e" value="1" >
            <input type="hidden" id="id_set_to_warehouse_e" name="id_set_to_warehouse_e" >
            <input type="hidden" id="branch_id_set_to_warehouse_e" name="branch_id_set_to_warehouse_e" >
            {{ csrf_field() }}

      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
               
                <div class="row" >
                    <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="branch_id_fk_c_e" class="col-md-3 col-form-label"> สาขา : </label>
                        <div class="col-md-9">
                            <select id="branch_id_fk_c_e" name="branch_id_fk_c_e" class="form-control select2-templating " >
                             <option value="">Select</option>
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
                    </div>
                    <div class="col-md-6 " >
                      <div class="form-group row">
                            <label for="warehouse_id_fk_c_e" class="col-md-2 col-form-label"> คลัง : </label>
                            <div class="col-md-10">
                              <select id="warehouse_id_fk_c_e" name="warehouse_id_fk_c_e" class="form-control select2-templating " required >
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
                    </div>
                  </div>

                  <div class="row" >
                    <div class="col-md-6 " >
                       <div class="form-group row">
                            <label for="zone_id_fk_c_e" class="col-md-3 col-form-label"> Zone : </label>
                            <div class="col-md-9">
                              <select id="zone_id_fk_c_e" name="zone_id_fk_c_e" class="form-control select2-templating " required >
                                <option value="">เลือก Zone</option>
                                 @if(@$Zone)
                                  @foreach(@$Zone AS $r)
                                  <option value="{{$r->id}}" {{ (@$r->id==@$sRow->zone_id_fk)?'selected':'' }} >
                                    {{$r->z_name}}
                                  </option>
                                  @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>
                    </div>
                    <div class="col-md-6 " >
                        <div class="form-group row">
                            <label for="shelf_id_fk_c_e" class="col-md-2 col-form-label"> Shelf : </label>
                            <div class="col-md-10">
                              <select id="shelf_id_fk_c_e"  name="shelf_id_fk_c_e" class="form-control select2-templating " required >
                                 <option value="">เลือก Shelf</option>
                                 @if(@$Shelf)
                                  @foreach(@$Shelf AS $r)
                                  <option value="{{$r->id}}" {{ (@$r->id==@$sRow->shelf_id_fk)?'selected':'' }} >
                                    {{$r->s_name}}
                                  </option>
                                  @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>
                    </div>
                  </div>


              </div>
            </div>
          </div>
        </div>

         <div class="row">
                    <div class="col-md-12 text-center  "  >
                       <button type="submit" class="btn btn-primary" style="width: 10%;" >Save</button>
                       <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-left: 1%;">Close</button>
                    </div>
                  </div>
      </div>
            <div class="modal-footer">
              <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
              <!-- <button type="submit" class="btn btn-primary">Save</button> -->
            </div>

      </form>

    </div>
  </div>
</div>


<div class="modal fade" id="modalNote" tabindex="-1" role="dialog" aria-labelledby="modalNoteTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  " role="document" style="max-width: 650px !important;" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNoteTitle"><b><i class="bx bx-play"></i>หมายเหตุ (สาเหตุที่ยกเลิก/หรืออื่นๆ)</b></h5>
      </div>

         <form action="{{ route('backend.transfer_warehouses.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="save_select_to_cancel" value="1" >
            <input type="hidden" id="id_to_cancel" name="id_to_cancel" >
            {{ csrf_field() }}

      <div class="modal-body">
        <div class="row">
          <div class="col-12">
               <textarea class="form-control" rows="5" id="note_to_cancel" name="note_to_cancel" required="" ></textarea>
          </div>
        </div>

         <div class="row">
                    <div class="col-md-12 text-center  "  >
                       <button type="submit" class="btn btn-primary" style="width: 10%;margin-top: 5%;" >Save</button>
                       <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-left: 1%;margin-top: 5%;">Close</button>
                    </div>
                  </div>
      </div>

      </form>

    </div>
  </div>
</div>


@endsection

@section('script')


<script type="text/javascript">
/*
        sessionStorage.setItem("role_group_id", role_group_id);
        var role_group_id = sessionStorage.getItem("role_group_id");
        var menu_id = sessionStorage.getItem("menu_id");
        // alert(sessionStorage.getItem("menu_id"));
          window.onload = function() {
          if(!window.location.hash) {
             window.location = window.location + '?role_group_id=' + role_group_id + '&menu_id=' + sessionStorage.getItem("menu_id") + '#menu_id=' + sessionStorage.getItem("menu_id") ;
          }
          // $(".btnSearch").trigger('click');
        }
*/

       $('#branch_id_fk_c').change(function(){

          var branch_id_fk = this.value;
          // alert(warehouse_id_fk);

           if(branch_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetWarehouse') }} ", 
                  method: "post",
                  data: {
                    branch_id_fk:branch_id_fk,
                    "_token": "{{ csrf_token() }}", 
                  },
                  success:function(data)
                  { 
                   if(data == ''){
                       alert('ไม่พบข้อมูลคลัง !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือกคลัง -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.w_name+'</option>';
                       });
                       $('#warehouse_id_fk_c').html(layout);
                       $('#zone_id_fk_c').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                       $('#shelf_id_fk_c').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                   }
                  }
                })
           }
 
      });


       $('#warehouse_id_fk_c').change(function(){

          var warehouse_id_fk = this.value;
          // alert(warehouse_id_fk);

           if(warehouse_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetZone') }} ", 
                  method: "post",
                  data: {
                    warehouse_id_fk:warehouse_id_fk,
                    "_token": "{{ csrf_token() }}", 
                  },
                  success:function(data)
                  { 
                   if(data == ''){
                       alert('ไม่พบข้อมูล Zone !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือก Zone -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.z_name+'</option>';
                       });
                       $('#zone_id_fk_c').html(layout);
                       $('#shelf_id_fk_c').html('กรุณาเลือกโซนก่อน');
                   }
                  }
                })
           }
 
      });


       $('#zone_id_fk_c').change(function(){

          var zone_id_fk = this.value;
          // alert(zone_id_fk);

           if(zone_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetShelf') }} ", 
                  method: "post",
                  data: {
                    zone_id_fk:zone_id_fk,
                    "_token": "{{ csrf_token() }}", 
                  },
                  success:function(data)
                  { 
                   if(data == ''){
                       alert('ไม่พบข้อมูล Shelf !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือก Shelf -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.s_name+'</option>';
                       });
                       $('#shelf_id_fk_c').html(layout);
                   }
                  }
                })
           }
 
      });



       $('#branch_id_fk_c_e').change(function(){

          var branch_id_fk = this.value;
          // alert(warehouse_id_fk);

           if(branch_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetWarehouse') }} ", 
                  method: "post",
                  data: {
                    branch_id_fk:branch_id_fk,
                    "_token": "{{ csrf_token() }}", 
                  },
                  success:function(data)
                  { 
                   if(data == ''){
                       alert('ไม่พบข้อมูลคลัง !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือกคลัง -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.w_name+'</option>';
                       });
                       $('#warehouse_id_fk_c_e').html(layout);
                       $('#zone_id_fk_c_e').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                       $('#shelf_id_fk_c_e').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                   }
                  }
                })
           }
 
      });


       $('#warehouse_id_fk_c_e').change(function(){

          var warehouse_id_fk = this.value;
          // alert(warehouse_id_fk);

           if(warehouse_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetZone') }} ", 
                  method: "post",
                  data: {
                    warehouse_id_fk:warehouse_id_fk,
                    "_token": "{{ csrf_token() }}", 
                  },
                  success:function(data)
                  { 
                   if(data == ''){
                       alert('ไม่พบข้อมูล Zone !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือก Zone -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.z_name+'</option>';
                       });
                       $('#zone_id_fk_c_e').html(layout);
                       $('#shelf_id_fk_c_e').html('กรุณาเลือกโซนก่อน');
                   }
                  }
                })
           }
 
      });


       $('#zone_id_fk_c_e').change(function(){

          var zone_id_fk = this.value;
          // alert(zone_id_fk);

           if(zone_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetShelf') }} ", 
                  method: "post",
                  data: {
                    zone_id_fk:zone_id_fk,
                    "_token": "{{ csrf_token() }}", 
                  },
                  success:function(data)
                  { 
                   if(data == ''){
                       alert('ไม่พบข้อมูล Shelf !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือก Shelf -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.s_name+'</option>';
                       });
                       $('#shelf_id_fk_c_e').html(layout);
                   }
                  }
                })
           }
 
      });


$(document).ready(function() {
  
      var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
      var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
      var sU = "{{@$sU}}"; //alert(sU);
      var sD = "{{@$sD}}"; //alert(sD);
      var oTable;
      $(function() {

          if("{{\Auth::user()->permission}}"==1){
             var branch_id_fk =0 ; // 0 = All
          }else{
             var branch_id_fk = $("#branch_id_fk").val(); //alert(branch_id_fk);
             var branch_id_fk = branch_id_fk?branch_id_fk:999999999; //alert(branch_id_fk); // 0 = All
          }

          oTable = $('#data-table-transfer-list').DataTable({
          "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
              processing: true,
              serverSide: true,
              scroller: true,
              scrollCollapse: true,
              scrollX: true,
              ordering: false,
              scrollY: ''+($(window).height()-370)+'px',
              iDisplayLength: 25,
              ajax: {
                url: '{{ route('backend.transfer_warehouses_code.datatable') }}',
                // data: function ( d ) {
                //   d.Where={};
                //   d.Where['branch_id_fk'] = branch_id_fk ;
                //   oData = d;
                // },
                 method: 'POST',
               },

              columns: [
                  {data: 'tr_number', title :'รหัสใบโอน', className: 'text-center w80'},
                  {data: 'action_date', title :'<center>วันที่ดำเนินการ </center>', className: 'text-center'},
                  // {data: 'amt', title :'<center>จำนวนรายการที่โอน </center>', className: 'text-center'},
                  {data: 'action_user', title :'<center>พนักงานที่ทำการโอน </center>', className: 'text-center'},
                  {data: 'approve_status',   title :'<center>สถานะการอนุมัติ</center>', className: 'text-center w100 ',render: function(d) {
                    if(d==1){
                        return '<span class="badge badge-pill badge-soft-success font-size-16" style="color:darkgreen">อนุมัติแล้ว</span>';
                    }else if(d==2){
                        return '<span class="badge badge-pill badge-soft-danger font-size-16" style="color:grey">ยกเลิก</span>';
                    }else if(d==3){
                        return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:black">ไม่อนุมัติ</span>';
                    }else{
                        return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">รออนุมัติ</span>';
                    }
                  }},
                  {data: 'approver', title :'<center>ผู้อนุมัติ </center>', className: 'text-center'},
                  {data: 'approve_date', title :'<center>วันอนุมัติ </center>', className: 'text-center'},
                  {data: 'id',   title :'พิมพ์ใบโอน', className: 'text-center ',render: function(d) {
                      return '<center><a href="{{ URL::to('backend/transfer_warehouses/print_transfer') }}/'+d+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center>';
                  }},
                  {data: 'note',   title :'หมายเหตุ', className: 'text-center ',render: function(d) {
                      return d ;
                  }},
                  {data: 'id', title :'Tools', className: 'text-center w80'}, 
              ],
              rowCallback: function(nRow, aData, dataIndex){

                // if(sU!=''&&sD!=''){
                //     $('td:last-child', nRow).html('-');
                // }else{ 

                  if(aData['approve_status']!=0){

                    // $('td:eq(6)', nRow).html( '' );
                    $('td:last-child', nRow).html(''
                      + '<a href="{{ route('backend.transfer_warehouses.index') }}/'+aData['id']+'/edit?role_group_id='+role_group_id+'&menu_id='+menu_id+'&list_id='+aData['id']+'" class="btn btn-sm btn-primary" style="font-size:16px;padding-top:0px !important;padding-bottom:0px !important;" ><i class="mdi mdi-eye-outline align-middle" ></i></a> '
                      + '<a href="javascript: void(0);"  class="btn btn-sm btn-secondary " title="ยกเลิก" ><i class="bx bx-x font-size-18 font-weight-bold align-middle" style="color:#bfbfbf;"></i></a>'
                    ).addClass('input');

                  }else{

                    $('td:last-child', nRow).html(''
                      + '<a href="{{ route('backend.transfer_warehouses.index') }}/'+aData['id']+'/edit?role_group_id='+role_group_id+'&menu_id='+menu_id+'&list_id='+aData['id']+'" class="btn btn-sm btn-primary " style=" font-size:16px;padding-top:0px !important;padding-bottom:0px !important;" ><i class="mdi mdi-eye-outline align-middle" ></i></a> '
                      + '<a href="javascript: void(0);" data-id="'+aData['id']+'" class="btn btn-sm btn-danger cCancel " title="ยกเลิก" ><i class="bx bx-x font-size-18 font-weight-bold align-middle"></i></a>'
                    ).addClass('input');

                  }

                // }
              }
          });
          $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
            oTable.draw();
          });
      });
    });
  	

    $(document).ready(function() {
        $(document).on('click', '.btnSearch', function(event) {
          event.preventDefault();

          var branch_id_fk = $("#branch_id_fk").val();
          var product_id = $("#product").val();
          var product_id = product_id?product_id:0;

          $("#branch_id_select_to_transfer").val(branch_id_fk);
          
          if(branch_id_fk==''){
            $("#branch_id_fk").select2('open');
            return false;
          }else if(product_id==''){
            $("#product").select2('open');
            return false;
          }else{

            $("#spinner_frame").show();

  					var oTable;
  					$(function() {

  					    oTable = $('#data-table-choose').DataTable({
  					    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
  					        processing: true,
  					        serverSide: true,
  					        ordering: false,
  					        "info":     false,
  					        destroy: true,
  					        searching: false,
  					        // paging: false,
  					        ajax: {
  					          url: '{{ route('backend.check_stock.datatable') }}',
  					          data: function ( d ) {
  					            d.Where={};
  					            d.Where['branch_id_fk'] = branch_id_fk ;
                        d.Where['product_id_fk'] = product_id ;
  					            oData = d;
  					          },
  					          method: 'POST'
  					        },
  					        columns: [
  					            {data: 'id', title :'ID', className: 'text-center w50'},
  					            {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left'},
  					            {data: 'lot_number', title :'<center>ล็อตนัมเบอร์ </center>', className: 'text-left'},
  					            {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
  					            {data: 'warehouses', title :'<center>คลังสินค้า </center>', className: 'text-left'},
                        {data: 'amt',   title :'<center>จำนวนที่มีในคลัง</center>', className: 'text-center',render: function(d) {
                           return '<center>'+(d)+'<input type="hidden" class="amt_in_warehouse" value="'+(d)+'" > ' ;
                        }},
  					            {data: 'id',   title :'<center>จำนวนโอน</center>', className: 'text-center',render: function(d) {
  					               return '<center><input class="form-control amt_to_transfer in-tx  " type="number"  name="amt_transfer[]" style="background-color:#e6ffff;border: 2px inset #EBE9ED;width:60%;text-align:center;" ><input type="hidden" name="id[]" value="'+(d)+'" >' ;
  					            }},

  					        ],

  					    });
  					  
  					});

            			$('#exampleModalCenter').modal('show');
          }
          
        });
    });


    $('#exampleModalCenter').on('focus', function () {
        $("#spinner_frame").hide();
    });

    var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
    var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
    var sU = "{{@$sU}}"; //alert(sU);
    var sD = "{{@$sD}}"; //alert(sD);
    var oTable;
    $(function() {
        oTable = $('#data-table-to-transfer').DataTable({
        "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
            processing: true,
            serverSide: true,
            ordering: false,
            "info":     false,
            destroy: true,
            searching: false,
            // paging: false,
            ajax: {
              url: '{{ route('backend.transfer_choose.datatable') }}',
              data: function ( d ) {
                d.Where={};
                $('.myWhere').each(function() {
                  if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                    d.Where[$(this).attr('name')] = $.trim($(this).val());
                  }
                });
                d.Like={};
                $('.myLike').each(function() {
                  if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                    d.Like[$(this).attr('name')] = $.trim($(this).val());
                  }
                });
                d.Custom={};
                $('.myCustom').each(function() {
                  if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                    d.Custom[$(this).attr('name')] = $.trim($(this).val());
                  }
                });
                oData = d;
              },
              method: 'POST'
            },

           columns: [
    	            {data: 'id', title :'ID', className: 'text-center w50'},
    	            {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left'},
    	            {data: 'lot_number', title :'<center>ล็อตนัมเบอร์ </center>', className: 'text-left'},
    	            {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
    	            {data: 'amt_in_warehouse', title :'<center>จำนวนที่มีในคลัง </center>', className: 'text-center'},
    	            {data: 'amt', title :'<center>จำนวนที่ต้องการโอน </center>', className: 'text-center'},
                  {data: 'warehouses',   title :'<center>โอนย้ายไปที่คลัง</center>', className: 'text-center',render: function(d) {
                      if(d!='0'){
                         return d;
                       }else{
                          return  "<span style='color:red;'>* รอเลือกคลังปลายทาง </span>";
                       }
                  }},
    	          	{data: 'id', title :'Tools', className: 'text-center w150'}, 
    		        ],
    		        rowCallback: function(nRow, aData, dataIndex){
    		          // if(sU!=''&&sD!=''){
    		          //     $('td:last-child', nRow).html('-');
    		          // }else{ 

                    if(aData['warehouse_id_fk']>0){

                      $('td:last-child', nRow).html(''
                        + '<input type="hidden" name="transfer_choose_id[]" value="'+aData['id']+'"> '
                        + '<a href="#" class="btn btn-sm btn-success btnEditToWarehouse " data-id="'+aData['id']+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.transfer_choose.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');

                    }else{

                      $('td:last-child', nRow).html(''
                        + '<a href="#" class="btn btn-sm btn-primary btnSetToWarehouse " data-id="'+aData['id']+'"  ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.transfer_choose.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"  ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
    		              

    		          }
    		        // }

    	    });
    	  
    	});


    $(document).ready(function() {
       $(document).on('change', '.amt_to_transfer', function(event) {
           event.preventDefault();
           var amt_to_transfer = $(this).val();
           var amt_in_warehouse = $(this).closest("tr").find(".amt_in_warehouse").val();
           // alert(amt_to_transfer+":"+amt_in_warehouse);
           var a = parseInt(amt_to_transfer);
           var b = parseInt(amt_in_warehouse);
           if(a>b){
              alert("!!! จำนวนไม่ถูกต้อง จำนวนที่โอนควรมีค่าน้อยกว่าหรือเท่ากับจำนวนที่มีในคลัง");
              $(this).val("");
              return false;
           }
       });
    });


</script>
<script type="text/javascript">

    $(document).ready(function() {

      // $("#exampleModalCenter").modal({ show : true });
      

       $(window).keydown(function(event){
          if(event.keyCode == 13) {
            event.preventDefault();
            return false;
          }
        });


        $('#note').change(function() {
            localStorage.setItem('note', this.value);
        });


        if(localStorage.getItem('note')){
          $('#note').val(localStorage.getItem('note'));
        }

        $(document).on('click', '.btnSave', function(event) {
           event.preventDefault();
           var branch_id_fk = $("#branch_id_fk").val();
           if(branch_id_fk==""){
              $("#branch_id_fk").select2('open');
              return false;
           }

           $("#spinner_frame").show();

            setTimeout(function(){
              // localStorage.clear();
              // $("#spinner_frame").hide();
              // location.reload();
              $("#frm_save_to_transfer_list").submit();
            }, 2500);


        });


        $(document).on('click', '.btnAddTransferItem', function(event) {
            // event.preventDefault();
            $("#spinner_frame").show();
            setTimeout(function(){
              // $(".divAddTransferItem").show();
              $(".divAddTransferItem").toggle();
              $("#spinner_frame").hide();
            }, 500);
            // $(".divAddTransferItem").toggle();
        });


        $(document).on('click', '.btnSetToWarehouse', function(event) {
          event.preventDefault();
             var id = $(this).data('id');
             var branch_id_fk = $("#branch_id_fk").val();
             $('#branch_id_set_to_warehouse').val(branch_id_fk);
             $('#branch_id_fk_c').val(branch_id_fk).select2();

             // alert(branch_id_fk);

             if(branch_id_fk != ''){
               $.ajax({
                     url: " {{ url('backend/ajaxGetWarehouse') }} ", 
                    method: "post",
                    data: {
                      branch_id_fk:branch_id_fk,
                      "_token": "{{ csrf_token() }}", 
                    },
                    success:function(data)
                    { 
                     if(data == ''){
                         alert('ไม่พบข้อมูลคลัง !!.');
                     }else{
                         var layout = '<option value="" selected>- เลือกคลัง -</option>';
                         $.each(data,function(key,value){
                          layout += '<option value='+value.id+'>'+value.w_name+'</option>';
                         });
                         $('#warehouse_id_fk_c').html(layout);
                         $('#zone_id_fk_c').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                         $('#shelf_id_fk_c').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                     }
                    }
                  })
             }else{
                $('#warehouse_id_fk_c').html('<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
                $('#zone_id_fk_c').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                $('#shelf_id_fk_c').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
             }
  
             $("#id_set_to_warehouse").val(id);
             $('#branch_id_fk_c').attr("disabled", true);
             $('#setToWarehouseModal').modal('show');

        });


        $(document).on('change', '#branch_id_fk', function(event) {
            event.preventDefault();
            var id = $(this).val();
            localStorage.setItem('branch_id_fk', id);
        });

        if(localStorage.getItem('branch_id_fk')){
            $('#branch_id_fk').val(localStorage.getItem('branch_id_fk')).select2();
        }


        $(document).on('change', '#product', function(event) {
            event.preventDefault();
            var id = $(this).val();
            localStorage.setItem('product', id);
        });

        if(localStorage.getItem('product')){
            $('#product').val(localStorage.getItem('product')).select2();
        }



        $(document).on('click', '.btnEditToWarehouse', function(event) {
          event.preventDefault();
           var id = $(this).data('id');
           // alert(id);

           var branch_id_fk = $("#branch_id_fk").val();
           $('#branch_id_set_to_warehouse_e').val(branch_id_fk);

           $("#id_set_to_warehouse_e").val(id);
           // $('#setToWarehouseModal').modal('show');

           if(id != ''){

                 $.ajax({

                       type:'POST',
                       url: " {{ url('backend/ajaxGetSetToWarehouse') }} ", 
                       data:{ _token: '{{csrf_token()}}',id:id },
                        success:function(data){
                             
                             // console.log(data); 

                                var obj = JSON.parse(data);
                                $.each(obj, function( index, value ) {

                                    // $('#warehouse_id_fk_e').val(value.warehouse_id_fk).select2();
                                    // $('#subwarehouse_id_fk_e').val(value.subwarehouse_id_fk).select2();
                                    // $("#subwarehouse_id_fk_e").append("<option value='"+ value.subwarehouse_id_fk +"' selected > " + value.Subwarehouse_name + "</option>");

                                //     $("#zone_id_fk_e").append("<option value='"+ value.zone_id_fk +"' selected > " + value.zone + "</option>");

                                //     $("#shelf_id_fk_e").append("<option value='"+ value.shelf_id_fk +"' selected > " + value.shelf + "</option>");

                                // });

                                // $('#setToWarehouseModalEdit').modal('show');
                                // $('#warehouse_id_fk_e').attr("disabled", true);

                                // console.log(value.branch_id_fk);

                                var branch_id_fk = value.branch_id_fk;
                                var warehouse_id_fk = value.warehouse_id_fk;
                                var zone_id_fk = value.zone_id_fk;
                                var shelf_id_fk = value.shelf_id_fk;

                                console.log(warehouse_id_fk);
                                console.log(zone_id_fk);
                                console.log(shelf_id_fk);

                                $('#branch_id_fk_c_e').val(branch_id_fk).select2();
                                $('#warehouse_id_fk_c_e').val(warehouse_id_fk).select2();
                                $('#zone_id_fk_c_e').val(zone_id_fk).select2();
                                $('#shelf_id_fk_c_e').val(shelf_id_fk).select2();
                               

                             });

                                $('#branch_id_fk_c_e').attr("disabled", true);

                                $('#setToWarehouseModalEdit').modal('show');

                          },
                        error: function(jqXHR, textStatus, errorThrown) { 
                            console.log(JSON.stringify(jqXHR));
                            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        }
                  });

       
           }


        });



        $(document).on('click', '.cDelete', function(event) {
              setTimeout(function(){
                location.reload();
              }, 3000);  
        });

        $(document).on('click', '.cCancel', function(event) {
              event.preventDefault();
              var id = $(this).data('id');
              $('#id_to_cancel').val(id);
              $('#modalNote').modal('show');
           
        });




       $('#branch_id_search').change(function(){

          var branch_id_fk = this.value;
          // alert(warehouse_id_fk);

           if(branch_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetWarehouse') }} ", 
                  method: "post",
                  data: {
                    branch_id_fk:branch_id_fk,
                    "_token": "{{ csrf_token() }}", 
                  },
                  success:function(data)
                  { 
                   if(data == ''){
                       alert('ไม่พบข้อมูลคลัง !!.'); 
                       $("#warehouse_id_search").val('').trigger('change'); 
                       $('#warehouse_id_search').html('<option disabled selected >(คลัง) กรุณาเลือกสาขาก่อน</option>');
                   }else{
                       var layout = '<option value="" selected>- เลือกคลัง -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.w_name+'</option>';
                       });
                       $('#warehouse_id_search').html(layout);
                   }
                  }
                })
           }
 
      });



    });


</script>

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#startDate').datepicker({
            format: 'dd/mm/yyyy',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // minDate: today,
            // maxDate: function () {
            //     return $('#endDate').val();
            // }
        });
        $('#endDate').datepicker({
            format: 'dd/mm/yyyy',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                return $('#startDate').val();
            }
        });

         $('#startDate').change(function(event) {
           $('#endDate').val($(this).val());
         });

        $(document).ready(function() {
          
            $(document).on('click', '.btnSearchInList', function(event) {
                  // event.preventDefault();

                  $("#spinner_frame").show();

                  var branch_id_search = $('#branch_id_search').val();
                  var warehouse_id_search = $('#warehouse_id_search').val();
                  var warehouse_id_search = warehouse_id_search==null?"":warehouse_id_search;
                  var status_search = $('#status_search').val();
                  // var startDated = $('#startDate').val();

                  var s_date = $('#startDate').val();
                  var startDated = s_date.split("/").reverse().join("-");
                  var e_date = $('#endDate').val();
                  var endDate = e_date.split("/").reverse().join("-");
                  
                  console.log(branch_id_search);
                  console.log(warehouse_id_search);
                  console.log(status_search);
                  console.log(startDated);
                  console.log(endDate);

                        var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
                        var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
                        var sU = "{{@$sU}}"; //alert(sU);
                        var sD = "{{@$sD}}"; //alert(sD);
                        var oTable;
                        $(function() {

                          var branch_id_fk = branch_id_search?branch_id_search:0; //alert(branch_id_fk); // 0 = All
                          var approve_status = status_search; //alert(branch_id_fk); // 0 = All

                          oTable = $('#data-table-transfer-list').DataTable({
                          "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                              processing: true,
                              serverSide: true,
                              scroller: true,
                              scrollCollapse: true,
                              scrollX: true,
                              ordering: false,
                              destroy: true,
                              scrollY: ''+($(window).height()-370)+'px',
                              iDisplayLength: 25,
                              ajax: {
                                url: '{{ route('backend.transfer_warehouses_code.datatable') }}',
                                data :{
                                  branch_id:branch_id_fk,
                                  approve_status:approve_status,
                                  startDated:startDated,
                                  endDate:endDate,
                                },
                                // กรณีนี้จะไม่ทำงาน ถ้าเป็น Datatable คิวรี่แบบธรรมดา  $sTable = DB::select();
                                // จะใช้ได้กับแบบที่เป็น Models => $sTable = \App\Models\Backend\;
                                // data: function ( d ) {
                                //   d.myWhere={};
                                //   d.myWhere['branch_id_fk'] = branch_id_fk ;
                                //   d.myWhere['approve_status'] = approve_status ;
                                //   d.myWhere['action_date'] = startDated+":"+endDate ;
                                //   oData = d;
                                  // $("#spinner_frame").hide();
                                // },
                                 method: 'POST',
                               },

                              columns: [
                                  {data: 'tr_number', title :'รหัสใบโอน', className: 'text-center w80'},
                                  {data: 'action_date', title :'<center>วันที่ดำเนินการ </center>', className: 'text-center'},
                                  {data: 'action_user', title :'<center>พนักงานที่ทำการโอน </center>', className: 'text-center'},
                                  {data: 'approve_status',   title :'<center>สถานะการอนุมัติ</center>', className: 'text-center w100 ',render: function(d) {
                                    if(d==1){
                                        return '<span class="badge badge-pill badge-soft-success font-size-16" style="color:darkgreen">อนุมัติแล้ว</span>';
                                    }else if(d==2){
                                        return '<span class="badge badge-pill badge-soft-danger font-size-16" style="color:grey">ยกเลิก</span>';
                                    }else if(d==3){
                                        return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:black">ไม่อนุมัติ</span>';
                                    }else{
                                        return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">รออนุมัติ</span>';
                                    }
                                  }},
                                  {data: 'approver', title :'<center>ผู้อนุมัติ </center>', className: 'text-center'},
                                  {data: 'approve_date', title :'<center>วันอนุมัติ </center>', className: 'text-center'},
                                  {data: 'id',   title :'พิมพ์ใบโอน', className: 'text-center ',render: function(d) {
                                      return '<center><a href="{{ URL::to('backend/transfer_warehouses/print_transfer') }}/'+d+'" target=_blank ><i class="bx bx-printer grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center>';
                                  }},
                                  {data: 'note',   title :'หมายเหตุ', className: 'text-center ',render: function(d) {
                                      return d ;
                                  }},
                                  {data: 'id', title :'Tools', className: 'text-center w80'}, 
                              ],
                              rowCallback: function(nRow, aData, dataIndex){

                                // if(sU!=''&&sD!=''){
                                //     $('td:last-child', nRow).html('-');
                                // }else{ 

                                  if(aData['approve_status']==2){

                                    // $('td:eq(6)', nRow).html( '' );
                                    $('td:last-child', nRow).html(''
                                      + '<a href="{{ route('backend.transfer_warehouses.index') }}/'+aData['id']+'/edit?role_group_id='+role_group_id+'&menu_id='+menu_id+'&list_id='+aData['id']+'" class="btn btn-sm btn-primary" style="font-size:16px;padding-top:0px !important;padding-bottom:0px !important;" ><i class="mdi mdi-eye-outline align-middle" ></i></a> '
                                      + '<a href="javascript: void(0);"  class="btn btn-sm btn-secondary " title="ยกเลิก" ><i class="bx bx-x font-size-18 font-weight-bold align-middle" style="color:#bfbfbf;"></i></a>'
                                    ).addClass('input');

                                  }else{

                                    $('td:last-child', nRow).html(''
                                      + '<a href="{{ route('backend.transfer_warehouses.index') }}/'+aData['id']+'/edit?role_group_id='+role_group_id+'&menu_id='+menu_id+'&list_id='+aData['id']+'" class="btn btn-sm btn-primary " style=" font-size:16px;padding-top:0px !important;padding-bottom:0px !important;" ><i class="mdi mdi-eye-outline align-middle" ></i></a> '
                                      + '<a href="javascript: void(0);" data-id="'+aData['id']+'" class="btn btn-sm btn-danger cCancel " title="ยกเลิก" ><i class="bx bx-x font-size-18 font-weight-bold align-middle"></i></a>'
                                    ).addClass('input');

                                  }

                                // }
                              },

                              drawCallback : function( settings ) {
                                  var api = this.api();
                                  // Output the data for the visible rows to the browser's console
                                  console.log( api.rows( {page:'current'} ).data() );
                                   $("#spinner_frame").hide();
                              }

                          });

                         
              
                      });
               
               
            });

        }); 
    </script>



@endsection

