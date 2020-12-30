@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

<style type="text/css">
  /* DivTable.com */
  .divTable{
    display: table;
    width: 100%;
    
  }
  .divTableRow {
    display: table-row;
  }
  .divTableHeading {
    background-color: #EEE;
    display: table-header-group;
  }
  .divTableCell, .divTableHead {
    border: 1px solid white;
    display: table-cell;
    padding: 3px 10px;
  }
  .divTableHeading {
    background-color: #EEE;
    display: table-header-group;
    font-weight: bold;
  }
  .divTableFoot {
    background-color: #EEE;
    display: table-footer-group;
    font-weight: bold;
  }
  .divTableBody {
    display: table-row-group;
  }
  .divTH {text-align: right;}


  .inline-group {
    max-width: 5rem;
    padding: .5rem;
  }

  .inline-group .form-control {
    text-align: right;
  }

  .form-control[type="number"]::-webkit-inner-spin-button,
  .form-control[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  .quantity {width: 50px;text-align: right;color: blue;font-weight: bold;font-size: 16px;}

  .btn-minus,.btn-plus {background-color: bisque !important;}

  input[type="text"]:disabled {
    background: #f2f2f2;
  }


</style>
@endsection

@section('content')


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


<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-10">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> จำหน่ายสินค้าหน้าร้าน > เปิดรายการขาย </h4>

                    <a class="btn btn-secondary btn-sm waves-effect btnBack " href="{{ url("backend/frontstore") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>

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
              <form action="{{ route('backend.frontstore.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.frontstore.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                      <div class="myBorder">

                          <div class="form-group row">
                            <label for="customers_id_fk" class="col-md-3 col-form-label"> รหัสลูกค้า : ชื่อลูกค้า : * </label>
                            <div class="col-md-9">
                              <select id="customers_id_fk" name="customers_id_fk" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                  @if(@$Customer)
                                    @foreach(@$Customer AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->customers_id_fk)?'selected':'' }} >
                                        {{$r->id}} : {{$r->prefix_name}}{{$r->first_name}} 
                                        {{$r->last_name}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="distribution_channel_id_fk" class="col-md-3 col-form-label"> ช่องทางการจำหน่าย : * </label>
                            <div class="col-md-9">
                              <select id="distribution_channel_id_fk" name="distribution_channel_id_fk" class="form-control select2-templating "  >
                                <option value="">Select</option>
                                    @if(@$sDistribution_channel)
                                      @foreach(@$sDistribution_channel AS $r)
                                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->distribution_channel_id_fk)?'selected':'' }}  >
                                          {{$r->txt_desc}} 
                                        </option>
                                      @endforeach
                                    @endif                                
                              </select>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="purchase_type_id_fk" class="col-md-3 col-form-label"> ประเภทการซื้อ : </label>
                            <div class="col-md-9">
                              <select id="purchase_type_id_fk" name="purchase_type_id_fk" class="form-control select2-templating "  >
                                <option value="">Select</option>
                                    @if(@$sPurchase_type)
                                      @foreach(@$sPurchase_type AS $r)
                                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->purchase_type_id_fk)?'selected':'' }} >
                                          {{$r->txt_desc}} 
                                        </option>
                                      @endforeach
                                    @endif
                              </select>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="pay_type_id_fk" class="col-md-3 col-form-label"> รูปแบบการชำระ : * </label>
                            <div class="col-md-9">
                              <select id="pay_type_id_fk" name="pay_type_id_fk" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                    @if(@$sPay_type)
                                      @foreach(@$sPay_type AS $r)
                                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->pay_type_id_fk)?'selected':'' }}  >
                                          {{$r->detail}} 
                                        </option>
                                      @endforeach
                                    @endif                                
                              </select>
                            </div>
                          </div>


                          <div class="form-group row">
                            <label for="note" class="col-md-3 col-form-label">หมายเหตุ :</label>
                            <div class="col-md-9">
                              <textarea class="form-control" id="note" name="note" rows="3">{{ @$sRow->note }}</textarea>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="tracking_type" class="col-md-3 col-form-label"> Tracking Type : </label>
                            <div class="col-md-9">
                              <!-- <select id="tracking_type"  name="tracking_type" class="form-control select2-templating " disabled="" >
                                <option value=""></option>
                              </select> -->
                              <input type="text" class="form-control" id="tracking_type" name="tracking_type" value="{{ @$sRow->tracking_type }}" disabled="" >
                            </div>
                          </div>

                           <div class="form-group row">
                            <label for="tracking_no" class="col-md-3 col-form-label">Tracking No. :</label>
                            <div class="col-md-9">
                              <input type="text" class="form-control" id="tracking_no" name="tracking_no" value="{{ @$sRow->tracking_no }}" disabled="" >
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">การแจ้งชำระ :</label>
                            <div class="col-md-9">
                              <input type="text" class="form-control" value="รอชำระเงิน" disabled="" >
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="" class="col-md-3 col-form-label">Currency :</label>
                            <div class="col-md-9">
                              <input type="text" class="form-control" value="THB" disabled="" >
                            </div>
                          </div>



                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect btnBack " href="{{ url("backend/frontstore") }}">
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
        </div>
    </div> <!-- end col -->

</div>
<!-- end row -->

<?php 
  if(empty(@$sRow)){
      $dis = 'display: none;';
  }else{
      $dis = '';
  }
 ?>
