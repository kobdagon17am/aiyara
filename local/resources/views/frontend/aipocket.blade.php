  
@extends('frontend.layouts.customer.customer_app')
@section('conten')

<div class="row">


    <div class="col-md-3 col-xl-3">
        <div class="card bg-c-blue order-card">
            <div class="card-block">
                <h5 class="m-b-20">Ai-PocKet</h5>
                <h3 class="text-right"><i class="ti-wallet f-left"></i><span>9,562 PV</span></h3>
                <p class="m-b-0">จำนวนคะแนนที่เหลือ{{-- <span class="f-right">$542</span> --}}</p>
            </div>
        </div>
    </div>

    <div class="col-md-9 col-xl-9">
        <div class="card">
            <div class="card-block">
                <h6>จุดประสงค์การใช้</h6>

                <div class="form-group row">
                    <div class="col-md-4 m-t-5">

                       <select class="form-control" name="type">
                        @foreach($type as $value)
                         

                        <option value="{{ $value->group_id }}">{{ $value->orders_type }}</option>
                         @endforeach
                    </select>
                </div>  

                <div class="col-md-4 m-t-5">


                    <div class="input-group input-group-button">
                        <input type="text" class="form-control" placeholder="รหัสสินค้าโปรโมชั่น">
                        <span class="input-group-addon btn btn-primary" id="check_code">
                            <span class="">Check Code</span>
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>ประวัติการสั่งซื้อ</h5>
                {{-- <span>DataTables has most features enabled by default, so all you need to do to use it with your own ables is to call the construction function: $().DataTable();.</span> --}}
            </div>
            <div class="card-block">
                <div class="table-responsive dt-responsive">
                    <table id="aipocket" class="table table-striped table-bordered nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>วันที่</th>
                                <th>Type</th>
                                <th>ผู้รับ</th>
                                <th>PV</th>
                                <th>สถานะ</th>
                                <th>รายละเอียด</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Start Contact Info area-->
<div class="contact-info-area mg-t-30" style="background-color: white;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcomb-list">
                    <h3>Pocket </h3><h4>จำนวนคะแนนที่เหลือ 1000 PV</h4>

                </div>
            </div>
        </div>
        <hr/>

        <div class="row">

            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
                <div class="contact-form sm-res-mg-t-30 tb-res-mg-t-30 tb-res-mg-t-0">
                   <p>   จุดประสงค์การใช้ :</p> 
                   <select class="form-control form-control-user"  class="form-control form-control-user"  >
                    <option value="0" selected="">ทำคุณสมบัติ</option>
                    <option value="1">รักษาคุณสมบัติ</option>
                    <option value="2">รักษาคุณสมบัติท่องเที่ยว</option>
                </select><br/>
                <input type="number"  id="member_id" class="form-control form-control-user"  placeholder="จำนวน PV"><br/>
                <input type="text"  id="member_id" class="form-control form-control-user"  placeholder="รหัสสมาชิกที่ใช้">
                <input type="checkbox"><font style="color: #00c454;">ใช้ให้ตนเอง</font><br/>
                <button onclick="location.reload()" class="btn btn-lg btn-gray gray-icon-notika waves-effect" style=" background-color: #00c454; color: black">ทำรายการ</button>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" >
            <div class="contact-form sm-res-mg-t-30 tb-res-mg-t-30 tb-res-mg-t-0">
                <select style=" font-size: 20px; width: 100%;" >
                    <option>รายการบันทึกทั้งหมด</option>
                    <option>ทำคุณสมบัติ</option>
                    <option value="1">รักษาคุณสมบัติ</option>
                    <option value="2">รักษาคุณสมบัติท่องเที่ยว</option>
                </select>
            </div>
        </div>




        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
            <div class="contact-form sm-res-mg-t-30 tb-res-mg-t-30 tb-res-mg-t-0">
                <div class="table-responsive">
                    <table class="table table-striped table-responsive" >
                        <thead>
                            <tr class="info">
                                <th style="font-size: 12px;">วันที่</th>
                                <th style="font-size: 12px;">Type</th>
                                <th style="font-size: 12px;">ผู้รับ</th>
                                <th style="font-size: 12px;">จำนวน</th>
                                <th style="font-size: 12px;">สถานะ</th>
                                <th style="font-size: 12px;">รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10/16/2563</td>
                                <td>ทำคุณสมบัติ</td>
                                <td>ตนเอง</td>
                                <td>200</td>
                                <td style="color:#00c454;">สำเร็จ</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>10/16/2563</td>
                                <td>ทำคุณสมบัติ</td>
                                <td>A903</td>
                                <td>100(THB)</td>
                                <td style="color: #ffcc00">รอดำเนินการ</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>10/16/2563</td>
                                <td>ทำคุณสมบัติ</td>
                                <td>ตนเอง</td>
                                <td>1000</td>
                                <td style="color: black;">ทำรายการไม่สำเร็จ</td>
                                <td>-</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- End Contact Info area-->


@endsection
@section('js')


@endsection

