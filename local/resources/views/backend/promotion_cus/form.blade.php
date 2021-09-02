@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')
<div class="myloading"></div>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> สร้างรหัสคูปอง </h4>
        </div>
    </div>
</div>

  @if( empty(@$sRow) )
  <form id="frmGen" action="{{ route('backend.promotion_code.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
  @else
  <form id="frmGen" action="{{ route('backend.promotion_code.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
    <input name="promotion_code_id_fk" type="hidden" value="{{@$sRow->id}}">
  @endif
    {{ csrf_field() }}


      <div class="myBorder" >
        <div class="container">
          
          <div class="col-12">
            <div class="panel panel-default">
              <div class="panel-body">
     
                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label required_star_red ">เลือกโปรโมชั่น :</label>
                    <div class="col-md-6">
                      
                     
                      
                          <select name="promotion_id_fk" id="promotion_id_fk" class="form-control select2-templating " required >
                            <option value="">Select</option>
                              @if(@$sPromotions)
                                @foreach(@$sPromotions AS $r)
                                  <option value="{{$r->id}}" {{ (@$r->id==@$sRow->promotion_id_fk)?'selected':'' }} >{{$r->name_thai}}</option>
                                @endforeach
                              @endif
                          </select>


                    </div>

                  </div>

                <div class="form-group row">
                    <label for="coupon_terms" class="col-md-3 col-form-label required_star_red ">เงื่อนไขการได้รับคูปอง : </label>
                    <div class="col-md-6">
                        <input class="form-control" type="text" value="{{ @$sRow->coupon_terms }}" id="coupon_terms" name="coupon_terms" required>
                    </div>
                </div>

                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label required_star_red ">วันเริ่ม - วันสิ้นสุด</label>
                    <div class="col-md-6 d-flex  ">
                      <?php 
                         $sd = explode('-', @$sRow->pro_sdate);
                         $sd = @$sd[2].'/'.@$sd[1].'/'.@$sd[0];
                         $sd = !empty(@$sRow->pro_sdate)?$sd:'';
                         $ed = explode('-', @$sRow->pro_edate);
                         $ed = @$ed[2].'/'.@$ed[1].'/'.@$ed[0];
                         $ed = !empty(@$sRow->pro_edate)?$ed:'';
                      ?>
                         <input id="startDate"  autocomplete="off" placeholder="วันเริ่ม" value="{{ @$sd }}" required />
                         <input id="endDate"  autocomplete="off" placeholder="วันสิ้นสุด" value="{{ @$ed }}" required />
           
                      </div>

                      <div class="col-md-3">
                         <input id="pro_sdate" name="pro_sdate" type="hidden" value="{{ @$sRow->pro_sdate }}" />
                         <input id="pro_edate" name="pro_edate" type="hidden" value="{{ @$sRow->pro_edate }}" />

                         @IF(!empty(@$sRow->promotion_id_fk))
                         <input type='button' class="btn btn-success btnSaveDate " value='บันทึกแก้ไขวันที่' >
                         @ELSE
                         <input type='button' class="btn btn-success btnSaveDate " value='บันทึกแก้ไขวันที่' disabled="">
                         @ENDIF

                      </div>

                  </div>

              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="myBorder" style="background-color: #e6e6e6;" >
        <div class="container">
          
          <div class="col-12">
            <div class="panel panel-default">
              <div class="panel-body">
      
                  <div class="form-group row">
                    <div class="col-md-3"></div>
                    
                    <div class="col-md-3">
                    <label for="" class="col-form-label">นำเข้าไฟล์ Excel (.xlsx) :</label>
                    </div>
                    <div class="col-md-4 " style="" >
                    <label for="" class="col-form-label"><span class="dLexam" style="cursor: pointer;">[Download ตัวอย่างโครงสร้างไฟล์เพื่อใช้นำเข้า]</span></label>
                    </div>
                    
                  </div>
                  
                   <div class="form-group row">
                    
                    <div class="col-md-3"></div>
                    <div class="col-md-3">
                      <input type="file" accept=".xlsx" class="form-control" name="fileXLS" required>
                    </div>
                    <div class="col-md-4 " style="" >
                      <input type='submit' name="submit" class="btn btn-primary btnImXlsx " value='Import'>
                      &nbsp;
                      &nbsp;
                      &nbsp;
                       <input type='button' class="btn btn-danger btnClearImport " value='Clear data Import' >
                 
                    </div>
                    
                  </div>

                  @if(Session::has('message'))
                  <div class="form-group row ">
                    <label for="" class="col-md-2 col-form-label"></label>
                    <div class="col-md-6 ">
                      <p style="color:green;font-weight:bold;font-size: 16px;" >{{ Session::get('message') }}</p>
                    </div>
                  </div>
                  @endif

               
              </div>
            </div>
          </div>
        </div>
      </div>



      <div class="myBorder" >
        <div class="container">
          
          <div class="col-12">
            <div class="panel panel-default">
              <div class="panel-body">
     
                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">จำนวนรหัสที่จะ gen :</label>
                    <div class="col-md-3">
                      <input type="number" class="form-control" name="GenAmt" >
                    </div>
                    <div class="col-md-3">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">Random กี่หลัก :</label>
                    <div class="col-md-3">
                      <input type="number" class="form-control" name="amt_random" >
                    </div>
                    <div class="col-md-3">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">รหัส Prefix :</label>
                    <div class="col-md-3" >
                      <input type="text" class="form-control" id="prefix_coupon"  name="prefix_coupon" >
                    </div>
                    <div class="col-md-3" style="text-align: right;" >
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">รหัส Suffix :</label>
                    <div class="col-md-3" >
                      <input type="text" class="form-control" id="suffix_coupon"  name="suffix_coupon" >
                    </div>
                    <div class="col-md-3" style="" >
                      <!-- <input type='button' class="btn btn-primary btnPrefixCoupon " value='เพิ่มรหัส Prefix Coupon' > -->
                      <input type='button' class="btn btn-primary btnGenCode " value='GEN รหัส'>
                      &nbsp;
                      &nbsp;
                      &nbsp;
                       <input type='button' class="btn btn-danger btnClearGen " value='Clear data Gen' >

                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>


 </form>

 <div class="myBorder">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-8">

                  <input type="text" class="form-control float-left text-center w130 myLike" placeholder="ค้น : รหัสคูปอง" name="promotion_code">
                  <input type="hidden" class="form-control float-left text-center w130 myLike" name="promotion_code_id_fk" value="{{@$sRow->id}}">

                 @if( !empty(@$sRowProCus) )
                    &nbsp;
                    &nbsp;
                    &nbsp;
                     <input type='button' class="btn btn-info btnExportExls " value='Export Excel' >
               
                  @endif

                </div>
                <div class="col-4 text-right" >
                  <a class="btn btn-success btn-sm mt-1 font-size-16 btnApprove " href='' >
                    อนุมัติใช้งานได้ทั้งหมด
                  </a>
   

                </div>
              </div>
              
              <table id="data-table-coupon" class="table table-bordered dt-responsive" style="width: 100%;">
              </table>
              

                 <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/promotion_code") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div>
                </div>

            </div>

          </div>
          </div> <!-- end col -->
          </div> <!-- end row -->
          </div> <!-- end row -->



        <div class="modal fade" id="modalAddList" tabindex="-1" role="dialog" aria-labelledby="modalAddListTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg " role="document" style="max-width: 50% !important;">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalAddListTitle"><b><i class="bx bx-play"></i>เพิ่มสินค้าให้กับโปร</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>
              
              <div class="modal-body">
                
                <div class="card-body" >
                  <form action="{{ route('backend.promotion_code_product.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="promotion_code_id_fk" value="{{@$sRow->id}}">
                    {{ csrf_field() }}
                    <div class="form-group row">
                      <label for="" class="col-md-3 col-form-label"> <b>ค้นหาสินค้า :</b> </label>
                      <div class="col-md-8">
                        <select name="product_id_fk" id="product_id_fk" class="form-control select2-templating "  >
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
                          <textarea class="form-control" rows="4" disabled style="text-align: left !important;background: #f2f2f2;" ></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="" class="col-md-3 col-form-label"> <b>จำนวน :</b> </label>
                      <div class="col-md-2">
                        <input type="number" name="amt" id="amt" class="form-control" value="" required="">
                      </div>
                    </div>
                        <div class="form-group row">
                          <label for="example-text-input" class="col-md-3 col-form-label">หน่วย : * </label>
                          <div class="col-md-3">
                            <select name="product_unit_id_fk" class="form-control select2-templating " required >
                              <option value="">Select</option>
                                @if(@$sProductUnit)
                                  @foreach(@$sProductUnit AS $r)
                                    <option value="{{$r->id}}" {{ (@$r->id==@$sRow->product_unit_id_fk)?'selected':'' }} >{{$r->product_unit}}</option>
                                  @endforeach
                                @endif
                            </select>
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

