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

        .column {
            float: left;
            width: 50%;
            padding: 10px;
            height: 200px; /* Should be removed. Only for demonstration */
          }

          /* Clear floats after the columns */
          .row:after {
            content: "";
            display: table;
            clear: both;
          }

          /* DivTable.com */
.divTable{
  display: table;
  width: 100%;
}
.divTableRow {
  display: table-row;
}
.divTableHeading {
  background-color: #EEE;
  display: table-header-group;
}
.divTableCell, .divTableHead {
  /*border: 1px solid #999999;*/
  display: table-cell;
  padding: 3px 10px;
}
.divTableHeading {
  background-color: #EEE;
  display: table-header-group;
  font-weight: bold;
}
.divTableFoot {
  background-color: #EEE;
  display: table-footer-group;
  font-weight: bold;
}
.divTableBody {
  display: table-row-group;
}

    </style>

@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> {{ __('message.member_service_system') }} </h4>

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

<div class="row" >
    <div class="col-12">
        <div class="card">
            <div class="card-body">


    <div class="myBorder">

              <div class="row" >
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="customer_id" class="col-md-3 col-form-label"> {{ __('message.info_member') }} : </label>
                    <div class="col-md-9">
                       <select id="customer_id" name="customer_id" class="form-control" ></select>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="business_name" class="col-md-3 col-form-label"> {{ __('message.username') }} :  </label>
                    <div class="col-md-9">
                       <select id="business_name" name="business_name" class="form-control" ></select>
                    </div>
                  </div>
                </div>
              </div>

            <div class="row" >
                <div class="col-md-6 " >
                  <div class="form-group row">
                     <div class="col-md-9 d-flex">

                    </div>
                  </div>
                </div>
                <div class="col-md-6 " >
                  <div class="form-group row">
                     <div class="col-md-9 ">

                    </div>
                  </div>
                </div>
              </div>

            <div class="row" style="margin-bottom: 1% !important;"  >
                 <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="introduce_id" class="col-md-3 col-form-label"> {{ __('message.counselor_id') }} : </label>
                    <div class="col-md-9">
                       <select id="introduce_id" name="introduce_id" class="form-control" ></select>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 " style="" >
                  <div class="form-group row">
                    <label for="" class="col-md-3 col-form-label">  </label>
                     <div class="col-md-9" >
                     <a class="btn btn-info btn-sm btnSearch01 " href="#" style="font-size: 14px !important;margin-left: 0.8%;margin-top: 1%;" >
                        <i class="bx bx-search align-middle "></i> SEARCH
                      </a>

                    </div>
                  </div>
                </div>
              </div>

                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>

</div>

                <b style="font-size: 14px;">{{ __('message.remark') }}</b>
                <div class="divTable">
                  <div class="divTableBody">
                    <div class="divTableRow">
                      <?php
                      foreach ($filetype as $key => $value) {
                      echo '<div class="divTableCell">'.$value->icon.' : '.__('message.file_types.'.$value->id).' </div>';
                      }
                      ?>
                    </div>
                  </div>
                </div>

                <div class="" style="
                  margin-left: 1%;
                  color: black;
                  ">
                  {!! __('message.member_pv_status_info') !!}
                </div>


            </div>  <!-- end card-body -->
        </div>  <!-- end card -->
    </div> <!-- end col-12 -->
</div> <!-- end row -->




@endsection

@section('script')

