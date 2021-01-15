       <div class="modal fade" id="show_qr" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-md" role="document">

          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">QRCODE Register Course/Event</h4>
            </div>
             

            <div class="modal-body text-center">
             {{ QrCode::size(300)->generate($data->qr_code) }}
             <p>{{ $data->ticket_number }}</p>
           </div>

           <div class="modal-footer">
            <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
          </div>

        </div>

      </div>
    </div>