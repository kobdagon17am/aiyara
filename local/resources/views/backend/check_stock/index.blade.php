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
                    <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label"> สินค้า : </label>
                        <div class="col-md-9">
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
                      </div>
                    </div>
                    <div class="col-md-6 " >
                      <div class="form-group row">
                            <label for="" class="col-md-2 col-form-label"> Lot-No. : </label>
                            <div class="col-md-10">
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
                          </div>
                    </div>
                  </div>

               <div class="row" >
                    <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="branch_id_fk" class="col-md-3 col-form-label"> สาขา : </label>
                        <div class="col-md-9">
                            <select id="branch_id_fk" name="branch_id_fk" class="form-control select2-templating " >
                             <option value="">Select</option>
                             @if(@$sBranchs)
                              @foreach(@$sBranchs AS $r)
                              <option value="{{$r->id}}" {{ (@$r->id==@$sRow->branch_id_fk)?'selected':'' }} >
                                {{$r->b_name}}
                              </option>
                              @endforeach
                              @endif
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 " >
                      <div class="form-group row">
                            <label for="warehouse_id_fk" class="col-md-2 col-form-label"> คลัง : </label>
                            <div class="col-md-10">
                              <select id="warehouse_id_fk" name="warehouse_id_fk" class="form-control select2-templating " required >
                                 <option value="">เลือกคลัง</option>
                                 @if(@$Subwarehouse)
                                  @foreach(@$Subwarehouse AS $r)
                                  <option value="{{$r->id}}" >
                                    {{$r->w_name}}
                                  </option>
                                  @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>
                    </div>
                  </div>

                  <div class="row" >
                    <div class="col-md-6 " >
                       <div class="form-group row">
                            <label for="zone_id_fk" class="col-md-3 col-form-label"> Zone : </label>
                            <div class="col-md-9">
                              <select id="zone_id_fk" name="zone_id_fk" class="form-control select2-templating " required >
                                <option disabled selected>กรุณาเลือกคลังก่อน</option>
                              </select>
                            </div>
                          </div>
                    </div>
                    <div class="col-md-6 " >
                        <div class="form-group row">
                            <label for="shelf_id_fk" class="col-md-2 col-form-label"> Shelf : </label>
                            <div class="col-md-10">
                              <select id="shelf_id_fk"  name="shelf_id_fk" class="form-control select2-templating " required >
                                 <option disabled selected>กรุณาเลือกโซนก่อน</option>
                              </select>
                            </div>
                          </div>
                    </div>
                  </div>

                  <div class="row" >
                    <div class="col-md-12" >
                       <div class="form-group row">
                        <div class="col-md-12">
                        <center>
                          <a class="btn btn-info btn-sm btnSearch " href="#" style="font-size: 14px !important;" >
                            <i class="bx bx-search align-middle "></i> SEARCH
                          </a>
                        </div>
                        </div>
                    </div>
                  </div>

              </div>
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
                  var branch_id_fk = $('#branch_id_fk').val();
                  var warehouse_id_fk = $('#warehouse_id_fk').val();
                  var zone_id_fk = $('#zone_id_fk').val();
                  var shelf_id_fk = $('#shelf_id_fk').val();
                  
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
                                          d.Where['branch_id_fk'] = branch_id_fk ;
                                          d.Where['warehouse_id_fk'] = warehouse_id_fk ;
                                          d.Where['zone_id_fk'] = zone_id_fk ;
                                          d.Where['shelf_id_fk'] = shelf_id_fk ;
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



<script type="text/javascript">

       $('#branch_id_fk').change(function(){

          var branch_id_fk = this.value;
          // alert(warehouse_id_fk);

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
                   }else{
                       var layout = '<option value="" selected>- เลือกคลัง -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.w_name+'</option>';
                       });
                       $('#warehouse_id_fk').html(layout);
                       $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                       $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                   }
                  }
                })
           }
 
      });


       $('#warehouse_id_fk').change(function(){

          var warehouse_id_fk = this.value;
          // alert(warehouse_id_fk);

           if(warehouse_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetZone') }} ", 
                  method: "post",
                  data: {
                    warehouse_id_fk:warehouse_id_fk,
                    "_token": "{{ csrf_token() }}", 
                  },
                  success:function(data)
                  { 
                   if(data == ''){
                       alert('ไม่พบข้อมูล Zone !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือก Zone -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.z_name+'</option>';
                       });
                       $('#zone_id_fk').html(layout);
                       $('#shelf_id_fk').html('กรุณาเลือกโซนก่อน');
                   }
                  }
                })
           }
 
      });


       $('#zone_id_fk').change(function(){

          var zone_id_fk = this.value;
          // alert(zone_id_fk);

           if(zone_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetShelf') }} ", 
                  method: "post",
                  data: {
                    zone_id_fk:zone_id_fk,
                    "_token": "{{ csrf_token() }}", 
                  },
                  success:function(data)
                  { 
                   if(data == ''){
                       alert('ไม่พบข้อมูล Shelf !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือก Shelf -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.s_name+'</option>';
                       });
                       $('#shelf_id_fk').html(layout);
                   }
                  }
                })
           }
 
      });

</script>


@endsection
