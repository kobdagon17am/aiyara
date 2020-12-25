@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')

@endsection

<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header" style="padding: 12px;margin-bottom: -23px;">
        <h4 class="sub-title"><i class="fa fa-user"></i> ข้อมูลส่วนตัว</h4>
      </div>
      <div class="card-block">
        <div class="row col-md-12">
          <div class="col-md-4 col-xl-4">
           <div class="card bg-c-green order-card m-b-5">
            <div class="card-block">
              <div class="row">
                <div class="col-md-5">
                 <h5 class="m-b-20" style="color: #000">คะแนนสะสม</h5>

               </div>
               <div class="col-md-7">
                <h3 class="text-right" style="color: #000" >{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->pv) }} PV</span></h3>
              </div>
            </div>

            <p class="m-b-0" style="font-size: 16px"><b class="f-right"><i class="fa fa-star p-2 m-b-0"></i>  BRONZE STAR AWARD ( BSA )</b></p>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-xl-4">
        <div class="card bg-c-yellow order-card m-b-5">
          <div class="card-block">
            <div class="row"> 
              <div class="col-md-6">
               <h5 class="m-b-10" style="color: #000">คงเหลือ</h5>

             </div>
             <div class="col-md-6">
              <h3 class="text-right" style="color: #000">{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->pv_mt) }} PV</span></h3>
            </div>
          </div>

          <p style="font-size: 15px;color: #000;">สถานะรักษาคุณสมบัติรายเดือนของคุณ </p>

          @if(empty(Auth::guard('c_user')->user()->pv_mt_active) || (strtotime(Auth::guard('c_user')->user()->pv_mt_active) < strtotime(date('Ymd')) ))
          <p class="m-b-0"><span class="label label-danger"  data-toggle="tooltip" data-placement="right" data-original-title="{{ date('d/m/Y',strtotime(Auth::guard('c_user')->user()->pv_mt_active)) }}"  style="font-size: 14px">Not Active </span>  </p>
          @else

          <p class="m-b-0"><span class="label label-success" style="font-size: 14px">Active ถึง {{ date('d/m/Y',strtotime(Auth::guard('c_user')->user()->pv_mt_active)) }} </span>  </p>
          @endif


          {{-- <p class="m-b-0" style="color: #000">Active ถึง 14/09/2020</p> --}}
        </div>
      </div>
    </div>
    <div class="col-md-4 col-xl-4">
      <div class="card bg-c-yellow order-card m-b-5">
        <div class="card-block">
          <div class="row">
            <div class="col-md-6">
             <h5 class="m-b-10" style="color: #000">คงเหลือ</h5>

           </div>
           <div class="col-md-6">
            <h3 class="text-right" style="color: #000">{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->pv_tv) }} PV</span></h3>
          </div>
        </div>

        <p style="font-size: 15px;color: #000;">สถานะรักษาคุณสมบัติท่องเที่ยวของคุณ </p> 
        <?php 
        $pv_tv_active = Auth::guard('c_user')->user()->pv_tv_active;
        if(!empty($pv_tv_active)){
          $pv_tv_active = date('d/m/Y',strtotime(Auth::guard('c_user')->user()->pv_tv_active));
        }else{
          $pv_tv_active = '';

        }

        ?> 

        @if(empty(Auth::guard('c_user')->user()->pv_tv_active) || (strtotime(Auth::guard('c_user')->user()->pv_tv_active) < strtotime(date('Ymd')) ))
        <p class="m-b-0"><span class="label label-danger" data-toggle="tooltip" data-placement="right" data-original-title="{{ $pv_tv_active }}" style="font-size: 14px">Not Active </span>  </p>
        @else

        <p class="m-b-0"><span class="label label-success" style="font-size: 14px">Active ถึง {{ date('d/m/Y',strtotime(Auth::guard('c_user')->user()->pv_tv_active)) }} </span>  </p>
        @endif
      </div>
    </div>
  </div> 
</div>
<div class="row col-md-12">

    <div class="col-xl-4 col-lg-4 col-md-4">
   <div class="card bg-c-blue order-card m-b-5">
    <div class="card-block">
      <div class="row">
        <div class="col-md-5">
          <h5 class="m-b-20" style="color: #000">Ai-Stockist</h5>


        </div>
        <div class="col-md-7">
         <h3 class="text-right" style="color: #000" >{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format(Auth::guard('c_user')->user()->pv_aipocket) }} PV</span></h3>
       </div>
     </div>

     <p class="m-b-0" {{-- style="color:#000" --}}>จำนวนคะแนนคงเหลือ</p>
   </div>
 </div>

</div> 

<div class="col-xl-4 col-lg-4 col-md-4">
 <div class="card bg-c-pink order-card m-b-5">
  <div class="card-block">
    <div class="row">
      <div class="col-md-5">
       <h6 class="m-b-20" style="font-size: 16px">Gift Voucher </h6>

     </div>
     <div class="col-md-6">
      <?php  
      $gv = \App\Helpers\Frontend::get_gitfvoucher(Auth::guard('c_user')->user()->id);
      ?>
      <h3 class="text-right">{{-- <i class="ti-wallet f-left"></i> --}}<span>{{ number_format($gv->sum_gv) }} </span></h3>
    </div>
  </div>

  <p class="m-b-0">จำนวน Gift Voucher คงเหลือ</p>
</div>
</div>
</div>
  
</div>

 
</div>

</div>
</div>
</div>
@endsection
@section('js')
@endsection


