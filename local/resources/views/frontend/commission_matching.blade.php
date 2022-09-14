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
            <h4 class="m-b-10">Matching Bonus Detail</h4>
             <h5 class="m-b-10">{{$data['user_name']}} วันที่: ( {{ date('d/m/Y',strtotime($data['date'])) }} ) </h5>
        </div>

        <div class="card-block">
            <div class=" table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">

                    <thead>
                      <tr class="info" style='text-align:center;'>
                        <th class="text-center" >Deep</th>
                        <th class="text-center" >Gen</th>
                        <th class="text-center" >UserName</th>
                        <th class="text-center" >Name</th>
                        <th class="text-center" >Bns_strong_leg</th>
                        <th class="text-center" >Benefit</th>
                        <th class="text-center" >Reward Bonus</th>
                    </tr>
                </thead>
                <tfoot>
                  <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Total</td>
                      <td></td>

                  </tr>
              </tfoot>



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
<script type="text/javascript">
 function numberWithCommas(x) {
        // x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        rs = parseFloat(x).toFixed(2);
        return rs;

    }
  $(function() {
      var oTable = $('#multi-colum-dt').DataTable({
          processing: true,
          serverSide: true,
          searching: true,
          paging: false,
          ajax: {
              url: "{!! route('dt_commission_matching') !!}",
              type:'GET',
              data: function (d) {
                d.user_name = "{{ $data['user_name'] }}";
                d.date =  "{{ $data['date'] }}";
            }
              },

          columns: [{data: 'deep'},
              {data: 'gen'},
              {data: 'username'},
              {data: 'name'},
              {data: 'bns_strong_leg'},
              {data: 'benefit'},
              {data: 'reward_bonus'},
          ],order:[[0,'DESC']],
          "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;

                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };


                sum = api
                    .column(6, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                $(api.column(6).footer()).html(numberWithCommas(sum));

            }
      });
  });
</script>


@endsection