<div class="row" style="{{@$dis}}">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        <div class="myBorder">
          
          <div class="page-title-box d-flex justify-content-between ">
            <h4 class=" col-6 mb-0 font-size-18"><i class="bx bx-play"></i> รายการย่อย </h4>

            <a class="btn btn-info btn-sm mt-1 btnPrint " href="{{ URL::to('backend/frontstore/print_receipt') }}/1" target=_blank >
              <i class="bx bx-printer align-middle mr-1 font-size-18 "></i><span style="font-size: 14px;"> พิมพ์ใบเสร็จ</span>
            </a>

            <a class="btn btn-info btn-sm mt-1 btnAddFromPromotion " href="#" >
              <i class="bx bx-plus align-middle mr-1 font-size-18"></i><span style="font-size: 14px;">เพิ่มจากรหัสโปรโมชั่น</span>
            </a>

            <a class="btn btn-info btn-sm mt-1 btnAddFromProdutcsList " href="#" >
              <i class="bx bx-plus align-middle mr-1 font-size-18"></i><span style="font-size: 14px;">เพิ่มแบบ List</span>
            </a>

            <a class="btn btn-info btn-sm mt-1 btnAddList " href="#" >
              <i class="bx bx-plus align-middle mr-1 font-size-18"></i><span style="font-size: 14px;">เพิ่ม</span>
            </a>

          </div>

          <div class="form-group row">
            <div class="col-md-12">
              <table id="data-table-list" class="table table-bordered dt-responsive" style="width: 100%;">
              </table>
            </div>
          </div>



   
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="divTable">
          <div class="divTableBody">

              <div class="divTableRow">
                <div class="divTableCell" style="width: 60% !important ;">&nbsp; </div>
                <div class="divTH">
                  <label for="" >รวม : </label>
                </div>
                <div class="divTableCell">
                  <input  class="form-control" autocomplete="off" />
                </div>
                <div class="divTableCell">
                </div>
              </div>

             <div class="divTableRow">
                <div class="divTableCell" style="width: 60% !important ;">&nbsp; </div>
                <div class="divTH">
                  <label for="" >ภาษี 7.00 % : </label>
                </div>
                <div class="divTableCell">
                  <input class="form-control" autocomplete="off" />
                </div>
                <div class="divTableCell">
                </div>
              </div>

             <div class="divTableRow">
                <div class="divTableCell" style="width: 60% !important ;">&nbsp; </div>
                <div class="divTH">
                  <label for="" >รวมค่าสินค้า : </label>
                </div>
                <div class="divTableCell">
                  <input class="form-control" autocomplete="off" />
                </div>
                <div class="divTableCell">
                </div>
              </div>

             <div class="divTableRow">
                <div class="divTableCell" style="width: 60% !important ;">
                สถานที่จัดส่ง : <span style="color: red;">รับสินค้าด้วยตัวเอง / สาขา ศูนย์การขายสินค้า </span> </div>
                <div class="divTH">
                  <label for="" >ค่าจัดส่ง : </label>
                </div>
                <div class="divTableCell">
                  <input class="form-control" autocomplete="off" />
                </div>
                <div class="divTableCell">
                  <button type="button" class="btn btn-success btn-sm waves-effect font-size-14 btnDelivery ">
                    <i class="bx bx-edit font-size-16 align-middle mr-1"></i> การจัดส่ง
                    </button>
                </div>
              </div>



             <div class="divTableRow"> 
                <div class="divTableCell" style="width: 60% !important ;">
                การชำระ : </div>
                <div class="divTH">
                  <label for="" >รวมทั้งสิ้น : </label>
                </div>
                <div class="divTableCell">
                  <input class="form-control" autocomplete="off" />
                </div>
                <div class="divTableCell">
              
                </div>
              </div>


              <br>
              <br>

            <a class="btn btn-secondary btn-sm waves-effect btnBack " href="{{ url("backend/frontstore") }}">
            <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
            </a>

          </div>
        </div>
        <!-- DivTable.com -->
      </div>
    </div>
  </div>
</div>




        </div>

      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="modalAddFromPromotion" tabindex="-1" role="dialog" aria-labelledby="modalAddFromPromotionTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddFromPromotionTitle"><b><i class="bx bx-play"></i>รายการรหัสโปรโมชั่น</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

     
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body" >

                <div class="page-title-box d-flex">
                  <label for="" class="col-2" ><b>รหัสโปรโมชั่น : </b></label>
                  <input class="col-4 form-control " type="text" name="" value="" > &nbsp; &nbsp;
                  <button class="btn btn-success " >ตรวจสอบ</button>
                </div>

                <table id="data-table-promotion" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>


              </div>
            </div>
          </div>
        </div>


      </div>
           
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>



<div class="modal fade" id="modalAddFromProductsList" tabindex="-1" role="dialog" aria-labelledby="modalAddFromProductsListTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddFromProductsListTitle"><b><i class="bx bx-play"></i>รายการสินค้า</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

     
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body" >

                <div class=" d-flex " >
                  <button class="btn btn-success btnProductAll1 " data-value="1" style="margin-right: 1%;" >ทั้งหมด</button>
                  <button class="btn btn-success btnAihealth2 " data-value="2" style="margin-right: 1%;" >AiHealth</button>
                  <button class="btn btn-success btnAilada3 " data-value="3" style="margin-right: 1%;" >AiLADA</button>
                  <button class="btn btn-success btnAibody4 " data-value="4" style="margin-right: 1%;" >AiBODY</button>
                  <button class="btn btn-success btnPromotion8 " data-value="8" style="margin-right: 1%;" >Promotion</button>
                </div>

                <form id="frmFrontstorelist" action="{{ route('backend.frontstorelist.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                  <input name="frontstore_id" type="hidden" value="{{@$sRow->id}}">
                  <input name="product_plus" type="hidden" value="1">
                  {{ csrf_field() }}
                  <table id="data-table-products-list" class="table table-bordered dt-responsive" style="width: 100%;">
                  </table>
                </form>

              </div>
            </div>
          </div>
        </div>

       
      </div>
           
<!--         <div class="modal-footer">

          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div> -->

    </div>
  </div>
</div>





