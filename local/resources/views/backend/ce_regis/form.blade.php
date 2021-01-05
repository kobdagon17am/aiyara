@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')
<div class="myloading"></div>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> บันทึกกิจกรรม COURSE/EVENT </h4>
        </div>
    </div>
</div>
<!-- end page title -->

  <?php 
    $sPermission = \Auth::user()->permission ;
    $menu_id = @$_REQUEST['menu_id'];
    $role_group_id = @$_REQUEST['role_group_id'];
    if($sPermission==1){
      $sC = '';
      $sU = '';
      $sD = '';
      $sA = '';
    }else{
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sA = @$menu_permit->can_answer==1?'':'display:none;';
    }

      //   echo $sPermission;
      // echo $role_group_id;
      // echo $menu_id;  

   ?>
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
                            <label for="example-text-input" class="col-md-3 col-form-label"> ชื่อลูกค้า : * </label>
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
                            <label for="example-text-input" class="col-md-3 col-form-label">วันที่ลงทะเบียน : * </label>
                            <div class="col-md-3">
                              <input class="form-control" type="date" value="{{ @$sRow->regis_date }}" name="regis_date" required >
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label">หมายเหตุ :</label>
                            <div class="col-md-8">
                              <input class="form-control" type="text" value="{{ @$sRow->ticket_number }}" id='autocomplete' name="ticket_number" >
                            </div>
                          </div>


                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">ผู้รับเรื่อง (User Login) :</label>
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
                    <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/ce_regis") }}">
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>
                  </div>
                  <div class="col-md-6 text-right">
                      
                      <input type="hidden" name="role_group_id" value="{{@$_REQUEST['role_group_id']}}" >
                      <input type="hidden" name="menu_id" value="{{@$_REQUEST['menu_id']}}" >

                    <button type="submit" class="btn btn-primary btn-sm waves-effect">
                    <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
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
                      <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                    </a>
                  </div>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->

@endsection

@section('script')

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


      });


      
    </script>


@endsection

