@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style>
    .select2-selection {height: 34px !important;margin-left: 3px;}
    .border-left-0 {height: 67%;}

      .form-group {
     /*margin-bottom: 1rem; */
     margin-bottom: 0rem  !important; 
  }

.btn-outline-secondary {
    margin-bottom: 36% !important;
}
</style>
@endsection

@section('content')

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
      $sA = '';
    }else{
      $role_group_id = \Auth::user()->role_group_id_fk;
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      // dd($menu_permit);
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sU = @$menu_permit->u==1?'':'display:none;';
      $sD = @$menu_permit->d==1?'':'display:none;';
      $sA = @$menu_permit->can_approve==1?'':'display:none;';
      // echo  @$menu_permit->can_approve;
      // echo $sA;
    }
   ?>


<div class="row" >
    <div class="col-12">
        <div class="card">
            <div class="card-body">

              <div class="row" >

                 <div class="col-md-6 " >
                      <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label">Business Location : </label>
                        <div class="col-md-9">
                         <select id="business_location_id_fk" name="business_location_id_fk" class="form-control select2-templating " required="" >
                              <option value="">-Business Location-</option>
                              @if(@$sBusiness_location)
                                @foreach(@$sBusiness_location AS $r)
                                <option value="{{$r->id}}" >
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

                              <select id="branch_id_fk"  name="branch_id_fk" class="form-control select2-templating "  >
                                 <option disabled selected>กรุณาเลือก Business Location ก่อน</option>
                              </select>

                            </div>
                          </div>
                    </div>

               </div>

      
                  <div class="row" >
                    <div class="col-md-6 " >
                       <div class="form-group row">
                            <label for="ref_code" class="col-md-3 col-form-label"> รหัสอ้างอิง : </label>
                            <div class="col-md-9">

                              <select id="ref_code" name="ref_code" class="form-control select2-templating " >
                              <option value="">-รหัสอ้างอิง-</option>
                              @if(@$sStocks_account_code)
                                @foreach(@$sStocks_account_code AS $r)
                                <option value="{{$r->ref_code}}" >
                                  {{$r->ref_code}}
                                </option>
                                @endforeach
                              @endif
                            </select>

                            </div>
                          </div>
                    </div>

                    <div class="col-md-6 " >
                        <div class="form-group row">
                            <label for="status_accepted" class="col-md-2 col-form-label"> สถานะ : </label>
                            <div class="col-md-10">
                              
                           <select id="status_accepted" name="status_accepted" class="form-control select2-templating " >
                            <option value="" >-สถานะ-</option>
                            <option value="0" >รออนุมัติ</option>
                            <option value="1" >อนุมัติ</option>
                            <option value="3" >ไม่อนุมัติ</option>
                            <option value="2" >ยกเลิก</option>
                          </select>

                            </div>
                          </div>
                    </div>
                  </div>

               <div class="row" >
                    <div class="col-md-6 " >
                       <div class="form-group row">
                            <label for="ref_code" class="col-md-3 col-form-label">  </label>
                            <div class="col-md-9 d-flex">

                                 <input id="startDate"  autocomplete="off" placeholder="วันเริ่ม"  style="margin-left: 2%;border: 1px solid grey;" />
                                 <input id="endDate"  autocomplete="off" placeholder="วันสิ้นสุด"  style="border: 1px solid grey;" />

                            </div>
                          </div>
                    </div>

                    <div class="col-md-6 " >
                        <div class="form-group row">
                        
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


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                  <div class="col-8">
                  <div class="row">
                    <div class="col-12 d-flex ">
                 
                    </div>
                  </div>

                 


                  </div>

                      <div class="col-4 text-right" style="{{@$sC}}" >
                        <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.check_stock_account.create') }}">
                          <i class="bx bx-plus font-size-20 align-middle mr-1"></i>ADD (สร้างใบตรวจนับ)
                        </a>
                      </div>

           <!--        <div class="col-4 text-right" style="{{@$sC}}" >
                  </div>
 -->
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

  function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
    }
    return val;
}


