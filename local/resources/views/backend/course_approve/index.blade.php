@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style>
  .select2-selection {height: 34px !important;margin-left: 3px;}
</style>
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> คอร์สรออนุมัติ </h4>
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
                      <label> สาขา : * </label>

                        @if($sPermission==1)
                        <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating" required>
                          <option value="" selected>กรุณาเลือก Business Location ก่อน</option>
                        </select>
                        @else

                        @if( empty(@$sRow) )
                        <input type="hidden" name="branch_id_fk" value="{{@\Auth::user()->branch_id_fk}}">
                        @else
                        <input type="hidden" name="branch_id_fk" value="{{@$sRow->branch_id_fk}}">
                        @endif
                        <select class="form-control select2-templating" disabled="">
                          @if(@$sBranchs)
                          @foreach(@$sBranchs AS $r)
                          <?=$branch_id_fk=(@$sRow->branch_id_fk?@$sRow->branch_id_fk : @\Auth::user()->branch_id_fk)?>
                          <option value="{{$r->id}}" {{ ( @$r->id==$branch_id_fk) ? 'selected': ''  }}>
                            {{$r->b_name}}
                          </option>
                          @endforeach
                          @endif
                        </select>
                        @endif
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="orderStatus"> สถานะ : </label>
                      <select id="orderStatus" name="orderStatus" class="form-control select2-templating">
                        <option value="">-Select-</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->detail }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label>เลขใบสั่งซื้อ :</label>
                      <input type="text" class="form-control" id="codeOrder">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="type"> จุดประสงค์การสั่งซื้อ :  </label>
                      <select id="type" name="type" class="form-control select2-templating">
                          <option value="">-Select-</option>
                          @foreach ($types as $type)
                              <option value="{{ $type->id }}">{{ $type->orders_type }}</option>
                          @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label> วั่นเริ่ม - วันสิ้นสุด : </label>
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
          url: '{{ route('backend.course_approve.datatable') }}',
          data: function ( d ) {
            d.business_location_id_fk = $('#business_location_id_fk').val()
            d.branch_id_fk = $('#branch_id_fk').val()
            d.orderStatus = $('#orderStatus').val()
            d.type = $('#type').val()
            d.date = {}
            d.date['start'] = $('#startDate').val()
            d.date['end'] = $('#endDate').val()
            d.codeOrder = $('#codeOrder').val().trim()
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
            {data: 'status', title :'<center>สถานะ </center>', className: 'text-center'},
            {data: 'status_slip',   title :'<center>Status Slip</center>', className: 'text-center',render: function(d) {
              if(d=='true'){
                  return '<span class="badge badge-pill badge-soft-success font-size-16">T</span>';
              }else{
                  return '<span class="badge badge-pill badge-soft-danger font-size-16">F</span>';
              }
            }},
            {data: 'id',   title :'<center>ตรวจสอบ/อนุมัติ</center>', className: 'text-center', orderable: false, render: function(d) {
               return d!=1?d:'';
            }},
        ],
        rowCallback: function(nRow, aData, dataIndex){

          if(sU!=''){
              $('td:last-child', nRow).html('-');
          }else{

            if(aData['id']!=1){
                  $('td:last-child', nRow).html(''
                  + '<a href="{{ route('backend.course_approve.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                  + ''
                ).addClass('input');
            }

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
      $('#branch_id_fk').val('').trigger('change')
      $('#orderStatus').val('').trigger('change')
      $('#type').val('').trigger('change')
      $('#codeOrder').val('')
      $('#startDate').val('').trigger('change')
      $('#endDate').val('').trigger('change')
      oTable.draw();
    })

    $('#business_location_id_fk').change(function(){
          $(".myloading").show();
          var business_location_id_fk = this.value;
          // alert(warehouse_id_fk);

           if(business_location_id_fk != ''){
             $.ajax({
                  url: " {{ url('backend/ajaxGetBranch') }} ",
                  method: "post",
                  data: {
                    business_location_id_fk:business_location_id_fk,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                   if(data == ''){
                       alert('ไม่พบข้อมูลสาขา !!.');
                       $(".myloading").hide();
                   }else{
                       var layout = '<option value="" selected>- เลือกสาขา -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.b_name+'</option>';
                       });
                       $('#branch_id_fk').html(layout);
                       $('#warehouse_id_fk').html('<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
                       $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                       $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                       $(".myloading").hide();
                   }
                  }
                })
           }else{
            $(".myloading").hide();
           }

      });



 $('#branch_id_fk').change(function(){

          $(".myloading").show();
          var branch_id_fk = this.value;
          // alert(branch_id_fk);

           if(branch_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetWarehouse') }} ",
                  method: "post",
                  data: {
                    branch_id_fk:branch_id_fk,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                   if(data == ''){
                       alert('ไม่พบข้อมูลคลัง !!.');
                       $(".myloading").hide();
                   }else{
                       var layout = '<option value="" selected>- เลือกคลัง -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.w_name+'</option>';
                       });
                       $('#warehouse_id_fk').html(layout);
                       $(".myloading").hide();
                   }
                  }
                })
           }else{
               $('#warehouse_id_fk').html('<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
               $(".myloading").hide();
           }

      });

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
@endsection

