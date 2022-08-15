<?php
use App\Helpers\Frontend;
use App\Models\Frontend\DirectSponsor;

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
            <h4 class="m-b-10">{{trans('message.p_comission score movement and daily bonus')}}  [{{ Auth::guard('c_user')->user()->user_name }}]</h4>
            <div class="col-md-12">
              <div class="row">
                  <div class="col-lg-3 col-md-3 p-1">
                      <input class="form-control" type="date" id="s_date" value="{{ date('Y-m-01') }}">
                  </div>
                  <div class="col-lg-3 col-md-3  p-1">
                      <input class="form-control" type="date" id="e_date" value="{{ date('Y-m-t') }}">
                  </div>
                  <div class="col-lg-1 col-md-1 p-1">
                      <button id="search-form" class="btn btn-primary btn-block"> Start </button>
                  </div>
              </div>
          </div>
        </div>
        <div class="card-block">

            <div class=" table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">

                    <thead>
                      <tr class="info" style='text-align:center;'>
                        <th class="text-center" rowspan="2" >{{trans('message.p_comission date')}}</th>
                        <th class="text-center" rowspan="2" >{{trans('message.p_comission personal')}}</th>
                        <th class="text-center" rowspan="2" >{{trans('message.p_comission buy')}}</th>
                        <th class="text-center" rowspan="2" >{{trans('message.p_comission keep')}}</th>
                        <th class="text-center" colspan="3" >{{trans('message.p_comission new s')}}</th>
                        <th class="text-center" colspan="3" >{{trans('message.p_comission balance')}}</th>

                        <th class="text-center"  rowspan="2">Faststart</th>
                        <th class="text-center"  rowspan="2">Pro.</th>
                        <th class="text-center"  rowspan="2">TMB</th>
                        <th class="text-center"  rowspan="2">Booster</th>
                        <th class="text-center"  rowspan="2">Matching</th>
                        <th class="text-center"  rowspan="2">Pool</th>
                        <th class="text-center"  rowspan="2">Outime</th>
                        <th class="text-center"  rowspan="2">{{trans('message.p_comission total')}}</th>
                    </tr>
                    <tr>
                        <th class="text-center"  style="font-size: 12px;">A</th>
                        <th class="text-center"  style="font-size: 12px;">B</th>
                        <th class="text-center"  style="font-size: 12px;">C</th>

                        <th class="text-center"  style="font-size: 12px;">A</th>
                        <th class="text-center"  style="font-size: 12px;">B</th>
                        <th class="text-center"  style="font-size: 12px;">C</th>

                    </tr>
                </thead>

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
<script type="text/javascript">

  $(function() {
    $.ajaxSetup({headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }

});
      var oTable = $('#multi-colum-dt').DataTable({
          processing: true,
          serverSide: true,
          searching: true,
          ajax: {
              url: "{!! route('dt_commission_perday') !!}",
              type:'POST',
              data: function(d) {
                    d.s_date = $('#s_date').val();
                    d.e_date = $('#e_date').val();
                }
              },
          // type: "POST",
          columns: [{data: 'action_date'},
              {data: 'pv'},
              {data: 'pv_pay_add'},
              {data: 'pv_pay_active'},
              {data: 'new_pv_a'},
              {data: 'new_pv_b'},
              {data: 'new_pv_c'},
              {data: 'old_pv_a'},
              {data: 'old_pv_b'},
              {data: 'old_pv_c'},
              {data: 'faststart'},
              {data: 'pro'},
              {data: 'tmb'},
              {data: 'booster'},
              {data: 'matching'},
              {data: 'pool'},
              {data: 'outtime'},
              {data: 'bonus_total'},
          ],order:[[0,'DESC']]
      });

      $('#search-form').on('click', function(e) {
            oTable.draw();
            e.preventDefault();
        });
  });




</script>


@endsection





