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

    // echo  \Auth::user()->branch_id_fk;

    $sPermission = \Auth::user()->permission ;
      // $menu_id = @$_REQUEST['menu_id'];
      $menu_id = Session::get('session_menu_id');
      // echo $menu_id;
    if($sPermission==1){
      $sC = '';
      $sU = '';
      $sD = '';
      $sA = '';
      $dis_edit = '';
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
      if(@$menu_permit->can_approve==1){
        $dis_edit = 'display:none;';
      }else{
        $dis_edit = '';
      }

    }

    // echo $menu_id;
    // echo \Auth::user()->id;
    // echo \Auth::user()->role_group_id_fk;
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
                            <label for="branch_id_fk" class="col-md-2 col-form-label"> Branch : </label>
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
                            <label for="ref_code" class="col-md-3 col-form-label"> Ref-Code : </label>
                            <div class="col-md-9">

                              <select id="ref_code" name="ref_code" class="form-control select2-templating " >
                              <option value="">-Ref-Code-</option>
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
                            <label for="status_accepted" class="col-md-2 col-form-label"> Status : </label>
                            <div class="col-md-10">
                          
                          <!-- 0=NEW,1=PENDDING,2=REQUEST,3=ACCEPTED/APPROVED,4=NO APPROVED,5=CANCELED -->
                           <select id="status_accepted" name="status_accepted" class="form-control select2-templating " >
                            <option value="" >-สถานะ-</option>
                            <option value="0" >NEW</option>
                            <option value="1" >PENDDING</option>
                            <option value="2" >REQUEST</option>
                            <option value="3" >ACCEPTED</option>
                            <option value="4" >NO APPROVED</option>
                            <option value="5" >CANCELED</option>
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

                                 <input id="startDate"  autocomplete="off" placeholder="Begin"  style="margin-left: 2%;border: 1px solid grey;" />
                                 <input id="endDate"  autocomplete="off" placeholder="End"  style="border: 1px solid grey;" />

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

