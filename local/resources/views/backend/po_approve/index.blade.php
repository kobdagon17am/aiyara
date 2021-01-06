@extends('backend.layouts.master')
@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ใบสั่งซื้อรออนุมัติ </h4>
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
                  <div class="col-8">
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
var sU = "{{@$sU}}"; 
var sD = "{{@$sD}}";  
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
          url: '{{ route('backend.po_approve.datatable') }}',
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
            {data: 'id', title :'PO-ID', className: 'text-center w50'},
            {data: 'code_order', title :'<center>เลขใบสั่งซื้อ </center>', className: 'text-center'},
            {data: 'price', title :'<center>ยอดชำระ </center>', className: 'text-center'},
            {data: 'pv_total', title :'<center>PV </center>', className: 'text-center'},
            {data: 'type', title :'<center>จุดประสงค์การสั่งซื้อ </center>', className: 'text-center'},
            {data: 'date', title :'<center>วันที่สั่งซื้อ </center>', className: 'text-center'},
            {data: 'pay_type_name', title :'<center>ชำระโดย </center>', className: 'text-center'},
            {data: 'status', title :'<center>สถานะ </center>', className: 'text-center'},
            {data: 'id',   title :'<center>ตรวจสอบ/อนุมัติ</center>', className: 'text-center',render: function(d) {
               return d!=1?d:'';
            }},
        ],
        rowCallback: function(nRow, aData, dataIndex){

          if(sU!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

            if(aData['id']!=1){
                  $('td:last-child', nRow).html(''
                  + '<a href="{{ route('backend.po_approve.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                  + ''
                ).addClass('input');
            }

          }
          
        }

    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>
@endsection

