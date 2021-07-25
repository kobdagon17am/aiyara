
    <div class="modal fade" id="show_qr" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-md" role="document">

          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">QRCODE Register Course/Event</h4>
              </div>

              <div class="modal-body text-center">
                <?php //dd($data); ?>
                  {{ QrCode::size(300)->generate($data->qr_code) }}
                  <p>{{ $data->ticket_number }}</p>
                  <?php
                  $now = \Carbon\Carbon::now();
                  //$date = Carbon::parse(date('Y-m-d H:i:s', strtotime('+30 minutes')));
                  $s = $now->diffInSeconds($data->qr_endate);
                  // $i = $now->diffInMinutes($data->qr_endate);
                  //$time =  strtotime($data->qr_endate) - strtotime(date('Y-m-d H:i:s'));
                  //$time = Carbon::now()->subSeconds('Y-m-d H:i:s',$data->qr_endate)->diffForHumans();
                  //$i = date('i', $time);
                  //$s = date('s', $time);
                  ?>

                  <input type="hidden" id="s" value="{{ $s }}" >
                  {{-- <input type="hidden" id="i" value="{{ $i }}" > --}}
                  <input type="hidden" id="id" value="{{ $data->id }}" >
                  <input type="hidden" id="type_qr_modal" value="{{ $type_qr_modal }}">

                  <b id="time"> </b>

                  <article class="clock" id="model3">
                   <h3></h3>

                   <div class="count">
                     <div id="timer"></div>
                   </div>
                 </article>

              </div>

              <div class="modal-footer">
                <div id="close_modal"></div>

              </div>

          </div>

      </div>
  </div>
