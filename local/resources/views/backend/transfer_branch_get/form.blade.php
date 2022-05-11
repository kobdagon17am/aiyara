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
            <h4 class="mb-0 font-size-18"> รับสินค้าจากการโอนระหว่างสาขา </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row ">
    <div class="col-10">
        <div class="card">
            <div class="card-body">

            <div class="myBorder">

              @if( empty($sRow) )
              <form action="{{ route('backend.transfer_branch_get.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.transfer_branch_get.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

                 <input name="save_from_firstform" type="hidden" value="1">

                <div class="form-group row">
                  <label for="tr_number" class="col-md-3 col-form-label">รหัสใบโอน :</label>
                  <div class="col-md-6">
                    

                    @if( empty(@$sRow) )
                        <input class="form-control" type="text" name="tr_number" value="" required >
                    @else
                        <input class="form-control" type="text" name="tr_number" value="{{ @$sRow->tr_number }}" readonly >
                    @endif

                  </div>
                </div>

                        
                        <div class="form-group row">
                          <label for="" class="col-md-3 col-form-label "> รับจากสาขา : </label>
                          <div class="col-md-6">

                              @if( empty(@$sRow) )
                                <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating " disabled >
                                   <option value="" selected>กรุณาเลือก Business Location ก่อน</option>
                                </select>
                              @else

                                  <select id="get_from_branch_id_fk"  name="get_from_branch_id_fk" class="form-control select2-templating " disabled >
                                    @if(@$sBranchs)
                                      @foreach(@$sBranchs AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->get_from_branch_id_fk)?'selected':'' }} >
                                        {{$r->b_name}}
                                      </option>
                                      @endforeach
                                    @endif

                                      </select>
                              @endif
                                

                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="example-text-input" class="col-md-3 col-form-label">  ผู้รับโอน :  </label>
                          <div class="col-md-6">

                            @if( empty(@$sRow->action_user) )

                                <select name="action_user" id="action_user" class="form-control select2-templating " required >
                                  <option value="">Select</option>
                                    @if(@$sUserAdmin)
                                      @foreach(@$sUserAdmin AS $r)
                                        <option value="{{$r->id}}"  >
                                          {{$r->name}} 
                                        </option>
                                      @endforeach
                                    @endif
                                </select>

                            @else

                                <select name="action_user" id="action_user" class="form-control select2-templating " disabled >
                                    @if(@$sUserAdmin_ALL)
                                      @foreach(@$sUserAdmin_ALL AS $r)
                                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->action_user)?'selected':'' }} >
                                          {{$r->name}} 
                                        </option>
                                      @endforeach
                                    @endif
                                </select>

                            @endif

                       

                          </div>
                        </div>


                <div class="form-group row">
                    <label for="created_at" class="col-md-3 col-form-label">วันที่สร้างใบโอน :  </label>
                    <div class="col-md-3">
                          <input class="form-control" autocomplete="off" id="created_at" name="created_at" value="{{@$sRow->created_at}}"  disabled  />
                    </div>
                </div>

              <!--             <div class="form-group row">
                            <label for="tr_status" class="col-md-3 col-form-label">สถานะ :</label>
                            <div class="col-md-6">
                               <select id="tr_status" name="tr_status" class="form-control select2-templating " >
                                <option value="">-Status-</option>
                                <option value="0" {{ (0==@$sRow->tr_status)?'selected':'' }}> อยู่ระหว่างการดำเนินการ </option>
                                <option value="1" {{ (1==@$sRow->tr_status)?'selected':'' }}> ได้รับสินค้าครบแล้ว </option>
                                <option value="2" {{ (2==@$sRow->tr_status)?'selected':'' }}> ยังค้างรับสินค้า </option>
                                <option value="3" {{ (3==@$sRow->tr_status)?'selected':'' }}> ไม่อนุมัติรับโอน/ปฏิเสธการรับโอน </option>
                              </select>
                            </div>
                          </div> -->

                <div class="form-group row">
                  <label for="note1" class="col-md-3 col-form-label">หมายเหตุ :</label>
                  <div class="col-md-6">
                    <textarea class="form-control" rows="3" id="note1" name="note1" readonly="" >{{ @$sRow->note1 }}</textarea>
                  </div>
                </div>


    

