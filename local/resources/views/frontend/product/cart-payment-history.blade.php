 @extends('frontend.layouts.customer.customer_app')
 @section('css')
 
 @endsection
 @section('conten')
 <!-- Invoice card start -->
 <div class="card">

    <div class="card-block">
        <div class="row invoive-info">
            <div class="col-md-4 col-xs-12 invoice-client-info">
                <h6>Information :</h6>

                @if($order->delivery_location_status == 'sent_office')
                <h6 class="m-0">{{ $order->office_name }}</h6> 
                <p class="m-0 m-t-10">@if($order->tel) Tel. {{ $order->office_tel }} @endif</p>
                <p class="m-0">@if($order->office_email) Email. {{ $order->office_email }} @endif</p>
                <p class="m-0">@if($order->office_house_no) {{ $order->office_house_no }} @endif {{-- @if($order->office_moo) M.{{ $order->office_moo }} @endif  --}} @if($order->office_soi) , {{ $order->office_soi }} @endif</p>
                <p class="m-0"> @if($order->office_district), {{ $order->office_district }} @endif @if($order->office_district_sub) , {{ $order->office_district_sub }} @endif</p>
                <p class="m-0">@if($order->office_road) Road {{ $order->office_road }} @endif @if($order->office_province), {{ $order->office_province }} @endif @if($order->office_zipcode) , {{ $order->office_zipcode }} @endif</p>

                @else
                <h6 class="m-0">{{ $order->name }}</h6> 
                <p class="m-0 m-t-10">@if($order->tel) Tel. {{ $order->tel }} @endif</p>
                <p class="m-0">@if($order->email) Email. {{ $order->email }} @endif</p>
                <p class="m-0">@if($order->house_no) {{ $order->house_no }} @endif @if($order->moo) M.{{ $order->moo }} @endif @if($order->house_name) , {{ $order->house_name }} @endif</p>
                <p class="m-0">@if($order->soi) , {{ $order->soi }} @endif @if($order->district), {{ $order->district }} @endif @if($order->district_sub) , {{ $order->district_sub }} @endif</p>
                <p class="m-0">@if($order->road) ,Road. {{ $order->road }} @endif @if($order->province), {{ $order->province }} @endif @if($order->zipcode) , {{ $order->zipcode }} @endif</p>

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
                                @if($order->detail)<span class="label label-{{ $order->css_class }}">{{ $order->detail }}</span>@endif
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
        <h6 class="m-b-20">เลขใบสั่งซื้อ : @if($order->detail)<span> {{ $order->code_order }} </span> @endif</h6>

        <h6 class="m-b-20 ">เลขใบเสร็จ : @if($order->order_payment_code)<span class="text-success"> {{ $order->order_payment_code }} </span> @endif</h6>

        <h6 class="m-b-20 ">Paid by : @if($order->pay_type_name)<span class="text-success"> {{ $order->pay_type_name }} </span> @endif</h6>

          {{--   <table class="table table-responsive invoice-table invoice-order table-borderless">
                <tbody>
                    <tr>
                        <th>มูลค่าสินค้า : </th>
                        <td> {{ number_format($order->price,2) }}</td>
                    </tr>
                    <tr>
                        <th>VAT({{ $order->vat }}%) : </th>
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
                            @if($order->orders_type_id_fk == 6)<th>Ticket Number</th>@endif
                            @if($order->orders_type_id_fk == 7)@else <th>Quantity</th> @endif
                            <th>Amount</th>
                            <th>PV</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order_items as $value)
                         
                        <tr>
                            <td>
                                <h6>{{ $value->product_name }}</h6>

                                @if($value->type_product == 'promotion')

                                <ul>
                                   <?php 
                                   $location_id = Auth::guard('c_user')->user()->business_location_id;
                                   $get_promotion_detail = \App\Helpers\Frontend::get_promotion_detail($value->promotion_id_fk,$location_id);
                                    ?>
                                    @foreach($get_promotion_detail as $promotion_product)
                                    <li style="font-size: 12px">
                                        <i class="icofont icofont-double-right text-success"></i> {{ $promotion_product->product_name }} {{ $promotion_product->product_amt }} {{ $promotion_product->unit_name }} 
                                    </li>
                                    @endforeach
                                </ul>

                                @endif
                            </td>
                            @if($order->orders_type_id_fk == 6)
                            <td ><b class="text-primary">{{ $value->ticket_number }}</b></td>
                            @endif
                            @if($order->orders_type_id_fk == 7)
                            @else
                            <td>{{ $value->amt }}</td>
                            @endif
                            <td>{{ $value->selling_price }}</td>
                            <td class="text-success"><b>{{ $value->pv }}</b></td>
                            @if($order->orders_type_id_fk == 7)
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

                    $price_vat =  $order->sum_price * ($order->vat/100); 
                    $price_vat_sum  = $order->sum_price - $price_vat;
                    ?>
                    <td> {{ number_format($price_vat_sum,2) }}</td>
                </tr>
                <tr>
                    <th>VAT({{ $order->vat }}%) : </th>
                    <td> {{ number_format($price_vat,2) }}</td> 
                </tr>
                <tr>
                    <th>รวม : </th>
                    <td> {{ number_format($order->sum_price,2) }}</td>
                </tr>
                @if($order->orders_type_id_fk != 6 and $order->orders_type_id_fk != 7)

                <tr>
                    <th>ค่าจัดส่ง : </th>
                    <td> {{ number_format($order->shipping_price,2) }} </td>
                </tr>
                @endif
                <tr>
                    <th>คะแนนที่ได้รับ : </th>
                    <td class="text-success"><b> {{ $order->pv_total }} PV </b></td>
                </tr>

                
                @if($order->orders_type_id_fk == 5)
                
                <tr>
                    <td><strong>ยอดรวม : </strong></td>
                    <td align="right"><strong> {{ number_format($order->sum_price + $order->shipping_price,2) }}</strong>
                    </td>
                </tr>
                <tr>
                    <td><strong class="text-primary">Gift Voucher : </strong></td>
                    <td align="right"><strong class="text-primary"> {{  number_format($order->gift_voucher,2) }}</strong>
                    </td>
                </tr>

                <tr>
                    <td><strong>ยอดที่ต้องชำระเพิ่ม : </strong></td>
                    <td align="right"><strong> {{  number_format($order->price_remove_gv,2) }}</strong>

                    </td>
                </tr>

                @elseif($order->orders_type_id_fk == 6 || $order->orders_type_id_fk == 7)
                <tr>
                    <td><strong>ยอดชำระ</strong></td>
                    <td align="right"><strong > {{ number_format($order->sum_price,2) }}</strong>
                    </td>
                </tr>
                @else
                <tr>
                    <td><strong>ยอดชำระ</strong></td>
                    <td align="right"><strong > {{ number_format($order->sum_price + $order->shipping_price,2) }}</strong>
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