var sA = "{{@$sA}}"; 
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
          url: '{{ route('backend.stocks_account_code.datatable') }}',
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
            {data: 'business_location', title :'<center> Business <br> location </center>', className: 'text-left'},
            {data: 'branch', title :'<center> สาขา </center>', className: 'text-left'},
            {data: 'ref_code', title :'<center> รหัสอ้างอิง </center>', className: 'text-left'},
            {data: 'action_user', title :'<center> ผู้ดำเนินการ </center>', className: 'text-center'},
            {data: 'action_date', title :'<center> วันที่ดำเนินการ </center>', className: 'text-center'},
            {data: 'status_accepted',   title :'<center>สถานะ</center>', className: 'text-center  ',render: function(d) {
              if(d=="0"){
                  return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:darkred">รออนุมัติ</span>';
              }else if(d=="2"){
                  return '<span class="badge badge-pill badge-soft-danger font-size-16" style="color:red">ไม่อนุมัติ</span>';
              }else if(d=="3"){
                  return '<span class="badge badge-pill badge-soft-danger font-size-16" style="color:red">ยกเลิก</span>';
              }else{
                  return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">อนุมัติ/ตรวจสอบแล้ว</span>';
              }
            }},
            {data: 'amt_diff', title :'<center> หมายเหตุ </center>', className: 'text-center'},
            {data: 'approver', title :'<center> ผู้อนุมัติ </center>', className: 'text-center'},
            {data: 'approve_date', title :'<center> วันที่อนุมัติ </center>', className: 'text-center'},            
            {data: 'id', title :'Tools', className: 'text-center w100'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){

                var info = $(this).DataTable().page.info();
                $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

                if( aData['amt_diff']>0 || aData['amt_diff'] <0 ){
                    $("td:eq(7)", nRow).html("<span style='color:red;'>* มีปรับยอด </span>");
                }else{
                  $("td:eq(7)", nRow).html('');
                }

                if(sU!=''&&sA!=''){
                    $('td:last-child', nRow).html(aData['amt_diff']);
                }else{ 

                  $('td:last-child', nRow).html(''
                  + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                  + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit?Approve" class="btn btn-sm btn-primary" style="'+sA+'" ><i class="mdi mdi-eye-outline align-middle"></i></a> '
                  + '<a href="javascript: void(0);" data-url="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                
                ).addClass('input');

          }

        }
    });
    $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
      oTable.draw();
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
                  var ref_code = $('#ref_code').val();
                  var status_accepted = $('#status_accepted').val();
                  var startDate = $('#startDate').val();
                  var endDate = $('#endDate').val();

                  // alert(startDate+":"+endDate);

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

                  // console.log(lot_number);
                  // return false;
                        var oTable02;
                        $(function() {
                                oTable02 = $('#data-table').DataTable({
                                "sDom": "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",
                                    processing: true,
                                    serverSide: true,
                                    scroller: true,
                                    scrollCollapse: true,
                                    scrollX: true,
                                    ordering: false,
                                    destroy: true,
                                    // scrollY: ''+($(window).height()-370)+'px',
                                    iDisplayLength: 25,
                                    ajax: {
                                      url: '{{ route('backend.stocks_account_code.datatable') }}',
                                      data: function ( d ) {
                                          d.myWhere={};
                                          d.myWhere['business_location_id_fk'] = business_location_id_fk ;
                                          d.myWhere['branch_id_fk'] = branch_id_fk ;
                                          d.myWhere['ref_code'] = ref_code ;
                                          d.myWhere['status_accepted'] = status_accepted ;
                                          d.myWhere['action_date'] = startDate+":"+endDate ;
                                          oData = d;
                                          $("#spinner_frame").hide();
                                        },
                                         method: 'POST',
                                       },
                                        columns: [
							            {data: 'id', title :'ID', className: 'text-center w50'},
							            {data: 'business_location', title :'<center> Business <br> location </center>', className: 'text-left'},
							            {data: 'branch', title :'<center> สาขา </center>', className: 'text-left'},
							            {data: 'ref_code', title :'<center> รหัสอ้างอิง </center>', className: 'text-left'},
							            {data: 'action_user', title :'<center> ผู้ดำเนินการ </center>', className: 'text-center'},
							            {data: 'action_date', title :'<center> วันที่ดำเนินการ </center>', className: 'text-center'},
							            {data: 'status_accepted',   title :'<center>สถานะ</center>', className: 'text-center  ',render: function(d) {
							              if(d=="0"){
							                  return '<span class="badge badge-pill badge-soft-warning font-size-16" style="color:darkred">รออนุมัติ</span>';
							              }else if(d=="2"){
							                  return '<span class="badge badge-pill badge-soft-danger font-size-16" style="color:red">ไม่อนุมัติ</span>';
							              }else if(d=="3"){
							                  return '<span class="badge badge-pill badge-soft-danger font-size-16" style="color:red">ยกเลิก</span>';
							              }else{
							                  return '<span class="badge badge-pill badge-soft-primary font-size-16" style="color:darkred">อนุมัติ/ตรวจสอบแล้ว</span>';
							              }
							            }},
							            {data: 'amt_diff', title :'<center> หมายเหตุ </center>', className: 'text-center'},
							            {data: 'approver', title :'<center> ผู้อนุมัติ </center>', className: 'text-center'},
							            {data: 'approve_date', title :'<center> วันที่อนุมัติ </center>', className: 'text-center'},            
							            {data: 'id', title :'Tools', className: 'text-center w100'}, 
							        ],
							        rowCallback: function(nRow, aData, dataIndex){

							                var info = $(this).DataTable().page.info();
							                $("td:eq(0)", nRow).html(info.start + dataIndex + 1);

							                if( aData['amt_diff']>0 || aData['amt_diff'] <0 ){
							                    $("td:eq(7)", nRow).html("<span style='color:red;'>* มีปรับยอด </span>");
							                }else{
							                  $("td:eq(7)", nRow).html('');
							                }

							                if(sU!=''&&sA!=''){
							                    $('td:last-child', nRow).html(aData['amt_diff']);
							                }else{ 

							                  $('td:last-child', nRow).html(''
							                  + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
							                  + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit?Approve" class="btn btn-sm btn-primary" style="'+sA+'" ><i class="mdi mdi-eye-outline align-middle"></i></a> '
							                  + '<a href="javascript: void(0);" data-url="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
							                
							                ).addClass('input');

							          }

							        }
							    });
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
           $('#endDate').val($(this).val());
         });        




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
                   }
                  }
                })
           }
 
      });


</script>

@endsection