<!--                   <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">ผู้ดำเนินการ(User Login):</label>
                            <div class="col-md-6">
                              @if( empty($sRow) )
                                <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                  <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="action_user" readonly >
                                  @else
                                    <input class="form-control" type="text" value="{{@$action_user}}" readonly >
                                  <input class="form-control" type="hidden" value="{{ @$sRow->action_user }}" name="action_user" >
                               @endif
                            </div>
                       </div>
                           -->
  
                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/transfer_branch_get") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                <!--         <button type="submit" class="btn btn-primary btn-sm waves-effect btnSave ">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                        </button> -->
                    </div>
                </div> 

              </form>
            </div>



      <div class="myBorder">
        <div style="">
          <div class="form-group row">
            <div class="col-md-12">
              <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการรับสินค้าตามใบโอน </span>
<!-- 
              <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.transfer_branch_get_products.create') }}/{{@$sRow->id}}" style="float: right;" >
                <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
              </a> -->

         <!--       <a href="{{ URL::to('backend/transfer_branch_get_products/print_receipt') }}/{{@$sRow->id}}" target=_blank ><i class="bx bx-printer grow " style="font-size:26px;cursor:pointer;color:#0099cc;float: right;padding: 1%;margin-right: 1%;"></i> 
               </a> -->

            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <table id="data-table-01" class="table table-bordered " style="width: 100%;">
              </table>
            </div>
          </div>
        </div>

        <div class="form-group mb-0 row">
            <div class="col-md-6">

                <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/transfer_branch_get") }}">
                  <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                </a>

            </div>
        </div>

      </div>



<div class="myBorder" >

      
        <div style="">
          <div class="form-group row">
            <div class="col-md-12">
              <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> ประวัติการรับสินค้า </span>
              <!--   <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.transfer_branch_get.create') }}/{{@$sRow->id}}" style="float: right;" >
                <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">บันทึกประวัติการรับสินค้า</span>
              </a> -->
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <table id="data-table-02" class="table table-bordered " style="width: 100%;">
              </table>
            </div>
          </div>
        </div>

        <div class="form-group mb-0 row">
            <div class="col-md-6">
                   <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/transfer_branch_get") }}">
                  <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                </a>
            </div>
        </div>

      </div>



            <div class="myBorder div_approve_transfer_branch_get " >

               <h4><i class="bx bx-play"></i> อนุมัติรับสินค้าจากการโอน</h4>
        
              <form id="frm-main" action="{{ route('backend.transfer_branch_get.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input name="id" type="hidden" value="{{@$sRow->id}}">
                <input name="approved_getproduct" type="hidden" value="1">
                {{ csrf_field() }}


                <div class="form-group row">
                    <label class="col-md-4 col-form-label">สถานะการอนุมัติ :</label>
                   

                        <!-- รับสินค้าครบแล้ว -->
                        @IF(@$sRow->tr_status_get == 4)

                         <div class="col-md-3 mt-2">
                      <div class=" ">

                          <input type="radio" class="" id="customSwitch1" name="approve_status" value="1" {{ ( @$sRow->approve_status=='1')?'checked':'' }} required >
                          <label for="customSwitch1">อนุมัติ / Aproved (รับโอน)</label>

                      </div>
                    </div>
                        @ENDIF

                     <div class="col-md-4 mt-2">
                      <div class=" ">
              
                          <input type="radio" class="" id="customSwitch2" name="approve_status" value="5" {{ ( @$sRow->approve_status=='5')?'checked':'' }} required >
                          <label class="" for="customSwitch2">ไม่อนุมัติ / No Aproved (ปฏิเสธการรับโอน)</label>

                      </div>
                    </div>

                </div>

                <div class="form-group row">
                  <label for="note" class="col-md-4 col-form-label required_star_red ">หมายเหตุ :</label>
                  <div class="col-md-8">
                    <textarea class="form-control" rows="3" id="note2" name="note2" required >{{ @$sRow->note2 }}</textarea>
                  </div>
                </div>
                 <div class="form-group row">
                      <label for="" class="col-md-4 col-form-label">ผู้อนุมัติ (Admin Login) :</label>
                      <div class="col-md-6">
                        @if( empty(@$sRow->id) )
                          <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="approver" >
                            @else
                              <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ @$sRow->approver }}" name="approver" >
                         @endif
                          
                      </div>
                  </div>


                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                       <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/transfer_branch_get") }}">
                  <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                </a>
                  </div>
                  <div class="col-md-6 text-right">
                  @IF(@$sRow->approve_status=='')
                    <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 ">
                    <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึก
                    </button>
                  @ENDIF

                  </div>
                </div>

            </form>

          </div>

        </div>
    </div> <!-- end col -->
