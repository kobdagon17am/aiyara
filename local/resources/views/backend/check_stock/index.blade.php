@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style type="text/css">
  .sorting_disabled {background-color: #cccccc !important;font-weight: bold;}
</style>
@endsection

@section('content')


<div class="row">
    <div class="col-md-12" style="">
        <div id="spinner_frame"
            style="display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            z-index: 9999;
            "><p align="center">
                <img src="{{ asset('backend/images/preloader_big.gif') }}">
            </p></div>
        </div>
    </div>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> Check Stock </h4>
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
                        <div class="form-group row">
                            <div class="col-md-6">
                              <select name="product" id="product" class="form-control select2-templating "  >
                                <option value="">-รหัสสินค้า : ชื่อสินค้า-</option>
                                   @if(@$Products)
                                        @foreach(@$Products AS $r)
                                          <option value="{{@$r->product_id}}" >
                                            {{@$r->product_code." : ".@$r->product_name}}
                                          </option>
                                        @endforeach
                                      @endif
                              </select>
                            </div>

                            <div class="col-md-3">
                              <select name="lot_number" id="lot_number" class="form-control select2-templating "  >
                                <option value="">-Lot Number-</option>
                                   @if(@$Check_stock)
                                        @foreach(@$Check_stock AS $r)
                                          <option value="{{@$r->lot_number}}" >
                                            {{@$r->lot_number}}
                                          </option>
                                        @endforeach
                                      @endif
                              </select>
                            </div>

                            <div class="col-2" >
                              <a class="btn btn-info btn-sm btnSearch " href="#" style="font-size: 14px !important;" >
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

<script src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js" type="text/javascript" charset="utf-8" async defer></script>
<script type="text/javascript">
 $(document).ready(function() {
    $('#example').DataTable( {
      "bLengthChange": false ,
      "searching": false,
        order: [[2, 'asc']],
        rowGroup: {
            startRender: null,
            endRender: function ( rows, group ) {
                var salaryAvg = rows
                    .data()
                    .pluck(5)
                    .reduce( function (a, b) {
                        return a + b.replace(/[^\d]/g, '')*1;
                    // }, 0) / rows.count();
                    }, 0) / 1 ;
                salaryAvg = $.fn.dataTable.render.number(',', '.', 0, '$').display( salaryAvg );
 
                var ageAvg = rows
                    .data()
                    .pluck(3)
                    .reduce( function (a, b) {
                        return a + b*1;
                    }, 0) / rows.count();
 
                return $('<tr/ style=" background-color:#f2f2f2 !important;">')
                    .append( '<td colspan="3">Averages for '+group+'</td>' )
                    .append( '<td>'+ageAvg.toFixed(0)+'</td>' )
                    .append( '<td/>' )
                    .append( '<td>'+salaryAvg+'</td>' );
            },
            dataSrc: 2
        }
    } );
} );
</script>
<script type="text/javascript">

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
          url: '{{ route('backend.check_stock.datatable') }}',
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
            {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left'},
            {data: 'lot_number', title :'<center>ล็อตนัมเบอร์ </center>', className: 'text-left'},
            {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
            // {data: 'amt', title :'<center>จำนวน </center>', className: 'text-center'},
            {data: 'amt',
                 defaultContent: "0",   title :'<center>จำนวน</center>', className: 'text-center',render: function(d) {
                     return d;
                  
              }},

            {data: 'warehouses', title :'<center>คลังสินค้า </center>', className: 'text-left'},
        ],
        order: [[1, 'asc']],
        rowGroup: {
            startRender: null,
            endRender: function ( rows, group ) {
                var sTotal = rows
                   .data()
                   .pluck('amt')
                   .reduce( function (a, b) {
                       return a + b*1;
                   }, 0);
                    sTotal = $.fn.dataTable.render.number(',', '.', 0, '<span>&#3647;</span> ').display( sTotal );
                // sTotal = 2;
 
                return $('<tr/ style=" background-color:#f2f2f2 !important;">')
                    .append( '<td colspan="4" style="text-align:center;">Total for '+group+'</td>' )
                    .append( '<td style=" background-color:#f2f2f2 !important;font-weight: bold; "><center>'+(sTotal)+'</td>' )
                    .append( '<td></td>' );
            },
            dataSrc: "product_name"
        },
   
    });

});

/*
  sessionStorage.setItem("role_group_id", role_group_id);
  var role_group_id = sessionStorage.getItem("role_group_id");
  var menu_id = sessionStorage.getItem("menu_id");
    window.onload = function() {
    if(!window.location.hash) {
       window.location = window.location + '?role_group_id=' + role_group_id + '&menu_id=' + menu_id + '#menu_id=' + menu_id ;
    }
  }
*/

</script>


  <script>


        $(document).ready(function() {
          
            $(document).on('click', '.btnSearch', function(event) {
                  event.preventDefault();

                  $("#spinner_frame").show();

                  var product = $('#product').val();
                  var lot_number = $('#lot_number').val();
                  
                  console.log(product);
                  console.log(lot_number);

                  // return false;

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
                                    destroy: true,
                                    scrollY: ''+($(window).height()-370)+'px',
                                    iDisplayLength: 25,
                                    ajax: {
                                      url: '{{ route('backend.check_stock.datatable') }}',
                                      data: function ( d ) {
                                          d.Where={};
                                          d.Where['product_id_fk'] = product ;
                                          d.Where['lot_number'] = lot_number ;
                                          oData = d;
                                          $("#spinner_frame").hide();
                                        },
                                         method: 'POST',
                                       },

                                    columns: [
                                        {data: 'id', title :'ID', className: 'text-center w50'},
                                        {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left'},
                                        {data: 'lot_number', title :'<center>ล็อตนัมเบอร์ </center>', className: 'text-left'},
                                        {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
                                        // {data: 'amt', title :'<center>จำนวน </center>', className: 'text-center'},
                                        {data: 'amt',
                                             defaultContent: "0",   title :'<center>จำนวน</center>', className: 'text-center',render: function(d) {
                                                 return d;
                                              
                                          }},

                                        {data: 'warehouses', title :'<center>คลังสินค้า </center>', className: 'text-left'},
                                    ],
                                    order: [[1, 'asc']],
                                    rowGroup: {
                                        startRender: null,
                                        endRender: function ( rows, group ) {
                                            var sTotal = rows
                                               .data()
                                               .pluck('amt')
                                               .reduce( function (a, b) {
                                                   return a + b*1;
                                               }, 0);
                                                sTotal = $.fn.dataTable.render.number(',', '.', 0, '<span>&#3647;</span> ').display( sTotal );
                                            // sTotal = 2;
                             
                                            return $('<tr/ style=" background-color:#f2f2f2 !important;">')
                                                .append( '<td colspan="4" style="text-align:center;">Total for '+group+'</td>' )
                                                .append( '<td style=" background-color:#f2f2f2 !important;font-weight: bold; "><center>'+(sTotal)+'</td>' )
                                                .append( '<td></td>' );
                                        },
                                        dataSrc: "product_name"
                                    },
                               
                                });

                            });


               
               
            });

        }); 
    </script>


@endsection