var sPermission = "<?=\Auth::user()->permission?>";
var sUserID = "<?=\Auth::user()->id?>";
var sA = "{{@$sA}}"; 
var sU = "{{@$sU}}"; 
var sD = "{{@$sD}}";
var dis_edit = "{{@$dis_edit}}"; //alert(dis_edit);
// alert(sA+":"+sU+":"+sD);
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
        // stateSave: true,
        // scrollY: ''+($(window).height()-370)+'px',
        iDisplayLength: 25,
         ajax: {
            url: '{{ route('backend.stocks_account_code.datatable') }}',
            // data :{
            //       branch_id_fk:branch_id_fk,
            //     },
              method: 'POST',
            },

        columns: [
            {data: 'id', title :'ID', className: 'text-center w50'},
            {data: 'business_location', title :'<center> Business <br> location </center>', className: 'text-left'},
            {data: 'branch', title :'<center> Branch </center>', className: 'text-left'},
            {data: 'ref_code', title :'<center> Ref-Code </center>', className: 'text-left'},
            {data: 'action_user', title :'<center> Operator </center>', className: 'text-center'},
            {data: 'action_date', title :'<center> Implementation date  </center>', className: 'text-center'},
            {data: 'status_accepted',   title :'<center>Status</center>', className: 'text-center  ',render: function(d) {
              // 0=NEW,1=PENDDING,2=REQUEST,3=ACCEPTED/APPROVED,4=NO APPROVED,5=CANCELED             
              if(d=="1"){
                  return '<span class="badge badge-pill badge-soft-info font-size-14" style="color:black">PENDDING</span>';
              }else if(d=="2"){
                  return '<span class="badge badge-pill badge-info font-size-14" style="color:white">REQUEST</span>';
              }else if(d=="3"){
                  return '<span class="badge badge-pill badge-soft-success font-size-14" style="color:black">ACCEPTED</span>';
              }else if(d=="4"){
                  return '<span class="badge badge-pill badge-soft-warning font-size-14" style="color:black">NO APPROVED</span>';
              }else if(d=="5"){
                  return '<span class="badge badge-pill badge-soft-warning font-size-14" style="color:black">CANCELED</span>';
              }else{
                  return '<span class="badge badge-pill badge-soft-info font-size-14" style="color:black">NEW</span>';
              }
            }},
            {data: 'amt_diff', title :'<center> Note </center>', className: 'text-center'},
            {data: 'approver', title :'<center> Approval </center>', className: 'text-center'},
            {data: 'approve_date', title :'<center> Approval date  </center>', className: 'text-center'},            
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

                // sPermission
                if(sPermission==1){

                      if(aData['status_accepted']==3||aData['status_accepted']==4){
                              $('td:last-child', nRow).html(''
                              + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit?Approve" class="btn btn-sm btn-primary" style="'+sA+'" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                              + '<a href="javascript: void(0);" class="btn btn-sm btn-secondary " title="ยกเลิก"><i class="bx bx-trash font-size-16 align-middle" style="color:#bfbfbf;"></i></a>'
                              ).addClass('input');
                      }else if(aData['status_accepted']==2){
                              $('td:last-child', nRow).html(''
                              + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+';" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                              + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit?Approve" class="btn btn-sm btn-primary" style="'+sA+'" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                              + '<a href="javascript: void(0);" data-url="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+';" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                              ).addClass('input');
                      }else if(aData['status_accepted']==0||aData['status_accepted']==1){
                              $('td:last-child', nRow).html(''
                              + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+';" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                              + '<a href="javascript: void(0);" data-url="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+';" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                              ).addClass('input');
                      }else if(aData['status_accepted']==5){
                        $('td:last-child', nRow).html(''
                              + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+';" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                              + '<a href="javascript: void(0);" class="btn btn-sm btn-secondary " title="ยกเลิก"><i class="bx bx-trash font-size-16 align-middle" style="color:#bfbfbf;"></i></a>'
                              ).addClass('input');

                      }

               // sPermission       
              }else{


                  if(aData['action_user_id']==sUserID){

                     if(aData['status_accepted']==3||aData['status_accepted']==4){

                              $('td:last-child', nRow).html(''
                              + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit?Approve" class="btn btn-sm btn-primary" style="" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                              + '<a href="javascript: void(0);" class="btn btn-sm btn-secondary " title="ยกเลิก"><i class="bx bx-trash font-size-16 align-middle" style="color:#bfbfbf;"></i></a>'
                              ).addClass('input');

                      }else if(aData['status_accepted']==2){
                              $('td:last-child', nRow).html(''
                              + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+';" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                              + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit?Approve" class="btn btn-sm btn-primary" style="'+sA+'" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                              + '<a href="javascript: void(0);" data-url="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+';" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                              ).addClass('input');

                       }else if(aData['status_accepted']==0||aData['status_accepted']==1){
                            
                                $('td:last-child', nRow).html(''
                                + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+';" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                                + '<a href="javascript: void(0);" data-url="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+';" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                ).addClass('input');

                            // $('td:last-child', nRow).html(aData['action_user_id']);
                              
                      }else if(aData['status_accepted']==5){
                              $('td:last-child', nRow).html(''
                              + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+';" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                              + '<a href="javascript: void(0);" class="btn btn-sm btn-secondary " title="ยกเลิก"><i class="bx bx-trash font-size-16 align-middle" style="color:#bfbfbf;"></i></a>'
                              ).addClass('input');

                      }

                }else{

                              $('td:last-child', nRow).html(''
                              + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit?Approve" class="btn btn-sm btn-primary" style="" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                              + '<a href="javascript: void(0);" class="btn btn-sm btn-secondary " title="ยกเลิก"><i class="bx bx-trash font-size-16 align-middle" style="color:#bfbfbf;"></i></a>'
                              ).addClass('input');

                }


              } // sPermission   


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

                  var sA = "{{@$sA}}"; 
                  var sU = "{{@$sU}}"; 
                  var sD = "{{@$sD}}";
                  var dis_edit = "{{@$dis_edit}}"; //alert(dis_edit);

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
                                    // stateSave: true,
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
                                          {data: 'branch', title :'<center> Branch </center>', className: 'text-left'},
                                          {data: 'ref_code', title :'<center> Ref-Code </center>', className: 'text-left'},
                                          {data: 'action_user', title :'<center> Operator </center>', className: 'text-center'},
                                          {data: 'action_date', title :'<center> Implementation date  </center>', className: 'text-center'},
                                          {data: 'status_accepted',   title :'<center>Status</center>', className: 'text-center  ',render: function(d) {
                                            // 0=NEW,1=PENDDING,2=REQUEST,3=ACCEPTED/APPROVED,4=NO APPROVED,5=CANCELED             
                                            if(d=="1"){
                                                return '<span class="badge badge-pill badge-soft-info font-size-14" style="color:black">PENDDING</span>';
                                            }else if(d=="2"){
                                                return '<span class="badge badge-pill badge-info font-size-14" style="color:white">REQUEST</span>';
                                            }else if(d=="3"){
                                                return '<span class="badge badge-pill badge-soft-success font-size-14" style="color:black">ACCEPTED</span>';
                                            }else if(d=="4"){
                                                return '<span class="badge badge-pill badge-soft-warning font-size-14" style="color:black">NO APPROVED</span>';
                                            }else if(d=="5"){
                                                return '<span class="badge badge-pill badge-soft-warning font-size-14" style="color:black">CANCELED</span>';
                                            }else{
                                                return '<span class="badge badge-pill badge-soft-info font-size-14" style="color:black">NEW</span>';
                                            }
                                          }},
                                          {data: 'amt_diff', title :'<center> Note </center>', className: 'text-center'},
                                          {data: 'approver', title :'<center> Approval </center>', className: 'text-center'},
                                          {data: 'approve_date', title :'<center> Approval date  </center>', className: 'text-center'},            
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

                                              // sPermission
                                              if(sPermission==1){

                                                    if(aData['status_accepted']==3||aData['status_accepted']==4){
                                                            $('td:last-child', nRow).html(''
                                                            + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit?Approve" class="btn btn-sm btn-primary" style="'+sA+'" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                                                            + '<a href="javascript: void(0);" class="btn btn-sm btn-secondary " title="ยกเลิก"><i class="bx bx-trash font-size-16 align-middle" style="color:#bfbfbf;"></i></a>'
                                                            ).addClass('input');
                                                    }else if(aData['status_accepted']==2){
                                                            $('td:last-child', nRow).html(''
                                                            + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+';" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                                                            + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit?Approve" class="btn btn-sm btn-primary" style="'+sA+'" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                                                            + '<a href="javascript: void(0);" data-url="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+';" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                                            ).addClass('input');
                                                    }else if(aData['status_accepted']==0||aData['status_accepted']==1){
                                                            $('td:last-child', nRow).html(''
                                                            + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+';" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                                                            + '<a href="javascript: void(0);" data-url="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+';" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                                            ).addClass('input');
                                                    }else if(aData['status_accepted']==5){
                                                      $('td:last-child', nRow).html(''
                                                            + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+';" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                                                            + '<a href="javascript: void(0);" class="btn btn-sm btn-secondary " title="ยกเลิก"><i class="bx bx-trash font-size-16 align-middle" style="color:#bfbfbf;"></i></a>'
                                                            ).addClass('input');

                                                    }

                                             // sPermission       
                                            }else{


                                                if(aData['action_user_id']==sUserID){

                                                   if(aData['status_accepted']==3||aData['status_accepted']==4){

                                                            $('td:last-child', nRow).html(''
                                                            + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit?Approve" class="btn btn-sm btn-primary" style="" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                                                            + '<a href="javascript: void(0);" class="btn btn-sm btn-secondary " title="ยกเลิก"><i class="bx bx-trash font-size-16 align-middle" style="color:#bfbfbf;"></i></a>'
                                                            ).addClass('input');

                                                    }else if(aData['status_accepted']==2){
                                                            $('td:last-child', nRow).html(''
                                                            + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+';" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                                                            + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit?Approve" class="btn btn-sm btn-primary" style="'+sA+'" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                                                            + '<a href="javascript: void(0);" data-url="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+';" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                                            ).addClass('input');

                                                     }else if(aData['status_accepted']==0||aData['status_accepted']==1){
                                                          
                                                              $('td:last-child', nRow).html(''
                                                              + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+';" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                                                              + '<a href="javascript: void(0);" data-url="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+';" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                                                              ).addClass('input');

                                                          // $('td:last-child', nRow).html(aData['action_user_id']);
                                                            
                                                    }else if(aData['status_accepted']==5){
                                                            $('td:last-child', nRow).html(''
                                                            + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+';" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                                                            + '<a href="javascript: void(0);" class="btn btn-sm btn-secondary " title="ยกเลิก"><i class="bx bx-trash font-size-16 align-middle" style="color:#bfbfbf;"></i></a>'
                                                            ).addClass('input');

                                                    }

                                              }else{

                                                            $('td:last-child', nRow).html(''
                                                            + '<a href="{{ route('backend.check_stock_account.index') }}/'+aData['id']+'/edit?Approve" class="btn btn-sm btn-primary" style="" ><i class="bx bx-info-circle font-size-16 align-middle"></i></a> '
                                                            + '<a href="javascript: void(0);" class="btn btn-sm btn-secondary " title="ยกเลิก"><i class="bx bx-trash font-size-16 align-middle" style="color:#bfbfbf;"></i></a>'
                                                            ).addClass('input');

                                              }


                                            } // sPermission   


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