<div class="modal fade" id="modalDelivery" tabindex="-1" role="dialog" aria-labelledby="modalDeliveryTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDeliveryTitle"><b><i class="bx bx-play"></i>ข้อมูลการจัดส่งสินค้า</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     
      <div class="modal-body">

          <div class="col-12">
            <div class="card">
              <div class="card-body" >


                 <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> ประเภทการรับสินค้า : </label>
                  <div class="col-md-7">
                    <select name="" class="form-control select2-templating "  >
                      <option value="">Select</option>
                    </select>
                  </div>
                </div>


                 <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> ตัวเลือกที่อยู่จัดส่ง : </label>
                  <div class="col-md-7">
                    <select name="" class="form-control select2-templating "  >
                      <option value="">Select</option>
                    </select>
                  </div>
                </div>


                 <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> ชื่อ-นามสกุล : </label>
                  <div class="col-md-7">
                    <input type="text" name="" class="form-control" value="" placeholder="">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> ที่อยู่การจัดส่ง : </label>
                  <div class="col-md-7">
                    <textarea name="" class="form-control" rows="3"></textarea>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> ตำบล/แขวง : </label>
                  <div class="col-md-7">
                    <input type="text" name="" class="form-control" value="" placeholder="">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> อำเภอ/เขต : </label>
                  <div class="col-md-7">
                    <input type="text" name="" class="form-control" value="" placeholder="">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> จังหวัด : </label>
                  <div class="col-md-7">
                    <input type="text" name="" class="form-control" value="" placeholder="">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> รหัสไปรษณีย์ : </label>
                  <div class="col-md-7">
                    <input type="text" name="" class="form-control" value="" placeholder="">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> โทร : </label>
                  <div class="col-md-7">
                    <input type="text" name="" class="form-control" value="" placeholder="">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> เบอร์มือถือ : </label>
                  <div class="col-md-7">
                    <input type="text" name="" class="form-control" value="" placeholder="">
                  </div>
                </div>


              </div>
            </div>
          </div>


      </div>
           
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="bx bx-save font-size-16 align-middle "></i> Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>




<div class="modal fade" id="modalAddList" tabindex="-1" role="dialog" aria-labelledby="modalAddListTitle" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered modal-lg " role="document" style="max-width: 50% !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddListTitle"><b><i class="bx bx-play"></i>เพิ่มรายการย่อย</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     
      <div class="modal-body">

        
              <div class="card-body" >


            <!--     <iframe id="main" src="http://localhost/aiyara.host/backend/frontstore/2/edit" width=420 height=250 marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=1 scrolling="yes"></iframe> -->


            <form id="frmFrontstoreAddList" action="{{ url('backend/frontstorelist/plus') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                  <input class="product_id_fk_this" name="product_id_fk_this" type="hidden" >
                  <input class="frontstore_id"  name="frontstore_id" type="hidden" value="{{@$sRow->id}}">
                  <input name="product_plus" type="hidden" value="1">
                  <input name="product_plus_addlist" type="hidden" value="1">
                  {{ csrf_field() }}


                 <div class="form-group row">
                  <label for="" class="col-md-3 col-form-label"> <b>ค้นหาสินค้า :</b> </label>
                  <div class="col-md-8">
                    <!-- <input type="text" id="txtSearch" name="" class="form-control" value="" placeholder="กรอกคำค้น รหัสสินหค้า หรือ ชื่อสินค้า" > -->
                     <select name="product_id_fk[]" id="product_id_fk" class="form-control select2-templating "  >
                                <option value="">-ค้น-</option>
                                   @if(@$Products)
                                        @foreach(@$Products AS $r)
                                          <option value="{{@$r->product_id}}" >
                                            {{@$r->product_code." : ".@$r->product_name}}
                                          </option>
                                        @endforeach
                                      @endif
                              </select>
                  </div>
                </div>

                 <div class="form-group row">
                  <label for="" class="col-md-3 col-form-label"> <b>สินค้า :</b> </label>
                  <div class="col-md-8">
                    <div id="show_product">
                       <textarea class="form-control" rows="5" disabled style="text-align: left !important;background: #f2f2f2;" ></textarea>
                    </div>
                  </div>
                </div>

                 <div class="form-group row">
                  <label for="" class="col-md-3 col-form-label"> <b>จำนวน :</b> </label>
                  <div class="col-md-8">
                    <input type="number" name="quantity[]" id="amt" class="form-control" value="" required="">
                  </div>
                </div>  

                 <div class="form-group row">
                  <label for="" class="col-md-3 col-form-label"> </label>
                  <div class="col-md-8 text-right ">
                    <button type="submit" class="btn btn-primary btnSaveAddlist "><i class="bx bx-save font-size-16 align-middle "></i> Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>  

           </form>


              </div>

      </div>
           
    </div>
  </div>
</div>


@endsection

@section('script')



<script type="text/javascript">


            $.fn.dataTable.ext.errMode = 'throw';
  
            var frontstore_id_fk = "{{@$sRow->id}}"; //alert(frontstore_id_fk);
            var oTable;

            $(function() {
                oTable = $('#data-table-list').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    scroller: true,
                    scrollCollapse: true,
                    scrollX: true,
                    ordering: false,
                    "info":     false,
                    "paging":   false,
                    scrollY: ''+($(window).height()-370)+'px',
                    iDisplayLength: 5,
                    ajax: {
                        url: '{{ route('backend.frontstorelist.datatable') }}',
                        data :{
                              frontstore_id_fk:frontstore_id_fk,
                            },
                          method: 'POST',
                        },
                    columns: [
                        {data: 'id',   title :'<center>#</center>', className: 'text-center w50 ',render: function(d) {
                           return d ;
                        }},
                        {data: 'purchase_type',   title :'<center>ประเภท</center>', className: 'text-center',render: function(d) {
                           return d;
                        }},
                        {data: 'product_id_fk',   title :'<center>รหัสสินค้า</center>', className: 'text-center',render: function(d) {
                           return d ;
                        }},
                        {data: 'product_name',   title :'<center>ชื่อสินค้า/บริการ</center>', className: 'text-left',render: function(d) {
                           return d;
                        }},
                        {data: 'amt',   title :'<center>จำนวน</center>', className: 'text-center',render: function(d) {
                           return d;
                        }},
                        
                        {data: 'product_unit', title :'<center>หน่วย </center>', className: 'text-center'},

                        {data: 'pv',   title :'<center>PV</center>', className: 'text-center w50 ',render: function(d) {
                           return d;
                        }},
                        {data: 'selling_price',   title :'<center>ราคาขาย</center>', className: 'text-center',render: function(d) {
                           return d;
                        }},
                        {data: 'total_pv',   title :'<center>รวม PV</center>', className: 'text-center',render: function(d) {
                           return d;
                        }},
                        {data: 'total_price',   title :'<center>รวม</center>', className: 'text-center',render: function(d) {
                           return d;
                        }},
    
                        {data: 'id', title :'Tools', className: 'text-center w60'}, 
                    ],
                     'columnDefs': [
                     {
                            'targets': 0,
                            'checkboxes': {
                               'selectRow': true
                            }
                         }
                      ],
                      'select': {
                         'style': 'multi'
                      },
                    rowCallback: function(nRow, aData, dataIndex){
                      $('td:last-child', nRow).html(''
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstorelist.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });

            });
              

