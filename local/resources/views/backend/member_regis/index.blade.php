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
            <h4 class="mb-0 font-size-18"> สมาชิกลงทะเบียน </h4>
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
                    <label for="customer_id" class="col-md-3 col-form-label"> รหัส-ชื่อสมาชิก : </label>
                    <div class="col-md-9">
                       <select id="customer_id" name="customer_id" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                        @if(@$customer)
                        @foreach(@$customer AS $r)
                        <option value="{{$r->customer_id}}" >
                          {{$r->cus_code}} :
                          {{$r->prefix_name}}{{$r->first_name}} {{$r->last_name}}
                        </option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="po_status" class="col-md-3 col-form-label"> ประเภทไฟล์ :  </label>
                    <div class="col-md-9">
                       <select id="filetype" name="filetype" class="form-control select2-templating " >
                        <option value="">-Select-</option>
                        @if(@$filetype)
                        @foreach(@$filetype AS $r)
                        <option value="{{$r->id}}" >
                          {{$r->txt_desc}} 
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
                    <label for="startDate" class="col-md-3 col-form-label"> ช่วงวันที่ลงทะเบียน : </label>
                     <div class="col-md-9 d-flex">
                      <input id="startDate"  autocomplete="off" placeholder="Begin Date"  style="margin-left: 1.5%;border: 1px solid grey;font-weight: bold;color: black" />
                      <input id="endDate"  autocomplete="off" placeholder="End Date"  style="border: 1px solid grey;font-weight: bold;color: black" />
                    </div>
                  </div>
                </div>
                <div class="col-md-6 " >
                  <div class="form-group row">
                    <label for="approver" class="col-md-3 col-form-label"> ผู้อนุมัติ : </label>
                     <div class="col-md-9 ">
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
              </div>
              

            <div class="row" style="margin-bottom: 2% !important;"  >
                <div class="col-md-6 " style="margin-top: -1% !important;" >
                  <div class="form-group row">
                    <label for="action_user" class="col-md-3 col-form-label">  </label>
                    <div class="col-md-9">
                     
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

                    </div>
                  </div>
                </div>
              </div>

                <table id="data-table" class="table table-bordered dt-responsive" style="width: 100%;">
                </table>
<div style="text-align: center;">
<b>หมายเหตุ</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<i class="bx bx-id-card" style="font-size:24px;vertical-align: middle;"></i> : บัตรประชาชน
<i class="bx bx-book" style="font-size:24px;vertical-align: middle;"></i> : หน้าบัญชีธนาคาร
<i class="bx bx-file" style="font-size:24px;vertical-align: middle;"></i> : เอกสารการสมัคร
<i class="bx bxs-user-badge" style="font-size:24px;vertical-align: middle;"></i> : ภาพใบหน้าพร้อมถือบัตร ปชช.
</div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

          

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->



