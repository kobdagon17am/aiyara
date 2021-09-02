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

  .bx-check-circle { font-size: 20px;color: #00994d;font-weight: bold;}

  .btnAddAiCashModal02 {
    cursor: pointer;
  }
  .btnAddAiCashModal02:hover {
    color: blue;
    font-weight: bold;
    background-color: #FFFF00;
  }


    @media screen and (min-width: 676px) {
        .modal-dialog-choose-course {
          max-width: 1200px !important; /* New width for default modal */
        }
    }


        #aicash_choose{
          color: #4d4d4d !important;
          font-weight: bold;
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
             <h4 class="mb-0 font-size-18 test_clear_data "> จำหน่ายสินค้าหน้าร้าน > เปิดรายการขาย ({{\Auth::user()->position_level==1?'Supervisor/Manager':'CS'}}) </h4>

                    <a class="btn btn-secondary btn-sm waves-effect btnBack " href="{{ url("backend/frontstore") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>

        </div>

    </div>
</div>
<!-- end page title -->

<!-- ปิด หลัง save เพื่อไม่ให้การคำนวณยอดเงินผิดเพี้ยนไปจากเดิม หากต้องการแก้ไข ให้ไปเปิดบิลใหม่ ทำการยกเลิกบิลนี้ก่อน -->
@IF(!empty(@$sRow->pay_type_id_fk))
  @php
  $disAfterSave = ' disabled '
  @endphp
@ELSE
  @php
  $disAfterSave = ''
  @endphp
@ENDIF



 <?php 

 /*
 1  เงินโอน
2 บัตรเครดิต
3 Ai-Cash
4 Gift Voucher

5 เงินสด
6 เงินสด + Ai-Cash
7 เครดิต + เงินสด
8 เครดิต + เงินโอน
9 เครดิต + Ai-Cash
10  เงินโอน + เงินสด
11  เงินโอน + Ai-Cash

12  Gift Voucher + เงินโอน
13  Gift Voucher + บัตรเครดิต
14  Gift Voucher + Ai-Cash
15  PromptPay
16  TrueMoney
17  Gift Voucher + PromptPay
18  Gift Voucher + TrueMoney

*/

    if(@$sRow->pay_type_id_fk==6||@$sRow->pay_type_id_fk==8||@$sRow->pay_type_id_fk==9||@$sRow->pay_type_id_fk==10||@$sRow->pay_type_id_fk==11){
      $pay_type_transfer_aicash = " disabled ";
    }else{
      $pay_type_transfer_aicash = "";
    }

 ?>

<div class="row" style="margin-bottom: -4.1%;" >
    <div class="col-12">
        <div class="card">
            <div class="card-body">
              @if( empty(@$sRow) )
              <form id="frm-main" action="{{ route('backend.frontstore.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">

              @else
              <form id="frm-main"  action="{{ route('backend.frontstore.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">

                <!-- @method('PUT') -->

                      <input id="frontstore_id_fk" name="frontstore_id" type="hidden" value="{{@$sRow->id}}">
                      <input name="receipt_save_list" type="hidden" value="1">
                      <input name="invoice_code" type="hidden" value="{{@$sRow->invoice_code}}">
                      <input name="customers_id_fk" type="hidden" value="{{@$sRow->customers_id_fk}}">
                      <input name="this_branch_id_fk" type="hidden" value="{{@$sRow->branch_id_fk}}">


              @endif
                {{ csrf_field() }}


                      <div class="myBorder" style="background-color: white;">


                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label for="" class="col-md-4 col-form-label">สาขาที่ตั้งธุรกิจ : * </label>
                                  <div class="col-md-6">

                                       @if(!empty(@$sRow->branch_id_fk))

                                         <input type="hidden" name="branch_id_fk" value="{{$sRow->branch_id_fk}}"  >
                                         <input type="text" class="form-control" value="{{@$BranchName}}"  disabled="" >

                                       @else

                                         @if(@$sBranchs)
                                         <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating " required >
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


                                       @if(!empty(@$sRow->customers_id_fk))

                                         <input type="hidden" name="customers_id_fk" value="{{$sRow->customers_id_fk}}"  >
                                         <input type="text" class="form-control" value="{{@$CusName}}"  disabled="" >

                                       @else

                                         <select id="customers_id_fk" name="customers_id_fk" class="form-control" required >
                                         </select> 

                                           <!-- <input type="text" class="form-control customer " id="customer" name="customer" > -->
                                           <!-- <input type="hidden" id="customers_id_fk" name="customers_id_fk"  > -->

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
                                              @if(empty(@$sRow->distribution_channel_id_fk))
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
                                  <label for="" class="col-md-4 col-form-label"> ประเภทการซื้อ : * </label>
                                  <div class="col-md-7">

                         @if($ChangePurchaseType==1)
                                
                                         <!-- Gift Voucher -->
                                      @if(!empty(@$sRow->purchase_type_id_fk) && @$sRow->purchase_type_id_fk==5)  
                                           <input type="hidden" id="purchase_type_id_fk" name="purchase_type_id_fk" value="{{@$sRow->purchase_type_id_fk}}"  >
                                           <input type="text" class="form-control" value="{{@$PurchaseName}}"  disabled="" >
                                      @ELSE
                                     
                                         <select id="purchase_type_id_fk" name="purchase_type_id_fk" class="form-control select2-templating "  >
                                          <option value="">Select</option>
                                          @if(@$sPurchase_type)
                                            @foreach(@$sPurchase_type AS $r)

                                             @if(empty(@$sRow->purchase_type_id_fk))

                                                <option value="{{$r->id}}" {{ (@$r->id==@$sRow->purchase_type_id_fk)?'selected':'' }} >
                                                  {{$r->orders_type}}
                                                </option>

                                             @else

                                                @if($r->id<=3)
                                                <option value="{{$r->id}}" {{ (@$r->id==@$sRow->purchase_type_id_fk)?'selected':'' }} >
                                                  {{$r->orders_type}}
                                                </option>
                                                @endif

                                             @ENDIF

                                            @endforeach
                                          @endif
                                        </select>

                                    @ENDIF

                              @else

                                @if(!empty(@$sRow->purchase_type_id_fk))

                                   <input type="hidden" class="purchase_type_id_fk" id="purchase_type_id_fk" name="purchase_type_id_fk" value="{{@$sRow->purchase_type_id_fk}}"  >
                                   <input type="text" class="form-control" value="{{@$PurchaseName}}"  disabled="" >

                                @else

                                   <select id="purchase_type_id_fk" name="purchase_type_id_fk" class="form-control select2-templating " disabled  >
                                      <option value="">Select</option>
                                      @if(@$sPurchase_type)
                                        @foreach(@$sPurchase_type AS $r)
                                          <option value="{{$r->id}}" {{ (@$r->id==@$sRow->purchase_type_id_fk)?'selected':'' }} >
                                            {{$r->orders_type}}
                                          </option>
                                        @endforeach
                                      @endif
                                    </select>

                                @endif

                              @endif

                                  </div>
                                </div>
                              </div>
                            </div>

                          <div class="row">
                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label for="" class="col-md-4 col-form-label">AiStockist :  </label>
                                  <div class="col-md-6">

                                    @if($ChangePurchaseType==1)
                                         <input type="hidden" class="aistockist" name="aistockist" value="{{@$sRow->aistockist}}"  >
                                       @IF(@$sRow->aistockist!=="")
                                          <select id="aistockist" class="form-control select2-templating "  >
                                                  <option  selected >
                                                    {{@$CusAistockistName}}
                                                  </option>
                                          </select>
                                        @ELSE
                                         <select id="aistockist" class="form-control select2-templating "  >
                                         </select> 
                                        @ENDIF
                                    @else
                                         <input type="hidden" class="aistockist" name="aistockist" value="{{@$sRow->aistockist}}"  >
                                         <select id="aistockist" class="form-control select2-templating " disabled >
                                                  <option  selected >
                                                    {{@$CusAistockistName}}
                                                  </option>
                                          </select>
                                     @endif
           

                                  </div>
                                </div>
                              </div>

                               <div class="col-md-6">
                                <div class="col-md-12 form-group row" style="position: absolute;">
                                   <label for="" class="col-form-label">หมายเหตุ :  </label>
                                  <div class="col-md-10">
                                       <textarea class="form-control" id="note" name="note" rows="5" >{{ @$sRow->note }}</textarea>
                                  </div>
                                </div>
                              </div>
                            </div>



                        <div class="row">
                              <div class="col-md-6">
                                <div class="form-group row">
                                  <label for="" class="col-md-4 col-form-label"> Agency : </label>
                                  <div class="col-md-6" >

                                    @if($ChangePurchaseType==1)
                                         <input type="hidden" class="agency" name="agency" value="{{@$sRow->agency}}"  >
                                       @IF(@$sRow->agency!=="")
                                          <select id="agency" class="form-control select2-templating "  >
                                                  <option  selected >
                                                    {{@$CusAgencyName}}
                                                  </option>
                                          </select>
                                        @ELSE
                                         <select id="agency" class="form-control select2-templating "  >
                                         </select> 
                                        @ENDIF
                                    @else
                                         <input type="hidden" class="agency" name="agency" value="{{@$sRow->agency}}"  >
                                         <select id="agency" class="form-control select2-templating " disabled >
                                                  <option  selected >
                                                    {{@$CusAgencyName}}
                                                  </option>
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

               @if( empty(@$sRow) )

                      <div class="row">

                        <div class="col-md-6">
                          <div class="form-group row">
                            <label for="" class="col-md-4 col-form-label"> </label>
                            <div class="col-md-6">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="col-md-12 form-group row" style="position: absolute;">
                            <label for="" class="col-form-label"> </label>
                            <div class="col-md-10">
                            </div>
                          </div>
                        </div>

                      </div>

                      <div class="row">
                        <div class="col-md-6">
                        </div>

                        <div class="col-md-5 text-right">

                           @if(empty(@$sRow->purchase_type_id_fk))
                            
                              <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-14 ">
                              <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                              </button>  

                          @ENDIF

                        </div>
                      </div>
             </form>

             @endif

                       
 <div class="text-right">
<?php 
  // echo "วันที่สร้าง : ". $DATE_CREATED ;echo " / "; echo "วันที่เมื่อวานนี้ : ". $DATE_YESTERDAY ;echo " / "; echo "วันนี้ : ".$DATE_TODAY;
 ?>
     </div>         
           
           @if($ChangePurchaseType==1)

                @if(!empty(@$sRow->purchase_type_id_fk))

                 @if(@$sRow->purchase_type_id_fk==5)  
                     @ELSE
                          <div class="col-md-11 text-right div_btnSaveChangePurchaseType " style="display: none;">
                            <br>
                            <button type="button" class="btn btn-primary btn-sm waves-effect font-size-14 btnSaveChangePurchaseType ">
                            <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึก > แก้ไขข้อมูล
                            </button> 
                          </div>
                     @ENDIF

                 @ELSE
                    
                @ENDIF
           @endif
<br>

<?php
// echo @$sRow;
  if(!empty(@$sRow)){
 ?>
<div class="row"  >
  <div class="col-12">



          <div class="page-title-box d-flex justify-content-between ">

            <h4 class=" col-5 mb-0 font-size-18"><i class="bx bx-play"></i> 

             @if(@$sRow->purchase_type_id_fk==6) รายการคอร์สอบรม @ELSE รายการสินค้า/บริการ @ENDIF

             <?=@$sRow->invoice_code?'['.@$sRow->invoice_code.']':'';?> </h4>
             <div style="text-align: right;">


@IF(!empty(@$sRow->pay_type_id_fk))
<!-- ไม่ต้องแสดงปุ่ม เพิ่มต่างๆ  -->
@ELSE

           @if(@$sRow->purchase_type_id_fk==6)

              <a class="btn btn-success btn-aigreen btn-sm mt-1 btnCourse " href="#" >
                <i class="bx bx-plus align-middle mr-1 font-size-18"></i><span style="font-size: 14px;">สมัครคอร์ส </span>
              </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            @ELSE

      <!--         <a class="btn btn-success btn-aigreen btn-sm mt-1 btnPrint " href="{{ URL::to('backend/frontstore/print_receipt') }}/{{@$sRow->id}}" target=_blank style="display: none;" >
                <i class="bx bx-printer align-middle mr-1 font-size-18 "></i><span style="font-size: 14px;"> ใบเสร็จ [ 1 : A4 ]</span>
              </a>
              <a class="btn btn-success btn-aigreen btn-sm mt-1 btnPrint " href="{{ URL::to('backend/frontstore/print_receipt_02') }}/{{@$sRow->id}}" target=_blank style="display: none;" >
                <i class="bx bx-printer align-middle mr-1 font-size-18 "></i><span style="font-size: 14px;"> ใบเสร็จ [ 2 ]</span>
              </a> -->
              <!-- แลก  Ai Voucher -->
              <a class="btn btn-success btn-aigreen btn-sm mt-1 btnAddFromPromotion " href="#" >
                <i class="bx bx-plus align-middle mr-1 font-size-18"></i><span style="font-size: 14px;" >เพิ่มจากรหัสโปรโมชั่น</span>
              </a>
              <a class="btn btn-success btn-aigreen btn-sm mt-1 btnAddFromProdutcsList " href="#" >
                <i class="bx bx-plus align-middle mr-1 font-size-18"></i><span style="font-size: 14px;">เพิ่มแบบ List</span>
              </a>
              <a class="btn btn-success btn-aigreen btn-sm mt-1 btnAddList " href="#" >
                <i class="bx bx-plus align-middle mr-1 font-size-18"></i><span style="font-size: 14px;">เพิ่ม</span>
              </a>

            @ENDIF

@ENDIF

            </div>
          </div>

          <div class="form-group row ">
            <div class="col-md-12">

                  <table id="data-table-list" class="table table-bordered dt-responsive" style="width: 100%;"></table>

            </div>
          </div>

          <div class="form-group row ">
            <div class="col-md-12">

              @if(@$sRow->purchase_type_id_fk!=6) 

                @IF(@$check_giveaway)

              
                @ENDIF

              @ENDIF

            </div>
          </div>


                <div class="form-group row" style="display: none;">
                  <label for="" class="col-md-9 col-form-label"> Total : * </label>
                  <div class="col-md-2">
                    <input  class="form-control" id="sum_price_desc" name="sum_price_desc" value="{{@$sFrontstoreDataTotal[0]->total}}" readonly="" style="text-align: center;font-size: 16px;color: red;font-weight: bold;" />
                  </div>
                </div>

<?php
  // dd(@$sFrontstoreDataTotal);
  if(empty(@$sFrontstoreDataTotal)){
    $div_cost = "display:none;";
  }else{
    $div_cost = "";
  }
?>


        <div class="row div_cost " style="<?=$div_cost?>">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">

                <div class="table-responsive">
                  <table class="table table-striped mb-0">

                    <?php $bg_00 = @$sRow->delivery_location==0?'background-color: #00e673;color:black;':''; ?>
                    <?php $bg_01 = @$sRow->delivery_location==1?'background-color: #00e673;color:black;':''; ?>
                    <?php $bg_02 = @$sRow->delivery_location==2?'background-color: #00e673;color:black;':''; ?>
                    <?php $bg_03 = @$sRow->delivery_location==3?'background-color: #00e673;color:black;':''; ?>
                    <?php $bg_04 = @$sRow->delivery_location==4?'background-color: #00e673;color:black;':''; ?>

                    @IF(@$sRow->purchase_type_id_fk==6)
                        <?php
                          @$dis_addr = ' style="display: none;" ';
                        ?>
                        <?php $bg_00 = @$sRow->delivery_location==0?'background-color:"";color:"";':''; ?>
                        <?php $bg_01 = @$sRow->delivery_location==1?'background-color:"";color:"";':''; ?>
                        <?php $bg_02 = @$sRow->delivery_location==2?'background-color:"";color:"";':''; ?>
                        <?php $bg_03 = @$sRow->delivery_location==3?'background-color:"";color:"";':''; ?>
                        <?php $bg_04 = @$sRow->delivery_location==4?'background-color:"";color:"";':''; ?>
                    @ELSE
                        <?php
                          @$dis_addr = '';
                        ?>
                    @ENDIF


                    <thead <?=@$dis_addr?>  >
                      <tr style="background-color: #f2f2f2;">
                        <th>ระบุที่อยู่ในการจัดส่ง</th>
                        <input type="hidden" class="ShippingCalculate02" province_id="{{@$sRow->delivery_province_id}}" id="delivery_province_id" value="{{@$sRow->delivery_province_id}}">
                      </tr>
                    </thead>
                    <tbody <?=@$dis_addr?> >

                       <tr>
                        <th scope="row" class="bg_addr d-flex" style="<?=$bg_04?>" >

                          @IF(@$sRow->shipping_special == 1)
                           <input {{@$pay_type_transfer_aicash}} disabled type="radio" province_id="0" class="ShippingCalculate" name="delivery_location" id="addr_04" value="4" <?=(@$sRow->delivery_location==4?'checked':'')?>  > <label for="addr_04">&nbsp;&nbsp;จัดส่งพร้อมบิลอื่น </label>
                          @ELSE

                          <input {{@$pay_type_transfer_aicash}} type="radio" province_id="0" class="ShippingCalculate" name="delivery_location" id="addr_04" value="4" <?=(@$sRow->delivery_location==4?'checked':'')?> > <label for="addr_04">&nbsp;&nbsp;จัดส่งพร้อมบิลอื่น </label>
                          @ENDIF
                          
                        </th>
                      </tr>


                      <tr>
                        <th scope="row" class="bg_addr d-flex" style="<?=$bg_00?>">
                          @IF(@$sRow->shipping_special == 1)

                           <input {{@$pay_type_transfer_aicash}} disabled type="radio" province_id="0" class="ShippingCalculate" name="delivery_location" id="addr_00" value="0" <?=(@$sRow->delivery_location==0?'checked':'')?>  > <label for="addr_00">&nbsp;&nbsp;รับสินค้าด้วยตัวเอง > ระบุสาขา : </label>

                            <div class="col-md-6">
                             <select {{@$pay_type_transfer_aicash}} disabled id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating ShippingCalculate " {{@$dis_addr}}  >
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
                                  <option value="{{$r->id}}" {{ (@$r->id==@$sRow->branch_id_fk)?'selected':'' }} >
                                    {{$r->b_name}}
                                  </option>
                                  @endif
                                @endforeach
                              @endif
                             </select>
                           </div>


                          @ELSE
                           <input {{@$pay_type_transfer_aicash}} type="radio" province_id="0" class="ShippingCalculate" name="delivery_location" id="addr_00" value="0" <?=(@$sRow->delivery_location==0?'checked':'')?> > <label for="addr_00">&nbsp;&nbsp;รับสินค้าด้วยตัวเอง > ระบุสาขา : </label>

                            <div class="col-md-6">
                             <select {{@$pay_type_transfer_aicash}} id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating ShippingCalculate " {{@$dis_addr}}  >
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
                                  <option value="{{$r->id}}" {{ (@$r->id==@$sRow->branch_id_fk)?'selected':'' }} >
                                    {{$r->b_name}}
                                  </option>
                                  @endif
                                @endforeach
                              @endif
                             </select>
                           </div>

                          @ENDIF
                         
                           

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
                                      customers_detail.amphures_id_fk,
                                      customers_detail.district_id_fk,
                                      customers_detail.road,
                                      customers_detail.province_id_fk,
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
                                      Left Join dataset_provinces ON customers_address_card.card_province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_address_card.card_amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_address_card.card_district_id_fk = dataset_districts.id
                                      Left Join customers ON customers_address_card.customer_id = customers.id
                                      Left Join customers_detail ON customers.id = customers_detail.customer_id
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
                        <th scope="row" class="bg_addr" style="<?=$bg_01?>">
                          <input {{@$pay_type_transfer_aicash}} type="radio" province_id="<?=@$addr[0]->card_province?>" class="ShippingCalculate" name="delivery_location" id="addr_01" value="1" <?=(@$sRow->delivery_location==1?'checked':'')?> {{@$dis_addr}}  > <label for="addr_01"> ที่อยู่ตามบัตร ปชช. </label>
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
                                      customers_detail.amphures_id_fk,
                                      customers_detail.district_id_fk,
                                      customers_detail.road,
                                      customers_detail.province_id_fk,
                                      customers.prefix_name,
                                      customers.first_name,
                                      customers.last_name,
                                      dataset_provinces.name_th AS provname,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_districts.name_th AS tamname
                                      FROM
                                      customers_detail
                                      Left Join customers ON customers_detail.customer_id = customers.id
                                      Left Join dataset_provinces ON customers_detail.province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_detail.amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_detail.district_id_fk = dataset_districts.id
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
                        <th scope="row" class="bg_addr" style="<?=$bg_02?>">
                          <input {{@$pay_type_transfer_aicash}} type="radio" province_id="<?=@$addr[0]->province?>"
                           class="ShippingCalculate" name="delivery_location" id="addr_02" value="2" <?=(@$sRow->delivery_location==2?'checked':'')?> {{@$dis_addr}}  > <label for="addr_02"> ที่อยู่จัดส่งไปรษณีย์ </label>
                           <br><?=@$address?>
                        </th>
                      </tr>

                    <?php
                         @$addr = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                                dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname
                                from customers_addr_frontstore
                                Left Join dataset_provinces ON customers_addr_frontstore.province_id_fk = dataset_provinces.id
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
                        <th scope="row" class="bg_addr" style="<?=$bg_03?>">
                          <input {{@$pay_type_transfer_aicash}} type="radio" province_id="<?=@$addr[0]->province_id_fk?>" class="ShippingCalculate03" name="delivery_location" id="addr_03" value="3" <?=(@$sRow->delivery_location==3?'checked':'')?> {{@$dis_addr}}  > <label for="addr_03"> ที่อยู่กำหนดเอง </label>
                           <br><?=@$address?>
                        </th>
                      </tr>


        @IF(@$shipping_special[0]->status_special==1 || @$sRow->shipping_special == 1)
          @if( @$sRow->updated_at >= @$shipping_special[0]->updated_at  )
                      <tr>
                        <th scope="row"  style="">
                          <input {{@$pay_type_transfer_aicash}} type="checkbox" province_id="0" name="shipping_special" class="ShippingCalculate" id="addr_05" value="1" <?=(@$sRow->shipping_special==1?'checked':'')?> style="transform: scale(1.5);margin-right:5px; " {{@$dis_addr}}  > <label for="addr_05"> {{@$shipping_special[0]->shipping_name}} </label>
                          <input type="hidden" name="shipping_special_cost" value="{{@$shipping_special[0]->shipping_cost}}">
                        </th>
                      </tr>
                    @ELSE
                  <tr>
                        <th scope="row"  style="">
                          <input {{@$pay_type_transfer_aicash}} type="checkbox" province_id="0" name="shipping_special" class="ShippingCalculate" id="addr_05" value="1" <?=(@$sRow->shipping_special==1?'checked':'')?> style="transform: scale(1.5);margin-right:5px; " {{@$dis_addr}}  > <label for="addr_05"> {{@$shipping_special[0]->shipping_name}} </label>
                          <input type="hidden" name="shipping_special_cost" value="{{@$shipping_special[0]->shipping_cost}}">
                        </th>
                      </tr>
                      @ENDIF
                 @ENDIF

                    </tbody>
                  </table>
                </div>

              </div>
            </div>
          </div>


      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">



<!--         dataset_orders_type
        1 ทำคุณสมบัติ
        2 รักษาคุณสมบัติรายเดือน
        3 รักษาคุณสมบัติท่องเที่ยว
        4 เติม Ai-Stockist
        5 แลก  Ai Voucher

    dataset_pay_type
    1 โอนชำระ
    2 บัตรเครดิต
    3 Ai-Cash
    4  Ai Voucher
    5 เงินสด -->


            <div class="row">
              <div class="">
                <div class="divTable">
                  <div class="divTableBody">


                    <div class="divTableRow">
                      <div class="divTableCell" >&nbsp; </div>
                      <div class="divTH">
                        <label for="" >รวม PV : </label>
                        <!-- <input type="button" class="btnClickRefresh" value="R"> -->
                      </div>
                      <div class="divTableCell">

                        <input class="form-control f-ainumber-18 input-aireadonly " id="pv_total" name="pv_total" value="{{@$sRow->pv_total>0?number_format(@$sRow->pv_total,2):'0.00'}}" readonly="" />
                      </div>
                      <div class="divTableCell">
                      </div>
                    </div>


                    <div class="divTableRow">
                      <div class="divTableCell" >&nbsp; </div>
                      <div class="divTH">
                        <label for="" >ยอดรวมค่าสินค้า : </label>
                      </div>
                      <div class="divTableCell">

                        <input class="form-control f-ainumber-18 input-aireadonly " id="sum_price" name="sum_price" value="{{@$sRow->sum_price>0?number_format(@$sRow->sum_price,2):''}}" readonly="" />
                      </div>
                      <div class="divTableCell">
                      </div>
                    </div>


                     <div class="divTableRow">
                      <div class="divTableCell">&nbsp; </div>
                      <div class="divTH">
                        <label for="" >มูลค่าสินค้า : </label>
                      </div>
                      <div class="divTableCell">
                        <input id="product_value" name="product_value" class="form-control f-ainumber-18 input-aireadonly"
                        value="{{@$sRow->product_value>0?number_format(@$sRow->product_value,2):''}}"
                        readonly="" />
                      </div>
                      <div class="divTableCell">
                      </div>
                    </div>

                    <div class="divTableRow">
                      <div class="divTableCell" >&nbsp; </div>
                      <div class="divTH">
                        <label for="" >ภาษีมูลค่าเพิ่ม (7%) : </label>
                      </div>
                      <div class="divTableCell">
                        <input id="tax" name="tax" class="form-control f-ainumber-18 input-aireadonly" value="{{@$sRow->tax>0?number_format(@$sRow->tax,2):''}}" readonly="" />
                      </div>
                      <div class="divTableCell">
                      </div>
                    </div>

@IF(@$sRow->purchase_type_id_fk!=6)

          <div class="divTableRow " >
            <div class="divTableCell" >
              <span style="color: red;">
              </div>
              <div class="divTH" style='z-index: 1' >
                <label for="" >ค่าขนส่ง : </label>
              </div>
              <div class="divTableCell" style='z-index: 1' >
                <input class="form-control f-ainumber-18 input-aireadonly input_shipping_free "  value="(ค่าจัดส่งฟรี)" readonly style="display: none;" />
                <input class="form-control f-ainumber-18 input-aireadonly input_shipping_nofree " id="shipping_price" name="shipping_price"  value="{{@$sRow->shipping_price>0?number_format(@$sRow->shipping_price,2):'0.00'}}" readonly />
              </div>
              <div class="divTableCell" >
              </div>
          </div>
@ENDIF

@if(@$sRow->purchase_type_id_fk==5)

        <div class="divTableRow" >
          <div class="divTableCell" >
          </div>
          <div class="divTH">
            <label for="" > รูปแบบการชำระเงิน : </label>
          </div>
          <div class="divTableCell">
            <input class="form-control f-ainumber-18 input-aireadonly"  value=" Ai Voucher" readonly="" >
          </div>
        </div>


        <div class="divTableRow" >
          <div class="divTableCell" >
          </div>
          <div class="divTH">
            <label for="" > ยอด Ai Voucherr ที่มี : </label>
          </div>
          <div class="divTableCell">
            <input class="form-control f-ainumber-18 input-aireadonly" id="gift_voucher_cost" name="gift_voucher_cost" value="{{number_format(@$giftvoucher_this,2)}}" readonly="" >
          </div>
        </div>


@ENDIF


@if(@$sRow->purchase_type_id_fk==5)

                <div class="divTableRow " style=""  >
                    <div class="divTableCell" >
                    </div>
                    <div class="divTH">
                      <label for="" class="" >ยอด GIft Voucher ที่ชำระ : </label>
                    </div>
                    <div class="divTableCell">
                        <input class="form-control CalGiftVoucherPrice NumberOnly input-airight f-ainumber-18 input-aifill" name="gift_voucher_price" id="gift_voucher_price" value="{{@$sRow->gift_voucher_price>0?number_format(@$sRow->gift_voucher_price,2):''}}" required="" >
                    </div>
                    <div class="divTableCell">
                      <i class="bx bx-check-circle" title="ยอดชำระ"></i>
                    </div>
                  </div>

@ENDIF


                    <div class="divTableRow">
                      <div class="divTableCell">&nbsp; </div>
                      <div class="divTH">
                        @if(@$sRow->purchase_type_id_fk==5)
                        <label for="" > รูปแบบการชำระเงินอื่นๆ  : </label>
                        @ELSE
                        <label for="" > รูปแบบการชำระเงิน : </label>
                        @ENDIF
                      </div>
                      <div class="divTableCell">
<!-- ===================================
1 เงินโอน
8 เครดิต + เงินโอน
10  เงินโอน + เงินสด
11  เงินโอน + Ai-Cash
12  Gift Voucher + เงินโอน
===================================
3 Ai-Cash
6 เงินสด + Ai-Cash
9 เครดิต + Ai-Cash
=================================== -->
                        @if(@$sRow->check_press_save==2 && (@$sRow->purchase_type_id_fk==1 || @$sRow->purchase_type_id_fk==8 || @$sRow->purchase_type_id_fk==10 || @$sRow->purchase_type_id_fk==11 || @$sRow->purchase_type_id_fk==12))

                          @php
                          $disAfterSave = ' disabled '
                          @endphp

                        @ELSE

                          @php
                          $disAfterSave = ''
                          @endphp

                        @ENDIF

                             <select id="pay_type_id_fk" name="pay_type_id_fk" class="form-control select2-templating " {{@$disAfterSave}} required >
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
                      <div class="divTableCell">
                      </div>
                    </div>



                    <?php $div_fee = @$sRow->fee==0?"display: none;":''; ?>
                    <div class="divTableRow div_fee " style="<?=$div_fee?>">
                      <div class="divTableCell">&nbsp; </div>
                      <div class="divTH">
                        <label for="" >ค่าธรรมเนียมบัตรเครดิต : </label>
                      </div>
                      <div class="divTableCell">
                           <select {{@$disAfterSave}} id="fee" name="fee" class="form-control select2-templating " >
                                <option value="">Select</option>
                                @if(@$sFee)
                                @foreach(@$sFee AS $r)
                                <option value="{{$r->id}}" {{ (@$r->id==@$sRow->fee)?'selected':'' }}  >
                                  {{$r->txt_desc}}
                                  [{{$r->txt_value>0?$r->txt_value:$r->txt_fixed_rate}}]
                                </option>
                                @endforeach
                                @endif
                              </select>
                      </div>
                      <div class="divTableCell">
                      </div>
                    </div>


              <?php $div_fee = @$sRow->fee==0?"display: none;":''; ?>
                    <div class="divTableRow div_fee " style="<?=$div_fee?>">
                      <div class="divTableCell">&nbsp; </div>
                      <div class="divTH">
                        <label for="" > </label>
                      </div>
                      <div class="divTableCell div_charger_type " style="">
                          <?php $charger_type = @$sRow->charger_type==0||@$sRow->charger_type==''?"checked":''; ?>
                            <input {{@$disAfterSave}} type="radio" class="" id="charger_type_01" name="charger_type" value="1" <?=(@$sRow->charger_type==1?'checked':$charger_type)?>  > <label for="charger_type_01">&nbsp;&nbsp;ชาร์ทในบัตร </label>&nbsp;&nbsp;&nbsp;&nbsp;
                            <input {{@$disAfterSave}} type="radio" class="" id="charger_type_02" name="charger_type" value="2" <?=(@$sRow->charger_type==2?'checked':'')?>  > <label for="charger_type_02">&nbsp;&nbsp;แยกชำระ </label>
                      </div>
                      <div class="divTableCell">
                      </div>
                    </div>

                    <?php $show_div_credit = @$sRow->credit_price==0?"display: none;":''; ?>
                    <div class="divTableRow show_div_credit " style="<?=$show_div_credit?>" >
                      <div class="divTableCell" > </div>
                      <div class="divTH">
                          <label for="" class="" > บัตรเครดิต  :  </label>
                      </div>
                      <div class="divTableCell">
                          <input {{@$disAfterSave}} class="form-control CalPrice NumberOnly input-airight f-ainumber-18 input-aifill in-tx " id="credit_price" name="credit_price" value="{{number_format(@$sRow->credit_price,2)}}" required="" />
                      </div>
                    </div>

                    <?php $show_div_credit = @$sRow->credit_price==0?"display: none;":''; ?>
                    <div class="divTableRow show_div_credit " style="<?=$show_div_credit?>" >
                      <div class="divTableCell" >&nbsp; </div>
                      <div class="divTH">
                        <label for="" class="" > ค่าธรรมเนียมบัตร :  </label>
                      </div>
                      <div class="divTableCell">
                          <input {{@$disAfterSave}} class="form-control f-ainumber-18 input-aireadonly " id="fee_amt" name="fee_amt" value="{{number_format(@$sRow->fee_amt,2)}}" readonly  />
                      </div>

                    </div>

                   <?php $show_div_credit = @$sRow->credit_price==0?"display: none;":''; ?>
                    <div class="divTableRow show_div_credit " style="<?=$show_div_credit?>" >
                      <div class="divTableCell" >&nbsp; </div>
                      <div class="divTH">
                        <label for="" class="" > หักจากบัตรเครดิต :  </label>
                      </div>
                      <div class="divTableCell">
                       <input {{@$disAfterSave}} class="form-control f-ainumber-18-b input-aireadonly " id="sum_credit_price" name="sum_credit_price" value="{{number_format(@$sRow->sum_credit_price,2)}}" readonly />
                      </div>

                      <div class="divTableCell">
                        <i class="bx bx-check-circle" title="ยอดชำระ"></i>
                      </div>
                    </div>



                    <?php $div_account_bank_id = @$sRow->account_bank_id==0||@$sRow->account_bank_id==''?"display: none;":''; ?>
                    <div class="divTableRow div_account_bank_id " style="<?=$div_account_bank_id?>">
                      <div class="divTableCell">&nbsp; </div>
                      <div class="divTH">
                        <label for="" > เลือกบัญชีสำหรับโอน : </label>
                      </div>
                      <div class="divTableCell">
                              @if(@$sAccount_bank)
                              @foreach(@$sAccount_bank AS $r)
                                  <input {{@$disAfterSave}} type="radio" id="account_bank_id{{@$r->id}}"  name="account_bank_id" value="{{@$r->id}}" <?=(@$r->id==@$sRow->account_bank_id?'checked':'')?> > <label for="account_bank_id{{@$r->id}}">&nbsp;&nbsp;{{@$r->txt_account_name}} {{@$r->txt_bank_name}} {{@$r->txt_bank_number}}</label><br>
                              @endforeach
                            @endif

                     </div>
                      </div>


                   <?php $div_pay_with_other_bill = @$sRow->pay_with_other_bill==0||@$sRow->pay_with_other_bill==''?"display: none;":''; ?>
                    <div class="divTableRow div_pay_with_other_bill " style="<?=$div_pay_with_other_bill?>">
                      <div class="divTableCell">&nbsp; </div>
                      <div class="divTH">
                        <label for="" > </label>
                      </div>
                      <div class="divTableCell">
                    
                            <input {{@$disAfterSave}} type="checkbox" id="pay_with_other_bill" name="pay_with_other_bill" value="1" {{@$sRow->pay_with_other_bill==1?'checked':''}}> 
                            <label for="pay_with_other_bill">&nbsp;&nbsp;ชำระพร้อมบิลอื่น</label>
                            <br>

                            <input {{@$disAfterSave}} type="text" class="form-control" id="pay_with_other_bill_note" name="pay_with_other_bill_note" placeholder="(ระบุ) หมายเหตุ * กรณีชำระพร้อมบิลอื่น " value="{{@$sRow->pay_with_other_bill_note}}"> 

                      </div>
                      <div class="divTableCell">
                      </div>
                      </div>

                     <div class="divTableRow div_account_bank_id " style="<?=$div_account_bank_id?>">
                      <div class="divTableCell">&nbsp; </div>
                      <div class="divTH">
                        <label for="" > </label>
                      </div>
                      <div class="divTableCell">

                          <div class="d-flex">

                           <button {{@$disAfterSave}} type="button" class="btn btn-success btn-sm font-size-12 btnUpSlip " style="">อัพไฟล์สลิป (ถ้ามี)</button>
                            <?php if(!empty(@$sRow->transfer_money_datetime)){
                              $ds1 = substr(@$sRow->transfer_money_datetime, 0,10);
                              $ds = explode("-", $ds1);
                              $ds_d = $ds[2];
                              $ds_m = $ds[1];
                              $ds_y = $ds[0];
                              $ds = $ds_d.'/'.$ds_m.'/'.$ds_y.' '.(date('H:i',strtotime(@$sRow->transfer_money_datetime)));
                            }else{$ds='';} ?>
                              <input {{@$disAfterSave}} class="form-control transfer_money_datetime" autocomplete="off" value="{{$ds}}" style="width: 45%;margin-left: 5%;font-weight: bold;" placeholder="วัน เวลา ที่โอน" />
                              <input type="hidden" id="transfer_money_datetime" name="transfer_money_datetime" value="{{@$sRow->transfer_money_datetime}}"  />
                          </div>

                              <input {{@$disAfterSave}} type="file" accept="image/*" id="image01" name="image01" class="form-control" OnChange="showPreview_01(this)" style="display: none;" >

                         <span width="100" class="span_file_slip" >

                                @IF(!empty(@$sRow->file_slip))

                                 <img id="imgAvatar_01" src="{{ asset(@$sRow->file_slip) }}" style="margin-top: 5px;height: 180px;" >
                                  
                                @ELSE
                                  <img id="imgAvatar_01" src="{{ asset('local/public/images/file-slip.png') }}" style="margin-top: 5px;height: 180px;display: none;" >
                                @ENDIF

                                  <button {{@$disAfterSave}} type="button" data-id="{{@$sRow->id}}" class="btn btn-danger btn-sm font-size-10 btnDelSlip " style="vertical-align: bottom;margin-bottom: 5px;">ลบไฟล์</button>
                         </span>


                      </div>
                      <div class="divTableCell">
                      </div>
                    </div>



                    <div class="divTableRow div_account_bank_id " style="<?=$div_account_bank_id?>">
                        <div class="divTableCell" ></div>
                        <div class="divTH">
                          <label for="" class="label_transfer_price" > หมายเหตุ (1) : </label>
                        </div>
                        <div class="divTableCell">

                             <input {{@$disAfterSave}} type="text" class="form-control" id="note_fullpayonetime" name="note_fullpayonetime" placeholder="ยอดชำระเต็มจำนวน กรณีมีหลายยอดในการโอนครั้งเดียว" value="{{@$sRow->note_fullpayonetime}}" >

                        </div>
                         <div class="divTableCell">
                        </div>
                      </div>


                    <div class="divTableRow div_account_bank_id " style="<?=$div_account_bank_id?>">
                      <div class="divTableCell">&nbsp; </div>
                      <div class="divTH">
                        <label for="" >  </label>
                      </div>
                      <div class="divTableCell">

                          <div class="d-flex">

                           <button type="button" class="btn btn-success btn-sm font-size-12 btnUpSlip_02 " style="">อัพไฟล์สลิป (ถ้ามี)</button>
                            <?php if(!empty(@$sRow->transfer_money_datetime_02)){
                              $ds1_02 = substr(@$sRow->transfer_money_datetime_02, 0,10);
                              $ds_02 = explode("-", $ds1_02);
                              $ds_d_02 = $ds_02[2];
                              $ds_m_02 = $ds_02[1];
                              $ds_y_02 = $ds_02[0];
                              $ds_02 = $ds_d_02.'/'.$ds_m_02.'/'.$ds_y_02.' '.(date('H:i',strtotime(@$sRow->transfer_money_datetime_02)));
                            }else{$ds_02='';} ?>
                              <input class="form-control transfer_money_datetime_02 class_transfer_edit " autocomplete="off" value="{{$ds_02}}" style="width: 45%;margin-left: 5%;font-weight: bold;" placeholder="วัน เวลา ที่โอน" />
                              <input type="hidden" id="transfer_money_datetime_02" name="transfer_money_datetime_02" value="{{@$sRow->transfer_money_datetime_02}}"  />
                          </div>

                              <input type="file" accept="image/*" id="image02" name="image02" class="form-control" OnChange="showPreview_02(this)" style="display: none;" >

                         <span width="100" class="span_file_slip_02" >
                                @IF(!empty(@$sRow->file_slip_02))
                                  <img id="imgAvatar_02" src="{{ asset(@$sRow->file_slip_02) }}" style="margin-top: 5px;height: 180px;" >
                                 
                                @ELSE
                                  <img id="imgAvatar_02" src="{{ asset('local/public/images/file-slip.png') }}" style="margin-top: 5px;height: 180px;display: none;" >
                                @ENDIF

                                 <button type="button" data-id="{{@$sRow->id}}" class="btn btn-danger btn-sm font-size-10 btnDelSlip_02 " style="vertical-align: bottom;margin-bottom: 5px;">ลบไฟล์</button>
                         </span>


                      </div>
                      <div class="divTableCell">
                      </div>
                    </div>


                   <div class="divTableRow div_account_bank_id " style="<?=$div_account_bank_id?>">
                        <div class="divTableCell" ></div>
                        <div class="divTH">
                          <label for="" class="label_transfer_price" > หมายเหตุ (2) : </label>
                        </div>
                        <div class="divTableCell">

                             <input type="text" class="form-control" id="note_fullpayonetime_02" name="note_fullpayonetime_02" placeholder="" value="{{@$sRow->note_fullpayonetime_02}}" >

                        </div>
                         <div class="divTableCell">
                        </div>
                      </div>


                    <div class="divTableRow div_account_bank_id " style="<?=$div_account_bank_id?>">
                      <div class="divTableCell">&nbsp; </div>
                      <div class="divTH">
                        <label for="" >  </label>
                      </div>
                      <div class="divTableCell">

                          <div class="d-flex">

                           <button type="button" class="btn btn-success btn-sm font-size-12 btnUpSlip_03  " style="">อัพไฟล์สลิป (ถ้ามี)</button>
                            <?php if(!empty(@$sRow->transfer_money_datetime_03)){
                              $ds1_03 = substr(@$sRow->transfer_money_datetime_03, 0,10);
                              $ds_03 = explode("-", $ds1_03);
                              $ds_d_03 = $ds_03[2];
                              $ds_m_03 = $ds_03[1];
                              $ds_y_03 = $ds_03[0];
                              $ds_03 = $ds_d_03.'/'.$ds_m_03.'/'.$ds_y_03.' '.(date('H:i',strtotime(@$sRow->transfer_money_datetime_03)));
                            }else{$ds_03='';} ?>
                              <input class="form-control transfer_money_datetime_03 class_transfer_edit " autocomplete="off" value="{{$ds_03}}" style="width: 45%;margin-left: 5%;font-weight: bold;" placeholder="วัน เวลา ที่โอน" />
                              <input type="hidden" id="transfer_money_datetime_03" name="transfer_money_datetime_03" value="{{@$sRow->transfer_money_datetime_03}}"  />
                          </div>

                              <input type="file" accept="image/*" id="image03" name="image03" class="form-control" OnChange="showPreview_03(this)" style="display: none;" >

                         <span width="100" class="span_file_slip_03" >
                                @IF(!empty(@$sRow->file_slip_03))
                                  <img id="imgAvatar_03" src="{{ asset(@$sRow->file_slip_03) }}" style="margin-top: 5px;height: 180px;" >
                                 
                                @ELSE
                                  <img id="imgAvatar_03" src="{{ asset('local/public/images/file-slip.png') }}" style="margin-top: 5px;height: 180px;display: none;" >
                                @ENDIF

                                 <button type="button" data-id="{{@$sRow->id}}" class="btn btn-danger btn-sm font-size-10 btnDelSlip_03 " style="vertical-align: bottom;margin-bottom: 5px;">ลบไฟล์</button>

                         </span>


                      </div>
                      <div class="divTableCell">
                      </div>
                    </div>



                    <div class="divTableRow div_account_bank_id " style="<?=$div_account_bank_id?>">
                        <div class="divTableCell" ></div>
                        <div class="divTH">
                          <label for="" class="label_transfer_price" > หมายเหตุ (3) : </label>
                        </div>
                        <div class="divTableCell">

                             <input type="text" class="form-control" id="note_fullpayonetime_03" name="note_fullpayonetime_03" placeholder="" value="{{@$sRow->note_fullpayonetime_03}}" >

                        </div>
                         <div class="divTableCell">
                        </div>
                      </div>


                   <?php $show_div_transfer_price = @$sRow->pay_type_id_fk==8||@$sRow->pay_type_id_fk==10||@$sRow->pay_type_id_fk==11?"":'display: none;'; ?>
                    <div class="divTableRow show_div_transfer_price " style="<?=$show_div_transfer_price?>" >
                        <div class="divTableCell" ></div>
                        <div class="divTH">
                          <label for="" class="label_transfer_price" > ยอดเงินโอน : </label>
                        </div>
                        <div class="divTableCell">

                            @IF(@$sRow->pay_type_id_fk==8)
                            <input class="form-control input-airight f-ainumber-18-b input-aireadonly  class_transfer_edit " id="transfer_price" name="transfer_price" value="{{number_format(@$sRow->transfer_price,2)}}" >
                            @ELSE
                            <input  class="form-control CalPrice input-airight f-ainumber-18-b input-aifill class_transfer_edit " id="transfer_price" name="transfer_price" value="{{number_format(@$sRow->transfer_price,2)}}" >
                            @ENDIF

                        </div>
                         <div class="divTableCell">
                          <i class="bx bx-check-circle" title="ยอดชำระ"></i>
                        </div>
                      </div>


                  <?php $show_div_aicash_price = @$sRow->pay_type_id_fk==6||@$sRow->pay_type_id_fk==9||@$sRow->pay_type_id_fk==11?"":'display: none;'; ?>

                  <div class="divTableRow show_div_aicash_price " style="<?=$show_div_aicash_price?>">
                      <div class="divTableCell" ></div>
                      <div class="divTH">
                        <label for="aicash_price" class="" > ระบุรหัสสมาชิก Ai-cash :  </label>
                      </div>
                      <div class="divTableCell">
                         
                        @if(!empty(@$sRow->member_id_aicash))

                          <input type="hidden" class="form-control" name="member_id_aicash" id="member_id_aicash" value="{{@$sRow->member_id_aicash}}" >
                          <input type="hidden" class="form-control" name="member_name_aicash" id="member_name_aicash" value="{{@$Customer_name_Aicash}}" >

                          <select {{@$disAfterSave}} id="aicash_choose" class="form-control "  >
                             <option value="{{@$sRow->member_id_aicash}}" selected >{{@$Customer_name_Aicash}}</option>
                          </select> 

                        @else

                          <input type="hidden" name="member_id_aicash" id="member_id_aicash" >
                          <select id="aicash_choose" class="form-control "  >
                            <option value=""  >-Select-</option>
                          </select> 
                        @endif

                         <select  id="member_id_aicash_select" name="member_id_aicash_select" class="form-control"  ></select> 

                      </div>
                       <div class="divTableCell">
                        </div>
                    </div>

                   <div class="divTableRow show_div_aicash_price " style="<?=$show_div_aicash_price?>">
                        <div class="divTableCell" >
                        </div>

                        
@IF(!empty(@$sRow->pay_type_id_fk))


                      <div class="divTH">
                      
                        <label  for="" class="btnAddAiCashModal02" > ยอด Ai-Cash คงเหลือ : </label>
                        </div>

                        <div class="divTableCell">
                            <input  class="form-control f-ainumber-18 input-aireadonly  "  value="{{@$Cus_Aicash}}" id="aicash_remain"  readonly="" >
                        </div>

                        <div class="divTableCell">

                     
                         <button {{@$disAfterSave}} type="button" class="btn btn-primary font-size-14 btnCalAddAicash " style="padding: 3px;">ดำเนินการ</button>

                        </div>

                      </div>

@ELSE

                      <div class="divTH">
                      
                        <label  for="" class="btnAddAiCashModal02" > ยอด Ai-Cash คงเหลือ : </label>
                        </div>

                        <div class="divTableCell">
                            <input {{@$disAfterSave}} class="form-control f-ainumber-18 input-aireadonly  " name="aicash_remain" id="aicash_remain" value="{{@$Cus_Aicash}}" readonly="" >
                        </div>

                        <div class="divTableCell">

                     
                         <button {{@$disAfterSave}} type="button" class="btn btn-primary font-size-14 btnCalAddAicash " style="padding: 3px;">ดำเนินการ</button>

                        </div>

                      </div>
@ENDIF



                  <div class="divTableRow show_div_aicash_price " style="<?=$show_div_aicash_price?>">
                      <div class="divTableCell" ></div>
                      <div class="divTH">
                        <label for="aicash_price" class="" > ยอด Ai-cash ที่ชำระ :  </label>
                      </div>
                      <div class="divTableCell">
<!-- CalPriceAicash -->
            @IF(@$sRow->pay_type_id_fk==6)
              <input {{@$disAfterSave}} class="form-control CalPriceAicash input-airight f-ainumber-18-b NumberOnly in-tx input-aifill " id="aicash_price" name="aicash_price" value="{{number_format(@$sRow->aicash_price,2)}}" >
            @ELSE
              <input {{@$disAfterSave}} class="form-control input-airight f-ainumber-18-b input-aireadonly " id="aicash_price" name="aicash_price" value="{{number_format(@$sRow->aicash_price,2)}}" readonly="" >
            @ENDIF

                      </div>
                       <div class="divTableCell">
                          <i class="bx bx-check-circle" title="ยอดชำระ"></i>
                        </div>
                    </div>



                  <div class="divTableRow show_div_cash_price " style="display: none;">
                      <div class="divTableCell" ></div>
                      <div class="divTH">
                        <label for="cash_price" class="" > cash_price :  </label>
                      </div>
                      <div class="divTableCell">
                          <input class="form-control input-airight f-ainumber-18 " id="cash_price" name="cash_price" value="{{number_format(@$sRow->cash_price,2)}}" readonly >
                      </div>
                    </div>



                    <?php $show_div_cash_pay = (@$sRow->pay_type_id_fk==''||@$sRow->pay_type_id_fk==8||@$sRow->pay_type_id_fk==9||@$sRow->pay_type_id_fk==11)?"display: none;":''; ?>
                      <div class="divTableRow show_div_cash_pay " style="<?=$show_div_cash_pay?>"  >
                        <div class="divTableCell" >
                        </div>
                        <div class="divTH">
                          <label for="" class="label_cash_pay" >ยอดเงินสดที่ต้องชำระ : </label>
                        </div>
                        <div class="divTableCell">
                          <?php

                          // echo @$gift_voucher;
                              if(@$gift_voucher>0){
                                if(@$sRow->sum_price>@$gift_voucher){
                                  $cash_pay = @$sRow->sum_price-@$gift_voucher+@$sRow->shipping_price;
                                }else{
                                  $cash_pay = @$gift_voucher-@$sRow->sum_price+@$sRow->shipping_price;
                                }
                                ?>
                                <input class="form-control f-ainumber-18 input-aireadonly in-tx " name="cash_pay" id="cash_pay" value="{{number_format(@$cash_pay,2)}}" readonly="" >
                                <?php
                              }else{

                                // echo $sRow->cash_pay;

                                $cash_pay = @$sRow->cash_pay ;
                                ?>
                                <input class="form-control f-ainumber-18-b input-aireadonly in-tx " name="cash_pay" id="cash_pay" value="{{number_format(@$cash_pay,2)}}" readonly="" >
                                <?php
                              }

                          ?>

                        </div>

                        <div class="divTableCell">
                          <i class="bx bx-check-circle" title="ยอดชำระ"></i>
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
                          <label class="" > </label>
                        </div>
                        <div class="divTableCell">
<!-- 
                          <button type="submit" class="btn btn-primary btn-sm waves-effect font-size-16 class_btnSave " style="float: right;"  >
                                <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูลใบเสร็จ
                                </button> -->

                    <?php 
                                // ประเภทโอน
                    if(@$sRow->pay_type_id_fk==1 || @$sRow->pay_type_id_fk==8 || @$sRow->pay_type_id_fk==10 || @$sRow->pay_type_id_fk==11 || @$sRow->pay_type_id_fk==12){
                    ?>

                         <input type="hidden" name="pay_type_transfer_slip" value="1">

                          <button type="button" class="btn btn-primary btn-sm waves-effect font-size-16 class_btnSave " style="float: right;" disabled >
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูลใบเสร็จ
                          </button> 

                    <?php }else{ ?>

                          <button type="button" class="btn btn-primary btn-sm waves-effect font-size-16 class_btnSave " style="float: right;" disabled >
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูลใบเสร็จ
                          </button>                       

                    <?php }?>

                              <!--    <button type="button" class="btn btn-primary btn-sm waves-effect font-size-16 class_btnSaveTransferType " style="float: right;display: none;"  >
                                <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูลใบเสร็จ
                                </button> 
  -->

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
                              @IF(@$sRow->id)
                                 <table id="data-table-list-pro" class="table table-bordered " style="width: 100%;"></table>
                              @ENDIF
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
          <input name="customers_addr_frontstore_id" type="hidden" value="{{@$sRow->id}}">
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
                        <option value="{{$r->id}}" {{ (@$r->id==@$CusAddrFrontstore[0]->province_id_fk)?'selected':'' }} >
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
                  <label for="" class="col-md-4 col-form-label"> เบอร์มือถือ : </label>
                  <div class="col-md-7">
                    <input type="text" name="delivery_tel" class="form-control" value="{{@$CusAddrFrontstore[0]->tel}}"  required >
                  </div>
                </div>

                <div class="form-group row">
                  <label for="" class="col-md-4 col-form-label"> เบอร์บ้าน : </label>
                  <div class="col-md-7">
                    <input type="text" name="delivery_tel_home" class="form-control" value="{{@$CusAddrFrontstore[0]->tel_home}}"  >
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



<div id="modalAddAiCash" tabindex="-1" role="dialog" aria-labelledby="modalAddAiCash" aria-hidden="true" class="modal fade" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddAiCash"><b><i class="bx bx-play"></i>เติม Ai-Cash</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


   <form  id="frmAddAiCash" action="{{ route('backend.frontstorelist.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
      <!-- <input name="frontstore_id" type="hidden" value="{{@$sRow->id}}"> -->
      <input name="add_ai_cash" type="text" value="add_ai_cash">
      <input name="member_id_aicash_modal" id="member_id_aicash_modal" type="text" >
      {{ csrf_field() }}

      <div class="modal-body">
<!--
       <iframe id="iframe" src="{{url('backend/add_ai_cash/1/edit')}}" width=750 height=350 marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=0 scrolling="yes"></iframe> -->

      </div>

      <div class="modal-footer">
        <!-- <button type="submit" class="btn btn-primary " style="display: none;"><i class="bx bx-save font-size-16 align-middle "></i> Save</button> -->
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
      </div>

 </form>

    </div>
  </div>
</div>




<div class="modal fade" id="modalCourse" tabindex="-1" role="dialog" aria-labelledby="modalCourseTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-choose-course " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCourseTitle"><b><i class="bx bx-play"></i>รายการคอร์ส</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

          <form action="{{ route('backend.frontstorelist.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input name="add_course" type="hidden" value="1">
            <input name="frontstore_id" type="hidden" value="{{@$sRow->id}}">
            {{ csrf_field() }}
            <div class="modal-body">
          
              <div class="row">
                <div class="col-12">
                  <table id="data-table-choose-course" class="table table-bordered dt-responsive" style="width: 100%;"></table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-right  "  >
                <button type="submit" class="btn btn-primary btnSaveCourse " style="width: 8%;" >Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-left: 2px;">Close</button>
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


            

            var frontstore_id_fk = $("#frontstore_id_fk").val(); ////alert(frontstore_id_fk);
            var purchase_type_id_fk = "{{@$sRow->purchase_type_id_fk}}"; ////alert(frontstore_id_fk);
            var oTable;

            if(purchase_type_id_fk==6){
              var desc = "ชื่อคอร์ส";
            }else{
              var desc = "ชื่อสินค้า/บริการ";
            }

            $(function() {
              $.fn.dataTable.ext.errMode = 'throw';
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
                    // scrollY: ''+($(window).height()-370)+'px',
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
                        {data: 'product_name',   title :'<center> '+desc+' </center>', className: 'text-left',render: function(d) {
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
                      // 'select': {
                      //    'style': 'multi'
                      // },
                    rowCallback: function(nRow, aData, dataIndex){

                      var info = $(this).DataTable().page.info();
                      $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                      if(aData['add_from']==2){
                        $("td:eq(1)", nRow).html("โปรแกรมโมชั่นคูปอง");
                        $("td:eq(4)", nRow).html("ชุด");
                      }

                      if(aData['type_product']=='course'){
                        $("td:eq(4)", nRow).html("คอร์ส");
                      }

                      // // // // console.log(frontstore_id_fk);
                      // // // console.log(aData['pay_type']);

                      if(aData['pay_type'] !== "" && aData['pay_type'] !== 0){

                          $('td:last-child', nRow).html('-');

                      }else{

                            $('td:last-child', nRow).html(''
                            + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstorelist.index') }}/'+aData['id']+'" data-id="'+aData['id']+'"  frontstore_id_fk="'+aData['frontstore_id_fk']+'" class="btn btn-sm btn-danger cCancel "><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                          ).addClass('input');

                      }

                      

                    }
                });
              $.fn.dataTable.ext.errMode = 'throw';

            });



         $(document).ready(function() {


          

            $(document).on('change', '#purchase_type_id_fk', function(event) {
                  $(".div_btnSaveChangePurchaseType").show();
            });



            $(document).on('click', '.btnSaveChangePurchaseType', function(event) {
               var orders_id_fk = "{{@$sRow->id}}";
               var purchase_type_id_fk = $("#purchase_type_id_fk").val();
               var aistockist = $("input[name=aistockist]").val();
               var agency = $("input[name=agency]").val();

               console.log(orders_id_fk);
               console.log(purchase_type_id_fk);
               console.log(aistockist);
               console.log(agency);

               // return false;

                         Swal.fire({
                          title: 'ยืนยัน ? การแก้ไขข้อมูล ',
                          type: 'question',
                          showCancelButton: true,
                          confirmButtonColor: '#556ee6',
                          cancelButtonColor: "#f46a6a"
                          }).then(function (result) {
                            // // // console.log(result);
                              if (result.value) {
                                 $(".myloading").show();
                                     $.ajax({
                                       type:'POST',
                                       url: " {{ url('backend/ajaxSaveChangePurchaseType') }} ",
                                       data: { _token: '{{csrf_token()}}', 
                                           orders_id_fk:orders_id_fk,
                                           purchase_type_id_fk:purchase_type_id_fk,
                                           aistockist:aistockist,
                                           agency:agency,
                                        },
                                        success:function(data){
                                               
                                                console.log(data);

                                                setTimeout(function(){
                                                    $(".myloading").hide();
                                                    location.reload();
                                                  }, 3000);  
                                          },
                                    });
                              }else{
                                 $(".myloading").hide();
                              }
                        });

             });



           $(document).on('click', '.cCancel', function(event) {

            var id = $(this).data('id');
            var frontstore_id_fk = $(this).attr('frontstore_id_fk');
         
              if (!confirm("ยืนยัน ? เพื่อลบรายการ ")){
                  return false;
              }else{
              $.ajax({
                  url: " {{ url('backend/ajaxDeLProductOrderBackend') }} ", 
                  method: "post",
                  data: {
                    "_token": "{{ csrf_token() }}", 
                    id:id,
                    frontstore_id_fk:frontstore_id_fk,
                  },
                  success:function(data)
                  { 
                    console.log(data);
                    // return false;

                        var frontstore_id_fk = $("#frontstore_id_fk").val();
                        $.ajax({
                             type:'POST',
                             url: " {{ url('backend/ajaxForCheck_press_save') }} ", 
                             data:{ _token: '{{csrf_token()}}',frontstore_id_fk:frontstore_id_fk},
                              success:function(data){
                                   // // console.log(data); 
                                },
                              error: function(jqXHR, textStatus, errorThrown) { 
                                  // // console.log(JSON.stringify(jqXHR));
                              }
                          });


                        // Swal.fire({
                        //   type: 'success',
                        //   title: 'ทำการลบรายการสินค้าที่ระบุเรียบร้อยแล้ว',
                        //   showConfirmButton: false,
                        //   timer: 2000
                        // });

                        // setTimeout(function () {
                          // $('#data-table-list').DataTable().clear().draw();
                          //fnGetDBfrontstore();

                              // $.ajax({
                              //    type:'POST',
                              //    dataType:'JSON',
                              //    url: " {{ url('backend/ajaxClearCostFrontstore') }} ",
                              //    data: { _token: '{{csrf_token()}}', frontstore_id_fk:frontstore_id_fk },
                              //    success:function(data){

                                  location.reload();

                              //    },
                              // });

                          
                        // }, 1500);
                  }
                });

            }

              
            });
                
      });

    </script>

</script>





<script type="text/javascript">

// xxxxxxxxxxxx
            

            var frontstore_id_fk = $("#frontstore_id_fk").val(); ////alert(frontstore_id_fk);
            var order_type = "{{@$sRow->purchase_type_id_fk}}"; 

            $(function() {
              $.fn.dataTable.ext.errMode = 'throw';
              $('#data-table-list-pro').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('backend.frontstorelist_pro.datatable') }}',
                        data :{
                              frontstore_id_fk:frontstore_id_fk,
                              order_type:order_type,
                            },
                          method: 'POST',
                        },
                    columns: [
                        {data: 'p_img',   title :'<center>รูป</center>', className: 'text-center w100 ',render: function(d) {
                           if(d==null){
                              return '';
                           }else{
                            return '<img src='+d+' width="80">';
                           } 
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
                $.fn.dataTable.ext.errMode = 'throw';
            });


</script>


<script type="text/javascript">

    $(document).ready(function() {

        //fnGetDBfrontstore();

        $(document).on('click', '.btnAddFromPromotion', function(event) {
          event.preventDefault();
            $('#modalAddFromPromotion').modal('show');
         });

        $(document).on('click', '.btnAddFromProdutcsList', function(event) {
          event.preventDefault();

          var frontstore_id_fk = $("#frontstore_id_fk").val();
          var order_type = $("#purchase_type_id_fk").val();
          // var order_type = "{{@$sRow->purchase_type_id_fk}}"; 
          // alert(frontstore_id_fk);
          // alert(order_type);

            $("#spinner_frame").show();

            var oTable;
            $(function() {
                $.fn.dataTable.ext.errMode = 'throw';
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
                              category_id: '' ,
                              order_type: order_type ,
                            },
                          method: 'POST',
                        },
                    columns: [
                        {data: 'p_img',   title :'<center>รูป</center>', className: 'text-center w100 ',render: function(d) {
                           if(d==null){
                              return '';
                           }else{
                            return '<img src='+d+' width="80">';
                           } 
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
                $('.div_cost').hide();
            })

            $(document).on('click', '.btnCourse', function(event) {
                event.preventDefault();
                $('#modalCourse').modal('show');
            });


            $('#modalAddFromProductsList,#modalAddList').on('hidden.bs.modal', function () {
                $("#spinner_frame").show();
                // $(".myloading").show();
                setTimeout(function(){
                  window.location.reload(true);
                },1000);
                
            });


             $(document).on('click', '.btnClickRefresh', function () {
                  $(".myloading").show();
                  location.reload();
              });





      });


    $(document).ready(function() {


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
            // localStorage.setItem('branch_id_fk', id);
        });

        // if(localStorage.getItem('branch_id_fk')){
        //     $('#branch_id_fk').val(localStorage.getItem('branch_id_fk')).select2();
        // }


        // if(localStorage.getItem('aicash_remain')){
        //   $('#aicash_remain').val(localStorage.getItem('aicash_remain'));
        // }


        if($("#aicash_remain").val()>0){
          $(".btnCalAddAicash").show();
        }else{
          $(".btnCalAddAicash").hide();
        }


    $(document).on('change', '#gift_voucher_id', function(event) {
      var id = $(this).val();
            $.ajax({
                 type:'POST',
                 url: " {{ url('backend/ajaxGetVoucher') }} ",
                 data:{ _token: '{{csrf_token()}}',id:id },
                  success:function(data){
                      // // // // console.log(data);
                       $.each(data,function(key,value){
                          // $(".label_money_cash").html("ยอด"+value.pay_type+" :");
                          $("#gift_voucher_cost").val(value.banlance);
                       });
                       $("#spinner_frame").hide();
                    },
                  error: function(jqXHR, textStatus, errorThrown) {
                      // // // // console.log(JSON.stringify(jqXHR));
                      // // // // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                      $("#spinner_frame").hide();
                  }
              });

        });



        $(document).on('change', '#branch_id_fk', function(event) {
              $("#addr_00").prop("checked", true);
        });


// รับสินค้าด้วยตัวเอง 
       $(document).on('click', '#addr_00', function(event) {
              var v = $(this).val();
              // // // console.log(v);
              $("#addr_05").prop("disabled", true);
        });
// ที่อยู่ตามบัตร ปชช.
       $(document).on('click', '#addr_01', function(event) {
              var v = $(this).val();
              // // // console.log(v);
              $("#addr_05").prop("disabled", false);
        });
// ที่อยู่จัดส่งไปรษณีย์
       $(document).on('click', '#addr_02', function(event) {
              var v = $(this).val();
              // // // console.log(v);
              $("#addr_05").prop("disabled", false);
        });

// ที่อยู่การจัดส่ง (กำหนดเอง)
       $(document).on('click', '#addr_03', function(event) {
              var v = $(this).val();
              // // // console.log(v);
              if(v==3){
                $('#modalDelivery').modal('show');
              }
              $("#addr_05").prop("disabled", false);
        });
// จัดส่งพร้อมบิลอื่น
       $(document).on('click', '#addr_04', function(event) { 
              var v = $(this).val();
              // // // console.log(v);
              $("#addr_05").prop("disabled", true);
        });
// ส่งแบบพิเศษ / Premium       
       $(document).on('click', '#addr_05', function(event) {
              var v = $(this).val();
              // // // console.log(v);
            if (this.checked) {
              $("#addr_04").prop("disabled", true);
              $("#addr_00").prop("disabled", true);
              $("#branch_id_fk").prop("disabled", true);
            }else{
              $("#addr_04").prop("disabled", false);
              $("#addr_00").prop("disabled", false);
              $("#branch_id_fk").prop("disabled", false);
            }
        });


        // $('#note').change(function() {
        //     localStorage.setItem('note', this.value);
        // });

        // if(localStorage.getItem('note')){
        //   $('#note').val(localStorage.getItem('note'));
        // }

        $(document).on('click', '.btn-plus', function(e) {
          // event.preventDefault();

          const input = $(e.target).closest('.input-group').find('input');
          if (input.is('input')) {
             // alert(input.val());
             // frmFrontstorelist
             // $("#frmFrontstorelist").submit();
           // var d =  $("#frmFrontstorelist").serialize();
           var d =  $(".frmFrontstorelist").serialize();
           var product_id_fk_this =  $(this).data('product_id_fk');

           var frontstore_id_fk = $("#frontstore_id_fk").val(); ////alert(frontstore_id_fk);
           var purchase_type_id_fk = "{{@$sRow->purchase_type_id_fk}}"; ////alert(frontstore_id_fk);
           // alert(purchase_type_id_fk);
           // return false;
            $.ajax({
               type:'POST',
               url: " {{ url('backend/frontstorelist/plus') }} ",
               // data:{ d:d , _token: '{{csrf_token()}}' },
               data: $(".frmFrontstorelist").serialize()+"&product_id_fk_this="+product_id_fk_this+"&purchase_type_id_fk="+purchase_type_id_fk,
                success: function(response){ // What to do if we succeed
                       // // // // console.log(response);
                       // // // // console.log(frontstore_id_fk);
                       // return false;
                        var oTable;

                          $(function() {
                            $.fn.dataTable.ext.errMode = 'throw';
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
                                  // scrollY: ''+($(window).height()-370)+'px',
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


                                    if(aData['type_product']=='course'){
                                      $("td:eq(4)", nRow).html("คอร์ส");
                                    }

                                    $('td:last-child', nRow).html(''
                                      + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstorelist.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                    ).addClass('input');


                                    //fnGetDBfrontstore();
                                    setTimeout(function(){
                                      $(".ShippingCalculate02").trigger('click');
                                    }, 1000);


                                  }
                              });
                              $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                                oTable.draw();
                              });

                          });


                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    // // // // console.log(JSON.stringify(jqXHR));
                    // // // // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });


          }


        });


        $(document).on('click', '.btn-minus', function(e) {
          // event.preventDefault();

          const input = $(e.target).closest('.input-group').find('input');
          if (input.is('input')) {
             // alert(input.val());
             // frmFrontstorelist
             // $("#frmFrontstorelist").submit();
           var d =  $(".frmFrontstorelist").serialize();
           var product_id_fk_this =  $(this).data('product_id_fk');

           var frontstore_id_fk = $("#frontstore_id_fk").val(); ////alert(frontstore_id_fk);
           var purchase_type_id_fk = "{{@$sRow->purchase_type_id_fk}}"; ////alert(frontstore_id_fk);

           // alert(product_id_fk_this);

            $.ajax({
               type:'POST',
               url: " {{ url('backend/frontstorelist/minus') }} ",
               // data:{ d:d , _token: '{{csrf_token()}}' },
               data: $(".frmFrontstorelist").serialize()+"&product_id_fk_this="+product_id_fk_this+"&purchase_type_id_fk="+purchase_type_id_fk,
                success: function(response){ // What to do if we succeed
                       // // // // console.log(response);

                        var oTable;
                            $(function() {
                              $.fn.dataTable.ext.errMode = 'throw';
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
                                    // scrollY: ''+($(window).height()-370)+'px',
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

                                        if(aData['type_product']=='course'){
                                          $("td:eq(4)", nRow).html("คอร์ส");
                                        }

                                        $('td:last-child', nRow).html(''
                                          + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstorelist.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                        ).addClass('input');


                                        //fnGetDBfrontstore();
                                         setTimeout(function(){
                                            $(".ShippingCalculate02").trigger('click');
                                          }, 1000);


                                      }
                                });
                                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                                  oTable.draw();
                                });

                            });


                },
                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    // // // // console.log(JSON.stringify(jqXHR));
                    // // // // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
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

             var frontstore_id_fk = $("#frontstore_id_fk").val(); ////alert(frontstore_id_fk);
             var purchase_type_id_fk = "{{@$sRow->purchase_type_id_fk}}"; ////alert(frontstore_id_fk);

             // alert(product_id_fk_this);

             // return false;

              $.ajax({
                 type:'POST',
                 url: " {{ url('backend/frontstorelist/plusPromotion') }} ",
                 // data:{ d:d , _token: '{{csrf_token()}}' },
                 data: $(".frmFrontstorelistPro").serialize()+"&promotion_id_fk_this="+promotion_id_fk_this+"&purchase_type_id_fk="+purchase_type_id_fk,
                  success: function(response){ // What to do if we succeed
                         // // // // console.log(response);
                         // // // // console.log(frontstore_id_fk);
                         // return false;

                          var oTable;

                            $(function() {
                              $.fn.dataTable.ext.errMode = 'throw';
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
                                    // scrollY: ''+($(window).height()-370)+'px',
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


                                      if(aData['type_product']=='course'){
                                        $("td:eq(4)", nRow).html("คอร์ส");
                                      }

                                      $('td:last-child', nRow).html(''
                                        + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstorelist.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                      ).addClass('input');

                                      //fnGetDBfrontstore();
                                      setTimeout(function(){
                                        $(".ShippingCalculate02").trigger('click');
                                      }, 1000);

                                    }
                                });
                                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                                  oTable.draw();
                                });

                            });


                  },
                  error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                      // // // // console.log(JSON.stringify(jqXHR));
                      // // // // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                  }
              });

            }



          });



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

             var frontstore_id_fk = $("#frontstore_id_fk").val(); ////alert(frontstore_id_fk);
             var purchase_type_id_fk = "{{@$sRow->purchase_type_id_fk}}"; ////alert(frontstore_id_fk);

             // alert(product_id_fk_this);

             // return false;

              $.ajax({
                 type:'POST',
                 url: " {{ url('backend/frontstorelist/minusPromotion') }} ",
                 // data:{ d:d , _token: '{{csrf_token()}}' },
                 data: $(".frmFrontstorelistPro").serialize()+"&promotion_id_fk_this="+promotion_id_fk_this+"&purchase_type_id_fk="+purchase_type_id_fk,
                  success: function(response){ // What to do if we succeed
                         // // // // console.log(response);
                         // // // // console.log(frontstore_id_fk);
                         // return false;

                          var oTable;
                            $(function() {
                                $.fn.dataTable.ext.errMode = 'throw';
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
                                    // scrollY: ''+($(window).height()-370)+'px',
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


                                        if(aData['type_product']=='course'){
                                          $("td:eq(4)", nRow).html("คอร์ส");
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


                  },
                  error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                      // // // // console.log(JSON.stringify(jqXHR));
                      // // // // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
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
          // alert(category_id);

          $("#spinner_frame").show();

          var frontstore_id_fk = $("#frontstore_id_fk").val();
          var order_type = $("#purchase_type_id_fk").val();
          // alert(order_type);

            /*
              1 ทำคุณสมบัติ
              2 รักษาคุณสมบัติรายเดือน
              3 รักษาคุณสมบัติท่องเที่ยว
              4 เติม Ai-Stockist
              5 แลก  Ai Voucher
            */
       

            var oTable;
            $(function() {
              $.fn.dataTable.ext.errMode = 'throw';
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
                              order_type:order_type,
                            },
                          method: 'POST',
                        },
                    columns: [
                        {data: 'p_img',   title :'<center>รูป</center>', className: 'text-center w100 ',render: function(d) {
                         if(d==null){
                              return '';
                           }else{
                            return '<img src='+d+' width="80">';
                           } 
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

            var frontstore_id_fk = $("#frontstore_id_fk").val(); ////alert(frontstore_id_fk);
            var order_type = $("#purchase_type_id_fk").val();
            // alert(order_type);

            var oTablefrontstorelistPro ;

            $(function() {
              $.fn.dataTable.ext.errMode = 'throw';
              oTablefrontstorelistPro =  $('#data-table-list-pro').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    destroy:true,
                    ajax: {
                        url: '{{ route('backend.frontstorelist_pro.datatable') }}',
                        data :{
                              frontstore_id_fk:frontstore_id_fk,
                              order_type:order_type,
                            },
                          method: 'POST',
                        },
                    columns: [
                        {data: 'p_img',   title :'<center>รูป</center>', className: 'text-center w100 ',render: function(d) {
                          if(d==null){
                              return '';
                           }else{
                            return '<img src='+d+' width="80">';
                           } 
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


                            var cuase_cannot_buy = aData['cuase_cannot_buy'];
                            // console.log(cuase_cannot_buy);

                           if(cuase_cannot_buy.length>0){
                             $("td:eq(4)", nRow).html(
                                '<input type="hidden" disabled ><div class="input-group inline-group"> '
                                +' <div class="input-group-prepend"> '
                                +'   <button class="btn btn-outline-secondary  " disabled style="background-color:#d9d9d9 !important;" > '
                                +'     <i class="fa fa-minus"></i> '
                                +'   </button>'
                                +'   <input class=" quantity " min="0"  type="number" readonly placeholder="-" data-toggle="tooltip" title="สาเหตุที่ซื้อไม่ได้ เพราะ '+aData['cuase_cannot_buy']+' " style="cursor:pointer;background-color:#d9d9d9 !important;" >'
                                +'   <div class="input-group-append"> '
                                +'   <button  class="btn btn-outline-secondary  " disabled style="background-color:#d9d9d9 !important;" > '
                                +'     <i class="fa fa-plus"></i> '
                                +'    </button> '
                                +'  </div> '
                                +' </div> '
                                );
                           }

                      }

                });

               oTablefrontstorelistPro.on( 'draw', function () {
                $('[data-toggle="tooltip"]').tooltip();
                });


            });

        });
     });



    $(document).ready(function() {
        $(document).on('click', '.btnCheckProCode', function(event) {
          event.preventDefault();

          var frontstore_id_fk = $("#frontstore_id_fk").val(); ////alert(frontstore_id_fk);

          var txtSearchPro = $("#txtSearchPro").val();
          // $("#spinner_frame").show();
          if(txtSearchPro==''){
            $("#txtSearchPro").focus();
            return false;
          }

          // ตรวจสอบดูก่อนว่า ในตาราง `db_order_products_list` เคยใช้รหัสนี้หรือไม่ และ มีสถานะ =  1=ใช้งานได้ หรือไม่
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
                                   // // // // console.log(data);

                                    $("#promotion_id_fk").val(data);

                                            var oTable2;

                                            $(function() {
                                              $.fn.dataTable.ext.errMode = 'throw';
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
                                  // // // // console.log(JSON.stringify(jqXHR));
                                  // // // // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                              }
                          });


                          $.ajax({
                             type:'POST',
                             url: " {{ url('backend/ajaxGetPromotionName') }} ",
                             data:{ _token: '{{csrf_token()}}',txtSearchPro:txtSearchPro},
                              success:function(data){
                                   // // // // console.log(data);
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
                                  // // // // console.log(JSON.stringify(jqXHR));
                                  // // // // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                  $("#pro_name").hide();
                              }
                          });

                          $('#data-table-promotion').show();
                          $('#data-table-promotions-cost').show();
                          $('#data-table-promotions-cost_wrapper').show();
                          $('.btnSavePro').show();
                          $('.show_add_coupon').show();
                          var oTable;
                          $(function() {
                              $.fn.dataTable.ext.errMode = 'throw';
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
                             // // // // console.log(data);
                             // location.reload();
                             $('#show_product').html(data);
                             // $('#modalAddList').modal('show');

                              $('#amt').val($('#p_amt').val());
                              $('#amt').focus().select();

                          },
                        error: function(jqXHR, textStatus, errorThrown) {
                            // // // // console.log(JSON.stringify(jqXHR));
                            // // // // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                            $("#spinner_frame").hide();
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
            $('#data-table-promotions-cost').hide();
            $('#data-table-promotions-cost_wrapper').hide();
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

                var frontstore_id_fk = $("#frontstore_id_fk").val(); ////alert(frontstore_id_fk);

                     $.ajax({
                         type:'POST',
                         url: " {{ url('backend/frontstorelist/plus') }} ",
                         // data:{ d:d , _token: '{{csrf_token()}}' },
                          data: $("#frmFrontstoreAddList").serialize(),
                          success: function(response){ // What to do if we succeed
                                 // // // // console.log(response);
                                  var oTable;

                                    $(function() {
                                      $.fn.dataTable.ext.errMode = 'throw';
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
                                            // scrollY: ''+($(window).height()-370)+'px',
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


                                                  if(aData['type_product']=='course'){
                                                    $("td:eq(4)", nRow).html("คอร์ส");
                                                  }

                                                $('td:last-child', nRow).html(''
                                                  + '<a href="javascript: void(0);" data-url="{{ route('backend.frontstorelist.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                                ).addClass('input');
                                              }
                                        });
                                    });

                              },
                              error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                  // // // // console.log(JSON.stringify(jqXHR));
                                  // // // // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
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

                     setTimeout(function(){

                // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                    var frontstore_id_fk = $("#frontstore_id_fk").val();
                    $("input[name=_method]").val('');

                    //alert(frontstore_id_fk);

                    $.ajax({
                         type:'POST',
                         dataType:'JSON',
                         url: " {{ url('backend/ajaxGetDBfrontstore') }} ",
                         data:{ _token: '{{csrf_token()}}',
                             frontstore_id_fk:frontstore_id_fk,
                            },
                          success:function(d){

                            if(d){

                              var pay_type_id_fk = $("#pay_type_id_fk").val();
                              if(pay_type_id_fk==''){
                                $("#cash_price").val('');
                                $("#cash_pay").val('');
                              }

                              if(pay_type_id_fk==5){
                                $("#aicash_price").val(formatNumber(parseFloat(0).toFixed(2)));
                              }

                              if(pay_type_id_fk==6){
                                $("#aicash_price").focus();
                                $("#cash_price").val('');
                                $("#cash_pay").val('');
                              }

                              $.each(d,function(key,value){

                                  $("#pv_total").val(formatNumber(parseFloat(value.pv_total).toFixed(2)));
                                  $("#sum_price").val(formatNumber(parseFloat(value.sum_price).toFixed(2)));
                                  $("#product_value").val(formatNumber(parseFloat(value.product_value).toFixed(2)));
                                  $("#tax").val(formatNumber(parseFloat(value.tax).toFixed(2)));

                                  $("#credit_price").val(formatNumber(parseFloat(value.credit_price).toFixed(2)));
                                  $("#fee_amt").val(formatNumber(parseFloat(value.fee_amt).toFixed(2)));
                                  $("#sum_credit_price").val(formatNumber(parseFloat(value.sum_credit_price).toFixed(2)));

                                  $("#transfer_price").val(formatNumber(parseFloat(value.transfer_price).toFixed(2)));

                                  $("#cash_price").val(formatNumber(parseFloat(value.cash_price).toFixed(2)));

                                  if(pay_type_id_fk==''){
                                     $("#cash_pay").val('');
                                  }else{
                                     $("#cash_pay").val(formatNumber(parseFloat(value.cash_pay).toFixed(2)));
                                  }

                                  $("#transfer_money_datetime").val(value.transfer_money_datetime);
                                  $("#transfer_money_datetime_02").val(value.transfer_money_datetime_02);
                                  $("#transfer_money_datetime_03").val(value.transfer_money_datetime_03);

                                  if(value.shipping_free==1){
                                      $('.input_shipping_free').show();
                                      $('.input_shipping_nofree').hide();
                                  }else{
                                      $('#shipping_price').val(formatNumber(parseFloat(value.shipping_price).toFixed(2)));
                                      $('.input_shipping_free').hide();
                                      $('.input_shipping_nofree').show();
                                  }

                                  if(pay_type_id_fk==6||pay_type_id_fk==11){
                                    if(value.aicash_price>0){
                                      $("#aicash_price").val(formatNumber(parseFloat(value.aicash_price).toFixed(2)));
                                    }else{
                                      $("#aicash_price").val('');
                                      $('#aicash_price').attr('required', true);
                                    }
                                  }

                                  if(pay_type_id_fk==9){
                                    if(value.aicash_price>0){
                                      $("#aicash_price").val(formatNumber(parseFloat(value.aicash_price).toFixed(2)));
                                    }else{
                                      $("#aicash_price").val(formatNumber(parseFloat(0).toFixed(2)));
                                    }
                                  }

                                });

                               }

                              $("input[name=_method]").val('PUT');
                              // alert("A");
                              // $("#spinner_frame").hide();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                $("#spinner_frame").hide();
                            }
                      });

                      var table = $('#data-table-list').DataTable();
                      table.clear().draw();
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

                    // alert("B");
                    location.reload();

                    }, 3000);



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
                          // // // // console.log(data);
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


                    var pay_type_id_fk =  $('#pay_type_id_fk').val();
                                  // กรณีเงินโอน ไปอีกช่องทางนึง
                    if(pay_type_id_fk==1 || pay_type_id_fk==8 || pay_type_id_fk==10 || pay_type_id_fk==11 || pay_type_id_fk==12){

                      // คำนวณ บวกเงินค่าขนส่ง ใส่เข้าไปในยอด โอน หรือ เงินสด บิลเดิมนี้ด้วย 
                      // $('#pay_type_id_fk').prop('disabled',true);
                      // $('.class_btnSaveTransferType').removeAttr( "disabled" );
                      // $('.class_btnSaveTransferType').show();


                    }



                     // $(document).on('change', '#transfer_price', function(event) {

                              // เปลี่ยนปุ่ม save เฉพาะกิจ

                              // $('.class_btnSaveTransferType').removeAttr( "disabled" );

                

                    // });


                $(document).on('click', '.class_btnSaveTransferType', function(event) {

                    $('.myloading').show();

                  // เปลี่ยนปุ่ม save เฉพาะกิจ
                      var frontstore_id_fk = "{{@$sRow->id}}";
                      let transfer_price =  $('#transfer_price').val();
                      let cash_pay =  $('#cash_pay').val();

                         $.ajax({
                            url: "{{ url('backend/ajaxCalSaveTransferType') }}",
                            type: "POST",
                            data: {_token: '{{csrf_token()}}', 
                            frontstore_id_fk:frontstore_id_fk,
                            transfer_price:transfer_price,
                            cash_pay:cash_pay,
                          },
                            success:function(data){
                                // // console.log(data);
                                // location.reload();
                            }
                        });

                        setTimeout(function(){
                          // $('#frm-main').attr('action', "{{ url('backend/test1') }}").submit();
                          location.replace("{{url('backend/frontstore')}}");
                        },2000);


                });


                $('.ShippingCalculate').on('click change', function(e) {

                    var v = $(this).val();
                    // alert(v);

                    var frontstore_id_fk = "{{@$sRow->id}}";

                    var pay_type_id_fk =  $('#pay_type_id_fk').val()?$('#pay_type_id_fk').val():0;

                    // // console.log(pay_type_id_fk);

                     $('#pay_type_id_fk').val("").select2();
                     $('#pay_type_id_fk').val("").trigger("change");
/*

1 เงินโอน
2 บัตรเครดิต
3 Ai-Cash
4 Gift Voucher
==============================
5 เงินสด
6 เงินสด + Ai-Cash
7 เครดิต + เงินสด
8 เครดิต + เงินโอน
9 เครดิต + Ai-Cash
10  เงินโอน + เงินสด
11  เงินโอน + Ai-Cash
===============================
12  Gift Voucher + เงินโอน
13  Gift Voucher + บัตรเครดิต
14  Gift Voucher + Ai-Cash
15  PromptPay
16  TrueMoney
17  Gift Voucher + PromptPay
18  Gift Voucher + TrueMoney

กรณีเงินโอน ได้แก่ 1,8,10,11,12

*/
                    // return false;
                    // $(".show_div_credit").hide();
                    // $(".div_fee").hide();
                    // $(".show_div_transfer_price").hide();
                    // $(".div_account_bank_id").hide();
                    // $(".show_div_aicash_price").hide();
                    // $(".show_div_cash_pay").hide();
                    // $(".div_pay_with_other_bill").hide();
                    
                    // $('.class_btnSave').addClass(' btnSave ');
                    // $('.class_btnSave').removeAttr( "disabled" );
                    $('.class_btnSave').hide();

                    // var pay_type_id_fk =  $('#pay_type_id_fk').val();
                    // $('#pay_type_id_fk').val(pay_type_id_fk).trigger("change");

                    // กรณีเงินโอน ไปอีกช่องทางนึง
                    if(pay_type_id_fk==1 || pay_type_id_fk==8 || pay_type_id_fk==10 || pay_type_id_fk==11 || pay_type_id_fk==12){

                      // คำนวณ บวกเงินค่าขนส่ง ใส่เข้าไปในยอด โอน หรือ เงินสด บิลเดิมนี้ด้วย 

                      // $('#pay_type_id_fk').prop('disabled',true);

                      // $('.class_btnSaveTransferType').removeAttr( "disabled" );
                      // $('.class_btnSaveTransferType').show();

                      // $("#cash_pay").load(location.href + " #cash_pay");
                      
                      location.reload();
                      


                      // let shipping_price =  parseFloat($('#shipping_price').val().replace(",", ""));
                      // let transfer_price =  parseFloat($('#transfer_price').val().replace(",", ""));
                      // let cash_pay =  parseFloat($('#cash_pay').val().replace(",", ""));

                      // // // console.log("shipping_price : "+shipping_price);
                      // // // console.log("transfer_price : "+transfer_price);
                      // // // console.log("cash_pay : "+cash_pay);

                      // 1 เงินโอน
                      // 8 เครดิต + เงินโอน
                      // 9 เครดิต + Ai-Cash
                      // 10  เงินโอน + เงินสด
                      // 11  เงินโอน + Ai-Cash
                      // 12  Gift Voucher + เงินโอน

                      // 10  เงินโอน + เงินสด
                      // if(pay_type_id_fk==10){
                      //     let res =  (+cash_pay+shipping_price);
                      //     $('#cash_pay').val(res.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                      // }

                      // $('#transfer_price').removeAttr( "disabled" );
                      // $('#transfer_price').val("");
                      // $('#cash_pay').val("");

                      // $('.class_btnSave').addClass(' btnSave ');
                    // $('.class_btnSave').removeAttr( "disabled" );

                        //  $.ajax({
                        //     url: "{{ url('backend/ajaxClearPayTypeFrontstore') }}",
                        //     type: "POST",
                        //     data: {_token: '{{csrf_token()}}', frontstore_id_fk:frontstore_id_fk},
                        //     success: function () {
                        //       // $('#pay_type_id_fk').val("").select2();
                        //       // $('#pay_type_id_fk').val("").trigger("change");
                        //       location.reload(true);
                        //     }
                        // });

                      }else if(pay_type_id_fk==5){

                        $('#pay_type_id_fk').attr("disabled", false);
                        $('#pay_type_id_fk').val("").select2();
                        $('#pay_type_id_fk').val("").trigger("change");


                    }else{

                           $.ajax({
                              url: "{{ url('backend/ajaxClearPayTypeFrontstore') }}",
                              type: "POST",
                              data: {_token: '{{csrf_token()}}', frontstore_id_fk:frontstore_id_fk},
                              success: function (data) {
                                // // console.log(data);
                                // $('#pay_type_id_fk').val("").select2();
                                // $('#pay_type_id_fk').val("").trigger("change");
                                // location.reload(true);
                              }
                          });

                    }


                    if(v!=5){

                      $("#spinner_frame").show();
                      $(".bg_addr").css("background-color", "");
                      $(this).closest('.bg_addr').css("background-color", "#00e673");

                    }

                    var province_id = $(this).attr('province_id');

                    fnShippingCalculate(province_id);

                    // $('#pay_type_id_fk').val("").select2();
                    // $('#cash_price').val("");
                    // $('#cash_pay').val("");
                    //fnGetDBfrontstore();

              });


           $('.ShippingCalculate02').on('click', function(e) {

                $("#spinner_frame").show();
                    var province_id = $('.ShippingCalculate02').attr('province_id');
                    fnShippingCalculate(province_id);
                    //fnGetDBfrontstore();

              });

            });



      $(document).ready(function() {

            // $(".ShippingCalculate02").trigger('click');


                 $('#modalAddFromPromotion,#modalAddList,#modalAddList').on('hidden.bs.modal', function () {
                    $("#spinner_frame").show();
                    fnCheckDBfrontstore();
                    setTimeout(function(){
                      $(".ShippingCalculate02").trigger('click');
                    }, 1000);

                });


                 $('#modalAddFromProductsList').on('hidden.bs.modal', function () {
                    $("#spinner_frame").show();
                    fnCheckDBfrontstore();
                    setTimeout(function(){
                      $(".ShippingCalculate02").trigger('click');
                    }, 1000);

                    // setTimeout(function(){
                    //   location.reload();
                    // }, 2000);

                });

          });


     $(document).ready(function() {

            // $(".ShippingCalculate02").trigger('click');

                $('.btnUpSlip').on('click', function(e) {
                      $("#image01").trigger('click');
                });

                $('.btnUpSlip_02').on('click', function(e) {
                      $("#image02").trigger('click');
                });

               $('.btnUpSlip_03').on('click', function(e) {
                      $("#image03").trigger('click');
                });


          });


          </script>

          <script type="text/javascript">




            function fnCheckDBfrontstore() {

                  var frontstore_id_fk = $("#frontstore_id_fk").val();
                  //alert(frontstore_id_fk);

                  $.ajax({
                        url: " {{ url('backend/ajaxCheckDBfrontstore') }} ",
                        method: "post",
                        data: {
                          frontstore_id_fk:frontstore_id_fk,
                          "_token": "{{ csrf_token() }}",
                        },
                        success:function(data)
                        {
                         // alert(data);
                         if(data == 0 ){
                           $('.div_cost').hide();
                         }else{

                          $('.div_cost').show();
                         }
                          $("#spinner_frame").hide();
                        }
                      });

                      $("input[name=_method]").val('');
                      $.ajax({
                         type:'POST',
                         dataType:'JSON',
                         url: " {{ url('backend/ajaxGetDBfrontstore') }} ",
                         data:{ _token: '{{csrf_token()}}',
                             frontstore_id_fk:frontstore_id_fk,
                            },
                          success:function(d){
                             // // // console.log(d);
                            if(d){
                              $.each(d,function(key,value){

                                  $("#product_value").val(formatNumber(parseFloat(value.product_value).toFixed(2)));
                                  $("#tax").val(formatNumber(parseFloat(value.tax).toFixed(2)));

                                });

                            }
                              $("input[name=_method]").val('PUT');
                              $("#spinner_frame").hide();

                            },
                          error: function(jqXHR, textStatus, errorThrown) {
                              $("#spinner_frame").hide();
                          }
                      });


            }


          function formatNumber(num) {
              return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
          }



        function fnShippingCalculate($province_id){

             $("input[name=_method]").val('');
             var province_id = $province_id?$province_id:0;
             var pay_type_id_fk = $("#pay_type_id_fk").val();

                     $.ajax({
                       type:'POST',
                       dataType:'JSON',
                       url: " {{ url('backend/ajaxShippingCalculate') }} ",
                       data: $("#frm-main").serialize()+"&province_id="+$province_id+"&pay_type_id_fk="+pay_type_id_fk,
                        success:function(data){
                            // // console.log(data);
                            // return false;

                            $("#shipping_price").val(formatNumber(parseFloat(data).toFixed(2)));
                            //fnGetDBfrontstore();
                            $("input[name=_method]").val('PUT');
                            $("#spinner_frame").hide();
                          },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $("#spinner_frame").hide();
                        }
                    });
        }


</script>



  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" />


<!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

 <script>


      $('.transfer_money_datetime').datetimepicker({
          value: '',
          rtl: false,
          format: 'd/m/Y H:i',
          formatTime: 'H:i',
          formatDate: 'd/m/Y',
          monthChangeSpinner: true,
          closeOnTimeSelect: true,
          closeOnWithoutClick: true,
          closeOnInputClick: true,
          openOnFocus: true,
          timepicker: true,
          datepicker: true,
          weeks: false,
          minDate: 0,
      });

      $('.transfer_money_datetime').change(function(event) {
        var d = $(this).val();
        // // // console.log(d);
        var t = d.substring(d.length - 5);
        // // // console.log();
        var d = d.substring(0, 10);
        // // // console.log();
        var d = d.split("/").reverse().join("-");
        // // // console.log();
        $('#transfer_money_datetime').val(d+' '+t);
      });



      $('.transfer_money_datetime_02').datetimepicker({
          value: '',
          rtl: false,
          format: 'd/m/Y H:i',
          formatTime: 'H:i',
          formatDate: 'd/m/Y',
          monthChangeSpinner: true,
          closeOnTimeSelect: true,
          closeOnWithoutClick: true,
          closeOnInputClick: true,
          openOnFocus: true,
          timepicker: true,
          datepicker: true,
          weeks: false,
          minDate: 0,
      });

      $('.transfer_money_datetime_02').change(function(event) {
        var d = $(this).val();
        // // // console.log(d);
        var t = d.substring(d.length - 5);
        // // // console.log();
        var d = d.substring(0, 10);
        // // // console.log();
        var d = d.split("/").reverse().join("-");
        // // // console.log();
        $('#transfer_money_datetime_02').val(d+' '+t);
      });


      $('.transfer_money_datetime_03').datetimepicker({
          value: '',
          rtl: false,
          format: 'd/m/Y H:i',
          formatTime: 'H:i',
          formatDate: 'd/m/Y',
          monthChangeSpinner: true,
          closeOnTimeSelect: true,
          closeOnWithoutClick: true,
          closeOnInputClick: true,
          openOnFocus: true,
          timepicker: true,
          datepicker: true,
          weeks: false,
          minDate: 0,
      });

      $('.transfer_money_datetime_03').change(function(event) {
        var d = $(this).val();
        // // // console.log(d);
        var t = d.substring(d.length - 5);
        // // // console.log();
        var d = d.substring(0, 10);
        // // // console.log();
        var d = d.split("/").reverse().join("-");
        // // // console.log();
        $('#transfer_money_datetime_03').val(d+' '+t);
      });


</script>



        <script type="text/javascript">

                function showPreview_01(ele)
                    {
                        $('#image01').attr('src', ele.value); // for IE
                        if (ele.files && ele.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $('.span_file_slip').show();
                                $('#imgAvatar_01').show();
                                $('#imgAvatar_01').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(ele.files[0]);
                    }
                }

                function showPreview_02(ele)
                    {
                        $('#image02').attr('src', ele.value); // for IE
                        if (ele.files && ele.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $('.span_file_slip_02').show();
                                $('#imgAvatar_02').show();
                                $('#imgAvatar_02').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(ele.files[0]);
                    }
                }

                function showPreview_03(ele)
                    {
                        $('#image03').attr('src', ele.value); // for IE
                        if (ele.files && ele.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $('.span_file_slip_03').show();
                                $('#imgAvatar_03').show();
                                $('#imgAvatar_03').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(ele.files[0]);
                    }
                }


                $(document).on('click', '.btnDelSlip', function(event) {
                          var id = $(this).data('id');
                          if (!confirm("ยืนยันการลบ ! / Confirm to delete ? ")){
                              return false;
                          }else{

                              $.ajax({
                                  type: "POST",
                                  url: " {{ url('backend/ajaxDelFileSlip') }} ",
                                   data:{ _token: '{{csrf_token()}}',id:id },
                                  success: function(data){
                                    // // // console.log(data);
                                    // location.reload();
                                    $(".span_file_slip").hide();
                                    $(".transfer_money_datetime").val('');
                                    $("#note_fullpayonetime").val('');
                                  }
                              });

                          }

                });


                $(document).on('click', '.btnDelSlip_02', function(event) {
                          var id = $(this).data('id');
                          if (!confirm("ยืนยันการลบ ! / Confirm to delete ? ")){
                              return false;
                          }else{

                              $.ajax({
                                  type: "POST",
                                  url: " {{ url('backend/ajaxDelFileSlip_02') }} ",
                                   data:{ _token: '{{csrf_token()}}',id:id },
                                  success: function(data){
                                    // // // console.log(data);
                                    // location.reload();
                                    $(".span_file_slip_02").hide();
                                    $(".transfer_money_datetime_02").val('');
                                    $("#note_fullpayonetime_02").val('');
                                  }
                              });

                          }

                });


                $(document).on('click', '.btnDelSlip_03', function(event) {
                          var id = $(this).data('id');
                          if (!confirm("ยืนยันการลบ ! / Confirm to delete ? ")){
                              return false;
                          }else{

                              $.ajax({
                                  type: "POST",
                                  url: " {{ url('backend/ajaxDelFileSlip_03') }} ",
                                   data:{ _token: '{{csrf_token()}}',id:id },
                                  success: function(data){
                                    // // // console.log(data);
                                    // location.reload();
                                    $(".span_file_slip_03").hide();
                                    $(".transfer_money_datetime_03").val('');
                                    $("#note_fullpayonetime_03").val('');
                                  }
                              });

                          }

                });


        $(document).ready(function() {
              /*

              1 เงินสด
              2 เงินสด + Ai-Cash
              3 เครดิต + เงินสด
              4 เครดิต + เงินโอน
              5 เครดิต + Ai-Cash
              6 เงินโอน + เงินสด
              7 เงินโอน + Ai-Cash

              */
          var pay_type_id_fk = "{{@$sRow->pay_type_id_fk}}";
        // alert(pay_type_id_fk);

                // $(".show_div_credit").hide();
                // $(".div_fee").hide();
                // $(".show_div_transfer_price").hide();
                // $(".div_account_bank_id").hide();
                // $(".show_div_aicash_price").hide();

          // ####################################

              $(document).on('change', '#fee', function(event) {
                    event.preventDefault();
                    $("#spinner_frame").show();
                    $('#credit_price').attr('required', true);
                    setTimeout(function(){
                      $('#credit_price').focus();
                      $("#spinner_frame").hide();
                    });
                });

              $(document).on('change', '.transfer_money_datetime,.transfer_money_datetime_02,.transfer_money_datetime_03', function(event) {
                    event.preventDefault();
                    $("#spinner_frame").show();
                    $('#transfer_price').attr('required', true);
                    setTimeout(function(){
                        // $('#transfer_price').focus();
                        $("#spinner_frame").hide();
                    });
                });


              $(document).on('change', '#credit_price', function(event) {
                    event.preventDefault();
                    var fee = $("#fee").val();
                    if(fee==''){
                      $(this).val();
                      $("#fee").select2('open');
                    }
                });

// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$



                $(document).on('change', '#pay_type_id_fk', function(event) {

                        // event.preventDefault();
                        $("#spinner_frame").show();

                        var pay_type_id_fk = $("#pay_type_id_fk").val();
                        localStorage.setItem('pay_type_id_fk', pay_type_id_fk);

                        var purchase_type_id_fk = "{{@$sRow->purchase_type_id_fk}}";
                        var gift_voucher_price = $("#gift_voucher_price").val();
                        var frontstore_id_fk = $("#frontstore_id_fk").val();

                        var user_name = '{{@$user_name}}' ;

                        // // // console.log(pay_type_id_fk);
                        // // // console.log(frontstore_id_fk);
                        // // // console.log(user_name);

                        // return false;

                        // $("input[name=_method]").val('');

                        // หลังเคลียร์เสร็จ เอา pay_type_id_fk ไปเก็บไว้ก่อน เคลียร์์ค่าอื่นๆ 
                         $.ajax({
                              url: "{{ url('backend/ajaxClearPayTypeFrontstore') }}",
                              type: "POST",
                              data: {_token: '{{csrf_token()}}', 
                              pay_type_id_fk:pay_type_id_fk,
                              frontstore_id_fk:frontstore_id_fk,
                              user_name:user_name,
                            },
                              success: function (dd) {
                                // // // console.log(dd);
                                // return false;
                                // $('#pay_type_id_fk').val("").select2();
                                // $('#pay_type_id_fk').val("").trigger("change");
                              }
                          });


                        $('#member_id_aicash_select').toggleSelect2(false); //hide
                        $('#aicash_choose').show(); 
                     
                        $("#aicash_remain").val("");
                        $(".show_div_cash_pay").hide();
                        $(".show_div_credit").hide();
                        $(".div_fee").hide();
                        $(".show_div_transfer_price").hide();
                        $(".div_account_bank_id").hide();
                        $(".show_div_aicash_price").hide();
                        $("#cash_price").val('');
                        $("#cash_pay").val('');
                        $('#member_id_aicash').val("");
                        $("input[name=_method]").val('');
                        $('#aicash_price').attr('required', false);
                        $('#member_id_aicash_select').attr('required', false);
                        $('#fee').attr('required', false);
                        $('input[name=account_bank_id]').attr('required', false);
                        $(".transfer_money_datetime").attr('required', false);
                        $('#charger_type:first-child').attr('checked',false);
                        $('#member_id_aicash').attr('required', false);
                        $("#transfer_price").attr('required',false);
                        $("#credit_price").attr('disabled', true);
                        $(".div_pay_with_other_bill").hide();

                        // $(".show_div_aicash_price").html('');

/*
1 เงินโอน
2 บัตรเครดิต
3 Ai-Cash
4 Gift Voucher

//////////////////////////////
5 เงินสด
6 เงินสด + Ai-Cash
7 เครดิต + เงินสด
8 เครดิต + เงินโอน
9 เครดิต + Ai-Cash
10  เงินโอน + เงินสด
11  เงินโอน + Ai-Cash
//////////////////////////////

12  Gift Voucher + เงินโอน
13  Gift Voucher + บัตรเครดิต
14  Gift Voucher + Ai-Cash
15  PromptPay
16  TrueMoney
17  Gift Voucher + PromptPay
18  Gift Voucher + TrueMoney

 */

                        // check เป็นกรณีๆ ไป จับคู่ แล้ว localStorage เป็นเรื่องๆ ไป
                        // เอากรณี Ai-cash ก่อน 6,9,11
                        // 6 เงินสด + Ai-Cash
                        


                        if(purchase_type_id_fk==5){

                            if(gift_voucher_price==''){
                              alert("! กรุณา กรอกยอด Ai Voucher ");
                              $(this).val('').select2();
                              $("#gift_voucher_price").focus();
                              $("#spinner_frame").hide();
                              return false;
                            }

                        }

                        if(pay_type_id_fk==''){
                          $("#spinner_frame").hide();
                          return false;
                        }


                        $.ajax({
                         type:'POST',
                         dataType:'JSON',
                         url: " {{ url('backend/ajaxCalPriceFrontstore01') }} ",
                         data: $("#frm-main").serialize(),
                          success:function(data){
                                // // // console.log(data);
                                // return false;
                                $.each(data,function(key,value){
                                  $("#aicash_price").val(formatNumber(parseFloat(value.aicash_price).toFixed(2)));
                                  $("#cash_price").val(formatNumber(parseFloat(value.cash_price).toFixed(2)));
                                  $("#cash_pay").val(formatNumber(parseFloat(value.cash_pay).toFixed(2)));

                                  $("#transfer_money_datetime").val(value.transfer_money_datetime);
                                  $(".transfer_money_datetime").val(value.transfer_money_datetime);

                                  $("#transfer_money_datetime_02").val(value.transfer_money_datetime_02);
                                  $(".transfer_money_datetime_02").val(value.transfer_money_datetime_02);
                                  
                                  $("#transfer_money_datetime_03").val(value.transfer_money_datetime_03);
                                  $(".transfer_money_datetime_03").val(value.transfer_money_datetime_03);
                                       
                                });

                                $("input[name=_method]").val('PUT');

                        // 1 เงินสด
                          if(pay_type_id_fk==5){
                              $(".show_div_cash_pay").show();
                            }else
                        // 2  เงินสด + Ai-Cash
                            if(pay_type_id_fk==6){

                                $("#aicash_price").val('');
                                $("#aicash_price").removeAttr('readonly');
                                $('#aicash_price').attr('required', true);
                                $("#aicash_price").removeClass('input-aireadonly').addClass('input-aifill').addClass('CalPrice');
                                $("#cash_pay").val('');
                                $(".show_div_aicash_price").show();
                                $(".show_div_cash_pay").show();
                                $('#member_id_aicash_select').attr('required', true);

                            }else
                        // 3  เครดิต + เงินสด
                            if(pay_type_id_fk==7){
                              // เครดิต
                              $(".show_div_credit").show();
                              $("#credit_price").attr('disabled', false);
                              $("#credit_price").val('');
                              $(".div_fee").show();
                              $("#fee_amt").val('');
                              $('#fee').attr('required', true);
                              $("#sum_credit_price").val('');
                              // เงินสด
                              $(".show_div_cash_pay").show();
                              $("#cash_pay").val('');

                            }else
                        // 4  เครดิต + เงินโอน
                            if(pay_type_id_fk==8){
                              // เครดิต
                              $(".show_div_credit").show();
                              $("#credit_price").val('');
                              $("#credit_price").attr('disabled', false);
                              $(".div_fee").show();
                              $("#fee_amt").val('');
                              $('#fee').val("").select2();
                              $('#fee').attr('required', true);
                              $("#sum_credit_price").val('');
                              // เงินโอน
                              $(".show_div_transfer_price").show();
                              // $('input[name=account_bank_id]').prop('checked',false);
                              $('input[name=account_bank_id]').attr('required', true);
                              $(".div_account_bank_id").show();
                              $(".div_pay_with_other_bill").show();
                              $("#transfer_price").val('');
                              $(".transfer_money_datetime").attr('required', true);
                              $("#transfer_price").removeAttr('required');
                              $("#transfer_price").removeClass('input-aifill').addClass('input-aireadonly');

                              $(".show_div_cash_pay").hide();

                            }else
                        // 5  เครดิต + Ai-Cash
                             if(pay_type_id_fk==9){
                              // เครดิต
                              $(".show_div_credit").show();
                              $(".div_fee").show();
                              $("#credit_price").val('');
                              $("#credit_price").attr('disabled', false);
                              $("#fee_amt").val('');
                              $("#sum_credit_price").val('');
                              $("#cash_pay").val('');
                              $('#fee').val("").select2();
                              $('#fee').attr('required', true);
                              $('#charger_type:first-child').attr('checked',true);

                              // Ai-Cash
                              $(".show_div_aicash_price").show();
                              $("#aicash_price").removeClass('input-aifill').addClass('input-aireadonly');
                              $("#aicash_price").val('');
                              $("#aicash_price").attr('readonly',true);

                              $("#cash_pay").val('');
                              $(".show_div_cash_pay").hide();

                              $('input[name=account_bank_id]').removeAttr('required');
                              $(".transfer_money_datetime").removeAttr('required');
                              $("#transfer_price").removeAttr('required');
                              $("#transfer_price").removeClass('input-aifill').addClass('input-aireadonly');

                              $('#member_id_aicash').attr('required', true);

                            }else
                              // 6  เงินโอน + เงินสด
                              if(pay_type_id_fk==10){

                                // เงินโอน
                                $(".show_div_transfer_price").show();
                                // $('input[name=account_bank_id]').prop('checked',false);
                                $('input[name=account_bank_id]').attr('required', true);
                                $(".div_account_bank_id").show();
                                $(".div_pay_with_other_bill").show();
                                $("#transfer_price").val('');
                                $(".transfer_money_datetime").attr('required', true);
                                $("#transfer_price").attr('required',true);
                                $("#transfer_price").removeClass('input-aireadonly').addClass('input-aifill').addClass('CalPrice');

                                $(".show_div_cash_pay").show();
                                $('#fee').removeAttr('required');
                                $('#aicash_price').removeAttr('required');
                                $("#cash_pay").val('');

                              }else
                                // 7  เงินโอน + Ai-Cash
                                if(pay_type_id_fk==11){

                                  // เงินโอน
                                  $(".show_div_transfer_price").show();
                                  // $('input[name=account_bank_id]').prop('checked',false);
                                  $('input[name=account_bank_id]').attr('required', true);
                                  $(".div_account_bank_id").show();
                                  $(".div_pay_with_other_bill").show();
                                  $("#transfer_price").val('');
                                  $(".transfer_money_datetime").attr('required', true);
                                  $("#transfer_price").attr('required',true);
                                  $("#transfer_price").removeClass('input-aireadonly').addClass('input-aifill').addClass('CalPrice');

                                  // Ai-Cash
                                  $(".show_div_aicash_price").show();
                                  $("#aicash_price").removeClass('input-aifill').addClass('input-aireadonly');
                                  $("#aicash_price").val('');
                                  $("#aicash_price").attr('readonly',true);

                                  $(".show_div_cash_pay").hide();
                                  $('#fee').removeAttr('required');
                                  $("#cash_pay").val('');

                                  $('#member_id_aicash').attr('required', true);

                              }else{
                                  $('#fee').removeAttr('required');
                                  $('input[name=account_bank_id]').removeAttr('required');
                                  $('.transfer_money_datetime').removeAttr('required');
                                  $('#aicash_price').removeAttr('required');
                                  $(".show_div_cash_pay").hide();
                                  $(".show_div_transfer_price").hide();
                                  $(".div_account_bank_id").hide();

                              }

                                
                                $('.class_btnSave').addClass(' btnSave ');
                                $('.class_btnSave').removeAttr( "disabled" );
                                $('.class_btnSave').show();

                                $("#spinner_frame").hide();

                            },
                          error: function(jqXHR, textStatus, errorThrown) {
                              // // // // console.log(JSON.stringify(jqXHR));
                              // // // // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                              $("#spinner_frame").hide();
                          }
                      });

                    });




            });





      $(document).on('change', '.CalPrice', function(event) {

              event.preventDefault();
              $("#spinner_frame").show();

              var id = "{{@$sRow->id}}";

              var pay_type_id_fk = $("#pay_type_id_fk").val();

              if(pay_type_id_fk==''){
                $("#cash_price").val('');
                $("#cash_pay").val('');
                $("#spinner_frame").hide();
                $(".show_div_cash_pay").hide();
                return false;
              }


                  $("input[name=_method]").val('');

                  $.ajax({
                   type:'POST',
                   dataType:'JSON',
                   url: " {{ url('backend/ajaxCalPriceFrontstore02') }} ", 
                   data: $("#frm-main").serialize()+"&id="+id+"&pay_type_id_fk="+pay_type_id_fk,
                    success:function(data){
                          console.log(data); 

                          // return false;

                            $.each(data,function(key,value){
                                  
                                  $("#credit_price").val(formatNumber(parseFloat(value.credit_price).toFixed(2)));
                                  $("#fee_amt").val(formatNumber(parseFloat(value.fee_amt).toFixed(2)));
                                  $("#sum_credit_price").val(formatNumber(parseFloat(value.sum_credit_price).toFixed(2)));
                                  $("#transfer_price").val(formatNumber(parseFloat(value.transfer_price).toFixed(2)));  

                                  if(pay_type_id_fk==''){
                                     $("#cash_pay").val('');    
                                  }else{
                                     $("#cash_pay").val(formatNumber(parseFloat(value.cash_pay).toFixed(2)));    
                                  }
                                  // $("#transfer_money_datetime").val(value.transfer_money_datetime);

                                  $("#spinner_frame").hide();

                                });

                                 $("input[name=_method]").val('PUT');

                         
                               $("#spinner_frame").hide();

                            },
                          error: function(jqXHR, textStatus, errorThrown) { 
                              // // // // console.log(JSON.stringify(jqXHR));
                              // // // // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                              $("#spinner_frame").hide();
                          }
                      });


      });

              $(document).on('change', '.CalPriceAicash', function(event) {

                $("#spinner_frame").show();

                var this_element = $(this).attr('id');
                // alert(this_element);

                // return false;

                     $("input[name=_method]").val('');

                     $.ajax({
                       type:'POST',
                       dataType:'JSON',
                       url: " {{ url('backend/ajaxCalPriceFrontstore03') }} ",
                       data: $("#frm-main").serialize()+"&this_element="+this_element,
                        success:function(data){
                               // // console.log(data);
                               // return false;
                               fnGetDBfrontstore();

                               $('.class_btnSave').addClass(' btnSave ');
                                $('.class_btnSave').removeAttr( "disabled" );
                                $('.class_btnSave').show();

                              $("input[name=_method]").val('PUT');
                              $("#spinner_frame").hide();
                          },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $("#spinner_frame").hide();
                        }
                    });

              });


              $(document).on('change', '.CalGiftVoucherPrice', function(event) {

                var this_element = $(this).attr('id');
                // alert(this_element);

                $('#pay_type_id_fk').val("").select2();
                $('#cash_price').val("");
                $('#cash_pay').val("");
                $(".show_div_cash_pay").hide();

                     $("input[name=_method]").val('');

                     $.ajax({
                       type:'POST',
                       dataType:'JSON',
                       url: " {{ url('backend/ajaxCalGiftVoucherPrice') }} ",
                       data: $("#frm-main").serialize()+"&this_element="+this_element,
                        success:function(data){
                               // // // console.log(data);
                               // //fnGetDBfrontstore();
                               $.each(data,function(key,value){
                                   $("#gift_voucher_price").val(value.gift_voucher_price);
                                });

                              $("input[name=_method]").val('PUT');
                              $("#spinner_frame").hide();
                          },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $("#spinner_frame").hide();
                        }
                    });

              });



              $(document).on('change', 'input[name=charger_type]', function(event) {


                  var frontstore_id_fk = $("#frontstore_id_fk").val();
                  // //alert(frontstore_id_fk);

                    $("#credit_price").val('');
                    $("#fee_amt").val('');
                    $("#sum_credit_price").val('');
                    $("#aicash_price").val('');
                    $("#transfer_price").val('');
                    $("#cash_pay").val('');

                     $.ajax({
                       type:'POST',
                       // dataType:'JSON',
                       url: " {{ url('backend/ajaxClearAfterSelChargerType') }} ",
                       data: { _token: '{{csrf_token()}}', frontstore_id_fk:frontstore_id_fk },
                        success:function(data){
                               // // // console.log(data);

                               // $.each(data,function(key,value){
                               //    if(value.ai_cash==0){
                               //      alert('! กรุณา ทำการเติม Ai-Cash ก่อนเลือกชำระช่องทางนี้ ขอบคุณค่ะ');
                               //       $("#spinner_frame").hide();
                               //    }
                               //  });

                              setTimeout(function(){
                                 $("#credit_price").focus();
                              });

                              $("#spinner_frame").hide();
                          },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $("#spinner_frame").hide();
                        }
                    });

              });



              // $(document).on('change', '#member_id_aicash_select', function(event) {
              //     event.preventDefault();

              //     $("#aicash_price").val('');
              //     $("#cash_pay").val('');
              //     $("#aicash_remain").val('');

              //     var id = $(this).val();
              //     localStorage.setItem('member_id_aicash', id);
              // });




              $(document).on('change', '#member_id_aicash_select', function(event) {

                  $("#spinner_frame").show();

                      var customer_id = $(this).val();
                      var frontstore_id_fk = $("#frontstore_id_fk").val();
                      // alert(customer_id);
                     // // // console.log(customer_id);

                      if(customer_id==''){
                          alert('! กรุณา ระบุสมาชิกเพื่อชำระด้วย Ai-Cash ก่อนค่ะ ขอบคุณค่ะ');
                          $("#spinner_frame").hide();
                          return false;
                      }

                     $('#member_id_aicash').val($(this).val());

                     // localStorage.setItem('member_id_aicash', member_id_aicash);

                     $('#member_name_aicash').val('');

                     $.ajax({
                       type:'POST',
                       dataType:'JSON',
                       url: " {{ url('backend/ajaxGetAicash') }} ",
                       data: { _token: '{{csrf_token()}}',customer_id:customer_id,frontstore_id_fk:frontstore_id_fk },
                        success:function(data){
                               // // console.log(data);
                               // return false;
                               $.each(data,function(key,value){
                                  // $("#aicash_remain").val(value.ai_cash);
                                  if(value.ai_cash==0 || value.ai_cash=="0.00" ){
                                      // alert('! กรุณา ทำการเติม Ai-Cash สำหรับสมาชิกที่ระบุเพื่อชำระด้วย Ai-Cash ก่อนค่ะ ขอบคุณค่ะ');
                                      $("#spinner_frame").hide();
                                      $(".btnCalAddAicash").hide();
                                      $(".btnSave").attr("disabled", true);
                                      $("#aicash_price").attr("disabled", true);
                                  }else{
                                     $(".btnCalAddAicash").show();
                                     $(".btnCalAddAicash").attr("disabled", false);
                                     $(".btnSave").attr("disabled", false);
                                     $("#aicash_price").attr("disabled", false);
                                  }

                                  $("#aicash_remain").val(formatNumber(parseFloat(value.ai_cash).toFixed(2)));

                                  // localStorage.setItem('aicash_remain', value.ai_cash);

                                });

                              $("#spinner_frame").hide();
                          },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $("#spinner_frame").hide();
                        }
                    });


              });



              $(document).on('click', '.btnCalAddAicash', function(event) {

                  $("#spinner_frame").show();

                      var this_element = "aicash_price";
                              // alert(this_element);
                       $("input[name=_method]").val('');

                       // เช็คก่อนว่า จับคุณกับอะไร
                       var pay_type_id_fk = $("#pay_type_id_fk").val();
                       var aicash_remain = $("#aicash_remain").val();
                       var member_id_aicash = $("#member_id_aicash").val();
                       // console.log(aicash_remain);
                       // return false;

                       $.ajax({
                         type:'POST',
                         dataType:'JSON',
                         url: " {{ url('backend/ajaxCalPriceFrontstore04') }} ",
                         data: $("#frm-main").serialize()+"&this_element="+this_element+"&aicash_remain="+aicash_remain+"&member_id_aicash="+member_id_aicash,
                          success:function(data){
                                 // console.log(data);
                                 // return false;

                                 fnGetDBfrontstore();

                                 $('.class_btnSave').addClass(' btnSave ');
                                $('.class_btnSave').removeAttr( "disabled" );
                                $('.class_btnSave').show();

                                $("input[name=_method]").val('PUT');
                                $("#spinner_frame").hide();
                            },
                          error: function(jqXHR, textStatus, errorThrown) {
                              $("#spinner_frame").hide();
                          }
                      });

              });




            function fnGetDBfrontstore(){

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                    var frontstore_id_fk = $("#frontstore_id_fk").val();
                    $("input[name=_method]").val('');
                    //alert(frontstore_id_fk);

                    $.ajax({
                         type:'POST',
                         dataType:'JSON',
                         url: " {{ url('backend/ajaxGetDBfrontstore') }} ",
                         data:{ _token: '{{csrf_token()}}',
                             frontstore_id_fk:frontstore_id_fk,
                            },
                          success:function(d){

                            // console.log(d);
                            // return false;

                            if(d){

                              var pay_type_id_fk = $("#pay_type_id_fk").val();
                              if(pay_type_id_fk==''){
                                $("#cash_price").val('');
                                $("#cash_pay").val('');
                              }

                              if(pay_type_id_fk==5){
                                $("#aicash_price").val(formatNumber(parseFloat(0).toFixed(2)));
                              }

                              if(pay_type_id_fk==6){
                                $("#aicash_price").focus();
                                $("#cash_price").val('');
                                $("#cash_pay").val('');
                              }

                              $.each(d,function(key,value){

                                  $("#pv_total").val(formatNumber(parseFloat(value.pv_total).toFixed(2)));
                                  $("#sum_price").val(formatNumber(parseFloat(value.sum_price).toFixed(2)));
                                  $("#product_value").val(formatNumber(parseFloat(value.product_value).toFixed(2)));
                                  $("#tax").val(formatNumber(parseFloat(value.tax).toFixed(2)));

                                  $("#credit_price").val(formatNumber(parseFloat(value.credit_price).toFixed(2)));
                                  $("#fee_amt").val(formatNumber(parseFloat(value.fee_amt).toFixed(2)));
                                  $("#sum_credit_price").val(formatNumber(parseFloat(value.sum_credit_price).toFixed(2)));

                                  $("#transfer_price").val(formatNumber(parseFloat(value.transfer_price).toFixed(2)));

                                  $("#cash_price").val(formatNumber(parseFloat(value.cash_price).toFixed(2)));

                                  if(pay_type_id_fk==''){
                                     $("#cash_pay").val('');
                                  }else{
                                     $("#cash_pay").val(formatNumber(parseFloat(value.cash_pay).toFixed(2)));
                                  }

                                  $("#transfer_money_datetime").val(value.transfer_money_datetime);
                                  $("#transfer_money_datetime_02").val(value.transfer_money_datetime_02);
                                  $("#transfer_money_datetime_03").val(value.transfer_money_datetime_03);

                                  if(value.shipping_free==1){
                                      $('.input_shipping_free').show();
                                      $('.input_shipping_nofree').hide();
                                  }else{
                                      $('#shipping_price').val(formatNumber(parseFloat(value.shipping_price).toFixed(2)));
                                      $('.input_shipping_free').hide();
                                      $('.input_shipping_nofree').show();
                                  }

                                  if(pay_type_id_fk==6||pay_type_id_fk==11){
                                    if(value.aicash_price>0){
                                      $("#aicash_price").val(formatNumber(parseFloat(value.aicash_price).toFixed(2)));
                                    }else{
                                      $("#aicash_price").val('');
                                      $('#aicash_price').attr('required', true);
                                    }
                                  }

                                  if(pay_type_id_fk==9){
                                    if(value.aicash_price>0){
                                      $("#aicash_price").val(formatNumber(parseFloat(value.aicash_price).toFixed(2)));
                                    }else{
                                      $("#aicash_price").val(formatNumber(parseFloat(0).toFixed(2)));
                                    }
                                  }

                                });

                               }

                              $("input[name=_method]").val('PUT');
                              $("#spinner_frame").hide();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                $("#spinner_frame").hide();
                            }
                      });
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@


                }



            function fnGetDBfrontstoreGiftvoucher(){

                    var frontstore_id_fk = $("#frontstore_id_fk").val();
                    $("input[name=_method]").val('');
                    //alert(frontstore_id_fk);

                    $.ajax({
                         type:'POST',
                         dataType:'JSON',
                         url: " {{ url('backend/ajaxGetDBfrontstore') }} ",
                         data:{ _token: '{{csrf_token()}}',
                             frontstore_id_fk:frontstore_id_fk,
                            },
                          success:function(d){

                            if(d){
                              // // // // console.log(d);
                              /*

                              1 เงินสด
                              2 เงินสด + Ai-Cash
                              3 เครดิต + เงินสด
                              4 เครดิต + เงินโอน
                              5 เครดิต + Ai-Cash
                              6 เงินโอน + เงินสด
                              7 เงินโอน + Ai-Cash

                              */
                              var pay_type_id_fk = $("#pay_type_id_fk").val();
                              if(pay_type_id_fk==''){
                                $("#cash_price").val('');
                                $("#cash_pay").val('');
                              }

                              if(pay_type_id_fk==5){
                                $("#aicash_price").val(formatNumber(parseFloat(0).toFixed(2)));
                              }

                              if(pay_type_id_fk==6){
                                $("#aicash_price").focus();
                                $("#cash_price").val('');
                                $("#cash_pay").val('');
                              }

                              $.each(d,function(key,value){

                                  $("#pv_total").val(formatNumber(parseFloat(value.pv_total).toFixed(2)));
                                  $("#sum_price").val(formatNumber(parseFloat(value.sum_price).toFixed(2)));
                                  $("#product_value").val(formatNumber(parseFloat(value.product_value).toFixed(2)));
                                  $("#tax").val(formatNumber(parseFloat(value.tax).toFixed(2)));

                                  $("#credit_price").val(formatNumber(parseFloat(value.credit_price).toFixed(2)));
                                  $("#fee_amt").val(formatNumber(parseFloat(value.fee_amt).toFixed(2)));
                                  $("#sum_credit_price").val(formatNumber(parseFloat(value.sum_credit_price).toFixed(2)));

                                  $("#transfer_price").val(formatNumber(parseFloat(value.transfer_price).toFixed(2)));

                                  $("#cash_price").val(formatNumber(parseFloat(value.cash_price).toFixed(2)));

                                  if(pay_type_id_fk==''){
                                     $("#cash_pay").val('');
                                  }else{
                                     $("#cash_pay").val(formatNumber(parseFloat(value.cash_pay).toFixed(2)));
                                  }


                                  $("#transfer_money_datetime").val(value.transfer_money_datetime);
                                  $("#transfer_money_datetime_02").val(value.transfer_money_datetime_02);
                                  $("#transfer_money_datetime_03").val(value.transfer_money_datetime_03);

                                  if(value.shipping_free==1){
                                      $('.input_shipping_free').show();
                                      $('.input_shipping_nofree').hide();
                                  }else{
                                      $('#shipping_price').val(formatNumber(parseFloat(value.shipping_price).toFixed(2)));
                                      $('.input_shipping_free').hide();
                                      $('.input_shipping_nofree').show();
                                  }

                                  if(pay_type_id_fk==6||pay_type_id_fk==11){
                                    if(value.aicash_price>0){
                                      $("#aicash_price").val(formatNumber(parseFloat(value.aicash_price).toFixed(2)));
                                    }else{
                                      $("#aicash_price").val('');
                                      $('#aicash_price').attr('required', true);
                                    }
                                  }

                                  if(pay_type_id_fk==9){
                                    if(value.aicash_price>0){
                                      $("#aicash_price").val(formatNumber(parseFloat(value.aicash_price).toFixed(2)));
                                    }else{
                                      $("#aicash_price").val(formatNumber(parseFloat(0).toFixed(2)));
                                    }
                                  }

                                });

                               }

                              $("input[name=_method]").val('PUT');

                              $("#spinner_frame").hide();

                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                // // // // console.log(JSON.stringify(jqXHR));
                                // // // // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                $("#spinner_frame").hide();
                            }
                      });


                }

        </script>


<script type="text/javascript">
  
            var user_name = '{{@$user_name}}' ;
            var oTableChooseCourse ;
            $(function() {
                $.fn.dataTable.ext.errMode = 'throw';
                oTableChooseCourse = $('#data-table-choose-course').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    ordering: false,
                    "info":     false,
                    destroy: true,
                    searching: false,
                    paging: false,
                    ajax: {
                        url: '{{ route('backend.course_event_frontstore.datatable') }}',
                        data :{
                              user_name:user_name,
                            },
                          method: 'POST',
                        },
                     columns: [
                        {data: 'id', title :'ID', className: 'text-center w30'},
                        {data: 'ce_name', title :'<center>ชื่อกิจกรรม</center>', className: 'text-left w100 '},
                        {data: 'ce_type_desc', title :'<center>ประเภท </center>', className: 'text-center w50'},
                        {data: 'ce_place', title :'<center>สถานที่จัดงาน</center>', className: 'text-left w150 '},
                        {data: 'ce_ticket_price', title :'<center>ราคาบัตร (บาท)</center>', className: 'text-center'},
                        {data: 'ce_sdate', title :'<center>เริ่ม</center>', className: 'text-center  w70  '},
                        {data: 'ce_edate', title :'<center>สิ้นสุด</center>', className: 'text-center  w70 '},
                        {data: 'id', title :'<center>จำนวนสมัคร</center>', className: 'text-center  w70 '},
                        {data: 'id', title :'', className: 'text-center w2 '},
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                        // // // // console.log(aData['CourseCheckRegis']);
                        // // // // console.log(aData['user_name']);


                        $('td:last-child', nRow).html('');

                        if(aData['CourseCheckRegis']=="success"){
                             $("td:eq(7)", nRow).html('<center><input class="form-control amt_apply in-tx " type="number"  name="amt_apply[]" user_name="'+aData['user_name']+'" id_course="'+aData['id']+'" style="background-color:#e6ffff;border: 2px inset #EBE9ED;width:60%;text-align:center;" ><input type="hidden" name="id[]" value="'+(aData['id'])+'" >');
                        }else{
                            
                            var cuase_cannot_buy = aData['cuase_cannot_buy'];

                            $("td:eq(7)", nRow).html('<center><input class="form-control " type="text" style="background-color:#d9d9d9 !important;border: 1px inset #EBE9ED;width:60%;text-align:center;cursor:pointer;" readonly placeholder="-" data-toggle="tooltip" title="สาเหตุที่ซื้อไม่ได้ เพราะ '+aData['cuase_cannot_buy']+' "  >');
                              for (var i = 0; i < 7; i++) {
                                // $('td:eq( '+i+')', nRow).html(aData[i]).css({'color':'#cccccc','text-decoration':'line-through','font-style':'italic'});
                                $('td:eq( '+i+')', nRow).html(aData[i]).css({'color':'#999999','font-style':'italic'});
                              }
                        }

                    }

                });

                    oTableChooseCourse.on( 'draw', function () {
                      $('[data-toggle="tooltip"]').tooltip();
                      });

              
            });

  </script>

  <script type="text/javascript">
     $(document).ready(function(){   

          $(".btnSaveCourse").prop('disabled', true);

         $(document).on('change', '.amt_apply', function(event) {
            // event.preventDefault();
             $("#spinner_frame").show();
             let amt_apply = $(this).val();
             let id_course = $(this).attr('id_course');
             let user_name = $(this).attr('user_name');
             // // // console.log(amt_apply);
             // // // console.log(id_course);
             // // // console.log(user_name);

             // $request->id_course,$request->amt_apply ,$request->user_name);

              $.ajax({
                   type:'POST',
                   url: " {{ url('backend/ajaxCourseCheckRegis') }} ",
                   data: { _token: '{{csrf_token()}}', 
                   amt_apply:amt_apply,
                   id_course:id_course,
                   user_name:user_name,
                  },
                  success:function(data){
                         // // // console.log(data);
                         // alert("Test");
                         if(data=="fail"){
                          alert("! ไม่สามารถสมัครคอร์สนี้ได้ เนื่องจากไม่เข้าเงื่อนไขเกณฑ์ที่กำหนด โปรดตรวจสอบอีกครั้ง");
                          $('.amt_apply').val('');
                          $('.amt_apply').focus();
                          $("#spinner_frame").hide();
                          $(".btnSaveCourse").prop('disabled', true);
                          return false;
                         }else{
                          $(".btnSaveCourse").prop('disabled', false);
                          $("#spinner_frame").hide();
                         }
                       
                    },

              });

         });
     });
  </script>

        <script>


      //   $(document).on('click', '.btnAddAiCashModal02', function(event) {
            // event.preventDefault();

            // $("#spinner_frame").show();
            // var member_id_aicash = $("#member_id_aicash").val();
            // if(member_id_aicash==''){
            //   alert("! กรุณา ระบุสมาชิกเพื่อชำระด้วย Ai-Cash ก่อนค่ะ ขอบคุณค่ะ");
            //   $("#spinner_frame").hide();
            //   return false;
            // }

            // $('#modalAddAiCash').modal('show');
            // $("#member_id_aicash_modal").val(member_id_aicash);
            // $("#spinner_frame").hide();

   //      });

         $(document).on('click', '.btnAddAiCashModal02', function(event) {
            event.preventDefault();
             $("#spinner_frame").show();
            // var customer_id = "{{@$sRow->customers_id_fk}}";
            var customer_id = $("#member_id_aicash").val();
            var member_name_aicash = $("#member_name_aicash").val();
            // alert(member_name_aicash);
            // return false;

            if(customer_id==''){
              alert("! กรุณา ระบุสมาชิกเพื่อชำระด้วย Ai-Cash ก่อนค่ะ ขอบคุณค่ะ");
              $("#spinner_frame").hide();
              return false;
            }
            var frontstore_id_fk = $("#frontstore_id_fk").val();
            // alert(customer_id);
            setTimeout(function(){
              location.replace("{{ url('backend/add_ai_cash/create') }}"+"/?customer_id="+customer_id+"&frontstore_id_fk="+frontstore_id_fk+"&member_name_aicash="+member_name_aicash+"&fromAddAiCash=1");
            }, 1000);
         });



           $(document).on('click', '.btnSave', function(event) {
            // alert("xx");
            event.preventDefault();
             $("#frm-main").valid();

                            Swal.fire({
                                title: 'ยืนยัน ! การบันทึกข้อมูลใบเสร็จ ',
                                type: 'info',
                                showCancelButton: true,
                                confirmButtonColor: '#556ee6',
                                cancelButtonColor: "#f46a6a"
                                }).then(function (result) {
                                  // // // console.log(result);
                                    if (result.value) {
                                     // $("form").submit();
                                     // $("#spinner_frame").show();
                                     // location.reload();

                                     // OK @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@       
                                       $("#frm-main").valid();
                                       $("#spinner_frame").hide();

                                                   // alert(pay_type_id_fk+":"+aicash_remain+":"+aicash_price);
                                        if(pay_type_id_fk==6||pay_type_id_fk==9||pay_type_id_fk==11){
                                            // event.preventDefault();
                                            $("#aicash_price").focus();
                                          if(aicash_price>=0 && aicash_remain<=0){
                                            alert("! ยอด Ai-Cash ไม่เพียงพอต่อการชำระช่องทางนี้ กรุณาเติมยอด Ai-Cash ขอบคุณค่ะ");
                                            return false;
                                          }else{
                                            $('.btnSave').removeAttr("type").attr("type", "submit");
                                            $('#frm-main').submit();

                                          }

                                        }else{
                                          // $('#member_id_aicash').attr('required', false);
                                          $('.btnSave').removeAttr("type").attr("type", "submit");
                                           $('#frm-main').submit();
                                        }

                                     // OK @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

                                    }else{
                                       $("#spinner_frame").hide();
                                       return false;
                                    }
                              });

                  
         });

         // $(document).ready(function() {

         //          var fromAddAiCash = "<?=@$_REQUEST['fromAddAiCash']?>";
         //          // alert(fromAddAiCash);
         //          if(fromAddAiCash !=="" && fromAddAiCash==1){

         //            var frontstore_id_fk = $("#frontstore_id_fk").val();

         //            // var customer_id = $('#member_id_aicash').val();
         //              if(frontstore_id_fk==''){
         //                  return false;
         //              }else{

         //                  $.ajax({
         //                     type:'POST',
         //                     url: " {{ url('backend/ajaxClearAfterAddAiCash') }} ",
         //                     data: { _token: '{{csrf_token()}}', frontstore_id_fk:frontstore_id_fk },
         //                      success:function(data){
         //                             // // // console.log(data);
         //                             // alert("Test");
         //                            $("#spinner_frame").hide();
         //                        },

         //                  });

         //                  $('#pay_type_id_fk').val("").select2();
         //                  $('#pay_type_id_fk').attr('required', true);

         //                  $(".show_div_credit").hide();
         //                  $(".div_fee").hide();
         //                  $(".show_div_transfer_price").hide();
         //                  $(".div_account_bank_id").hide();
         //                  $(".show_div_aicash_price").hide();

         //                  $("#cash_price").val('');
         //                  $("#cash_pay").val('');
         //                  $("#spinner_frame").hide();
         //                  $(".show_div_cash_pay").hide();

         //                  $('#member_id_aicash').attr('required', false);
         //                  $('#member_id_aicash').val("");

         //                  $('#fee').removeAttr('required');
         //                  $('input[name=account_bank_id]').removeAttr('required');
         //                  $('.transfer_money_datetime').removeAttr('required');
         //                  $('#aicash_price').removeAttr('required');
         //                  $(".show_div_cash_pay").hide();
         //                  $(".show_div_transfer_price").hide();
         //                  $(".div_account_bank_id").hide();

         //              }

         //                  setTimeout(function(){
         //                      var uri = window.location.toString();
         //                    if (uri.indexOf("?") > 0) {
         //                      var clean_uri = uri.substring(0, uri.indexOf("?"));
         //                      window.history.replaceState({}, document.title, clean_uri);
         //                    }
         //                 },3000);

         //            }


         // });


        $(document).ready(function() {
            $(document).on('click', '.btnBack', function(event) {
               localStorage.clear();
            });
        });



    </script>
