 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Payment PDF</title>
     <link rel="icon" href="{{asset('frontend/assets/icon/logo_icon.png')}}" type="image/x-icon">
     <style>
     @font-face {
         font-family: 'THSarabunNew';
         font-style: normal;
         font-weight: normal;
         src: url("{{ asset('backend/fonts/THSarabunNew.ttf') }}") format('truetype');
     }

     @font-face {
         font-family: 'THSarabunNew';
         font-style: normal;
         font-weight: bold;
         src: url("{{ asset('backend/fonts/THSarabunNew Bold.ttf') }}") format('truetype');
     }

     @font-face {
         font-family: 'THSarabunNew';
         font-style: italic;
         font-weight: normal;
         src: url("{{ asset('backend/fonts/THSarabunNew Italic.ttf') }}") format('truetype');
     }

     @font-face {
         font-family: 'THSarabunNew';
         font-style: italic;
         font-weight: bold;
         src: url("{{ asset('backend/fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
     }

     body {
         font-family: "THSarabunNew";
         font-size: 16px;
     }

     .td-custom {
         padding-left: 15px;
         valign="top";
     }

     @page {
         size: A4;
         padding: 5px;
     }
     @media print {

         html,
         body {
             /*width: 210mm;*/
             /*height: 297mm;*/
         }
     }
     ul li {
         display: block;
     }

     ul {
         margin-top: 0px;
         padding-left: 14px;
     }

     p {
         line-height: 15px
     }


     @charset "utf-8";
     </style>

 </head>

 <body>
   <div>
     <?php //dd(asset('backend/images/logo2.png')) ?>
    <img src="<?=public_path('images/logo2.png')?>" width="50">
  </div>

    <table width="100%" style="width:100%" border=0" width="30%" >

         <th style="text-align: left;">
          @if($address)
          <p><b>{{ $address['name'] }}</b><br>
            @if ($address['tel']) Tel: {{ $address['tel'] }} <br>@endif
            @if ($address['email']) Email: {{ $address['email'] }}<br>@endif
            @if ($address['house_no']) {{ $address['house_no'] }} @endif
            @if ($address['moo'] != '-' and $address['moo'] != '') หมู่.{{ $address['moo'] }} @endif
            @if ($address['house_name'] != '-' and $address['house_name'] != '') {{ $address['house_name'] }} @endif
            @if ($address['soi'] != '-' and $address['soi'] != '') {{ $address['soi'] }} @endif
            @if ($address['road'] != '-' and $address['road'] != '') ถนน.{{ $address['road'] }} @endif

            @if ($address['district_name'] != '-' and $address['district_name'] != '')<br>{{ $address['district_name'] }} @endif
            @if ($address['amphures_name'] != '-' and $address['amphures_name'] != ''){{ $address['amphures_name'] }} @endif
            @if ($address['provinces_name'] != '-' and $address['provinces_name'] != ''){{ $address['provinces_name'] }} @endif
            @if ($address['zipcode']) {{ $address['zipcode'] }}@endif
        </p>
      @else
       <p><b> จัดส่งพร้อมบิลอื่น </b></p>
      @endif

             @if ($order->status_payment_sent_other == 1)
                 <?php
                    $sent_to_customer_data = \App\Helpers\Frontend::get_customer_id($order->customers_sent_id_fk);
                    $customer_pay = \App\Helpers\Frontend::get_customer_id($order->customers_id_fk);

                 ?>
                 <hr>
                 <span class="mb-2"><u>สั่งซื้อให้ลูกทีม</u></span>
                 <p class="mt-1">
                  <b>ผู้สังซื้อ </b> |  {{ $customer_pay->first_name }} {{ $customer_pay->last_name }} ({{ $customer_pay->user_name }})
                   <br>
                  <b>ผู้รับคะแนน </b> |  {{ $sent_to_customer_data->first_name }} {{ $sent_to_customer_data->last_name }} ({{ $sent_to_customer_data->user_name }})
                 </p>

            @endif

         </th>

         <th style="text-align: left;" valign="top">
             <p>Date : @if($order->created_at) {{ date('d/m/Y',strtotime($order->created_at)) }} @endif <br>
                 Status : <u>{{ $order->detail }}</u> <br>
                 Type : @if($order->type) {{ $order->type }} @endif
             </p>
         </th>

         <th style="text-align: left;" valign="top">
             <p>เลขใบสั่งซื้อ : @if($order->detail) {{ $order->code_order }} @endif<br>
                 เลขใบเสร็จ : @if($order->order_payment_code)
                 {{ $order->order_payment_code }} @endif <br>
                 Paid by : <u>@if($order->pay_type_name)
                     {{ $order->pay_type_name }} @endif</u>
             </p>
         </th>

     </table>

     <table width="100%" style="width:100%;border-collapse: collapse;" border="1" cellspacing="0">
         <tr style="text-align: left;    background-color: aliceblue;">
             <th style="text-align: center;">Description</th>
             @if($order->purchase_type_id_fk == 6)<th style="text-align: left;">Ticket Number</th>@endif
             @if($order->purchase_type_id_fk == 7)@else <th style="text-align: center;">Quantity</th> @endif
             <th style="text-align: center;">Amount</th>
             <th style="text-align: center;">PV</th>
             <th style="text-align: center;">Total</th>
         </tr>

         @foreach($order_items as $index => $value)

         <tr>
             <td class="td-custom">
                 <b>{{ $value->product_name }}</b>
                 @if($value->type_product == 'promotion')

                    @if($value->promotion_code)
                      <br><b style="color: #000"> CODE : {{ $value->promotion_code }} </b>
                    @endif
                 <ul class="mt-2">
                     <?php
                    $location_id = Auth::guard('c_user')->user()->business_location_id;
                    $get_promotion_detail = \App\Helpers\Frontend::get_promotion_detail($value->promotion_id_fk, $location_id);
                    ?>

                     @foreach($get_promotion_detail as $promotion_product)
                     <li>-
                         {{ $promotion_product->product_name }}
                         {{ $promotion_product->product_amt }} {{ $promotion_product->unit_name }}
                     </li>
                     @endforeach
                 </ul>
                 @endif

                 @if($value->type_product == 'giveaway')
                 <ul>
                     <?php $location_id = Auth::guard('c_user')->user()->business_location_id;
                     $get_giveaway = \App\Helpers\Frontend::get_giveaway_detail($value->id, $location_id);
                     ?>

                     @foreach($get_giveaway as $giveaway_value)
                     @if($giveaway_value->type_product == 'giveaway_product')
                     <li>
                         <?php $sum_giveaway = $giveaway_value->product_amt * $value->amt?>
                         -
                         {{ $giveaway_value->product_name }} {{ $giveaway_value->product_amt }}
                         {{ $giveaway_value->product_unit_name }}
                         x [{{$value->amt}}] <b> => {{$sum_giveaway}}
                             {{ $giveaway_value->product_unit_name }}</b>
                     </li>

                     @else
                     <li>
                         <?php $gv_giveaway = number_format($giveaway_value->gv_free * $value->amt)?>
                         - GiftVoucher
                         {{$giveaway_value->gv_free}} x [{{$value->amt}}]
                         <b> => {{$gv_giveaway}} GV </b>
                     </li>
                     @endif
                     @endforeach
                 </ul>
                 @endif
             </td>

             @if($order->purchase_type_id_fk == 6)
             <td style="text-align: center;"><b>{{ $value->ticket_number }}</b></td>
             @endif
             @if($order->purchase_type_id_fk == 7)
             @else
             <td style="text-align: center;">{{ $value->amt }}</td>
             @endif
             <td style="text-align: center;">{{ number_format($value->selling_price,2) }}</td>
             <td style="text-align: center;"><b>{{ $value->pv }}</b></td>
             @if($order->purchase_type_id_fk == 7)
             <td style="text-align: center;">{{ number_format($value->selling_price,2) }}</td>
             @else
             <td style="text-align: center;">{{ number_format($value->amt * $value->selling_price,2) }}</td>
             @endif

         </tr>
         @endforeach

     </table>
     <br>

     <table align="right" border="0" cellspacing="0" style="border-spacing: 0px">
         <tr>
             <th  style="text-align: right;font-size: 16px">มูลค่าสินค้า : </th>
             <?php
             //$price_vat = $order->sum_price * ($order->vat / 100);
             $price_vat_sum = $order->sum_price - $order->tax;
             ?>
             <th  style="text-align: left;padding-left:10px;font-size: 16px" width="28%"> {{ number_format($price_vat_sum,2) }}</th>
         </tr>
         <tr>
             <th style="text-align: right;font-size: 16px"> VAT({{ $order->vat }}%) : </th>
             <th style="text-align: left;padding-left:10px;font-size: 16px"> {{ number_format(@$price_vat,2) }}</th>
         </tr>
         <tr>
             <th style="text-align: right;font-size: 16px">รวม : </th>
             <th style="text-align: left;padding-left:10px;font-size: 16px"> {{ number_format($order->sum_price,2) }}</th>
         </tr>
         @if($order->purchase_type_id_fk != 6 and $order->purchase_type_id_fk != 7)

         <tr>
             <th style="text-align: right;"> @if($order->shipping_cost_detail)<span style="color:#607d8b">( {{ $order->shipping_cost_detail }} )</span> @endif  <b>ค่าจัดส่ง : </b></th>
             <th  style="text-align: left;padding-left:10px;font-size: 18px"> {{ number_format($order->shipping_price,2) }} </th>
         </tr>
         @endif
         <tr>
             <th style="text-align: right;">คะแนนที่ได้รับ : </th>
             @if($order->purchase_type_id_fk == 5)
             <th  style="text-align: left;padding-left:10px;font-size: 18px"><b> 0 PV </b> </th>
             @else
             <th  style="text-align: left;padding-left:10px;font-size: 18px"><b> {{ $order->pv_total }} PV </b> </th>
             @endif

         </tr>

         @if($order->purchase_type_id_fk == 5)

         <tr>
             <th  style="text-align: right;font-size: 18px"><strong>ยอดรวม : </strong></th>
             <th  style="text-align: left;padding-left:10px;font-size: 18px"><strong>
              {{ number_format($order->total_price,2) }}</strong>
             </th>
         </tr>
         <tr style="margin-bottom: 0px;padding-bottom: 0px">
             <th  style="text-align: right;font-size: 16px"><strong class="text-primary"> Ai Voucher : </strong></th>
             <th  style="text-align: left;padding-left:10px;font-size: 16px"><strong class="text-primary">
              {{  number_format($order->gift_voucher_price,2) }}</strong>
             </th>
         </tr>

         <tr style="margin-top: 0px;padding-top: 0px">
          <?php $price_remove_gv= $order->total_price - $order->gift_voucher_price; ?>
             <th  style="text-align: right;font-size: 20px"><strong>ยอดที่ต้องชำระเพิ่ม : </strong></th>
             <th style="text-align: left;padding-left:10px;font-size: 20px;"><strong> {{  number_format($price_remove_gv,2) }}</strong></th>

         </tr>

         @elseif($order->purchase_type_id_fk == 6 || $order->purchase_type_id_fk == 7)
         <tr>
             <th  style="text-align: right;font-size: 20px"><strong>ยอดชำระ</strong></th>
             <th  style="text-align: left;padding-left:10px;font-size: 20px"><strong> <u>{{ number_format($order->sum_price,2) }}</u></strong>
             </th>
         </tr>
         @else
         <tr>
             <th  style="text-align: right;font-size: 20px"><strong>ยอดชำระ : </strong></th>
             <th  style="text-align: left;padding-left:10px;font-size: 20px"><strong>
                <u>{{ number_format($order->sum_price + $order->shipping_price,2) }}</u></strong>
             </th>
         </tr>
         @endif

     </table>

 </body>

 </html>
