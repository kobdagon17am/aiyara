      
@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')

<link rel="stylesheet" href="{{asset('public/css/bootstrap-slider.css')}}">
@endsection
<div class="breadcomb-area">
	<div class="container">
		<div class="row">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="breadcomb-list">


					<select style=" font-size: 20px;" >
						<option>แสดงผล 5 รายการล่าลุด</option>
						<option>แสดงผล 15 วันที่ผ่านมา</option>
						<option>แสดงผล 30 วันที่ผ่านมา</option>
						<option>แสดงผล 3 เดือนที่ผ่านมา</option>
						<option>แสดงผล 6 เดือนที่ผ่านมา</option>
						<option>แสดงผล 1 ปีที่ผ่านมา</option>
						<option>แสดงผล รายการทั้งหมด</option>
					</select>


					<hr/>
					@for ($x=1; $x<=5; $x++)
					<div class="row">
						<h4>คำสั่งซื้อที่ : 2020xxxxxx00{{ $x }}</h4>
						<h6>วันที่สั่งซื้อ 30/04/2020 16:30:48</h6>
						<h6>วันที่ได้รับสินค้า 30/04/2020 16:30:48</h6>
						<div class="col" style=" margin-left: 10%;">                                                    
						</div>
						<!---   Product List  -->
						<?php $rnd=rand(2,7); ?>

						@for ($y = 1; $y <= $rnd; $y++)
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<div class="breadcomb-wp">
								<div class="breadcomb-ctn">
									<br/>
									<div class="animation-img mg-b-15">
										<img class="animate-one" src="{{asset('public/img/widgets/2.png')}}" alt="" />
									</div>
									<p>สินค้าชิ้นที่ #{{ $y }}</p>  
									<p>จำนวน 1 qty. ราคา  ฿1,000.00</p>
								</div>
							</div>
						</div>
						@endfor
						<!---   Product List  -->

					</div>
					<hr/>
					@endfor
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
 