<!-- 

         $('.in-tx').keypress(function (e) {
             if (e.which === 13) {
                 var index = $('.in-tx').index(this) + 1;
                 // $('.in-tx').eq(index).focus();
                 // $(".btnSave").focus();
                 return false;
             }
         });
 -->

 <script language="JavaScript">
      document.onkeydown = chkEvent 
      function chkEvent(e) {
        var keycode;
        if (window.event) keycode = window.event.keyCode; //*** for IE ***//
        else if (e) keycode = e.which; //*** for Firefox ***//
        if(keycode==13)
        {
           var index = $('.in-tx').index(this) + 1;
          return false;
        }
    }
</script>

  <!-- Script -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->

<!-- jQuery UI -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script type="text/javascript">

    // $( function() {
    //  // สำหรับกรณี Autocomplete
    //  $( "#customer" ).autocomplete({
    //        source: function( request, response ) {
    //          $("#spinner_frame").show();
    //          var txt = $('#customer').val();
    //          $.ajax({
    //           url: " {{ url('backend/ajaxGetCustomer') }} ",
    //           method: "post",
    //           dataType: "json",
    //           data: {
    //             txt:txt,
    //             "_token": "{{ csrf_token() }}",
    //           },
    //           success:function(data){
    //              // // // console.log(data);
    //              response( data );
    //              $("#spinner_frame").hide();

    //               $.each(data, function( index, value ) {
    //                     $('#customers_id_fk').val(value.id);
    //                });
    //           }
    //          });
    //         },

    //        });

    //  });


