@extends('backend.layouts.master')

@section('title') Account (ผู้ดูแลระบบ) @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">Account (ผู้ดูแลระบบ)</h4>

  <!--           <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Backend</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Permission</a></li>
                    <li class="breadcrumb-item active">Account (ผู้ดูแลระบบ) x</li>
                </ol>
            </div> -->

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
                    <input type="text" class="form-control float-left text-center w130 myLike" placeholder="Name" name="name">
                    <input type="text" class="form-control float-left ml-1 text-center w200 myLike" placeholder="E-Mail" name="email">
                  </div>
                  <div class="col-4 text-right">
                    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.permission.admin.create') }}">
                      <i class="bx bx-plus font-size-16 align-middle mr-1"></i>เพิ่มผู้ใช้งาน
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
  // alert(sPermission);
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
          url: '{{ action("Backend\Permission\AdminController@Datatable") }}',
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
            {data: 'id', title :'Code', className: 'text-center w50'},
            @if(empty(\Auth::user()->locale_id))
            {data: 'locale.name', title :'Language', className: 'text-center w100'},
            @endif
            {data: 'name', title :'<center>Name</center>', className: 'text-center'},
            {data: 'email', title :'<center>E-Mail</center>', className: 'text-left'},
            {data: 'isActive', title :'<center>Active</center>', className: 'text-center'},
            {data: 'updated_at', title :'Updated At', className: 'text-center w130'},
            {data: 'id', title :'Action', className: 'text-center w200'},
        ],
        rowCallback: function(nRow, aData, dataIndex){
          if(sPermission==1){
            if(aData['permission']==1&&aData['id']==1){
                  $('td:last-child', nRow).html(''
                + '<a title="แก้ไข" href="{{ route('backend.permission.admin.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
              ).addClass('input');
            }else{
                $('td:last-child', nRow).html(''
                + '<a title="แก้ไข" href="{{ route('backend.permission.admin.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                + '<a title="กำหนดสิทธิ์" href="{{ route('backend.menu_permission.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-success"><i class="bx bxs-key font-size-16 align-middle"></i></a> '
                + '<a href="javascript: void(0);" data-url="{{ route('backend.permission.admin.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
              ).addClass('input');
            }
               
          }else{
                $('td:last-child', nRow).html(''
                + '<a title="แก้ไข" href="{{ route('backend.permission.admin.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
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

