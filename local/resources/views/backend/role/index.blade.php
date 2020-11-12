@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Role  </h4>
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
                   <!--  <input type="text" class="form-control float-left text-center w130 myLike" placeholder="ค้น : เลขใบรับเรื่อง" name="subject_receipt_number"> -->
                  </div>

                  <div class="col-4 text-right">
                    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.role.create') }}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD
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
var oTable;
$(function() {
    var sPermission = '{{$sPermission}}' ;
    console.log(sPermission);
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
          url: '{{ route('backend.role.datatable') }}',
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
            {data: 'role_name', title :'<center>ชื่อกลุ่มสิทธิ์</center>', className: 'text-left'},
            {data: 'access_menu',   title :'<center>เข้าถึงเมนู</center>', className: 'text-left',render: function(d) {
              if(d){
                return d.replace(/ *, */g, '<br>');
              }else{
                return '-';
              }
            }},
            {data: 'member_ingroup',   title :'<center>รายชื่อสมาชิกที่อยู่ในกลุ่มนี้</center>', className: 'text-left',render: function(d) {
               return d.replace(/ *, */g, '<br>');
            }},
            {data: 'status',   title :'<center>Status</center>', className: 'text-center',render: function(d) {
               return d==1?'<span style="color:blue">เปิดใช้งาน</span>':'<span style="color:red">ปิด</span>';
            }},
            {data: 'id', title :'Tools', className: 'text-center w80'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){

            if(sPermission==1){
                if(aData['id']==1){
                    $('td:last-child', nRow).html('');
                }else{
                    $('td:last-child', nRow).html(''
                      + '<a href="{{ route('backend.role.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-success"><i class="bx bxs-key font-size-16 align-middle"></i></a> '
                      + '<a href="javascript: void(0);" data-url="{{ route('backend.role.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                    ).addClass('input');
                }
          }else{
                $('td:last-child', nRow).html(''
                + '<a title="แก้ไข" href="{{ route('backend.role.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                ).addClass('input');
          }


        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>
@endsection

