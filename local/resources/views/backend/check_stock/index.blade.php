@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style type="text/css">
  .sorting_disabled {background-color: #cccccc !important;font-weight: bold;}

  .form-group {
     /*margin-bottom: 1rem; */
     margin-bottom: 0rem  !important; 
  }
    
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


              <div class="row" >

                 <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label">Business Location : </label>
                        <div class="col-md-9">
                          <?php $dis01 = !empty(@$sRow->condition_business_location)?'disabled':'' ?>
                         <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " required="" <?=$dis01?> >
                              <option value="">-Business Location-</option>
                              @if(@$sBusiness_location)
                                @foreach(@$sBusiness_location AS $r)
                                <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_business_location)?'selected':'' }} >
                                  {{$r->txt_desc}}
                                </option>
                                @endforeach
                              @endif
                            </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6 " >
                      <div class="form-group row">
                            <label for="branch_id_fk" class="col-md-2 col-form-label"> สาขา : </label>
                            <div class="col-md-10">
                            
                              <?php if(!empty(@$sRow->condition_branch)){ ?>
                                  <select class="form-control select2-templating " disabled="" >
                                      @if(@$sBranchs)
                                        @foreach(@$sBranchs AS $r)
                                          <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_branch)?'selected':'' }} >
                                            {{$r->b_name}}
                                          </option>
                                        @endforeach
                                      @endif
                                  </select>
                              <?php }else{ ?>
                                  <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating " >
                                     <option disabled selected>กรุณาเลือก Business Location ก่อน</option>
                                  </select>
                              <?php } ?>

                            </div>
                          </div>
                    </div>

               </div>

      
                  <div class="row" >
                    <div class="col-md-6 " >
                       <div class="form-group row">
                            <label for="warehouse_id_fk" class="col-md-3 col-form-label"> คลัง : </label>
                            <div class="col-md-9">

                              <?php if(!empty(@$sRow->condition_warehouse)){ ?>
                                  <select class="form-control select2-templating " disabled="" >
                                      @if(@$Warehouse)
                                        @foreach(@$Warehouse AS $r)
                                          <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_warehouse)?'selected':'' }} >
                                            {{$r->w_name}}
                                          </option>
                                        @endforeach
                                      @endif
                                  </select>
                              <?php }else{ ?>
                                  <select id="warehouse_id_fk"  name="warehouse_id_fk" class="form-control select2-templating " required >
                                     <option disabled selected>กรุณาเลือกสาขาก่อน</option>
                                  </select>
                              <?php } ?>

                            </div>
                          </div>
                    </div>

                    <div class="col-md-6 " >
                        <div class="form-group row">
                            <label for="zone_id_fk" class="col-md-2 col-form-label"> Zone : </label>
                            <div class="col-md-10">

                              <?php if(!empty(@$sRow->condition_zone)){ ?>
                                  <select class="form-control select2-templating " disabled="" >
                                      @if(@$Zone)
                                        @foreach(@$Zone AS $r)
                                          <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_zone)?'selected':'' }} >
                                            {{$r->z_name}}
                                          </option>
                                        @endforeach
                                      @endif
                                  </select>
                              <?php }else{ ?>
                                  <select id="zone_id_fk"  name="zone_id_fk" class="form-control select2-templating "  >
                                     <option disabled selected>กรุณาเลือกคลังก่อน</option>
                                  </select>
                              <?php } ?>

                            </div>
                          </div>
                    </div>
                  </div>

                  <div class="row" >
                    <div class="col-md-6 " >
                       <div class="form-group row">
                            <label for="shelf_id_fk" class="col-md-3 col-form-label"> Shelf : </label>
                            <div class="col-md-9">

                              <?php if(!empty(@$sRow->condition_shelf)){ ?>
                                  <select class="form-control select2-templating " disabled="" >
                                      @if(@$Shelf)
                                        @foreach(@$Shelf AS $r)
                                          <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_shelf)?'selected':'' }} >
                                            {{$r->s_name}}
                                          </option>
                                        @endforeach
                                      @endif
                                  </select>
                              <?php }else{ ?>
                                  <select id="shelf_id_fk" name="shelf_id_fk" class="form-control select2-templating " >
                                    <option disabled selected>กรุณาเลือกโซนก่อน</option>
                                  </select>
                              <?php } ?>

                            </div>
                          </div>
                    </div>

                    <div class="col-md-6 " >
                        <div class="form-group row">
                            <label for="shelf_floor" class="col-md-2 col-form-label"> ชั้น : </label>
                            <div class="col-md-10">
                              <?php $dis02 = !empty(@$sRow->condition_shelf_floor)?'disabled':'' ?>
                              <select id="shelf_floor" name="shelf_floor" class="form-control select2-templating " <?=$dis02?> >
                                 <option value="">-select-</option>
                                 <option value="1" {{ (@$sRow->condition_shelf_floor==1)?'selected':'' }} >1</option>
                                 <option value="2" {{ (@$sRow->condition_shelf_floor==2)?'selected':'' }} >2</option>
                                 <option value="3" {{ (@$sRow->condition_shelf_floor==3)?'selected':'' }} >3</option>
                                 <option value="4" {{ (@$sRow->condition_shelf_floor==4)?'selected':'' }} >4</option>
                                 <option value="5" {{ (@$sRow->condition_shelf_floor==5)?'selected':'' }} >5</option>
                                 <option value="6" {{ (@$sRow->condition_shelf_floor==6)?'selected':'' }} >6</option>
                                 <option value="7" {{ (@$sRow->condition_shelf_floor==7)?'selected':'' }} >7</option>
                                 <option value="8" {{ (@$sRow->condition_shelf_floor==8)?'selected':'' }} >8</option>
                                 <option value="9" {{ (@$sRow->condition_shelf_floor==9)?'selected':'' }} >9</option>
                                 <option value="10" {{ (@$sRow->condition_shelf_floor==10)?'selected':'' }} >10</option>
                              </select>
                            </div>
                          </div>
                    </div>
                  </div>

         <div class="row" >

                 <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label"> สินค้า : </label>
                        <div class="col-md-9">
                           <?php $dis03 = !empty(@$sRow->condition_product)?'disabled':'' ?>
                           <select name="product" id="product" class="form-control select2-templating " <?=$dis03?> >
                                <option value="">-รหัสสินค้า : ชื่อสินค้า-</option>
                                   @if(@$Products)
                                        @foreach(@$Products AS $r)
                                          <option value="{{@$r->product_id}}" {{ (@$r->product_id==@$sRow->condition_product)?'selected':'' }} >
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
                        <label for="lot_number" class="col-md-2 col-form-label"> Lot-No. : </label>
                        <div class="col-md-10">
                             <?php $dis04 = !empty(@$sRow->condition_lot_number)?'disabled':'' ?>
                             <select name="lot_number" id="lot_number" class="form-control select2-templating " <?=$dis04?> >
                                <option value="">-Lot Number-</option>
                                   @if(@$Check_stock)
                                      @foreach(@$Check_stock AS $r)
                                        <option value="{{@$r->lot_number}}" {{ (@$r->lot_number==@$sRow->condition_lot_number)?'selected':'' }} >
                                          {{@$r->lot_number}}
                                        </option>
                                      @endforeach
                                    @endif
                              </select>
                        </div>
                      </div>
                    </div>
                    

                  </div>

                  @IF(empty(@$sRow))
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
                  @ENDIF 

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
          // url: '{{ route('backend.check_stock_account.datatable') }}',
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
            // {data: 'product_id_fk', title :'<center>รหัสสินค้า </center>', className: 'text-left'},
            {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left w230 '},
            {data: 'lot_number', title :'<center>ล็อตนัมเบอร์ </center>', className: 'text-left'},
            {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
            // {data: 'amt', title :'<center>จำนวน </center>', className: 'text-center'},
            {data: 'amt',
                 defaultContent: "0",   title :'<center>จำนวน</center>', className: 'text-center',render: function(d) {
                     return d;
            }},

            {data: 'warehouses', title :'<center>คลังสินค้า </center>', className: 'text-left'},
            {data: 'id', title :'STOCK CARD', className: 'text-center w100'},
        ],
        order: [[1, 'asc']],
        rowGroup: {
            startRender: null,
            endRender: function ( rows, group  ) {
                // var product_id_fk = data('product_id_fk') ;
                var sTotal = rows
                   .data()
                   .pluck('amt')
                   .reduce( function (a, b) {
                       return a + b*1;
                   }, 0);
                    sTotal = $.fn.dataTable.render.number(',', '.', 0, '<span>&#3647;</span> ').display( sTotal );
 
                return $('<tr>')
                    .append( '<td colspan="4" style="text-align:center;background-color:#f2f2f2 !important;font-weight: bold;">Total for '+group+'</td>' )
                    .append( '<td style=" background-color:#f2f2f2 !important;font-weight: bold; "><center>'+(sTotal)+'</td>' );
                    // .append( '<td style=" background-color:#f2f2f2 !important;font-weight: bold;color:#50a5f1;"><a class="btn btn-outline-success waves-effect waves-light" href="{{ url('backend/check_stock/stock_card') }}/'+rows+'" style="padding: initial;padding-left: inherit;padding-right: inherit;"> STOCK CARD </a> </td>' );
            },
            dataSrc: "product_name"
        },
           rowCallback: function(nRow, aData, dataIndex){

                if(sU!=''&&sD!=''){
                    $('td:last-child', nRow).html('-');
                }else{ 
                      $('td:last-child', nRow).html(''
                        + '<a class="btn btn-outline-success waves-effect waves-light" href="{{ url('backend/check_stock/stock_card') }}/'+aData['product_id_fk']+'/'+aData['lot_number']+'" style="padding: initial;padding-left: 2px;padding-right: 2px;"> STOCK CARD </a>  '
                        
                      ).addClass('input');
                }



           },
    });

});