<script>

          $('.myLike').on('change', function(e){
          var promotion_code_id_fk = "{{@$sRow->id}}"; //alert(promotion_code_id_fk);
          var oTable2;
          $(function() {
              oTable2 = $('#data-table-coupon').DataTable({
              "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                  processing: true,
                  serverSide: true,
                  scroller: true,
                  scrollCollapse: true,
                  scrollX: true,
                  destroy: true,
                  ordering: false,
                  scrollY: ''+($(window).height()-370)+'px',
                  iDisplayLength: 25,
                  ajax: {
                    url: '{{ route('backend.promotion_cus.datatable') }}',
                    data: function ( d ) {
                      d.Like={};
                      $('.myLike').each(function() {
                        if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                          d.Like[$(this).attr('name')] = $.trim($(this).val());
                        }
                      });
                      oData = d;
                    },
                    method: 'POST'
                  },
                  columns: [
                      {data: 'id', title :'ID', className: 'text-center w50'},
                      {data: 'promotion_code', title :'<center>รหัสคูปอง</center>', className: 'text-left'},
                      {data: 'user_name',   title :'<center>รหัสสมาชิก </center>', className: 'text-center',render: function(d) {
                        //  '4=import เข้ารอตรวจสอบหรือนำไปใช้,1=ใช้งานได้,2=ถูกใช้แล้ว,3=หมดอายุแล้ว',
                        if(d==0){
                            return '-';
                        }else{
                            return d;
                        }
                      }},            
                      // {data: 'pro_status', title :'<center>สถานะ </center>', className: 'text-center'},
                      {data: 'pro_status',   title :'<center>สถานะ</center>', className: 'text-center',render: function(d) {
                        //  '4=import เข้ารอตรวจสอบหรือนำไปใช้,1=ใช้งานได้,2=ถูกใช้แล้ว,3=หมดอายุแล้ว',
                        if(d==4){
                            return 'Import Excel';
                        }else if(d==5){
                            return 'Gen code';                            
                        }else if(d==1){
                            return 'ใช้งานได้';
                        }else if(d==2){
                            return '<span style="color:red;">ถูกใช้แล้ว</span>';
                        }else if(d==3){
                            return '<span style="color:red;">Expired/หมดอายุ</span>';
                        }else{
                            return d;
                        }
                      }},
              
                {data: 'used_user_name', title :'<center>รหัสคนนำไปใช้</center>', className: 'text-center'},
                {data: 'used_date', title :'<center>วันที่ใช้</center>', className: 'text-center'},
                  ],

                  fnRowCallback: function (nRow, aData, iDisplayIndex) {
                       var info = $(this).DataTable().page.info();
                       $("td:eq(0)", nRow).html(info.start + iDisplayIndex + 1);
                   },

              });

               oTable2.draw();
    
          });

               

          });

    var promotion_code_id_fk = "{{@$sRow->id}}"; //alert(promotion_code_id_fk);
    var oTable1;
    $(function() {
        oTable1 = $('#data-table-coupon').DataTable({
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
              url: '{{ route('backend.promotion_cus.datatable') }}',
              data: function ( d ) {
                   d.Where={};
                // // $('.Where').each(function() {
                //   // if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                    if(promotion_code_id_fk!=''){
                       d.Where['promotion_code_id_fk'] = promotion_code_id_fk ;
                    }else{
                       d.Where['promotion_code_id_fk'] = '999999999999999999999' ;
                    }
                  // }
                // });
                oData = d;
              },
              method: 'POST'
            },
            columns: [
                {data: 'id', title :'ID', className: 'text-center w50'},
                {data: 'promotion_code', title :'<center>รหัสคูปอง</center>', className: 'text-left'},
                {data: 'user_name',   title :'<center>รหัสสมาชิก </center>', className: 'text-center',render: function(d) {
                  //  '4=import เข้ารอตรวจสอบหรือนำไปใช้,1=ใช้งานได้,2=ถูกใช้แล้ว,3=หมดอายุแล้ว',
                  if(d==0){
                      return '-';
                  }else{
                      return d;
                  }
                }},            
                // {data: 'pro_status', title :'<center>สถานะ </center>', className: 'text-center'},
                {data: 'pro_status',   title :'<center>สถานะ</center>', className: 'text-center',render: function(d) {
                  //  '4=import เข้ารอตรวจสอบหรือนำไปใช้,1=ใช้งานได้,2=ถูกใช้แล้ว,3=หมดอายุแล้ว',
                        if(d==4){
                            return 'Import Excel';
                        }else if(d==5){
                            return 'Gen code';                            
                        }else if(d==1){
                            return 'ใช้งานได้';
                        }else if(d==2){
                            return '<span style="color:red;">ถูกใช้แล้ว</span>';
                        }else if(d==3){
                            return '<span style="color:red;">Expired/หมดอายุ</span>';
                        }else{
                            return d;
                        }
                }},
                {data: 'used_user_name', title :'<center>รหัสคนนำไปใช้</center>', className: 'text-center'},
                {data: 'used_date', title :'<center>วันที่ใช้</center>', className: 'text-center'},
            ],
             fnRowCallback: function (nRow, aData, iDisplayIndex) {
                 var info = $(this).DataTable().page.info();
                 $("td:eq(0)", nRow).html(info.start + iDisplayIndex + 1);
             },

        });

    });


