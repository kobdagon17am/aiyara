@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')
<style type="text/css">
  /*table.dataTable thead {background-color:blue !important;}*/
</style>
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> CRM  </h4>
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
      $sA = '';
    }else{
      $role_group_id = \Auth::user()->role_group_id_fk;
      $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      $sC = @$menu_permit->c==1?'':'display:none;';
      $sU = @$menu_permit->u==1?'':'display:none;';
      $sD = @$menu_permit->d==1?'':'display:none;';
      $sA = @$menu_permit->can_answer==1?'':'display:none;';
    }
   ?>

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.crm.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.crm.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                      <div class="myBorder">

                        <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label"> หมวดหลัก : * </label>
                            <div class="col-md-8">
                              <select name="role_group_id_fk" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                  @if(@$sMainGroup)
                                    @foreach(@$sMainGroup AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->role_group_id_fk)?'selected':'' }} >{{$r->role_name}}</option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>


                          <div class="form-group row">
                            <label for="example-text-input" class="col-md-3 col-form-label"> หมวดย่อย : * </label>
                            <div class="col-md-8">
                              <select name="crm_gettopic_id" class="form-control select2-templating " required >
                                <option value="">Select</option>
                                  @if(@$sCrm_topic)
                                    @foreach(@$sCrm_topic AS $r)
                                      <option value="{{$r->id}}" {{ (@$r->id==@$sRow->crm_gettopic_id)?'selected':'' }} >{{$r->txt_desc}}</option>
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
                                        {{$r->prefix_name}}{{$r->first_name}} 
                                        {{$r->last_name}}
                                      </option>
                                    @endforeach
                                  @endif
                              </select>
                            </div>
                          </div>



                             <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">เลขใบรับเรื่อง :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ @$sRow->subject_receipt_number }}" name="subject_receipt_number"  >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">วันที่รับเรื่อง :</label>
                                <div class="col-md-3">
                                  <?php // echo $sRow->receipt_date ?>
                                    <input class="form-control receipt_date " type="text" value="{{ @$sRow->receipt_date }}" name="receipt_date" autocomplete="off" >
                                </div>
                            </div>

                              <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">หัวข้อที่ลูกค้าแจ้ง :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" value="{{ @$sRow->topics_reported }}" name="topics_reported"  >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">รายละเอียดที่รับเรื่อง :</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" rows="5" name="contact_details" >{{@$sRow->contact_details}}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">ผู้รับเรื่อง :</label>
                                <div class="col-md-8">

                                	@if( empty($sRow) )
                                		<input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                    	<input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="subject_recipient" >
                    									@else
                    										<input class="form-control" type="text" value="{{$subject_recipient_name}}" readonly style="background-color: #f2f2f2;" >
                                    	<input class="form-control" type="hidden" value="{{ @$sRow->subject_recipient }}" name="subject_recipient" >
									                 @endif
                                    
                                </div>
                            </div>


                          <div class="form-group row">
                                <label for="example-text-input" class="col-md-3 col-form-label">ผู้ดำเนินการ(User Login):</label>
                                <div class="col-md-8">

                                  @if( empty($sRow) )
                                    <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                                      <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="operator" >
                                      @else
                                        <input class="form-control" type="text" value="{{@$operator_name}}" readonly style="background-color: #f2f2f2;" >
                                      <input class="form-control" type="hidden" value="{{ @$sRow->operator }}" name="operator" >
                                   @endif
                                    
                                </div>
                            </div>


                  <div class="form-group row" style="display: none;">
                    <label for="example-text-input" class="col-md-3 col-form-label">วันที่อัพเดตล่าสุด :</label>
                    <div class="col-md-3">
                      <input class="form-control" type="date" value="{{ @$sRow->last_update }}" name="last_update" >
                    </div>
                  </div>

                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/crm") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </div>


                  <hr> 

                    <div class="myBorder">
                      <div style="">
                        <div class="form-group row">
                          <div class="col-md-12" style="{{@$sA}}" > 
                            <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.crm_answer.create') }}/{{@$sRow->id}}" style="float: right;">
                              <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม การตอบคำถาม</span>
                            </a>
                            <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รายการตอบคำถาม  </span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-12">
                            <table id="data-table-answer" class="table table-bordered dt-responsive" style="width: 100%;">
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="myBorder">

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">สถานะการปิดการรับเรื่อง :</label>
                    <div class="col-md-8 mt-2">
                      <div class="custom-control custom-switch">
                        @if( empty($sRow) )
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status_close_job" value="1" >
                        @else
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status_close_job" value="1" {{ ( @$sRow->status_close_job=='1')?'checked':'' }}>
                        @endif
                          <label class="custom-control-label" for="customSwitch">ปิดการรับเรื่อง</label>
                      </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">สถานะ :</label>
                    <div class="col-md-8 mt-2">
                      <div class="custom-control custom-switch">
                        @if( empty($sRow) )
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" checked >
                        @else
                          <input type="checkbox" class="custom-control-input" id="customSwitch" name="status" value="1" {{ ( @$sRow->status=='1')?'checked':'' }}>
                        @endif
                          <label class="custom-control-label" for="customSwitch">เปิดใช้งาน/แสดง</label>
                      </div>
                    </div>
                </div>
                    </div>

                             <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/crm") }}">
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
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->
@section('script')

