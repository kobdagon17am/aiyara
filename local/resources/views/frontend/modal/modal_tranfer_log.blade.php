<div class="modal fade" id="log_tranfer_show" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title" style="color: #FFFF">{{ $customer->first_name . ' ' . $customer->last_name }}
                    ({{ $customer->user_name }}) </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-left p-4">
               <div class="col-md-12">
                <div class="row">
                  <p><span>เลขใบสั่งซื้อ : {{ $file_slip[0]->code_order }}</span><br>
                    <code>{{  $order->transfer_bill_note }}</code>
                  </p>
                </div>
                  <div class="row">
                    <table class="table table-responsive">
                      <tbody>
                        <tr>
                          <th> วันที่ </th>
                          <th> บิลใบเสร็จ </th>
                          <th> หมายเหตุ </th>
                        </tr>
                          @foreach ($file_slip as $value)
                              <tr>
                                  <td>
                                      <p>{{ date('d/m/Y') }}</p>
                                  </td>
                                  <td>
                                   <img class="img-fluid"
                                        src="{{ asset($value->url . '/' . $value->file) }}" alt="Theme-Logo">
                                </td>
                                  <td>
                                      <p>{{ $value->note2 }}</p>
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
                  </div>
               </div>
            </div>
        </div>
    </div>
</div>
