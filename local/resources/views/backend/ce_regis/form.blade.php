@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
    <style>
      input[type=checkbox] {
        width: 20px;
        height: 20px;
        vertical-align: middle;
        margin-right: 3%;
      }
    </style>
@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-flex align-items-center justify-content-between">
      <h4 class="mb-0 font-size-18"> {{ __('message.course_event_save_header') }} </h4>
      <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/ce_regis") }}">
        <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> {{ __('message.back') }}
      </a>
      
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

@IF($_REQUEST['v']==1)
<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty(@$sRow) )
              <form action="{{ route('backend.ce_regis.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.ce_regis.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                      <div class="myBorder">

                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label">Course/Event : * </label>
                            <div class="col-md-8">
                              <select name="ce_id_fk" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                  @if(@$sCourse)
                                    @foreach(@$sCourse AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->ce_id_fk)?'selected':'' }} >{{$r->ce_name}}</option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>


                          <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label"> {{ __('message.customer_name') }} : * </label>
                            <div class="col-md-8">
                              <select name="customers_id_fk" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                  @if(@$Customer)
                                    @foreach(@$Customer AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->customers_id_fk)?'selected':'' }} >
                                        {{$r->id}} : {{$r->prefix_name}}{{$r->first_name}} 
                                        {{$r->last_name}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label">{{ __('message.register_date') }} : * </label>
                            <div class="col-md-3">
                              <input class="form-control" type="date" value="{{ @$sRow->regis_date }}" name="regis_date" required >
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label">{{ __('message.remark') }} :</label>
                            <div class="col-md-8">
                              <input class="form-control" type="text" value="{{ @$sRow->ticket_number }}" id='autocomplete' name="ticket_number" >
                            </div>
                          </div>


                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">{{ __('message.reciever') }} (User Login) :</label>
                                <div class="col-md-8">

                                	@if( empty(@$sRow) )
                                		<input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                    	<input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="subject_recipient" >
                    									@else
                    										<input class="form-control" type="text" value="{{$subject_recipient_name}}" readonly style="background-color: #f2f2f2;" >
                                    	<input class="form-control" type="hidden" value="{{ @$sRow->subject_recipient }}" name="subject_recipient" >
									                 @endif
                                    
                                </div>
                            </div>


                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                 
                  </div>
                  <div class="col-md-6 text-right">
                      
                      <input type="hidden" name="role_group_id" value="{{@$_REQUEST['role_group_id']}}" >
                      <input type="hidden" name="menu_id" value="{{@$_REQUEST['menu_id']}}" >

                    <button type="submit" class="btn btn-primary btn-sm waves-effect">
                    <i class="bx bx-save font-size-16 align-middle mr-1"></i> {{ __('message.save') }}
                    </button>
                  </div>
                </div>

              </form>
              </div>


                <div class="row">
                  <div class="col-6">
                    <div class="myBorder">

                      <form class="form-horizontal" method="POST" action="backend/uploadCe_regisCSV" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                          <label for="csv_file" class="col-md-4 control-label"><b>CSV file to import</b></label>
                          <div class="col-md-6">
                            <input type="file" accept=".csv" class="form-control" name="fileCSV" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-8 col-md-offset-4">
                            <input type='submit' name='submit' class="btn btn-primary btnImCSV " value='Import CSV'>
                          </div>
                        </div>
                      </form>

                      <hr>

					<div class="form-group">
						<div class="col-md-12">
							<select id="ce_id_fk_csv" name="ce_id_fk_csv" class="form-control select2-templating " required >
								<option value="">-เลือกกิจกรรม/อีเว้นท์-</option>
								@if(@$sCourse)
								@foreach(@$sCourse AS $r)
								<option value="{{$r->id}}">{{$r->ce_name}}</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-8 col-md-offset-4">
							<input type='button' class="btn btn-success btnExportCSV " value='Export CSV'> &nbsp;&nbsp;
						</div>
					</div>


                    </div>
                  </div>

                  <div class="col-6">
                    <div class="myBorder">
                      <form class="form-horizontal" method="POST" action="backend/uploadCe_regis" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                          <label for="csv_file" class="col-md-4 control-label"><b>XLSX file to import</b></label>
                          <div class="col-md-6">
                            <input type="file" accept=".xlsx" class="form-control" name="fileXLS" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-8 col-md-offset-4">
                            <input type='submit' name='submit' class="btn btn-primary btnImXlsx " value='Import XLSX'>
                          </div>
                        </div>
                      </form>

                      <hr>

						<div class="form-group">
							<div class="col-md-12">
								<select id="ce_id_fk_xls" name="ce_id_fk_xls" class="form-control select2-templating " required >
									<option value="">-เลือกกิจกรรม/อีเว้นท์-</option>
									@if(@$sCourse)
									@foreach(@$sCourse AS $r)
									<option value="{{$r->id}}" >{{$r->ce_name}}</option>
									@endforeach
									@endif
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-8 col-md-offset-4">
								<input type='button' class="btn btn-success btnExport " value='Export excel'> &nbsp;&nbsp;
							</div>
						</div>

                    </div>
                  </div>

                </div>


                <div class="form-group mb-0 row">
                  <div class="col-md-6">
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/ce_regis") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> {{ __('message.back') }}
                    </a>
                  </div>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
@ENDIF 
<!-- 001 -->

<!-- 002 -->
@IF($_REQUEST['v']==2)

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


              <div class="myBorder">

                  <div class="col-12">
                    <div class="form-group row ">
                      <div class="col-md-12 d-flex  ">
                        <label class="col-4" >Scan QR-code : </label>
                        <div class="col-md-4">
                          <input type="text" class="form-control" id="txtSearch" name="txtSearch" style="font-size: 18px !important;color: blue;" autofocus value="" > 
                        </div>
                        <a class="btn btn-info btn-sm btnSearch " href="#" style="font-size: 14px !important;padding: 0.7%" >
                          <i class="bx bx-search align-middle "></i> SEARCH
                        </a>
                        <input type="hidden" id="id">
                      </div>
                    </div>
                  </div>

                    <div class="form-group row div_data_table ">
                      <div class="col-md-12">

                       <table id="data-table" class="table table-bordered " style="width: 100%;" ></table>


                    </div>
                  </div>
                  </div>




            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<div class="modal fade" id="modalNote" tabindex="-1" role="dialog" aria-labelledby="modalNoteTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  " role="document" style="max-width: 650px !important;" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNoteTitle"><b><i class="bx bx-play"></i>บันทึกการเข้าร่วมกิจกรรม</b></h5>
      </div>
      <form action="{{ route('backend.ce_regis.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="save_ce_regis" value="1" >
        <input type="hidden" id="id_ce_regis" name="id_ce_regis" >
        {{ csrf_field() }}
        <div class="modal-body">

                  <div class="col-12">

                    <div class="form-group row ">
                        <label class="col-3" > รหัส : ชื่อ ลูกค้า : </label>
                        <div class="col-6" style="font-weight: bold;font-size: 16px;">
                         <span class="cus_name"></span>
                        </div>
                    </div>

                    <div class="form-group row ">
                        <label class="col-3" > ชื่อกิจกรรม : </label>
                        <div class="col-6" style="font-weight: bold;font-size: 16px;">
                         <span class="ce_name"></span>
                        </div>
                    </div>
        
                    <div class="form-group row ">
                        <label class="col-3" > Package : </label>
                        <div class="col-6" style="font-weight: bold;font-size: 16px;">
                         <span class="cus_package"></span>
                        </div>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="form-group row ">
                        <label class="col-3" >  </label>
                        <div class="col-8" style="font-weight: bold;font-size: 16px;">
                            <div class="checkbox-color checkbox-primary Ce_regis_gift ">
                            </div>
                        </div>
                    </div>
                  </div>


                  <div class="col-12">
                    <div class="form-group row ">
                        <label class="col-3" > หมายเหตุ : </label>
                        <div class="col-8" style="font-weight: bold;font-size: 16px;">
                          <textarea class="form-control note " rows="4" name="note" ></textarea>
                        </div>
                    </div>
                  </div>


                  <div class="col-12">
                    <div class="form-group row ">
                        <label class="col-3" >  </label>
                        <div class="col-8" style="font-weight: bold;font-size: 16px;">
               
                            <div class="checkbox-color checkbox-primary">
                                <input id="status_in" name="status_in"
                                type="checkbox" 
                                value="1">
                                <label for="status_in" id="label_status_in">
                                    ลงทะเบียน
                                </label>
                            </div>
                      
                        </div>
                    </div>
                  </div>


          <div class="row">
            <div class="col-md-12 text-center  "  >
              <button type="submit" class="btn btn-primary" style="width: 10%;margin-top: 5%;" >Save</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-left: 1%;margin-top: 5%;">Close</button>
            </div>
          </div>


        </div>
      </form>
    </div>
  </div>
</div>


@ENDIF 

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
        iDisplayLength: 10,
        ajax: {
          url: '{{ route('backend.ce_regis.datatable') }}',
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
            {data: 'ce_name', title :'<center>{{ __("message.course_event_name") }}</center>', className: 'text-center'},
            {data: 'customer_name', title :'<center>{{ __("message.customer_name") }}</center>', className: 'text-left'},
            // {data: 'ticket_number', title :'<center>หมายเลขบัตร </center>', className: 'text-center'},
            {data: 'cus_package', title :'<center>Package </center>', className: 'text-left'},
            {data: 'ce_regis_gift', title :'<center>{{ __("message.pick_up") }}</center>', className: 'text-left'},
            // {data: 'level_class',   title :'<center>Class</center>', className: 'text-center ',render: function(d) {
            //     return '<span class="badge badge-pill badge-soft-success font-size-16">'+d+'</span>';
            // }},
            {data: 'regis_date', title :'<center>{{ __("message.register_date") }}</center>', className: 'text-center'},
            {data: 'note', title :'<center>{{ __("message.remark") }}</center>', className: 'text-left'},
            {data: 'id', title :'Tools', className: 'text-center w80'}, 
        ],
        rowCallback: function(nRow, aData, dataIndex){
          if(sU!=''&&sD!=''){
              $('td:last-child', nRow).html('-');
          }else{ 

              $('td:last-child', nRow).html(''
                + '<a href="#" class="btn btn-sm btn-primary btnEdit " style="'+sU+'" data-id="'+aData['id']+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
             
              ).addClass('input');
//    + '<a href="javascript: void(0);" data-url="{{ route('backend.ce_regis.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sD+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
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

      // $(".myloading").show();

      $(".btnExport").click(function(event) {
            /* Act on the event */
            var ce_id_fk_xls = $("#ce_id_fk_xls").val();
            // alert(ce_id_fk_xls);
            if(ce_id_fk_xls==''){
            	alert("กรุณา เลือกกิจกรรม/อีเว้นท์ ");
            	return false;
            }

            $(".myloading").show();
            $.ajax({

                   type:'POST',
                   url: " {{ url('backend/excelExportCe_regis') }} ", 
                   data:{ _token: '{{csrf_token()}}',ce_id:ce_id_fk_xls },
                    success:function(data){
                         console.log(data); 
                         // location.reload();

                         setTimeout(function(){
                            var url='local/public/excel_files/ce_regis.xlsx';
                            window.open(url, 'Download');  
                            $(".myloading").hide();
                        },3000);

                      },
                    error: function(jqXHR, textStatus, errorThrown) { 
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        $(".myloading").hide();
                    }
                });
          });


          $(".btnExportCSV").click(function(event) {
            /* Act on the event */
            var ce_id_fk_csv = $("#ce_id_fk_csv").val();
            // alert(ce_id_fk_csv);
            if(ce_id_fk_csv==''){
            	alert("กรุณา เลือกกิจกรรม/อีเว้นท์ ");
            	return false;
            }

            $(".myloading").show();
            $.ajax({

                   type:'POST',
                   url: " {{ url('backend/csvExportCe_regis') }} ", 
                   data:{ _token: '{{csrf_token()}}',ce_id:ce_id_fk_csv },
                    success:function(data){
                         console.log(data); 
                         // location.reload();

                         setTimeout(function(){
                            var url='local/public/excel_files/ce_regis.csv';
                            window.open(url, 'Download');  
                            $(".myloading").hide();
                        },3000);

                      },
                    error: function(jqXHR, textStatus, errorThrown) { 
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        $(".myloading").hide();
                    }
                });
          });



         $(".btnImCSV").click(function(event) {
                /* Act on the event */
                var v = $("input[name=fileCSV]").val();
                if(v!=''){
                  $(".myloading").show();
                }
                
          });


         $(".btnImXlsx").click(function(event) {
                /* Act on the event */
                var v = $("input[name=fileXLS]").val();
                if(v!=''){
                  $(".myloading").show();
                }
                
          });

        $(document).on('change', '#txtSearch', function(event) {
              event.preventDefault();
              $(".btnSearch").trigger('click');
           
        });

        $(document).on('click', '.btnSearch', function(event) {
              event.preventDefault();
              var txtSearch = $("#txtSearch").val();
              // $("#id_ce_regis").val('');
              
              if(txtSearch==''){

                $("#txtSearch").focus();
                return false;

              }else{

                      $(".myloading").show();

                        $.ajax({
                             type:'POST',
                             url: " {{ url('backend/ajaxGetCEQrcode') }} ", 
                             data:{ _token: '{{csrf_token()}}', txtSearch:txtSearch },
                              success:function(data){
                                  console.log(data); 
                                  if(data==""){
                                     alert("! ไม่พบข้อมูล");
                                      $(".myloading").hide();
                                      $("#id_ce_regis").val("");
                                      $("#txtSearch").focus();
                                      return false;
                                  }else{
                                      
                                      // $.each(data,function(key,value){
                                        $("#id_ce_regis").val(data);

                                          $.ajax({
                                             type:'POST',
                                             url: " {{ url('backend/ajaxGetCEUserRegis') }} ", 
                                             data:{ _token: '{{csrf_token()}}',id:data },
                                              success:function(d){
                                                   console.log(d); 

                                                   $.each(d,function(key,value){

                                                        if(value.status_in == '1'){
                                                            $("#status_in").prop("checked",true);
                                                        }
                                                        else {
                                                            $("#status_in").prop("checked",false);
                                                        }
                                                        $(".ce_name").html(value.ce_name);
                                                        $(".cus_name").html(value.cus_name);
                                                        $(".cus_package").html(value.cus_package);
                                                        $(".note").html(value.note);

                                                       var ce_regis_gift = value.ce_regis_gift;
                                                        
                                                        $.ajax({
                                                             type:'POST',
                                                             url: " {{ url('backend/ajaxGetCe_regis_gift') }} ", 
                                                             data:{ _token: '{{csrf_token()}}', ce_regis_gift:ce_regis_gift },
                                                              success:function(d){
                                                                   console.log(d); 
                                                                   $(".Ce_regis_gift").html(d);
                                                                },
                                                        });

                                                   });

                                                  setTimeout(function(){
                                                      $('#modalNote').modal('show');
                                                      $(".myloading").hide();
                                                  }, 500); 
                                                
                                                },
                                              error: function(jqXHR, textStatus, errorThrown) { 
                                                  console.log(JSON.stringify(jqXHR));
                                                  console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                                  $(".myloading").hide();
                                              }
                                          });


                                  }
                                 
                                },
                        });

       
              }

        

                
           
        });


        $(document).on('click', '.btnEdit', function(event) {
              event.preventDefault();
              $(".myloading").show();
              var id = $(this).data('id');
              $("#id_ce_regis").val(id);
              console.log(id);
                  $.ajax({
                     type:'POST',
                     url: " {{ url('backend/ajaxGetCEUserRegis') }} ", 
                     data:{ _token: '{{csrf_token()}}',id:id },
                      success:function(data){
                           console.log(data); 

                           $.each(data,function(key,value){

                                if(value.status_in == '1'){
                                    $("#status_in").prop("checked",true);
                                }
                                else {
                                    $("#status_in").prop("checked",false);
                                }
                                $(".ce_name").html(value.ce_name);
                                $(".cus_name").html(value.cus_name);
                                $(".cus_package").html(value.cus_package);
                                $(".note").html(value.note);

                               var ce_regis_gift = value.ce_regis_gift;
                                
                                $.ajax({
                                     type:'POST',
                                     url: " {{ url('backend/ajaxGetCe_regis_gift') }} ", 
                                     data:{ _token: '{{csrf_token()}}', ce_regis_gift:ce_regis_gift },
                                      success:function(d){
                                           console.log(d); 
                                           $(".Ce_regis_gift").html(d);
                                        },
                                });

                           });
                           
                           setTimeout(function(){
                                $('#modalNote').modal('show');
                                $(".myloading").hide();
                            }, 500);  

                        
                        },
                      error: function(jqXHR, textStatus, errorThrown) { 
                          console.log(JSON.stringify(jqXHR));
                          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                          $(".myloading").hide();
                      }
                  });
           
        });



        // $('#modalNote').modal('show');


      });


      
    </script>


@endsection

