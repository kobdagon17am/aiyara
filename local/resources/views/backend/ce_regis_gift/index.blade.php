@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ประเภทของที่ให้ตอนเข้าร่วมงาน </h4>
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
                    <!-- <input type="text" class="form-control float-left text-center w130 myLike" placeholder="รหัสย่อ" name="short_code"> -->
                  </div>

                  <div class="col-4 text-right" >
                    <a class="btn btn-info btn-sm mt-1 class_btn_add " href="{{ route('backend.ce_regis_gift.create') }}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD
                    </a>
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
          url: '{{ route('backend.ce_regis_gift.datatable') }}',
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
            {data: 'txt_desc', title :'<center>รายการ </center>', className: 'text-left'},
            {data: 'status',   title :'<center>สถานะ</center>', className: 'text-center',render: function(d) {
               return d==1?'<span style="color:blue">เปิดใช้งาน</span>':'<span style="color:red">ปิด</span>';
            }},
            // {data: 'id', title :'Tools', className: 'text-center w60'}, 
            {data: 'id',   title :'<center>Tools</center>', className: 'text-center',render: function(d) {
               return d!=1?d:'';
            }},
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
                str_U = '<a href="{{ route('backend.ce_regis_gift.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
              }
     
              if(sU!='1' ){
                 $('td:last-child', nRow).html('-');
              }else{
                $('td:last-child', nRow).html( str_U ).addClass('input');
              }
              

          
        }

    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>
@endsection

