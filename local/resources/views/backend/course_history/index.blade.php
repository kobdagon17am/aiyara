@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ประวัติการลงทะเบียน Course / Event </h4>
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
                    <!-- <input type="text" class="form-control float-left text-center w130 myLike" placeholder="" name="package_name"> -->
                  </div>

       <!--            <div class="col-4 text-right">
                    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.course_history.create') }}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD
                    </a>
                  </div>
 -->
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
          url: '{{ route('backend.course_history.datatable') }}',
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
            {data: 'course_event_desc', title :'<center>ชื่อกิจกรรม</center>', className: 'text-left'},
            {data: 'regis_date', title :'<center>วันที่ลงทะเบียน</center>', className: 'text-center'},
            {data: 'amt_registered', title :'<center>จำนวนผู้ลงทะเบียน</center>', className: 'text-center'},
            {data: 'id',   title :'<center>ดูรายชื่อ</center>', className: 'text-center',render: function(d) {
               return '<a class="btn btn-info btn-sm mt-1" href="backend/course_history_list/'+d+'" >ตรวจสอบ</a>';
            }},
            {data: 'file_download',   title :'<center>Download</center>', className: 'text-center',render: function(d) {
               // return '<a class="btn btn-info btn-sm mt-1" href="{{ asset('local/public/file_download/') }}/'+d+'" download >Download</a>';
               return '-อยู่ระหว่างการปรับปรุง-';
            }},
        ],

    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>
@endsection