<div class="modal fade" id="checkRegis" tabindex="-1" role="dialog" aria-labelledby="checkRegisTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg " role="document" style="max-width: 1000px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkRegisTitle"><b><i class="bx bx-play"></i>บันทึกตรวจสอบการลงทะเบียน </b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{ route('backend.member_regis.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="save_regis" value="1" >
            <input type="hidden" id="id" name="id" value="">
            <input type="hidden" id="type" >
            {{ csrf_field() }}

      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
               
                <div class="row" >
                     <div class="col-md-12" >
                      <div class="form-group row">
                        <label for="" class="col-md-3 col-form-label"> File : </label>
                        <div class="col-md-8 ">
                            <img id="file_path" class="grow" src="" width="80%" style="cursor: pointer;" > 
                            <!-- <a id="file_path_a"  href="file_path_a" download> file_name </a>  -->
                            <span class="file_path_desc"></span>

                        </div>
                      </div>
                    </div>
                </div>
                <br>
                <br>

              <div class="row" >
                      <div class="col-md-12" >
                      <div class="form-group row">
                        <label for="comment" class="col-md-4 col-form-label"> หมายเหตุ (ถ้ามี) : </label>
                        <div class="col-md-6">
                            <textarea class="form-control" rows="3" id="comment" name="comment" ></textarea>
                        </div>
                      </div>
                    </div>
                </div>
                <br>
                <div class="row" >
                      <div class="col-md-12" >
                      <div class="form-group row">
                        <label for="regis_status" class="col-md-4 col-form-label"> ผลการตรวจสอบ : </label>
                        <div class="col-md-4">
                           <select name="regis_status" class="form-control select2-templating " required >
                              <option value="">Select</option>
                              <option value="S">ผ่าน</option>
                              <option value="F">ไม่ผ่าน</option>
                            </select>
                        </div>
                      </div>
                    </div>
                </div>


              </div>
            </div>
          </div>
        </div>

         <div class="row">
          <div class="col-md-12 text-center  "  >
             <button type="submit" class="btn btn-primary" style="width: 10%;" >Save</button>
             <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-left: 1%;">Close</button>
          </div>
        </div>

      </div>
            <div class="modal-footer">
              <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
              <!-- <button type="submit" class="btn btn-primary">Save</button> -->
            </div>

      </form>

    </div>
  </div>
</div>


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
          url: '{{ route('backend.member_regis.datatable') }}',
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
            {data: 'customer_name', title :'<center>รหัส : ชื่อสมาชิก </center>', className: 'text-left'},
            {data: 'filetype', title :'<center> ประเภทไฟล์ </center>', className: 'text-left'},
            {data: 'regis_status', title :'<center>สถานะการอนุมัติ </center>', className: 'text-center'},
            {data: 'approver', title :'<center>ผู้อนุมัติ </center>', className: 'text-center'},
            {data: 'approve_date', title :'<center>วันที่นุมัติ </center>', className: 'text-center'},
            {data: 'icon', title :'<center> Icon </center>', className: 'text-center'},
            {data: 'id', title :'Tools', className: 'text-center w80'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){
          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

              // $('td:last-child', nRow).html(''
              //   + '<a href="{{ route('backend.po_receive.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary" style="'+sU+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
              
              // ).addClass('input');

               $('td:last-child', nRow).html(''
                  + '<a href="#" class="btn btn-sm btn-primary btnCheckRegis " data-id="'+aData['id']+'"  ><i class="bx bx-edit font-size-16 align-middle"></i> </a> '
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
                  console.log(supplier_id_fk);
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

     $(document).on('click', '.btnCheckRegis', function(event) {
          event.preventDefault();
             var id = $(this).data('id');
             $('#id').val(id);

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
                              console.log(value.file_path);
                              console.log(value.type);
                              console.log(value.file);

                               var strArray = value.file.split(".");

                                console.log(strArray[1]);

                                // var arrXls = ['xls', 'xlsx'];
                                var arrXls = ['png', 'jpg', 'jpeg', 'gif'];

                                console.log(arrXls.includes(strArray[1]));

                                let ch = arrXls.includes(strArray[1]);
                                if(ch==true){
                                  // $('#file_path').hide();
                                  // $('#file_path_a').attr("href", value.file_path);
                                  // $('#file_path_a').text("File : Excel");
                                  // $('#file_path_a').css({"font-size": "24px"});
                                  $('#file_path').attr("src", value.file_path);
                                  $('#file_path').show();
                                  $('.file_path_desc').hide();

                                }else{
                                  $('#file_path').hide();
                                  // $('#file_path').attr("src", value.file_path);
                                  $('.file_path_desc').html("File not found");
                                  $('.file_path_desc').css({"background-color": "#ccffe6", "font-size": "24px"});
                                  $('.file_path_desc').show();
                                }

                               $('#type').val(value.type);
                            
                           });

                            // console.log(v);
                            // if(v==null){
                            //     console.log("aaaaa");
                            //     $('#file_path').hide();
                            //     $('.file_path_desc').html("File not found");
                            //     $('.file_path_desc').css({"background-color": "#ccffe6", "font-size": "24px"});
                            // }else{
                            //     console.log("bbbbb");
                            //     $('#file_path').show();
                            // }

                           $('#checkRegis').modal('show');
                  }
                })

        });


     $('#myModal').on('hidden.bs.modal', function () {
        location.reload();
     });


</script> 
@endsection

