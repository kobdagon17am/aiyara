@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style>
  .select2-selection {height: 34px !important;margin-left: 3px;}
  .dataTables_processing {
    width: 0 !important;
  }
</style>
@endsection

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ตั้งค่าการแถมสินค้า </h4>
        </div>
    </div>
</div>
<!-- end page title -->
  <?php
    $sPermission = \Auth::user()->permission ;
      // $menu_id = @$_REQUEST['menu_id'];
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
                    <div class="col-md-4">
                      <div class="form-group">
                        <label> Business Location : * </label>
                        <select id="business_location_id_fk" name="business_location_id_fk"
                          class="form-control select2-templating " required="" @if($sPermission !== 1) disabled @endif>
                          <option value="">-Business Location-</option>
                          @if(@$sBusiness_location)
                            @foreach(@$sBusiness_location AS $r)
                                <option value="{{$r->id}}" {{ (@$r->id == auth()->user()->business_location_id_fk && auth()->user()->permission !== 1) ?'selected':'' }}>
                                  {{$r->txt_desc}}
                                </option>
                            @endforeach
                          @endif
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>ชื่อการแถม :</label>
                        <input type="text" class="form-control" id="giveaway_name">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label> วั่นเริ่มต้น - วันสิ้นสุด : </label>
                         <div class="d-flex">
                          <input id="startDate"  autocomplete="off" placeholder="Start Date" class="h-auto"/>
                          <input id="endDate"  autocomplete="off" placeholder="End Date" class="h-auto"/>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <button class="btn btn-info btn-sm" id="searchFilter">
                          <i class="bx bx-search align-middle "></i> SEARCH
                        </button>
                        <button class="btn btn-dark btn-sm" id="clearFilter">
                          <i class="bx bx-revision"></i> CLEAR
                        </button>
                      </div>
                    </div>
                  <div class="col-8">
                    <!-- <input type="text" class="form-control float-left text-center w130 myLike" placeholder="รหัสย่อ" name="short_code"> -->
                  </div>

                  <div class="col-4 text-right" style="{{@$sC}}" >
                    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.giveaway.create') }}">
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
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
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
        ordering: true,
        scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.giveaway.datatable') }}',
          data: function ( d ) {
            d.business_location_id_fk = $('#business_location_id_fk').val()
            d.giveaway_name = $('#giveaway_name').val().trim();
            d.startDate = $('#startDate').val()
            d.endDate = $('#endDate').val()
          },
          method: 'POST'
        },

        columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 'business_location', title :'<center>Business Location </center>', className: 'text-left'},
            {data: 'giveaway_name', title :'<center>ชื่อการแถม </center>', className: 'text-center'},
            {data: 'start_date', title :'<center>วันเริ่มต้น </center>', className: 'text-center'},
            {data: 'end_date', title :'<center>วันสิ้นสุด </center>', className: 'text-center'},
            {data: 'status',   title :'<center>สถานะ</center>', className: 'text-center',render: function(d) {
               return d==1?'<span style="color:blue">เปิดใช้งาน</span>':'<span style="color:red">ปิด</span>';
            }},
            {data: 'id', title :'Tools', className: 'text-center w60', orderable: false},
        ],
        order: [
          [ 0, 'asc' ]
        ],
        language: {
            loadingRecords: '&nbsp;',
            processing: '<div class="myloading d-block"></div>'
        } ,
        rowCallback: function(nRow, aData, dataIndex){

          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{

          $('td:last-child', nRow).html(''
            + '<a href="{{ route('backend.giveaway.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
            + '<a href="javascript: void(0);" data-url="{{ route('backend.giveaway.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"  style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
          ).addClass('input');

        }

        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });

    $('#searchFilter').on('click', function () {
      oTable.draw();
    })


    $('#clearFilter').on('click', function () {
      $('#business_location_id_fk').val('').trigger('change')
      $('#giveaway_name').val('')
      $('#startDate').val('').trigger('change')
      $('#endDate').val('').trigger('change')
      oTable.draw();
    })

    $('#startDate').datepicker({
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4',
        iconsLibrary: 'fontawesome',
    });

    $('#endDate').datepicker({
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4',
        iconsLibrary: 'fontawesome',
        minDate: function () {
            return $('#startDate').val();
        }
    });
});
</script>

<script type="text/javascript">
  /*
  var menu_id = sessionStorage.getItem("menu_id");
    window.onload = function() {
    if(!window.location.hash) {
       window.location = window.location + '?menu_id=' + menu_id + '#menu_id=' + menu_id ;
    }
  }
  */
</script>
@endsection

