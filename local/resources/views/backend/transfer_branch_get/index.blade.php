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
            <h4 class="mb-0 font-size-18"> รับสินค้าจากการโอนระหว่างสาขา </h4>


              <!--     <div class="col-4 text-right" >
                    <a class="btn btn-info btn-sm mt-1  font-size-16 " href="{{ route('backend.transfer_branch_get.create') }}">
                      <i class="bx bx-plus font-size-20 align-middle mr-1"></i> สร้างใบรับสินค้าจากการโอน
                    </a>
                  </div> -->

        </div>
    </div>
</div>
<!-- end page title -->

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
                         <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " required="" >
                              <option value="">-Business Location-</option>
                              @if(@$sBusiness_location)
                                @foreach(@$sBusiness_location AS $r)
                                  <option value="{{@$r->id}}" {{ (@$r->id==(\Auth::user()->business_location_id_fk))?'selected':'' }} >{{$r->txt_desc}}</option>
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

                              <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating "  >
                                 <option disabled selected value="">กรุณาเลือก Business Location ก่อน</option>
                                 @if(@$sBranchs)
                                  @foreach(@$sBranchs AS $r)
                                   @if($sPermission==1)
                                    @if($r->business_location_id_fk==(\Auth::user()->business_location_id_fk))
                                    <option value="{{@$r->id}}" {{ (@$r->id==(\Auth::user()->branch_id_fk))?'selected':'' }} >{{$r->b_name}}</option>
                                    @endif
                                    @else
                                     @if($r->business_location_id_fk==(\Auth::user()->business_location_id_fk))
                                    <option value="{{@$r->id}}" {{ (@$r->id==(\Auth::user()->branch_id_fk))?'selected':'' }} >{{$r->b_name}}</option>
                                    @endif
                                    @endif
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
                    <label for="get_from_branch_id_fk" class="col-md-3 col-form-label"> รับมาจากสาขา : </label>
                    <div class="col-md-9">
                      <select name="get_from_branch_id_fk" id="get_from_branch_id_fk" class="form-control select2-templating " required >
                              <option value="">-Select-</option>
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
                </div>

                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="tr_status_get" class="col-md-3 col-form-label"> สถานะใบโอน :  </label>
                    <div class="col-md-9">
                      <select id="tr_status_get" name="tr_status_get" class="form-control select2-templating " >
                         <option value="">-Select-</option>
                                 @if(@$Transfer_branch_status)
                                  @foreach(@$Transfer_branch_status AS $r)
                                    <option value="{{$r->id}}"  >
                                      {{$r->txt_to}}
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
                    <label for="startDate" class="col-md-3 col-form-label"> ช่วงวันที่สร้างใบโอน : </label>
                     <div class="col-md-9 d-flex">
                      <input id="startDate"  autocomplete="off" placeholder="Begin Date"  style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black" />
                      <input id="endDate"  autocomplete="off" placeholder="End Date"  style="border: 1px solid grey;font-weight: bold;color: black" />
                    </div>
                  </div>
                </div>
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="tr_number" class="col-md-3 col-form-label"> รหัสใบโอน : </label>
                     <div class="col-md-9 ">
                      <select id="tr_number" name="tr_number" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                        @if(@$tr_number)
                        @foreach(@$tr_number AS $r)
                        <option value="{{$r->tr_number}}" >
                          {{$r->tr_number}}
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
                    <label for="action_user" class="col-md-3 col-form-label"> ผู้รับใบโอน : </label>
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


                <!--     <a class="btn btn-info btn-sm float-right " style="{{@$sC}}" href="{{ route('backend.transfer_branch_get.create') }}">
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
          url: '{{ route('backend.transfer_branch_get.datatable') }}',
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
            {data: 'tr_number', title :'<center>รหัสใบโอน </center>', className: 'text-center'},
            {data: 'get_from_branch', title :'<center>รับจากสาขา</center>', className: 'text-center'},
            {data: 'action_user', title :'<center>ผู้รับใบโอน </center>', className: 'text-center'},
            {data: 'created_at', title :'<center>วันที่สร้างใบโอน </center>', className: 'text-center'},
            {data: 'approver', title :'<center>ผู้อนุมัติ </center>', className: 'text-center'},
            {data: 'approve_date', title :'<center>วันที่อนุมัติ </center>', className: 'text-center'},
            {data: 'tr_status_get', title :'<center>สถานะใบโอน </center>', className: 'text-center'},
            {data: 'id', title :'Tools', className: 'text-center w80'},
        ],
        rowCallback: function(nRow, aData, dataIndex){

          // console.log(aData['tr_status_get']);
          // console.log(aData['tr_status_code']);

              var sPermission = "<?=\Auth::user()->permission?>";
              var sU = sessionStorage.getItem("sU");
              if(sPermission==1){
                sU = 1;
              }
              var str_U = '';
              if(sU=='1'){

                // กรณีปฏิเสธการรับจากฝั่งรับ
                if(aData['tr_status_code']=='5'){
                  str_U = '<a href="{{ URL('backend/transfer_branch_get/noget') }}/'+aData['id']+'" class="btn btn-sm btn-primary"  ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                }else{
                  str_U = '<a href="{{ route('backend.transfer_branch_get.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                }

              }

              if(sU!='1'){
                 $('td:last-child', nRow).html('-');
              }else{
                $('td:last-child', nRow).html( str_U ).addClass('input');
              }


        }
    });

});
</script>


