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
            <h4 class="mb-0 font-size-20"> สแกน QR-CODE สินค้ารายชิ้น </h4>

                      <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/pay_product_receipt") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                      </a>

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
        $role_group_id = '%';
        $can_packing_list = '1';
        $can_payproduct = '1';
      }else{
        $role_group_id = \Auth::user()->role_group_id_fk;
        // echo $role_group_id;
        // echo $menu_id;     
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        $sC = @$menu_permit->c==1?'':'display:none;';
        $sU = @$menu_permit->u==1?'':'display:none;';
        $sD = @$menu_permit->d==1?'':'display:none;';
        $can_packing_list = @$menu_permit->can_packing_list==1?'1':'0';
        $can_payproduct = @$menu_permit->can_payproduct==1?'1':'0';
      }
      // echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;     
      // echo  @$menu_permit->can_packing_list;     
      // echo  @$menu_permit->can_payproduct;     
      // echo $can_packing_list."xxxxxxxxxxxxxxxxxxxxxxxxxxx";     
   ?>

<div class="row">
  <div class="col-8">
    <div class="card">
      <div class="card-body">

        <div class="myBorder" style="background-color: #f2f2f2;">


            <form id="frm-example" action="{{ route('backend.pay_product_receipt.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">

              <input type="hidden" name="save_to_qrscan" value="1" >
              <input type="hidden" name="invoice_code" value="{{@$id}}" >

              {{ csrf_field() }}

              <?php  //echo "<pre>"; print_r($warehouse_qrcode); echo "</pre>"; ?>
              <?php  //echo($warehouse_qrcode[3]->pick_warehouse_tmp_id_fk); ?>
              <?php  //echo($warehouse_qrcode[3]->product_id_fk); ?>
              <?php  //echo($warehouse_qrcode[3]->qr_code);  ?>
              <?php  
                  if(date("Y-m-d",strtotime(@$warehouse_qrcode[0]->updated_at))==date("Y-m-d")){
                    // echo "Y";
                    $dis_Del = "";
                  }else{
                    // echo "N";
                    $dis_Del = "display:none;";
                  }  
              ?>

              @IF($sRow)


              @php($j = 0)

              @foreach(@$sRow AS $k => $r)

                <div class="form-group row ">
                  <div class="col-md-12" style="font-size: 16px;color: black;font-weight: bold;">
                   <i class="bx bx-play"></i> สินค้า : {{$r->product_code." : ".$r->product_name}}  > จำนวน {{@$r->amt}} รายการ
                  </div>
                </div>

                      <?php 

                      $amt = @$r->amt;
                      
                      for ($i=0; $i < $amt ; $i++) { 
                        
                        @$qr_code = !empty($warehouse_qrcode[$j]->qr_code)?$warehouse_qrcode[$j]->qr_code:'';


                        ?>
                            <div class="form-group row " >
                              <div class="col-md-10 d-flex " >
                                <label class="col-5" style="margin: auto;text-align:right;" > <?=$amt>1?"(".($i+1).")":""?>  </label>
                                <div class="col-md-5" style="margin: auto;text-align:left;">

                                  <input type="hidden" name="warehouse_qrcode_id[]" value="{{@$warehouse_qrcode[$j]->id}}" >
                                  <input type="hidden" name="pick_warehouse_tmp_id_fk[]" value="{{@$r->id}}" >
                                  <input type="hidden" name="product_id_fk[]" value="{{@$r->product_id_fk}}" >

                                  <input type="text" class="form-control" name="txtScan[]" style="font-size: 16px !important;color: blue;"  value="{{@$qr_code}}">

                                  <input type="hidden" name="qr_code[]" value="{{@$qr_code}}" >

                                </div>
                                <div class="col-md-2" style="margin: auto;text-align:left;">
                                  <i class="far fa-window-close font-size-18 btnDelete " warehouse_qrcode_id="{{@$warehouse_qrcode[$j]->id}}" style="color: red;cursor: pointer;<?=$dis_Del?>" ></i>
                                </div>

                              </div>
                            </div>

                        <?php 
                        $j++;
                      }

                      ?>


              @ENDFOREACH 
              @ENDIF 

                      <div class="form-group row ">
                        <div class="col-md-10 d-flex  ">
                          <label class="col-5" ></label>
                          <div class="col-md-5" >
                            <button type="submit" class="btn btn-primary btn-sm waves-effect btnScan " style="font-size: 16px !important;">
                               <i class="bx bx-barcode align-middle font-size-22 "></i> SCAN
                            </button>
                        </div>
                        <div class="col-md-5" style="padding-top: 14px;">Last Update : {{@$warehouse_qrcode[0]->updated_at}} 
                        </div>
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
 
  <script> 

          $(document).ready(function() {

                $(document).on('click', '.btnDelete', function(event) {

                        if (!confirm("ยืนยัน ! เพื่อลบ ? ")){
                              return false;
                        }else{

                          $(".myloading").show();

                          var warehouse_qrcode_id = $(this).attr('warehouse_qrcode_id');
                          // alert(warehouse_qrcode_id);
                           $.ajax({
                             type:'POST',
                             url: " {{ url('backend/ajaxDeleteQrcodeProduct') }} ", 
                             data:{ _token: '{{csrf_token()}}',id:warehouse_qrcode_id },
                              success:function(data){
                                   // console.log(data); 
                                   location.reload();
                            
                                },
                              error: function(jqXHR, textStatus, errorThrown) { 
                                  console.log(JSON.stringify(jqXHR));
                                  console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                  $(".myloading").hide();
                              }
                          });

                        }

                }); 

          });

    </script> 

@endsection


