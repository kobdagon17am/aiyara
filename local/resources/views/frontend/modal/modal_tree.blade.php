<?php

use App\Helpers\Frontend;
use App\Models\Frontend\MonthPv;
$count_directsponsor = Frontend::check_customer_directsponsor($data->team_active_a,$data->team_active_b,$data->team_active_c);

?>
<div class="modal fade" id="modal_tree_show" tabindex="-1" role="dialog">
 <div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
   <div class="modal-header bg-c-green">
    @if(@$data->business_name and $data->business_name  != '-')

    <h4 class="modal-title" style="color: #FFFF">{{$data->business_name}} ({{$data->user_name}})</h4>
    @else
    <h4 class="modal-title" style="color: #FFFF">{{$data->prefix_name.' '.$data->first_name.' '.$data->last_name }} ({{$data->user_name}}) </h4>

    @endif

    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
   </button>
 </div>

 <div class="modal-body text-left">
  <div class="table-responsive">
   <table class="table">
    <tbody>
     <tr class="table-success">
      <td><strong>วันที่สมัคร </strong></td>
      <td>{{ date('d/m/Y',strtotime($data->created_at)) }}</td>
      <td></td>
    </tr>
    <tr>
      <td><strong>สั่งซื้อครั้งแรก </strong></td>
      <td>@if(empty($data->date_order_first))
        -
        @else
        {{ date('d/m/Y',strtotime($data->date_mt_first)) }}
      @endif </td>
      <td></td>
    </tr>
    <tr class="table-success">
      <td><strong>คะแนนส่วนตัว:</strong></td>
      <td>{{ number_format($data->pv) }} PV</td>
      <td><b class="text-danger">{{ $data->dt_package }}</b></td>

    </tr>
    <tr>
      <td><strong>คุณสมบัติรายเดือน Active ถึง</strong></td>
      <td>@if(empty($data->pv_mt_active))
        -
        @else
        {{ date('d/m/Y',strtotime($data->pv_mt_active)) }}
      @endif </td>
      <td>[เหลือ {{ number_format($data->pv_mt) }} PV]</td>
    </tr>
    <tr class="table-success">
      <td><strong>คุณวุฒิสูงสุด</strong></td>
      <td>{{ $data->max_q_name }}</td>
      <td></td>
    </tr>
    <tr>
      <td><strong>สิทธิ Reward Bonus</strong></td>
      <td>{{ $count_directsponsor['reward_bonus'] }}</td>
      <td></td>
    </tr>
    <tr class="table-success">
      <td><strong>นับคุณวุฒิจาก</strong></td>
      <td>{{ date('01/m/Y') }} ถึง {{ date('t/m/Y') }}</td>
      <td></td>
    </tr>
    <tr>
      <td><strong>ทีมกลางคือทีม</strong></td>

    <?php $get_month_pv = MonthPv::get_month_pv($data->user_name); ?>
      <td>
        @if($get_month_pv['data_avg_pv']['pv_month'] <= 0)
        -
        @else
         <b class="text-danger">{{ $get_month_pv['data_avg_pv']['type'] }}  มีคะแนนสะสม {{ number_format($get_month_pv['data_avg_pv']['pv_month']) }} PV</b>
        @endif

      </td>
      <td></td>
    </tr>

  </tbody>
</table>
</div>
<div class="b-t-default transection-footer row">
 <div class="col-6  b-r-default">
  <strong>คะแนนคงเหลือยกมา</strong><br>
  [ A ] <font class="font-red">{{ number_format($data->bl_a) }}</font> [ B ] <font class="font-red">{{ number_format($data->bl_b) }}</font> [ C ] <font class="font-red">{{ number_format($data->bl_c) }}</font>
</div>
<div class="col-6">
  <strong>คะแนนวันนี้</strong><br>
  [ A ] <font class="font-red">{{ number_format($data->pv_a) }}</font> [ B ] <font class="font-red">{{ number_format($data->pv_b) }}</font> [ C ] <font class="font-red">{{ number_format($data->pv_c) }}</font>
</div>
</div>
</div>

<div class="modal-footer">
  <p class="m-b-0 text-left" style="font-size: 12px"> ข้อมูลวันที่ :{{ date('d/m/Y') }} </p>
  <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">ปิด</button>

  <form id="line_id_v1" action="{{route('tree_view')}}" method="POST">
    <input type="hidden" name="id" value="{{$data->user_name}}">
    @csrf
    <button type="submit" class="btn btn-primary waves-effect waves-light ">ดูสายงาน</button>
  </form>


</div>

</div>
</div>
</div>