</div>
</div>
<!-- end row -->





<div class="modal fade" id="setToWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="setToWarehouseModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg " role="document" style="max-width: 800px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="setToWarehouseModalTitle"><b><i class="bx bx-play"></i>บันทึกการรับสินค้าและระบุคลังจัดเก็บสินค้า </b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{ route('backend.transfer_branch_get_products.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="save_set_to_warehouse" value="1" >
            <input type="hidden" id="this_id" name="id" >
            <input type="hidden" id="transfer_branch_get_products_id_fk" name="transfer_branch_get_products_id_fk" >
            <input type="hidden" id="transfer_branch_get_id_fk" name="transfer_branch_get_id_fk" value="{{@$sRow->id}}">
            <input type="hidden" id="product_id_fk" name="product_id_fk" >
            {{ csrf_field() }}

      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
               

              <div class="row" >
                <div class="col-md-12" style="font-size: 18px;" >
                  <div class="form-group row">
                    <label for="div_show_product" class="col-md-3 " style="vertical-align: top;"> สินค้า : </label>
                    <div class="col-md-9">
                      <div id="div_show_product" style="vertical-align: top;color: black;"></div>

                       <input type="hidden" class="form-control" id="lot_number" name="lot_number" readonly >
                       <input type="hidden" class="form-control" id="lot_expired_date" name="lot_expired_date" readonly >
                       <input type="hidden" class="form-control" id="product_unit_id_fk" name="product_unit_id_fk" readonly >
                       <input type="hidden" class="form-control" id="branch_id_fk_c" name="branch_id_fk_c" readonly >
                       <!-- บอกสถานะว่า มีการปฏิเสธการรับ จากฝั่งรับ  -->
                       <!-- <input type="text"  id="tr_status_get" name="tr_status_get" readonly > -->

                    </div>
                  </div>
                </div>
              </div>

              <hr>

               <div class="row" >
                    <div class="col-md-5 " >
                      <div class="form-group row">
                        <label for="" class="col-md-5 col-form-label required_star_red "> จำนวนที่ได้รับ : </label>
                        <div class="col-md-7">
                            <input type="text" class="form-control NumberOnly " name="amt_get" id="amt_get" required >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="" class="col-md-5 col-form-label">นำไปเก็บที่ => สาขา : </label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="branch_name" readonly >
                        </div>
                      </div>
                    </div>
                  </div>



                <div class="row" >
                    <div class="col-md-5 " >
                      <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label required_star_red "> คลัง : </label>
                            <div class="col-md-9">
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
                    <div class="col-md-6 " >
                      <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label required_star_red "> Zone : </label>
                            <div class="col-md-9">
                             <select id="zone_id_fk_c" name="zone_id_fk_c" class="form-control select2-templating " required >
                                <option disabled selected>กรุณาเลือกคลังก่อน</option>
                              </select>
                            </div>
                          </div>
                    </div>
                  </div>



                <div class="row" >
                    <div class="col-md-5 " >
                      <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label required_star_red "> Shelf : </label>
                            <div class="col-md-9">
                             <select id="shelf_id_fk_c"  name="shelf_id_fk_c" class="form-control select2-templating " required >
                                 <option disabled selected>กรุณาเลือกโซนก่อน</option>
                              </select>
                            </div>
                          </div>
                    </div>
                    <div class="col-md-6 " >
                      <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label required_star_red "> ชั้น : </label>
                            <div class="col-md-9">
                             <input type="text" class="form-control NumberOnly " id="shelf_floor_c" name="shelf_floor_c" placeholder="เก็บไว้ที่ชั้น" required >
                            </div>
                          </div>
                    </div>
                  </div>
<br>

         <div class="row">
          <div class="col-md-12 text-center  "  >
             <button type="submit" class="btn btn-primary" style="width: 10%;" onclick="return confirm('ยืนยันการทำรายการ');">Save</button>
             <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-left: 1%;">Close</button>
          </div>
        </div>


              </div>
            </div>
          </div>
        </div>


      </div>

      </form>

    </div>
  </div>
</div>

<div class="modal fade" id="product_repair" tabindex="-1" role="dialog" aria-labelledby="product_repairTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg " role="document" style="max-width: 800px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="product_repairTitle"><b><i class="bx bx-play"></i>บันทึกสินค้าชำรุด </b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{ url('backend/transfer_branch_get_products_defective') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="save_set_to_warehouse" value="1" >
            <input type="hidden" id="this_id2" name="id" >
            <input type="hidden" id="transfer_branch_get_products_id_fk2" name="transfer_branch_get_products_id_fk" >
            <input type="hidden" id="transfer_branch_get_id_fk2" name="transfer_branch_get_id_fk" value="{{@$sRow->id}}">
            <input type="hidden" id="product_id_fk2" name="product_id_fk" >
            {{ csrf_field() }}

      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
               

              <div class="row" >
                <div class="col-md-12" style="font-size: 18px;" >
                  <div class="form-group row">
                    <label for="div_show_product" class="col-md-3 " style="vertical-align: top;"> สินค้า : </label>
                    <div class="col-md-9">
                      <div id="div_show_product2" style="vertical-align: top;color: black;"></div>

                       <input type="hidden" class="form-control" id="lot_number2" name="lot_number" readonly >
                       <input type="hidden" class="form-control" id="lot_expired_date2" name="lot_expired_date" readonly >
                       <input type="hidden" class="form-control" id="product_unit_id_fk2" name="product_unit_id_fk" readonly >
                       <input type="hidden" class="form-control" id="branch_id_fk_c2" name="branch_id_fk_c" readonly >
                       <!-- บอกสถานะว่า มีการปฏิเสธการรับ จากฝั่งรับ  -->
                       <!-- <input type="text"  id="tr_status_get" name="tr_status_get" readonly > -->

                    </div>
                  </div>
                </div>
              </div>

              <hr>

               <div class="row" >
                    <div class="col-md-5 " >
                      <div class="form-group row">
                        <label for="" class="col-md-5 col-form-label required_star_red "> จำนวนที่ชำรุด : </label>
                        <div class="col-md-7">
                            <input type="text" class="form-control  " name="amt_get" id="amt_get2" required >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="" class="col-md-5 col-form-label">หมายเหตุ : </label>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="remark_repair" name="remark_repair">
                        </div>
                      </div>
                    </div>
                  </div>
<br>

         <div class="row">
          <div class="col-md-12 text-center  "  >
             <button type="submit" class="btn btn-primary" style="width: 10%;" onclick="return confirm('ยืนยันการทำรายการ');">Save</button>
             <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-left: 1%;">Close</button>
          </div>
        </div>


              </div>
            </div>
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


                function showPreview_01(ele)
                    {
                        $('#image').attr('src', ele.value); // for IE
                        if (ele.files && ele.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $('#imgAvatar_01').show();
                                $('#imgAvatar_01').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(ele.files[0]);
                    }
                }


        </script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" />

 <script>
      $('#created_at').datetimepicker({
          value: '',
          rtl: false,
          format: 'Y-m-d H:i',
          formatTime: 'H:i',
          formatDate: 'Y-m-d',
          monthChangeSpinner: true,
          closeOnTimeSelect: true,
          closeOnWithoutClick: true,
          closeOnInputClick: true,
          openOnFocus: true,
          timepicker: true,
          datepicker: true,
          weeks: false,
          minDate: 0,
          minView: 2, 
      });


</script>


  <script>

            var transfer_branch_get_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
            var oTable;

            $(function() {
                oTable = $('#data-table-01').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    scroller: true,
                    scrollCollapse: true,
                    scrollX: true,
                    ordering: false,
                    scrollY: ''+($(window).height()-370)+'px',
                    iDisplayLength: 100,
                    ajax: {
                            url: '{{ route('backend.transfer_branch_get_products.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['transfer_branch_get_id_fk'] = transfer_branch_get_id_fk ;
                                    oData = d;
                                  },
                              method: 'POST',
                            },
                 
                    columns: [
                        {data: 'id', title :'ID', className: 'text-center w50'},
                        {data: 'product_name', title :'รหัส : ชื่อสินค้า', className: 'text-left'},
                        {data: 'product_amt', title :'จำนวนตามใบโอน', className: 'text-center'},
                        {data: 'product_amt_receive', title :'จำนวนที่รับมาแล้ว', className: 'text-center'},
                        {data: 'defective', title :'จำนวนที่ชำรุด', className: 'text-center'},
                        {data: 'product_unit_desc', title :'หน่วยนับ', className: 'text-center'},
                        {data: 'get_status', title :'สถานะ', className: 'text-center'},
                        {data: 'defective_remark', title :'หมายเหตุ', className: 'text-center'},
                        {data: 'id', title :'Tools', className: 'text-center w180'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){

                      // console.log(aData['get_status_2']);
                      // console.log(aData['product_amt']);
                      // console.log(aData['product_amt_receive']);
                      // console.log(aData['product_details']);
                      // console.log(aData['tr_status']);

                      // if(aData['get_status_2']==2){
                      if(aData['product_amt']>aData['product_amt_receive']){

                        if(aData['get_status_2']==2){

                          if(aData['tr_status']>2){
                              $('td:last-child', nRow).html('-');
                          }else{

                          $('td:last-child', nRow).html(''
                            + '<a href="#" class="btn btn-sm btn-primary btnSetToWarehouse " data-id="'+aData['id']+'" product_name="'+aData['product_name']+'" product_id_fk="'+aData['product_id_fk']+'" product_details="'+aData['product_details']+'" branch_name="'+aData['branch_name']+'" branch_id_this="'+aData['branch_id_this']+'" lot_number="'+aData['lot_number']+'" lot_expired_date="'+aData['lot_expired_date']+'" product_unit_id_fk="'+aData['product_unit_id_fk']+'"  ><i class="bx bx-plus font-size-16 align-middle"></i> เพิ่มการรับ </a> '
                            + '<a href="#" class="btn btn-sm btn-danger btnSetToWarehouse_repair " data-id="'+aData['id']+'" product_name="'+aData['product_name']+'" product_id_fk="'+aData['product_id_fk']+'" product_details="'+aData['product_details']+'" branch_name="'+aData['branch_name']+'" branch_id_this="'+aData['branch_id_this']+'" lot_number="'+aData['lot_number']+'" lot_expired_date="'+aData['lot_expired_date']+'" product_unit_id_fk="'+aData['product_unit_id_fk']+'"  ><i class="bx bx-plus font-size-16 align-middle"></i> สินค้าชำรุด </a> '
                          ).addClass('input');
                        }

                        }else{
                          $('td:last-child', nRow).html('-');
                        }

                      }else{
                          $('td:last-child', nRow).html('-');
                      }


                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });


            </script>


  <script>

            // var transfer_branch_get_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
            var transfer_branch_get_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
            // console.log(transfer_branch_get_id_fk);
            var oTable2;

            $(function() {
                oTable2 = $('#data-table-02').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    scroller: true,
                    scrollCollapse: true,
                    scrollX: true,
                    ordering: false,
                    scrollY: ''+($(window).height()-370)+'px',
                    iDisplayLength: 100,
                    ajax: {
                        url: '{{ route('backend.transfer_branch_get_products_receive.datatable') }}',
                        data :{
                              transfer_branch_get_id_fk:transfer_branch_get_id_fk,
                            },
                          method: 'POST',
                        },
                    columns: [
                        {data: 'id', title :'<center>ID', className: 'text-center w50'},
                        {data: 'action_date', title :'<center>วันที่ได้รับสินค้า', className: 'text-center'},
                        {data: 'product_name', title :'<center>ชื่อสินค้า', className: 'text-center'},
                        {data: 'amt_get', title :'<center>จำนวนที่ได้รับ', className: 'text-center'},
                        {data: 'product_unit_desc', title :'หน่วยนับ', className: 'text-center'},
                        {data: 'warehouses', title :'สินค้าอยู่ที่', className: 'text-center'},
                        {data: 'id', title :'<center>Tools', className: 'text-center w80'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){

                      console.log(aData['tr_status_get']);

                          if(aData['tr_status_get']>2){
                              $('td:last-child', nRow).html('-');
                          }else{

                              $('td:last-child', nRow).html(''
                                  + '<a href="javascript: void(0);" data-url="{{ route('backend.transfer_branch_get.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                ).addClass('input');

                              }
                      }
                });
      
            });


            </script>


<script type="text/javascript">

       $('#business_location_id_fk').change(function(){

        $('.myloading').show();

          var business_location_id_fk = this.value;
          // alert(warehouse_id_fk);
           if(business_location_id_fk != ''){
             $.ajax({
                  url: " {{ url('backend/ajaxGetBranch') }} ",
                  method: "post",
                  data: {
                    business_location_id_fk:business_location_id_fk,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                   if(data == ''){
                       alert('ไม่พบข้อมูลสาขา !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือกสาขา -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.b_name+'</option>';
                       });
                       $('#branch_id_fk').html(layout);
                   }
                   $('.myloading').hide();
                  }
                })
           }

      });


     $(document).on('click', '.btnSetToWarehouse', function(event) {
          event.preventDefault();
              var id = $(this).data('id');
              var product_name = $(this).attr('product_name');
              var product_id_fk = $(this).attr('product_id_fk');
              var transfer_branch_get_products_id_fk = $(this).data('id');
              var product_details = $(this).attr('product_details');
              var branch_name = $(this).attr('branch_name');
              var branch_id_this = $(this).attr('branch_id_this');
              var product_unit_id_fk = $(this).attr('product_unit_id_fk');
              var lot_expired_date = $(this).attr('lot_expired_date');
              var lot_number = $(this).attr('lot_number');
              // console.log(product_id_fk);
              // console.log(product_name);
              // console.log(product_name);
              // var branch_id_fk = $("#branch_id_fk").val();
              $('#product_name').val(product_name);
              $('#product_id_fk').val(product_id_fk);
              $('#transfer_branch_get_products_id_fk').val(transfer_branch_get_products_id_fk);
              $('#this_id').val(transfer_branch_get_products_id_fk);

              $('#branch_id_fk_c').val(branch_id_this);
              $('#product_unit_id_fk').val(product_unit_id_fk);
              $('#lot_expired_date').val(lot_expired_date);
              $('#lot_number').val(lot_number);


              $('#div_show_product').html(product_details);
              $('#branch_name').val(branch_name);
            
             setTimeout(function(){
                 $('#amt_get').focus();
             }, 500);

             if(branch_id_this != ''){
               $.ajax({
                     url: " {{ url('backend/ajaxGetWarehouse') }} ", 
                    method: "post",
                    data: {
                      branch_id_fk:branch_id_this,
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

            
             $('#setToWarehouseModal').modal('show');

        });

        $(document).on('click', '.btnSetToWarehouse_repair', function(event) {
          event.preventDefault();
              var id = $(this).data('id');
              var product_name = $(this).attr('product_name');
              var product_id_fk = $(this).attr('product_id_fk');
              var transfer_branch_get_products_id_fk = $(this).data('id');
              var product_details = $(this).attr('product_details');
              var branch_name = $(this).attr('branch_name');
              var branch_id_this = $(this).attr('branch_id_this');
              var product_unit_id_fk = $(this).attr('product_unit_id_fk');
              var lot_expired_date = $(this).attr('lot_expired_date');
              var lot_number = $(this).attr('lot_number');
              // console.log(product_id_fk);
              // console.log(product_name);
              // console.log(product_name);
              // var branch_id_fk = $("#branch_id_fk").val();
              $('#product_name2').val(product_name);
              $('#product_id_fk2').val(product_id_fk);
              $('#transfer_branch_get_products_id_fk2').val(transfer_branch_get_products_id_fk);
              $('#this_id2').val(transfer_branch_get_products_id_fk);

              $('#branch_id_fk_c2').val(branch_id_this);
              $('#product_unit_id_fk2').val(product_unit_id_fk);
              $('#lot_expired_date2').val(lot_expired_date);
              $('#lot_number2').val(lot_number);


              $('#div_show_product2').html(product_details);
              $('#branch_name2').val(branch_name);
            
             setTimeout(function(){
                 $('#amt_get2').focus();
             }, 500);

             if(branch_id_this != ''){
               $.ajax({
                     url: " {{ url('backend/ajaxGetWarehouse') }} ", 
                    method: "post",
                    data: {
                      branch_id_fk:branch_id_this,
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
                         $('#warehouse_id_fk_c2').html(layout);
                         $('#zone_id_fk_c2').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                         $('#shelf_id_fk_c2').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                     }
                    }
                  })
             }else{
                $('#warehouse_id_fk_c2').html('<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
                $('#zone_id_fk_c2').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                $('#shelf_id_fk_c2').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
             }

            
             $('#product_repair').modal('show');

        });



       $('#warehouse_id_fk_c').change(function(){
          $('.myloading').show();
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
                       $('.myloading').hide();
                   }
                  }
                })
           }
 
      });


       $('#zone_id_fk_c').change(function(){
          $('.myloading').show();
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
                       $('.myloading').hide();
                   }
                  }
                })
           }
 
      });



       $('#shelf_id_fk_c').change(function(){
           setTimeout(function(){
            $('#shelf_floor_c').focus();
           })
       });