</script>

<script type="text/javascript">

    $(document).ready(function() {
        $(document).on('click', '.btnAddFromPromotion', function(event) {
          event.preventDefault();

          $.fn.dataTable.ext.errMode = 'throw';

            $("#spinner_frame").show();

            var oTable;
            $(function() {

                oTable = $('#data-table-promotion').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    "info":     false,
                    destroy: true,
                    searching: false,
                    paging: false,
                    ajax: {
                      url: '{{ route('backend.check_stock.datatable') }}',
                      data: function ( d ) {
                        d.Where={};
                        d.Where['branch_id_fk'] = 1 ;
                        d.Where['product_id_fk'] = 1 ;
                        oData = d;
                      },
                      method: 'POST'
                    },
                    columns: [
                        {data: 'id',   title :'<center>รูป</center>', className: 'text-center w100 ',render: function(d) {
                           return '' ;
                        }},
                        {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left'},
                        {data: 'id',   title :'<center>PV</center>', className: 'text-center',render: function(d) {
                           return '' ;
                        }},
                        {data: 'id',   title :'<center>ราคา</center>', className: 'text-center',render: function(d) {
                           return '' ;
                        }},
                        {data: 'amt',   title :'<center>จำนวน</center>', className: 'w140 ',render: function(d) {
                           return '<div class="input-group inline-group"> '
                                +' <div class="input-group-prepend"> '
                                +'   <button class="btn btn-outline-secondary btn-minus"> '
                                +'     <i class="fa fa-minus"></i> '
                                +'   </button>'
                                +'   <input class=" quantity " min="0" name="quantity" value="0" type="number" >'
                                +'   <div class="input-group-append"> '
                                +'   <button class="btn btn-outline-secondary btn-plus"> '
                                +'     <i class="fa fa-plus"></i> '
                                +'    </button> '
                                +'  </div> '
                                +' </div> ' ;
                        }},
                    ],

                });
              
            });

                  $('#modalAddFromPromotion').modal('show');
          
        });


            $('#modalAddFromPromotion').on('focus', function () {
                $("#spinner_frame").hide();
            });

      });




    $(document).ready(function() {
        $(document).on('click', '.btnAddFromProdutcsList', function(event) {
          event.preventDefault();

          $.fn.dataTable.ext.errMode = 'throw';

          var frontstore_id_fk = "{{@$sRow->id}}"; 
          // alert(frontstore_id_fk);

            $("#spinner_frame").show();

            var oTable;
            $(function() {

                oTable = $('#data-table-products-list').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    "info":  false,
                    destroy: true,
                    searching: false,
                    paging: true,
                    scrollY: ''+($(window).height()-370)+'px',
                    iDisplayLength: 25,                    
                    ajax: {
                      url: '{{ route('backend.productsList.datatable') }}',
                      data :{
                              frontstore_id_fk:frontstore_id_fk,
                            },
                          method: 'POST',
                        },
                    columns: [
                        {data: 'p_img',   title :'<center>รูป</center>', className: 'text-center w100 ',render: function(d) {
                           return '<img src='+d+' width="80">';
                        }},
                        {data: 'pn', title :'<center>ชื่อสินค้า </center>', className: 'text-left'},
                        {data: 'pv', title :'<center>PV </center>', className: 'text-left w50 '},
                        {data: 'price',   title :'<center>ราคา</center>', className: 'text-center',render: function(d) {
                           return d + "&#3647";
                        }},
                        {data: 'id',   title :'<center>จำนวน</center>', className: 'w140 ',render: function(d) {

                           return '<input name="product_id_fk[]" value="'+d+'" type="hidden" ><div class="input-group inline-group"> '
                                +' <div class="input-group-prepend"> '
                                +'   <button class="btn btn-outline-secondary btn-minus"> '
                                +'     <i class="fa fa-minus"></i> '
                                +'   </button>'
                                +'   <input class=" quantity " min="0" name="quantity[]" value="0" type="number" readonly >'
                                +'   <div class="input-group-append"> '
                                +'   <button data-product_id_fk="'+d+'" class="btn btn-outline-secondary btn-plus"> '
                                +'     <i class="fa fa-plus"></i> '
                                +'    </button> '
                                +'  </div> '
                                +' </div> ' ;
                        }},
                    ],
                    rowCallback: function(nRow, aData, dataIndex){

                          if (aData['frontstore_products_list'] > 0 ) {

                              $("td:eq(4)", nRow).html(
                                '<input name="product_id_fk[]" value="'+aData['id']+'" type="hidden" ><div class="input-group inline-group"> '
                                +' <div class="input-group-prepend"> '
                                +'   <button class="btn btn-outline-secondary btn-minus"> '
                                +'     <i class="fa fa-minus"></i> '
                                +'   </button>'
                                +'   <input class=" quantity " min="0" name="quantity[]" value="'+aData['frontstore_products_list']+'" type="number" readonly >'
                                +'   <div class="input-group-append"> '
                                +'   <button data-product_id_fk="'+aData['id']+'" class="btn btn-outline-secondary btn-plus"> '
                                +'     <i class="fa fa-plus"></i> '
                                +'    </button> '
                                +'  </div> '
                                +' </div> '
                                );

                          }
                        
                      }


                });
              
            });

                  $('#modalAddFromProductsList').modal('show');
          
        });


            $('#modalAddFromProductsList').on('focus', function () {
                $("#spinner_frame").hide();
            });


            $(document).on('click', '.btnDelivery', function(event) {
                event.preventDefault();
                $('#modalDelivery').modal('show');
            });


            $(document).on('click', '.btnAddList', function(event) {
                event.preventDefault();
                $('#modalAddList').modal('show');
            });

            $('#modalAddList').on('shown.bs.modal', function () {
                $('#txtSearch').focus();
            })  


      });


    $(document).ready(function() {


        $(document).on('click', '.btn-plus, .btn-minus', function(e) {
        event.preventDefault();
          const isNegative = $(e.target).closest('.btn-minus').is('.btn-minus');
          const input = $(e.target).closest('.input-group').find('input');
          if (input.is('input')) {
            input[0][isNegative ? 'stepDown' : 'stepUp']()
          }
        })


        $(document).on('change', '#customers_id_fk', function(event) {
            event.preventDefault();
            var id = $(this).val();
            localStorage.setItem('customers_id_fk', id);
        });

        if(localStorage.getItem('customers_id_fk')){
            $('#customers_id_fk').val(localStorage.getItem('customers_id_fk')).select2();
        }

        $(document).on('change', '#distribution_channel_id_fk', function(event) {
            event.preventDefault();
            var id = $(this).val();
            localStorage.setItem('distribution_channel_id_fk', id);
        });

        if(localStorage.getItem('distribution_channel_id_fk')){
            $('#distribution_channel_id_fk').val(localStorage.getItem('distribution_channel_id_fk')).select2();
        }

        $(document).on('change', '#purchase_type_id_fk', function(event) {
            event.preventDefault();
            var id = $(this).val();
            localStorage.setItem('purchase_type_id_fk', id);
        });

        if(localStorage.getItem('purchase_type_id_fk')){
            $('#purchase_type_id_fk').val(localStorage.getItem('purchase_type_id_fk')).select2();
        }

        $(document).on('change', '#pay_type_id_fk', function(event) {
            event.preventDefault();
            var id = $(this).val();
            localStorage.setItem('pay_type_id_fk', id);
        });

        if(localStorage.getItem('pay_type_id_fk')){
            $('#pay_type_id_fk').val(localStorage.getItem('pay_type_id_fk')).select2();
        }

        $(document).on('change', '#pay_type_id_fk', function(event) {
            event.preventDefault();
            var id = $(this).val();
            localStorage.setItem('pay_type_id_fk', id);
        });

        if(localStorage.getItem('pay_type_id_fk')){
            $('#pay_type_id_fk').val(localStorage.getItem('pay_type_id_fk')).select2();
        }
                                
        $('#note').change(function() {
            localStorage.setItem('note', this.value);
        });

        if(localStorage.getItem('note')){
          $('#note').val(localStorage.getItem('note'));
        }


        $(document).ready(function() {
            $(document).on('click', '.btnBack', function(event) {
              localStorage.clear();
            });
        });




        $(document).on('click', '.btn-plus', function(e) {
          // event.preventDefault();
          $.fn.dataTable.ext.errMode = 'throw';

          const input = $(e.target).closest('.input-group').find('input');
          if (input.is('input')) {
             // alert(input.val());
             // frmFrontstorelist
             // $("#frmFrontstorelist").submit();
           var d =  $("#frmFrontstorelist").serialize();
           var product_id_fk_this =  $(this).data('product_id_fk');

           var frontstore_id_fk = "{{@$sRow->id}}"; //alert(frontstore_id_fk);

           // alert(product_id_fk_this);

            $.ajax({
               type:'POST',
               url: " {{ url('backend/frontstorelist/plus') }} ", 
               // data:{ d:d , _token: '{{csrf_token()}}' },
               data: $("#frmFrontstorelist").serialize()+"&product_id_fk_this="+product_id_fk_this,
                success: function(response){ // What to do if we succeed
                       console.log(response); 
                        var oTable;

                          $(function() {
                              oTable = $('#data-table-list').DataTable({
                              "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                  processing: true,
                                  serverSide: true,
                                  scroller: true,
                                  scrollCollapse: true,
                                  scrollX: true,
                                  ordering: false,
                                  "info":     false,
                                  "paging":   false,
                                  destroy: true,
                                  scrollY: ''+($(window).height()-370)+'px',
                                  iDisplayLength: 5,
                                  ajax: {
                                      url: '{{ route('backend.frontstorelist.datatable') }}',
                                      data :{
                                            frontstore_id_fk:frontstore_id_fk,
                                          },
                                        method: 'POST',
                                      },
                                  columns: [
                                      {data: 'id',   title :'<center>#</center>', className: 'text-center w50 ',render: function(d) {
                                         return d ;
                                      }},
                                      {data: 'purchase_type',   title :'<center>ประเภท</center>', className: 'text-center',render: function(d) {
                                         return d;
                                      }},
                                      {data: 'product_id_fk',   title :'<center>รหัสสินค้า</center>', className: 'text-center',render: function(d) {
                                         return d ;
                                      }},
                                      {data: 'product_name',   title :'<center>ชื่อสินค้า/บริการ</center>', className: 'text-left',render: function(d) {
                                         return d;
                                      }},
                                      {data: 'amt',   title :'<center>จำนวน</center>', className: 'text-center',render: function(d) {
                                         return d;
                                      }},
                                      {data: 'product_unit',   title :'<center>หน่วย</center>', className: 'text-center',render: function(d) {
                                         return d;
                                      }},
                                      {data: 'pv',   title :'<center>PV</center>', className: 'text-center w50 ',render: function(d) {
                                         return d;
                                      }},
                                      {data: 'selling_price',   title :'<center>ราคาขาย</center>', className: 'text-center',render: function(d) {
                                         return d;
                                      }},
                                      {data: 'total_pv',   title :'<center>รวม PV</center>', className: 'text-center',render: function(d) {
                                         return d;
                                      }},
                                      {data: 'total_price',   title :'<center>รวม</center>', className: 'text-center',render: function(d) {
                                         return d;
                                      }},
                  
                                      {data: 'id', title :'Tools', className: 'text-center w60'}, 
                                  ],
                                   'columnDefs': [
                                   {
                                          'targets': 0,
                                          'checkboxes': {
                                             'selectRow': true
                                          }
                                       }
                                    ],
                                    'select': {
                                       'style': 'multi'
                                    },
                                  rowCallback: function(nRow, aData, dataIndex){
                                    $('td:last-child', nRow).html(''
                                      + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstorelist.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                    ).addClass('input');
                                  }
                              });
                              $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                                oTable.draw();
                              });

                          });
                            

                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });

          }
        })


        $(document).on('click', '.btn-minus', function(e) {
          // event.preventDefault();
          $.fn.dataTable.ext.errMode = 'throw';

          const input = $(e.target).closest('.input-group').find('input');
          if (input.is('input')) {
             // alert(input.val());
             // frmFrontstorelist
             // $("#frmFrontstorelist").submit();
           var d =  $("#frmFrontstorelist").serialize();
           var product_id_fk_this =  $(this).data('product_id_fk');

           var frontstore_id_fk = "{{@$sRow->id}}"; //alert(frontstore_id_fk);

           // alert(product_id_fk_this);

            $.ajax({
               type:'POST',
               url: " {{ url('backend/frontstorelist/minus') }} ", 
               // data:{ d:d , _token: '{{csrf_token()}}' },
               data: $("#frmFrontstorelist").serialize()+"&product_id_fk_this="+product_id_fk_this,
                success: function(response){ // What to do if we succeed
                       console.log(response); 

                        var oTable;

                            $(function() {
                                oTable = $('#data-table-list').DataTable({
                                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                    processing: true,
                                    serverSide: true,
                                    scroller: true,
                                    scrollCollapse: true,
                                    scrollX: true,
                                    ordering: false,
                                    "info":     false,
                                    "paging":   false,
                                    destroy: true,
                                    scrollY: ''+($(window).height()-370)+'px',
                                    iDisplayLength: 5,
                                      ajax: {
                                          url: '{{ route('backend.frontstorelist.datatable') }}',
                                          data :{
                                                frontstore_id_fk:frontstore_id_fk,
                                              },
                                            method: 'POST',
                                          },
                                    columns: [
                                        {data: 'id',   title :'<center>#</center>', className: 'text-center w50 ',render: function(d) {
                                           return d ;
                                        }},
                                        {data: 'purchase_type',   title :'<center>ประเภท</center>', className: 'text-center',render: function(d) {
                                           return d;
                                        }},
                                        {data: 'product_id_fk',   title :'<center>รหัสสินค้า</center>', className: 'text-center',render: function(d) {
                                           return d ;
                                        }},
                                        {data: 'product_name',   title :'<center>ชื่อสินค้า/บริการ</center>', className: 'text-left',render: function(d) {
                                           return d;
                                        }},
                                        {data: 'amt',   title :'<center>จำนวน</center>', className: 'text-center',render: function(d) {
                                           return d;
                                        }},
                                        {data: 'product_unit',   title :'<center>หน่วย</center>', className: 'text-center',render: function(d) {
                                           return d;
                                        }},
                                        {data: 'pv',   title :'<center>PV</center>', className: 'text-center w50 ',render: function(d) {
                                           return d;
                                        }},
                                        {data: 'selling_price',   title :'<center>ราคาขาย</center>', className: 'text-center',render: function(d) {
                                           return d;
                                        }},
                                        {data: 'total_pv',   title :'<center>รวม PV</center>', className: 'text-center',render: function(d) {
                                           return d;
                                        }},
                                        {data: 'total_price',   title :'<center>รวม</center>', className: 'text-center',render: function(d) {
                                           return d;
                                        }},
                    
                                        {data: 'id', title :'Tools', className: 'text-center w60'}, 
                                    ],
                                     'columnDefs': [
                                     {
                                            'targets': 0,
                                            'checkboxes': {
                                               'selectRow': true
                                            }
                                         }
                                      ],
                                      'select': {
                                         'style': 'multi'
                                      },
                                    rowCallback: function(nRow, aData, dataIndex){
                                      $('td:last-child', nRow).html(''
                                        + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstorelist.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                      ).addClass('input');
                                    }
                                });
                                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                                  oTable.draw();
                                });

                            });
                              


                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });

          }
        });





    });


