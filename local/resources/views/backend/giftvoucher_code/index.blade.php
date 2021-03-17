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
            <h4 class="mb-0 font-size-18"> รายการ Gift Voucher (ที่เพิ่ม) </h4>
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


      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-10 d-flex " >

                   <input type="text" class="form-control text-center w250 myLike" placeholder="ค้น : ชื่อ Gift Voucher" name="descriptions"> 
                   <input type="text" class="form-control text-center w180 myLike" placeholder="ค้น : วันเริ่มต้น " name="pro_sdate" style="margin-left: 1%;" > 
                   <input type="text" class="form-control text-center w180 myLike" placeholder="ค้น : วันสิ้นสุด " name="pro_edate" style="margin-left: 1%;" > 
                </div>
                <div class="col-2 text-right" >
                  <a class="btn btn-info btn-sm mt-1 font-size-16 " href="{{ route('backend.giftvoucher_code.create') }}">
                    <i class="bx bx-plus font-size-20 align-middle mr-1"></i>เพิ่ม
                  </a>
                </div>
              </div>
              
              <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
              </table>
              
            </div>
          </div>
          </div> <!-- end col -->
          </div> <!-- end row -->

@endsection

@section('script')

<script>
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
var oTable;
$(function() {
    oTable = $('#data-table').DataTable({
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
          url: '{{ route('backend.giftvoucher_code.datatable') }}',
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
            {data: 'descriptions', title :'<center>ชื่อ Gift Voucher</center>', className: 'text-left'},
            {data: 'amount', title :'<center>จำนวน</center>', className: 'text-center'},
            {data: 'pro_sdate', title :'<center>วันเริ่มต้น</center>', className: 'text-center'},
            {data: 'pro_edate', title :'<center>วันสิ้นสุด</center>', className: 'text-center'},
            {data: 'status', title :'<center>สถานะ</center>', className: 'text-center'},
            {data: 'id', title :'Tools', className: 'text-center w100'},         
        ],
        rowCallback: function(nRow, aData, dataIndex){

          if(aData['status']==1){
            $("td:eq(5)", nRow).html('<span style="color:blue;">เปิดใช้งานปกติ</span>');
          }else{
            $("td:eq(5)", nRow).html('<span style="color:red;">ยกเลิกการใช้งาน</span>');

            for (var i = 0; i < 5; i++) {
              $('td:eq( '+i+')', nRow).html(aData[i]).css({'color':'#d9d9d9','text-decoration':'line-through','font-style':'italic'});
            }

          }

          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

          $('td:last-child', nRow).html(''
            + '<a href="{{ route('backend.giftvoucher_code.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
            + '<a href="javascript: void(0);" data-url="{{ route('backend.giftvoucher_code.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
          ).addClass('input');

        }

        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});


$(document).ready(function() {


    $(document).on('click', '.cDelete', function(event) {
      event.preventDefault();
 
          setTimeout(function(){
            location.reload();
          }, 1500);  

    });


    $(".btnClearData").click(function(event) {
        /* Act on the event */
        $(".myloading").show();

        $.ajax({

               type:'POST',
               url: " {{ url('backend/ajaxClearDataPromotionCode') }} ", 
               data:{ _token: '{{csrf_token()}}' },
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


     $(".btnImXlsx").click(function(event) {
            var v = $("input[name=fileXLS]").val();
            if(v!=''){
              $(".myloading").show();
            }

      });

      $(".btnGenCode").click(function(event) {
          var v = $("input[name=GenAmt]").val();
          if(v=='' || v==0){
            $("input[name=GenAmt]").focus();
            return false;
          }

        $(".myloading").show();
        
        $.ajax({
           type:'POST',
           url: " {{ url('backend/ajaxGenPromotionCode') }} ", 
           data:{ _token: '{{csrf_token()}}' , amt_gen:v },
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



    $(".btnExportElsx").click(function(event) {
        /* Act on the event */
        $(".myloading").show();
        $.ajax({

               type:'POST',
               url: " {{ url('backend/excelExportPromotionCus') }} ", 
               data:{ _token: '{{csrf_token()}}' },
                success:function(data){
                     console.log(data); 
                     // location.reload();

                     setTimeout(function(){
                        var url='local/public/excel_files/promotion_code.xlsx';
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




});


</script>


@endsection

