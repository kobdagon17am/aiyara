  
@extends('frontend.layouts.customer.customer_app')
@section('css')
<style type="text/css">

  .feed-blog li {
    position: relative;
    padding-left: 31px;
    margin-bottom: 24px;
  }
  p{
    margin-bottom: 10px;
  }
</style>


@endsection

@section('conten') 


<div class="row">

  <div class="col-lg-5 col-md-5">
    <div class="card">
     <div class="card-header">
      <h4>รางวัลเกียรติยศ Active </h4>
    </div>
    <div class="card-block">
      <ul class="feed-blog">


        @foreach($data as $value)
        <?php 
        $qualification_id = Auth::guard('c_user')->user()->qualification_id;
        $customer_id = Auth::guard('c_user')->user()->id;

        ?>
        @if($value->id <= $qualification_id )
        <?php 

        $rs_check = \App\Helpers\Frontend::check_reward_history($customer_id,$value->id);


        ?>
        <li class="active-feed" style="margin-bottom: 25px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/true.png')}}" class="img-radius " alt="User-Profile-Image">
          </div> 
          <h6><span class="label label-success">Active</span> {{ $value->business_qualifications }}</h6>
          @if($rs_check)
          <p><small class="text-muted">Date {{ date('d/m/Y',strtotime($rs_check->created_at)) }}</small></p>
          @endif
        </li>
        @else
        <li class="diactive-feed" style="margin-bottom: 30px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/false.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6><span class="label label-inverse-info-border">Not Active</span> {{ $value->business_qualifications }}</h6>
        </li>

        @endif

        @endforeach

      </ul>
    </div>
    <div class="card-block">

    </div>
  </div>
</div>

