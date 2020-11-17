@extends('backend.layouts.master')

@section('title') Aiyara Planet @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18"> ตอบคำถาม </h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
              @if( empty($sRow) )
              <form action="{{ route('backend.crm_answer.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
              @else
              <form action="{{ route('backend.crm_answer.update', @$sRow->id ) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                <input name="_method" type="hidden" value="PUT">
              @endif
                {{ csrf_field() }}


                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">คำถาม : </label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" value="{{@$sRowNew->topics_reported}}" name="" readonly >
                        <input class="form-control" type="hidden" value="{{@$sRowNew->id}}" name="crm_id_fk"  >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">รายละเอียดคำถาม : </label>
                    <div class="col-md-10">
                        <textarea class="form-control" rows="5"  readonly >{{@$sRowNew->contact_details}}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">คำตอบ : * </label>
                    <div class="col-md-10">
                        <textarea class="form-control" rows="5" name="txt_answer" required >{{@$sRow->txt_answer}}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="example-text-input" class="col-md-2 col-form-label">วันที่ตอบ : * </label>
                    <div class="col-md-3">
                        <input class="form-control NumberOnly " type="date" value="{{ @$sRow->date_answer }}" name="date_answer" required>
                    </div>
                </div>

                 <div class="form-group row">
                      <label for="example-text-input" class="col-md-2 col-form-label">ผู้ตอบ (User Login) :</label>
                      <div class="col-md-10">

                        @if( empty($sRow) )
                          <input class="form-control" type="text" value="{{ \Auth::user()->name }}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ \Auth::user()->id }}" name="respondent" >
                            @else

                            <?php  if(@$sRow->respondent!=\Auth::user()->id){ 
                              // echo @$sRow->respondent;
                              // echo @\Auth::user()->id;
                              $url = "backend/crm/".@$sRowNew->id."/edit";
                              ?>
                              <script type="text/javascript">
                                var url = "<?=$url?>";
                                location.replace(url);
                              </script><?php

                              } ?>

                              <input class="form-control" type="text" value="{{$respondent_name}}" readonly style="background-color: #f2f2f2;" >
                            <input class="form-control" type="hidden" value="{{ @$sRow->respondent }}" name="respondent" >
                         @endif
                          
                      </div>
                  </div>


                <div class="form-group mb-0 row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary btn-sm waves-effect btnBack " href="{{ route('backend.crm.index') }}/{{@$sRowNew->id}}/edit" }}">
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
        </div>
    </div> <!-- end col -->
</div>
<!-- end row -->
@endsection

