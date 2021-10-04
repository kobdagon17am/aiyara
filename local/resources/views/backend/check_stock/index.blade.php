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
            <h4 class="mb-0 font-size-18">

             <a class="btn btn-info btn-sm btnStockMovement " href="#" style="font-size: 14px !important;display: none;" >
                            <i class="bx bx-cog align-middle "></i> Process Stock movement
                          </a>

                          </h4>

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


                              <?php if(@\Auth::user()->permission==1){ ?>

                                      <?php $dis01 = !empty(@$sRow->condition_business_location)?'disabled':'' ?>
                                       <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " required="" <?=$dis01?> >
                                            <option value="">-Business Location-</option>
                                            @if(@$sBusiness_location)
                                              @foreach(@$sBusiness_location AS $r)
                                              @IF(empty(@$sRow->condition_business_location))
                                              <option value="{{$r->id}}" {{ (@$r->id=='1')?'selected':'' }} >
                                                {{$r->txt_desc}}
                                              </option>
                                              @ELSE
                                              <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_business_location)?'selected':'' }} >
                                                {{$r->txt_desc}}
                                              </option>
                                              @ENDIF
                                              @endforeach
                                            @endif
                                          </select>

                              <?php }else{ ?>

                                       <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " disabled >
                                        <option value="">-Business Location-</option>
                                        @if(@$sBusiness_location)
                                          @foreach(@$sBusiness_location AS $r)
                                          @IF(empty(@$sRow->condition_business_location))
                                          <option value="{{$r->id}}" {{ (@$r->id=='1')?'selected':'' }} >
                                            {{$r->txt_desc}}
                                          </option>
                                          @ELSE
                                          <option value="{{$r->id}}" {{ (@$r->id==@$sRow->condition_business_location)?'selected':'' }} >
                                            {{$r->txt_desc}}
                                          </option>
                                          @ENDIF
                                          @endforeach
                                        @endif
                                      </select>

                              <?php } ?>





                        </div>
                      </div>
                    </div>

                    <div class="col-md-6 " >
                      <div class="form-group row">
                            <label for="branch_id_fk" class="col-md-2 col-form-label"> สาขา : </label>
                            <div class="col-md-10">

                              <?php if(@\Auth::user()->permission==1){ ?>
                                  <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating "  >
                                      @if(@$sBranchs)
                                        @foreach(@$sBranchs AS $r)
                                          <option value="{{$r->id}}" >
                                            {{$r->b_name}}
                                          </option>
                                        @endforeach
                                      @endif
                                  </select>
                              <?php }else{ ?>
                                  <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating " disabled="" >
                                     <option value="" selected>กรุณาเลือก Business Location ก่อน</option>
                                     @if(@$sBranchs)
                                        @foreach(@$sBranchs AS $r)
                                          <option value="{{$r->id}}" {{ (@$r->id==(\Auth::user()->branch_id_fk))?'selected':'' }} >
                                            {{$r->b_name}}
                                          </option>
                                        @endforeach
                                      @endif
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

                              <?php if(@\Auth::user()->permission==1){ ?>

                                 <select id="warehouse_id_fk"  name="warehouse_id_fk" class="form-control select2-templating " required >
                                     <option disabled selected>กรุณาเลือกสาขาก่อน</option>
                                  </select>

                              <?php }else{ ?>

                                  <select id="warehouse_id_fk"  name="warehouse_id_fk" class="form-control select2-templating " required >
                                      <option value="" >-select-</option>
                                      @if(@$Warehouse)
                                        @foreach(@$Warehouse AS $r)
                                          <option value="{{$r->id}}"  >
                                            {{$r->w_name}}
                                          </option>
                                        @endforeach
                                      @endif
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


      <div class="row" >
                    <div class="col-md-6 " >
                       <div class="form-group row">
                            <label for="ref_code" class="col-md-3 col-form-label">Lot expired date : </label>
                            <div class="col-md-9 d-flex">
                              <?php
                                $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
                                $last_day_this_month  = date('Y-m-t');
                               ?>
                               <input id="start_date"  autocomplete="off" placeholder="Begin" value="<?=$first_day_this_month?>" style="border: 1px solid grey;"  />
                               <input id="end_date"  autocomplete="off" placeholder="End" value="<?=$last_day_this_month?>" style="border: 1px solid grey;"  />

                            </div>
                          </div>
                    </div>

                    <div class="col-md-6 " >
                        <div class="form-group row">
                          <label for="ref_code" class="col-md-2 col-form-label">  </label>
                            <div class="col-md-10">
                          <a class="btn btn-info btn-sm btnSearch " href="#" style="font-size: 14px !important;" >
                            <i class="bx bx-search align-middle "></i> SEARCH
                          </a>
                         </div>
                       </div>
                    </div>

                  </div>

                  @IF(empty(@$sRow))
                  <div class="row" >
                    <div class="col-md-12" >
                       <div class="form-group row">

                        </div>
                    </div>
                  </div>
                  @ENDIF

              </div>


            </div>
          </div>
        </div>


                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;"></table>


              <div class="myBorder" style="margin-top: 2%;">

                <div style="">
                  <div class="form-group row">
                    <div class="col-md-12">
                      <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการสินค้าที่อยู่ระหว่างการโอนระหว่างสาขา </span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12">
                      <!-- ตารางนี้ถ้ามีการโอนค่อยแสดง -->
                      <table id="data-table-02" class="table table-bordered dt-responsive" style="width: 100%;">
                      </table>
                    </div>
                  </div>
                </div>
              </div>


            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->



@endsection

@section('script')

<script src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js" type="text/javascript" charset="utf-8" async defer></script>

  <script>

        $(document).ready(function() {

            $(document).on('click', '.btnSearch', function(event) {
                  event.preventDefault();

                  $("#spinner_frame").show();

                  // return false;

                  var business_location_id_fk = $('#business_location_id_fk').val();
                  var branch_id_fk = $('#branch_id_fk').val();
                  var start_date = $('#start_date').val();
                  var end_date = $('#end_date').val();

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

                  if(start_date==''){
                      $("#start_date").focus();
                      $("#spinner_frame").hide();
                       return false;
                    }

                    if(end_date==''){
                      $("#end_date").focus();
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
                                      destroy:true,
                                      stateSave:true,
                                      iDisplayLength: 15,
                                      "searching": false,
                                      ajax: {
                                      url: '{{ route('backend.check_stock.datatable') }}',
                                      data: function ( d ) {
                                          d.myWhereStock={};
                                          // d.myWhereStock['business_location_id_fk'] = business_location_id_fk ;
                                          // d.myWhereStock['product_id_fk'] = product ;
                                          // d.myWhereStock['lot_number'] = lot_number ;
                                          // d.myWhereStock['branch_id_fk'] = branch_id_fk ;
                                          // d.myWhereStock['warehouse_id_fk'] = warehouse_id_fk ;
                                          // d.myWhereStock['zone_id_fk'] = zone_id_fk ;
                                          // d.myWhereStock['shelf_id_fk'] = shelf_id_fk ;
                                          // d.myWhereStock['shelf_floor'] = shelf_floor ;
                                          // d.myWhereStock['lot_expired_date'] = start_date+":"+end_date ;
                                          oData = d;
                                        },
                                         method: 'POST',
                                       },
                                        // dom: 'frtipB',
                                        dom: 'Bfrtip',
                                        buttons: [
                                            {
                                                extend: 'excelHtml5',
                                                title: 'CHECK STOCK'
                                            },

                                        ],
                                       columns: [
                                              {data: 'id', title :'ID', className: 'text-center w50'},
                                              {data: 'product_name', title :'<center>รหัสสินค้า : ชื่อสินค้า </center>', className: 'text-left w230 '},
                                              {data: 'lot_number', title :'<center>ล็อตนัมเบอร์ </center>', className: 'text-left'},
                                              {data: 'lot_expired_date', title :'<center>วันหมดอายุ </center>', className: 'text-center'},
                                              {data: 'amt',defaultContent: "0",   title :'<center>จำนวน</center>', className: 'text-center',render: function(d) {
                                                       return d;
                                              }},
                                              {data: 'warehouses', title :'<center>คลังสินค้า </center>', className: 'text-left'},
                                              {data: 'stock_card', title :'STOCK CARD', className: 'text-center w150'},
                                          ],
                                          // order: [[1, 'asc']],
                                          // columnDefs: [
                                          //               { "visible": false, "targets": 6 }
                                          //           ],
                                          rowGroup: {
                                          startRender: null,
                                          endRender: function ( rows, group  ) {
                                              var sTotal = rows
                                                 .data()
                                                 .pluck('amt')
                                                 .reduce( function (a, b) {
                                                     return a + b*1;
                                                     // return a + b;
                                                 }, 0);
                                                  sTotal = $.fn.dataTable.render.number(',', '.', 0, '  ').display( sTotal );

                                              var product_id_fk = rows.data().pluck('product_id_fk').toArray();
                                              var product_id_fk = product_id_fk[0] ;

                                              var lot_number = rows.data().pluck('lot_number').toArray();
                                              var lot_number = lot_number[0];

                                              if ( group==lot_number ) {

                                                  return $('<tr>')
                                                  .append( '<td colspan="4" style="text-align:right;background-color:#f2f2f2 !important;">Total > '+group+'</td>' )
                                                  .append( '<td style=" background-color:#f2f2f2 !important;font-weight: bold; "><center>'+(sTotal)+'</td>' )
                                                  .append( '<td></td><td style=" background-color:#f2f2f2 !important;font-weight: bold;text-align:center; "><a class="btn btn-outline-warning waves-effect waves-light" href="{{ url('backend/check_stock/stock_card') }}/'+product_id_fk+'/'+lot_number+'/'+start_date+':'+end_date+'/'+sTotal.trim()+'" style="padding: initial;padding-left: 2px;padding-right: 2px;color:black;" target=_blank > STOCK CARD </a> </td>' );


                                              }else{
                                                   return $('<tr>')
                                                  .append( '<td colspan="4" style="text-align:right;background-color:#e6e6e6 !important;font-weight: bold;">Total for '+group+'</td>' )
                                                  .append( '<td style=" background-color:#e6e6e6 !important;font-weight: bold; "><center>'+(sTotal)+'</td>' )
                                                  .append( '<td style=" background-color:#e6e6e6 !important;font-weight: bold; "></td>' );
                                              }


                                          },
                                          dataSrc: [  "product_name", "lot_number" ]
                                      },

                                         rowCallback: function(nRow, aData, dataIndex){

                                           $('td:last-child', nRow).html(''
                                                      + '<a class="btn btn-outline-success waves-effect waves-light" href="{{ url('backend/check_stock/stock_card_01') }}/'+aData['product_id_fk']+'/'+aData['lot_number']+'/'+start_date+':'+end_date+'/'+aData['amt']+'" style="padding: initial;padding-left: 2px;padding-right: 2px;color:black;" target=_blank > STOCK CARD </a>  '

                                                    ).addClass('input');

                                         },


                                  });


                            });


                  setTimeout(function(){
                    $("#spinner_frame").hide();
                  },2000);

            });

        });
    </script>


  <script>
            var oTable02;
            $(function() {
                oTable02 = $('#data-table-02').DataTable({
                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                    processing: true,
                    serverSide: true,
                    scroller: true,
                    scrollCollapse: true,
                    scrollX: true,
                    ordering: false,
                    scrollY: ''+($(window).height()-370)+'px',
                    iDisplayLength: 5,
                    ajax: {
                        url: '{{ route('backend.transfer_branch_get_products_03.datatable') }}',
                        // data :{
                              // branch_id_fk:branch_id_fk,
                        //     },
                          method: 'POST',
                        },
                    columns: [
                        {data: 'id', title :'No.', className: 'text-center w50'},
                        {data: 'tr_number', title :'รหัสใบโอน', className: 'text-center'},
                        {data: 'product_name', title :'<center>รายการสินค้า', className: 'text-left'},
                        {data: 'branch_from', title :'สาขาต้นทาง', className: 'text-center'},
                        {data: 'branch_to', title :'สาขาปลายทาง', className: 'text-center'},
                        {data: 'tr_status_from', title :'ฝั่งส่ง', className: 'text-center'},
                        {data: 'tr_status_to', title :'ฝั่งรับ', className: 'text-center'},
                        {data: 'updated_at', title :'วัน-เวลา<br>ดำเนินการล่าสุด', className: 'text-center'},

                    ],
                    rowCallback: function(nRow, aData, dataIndex){

                    }
                });

                    oTable02.on( 'draw', function () {
                    $('[data-toggle="tooltip"]').tooltip();
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

          $("#spinner_frame").show();

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

                   $("#spinner_frame").hide();

                  }
                })
           }

      });


       $('#warehouse_id_fk').change(function(){

        $("#spinner_frame").show();

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

                   $("#spinner_frame").hide();

                  }
                })
           }

      });


       $('#zone_id_fk').change(function(){

          $("#spinner_frame").show();

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
                   $("#spinner_frame").hide();

                  }
                })
           }

      });


       $('#product').change(function(){

          var product_id_fk = this.value;
          // alert(zone_id_fk);

           if(product_id_fk != ''){
             $.ajax({
                   url: " {{ url('backend/ajaxGetLotnumber') }} ",
                  method: "post",
                  data: {
                    product_id_fk:product_id_fk,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {
                   if(data == ''){
                       alert('ไม่พบข้อมูล Lot number !!.');
                       var layout = '<option value="" selected>- เลือก Lot number -</option>';
                       $('#lot_number').html(layout);
                   }else{
                       var layout = '<option value="" selected>- เลือก Lot number -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.lot_number+'>'+value.lot_number+'</option>';
                       });
                       $('#lot_number').html(layout);
                   }
                  }
                })
           }

      });


