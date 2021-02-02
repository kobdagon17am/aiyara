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
    padding: 3px 6px;
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

  .btn-minus,.btn-plus,.btn-minus-pro,.btn-plus-pro,.btn-plus-product-pro,.btn-minus-product-pro {background-color: bisque !important;}

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
    <div class="col-12">
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
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',@$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sA = @$menu_permit->can_answer==1?'':'display:none;';
    }

      // echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;  

   ?>

<div class="row" style="margin-bottom: -4.1%;" >
    <div class="col-12">
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

                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label for="" class="col-md-4 col-form-label">สาขาที่ตั้งธุรกิจ : * </label>
                                  <div class="col-md-6">
                                      <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating " required="" >
                                       

                                         @if(@$sBranchs)

                                           @if(!empty(@$sRow->branch_id_fk))

                                           <option value="{{@$sBranchs[0]->id}}" {{ (@$sBranchs[0]->id==@$sRow->branch_id_fk)?'selected':'' }} >
                                              {{@$sBranchs[0]->b_name}}
                                            </option>

                                           @else

                                             <option value="">Select</option>

                                              @foreach(@$sBranchs AS $r)
                                              
                                              @if(@$r->id==$User_branch_id)
                                              <option value="{{$r->id}}" {{ (@$r->id==$User_branch_id)?'selected':'' }} >
                                                {{$r->b_name}}
                                              </option>
                                              @else
                                              <option value="{{$r->id}}" {{ (@$r->id==@$sRow->branch_id_fk)?'selected':'' }} >
                                                {{$r->b_name}}
                                              </option>
                                              @endif

                                              @endforeach

                                          @endif

                                        @endif

                                      </select>
                                  </div>
                                </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label for="customers_id_fk" class="col-md-4 col-form-label"> รหัสลูกค้า : ชื่อลูกค้า : * </label>
                                  <div class="col-md-7">
                                    <select id="customers_id_fk" name="customers_id_fk" class="form-control select2-templating " required >
                                      
                                      @if(@$Customer)

                                       @if(!empty(@$sRow->customers_id_fk))

                                           <option value="{{@$Customer[0]->id}}" 
                                            {{ (@$Customer[0]->id==@$sRow->customers_id_fk)?'selected':'' }} >
                                              {{@$Customer[0]->user_name}} : {{@$Customer[0]->prefix_name}}{{@$Customer[0]->first_name}}
                                              {{@$Customer[0]->last_name}}
                                            </option>

                                           @else

                                           <option value="">Select</option>

                                              @foreach(@$Customer AS $r)
                                              <option value="{{$r->id}}" {{ (@$r->id==@$sRow->customers_id_fk)?'selected':'' }} >
                                                {{$r->user_name}} : {{$r->prefix_name}}{{$r->first_name}}
                                                {{$r->last_name}}
                                              </option>
                                              @endforeach
                                              
                                            @endif

                                         @endif


                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>


                         <div class="row">
                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label for="" class="col-md-4 col-form-label">ช่องทางการจำหน่าย : * </label>
                                  <div class="col-md-6">
                                    <select id="distribution_channel_id_fk" name="distribution_channel_id_fk" class="form-control select2-templating " required >
                                      <option value="">Select</option>
                                          @if(@$sDistribution_channel)
                                            @foreach(@$sDistribution_channel AS $r)
                                              @if(empty(@$sRow->purchase_type_id_fk))
                                                    <option value="{{$r->id}}" {{ (@$r->id==1)?'selected':'' }} >
                                                      {{$r->txt_desc}} 
                                                    </option>
                                              @else 
                                                    <option value="{{$r->id}}" 
                                                      {{ (@$r->id==@$sRow->distribution_channel_id_fk)?'selected':'' }}  >{{$r->txt_desc}} 
                                                    </option>
                                              @endif
                                            @endforeach
                                          @endif                                
                                    </select>
                                  </div>
                                </div>
                              </div>

                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label for="customers_id_fk" class="col-md-4 col-form-label"> ประเภทการซื้อ : * </label>
                                  <div class="col-md-7">
                                     <select id="purchase_type_id_fk" name="purchase_type_id_fk" class="form-control select2-templating " required  >
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
                              </div>
                            </div>
                
                        <div class="row">
                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label for="" class="col-md-4 col-form-label">AiStockist :  </label>
                                  <div class="col-md-6">

                                    @if( empty(@$sRow) )
                                        <input class="form-control" type="text" value="*กดปุ่มบันทึกข้อมูลก่อน" readonly="">
                                    @else

                                        <select id="aistockist" name="aistockist" class="form-control select2-templating "  >
                                        <option value="">Select</option>
                                        @if(@$aistockist)
                                          @foreach(@$aistockist AS $r)
                                            <option value="{{$r->user_name}}" {{ (@$r->user_name==@$sRow->aistockist)?'selected':'' }} >
                                              {{$r->user_name}} : {{$r->first_name}} {{$r->last_name}}
                                            </option>
                                          @endforeach
                                        @endif
                                      </select>   

                                   @endif


                                  </div>
                                </div>
                              </div>

                               <div class="col-md-6">
                                <div class="col-md-12 form-group row" style="position: absolute;">
                                   <label for="" class="col-form-label">หมายเหตุ :  </label>
                                  <div class="col-md-10">
                                       <textarea class="form-control" id="note" name="note" rows="5">{{ @$sRow->note }}</textarea>
                                  </div>
                                </div>
                              </div>
                            </div>



                        <div class="row">
                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label for="customers_id_fk" class="col-md-4 col-form-label"> Agency : </label>
                                  <div class="col-md-6" >

                                    @if( empty(@$sRow) )
                                        <input class="form-control" type="text" value="*กดปุ่มบันทึกข้อมูลก่อน" readonly="">
                                    @else
                                        <select id="agency" name="agency" class="form-control select2-templating "   >
                                        <option value="">Select</option>
                                        @if(@$agency)
                                          @foreach(@$agency AS $r)
                                            <option value="{{$r->user_name}}" {{ (@$r->user_name==@$sRow->agency)?'selected':'' }} >
                                              {{$r->user_name}} : {{$r->first_name}} {{$r->last_name}}
                                            </option>
                                          @endforeach
                                        @endif
                                      </select> 

                                      @endif 

                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="form-group row">
                                 
                                </div>
                              </div>
                            </div>

              

                          <div class="form-group  row " style="display: none;">

                            <label for="tracking_type" class="col-md-3 col-form-label">  Tracking Type :  </label>
                            <div class="col-md-3 d-flex ">
                              <input type="text" class="form-control" id="tracking_type" name="tracking_type" value="{{ @$sRow->tracking_type }}" disabled="" >
                            </div>

                             <div class="col-md-6 d-flex ">
                              <label for="tracking_type " class="col-md-3 col-form-label"> Tracking No. : </label>
                              <div class="col-md-9">
                               <input type="text" class="form-control" id="tracking_no" name="tracking_no" value="{{ @$sRow->tracking_no }}" disabled="" >
                               </div>
                             </div>

                          </div>



                          <div class="form-group  row " style="display: none;" >
                            
                            <label for="tracking_type" class="col-md-3 col-form-label">  การแจ้งชำระ :  </label>
                            <div class="col-md-3 d-flex ">
                               <input type="text" class="form-control" value="รอชำระเงิน" disabled="" >
                            </div>

                             <div class="col-md-6 d-flex ">
                              <label for="tracking_type " class="col-md-3 col-form-label"> Currency : </label>
                              <div class="col-md-9">
                               <input type="text" class="form-control" value="THB" disabled="" >
                               </div>
                             </div>

                          </div>


<br>