<script>

        $(document).ready(function() {

            $(document).on('click', '.btnSearch01', function(event) {
                  event.preventDefault();

                  $(".myloading").show();

                  var business_location_id_fk = $('#business_location_id_fk').val();
                  var branch_id_fk = $('#branch_id_fk').val();
                  var get_from_branch_id_fk = $('#get_from_branch_id_fk').val();
                  var tr_number = $('#tr_number').val();
                  var startDate = $('#startDate').val();
                  var endDate = $('#endDate').val();
                  var action_user = $('#action_user').val();
                  var tr_status_get = $('#tr_status_get').val();


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
                                          url: '{{ route('backend.transfer_branch_get.datatable') }}',
                                          data :{
                                            _token: '{{csrf_token()}}',
                                                business_location_id_fk:business_location_id_fk,
                                                branch_id_fk:branch_id_fk,
                                                get_from_branch_id_fk:get_from_branch_id_fk,
                                                tr_number:tr_number,
                                                startDate:startDate,
                                                endDate:endDate,
                                                action_user:action_user,
                                                tr_status_get:tr_status_get,
                                              },
                                            method: 'POST',
                                          },
                                     columns: [
                                          {data: 'id', title :'ID', className: 'text-center w50'},
                                          {data: 'tr_number', title :'<center>รหัสใบโอน </center>', className: 'text-center'},
                                          {data: 'get_from_branch', title :'<center>รับจากสาขา</center>', className: 'text-center'},
                                          {data: 'action_user', title :'<center>ผู้รับใบโอน </center>', className: 'text-center'},
                                          {data: 'created_at', title :'<center>วันที่สร้างใบโอน </center>', className: 'text-center'},
                                          {data: 'approver', title :'<center>ผู้อนุมัติ </center>', className: 'text-center'},
                                          {data: 'approve_date', title :'<center>วันที่อนุมัติ </center>', className: 'text-center'},
                                          {data: 'tr_status_get', title :'<center>สถานะใบโอน </center>', className: 'text-center'},
                                          {data: 'id', title :'Tools', className: 'text-center w80'},
                                      ],
                                      rowCallback: function(nRow, aData, dataIndex){

                                        console.log(aData['tr_status_get']);
                                        console.log(aData['tr_status_code']);

                                            var sPermission = "<?=\Auth::user()->permission?>";
                                            var sU = sessionStorage.getItem("sU");
                                            if(sPermission==1){
                                              sU = 1;
                                            }
                                            var str_U = '';
                                            if(sU=='1'){
                                              // console.log(aData['tr_status_code']);
                                              // กรณีปฏิเสธการรับจากฝั่งรับ
                                              if(aData['tr_status_code']==5){
                                                str_U = '<a href="{{ URL('backend/transfer_branch_get/noget') }}/'+aData['id']+'" class="btn btn-sm btn-primary"  ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                                              }else{
                                                str_U = '<a href="{{ route('backend.transfer_branch_get.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  ><i class="bx bx-edit font-size-16 align-middle"></i></a> ';
                                              }

                                            }

                                            if(sU!='1'){
                                               $('td:last-child', nRow).html('-');
                                            }else{
                                              $('td:last-child', nRow).html( str_U ).addClass('input');
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

