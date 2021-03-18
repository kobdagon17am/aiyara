@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style type="text/css">
  .border-left-0 {height: 34px;}
</style>
@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> เพิ่ม Gift Voucher </h4>
        </div>
    </div>
</div>
<!-- end page title -->

  <?php 
      $sPermission = \Auth::user()->permission ;
      $menu_id = @$_REQUEST['menu_id'];
      if($sPermission==1){
        $sC = '';
        $sU = '';
        $sD = '';
      }else{
        $role_group_id = \Auth::user()->role_group_id_fk;
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        $sC = @$menu_permit->c==1?'':'display:none;';
        $sU = @$menu_permit->u==1?'':'display:none;';
        $sD = @$menu_permit->d==1?'':'display:none;';
      }
   ?>

<?php//echo  @$sRow ?>
  @if( empty(@$sRow) )
  <form id="frmGen" action="{{ route('backend.giftvoucher_code.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
  @else
  <form id="frmGen" action="{{ route('backend.giftvoucher_code.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
    <input name="giftvoucher_code_id_fk" type="hidden" value="{{@$sRow->id}}">
  @endif
    {{ csrf_field() }}

<!-- 
 <form  method="POST" action="backend/uploadPromotionCus" enctype="multipart/form-data" autocomplete="off">
    {{ csrf_field() }} -->

      <div class="myBorder" >
        <div class="container">
          
          <div class="col-12">
            <div class="panel panel-default">
              <div class="panel-body">
     
                  <div class="form-group row">
                    <label for="descriptions" class="col-md-3 col-form-label">Descriptions :</label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" id="descriptions" name="descriptions" value="{{@$sRow->descriptions}}" required >
                    </div>
                    <div class="col-md-3">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="receipt" class="col-md-3 col-form-label">วันเริ่ม - วันสิ้นสุด</label>
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
@if( !empty(@$sRow) )
                         <input type='button' class="btn btn-success btnSaveDate " value='บันทึกแก้ไขวันที่'>
@endif
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
                    
                    <label for="receipt" class="col-md-3 col-form-label">นำเข้าไฟล์ Excel (.xlsx) :</label>
                    <div class="col-md-3">
                      <input type="file" accept=".xlsx" class="form-control" name="fileXLS" required>
                    </div>
                    <div class="col-md-4" >
                      <input type='submit' name="submit" class="btn btn-primary btnImXlsx " value='Import'>
                      &nbsp;
                      &nbsp;
                      &nbsp;
                      <input type='button' class="btn btn-outline-info waves-effect waves-light btnDLTemp " value='Download Template'>
                    </div>
                    
                  </div>
                  
                  @if(Session::has('message'))
                  <div class="form-group row ">
                    <label for="receipt" class="col-md-2 col-form-label"></label>
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
                      <label for="example-text-input" class="col-md-3 col-form-label"> ID สมาชิก : </label>
                      <div class="col-md-3">
                        <select id="customer_code" name="customer_code" class="form-control select2-templating " >
                          <option value="">Select</option>
                            @if(@$Customer)
                              @foreach(@$Customer AS $r)
                              <option value="{{$r->user_name}}"  >
                                {{$r->user_name}} : {{$r->first_name}}{{$r->last_name}}
                              </option>
                              @endforeach
                            @endif
                        </select>
                      </div>
                    </div>

                  <div class="form-group row">
                    <label for="giftvoucher_value" class="col-md-3 col-form-label">Value :</label>
                    <div class="col-md-3">
                      <input type="number" class="form-control" id="giftvoucher_value" name="giftvoucher_value" >
                    </div>
                    <div class="col-md-3" >
                      <input type='button' class="btn btn-primary btnSaveByCase " value='SAVE รายคน'>
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
                <div class="col-6">

                  <input type="text" class="form-control float-left text-center w130 myLike" placeholder="ค้น : รหัสลูกค้า " name="giftvoucher_code">
                  <input type="hidden" class="form-control float-left text-center w130 myLike" name="giftvoucher_code_id_fk" value="{{@$sRow->id}}">


                </div>
 
                <div class="col-6 " style="text-align: right;" >
                    <input type='button' class="btn btn-danger font-size-16 btnClearImport " value='Clear Data [รออนุมัติ]' >
                    &nbsp;
                    <input type='button' class="btn btn-success font-size-16 btnApprove " value='อนุมัติใช้งานได้ทั้งหมด' >

                    @if( !empty(@$sRowGiftvoucherCus) )
                    &nbsp;
                    <input type='button' class="btn btn-success font-size-16 btnExportExls " value='Export Excel' >
                    <!--     <input type='button' class="btn btn-success btnExportChart " value='Export Chart' > -->
                    @endif

                </div>
              </div>
              
              <table id="data-table-coupon" class="table table-bordered dt-responsive" style="width: 100%;">
              </table>
              

                 <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/giftvoucher_code") }}">
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
                  <form action="{{ route('backend.giftvoucher_code.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="giftvoucher_code_id_fk" value="{{@$sRow->id}}">
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

    var sU = "{{@$sU}}"; //alert(sU);
    var sD = "{{@$sD}}"; //alert(sD);
    var giftvoucher_code_id_fk = "{{@$sRow->id}}"; //alert(giftvoucher_code_id_fk);
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
              url: '{{ route('backend.giftvoucher_cus.datatable') }}',
              data: function ( d ) {
                   d.Where={};
                // // $('.Where').each(function() {
                //   // if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
                    if(giftvoucher_code_id_fk!=''){
                       d.Where['giftvoucher_code_id_fk'] = giftvoucher_code_id_fk ;
                    }else{
                       d.Where['giftvoucher_code_id_fk'] = '999999999999999999999' ;
                    }
                  // }
                // });
                oData = d;
              },
              method: 'POST'
            },
            columns: [
                {data: 'id', title :'ID', className: 'text-center w50'},
                {data: 'customer_code',   title :'<center>รหัสสมาชิก (ลูกค้า) </center>', className: 'text-center',render: function(d) {
                  //  '4=import เข้ารอตรวจสอบหรือนำไปใช้,1=ใช้งานได้,2=ถูกใช้แล้ว,3=หมดอายุแล้ว',
                  if(d==0){
                      return '-';
                  }else{
                      return d;
                  }
                }},  
                {data: 'giftvoucher_value', title :'<center>Values</center>', className: 'text-left'},
                {data: 'pro_status',   title :'<center>สถานะ</center>', className: 'text-center',render: function(d) {
                        if(d==4){
                            return 'รออนุมัติ';
                        }else if(d==1){
                            return 'ใช้งานได้';
                        }else if(d==2){
                            return '<span style="color:green;font-weight:bold;">ถูกใช้แล้ว</span>';
                        }else if(d==3){
                            return 'หมดอายุแล้ว';
                        }else{
                            return d;
                        }
                }},
                {data: 'id', title :'ID', className: 'text-center w50'},
            ],
             fnRowCallback: function (nRow, aData, iDisplayIndex) {
                 var info = $(this).DataTable().page.info();
                 $("td:eq(0)", nRow).html(info.start + iDisplayIndex + 1);

                  if(sU!=''&&sD!=''){
                      $('td:last-child', nRow).html('-');
                  }else{ 

                    if(aData['pro_status']!=2){

                      $('td:last-child', nRow).html(''
                        + '<a href="javascript: void(0);" data-id="'+aData['id']+'"  class="btn btn-sm btn-danger cDeleteByCase " style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                        
                      ).addClass('input');

                    }else{
                      $('td:last-child', nRow).html('');
                    }

                }


             },

        });

    });


