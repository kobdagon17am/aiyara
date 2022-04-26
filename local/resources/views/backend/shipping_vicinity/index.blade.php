@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ค่าขนส่ง  </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                  <div class="col-8">
                  </div>

                  <div class="col-4 text-right" >
               <!--      <a class="btn btn-info btn-sm mt-1 class_btn_add " href="{{ route('backend.shipping_cost.create') }}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i> เพิ่ม
                    </a> -->
                  </div> 

                </div>

                <table id="data-table" class="table table-bordered " style="width: 100%;">
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->



@endsection

@section('script')

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
        ordering: false,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.shipping_cost.datatable') }}',
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
            {data: 'business_location', title :'<center>Business location </center>', className: 'text-center'},
            {data: 'shipping_name', title :'<center>รายการค่าขนส่ง </center>', className: 'text-center'},
            {data: 'purchase_amt',   title :'<center>ยอดซื้อ (บาท) </center>', className: 'text-center',render: function(d) {
               return d>0?d:'';
            }},            
            {data: 'shipping_cost', title :'<center>ค่าขนส่ง (บาท) </center>', className: 'text-center'},
            {data: 'id', title :'Tools', className: 'text-center w80'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){
   

            if(aData['shipping_type_id']==1){


                    var sPermission = "<?=\Auth::user()->permission?>";
                    var sU = sessionStorage.getItem("sU");
                    var sD = sessionStorage.getItem("sD");
                    if(sPermission==1){
                      sU = 1;
                      sD = 1;
                    }
                    var str_U = '';
                    if(sU=='1'){
                      str_U = '<a href="{{ route('backend.shipping_cost.index') }}/'+aData['business_location_id_fk']+'/edit" class="btn btn-sm btn-primary" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                    }
           
                    if(sU!='1' ){
                       $('td:last-child', nRow).html('-');
                    }else{
                      $('td:last-child', nRow).html( str_U ).addClass('input');
                    }
                    
                    

             }else{
                $('td:last-child', nRow).html('');
             }


        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>


@endsection

