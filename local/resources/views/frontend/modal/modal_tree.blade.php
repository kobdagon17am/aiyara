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
      <td><strong>@lang('message.Register_Date') </strong></td>
      <td>{{ date('d/m/Y',strtotime($data->created_at)) }}</td>
      <td></td>
    </tr>
    <tr>
      <td><strong>@lang('message.First_order') </strong></td>
      <td>@if(empty($data->date_order_first) ||  $data->date_order_first == '0000-00-00 00:00:00')
        -
        @else
        {{ date('d/m/Y',strtotime($data->date_order_first)) }}
      @endif </td>
      <td></td>
    </tr>
    <tr class="table-success">
      <td><strong>@lang('message.Personal_Score') :</strong></td>
      <td>{{ number_format($data->pv) }} PV</td>
      <td><b class="text-danger">{{ $data->dt_package }}</b></td>

    </tr>
    <tr>

      <td><strong>@lang('message.Monthly_Features_Active_To')</strong></td>
      <td>@if(empty($data->pv_mt_active) || $data->pv_mt_active == '0000-00-00')
        -
        @else
        {{ date('d/m/Y',strtotime($data->pv_mt_active)) }}
      @endif </td>
      <td>[เหลือ {{ number_format($data->pv_mt) }} PV]</td>
    </tr>
    <tr class="table-success">
      <td><strong>@lang('message.Highest_Qualifications')</strong></td>
      <td>{{ $data->max_q_name }}</td>
      <td></td>
    </tr>
    <tr>
      <td><strong>@lang('message.Reward_Bonus')</strong></td>
      <td>{{ $count_directsponsor['reward_bonus'] }}</td>
      <td></td>
    </tr>
    <tr class="table-success">
      <td><strong>@lang('message.Count_qualifications') </strong></td>
      <td>{{ date('01/m/Y') }} ถึง {{ date('t/m/Y') }}</td>
      <td></td>
    </tr>

  </tbody>
</table>
</div>

<div class="row">
  <div class="col-6  b-r-default">

    <strong>@lang('message.Middle')</strong><br>
    [ {{$data->team_center}} ] <font class="font-red">{{number_format($data->pv_team_center)}}</font>
   </div>
   <div class="col-6">

    <strong>คะแนนแนะนำตรงส่วนตัวเดือนนี้</strong><br>
      [ A ] <font class="font-red">{{ number_format(@$bonus_per_day->month_sppv_a) }}</font> [ B ] <font class="font-red">{{ number_format(@$bonus_per_day->month_sppv_b) }}</font> [ C ] <font class="font-red">{{ number_format(@$bonus_per_day->month_sppv_c) }}</font>

   </div>
 </div>


<div class="b-t-default transection-footer row">
 <div class="col-6  b-r-default">
  <strong>@lang('message.Remaining')</strong><br>
  [ A ] <font class="font-red">{{ number_format($data->bl_a) }}</font> [ B ] <font class="font-red">{{ number_format($data->bl_b) }}</font> [ C ] <font class="font-red">{{ number_format($data->bl_c) }}</font>
</div>
<div class="col-6">
  <strong>@lang('message.Today_score')</strong><br>
  [ A ] <font class="font-red">{{ number_format($data->pv_a) }}</font> [ B ] <font class="font-red">{{ number_format($data->pv_b) }}</font> [ C ] <font class="font-red">{{ number_format($data->pv_c) }}</font>
</div>
</div>
</div>

<div class="modal-footer">
  <p class="m-b-0 text-left" style="font-size: 12px"> @lang('message.date_information'):{{ date('d/m/Y') }} </p>
  <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">@lang('message.close')</button>

  <form id="line_id_v1" action="{{route('tree_view')}}" method="POST">
    <input type="hidden" name="id" value="{{$data->user_name}}">
    @csrf
    <button type="submit" class="btn btn-primary waves-effect waves-light ">@lang('message.view_line')</button>
  </form>


</div>

</div>
</div>
</div>