// $(function() {

//   $(document).on('click', 'input[type=number]', function(e) {
//     e.preventDefault();
//     alert("xxxx");
//     /* Act on the event */
//     $(e.target).closest('.input-group').find('input').focus();
//     // $(this).next(".btn-plus").click();
//   });

// $('input[type="text"], input[type=number], input[type=date], input[type="tel"], input[type=password], input[type="email"], input[type="url"], textarea, select').live("click", function(event) {
//         event.stopPropagation();
//     });

// });




    $(document).ready(function() {
        $(document).on('click', '.btnProductAll1,.btnAihealth2,.btnAilada3,.btnAibody4,.btnPromotion8', function(event) {
          event.preventDefault();

          var table = $('#data-table-products-list').DataTable();
          table.clear().draw();

          var category_id = $(this).data('value');

          $("#spinner_frame").show();

          $.fn.dataTable.ext.errMode = 'throw';

          var frontstore_id_fk = "{{@$sRow->id}}"; 
          // alert(frontstore_id_fk);

            var oTable;
            $(function() {

                oTable = $('#data-table-products-list').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    "info":  false,
                    destroy: true,
                    searching: false,
                    paging: true,
                    scrollX: false,
                    scrollCollapse: false,
                    scrollY: ''+($(window).height()-370)+'px',
                    iDisplayLength: 25,                    
                    ajax: {
                      url: '{{ route('backend.productsList.datatable') }}',
                      data :{
                              frontstore_id_fk:frontstore_id_fk,
                              category_id: category_id ,
                            },
                          method: 'POST',
                        },
                    columns: [
                        {data: 'p_img',   title :'<center>รูป</center>', className: 'text-center w100 ',render: function(d) {
                           return '<img src='+d+' width="80">';
                        }},
                        {data: 'pn', title :'<center>ชื่อสินค้า </center>', className: 'text-left'},
                        {data: 'pv', title :'<center>PV </center>', className: 'text-left w50 '},
                        {data: 'price',   title :'<center>ราคา</center>', className: 'text-center',render: function(d) {
                           return d + "&#3647";
                        }},
                        {data: 'id',   title :'<center>จำนวน</center>', className: 'w140 ',render: function(d) {

                           return '<input name="product_id_fk[]" value="'+d+'" type="hidden" ><div class="input-group inline-group"> '
                                +' <div class="input-group-prepend"> '
                                +'   <button class="btn btn-outline-secondary btn-minus"> '
                                +'     <i class="fa fa-minus"></i> '
                                +'   </button>'
                                +'   <input class=" quantity " min="0" name="quantity[]" value="0" type="number" readonly >'
                                +'   <div class="input-group-append"> '
                                +'   <button data-product_id_fk="'+d+'" class="btn btn-outline-secondary btn-plus"> '
                                +'     <i class="fa fa-plus"></i> '
                                +'    </button> '
                                +'  </div> '
                                +' </div> ' ;
                        }},
                    ],
                    rowCallback: function(nRow, aData, dataIndex){

                          if (aData['frontstore_products_list'] > 0 ) {

                              $("td:eq(4)", nRow).html(
                                '<input name="product_id_fk[]" value="'+aData['id']+'" type="hidden" ><div class="input-group inline-group"> '
                                +' <div class="input-group-prepend"> '
                                +'   <button class="btn btn-outline-secondary btn-minus"> '
                                +'     <i class="fa fa-minus"></i> '
                                +'   </button>'
                                +'   <input class=" quantity " min="0" name="quantity[]" value="'+aData['frontstore_products_list']+'" type="number" readonly >'
                                +'   <div class="input-group-append"> '
                                +'   <button data-product_id_fk="'+aData['id']+'" class="btn btn-outline-secondary btn-plus"> '
                                +'     <i class="fa fa-plus"></i> '
                                +'    </button> '
                                +'  </div> '
                                +' </div> '
                                );

                          }
                        
                      }


                });
           
                setTimeout(function(){
                   $('#modalAddFromProductsList').focus();
                }, 500);  

                $('#modalAddFromProductsList').on('focus', function () {
                    $("#spinner_frame").hide();
                });
          
            });

        });
     });