<script type="text/javascript">

            var role_group_id = "{{@$role_group_id?@$role_group_id:0}}"; 
            var menu_id = sessionStorage.getItem("menu_id");

            var user_id = "{{\Auth::user()->id}}";
            var crm_id_fk = "{{@$sRow->id?@$sRow->id:'999999999999999'}}"; //alert(crm_id_fk);
            var oTable;

            $(function() {
                oTable = $('#data-table-answer').DataTable({
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
                            url: '{{ route('backend.crm_answer.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['crm_id_fk'] = crm_id_fk ;
                                    oData = d;
                                  },
                              method: 'POST',
                            },
                    columns: [
                        // {data: 'id', title :'ID', className: 'text-center w50'},
                        // {data: 'txt_answer', title :'<center>คำตอบ</center>', className: 'text-center font-size-16 '},
                        {data: 'txt_answer',   title :'<center>คำตอบ</center>', className: 'text-left font-size-16 th_ ',render: function(d) {
                           return '<span class="badge font-size-16">'+d+'</span>';
                        }},
                        {data: 'level_class', title :'<center>Class</center>', className: 'text-center font-size-14 '},
                        // {data: 'level_class',   title :'<center>Class</center>', className: 'text-center ',render: function(d) {
                        //    return '<span class="badge badge-pill badge-soft-success font-size-16">'+d+'</span>';
                        // }},

                        {data: 'respondent_name', title :'<center>ผู้ตอบคำถาม</center>', className: 'text-center'},
                        {data: 'date_answer', title :'<center>วันที่ตอบคำถาม</center>', className: 'text-center'},
                        {data: 'id', title :'Tools', className: 'text-center w60'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){

                  // alert(aData['respondent']);
                    // var sU = (aData['respondent']==user_id)?'':'display:none;';
                    // var sD = (aData['respondent']==user_id)?'':'display:none;';
                    if((aData['respondent']==user_id)){
                        var sA = "{{$sA}}";
                    }else{
                        var sA = 'display:none;';
                    }

                      $('td:last-child', nRow).html(''
                        + '<a href="{{ route('backend.crm_answer.index') }}/'+aData['id']+'/edit'+ '?role_group_id=' + role_group_id + '&menu_id=' + menu_id +'" class="btn btn-sm btn-primary" style="'+sA+'" ><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.crm_answer.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete" style="'+sA+'" ><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });
              
</script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" />

 <script>
      $('.receipt_date').datetimepicker({
          value: '',
          rtl: false,
          format: 'Y-m-d H:i',
          formatTime: 'H:i',
          formatDate: 'Y-m-d H:i',
          monthChangeSpinner: true,
          closeOnTimeSelect: true,
          closeOnWithoutClick: true,
          closeOnInputClick: true,
          openOnFocus: true,
          timepicker: true,
          datepicker: true,
          weeks: false,
          minDate: 0,
      });



</script>

@endsection



@endsection