</script>

<script type="text/javascript">
  
         $(document).ready(function(){   


            $("#customers_id_fk").select2({
                minimumInputLength: 3,
                allowClear: true,
                placeholder: '-Select-',
                ajax: {
                url: " {{ url('backend/ajaxGetCustomerForFrontstore') }} ",
                type  : 'POST',
                dataType : 'json',
                delay  : 250,
                cache: false,
                data: function (params) {
                 return {          
                  term: params.term  || '',   // search term
                  page: params.page  || 1
                 };
                },
                processResults: function (data, params) {
                 return {
                  results: data
                 };
                }
               }
              });


            $("#member_id_aicash_select").select2({
                minimumInputLength: 3,
                allowClear: true,
                placeholder: '-Select-',
                ajax: {
                url: " {{ url('backend/ajaxGetCustomerForAicashSelect') }} ",
                type  : 'POST',
                dataType : 'json',
                delay  : 250,
                cache: false,
                data: function (params) {
                 return {          
                  term: params.term  || '',   // search term
                  page: params.page  || 1
                 };
                },
                processResults: function (data, params) {
                 return {
                  results: data
                 };
                }
               }
              });



            $("#aistockist").select2({
                minimumInputLength: 3,
                allowClear: true,
                placeholder: '-Select-',
                ajax: {
                url: " {{ url('backend/ajaxGetCustomerAistockist') }} ",
                type  : 'POST',
                dataType : 'json',
                delay  : 250,
                cache: false,
                data: function (params) {
                 return {          
                  term: params.term  || '',   // search term
                  page: params.page  || 1
                 };
                },
                processResults: function (data, params) {
                 return {
                  results: data
                 };
                }
               }
              });

             $(document).on('change', '#aistockist', function(event) {

                    $('.aistockist').val($(this).val());
                     
              });
      

             $("#agency").select2({
                minimumInputLength: 3,
                allowClear: true,
                placeholder: '-Select-',
                ajax: {
                url: " {{ url('backend/ajaxGetCustomerAgency') }} ",
                type  : 'POST',
                dataType : 'json',
                delay  : 250,
                cache: false,
                data: function (params) {
                 return {          
                  term: params.term  || '',   // search term
                  page: params.page  || 1
                 };
                },
                processResults: function (data, params) {
                 return {
                  results: data
                 };
                }
               }
              });

             $(document).on('change', '#agency', function(event) {

                    $('.agency').val($(this).val());
                     
              });
      

       });

 

         $(document).ready(function() {

                    $(document).on('change', '#customers_id_fk', function(event) {
                        $("#spinner_frame").show();
                        var customer_id = $(this).val();
                         $.ajax({
                             type:'POST',
                             url: " {{ url('backend/ajaxGetRegis_date_doc') }} ",
                             data: { _token: '{{csrf_token()}}', 
                             customer_id:customer_id,
                            },
                            success:function(data){
                                  // // // // console.log(data);
                                   $.each(data, function( index, value ) {

                                            if(value.regis_date_doc==null){
                                              $(".note_check_regis_doc").show();
                                              $(".btn_btnsave").attr("disabled", true);
                                            }else{
                                              $(".note_check_regis_doc").hide();
                                              $(".btn_btnsave").attr("disabled", false);
                                            }

                                            $("#spinner_frame").hide();
                                    });
                            },

                        });
                    });



             $(document).on('change', '.class_transfer_edit', function(event) {

                        $("#spinner_frame").show();
                        $(".btnCalAddAicash").trigger('click');
                        $('.class_btnSave').addClass(' btnSave ');
                        $('.class_btnSave').removeAttr( "disabled" );

              });



        });


// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
     $(document).ready(function() {


          if(localStorage.getItem('pay_type_id_fk')){
              $('#pay_type_id_fk').val(localStorage.getItem('pay_type_id_fk')).select2();
          }


          // if(localStorage.getItem('member_id_aicash')){
              // $('#member_id_aicash').val(localStorage.getItem('member_id_aicash'));
              // $('#member_id_aicash_select').val(localStorage.getItem('member_id_aicash')).select2();
          // }
          // var member_id_aicash = $('#member_id_aicash').val();
          // console.log(member_id_aicash);
          
          // var member_id_aicash_select = $('#member_id_aicash_select').val();
          // console.log(member_id_aicash_select);

          $(document).on('submit', '#frm-main', function(event) {
              $("#spinner_frame").show();
          });

          $.fn.toggleSelect2 = function(state) {
              return this.each(function() {
                  $.fn[state ? 'show' : 'hide'].apply($(this).next('.select2-container'));
              });
          };

          $('#member_id_aicash_select').toggleSelect2(false); //hide
          $('#aicash_choose').show(); 

          var frontstore_id_fk = $("#frontstore_id_fk").val();
          var pay_type_id_fk = $("#pay_type_id_fk").val();
          console.log(pay_type_id_fk);
          var cash_pay = $("#cash_pay").val();
          // // // console.log(cash_pay);
/*
1 เงินโอน
2 บัตรเครดิต
3 Ai-Cash
4 Gift Voucher
5 เงินสด
6 เงินสด + Ai-Cash
7 เครดิต + เงินสด
8 เครดิต + เงินโอน
9 เครดิต + Ai-Cash
10  เงินโอน + เงินสด
11  เงินโอน + Ai-Cash
12  Gift Voucher + เงินโอน
13  Gift Voucher + บัตรเครดิต
14  Gift Voucher + Ai-Cash
15  PromptPay
16  TrueMoney
17  Gift Voucher + PromptPay
18  Gift Voucher + TrueMoney
*/


           var check_press_save = "{{@$sRow->check_press_save}}";
           var check_product_value = "{{@$sRow->product_value}}";
           // // // console.log(check_press_save);
           // // // console.log(check_product_value);

          if(check_product_value>0){
              $(".div_gift_set").show();
          }else{
              $(".div_gift_set").hide();
          }

          // Ajax ไปเช็คว่ามีการเลือกสินค้าแล้วหรือยัง เพื่อ อัพเดตฟิลด์ check_press_save
          var frontstore_id_fk = $("#frontstore_id_fk").val();
          $.ajax({
               type:'POST',
               url: " {{ url('backend/ajaxForCheck_press_save') }} ", 
               data:{ _token: '{{csrf_token()}}',frontstore_id_fk:frontstore_id_fk},
                success:function(data){
                     // // // console.log(data); 
                  },
                error: function(jqXHR, textStatus, errorThrown) { 
                    // // console.log(JSON.stringify(jqXHR));
                }
            });


         // ถ้าไม่มีการเลือกรูปแบบการชำระเงินแล้ว และเช็คดูว่า เลือกรหัสอะไรไว้ ให้ทำการ onchange pay_type_id_fk อีกครั้ง เพื่อเรียกฟอร์มที่เกี่ยวข้องต่างๆ แสดงให้สอดคล้อง ไม่ให้มันหายไป
          // if ((pay_type_id_fk !== undefined) && (pay_type_id_fk !== null) && (pay_type_id_fk !== "")) {
          if ((pay_type_id_fk == undefined) && (pay_type_id_fk == null) && (pay_type_id_fk == "")) {
             // // console.log(pay_type_id_fk);
             // $("#pay_type_id_fk").select2('destroy').val("").select2();
               $('#pay_type_id_fk').val(pay_type_id_fk).trigger("change");
            // ถ้าไม่ได้เลือก รูปแบบการชำระเงิน แล้ว รีเฟรชเพ็จ
                //  $.ajax({
                //    type:'POST',
                //    dataType:'JSON',
                //    url: " {{ url('backend/ajaxClearCostFrontstore') }} ",
                //    data: { _token: '{{csrf_token()}}', frontstore_id_fk:frontstore_id_fk },
                //    success:function(data){
                //    },
                // });
          }


          // ไปเช็คตารางสินค้าด้วย ถ้ายังไม่มีการเลือกสินค้า ยังไม่ต้องเช็คเงื่อนไขนี้ แต่ถ้ามีการเลือกสินค้าแล้ว ค่อยตรวจสอบอีกครั้ง
          // check_press_save => 0=ยังไม่ได้เลือกสินค้า 1=มีการเลือกสินค้าแล้ว 2=มีการกดปุ่ม save แล้ว (เอาไว้เช็คกรณีซื้อที่หลังบ้าน เพื่อไม่ให้การคำนวณเงินผิดเพี้ยนไปจากเดิม)
          // if(check_press_save=='1' && check_product_value>0 ){
          if(check_press_save=='1'){


               // $.ajax({
               //        url: "{{ url('backend/ajaxClearPayTypeFrontstore') }}",
               //        type: "POST",
               //        data: {_token: '{{csrf_token()}}', frontstore_id_fk:frontstore_id_fk},
               //        success: function () {
               //          $('#pay_type_id_fk').val("").select2();
               //          $('#pay_type_id_fk').val("").trigger("change");
               //        }
               //    });




//         window.addEventListener("unload", function(){
//           var count = parseInt(localStorage.getItem('counter') || 0);

//           localStorage.setItem('counter', ++count)
//         }, false);

//         var c = localStorage.getItem('counter')?localStorage.getItem('counter'):0;

//         if(c>=0){

//                                       Swal.fire({
//                                         title: ' ไม่ได้ทำการบันทึกข้อมูลใบเสร็จ ? ',
//                                         // showDenyButton: true,
//                                         showCancelButton: true,
//                                         confirmButtonText: `Ok`,
//                                         // denyButtonText: `Don't save`,
//                                       }).then((result) => {
//                                         /* Read more about isConfirmed, isDenied below */
//                                         if (result.isConfirmed) {
//                                           // Swal.fire('Saved!', '', 'success')
//                                            location.replace("{{ url("backend/frontstore") }}")

//                                         } else  {
//                                           // Swal.fire('Changes are not saved', '', 'info')

//                                            $.ajax({
//                                                 url: "{{ url('backend/ajaxClearPayTypeFrontstore') }}",
//                                                 type: "POST",
//                                                 data: {_token: '{{csrf_token()}}', frontstore_id_fk:frontstore_id_fk},
//                                                 success: function () {
//                                                   $('#pay_type_id_fk').val("").select2();
//                                                 }
//                                             });


//                                              // $.ajax({
//                                              //        url: "{{ url('backend/ajaxClearPayTypeFrontstore') }}",
//                                              //        type: "POST",
//                                              //        data: { _token: '{{csrf_token()}}', frontstore_id_fk:frontstore_id_fk },
//                                              //        dataType: "html",
//                                              //        success: function () {
//                                              //            $('#pay_type_id_fk').val("").select2();
//                                              //            swal("Done!", "It was succesfully deleted!", "success");
//                                              //            $("#spinner_frame").hide();
//                                              //        },
//                                              //    })


//                                         }
//                                       })
// }

                      // Swal.fire({
                      //   title: 'กรุณาตรวจสอบอีกครั้ง ! คุณยังไม่ได้ทำการบันทึกข้อมูลใบเสร็จ',
                      //   showCancelButton: true,
                      //   confirmButtonText: `Save`,
                      //   // denyButtonText: `Don't save`,
                      // }).then((result) => {

                      //   $("#spinner_frame").show();

                      //   /* Read more about isConfirmed, isDenied below */
                      //   if (result.isConfirmed) {

                      //      // $.ajax({
                      //      //     type:'POST',
                      //      //     dataType:'JSON',
                      //      //     url: " {{ url('backend/ajaxClearPayTypeFrontstore') }} ",
                      //      //     data: { _token: '{{csrf_token()}}', frontstore_id_fk:frontstore_id_fk },
                      //      //     success:function(data){
                      //      //       $('#pay_type_id_fk').val("").select2();
                      //      //        $("#spinner_frame").hide();
                      //      //        alert("xxxxxxxxx");
                      //      //     },
                      //      //  })


                      //     Swal.fire('Saved!', '', 'success')

                      //   } else if (result.isDenied) {
                      //      $("#spinner_frame").hide();
                      //     Swal.fire('Changes are not saved', '', 'info')
                      //     location.replace("{{ url("backend/frontstore") }}")
                      //   }
                      // })


                    // Swal.fire({
                    //   title: 'กรุณาตรวจสอบอีกครั้ง ! คุณยังไม่ได้ทำการบันทึกข้อมูลใบเสร็จ  ',
                    //   type: 'info',
                    //   showCancelButton: true,
                    //   confirmButtonColor: '#556ee6',
                    //   cancelButtonColor: "#f46a6a"
                    //   }).then(function (result) {
                    //     // // // console.log(result);
                    //     $("#spinner_frame").show();
                    //       if (result.value) {
                    //        // $("form").submit();
                             
                    //        // location.reload();
                    //         // $(".btnBack").trigger('click');

                    //        // OK @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@   
                    //         $.ajax({
                    //            type:'POST',
                    //            dataType:'JSON',
                    //            url: " {{ url('backend/ajaxClearPayTypeFrontstore') }} ",
                    //            data: { _token: '{{csrf_token()}}', frontstore_id_fk:frontstore_id_fk },
                    //            success:function(data){

                    //              $('#pay_type_id_fk').val("").select2();
                    //               $("#spinner_frame").hide();
                    //               alert("xxxxxxxxx");
                    //            },
                    //         });  

                    //          // location.reload();  
                    //          // $("#frm-main").valid();
                            
                    //        // OK @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

                    //       }else{
                    //          location.replace("{{ url("backend/frontstore") }}")
                    //          // $("#spinner_frame").hide();
                    //          return false;
                    //       }
                    // });

           }

          // if(check_press_save=='2'){
          //   $("#pay_type_id_fk").prop('disabled',true);
          // }



            if(pay_type_id_fk==5){
              // ยอดเงินสดที่ต้องชำระ ต้องไม่เป็น 0 ถ้าเป็น 0 ให้ pay_type_id reset 
               if(cash_pay=="0.00"){
                 $("#pay_type_id_fk").select2('destroy').val("").select2();
                 $(".show_div_cash_pay ").hide();
               }
            }


          $(document).on('click', '#aicash_choose', function () {
                $(this).hide();
                $('#member_id_aicash_select').next(".select2-container").show();
                $("#member_id_aicash_select").select2('open');

          });


          $('#pay_with_other_bill').click(function(event) {
            if($('#pay_with_other_bill').is(':checked')==true){
                $("#pay_with_other_bill_note").prop('required',true);
                $("input[name='account_bank_id']").removeAttr("required");
                $(".transfer_money_datetime").removeAttr("required");
                $("#pay_with_other_bill_note").focus();
            }else{
                $("#pay_with_other_bill_note").removeAttr("required");
                $("input[name='account_bank_id']").prop('required',true);
                $("#pay_with_other_bill_note").val("");
            }
        });


            fnGetDBfrontstore();
     

     });


        // window.addEventListener("unload", function(){
        //   var count = parseInt(localStorage.getItem('counter') || 0);

        //   localStorage.setItem('counter', ++count)
        // }, false);

        // var c = localStorage.getItem('counter')?localStorage.getItem('counter'):0;

        // alert('You refreshed page '+c+' times');

        // if (localStorage.getItem('counter') == 6) {
        //  alert('You refreshed page 6 times')
        // }
  

