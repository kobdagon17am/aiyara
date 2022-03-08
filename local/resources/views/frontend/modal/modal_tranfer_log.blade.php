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

            <div class="modal-body text-left">
                <span>เลขใบสั่งซื้อ : {{ $file_slip[0]->code_order }}</span>
                <table class="table table-responsive">
                    <tbody>
                        @foreach ($file_slip as $value)
                            <tr>
                                <td>
                                    <p>{{ date('d/m/Y') }}</p><img class="img-fluid"
                                        src="{{ asset($value->url . '/' . $value->file) }}" alt="Theme-Logo">
                                </td>
                                <td>
                                    <p>{{ $value->note }}</p>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>



            </div>


        </div>
    </div>
</div>
