@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

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

  <div class="toast toast-color" id="notification"
                data-delay="3000">
        <div class="toast-body">
            ! พบรายการ Ai-Cash ที่ยังรอการชำระ 
        </div>
    </div>

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> เติม Ai-Cash </h4> 
        </div>
    </div>
</div>
<!-- end page title -->
  <?php 
      $sPermission = \Auth::user()->permission ;
      $menu_id = Session::get('session_menu_id');
      // print_r($menu_id);
    if($sPermission==1){
      $sC = '';
      $sU = '';
      $sD = '';
       $sA = 1 ;
    }else{
      $role_group_id = \Auth::user()->role_group_id_fk;
      // print_r($role_group_id);
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      // print_r($menu_permit);
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sU = @$menu_permit->u==1?'':'display:none;';
      $sD = @$menu_permit->d==1?'':'display:none;';
      $sA = @$menu_permit->can_approve ;
    }

    // print_r($sD);

   ?>


<div class="row" >
    <div class="col-12">
        <div class="card">
            <div class="card-body">

              <div class="row" >

                 <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="business_location_id_fk" class="col-md-3 col-form-label">Business Location : </label>
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
                    <label for="customer_id_fk" class="col-md-3 col-form-label"> รหัส/ชื่อสมาชิก : </label>
                    <div class="col-md-9">
                      <select id="customer_id_fk" name="customer_id_fk" class="form-control select2-templating " >
                        <option value="">-Customer-</option>
                        @if(@$customer)
                        @foreach(@$customer AS $r)
                        <option value="{{$r->customer_id_fk}}" >
                          <!-- {{$r->customer_id_fk}} -->
                          {{$r->cus_code}} :
                          {{$r->prefix_name}}{{$r->first_name}} {{$r->last_name}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                </div>
                <!--  `bill_status` int(11) DEFAULT '0' COMMENT '1=รอชำระ,2=ชำระแล้ว,3=ยกเลิก', -->
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="bill_status" class="col-md-3 col-form-label"> สถานะบิล :  </label>
                    <div class="col-md-9">
                      <select id="bill_status" name="bill_status" class="form-control select2-templating " >
                        <option value="">-Status-</option>
                        <option value="1" > รอชำระ </option>
                        <option value="2" > ชำระแล้ว </option>
                        <option value="3" > ยกเลิก </option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

            <div class="row" >
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="startDate" class="col-md-3 col-form-label"> วันที่ดำเนินการ : </label>
                     <div class="col-md-9 d-flex">
                      <input id="startDate"  autocomplete="off" placeholder="Begin Date"  style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black" />
                      <input id="endDate"  autocomplete="off" placeholder="End Date"  style="border: 1px solid grey;font-weight: bold;color: black" />
                    </div>
                  </div>
                </div>
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="invoice_code" class="col-md-3 col-form-label"> เลขออเดอร์ : </label>
                     <div class="col-md-9 ">
                      <select id="invoice_code" name="invoice_code" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                        @if(@$sInvoice_code)
                        @foreach(@$sInvoice_code AS $r)
                        <option value="{{$r->invoice_code}}" >
                          {{$r->invoice_code}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              

            <div class="row" >
                <div class="col-md-6 " style="margin-top: -1% !important;" >
                  <div class="form-group row">
                    <label for="approver" class="col-md-3 col-form-label"> ผู้อนุมัติ : </label>
                    <div class="col-md-9">
                     <select id="approver" name="approver" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                        @if(@$sApprover)
                        @foreach(@$sApprover AS $r)
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

                    <a class="btn btn-info btn-sm float-right " style="{{@$sC}}" href="{{ route('backend.add_ai_cash.create') }}">
                      <i class="bx bx-plus font-size-20 align-middle"></i>ADD
                    </a>


                    </div>
                  </div>
                </div>
              </div>
              <br>

<!--         </div>
      </div>
    </div>
  </div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body"> -->
                <div class="row">
                  <div class="col-8">
                    <!-- <input type="text" class="form-control float-left text-center w130 myLike" placeholder="รหัสย่อ" name="short_code"> -->
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
        // scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
        ajax: {
          url: '{{ route('backend.add_ai_cash.datatable') }}',
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
            {data: 'invoice_code', title :'<center>เลขออเดอร์ </center>', className: 'text-left'},
            {data: 'customer_name', title :'<center>ลูกค้า </center>', className: 'text-left w100 '},
            {data: 'aicash_remain', title :'<center>ยอด Ai-Cash <br> คงเหลือล่าสุด</center>', className: 'text-center'},
            {data: 'aicash_amt', title :'<center>ยอด Ai-Cash <br>ที่เติมครั้งนี้</center>', className: 'text-center'},
            {data: 'action_user', title :'<center>พนักงาน <br> ที่ดำเนินการ </center>', className: 'text-center'},
            {data: 'pay_type_id_fk', title :'<center>รูปแบบการชำระเงิน </center>', className: 'text-center'},
            {data: 'total_amt', title :'<center>ยอดชำระเงิน </center>', className: 'text-center'},
             {data: 'status',   title :'<center>สถานะ</center>', className: 'text-center w100 ',render: function(d) {
              if(d=="รออนุมัติ"){
                  return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:darkred">'+d+'</span>';
              }else{
                  return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">'+d+'</span>';
              }
            }},
            {data: 'approver', title :'<center>ผู้อนุมัติ</center>', className: 'text-center'},

            {data: 'updated_at', title :'<center>วันที่ </center>', className: 'text-center'},
            // {data: 'aicash_amt',   title :'ยอด Ai-Cash ', className: 'text-center ',render: function(d) {
            //     return (parseFloat(d)>0)?d:'-';
            // }},
            {data: 'id', title :'Tools', className: 'text-center w60'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){

          if(aData['approve_status']==4){
            for (var i = 0; i < 6; i++) {
              $('td:eq( '+i+')', nRow).html(aData[i]).css({'color':'#d9d9d9','text-decoration':'line-through','font-style':'italic'});
            }

            // $('td:last-child', nRow).html('-ยกเลิก-');
            $('td:last-child', nRow).html('-');

          }else{

                 if(sU!=''&&sD!=''){
                          $('td:last-child', nRow).html('-');
                      }else{ 

                      $('td:last-child', nRow).html(''
                        + '<a href="{{ route('backend.add_ai_cash.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.add_ai_cash.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDeleteX cDelete " customer_id_fk="'+aData['customer_id_fk']+'"  data-id="'+aData['id']+'" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');

                    }

          }

     

        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
    });
});


  $(document).ready(function() {

             $(document).on('click', '.cDeleteX', function(event) {

                  var id = $(this).data('id');
                  var customer_id_fk = $(this).attr('customer_id_fk');
                  // alert(id);
                  $.ajax({
                       type:'POST',
                       url: " {{ url('backend/ajaxCheckAddAiCash') }} ", 
                       data: { id:id,customer_id_fk:customer_id_fk },
                        success:function(data){
                               console.log(data); 
                               if(data=="no"){
                                 alert("! ไม่สามารถยกเลิกได้หรือลบได้ เนื่องจากยอด Ai-Cash ถูกใช้ไปแล้ว ");
                                 return false;
                               }
                              $(".myloading").hide();
                          },
                       
                    });

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
                  var customer_id_fk = $('#customer_id_fk').val();
                  var startDate = $('#startDate').val();
                  var endDate = $('#endDate').val();
                  var bill_status = $('#bill_status').val();
                  var approver = $('#approver').val();
                  var invoice_code = $('#invoice_code').val();

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
                                          url: '{{ route('backend.add_ai_cash.datatable') }}',
                                          data :{
                                            _token: '{{csrf_token()}}',
                                                business_location_id_fk:business_location_id_fk,
                                                branch_id_fk:branch_id_fk,
                                                customer_id_fk:customer_id_fk,
                                                startDate:startDate,
                                                endDate:endDate,
                                                bill_status:bill_status,                                 
                                                approver:approver,                                 
                                                invoice_code:invoice_code,                                 
                                              },
                                            method: 'POST',
                                          },
                                columns: [
                                    {data: 'id', title :'ID', className: 'text-center w50'},
                                    {data: 'invoice_code', title :'<center>เลขออเดอร์ </center>', className: 'text-left'},
                                    {data: 'customer_name', title :'<center>ลูกค้า </center>', className: 'text-left w100 '},
                                    {data: 'aicash_remain', title :'<center>ยอด Ai-Cash <br> คงเหลือล่าสุด</center>', className: 'text-center'},
                                    {data: 'aicash_amt', title :'<center>ยอด Ai-Cash <br>ที่เติมครั้งนี้</center>', className: 'text-center'},
                                    {data: 'action_user', title :'<center>พนักงาน <br> ที่ดำเนินการ </center>', className: 'text-center'},
                                    {data: 'pay_type_id_fk', title :'<center>รูปแบบการชำระเงิน </center>', className: 'text-center'},
                                    {data: 'total_amt', title :'<center>ยอดชำระเงิน </center>', className: 'text-center'},
                                     {data: 'status',   title :'<center>สถานะ</center>', className: 'text-center w100 ',render: function(d) {
                                      if(d=="รออนุมัติ"){
                                          return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:darkred">'+d+'</span>';
                                      }else{
                                          return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">'+d+'</span>';
                                      }
                                    }},
                                    {data: 'approver', title :'<center>ผู้อนุมัติ</center>', className: 'text-center'},

                                    {data: 'updated_at', title :'<center>วันที่ </center>', className: 'text-center'},
                                    // {data: 'aicash_amt',   title :'ยอด Ai-Cash ', className: 'text-center ',render: function(d) {
                                    //     return (parseFloat(d)>0)?d:'-';
                                    // }},
                                    {data: 'id', title :'Tools', className: 'text-center w60'}, 
                                ],
                                rowCallback: function(nRow, aData, dataIndex){

                                  if(aData['approve_status']==4){
                                    for (var i = 0; i < 6; i++) {
                                      $('td:eq( '+i+')', nRow).html(aData[i]).css({'color':'#d9d9d9','text-decoration':'line-through','font-style':'italic'});
                                    }

                                    // $('td:last-child', nRow).html('-ยกเลิก-');
                                    $('td:last-child', nRow).html('-');

                                  }else{

                                         if(sU!=''&&sD!=''){
                                                  $('td:last-child', nRow).html('-');
                                              }else{ 

                                              $('td:last-child', nRow).html(''
                                                + '<a href="{{ route('backend.add_ai_cash.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"  style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                                                + '<a href="javascript: void(0);" data-url="{{ route('backend.add_ai_cash.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDeleteX cDelete " customer_id_fk="'+aData['customer_id_fk']+'"  data-id="'+aData['id']+'" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                              ).addClass('input');

                                            }

                                  }

                             

                                }
                            });
                            $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                              oTable.draw();
                            });
                        });

                    // @@@@@@@@@@@@@@@@@@@@@@@@@@ datatables @@@@@@@@@@@@@@@@@@@@@@@@@@

                setTimeout(function(){
                   $(".myloading").hide();
                }, 1500);

               
            });
          });

    </script>

    <script>
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
                    $(".myloading").hide();
                   if(data == ''){
                       alert('ไม่พบข้อมูลสาขา !!.');
                   }else{
                       var layout = '<option value="" selected>- เลือกสาขา -</option>';
                       $.each(data,function(key,value){
                        layout += '<option value='+value.id+'>'+value.b_name+'</option>';
                       });
                       $('#branch_id_fk').html(layout);
                   }
                  }
                })
           }else{
            $(".myloading").hide();
           }
 
      });



    </script>


