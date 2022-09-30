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


    @charset "utf-8";


    .NameAndAddress {
        width: 100%;
        /*color: #898989;*/
        /*margin-bottom: 20px;*/
    }

    .NameAndAddress b {
        /*color: #222222;*/
        margin-right: 5px;
        border-radius: 15px;
    }

    .NameAndAddress table {
        width: 100%;
        /*border: 1px solid #ccc;*/

    }

    .NameAndAddress table thead {
        background: #ebebeb;
        color: #222222;
        border-bottom: 1px solid #ccc;
    }

    .NameAndAddress table thead tr th {
        text-align: center;
        font-size: 14px;
        font-weight: bold;
        padding: 5px 0;
    }

    .NameAndAddress table tbody tr td {
        /*padding: 10px;*/
    }

    .NameAndAddress table tbody tr td b {
        width: 130px;
        display: inline-block;
    }

    /*เพิ่มเติมvvvv*/
    .NameAndAddress table tbody tr td {
        padding-left: 10px;
        padding-top: 5px;
    }

    .NameAndAddress table tbody tr td b {
        width: 130px;
        display: inline-block;
    }


    * {
        box-sizing: border-box;
    }

    /* Create two equal columns that floats next to each other */
    .column {
        float: left;
        color: black;
        padding: 0px;
        height: 100px;
        /* Should be removed. Only for demonstration */
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }


    .topics tr {
        line-height: 10px;
    }


    tr.border_bottom td {
        border-bottom: 1px solid #ccc;
        ;
    }


    /* Create four equal columns that floats next to each other */
    .column-1 {
        float: left;
        width: 36%;
        padding: 10px;
        height: 50px;
        font-size: 12px !important;
    }

    .column-2 {
        float: left;
        width: 50%;
        font-size: 12px !important;
        color: grey;
    }

    .column-2-1 {
        float: left;
        width: 45%;
        padding: 10px;
        font-size: 12px !important;
    }

    .column-2-2 {
        float: right;
        width: 35%;
        padding: 10px;
        font-size: 14px !important;
    }

    .column-4 {
        float: left;
        width: 17%;
        padding: 10px;
        height: 50px;
        text-align: center;
    }

    /* Clear floats after the columns */
    .row-4:after {
        content: "";
        display: table;
        clear: both;
    }



    .page-break {
        page-break-after: always;
    }



    .divTable {
        display: table;
        width: 100%;

    }

    .divTableRow {
        display: table-row;
    }

    .divTableHeading {
        background-color: #EEE;
        display: table-header-group;
    }

    .divTableCell,
    .divTableHead {
        border: 1px solid white;
        display: table-cell;
        padding: 3px 6px;
        word-break: break-all;
    }

    .divTableHeading {
        background-color: #EEE;
        display: table-header-group;
        font-weight: bold;
    }

    .divTableFoot {
        background-color: #EEE;
        display: table-footer-group;
        font-weight: bold;
    }

    .divTableBody {
        display: table-row-group;
    }

    .divTH {
        text-align: right;
    }
</style>



<div class="NameAndAddress" style="">
    <table style="border-collapse: collapse;">
        <tr>
            <th style="text-align: left;">
                <img src="<?= public_path('images/logo2.png') ?>">
            </th>
            <th style="text-align: right;">
                2102/1 อาคารไอยเรศวร ซ.ลาดพร้าว 84 ถ.ลาดพร้าว <br>
                แขวงวังทองหลาง เขตวังทองหลาง กรุงเทพ 10310 ประเทศไทย <br>
                TEL : +66 (0) 2026 3555
                FAX : +66 (0) 2514 3944
                E-MAIL : info@aiyara.co.th
            </th>
        </tr>

    </table>
</div>

<div class="NameAndAddress" style="">
    <table style="border-collapse: collapse;">
        <tr>
            <th style="text-align: left; width:40%;">
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.......................................................................
            </th>
            <th style="text-align: left;font-size: 30px;">
                รายละเอียดจัดส่งสินค้า
                {{-- <center> รายละเอียดจัดส่งสินค้า </center> --}}
            </th>
        </tr>

    </table>
</div>

<?php
$packing = DB::select(' SELECT * FROM db_pick_pack_packing WHERE delivery_id_fk=' . $data[0] . ' and packing_code_id_fk=' . $data[1] . '  GROUP BY packing_code ');