</script>


  <script>


        $(document).ready(function() {
          
            $(document).on('click', '.btnSearch', function(event) {
                  event.preventDefault();

                  $("#spinner_frame").show();

                  var business_location_id_fk = $('#business_location_id_fk').val();
                  var branch_id_fk = $('#branch_id_fk').val();

                   if(business_location_id_fk==''){
                      $("#business_location_id_fk").select2('open');
                      $("#spinner_frame").hide();
                       return false;
                    }
                   if(branch_id_fk==''){
                      $("#branch_id_fk").select2('open');
                      $("#spinner_frame").hide();
                       return false;
                    }

                  var product = $('#product').val();
                  var lot_number = $('#lot_number').val();
                  var warehouse_id_fk = $('#warehouse_id_fk').val();
                  var zone_id_fk = $('#zone_id_fk').val();
                  var shelf_id_fk = $('#shelf_id_fk').val();
                  var shelf_floor = $('#shelf_floor').val();

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
                                          d.Where['business_location_id_fk'] = business_location_id_fk ;
                                          d.Where['product_id_fk'] = product ;
                                          d.Where['lot_number'] = lot_number ;
                                          d.Where['branch_id_fk'] = branch_id_fk ;
                                          d.Where['warehouse_id_fk'] = warehouse_id_fk ;
                                          d.Where['zone_id_fk'] = zone_id_fk ;
                                          d.Where['shelf_id_fk'] = shelf_id_fk ;
                                          d.Where['shelf_floor'] = shelf_floor ;
                                          oData = d;
                                          // $("#spinner_frame").hide();
                                        },
                                         method: 'POST',
                                       },
                                        columns: [
                                          {data: 'id', title :'ID', className: 'text-center w50'},
                                          // {data: 'product_id_fk', title :'<center>รหัสสินค้า </center>', className: 'text-left'},
                                          {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left w230 '},
                                          {data: 'lot_number', title :'<center>ล็อตนัมเบอร์ </center>', className: 'text-left'},
                                          {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
                                          // {data: 'amt', title :'<center>จำนวน </center>', className: 'text-center'},
                                          {data: 'amt',
                                               defaultContent: "0",   title :'<center>จำนวน</center>', className: 'text-center',render: function(d) {
                                                   return d;
                                          }},

                                          {data: 'warehouses', title :'<center>คลังสินค้า </center>', className: 'text-left'},
                                          {data: 'id', title :'STOCK CARD', className: 'text-center w100'},
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
                             
                                            return $('<tr>')
                                            .append( '<td colspan="4" style="text-align:center;background-color:#f2f2f2 !important;font-weight: bold;">Total for '+group+'</td>' )
                                            .append( '<td style=" background-color:#f2f2f2 !important;font-weight: bold; "><center>'+(sTotal)+'</td>' );
                                        },
                                        dataSrc: "product_name"
                                    },

                                     rowCallback: function(nRow, aData, dataIndex){
                                           // setTimeout(function(){
                                            $("#spinner_frame").hide();
                                          // },2000);

                                            if(sU!=''&&sD!=''){
                                                $('td:last-child', nRow).html('-');
                                            }else{ 
                                                  $('td:last-child', nRow).html(''
                                                    + '<a class="btn btn-outline-success waves-effect waves-light" href="{{ url('backend/check_stock/stock_card') }}/'+aData['product_id_fk']+'/'+aData['lot_number']+'" style="padding: initial;padding-left: 2px;padding-right: 2px;"> STOCK CARD </a>  '
                                                    
                                                  ).addClass('input');
                                            }

                                     },

                               
                                });

                            });


               
               
            });

        }); 
    </script>



<script type="text/javascript">


       $('#business_location_id_fk').change(function(){

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
                   }else{
                       var layout = '<option value="" selected>- เลือกสาขา -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.b_name+'</option>';
                       });
                       $('#branch_id_fk').html(layout);
                       $('#warehouse_id_fk').html('<option value="" selected>กรุณาเลือกสาขาก่อน</option>');
                       $('#zone_id_fk').html('<option value="" selected>กรุณาเลือกคลังก่อน</option>');
                       $('#shelf_id_fk').html('<option value="" selected>กรุณาเลือกโซนก่อน</option>');
                   }
                  }
                })
           }
 
      });


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