<!-- <audio autoplay> 
  <source src="http://freesound.org/data/previews/263/263133_2064400-lq.mp3">
</audio>
 -->

<script type="text/javascript">
       $(document).ready(function() {

       		var sApprove = "<?=$sA?>";
       		// alert(sApprove);
            // setInterval(function(){ 
            //   $.ajax({
            //      type:'POST',
            //      url: " {{ url('backend/ajaxCheckAddAiCashStatus') }} ", 
            //       success:function(data){
            //              console.log(data); 
            //              if(data>0){
            //                 $('.toast').toast('show');
            //              }
            //         },
                 
            //   });

            // }, 3000);

             // setInterval(function(){ 
              // alert("Oooo Yeaaa!");
             if(sApprove==1){
              $.ajax({
                 type:'POST',
                 url: " {{ url('backend/ajaxCheckAddAiCashStatus') }} ", 
                  success:function(data){
                         console.log(data); 
                         if(data>0){
                           // alert("! พบรายการ Ai-Cash ที่ยังรอการชำระ ");
                           // return false;
                           Swal.fire({
                                type: 'warning',
                                title: '! พบรายการ Ai-Cash ที่ยังรอการชำระ ',
                                showConfirmButton: false,
                                timer: 3500
                              })

                         }
                    },
                 
              });

          }

            // }, 5000);

      });
</script>

<script type="text/javascript">
$(document).ready(function() {

	var sApprove = "<?=$sA?>";

	if(sApprove==1){

      $(document).idle({
        onIdle: function(){
         // alert('You did nothing for 5 seconds');
           $.ajax({
                 type:'POST',
                 url: " {{ url('backend/ajaxCheckAddAiCashStatus') }} ", 
                  success:function(data){
                         console.log(data); 
                         if(data>0){
                           // alert("! พบรายการ Ai-Cash ที่ยังรอการชำระ ");
                           // return false;
                           Swal.fire({
                                type: 'warning',
                                title: '! พบรายการ Ai-Cash ที่ยังรอการชำระ ',
                                showConfirmButton: false,
                                timer: 3500
                              })

                         }
                    },
                 
              });

         },
       idle: 3000
    });

  }


});

</script>

 
@endsection



