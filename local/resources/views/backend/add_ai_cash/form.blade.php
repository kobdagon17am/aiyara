@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-7">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> เติม Ai-Cash </h4>

            <?php if(isset($_REQUEST['fromAddAiCash'])){ $frontstore_id_fk = $_REQUEST['frontstore_id_fk']; ?>
                <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/frontstore/$frontstore_id_fk/edit") }}">
                      <i class="bx bx-arrow-back font-size-18 align-middle mr-1"></i> <span style="font-size: 14px;">กลับไปหน้าขาย</span>
                </a>
            <?php }else{ ?>
                <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/add_ai_cash") }}">
                      <i class="bx bx-arrow-back font-size-18 align-middle mr-1"></i> <span style="font-size: 14px;">ย้อนกลับ</span>
                </a>
            <?php } ?>
                    
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

   ?>

<div class="row">
    <div class="col-7">
        <div class="card">
            <div class="card-body">
              @if( empty(@$sRow) )
              <form id="frm-main" action="{{ route('backend.add_ai_cash.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form id="frm-main" action="{{ route('backend.add_ai_cash.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
                <input name="id" type="hidden" value="{{@$sRow->id}}">
              @endif
                {{ csrf_field() }}


                      <div class="myBorder">

                        <?php if(isset($_REQUEST['fromAddAiCash'])){ $customer_id = $_REQUEST['customer_id']; ?>

                          <div class="form-group row">
                            <label for="customer_id_fk" class="col-md-4 col-form-label"> รหัส:ชื่อลูกค้า : * </label>
                            <div class="col-md-8">
                              <select  id="customer_id_fk" class="form-control select2-templating " disabled="" >
                                <option value="">Select</option>
                                  @if(@$Customer)
                                    @foreach(@$Customer AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$customer_id)?'selected':'' }} >
                                        {{$r->user_name}} : {{$r->prefix_name}}{{$r->first_name}} 
                                        {{$r->last_name}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>

                          <input type="hidden" name="customer_id_fk" value="<?=@$customer_id?>" >

                       <?php }else{ ?>

                         <div class="form-group row">
                            <label for="customer_id_fk" class="col-md-4 col-form-label"> รหัส:ชื่อลูกค้า : * </label>
                            <div class="col-md-8">
                              <select name="customer_id_fk" id="customer_id_fk" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                  @if(@$Customer)
                                    @foreach(@$Customer AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->customer_id_fk)?'selected':'' }} >
                                        {{$r->user_name}} : {{$r->prefix_name}}{{$r->first_name}} 
                                        {{$r->last_name}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>

                        <?php } ?>
<?php //dd($CustomerAicash) ?>
                        @if(!empty(@$sRow))
                          <div class="form-group row">
                            <label for="aicash_amt" class="col-md-4 col-form-label">ยอด Ai-Cash ที่มี :</label>
                            <div class="col-md-8">
                               <input class="form-control f-ainumber-18 input-aireadonly " value="{{number_format(@$CustomerAicash[0]->ai_cash,2)}}" readonly />
                            </div>
                          </div>
                        @ENDIF
                          
                          <div class="form-group row">
                            <label for="aicash_amt" class="col-md-4 col-form-label">ยอด Ai-Cash ที่เติม :</label>
                            <div class="col-md-8">
                              <input class="form-control CalAicashAmt NumberOnly input-airight f-ainumber-18 input-aifill" type="text" value="{{ @$sRow->aicash_amt }}" name="aicash_amt" autofocus required >
                            </div>
                          </div>
                         
                          @if( !empty(@$sRow) )
                <hr>

                          <div class="form-group row">
                            <label for="pay_type_id" class="col-md-4 col-form-label"> รูปแบบการชำระเงิน :</label>
                            <div class="col-md-8">
                               <select id="pay_type_id" name="pay_type_id" class="form-control select2-templating " required="" >
                                <option value="">Select</option>
                                    @if(@$sPay_type)
                                      @foreach(@$sPay_type AS $r)
                                        <option value="{{$r->id}}" {{ (@$r->id==@$sRow->pay_type_id)?'selected':'' }}  >
                                          {{$r->detail}} 
                                        </option>
                                      @endforeach
                                    @endif                                
                              </select> 
                            </div>
                          </div>


                        <?php $div_fee = @$sRow->fee==0?"display: none;":''; ?>
                          <div class="form-group row div_fee " style="<?=$div_fee?>">
                            <label for="fee" class="col-md-4 col-form-label"> ค่าธรรมเนียมบัตรเครดิต :</label>
                            <div class="col-md-8">
                              <select id="fee" name="fee" class="form-control select2-templating " >
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
                          </div>


                        <?php $div_fee = @$sRow->fee==0?"display: none;":''; ?>
                          <div class="form-group row div_fee " style="<?=$div_fee?>">
                            <label for="" class="col-md-4 col-form-label"> </label>
                            <div class="col-md-8 div_charger_type ">
                                   <?php $charger_type = @$sRow->charger_type==0||@$sRow->charger_type==''?"checked":''; ?>
                                  <input type="radio" class="" id="charger_type_01" name="charger_type" value="1" <?=(@$sRow->charger_type==1?'checked':$charger_type)?>  > <label for="charger_type_01">&nbsp;&nbsp;ชาร์ทในบัตร </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                  <input type="radio" class="" id="charger_type_02" name="charger_type" value="2" <?=(@$sRow->charger_type==2?'checked':'')?>  > <label for="charger_type_02">&nbsp;&nbsp;แยกชำระ </label>
                            </div>
                          </div>


                     <?php $show_div_credit = @$sRow->credit_price==0?"display: none;":''; ?>
                        <div class="form-group row show_div_credit " style="<?=$show_div_credit?>">
                          <label for="" class="col-md-4 col-form-label"> บัตรเครดิต : </label>
                          <div class="col-md-8 ">
                               <input class="form-control CalPrice NumberOnly input-airight f-ainumber-18 input-aifill in-tx " id="credit_price" name="credit_price" value="{{number_format(@$sRow->credit_price,2)}}" required="" />
                          </div>
                        </div>


                       <?php $show_div_credit = @$sRow->credit_price==0?"display: none;":''; ?>
                        <div class="form-group row show_div_credit " style="<?=$show_div_credit?>">
                          <label for="" class="col-md-4 col-form-label"> ค่าธรรมเนียมบัตร : </label>
                          <div class="col-md-8 ">
                                 <input class="form-control f-ainumber-18 input-aireadonly " id="fee_amt" name="fee_amt" value="{{number_format(@$sRow->fee_amt,2)}}" readonly />
                          </div>
                        </div>


                      <?php $show_div_credit = @$sRow->credit_price==0?"display: none;":''; ?>
                        <div class="form-group row show_div_credit " style="<?=$show_div_credit?>">
                          <label for="" class="col-md-4 col-form-label"> หักจากบัตรเครดิต : </label>
                          <div class="col-md-8 ">
                                  <input class="form-control f-ainumber-18-b input-aireadonly " id="sum_credit_price" name="sum_credit_price" value="{{number_format(@$sRow->sum_credit_price,2)}}" readonly />
                          </div>
                        </div>


                     <?php $div_account_bank_id = @$sRow->account_bank_id==0||@$sRow->account_bank_id==''?"display: none;":''; ?>
                        <div class="form-group row div_account_bank_id " style="<?=$div_account_bank_id?>">
                          <label for="" class="col-md-4 col-form-label"> เลือกบัญชีสำหรับโอน : </label>
                          <div class="col-md-8 ">
                              @if(@$sAccount_bank)
                              @foreach(@$sAccount_bank AS $r)
                                  <input type="radio" id="account_bank_id{{@$r->id}}"  name="account_bank_id" value="{{@$r->id}}" <?=(@$r->id==@$sRow->account_bank_id?'checked':'')?> > <label for="account_bank_id{{@$r->id}}">&nbsp;&nbsp;{{@$r->txt_account_name}} {{@$r->txt_bank_name}} {{@$r->txt_bank_number}}</label><br>
                              @endforeach
                            @endif 
                          </div>
                        </div>

                     <?php $div_account_bank_id = @$sRow->account_bank_id==0||@$sRow->account_bank_id==''?"display: none;":''; ?>
                        <div class="form-group row div_account_bank_id " style="<?=$div_account_bank_id?>">
                          <label for="" class="col-md-4 col-form-label">  </label>
                           <div class="col-md-8 d-flex ">
                           <button type="button" class="btn btn-success btn-sm font-size-12 btnUpSlip " style="">อัพไฟล์สลิป (ถ้ามี)</button>
                            <?php if(!empty(@$sRow->transfer_money_datetime)){
                              $ds1 = substr(@$sRow->transfer_money_datetime, 0,10);
                              $ds = explode("-", $ds1);
                              $ds_d = $ds[2];
                              $ds_m = $ds[1];
                              $ds_y = $ds[0];
                              $ds = $ds_d.'/'.$ds_m.'/'.$ds_y.' '.(date('H:i',strtotime(@$sRow->transfer_money_datetime)));
                            }else{$ds='';} ?>           
                              <input class="form-control transfer_money_datetime" autocomplete="off" value="{{$ds}}" style="width: 45%;margin-left: 5%;font-weight: bold;" placeholder="วัน เวลา ที่โอน" />
                              <input type="hidden" id="transfer_money_datetime" name="transfer_money_datetime" value="{{@$sRow->transfer_money_datetime}}"  />

                          </div>
                        </div>

                         <div class="form-group row div_account_bank_id " style="<?=$div_account_bank_id?>">
                          <label for="" class="col-md-4 col-form-label"> </label>
                          <div class="col-md-8 ">
                              <input type="file" accept="image/*" id="image01" name="image01" class="form-control" OnChange="showPreview_01(this)" style="display: none;" >

                                  <span width="100" class="span_file_slip" >
                                    @IF(!empty(@$sRow->file_slip))
                                      <img id="imgAvatar_01" src="{{ asset(@$sRow->file_slip) }}" style="margin-top: 5px;height: 180px;" > 
                                      <button type="button" data-id="{{@$sRow->id}}" class="btn btn-danger btn-sm font-size-10 btnDelSlip " style="vertical-align: bottom;margin-bottom: 5px;">ลบไฟล์</button>
                                    @ELSE
                                      <img id="imgAvatar_01" src="{{ asset('local/public/images/file-slip.png') }}" style="margin-top: 5px;height: 180px;display: none;" > 
                                    @ENDIF
                                  </span>
                          </div>
                        </div>



                      <?php $show_div_transfer_price = @$sRow->pay_type_id==8||@$sRow->pay_type_id==10||@$sRow->pay_type_id==11?"":'display: none;'; ?>
                        <div class="form-group row show_div_transfer_price " style="<?=$show_div_transfer_price?>">
                          <label for="" class="col-md-4 col-form-label"> ยอดเงินโอน : </label>
                          <div class="col-md-8 ">
                              
                            @IF(@$sRow->pay_type_id==8)
                            <input class="form-control input-airight f-ainumber-18-b input-aireadonly " id="transfer_price" name="transfer_price" value="{{number_format(@$sRow->transfer_price,2)}}" >
                            @ELSE
                            <input class="form-control CalPrice input-airight f-ainumber-18-b input-aifill " id="transfer_price" name="transfer_price" value="{{number_format(@$sRow->transfer_price,2)}}" >
                            @ENDIF
                                     
                          </div>
                        </div>

                      
                      <div class="form-group row show_div_cash_price " style="display: none;">
                        <label for="cash_price" class="col-md-4 col-form-label"> cash_price : </label>
                        <div class="col-md-8 ">
                              <input class="form-control input-airight f-ainumber-18 " id="cash_price" name="cash_price" value="{{number_format(@$sRow->cash_price,2)}}" readonly >
                        </div>
                      </div>


                      <?php $show_div_cash_pay = (@$sRow->pay_type_id==8||@$sRow->pay_type_id==9||@$sRow->pay_type_id==11)?"display: none;":''; ?>
                        <div class="form-group row show_div_cash_pay " style="<?=$show_div_cash_pay?>">
                          <label for="" class="col-md-4 col-form-label"> ยอดเงินสดที่ต้องชำระ : </label>
                          <div class="col-md-8 ">
                              
                           <?php 
                                $cash_pay = @$sRow->cash_pay ; 
                          ?>
                                <input class="form-control f-ainumber-18-b input-aireadonly in-tx " name="cash_pay" id="cash_pay" value="{{number_format(@$cash_pay,2)}}" readonly="" >
                                     
                          </div>
                        </div>

@ENDIF

                       <div class="form-group row">
                            <label for="" class="col-md-4 col-form-label">ผู้ดำเนินการ(User Login):</label>
                            <div class="col-md-8">
                              @if( empty($sRow) )
                                <input class="form-control f-ainumber-18 input-aireadonly" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                  <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="action_user" >
                                  @else
                                    <input class="form-control f-ainumber-18 input-aireadonly" type="text" value="{{@$action_user}}" readonly style="background-color: #f2f2f2;" >
                                  <input class="form-control" type="hidden" value="{{ @$sRow->action_user }}" name="action_user" >
                               @endif
                            </div>
                       </div>
                          

                       <div class="form-group row">
                            <label for="" class="col-md-4 col-form-label"></label>
                            <div class="col-md-8">

                                <input type="hidden" value="<?=@$_REQUEST['fromAddAiCash']?>" name="fromAddAiCash" >
                                <input type="hidden" value="<?=@$_REQUEST['frontstore_id_fk']?>" name="frontstore_id_fk" >

                                @if( empty($sRow) )
                                <input type="hidden" name="save_new" value="1" >
                                <button type="submit" class="btn btn-primary btn-sm waves-effect">
                                <i class="bx bx-save font-size-18 align-middle mr-1"></i><span style="font-size: 14px;"> บันทึกข้อมูล</span>
                                </button>
                                @else
                                <input type="hidden" name="save_update" value="1" >
                                <button type="submit" class="btn btn-primary btn-sm waves-effect">
                                <i class="bx bx-save font-size-18 align-middle mr-1"></i><span style="font-size: 14px;"> บันทึกข้อมูล</span>
                                </button>
                                @endif


                                  <a class="btn btn-info btn-sm btnPrint " href="{{ URL::to('backend/add_ai_cash/print_receipt') }}/{{@$sRow->id}}" target=_blank style="float: right;" >
                                  <i class="bx bx-printer align-middle mr-1 font-size-18 "></i><span style="font-size: 14px;"> ใบเสร็จ </span></a>


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

<script>
  $(document).ready(function() {


          function formatNumber(num) {
              return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            }
     
      $(document).on('change', '#pay_type_id', function(event) {

              event.preventDefault();
              $(".myloading").show();

              var id = "{{@$sRow->id}}";
              var pay_type_id = $("#pay_type_id").val();

              // alert(pay_type_id);

              $(".show_div_credit").hide();
              $(".div_fee").hide();
              $(".show_div_transfer_price").hide();
              $(".div_account_bank_id").hide();

              $('#fee').val("").select2();
              $("#cash_price").val('');
              $("#cash_pay").val('');
              $("#credit_price").val('');
              $("#fee_amt").val('');
              $("#sum_credit_price").val('');
              $("#transfer_price").val('');

              $('#fee').attr('required', false);
              $('#credit_price').attr('required', false);
              $('input[name=account_bank_id]').attr('required', false);
              $('input[name=account_bank_id]').val('');
              $(".transfer_money_datetime").attr('required', false);
              $(".transfer_money_datetime").val('');
              $("#transfer_price").removeAttr('required');


              if(pay_type_id==''){
                $(".myloading").hide();
                $(".show_div_cash_pay").hide();
                return false;
              }


                    $("input[name=_method]").val('');

                      $.ajax({
                       type:'POST',
                       dataType:'JSON',
                       url: " {{ url('backend/ajaxCalAddAiCashFrontstore') }} ", 
                       data: $("#frm-main").serialize()+"&id="+id,
                        success:function(data){
                                  console.log(data); 
                                  // return false;
                                  $.each(data,function(key,value){
                                     $("#cash_pay").val(formatNumber(parseFloat(value.cash_pay).toFixed(2)));  
                                  });

                                  $("input[name=_method]").val('PUT');
                                  $(".myloading").hide();
                                },
                              error: function(jqXHR, textStatus, errorThrown) { 
                                  $(".myloading").hide();
                              }
                          });

// alert(pay_type_id);
                          if(pay_type_id==5){
                              $(".show_div_cash_pay").show();

                            }else
                        // 3  เครดิต + เงินสด
                            if(pay_type_id==7){
                              // เครดิต
                                $(".show_div_credit").show();
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
                            if(pay_type_id==8){
                             
                                  // เครดิต
                                  $(".show_div_credit").show();
                                  $("#credit_price").val('');
                                  $(".div_fee").show();
                                  $("#fee_amt").val('');
                                  $('#fee').val("").select2();
                                  $('#fee').attr('required', true);
                                  $("#sum_credit_price").val('');
                                  // เงินโอน
                                  $(".show_div_transfer_price").show();
                                  $('input[name=account_bank_id]').prop('checked',false);
                                  $('input[name=account_bank_id]').attr('required', true);
                                  $(".div_account_bank_id").show();
                                  $("#transfer_price").val('');
                                  $(".transfer_money_datetime").attr('required', true);
                                  $("#transfer_price").removeAttr('required');
                                  $("#transfer_price").removeClass('input-aifill').addClass('input-aireadonly');

                                  $(".show_div_cash_pay").hide();

                                        
                              }else 
                              // 6  เงินโอน + เงินสด
                              if(pay_type_id==10){

                                // เงินโอน
                                $(".show_div_transfer_price").show();
                                $('input[name=account_bank_id]').prop('checked',false);
                                $('input[name=account_bank_id]').attr('required', true);
                                $(".div_account_bank_id").show();
                                $("#transfer_price").val('');
                                $(".transfer_money_datetime").attr('required', true);
                                $("#transfer_price").attr('required',true);
                                $("#transfer_price").removeClass('input-aireadonly').addClass('input-aifill').addClass('CalPrice');

                                $(".show_div_cash_pay").show();
                                $('#fee').removeAttr('required');
                                $("#cash_pay").val('');
                              
                            }
      });



      $(document).on('change', '.CalPrice', function(event) {

              event.preventDefault();
              $(".myloading").show();

              var id = "{{@$sRow->id}}";


              var pay_type_id = $("#pay_type_id").val();


              // $(".show_div_credit").hide();
              // $(".div_fee").hide();
              // $(".show_div_transfer_price").hide();
              // $(".div_account_bank_id").hide();
              // $('#fee').val("").select2();

              if(pay_type_id==''){
                $("#cash_price").val('');
                $("#cash_pay").val('');
                $(".myloading").hide();
                $(".show_div_cash_pay").hide();
                return false;
              }


                  $("input[name=_method]").val('');

                  $.ajax({
                   type:'POST',
                   dataType:'JSON',
                   // url: " {{ url('backend/ajaxCalPriceFrontstore01') }} ", 
                   url: " {{ url('backend/ajaxCalAddAiCashFrontstore') }} ", 
                   data: $("#frm-main").serialize()+"&id="+id,
                    success:function(data){
                          console.log(data); 

                          // return false;

                            $.each(data,function(key,value){
                                  
                                  $("#credit_price").val(formatNumber(parseFloat(value.credit_price).toFixed(2)));
                                  $("#fee_amt").val(formatNumber(parseFloat(value.fee_amt).toFixed(2)));
                                  $("#sum_credit_price").val(formatNumber(parseFloat(value.sum_credit_price).toFixed(2)));
                                  $("#transfer_price").val(formatNumber(parseFloat(value.transfer_price).toFixed(2)));  
                                  // $("#cash_price").val(formatNumber(parseFloat(value.cash_price).toFixed(2)));
                                  if(pay_type_id==''){
                                     $("#cash_pay").val('');    
                                  }else{
                                     $("#cash_pay").val(formatNumber(parseFloat(value.cash_pay).toFixed(2)));    
                                  }

                                  // $("#transfer_money_datetime").val(value.transfer_money_datetime);


                                });

                          $("input[name=_method]").val('PUT');
/*

          1 เงินสด
          2 เงินสด + Ai-Cash
          3 เครดิต + เงินสด
          4 เครดิต + เงินโอน
          5 เครดิต + Ai-Cash
          6 เงินโอน + เงินสด
          7 เงินโอน + Ai-Cash

        */
              // 1  เงินสด
                          if(pay_type_id==5){
                              $(".show_div_cash_pay").show();

                            }else
                        // 3  เครดิต + เงินสด
                            if(pay_type_id==7){
                              // เครดิต
                                // $(".show_div_credit").show();
                                // $("#credit_price").val('');
                                // $(".div_fee").show();
                                // $("#fee_amt").val('');
                                // $('#fee').attr('required', true);
                                // $("#sum_credit_price").val('');
                                // // เงินสด
                                // $(".show_div_cash_pay").show();
                                // $("#cash_pay").val('');

                            }else
                        // 4  เครดิต + เงินโอน
                            if(pay_type_id==8){
                                  // เครดิต
                                  // $(".show_div_credit").show();
                                  // $("#credit_price").val('');
                                  // $(".div_fee").show();
                                  // $("#fee_amt").val('');
                                  // $('#fee').val("").select2();
                                  // $('#fee').attr('required', true);
                                  // $("#sum_credit_price").val('');
                                  // // เงินโอน
                                  // $(".show_div_transfer_price").show();
                                  // $('input[name=account_bank_id]').prop('checked',false);
                                  // $('input[name=account_bank_id]').attr('required', true);
                                  // $(".div_account_bank_id").show();
                                  // $("#transfer_price").val('');
                                  // $(".transfer_money_datetime").attr('required', true);
                                  // $("#transfer_price").removeAttr('required');
                                  // $("#transfer_price").removeClass('input-aifill').addClass('input-aireadonly');

                                  // $(".show_div_cash_pay").hide();

                                        
                              }else 
                              // 6  เงินโอน + เงินสด
                              if(pay_type_id==10){

                                // เงินโอน
                                // $(".show_div_transfer_price").show();
                                // $('input[name=account_bank_id]').prop('checked',false);
                                // $('input[name=account_bank_id]').attr('required', true);
                                // $(".div_account_bank_id").show();
                                // $("#transfer_price").val('');
                                // $(".transfer_money_datetime").attr('required', true);
                                // $("#transfer_price").attr('required',true);
                                // $("#transfer_price").removeClass('input-aireadonly').addClass('input-aifill').addClass('CalPrice');

                                // $(".show_div_cash_pay").show();
                                // $('#fee').removeAttr('required');
                                // $("#cash_pay").val('');
                              
                            }else{
                                // $('#fee').removeAttr('required');
                                // $('input[name=account_bank_id]').removeAttr('required');
                                // $('.transfer_money_datetime').removeAttr('required');
                                // $(".show_div_cash_pay").hide();
                                // $(".show_div_transfer_price").hide();
                                // $(".div_account_bank_id").hide();
                            }
                         
                               $(".myloading").hide();

                            },
                          error: function(jqXHR, textStatus, errorThrown) { 
                              // console.log(JSON.stringify(jqXHR));
                              // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                              $(".myloading").hide();
                          }
                      });


      });

       $(document).on('change', 'input[name=charger_type]', function(event) {


                  // var frontstore_id_fk = $("#frontstore_id_fk").val();
                  // alert(frontstore_id_fk);

                    $("#credit_price").val('');
                    $("#fee_amt").val('');
                    $("#sum_credit_price").val('');  
                    $("#transfer_price").val('');
                    $("#cash_pay").val('');

                    setTimeout(function(){
                         $("#credit_price").focus();
                    });


                    //  $.ajax({
                    //    type:'POST',
                    //    // dataType:'JSON',
                    //    url: " {{ url('backend/ajaxClearAfterSelChargerType') }} ", 
                    //    data: { frontstore_id_fk:frontstore_id_fk },
                    //     success:function(data){
                    //            console.log(data); 
                    //            // $.each(data,function(key,value){
                    //            //    if(value.ai_cash==0){
                    //            //      alert('! กรุณา ทำการเติม Ai-Cash ก่อนเลือกชำระช่องทางนี้ ขอบคุณค่ะ');
                    //            //       $(".myloading").hide();
                    //            //    }
                    //            //  });

                    //           setTimeout(function(){
                    //              $("#credit_price").focus();
                    //           });

                    //           $(".myloading").hide();
                    //       },
                    //     error: function(jqXHR, textStatus, errorThrown) { 
                    //         $(".myloading").hide();
                    //     }
                    // });

              });

     
           $(document).on('change', '.CalAicashAmt', function(event) {


                  var id = "{{@$sRow->id}}";
                  // alert(frontstore_id_fk);

                    // $("#credit_price").val('');
                    // $("#fee_amt").val('');
                    // $("#sum_credit_price").val('');  
                    // $("#transfer_price").val('');
                    // $("#cash_pay").val('');

                     $.ajax({
                       type:'POST',
                       // dataType:'JSON',
                       url: " {{ url('backend/ajaxCalAicashAmt') }} ", 
                       data: { id:id },
                        success:function(data){
                               console.log(data); 
                                $.each(data,function(key,value){
                                 
                                });

                                $('#pay_type_id').val("").select2();
                                $(".show_div_cash_pay").hide();

                              // setTimeout(function(){
                              //    $("#credit_price").focus();
                              // });

                              $(".myloading").hide();
                          },
                        error: function(jqXHR, textStatus, errorThrown) { 
                            $(".myloading").hide();
                        }
                    });

              });



  });
</script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" />

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
        console.log(d);
        var t = d.substring(d.length - 5);
        console.log();
        var d = d.substring(0, 10);
        console.log();
        var d = d.split("/").reverse().join("-");
        console.log();
        $('#transfer_money_datetime').val(d+' '+t);
      });

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


      $(document).ready(function() {
        
              $(document).on('click', '.btnDelSlip', function(event) {
                    var id = $(this).data('id');
                    if (!confirm("ยืนยันการลบ ! / Confirm to delete ? ")){
                        return false;
                    }else{
                    
                        $.ajax({
                            type: "POST",
                            url: " {{ url('backend/ajaxDelFileSlipGiftVoucher') }} ", 
                             data:{ _token: '{{csrf_token()}}',id:id },
                            success: function(data){
                              console.log(data);
                              // location.reload();
                              $(".span_file_slip").hide();
                            }
                        });

                    }

                });



            $(document).on('change', '#fee', function(event) {
                    event.preventDefault();
                    $(".myloading").show();
                    $('#credit_price').attr('required', true);
                    setTimeout(function(){
                    $('#credit_price').focus();
                    $(".myloading").hide();
                    });
                });

              $(document).on('change', '.transfer_money_datetime', function(event) {
                    event.preventDefault();
                    $(".myloading").show();
                    $('#transfer_price').attr('required', true);
                    setTimeout(function(){
                        $('#transfer_price').focus();
                        $(".myloading").hide();
                    });
                });


            $('.btnUpSlip').on('click', function(e) {
                  $("#image01").trigger('click');
            });

      });


</script>

@endsection

