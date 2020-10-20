@extends('layouts.promote.promote_app')
@section('conten')
    <div class="container-fluid nopan wow fadeInDown">
        <div class="row ">
            <div class="flexslider">
                <ul class="slides">
                    <li>
                        <div class="wrap_slidecaption">
                            <div class="slidecaption slideleft">
                                <h1 class="text00"> Time to Travel </h1>
                            </div>
                            <div class="slidecaption slideright">
                                <h2>Name ultrices pellentesque facilisis. </h2>
                            </div>
                        </div>
                        <img src="{{asset('public/webpromote/images/bg-01.jpg')}}" class="img-fluid" style="width: 100%;" />
                    </li>
                    <li>
                        <div class="wrap_slidecaption">
                            <div class="slidecaption slideleft">
                                <h1 class="text00"> Time to Travel </h1>
                            </div>
                            <div class="slidecaption slideright">
                                <h2>Name ultrices pellentesque facilisis. </h2>
                            </div>
                        </div>
                        <img src="{{asset('public/webpromote/images/bg-01.jpg')}}" class="img-fluid" style="width: 100%;" />
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid nopan wow fadeInDown">
        <div class="container wow fadeInDown">
            <div class="row">
                <div class="col-sm-3">
                    <div class="box-profile ">
                        <div class="promotions">
                            <h2 class="textporfile">Profile</h2>
                            <img src="{{asset('public/webpromote/images/title-h-line.png')}}" alt="" class="img-fluid">
                        </div>

                        <div class="image-cropper">
                            <img src="{{asset('public/webpromote/images/young.jpg')}}" alt="" class="img-fluid profile-pic">
                        </div>
                        <h2 class="text-nameprofile">กนกวรรณ เพรชทอง</h2>
                        <div class="textprofile">ทำงานที่ : บริษัททัวร์</div>
                        <div class="textprofile">อายุ : 26 ปี</div>
                        <div class="textprofile">เชื้อชาติ : ไทย</div>
                        <div class="textprofile">วันเกิด 9 ต.ค 1994</div>
                        <div class="textprofile">ที่อยู่ : กรุงเทพ</div>
                        <div class="textprofile">ภาษา : ไทย , อังกฤษ</div>
                        <a href="promote2">ข่าว</a>
                        <a href="promote">ท่องเที่ยว</a>
                        <a href="promote3">สินค้า</a>
                        <div class="center" style=" background-color: #00c454; color: white;">
                        ติดต่อซื้อสินค้า/ร่วมธุรกิจ<br/>
                        <select  class="center" style=" width: 90%; margin-left: 5%;">
                            <option>เลือกสินค้า</option>
                            <?php for($x=1;$x<=5;$x++) { ?>
                            <option>สินค้าโปรโมชั่นชุดที่ {{ $x }}</option>
                            <?php } ?>
                        </select>
                        <input class="center" style=" width: 90%; margin-left: 5%;" type="text" placeholder="ชื่อ-นามสกุล" />
                        <input class="center" style=" width: 90%; margin-left: 5%;" type="text" placeholder="เบอร์โทรศัพท์" />
                        <input class="center" style=" width: 90%; margin-left: 5%;" type="text" placeholder="อีเมล์" />
                        <textArea class="center" style=" width: 90%; height: 100px; margin-left: 5%;">รายละเอียดเพิ่มเติม</textArea>
                        <input class="center" style=" width: 90%; margin-left: 5%; background-color: #00c454; color: white;" type="button" value="ส่งข้อความ" />
                        <br/><br/>
                        </div>
                    </div>
                </div>

                <div class="col-sm-9 ">
                    <div class="box-2">
                        <div class="textupdat">Latest update</div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <a href="detail-Gallery">
                                    <img src="{{asset('public/webpromote/images/1_10871_02.jpg')}}" alt="" class="img-fluid">
                                    <div class="text-new">Text head </div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="detail-Gallery">
                                    <img src="{{asset('public/webpromote/images/shutterstock_583691665.jpg')}}" alt="" class="img-fluid">
                                    <div class="text-new">Text head</div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="detail-Gallery">
                                    <img src="{{asset('public/webpromote/images/unnamed.jpg')}}" alt="" class="img-fluid">
                                    <div class="text-new">Text head</div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="detail-Gallery">
                                    <img src="{{asset('public/webpromote/images/56339a3cec60adb92a8b45e6-2-full-ideas.jpg')}}" alt="" class="img-fluid">
                                    <div class="text-new">Text head</div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="detail-Gallery">
                                    <img src="{{asset('public/webpromote/images/iStock_000057679524_Small-1.jpg')}}" alt="" class="img-fluid">
                                    <div class="text-new">Text head</div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="detail-Gallery">
                                    <img src="{{asset('public/webpromote/images/leephe2.jpg')}}" alt="" class="img-fluid">
                                    <div class="text-new">Text head</div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="detail-Gallery">
                                    <img src="{{asset('public/webpromote/images/1438183527-crusoe1-o.jpg')}}" alt="" class="img-fluid">
                                    <div class="text-new">Text head</div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="detail-Gallery">
                                    <img src="{{asset('public/webpromote/images/HCtHFA7ele6Q2dUK4wd8KSt8pOwDg1r2FiaUqTu8nGBx40Hj8IqlT0SBhLNlRw3JxA.jpg')}}" alt="" class="img-fluid">
                                    <div class="text-new">Text head</div>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="detail-Gallery">
                                    <img src="{{asset('public/webpromote/images/HCtHFA7ele6Q2dUK4wd8KSt8pOwDg1r2FiaUqTu8nGBx40Hj8IqlT0SBhLNlRw3JxA.jpg')}}" alt="" class="img-fluid">
                                    <div class="text-new">Text head</div>
                                </a>
                            </div>

                        </div>


                        <div class="textupdat">Long time ago</div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{asset('public/webpromote/images/4-2-850x567.jpg')}}" alt="" class="img-fluid">
                                <div class="text-new">Text head </div>
                            </div>

                            <div class="col-md-4">
                                <img src="{{asset('public/webpromote/images/48a0af41-831a-4147-8e96-59e25971c5d4.jpg')}}" alt="" class="img-fluid">
                                <div class="text-new">Text head</div>
                            </div>

                            <div class="col-md-4">
                                <img src="{{asset('public/webpromote/images/800x513.jpg')}}" alt="" class="img-fluid">
                                <div class="text-new">Text head</div>
                            </div>

                            <div class="col-md-4">
                                <img src="{{asset('public/webpromote/images/1_10871_02.jpg')}}" alt="" class="img-fluid">
                                <div class="text-new">Text head </div>
                            </div>

                            <div class="col-md-4">
                                <img src="{{asset('public/webpromote/images/shutterstock_583691665.jpg')}}" alt="" class="img-fluid">
                                <div class="text-new">Text head</div>
                            </div>

                            <div class="col-md-4">
                                <img src="{{asset('public/webpromote/images/unnamed.jpg')}}" alt="" class="img-fluid">
                                <div class="text-new">Text head</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="margin-bottom"></div>
    </div>
@endsection