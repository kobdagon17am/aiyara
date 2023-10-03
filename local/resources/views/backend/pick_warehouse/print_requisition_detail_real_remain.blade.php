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
<?php
$db_pick_pack_packing_data = DB::table('db_pick_pack_packing')
    ->where('packing_code_id_fk', $data[0])
    ->orderBy('delivery_id_fk', 'asc')
    ->get();
$db_pick_pack_packing_code = DB::table('db_pick_pack_packing_code')
    ->where('id', $data[0])
    ->first();
$all_order = explode(',', $db_pick_pack_packing_code->receipt);
$arr_has_db_pick_pack_packing_data = [];

// วนเช็ค

foreach ($db_pick_pack_packing_data as $index => $pick_pack_packing_data) {
    $packing = DB::select(' SELECT * FROM db_pick_pack_packing WHERE delivery_id_fk=' . $pick_pack_packing_data->delivery_id_fk . ' and packing_code_id_fk=' . $data[0] . '  GROUP BY packing_code ');
    $recipient_name = '';
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
              $pick_pack_packing_data->delivery_id_fk .
            ' AND set_addr_send_this=1 ',
    );

    if (!$delivery) {
        $pack_check = DB::table('db_delivery_packing')
            ->select('packing_code')
            ->where('delivery_id_fk', $pick_pack_packing_data->delivery_id_fk)
            ->first();
        if ($pack_check) {
            $pack_check_arr = DB::table('db_delivery_packing')
                ->select('delivery_id_fk')
                ->where('packing_code', $pack_check->packing_code)
                ->pluck('delivery_id_fk')
                ->toArray();
            if (count($pack_check_arr) > 0) {
                $delivery = DB::table('db_delivery')
                    ->select('set_addr_send_this', 'recipient_name', 'addr_send', 'postcode', 'mobile', 'tel_home', 'status_pack', 'receipt', 'id as delivery_id_fk', 'orders_id_fk')
                    ->whereIn('id', $pack_check_arr)
                    ->where('set_addr_send_this', 1)
                    ->get();
            }
        }
    }

    //       $delivery0 = DB::select(" SELECT
    //       db_delivery.set_addr_send_this,
    //       db_delivery.recipient_name,
    //       db_delivery.addr_send,
    //       db_delivery.postcode,
    //       db_delivery.mobile,
    //       db_delivery.tel_home,
    //       db_delivery.status_pack,
    //       db_delivery.receipt,
    //       db_delivery.id as delivery_id_fk,
    //       db_delivery.id,
    //       db_delivery.orders_id_fk
    //       FROM
    //       db_delivery
    //       WHERE
    //       db_delivery.id = ".$pick_pack_packing_data->delivery_id_fk." AND set_addr_send_this=0 ");

    // if(!$delivery){
    //     if($delivery0){
    //           // วุฒิเพิ่มมาสำหรับพวกบิลส่งกับบิลอื่น
    //           $order_this = DB::table('db_orders')
    //                     ->where('id', $delivery0[0]->orders_id_fk)
    //                     ->first();
    //                 if ($order_this) {
    //                     $order_send = DB::table('db_orders')
    //                         ->where('code_order', 'like', '%' . $order_this->bill_transfer_other . '%')
    //                         ->first();
    //                     if ($order_send) {
    //                         $delivery2 = DB::table('db_delivery')
    //                             ->where('orders_id_fk', $order_send->id)
    //                             ->first();
    //                         if ($delivery2) {
    //                             DB::table('db_delivery')
    //                                 ->where('id', $delivery0[0]->id)
    //                                 ->update([
    //                                     'recipient_name' => $delivery2->recipient_name,
    //                                     'addr_send' => $delivery2->addr_send,
    //                                     'postcode' => $delivery2->postcode,
    //                                     'mobile' => $delivery2->mobile,
    //                                     'tel_home' => $delivery2->tel_home,
    //                                 ]);

    //                                 $delivery = DB::select(" SELECT
    //                                   db_delivery.set_addr_send_this,
    //                                   db_delivery.recipient_name,
    //                                   db_delivery.addr_send,
    //                                   db_delivery.postcode,
    //                                   db_delivery.mobile,
    //                                   db_delivery.tel_home,
    //                                   db_delivery.status_pack,
    //                                   db_delivery.receipt,
    //                                   db_delivery.id as delivery_id_fk,
    //                                   db_delivery.orders_id_fk
    //                                   FROM
    //                                   db_delivery
    //                                   WHERE
    //                                   db_delivery.id = ".$delivery0[0]->id);
    //                                         }
    //                     }
    //                 }
    //     }
    // }

    $recipient_name = @$delivery[0]->recipient_name ? @$delivery[0]->recipient_name : '';
    $addr_send = @$delivery[0]->addr_send . ' ' . @$delivery[0]->postcode;
    $tel = @$delivery[0]->mobile . ' ' . @$delivery[0]->tel_home;
    $receipt = '';

    if (@$delivery[0]->status_pack == 1) {
        $d1 = DB::select(' SELECT * from db_delivery WHERE id=' . $delivery[0]->delivery_id_fk . '');
        $d2 = DB::select(' SELECT * from db_delivery WHERE packing_code=' . $d1[0]->packing_code . '');
        $arr1 = [];
        foreach ($d2 as $key => $v) {
            if (in_array($v->receipt, $all_order)) {
                array_push($arr1, $v->receipt);
            }
        }
        $receipt = implode(',', $arr1);
    } else {
        $receipt = @$delivery[0]->receipt;
    }

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
                      $data[0] .
                      "
          AND db_pick_pack_packing.delivery_id_fk = " .
                      $pick_pack_packing_data->delivery_id_fk .
                      "
          ORDER BY db_pick_pack_packing.id
          ",
              );

    foreach ($sTable as $key => $row) {
        $sum_amt = 0;
        $r_ch_t = '';
        $fid_arr = explode(',', $receipt);
        $order_arr = DB::table('db_orders')
            ->select('id')
            ->whereIn('code_order', $fid_arr)
            ->pluck('id')
            ->toArray();
        $db_pay_requisition_002 = DB::table('db_pay_requisition_002')
            ->where('pick_pack_requisition_code_id_fk', $db_pick_pack_packing_code->ref_bill_id)
            ->where('amt_remain', '>', 0)
            ->orderBy('time_pay', 'desc')
            ->get();
        $arr_002 = [];
        foreach ($db_pay_requisition_002 as $data_002) {
            if (!isset($arr_002[$data_002->product_id_fk])) {
                $arr_002[$data_002->product_id_fk] = $data_002->id;
            }
        }

        $db_pay_requisition_002_items = DB::table('db_pay_requisition_002_item')
            ->select('db_pay_requisition_002_item.*')
            ->whereIn('db_pay_requisition_002_item.requisition_002_id', $arr_002)
            ->whereIn('db_pay_requisition_002_item.order_id', $order_arr)
            ->where('db_pay_requisition_002_item.amt_remain', '>', 0)
            ->orderBy('db_pay_requisition_002_item.requisition_002_id', 'desc')
            ->get();

        $arr_002_item2 = [];
        foreach ($db_pay_requisition_002_items as $data_002_item2) {
            if (!isset($arr_002_item2[$data_002_item2->product_id_fk])) {
                $arr_002_item2[$data_002_item2->product_id_fk] = $data_002_item2;
            }
        }

        if (@$arr_002_item2) {
            foreach ($arr_002_item2 as $key => $value) {
                if (!empty($value->product_id_fk)) {
                    // true
                    $arr_has_db_pick_pack_packing_data[$pick_pack_packing_data->id] = $pick_pack_packing_data->id;
                }
            }
        }
    }
}