<?php 
  if(!empty(@$sRow)){
 ?>
<div class="row"  >
  <div class="col-12">

          
          <div class="page-title-box d-flex justify-content-between ">
            <h4 class=" col-5 mb-0 font-size-18"><i class="bx bx-play"></i> รายการย่อย [{{@$sRow->invoice_code}}] </h4>

            <a class="btn btn-success btn-sm mt-1 btnPrint " href="{{ URL::to('backend/frontstore/print_receipt') }}/{{@$sRow->id}}" target=_blank >
              <i class="bx bx-printer align-middle mr-1 font-size-18 "></i><span style="font-size: 14px;"> ใบเสร็จ [ 1 : A4 ]</span>
            </a>

            <a class="btn btn-success btn-sm mt-1 btnPrint " href="{{ URL::to('backend/frontstore/print_receipt_02') }}/{{@$sRow->id}}" target=_blank >
              <i class="bx bx-printer align-middle mr-1 font-size-18 "></i><span style="font-size: 14px;"> ใบเสร็จ [ 2 ]</span>
            </a>

            <a class="btn btn-success btn-sm mt-1 btnAddFromPromotion " href="#" >
              <i class="bx bx-plus align-middle mr-1 font-size-18"></i><span style="font-size: 14px;">เพิ่มจากรหัสโปรโมชั่น</span>
            </a>

            <a class="btn btn-success btn-sm mt-1 btnAddFromProdutcsList " href="#" >
              <i class="bx bx-plus align-middle mr-1 font-size-18"></i><span style="font-size: 14px;">เพิ่มแบบ List</span>
            </a>

            <a class="btn btn-success btn-sm mt-1 btnAddList " href="#" >
              <i class="bx bx-plus align-middle mr-1 font-size-18"></i><span style="font-size: 14px;">เพิ่ม</span>
            </a>

          </div>

          <div class="form-group row ">
            <div class="col-md-12">
              <table id="data-table-list" class="table table-bordered dt-responsive" style="width: 100%;">
              </table>
            </div>
          </div>

      <?php 

        $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total from db_frontstore_products_list WHERE frontstore_id_fk=".@$sRow->id." GROUP BY frontstore_id_fk ");
        $sFrontstoreData = DB::select(" select * from db_frontstore_products_list WHERE frontstore_id_fk=".@$sRow->id." ");

        $vat = intval(@$sFrontstoreDataTotal[0]->total) - (intval(@$sFrontstoreDataTotal[0]->total)/1.07) ;

        $shipping_price = 100;

       ?>

         

                <div class="form-group row">
                  <label for="pay_type_id_fk" class="col-md-9 col-form-label"> Total : * </label>
                  <div class="col-md-2">
                    <input  class="form-control" id="sum_price_desc" name="sum_price_desc" value="{{@$sFrontstoreDataTotal[0]->total}}" readonly="" style="text-align: center;font-size: 16px;color: red;font-weight: bold;" />
                  </div>
                </div>


                <div class="form-group row">
                            <label for="pay_type_id_fk" class="col-md-6 col-form-label"> รูปแบบการชำระอื่นๆ : * </label>
                            <div class="col-md-5">
                              <select id="pay_type_id_fk" name="pay_type_id_fk" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                    @if(@$sPay_type01)
                                      @foreach(@$sPay_type01 AS $r)
                                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->pay_type_id_fk)?'selected':'' }}  >
                                          {{$r->detail}} 
                                        </option>
                                      @endforeach
                                    @endif                                
                              </select>
                            </div>
                          </div>

                          <?php $div_fee = @$sRow->pay_type_id_fk!=2?"display: none;":''; ?>

                          <div class="form-group row div_fee " style="" >
                            <label for="pay_type_id_fk" class="col-md-6 col-form-label"> ค่าธรรมเนียมกรณีชำระด้วยบัตรเครดิต : </label>
                            <div class="col-md-5">
                              <select id="fee" name="fee" class="form-control select2-templating " >
                                <option value="">Select</option>
                                @if(@$sFee)
                                @foreach(@$sFee AS $r)
                                <option value="{{$r->id}}" {{ (@$r->id==@$sRow->fee)?'selected':'' }}  >
                                  {{$r->txt_desc}}
                                  [{{$r->txt_value}}]
                                </option>
                                @endforeach
                                @endif
                              </select>
                              <input type="hidden" id="fee_value">
                            </div>
                          </div>

                          <div class="form-group row ">
                            <label for="pay_type_id_fk_2" class="col-md-6 col-form-label"> รูปแบบการชำระประเภทเงินสด: </label>
                            <div class="col-md-5">
                              <select id="pay_type_id_fk_2" name="pay_type_id_fk_2" class="form-control select2-templating " >
                                <option value="0">Select</option>
                                    @if(@$sPay_type02)
                                      @foreach(@$sPay_type02 AS $r)

                                      @if(empty(@$sRow->pay_type_id_fk_2))
                                         <option value="{{$r->id}}" {{ (@$r->id==5)?'selected':'' }}  >
                                            {{$r->detail}} 
                                          </option>
                                      @else
                                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->pay_type_id_fk_2)?'selected':'' }}  >
                                          {{$r->detail}} 
                                        </option>
                                      @endif

                                      @endforeach
                                    @endif                                
                              </select>
                            </div>
                          </div>


                <div class="row">
                  <div class="col-md-6">
         
                  </div>
                  <div class="col-md-5 text-right">
                    <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-14 ">
                    <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                    </button>
                  </div>
                </div>
   </form>

<?php }else{ ?>


                <div style="text-align: right;margin-right: 3.5%">
                    <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-14 ">
                    <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                    </button>
                </div>
   </form>

<?php } ?>

