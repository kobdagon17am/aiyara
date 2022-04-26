@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style>
    .select2-selection {height: 34px !important;margin-left: 3px;}
    .border-left-0 {height: 67%;}
</style>
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> รายการเดินบัญชีกัมพูชา </h4>
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
                  <div class="row">
                    <div class="col-12 d-flex ">
                      <div class="col-md-3 ">
                        <div class="form-group row">
                          <select id="branch_id_search" name="branch_id_search" class="form-control select2-templating " >
                            <option value="">สาขา</option>
                            @if(@$sBranchs)
                            @foreach(@$sBranchs AS $r)
                            <option value="{{$r->id}}"  >
                              {{$r->b_name}}
                            </option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group row">
                          <select id="warehouse_id_search" name="warehouse_id_search" class="form-control select2-templating "  >
                            <option disabled selected >(คลัง) กรุณาเลือกสาขาก่อน</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group row">
                          <select id="status_search" name="status_search" class="form-control select2-templating " >
                            <option value="" >สถานะ</option>
                            <option value="0" >รออนุมัติ</option>
                            <option value="1" >อนุมัติ</option>
                            <option value="3" >ไม่อนุมัติ</option>
                            <option value="2" >ยกเลิก</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4 d-flex  ">
                         <input id="startDate"  autocomplete="off" placeholder="วันเริ่ม"  />
                         <input id="endDate"  autocomplete="off" placeholder="วันสิ้นสุด"  />
                      </div>
                      <div class="col-md-2">
                        <div class="form-group row"> &nbsp; &nbsp;
                          <button type="button" class="btn btn-success btn-sm waves-effect btnSearchInList " style="font-size: 14px !important;" >
                          <i class="bx bx-search font-size-16 align-middle mr-1"></i> ค้น
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>


                  </div>

                  <div class="col-4 text-right" style="{{@$sC}}" >
                 <!--    <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.check_money_daily.create') }}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD
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
          url: '{{ route('backend.commission_transfer.datatable') }}',
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

// POST DATE  DESCRIPTION CODE  VALUE DATE  CREDIT  AMOUNT  BALANCE
        columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 'action_date', title :'<center>POST DATE</center>', className: 'text-left'},
            {data: 'action_date',   title :'<center>DESCRIPTION</center>', className: 'text-center',render: function(d) {
               return 'Account Tranfer';
            }},
            {data: 'action_date',   title :'<center>CODE</center>', className: 'text-center',render: function(d) {
               return 'f2t004z8d131';
            }},
            {data: 'amount', title :'<center>VALUE DATE</center>', className: 'text-left'},
            {data: 'action_date',   title :'<center>CREDIT</center>', className: 'text-center',render: function(d) {
               return 'CREDIT';
            }},
            {data: 'amount', title :'<center>AMOUNT</center>', className: 'text-left'},
            {data: 'amount', title :'<center>BALANCE</center>', className: 'text-left'},
            
            {data: 'id', title :'Tools', className: 'text-center w60'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){

          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

            $('td:last-child', nRow).html('-');

          // $('td:last-child', nRow).html(''
          //   + '<a href="{{ route('backend.check_money_daily.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
          //   + '<a href="javascript: void(0);" data-url="{{ route('backend.check_money_daily.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
          // ).addClass('input');

          }

        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>


    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#startDate').datepicker({
            format: 'dd/mm/yyyy',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
        });
        $('#endDate').datepicker({
            format: 'dd/mm/yyyy',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                return $('#startDate').val();
            }
        });

         $('#startDate').change(function(event) {
           $('#endDate').val($(this).val());
         });        

</script>

@endsection