$(document).ready(function() {

    $(".btnClearImport").click(function(event) {
        /* Act on the event */
        $(".myloading").show();

        var giftvoucher_code_id_fk = "{{@$sRow->id}}"; //alert(giftvoucher_code_id_fk);

      if (!confirm("Are you sure ? ")){
        $(".myloading").hide();
          return false;
      }else{

        $.ajax({

               type:'POST',
               url: " {{ url('backend/ajaxClearDataGiftvoucherCode') }} ", 
               data:{ _token: '{{csrf_token()}}' ,giftvoucher_code_id_fk:giftvoucher_code_id_fk,param:'Import' },
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

            var giftvoucher_code_id_fk = "{{@$sRow->id}}"; //alert(giftvoucher_code_id_fk);

          if (!confirm("Are you sure ? ")){
            $(".myloading").hide();
              return false;
          }else{

            $.ajax({

                   type:'POST',
                   url: " {{ url('backend/ajaxClearDataPromotionCode') }} ", 
                   data:{ _token: '{{csrf_token()}}' ,giftvoucher_code_id_fk:giftvoucher_code_id_fk,param:'Gen' },
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

          $("#descriptions").valid();
          var descriptions = $("#descriptions").val();
          if(!descriptions){
            $("#descriptions").focus();
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

          var v = $("input[name=fileXLS]").val();
          if(v!=''){
            $(".myloading").show();
            // $('#frmGen').attr('action', 'backend/uploadPromotionCus');
            $('#frmGen').attr('action', 'backend/uploadGiftVoucherCus');
            $('#frmGen').submit();
          }else{
            $("input[name=fileXLS]").trigger('click');
            return false;
          }

    });


      $(".btnSaveByCase").click(function(event) {
          // var form = $( "#frmGen" );
          // form.validate();
          var giftvoucher_code_id_fk = "{{@$sRow->id}}"; 
          // alert(giftvoucher_code_id_fk);
          // return false;

          $("#descriptions").valid();
          var descriptions = $("#descriptions").val();
          if(!descriptions){
            $("#descriptions").focus();
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

          var customer_code = $("#customer_code").val();
            if(customer_code==''){
            $("#customer_code").select2('open');
            return false;
          } 

          var giftvoucher_value = $("#giftvoucher_value").val();
            if(giftvoucher_value==''){
            $("#giftvoucher_value").focus();
            return false;
          } 

          $(".myloading").show();

          var frm = $("#frmGen").serialize()+"&giftvoucher_code_id_fk="+giftvoucher_code_id_fk;
        
          $.ajax({
             type:'POST',
             url: " {{ url('backend/ajaxSaveGiftvoucherCode') }} ", 
             data: frm ,
              success:function(data){
                   console.log(data); 
                   // return false;
                    setTimeout(function(){
                        location.replace("{{ url('backend/giftvoucher_code') }}"+"/"+data+"/edit");
                    }, 1000);

                   $(".myloading").hide();
                   // location.reload();
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

          var descriptions = $("#descriptions").val();

          if(descriptions=='' || descriptions==0){
            alert("! กรุณา กรอกข้อมูล Descriptions");
            $("#descriptions").focus();
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

        $(".myloading").show();

        $.ajax({
           type:'POST',
           url: " {{ url('backend/ajaxGiftVoucherSaveDate') }} ", 
           data:{ _token: '{{csrf_token()}}',
           id:id,
           pro_sdate:pro_sdate,
           pro_edate:pro_edate,
            },
            success:function(data){
                 console.log(data); 
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

        var giftvoucher_code_id_fk = "{{@$sRow->id}}"; 


        $(".myloading").show();
        $.ajax({

               type:'POST',
               url: " {{ url('backend/excelExportGiftvoucherCus') }} ", 
               data:{ _token: '{{csrf_token()}}',giftvoucher_code_id_fk:giftvoucher_code_id_fk },
                success:function(data){
                     console.log(data); 
                     // location.reload();

                     setTimeout(function(){
                        var url='local/public/excel_files/giftvoucher_cus.xlsx';
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




    $(".btnDLTemp").click(function(event) {

        $(".myloading").show();
        setTimeout(function(){
            var url='local/public/excel_files/giftvoucher_template.xlsx';
            window.open(url, 'Download');  
            $(".myloading").hide();
        },1000);

    });



    // $(".btnExportChart").click(function(event) {
    //     /* Act on the event */


    //     $(".myloading").show();
    //     $.ajax({

    //            type:'POST',
    //            url: " {{ url('backend/excelExportChart') }} ", 
    //            data:{ _token: '{{csrf_token()}}' },
    //             success:function(data){
    //                  console.log(data); 
    //                  // location.reload();

    //                  setTimeout(function(){
    //                     var url='local/public/excel_files/export_chart.xlsx';
    //                     window.open(url, 'Download');  
    //                     $(".myloading").hide();
    //                 },3000);

    //               },
    //             error: function(jqXHR, textStatus, errorThrown) { 
    //                 console.log(JSON.stringify(jqXHR));
    //                 console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
    //                 $(".myloading").hide();
    //             }
    //         });
    // });


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

              var giftvoucher_code_id_fk = "{{@$sRow->id?@$sRow->id:0}}";

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
                            url: '{{ route('backend.giftvoucher_code.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['giftvoucher_code_id_fk'] = giftvoucher_code_id_fk ;
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
                        // + '<a href="{{ route('backend.giftvoucher_code.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.giftvoucher_code.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
        
            });

       $(document).on('click', '.cDelete', function(event) {
              setTimeout(function(){
                location.reload();
              }, 1500);  
        });

        $(document).on('change', '#giftvoucher_code_id_fk', function(event) {
            event.preventDefault();
            var id = $(this).val();
            localStorage.setItem('giftvoucher_code_id_fk', id);
        });

        if(localStorage.getItem('giftvoucher_code_id_fk')){
            $('#giftvoucher_code_id_fk').val(localStorage.getItem('giftvoucher_code_id_fk')).select2();
        }

        $(document).on('click', '.btnApprove', function(event) {
             event.preventDefault();
             $(".myloading").show();

            if (!confirm("ยืนยัน Approve ? ")){
                $(".myloading").hide();
                return false;
            }else{

               var giftvoucher_code_id_fk = "{{@$sRow->id?@$sRow->id:0}}";
               // console.log(giftvoucher_code_id_fk);
                $.ajax({
                   type:'POST',
                   url: " {{ url('backend/ajaxApproveGiftvoucherCode') }} ", 
                   data:{ _token: '{{csrf_token()}}',giftvoucher_code_id_fk:giftvoucher_code_id_fk },
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


        $(document).on('click', '.cDeleteByCase', function(event) {
             event.preventDefault();
             $(".myloading").show();
             var id = $(this).data('id');


          if (!confirm("ยืนยัน ลบ ? ")){
                $(".myloading").hide();
                return false;
            }else{

              $.ajax({
                 type:'POST',
                 url: " {{ url('backend/ajaxClearDataGiftvoucherCode') }} ", 
                 data:{ _token: '{{csrf_token()}}',id:id,param:'ByCase' },
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



</script>


@endsection