<?php 
  if(!empty(@$sRow)){
 ?>

 <form action="{{ route('backend.frontstorelist.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
      <input name="frontstore_id" type="hidden" value="{{@$sRow->id}}">
      <input name="receipt_save_list" type="hidden" value="1">
      <input name="invoice_code" type="hidden" value="{{@$sRow->invoice_code}}">
      <input name="customers_id_fk" type="hidden" value="{{@$sRow->customers_id_fk}}">

      {{ csrf_field() }}


        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                
                <div class="table-responsive">
                  <table class="table table-striped mb-0">
                    
                    <thead>
                      <tr style="background-color: #f2f2f2;">
                        <th>ระบุที่อยู่ในการจัดส่ง</th>
                      </tr>
                    </thead>
                    <tbody>

                      <tr>
                        <th scope="row" class="d-flex">
                          <input type="radio" name="delivery_location" id="addr_00" value="0" <?=(@$sRow->delivery_location==0?'checked':'')?> > <label for="addr_00">&nbsp;&nbsp;รับสินค้าด้วยตัวเอง > ระบุสาขา : </label>
                            <div class="col-md-6">
                             <select id="sentto_branch_id" name="sentto_branch_id" class="form-control select2-templating "  >
                              <option value="">-</option>
                              @if(@$sBranchs)
                                @foreach(@$sBranchs AS $r)
                                  @if(@$r->id==@$sRow->branch_id_fk)
                                  <option value="{{$r->id}}" {{ (@$r->id==@$sRow->branch_id_fk)?'selected':'' }} >
                                    {{$r->b_name}}
                                  </option>
                                  @elseif(@$r->id==$User_branch_id)
                                  <option value="{{$r->id}}" {{ (@$r->id==$User_branch_id)?'selected':'' }} >
                                    {{$r->b_name}}
                                  </option>
                                  @else
                                  <option value="{{$r->id}}" {{ (@$r->id==@$sRow->sentto_branch_id)?'selected':'' }} >
                                    {{$r->b_name}}
                                  </option>
                                  @endif
                                @endforeach
                              @endif
                             </select>
                           </div>

                        </th>
                      </tr>

                  <?php
                            $addr = DB::select(" SELECT
                                      customers_address_card.id,
                                      customers_address_card.customer_id,
                                      customers_address_card.card_house_no,
                                      customers_address_card.card_house_name,
                                      customers_address_card.card_moo,
                                      customers_address_card.card_zipcode,
                                      customers_address_card.card_soi,
                                      customers_address_card.card_district,
                                      customers_address_card.card_district_sub,
                                      customers_address_card.card_road,
                                      customers_address_card.card_province,
                                      customers_address_card.created_at,
                                      customers_address_card.updated_at,
                                      dataset_provinces.name_th AS provname,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_districts.name_th AS tamname,
                                      customers.prefix_name,
                                      customers.first_name,
                                      customers.last_name
                                      FROM
                                      customers_address_card
                                      Left Join dataset_provinces ON customers_address_card.card_province = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_address_card.card_district = dataset_amphures.id
                                      Left Join dataset_districts ON customers_address_card.card_district_sub = dataset_districts.id
                                      Left Join customers ON customers_address_card.customer_id = customers.id
                                      where customers_address_card.customer_id = ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)."

                                     ");
                            if(@$addr[0]->provname!=''){
                              @$address = "";
                              @$address .=  "ชื่อผู้รับ : ". @$addr[0]->prefix_name.@$addr[0]->first_name." ".@$addr[0]->last_name;
                              @$address .=  "<br>ที่อยู่ : ". @$addr[0]->card_house_no; 
                              @$address .=  " ต. ". @$addr[0]->tamname; 
                              @$address .=  " อ. ". @$addr[0]->ampname;
                              @$address .=  " จ. ". @$addr[0]->provname; 
                              @$address .=  " รหัส ปณ. ". @$addr[0]->card_zipcode;
                            }else{
                              @$address = '-ไม่พบข้อมูล-';
                            }

                  ?>

                      <tr>
                        <th scope="row">
                          <input type="radio" name="delivery_location" id="addr_01" value="1" <?=(@$sRow->delivery_location==1?'checked':'')?> > <label for="addr_01"> ที่อยู่ตามบัตร ปชช. </label>
                           <br><?=@$address?>
                        </th>
                      </tr>

                    <?php 

                              @$addr = DB::select("SELECT
                                      customers_detail.customer_id,
                                      customers_detail.house_no,
                                      customers_detail.house_name,
                                      customers_detail.moo,
                                      customers_detail.zipcode,
                                      customers_detail.soi,
                                      customers_detail.district,
                                      customers_detail.district_sub,
                                      customers_detail.road,
                                      customers_detail.province,
                                      customers.prefix_name,
                                      customers.first_name,
                                      customers.last_name,
                                      dataset_provinces.name_th AS provname,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_districts.name_th AS tamname
                                      FROM
                                      customers_detail
                                      Left Join customers ON customers_detail.customer_id = customers.id
                                      Left Join dataset_provinces ON customers_detail.province = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_detail.district = dataset_amphures.id
                                      Left Join dataset_districts ON customers_detail.district_sub = dataset_districts.id
                                      WHERE customers_detail.customer_id = 
                                       ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)." ");

                              // print_r(sizeof(@$addr));
                              if(sizeof(@$addr)>0){

                                @$address = "";
                                @$address .= "ชื่อผู้รับ : ". @$addr[0]->prefix_name.@$addr[0]->first_name." ".@$addr[0]->last_name;
                                @$address .= "<br>ที่อยู่ : ". @$addr[0]->house_no. " ". @$addr[0]->house_name. " ";
                                @$address .= " ต. ". @$addr[0]->tamname; 
                                @$address .= " อ. ". @$addr[0]->ampname; 
                                @$address .= " จ. ". @$addr[0]->provname; 
                                @$address .= " รหัส ปณ. ". @$addr[0]->zipcode ;

                              }else{
                                @$address = '-ไม่พบข้อมูล-';
                              }

                     ?>

                      <tr>
                        <th scope="row">
                          <input type="radio" name="delivery_location" id="addr_02" value="2" <?=(@$sRow->delivery_location==2?'checked':'')?> > <label for="addr_02"> ที่อยู่จัดส่งไปรษณีย์ </label>
                           <br><?=@$address?>
                        </th>
                      </tr>

                    <?php 
                         @$addr = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                                dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname 
                                from customers_addr_frontstore
                                Left Join dataset_provinces ON customers_addr_frontstore.province_code = dataset_provinces.id
                                Left Join dataset_amphures ON customers_addr_frontstore.amphur_code = dataset_amphures.id
                                Left Join dataset_districts ON customers_addr_frontstore.tambon_code = dataset_districts.id
                                where customers_addr_frontstore.id = ".(@$CusAddrFrontstore[0]->id?$CusAddrFrontstore[0]->id:0)." ");

                         if(sizeof(@$addr)>0){

                              @$address = "ชื่อผู้รับ : ". @$addr[0]->recipient_name; 
                              @$address .= "<br> ที่อยู่ : ". @$addr[0]->addr_no ;
                              @$address .= " ต. ". @$addr[0]->tamname; 
                              @$address .= " อ. ". @$addr[0]->ampname; 
                              @$address .= " จ. ". @$addr[0]->provname; 
                              @$address .= " รหัส ปณ. ". @$addr[0]->zip_code ;

                          }else{
                                @$address = '-ไม่พบข้อมูล-';
                          }


                     ?>

                      <tr>
                        <th scope="row">
                          <input type="radio" name="delivery_location" id="addr_03" value="3" <?=(@$sRow->delivery_location==3?'checked':'')?> > <label for="addr_03"> ที่อยู่กำหนดเอง </label>
                           <br><?=@$address?>
                        </th>
                      </tr>

                    </tbody>
                  </table>
                </div>
                
              </div>
            </div>
          </div>

      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            
            <div class="row">
              <div class="col-11">
                <div class="divTable">
                  <div class="divTableBody">
                    <div class="divTableRow">
                      <div class="divTableCell">&nbsp; </div>
                      <div class="divTH">
                        <label for="" >มูลค่าสินค้า : </label>
                      </div>
                      <div class="divTableCell">
                        <input  class="form-control" value="{{number_format(@$sFrontstoreDataTotal[0]->total-@$vat,2)}}" readonly="" />
                      </div>
                      <div class="divTableCell">
                      </div>
                    </div>
                    <div class="divTableRow">
                      <div class="divTableCell" >&nbsp; </div>
                      <div class="divTH">
                        <label for="" >ภาษี 7.00 % : </label>
                      </div>
                      <div class="divTableCell">
                        <input  class="form-control" value="{{number_format(@$vat,2)}}" readonly="" />
                      </div>
                      <div class="divTableCell">
                      </div>
                    </div>
                    <div class="divTableRow">
                      <div class="divTableCell" >&nbsp; </div>
                      <div class="divTH">
                        <label for="" >รวมค่าสินค้า : </label>
                      </div>
                      <div class="divTableCell">
                    <!--     <input  class="form-control" id="sum_price" name="sum_price" value="{{number_format(@$sFrontstoreDataTotal[0]->total,2)}}" readonly="" /> -->
                        <input  class="form-control" id="sum_price" name="sum_price" value="{{@$sFrontstoreDataTotal[0]->total}}" readonly="" />
                      </div>
                      <div class="divTableCell">
                      </div>
                    </div>

                    <div class="divTableRow">
                      <div class="divTableCell" >
                        <span style="color: red;">
                        </div>
                        <div class="divTH" style='z-index: 1' >
                          <label for="" >ค่าจัดส่ง : </label>
                        </div>
                        <div class="divTableCell" style='z-index: 1' >
                          <input class="form-control" id="shipping_price" name="shipping_price"  value="{{@$shipping_price}}" readonly="" />
                        </div>
                         <div class="divTableCell" >
                        </div>
                      </div>

                    
                    <div class="divTableRow" style="background-color: #eff2f7;" >
                      <div class="divTableCell" style="border: aliceblue;" > </div>
                      <div class="divTH">
                        <label for="" > ยอดเงินโอน :  </label>
                      </div>
                      <div class="divTableCell">
                         <!-- // 5=เงินสด,2=บัตรเครดิต -->
                          <input  class="form-control FeeCal NumberOnly " id="transfer_price" name="transfer_price" value="{{(@$sRow->transfer_price>0)?number_format(@$sRow->transfer_price):''}}" style="color: red;font-weight: bold;" required="" />
                      </div>
                   
                    </div>

                    <div class="divTableRow" style="background-color: #eff2f7;" >
                      <div class="divTableCell" style="border: aliceblue;" >&nbsp; </div>
                      <div class="divTH">
                        <label for="" > ค่าธรรมเนียมตัดบัตรเครดิต :  </label>
                      </div>
                      <div class="divTableCell">
                          <input  class="form-control" id="fee_amt" name="fee_amt" value="{{number_format(@$sRow->fee_amt)}}" readonly style="color: red;font-weight: bold;" />
                      </div>
                  
                    </div>


                    <div class="divTableRow" style="background-color: #eff2f7;">
                      <div class="divTableCell" style="border: aliceblue;" ></div>
                      <div class="divTH">
                        <label for="" > ยอดเงินสด :  </label>
                      </div>
                      <div class="divTableCell">
                          <input  class="form-control " id="cash_price" name="cash_price" value="{{@$sRow->cash_price}}" style="color: blue;font-weight: bold;" readonly="" >
                      </div>
                    
                    </div>


                      <div class="divTableRow">
                        <div class="divTableCell" >
                        </div>
                        <div class="divTH">
                          <label for="" >ยอดชำระ : </label>
                        </div>
                        <div class="divTableCell">
                          <input  class="form-control" id="total_price" name="total_price" value="{{number_format(@$sFrontstoreDataTotal[0]->total+@$shipping_price+@$sRow->fee_amt,2)}}" readonly="" style="font-size:18px;color: red;font-weight: bold;" />
                        </div>
                         <div class="divTableCell" >
                        </div>
                      </div>

                      <div class="divTableRow">
                        <div class="divTableCell" >
                        </div>
                        <div class="divTH">
                          <label for="" > </label>
                        </div>
                        <div class="divTableCell">
                        </div>
                      </div>

                      <div class="divTableRow">
                        <div class="divTableCell" >
                        </div>
                        <div class="divTH">
                          <label for="" > </label>
                        </div>
                        <div class="divTableCell">
                            <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-14  " style="float: right;" >
                            <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูลใบเสร็จ
                            </button>
                        </div>
                      </div>


                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- DivTable.com -->
 
          
        </form>
      </div>

