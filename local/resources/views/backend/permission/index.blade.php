@extends('backend.layouts.master')

@section('title') Account (ผู้ดูแลระบบ) @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">Account (ผู้ดูแลระบบ) </h4>
        </div>
    </div>
</div>
<!-- end page title -->


  <?php 
    $sPermission = \Auth::user()->permission ;
      // $menu_id = @$_REQUEST['menu_id'];
      // $menu_id = Session::get('session_menu_id');
      $menu_id = 37 ;
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
                    <input type="text" class="form-control float-left text-center w130 myLike" placeholder="Name" name="name">
                    <input type="text" class="form-control float-left ml-1 text-center w200 myLike" placeholder="E-Mail" name="email">
                  </div>
                  <div class="col-4 text-right" style="{{@$sC}}" >
                    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.permission.create') }}">
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

var sU = "{{@$sU}}"; 
var sD = "{{@$sD}}";

var oTable;
$(function() {

  var sPermission = '{{$sPermission}}' ;
  // alert(sPermission);
    oTable = $('#data-table').DataTable({
    // "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        paging: false,
        // scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 100,
        ajax: {
          url: '{{ route('backend.admin.datatable') }}',
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
            {data: 'branch', title :'<center>สาขา</center>', className: 'text-left'},
            {data: 'business_location', title :'<center>Business location</center>', className: 'text-center'},
            {data: 'isActive', title :'<center>Active</center>', className: 'text-center'},
            {data: 'updated_at', title :'Updated At', className: 'text-center w130'},
            {data: 'id', title :'Action', className: 'text-center w200'},
        ],
        rowCallback: function(nRow, aData, dataIndex){

        	if(aData['permission']==1){
        		$('td:eq(3)', nRow).html('(Super Admin)');
        	}else if(aData['branch']==0){
        		$('td:eq(3)', nRow).html('(ไม่ระบุสาขา)');
        	}

            if(sPermission==1){

	            if(aData['permission']==1&&aData['id']==1){
	                  $('td:last-child', nRow).html(''
	                + '<a title="แก้ไข" href="{{ route('backend.permission.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
	              ).addClass('input');
	            }else{
	                $('td:last-child', nRow).html(''
	                + '<a title="แก้ไข" href="{{ route('backend.permission.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  style="'+sU+'"  ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
	                + '<a href="javascript: void(0);" data-url="" class="btn btn-sm btn-danger  cCancel " data-id="'+aData['id']+'" style="'+sD+'"  ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
	              ).addClass('input');
	            }
               
          }else{
                $('td:last-child', nRow).html(''
                + '<a title="แก้ไข" href="{{ route('backend.permission.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  style="'+sU+'"  ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
              ).addClass('input');
          }
          
        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>


      <script>
      $(document).ready(function() {


          $(document).on('click', '.cCancel', function(event) {

            var id = $(this).data('id');
         
              if (!confirm("ยืนยัน ? เพื่อยกลบ ")){
                  return false;
              }else{
              $.ajax({
                  url: " {{ url('backend/ajaxDelUser') }} ", 
                  method: "post",
                  data: {
                    "_token": "{{ csrf_token() }}", id:id,
                  },
                  success:function(data)
                  { 
                    // console.log(data);
                    // return false;
                        Swal.fire({
                          type: 'success',
                          title: 'ทำการลบรายชื่อเรียบร้อยแล้ว',
                          showConfirmButton: false,
                          timer: 2000
                        });

                        setTimeout(function () {
                          // $('#data-table').DataTable().clear().draw();
                          location.reload();
                        }, 1500);
                  }
                });

            }

              
            });
                
      });

    </script>
@endsection