$(document).ready(function() {


              $(document).on('change', '#product_id_fk', function(event) {

                      event.preventDefault();
                      var product_id_fk = $(this).val();
                      var frontstore_id = $(".frontstore_id").val();
                      // alert(frontstore_id);
                      // return false;
                      $(".product_id_fk_this").val(product_id_fk);

                      $.ajax({
                       type:'POST',
                       url: " {{ url('backend/ajaxGetProduct') }} ", 
                       data:{ _token: '{{csrf_token()}}',product_id_fk:product_id_fk,frontstore_id:frontstore_id },
                        success:function(data){
                             // console.log(data); 
                             // location.reload();
                             $('#show_product').html(data);
                             // $('#modalAddList').modal('show');
                              
                              $('#amt').val($('#p_amt').val());
                              $('#amt').focus().select();

                          },
                        error: function(jqXHR, textStatus, errorThrown) { 
                            console.log(JSON.stringify(jqXHR));
                            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                            $(".myloading").hide();
                        }
                    });

                });


        $("#modalAddList").on("hidden.bs.modal", function(){
            $("#frmFrontstoreAddList").find("input[type=number], textarea").val("");
            $("#product_id_fk").select2('destroy').val("").select2();
        });



        $(document).on('change', '#amt', function(event) {
            event.preventDefault();
            // $('.btnSaveAddlist ').focus();
            $("#frmFrontstoreAddList").submit();
        });



        // $(document).on('click', '.btnSaveAddlist', function(event) {
        //     event.preventDefault();
        //     $("#frmFrontstoreAddList").submit();
        // });

      // $(document).on('change', '#product_id_fk', function(event) {
      //     $('#amt ').focus();
      // });

      // $('#product_id_fk').keypress(function(event){
      //     var keycode = (event.keyCode ? event.keyCode : event.which);
      //     if(keycode == '13'){
      //       $('#amt ').focus();
      //     }
      // });


});