$recipient_name = '';

// foreach ($p1 as $key => $value) {

$delivery = DB::select(
    " SELECT
              db_delivery.set_addr_send_this,
              db_delivery.recipient_name,
              db_delivery.addr_send,
              db_delivery.postcode,
              db_delivery.mobile,
              db_delivery.tel_home,
              db_delivery.status_pack,
              db_delivery.receipt,
              db_delivery.id as delivery_id_fk,
              db_delivery.orders_id_fk
              FROM
              db_delivery
              WHERE
              db_delivery.id = " .
        @$data[0] .
        ' AND set_addr_send_this=1 ',
);

$delivery0 = DB::select(
    " SELECT
              db_delivery.set_addr_send_this,
              db_delivery.recipient_name,
              db_delivery.addr_send,
              db_delivery.postcode,
              db_delivery.mobile,
              db_delivery.tel_home,
              db_delivery.status_pack,
              db_delivery.receipt,
              db_delivery.id as delivery_id_fk,
              db_delivery.id,
              db_delivery.orders_id_fk
              FROM
              db_delivery
              WHERE
              db_delivery.id = " .
        @$data[0] .
        ' AND set_addr_send_this=0 ',
);

if (!$delivery) {
    if ($delivery0) {
        // วุฒิเพิ่มมาสำหรับพวกบิลส่งกับบิลอื่น
        $order_this = DB::table('db_orders')
            ->where('id', $delivery0[0]->orders_id_fk)
            ->first();
        if ($order_this) {
            $order_send = DB::table('db_orders')
                ->where('code_order', 'like', '%' . $order_this->bill_transfer_other . '%')
                ->first();
            if ($order_send) {
                $delivery2 = DB::table('db_delivery')
                    ->where('orders_id_fk', $order_send->id)
                    ->first();
                if ($delivery2) {
                    DB::table('db_delivery')
                        ->where('id', $delivery0[0]->id)
                        ->update([
                            'recipient_name' => $delivery2->recipient_name,
                            'addr_send' => $delivery2->addr_send,
                            'postcode' => $delivery2->postcode,
                            'mobile' => $delivery2->mobile,
                            'tel_home' => $delivery2->tel_home,
                        ]);

                    $delivery = DB::select(
                        " SELECT
              db_delivery.set_addr_send_this,
              db_delivery.recipient_name,
              db_delivery.addr_send,
              db_delivery.postcode,
              db_delivery.mobile,
              db_delivery.tel_home,
              db_delivery.status_pack,
              db_delivery.receipt,
              db_delivery.id as delivery_id_fk,
              db_delivery.orders_id_fk
              FROM
              db_delivery
              WHERE
              db_delivery.id = " . $delivery0[0]->id,
                    );
                }
            }
        }
    }
}

$recipient_name = @$delivery[0]->recipient_name ? @$delivery[0]->recipient_name : '';
$addr_send = @$delivery[0]->addr_send . ' ' . @$delivery[0]->postcode;
$tel = @$delivery[0]->mobile . ' ' . @$delivery[0]->tel_home;
$receipt = '';

if (@$delivery[0]->status_pack == 1) {
    $d1 = DB::select(' SELECT * from db_delivery WHERE id=' . $delivery[0]->delivery_id_fk . '');
    $d2 = DB::select(' SELECT * from db_delivery WHERE packing_code=' . $d1[0]->packing_code . '');
    $arr1 = [];
    foreach ($d2 as $key => $v) {
        array_push($arr1, $v->receipt);
    }
    $receipt = implode(',', $arr1);
} else {
    $receipt = @$delivery[0]->receipt;
}

// dd($receipt);

?>

