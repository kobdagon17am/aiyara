@extends('frontend.layouts.customer.customer_app')
@section('css')

@endsection
@section('conten')
    <!-- Invoice card start -->
    <div class="card">

        <div class="card-block">
            <div class="card-header">

                <div class="card-header-right">

                      <div class="btn-group " role="group" data-toggle="tooltip" data-placement="top" title="" data-original-title=".btn-xlg"  >
                        <a href="{{route('salepage.vip-report')}}" class="btn btn-primary btn-mini waves-effect waves-light"><i class="fa fa-reply" style="color:#fff"></i>กลับ </a>

                    </div>

                </div>
            </div>
            <div class="row invoive-info">
                <div class="col-md-4 col-xs-12 invoice-client-info">
                    <?php
                    //'sent_address','sent_address_card','sent_office','sent_address_other'

                    if ($order->delivery_location_frontend == 'sent_address') {
                        $address_sent = 'ที่อยู่ตามที่ลงทะเบียน';
                    } elseif ($order->delivery_location_frontend == 'sent_address_card') {
                        $address_sent = 'ที่อยู่ตามบัตรประชาชน';
                    } elseif ($order->delivery_location_frontend == 'sent_office') {
                        $address_sent = 'รับที่สาขา';
                    } elseif ($order->delivery_location_frontend == 'sent_address_other') {
                        $address_sent = 'ที่อยู่อื่นๆ';
                    } else {
                        $address_sent = 'จัดส่งพร้อมบิลอื่น';
                    }

                    ?>
                    <h6>ที่อยู่การจัดส่ง : <span class="label label-{{ $order->css_class }}">{{ $address_sent }}</span>
                    </h6>
                    @if ($address)
                    <p><b>{{ $address['name'] }}</b><br>
                      @if ($address['tel']) Tel: {{ $address['tel'] }} <br>@endif
                      @if ($address['email']) Email: {{ $address['email'] }}<br>@endif
                      @if ($address['house_no']) {{ $address['house_no'] }}, @endif
                      @if ($address['moo'] != '-' and $address['moo'] != '') หมู่.{{ $address['moo'] }}, @endif
                      @if ($address['house_name'] != '-' and $address['house_name'] != '') {{ $address['house_name'] }}, @endif
                      @if ($address['soi'] != '-' and $address['soi'] != '') {{ $address['soi'] }}, @endif
                      @if ($address['road'] != '-' and $address['road'] != '') ถนน.{{ $address['road'] }}, @endif

                      @if ($address['district_name'] != '-' and $address['district_name'] != '')<br>{{ $address['district_name'] }}, @endif
                      @if ($address['amphures_name'] != '-' and $address['amphures_name'] != ''){{ $address['amphures_name'] }}, @endif
                      @if ($address['provinces_name'] != '-' and $address['provinces_name'] != ''){{ $address['provinces_name'] }}, @endif
                      @if ($address['zipcode']) {{ $address['zipcode'] }}@endif
                  </p>
                    @else
                        <p><b> </b>
                    @endif
                         @if ($order->status_payment_sent_other == 1)
                         <?php
                         $sent_to_customer_data = \App\Helpers\Frontend::get_customer_id($order->customers_sent_id_fk);
                         $customer_pay = \App\Helpers\Frontend::get_customer_id($order->customers_id_fk);

                         ?>
                         <hr>
                         <span class="label label-success mb-2">สั่งซื้อให้ลูกทีม</span>
                         <p class="mt-1">
                          <b>ผู้สังซื้อ </b> |  {{ $customer_pay->first_name }} {{ $customer_pay->last_name }} ({{ $customer_pay->user_name }})
                           <br>
                          <b>ผู้รับคะแนน </b> |  {{ $sent_to_customer_data->first_name }} {{ $sent_to_customer_data->last_name }} ({{ $sent_to_customer_data->user_name }})
                         </p>

                     @endif
                </div>
                <div class="col-md-4 col-sm-6">
                    <h6>Order Information :</h6>
                    <table class="table table-responsive invoice-table invoice-order table-borderless">
                        <tbody>
                            <tr>
                                <th>Date :</th>
                                <td>@if ($order->created_at) {{ date('d/m/Y', strtotime($order->created_at)) }} @endif</td>
                            </tr>
                            <tr>
                                <th>Status :</th>
                                <td>
                                    @if ($order->detail)<span
                                            class="label label-{{ $order->css_class }}">{{ $order->detail }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Type :</th>
                                <td>
                                    @if ($order->type) <span>{{ $order->type }}</span> @endif
                                </td>
                            </tr>
                            {{-- <tr>
                       <th>Paid by :</th>
                       <td>
                        @if ($order->pay_type_name) <span>{{ $order->pay_type_name }}</span> @endif
                        </td>
                        </tr> --}}


                        </tbody>
                    </table>
                </div>
                <div class="col-md-4 col-sm-6">
                    <h6 class="m-b-20">เลขใบสั่งซื้อ : @if ($order->detail)<span> {{ $order->code_order }} </span> @endif
                    </h6>

                    <h6 class="m-b-20 ">เลขใบเสร็จ : @if ($order->order_payment_code)<span class="text-success">
                                {{ $order->order_payment_code }} </span> @endif
                    </h6>

                    {{-- <h6 class="m-b-20 ">Paid by : @if ($order->pay_type_name)<span class="text-success">
                                {{ $order->pay_type_name }} </span> @endif
                    </h6> --}}

                    @if($order->aistockist)
                    <?php
                     $aistockis = App\Helpers\Frontend::get_customer_id($order->aistockist);

                    ?>
                     <b class="m-b-20">ซื้อผ่าน Ai-Stockist </b><br> <span> {{$aistockis->first_name}} {{$aistockis->last_name}} ( {{$aistockis->user_name}} ) </span></b>
                   @endif

                    {{-- <table class="table table-responsive invoice-table invoice-order table-borderless">
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

                                    @if ($order->purchase_type_id_fk == 6)<th>Ticket Number</th>@endif
                                    @if ($order->purchase_type_id_fk == 7)@else <th>Quantity</th> @endif
                                    <th>Amount</th>
                                    <th>PV</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order_items as $index => $value)

                                    <tr>
                                        <td>
                                            <h6>{{ $value->product_name }}</h6>

                                            @if ($value->type_product == 'promotion')

                                                @if ($value->promotion_code)
                                                    <span class="label label-warning"><b style="color: #000">
                                                            {{ $value->promotion_code }} </b></span>
                                                @endif
                                                <ul class="mt-2">
                                                    <?php
                                                    $location_id = Auth::guard('c_user')->user()->business_location_id;
                                                    $get_promotion_detail = \App\Helpers\Frontend::get_promotion_detail($value->promotion_id_fk, $location_id);
                                                    ?>
                                                    @foreach ($get_promotion_detail as $promotion_product)
                                                        <li style="font-size: 12px">
                                                            <i class="icofont icofont-double-right text-success"></i>
                                                            {{ $promotion_product->product_name }}
                                                            {{ $promotion_product->product_amt }}
                                                            {{ $promotion_product->unit_name }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                            @if ($value->type_product == 'giveaway')
                                                <ul>
                                                    <?php
                                                    $location_id = Auth::guard('c_user')->user()->business_location_id;
                                                    $get_giveaway = \App\Helpers\Frontend::get_giveaway_detail($value->id, $location_id);

                                                    ?>

                                                    @foreach ($get_giveaway as $giveaway_value)

                                                        @if ($giveaway_value->type_product == 'giveaway_product')

                                                            <li style="font-size: 12px">
                                                                <?php $sum_giveaway = $giveaway_value->product_amt * $value->amt; ?>
                                                                <i class="icofont icofont-double-right text-success"></i>
                                                                {{ $giveaway_value->product_name }}
                                                                {{ $giveaway_value->product_amt }}
                                                                {{ $giveaway_value->product_unit_name }}
                                                                x [{{ $value->amt }}] <b> => {{ $sum_giveaway }}
                                                                    {{ $giveaway_value->product_unit_name }}</b>
                                                            </li>


                                                        @else

                                                            <li style="font-size: 12px">
                                                                <?php $gv_giveaway = number_format($giveaway_value->gv_free * $value->amt); ?>
                                                                <i
                                                                    class="icofont icofont-double-right text-success"></i>GiftVoucher
                                                                {{ $giveaway_value->gv_free }} x [{{ $value->amt }}]
                                                                <b> => {{ $gv_giveaway }} GV </b>
                                                            </li>
                                                        @endif

                                                    @endforeach

                                                </ul>
                                            @endif
                                        </td>

                                        @if ($order->purchase_type_id_fk == 6)
                                            <td><b class="text-primary">{{ $value->ticket_number }}</b></td>
                                        @endif
                                        @if ($order->purchase_type_id_fk == 7)
                                        @else
                                            <td>{{ $value->amt }}</td>
                                        @endif
                                        <td>{{ number_format($value->selling_price, 2) }}</td>
                                        <td class="text-success"><b>{{ $value->pv }}</b></td>
                                        @if ($order->purchase_type_id_fk == 7)
                                            <td>{{ number_format($value->selling_price, 2) }}</td>
                                        @else
                                            <td>{{ number_format($value->amt * $value->selling_price, 2) }}</td>
                                        @endif

                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">

                  @if ($order->distribution_channel_id_fk != 3 and ($order->pay_type_id_fk == 3 || $order->pay_type_id_fk == 6 ||
                   $order->pay_type_id_fk == 9 || $order->pay_type_id_fk == 11 || $order->pay_type_id_fk == 14 ) )
                  <div class="card widget-statstic-card borderless-card">
                   <div class="card-block">
                     <div class="row">
                         <div class="col-md-12">
                             <h5 id="aicash_user" style="color: #000">รายละเอียดการใช้ Ai-Cash</h5>
                         </div>
                     </div>
                         <hr class="m-b-5 m-t-5">
                     <div class="row">
                         <div class="col-md-4">
                             <h6 class="m-b-0">ผู้อนุมัติ </h6>
                         </div>
                         <div class="col-md-8 text-right">

                           <p class="m-b-0 text-right" id="text_pv" style="color: #000">{{$customer_confirm->first_name  }} {{$customer_confirm->last_name  }}({{$customer_confirm->business_name  }}) </p>
                         </div>

                         <div class="col-md-4">
                             <h6 class="m-b-0" style="color: #000">รหัส</h6>
                         </div>
                         <div class="col-md-8">

                             <p class="m-b-0 text-right" id="text_pv" style="color: #000">{{$customer_confirm->user_name  }} </p>
                      </div>
                     </div>

                     <div id="text_confirm_aicash">
                         <hr class="m-b-5 m-t-5">
                         <div class="row">
                             <div class="col-md-4">
                                 <h6 class="m-b-0" style="color: #000">สั่งซื้อสินค้า</h6>
                             </div>
                             <div class="col-md-8">
                               {{-- $customer_confirm =  DB::table('customers')
                               ->select('first_name','last_name','user_name','business_name') --}}
                                 <p class="m-b-0 text-right" id="text_pv" style="color: #000">{{$customer_use->first_name  }} {{$customer_use->last_name  }}({{$customer_use->business_name  }}) </p>
                             </div>

                             <div class="col-md-4">
                                 <h6 class="m-b-0" style="color: #000">รหัส</h6>
                             </div>
                             <div class="col-md-8">

                                 <p class="m-b-0 text-right" id="text_pv" style="color: #000">{{$customer_use->user_name  }} </p>
                             </div>
                         </div>
                         <hr class="m-b-5 m-t-5">
                         <div class="row">
                             <div class="col-md-6">
                                 <h6 class="m-b-0" style="color: #000">ยอดขอชำระด้วย AiCash</h6>
                             </div>
                             <div class="col-md-6" id="">
                                 <u>
                                     <h5 class="m-b-0 text-right" id="text_pv" style="color: #000">{{number_format($order->aicash_price)}}</h5>
                                 </u>
                             </div>
                         </div>
                     </div>
                    </div>


                  </div>

                  @endif



                </div>
                <div class="col-sm-6">
                    <table class="table table-responsive invoice-table invoice-total">
                        <tbody>
                            <tr>
                                <th>มูลค่าสินค้า : </th>

                                <?php
                                $price_vat_sum = $order->sum_price - $order->tax;
                                ?>
                                <td> {{ number_format($price_vat_sum, 2) }}</td>
                            </tr>
                            <tr>
                                <th>VAT({{ $order->vat }}%) : </th>
                                <td> {{ number_format($order->tax, 2) }}</td>
                            </tr>
                            <tr>
                              <th>รวมค่าสินค้า : </th>
                              <td> {{ number_format($order->sum_price, 2) }}</td>
                            </tr>

                          @if($order->fee_amt)
                            <tr>
                              <th>ค่าบริการบัตรเครดิต : </th>
                              <td> {{ number_format($order->fee_amt, 2) }}</td>
                           </tr>
                          @endif

                            @if ($order->purchase_type_id_fk != 6 and $order->purchase_type_id_fk != 7)

                                <tr>
                                    <th>
                                        @if ($order->shipping_cost_detail) <label
                                                class="label label-inverse-warning">
                                                <font id="shipping_detail" style="color: #000">
                                                    {{ $order->shipping_cost_detail }}
                                                </font>
                                            </label>@endif ค่าจัดส่ง :
                                    </th>
                                    <td> {{ number_format($order->shipping_price, 2) }} </td>
                                </tr>


                            @endif


                            @if ($order->purchase_type_id_fk == 5)

                                <tr>
                                    <td><strong>ยอดรวม : </strong></td>
                                    <td align="right"><strong>
                                            {{ number_format($order->total_price, 2) }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-primary">Ai Voucher : </strong></td>
                                    <td align="right"><strong class="text-primary">
                                            {{ number_format($order->gift_voucher_price, 2) }}</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>ยอดที่ต้องชำระเพิ่ม : </strong></td>
                                    <?php $price_remove_gv = $order->total_price - $order->gift_voucher_price; ?>
                                    <td align="right"><strong> {{ number_format($price_remove_gv, 2) }}</strong>

                                    </td>
                                </tr>

                            @elseif($order->purchase_type_id_fk == 6 || $order->purchase_type_id_fk == 7)
                                <tr>
                                    <td><strong>ยอดชำระ</strong></td>
                                    <td align="right"><strong> <u>{{ number_format($order->sum_price, 2) }}</u></strong>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td><strong>ยอดชำระ</strong></td>
                                    <td align="right"><strong>
                                            <u>{{ number_format($order->total_price, 2) }}</u></strong>
                                    </td>
                                </tr>
                            @endif

                            <tr>
                              <th>ได้รับ Aistockis : </th>
                              <td class="text-success"><b> {{ number_format($order->pv_total) }} PV </b></td>
                          </tr>

                          <tr>
                            <th>ได้รับ Drop Ship bonus : </th>
                            <td class="text-success"><b> {{ number_format($order->drop_ship_bonus) }} </b></td>
                        </tr>

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
