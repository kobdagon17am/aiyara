 @extends('frontend.layouts.customer.customer_app')
 @section('css')

 @endsection
 @section('conten')
 <!-- Invoice card start -->
 <div class="card">

     <div class="card-block">
         <div class="card-header">

             <div class="card-header-right">
             <a href="{{route('export_pdf_history',['code_order'=>$order->code_order])}}" target="_new"><span class="text-rimary"><i class="fa fa-file-pdf-o"> PDF</i></span></a>

             </div>
         </div>
         <div class="row invoive-info">
             <div class="col-md-4 col-xs-12 invoice-client-info">
                 <h6>ที่อยู่การจัดส่ง :</h6>
                 @if($address)
                     <p><b>{{ $address['name'] }}</b><br>
                      @if($address['tel']) Tel: {{ $address['tel'] }} <br>@endif
                      @if($address['email']) Email: {{ $address['email']}}<br>@endif
                      @if($address['house_no']) {{$address['house_no']}},@endif
                      @if($address['moo']) หมู่.{{ $address['moo'] }},@endif
                      @if($address['house_name']) บ.{{ $address['house_name'] }},@endif
                      @if($address['soi']) ซอย.{{ $address['soi'] }},@endif
                      @if($address['road']) ถนน.{{ $address['road'] }},@endif

                      @if($address['district_name'])<br> ต.{{ $address['district_name'] }},@endif
                      @if($address['amphures_name']) อ.{{ $address['amphures_name'] }},@endif
                      @if($address['provinces_name']) จ.{{ $address['provinces_name'] }},@endif
                      @if($address['zipcode']) {{ $address['zipcode'] }}@endif
                  </p>
                 @else
                  <p><b> Address Is Null</b>
                 @endif
                 @if($order->status_payment_sent_other == 1)
                 <?php
                 $sent_to_customer_data = \App\Helpers\Frontend::get_customer($order->address_sent_id_fk);

  //                dd($sent_to_customer_data);
  //                +"prefix_name": "คุณ"
  // +"first_name": "ชฎาพรww"
  // +"last_name": "พิกุลe"
  // +"business_name": "Orange Thailand"
  // +"user_name": "A0000032"
                 ?>
                 <hr>
                 <p>
                  <b>สั่งซื้อให้กับ {{ $sent_to_customer_data->prefix_name }} {{ $sent_to_customer_data->first_name }} {{ $sent_to_customer_data->last_name }}
                      </b><br>
                  <b>( {{ $sent_to_customer_data->business_name }} ) User : {{ $sent_to_customer_data->user_name }}</b>
                 </p>

                 @endif
             </div>
             <div class="col-md-4 col-sm-6">
                 <h6>Order Information :</h6>
                 <table class="table table-responsive invoice-table invoice-order table-borderless">
                     <tbody>
                         <tr>
                             <th>Date :</th>
                             <td>@if($order->created_at) {{ date('d/m/Y',strtotime($order->created_at)) }} @endif</td>
                         </tr>
                         <tr>
                             <th>Status :</th>
                             <td>
                                 @if($order->detail)<span
                                     class="label label-{{ $order->css_class }}">{{ $order->detail }}</span>@endif
                             </td>
                         </tr>
                         <tr>
                             <th>Type :</th>
                             <td>
                                 @if($order->type) <span>{{ $order->type }}</span> @endif
                             </td>
                         </tr>
                         {{--     <tr>
                        <th>Paid by :</th>
                        <td>
                         @if($order->pay_type_name) <span>{{ $order->pay_type_name }}</span> @endif
                         </td>
                         </tr> --}}


                     </tbody>
                 </table>
             </div>
             <div class="col-md-4 col-sm-6">
                 <h6 class="m-b-20">เลขใบสั่งซื้อ : @if($order->detail)<span> {{ $order->code_order }} </span> @endif
                 </h6>

                 <h6 class="m-b-20 ">เลขใบเสร็จ : @if($order->order_payment_code)<span class="text-success">
                         {{ $order->order_payment_code }} </span> @endif</h6>

                 <h6 class="m-b-20 ">Paid by : @if($order->pay_type_name)<span class="text-success">
                         {{ $order->pay_type_name }} </span> @endif</h6>

                 {{--   <table class="table table-responsive invoice-table invoice-order table-borderless">
                <tbody>
                    <tr>
                        <th>มูลค่าสินค้า : </th>
                        <td> {{ number_format($order->price,2) }}</td>
                 </tr>
                 <tr>
                     <th>VAT({{ $order->tax }}%) : </th>
                     <td> {{ $order->p_vat }}</td>
                 </tr>
                 <tr>
                     <th>รวม : </th>
                     <td> {{ number_format($order->price,2) }}</td>
                 </tr>
                 <tr>
                     <th>ค่าจัดส่ง : </th>
                     <td> {{ number_format($order->shipping,2) }} </td>
                 </tr>
                 <tr>
                     <th>ยอดชำระ : </th>
                     <td> {{ number_format($order->price+$order->shipping,2) }}</td>
                 </tr>
                 <tr>
                     <th>คะแนนที่ได้รับ : </th>
                     <td class="text-success"><b> {{ $order->pv_total }} PV </b></td>
                 </tr>
                 </tbody>
                 </table> --}}
             </div>
         </div>
         <div class="row">

             <div class="col-sm-12">
                 <div class="table-responsive">
                     <table class="table  invoice-detail-table">
                         <thead>
                             <tr class="thead-default">
                                 <th>Description</th>
                                 @if($order->purchase_type_id_fk == 6)<th>Ticket Number</th>@endif
                                 @if($order->purchase_type_id_fk == 7)@else <th>Quantity</th> @endif
                                 <th>Amount</th>
                                 <th>PV</th>
                                 <th>Total</th>
                             </tr>
                         </thead>
                         <tbody>
                             @foreach($order_items as $index => $value)

                             <tr>
                                 <td>
                                     <h6>{{ $value->product_name }}</h6>

                                     @if($value->type_product == 'promotion')

                                     <ul>
                                         <?php
