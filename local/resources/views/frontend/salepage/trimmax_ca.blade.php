@extends('frontend.layouts.customer.customer_salepage')

@section('title')
    TRIMMAX
@endsection

@section('css')

    <style>
        .bg_product {
            background-image: url("{{ asset('frontend/salepage/TrimMax/bg.png') }}");
            /* Full height */
            height: 100%;
            /* Center and scale the image nicely */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            /* max-width: 1300px;
          padding-right: 6%;
          padding-left: 6%; */
        }

    </style>

{!! $rs['data']->js_page_6 !!}
@endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <img src="{{ asset('frontend/salepage/TrimMax_ca/01.jpg') }}"
                class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">
        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/TrimMax_ca/02.jpg') }}"
                class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">
        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/TrimMax_ca/03.jpg') }}"
                class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">
        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/TrimMax_ca/04.jpg') }}"
                class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">
        </div>

        <div class="row justify-content-center mt-2">

            <img src="{{ asset('frontend/salepage/TrimMax_ca/05.jpg') }}"
                class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">

        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/TrimMax_ca/06.jpg') }}"
                class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">
        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/TrimMax_ca/07.jpg') }}"
                class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">
        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/TrimMax_ca/08.jpg') }}"
                class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">
        </div>

        <div class="row justify-content-center mt-2">

            <img src="{{ asset('frontend/salepage/TrimMax_ca/09.jpg') }}"
                class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">

        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/TrimMax_ca/10.jpg') }}"
                class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">
        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/TrimMax_ca/11.jpg') }}"
                class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">
        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/TrimMax_ca/12.jpg') }}"
                class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">
        </div>

        <div class="row justify-content-center mt-2">

            <img src="{{ asset('frontend/salepage/TrimMax_ca/13.jpg') }}"
                class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">

        </div>

        <div class="row justify-content-center mt-2">

          <img src="{{ asset('frontend/salepage/TrimMax_ca/14.jpg') }}"
              class="img-fluid" alt="เครื่องดื่มเพื่อสุขภาพเกรดพรีเมี่ยม">

      </div>

    </div>


    <!-- FOOTER -->
    <footer class="d-flex flex-wrap align-items-center py-3 my-2 border-top" style="background-color: #28a745;">
      <div class="container">
          <div class="row justify-content-md-center">

              <div class="col-md-auto text-center">
                @if ($rs['data']->profile_img)
                <img src="{{ asset('local/public/profile_customer/' . $rs['data']->profile_img) }}" width="100"
                    class="img-fluid" alt="Member" style="margin-top: 15px">
            @else
                <img src="{{ asset('local/public/images/ex.png') }}" class="img-fluid" width="100"
                    alt="Member" style="margin-top: 15px">
            @endif
                <div class="justify-content-center" style="margin-top: 10px">

                  <b style="color: #fff">สนใจสมัครสมาชิกติดต่อ</b>
                  <p style="color: #fff">คุณ {{ $rs['data']->first_name }} {{ $rs['data']->last_name }} รหัส {{ $rs['data']->user_name }}
                  @if ($rs['data']->email)
                  <br><b>Email :</b> {{ $rs['data']->email }}
                  @endif
                  </p>
                  @if ($rs['data']->url_fb)
                  <a href="{{ $rs['data']->url_fb }}"> <img src="{{ asset('frontend/salepage/img/FB.png') }}"
                          style="margin: 5px;" class="img-fluid" width="30" alt="..."> </a>
              @endif
              @if ($rs['data']->url_ig)
                  <a href="{{ $rs['data']->url_ig }}"> <img src="{{ asset('frontend/salepage/img/IG.png') }}"
                          style="margin: 5px;" class="img-fluid" width="30" alt="..."> </a>
              @endif
              @if ($rs['data']->url_line)
                  <a href="{{ $rs['data']->url_line }}"> <img
                          src="{{ asset('frontend/salepage/img/line.png') }}" style="margin: 5px;"
                          class="img-fluid" width="30" alt="..."> </a>
              @endif
              <br>

              @if ($rs['data']->tel_number)
              <a href="tel:{{ $rs['data']->tel_number }}" class="btn btn-success"><i class="fa fa-phone-alt"></i>
                  <b>{{ $rs['data']->tel_number }}</b></a>
              </div>
            @endif
                </div>
              </div>

          </div>

      </div>


  </footer>


    <nav class="float-action-button">

        @if ($rs['data']->url_fb)
            <a href="{{ $rs['data']->url_fb }}" class="buttons" title="Facebook" data-toggle="tooltip"
                data-placement="left">
                <img src="{{ asset('frontend/salepage/img/FB.png') }}" style="margin-top: -16px;" class="img-fluid"
                    width="50" alt="...">
            </a>
        @endif
        @if ($rs['data']->url_ig)
            <a href="{{ $rs['data']->url_ig }}" class="buttons" title="IG" data-toggle="tooltip" data-placement="left">
                <img src="{{ asset('frontend/salepage/img/IG.png') }}" style="margin-top: -16px;" class="img-fluid"
                    width="50" alt="...">
            </a>
        @endif
        @if ($rs['data']->url_line)
        <a href="https://line.me/ti/p/{{ $rs['data']->url_line }}" class="buttons" title="line" data-toggle="tooltip"
                data-placement="left">
                <img src="{{ asset('frontend/salepage/img/line.png') }}" style="margin-top: -16px;" class="img-fluid"
                    width="50" alt="...">
            </a>
        @endif
        @if ($rs['data']->tel_number)
            <a href="tel:{{ $rs['data']->tel_number }}" class="buttons" title="Phone" data-toggle="tooltip"
                data-placement="left">
                <svg class="ico_d" width="40" height="40" viewBox="0 0 39 39" fill="none" xmlns="http://www.w3.org/2000/svg"
                    style="margin-top: -16px;transform: rotate(0deg);">
                    <circle class="color-element" cx="19.4395" cy="19.4395" r="19.4395" fill="#03E78B"></circle>
                    <path
                        d="M19.3929 14.9176C17.752 14.7684 16.2602 14.3209 14.7684 13.7242C14.0226 13.4259 13.1275 13.7242 12.8292 14.4701L11.7849 16.2602C8.65222 14.6193 6.11623 11.9341 4.47529 8.95057L6.41458 7.90634C7.16046 7.60799 7.45881 6.71293 7.16046 5.96705C6.56375 4.47529 6.11623 2.83435 5.96705 1.34259C5.96705 0.596704 5.22117 0 4.47529 0H0.745882C0.298353 0 5.69062e-07 0.298352 5.69062e-07 0.745881C5.69062e-07 3.72941 0.596704 6.71293 1.93929 9.3981C3.87858 13.575 7.30964 16.8569 11.3374 18.7962C14.0226 20.1388 17.0061 20.7355 19.9896 20.7355C20.4371 20.7355 20.7355 20.4371 20.7355 19.9896V16.4094C20.7355 15.5143 20.1388 14.9176 19.3929 14.9176Z"
                        transform="translate(9.07179 9.07178)" fill="white"></path>
                </svg>
            </a>
        @endif

        <a href="javascript:void(0)" class="buttons main-button float-action-button_ho" title="Contact"
            data-toggle="tooltip" data-placement="left">
            <i class="fa fa-times" style="color: #fff;"></i>
            <i class="fa fa-phone-volume"></i>
        </a>
    </nav>

@endsection
@section('js')
    <script>
        // call bootstrap tooltip
        $(function() {
            $('body').tooltip({
                selector: '[data-toggle="tooltip"]',
                container: 'body'
            });
        });

    </script>


@endsection
