@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> คูปอง </h4>

        </div>
    </div>
</div>
<!-- end page title -->

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="startDate" class="col-form-label"> ชื่อคูปอง : </label>
                    <input type="text" class="form-control" id="coupon_name">
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="pstatus" class="col-form-label"> สถานะ :  </label>
                    <select id="pstatus" name="pstatus" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                        <option value="1">ใช้งานได้</option>
                        <option value="2">ปิดการใช้งาน</option>
                        <option value="-1">Expired/หมดอายุ</option>
                    </select>
                  </div>
                </div>

                <div class="col-6">
                  <div class="form-group">
                    <label for="startDate" class="col-form-label"> วันเริ่มต้นโปร - วันสิ้นสุดโปร : </label>
                     <div class="d-flex">
                      <input id="startDate"  autocomplete="off" placeholder="Begin Date"  style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black" />
                      <input id="endDate"  autocomplete="off" placeholder="End Date"  style="border: 1px solid grey;font-weight: bold;color: black" />
                    </div>
                  </div>
                </div>

                <div class="col-6 align-self-end">
                  <div class="form-group">
                    <button class="btn btn-info btn-sm btnSearch01" style="font-size: 14px !important;margin-left: 0.8%;" >
                      <i class="bx bx-search align-middle "></i> SEARCH
                    </button>
                    <button class="btn btn-dark btn-sm" style="font-size: 14px !important;margin-left: 0.8%;" id="clearFilter">
                      CLEAR
                    </button>
                  </div>
                </div>

                <div class="col-8">
                  {{-- <input type="text" class="form-control float-left text-center w130 myLike" placeholder="ค้น : ชื่อคูปอง" name="promotion_name"> --}}
                </div>
                <div class="col-4 text-right" >
                  <a class="btn btn-info btn-sm mt-1 font-size-16 " href="{{ route('backend.promotion_cus.create') }}">
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
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script>
var oTable;
$(function() {
    oTable = $('#data-table').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: true,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.promotion_code.datatable') }}',
          data: function ( d ) {
            d.couponName = $('#coupon_name').val();
            d.startDate = $('#startDate').val();
            d.endDate = $('#endDate').val();
            d.pstatus = $('#pstatus').val();
            // d.Where={};
            // $('.myWhere').each(function() {
            //   if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
            //     d.Where[$(this).attr('name')] = $.trim($(this).val());
            //   }
            // });
            // d.Like={};
            // $('.myLike').each(function() {
            //   if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
            //     d.Like[$(this).attr('name')] = $.trim($(this).val());
            //   }
            // });
            // d.Custom={};
            // $('.myCustom').each(function() {
            //   if( $.trim($(this).val()) && $.trim($(this).val()) != '0' ){
            //     d.Custom[$(this).attr('name')] = $.trim($(this).val());
            //   }
            // })
            // oData = d;
          },
          method: 'POST'
        },
        columns: [
            {data: 'id', name: 'db_promotion_code.id', title :'ID', className: 'text-center ', orderable: true},
            {data: 'promotion_name', name: 'db_promotion_code.promotion_id_fk', title :'<center>ชื่อคูปอง</center>', className: 'text-left'},
            {data: 'pro_sdate', name: 'db_promotion_code.pro_sdate', title :'<center>วันเริ่มต้นโปร</center>', className: 'text-center'},
            {data: 'pro_edate', name: 'db_promotion_code.pro_edate', title :'<center>วันสิ้นสุดโปร</center>', className: 'text-center'},
            // `status` int(1) DEFAULT '1' COMMENT 'การใช้งาน/การแสดงผล 1=แสดงผล,0=ปิดการแสดง',
            {data: 'status', name: 'db_promotion_code.status', title :'<center>สถานะ</center>', className: 'text-center'},
            // {data: 'status',   title :'<center>Status</center>', className: 'text-center',render: function(d) {
            //    return d==1?'<span style="color:blue">เปิดใช้งาน</span>':'<span style="color:red">ปิด</span>';
            // }},
            {data: 'id', title :'Tools', className: 'text-center ', orderable: false},
        ],
        order: [
          [ 0, 'asc']
        ],
        rowCallback: function(nRow, aData, dataIndex){

              var sPermission = "<?=\Auth::user()->permission?>";
              var sU = sessionStorage.getItem("sU");
              var sD = sessionStorage.getItem("sD");
              if(sPermission==1){
                sU = 1;
                sD = 1;
              }
              var str_U = '';
              if(sU=='1'){
                str_U = '<a href="{{ route('backend.promotion_cus.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
              }
              // var str_D = '';
              // if(sD=='1'){
              //   str_D = '<a href="javascript: void(0);" data-url="{{ route('backend.account_bank.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" ><i class="bx bx-trash font-size-16 align-middle"></i></a>';
              // }
              // if(sU!='1' && sD!='1'){
              if(sU!='1'){
                 $('td:last-child', nRow).html('-');
              }else{
                // $('td:last-child', nRow).html( str_U + str_D).addClass('input');
                $('td:last-child', nRow).html( str_U ).addClass('input');
              }


        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
        oTable.draw();
    });

    $('.btnSearch01').on('click', function () {
      oTable.draw();
    })

    $('#clearFilter').on('click', function () {
      $('#coupon_name').val('');
      $('#startDate').val('');
      $('#endDate').val('');
      $("#pstatus").val('').trigger('change')
      oTable.draw();
    })
});


$(document).ready(function() {

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

  $('#startDate').datepicker({
      // format: 'dd/mm/yyyy',
      format: 'yyyy-mm-dd',
      uiLibrary: 'bootstrap4',
      iconsLibrary: 'fontawesome',
  });

  $('#endDate').datepicker({
      // format: 'dd/mm/yyyy',
      format: 'yyyy-mm-dd',
      uiLibrary: 'bootstrap4',
      iconsLibrary: 'fontawesome',
      minDate: function () {
          return $('#startDate').val();
      }
  });


});


</script>


@endsection