</script>


    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#start_date').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            // minDate: today,
            // maxDate: function () {
            //     return $('#end_date').val();
            // }
        });
        $('#end_date').datepicker({
            // format: 'dd/mm/yyyy',
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                return $('#start_date').val();
            }
        });

         $('#start_date').change(function(event) {
           $('#end_date').val($(this).val());
         });

  </script>


<script>
  /** It is done after the page load is complete . **/
  $(document).ready(function(){

    setTimeout(function(){
       $('.btnStockMovement').trigger('click');
    },1500);

    setTimeout(function(){
       $('.btnSearch').trigger('click');
    },1500);

  });
</script>


  <script>

        $(document).ready(function() {

            $(document).on('click', '.btnStockMovement', function(event) {
                  event.preventDefault();

                  $("#spinner_frame").show();


                   $.ajax({
                       url: " {{ url('backend/truncateStockMovement') }} ",
                      method: "post",
                      data: {
                        "_token": "{{ csrf_token() }}",
                      },
                      success:function(data)
                      {
// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                            setTimeout(function(){

                                 $.ajax({
                                     url: " {{ url('backend/insertStockMovement_From_db_general_receive') }} ",
                                    method: "post",
                                    data: {
                                      "_token": "{{ csrf_token() }}",
                                    },
                                    success:function(data)
                                    {

                                      console.log(data);
                                      $("#spinner_frame").hide();

                                    }
                                  });


                            },1000);

                          setTimeout(function(){

                               $.ajax({
                                   url: " {{ url('backend/insertStockMovement_From_db_general_takeout') }} ",
                                  method: "post",
                                  data: {
                                    "_token": "{{ csrf_token() }}",
                                  },
                                  success:function(data)
                                  {

                                    console.log(data);
                                    $("#spinner_frame").hide();

                                  }
                                });


                          },1000);


                          setTimeout(function(){

                               $.ajax({
                                   url: " {{ url('backend/insertStockMovement_From_db_stocks_account') }} ",
                                  method: "post",
                                  data: {
                                    "_token": "{{ csrf_token() }}",
                                  },
                                  success:function(data)
                                  {

                                    console.log(data);
                                    $("#spinner_frame").hide();

                                  }
                                });


                          },1000);

                           setTimeout(function(){

                               $.ajax({
                                   url: " {{ url('backend/insertStockMovement_From_db_products_borrow_code') }} ",
                                  method: "post",
                                  data: {
                                    "_token": "{{ csrf_token() }}",
                                  },
                                  success:function(data)
                                  {

                                    console.log(data);
                                    $("#spinner_frame").hide();

                                  }
                                });


                          },1000);

                           setTimeout(function(){

                               $.ajax({
                                   url: " {{ url('backend/insertStockMovement_From_db_transfer_warehouses_code') }} ",
                                  method: "post",
                                  data: {
                                    "_token": "{{ csrf_token() }}",
                                  },
                                  success:function(data)
                                  {

                                    console.log(data);
                                    $("#spinner_frame").hide();

                                  }
                                });


                          },1000);

                           setTimeout(function(){

                               $.ajax({
                                   url: " {{ url('backend/insertStockMovement_From_db_transfer_branch_code') }} ",
                                  method: "post",
                                  data: {
                                    "_token": "{{ csrf_token() }}",
                                  },
                                  success:function(data)
                                  {

                                    console.log(data);
                                    $("#spinner_frame").hide();

                                  }
                                });


                          },1000);


                          setTimeout(function(){

                               $.ajax({
                                   url: " {{ url('backend/insertStockMovement_From_db_pay_product_receipt_001') }} ",
                                  method: "post",
                                  data: {
                                    "_token": "{{ csrf_token() }}",
                                  },
                                  success:function(data)
                                  {

                                    console.log(data);
                                    $("#spinner_frame").hide();

                                  }
                                });

                          },1000);


                      setTimeout(function(){

                           $.ajax({
                               url: " {{ url('backend/insertStockMovement_From_db_stocks_return') }} ",
                              method: "post",
                              data: {
                                "_token": "{{ csrf_token() }}",
                              },
                              success:function(data)
                              {

                                console.log(data);
                                $("#spinner_frame").hide();

                              }
                            });

                      },1000);

        // จ่ายสินค้าตามใบเบิก
                      setTimeout(function(){

                           $.ajax({
                               url: " {{ url('backend/insertStockMovement_From_db_pay_requisition_001') }} ",
                              method: "post",
                              data: {
                                "_token": "{{ csrf_token() }}",
                              },
                              success:function(data)
                              {

                                console.log(data);
                                $("#spinner_frame").hide();

                              }
                            });

                      },1000);

                      setTimeout(function(){

                           $.ajax({
                               url: " {{ url('backend/insertStockMovement_Final') }} ",
                              method: "post",
                              data: {
                                "_token": "{{ csrf_token() }}",
                              },
                              success:function(data)
                              {

                                console.log(data);
                                $("#spinner_frame").hide();

                              }
                            });

                      },1000);

// %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
                      }
                    });








             });

        });
    </script>

@endsection
