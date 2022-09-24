@extends('frontend.layouts.customer.customer_app')
@section('css')
    <style>
        code {
            color: #009688 !important;
        }

    </style>
@endsection

@section('conten')

    <div class="row">


        <div class="col-md-5 col-xl-5">
            <div class="card">


                <div class="card-header">
                    <h5 class="card-header-text">Setting Salepage All </h5>
                    @if ($canAccess)
                        <button id="edit-Contact" type="button" onclick="save_contact()"
                            class="btn btn-primary waves-effect waves-light f-right">
                            <i class="icofont icofont-edit"></i> Save
                        </button>
                    @endif
                </div>

                <div class="card-block">


                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-group input-group-primary">
                                <span class="input-group-addon">
                                    <i class="ti-facebook"></i>
                                </span>
                                <input type="text" id="fb" class="form-control"
                                    placeholder="https://www.facebook.com/aiyaracorp" value="{{ @$data->url_fb }}">
                            </div>
                        </div>
                    </div>

                    <div class="input-group input-group-danger">
                        <span class="input-group-addon">
                            <i class="ti-instagram"></i>
                        </span>
                        <input type="text" id="ig" class="form-control" placeholder="https://www.instagram.com/aiyaracorp"
                            value="{{ @$data->url_ig }}">
                    </div>

                    <div class="input-group input-group-success">
                        <span class="input-group-addon">
                            {{-- <i class="icofont icofont-volume-off"></i> --}}
                            Line
                        </span>
                        <input type="text" id="line" class="form-control" placeholder="LineID"
                            value="{{ @$data->url_line }}">
                    </div>

                    <div class="input-group input-group-success">
                        <span class="input-group-addon">
                            <i class="fa fa-phone"></i>
                        </span>
                        <input type="text" id="tel_number" class="form-control" placeholder="Tel Number"
                            value="{{ @$data->tel_number }}">
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-header-text mb-0">Setting Salepage Name URL</h5>
                        </div>
                        <div class="card-block">
                            <div class="form-group">
                                <?php

                                if (empty($data->name_s1)) {
                                    $url_s1 = Auth::guard('c_user')->user()->user_name;
                                } else {
                                    $url_s1 = $data->name_s1;
                                }

                                ?>
                                <label>Example Url :</label> <code>{{ url($url_s1 . '/1') }}</code>
                                <div class="input-group input-group-button mb-0">

                                    <input type="text" class="form-control" name="name_s1" id="name_s1"
                                        placeholder="Edit Name Url" value="{{ $url_s1 }}">
                                    <span class="input-group-addon btn btn-primary btn-save-url">
                                        <span class="">Edit Url</span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php
                                if (empty($data->name_s1)) {
                                    $url_s1 = Auth::guard('c_user')->user()->user_name;
                                } else {
                                    $url_s1 = $data->name_s1;
                                }

                                if (empty($data->name_s2)) {
                                    $url_s2 = Auth::guard('c_user')->user()->user_name;
                                } else {
                                    $url_s2 = $data->name_s2;
                                }

                                if (empty($data->name_s3)) {
                                    $url_s3 = Auth::guard('c_user')->user()->user_name;
                                } else {
                                    $url_s3 = $data->name_s3;
                                }

                                if (empty($data->name_s4)) {
                                    $url_s4 = Auth::guard('c_user')->user()->user_name;
                                } else {
                                    $url_s4 = $data->name_s4;
                                }

                                if (empty($data->name_s5)) {
                                    $url_s5 = Auth::guard('c_user')->user()->user_name;
                                } else {
                                    $url_s5 = $data->name_s5;
                                }

                                if (empty($data->name_s6)) {
                                    $url_s6 = Auth::guard('c_user')->user()->user_name;
                                } else {
                                    $url_s6 = $data->name_s6;
                                }

                                ?>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">

                                        <p> <label>1. Aiyara</label>
                                            {{-- <button type="button" class="btn btn-default waves-effect" data-toggle="tooltip" data-placement="top" title="" data-original-title="tooltip on top">Top
                              </button> --}}
                                            {{-- <code>{{ route('aiyara', $url_s1) }}</code> --}}
                                            <a href="#!" class="pcoded-badge label label-warning copy-to-clipboard"
                                                data-toggle="tooltip" data-placement="top"
                                                data-original-title="Copy Url : {{ url($url_s1 . '/1') }}" href="#!"
                                                data-url="{{ url($url_s1 . '/1') }}"><b stye="color:#000">Copy Url</b></a>
                                            <br>
                                            <label>2. Aimmura</label>
                                            {{-- <code>{{ route('aimmura', $url_s2) }}</code> --}}
                                            <a href="#!" class="pcoded-badge label label-warning copy-to-clipboard"
                                                data-toggle="tooltip" data-placement="top"
                                                data-original-title="Copy Url : {{ url($url_s2 . '/2') }}" href="#!"
                                                data-url="{{ url($url_s2 . '/2') }}"><b stye="color:#000">Copy Url</b></a>
                                            <br>

                                            <label>3. Cashewy Drink</label>
                                            {{-- <code>{{ route('cashewy', $url_s3) }}</code> --}}
                                            <a href="#!" class="pcoded-badge label label-warning copy-to-clipboard"
                                                data-toggle="tooltip" data-placement="top"
                                                data-original-title="Copy Url : {{ url($url_s3 . '/3') }}" href="#!"
                                                data-url="{{ url($url_s3 . '/3') }}"><b stye="color:#000">Copy
                                                    Url</b></a><br>


                                    </div>

                                    <div class="col-md-6">
                                        <p>
                                            <label>4. Aifacad</label>
                                            {{-- <code>{{ route('aifacad', $url_s4) }}</code> --}}
                                            <a href="#!" class="pcoded-badge label label-warning copy-to-clipboard"
                                                data-toggle="tooltip" data-placement="top"
                                                data-original-title="Copy Url : {{ url($url_s4 . '/4') }}" href="#!"
                                                data-url="{{ url($url_s4 . '/4') }}"><b stye="color:#000">Copy
                                                    Url</b></a>
                                            <br>

                                            <label>5. Alada</label>
                                            {{-- <code>{{ url('ailada.'/,' $url_s5) }}</code> --}}
                                            <a href="#!" class="pcoded-badge label label-warning copy-to-clipboard"
                                                data-toggle="tooltip" data-placement="top"
                                                data-original-title="Copy Url : {{ url($url_s5 . '/5') }}" href="#!"
                                                data-url="{{ url($url_s5 . '/5') }}"><b stye="color:#000">Copy
                                                    Url</b></a>
                                            <br>
                                            <label>6. TrimMax</label>
                                            {{-- <code>{{ url('trimma.'/'', $url_s6) }}</code> --}}
                                            <a href="#!" class="pcoded-badge label label-warning copy-to-clipboard"
                                                data-toggle="tooltip" data-placement="top"
                                                data-original-title="Copy Url : {{ url($url_s6 . '/6') }}" href="#!"
                                                data-url="{{ url($url_s6 . '/6') }}"><b stye="color:#000">Copy
                                                    Url</b></a>
                                            <br>
                                        </p>

                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <h5 class="card-header-text">{{trans('message.p_sale page set')}} </h5>
                    @if ($canAccess)
                        <button id="edit-Contact" type="button" onclick="save_type_register()"
                            class="btn btn-primary waves-effect waves-light f-right">
                            <i class="icofont icofont-edit"></i> Save
                        </button>
                    @endif
                </div>

                <div class="card-block">
                    <?php $url_registers = Auth::guard('c_user')->user()->user_name; ?>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-xl-12 m-b-30">
                            {{-- <h4 class="sub-title">Radio Fill Button</h4> --}}
                            <div class="form-radio">
                                <form>
                                    <div class="radio radio-inline">
                                        <label>
                                            <input type="radio" name="type_regis" value="A" @if (Auth::guard('c_user')->user()->registers_setting == 'A') checked="checked" @endif>
                                            <i class="helper"></i> A
                                        </label>
                                    </div>
                                    <div class="radio radio-inline">
                                        <label>
                                            <input type="radio" name="type_regis" value="B" @if (Auth::guard('c_user')->user()->registers_setting == 'B') checked="checked" @endif>
                                            <i class="helper"></i> B
                                        </label>
                                    </div>
                                    <div class="radio radio-inline">
                                        <label>
                                            <input type="radio" name="type_regis" value="C" @if (Auth::guard('c_user')->user()->registers_setting == 'C') checked="checked" @endif>
                                            <i class="helper"></i> C
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-sm-12">
                            <div class="input-group input-group-button">
                                <input type="text" class="form-control"
                                    value="{{ url('registermember/' . $url_registers) }}">
                                <span class="input-group-addon btn btn-primary" id="basic-addon10" >
                                    <span class="copy-to-clipboard" data-url="{{ url('registermember/' . $url_registers) }}">Copy Url</span>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>

        <div class="col-md-7 col-xl-7">



            <div class="card">
                {{-- <div class="card-header">
        <h5>Setting Salepage</h5>

      </div> --}}

                <div class="card-header" style="margin-bottom: -24px;">
                    <h5 class="card-header-text">Setting Salepage </h5>
                    @if ($canAccess)
                        <button type="button" onclick="save_js()" class="btn btn-primary waves-effect waves-light f-right">
                            <i class="icofont icofont-edit"></i> Save
                        </button>
                    @endif
                </div>

                <div class="card-block">


                    <div class="form-group row">
                        <label> Add Facebook Pixel Salepage Aiyara </label>
                        <textarea rows="5" cols="5" id="js_page_1" class="form-control language-javascript"
                            placeholder="Tag javascript in hearder Page Aiyara">@if (@$data->js_page_1){{ @$data->js_page_1 }}@endif</textarea>

                    </div>
                    <div class="form-group row">
                        <label> Add Facebook Pixel Salepage Aimmura </label>
                        <textarea rows="5" cols="5" id="js_page_2" class="form-control"
                            placeholder="Tag javascript in hearder Page Aimmura">@if (@$data->js_page_2){{ @$data->js_page_2 }}@endif</textarea>

                    </div>
                    <div class="form-group row">
                        <label> Add Facebook Pixel Salepage Cashewy Drink </label>
                        <textarea rows="5" cols="5" id="js_page_3" class="form-control"
                            placeholder="Tag javascript in hearder Page Cashewy Drink ">@if (@$data->js_page_3){{ @$data->js_page_3 }}@endif</textarea>

                    </div>
                    <div class="form-group row">
                        <label> Add Facebook Pixel Salepage Aifacad </label>
                        <textarea rows="5" cols="5" id="js_page_4" class="form-control"
                            placeholder="Tag javascript in hearder Page Aifacad">@if (@$data->js_page_4){{ @$data->js_page_4 }}@endif</textarea>

                    </div>

                    <div class="form-group row">
                        <label> Add Facebook Pixel Salepage Alada </label>
                        <textarea rows="5" cols="5" id="js_page_5" class="form-control"
                            placeholder="Tag javascript in hearder Page Alada">@if (@$data->js_page_5){{ @$data->js_page_5 }}@endif</textarea>

                    </div>

                    <div class="form-group row">
                        <label> Add Facebook Pixel Salepage TrimMax </label>
                        <textarea rows="5" cols="5" id="js_page_6" class="form-control"
                            placeholder="Tag javascript in hearder Page TrimMax">@if (@$data->js_page_6){{ @$data->js_page_6 }}@endif</textarea>

                    </div>

                </div>
            </div>

        </div>

    </div>




@endsection
@section('js')
    <!-- Masking js -->
    <script src="{{ asset('frontend/assets/pages/form-masking/inputmask.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/form-masking/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/form-masking/autoNumeric.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/form-masking/form-mask.js') }}"></script>


    <script src="{{ asset('frontend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/data-table/js/jszip.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/data-table/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/data-table/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('frontend/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script>
    <!-- Custom js -->
    <script src="{{ asset('frontend/assets/pages/data-table/js/data-table-custom.js') }}"></script>


    <script type="text/javascript">
        function save_contact() {

            Swal.fire({
                title: 'Do you want to save the changes?',
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Save`,
                // denyButtonText: `Don't save`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    fb = $('#fb').val();
                    ig = $('#ig').val();
                    line = $('#line').val();
                    tel_number = $('#tel_number').val();

                    $.ajax({
                            url: '{{ route('salepage/save_contact') }}',
                            type: 'POST',

                            data: {
                                _token: '{{ csrf_token() }}',
                                fb: fb,
                                ig: ig,
                                line: line,
                                'tel_number': tel_number
                            },
                        })
                        .done(function(data) {

                            if (data['status'] == 'success') {
                                Swal.fire('Saved!', '', 'success');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Saved Fail',
                                    // text: 'Something went wrong!',
                                    // footer: '<a href>Why do I have this issue?</a>'
                                })

                            }

                        })
                        .fail(function() {

                            Swal.fire({
                                icon: 'error',
                                title: 'Saved Fail',
                                // text: 'Something went wrong!',
                                // footer: '<a href>Why do I have this issue?</a>'
                            })
                            console.log("error");
                        })




                }
            })

        }

        function save_type_register() {

            Swal.fire({
                title: 'Do you want to save the changes?',
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Save`,
                // denyButtonText: `Don't save`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {

                    var radios = document.getElementsByName('type_regis');

                    for (var i = 0, length = radios.length; i < length; i++) {
                        if (radios[i].checked) {
                            // do whatever you want with the checked radio
                           var type = radios[i].value;
                        }
                    }
                    $.ajax({
                            url: '{{ route('salepage/save_type_register') }}',
                            type: 'POST',

                            data: {
                                _token: '{{ csrf_token() }}',
                                type: type,
                            },
                        })
                        .done(function(data) {

                            if (data['status'] == 'success') {
                                Swal.fire('Saved!', '', 'success');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Saved Fail',
                                    // text: 'Something went wrong!',
                                    // footer: '<a href>Why do I have this issue?</a>'
                                })

                            }

                        })
                        .fail(function() {

                            Swal.fire({
                                icon: 'error',
                                title: 'Saved Fail',
                                // text: 'Something went wrong!',
                                // footer: '<a href>Why do I have this issue?</a>'
                            })
                            console.log("error");
                        })

                }
            })

        }



        function save_js() {

            Swal.fire({
                title: 'Do you want to save the changes?',
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Save`,
                // denyButtonText: `Don't save`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    js_page_1 = $('#js_page_1').val();
                    js_page_2 = $('#js_page_2').val();
                    js_page_3 = $('#js_page_3').val();
                    js_page_4 = $('#js_page_4').val();

                    $.ajax({
                            url: '{{ route('salepage/save_js') }}',
                            type: 'POST',

                            data: {
                                _token: '{{ csrf_token() }}',
                                js_page_1: js_page_1,
                                js_page_2: js_page_2,
                                js_page_3: js_page_3,
                                js_page_4: js_page_4
                            },
                        })
                        .done(function(data) {

                            if (data['status'] == 'success') {
                                Swal.fire('Saved!', '', 'success');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Saved Fail',
                                    // text: 'Something went wrong!',
                                    // footer: '<a href>Why do I have this issue?</a>'
                                })

                            }

                        })
                        .fail(function() {

                            Swal.fire({
                                icon: 'error',
                                title: 'Saved Fail',
                                // text: 'Something went wrong!',
                                // footer: '<a href>Why do I have this issue?</a>'
                            })
                            console.log("error");
                        })




                }
            })

        }

        $('.copy-to-clipboard').each(function() {
            $(this).on('click', function() {
                const el = document.createElement('textarea');
                el.value = $(this).attr('data-url')
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);

                Swal.fire('Copy Url', el.value, 'success');
            })
        })

        $('input[name^="name_s"]').each(function() {
            $(this).on('input', function(e) {
                return e.target.value = e.target.value.replaceAll(' ', '-')
            })
        })

        $('.btn-save-url').each(function() {
            $(this).on('click', function() {
                saveUrl($(this).prev(), $(this).prev().val())
            })
        })

        function saveUrl(input, value) {


            let data = {
                _token: "{{ csrf_token() }}"
            }

            data['name_s1'] = value;
            data['name_s2'] = value;
            data['name_s3'] = value;
            data['name_s4'] = value;
            data['name_s5'] = value;
            data['name_s6'] = value;

            Swal.fire({
                    title: 'Do you want to save the changes?',
                    showCancelButton: true,
                    confirmButtonText: `Save`,
                })
                .then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('salepage/save_url') }}",
                            type: "POST",
                            data: data,
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Saved!', '', 'success');
                                    input.removeClass('is-invalid')
                                    input.parent().parent().find('.invalid-feedback').remove();
                                    location.reload();
                                }
                                if (response.fail) {
                                    alert('fails')
                                }
                            },
                            error: function(response) {
                                if (response.status === 422) {
                                    input.addClass('is-invalid')
                                    input.parent().parent().append(`
                <div class="invalid-feedback d-block">
                  ${response.responseJSON.errors[input.attr('name')]}
                </div>
              `)
                                }
                            }
                        })
                    }
                })

        }
    </script>

@endsection
