@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">Course / Event</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">


              <div class="myBorder">   


              @if( empty($sRow) )
              <form action="{{ route('backend.course_event.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.course_event.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label">เลือกประเภท :</label>
                    <div class="col-md-9">
                         <select name="ce_type" class="form-control select2-templating " >
                         <option value="">Select</option>
                            @if(@$dsCe_type)
                                @foreach(@$dsCe_type AS $r)
                                    <option value="{{@$r->id}}" {{ (@$r->id==@$sRow->ce_type)?'selected':'' }} >{{@$r->txt_desc}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label">ชื่อกิจกรรม :</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" value="{{ @$sRow->ce_name }}" name="ce_name" required>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label">สถานที่จัดงาน :</label>
                    <div class="col-md-9">
                        <input class="form-control" type="text" value="{{ @$sRow->ce_place }}" name="ce_place" required>
                    </div>
                </div>           


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label">จำนวนบัตรสูงสุด :</label>
                    <div class="col-md-9">
                        <input class="form-control" type="number" value="{{ @$sRow->ce_max_ticket }}" name="ce_max_ticket" required>
                    </div>
                </div>   

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label">ราคาบัตร (หน่วย: บาทไทย) :</label>
                    <div class="col-md-9">
                        <input class="form-control" type="number" value="{{ @$sRow->ce_ticket_price }}" name="ce_ticket_price" required>
                    </div>
                </div> 


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label">วันเริ่มจำหน่าย (mm/dd/yyyy) :</label>
                    <div class="col-md-9">
                        <input class="form-control" type="date" value="{{ @$sRow->ce_sdate }}" name="ce_sdate" required >
                    </div>
                </div> 

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label">วันสิ้นสุดการจำหน่าย (mm/dd/yyyy) :</label>
                    <div class="col-md-9">
                        <input class="form-control" type="date" value="{{ @$sRow->ce_edate }}" name="ce_edate" required >
                    </div>
                </div> 

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label">คุณสมบัติของผู้จอง :</label>
                    <div class="col-md-9">
                         <select name="ce_features_booker" class="form-control select2-templating " >
                         <option value="">Select</option>
                            @if(@$dsCe_features_booker)
                                @foreach(@$dsCe_features_booker AS $r)
                                    <option value="{{@$r->id}}" {{ (@$r->id==@$sRow->ce_features_booker)?'selected':'' }} >{{@$r->txt_desc}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label">สมาชิก 1 คน  สามารถจองได้ (จำนวนบัตร) :</label>
                    <div class="col-md-9">
                         <select name="ce_can_reserve" class="form-control select2-templating " >
                         <option value="">Select</option>
                            @if(@$dsCe_can_reserve)
                                @foreach(@$dsCe_can_reserve AS $r)
                                    <option value="{{@$r->id}}" {{ (@$r->id==@$sRow->ce_can_reserve)?'selected':'' }} >{{@$r->txt_desc}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-md-3 col-form-label">การจำกัดจำนวน :</label>
                    <div class="col-md-9">
                         <select name="ce_limit" class="form-control select2-templating " >
                         <option value="">Select</option>
                            @if(@$dsCe_limit)
                                @foreach(@$dsCe_limit AS $r)
                                    <option value="{{@$r->id}}" {{ (@$r->id==@$sRow->ce_limit)?'selected':'' }} >{{@$r->txt_desc}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>



              <div class="form-group row">
                  <label for="example-text-input" class="col-md-3 col-form-label">Package ขั้นต่ำที่ซื้อได้ : </label>
                  <div class="col-md-9">
                    <select name="minimum_package_purchased" class="form-control select2-templating "  >
                      <option value="">Select</option>
                        @if(@$sPackage)
                          @foreach(@$sPackage AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->minimum_package_purchased)?'selected':'' }} >{{$r->dt_package}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-3 col-form-label">คุณวุฒิ reward ที่ซื้อได้ : </label>
                  <div class="col-md-9">
                    <select name="reward_qualify_purchased" class="form-control select2-templating "  >
                      <option value="">Select</option>
                        @if(@$sQualification)
                          @foreach(@$sQualification AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->reward_qualify_purchased)?'selected':'' }} >{{$r->business_qualifications}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-3 col-form-label">รักษาคุณสมบัติส่วนตัว : </label>
                  <div class="col-md-9">
                    <select name="keep_personal_quality" class="form-control select2-templating "  >
                      <option value="">Select</option>
                        @if(@$sPersonal_quality)
                          @foreach(@$sPersonal_quality AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->keep_personal_quality)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>


                <div class="form-group row">
                  <label for="example-text-input" class="col-md-3 col-form-label">รักษาคุณสมบัติท่องเที่ยว : </label>
                  <div class="col-md-9">
                    <select name="maintain_travel_feature" class="form-control select2-templating "  >
                      <option value="">Select</option>
                        @if(@$sTravel_feature)
                          @foreach(@$sTravel_feature AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->maintain_travel_feature)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>


                <div class="form-group row">
                  <label for="example-text-input" class="col-md-3 col-form-label">aistockist : </label>
                  <div class="col-md-9">
                    <select name="aistockist" class="form-control select2-templating "  >
                      <option value="">Select</option>
                        @if(@$sAistockist)
                          @foreach(@$sAistockist AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->aistockist)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="example-text-input" class="col-md-3 col-form-label">agency : </label>
                  <div class="col-md-9">
                    <select name="agency" class="form-control select2-templating "  >
                      <option value="">Select</option>
                        @if(@$sAgency)
                          @foreach(@$sAgency AS $r)
                            <option value="{{$r->id}}" {{ (@$r->id==@$sRow->agency)?'selected':'' }} >{{$r->txt_desc}}</option>
                          @endforeach
                        @endif
                    </select>
                  </div>
                </div>



                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/course_event") }}">
                          <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect">
                          <i class="bx bx-save font-size-16 align-middle mr-1"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </div>

              </form>
            </div>


@if( !empty($sRow) )
                    <div class="myBorder">
                      <div style="">
                        <div class="form-group row">
                          <div class="col-md-12">
                            
                            <a class="btn btn-info btn-sm mt-1" href="{{ route('backend.course_event_images.create') }}/{{@$sRow->id}}" style="float: right;" >
                              <i class="bx bx-plus align-middle mr-1"></i><span style="font-size: 14px;">เพิ่ม</span>
                            </a>
                            <span style="font-weight: bold;padding-right: 10px;"><i class="bx bx-play"></i> รูปภาพ  </span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-12">
                            <table id="data-table-images" class="table table-bordered dt-responsive" style="width: 100%;">
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="form-group mb-0 row">
                        <div class="col-md-6">
                          <a class="btn btn-secondary btn-sm waves-effect" href="{{ url("backend/course_event") }}">
                            <i class="bx bx-arrow-back font-size-16 align-middle mr-1"></i> ย้อนกลับ
                          </a>
                        </div>
                      </div>
                    </div>
@endif

            </div>
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->


@endsection

@section('script')

<script>

            $(function() {

              var course_event_id_fk = "{{@$sRow->id?@$sRow->id:0}}";

                oTable = $('#data-table-images').DataTable({
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
                            url: '{{ route('backend.course_event_images.datatable') }}',
                            data: function ( d ) {
                                    d.Where={};
                                    d.Where['course_event_id_fk'] = course_event_id_fk ;
                                    oData = d;
                                  },
                              method: 'POST',
                            },
                 
                    columns: [
                        {data: 'id', title :'ID', className: 'text-center w50'},
                        {data: 'img_path',   title :'<center>IMAGE</center>', className: 'text-center',render: function(d) {
                           return '<img src="'+d+'" width="150">';
                        }},
                        {data: 'img_default',   title :'<center>รูปหลัก</center>', className: 'text-center',render: function(d) {
                             return d==1?'Yes':'';
                        }},
                        {data: 'id', title :'Tools', className: 'text-center w60'}, 
                    ],
                    rowCallback: function(nRow, aData, dataIndex){
                      $('td:last-child', nRow).html(''
                        + '<a href="{{ route('backend.course_event_images.index') }}/'+aData['id']+'/edit" class="btn btn-sm btn-primary"><i class="bx bx-edit font-size-16 align-middle"></i></a> '
                        + '<a href="javascript: void(0);" data-url="{{ route('backend.course_event_images.index') }}/'+aData['id']+'" class="btn btn-sm btn-danger cDelete"><i class="bx bx-trash font-size-16 align-middle"></i></a>'
                      ).addClass('input');
                    }
                });
                $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e){
                  oTable.draw();
                });
            });

</script>

<script type="text/javascript">
  var menu_id = sessionStorage.getItem("menu_id");
    window.onload = function() {
    if(!window.location.hash) {
       window.location = window.location + '?menu_id=' + menu_id + '#menu_id=' + menu_id ;
    }
  }
</script>


@endsection
