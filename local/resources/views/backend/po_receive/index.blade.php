@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@section('css')
<style>
    .select2-selection {height: 34px !important;margin-left: 3px;}
    .border-left-0 {height: 67%;}

    .form-group {
        margin-bottom: 0rem  !important;
     }

    .btn-outline-secondary {
        margin-bottom: 36% !important;
    }
</style>

 <style>
        #notification {
            position: absolute;
            top: 150;
            margin-left: 60% ;
            /*margin-right: 5% ;*/
            /*float: right;*/
        }
        .toast-color {
            color: white;
            background-color: #33b5e5;
            border-radius: 5px;
        }
        h1 {
            color:green;
        }
    </style>

@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ใบ PO รับสินค้า </h4>
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
    <div class="col-12">
        <div class="card">
            <div class="card-body">

              <div class="row" >

                 <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="business_location_id_fk" class="col-md-3 col-form-label">Business Location</label>
                        <div class="col-md-9">
                         <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " required="" @if($sPermission !== 1) disabled @endif>
                              <option disabled selected value="">-Business Location-</option>
                              @if(@$sBusiness_location)
                                @foreach(@$sBusiness_location AS $r)
                                  <option value="{{@$r->id}}" {{ (@$r->id==(\Auth::user()->business_location_id_fk) && $sPermission !== 1)?'selected':'' }} >{{$r->txt_desc}}</option>
                                @endforeach
                              @endif
                            </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6 " >
                      <div class="form-group row">
                            <label for="branch_id_fk" class="col-md-3 col-form-label"> สาขาที่ดำเนินการ : </label>
                            <div class="col-md-9">

                              <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating " @if($sPermission !== 1) disabled @endif >
                                 <option disabled selected value="">กรุณาเลือก Business Location ก่อน</option>
                                 @if(@$sBranchs)
                                  @foreach(@$sBranchs AS $r)
                                    <option value="{{@$r->id}}" {{ (@$r->id==(\Auth::user()->branch_id_fk) && $sPermission !== 1)?'selected':'' }} >{{$r->b_name}}</option>
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
                    <label for="customer_id_fk" class="col-md-3 col-form-label"> Supplier : </label>
                    <div class="col-md-9">
                      <select name="supplier_id_fk" id="supplier_id_fk" class="form-control select2-templating " required >
                              <option value="">Select</option>
                                @if(@$Supplier)
                                  @foreach(@$Supplier AS $r)
                                    <option value="{{$r->id}}" {{ (@$r->id==@$sRow->supplier_id_fk)?'selected':'' }} >
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
                    <label for="po_status" class="col-md-3 col-form-label"> สถานะใบ PO :  </label>
                    <div class="col-md-9">
                      <select id="po_status" name="po_status" class="form-control select2-templating " >
                        <option value="">-Status-</option>
                        <option value="0" > อยู่ระหว่างการดำเนินการ </option>
                        <option value="1" > ได้รับสินค้าครบแล้ว </option>
                        <option value="2" > ยังค้างรับสินค้าจาก Supplier </option>
                        <option value="3" > ใบ PO ที่ยกเลิก </option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

            <div class="row" >
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="startDate" class="col-md-3 col-form-label"> วันที่สร้างใบ PO : </label>
                     <div class="col-md-9 d-flex">
                      <input id="startDate"  autocomplete="off" placeholder="Begin Date"  style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black" />
                      <input id="endDate"  autocomplete="off" placeholder="End Date"  style="border: 1px solid grey;font-weight: bold;color: black" />
                    </div>
                  </div>
                </div>
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="po_number" class="col-md-3 col-form-label"> รหัสใบ PO : </label>
                     <div class="col-md-9 ">
                      <select id="po_number" name="po_number" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                        @if(@$po_number)
                        @foreach(@$po_number AS $r)
                        <option value="{{$r->po_number}}" >
                          {{$r->po_number}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                </div>
              </div>


            <div class="row" style="margin-bottom: 2% !important;"  >
                <div class="col-md-6 " style="margin-top: -1% !important;" >
                  <div class="form-group row">
                    <label for="action_user" class="col-md-3 col-form-label"> ผู้สร้างใบ PO : </label>
                    <div class="col-md-9">
                     <select id="action_user" name="action_user" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                        @if(@$sAction_user)
                        @foreach(@$sAction_user AS $r)
                        <option value="{{$r->id}}" >
                           {{$r->name}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                </div>


                <div class="col-md-6 " style="margin-top: -0.5% !important;" >
                  <div class="form-group row">
                    <label for="branch_id_fk" class="col-md-3 col-form-label">  </label>
                     <div class="col-md-9" >
                     <a class="btn btn-info btn-sm btnSearch01 " href="#" style="font-size: 14px !important;margin-left: 0.8%;" >
                        <i class="bx bx-search align-middle "></i> SEARCH
                      </a>


                <!--     <a class="btn btn-info btn-sm float-right " style="{{@$sC}}" href="{{ route('backend.po_receive.create') }}">
                      <i class="bx bx-plus font-size-20 align-middle"></i>ADD สร้างใบรับสินค้าจาก PO
                    </a> -->


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
          url: '{{ route('backend.po_receive.datatable') }}',
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
            {data: 'branch', title :'<center>สาขา</center>', className: 'text-center'},
            {data: 'po_number', title :'<center>รหัสใบ PO </center>', className: 'text-center'},
            {data: 'supplier_name', title :'<center> Supplier </center>', className: 'text-center'},
            {data: 'action_user', title :'<center>ผู้สร้างใบ PO </center>', className: 'text-center'},
            {data: 'created_at', title :'<center>วันที่สร้างใบ PO </center>', className: 'text-center'},
            {data: 'po_status', title :'<center>สถานะใบ PO </center>', className: 'text-center'},
            {data: 'id', title :'Tools', className: 'text-center w80'},
        ],
        rowCallback: function(nRow, aData, dataIndex){
          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{

              $('td:last-child', nRow).html(''
                + '<a href="{{ route('backend.po_receive.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '

              ).addClass('input');

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
          // format: 'dd/mm/yyyy',
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
      });

      $('#endDate').datepicker({
          // format: 'dd/mm/yyyy',
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
          minDate: function () {
              return $('#startDate').val();
          }
      });

      $('#startDate').change(function(event) {

        if($('#endDate').val()>$(this).val()){
        }else{
          $('#endDate').val($(this).val());
        }
        $('#startPayDate').val('');
        $('#endPayDate').val('');
        $('#btnSearch03').val('0');

      });


      $('#endDate').change(function(event) {
        $('#btnSearch03').val('0');
      });


    </script>
    <script>

      $('#startPayDate').datepicker({
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
      });

      $('#endPayDate').datepicker({
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
          minDate: function () {
              return $('#startPayDate').val();
          }
      });

      $('#startPayDate').change(function(event) {

        if($('#endPayDate').val()>$(this).val()){
        }else{
          $('#endPayDate').val($(this).val());
        }

      });

    </script>


<script>

        $(document).ready(function() {

            $(document).on('click', '.btnSearch01', function(event) {
                  event.preventDefault();
                  $('#data-table').DataTable().clear();
                  $(".myloading").show();
                  var business_location_id_fk = $('#business_location_id_fk').val();
                  var branch_id_fk = $('#branch_id_fk').val();
                  var po_number = $('#po_number').val();
                  var startDate = $('#startDate').val();
                  var endDate = $('#endDate').val();
                  var action_user = $('#action_user').val();
                  var po_status = $('#po_status').val();
                  var supplier_id_fk = $('#supplier_id_fk').val();
                  console.log(po_status);
                  if(business_location_id_fk==''){
                    $('#business_location_id_fk').select2('open');
                    $(".myloading").hide();
                    return false;
                  }
                  // alert(branch_id_fk);
                  if(branch_id_fk=='' || branch_id_fk === null ){
                    $('#branch_id_fk').select2('open');
                    $(".myloading").hide();
                    return false;
                  }
                    // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                        var sU = "{{@$sU}}";
                        var sD = "{{@$sD}}";
                        var oTable;
                        $(function() {
                          $.fn.dataTable.ext.errMode = 'throw';
                             oTable = $('#data-table').DataTable({
                            "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                processing: true,
                                serverSide: true,
                                scroller: true,
                                destroy:true,
                                ordering: false,
                                ajax: {
                                          url: '{{ route('backend.po_receive.datatable') }}',
                                          data :{
                                            _token: '{{csrf_token()}}',
                                                business_location_id_fk:business_location_id_fk,
                                                branch_id_fk:branch_id_fk,
                                                po_number:po_number,
                                                startDate:startDate,
                                                endDate:endDate,
                                                action_user:action_user,
                                                po_status:po_status,
                                                supplier_id_fk:supplier_id_fk,
                                              },
                                            method: 'POST',
                                          },
                                       columns: [
                                      {data: 'id', title :'ID', className: 'text-center w50'},
                                      {data: 'branch', title :'<center>สาขา</center>', className: 'text-center'},
                                      {data: 'po_number', title :'<center>รหัสใบ PO </center>', className: 'text-center'},
                                      {data: 'supplier_name', title :'<center> Supplier </center>', className: 'text-center'},
                                      {data: 'action_user', title :'<center>ผู้สร้างใบ PO </center>', className: 'text-center'},
                                      {data: 'created_at', title :'<center>วันที่สร้างใบ PO </center>', className: 'text-center'},
                                      {data: 'po_status', title :'<center>สถานะใบ PO </center>', className: 'text-center'},
                                      {data: 'id', title :'Tools', className: 'text-center w80'},
                                  ],
                                  rowCallback: function(nRow, aData, dataIndex){
                                    if(sU!=''&&sD!=''){
                                        $('td:last-child', nRow).html('-');
                                    }else{

                                        $('td:last-child', nRow).html(''
                                          + '<a href="{{ route('backend.po_receive.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                                        ).addClass('input');

                                    }
                                  }
                              });
                        });

                    // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                setTimeout(function(){
                   $(".myloading").hide();
                }, 1500);


            });
          });

    </script>


<script type="text/javascript">

       $('#business_location_id_fk').change(function(){

        $('.myloading').show();

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
                   }
                   $('.myloading').hide();
                  }
                })
           }

      });



</script>
@endsection

