
<div class="modal fade" id="show_transfer_aistockist" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">รายงานการคำนวนโบนัสตามรอบของการโอน วันที่ {{ date('d/m/Y', strtotime($date)) }}
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">

                    <div class="card-block">
                        <div class="dt-responsive table-responsive">
                            <table id="simpletable" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr role="row">
                                        <th>Date</th>
                                        <th>Code</th>
                                        <th>SendTo</th>
                                        <th>Username</th>
                                        <th>Type</th>
                                        <th>PV</th>
                                        <th>Status</th>
                                        {{-- <th>Detail</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $value)
                                        <?php if ($value->status_transfer == '0') {
                                        $status = 'รออนุมัติ';
                                        } elseif ($value->status_transfer == '1') {
                                        $status = 'โอนสำเร็จ';
                                        } elseif ($value->status_transfer == '2') {
                                        $status = 'ยกเลิก';
                                        } elseif ($value->status_transfer == '3') {
                                        $status = 'ไม่อนุมัติ';
                                        } else {
                                        $status = '-';
                                        } ?>
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($value->bonus_transfer_date, 2)) }}</td>
                                            <td> {{ $value->transection_code }} </td>
                                            <td>{{ $value->prefix_name . ' ' . $value->first_name . ' ' . $value->last_name }}
                                            </td>
                                            <td>{{ $value->user_name }}</td>
                                            <td>{{ $value->orders_type }}</td>
                                            <td>{{ $value->pv }}</td>
                                            <td>{{ $status }}</td>
                                            {{-- <td>{{ $value->detail }}</td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-success">
                                        <th></th>
                                        <th></th>
                                        <th class="text-right">PV Total</th>
                                        <th>{{ number_format($data_total['pv_total'], 2) }}</th>
                                        <th class="text-right">Bonus({{ number_format($data_total['tax']) }}%)</th>
                                        <?php $bonus_total = ($data_total['pv_total'] *
                                        $data_total['tax']) / 100; ?>
                                        <th>{{ number_format($bonus_total, 2) }}</th>
                                        <th></th>


                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
            <div class="modal-footer">
              @if($canAccess)
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                @endif
                {{-- <button type="button" class="btn btn-primary waves-effect waves-light ">Save
                  changes</button> --}}
            </div>
        </div>
    </div>
</div>
