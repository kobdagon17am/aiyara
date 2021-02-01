<?php
use App\Helpers\Frontend; 
?>
@extends('frontend.layouts.customer.customer_app')
@section('css')
<!-- Data Table Css -->
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/pages/data-table/css/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}">

@endsection
@section('conten')

<div class="row">
    <div class="col-md-12">
        <div class="card"> 

           <div class="card-header">
            <h4 class="m-b-10">ข้อมูลแนะนำตรงทั้งหมด</h4>

        </div> 
        <div class="card-block">

            <div class="dt-responsive table-responsive">
                <table id="simpletable" class="table table-striped table-bordered nowrap">

                    <thead>
                      <tr class="info" style='text-align:center;'>
                        <th class="text-center" rowspan="2" >#</th>
                        <th class="text-center" rowspan="2" >Line</th>
                        <th class="text-center" rowspan="2" >ID</th>
                        <th class="text-center" rowspan="2" >Buniness Name</th>
                        <th class="text-center" rowspan="2" >Package</th>
                        <th class="text-center" rowspan="2" >Upline</th>
                        <th class="text-center" rowspan="2" >Personal Active</th>
                        <th class="text-center" colspan="3" >Team Active</th>
                        <th class="text-center" colspan="2" >Reward Bonus</th>
                        <th class="text-center" colspan="2" >Award</th>

                    </tr>
                    <tr>
                        <th class="text-center"  style="font-size: 12px;">A</th>
                        <th class="text-center"  style="font-size: 12px;">B</th>
                        <th class="text-center"  style="font-size: 12px;">C</th>
                        <th class="text-center"  style="font-size: 12px;">ปัจจุบัน</th>
                        <th class="text-center"  style="font-size: 12px;">สูงสุด</th>
                        <th class="text-center"  style="font-size: 12px;">ปัจจุบัน</th>
                        <th class="text-center"  style="font-size: 12px;">สูงสุด</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; ?>
                    @foreach($customers as $value)

                <?php
                    $data_customer = Frontend::get_customer($value->upline_id);
                    if(empty($data_customer)){
                      $upline = '-';
                  }else{
                      $upline = @$data_customer->user_name.'/'.$value->line_type;
                  }

                  $check_active_mt = Frontend::check_mt_active($value->id);
                  if($check_active_mt['status'] == 'success'){
                    if($check_active_mt['type'] == 'Y'){
                        $active_mt = "<span class='label label-inverse-success'><b>"
                      .$check_active_mt['date']."</b></span>";
                    }else{
                        $active_mt = "<span class='label label-inverse-info-border'><b>"
                      .$check_active_mt['date']."</b></span>";

                    }
                    
                  }else{
                    $active_mt = "<span class='label label-inverse-info-border'><b> Not Active </b></span>";
                  }

                   $count_directsponsor = Frontend::check_customer_directsponsor($value->id);
                   
                  
                  ?>
 
                      <tr>
                        <th style="font-size: 14px;">{{ $i }}</th>
                        <th style="font-size: 14px;"><b>{{ $value->introduce_type }}</b></th>
                        <th style="font-size: 14px;">{{ $value->user_name }}</th>
                        <th style="font-size: 14px;">{{ $value->business_name }}</th>
                        <th style="font-size: 14px;">
                         @if(empty($value->dt_package))
                            -
                            @else
                            {{ $value->dt_package }}
                            @endif
                        </th>
                        <th style="font-size: 14px;">{{ $upline }}</th>
                        <th style="font-size: 14px;">{!! $active_mt !!}</th>
                        <th style="font-size: 14px;">{{ $count_directsponsor['A'] }}</th>
                        <th style="font-size: 14px;">{{ $count_directsponsor['B'] }}</th>
                        <th style="font-size: 14px;">{{ $count_directsponsor['C'] }}</th>
                        <th style="font-size: 14px;">{{ $count_directsponsor['reward_bonus'] }}</th>
                        <th style="font-size: 14px;">
                            @if(empty($value->reward_max_id))
                            -
                            @else
                            {{ $value->reward_max_id }}
                            @endif
                        </th>
                        <th style="font-size: 14px;">{{ $value->code_name }}</th>
                        <th style="font-size: 14px;">{{ $value->max_code_name }}</th>
                        <?php $i++; ?>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div> 
</div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card"> 

           <div class="card-header">
            <h4 class="m-b-10">ข้อมูลแนะนำตรงในรอบ 60 วันแรก (Team Maker) 
            ชั้นลูก <b class="text-primary">{{ count($customers_sponser) }}</b> คน + ชั้นหลาน 3 คน</h4>

        </div> 
        <div class="card-block">

            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">

                    <thead>
                      <tr class="info" style='text-align:center;'>
                        <th class="text-center" >#</th>
                        <th class="text-center" >Line</th>
                        <th class="text-center" >ID</th>
                        <th class="text-center" >Buniness/Name</th>
                        <th class="text-center" >OwnPV</th>
                        <th class="text-center" >Package</th>
                        <th class="text-center" >First Date</th>
                        <th class="text-center" >Remie(Day)</th>
                    </tr>
            
                </thead>
                <tbody>
                    <?php $i=0;


                    ?>
                    @foreach($customers_sponser as $value_sponser)
                    <?php $i++;
                    $end_date = strtotime($value_sponser->end_date);
                    $start_date = strtotime(date('Y-m-d'));

                    $date_remine = ($end_date - $start_date)/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
                    $remine = ceil($date_remine)

                    ?>

                      <tr>
                        <th style="font-size: 13px;">{{ $i }}</th>
                        <th style="font-size: 13px;">{{ $value_sponser->introduce_type }}</th>
                        <th style="font-size: 13px;">{{ $value_sponser->user_name }}</th>
                        <th style="font-size: 13px;">{{ $value_sponser->business_name.' / '.$value_sponser->prefix_name.' '.$value_sponser->first_name.' '.$value_sponser->last_name }}</th>

                   
                        <th style="font-size: 13px;">{{ number_format($value_sponser->pv) }}</th>
                            <th style="font-size: 14px;">
                         @if(empty($value_sponser->dt_package))
                            -
                            @else
                            {{ $value_sponser->dt_package }}
                            @endif
                        </th>
                        <th style="font-size: 13px;"><span class='label label-inverse-success'><b>{{ date('d/m/Y',strtotime($value_sponser->created_at)) }}</b></span></th>
                        
                        <th style="font-size: 13px;">{{  $remine  }}</th>
  
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div> 
</div>
</div>

@endsection
@section('js')
<!-- data-table js -->

<script src="{{asset('frontend/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/jszip.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/pdfmake.min.js')}}"></script>
<script src="{{asset('frontend/assets/pages/data-table/js/vfs_fonts.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('frontend/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
<!-- Custom js -->
<script src="{{asset('frontend/assets/pages/data-table/js/data-table-custom.js')}}"></script>

@endsection





