@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> {{ __('message.course_event_save_header') }}  </h4>
        </div>
    </div>
</div>
<!-- end page title -->
  <?php 
      $sPermission = \Auth::user()->permission ;
      $menu_id = Session::get('session_menu_id');
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
                     <h4 class="mb-0 font-size-18"> {{ __('message.course_event_save_header') }} ({{ __('message.group_list') }}) </h4>
                  </div>

                  <div class="col-4 text-right" style="{{@$sC}}">
                    <a class="btn btn-info btn-sm mt-1 " href="{{ route('backend.ce_regis.create') }}/?v=1">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD / Import / Export 
                    </a>
                  </div>

                </div>

                <table id="data-table-001" class="table table-bordered " style="width: 100%;">
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->



<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                  <div class="col-8">
                     <h4 class="mb-0 font-size-18"> {{ __('message.course_event_save_header') }} ({{ __('message.personal_list') }}) </h4>
                  </div>

                  <div class="col-4 text-right" style="{{@$sC}}">
                    <a class="btn btn-info btn-sm mt-1 " href="{{ route('backend.ce_regis.create') }}/?v=2">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD / Import / Export 
                    </a>
                  </div>

                </div>

                <table id="data-table-002" class="table table-bordered " style="width: 100%;">
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->



@endsection

@section('script')

<script>
var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
var oTable;
$(function() {
    oTable = $('#data-table-001').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        scrollCollapse: true,
        scrollX: true,
        ordering: false,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 10,
        ajax: {
          url: '{{ route('backend.ce_regis.datatable') }}',
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
            {data: 'ce_name', title :'<center>{{ __("message.course_event_name") }}</center>', className: 'text-center'},
            {data: 'customer_name', title :'<center>{{ __("message.customer_name") }}</center>', className: 'text-center'},
            {data: 'ticket_number', title :'<center>{{ __("message.card_number") }}</center>', className: 'text-center'},
            // {data: 'level_class',   title :'<center>Class</center>', className: 'text-center ',render: function(d) {
            //     return '<span class="badge badge-pill badge-soft-success font-size-16">'+d+'</span>';
            // }},
            {data: 'regis_date', title :'<center>{{ __("message.register_date") }}</center>', className: 'text-center'},
            {data: 'id', title :'Tools', className: 'text-center w80'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){
          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

              $('td:last-child', nRow).html(''
                + '<a href="{{ route('backend.ce_regis.index') }}/'+aData['id']+'/edit?role_group_id='+role_group_id+'&menu_id='+menu_id+'" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                + '<a href="javascript: void(0);" data-url="{{ route('backend.ce_regis.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
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

