<?php
use App\Helpers\Frontend;
use App\Models\Frontend\DirectSponsor;
$count_sponser = 0;
?>
@extends('frontend.layouts.customer.customer_app')
@section('css')
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/assets/pages/data-table/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('frontend/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">


@endsection
@section('conten')

    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <h4 class="m-b-10">{{trans('message.p_direct sponsor reccom')}}</h4>

                </div>
                <div class="card-block">

                    <div class=" table-responsive">
                        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr class="info" style='text-align:center;'>
                                    <th class="text-center" rowspan="2">#</th>
                                    {{-- <th class="text-center" rowspan="2">ID</th> --}}
                                    <th class="text-center" rowspan="2">Line</th>
                                    <th class="text-center" rowspan="2">Buniness Name</th>
                                    <th class="text-center" rowspan="2">Package</th>
                                    <th class="text-center" rowspan="2">Upline</th>
                                    <th class="text-center" rowspan="2">Personal Active</th>
                                    <th class="text-center" colspan="3">Team Active</th>
                                    <th class="text-center" colspan="2">Reward Bonus</th>
                                    <th class="text-center" colspan="2">Award</th>

                                </tr>
                                <tr>
                                    <th class="text-center" style="font-size: 12px;">A</th>
                                    <th class="text-center" style="font-size: 12px;">B</th>
                                    <th class="text-center" style="font-size: 12px;">C</th>
                                    <th class="text-center" style="font-size: 12px;">ปัจจุบัน</th>
                                    <th class="text-center" style="font-size: 12px;">สูงสุด</th>
                                    <th class="text-center" style="font-size: 12px;">ปัจจุบัน</th>
                                    <th class="text-center" style="font-size: 12px;">สูงสุด</th>

                                </tr>
                                <?php
                                      $row = DB::table('customers')
            ->select('customers.id', 'customers.user_name', 'customers.introduce_id', 'customers.upline_id',
            'customers.pv_mt_active', 'customers.introduce_type', 'customers.business_name',
                'customers.reward_max_id', 'customers.line_type','customers.team_active_a','customers.team_active_b','customers.team_active_c',
                'dataset_package.dt_package', 'dataset_qualification.code_name', 'q_max.code_name as max_code_name')
            ->leftjoin('dataset_package', 'dataset_package.id', '=', 'customers.package_id')
            ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
            ->leftjoin('dataset_qualification as q_max', 'q_max.id', '=', 'customers.qualification_max_id')
            // ->where('customers.introduce_id', '=', $user_name)
            // ->orwhere('customers.user_name', '=', $user_name)
            ->where('customers.user_name', '=', Auth::guard('c_user')->user()->user_name)

            ->orderbyraw('customers.introduce_type,customers.id ASC')
            // ->orderbyraw('customers.introduce_type ASC')
            ->first();


                                ?>

                                <tr class="info" style='text-align:center;'>
                                  <td  >0</td>
                                  {{-- <td rowspan="2">ID</td> --}}
                                  <td >{{$row->introduce_type}}</td>
                                  <td > @if( !empty($row->business_name) ||  $row->business_name  != '-')
                                     {{$row->business_name}}<b>({{$row->user_name}})</b>
                                  @else
                                    <?php $name = @$row->first_name.' '. @$row->last_name.' <b>('.$row->user_name.')</b>';?>
                                    {!!$name!!}
                                  @endif
                                  </td>
                                  <td >{{ $row->dt_package}}</td>
                                  <td >
                                    <?php $user = DB::table('customers')
                                    ->select('line_type')
                                    ->where('user_name', '=', $row->upline_id)
                                    ->first();
                                    if($user){
                                      $user_text = $row->upline_id . '/' . $user->line_type;
                                    }else{
                                      $user_text = '-';
                                    }
                                    ?>
                                    {{$user_text}}
                                  </td>

                                  <td >
                                    <?php
                                    $check_active_mt =  \App\Helpers\Frontend::check_mt_active($row->pv_mt_active);
                                    if ($check_active_mt['status'] == 'success') {
                                        if ($check_active_mt['type'] == 'Y') {
                                            $active_mt = "<span class='label label-inverse-success'><b>"
                                                . $check_active_mt['date'] . "</b></span>";
                                        } else {
                                            $active_mt = "<span class='label label-inverse-info-border'><b>"
                                                . $check_active_mt['date'] . "</b></span>";
                                        }
                                    } else {
                                        $active_mt = "<span class='label label-inverse-info-border'><b> Not Active </b></span>";
                                    }

                                    ?>
                                    {!!$active_mt!!}
                                  </td>
                                  <td >{{$row->team_active_a}}</td>
                                  <td >{{$row->team_active_b}}</td>
                                  <td >{{$row->team_active_c}}</td>


                                  <td>
                                    <?php
                                     $count_directsponsor = \App\Helpers\Frontend::check_customer_directsponsor($row->team_active_a,$row->team_active_b,$row->team_active_c);

                                    ?>
                                    {{$count_directsponsor['reward_bonus']}}
                                  </td>


                                  <td>{{$row->reward_max_id}}</td>
                                  <td>{{$row->code_name}}</td>
                                  <td>{{$row->max_code_name}}</td>


                              </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
    <div class="col-md-12">
        <div class="card">

           <div class="card-header">
            <h4 class="m-b-10">ข้อมูลแนะนำตรงในรอบ 60 วันแรก (Team Maker)
                ชั้นลูก <b class="text-primary">{{ count($customers_sponser) }}</b> คน + ชั้นหลาน <b id="count_sponser" class="text-primary"></b> คน</h4>

            </div>
            <div class="card-block">

                <div class=" table-responsive">
                    <table id="setting-default" class="table table-striped table-bordered nowrap">

                        <thead>
                          <tr class="info" style='text-align:center;'>
                            <th class="text-center" >#</th>
                            <th class="text-center" >Line</th>
                            <th class="text-center" >ID</th>
                            <th class="text-center" >Name (Buniness)</th>
                            <th class="text-center" >OwnPV</th>
                            <th class="text-center" >Package</th>
                            <th class="text-center" >First Date</th>
                            <th class="text-center" >Remie(Day)</th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php $i = 0; ?>
                        @foreach ($customers_sponser as $value_sponser)
                        <?php $i++;

                        $end_date = strtotime($value_sponser->end_date);
                        $start_date = strtotime(date('Y-m-d'));

                        $date_remine = ($end_date - $start_date) / (60 * 60 * 24); // 1 day = 60*60*24
                        $remine = ceil($date_remine);

                        $directsponsor = DirectSponsor::sponsor_get_directsponsor($value_sponser->user_name);
                        $count_directsponsor = count($directsponsor);
                        if ($count_directsponsor > 0) {
                            $num_row = $count_directsponsor + 1;
                            $rowspan_index = 'rowspan="' . $num_row . '"';
                        } else {
                            $rowspan_index = '';
                        }

                        ?>

                    <tr class="table-warning">

                        <th {{ $rowspan_index }} style="font-size: 13px;"  class="text-center">{{ $i }}</th>
                        <th style="font-size: 13px;"  class="text-center">{{ $value_sponser->introduce_type }}</th>
                        <th style="font-size: 15px;"><label class="label label-inverse-info-border">{{ $value_sponser->user_name }}</label></th>
                        <th style="font-size: 13px;">{{ $value_sponser->prefix_name.' '.$value_sponser->first_name.' '.$value_sponser->last_name }} ({{ $value_sponser->business_name}})</th>


                        <th style="font-size: 13px;"  class="text-center">{{ number_format($value_sponser->pv) }}</th>
                        <th style="font-size: 14px;">
                         @if (empty($value_sponser->dt_package))
                         -
                         @else
                         {{ $value_sponser->dt_package }}
                         @endif
                     </th>
                     <th style="font-size: 13px;"  class="text-center"><span class='label label-inverse-info-border'><b>{{ date('Y/m/d',strtotime($value_sponser->created_at)) }}</b></span></th>

                     <th style="font-size: 13px;"  class="text-center">{{  $remine  }}</th>

                 </tr>


                 @if ($count_directsponsor > 0)
                 @foreach ($directsponsor as $directsponsor_value)

                 <?php
                 $count_sponser++;
                 $end_date = strtotime($directsponsor_value->end_date);
                 $start_date = strtotime(date('Y-m-d'));
                 $date_remine_sponsor = ($end_date - $start_date) / (60 * 60 * 24); // 1 day = 60*60*24
                 $remine_sponsor = ceil($date_remine_sponsor);
                 ?>

                 <tr class="table-active">
                    <td></td>
                    <td style="font-size: 13px;"  class="text-center">{{ $directsponsor_value->introduce_type }}</td>
                    <td style="font-size: 15px;"><span class='label label-inverse-info-border'>{{ $directsponsor_value->user_name }}</span></td>
                    <td style="font-size: 13px;">{{ $directsponsor_value->prefix_name.' '.$directsponsor_value->first_name.' '.$directsponsor_value->last_name }} ({{ $directsponsor_value->business_name}})</td>
                    <td style="font-size: 13px;"  class="text-center">{{ number_format($directsponsor_value->pv) }}</td>
                    <td style="font-size: 14px;">
                     @if (empty($directsponsor_value->dt_package))
                     -
                     @else
                     {{ $directsponsor_value->dt_package }}
                     @endif
                 </td>
                 <td style="font-size: 13px;"  class="text-center"><span class='label label-inverse-info-border'><b>{{ date('Y/m/d',strtotime($directsponsor_value->created_at)) }}</b></span></td>

                 <td  class="text-center">{{ $remine_sponsor }}</td>
             </tr>
             @endforeach
             @endif

             @endforeach
         </tbody>
     </table>
 </div>

</div>
</div>
</div>
</div> --}}