</div>



      </div>
      </div>

           <a class="btn btn-secondary btn-sm waves-effect btnBack " href="{{ url("backend/frontstore") }}">
              <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
              </a>


    </div>
  </div>
</div>

<?php } ?>

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

     
   <form  id="frmAddPro" action="{{ route('backend.frontstorelist.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
      <input name="frontstore_id" type="hidden" value="{{@$sRow->id}}">
      <input name="product_plus_pro" type="hidden" value="1">
      <input id="promotion_id_fk" name="promotion_id_fk" type="hidden" >
      <input id="limited_amt_person" name="limited_amt_person" type="hidden" placeholder="จำนวนจำกัดต่อคน" >
    
      {{ csrf_field() }}

      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body" >

                <div class="page-title-box d-flex">
                  <label for="" class="col-2" ><b>รหัสคูปอง : </b></label> 
                  <input class="col-4 form-control " type="text" name="txtSearchPro" id="txtSearchPro" > &nbsp; &nbsp;
                  <button class="btn btn-success btnCheckProCode " >ตรวจสอบ</button>
                </div>

                <div class="modal-title">
                <input id="pro_name" type="text" readonly="" style="border: 0px solid transparent;width: 100%;font-weight: bold;font-size: 18px;display: none;">
                </div>

                <table id="data-table-promotion" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>

                <table id="data-table-promotions-cost" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>

                <br>

                    <div class="form-group row show_add_coupon " style="display: none;" >
                      <label for="tracking_type" class="col-md-5 col-form-label"> จำนวน (ชุด) : </label>
                      <div class="col-md-7">
                        <div class="input-group inline-group">
                        <div class="input-group-prepend" >
                          <button type="button" class="btn btn-outline-secondary btn-minus-pro ">
                          <i class="fa fa-minus"></i>
                          </button>
                          <input class="quantity plus-pro " min="1" name="quantity" value="1" type="number" readonly >
                          <div class="input-group-append">
                            <button type="button" data-product_id_fk="" class="btn btn-outline-secondary btn-plus-pro ">
                            <i class="fa fa-plus"></i>
                            </button>
                          </div>
                        </div>
                        </div>
                      </div>
                    </div>

              </div>

            </div>
          </div>
        </div>

      </div>
           
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btnSavePro " style="display: none;"><i class="bx bx-save font-size-16 align-middle "></i> Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

 </form>

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
<!-- xxxxxxxxxxxx -->
                <div class=" d-flex " >
                  <button class="btn btn-success btnProductAll1 " data-value="1" style="margin-right: 1%;" >ทั้งหมด</button>
                  <button class="btn btn-success btnAihealth2 " data-value="2" style="margin-right: 1%;" >AiHealth</button>
                  <button class="btn btn-success btnAilada3 " data-value="3" style="margin-right: 1%;" >AiLADA</button>
                  <button class="btn btn-success btnAibody4 " data-value="4" style="margin-right: 1%;" >AiBODY</button>
                  <button class="btn btn-success btnPromotion8 " data-value="8" style="margin-right: 1%;" >Promotion</button>
                </div>

                <form  class="frmFrontstorelist" id="frmFrontstorelist" action="{{ route('backend.frontstorelist.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                  <input name="frontstore_id" type="hidden" value="{{@$sRow->id}}">
                  <input name="product_plus" type="hidden" value="1">
                  {{ csrf_field() }}
                  <table id="data-table-products-list" class="table table-bordered dt-responsive" style="width: 100%;">
                  </table>
                </form>

                 <form  class="frmFrontstorelistPro" action="{{ route('backend.frontstorelist.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                  <input name="frontstore_id" type="hidden" value="{{@$sRow->id}}">
                  {{ csrf_field() }}
                          <div class="form-group row div-data-table-list-pro " style="display: none;">
                            <div class="col-md-12"> 
                              <table id="data-table-list-pro" class="table table-bordered " style="width: 100%;">
                              </table>
                            </div>
                          </div>
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
  <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDeliveryTitle"><b><i class="bx bx-play"></i>ที่อยู่การจัดส่ง (กำหนดเอง) </b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     
      <div class="modal-body">


     @if( empty(@$CusAddrFrontstore) )
     
        <form  id="frmDeliveryCustom" action="{{ route('backend.frontstorelist.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
          <input name="add_delivery_custom" type="hidden" value="1">

     @else

        <form  id="frmDeliveryCustom" action="{{ route('backend.frontstorelist.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
          <input name="update_delivery_custom" type="hidden" value="1">
          <input name="customers_addr_frontstore_id" type="hidden" value="{{@$CusAddrFrontstore[0]->id}}">
          <input name="invoice_code" type="hidden" value="{{@$sRow->invoice_code}}">

     @endif


          <input name="frontstore_id" type="hidden" value="{{@$sRow->id}}">
          
          <input name="customers_id_fk" type="hidden" value="{{@$sRow->customers_id_fk}}">
          
          {{ csrf_field() }}

          <div class="col-12">

                 <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> ชื่อ-นามสกุล (ผู้รับ) : </label>
                  <div class="col-md-7">
                    <input type="text" name="delivery_cusname" class="form-control" value="{{@$CusAddrFrontstore[0]->recipient_name}}" required >
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> ที่อยู่ : </label>
                  <div class="col-md-7">
                    <textarea name="delivery_addr" class="form-control" rows="3"  required >{{@$CusAddrFrontstore[0]->addr_no}}</textarea>
                  </div>
                </div>
 
                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> จังหวัด : </label>
                  <div class="col-md-7">
                    <select id="delivery_province" name="delivery_province" class="form-control select2-templating " >
                       <option value="">Select</option>
                       @if(@$sProvince)
                        @foreach(@$sProvince AS $r)
                        <option value="{{$r->id}}" {{ (@$r->id==@$CusAddrFrontstore[0]->province_code)?'selected':'' }} >
                          {{$r->province_name}}
                        </option>
                        @endforeach
                        @endif
                    </select>

                  </div>
                </div>


                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> อำเภอ/เขต : </label>
                  <div class="col-md-7">
                    <select id="delivery_amphur" name="delivery_amphur" class="form-control select2-templating " required >
                      @if(@$CusAddrFrontstore[0]->amphur_code)
                           <option value="">Select</option>
                           @if(@$sAmphures)
                            @foreach(@$sAmphures AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$CusAddrFrontstore[0]->amphur_code)?'selected':'' }} >
                              {{$r->amphur_name}}
                            </option>
                            @endforeach
                            @endif
                      @else
                        <option disabled selected>กรุณาเลือกจังหวัดก่อน</option>
                      @endif
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> ตำบล/แขวง : </label>
                  <div class="col-md-7">
                    <select id="delivery_tambon" name="delivery_tambon" class="form-control select2-templating " required >
                      @if(@$CusAddrFrontstore[0]->tambon_code)
                           <option value="">Select</option>
                           @if(@$sTambons)
                            @foreach(@$sTambons AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$CusAddrFrontstore[0]->tambon_code)?'selected':'' }} >
                              {{$r->tambon_name}}
                            </option>
                            @endforeach
                            @endif
                      @else
                        <option disabled selected>กรุณาเลือกอำเภอก่อน</option>
                      @endif
                    </select>
                  </div>
                </div>


                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> รหัสไปรษณีย์ : </label>
                  <div class="col-md-7">
                    <input type="text" id="delivery_zipcode" name="delivery_zipcode" class="form-control" value="{{@$CusAddrFrontstore[0]->zip_code}}" required >
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> เบอร์โทร : </label>
                  <div class="col-md-7">
                    <input type="text" name="delivery_tel" class="form-control" value="{{@$CusAddrFrontstore[0]->tel}}"  required >
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> </label>
                  <div class="col-md-7">
                    <button type="submit" class="btn btn-primary float-right "><i class="bx bx-save font-size-16 align-middle "></i> Save</button>
                    <!-- <button type="button" class="btn btn-secondary float-right " data-dismiss="modal">Close</button> -->
                  </div>
                </div>

          </div>


        </form>


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
                                                
                      var info = $(this).DataTable().page.info();
                      $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                      if(aData['add_from']==2){
                        $("td:eq(1)", nRow).html("โปรแกรมโมชั่นคูปอง");
                        $("td:eq(4)", nRow).html("ชุด");
                      }

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

