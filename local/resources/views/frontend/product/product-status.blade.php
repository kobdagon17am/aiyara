      
@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')

<link rel="stylesheet" href="{{asset('public/css/bootstrap-slider.css')}}">
<script src="{{asset('public/js/qrcode/qrcode.js')}}"></script>
<script src="{{asset('public/js/qrcode/qrcode.min.js')}}"></script>
@endsection

<!-- Breadcomb area Start-->
<div class="breadcomb-area">
  <div class="container">
   <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
     <div class="breadcomb-list">
      <div class="row">
        <h2>คำสั่งซื้อที่ : 2020xxxxxxxxx</h2>

        <div id="qrcode" style=" width: 150px; height: 150px;"></div>
        <script type="text/javascript">
            new QRCode(document.getElementById("qrcode"), "http://jindo.dev.naver.com/collie");
        </script>
        <h6>วันที่สั่งซื้อ 30/04/2020 16:30:48</h6>
        <br/>
        <div class="col" style=" margin-left: 10%;">
            <input id="ex13" style="  width: 90%;    " type="text" data-slider-ticks="[0, 1, 2, 3, 4]" data-slider-ticks-snap-bounds="0" data-slider-ticks-labels='["กำลังดำเนินการชำระเงิน", "กำลังดำเนินการเตรียมสินค้า", "เตรียมจัดส่ง", "จัดส่งแล้ว", "ได้รับสินค้าแล้ว"]'/>

        </div>
        <!---   Product List  -->
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="breadcomb-wp">
             <div class="breadcomb-ctn">
                <br/>
                <div class="animation-img mg-b-15">
                    <img class="animate-one" src="{{asset('public/img/widgets/2.png')}}" alt="" />
                </div>
                <p>สินค้าชิ้นที่ #1</p>  
                <p>จำนวน 1 qty.</p>
                <p>ราคา  ฿1,000.00</p>
            </div>
        </div>
    </div>
    <!---   Product List  -->
    <!---   Product List  -->
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="breadcomb-wp">
         <div class="breadcomb-ctn">
            <br/>
            <div class="animation-img mg-b-15">
                <img class="animate-one" src="{{asset('public/img/widgets/2.png')}}" alt="" />
            </div>
            <p>สินค้าชิ้นที่ #2</p>  
            <p>จำนวน 1 qty.</p>
            <p>ราคา  ฿1,000.00</p>
        </div>
    </div>
</div>
<!---   Product List  -->
<!---   Product List  -->
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
    <div class="breadcomb-wp">
     <div class="breadcomb-ctn">
        <br/>
        <div class="animation-img mg-b-15">
            <img class="animate-one" src="{{asset('public/img/widgets/2.png')}}" alt="" />
        </div>
        <p>สินค้าชิ้นที่ #3</p>  
        <p>จำนวน 1 qty.</p>
        <p>ราคา  ฿1,000.00</p>
    </div>
</div>
</div>
<!---   Product List  -->
</div>
</div>
</div>
</div>

<hr/>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
     <div class="breadcomb-list">
      <div class="row">
        <h2>คำสั่งซื้อที่ : 2020xxxxxxxxx</h2>

        <div id="qrcode2" style=" width: 150px; height: 150px;"></div>
        <script type="text/javascript">
            new QRCode(document.getElementById("qrcode2"), "12345678*90");
        </script>
        <h6>วันที่สั่งซื้อ 30/04/2020 16:30:48</h6>
        <br/>
        <div class="col" style=" margin-left: 10%;">
            <input id="ex14" style="  width: 90%;    " type="text" data-slider-ticks="[0, 1, 2, 3, 4]" data-slider-ticks-snap-bounds="0" data-slider-ticks-labels='["กำลังดำเนินการชำระเงิน", "กำลังดำเนินการเตรียมสินค้า", "เตรียมจัดส่ง", "จัดส่งแล้ว", "ได้รับสินค้าแล้ว"]'/>

        </div>
        <!---   Product List  -->
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <div class="breadcomb-wp">
             <div class="breadcomb-ctn">
                <br/>
                <div class="animation-img mg-b-15">
                    <img class="animate-one" src="{{asset('public/img/widgets/2.png')}}" alt="" />
                </div>
                <p>สินค้าชิ้นที่ #1</p>  
                <p>จำนวน 1 qty.</p>
                <p>ราคา  ฿1,000.00</p>
            </div>
        </div>
    </div>
    <!---   Product List  -->
    <!---   Product List  -->
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <div class="breadcomb-wp">
         <div class="breadcomb-ctn">
            <br/>
            <div class="animation-img mg-b-15">
                <img class="animate-one" src="{{asset('public/img/widgets/2.png')}}" alt="" />
            </div>
            <p>สินค้าชิ้นที่ #2</p>  
            <p>จำนวน 1 qty.</p>
            <p>ราคา  ฿1,000.00</p>
        </div>
    </div>
</div>
<!---   Product List  -->
<!---   Product List  -->
<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
    <div class="breadcomb-wp">
     <div class="breadcomb-ctn">
        <br/>
        <div class="animation-img mg-b-15">
            <img class="animate-one" src="{{asset('public/img/widgets/2.png')}}" alt="" />
        </div>
        <p>สินค้าชิ้นที่ #3</p>  
        <p>จำนวน 1 qty.</p>
        <p>ราคา  ฿1,000.00</p>
    </div>
</div>
</div>
<!---   Product List  -->
</div>
</div>
</div>
</div>
</div>
</div>


@endsection
@section('js')

<script src="{{asset('public/js/bootstrap-slider.js')}}"></script>
<script type="text/javascript">
    var slider = new Slider("#ex13", {
        ticks: [0, 1, 2, 3, 4],
        ticks_labels: ['กำลังดำเนินการชำระเงิน', 'กำลังดำเนินการเตรียมสินค้า', 'เตรียมจัดส่ง', 'จัดส่งแล้ว', 'ได้รับสินค้าแล้ว'],
        ticks_snap_bounds: 0,
        tooltip: 'hide',
        enabled: false,
        value: 2
    });

    var slider2 = new Slider("#ex14", {
        ticks: [0, 1, 2, 3, 4],
        ticks_labels: ['กำลังดำเนินการชำระเงิน', 'กำลังดำเนินการเตรียมสินค้า', 'เตรียมจัดส่ง', 'จัดส่งแล้ว', 'ได้รับสินค้าแล้ว'],
        ticks_snap_bounds: 0,
        tooltip: 'hide',
        enabled: false,
        value: 3
    });
    document.getElementById("ex13").disabled = true;
    document.getElementById("ex14").disabled = true;
</script>
@endsection
