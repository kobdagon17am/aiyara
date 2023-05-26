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

    /* @page {
    size: A4;
    padding: 5px;
    orientation: landscape;
} */

    @media print {

        html,
        body {
            /*width: 210mm;*/
            /*height: 297mm;*/
        }
    }


    @charset "utf-8";

    .pagenum:before {
        content: counter(page);
    }


    .NameAndAddress {
        width: 100%;
        /*color: #898989;*/
        /*margin-bottom: 20px;*/
    }

    .NameAndAddress b {
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
        /* color: grey; */
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
</style>

<?php

ini_set('memory_limit', '9999999M');
ini_set('max_execution_time', '9999999');
set_time_limit(9999999);
?>


<div class="NameAndAddress" style="">
    <table style="border-collapse: collapse;">
        <!-- <tr>
            <th style="text-align: right;">&nbsp;</th>
            <th style="text-align: right;">
                หน้า 1/1
            </th>
        </tr> -->
        <tr>
            <th style="text-align: left;">
                <img src="{{ asset('images/logo2.png') }}">
            </th>
            <th style="text-align: right;">
                <br>
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
            <th style="font-size: 18px;">

            รายงานการโอนเงินคืน กรณีเงินเกิน

                <br>
                <?php
                if ($report_type == 'day') {
                    // echo "ประจำวันที่ ".date('d/m/Y', strtotime($startDate2))." ถึง ".date('Y/m/d', strtotime($endDate2));
                    echo 'ประจำวันที่ ' . date('d/m/Y', strtotime($startDate2));
                } else {
                    echo 'ประจำเดือน ' . date('m/Y', strtotime($startDate2));
                }

                ?>
                <br>
            </th>
        </tr>
    </table>
</div>


<div class="NameAndAddress">
    <div style="bo  rder-radius: 5px; padding: 3px;">
        <table style="border-collapse: collapse;">
            <thead>
                <!-- <tr style="background-color: #e6e6e6;"> -->
                <tr>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        วันเดือนปี
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        เลขที่เอกสาร
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        ชื่อ-นามสกุล
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        มูลค่าสินค้า
                    </td>

                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        ค่าขนส่ง
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        ค่าธรรมเนียม
                    </td>

                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        รวม
                    </td>

                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        ภาษีมูลค่าเพิ่ม
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        รวมทั้งหมด
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        คูปองส่วนลด
                    </td>

                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        เงินสด
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        colspan="2">
                        เงินโอน
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        เครดิต
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        Promtpay
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        Truemoney
                    </td>
                    <!-- <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        Ai-Cash
                    </td> -->
                </tr>

                <tr style="">
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        ออมทรัพย์
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        กระแสรายวัน
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php

                    $approve_status = 'WHERE db_orders.approve_status not in (0,5,1,6,3)';
                    $over = 'AND db_orders.approval_amount_transfer_over > 0';

                // แบบไม่ sum
                if ($report_type == 'day') {
                    $sTable = DB::select("
                                        SELECT
                                        db_orders.action_user,
                                        ck_users_admin.`name` as action_user_name,
                                        db_orders.pay_type_id_fk,
                                        dataset_pay_type.detail AS pay_type,
                                        date(db_orders.approve_date) AS approve_date,
                                        db_orders.branch_id_fk,
                                        db_orders.customers_sent_id_fk,
                                        branchs.b_name as branchs_name,
                                        dataset_business_location.txt_desc as business_location_name,

                                        db_orders.sum_credit_price,
                                        db_orders.transfer_price,
                                        db_orders.fee_amt,
                                        db_orders.aicash_price,
                                        db_orders.cash_pay,
                                        db_orders.gift_voucher_price,
                                        db_orders.shipping_price,
                                        db_orders.product_value,
                                        db_orders.tax,
                                        db_orders.sum_price,
                                        db_orders.true_money_price,
                                        db_orders.prompt_pay_price,
                                        db_orders.account_bank_name_customer,

                                        customers.first_name as action_first_name,
                                        customers.last_name as action_last_name,

                                        db_orders.code_order

                                        FROM
                                        db_orders
                                        Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                                        Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                                        Left Join branchs ON branchs.id = db_orders.branch_id_fk
                                        Left Join dataset_business_location ON dataset_business_location.id = db_orders.business_location_id_fk
                                        Left Join customers ON customers.id = db_orders.customers_id_fk
                                        $approve_status
                                        $startDate
                                        $endDate
                                        $action_user
                                        $business_location_id_fk
                                        $over

                                        ORDER BY approve_date ASC

                                  ");
                    //  AND db_orders.code_order = 'O123030200329'
                    // LIMIT 116
                } else {
                    $sTable = DB::select("
                                        SELECT
                                        db_orders.action_user,
                                        ck_users_admin.`name` as action_user_name,
                                        db_orders.pay_type_id_fk,
                                        dataset_pay_type.detail AS pay_type,
                                        db_orders.customers_sent_id_fk,
                                        date(db_orders.approve_date) AS approve_date,
                                        db_orders.branch_id_fk,
                                        branchs.b_name as branchs_name,
                                        dataset_business_location.txt_desc as business_location_name,

                                        db_orders.sum_credit_price,
                                        db_orders.transfer_price,
                                        db_orders.fee_amt,
                                        db_orders.aicash_price,
                                        db_orders.cash_pay,
                                        db_orders.gift_voucher_price,
                                        db_orders.shipping_price,
                                        db_orders.product_value,
                                        db_orders.tax,
                                        db_orders.sum_price,
                                        db_orders.true_money_price,
                                        db_orders.prompt_pay_price,
                                        db_orders.account_bank_name_customer,

                                        customers.first_name as action_first_name,
                                        customers.last_name as action_last_name,

                                        db_orders.code_order

                                        FROM
                                        db_orders
                                        Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                                        Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                                        Left Join branchs ON branchs.id = db_orders.branch_id_fk
                                        Left Join dataset_business_location ON dataset_business_location.id = db_orders.business_location_id_fk
                                        Left Join customers ON customers.id = db_orders.customers_id_fk
                                        $approve_status
                                        $startDate
                                        $endDate
                                        $action_user
                                        $business_location_id_fk
                                        $over
                                        ORDER BY approve_date ASC
                                  ");
                }

                $p = '';
                $product_value_total = 0;
                $tax_total = 0;
                $sum_price_total = 0;
                $cash_pay_total = 0;
                $cash_transfer_price_total = 0;
                $cash_transfer_price_1_total = 0;
                $cash_transfer_price_2_total = 0;
                $cash_sum_credit_price_total = 0;

                $shipping_price_new_total = 0;
                $fee_amt_new_total = 0;
                $sum_new_total = 0;
                $tax_new_total = 0;
                $all_sum_new_total = 0;
                $gift_voucher_price_new_total = 0;
                $transfer_price_1_new_total = 0;
                $transfer_price_2_new_total = 0;
                $prompt_pay_price_new_total = 0;
                $true_money_price_new_total = 0;

                $aicash_price_total = 0;
                $fee_amt_total = 0;
                $shipping_total = 0;
                $gift_voucher_price_total = 0;
                $true_money_price_total = 0;
                $prompt_pay_price_total = 0;

                $shipping_vat_total = 0;
                $fee_vat_total = 0;
                $transfer_price_1_total = 0;
                $transfer_price_2_total = 0;

                $arr_date = [];

                $total_date_product_value = 0;
                $total_date_tax = 0;
                $total_date_sum_price = 0;

                $total_date_shipping_price = 0;

                $total_date_cash_pay = 0;
                $total_date_transfer_price = 0;
                $total_date_sum_credit_price = 0;
                $total_date_aicash_price = 0;
                $total_date_fee_amt = 0;
                $total_date_sum_all = 0;
                $total_date_transfer_price_1 = 0;
                $total_date_transfer_price_2 = 0;

                $total_date_gift_voucher_price = 0;

                $total_date_prompt_pay_price = 0;
                $total_date_true_money_price = 0;

                $arr_data = [];
                $arr_data2 = [];

                // account_bank_name_customer ดูจากตรงนี้
                $bank_books = DB::table('dataset_account_bank')
                    ->select('id', 'type_book')
                    ->get();

                $day_old = '';
                $day_curr = '';

                foreach ($sTable as $key => $order) {
                    $day_curr = $order->approve_date;
                    if ($day_old == '') {
                        $day_old = $order->approve_date;
                    }

                    if ($order->action_user_name == '') {
                        $order->action_user_name = 'V3';
                    }
                    if ($order->cash_pay == '') {
                        $order->cash_pay = 0.0;
                    }
                    if ($order->transfer_price == '') {
                        $order->transfer_price = 0.0;
                    }
                    if ($order->sum_credit_price == '') {
                        $order->sum_credit_price = 0.0;
                    }
                    if ($order->aicash_price == '') {
                        $order->aicash_price = 0.0;
                    }

                    if ($order->gift_voucher_price == '') {
                        $order->gift_voucher_price = 0.0;
                    }

                    if ($order->true_money_price == '') {
                        $order->true_money_price = 0.0;
                    }

                    if ($order->prompt_pay_price == '') {
                        $order->prompt_pay_price = 0.0;
                    }

                    if ($order->shipping_price == '') {
                        $order->shipping_price = 0.0;
                    }

                    if ($order->fee_amt == '') {
                        $order->fee_amt = 0.0;
                    }

                    $shipping_vat = 0;
                    $fee_vat = 0;

                    if ($order->shipping_price > 0) {
                        $shipping_vat = $order->shipping_price * (7.0 / (100 + 7.0));
                        // $shipping_vat = round( $shipping_vat ,2 );
                        $shipping_vat = $shipping_vat;
                        $order->shipping_price = $order->shipping_price - $shipping_vat;
                        $order->tax = $order->tax + $shipping_vat;
                    }

                    if ($order->fee_amt > 0) {
                        $fee_vat = $order->fee_amt * (7.0 / (100 + 7.0));
                        // $fee_vat = round( $fee_vat ,2 );
                        $fee_vat = $fee_vat;
                        $order->fee_amt = $order->fee_amt - $fee_vat;
                        $order->tax = $order->tax + $fee_vat;
                    }

                    // วุฒิปรับ tax คำนวณใหม่
                    if ($order->sum_price > 0) {
                        $order->tax = $order->sum_price * (7.0 / (100 + 7.0));
                        // $order->tax = round( $order->tax ,2 );
                        $order->tax = $order->tax;
                        $order->product_value = $order->sum_price - $order->tax;
                    } //

                    $gift_voucher_price_total += $order->gift_voucher_price;
                    $product_value_total += $order->product_value;
                    // $tax_total += $order->tax;
                    $tax_total += $order->tax + $shipping_vat + $fee_vat;

                    $sum_price_total += $order->sum_price;

                    $cash_pay_total += $order->cash_pay;

                    $cash_sum_credit_price_total += $order->sum_credit_price;
                    $aicash_price_total += $order->aicash_price;
                    $shipping_total += $order->shipping_price;
                    $fee_amt_total += $order->fee_amt;

                    $true_money_price_total += $order->true_money_price;
                    $prompt_pay_price_total += $order->prompt_pay_price;

                    $shipping_vat_total += $shipping_vat;
                    $fee_vat_total += $fee_vat;

                    $transfer_price_1 = 0;
                    $transfer_price_2 = 0;

                    if ($order->transfer_price > 0) {
                        foreach ($bank_books as $b) {
                            if ($order->account_bank_name_customer == $b->id) {
                                if ($b->type_book == 1) {
                                    $transfer_price_1_total += $order->transfer_price;
                                    $transfer_price_1 = $order->transfer_price;
                                }
                                if ($b->type_book == 2) {
                                    $transfer_price_2_total += $order->transfer_price;
                                    $transfer_price_2 = $order->transfer_price;
                                }
                            }
                        }
                    }

                    $shipping_price_new_total += $order->shipping_price;
                    $fee_amt_new_total += $order->fee_amt;
                    $sum_new_total += $order->fee_amt + $order->shipping_price + $order->product_value;
                    $tax_new_total += $order->tax + $shipping_vat + $fee_vat;
                    $all_sum_new_total += $order->fee_amt + $order->shipping_price + $order->product_value + ($order->tax + $shipping_vat + $fee_vat);
                    $gift_voucher_price_new_total += $order->gift_voucher_price;
                    $transfer_price_1_new_total += $transfer_price_1;
                    $transfer_price_2_new_total += $transfer_price_2;
                    $prompt_pay_price_new_total += $order->prompt_pay_price;
                    $true_money_price_new_total += $order->true_money_price;

                    $cash_transfer_price_1_total += $transfer_price_1;
                    $cash_transfer_price_2_total += $transfer_price_2;

                    // if($report_type == 'day'){
                    $approve_date = date('d/m/Y', strtotime($order->approve_date));
                    // }else{
                    //   $action_date = date('m/Y', strtotime($order->action_date));
                    // }

                    // if($report_type == 'day'){
                    $code_order = $order->code_order;
                    // }else{
                    //   $code_order = '-';
                    // }

                    // if($report_type == 'day'){
                    $cus_name = $order->action_first_name . ' ' . $order->action_last_name;
                    if ($order->customers_sent_id_fk != '' && $order->customers_sent_id_fk != null && $order->customers_sent_id_fk != 0) {
                        $cus_detail = DB::table('customers')
                            ->select('first_name as action_first_name', 'customers.last_name as action_last_name')
                            ->where('id', $order->customers_sent_id_fk)
                            ->first();
                        if ($cus_detail) {
                            $cus_name = $cus_detail->action_first_name . ' ' . $cus_detail->action_last_name;
                        }
                    }

                    $cus_name = str_replace('(ยกเลิกสมัครซ้ำ)', '', $cus_name);
                    $cus_name = str_replace('(ยกเลิก)', ' ', $cus_name);
                    $cus_name = str_replace('-ลาออก-', ' ', $cus_name);

                    // }else{
                    //   $cus_name = '-';
                    // }

                    if (!isset($arr_date[$approve_date])) {
                        if (count($arr_date) == 0) {
                            $total_date_product_value += $order->product_value;
                            $total_date_tax += $order->tax + ($shipping_vat + $fee_vat);
                            $total_date_sum_price += $order->sum_price;
                            $total_date_shipping_price += $order->shipping_price;
                            $total_date_fee_amt += $order->fee_amt;

                            $total_date_sum_all += $order->fee_amt + $order->shipping_price + $order->product_value;

                            $total_date_cash_pay += $order->cash_pay;
                            $total_date_transfer_price += $order->transfer_price;

                            $total_date_transfer_price_1 += $transfer_price_1;
                            $total_date_transfer_price_2 += $transfer_price_2;

                            $total_date_gift_voucher_price += $order->gift_voucher_price;

                            $total_date_prompt_pay_price += $order->prompt_pay_price;
                            $total_date_true_money_price += $order->true_money_price;

                            $total_date_sum_credit_price += $order->sum_credit_price;
                            $total_date_aicash_price += $order->aicash_price;
                            $arr_data[$code_order] = $code_order;
                        }
                    }
                    // <td style="text-align: right; border-bottom: 1px solid #000; ">'.number_format($total_date_aicash_price,2,".",",").'</td>
                    if (count($arr_date) > 0) {
                        if (!isset($arr_date[$approve_date])) {
                            $p .=
                                '
                                  <tr>
                                  <td style="text-align: center;"></td>
                                  <td style="text-align: left;"></td>
                                  <td style="text-align: right;"></td>
                                  <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                                number_format($total_date_product_value, 2, '.', ',') .
                                '</td>
                                  <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                                number_format($total_date_shipping_price, 2, '.', ',') .
                                '</td>
                                  <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                                number_format($total_date_fee_amt, 2, '.', ',') .
                                '</td>
                                  <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                                number_format($total_date_sum_all, 2, '.', ',') .
                                '</td>
                                  <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                                number_format($total_date_tax, 2, '.', ',') .
                                '</td>
                                  <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                                number_format($total_date_sum_all + $total_date_tax, 2, '.', ',') .
                                '</td>
                                  <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                                number_format($total_date_gift_voucher_price, 2, '.', ',') .
                                '</td>
                                  <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                                number_format($total_date_cash_pay, 2, '.', ',') .
                                '</td>
                                  <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                                number_format($total_date_transfer_price_1, 2, '.', ',') .
                                '</td>
                                  <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                                number_format($total_date_transfer_price_2, 2, '.', ',') .
                                '</td>
                                  <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                                number_format($total_date_sum_credit_price, 2, '.', ',') .
                                '</td>
                                  <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                                number_format($total_date_prompt_pay_price, 2, '.', ',') .
                                '</td>
                                  <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                                number_format($total_date_true_money_price, 2, '.', ',') .
                                '</td>

                              </tr>
                                  ';
                        }
                    }
                    // <td style="text-align: right;">'.number_format($order->aicash_price,2,".",",").'</td>
                    // <td style="text-align: right;">'.number_format(($order->tax+$shipping_vat+$fee_vat),2,".",",").'</td>
                    $p .=
                        '
                              <tr>
                              <td style="text-align: center;">' .
                        $approve_date .
                        '</td>
                              <td style="text-align: left;">' .
                        $code_order .
                        '</td>
                              <td style="text-align: left;">' .
                        $cus_name .
                        '</td>
                              <td style="text-align: right;">' .
                        number_format($order->product_value, 2, '.', ',') .
                        '</td>
                              <td style="text-align: right;">' .
                        number_format($order->shipping_price, 2, '.', ',') .
                        '</td>
                              <td style="text-align: right;">' .
                        number_format($order->fee_amt, 2, '.', ',') .
                        '</td>
                              <td style="text-align: right;">' .
                        number_format($order->fee_amt + $order->shipping_price + $order->product_value, 2, '.', ',') .
                        '</td>
                              <td style="text-align: right;">' .
                        number_format($order->tax + $shipping_vat + $fee_vat, 2, '.', ',') .
                        '</td>
                              <td style="text-align: right;">' .
                        number_format($order->fee_amt + $order->shipping_price + $order->product_value + ($order->tax + $shipping_vat + $fee_vat), 2, '.', ',') .
                        '</td>
                              <td style="text-align: right;">' .
                        number_format($order->gift_voucher_price, 2, '.', ',') .
                        '</td>
                              <td style="text-align: right;">' .
                        number_format($order->cash_pay, 2, '.', ',') .
                        '</td>
                              <td style="text-align: right;">' .
                        number_format($transfer_price_1, 2, '.', ',') .
                        '</td>
                              <td style="text-align: right;">' .
                        number_format($transfer_price_2, 2, '.', ',') .
                        '</td>
                              <td style="text-align: right;">' .
                        number_format($order->sum_credit_price, 2, '.', ',') .
                        '</td>
                              <td style="text-align: right;">' .
                        number_format($order->prompt_pay_price, 2, '.', ',') .
                        '</td>
                              <td style="text-align: right;">' .
                        number_format($order->true_money_price, 2, '.', ',') .
                        '</td>

                          </tr>
                              ';

                    if (count($sTable) == $key + 1) {
                        if (count($sTable) == 1) {
                            $total_date_product_value = $order->product_value;
                            $total_date_tax = $order->tax + ($shipping_vat + $fee_vat);

                            $total_date_sum_price = $order->sum_price;
                            $total_date_shipping_price = $order->shipping_price;
                            $total_date_fee_amt = $order->fee_amt;

                            $total_date_sum_all = $order->fee_amt + $order->shipping_price + $order->product_value;

                            $total_date_cash_pay = $order->cash_pay;
                            $total_date_transfer_price = $order->transfer_price;

                            $total_date_transfer_price_1 = $transfer_price_1;
                            $total_date_transfer_price_2 = $transfer_price_2;

                            $total_date_prompt_pay_price = $order->prompt_pay_price;
                            $total_date_true_money_price = $order->true_money_price;

                            $total_date_gift_voucher_price = $order->gift_voucher_price;

                            $total_date_sum_credit_price = $order->sum_credit_price;
                            $total_date_aicash_price = $order->aicash_price;
                            $arr_data[$code_order] = $code_order;
                        } else {
                            if ($day_curr != $day_old) {
                                // dd($code_order);
                                $total_date_product_value = $order->product_value;
                                $total_date_tax = $order->tax + ($shipping_vat + $fee_vat);

                                $total_date_sum_price = $order->sum_price;
                                $total_date_shipping_price = $order->shipping_price;
                                $total_date_fee_amt = $order->fee_amt;

                                $total_date_sum_all = $order->fee_amt + $order->shipping_price + $order->product_value;

                                $total_date_cash_pay = $order->cash_pay;
                                $total_date_transfer_price = $order->transfer_price;

                                $total_date_transfer_price_1 = $transfer_price_1;
                                $total_date_transfer_price_2 = $transfer_price_2;

                                $total_date_prompt_pay_price = $order->prompt_pay_price;
                                $total_date_true_money_price = $order->true_money_price;

                                $total_date_gift_voucher_price = $order->gift_voucher_price;

                                $total_date_sum_credit_price = $order->sum_credit_price;
                                $total_date_aicash_price = $order->aicash_price;
                                $arr_data[$code_order] = $code_order;
                            } else {
                                $total_date_product_value += $order->product_value;
                                $total_date_tax += $order->tax + ($shipping_vat + $fee_vat);

                                $total_date_sum_price += $order->sum_price;
                                $total_date_shipping_price += $order->shipping_price;
                                $total_date_fee_amt += $order->fee_amt;

                                $total_date_sum_all += $order->fee_amt + $order->shipping_price + $order->product_value;

                                $total_date_cash_pay += $order->cash_pay;
                                $total_date_transfer_price += $order->transfer_price;

                                $total_date_transfer_price_1 += $transfer_price_1;
                                $total_date_transfer_price_2 += $transfer_price_2;

                                $total_date_prompt_pay_price += $order->prompt_pay_price;
                                $total_date_true_money_price += $order->true_money_price;

                                $total_date_gift_voucher_price += $order->gift_voucher_price;

                                $total_date_sum_credit_price += $order->sum_credit_price;
                                $total_date_aicash_price += $order->aicash_price;
                                $arr_data[$code_order] = $code_order;
                            }
                        }

                        $day_old = $order->approve_date;

                        $p .=
                            '
                                <tr>
                                <td style="text-align: center;"></td>
                                <td style="text-align: left;"></td>
                                <td style="text-align: right;"></td>
                                <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                            number_format($total_date_product_value, 2, '.', ',') .
                            '</td>
                                <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                            number_format($total_date_shipping_price, 2, '.', ',') .
                            '</td>
                                <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                            number_format($total_date_fee_amt, 2, '.', ',') .
                            '</td>
                                <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                            number_format($total_date_sum_all, 2, '.', ',') .
                            '</td>
                                <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                            number_format($total_date_tax, 2, '.', ',') .
                            '</td>
                                <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                            number_format($total_date_sum_all + $total_date_tax, 2, '.', ',') .
                            '</td>
                                <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                            number_format($total_date_gift_voucher_price, 2, '.', ',') .
                            '</td>
                                <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                            number_format($total_date_cash_pay, 2, '.', ',') .
                            '</td>
                                <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                            number_format($total_date_transfer_price_1, 2, '.', ',') .
                            '</td>
                                <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                            number_format($total_date_transfer_price_2, 2, '.', ',') .
                            '</td>
                                <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                            number_format($total_date_sum_credit_price, 2, '.', ',') .
                            '</td>
                                <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                            number_format($total_date_prompt_pay_price, 2, '.', ',') .
                            '</td>
                                <td style="text-align: right; border-bottom: 1px solid #000; ">' .
                            number_format($total_date_true_money_price, 2, '.', ',') .
                            '</td>

                            </tr>
                                ';
                        // <td style="text-align: right; border-bottom: 1px solid #000; ">'.number_format($total_date_aicash_price,2,".",",").'</td>
                    }

                    $day_old = $order->approve_date;

                    if (!isset($arr_date[$approve_date])) {
                        $arr_date[$approve_date] = $approve_date;
                        if (count($arr_date) != 0) {
                            $total_date_product_value = 0;
                            $total_date_tax = 0;
                            $total_date_sum_price = 0;
                            $total_date_shipping_price = 0;
                            $total_date_fee_amt = 0;
                            $total_date_sum_all = 0;
                            $total_date_cash_pay = 0;
                            $total_date_transfer_price = 0;
                            $total_date_transfer_price_1 = 0;
                            $total_date_transfer_price_2 = 0;
                            $total_date_prompt_pay_price = 0;
                            $total_date_true_money_price = 0;
                            $total_date_gift_voucher_price = 0;

                            $total_date_sum_credit_price = 0;
                            $total_date_aicash_price = 0;
                            $total_date_product_value += $order->product_value;
                            $total_date_tax += $order->tax + ($shipping_vat + $fee_vat);
                            $total_date_sum_price += $order->sum_price;
                            $total_date_shipping_price += $order->shipping_price;
                            $total_date_fee_amt += $order->fee_amt;
                            $total_date_sum_all += $order->fee_amt + $order->product_value + $order->shipping_price;

                            $total_date_cash_pay += $order->cash_pay;
                            $total_date_transfer_price += $order->transfer_price;

                            $total_date_transfer_price_1 += $transfer_price_1;
                            $total_date_transfer_price_2 += $transfer_price_2;

                            $total_date_prompt_pay_price += $order->prompt_pay_price;
                            $total_date_true_money_price += $order->true_money_price;
                            $total_date_gift_voucher_price += $order->gift_voucher_price;

                            $total_date_sum_credit_price += $order->sum_credit_price;
                            $total_date_aicash_price += $order->aicash_price;
                        }
                    } else {
                        $total_date_product_value += $order->product_value;
                        $total_date_tax += $order->tax + ($shipping_vat + $fee_vat);
                        $total_date_sum_price += $order->sum_price;
                        $total_date_shipping_price += $order->shipping_price;
                        $total_date_fee_amt += $order->fee_amt;
                        $total_date_sum_all += $order->fee_amt + $order->product_value + $order->shipping_price;

                        $total_date_cash_pay += $order->cash_pay;
                        $total_date_transfer_price += $order->transfer_price;

                        $total_date_transfer_price_1 += $transfer_price_1;
                        $total_date_transfer_price_2 += $transfer_price_2;

                        $total_date_prompt_pay_price += $order->prompt_pay_price;
                        $total_date_true_money_price += $order->true_money_price;

                        $total_date_gift_voucher_price += $order->gift_voucher_price;

                        $total_date_sum_credit_price += $order->sum_credit_price;
                        $total_date_aicash_price += $order->aicash_price;

                        $arr_data2[$code_order] = $code_order;
                    }
                }
                echo $p;
                ?>

                <tr>
                    <td style="text-align: center;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($product_value_total, 2, '.', ','); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($shipping_price_new_total, 2, '.', ','); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($fee_amt_new_total, 2, '.', ','); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($sum_new_total, 2, '.', ','); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($tax_new_total, 2, '.', ','); ?></td>

                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($all_sum_new_total, 2, '.', ','); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($gift_voucher_price_new_total, 2, '.', ','); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($cash_pay_total, 2, '.', ','); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($transfer_price_1_new_total, 2, '.', ','); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($transfer_price_2_new_total, 2, '.', ','); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($cash_sum_credit_price_total, 2, '.', ','); ?></td>

                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($prompt_pay_price_new_total, 2, '.', ','); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($true_money_price_new_total, 2, '.', ','); ?></td>

                    <!-- <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php //echo number_format($aicash_price_total,2,".",",");
                        ?></td> -->

                </tr>

            </tbody>


        </table>
    </div>
    <?php
    if (count($sTable) >= 8) {
        echo '<div class="page-break"></div>';
    }
    ?>
    <!-- <d iv style="border-radius: 5px; height: 33mm; border: 1px solid grey;padding:-1px;"> -->
    <table style="border-collapse: collapse;vertical-align: top;text-align: left;">

        <tr>
            <td style="border-left: 1px solid #ccc;"> รวมวิธีการชำระค่าสินค้าและบริการ &nbsp;</td>
            <td style="">&nbsp;</td>
            <td style="border-left: 1px solid #ccc;">รวมมูลค่าสินค้าและภาษีมูลค่าเพิ่ม &nbsp;</td>
            <!-- <td style="border-left: 1px solid #ccc;"> ในนาม บริษัท ไอยรา แพลนเน็ต จำกัด
                    <br>
                    <img src="" width="100"> (ลายเซ็น)
                    <br>
                    <br>
                    ผู้มีอำนาจลงนาม
                </td> -->
        </tr>


        <tr>
            <td style="border-left: 1px solid #ccc;"> เงินสด &nbsp;</td>
            <td style="text-align: right;"> <?php echo number_format($cash_pay_total, 2, '.', ','); ?> &nbsp;</td>
            <td style="border-left: 1px solid #ccc;"> มูลค่าสินค้า &nbsp;</td>
            <td style="text-align: right;"> <?php
            echo number_format($product_value_total, 2, '.', ',');
            // echo $product_value_total;
            ?> &nbsp;</td>
        </tr>
        <tr>
            <td style="border-left: 1px solid #ccc;"> เงินโอน &nbsp; ออมทรัพย์</td>
            <td style="text-align: right;"> <?php echo number_format($cash_transfer_price_1_total, 2, '.', ','); ?> &nbsp;</td>
            <td style="border-left: 1px solid #ccc;"> ค่าจัดส่ง &nbsp;</td>
            <td style="text-align: right;"> <?php echo number_format($shipping_total, 2, '.', ','); ?> &nbsp;</td>
        </tr>

        <tr>
            <td style="border-left: 1px solid #ccc;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                กระแสรายวัน</td>
            <td style="text-align: right;"> <?php echo number_format($cash_transfer_price_2_total, 2, '.', ','); ?> &nbsp;</td>
            <td style="border-left: 1px solid #ccc;"> ค่าธรรมเนียม&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($fee_amt_total, 2, '.', ','); ?> &nbsp;</td>
        </tr>

        <tr>
            <td style="border-left: 1px solid #ccc;"> เครดิต &nbsp;</td>
            <td style="text-align: right;"> <?php echo number_format($cash_sum_credit_price_total, 2, '.', ','); ?> &nbsp;</td>
            <td style="border-left: 1px solid #ccc;"> รวมมูลค่าก่อนภาษีมูลค่าเพิ่ม&nbsp;</td>
            <td style="text-align: right;"><?php
            echo number_format($product_value_total + $shipping_total + $fee_amt_total, 2, '.', ',');
            // echo ($product_value_total+$shipping_total+$fee_amt_total);
            ?> &nbsp;</td>
        </tr>

        <tr>
            <td style="border-left: 1px solid #ccc;"> PromptPay &nbsp;</td>
            <td style="text-align: right;"> <?php echo number_format($prompt_pay_price_total, 2, '.', ','); ?> &nbsp;</td>
            <td style="border-left: 1px solid #ccc;"> VAT 7% &nbsp;</td>
            <td style="text-align: right;"> <?php
            echo number_format($tax_total, 2, '.', ',');
            // echo round( $tax_total ,2 );
            // echo $tax_total;
            ?> &nbsp;</td>
        </tr>

        <tr>
            <td style="border-left: 1px solid #ccc;"> TrueMoney &nbsp;</td>
            <td style="text-align: right;"> <?php echo number_format($true_money_price_total, 2, '.', ','); ?> &nbsp;</td>
            <td style="border-left: 1px solid #ccc;"> <b>รวมมูลค่าสินค้าและภาษีมูลค่าเพิ่ม</b> &nbsp;</td>
            <td style="text-align: right;"> <?php echo number_format($product_value_total + $shipping_total + $fee_amt_total + $tax_total, 2, '.', ','); ?> &nbsp;</td>
        </tr>

        <tr>
            <td style="border-left: 1px solid #ccc;"> คูปองส่วนลด &nbsp;</td>
            <td style="text-align: right;"> <?php echo number_format($gift_voucher_price_total, 2, '.', ','); ?> &nbsp;</td>
            <td style="border-left: 1px solid #ccc;"> คูปอง &nbsp;</td>
            <td style="text-align: right;"> <?php echo number_format($gift_voucher_price_total, 2, '.', ','); ?> &nbsp;</td>
        </tr>

        <tr>
            <td style="border-left: 1px solid #ccc;"> <b><u>รวมยอดชำระทั้งสิ้น</u></b> </td>
            <td style="text-align: right;"><u style="text-align: right;">
                    <?php echo number_format($gift_voucher_price_total + $cash_pay_total + $cash_transfer_price_1_total + $cash_transfer_price_2_total + $cash_sum_credit_price_total + $true_money_price_total + $prompt_pay_price_total, 2, '.', ','); ?></u>
                &nbsp;</td>

            <td style="border-left: 1px solid #ccc;"> <b><u>รวมมูลค่าทั้งสิ้น</u></b> </td>
            <td style="text-align: right;"><u style="text-align: right;">
                    <?php echo number_format($product_value_total + $shipping_total + $fee_amt_total + $tax_total - $gift_voucher_price_total, 2, '.', ','); ?></u>
                &nbsp;</td>

        </tr>

    </table>
    <!-- </div> -->

    <!-- <div style="border-radius: 5px; height: 33mm; border: 1px solid grey;padding:-1px;">
        <table style="border-collapse: collapse;vertical-align: top;text-align: center;">

            <tr>

                <td style="border-left: 1px solid #ccc;"> ผู้รับเงิน

                    <br>
                    <img src="" width="100"> (ลายเซ็น)
                    <br>
                    <br>
                    วันที่ .........................................
                </td>


                <td style="border-left: 1px solid #ccc;"> ในนาม บริษัท ไอยรา แพลนเน็ต จำกัด
                    <br>
                    <img src="" width="100"> (ลายเซ็น)
                    <br>
                    <br>
                    ผู้มีอำนาจลงนาม
                </td>

            </tr>


        </table>
    </div> -->

</div>