// xxxxxxxxxxxx
            $.fn.dataTable.ext.errMode = 'throw';
  
            var frontstore_id_fk = "{{@$sRow->id}}"; //alert(frontstore_id_fk);

            $(function() {
              $('#data-table-list-pro').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('backend.frontstorelist-pro.datatable') }}',
                        data :{
                              frontstore_id_fk:frontstore_id_fk,
                            },
                          method: 'POST',
                        },
                    columns: [
                        {data: 'p_img',   title :'<center>รูป</center>', className: 'text-center w100 ',render: function(d) {
                           return '<img src='+d+' width="80">';
                        }},
                        {data: 'product_name',   title :'<center>รายการโปร</center>', className: 'text-left',render: function(d) {
                          return d;
                        }},
                        {data: 'pv',   title :'<center>PV</center>', className: 'text-center w50 ',render: function(d) {
                           return d;
                        }},
                        {data: 'selling_price',   title :'<center>ราคา</center>', className: 'text-center',render: function(d) {
                           return d;
                        }},
                        {data: 'select_amt',   title :'<center>จำนวน</center>', className: 'w120 ',render: function(d) {

                          if(d){
                          var v = d.split(':');

                           return '<input name="promotion_id_fk_pro[]" value="'+v[0]+'" type="hidden" ><div class="input-group inline-group"> '
                                +' <div class="input-group-prepend"> '
                                +'   <button promotion_id_fk="'+v[0]+'" limited_amt_person="'+v[1]+'" class="btn btn-outline-secondary btn-minus-product-pro "> '
                                +'     <i class="fa fa-minus"></i> '
                                +'   </button>'
                                +'   <input class=" quantity " min="0" name="quantity[]" value="0" type="number" readonly >'
                                +'   <div class="input-group-append"> '
                                +'   <button promotion_id_fk="'+v[0]+'" limited_amt_person="'+v[1]+'" class="btn btn-outline-secondary btn-plus-product-pro "> '
                                +'     <i class="fa fa-plus"></i> '
                                +'    </button> '
                                +'  </div> '
                                +' </div> ' ;
                              }
                        }},
                    ],
                    rowCallback: function(nRow, aData, dataIndex){

                          if (aData['frontstore_promotions_list'] > 0 ) {

                              $("td:eq(4)", nRow).html(
                                '<input name="promotion_id_fk_pro[]" value="'+aData['frontstore_promotions_list']+'" type="hidden" ><div class="input-group inline-group"> '
                                +' <div class="input-group-prepend"> '
                                +'   <button promotion_id_fk="'+aData['frontstore_promotions_list']+'" limited_amt_person="'+aData['limited_amt_person']+'" class="btn btn-outline-secondary btn-minus-product-pro "> '
                                +'     <i class="fa fa-minus"></i> '
                                +'   </button>'
                                +'   <input class=" quantity " min="0" name="quantity[]" value="'+aData['frontstore_promotions_list']+'" type="number" readonly >'
                                +'   <div class="input-group-append"> '
                                +'   <button promotion_id_fk="'+aData['frontstore_promotions_list']+'" limited_amt_person="'+aData['limited_amt_person']+'" class="btn btn-outline-secondary btn-plus-product-pro "> '
                                +'     <i class="fa fa-plus"></i> '
                                +'    </button> '
                                +'  </div> '
                                +' </div> ' 
                                );

                          }


                        
                      }                    
          
                });

            });
              

</script>



