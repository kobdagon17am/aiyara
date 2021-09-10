@extends('frontend.layouts.customer.customer_salepage')

@section('title')
    AIMMURA
@endsection

@section('css')

    <style>
        .bg_product {
            background-image: url("{{ asset('frontend/salepage/Aimmura/bg_product.png') }}");
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

    {!! $rs['data']->js_page_2 !!}
@endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <img src="{{ asset('frontend/salepage/Aimmura/01.jpg') }}"
                class="img-fluid" alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง">
        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/Aimmura/02.jpg') }}"
                class="img-fluid" alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง">
        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/Aimmura/03.jpg') }}"
                class="img-fluid" alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง">
        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/Aimmura/04.jpg') }}"
                class="img-fluid" alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง">
        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/Aimmura/05.jpg') }}"
                class="img-fluid" alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง">
        </div>

        <div class="row justify-content-center mt-2">
            <img src="{{ asset('frontend/salepage/Aimmura/06.jpg') }}"
                class="img-fluid" alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง">
        </div>


        <div class="row justify-content-center mt-2">
          {{-- <a href="{{ $url }}/aiyarashop/product-detail/2/18/{{ $rs['data']->user_name }}" target="_blank" >
            <img src="{{ asset('frontend/salepage/Aimmura/07.jpg') }}"
                class="img-fluid" alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง">
          </a> --}}
          <img src="{{ asset('frontend/salepage/Aimmura/07.jpg') }}">
        </div>

        <div class="row justify-content-center mt-2">
          <a href="{{ $url }}/aiyarashop/product-detail/2/14/{{ $rs['data']->user_name }}" target="_blank" >
            <img src="{{ asset('frontend/salepage/Aimmura/08.jpg') }}"
                class="img-fluid" alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง">
          </a>
        </div>

        <div class="row justify-content-center mt-2">
          <a href="{{ $url }}/aiyarashop/product-detail/2/17/{{ $rs['data']->user_name }}" target="_blank" >
            <img src="{{ asset('frontend/salepage/Aimmura/09.jpg') }}"
                class="img-fluid" alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง">
          </a>
        </div>

        <div class="row justify-content-center mt-2">
          <a href="{{ $url }}/aiyarashop/product-detail/2/19/{{ $rs['data']->user_name }}" target="_blank" >
            <img src="{{ asset('frontend/salepage/Aimmura/10.jpg') }}"
                class="img-fluid" alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง">
              </a>
        </div>

    </div>


    <div class="container bg_product mt-2">
        <!-- Three columns of text below the carousel -->
        <p style="margin-bottom: -3px;"><span style="color:#28a745;font-size:3.5rem;font-weight: 600;">เริ่มต้น</span> <span
                style="color:#3a3939;font-size:2rem;font-weight: 600;">สุขภาพดี</span>
        <h2 style="color:#3a3939;font-weight: 600;">ผลิตภัณฑ์ของแท้จากบริษัท</h2>
        </p>

        <div class="row text-center mt-2 mb-5">
          <div class="col-6 col-sm-6 col-lg-3">
            <img src="{{ asset('frontend/salepage/Aimmura/aimmura_19.png') }}" class="img-fluid" alt="Responsive image">
            <h2>2,100 บาท</h2>

            <a class="btn btn-success btn-md" href="#!" style="font-size: 23px"> สั่งซื้อ </a>
        </div><!-- /.col-lg-4 -->


            <div class="col-6 col-sm-6 col-lg-3">
                <img src="{{ asset('frontend/salepage/Aimmura/aimmura.png') }}" class="img-fluid" alt="Responsive image">
                <h2>1,550 บาท</h2>

                <a class="btn btn-success btn-md" href="{{ $url }}/aiyarashop/product-detail/2/18/{{ $rs['data']->user_name }}" target="_blank" style="font-size: 23px"> สั่งซื้อ </a>
            </div><!-- /.col-lg-4 -->
            <div class="col-6 col-sm-6 col-lg-3">
                <img src="{{ asset('frontend/salepage/Aimmura/Aimmura X+Box.png') }}" class="img-fluid"
                    alt="Responsive image">
                <h2>3,000 บาท</h2>

                <p><a class="btn btn-success" href="{{ $url }}/aiyarashop/product-detail/2/14/{{ $rs['data']->user_name }}" target="_blank" style="font-size: 23px"> สั่งซื้อ </a></p>
            </div><!-- /.col-lg-4 -->
            <div class="col-6 col-sm-6 col-lg-3">
                <img src="{{ asset('frontend/salepage/Aimmura/aimmura-E_box.png') }}" class="img-fluid"
                    alt="Responsive image">
                <h2>1,900 บาท</h2>

                <p><a class="btn btn-success" href="{{ $url }}/aiyarashop/product-detail/2/17/{{ $rs['data']->user_name }}" target="_blank" style="font-size: 23px"> สั่งซื้อ </a></p>
            </div><!-- /.col-lg-4 -->

            <div class="col-6 col-sm-6 col-lg-3">
                <img src="{{ asset('frontend/salepage/Aimmura/aimmura-V_box.png') }}" class="img-fluid"
                    alt="Responsive image">
                <h2>2,000 บาท</h2>
                <p><a class="btn btn-success" href="{{ $url }}/aiyarashop/product-detail/2/19/{{ $rs['data']->user_name }}" target="_blank" style="font-size: 23px"> สั่งซื้อ </a></p>
            </div><!-- /.col-lg-4 -->
        </div><!-- /.row -->


        <div class="row mt-4">
            <div class="col-6 mt-2"> <img src="{{ asset('frontend/salepage/Aimmura/bg_tranfer_1.png') }}"
                    class="img-fluid" alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง"></div>
            <div class="col-6 text-right"> <img src="{{ asset('frontend/salepage/Aimmura/bg_tranfer_2.png') }}"
                    class="img-fluid" alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง"></div>

        </div>

        <div class="row justify-content-center">
          <img src="{{ asset('frontend/salepage/Aimmura/hr.png') }}" class="img-fluid"
              alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง">
      </div>

    </div>


    {{-- <div class="container marketing" style="margin-top: 10px">
             <hr class="featurette-divider">
            <div class="row featurette">
                <div class="col-md-7">
                    <h2 class="featurette-heading"> First featurette heading. <span class="text-muted">It’ll blow your
                            mind.</span></h2>
                    <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis
                        euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus,
                        tellus ac cursus commodo.</p>
                </div>
                <div class="col-md-5">
                    <img src="http://website.aiyara.co.th/wp-content/uploads/2020/10/สื่อออนไลน์-โฟมล้างหน้าไอลดา-01-copy.jpg"
                        alt="ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง" class="img-thumbnail">ปัจจัยสิ่งแวดล้อมและพฤติกรรมเสี่ยง-02.jpg
                </div>
            </div>

           <hr class="featurette-divider">

            <div class="row featurette">
                <div class="col-md-7 order-md-2">
                    <h2 class="featurette-heading">Oh yeah, it’s that good. <span class="text-muted">See for
                            yourself.</span></h2>
                    <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis
                        euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus,
                        tellus ac cursus commodo.</p>
                </div>
                <div class="col-md-5 order-md-1">

                    <img src="http://website.aiyara.co.th/wp-content/uploads/2020/10/06-S__13918235-โชค.jpg" alt="..."
                        class="img-thumbnail">
                </div>
            </div>

            <hr class="featurette-divider p-2">

           <div class="row featurette">
                <div class="col-md-7">
                    <h2 class="featurette-heading">And lastly, this one. <span class="text-muted">Checkmate.</span></h2>
                    <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis
                        euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus,
                        tellus ac cursus commodo.</p>
                </div>
                <div class="col-md-5">
                    <img src="http://website.aiyara.co.th/wp-content/uploads/2020/10/05-S__57335986-ก้อย.jpg" alt="..."
                        class="img-thumbnail">
                </div>
            </div>

         <hr class="featurette-divider">

            <!-- /END THE FEATURETTES -->

        </div><!-- /.container --> --}}


    <!-- FOOTER -->
    <footer class="container bg_product">
        {{-- <p class="float-right"><a href="#">Back to top</a></p>
    <p>&copy; 2017-2020 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p> --}}
        <div class="row justify-content-center mt-2">

            @if ($rs['data']->url_fb)
                <a href="{{ $rs['data']->url_fb }}"> <img src="{{ asset('frontend/salepage/img/FB.png') }}"
                        style="margin: 5px;" class="img-fluid" width="50" alt="..."> </a>
            @endif
            @if ($rs['data']->url_ig)
                <a href="{{ $rs['data']->url_ig }}"> <img src="{{ asset('frontend/salepage/img/IG.png') }}"
                        style="margin: 5px;" class="img-fluid" width="50" alt="..."> </a>
            @endif
            @if ($rs['data']->url_line)
                <a href="{{ $rs['data']->url_line }}"> <img src="{{ asset('frontend/salepage/img/line.png') }}"
                        style="margin: 5px;" class="img-fluid" width="50" alt="..."> </a>
            @endif

        </div>
        @if ($rs['data']->tel_number)
            <div class="row justify-content-center" style="margin-top: 10px">
                <a href="tel:{{ $rs['data']->tel_number }}" class="btn btn-success"><i class="fa fa-phone-alt"></i>
                    <b>{{ $rs['data']->tel_number }}</b></a>
            </div>
        @endif

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
            <a href="{{ $rs['data']->url_line }}" class="buttons" title="line" data-toggle="tooltip"
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

        <a href="javascript:void(0)" class="buttons main-button float-action-button_ho" title="Contact" data-toggle="tooltip"
            data-placement="left">
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