</script>

<!-- jQuery UI -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">

        $(document).ready(function() {

   // amt_get เช็คว่ากรอกเกินจำนวนที่สั่งซื้อหรือไม่ โดยหักออกจากครั้งก่อนๆ ที่ได้รับก่อนหน้าแล้วด้วยนะ
           $(document).on('change', '#amt_get', function(event) {

             $(".myloading").show();
            
             var amt_get = $(this).val();
             var transfer_branch_get_id_fk = $('#transfer_branch_get_id_fk').val();
             var product_id_fk = $('#product_id_fk').val();
             // console.log(amt_get);
             // console.log(transfer_branch_get_id_fk);
             // console.log(product_id_fk);
             // return false;
             $.ajax({
               url: " {{ url('backend/ajaxCheckAmt_get_transfer_branch_get_products') }} ", 
                method: "post",
                data: {
                amt_get:amt_get,
                transfer_branch_get_id_fk:transfer_branch_get_id_fk,
                product_id_fk:product_id_fk,
                  "_token": "{{ csrf_token() }}", 
                },
              success:function(data){
                 console.log(data);
                 if(data==1){
                   alert("! กรอกข้อมูลไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง");
                   $('#amt_get').val('');
                   $('#amt_get').focus();
                   $(".myloading").hide();
                   return false;
                 }else{
                  $(".myloading").hide();
                 }
               }
      
             });
       
           });

           $(document).on('change', '#amt_get2', function(event) {

                $(".myloading").show();

                var amt_get = $(this).val();
                var transfer_branch_get_id_fk = $('#transfer_branch_get_id_fk2').val();
                var product_id_fk = $('#product_id_fk2').val();
                // console.log(amt_get);
                // console.log(transfer_branch_get_id_fk);
                // console.log(product_id_fk);
                // return false;
                $.ajax({
                  url: " {{ url('backend/ajaxCheckAmt_get_transfer_branch_get_products') }} ", 
                  method: "post",
                  data: {
                  amt_get:amt_get,
                  transfer_branch_get_id_fk:transfer_branch_get_id_fk,
                  product_id_fk:product_id_fk,
                    "_token": "{{ csrf_token() }}", 
                  },
                success:function(data){
                    console.log(data);
                    if(data==1){
                      alert("! กรอกข้อมูลไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง");
                      $('#amt_get2').val('');
                      $('#amt_get2').focus();
                      $(".myloading").hide();
                      return false;
                    }else{
                    $(".myloading").hide();
                    }
                  }

                });

                });


            $(document).on('click', '.cDelete', function(event) {
            
                     setTimeout(function(){
                         // $('#data-table-01').DataTable().draw();
                         location.reload();
                      }, 1500);
       
           });

        
        });



</script>

@endsection