<script>
var sU = "{{@$sU}}"; //alert(sU);
var sD = "{{@$sD}}"; //alert(sD);
var oTable;
$(function() {
    oTable = $('#data-table').DataTable({
    "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        scroller: true,
        destroy: true,
        ordering: false,
        iDisplayLength: 15,
        ajax: {
          url: '{{ route('backend.member_pv.datatable') }}',
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
            {data: 'customer_name', title :'<center>{{ __("message.info_member") }}</center>', className: 'text-left w250 '},
            {data: 'business_name', title :'<center> {{ __("message.username") }} </center>', className: 'text-left w100'},
            {data: 'aistockist_status', title :'<center> {{ __("message.status_ai_stockist") }} </center>', className: 'text-center w80 '},
            {data: 'qualification', title :'<center> {{ __("message.qualification") }} </center>', className: 'text-center w100'},
            {data: 'package', title :'<center> {{ __("message.package") }} </center>', className: 'text-center'},
            {data: 'pv', title :'<center> {{ __("message.personal_score") }} </center>', className: 'text-center  '},
            {data: 'introduce_id', title :'<center> {{ __("message.counselor_id") }} </center>', className: 'text-center  '},
            {data: 'regis_status', title :'<center> {!! __("message.status_apply") !!} </center>', className: 'text-center '},
            {data: 'regis_date_doc', title :'<center> {{ __("message.date_approve") }} </center>', className: 'text-center'},
            {data: 'id', title :'{{ __("message.profile") }}', className: 'text-center w80'},
        ],
        rowCallback: function(nRow, aData, dataIndex){
          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{
            // console.log(aData['customer_id']+" : "+aData['type']+" : "+aData['regis_status_02']+" : "+aData['item_checked']);
               $('td:last-child', nRow).html(''
                  + '<a class="btn btn-sm btn-info " href='+aData['routes_user']+' target="_blank" class="btn btn-primary"><i class="bx bx-file-find font-size-16 align-middle"></i> </a>'
                ).addClass('input');
          }
        }
    });
     oTable.on( 'draw', function () {
      $('[data-toggle="tooltip"]').tooltip();
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

      $('#startDate02').datepicker({
          // format: 'dd/mm/yyyy',
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
      });

      $('#endDate02').datepicker({
          // format: 'dd/mm/yyyy',
          format: 'yyyy-mm-dd',
          uiLibrary: 'bootstrap4',
          iconsLibrary: 'fontawesome',
          minDate: function () {
              return $('#startDate').val();
          }
      });

      $('#startDate02').change(function(event) {

        if($('#endDate02').val()>$(this).val()){
        }else{
          $('#endDate02').val($(this).val());
        }
        $('#startPayDate02').val('');
        $('#endPayDate02').val('');

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

                  var customer_id = $('#customer_id').val();
                  var business_name = $('#business_name').val();
                  var introduce_id = $('#introduce_id').val();

                  console.log(customer_id);
                  console.log(business_name);
                  console.log(introduce_id);

                    // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@
                            var oTable;
                            $(function() {
                                oTable = $('#data-table').DataTable({
                                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                    processing: true,
                                    serverSide: true,
                                    scroller: true,
                                    destroy:true,
                                    ordering: false,
                                    searching: false,
                                    ajax: {
                                          url: '{{ route('backend.member_pv.datatable') }}',
                                          data :{
                                            _token: '{{csrf_token()}}',
                                                customer_id:customer_id,
                                                business_name:business_name,
                                                introduce_id:introduce_id,
                                              },
                                            method: 'POST',
                                          },
                                          dom: 'Bfrtip',
                                          buttons: [
                                            {
                                              extend: 'excelHtml5',
                                              title: 'ข้อมูลงานบริการสมาชิก'
                                            },
                                          ],
                                          columns: [
                                            {data: 'id', title :'ID', className: 'text-center w50'},
                                            {data: 'customer_name', title :'<center>รหัส : ชื่อสมาชิก </center>', className: 'text-left w250 '},
                                            {data: 'business_name', title :'<center> นามแฝง </center>', className: 'text-left w100'},
                                            {data: 'aistockist_status', title :'<center> Status <br> AiStockis </center>', className: 'text-center w80 '},
                                            {data: 'qualification', title :'<center> คุณสมบัติ </center>', className: 'text-center w100'},
                                            {data: 'package', title :'<center> Package </center>', className: 'text-center'},
                                            {data: 'pv', title :'<center> คะแนน<br>ส่วนตัว </center>', className: 'text-center  '},
                                            {data: 'introduce_id', title :'<center> รหัสผู้แนะนำ </center>', className: 'text-center  '},
                                            {data: 'regis_status', title :'<center> สถานะการสมัคร <br> (อ้างอิงตามการส่งเอกสาร) </center>', className: 'text-center '},
                                            {data: 'regis_date_doc', title :'<center> วันที่ตรวจสอบผ่าน </center>', className: 'text-center'},
                                            {data: 'id', title :'ข้อมูล <br> ส่วนตัว', className: 'text-center w80'},
                                        ],
                                      rowCallback: function(nRow, aData, dataIndex){
                                        if(sU!=''&&sD!=''){
                                            $('td:last-child', nRow).html('-');
                                        }else{
                                          // console.log(aData['customer_id']+" : "+aData['type']+" : "+aData['regis_status_02']+" : "+aData['item_checked']);
                                             $('td:last-child', nRow).html(''
                                                + '<a class="btn btn-sm btn-info " href='+aData['routes_user']+' target="_blank" class="btn btn-primary"><i class="bx bx-file-find font-size-16 align-middle"></i> </a>'
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

     $(document).on('click', '.btnCheckRegis', function(event) {
          event.preventDefault();
             var id = $(this).data('id');
             $('#id').val(id);
             // console.log(id);

             $.ajax({
                  url: " {{ url('backend/ajaxGetFilepath') }} ",
                  method: "post",
                  data: {
                    id:id,
                    "_token": "{{ csrf_token() }}",
                  },
                  success:function(data)
                  {

                      // console.log(data);

                            $.each(data,function(key,value){
                            // if(value.file_path!=null){
                              // console.log(value.file_path);
                              // console.log(value.type);
                              // console.log(value.file);
                              // console.log(value.status);

                              $('#cus_name').html(value.cus_name);

                               var strArray = value.file.split(".");
                                // console.log(strArray[1]);

                                // var arrXls = ['xls', 'xlsx'];
                                var arrXls = ['png', 'jpg', 'jpeg', 'gif'];

                                // console.log(arrXls.includes(strArray[1]));

                                let ch = arrXls.includes(strArray[1]);
                                if(ch==true){
                                  // $('#file_path').hide();
                                  // $('#file_path_a').attr("href", value.file_path);
                                  // $('#file_path_a').text("File : Excel");
                                  // $('#file_path_a').css({"font-size": "24px"});

                                  // console.log(value.type);
                                  // console.log(value.status);
                                  // console.log(value.comment);

                                  $('#comment').val(value.comment);

                                  $('#regis_status').val(value.status);
                                   $('#regis_status').select2().trigger('change');
                                  if(value.status==0){
                                      $("#regis_status").select2('destroy').val("").select2();
                                  }

                                  if(value.type=="1"){
                                    $('#file_path1').attr("src", value.file_path);
                                    $('#file_path1').show();
                                    $('.p_desc_1').css({"background-color": "bisque", "color": "blue" });
                                    $('.p_desc_11').html("เลขบัตรประชาชน : "+value.id_card);
                                    $('.column_1').css({"border-style": "dotted", "border-width": "7px", "border-color": "coral" });
                                  }else{
                                    $('#file_path1').hide();
                                    $('.p_desc_1').css({"background-color": "", "color": "" });
                                    $('.column_1').css({"border-style": "", "border-width": "", "border-color": "" });
                                  }

                                  if(value.type=="2"){
                                    $('#file_path2').attr("src", value.file_path);
                                    $('#file_path2').show();
                                    $('.p_desc_2').css({"background-color": "bisque", "color": "blue" });
                                    $('.column_2').css({"border-style": "dotted", "border-width": "7px", "border-color": "coral" });
                                  }else{
                                    $('#file_path2').hide();
                                    $('.p_desc_2').css({"background-color": "", "color": "" });
                                    $('.column_2').css({"border-style": "", "border-width": "", "border-color": "" });

                                  }

                                  if(value.type=="3"){
                                    $('#file_path3').attr("src", value.file_path);
                                    $('#file_path3').show();
                                    $('.p_desc_3').css({"background-color": "bisque", "color": "blue" });
                                    $('.p_desc_33').html("เลขบัตรประชาชน : "+value.id_card);
                                    $('.column_3').css({"border-style": "dotted", "border-width": "7px", "border-color": "coral" });
                                  }else{
                                    $('#file_path3').hide();
                                    $('.p_desc_3').css({"background-color": "", "color": "" });
                                    $('.column_3').css({"border-style": "", "border-width": "", "border-color": "" });
                                  }

                                  if(value.type=="4"){
                                    $('#file_path4').attr("src", value.file_path);
                                    $('#file_path4').show();
                                    $('.p_desc_4').css({"background-color": "bisque", "color": "blue" });
                                    $('.p_desc_44').html("บัญชีธนาคาร : "+value.bank_no+" "+value.bank_name);
                                    $('.column_4').css({"border-style": "dotted", "border-width": "7px", "border-color": "coral" });
                                  }else{
                                    $('#file_path4').hide();
                                    $('.p_desc_4').css({"background-color": "", "color": "" });
                                    $('.column_4').css({"border-style": "", "border-width": "", "border-color": "" });
                                  }


                                    // กรณี type อื่นๆ ที่ผ่านก็แสดงเช่นเดียวกัน แต่ไม่มีกรอบ
                                    // ต้องส่ง ajax ไปดึงมาแสดงต่างหาก เว้น type อันที่ระบุ
                                   $.ajax({
                                        url: " {{ url('backend/ajaxGetFilepath02') }} ",
                                        method: "post",
                                        data: {
                                          id:id,
                                          "_token": "{{ csrf_token() }}",
                                        },
                                        success:function(data2)
                                        {
                                           // console.log(data2);
                                           // @@@@@@@@@@@@@@@@@@@@@@@@@@@@
                                             $.each(data2,function(key,value){


                                                   var strArray = value.file.split(".");

                                                    var arrXls = ['png', 'jpg', 'jpeg', 'gif'];

                                                    // console.log(arrXls.includes(strArray[1]));
                                                    let ch = arrXls.includes(strArray[1]);
                                                    if(ch==true){

                                                      if(value.type=="1" && value.status=="1"){
                                                        $('#file_path1').attr("src", value.file_path);
                                                        $('#file_path1').show();
                                                        $('.p_desc_11').html("เลขบัตรประชาชน : "+value.id_card);
                                                      }

                                                      if(value.type=="2" && value.status=="1"){
                                                        $('#file_path2').attr("src", value.file_path);
                                                        $('#file_path2').show();
                                                      }

                                                      if(value.type=="3" && value.status=="1"){
                                                        $('#file_path3').attr("src", value.file_path);
                                                        $('#file_path3').show();
                                                        $('.p_desc_33').html("เลขบัตรประชาชน : "+value.id_card);
                                                      }

                                                      if(value.type=="4" && value.status=="1"){
                                                        $('#file_path4').attr("src", value.file_path);
                                                        $('#file_path4').show();
                                                        $('.p_desc_44').html("บัญชีธนาคาร : "+value.bank_no+" "+value.bank_name);
                                                      }

                                                    }

                                               });
                                           // @@@@@@@@@@@@@@@@@@@@@@@@@@@@
                                        }
                                      });


                                }else{

                                  $('#file_path1').hide();
                                  $('#file_path2').hide();
                                  $('#file_path3').hide();
                                  $('#file_path4').hide();

                                }

                                 $('#type').val(value.type);

                           });


                  }
                })

        });



</script>

<script type="text/javascript">

   $(document).ready(function(){

      $("#customer_id").select2({
          minimumInputLength: 3,
          allowClear: true,
          placeholder: '-Select-',
          ajax: {
          url: " {{ url('backend/ajaxGetCustomer') }} ",
          type  : 'POST',
          dataType : 'json',
          delay  : 250,
          cache: false,
          data: function (params) {
           return {
            term: params.term  || '',   // search term
            page: params.page  || 1
           };
          },
          processResults: function (data, params) {
           return {
            results: data
           };
          }
         }
        });

   });
</script>

<script type="text/javascript">

   $(document).ready(function(){

      $("#business_name").select2({
          minimumInputLength: 3,
          allowClear: true,
          placeholder: '-Select-',
          ajax: {
          url: " {{ url('backend/ajaxGetBusinessName') }} ",
          type  : 'POST',
          dataType : 'json',
          delay  : 250,
          cache: false,
          data: function (params) {
           return {
            term: params.term  || '',   // search term
            page: params.page  || 1
           };
          },
          processResults: function (data, params) {
           return {
            results: data
           };
          }
         }
        });

   });
</script>

<script type="text/javascript">

   $(document).ready(function(){

      $("#introduce_id").select2({
          // minimumInputLength: 3,
          allowClear: true,
          placeholder: '-Select-',
          ajax: {
          url: " {{ url('backend/ajaxGetIntroduce_id') }} ",
          type  : 'POST',
          dataType : 'json',
          delay  : 250,
          cache: false,
          data: function (params) {
           return {
            term: params.term  || '',   // search term
            page: params.page  || 1
           };
          },
          processResults: function (data, params) {
           return {
            results: data
           };
          }
         }
        });

   });
</script>

@endsection

