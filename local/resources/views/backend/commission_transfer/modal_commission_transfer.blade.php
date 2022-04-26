<div class="modal fade" id="show_transfer" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">รายงานการคำนวนโบนัสตามรอบของการโอน วันที่ {{ date('d/m/Y',strtotime($date)) }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        {{-- <h5>รายงานการคำนวนโบนัสตามรอบของการโอน วันที่ 2021/01/01</h5> --}}
                        {{-- <span class="text-danger">*รหัสโปรโมชั่นละ 1 ชุด สามารถส่งต่อให้สมาชิกท่านอื่นๆได้ / ไม่สามารถใช้สิทธิ์กับรายการส่งเสริมการขายอื่นๆ รวมถึงการเติม AIPocket</span> --}}
                    </div>
                    <div class="card-block">

                          <table id="data-table" class="table table-bordered " style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th>Date</th>
                                        <th>FastStart</th>
                                        <th>TMB</th>
                                        <th>Booster</th>
                                        <th>Reward</th>
                                        <th>TeamMaker</th>
                                        <th>Promotion</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach ($data as $value)
                                  <?php //dd($value); ?>
                                  <tr>
                                    <td>{{ date('d/m/Y',strtotime($value->transfer_date,2)) }}</td>
                                    <td><b class="text-success">{{ number_format($value->faststart,2) }}</b></td>
                                    <td>{{ number_format($value->tmb,2) }}</td>
                                    <td>{{ number_format($value->booster,2) }}</td>
                                    <td>{{ number_format($value->reward,2) }}</td>
                                    <td>{{ number_format($value->team_maker,2) }}</td>
                                    <td>{{ number_format($value->pro,2) }}</td>
                                    <td>{{ number_format($value->bonus_total,2) }}</td>
                                </tr>
                                  @endforeach
                                </tbody>
                                <tfoot>
                                          <tr>
                                              <th>Total</th>
                                              <th>{{ number_format($total->faststart_total,2) }}</th>
                                              <th>{{ number_format($total->tmb_total,2) }}</th>
                                              <th>{{ number_format($total->booster_total,2) }}</th>
                                              <th>{{ number_format($total->reward_total,2) }}</th>
                                              <th>{{ number_format($total->team_maker_total,2) }}</th>
                                              <th>{{ number_format($total->pro_total,2) }}</th>
                                              <th>{{ number_format($total->sum_bonus_total,2) }}</th>
                                          </tr>
                                      </tfoot>
                            </table>

                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary waves-effect waves-light ">Save
                    changes</button> --}}
            </div>
        </div>
    </div>
</div>