<div class="NameAndAddress">

    <div style="border-radius: 5px; border: 1px solid grey;padding:-1px;">
        <table style="border-collapse: collapse;vertical-align: top;">

            <tr>
                <td style="width:30%;vertical-align: top;font-weight: bold">
                    รหัสอ้างอิง หรือ ลำดับ : {{ @$packing[0]->packing_code }}
                </td>
                <td style="width:30%;vertical-align: top;font-weight: bold;">
                    วันที่ : {{ @$packing[0]->created_at }}
                </td>
            </tr>

            <tr>
                <td style="width:30%;vertical-align: top;font-weight: bold">
                    ชื่อผู้รับสินค้า : {{ @$recipient_name }}
                </td>
                <td style="width:30%;vertical-align: top;font-weight: bold;">
                    เบอร์โทรศัพท์ : {{ $tel }}
                </td>
            </tr>

            <tr>
                <td colspan="2" style="width:30%;vertical-align: top;font-weight: bold">
                    ที่อยู่ : {{ @$addr_send }}
                </td>

            </tr>

            <tr>
                <td colspan="2" style="width:30%;vertical-align: top;font-weight: bold">
                    {{-- เลขที่ใบเสร็จ : {{ @$receipt }} --}}
                    <?php
                    $r_num = explode(',',@$receipt);
                    $text_receipt = "";
                    $r_i = 0;
                    foreach($r_num as $index_r => $r){
                     $r_i ++;
                     if($r_i==7){
                       $text_receipt.='<br>';
                       if(count($r_num)==$index_r+1){
                         $text_receipt.=$r;
                       }else{
                         $text_receipt.=$r.',';
                       }
                       $r_i = 0;
                     }else{
                       if(count($r_num)==$index_r+1){
                         $text_receipt.=$r;
                       }else{
                         $text_receipt.=$r.',';
                       }
                     }
                    }
                 ?>
                 {{-- เลขที่ใบเสร็จ : {{ @$receipt }} --}}
                 เลขที่ใบเสร็จ : {!! @$text_receipt !!}
                </td>

            </tr>

            <tr>
                <td colspan="2" style="width:30%;vertical-align: top;font-weight: bold">
                    [ รายการสินค้า ]
                </td>
            </tr>

            <?php

            // // วุฒิเพิ่มมา เอาไว้ตรวจว่าสินค้าไหนจ่ายแล้วของบิลไหน
            // $db_pay_requisition_002_datas = DB::table('db_pay_requisition_002')
            //               ->where('pick_pack_requisition_code_id_fk',@$data[1])->get();
            //               foreach($db_pay_requisition_002_datas as $data_002){
            //                             $product_amt_get = $data_002->amt_get;
            //                             $db_pick_pack_packing_code_data = DB::table('db_pick_pack_packing_code')->select('id','orders_id_fk')->where('id',@$data[1])->first();
            //                             $arr_order = explode(',',$db_pick_pack_packing_code_data->orders_id_fk);
            //                             $promotions_products_arr = DB::table('promotions_products')->select('promotion_id_fk')
            //                             ->where('product_id_fk',$data_002->product_id_fk)->pluck('promotion_id_fk')->toArray();
            //                             $db_order_products_list_data1 = DB::table('db_order_products_list')
            //                             ->select('frontstore_id_fk')
            //                             ->whereIn('frontstore_id_fk',$arr_order)
            //                             ->where('type_product','product')
            //                             ->where('product_id_fk',$data_002->product_id_fk);
            //                             $db_order_products_list_data2 = DB::table('db_order_products_list')
            //                             ->select('frontstore_id_fk')
            //                             ->whereIn('frontstore_id_fk',$arr_order)
            //                             ->where('type_product','promotion')
            //                             ->whereIn('promotion_id_fk',$promotions_products_arr)
            //                             ->union($db_order_products_list_data1)
            //                             ->get();
            //                             foreach($db_order_products_list_data2 as $data2){
            //                                   $order_product1 = DB::table('db_order_products_list')
            //                                   ->where('frontstore_id_fk',$data2->frontstore_id_fk)
            //                                   ->where('product_id_fk',$data_002->product_id_fk)
            //                                   ->where('type_product','product')
            //                                   ->get();
            //                                   $order_product2 = DB::table('db_order_products_list')
            //                                   ->where('frontstore_id_fk',$data2->frontstore_id_fk)
            //                                   ->whereIn('promotion_id_fk',$promotions_products_arr)
            //                                   ->where('type_product','promotion')
            //                                   ->get();
            //                                   $sum_want_amt = 0;
            //                                   foreach($order_product1 as $order_product1_data){
            //                                     $sum_want_amt+=$order_product1_data->amt;
            //                                   }
            //                                   foreach($order_product2 as $order_product2_data){
            //                                     $pro = DB::table('promotions_products')->where('promotion_id_fk',$order_product2_data->promotion_id_fk)->first();
            //                                     $sum_want_amt+=($order_product2_data->amt*$pro->product_amt);
            //                                   }

            //                                    $amt_get_data = 0;
            //                                    $amt_remain_data = 0;
            //                                   // จำนวนที่ต้องการหักจากที่ได้รับ
            //                                   if($sum_want_amt <= $product_amt_get){
            //                                     $product_amt_get = $product_amt_get - $sum_want_amt;
            //                                     $amt_get_data = $sum_want_amt;
            //                                     $amt_remain_data = 0;
            //                                   }else{
            //                                      $amt_get_data = $product_amt_get;
            //                                      $amt_remain_data = $sum_want_amt - $product_amt_get;
            //                                      $product_amt_get = 0;
            //                                   }

            //                                   //  if($data2->frontstore_id_fk==5){

            //                                   //   dd($sum_want_amt);

            //                                       DB::table('db_pay_requisition_002_item')->insert([
            //                                         'requisition_002_id' => $data_002->id,
            //                                         'order_id' => $data2->frontstore_id_fk,
            //                                         'product_id_fk' => $data_002->product_id_fk,
            //                                         'amt_need' => $sum_want_amt,
            //                                         'amt_get' => $amt_get_data,
            //                                         'amt_remain' => $amt_remain_data,
            //                                         'created_at' => date('Y:m:d H:i:s'),
            //                                         'updated_at' => date('Y:m:d H:i:s'),
            //                                         'delete_status' => 1,
            //                                       ]);

            //                                   //  }

            //                             }
            //               }

            // วุฒิเพิ่มมา
            $sTable = DB::select(
                "

                                      SELECT
                                      db_pick_pack_packing.id,
                                      db_pick_pack_packing.p_size,
                                      db_pick_pack_packing.p_weight,
                                      db_pick_pack_packing.p_amt_box,
                                      db_pick_pack_packing.packing_code_id_fk as packing_code_id_fk,
                                      db_pick_pack_packing.packing_code as packing_code,
                                      db_delivery.id as db_delivery_id,
                                      db_delivery.packing_code as db_delivery_packing_code
                                      FROM `db_pick_pack_packing`
                                      LEFT JOIN db_delivery on db_delivery.id=db_pick_pack_packing.delivery_id_fk
                                      WHERE
                                      db_pick_pack_packing.packing_code_id_fk =" .
                    $data[1] .
                    "
                                      AND db_pick_pack_packing.delivery_id_fk = " .
                    $data[0] .
                    "
                                      ORDER BY db_pick_pack_packing.id
                                      ",
            );

            foreach ($sTable as $key => $row) {
                $pn = '<tr>
                                            <td style="width:70%; "><b>รหัส : ชื่อสินค้า</b></td>
                                            <td style="width:15%; text-align:left;"><b>จำนวน</b></td>
                                            <td style="width:15%; text-align:left;"><b>หน่วย</b></td>
                                            </tr>';

                //   $pn = '<div class="divTable"><div class="divTableBody">';
                //   $pn .= '<div class="divTableRow">
                // <div class="divTableCell" style="width:200px;font-weight:bold;">รหัส : ชื่อสินค้า</div>
                // <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
                // <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
                // </div>
                // ';

                $sum_amt = 0;
                $r_ch_t = '';
                $fid_arr = explode(',', $receipt);
                $order_arr = DB::table('db_orders')
                    ->select('id')
                    ->whereIn('code_order', $fid_arr)
                    ->pluck('id')
                    ->toArray();
                $Products = DB::table('db_pay_requisition_002')
                    ->select('db_pay_requisition_002.product_name', 'db_pay_requisition_002.product_unit', 'db_pay_requisition_002_item.*')
                    ->join('db_pay_requisition_002_item', 'db_pay_requisition_002_item.requisition_002_id', 'db_pay_requisition_002.id')
                    ->where('db_pay_requisition_002.pick_pack_requisition_code_id_fk', $data[1])
                    ->whereIn('db_pay_requisition_002_item.order_id', $order_arr)
                    ->groupBy('product_id_fk')
                    ->get();

                if (@$Products) {
                    foreach ($Products as $key => $value) {
                        if (!empty($value->product_id_fk)) {
                            // หา max time_pay ก่อน
                            $r_ch01 = DB::select('SELECT time_pay FROM `db_pay_requisition_002_pay_history` where product_id_fk in(' . $value->product_id_fk . ') AND  pick_pack_packing_code_id_fk=' . $row->packing_code_id_fk . ' order by time_pay desc limit 1  ');
                            // Check ว่ามี status=2 ? (ค้างจ่าย)
                            $r_ch02 = DB::select('SELECT * FROM `db_pay_requisition_002_pay_history` where product_id_fk in(' . $value->product_id_fk . ') AND  pick_pack_packing_code_id_fk=' . $row->packing_code_id_fk . ' and time_pay=' . $r_ch01[0]->time_pay . ' and status=2 ');
                            //  if(count($r_ch02)>0){
                            //     $r_ch_t = '(รายการนี้ค้างจ่ายในรอบนี้ สินค้าในคลังมีไม่เพียงพอ)';
                            //  }else{
                            //    $r_ch_t = '';
                            //  }

                            //  วุฒิเพิ่มมา
                            $db_pay_requisition_002 = DB::table('db_pay_requisition_002')
                                ->where('product_id_fk', $value->product_id_fk)
                                ->where('pick_pack_requisition_code_id_fk', $row->packing_code_id_fk)
                                ->orderBy('time_pay', 'desc')
                                ->first();

                            // $db_pay_requisition_002_item = DB::table('db_pay_requisition_002_item')
                            //     ->where('product_id_fk', $value->product_id_fk)
                            //     ->where('order_id', $value->order_id)
                            //     ->where('requisition_002_id', @$db_pay_requisition_002->id)
                            //     ->first();

                            // if ($db_pay_requisition_002_item->amt_remain > 0) {
                            //     $r_ch_t = '&nbsp;<span style="font:15px;color:red;">(รายการนี้ค้างจ่าย จำนวน ' . $db_pay_requisition_002_item->amt_remain . ' )</span>';
                            // } else {
                            //     $r_ch_t = '';
                            // }

                            $db_pay_requisition_002_item = DB::table('db_pay_requisition_002_item')
                                ->select(DB::raw('SUM(amt_get) AS amt_get'), 'amt_need', DB::raw('SUM(amt_remain) AS amt_remain'))
                                ->where('product_id_fk', $value->product_id_fk)
                                // ->where('order_id',$value->order_id)
                                ->where('order_id',  $value->order_id)
                                // ->where('requisition_002_id',@$db_pay_requisition_002->id)
                                ->where('pick_pack_requisition_code_id_fk', $row->packing_code_id_fk)
                                ->groupBy('product_id_fk')
                                ->first();

                            if ($db_pay_requisition_002_item->amt_need - $db_pay_requisition_002_item->amt_get > 0) {
                                $r_ch_t = '&nbsp;<span style="font:15px;color:red;">(รายการนี้ค้างจ่าย จำนวน ' . ($db_pay_requisition_002_item->amt_need - $db_pay_requisition_002_item->amt_get) . ' )</span>';
                            } else {
                                $r_ch_t = '';
                            }

                            $amt_get = DB::table('db_pay_requisition_002')
                                ->join('db_pay_requisition_002_item', 'db_pay_requisition_002_item.requisition_002_id', 'db_pay_requisition_002.id')
                                ->where('db_pay_requisition_002.pick_pack_requisition_code_id_fk', $data[1])
                                ->whereIn('db_pay_requisition_002_item.order_id', $order_arr)
                                ->where('db_pay_requisition_002_item.product_id_fk', $value->product_id_fk)
                                ->sum('db_pay_requisition_002_item.amt_get');

                            $sum_amt += $amt_get;
                            // $pn .=
                            //     '<div class="divTableRow">
                            //                                                                                                                                                                                                                                                                                                                                                                                                                                     <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                            //                                                                                                                                                                                                                                                                                                                                                                                                                                     ' .
                            //     @$value->product_name .
                            //     '</b><br>
                            //                                                                                                                                                                                                                                                                                                                                                                                                                                     <font color=red>' .
                            //     $r_ch_t .
                            //     '</font>
                            //                                                                                                                                                                                                                                                                                                                                                                                                                                     </div>
                            //                                                                                                                                                                                                                                                                                                                                                                                                                                     <div class="divTableCell" style="text-align:center;">' .
                            //     @$amt_get .
                            //     '</div>
                            //                                                                                                                                                                                                                                                                                                                                                                                                                                     <div class="divTableCell" style="text-align:center;">' .
                            //     @$value->product_unit .
                            //     '</div>
                            //                                                                                                                                                                                                                                                                                                                                                                                                                                     ';
                            // $pn .= '</div>';

                            $pn .=
                                "
                                                        <tr>
                                                          <td>" .
                                @$value->product_name .
                                ' <br> ' .
                                $r_ch_t .
                                "</td>
                                                          <td style='text-align:left;'>" .
                                @$amt_get .
                                "</td>
                                                          <td style='text-align:left;'>" .
                                @$value->product_unit .
                                "</td>
                                                          </tr>
                                                      ";
                        }
                    }

                    // $pn .=
                    //     '<div class="divTableRow">
                    //                                                                                                                                                                                                                                                                                                                                                                                                                                           <div class="divTableCell" style="text-align:right;font-weight:bold;"> รวม </div>
                    //                                                                                                                                                                                                                                                                                                                                                                                                                                           <div class="divTableCell" style="text-align:center;font-weight:bold;">' .
                    //     @$sum_amt .
                    //     '</div>
                    //                                                                                                                                                                                                                                                                                                                                                                                                                                           <div class="divTableCell" style="text-align:center;"> </div>
                    //                                                                                                                                                                                                                                                                                                                                                                                                                                           </div>
                    //                                                                                                                                                                                                                                                                                                                                                                                                                                           ';

                    // $pn .= '</div>';

                    $pn .=
                        "  <tr>
                                                          <td style='text-align:right;'><b>รวม</b></td>
                                                          <td style='text-align:left;'>" .
                        @$sum_amt .
                        "</td>
                                                          <td style='text-align:left;'></td>
                                                          </tr>";

                    echo $pn;
                    // }
                }
            }

            ?>
            <br>

        </table>
    </div>
    <br> <br>
    <table style="border-collapse: collapse;">
      <tr>
        <th colspan="2" style="text-align: left;font-size: 18px;"><b><u>ส่วนงานคลัง</u></b></th>
      </tr>
        <tr>
            <th style="text-align: left;font-size: 18px;">
              <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" style="transform : scale(1.3);">
                &nbsp;ตรวจสอบเอกสารโดย ............................
            </th>
            <th style="text-align: left;font-size: 18px;">
                ถุงใหญ่(ใบ) ...........................
                ถุงเล็ก(ใบ) ................................
            </th>
        </tr>

        <tr>
          <th style="text-align: left;font-size: 18px;">
            <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" style="transform : scale(1.3);">
              &nbsp;บันทึกจ่ายระบบโดย ..............................
          </th>
          <th style="text-align: left;font-size: 18px;">
              ขนาดลัง ...........................
              จำนวน/ลัง .....................................
          </th>
      </tr>

      <tr>
        <th style="text-align: left;font-size: 18px;">
          <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" style="transform : scale(1.3);">
            &nbsp;รายการสินค้าถูกต้อง
        </th>
        <th style="text-align: left;font-size: 18px;"> น้ำหนัก(Kg.) ...................... ผู้ดำเนินการ .................................. </th>
    </tr>

    <tr>
      <th style="text-align: left;font-size: 18px;">
        <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" style="transform : scale(1.3);">
          &nbsp;จำนวนสินค้าถูกต้อง
      </th>
      <th style="text-align: left;font-size: 18px;">  </th>
  </tr>

  <tr>
    <th style="text-align: left;font-size: 18px;">
      <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" style="transform : scale(1.3);">
        &nbsp;สภาพสินค้าไม่มีรอยบุบหรือฉีกขาด
    </th>
    <th style="text-align: left;font-size: 18px;"> </th>
</tr>

<tr>
  <th style="text-align: left;font-size: 18px;">
    <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" style="transform : scale(1.3);">
      &nbsp;ชั่งน้ำหนักสินค้าเรียบร้อย
  </th>
  <th style="text-align: left;font-size: 18px;"> </th>
</tr>
<tr>
  <th style="text-align: left;font-size: 18px;">
    &nbsp;ผู้ตรวจ ........................ เวลา ........................
</th>
<th style="text-align: left;font-size: 18px;"> </th>
</tr>
    </table>
