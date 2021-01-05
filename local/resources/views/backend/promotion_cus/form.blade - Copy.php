@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> รหัสคูปอง  </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
<?php for ($i=0; $i < 3 ; $i++) { 
  # code...
  ?> <input class="sel_cus"><br><?php
} ?>
 
 

              @if( empty($sRow) )
              <form action="{{ route('backend.promotion_cus.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.promotion_cus.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                      <div class="myBorder">


                          <div class="row">
                                  <div class="col-12">
                                    <div class="card">
                                      <div class="card-body">
                                        <div class="row">
                                          <div class="col-8">
                                          </div>
                                        </div>
                                        
                                        <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                                        </table>
                                        
                                      </div>
                                    </div>
                                    </div> <!-- end col -->
                                    </div> <!-- end row -->


                            

                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/promotion_cus") }}">
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
    </div> <!-- end col -->
</div>
<!-- end row -->
@section('script')

<script type="text/javascript">
  
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
          url: '{{ route('backend.promotion_cus_choose.datatable') }}',
          data: function ( d ) {
            d.Where={};
            $('.Where').each(function() {
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
            // {data: 'promotion_code', title :'<center>รหัสคูปอง</center>', className: 'text-left'},
            {data: 'promotion_code',   title :'<center>รหัสคูปอง</center>', className: 'text-center',render: function(d) {
               return '<input class="form-control" name="promotion_code[]" type="text" value="'+d+'" readonly >';
            }},
            // {data: 'customer_id_fk', title :'<center>รหัสสมาชิก (ลูกค้า) </center>', className: 'text-center'},
            
            // {data: 'pro_status', title :'<center>สถานะ </center>', className: 'text-center'},
            {data: 'pro_status',   title :'<center>สถานะ</center>', className: 'text-center',render: function(d) {
              //  '4=import เข้ารอตรวจสอบหรือนำไปใช้,1=ใช้งานได้,2=ถูกใช้แล้ว,3=หมดอายุแล้ว',
              if(d==4){
                  return 'รอ Map code';
              }else if(d==1){
                  return 'ใช้งานได้';
              }else if(d==2){
                  return 'ถูกใช้แล้ว';
              }else if(d==3){
                  return 'หมดอายุแล้ว';
              }else{
                  return d;
              }
            }},

            {data: 'customer_id_fk',   title :'<center>รหัสสมาชิก หรือ ชื่อลูกค้า </center>', className: 'text-center w220 ',render: function(d) {
              //  '4=import เข้ารอตรวจสอบหรือนำไปใช้,1=ใช้งานได้,2=ถูกใช้แล้ว,3=หมดอายุแล้ว',
              if(d==0){
                  return '<input class="form-control sel_cus ui-autocomplete-input  " name="customer_id[]" type="text">';
                  // return '<input class="sel_cus">';
              }else{
                  return d;
              }
            }},            
        ],
        rowCallback: function(nRow, aData, dataIndex){
          
        }
    });

});



</script>

  <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
$(document).ready(function() {
  

    var availableTags = [
      "8 : 0001 : ชฎาพร1 พิกุล",
      "9 : 0002 : ชฎาพร2 พิกุล",
      "13 : 0003 : ชฎาพร3 พิกุล",
    ];
    $( ".sel_cus" ).autocomplete({
      source: availableTags,
    });
  } );
  </script>


@endsection

@endsection