</script>

       <script type='text/javascript'>
         $(document).ready(function() {
            $("#frmFrontstoreAddList").submit(function(e){
                // alert('submit intercepted');
                e.preventDefault(e);

                var frontstore_id_fk = "{{@$sRow->id}}"; //alert(frontstore_id_fk);

                     $.ajax({
                         type:'POST',
                         url: " {{ url('backend/frontstorelist/plus') }} ", 
                         // data:{ d:d , _token: '{{csrf_token()}}' },
                          data: $("#frmFrontstoreAddList").serialize(),
                          success: function(response){ // What to do if we succeed
                                 console.log(response); 
                                  var oTable;

                                    $(function() {
                                        oTable = $('#data-table-list').DataTable({
                                        "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                            processing: true,
                                            serverSide: true,
                                            scroller: true,
                                            scrollCollapse: true,
                                            scrollX: true,
                                            ordering: false,
                                            "info":     false,
                                            "paging":   false,
                                            destroy: true,
                                            scrollY: ''+($(window).height()-370)+'px',
                                            iDisplayLength: 5,
                                            ajax: {
                                                url: '{{ route('backend.frontstorelist.datatable') }}',
                                                data :{
                                                      frontstore_id_fk:frontstore_id_fk,
                                                      frontstore_id:frontstore_id_fk,
                                                    },
                                                  method: 'POST',
                                                },
                                            columns: [
                                                {data: 'id',   title :'<center>#</center>', className: 'text-center w50 ',render: function(d) {
                                                   return d ;
                                                }},
                                                {data: 'purchase_type',   title :'<center>ประเภท</center>', className: 'text-center',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'product_id_fk',   title :'<center>รหัสสินค้า</center>', className: 'text-center',render: function(d) {
                                                   return d ;
                                                }},
                                                {data: 'product_name',   title :'<center>ชื่อสินค้า/บริการ</center>', className: 'text-left',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'amt',   title :'<center>จำนวน</center>', className: 'text-center',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'product_unit',   title :'<center>หน่วย</center>', className: 'text-center',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'pv',   title :'<center>PV</center>', className: 'text-center w50 ',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'selling_price',   title :'<center>ราคาขาย</center>', className: 'text-center',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'total_pv',   title :'<center>รวม PV</center>', className: 'text-center',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'total_price',   title :'<center>รวม</center>', className: 'text-center',render: function(d) {
                                                   return d;
                                                }},
                            
                                                {data: 'id', title :'Tools', className: 'text-center w60'}, 
                                            ],
                                             'columnDefs': [
                                             {
                                                    'targets': 0,
                                                    'checkboxes': {
                                                       'selectRow': true
                                                    }
                                                 }
                                              ],
                                              'select': {
                                                 'style': 'multi'
                                              },
                                            rowCallback: function(nRow, aData, dataIndex){
                                              $('td:last-child', nRow).html(''
                                                + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstorelist.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                              ).addClass('input');
                                            }
                                        });
                                    });
                                      
                              },
                              error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                  console.log(JSON.stringify(jqXHR));
                                  console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                              }
                          });

            });
        });
        </script>

          <script type="text/javascript">
            $(document).ready(function() {
                
                  // $('.btnDelivery').trigger('click');
                  // $('.btnAddFromPromotion').trigger('click');
                  // $('#modalAddFromPromotion').modal('show');
                  // $('#modalAddFromProductsList').modal('show');
                  // $('.btnAddFromProdutcsList ').trigger('click');

                   // $('.btnAddList ').trigger('click');


                   $(document).on('click', '.cDelete', function(event) {
                       event.preventDefault();
                       // alert("xxxxx");

                        var table = $('#data-table-list').DataTable();
                        table.clear().draw();

                        var category_id = $(this).data('value');

                        $("#spinner_frame").show();

                        $.fn.dataTable.ext.errMode = 'throw';


                        setTimeout(function(){
                          $("#spinner_frame").hide();
                          // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                          var frontstore_id_fk = "{{@$sRow->id}}"; //alert(frontstore_id_fk);
                          var oTable;

                                    $(function() {
                                        oTable = $('#data-table-list').DataTable({
                                        "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                            processing: true,
                                            serverSide: true,
                                            scroller: true,
                                            scrollCollapse: true,
                                            scrollX: true,
                                            ordering: false,
                                            "info":     false,
                                            "paging":   false,
                                            destroy: true,
                                            scrollY: ''+($(window).height()-370)+'px',
                                            iDisplayLength: 5,
                                            ajax: {
                                                url: '{{ route('backend.frontstorelist.datatable') }}',
                                                data :{
                                                      frontstore_id_fk:frontstore_id_fk,
                                                      frontstore_id:frontstore_id_fk,
                                                    },
                                                  method: 'POST',
                                                },
                                            columns: [
                                                {data: 'id',   title :'<center>#</center>', className: 'text-center w50 ',render: function(d) {
                                                   return d ;
                                                }},
                                                {data: 'purchase_type',   title :'<center>ประเภท</center>', className: 'text-center',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'product_id_fk',   title :'<center>รหัสสินค้า</center>', className: 'text-center',render: function(d) {
                                                   return d ;
                                                }},
                                                {data: 'product_name',   title :'<center>ชื่อสินค้า/บริการ</center>', className: 'text-left',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'amt',   title :'<center>จำนวน</center>', className: 'text-center',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'product_unit',   title :'<center>หน่วย</center>', className: 'text-center',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'pv',   title :'<center>PV</center>', className: 'text-center w50 ',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'selling_price',   title :'<center>ราคาขาย</center>', className: 'text-center',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'total_pv',   title :'<center>รวม PV</center>', className: 'text-center',render: function(d) {
                                                   return d;
                                                }},
                                                {data: 'total_price',   title :'<center>รวม</center>', className: 'text-center',render: function(d) {
                                                   return d;
                                                }},
                            
                                                {data: 'id', title :'Tools', className: 'text-center w60'}, 
                                            ],
                                             'columnDefs': [
                                             {
                                                    'targets': 0,
                                                    'checkboxes': {
                                                       'selectRow': true
                                                    }
                                                 }
                                              ],
                                              'select': {
                                                 'style': 'multi'
                                              },
                                            rowCallback: function(nRow, aData, dataIndex){
                                              $('td:last-child', nRow).html(''
                                                + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstorelist.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                              ).addClass('input');
                                            }
                                        });
                                    });
                          // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                        }, 1500);

                   });

                $('#data-table-list').on('focus', function () {
                    $("#spinner_frame").hide();
                });


            }); 
          </script>

@endsection