$location_id = Auth::guard('c_user')->user()->business_location_id;
$get_promotion_detail = \App\Helpers\Frontend::get_promotion_detail($value->promotion_id_fk, $location_id);
?>
                                         @foreach($get_promotion_detail as $promotion_product)
                                         <li style="font-size: 12px">
                                             <i class="icofont icofont-double-right text-success"></i>
                                             {{ $promotion_product->product_name }}
                                             {{ $promotion_product->product_amt }} {{ $promotion_product->unit_name }}
                                         </li>
                                         @endforeach
                                     </ul>
                                     @endif

                                     @if($value->type_product == 'giveaway')
                                     <ul>
                                         <?php
$location_id = Auth::guard('c_user')->user()->business_location_id;
$get_giveaway = \App\Helpers\Frontend::get_giveaway_detail($value->id, $location_id);

?>

                                         @foreach($get_giveaway as $giveaway_value)

                                         @if($giveaway_value->type_product == 'giveaway_product')

                                         <li style="font-size: 12px">
                                             <?php $sum_giveaway = $giveaway_value->product_amt * $value->amt?>
                                             <i class="icofont icofont-double-right text-success"></i>
                                             {{ $giveaway_value->product_name }} {{ $giveaway_value->product_amt }}
                                             {{ $giveaway_value->product_unit_name }}
                                             x [{{$value->amt}}] <b> => {{$sum_giveaway}}
                                                 {{ $giveaway_value->product_unit_name }}</b>
                                         </li>


                                         @else

                                         <li style="font-size: 12px">
                                             <?php $gv_giveaway = number_format($giveaway_value->gv_free * $value->amt)?>
                                             <i class="icofont icofont-double-right text-success"></i>GiftVoucher
                                             {{$giveaway_value->gv_free}} x [{{$value->amt}}]
                                             <b> => {{$gv_giveaway}} GV </b>
                                         </li>
                                         @endif

                                         @endforeach

                                     </ul>
                                     @endif
                                 </td>

                                 @if($order->purchase_type_id_fk == 6)
                                 <td><b class="text-primary">{{ $value->ticket_number }}</b></td>
                                 @endif
                                 @if($order->purchase_type_id_fk == 7)
                                 @else
                                 <td>{{ $value->amt }}</td>
                                 @endif
                                 <td>{{ number_format($value->selling_price,2) }}</td>
                                 <td class="text-success"><b>{{ $value->pv }}</b></td>
                                 @if($order->purchase_type_id_fk == 7)
                                 <td>{{ number_format($value->selling_price,2) }}</td>
                                 @else
                                 <td>{{ number_format($value->amt * $value->selling_price,2) }}</td>
                                 @endif

                             </tr>
                             @endforeach


                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
         <div class="row">
             <div class="col-sm-12">
                 <table class="table table-responsive invoice-table invoice-total">
                     <tbody>
                         <tr>
                             <th>มูลค่าสินค้า : </th>

                             <?php

