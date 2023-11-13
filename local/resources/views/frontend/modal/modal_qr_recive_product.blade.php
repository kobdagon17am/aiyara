       <div class="modal fade" id="show_qr" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
           <div class="modal-dialog modal-md" role="document">

               <div class="modal-content">
                   <div class="modal-header">
                       <h4 class="modal-title">QRCODE Recive Product</h4>
                   </div>

                   <div class="modal-body text-center">
                       <p>{{$data_order->qr_code}}</p>
                       {{ QrCode::size(300)->generate($data_order->qr_code) }}
                       <p>{{ $data_order->code_order }}</p>
                       <?php
                       $now = \Carbon\Carbon::now();
                       //$date = Carbon::parse(date('Y-m-d H:i:s', strtotime('+30 minutes')));
                       $s = $now->diffInSeconds($data_order->qr_endate);
                       $i = $now->diffInMinutes($data_order->qr_endate);
                       //$time =  strtotime($data_order->qr_endate) - strtotime(date('Y-m-d H:i:s'));
                       //$time = Carbon::now()->subSeconds('Y-m-d H:i:s',$data_order->qr_endate)->diffForHumans();
                       //$i = date('i', $time);
                       //$s = date('s', $time);
                       ?>

                       <input type="hidden" id="s" value="{{ $s }}" >
                       <input type="hidden" id="i" value="{{ $i }}" >
                       <input type="hidden" id="id" value="{{ $data_order->id }}" >
                       <input type="hidden" id="type_qr_modal" value="{{ $type_qr_modal }}">

                       <span class="text-danger"> กรุณาใช้ QRCODE นี้ภายใน <b id="time"> </b> </span>

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