</script>



      <script>
// Clear data in View page  
      $(document).ready(function() {
            $(".test_clear_data").on('click',function(){

                  if (!confirm("โปรดระวัง ยืนยัน ! เพื่อล้างข้อมูลรายการสั่งซื้อทั้งหมดเพื่อเริ่มต้นคีย์ใหม่ ? ")){
                      return false;
                  }else{
                  
                      location.replace( window.location.href+"?test_clear_data=test_clear_data ");
                  }
   
            });
                
      });

    </script>
   
    <?php 
    if(isset($_REQUEST['test_clear_data'])){


      DB::select("TRUNCATE db_pay_product_receipt_001;");
      DB::select("TRUNCATE db_pay_product_receipt_002;");
      DB::select("TRUNCATE db_pay_product_receipt_002_pay_history;");
      DB::select("TRUNCATE db_pay_product_receipt_002_cancel_log;");

      DB::select("TRUNCATE `db_pay_requisition_001`;");
      DB::select("TRUNCATE `db_pay_requisition_002`;");
      DB::select("TRUNCATE `db_pay_requisition_002_cancel_log`;");
      DB::select("TRUNCATE `db_pay_requisition_002_pay_history`;");

      DB::select("TRUNCATE `db_pick_pack_packing`;");
      DB::select("TRUNCATE `db_pick_pack_packing_code`;");
      
      DB::select("TRUNCATE `db_pick_pack_requisition_code`;");

      DB::select("TRUNCATE db_pick_warehouse_qrcode;");
      DB::select("TRUNCATE db_stocks_return;");
      DB::select("TRUNCATE db_stock_card;");
      DB::select("TRUNCATE db_stock_card_tmp;");
      
      DB::select("TRUNCATE customers_addr_sent;");
          
      $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id; 
      $temp_db_stocks_check002 = "temp_db_stocks_check002".\Auth::user()->id; 
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id; 
      $temp_db_stocks_compare002 = "temp_db_stocks_compare002".\Auth::user()->id; 
      $temp_db_pick_pack_requisition_code = "db_pick_pack_requisition_code".\Auth::user()->id; 

      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check002 ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_compare002 ; ");
      DB::select(" DROP TABLE IF EXISTS $temp_db_pick_pack_requisition_code ; ");

      DB::select(" UPDATE db_stocks SET amt='100' ; ");


      DB::select("TRUNCATE `db_orders`;");
      DB::select("TRUNCATE `db_orders_tmp`;");
      DB::select("TRUNCATE `db_order_products_list`;");
      DB::select("TRUNCATE `db_order_products_list_tmp`;");
      
      DB::select("TRUNCATE `db_delivery` ;");
      DB::select("TRUNCATE `db_delivery_packing` ;");
      DB::select("TRUNCATE `db_delivery_packing_code` ;");
      DB::select("TRUNCATE `db_pick_warehouse_packing_code` ;");
      DB::select("TRUNCATE  db_consignments;");
      
      DB::select("TRUNCATE `db_sent_money_daily` ;");

      DB::select("TRUNCATE `db_add_ai_cash` ;");
      DB::select("TRUNCATE `payment_slip` ;");
      
      // DB::select("TRUNCATE `db_check_money_daily` ;"); // ไม่ได้ใช้แล้ว

      ?>
          <script>
          location.replace( "{{ url('backend/frontstore') }}");
          </script>
          <?php
      }
    ?>

@endsection