$price_vat = $order->sum_price * ($order->tax / 100);
$price_vat_sum = $order->sum_price - $price_vat;
?>
                             <td> {{ number_format($price_vat_sum,2) }}</td>
                         </tr>
                         <tr>
                             <th>VAT({{ $order->tax }}%) : </th>
                             <td> {{ number_format($price_vat,2) }}</td>
                         </tr>
                         <tr>
                             <th>รวม : </th>
                             <td> {{ number_format($order->sum_price,2) }}</td>
                         </tr>
                         @if($order->purchase_type_id_fk != 6 and $order->purchase_type_id_fk != 7)

                         <tr>
                             <th> @if($order->shipping_cost_detail) <label class="label label-inverse-warning"><font id="shipping_detail" style="color: #000">{{ $order->shipping_cost_detail }}
                            </font></label>@endif ค่าจัดส่ง : </th>
                             <td> {{ number_format($order->shipping_price,2) }} </td>
                         </tr>


                         @endif
                         <tr>
                             <th>คะแนนที่ได้รับ : </th>
                             <td class="text-success"><b> {{ $order->pv_total }} PV </b></td>
                         </tr>


                         @if($order->purchase_type_id_fk == 5)

                         <tr>
                             <td><strong>ยอดรวม : </strong></td>
                             <td align="right"><strong>
                                     {{ number_format($order->total_price,2) }}</strong>
                             </td>
                         </tr>
                         <tr>
                             <td><strong class="text-primary">Ai Voucher : </strong></td>
                             <td align="right"><strong class="text-primary">
                                     {{  number_format($order->gift_voucher_price,2) }}</strong>
                             </td>
                         </tr>

                         <tr>
                             <td><strong>ยอดที่ต้องชำระเพิ่ม : </strong></td>
                             <?php $price_remove_gv= $order->total_price - $order->gift_voucher_price; ?>
                             <td align="right"><strong> {{  number_format($price_remove_gv,2) }}</strong>

                             </td>
                         </tr>

                         @elseif($order->purchase_type_id_fk == 6 || $order->purchase_type_id_fk == 7)
                         <tr>
                             <td><strong>ยอดชำระ</strong></td>
                             <td align="right"><strong> <u>{{ number_format($order->sum_price,2) }}</u></strong>
                             </td>
                         </tr>
                         @else
                         <tr>
                             <td><strong>ยอดชำระ</strong></td>
                             <td align="right"><strong>
                                     <u>{{ number_format($order->total_price,2) }}</u></strong>
                             </td>
                         </tr>
                         @endif
                     </tbody>
                 </table>


             </div>
         </div>
         {{-- <div class="row">
    <div class="col-sm-12">
        <h6>Terms And Condition :</h6>
        <p>lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor </p>
    </div>
</div> --}}
     </div>
 </div>

 </div>
 </div>


 @endsection
 @section('js')

 @endsection