// วนแสดง

foreach ($db_pick_pack_packing_data as $no_data) {
    if (in_array($no_data->id, $arr_has_db_pick_pack_packing_data)) {
        // true
    } else {
        DB::table('db_pick_pack_packing')
            ->where('id', $no_data->id)
            ->update([
                'no_product' => 1,
            ]);
    }
}

$db_pick_pack_packing_data = DB::table('db_pick_pack_packing')
    ->where('packing_code_id_fk', $data[0])
    ->whereIn('id', $arr_has_db_pick_pack_packing_data)
    ->orderBy('delivery_id_fk', 'asc')
    ->get();

$db_pick_pack_packing_code = DB::table('db_pick_pack_packing_code')
    ->where('id', $data[0])
    ->first();
$all_order = explode(',', $db_pick_pack_packing_code->receipt);

?>

@foreach ($db_pick_pack_packing_data as $index => $pick_pack_packing_data)
    <div class="NameAndAddress" style="">
        <table style="border-collapse: collapse;">
            <tr>
                <th style="text-align: left;">
                    <img src="<?= public_path('images/logo2.png') ?>">
                </th>
                <th style="text-align: right;">
                    94 ซอยนาคนิวาส 6 ถนนนาคนิวาส <br>
                    แขวงลาดพร้าว เขตลาดพร้าว กรุงเทพมหานคร 10230 ประเทศไทย <br>
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
                <th style="text-align: center;font-size: 30px;">
                    <center> รายละเอียดจัดส่งสินค้า </center>
                </th>
            </tr>
        </table>
    </div>

    <?php
    $packing = DB::select(' SELECT * FROM db_pick_pack_packing WHERE delivery_id_fk=' . $pick_pack_packing_data->delivery_id_fk . ' and packing_code_id_fk=' . $data[0] . '  GROUP BY packing_code ');
    // $packing = DB::select(" SELECT * FROM db_pick_pack_packing WHERE id = $pick_pack_packing_data->id ");

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
            $pick_pack_packing_data->delivery_id_fk .
            '  ',
    );
    // AND set_addr_send_this=1

    $recipient_name = @$delivery[0]->recipient_name ? @$delivery[0]->recipient_name : '';
    $addr_send = @$delivery[0]->addr_send . ' ' . @$delivery[0]->postcode;
    $tel = @$delivery[0]->mobile . ' ' . @$delivery[0]->tel_home;
    $receipt = '';

    if (@$delivery[0]->status_pack == 1) {
        $d1 = DB::select(' SELECT * from db_delivery WHERE id=' . $delivery[0]->delivery_id_fk . '');
        $d2 = DB::select(' SELECT * from db_delivery WHERE packing_code=' . $d1[0]->packing_code . '');
        $arr1 = [];
        foreach ($d2 as $key => $v) {
            if (in_array($v->receipt, $all_order)) {
                array_push($arr1, $v->receipt);
            }
        }
        $receipt = implode(',', $arr1);
    } else {
        $receipt = @$delivery[0]->receipt;
    }

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
                        เลขที่ใบเสร็จ : {{ @$receipt }}
                    </td>

                </tr>

                <tr>
                    <td colspan="2" style="width:30%;vertical-align: top;font-weight: bold">
                        [ รายการสินค้า ]
                    </td>
                </tr>

                <?php

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
                        $data[0] .
                        "
                AND db_pick_pack_packing.delivery_id_fk = " .
                        $pick_pack_packing_data->delivery_id_fk .
                        "
                ORDER BY db_pick_pack_packing.id
                ",
                );

                foreach ($sTable as $key => $row) {
                    $pn = '<div class="divTable"><div class="divTableBody">';
                    $pn .= '<div class="divTableRow">
                <div class="divTableCell" style="width:200px;font-weight:bold;">รหัส : ชื่อสินค้า</div>
                <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
                <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
                </div>
                ';

                    $sum_amt = 0;
                    $r_ch_t = '';
                    $fid_arr = explode(',', $receipt);
                    $order_arr = DB::table('db_orders')
                        ->select('id')
                        ->whereIn('code_order', $fid_arr)
                        ->pluck('id')
                        ->toArray();

                    // $Products = App\Models\Backend\Orders::getAllProduct($order_arr);
                    $db_pay_requisition_002 = DB::table('db_pay_requisition_002')
                        ->where('pick_pack_requisition_code_id_fk', $db_pick_pack_packing_code->ref_bill_id)
                        ->where('amt_remain', '>', 0)
                        ->orderBy('time_pay', 'desc')
                        ->get();
                    $arr_002 = [];
                    foreach ($db_pay_requisition_002 as $data_002) {
                        if (!isset($arr_002[$data_002->product_id_fk])) {
                            $arr_002[$data_002->product_id_fk] = $data_002->id;
                        }
                    }

                    $db_pay_requisition_002_items = DB::table('db_pay_requisition_002_item')
                        ->select(
                            'db_pay_requisition_002_item.*',
                            // ,'products_details.product_name as product_name','products.product_code as product_code','dataset_product_unit.product_unit as product_unit'
                        )
                        // ->join('products','products.id','db_pay_requisition_002_item.product_id_fk')
                        // ->join('products_details','products_details.product_id_fk','db_pay_requisition_002_item.product_id_fk')
                        // ->join('products_units','products_units.product_id_fk','db_pay_requisition_002_item.product_id_fk')
                        // ->join('dataset_product_unit','dataset_product_unit.id','products_units.product_unit_id_fk')
                        ->whereIn('db_pay_requisition_002_item.requisition_002_id', $arr_002)
                        ->whereIn('db_pay_requisition_002_item.order_id', $order_arr)
                        ->where('db_pay_requisition_002_item.amt_remain', '>', 0)
                        ->orderBy('db_pay_requisition_002_item.requisition_002_id', 'desc')
                        // ->groupBy('product_id_fk')
                        ->get();

                    $arr_002_item2 = [];
                    foreach ($db_pay_requisition_002_items as $data_002_item2) {
                        if (!isset($arr_002_item2[$data_002_item2->product_id_fk])) {
                            $arr_002_item2[$data_002_item2->product_id_fk] = $data_002_item2;
                        }
                    }

                    if (@$arr_002_item2) {
                        foreach ($arr_002_item2 as $key => $value) {
                            if (!empty($value->product_id_fk)) {
                                $product_code = DB::table('products')
                                    ->select('product_code')
                                    ->where('id', $value->product_id_fk)
                                    ->first();
                                $product_name = DB::table('products_details')
                                    ->select('product_name')
                                    ->where('product_id_fk', $value->product_id_fk)
                                    ->first();
                                $product_unit_id = DB::table('products_units')
                                    ->select('product_unit_id_fk')
                                    ->where('product_id_fk', $value->product_id_fk)
                                    ->first();
                                $product_unit = DB::table('dataset_product_unit')
                                    ->select('product_unit')
                                    ->where('id', $product_unit_id->product_unit_id_fk)
                                    ->first();

                                $pn .=
                                    '<div class="divTableRow">
                    <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                    ' .
                                    @$product_code->product_code .
                                    ' : ' .
                                    @$product_name->product_name .
                                    '</b>
                    </div>
                    <div class="divTableCell" style="text-align:center;">' .
                                    @$value->amt_remain .
                                    '</div>
                    <div class="divTableCell" style="text-align:center;">' .
                                    @$product_unit->product_unit .
                                    '</div>
                    ';
                                $pn .= '</div>';
                                @$sum_amt += @$value->amt_remain;
                            }
                        }

                        $pn .=
                            '<div class="divTableRow">
                  <div class="divTableCell" style="text-align:right;font-weight:bold;"> รวม </div>
                  <div class="divTableCell" style="text-align:center;font-weight:bold;">' .
                            @$sum_amt .
                            '</div>
                  <div class="divTableCell" style="text-align:center;"> </div>
                  </div>
                  ';

                        $pn .= '</div>';
                        echo $pn;
                        // }
                    }
                }

                ?>

            </table>
        </div>

        <br>
        <table style="border-collapse: collapse;">
          <tr>
              <th colspan="2" style="text-align: left;font-size: 18px;"><b><u>ส่วนงานคลัง</u></b></th>
          </tr>
          <tr>
              <th style="text-align: left;font-size: 18px;">
                  <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike"
                      style="transform : scale(1.3);">
                  <label
                      style="position: relative; left: -20px; display: inline-block; vertical-align: middle;">&nbsp;ตรวจสอบเอกสารใบเสร็จรับเงินโดย
                      ............................</label>
              </th>
              <th style="text-align: left;font-size: 18px;">
                  ถุงใหญ่(ใบ) ...........................
                  ถุงเล็ก(ใบ) ................................
              </th>
          </tr>

          <tr>
              <th style="text-align: left;font-size: 18px;">
                  <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike"
                      style="transform : scale(1.3);">
                  <label
                      style="position: relative; left: -20px; display: inline-block; vertical-align: middle;">&nbsp;บันทึกจ่ายระบบโดย
                      ....................................................</label>
              </th>
              <th style="text-align: left;font-size: 18px;">
                  ขนาดลัง ...........................
                  จำนวน/ลัง .....................................
              </th>
          </tr>

          <tr>
              <th style="text-align: left;font-size: 18px;">
                  <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike"
                      style="transform : scale(1.3);">
                  <label style="position: relative; left: -20px; display: inline-block; vertical-align: middle;">
                      &nbsp;รายการสินค้าถูกต้อง</label>
              </th>
              <th style="text-align: left;font-size: 18px;"> น้ำหนัก(Kg.) ...................... เวลา
                  ............................................. </th>
          </tr>

          <tr>
            <th style="text-align: left;font-size: 18px;">
                <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike"
                    style="transform : scale(1.3);">
                <label style="position: relative; left: -20px; display: inline-block; vertical-align: middle;">
                    &nbsp;ชั่งน้ำหนักเรียบร้อย</label>
            </th>
            <th style="text-align: left;font-size: 18px;"> ผู้ดำเนินการ .............................................................................. </th>
        </tr>


          <tr>
              <th style="text-align: left;font-size: 18px;">
                  <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike"
                      style="transform : scale(1.3);">
                  <label
                      style="position: relative; left: -20px; display: inline-block; vertical-align: middle;">&nbsp;จำนวนสินค้าถูกต้อง</label>
              </th>
              <th style="text-align: left;font-size: 18px;"> </th>
          </tr>

          <tr>
              <th style="text-align: left;font-size: 18px;">
                  <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike"
                      style="transform : scale(1.3);">
                  <label
                      style="position: relative; left: -20px; display: inline-block; vertical-align: middle;">&nbsp;สภาพสินค้าไม่มีรอยบุบหรือฉีกขาด</label>
              </th>
              <th style="text-align: left;font-size: 18px;"> </th>
          </tr>

          {{-- <tr>
              <th style="text-align: left;font-size: 18px;">
                  <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike"
                      style="transform : scale(1.3);">
                  <label
                      style="position: relative; left: -20px; display: inline-block; vertical-align: middle;">&nbsp;ชั่งน้ำหนักสินค้าเรียบร้อย</label>
              </th>
              <th style="text-align: left;font-size: 18px;"> </th>
          </tr> --}}
          <tr>
              <th style="text-align: left;font-size: 18px;">
                  <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike"
                      style="transform : scale(1.3);">
                  <label
                      style="position: relative; left: -20px; display: inline-block; vertical-align: middle;">&nbsp;ผ่าน</label>
                  &nbsp;&nbsp; <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike"
                      style="transform : scale(1.3);">
                  <label
                      style="position: relative; left: -20px; display: inline-block; vertical-align: middle;">&nbsp;ไม่ผ่าน</label>
              </th>
              <th style="text-align: left;font-size: 18px;"></th>
          </tr>
          <tr>
              <th style="text-align: left;font-size: 18px;">
                  &nbsp; &nbsp; &nbsp; ผู้ตรวจสอบ .................................................................
              </th>
              <th style="text-align: left;font-size: 18px;"> </th>
          </tr>
      </table>

        @if ($index < count($db_pick_pack_packing_data) - 1)
            <div class="page-break"></div>
        @endif
@endforeach
