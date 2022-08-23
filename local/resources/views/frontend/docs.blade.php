<?php
use App\Helpers\Frontend;
$check_kyc = Frontend::check_kyc(Auth::guard('c_user')->user()->user_name);
?>
@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')

@endsection
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="sub-title"><i class="fa fa-file-text"></i> @lang('message.registration_documents') </h4>
            </div>
            <div class="card-block table-border-style">

                {{-- @if ($check_kyc['status'] == 'fail') --}}
                @if ($check_kyc['status'] == '99999')
                    <h4 class="sub-title"><i class="fa fa-upload"></i> @lang('message.send_additional_documents') </h4>
                    <form action="{{ route('docs_upload') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">

                            <div class="col-sm-6">
                                @if ($registeredDocs->regis_doc1_status !== 1)
                                    <div class="m-t-2 col-sm-12 text-center">
                                        <img src="{{ asset('frontend/assets/images/piccardwatremark_.png') }}"
                                            id="preview_1" class="img-fluid" style="height: 180px">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label> @lang('message.ID_card_photo')</label>
                                            <input type="file" id="file_1" name="file_1" class="form-control">
                                        </div>

                                    </div>
                                @endif

                                @if ($registeredDocs->regis_doc2_status !== 1)
                                    <div class="m-t-2 col-sm-12 text-center">
                                        <img src="{{ asset('frontend/assets/images/หน้าตรง.jpg') }}"
                                            id="preview_2" style="height: 180px" class="img-fluid">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>@lang('message.straight_face_photo')</label>
                                            <input type="file" id="file_2" name="file_2"  class="form-control">
                                        </div>

                                    </div>
                                @endif


                            </div>

                            <div class="col-sm-6">
                                @if ($registeredDocs->regis_doc3_status !== 1)
                                    <div class="m-t-2 col-sm-12 text-center">
                                        <img src="{{ asset('frontend/assets/images/user_card_new.jpg') }}"
                                            id="preview_3"  style="height: 180px" class="img-fluid">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>@lang('message.holding_an_ID_card')</label>
                                            <input type="file" id="file_3" name="file_3" class="form-control">
                                        </div>
                                    </div>

                                @endif

                                @if ($registeredDocs->regis_doc4_status !== 1)
                                    <div class="m-t-2 col-sm-12 text-center">
                                        <img src="{{ asset('frontend/assets/images/BookBank-7.png') }}"
                                            id="preview_4" style="height: 180px" class="img-fluid">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>@lang('message.bank_account_photo')</label>
                                            <input type="file" id="file_4" name="file_4" class="form-control">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row justify-content-md-center">
                          <div class="col-md-12 text-center mb-2">
                          <button type="submit" class="btn btn-primary">Upload File</button>
                          </div>
                        </div>
                    </form>

                @else
                    {!! $check_kyc['html'] !!}

                @endif

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                {{-- <th width="100">#</th> --}}
                                <th>Docs</th>
                                <th width="200">Date</th>
                                <th width="200">Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>

                            @foreach ($data as $value)
                                <?php $i++;
                                if ($value->type == 1) {
                                    $type = __('message.ID_card_photo');
                                } elseif ($value->type == 2) {
                                    $type = __('message.straight_face_photo');
                                } elseif ($value->type == 3) {
                                    $type = __('message.holding_an_ID_card');
                                } elseif ($value->type == 4) {
                                    $type =  __('message.bank_account_photo');
                                } else {
                                    $type = __('message.other');
                                }
                                //0=ส่งมาแล้วรอตรวจสอบ-สีเทา, 1=ผ่าน-เขียว, 2=ไม่ผ่าน-สีแดง, 3=ยังไม่ส่ง-สีส้ม
                                if ($value->regis_doc_status == '1') {
                                    $status = "<span class='pcoded-badge label label-success'>".__('message.approved')."</span>";
                                } elseif ($value->regis_doc_status == '2') {
                                    $status = "<span class='pcoded-badge label label-danger'>".__('message.not_approved')."</span>";
                                } elseif ($value->regis_doc_status == '3') {
                                    $status = "<span class='pcoded-badge label label-warning'>".__('message.not_submitted_documents')."</span>";
                                } else {
                                    $status = "<span class='pcoded-badge label label-warning'>".__('message.pending_approval')."</span>";
                                }

                                ?>
                                <tr>
                                    {{-- <th scope="row">{{ $i }}</th> --}}
                                    <td>{{ $type }}</td>
                                    <td>{{ date('d/m/Y', strtotime($value->created_at)) }}</td>
                                    <td>{!! $status !!}</td>
                                    <td>{!! $value->comment !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>
                <p class="text-danger">* @lang('message.document_case') </p>


            </div>

        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $('#file_1').change(function() {
        var fileExtension = ['jpg', 'png', 'jpeg'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
            this.value = '';
            return false;
        } else {
            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                document.getElementById("preview_1").src = e.target.result;
            };
            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        }
    });

    $('#file_2').change(function() {
        var fileExtension = ['jpg', 'png', 'jpeg'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
            this.value = '';
            return false;
        } else {

            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                document.getElementById("preview_2").src = e.target.result;
            };
            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        }
    });

    $('#file_3').change(function() {
        var fileExtension = ['jpg', 'png', 'jpeg'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
            this.value = '';
            return false;
        } else {

            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                document.getElementById("preview_3").src = e.target.result;
            };
            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        }
    });

    $('#file_4').change(function() {
        var fileExtension = ['jpg', 'png', 'jpeg'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
            this.value = '';
            return false;
        } else {

            var reader = new FileReader();
            reader.onload = function(e) {
                // get loaded data and render thumbnail.
                document.getElementById("preview_4").src = e.target.result;
            };
            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);

        }


    });
</script>
@endsection