<div class="col-lg-7 col-md-7">
  <div class="card">
    <div class="card-header">
      <h4>รายละเอียดรางวัลเกียรติยศ</h4>
    </div>
    <div class="card-block list-tag">
      <ul class="feed-blog">

        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/Crown-Diamond-.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Crown Diamond Star (CDS)</h6>
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง BLACK DIAMOND STAR (active ในเดือนเดียวกัน) L/T 5 รหัส : M/T 5 รหัส</p>
            </li>
            
            <li> 
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">One Time Bonus 5,000,000 บาท (จ่ายครั้งเดียว)</p>
            </li> 
          </ul>
        </li>

        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/Triple-Diamond.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Triple Diamond Star (TDS)</h6>
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Black Diamond star (active ในเดือนเดียวกัน) : L/T 3 รหัส : M/T 3 รหัส</p>
            </li>

            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">One Tmie Bonus 3,000,000 บาท(จ่ายครั้งเดียว)</p>
            </li> 
          </ul>
        </li>
        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/DOUBLE-Diamond.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Double Diamond Star (DDS)</h6>
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Black Diamond star (active ในเดือนเดียวกัน) L/T 1 รหัส : M/T 1 รหัส</p>
            </li>

            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">One Tmie Bonus 1,000,000 บาท (จ่ายครั้งเดียว)</p>
            </li> 
          </ul>
          
        </li>
   {{--      <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/false.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Black Diamond Star (BDS)</h6>
        </li> --}}
        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/EDS.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Executive Diamond Star (EDS)</h6>
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Platinum Star (active) L/T 4 รหัส : M/T 4 รหัส และรับโบนัส FS+TMB = 1,500,000 บาท/เดือน (จำนวน 3 เดือน ติดต่อกัน)</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> รักษาคุณสมบัติ (Re)
              <p style="margin-bottom: 10px;">แนะนำตรง Platinum Star (active) L/T 4 รหัส : M/T 4 รหัส และรับโบนัส FS+TMB = 1,500,000 บาท/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">Fund Home & Car</p>
            </li> 
          </ul>
        </li>

        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/Diamond.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Diamond Star (DS)</h6>
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Platinum Star (active) L/T 4 รหัส : M/T 4 รหัส และรับโบนัส FS+TMB = 1,500,000 บาท/เดือน (จำนวน 3 เดือน ติดต่อกัน)</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> รักษาคุณสมบัติ (Re)
              <p style="margin-bottom: 10px;">แนะนำตรง Platinum Star (active) L/T 4 รหัส : M/T 4 รหัส และรับโบนัส FS+TMB = 1,500,000 บาท/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">Fund Home & Car</p>
            </li> 
          </ul>
        </li>

        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/Emerald.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Emerald Star (EMS)</h6>
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Platinum star (active) : L/T 2 รหัส : M/T 2 รหัส มีคะแนน M/T = 1,000,000 PV/เดือน และรับโบนัส FS+TMB = 450,000 บาท/เดือน (จำนวน 2 เดือน ติดต่อกัน)</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> รักษาคุณสมบัติ (Re)
              <p>แนะนำตรง Blue star (active) : L/T 1 รหัส : M/T 1 รหัส และรับโบนัส FS+TMB = 450,000 บาท/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">Fund Home & Car 20,000 บาท/เดือน</p>
            </li> 
          </ul>
        </li>

        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/Blue-.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Blue Star (BLS)</h6>
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Platinum Star (active) L/T 1 รหัส : M/T 1 รหัส มีคะแนน M/T = 800,000 PV/เดือน<br>
              และโบนัส FS + TMB = 250,000 บาท/เดือน (จำนวน 2 เดือน ติดต่อกัน)</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> รักษาคุณสมบัติ (Re)
              <p style="margin-bottom: 10px;">แนะนำตรง Platinum Star (active) L/T 1 รหัส M/T 1 รหัส และรับโบนัส FS+TMB = 250,000 บาท/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">Fund Home & Car 10,000 บาท/เดือน</p>
            </li> 
          </ul>
        </li>

        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/Supphire-e.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Sapphire Star (SAS)</h6> 
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic (active) : L/T 5 รหัส : M/T 5 รหัส มีคะแนน M/T = 600,000 PV/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> รักษาคุณสมบัติ (Re)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic (active) : L/T 5 รหัส : M/T 5 รหัส มีคะแนน : M/T = 600,000 pv/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">One Time Bonus 15,000 บาท(จ่ายครั้งเดียว) รับโบนัส Trinary Boosterในเดือนถัดไป 1 เดือน</p>
            </li> 
          </ul>
        </li>

        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/ruby.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Ruby Star (RUS)</h6>
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic (active) : L/T 5 รหัส : M/T 5 รหัส มีคะแนน M/T = 450,000 pv/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> รักษาคุณสมบัติ (Re)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic (active) : L/T 5 รหัส : M/T 5 รหัส มีคะแนน M/T = 450,000 pv/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">One Time Bonus</p>
            </li> 
          </ul>
        </li>

        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/BLACK-PEARL.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Black Perl Star (BPS)</h6>
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic (active) : L/T 5 รหัส : M/T 5 รหัส มีคะแนน M/T = 350,000 PV/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> รักษาคุณสมบัติ (Re)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic (active) : L/T 5 รหัส : M/T 5 รหัส มีคะแนน M/T = 350,000 pv/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">One Time Bonus</p>
            </li> 
          </ul>
        </li>

        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/PEARL.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Perl Star (PES)</h6>
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic (active) : L/T 5 รหัส : M/T 5 รหัส มีคะแนน M/T = 250,000 PV/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> รักษาคุณสมบัติ (Re)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic (active) : L/T 5 รหัส : M/T 5 รหัส มีคะแนน M/T = 250,000 pv/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">One Time Bonus 10000 บาท(จ่ายครั้งเดียว) รับโบนัส Trinary Booster ในเดือนถัดไป 1 เดือน</p>
            </li> 
          </ul>
        </li>

        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/PS.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Platinum Star (PS)</h6>
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic (active) : L/T 5 รหัส : M/T 5 รหัส มีคะแนน M/T = 150,000 pv/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> รักษาคุณสมบัติ (Re)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic (active) : L/T 5 รหัส : M/T 5 รหัส มีคะแนน M/T = 150,000 pv/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">One Time Bonus 5000 บาท(จ่ายครั้งเดียว) รับโบนัส Trinary Booster ในเดือนถัดไป 1 เดือน</p>
            </li> 
          </ul> 
        </li>

      {{--   <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/false.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6> Tripple Gold Star (TGS)</h6>
        </li> --}} 

      {{--   <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/false.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Double Gold Star (DGS)</h6>

        </li> --}}

        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/Gold.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Gold Star Award (GSA)</h6>
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic : L/T 5 รหัส : M/T 5 รหัส มีคะแนน M/T = 20,000 PV/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> รักษาคุณสมบัติ (Re)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic (active) : L/T 5 รหัส : M/T 5 รหัส</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">Matching Leader Bonus ลูก หลาน เหลน</p>
            </li> 
          </ul>
        </li>

        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/Silver.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6> Silver Star Award (SSA)</h6>
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic : L/T 3 รหัส : M/T 3 รหัส มีคะแนน M/T = 10,000 PV/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> รักษาคุณสมบัติ (Re)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic (active) : L/T 3 รหัส : M/T 3 รหัส มีคะแนน M/T = 10,000 PV/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">Matching Leader Bonus ลูก หลาน</p>
            </li> 
          </ul>
        </li>

        <li class="diactive-feed" style="margin-bottom: 10px;">
          <div class="feed-user-img">
            <img src="{{asset('frontend/assets/images/img_reward/BronzeStarAward.png')}}" class="img-radius " alt="User-Profile-Image"> 
          </div>
          <h6>Bronze Star Award (BSA) </h6>
          
          <ul>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> ทำคุณสมบัติ (Q)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic : L/T 1 รหัส : M/T 1 รหัส มีคะแนน M/T = 5,000 PV/เดือน</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> รักษาคุณสมบัติ (Re)
              <p style="margin-bottom: 10px;">แนะนำตรง Basic (active) : L/T 1 รหัส : M/T 1 รหัส</p>
            </li>
            <li>
              <i class="icofont icofont-hand-right text-info"></i> สิทธิประโยชน์
              <p style="margin-bottom: 10px;">Matching Leader Bonus ลูก</p>
            </li>
          </ul>


        </li>

      </ul>
    </div>
  </div>
</div>

</div>

@endsection

@section('js')
@endsection


