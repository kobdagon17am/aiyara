@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> โอนสินค้าระหว่างคลัง </h4>
        </div>
    </div>
</div>
<!-- end page title -->
  <?php 
      $sPermission = \Auth::user()->permission ;
      $menu_id = @$_REQUEST['menu_id'];
      if($sPermission==1){
        $sC = '';
        $sU = '';
        $sD = '';
        $role_group_id = '%';
      }else{
        $role_group_id = \Auth::user()->role_group_id_fk;
        // echo $role_group_id;
        // echo $menu_id;     
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
        $sC = @$menu_permit->c==1?'':'display:none;';
        $sU = @$menu_permit->u==1?'':'display:none;';
        $sD = @$menu_permit->d==1?'':'display:none;';
      }
      // echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;     
   ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row" >
                  <div class="col-8">
                    <!-- <input type="text" class="form-control float-left text-center w125 myLike" placeholder="หมายเลข PO" name="po_number"> -->
                    <!-- <input type="text" class="form-control float-left text-center w125 myLike" placeholder="ชื่อ Supplier" name="supplier_name" style="margin-left: 1%;"> -->

                        <div class="form-group row">
                        <label for="example-text-input" class="col-md-2 col-form-label">ชื่อสินค้า : * </label>
                        <div class="col-md-6">
                          <select name="product" class="form-control select2-templating "  >
                            <option value="">Select</option>
                              @if(@$sProduct)
                                @foreach(@$sProduct AS $r)
                                  <option value="{{$r->id}}" >{{$r->product_name}}</option>
                                @endforeach
                              @endif
                          </select>
                        </div>


                  <div class="col-2" >
                    <a class="btn btn-info btn-sm btnSearch " href="{{ route('backend.transfer_warehouses.index') }}">
                      <i class="bx bx-search align-middle "></i> SEARCH
                    </a>
                  </div>

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

<script>
var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; //alert(sU);
var menu_id = "{{@$menu_id?@$menu_id:0}}"; //alert(sU);
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
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
          url: '{{ route('backend.general_receive.datatable') }}',
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
            // {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left'},
            {data: 'product_in_cause', title :'<center>สาเหตุที่รับเข้า </center>', className: 'text-left'},
            {data: 'lot_number', title :'<center>Lot number </center>', className: 'text-left'},
            {data: 'lot_expired_date', title :'<center>Lot expired date </center>', className: 'text-center'},
            {data: 'amt', title :'<center>จำนวน </center>', className: 'text-center'},
            {data: 'recipient_name', title :'<center>พนักงานที่รับ </center>', className: 'text-center'},
            {data: 'pickup_date', title :'<center>วันที่รับเข้า </center>', className: 'text-center'},
            
            {data: 'approve_status',   title :'<center>สถานะการอนุมัติ</center>', className: 'text-center w100 ',render: function(d) {
              if(d==1){
                return '<span class="badge badge-pill badge-soft-success font-size-16" style="color:darkgreen">อนุมัติแล้ว</span>';
              }else{
                  return '';
              }
            }},
            {data: 'id', title :'Tools', className: 'text-center w80'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){
          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

              $('td:last-child', nRow).html(''
                + '<a href="{{ route('backend.general_receive.index') }}/'+aData['id']+'/edit?role_group_id='+role_group_id+'&menu_id='+menu_id+'" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                + '<a href="javascript: void(0);" data-url="{{ route('backend.general_receive.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
              ).addClass('input');

          }
        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});
</script>


<script type="text/javascript">

  sessionStorage.setItem("role_group_id", role_group_id);
  var role_group_id = sessionStorage.getItem("role_group_id");
  var menu_id = sessionStorage.getItem("menu_id");
    window.onload = function() {
    if(!window.location.hash) {
       window.location = window.location + '?role_group_id=' + role_group_id + '&menu_id=' + menu_id + '#menu_id=' + menu_id ;
    }
  }




  $(document).ready(function() {
      $(document).on('click', '.btnSearch', function(event) {
        event.preventDefault();
        /* Act on the event */
      });
  });

  
</script>

@endsection