<script type="text/javascript">


            $.fn.dataTable.ext.errMode = 'throw';
  
            var oTable;

            $(function() {
                oTable = $('#data-table-list-promotion').DataTable({
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
                      url: '{{ route('backend.promotion_cus_products.datatable') }}',
                      data :{
                          txtSearchPro:1,
                        },
                      method: 'POST'
                    },
                    columns: [
                        {data: 'id',   title :'<center>#</center>', className: 'text-center w50 ',render: function(d) {
                           return d ;
                        }},
                        {data: 'purchase_type',   title :'<center>ประเภท</center>', className: 'text-center',render: function(d) {
                           return d;
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
                                                
                      var info = $(this).DataTable().page.info();
                      $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                      if(aData['add_from']==2){
                        $("td:eq(1)", nRow).html("โปรแกรมโมชั่นคูปอง");
                        $("td:eq(4)", nRow).html("ชุด");
                      }

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

                  $('#modalAddFromPromotion').modal('show');
          
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
                    "info":  true,
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

            $('#modalAddFromProductsList').on('shown.bs.modal', function () {
                $('.btnProductAll1').trigger('click');
            })  

            // $('#modalAddFromPromotion,#modalAddFromProductsList').on('hidden.bs.modal', function () {
            //     $("#spinner_frame").show();
            //     window.location.reload(true);
            // })

            $(document).on('click', '#addr_03', function(event) {
              var v = $(this).val();
              // alert(amp);
              if(v==3){
                $('#modalDelivery').modal('show');
              }
       
            });



      });


    $(document).ready(function() {

        var vch = $('#pay_type_id_fk').val();
        if(vch!=2){
            $('.div_fee').hide();
            $('#fee').removeAttr('required');
            $('#fee').val("").select2();
        }else{
            $('.div_fee').show();
            $('#fee').attr('required', true);
        }


        $(document).on('click', '.btn-plus-product-pro, .btn-minus-product-pro', function(e) {
          event.preventDefault();

          var limited_amt_person = $(this).attr('limited_amt_person');
          $(e.target).closest('.input-group').find('input.quantity').attr('max',limited_amt_person);

          const isNegative = $(e.target).closest('.btn-minus-product-pro').is('.btn-minus-product-pro');
          const input = $(e.target).closest('.input-group').find('input');
          if (input.is('input')) {
            input[0][isNegative ? 'stepDown' : 'stepUp']()
          }
        })


        $(document).on('click', '.btn-plus-pro, .btn-minus-pro', function(e) {
        event.preventDefault();
          const isNegative = $(e.target).closest('.btn-minus-pro').is('.btn-minus-pro');
          const input = $(e.target).closest('.input-group').find('input');
          if (input.is('input')) {
            input[0][isNegative ? 'stepDown' : 'stepUp']()
          }
        })


        $(document).on('click', '.btn-plus, .btn-minus', function(e) {

            event.preventDefault();
            const isNegative = $(e.target).closest('.btn-minus').is('.btn-minus');
            const input = $(e.target).closest('.input-group').find('input');
            if (input.is('input')) {
              input[0][isNegative ? 'stepDown' : 'stepUp']()
            }

        })

        $(document).on('change', '#branch_id_fk', function(event) {
            event.preventDefault();
            var id = $(this).val();
            localStorage.setItem('branch_id_fk', id);
        });

        if(localStorage.getItem('branch_id_fk')){
            $('#branch_id_fk').val(localStorage.getItem('branch_id_fk')).select2();
        }


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
            // alert(id+":"+id2);
            localStorage.setItem('pay_type_id_fk', id);
            if(id==2){
              $('.div_fee').show();
              $('#fee').attr('required', true);
            }else{
                $('.div_fee').hide();
                $('#fee').removeAttr('required');
                $('#fee').val("").select2();
            }
        });


        $(document).on('change', '#fee', function(event) {

            var v = $(this).val();
            localStorage.setItem('fee', v);
           
            $.ajax({
                 type:'POST',
                 url: " {{ url('backend/ajaxGetFeeValue') }} ", 
                 data:{ _token: '{{csrf_token()}}',fee_id:v },
                  success:function(data){
                       $('#fee_value').val(data);
                        localStorage.setItem('fee_value', data);
                    },
                  error: function(jqXHR, textStatus, errorThrown) { 
                      console.log(JSON.stringify(jqXHR));
                      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                      $(".myloading").hide();
                  }
              });
        });

        if(localStorage.getItem('fee')){
            $('#fee').val(localStorage.getItem('fee')).select2();
            $('#fee_value').val(localStorage.getItem('fee_value'));
        }


        if(localStorage.getItem('pay_type_id_fk')){
            $('#pay_type_id_fk').val(localStorage.getItem('pay_type_id_fk')).select2();
        }
                                
        $(document).on('change', '#pay_type_id_fk_2', function(event) {
            event.preventDefault();
            var id = $(this).val();
            localStorage.setItem('pay_type_id_fk_2', id);
        });

                                
        $(document).on('change', '.FeeCal', function(event) {


                var this_id = $(this).attr('id');
                // alert(this_id);

                var cash_price = $("#cash_price").val()?$('#cash_price').val():0;
                var transfer_price = $("#transfer_price").val()?$('#transfer_price').val():0;
                var fee_value = $('#fee_value').val()?$('#fee_value').val():0;
                var pay_type_id_fk = $('#pay_type_id_fk').val()?$('#pay_type_id_fk').val():0;
                var pay_type_id_fk_2 = $('#pay_type_id_fk_2').val()?$('#pay_type_id_fk_2').val():0;
                var sum_price = $('#sum_price_desc').val()?$('#sum_price_desc').val():0;
                var frontstore_id_fk = "{{@$sRow->id}}";
                var shipping_price = $('#shipping_price').val()?$('#shipping_price').val():0;
                // alert(cash_price+":"+transfer_price+":"+fee_value);
                // return false;
                $.ajax({
                 type:'POST',
                 dataType:'JSON',
                 url: " {{ url('backend/ajaxFeeCal') }} ", 
                 data:{ _token: '{{csrf_token()}}',cash_price:cash_price,transfer_price:transfer_price,fee_value:fee_value,pay_type_id_fk:pay_type_id_fk,pay_type_id_fk_2:pay_type_id_fk_2,sum_price:sum_price,frontstore_id_fk:frontstore_id_fk,this_id:this_id },
                  success:function(data){
                       console.log(data); 
                       // return false;
                       // location.reload();
                       $.each(data,function(key,value){
                        console.log(value.sum_price);
                        $("#cash_price").val(value.cash_price);
                        $("#transfer_price").val(value.transfer_price);
                        $("#fee_amt").val(value.fee_amt);
                        $("#total_price").val(value.sum_price);
                       });

                       // alert($('#pay_type_id_fk').val()+":"+$('#pay_type_id_fk_2').val());
                       // alert($("#transfer_price").val());

                       // if( ($('#pay_type_id_fk').val()==2 || $('#pay_type_id_fk_2').val()==2) && $("#transfer_price").val()=="0.00" ){
                       //  alert("!!! โปรดตรวจสอบ ข้อมูลบางอย่างไม่ถูกต้อง เช่น รูปแบบการชำระ ไม่สอดคล้องกับ ยอดเงินโอน ");
                       //  $("button[type=submit]").prop("disabled",true);
                       // }else{
                       //    if($("#transfer_price").val()>0&&$('#fee_value').val()==0){
                       //      alert("!!! โปรดตรวจสอบ ข้อมูลบางอย่างไม่ถูกต้อง เช่น รูปแบบการชำระ ไม่สอดคล้องกับ ยอดเงินโอน ");
                       //      $("button[type=submit]").prop("disabled",true);
                       //     }else{
                       //      $("button[type=submit]").prop("disabled",false);
                       //     }
                       // }
                      

                    },
                  error: function(jqXHR, textStatus, errorThrown) { 
                      console.log(JSON.stringify(jqXHR));
                      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                      $(".myloading").hide();
                  }
              });

          });



        if(localStorage.getItem('pay_type_id_fk_2')){
            $('#pay_type_id_fk_2').val(localStorage.getItem('pay_type_id_fk_2')).select2();
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
           // var d =  $("#frmFrontstorelist").serialize();
           var d =  $(".frmFrontstorelist").serialize();
           var product_id_fk_this =  $(this).data('product_id_fk');

           var frontstore_id_fk = "{{@$sRow->id}}"; //alert(frontstore_id_fk);

           // alert(product_id_fk_this);

           // return false;

            $.ajax({
               type:'POST',
               url: " {{ url('backend/frontstorelist/plus') }} ", 
               // data:{ d:d , _token: '{{csrf_token()}}' },
               data: $(".frmFrontstorelist").serialize()+"&product_id_fk_this="+product_id_fk_this,
                success: function(response){ // What to do if we succeed
                       console.log(response); 
                       // console.log(frontstore_id_fk); 
                       // return false;
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

                                    var info = $(this).DataTable().page.info();
                                    $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                                    if(aData['add_from']==2){
                                      $("td:eq(1)", nRow).html("โปรแกรมโมชั่นคูปอง");
                                      $("td:eq(4)", nRow).html("ชุด");
                                    }

                                    $("#sum_price_desc").val(aData['sum_price_desc']);


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
           var d =  $(".frmFrontstorelist").serialize();
           var product_id_fk_this =  $(this).data('product_id_fk');

           var frontstore_id_fk = "{{@$sRow->id}}"; //alert(frontstore_id_fk);

           // alert(product_id_fk_this);

            $.ajax({
               type:'POST',
               url: " {{ url('backend/frontstorelist/minus') }} ", 
               // data:{ d:d , _token: '{{csrf_token()}}' },
               data: $(".frmFrontstorelist").serialize()+"&product_id_fk_this="+product_id_fk_this,
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

                                        var info = $(this).DataTable().page.info();
                                        $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                                        if(aData['add_from']==2){
                                          $("td:eq(1)", nRow).html("โปรแกรมโมชั่นคูปอง");
                                          $("td:eq(4)", nRow).html("ชุด");
                                        }

                                        $("#sum_price_desc").val(aData['sum_price_desc']);

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



          $(document).on('click', '.btn-plus-product-pro', function(e) {
            // event.preventDefault();
            $.fn.dataTable.ext.errMode = 'throw';

            const input = $(e.target).closest('.input-group').find('input');
            if (input.is('input')) {
               // alert(input.val());
               // frmFrontstorelist
               // $("#frmFrontstorelist").submit();
             // var d =  $("#frmFrontstorelist").serialize();
             var d =  $(".frmFrontstorelistPro").serialize();
             var promotion_id_fk_this =  $(this).attr('promotion_id_fk');

             var frontstore_id_fk = "{{@$sRow->id}}"; //alert(frontstore_id_fk);

             // alert(product_id_fk_this);

             // return false;

              $.ajax({
                 type:'POST',
                 url: " {{ url('backend/frontstorelist/plusPromotion') }} ", 
                 // data:{ d:d , _token: '{{csrf_token()}}' },
                 data: $(".frmFrontstorelistPro").serialize()+"&promotion_id_fk_this="+promotion_id_fk_this,
                  success: function(response){ // What to do if we succeed
                         console.log(response); 
                         // console.log(frontstore_id_fk); 
                         // return false;

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

                                      var info = $(this).DataTable().page.info();
                                      $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                                      if(aData['add_from']==2){
                                        $("td:eq(1)", nRow).html("โปรแกรมโมชั่นคูปอง");
                                        $("td:eq(4)", nRow).html("ชุด");
                                      }

                                      $("#sum_price_desc").val(aData['sum_price_desc']);

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






          $(document).on('click', '.btn-minus-product-pro', function(e) {
            // event.preventDefault();
            $.fn.dataTable.ext.errMode = 'throw';

            const input = $(e.target).closest('.input-group').find('input');
            if (input.is('input')) {
               // alert(input.val());
               // frmFrontstorelist
               // $("#frmFrontstorelist").submit();
             // var d =  $("#frmFrontstorelist").serialize();
             var d =  $(".frmFrontstorelistPro").serialize();
             var promotion_id_fk_this =  $(this).attr('promotion_id_fk');

             var frontstore_id_fk = "{{@$sRow->id}}"; //alert(frontstore_id_fk);

             // alert(product_id_fk_this);

             // return false;

              $.ajax({
                 type:'POST',
                 url: " {{ url('backend/frontstorelist/minusPromotion') }} ", 
                 // data:{ d:d , _token: '{{csrf_token()}}' },
                 data: $(".frmFrontstorelistPro").serialize()+"&promotion_id_fk_this="+promotion_id_fk_this,
                  success: function(response){ // What to do if we succeed
                         console.log(response); 
                         // console.log(frontstore_id_fk); 
                         // return false;

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

                                      var info = $(this).DataTable().page.info();
                                      $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                                      if(aData['add_from']==2){
                                        $("td:eq(1)", nRow).html("โปรแกรมโมชั่นคูปอง");
                                        $("td:eq(4)", nRow).html("ชุด");
                                      }

                                      $("#sum_price_desc").val(aData['sum_price_desc']);

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



    });




    $(document).ready(function() {
        $(document).on('click', '.btnProductAll1,.btnAihealth2,.btnAilada3,.btnAibody4,.btnPromotion8', function(event) {
          event.preventDefault();

          $('#frmFrontstorelist').show();
          $('.div-data-table-list-pro').hide();

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
                    "info":  true,
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
                        {data: 'id',   title :'<center>จำนวน</center>', className: 'text-center w140 ',render: function(d) {

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
        $(document).on('click', '.btnPromotion8', function(event) {
          event.preventDefault();

          $('#frmFrontstorelist').hide();
          $('.div-data-table-list-pro').show();

          $("#spinner_frame").show();

           $.fn.dataTable.ext.errMode = 'throw';
  
            var frontstore_id_fk = "{{@$sRow->id}}"; //alert(frontstore_id_fk);

            $(function() {
              $('#data-table-list-pro').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    destroy:true,
                    ajax: {
                        url: '{{ route('backend.frontstorelist-pro.datatable') }}',
                        data :{
                              frontstore_id_fk:frontstore_id_fk,
                            },
                          method: 'POST',
                        },
                    columns: [
                        {data: 'p_img',   title :'<center>รูป</center>', className: 'text-center w100 ',render: function(d) {
                           return '<img src='+d+' width="80">';
                        }},
                        {data: 'product_name',   title :'<center>รายการโปร</center>', className: 'text-left',render: function(d) {
                          return d;
                        }},
                        {data: 'pv',   title :'<center>PV</center>', className: 'text-center w50 ',render: function(d) {
                           return d;
                        }},
                        {data: 'selling_price',   title :'<center>ราคา</center>', className: 'text-center',render: function(d) {
                           return d;
                        }},
                        {data: 'select_amt',   title :'<center>จำนวน</center>', className: 'w120 ',render: function(d) {

                          if(d){
                          var v = d.split(':');

                           return '<input name="promotion_id_fk_pro[]" value="'+v[0]+'" type="hidden" ><div class="input-group inline-group"> '
                                +' <div class="input-group-prepend"> '
                                +'   <button promotion_id_fk="'+v[0]+'" limited_amt_person="'+v[1]+'" class="btn btn-outline-secondary btn-minus-product-pro "> '
                                +'     <i class="fa fa-minus"></i> '
                                +'   </button>'
                                +'   <input class=" quantity " min="0" name="quantity[]" value="0" type="number" readonly >'
                                +'   <div class="input-group-append"> '
                                +'   <button promotion_id_fk="'+v[0]+'" limited_amt_person="'+v[1]+'" class="btn btn-outline-secondary btn-plus-product-pro "> '
                                +'     <i class="fa fa-plus"></i> '
                                +'    </button> '
                                +'  </div> '
                                +' </div> ' ;
                              }
                        }},
                    ],
                    rowCallback: function(nRow, aData, dataIndex){

                          if (aData['frontstore_promotions_list'] > 0 ) {

                              $("td:eq(4)", nRow).html(
                                '<input name="promotion_id_fk_pro[]" value="'+aData['frontstore_promotions_list']+'" type="hidden" ><div class="input-group inline-group"> '
                                +' <div class="input-group-prepend"> '
                                +'   <button promotion_id_fk="'+aData['frontstore_promotions_list']+'" limited_amt_person="'+aData['limited_amt_person']+'" class="btn btn-outline-secondary btn-minus-product-pro "> '
                                +'     <i class="fa fa-minus"></i> '
                                +'   </button>'
                                +'   <input class=" quantity " min="0" name="quantity[]" value="'+aData['frontstore_promotions_list']+'" type="number" readonly >'
                                +'   <div class="input-group-append"> '
                                +'   <button promotion_id_fk="'+aData['frontstore_promotions_list']+'" limited_amt_person="'+aData['limited_amt_person']+'" class="btn btn-outline-secondary btn-plus-product-pro "> '
                                +'     <i class="fa fa-plus"></i> '
                                +'    </button> '
                                +'  </div> '
                                +' </div> ' 
                                );

                          }


                        
                      }                    
          
                });

            });

        });
     });



    $(document).ready(function() {
        $(document).on('click', '.btnCheckProCode', function(event) {
          event.preventDefault();

          var frontstore_id_fk = "{{@$sRow->id}}"; //alert(frontstore_id_fk);

          var txtSearchPro = $("#txtSearchPro").val();
          // $("#spinner_frame").show();
          if(txtSearchPro==''){
            $("#txtSearchPro").focus();
            return false;
          }

          // ตรวจสอบดูก่อนว่า ในตาราง `db_frontstore_products_list` เคยใช้รหัสนี้หรือไม่ และ มีสถานะ =  1=ใช้งานได้ หรือไม่
          // pro_status => 1=ใช้งานได้,2=ถูกใช้แล้ว,3=หมดอายุแล้ว,4=import excel,5=Gen code
           $.ajax({
               type:'POST',
               url: " {{ url('backend/ajaxCheckCouponUsed') }} ", 
               data:{ _token: '{{csrf_token()}}',txtSearchPro:txtSearchPro},
                success:function(msg){
                     console.log(msg);

                     // return false;

                     if(msg=="InActive"){
                        alert('! คูปอง รหัสนี้ ไม่อยู่ในสถานะที่ใช้งานได้ ');
                        $("#pro_name").hide();
                        $('.btnSavePro').hide();
                        $('.show_add_coupon').hide();
                        $('#data-table-promotion').hide();
                        $('#data-table-promotions-cost').hide();
                        return false;
                     }

                     // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

                           $.ajax({
                             type:'POST',
                             url: " {{ url('backend/ajaxGetPromotionCode') }} ", 
                             data:{ _token: '{{csrf_token()}}',txtSearchPro:txtSearchPro},
                              success:function(data){
                                   console.log(data);

                                    $("#promotion_id_fk").val(data);

                                            var oTable2;

                                            $(function() {
                                                oTable2 = $('#data-table-promotions-cost').DataTable({
                                                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                                    processing: true,
                                                    serverSide: true,
                                                    scroller: true,
                                                    scrollCollapse: true,
                                                    destroy: true,
                                                    scrollX: true,
                                                    ordering: false,
                                                    "bLengthChange": false ,
                                                    "searching": false,
                                                    "paging":   false,
                                                    "scrollX": false,
                                                    "info":     false,
                                                    "lengthChange": false,
                                                    scrollY: ''+($(window).height()-370)+'px',
                                                    iDisplayLength: 5,
                                                    ajax: {
                                                        url: '{{ route('backend.promotions_cost.datatable') }}',
                                                        data :{
                                                              promotion_id_fk_pro:data,
                                                            },
                                                          method: 'POST',
                                                        },
                                                    columns: [
                                                        {data: 'business_location', title :'BUSINESS LOCATION', className: 'text-left'},
                                                        {data: 'currency', title :'<center>สกุลเงิน</center>', className: 'text-center'},
                                                        {data: 'cost_price', title :'<center>ราคาทุน</center>', className: 'text-center'},
                                                        {data: 'selling_price', title :'<center>ราคาขาย</center>', className: 'text-center'},
                                                        {data: 'member_price', title :'<center>ราคาสมาชิก</center>', className: 'text-center'},
                                                        {data: 'pv', title :'<center>PV</center>', className: 'text-center'},
                                                    ],
                                            
                                                });
                                      
                                            });

                                },
                              error: function(jqXHR, textStatus, errorThrown) { 
                                  console.log(JSON.stringify(jqXHR));
                                  console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                              }
                          });


                          $.ajax({
                             type:'POST',
                             url: " {{ url('backend/ajaxGetPromotionName') }} ", 
                             data:{ _token: '{{csrf_token()}}',txtSearchPro:txtSearchPro},
                              success:function(data){
                                   console.log(data);
                                     $.each(data,function(key,value){
                                      $("#pro_name").val("ชื่อโปร : "+value.pro_name);
                                      $("#limited_amt_person").val(value.limited_amt_person);
                                      $(".plus-pro").attr('max',value.limited_amt_person);
                                     });
                                    
                                    $("#pro_name").show();
                                    if(data==''){
                                      $("#pro_name").hide();
                                      $('.btnSavePro').hide();
                                      $('.show_add_coupon').hide();
                                    }
                                   
                                },
                              error: function(jqXHR, textStatus, errorThrown) { 
                                  console.log(JSON.stringify(jqXHR));
                                  console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                  $("#pro_name").hide();
                              }
                          });

                          $('#data-table-promotion').show();
                          $('.btnSavePro').show();
                          $('.show_add_coupon').show();
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
                                    url: '{{ route('backend.promotion_cus_products.datatable') }}',
                                    data :{
                                        txtSearchPro:txtSearchPro,
                                      },
                                    method: 'POST'
                                  },
                                  columns: [
                                      // {data: 'id', title :'ID', className: 'text-center w10'},
                                      {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left'},
                                      {data: 'product_amt', title :'<center>จำนวน</center>', className: 'text-center'},
                                      {data: 'product_unit', title :'<center>หน่วย</center>', className: 'text-center'},
                                  ],
                                  rowCallback: function(nRow, aData, dataIndex){

                                    }

                              });
              
                       });

       // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                  },
            });
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@


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

        $("#modalAddFromPromotion").on("hidden.bs.modal", function(){
            $("#frmAddPro").find("input[type=text]").val("");
            $('#data-table-promotion').hide();
            $('.show_add_coupon').hide();
        });


        $(document).on('change', '#amt', function(event) {
            event.preventDefault();
            // $('.btnSaveAddlist ').focus();
            $("#frmFrontstoreAddList").submit();
        });


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

                                                var info = $(this).DataTable().page.info();
                                                $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                                                if(aData['add_from']==2){
                                                  $("td:eq(1)", nRow).html("โปรแกรมโมชั่นคูปอง");
                                                  $("td:eq(4)", nRow).html("ชุด");
                                                }

                                                $("#sum_price_desc").val(aData['sum_price_desc']);

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

                   $(document).on('click', '.cDelete', function(event) {
                       event.preventDefault();
                    
                        var table = $('#data-table-list').DataTable();
                        table.clear().draw();

                        var category_id = $(this).data('value');

                        $("#spinner_frame").show();

                        $.fn.dataTable.ext.errMode = 'throw';

                        setTimeout(function(){
                          $("#spinner_frame").hide();
                          // $(this).closest("tr").hide();
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

                                                var info = $(this).DataTable().page.info();
                                                $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                                                if(aData['add_from']==2){
                                                  $("td:eq(1)", nRow).html("โปรแกรมโมชั่นคูปอง");
                                                  $("td:eq(4)", nRow).html("ชุด");
                                                }

                                                $("#sum_price_desc").val(aData['sum_price_desc']);

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


             $('#delivery_province').change(function(){

                var province_id = this.value;
                // alert(province_id);

                 if(province_id != ''){
                   $.ajax({
                         url: " {{ url('backend/ajaxGetAmphur') }} ", 
                        method: "post",
                        data: {
                          province_id:province_id,
                          "_token": "{{ csrf_token() }}", 
                        },
                        success:function(data)
                        { 
                         if(data == ''){
                             alert('ไม่พบข้อมูลอำเภอ !!.');
                         }else{
                             var layout = '<option value="" selected>- เลือกอำเภอ -</option>';
                             $.each(data,function(key,value){
                                layout += '<option value='+value.id+'>'+value.amphur_name+'</option>';
                             });
                             $('#delivery_amphur').html(layout);
                             $('#delivery_tambon').html('<option value="" selected >กรุณาเลือกอำเภอก่อน</option>');
                         }
                        }
                      })
                 }
       
            });


             $('#delivery_amphur').change(function(){

                var amphur_id = this.value;
                // alert(amphur_id);

                 if(amphur_id != ''){
                   $.ajax({
                         url: " {{ url('backend/ajaxGetTambon') }} ", 
                        method: "post",
                        data: {
                          amphur_id:amphur_id,
                          "_token": "{{ csrf_token() }}", 
                        },
                        success:function(data)
                        { 

                         $('#delivery_zipcode').val('');
                         
                         if(data == ''){
                             alert('ไม่พบข้อมูลตำบล !!.');
                         }else{
                             var layout = '<option value="" selected>- เลือกตำบล -</option>';
                             $.each(data,function(key,value){
                                layout += '<option value='+value.id+'>'+value.tambon_name+'</option>';
                             });
                             $('#delivery_tambon').html(layout);
                         }
                        }
                      })
                 }
       
            });


             $('#delivery_tambon').change(function(){

                var tambon_id = this.value;
                // alert(tambon_id);

                 if(tambon_id != ''){
                   $.ajax({
                         url: " {{ url('backend/ajaxGetZipcode') }} ", 
                        method: "post",
                        data: {
                          tambon_id:tambon_id,
                          "_token": "{{ csrf_token() }}", 
                        },
                        success:function(data)
                        { 
                          console.log(data);
                         if(data == ''){
                             alert('ไม่พบข้อมูลรหัส ปณ. !!.');
                         }else{
                             $.each(data,function(key,value){
                                $('#delivery_zipcode').val(value.zip_code);
                             });
                             
                         }
                        }
                      })
                 }
       
            });


            $(document).ready(function() {
                  // $('.btnDelivery').trigger('click');
                  // $('.btnAddFromPromotion').trigger('click');
                  // $('#modalAddFromPromotion').modal('show');
                  // $('#modalAddFromProductsList').modal('show');
                  // $('.btnAddFromProdutcsList ').trigger('click');
                  // $('.btnPromotion8').trigger('click');
                  // $('.btnAddFromPromotion ').trigger('click');
                   // $('.btnAddList ').trigger('click');
                   // $('#modalDelivery').modal('show');
                   // $('#modalDelivery').modal('show');
            });

          </script>

@endsection