$(document).ready(function() {

    $(".btnClearImport").click(function(event) {
        /* Act on the event */
        $(".myloading").show();

        var promotion_code_id_fk = "{{@$sRow->id}}"; //alert(promotion_code_id_fk);

      if (!confirm("Are you sure ? ")){
        $(".myloading").hide();
          return false;
      }else{

        $.ajax({

               type:'POST',
               url: " {{ url('backend/ajaxClearDataPromotionCode') }} ", 
               data:{ _token: '{{csrf_token()}}' ,promotion_code_id_fk:promotion_code_id_fk,param:'Import' },
                success:function(data){
                     console.log(data); 
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


    $(".btnClearGen").click(function(event) {
            /* Act on the event */
            $(".myloading").show();

            var promotion_code_id_fk = "{{@$sRow->id}}"; //alert(promotion_code_id_fk);

          if (!confirm("Are you sure ? ")){
            $(".myloading").hide();
              return false;
          }else{

            $.ajax({

                   type:'POST',
                   url: " {{ url('backend/ajaxClearDataPromotionCode') }} ", 
                   data:{ _token: '{{csrf_token()}}' ,promotion_code_id_fk:promotion_code_id_fk,param:'Gen' },
                    success:function(data){
                         console.log(data); 
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

   $(".btnImXlsx").click(function(event) {

          var v = $("#promotion_id_fk").val();
          if(v=='' || v==0){
            $("#promotion_id_fk").select2('open');
            return false;
          }

          var v = $("#coupon_terms").val();
          if(v=='' || v==0){
            $("#coupon_terms").focus();
            return false;
          }

          var v = $("input[name=pro_sdate]").val();
          if(v=='' || v==0){
            $("#startDate").focus();
            return false;
          }
          var v = $("input[name=pro_edate]").val();
          if(v=='' || v==0){
            $("#endDate").focus();
            return false;
          }          

          var v = $("input[name=fileXLS]").val();
          if(v!=''){
            $(".myloading").show();
            $('#frmGen').attr('action', 'backend/uploadPromotionCus');
            $('#frmGen').submit();
          }else{
            $("input[name=fileXLS]").trigger('click');
            return false;
          }

    });


      $(".btnGenCode").click(function(event) {

          var v = $("#promotion_id_fk").val();
          

          if(v=='' || v==0){
            // $("input[name=promotion_name]").focus();
            return false;
          }


          var coupon_terms = $("#coupon_terms").val();
          if(coupon_terms==''){
            $("#coupon_terms").focus();
            return false;
          }
          
          var pro_sdate = $("input[name=pro_sdate]").val();
          if(pro_sdate==''){
            $("#startDate").focus();
            return false;
          }
          var pro_edate = $("input[name=pro_edate]").val();
          if(pro_edate==''){
            $("#endDate").focus();
            return false;
          }          

          var GenAmt = $("input[name=GenAmt]").val();
          if(GenAmt==''){
            $("input[name=GenAmt]").focus();
            return false;
          }

          var amt_random = $("input[name=amt_random]").val();
          if(amt_random=='' || amt_random==0){
            $("input[name=amt_random]").focus();
            return false;
          }

        $(".myloading").show();

        var promotion_code_id_fk = "{{@$sRow->id}}"; 

        var frm = $("#frmGen").serialize();
        
        $.ajax({
           type:'POST',
           url: " {{ url('backend/ajaxGenPromotionCode') }} ", 
           // data:{ _token: '{{csrf_token()}}',amt_gen:v,promotion_code_id_fk:promotion_code_id_fk },
           data: frm ,
            success:function(data){
                 console.log(data); 
                 // return false;

                 if(data=='x'){
                  alert("! จำนวนหลักไม่พอ");
                  location.reload();
                  return false;
                 }else{
                   $(".myloading").hide();
                   location.replace('backend/promotion_cus/'+data+'/edit');
                 }
                
                 // location.reload();
                 // location.replace('backend/promotion_cus/'+data+'/edit');
                 // return redirect()->to(url("backend/promotion_cus/".$sRow->id."/edit"));
              },
            error: function(jqXHR, textStatus, errorThrown) { 
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                $(".myloading").hide();
            }
        });


    });


    $(".btnSaveDate").click(function(event) {

          var id = "{{@$sRow->id}}";
          var promotion_id_fk = $("#promotion_id_fk").val();
          // alert(promotion_id_fk);
          // return false;

          if(promotion_id_fk=='' || promotion_id_fk==0){
            alert("! กรุณา เลือกโปรโมชั่น");
            $("#promotion_id_fk").select2('open');
            return false;
          }

          var coupon_terms = $("#coupon_terms").val();
          if(coupon_terms==''){
            $("#coupon_terms").focus();
            return false;
          }

          var pro_sdate = $("input[name=pro_sdate]").val();
          // alert(pro_sdate);
          if(pro_sdate=='' || pro_sdate==0){
            $("#startDate").focus();
            return false;
          }
          var pro_edate = $("input[name=pro_edate]").val();
          // alert(pro_edate);
          if(pro_edate=='' || pro_edate==0){
            $("#endDate").focus();
            return false;
          }        

          // console.log(coupon_terms);

          // return false;

        $(".myloading").show();

        $.ajax({
           type:'POST',
           url: " {{ url('backend/ajaxGenPromotionSaveDate') }} ", 
           data:{ _token: '{{csrf_token()}}',
           id:id,
           promotion_id_fk:promotion_id_fk,
           coupon_terms:coupon_terms,
           pro_sdate:pro_sdate,
           pro_edate:pro_edate,
            },
            success:function(data){
                 // console.log(data); 
                 // return false;

                 $(".myloading").hide();
                 location.reload();
              },
            error: function(jqXHR, textStatus, errorThrown) { 
                $(".myloading").hide();
            }
        });


    });





    $(".btnExportExls").click(function(event) {
        /* Act on the event */
        $(".myloading").show();

        var promotion_code_id_fk = "{{@$sRow->id}}";

        $.ajax({

               type:'POST',
               url: " {{ url('backend/excelExportPromotionCus') }} ", 
               data:{ _token: '{{csrf_token()}}',promotion_code_id_fk:promotion_code_id_fk },
                success:function(data){
                     console.log(data); 
                     // location.reload();

                     setTimeout(function(){
                        var url='local/public/excel_files/promotion_cus.xlsx';
                        window.open(url, 'Download');  
                        $(".myloading").hide();
                    },3000);

                  },
                error: function(jqXHR, textStatus, errorThrown) { 
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    $(".myloading").hide();
                }
            });
    });


      $(".btnPrefixCoupon").click(function(event) {
          var v = $("input[name=prefix_coupon]").val();
          if(v=='' || v==0){
            $("input[name=prefix_coupon]").focus();
            return false;
          }

        $(".myloading").show();
        
        $.ajax({
           type:'POST',
           url: " {{ url('backend/ajaxGenPromotionCodePrefixCoupon') }} ", 
           data:{ _token: '{{csrf_token()}}' , prefix_coupon:v },
            success:function(data){
                 console.log(data); 
                 location.reload();
              },
            error: function(jqXHR, textStatus, errorThrown) { 
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                $(".myloading").hide();
            }
        });


    });



    $(document).on('click', '.btnAddList', function(event) {
        event.preventDefault();
        $('#modalAddList').modal('show');
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
            var s_date = $('#startDate').val();
            var startDated = s_date.split("/").reverse().join("-");
            var e_date = $('#endDate').val();
            var endDate = e_date.split("/").reverse().join("-");
            $('#pro_sdate').val(startDated);
            $('#pro_edate').val(endDate);

         });

         $('#endDate').change(function(event) {

            var e_date = $(this).val();
            var endDate = e_date.split("/").reverse().join("-");
            $('#pro_edate').val(endDate);

         });




      $(document).ready(function() {


                    $(document).on('change', '#product_id_fk', function(event) {

                            event.preventDefault();
                            var product_id_fk = $(this).val();
                            // return false;
                            $(".product_id_fk_this").val(product_id_fk);

                            $.ajax({
                             type:'POST',
                             url: " {{ url('backend/ajaxGetProductPromotionCus') }} ", 
                             data:{ _token: '{{csrf_token()}}',product_id_fk:product_id_fk },
                              success:function(data){

                                    $('#show_product').html(data);
                                    $('#amt').val('2');
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



      });




</script>



<script>

            $(function() {

              var promotion_code_id_fk = "{{@$sRow->id?@$sRow->id:0}}";

                oTable = $('#data-table-product').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    scroller: true,
                    scrollCollapse: true,
                    scrollX: true,
                    ordering: false,
                    scrollY: ''+($(window).height()-370)+'px',
                    iDisplayLength: 5,
                    ajax: {
                            url: '{{ route('backend.promotion_code_product.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['promotion_code_id_fk'] = promotion_code_id_fk ;
                                    oData = d;
                                  },
                              method: 'POST',
                            },
                 
                    columns: [
                        {data: 'id', title :'ID', className: 'text-center w50'},
                        // {data: 'product_img',   title :'<center>IMAGE</center>', className: 'text-center',render: function(d) {
                        //    return '<img src="'+d+'" width="150">';
                        // }},
                        {data: 'product_name', title :'<center>สินค้า</center>', className: 'text-center'},
                        {data: 'amt', title :'<center>จำนวน</center>', className: 'text-center'},
                        {data: 'product_unit', title :'<center>หน่วย </center>', className: 'text-center'},
                        {data: 'id', title :'Tools', className: 'text-center w60'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                      $('td:last-child', nRow).html(''
                        // + '<a href="{{ route('backend.promotion_code_product.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.promotion_code_product.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
        
            });

       $(document).on('click', '.cDelete', function(event) {
              setTimeout(function(){
                location.reload();
              }, 1500);  
        });

        $(document).on('change', '#promotion_code_id_fk', function(event) {
            event.preventDefault();
            var id = $(this).val();
            localStorage.setItem('promotion_code_id_fk', id);
        });

        if(localStorage.getItem('promotion_code_id_fk')){
            $('#promotion_code_id_fk').val(localStorage.getItem('promotion_code_id_fk')).select2();
        }

        $(document).on('click', '.btnApprove', function(event) {
             event.preventDefault();
             $(".myloading").show();
             var promotion_code_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
             // console.log(promotion_code_id_fk);

            if (!confirm("ยืนยัน Approve ? ")){
                $(".myloading").hide();
                return false;
            }else{
                    $.ajax({
                       type:'POST',
                       url: " {{ url('backend/ajaxApproveCouponCode') }} ", 
                       data:{ _token: '{{csrf_token()}}',promotion_code_id_fk:promotion_code_id_fk },
                        success:function(data){

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



      $(".dLexam").click(function(event) {
          $(".myloading").show();
          setTimeout(function(){
              var url='local/public/uploads/exam.xlsx';
              window.open(url, 'Download');  
              $(".myloading").hide();
          },500);
      });

</script>

@endsection