@endsection
@section('js')
    <!-- data-table js -->
    <script src="{{ asset('frontend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/data-table/js/jszip.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/data-table/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/pages/data-table/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('frontend/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('frontend/bower_components/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
    </script>

    <script>
        // var data = {{ $count_sponser }};
        // $('#count_sponser').html(data);
    </script>

    <script type="text/javascript">
        var oTable;
        $(function() {
            oTable = $('#multi-colum-dt').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                paging: false,
                ajax: {
                    url: '{{ route('dt_sponsor') }}',
                    // data: function(d) {
                    //     d.startDate = $('#startDate').val();
                    //     d.endDate = $('#endDate').val();
                    // },
                    method: 'get'
                },


                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', className:'text-center'},

                    {
                        data: 'introduce_type',
                    },

                    {
                        data: 'business_name',
                    },
                    {
                        data: 'dt_package',
                    },
                    {
                        data: 'upline',
                    },
                    {
                        data: 'pv_mt_active',
                    },
                    {
                        data: 'count_directsponsor_a',
                    },
                    {
                        data: 'count_directsponsor_b',
                    },
                    {
                        data: 'count_directsponsor_c',

                    },
                    {
                        data: 'reward_bonus',
                    },
                    {
                        data: 'reward_max_id',
                    },
                    {
                        data: 'code_name',
                    },
                    {
                        data: 'max_code_name',
                    },

                ]
            });
            $('.myWhere,.myLike,.myCustom,#onlyTrashed').on('change', function(e) {
                oTable.draw();
            });

            $('#search-form').on('click', function(e) {
                oTable.draw();
                e.preventDefault();
            });
        });
    </script>
@endsection
