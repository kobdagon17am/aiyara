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
</style>

<?php

ini_set("memory_limit","9999999M");
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
                <img src="<?=public_path('images/logo2.png')?>">
            </th>
            <th style="text-align: right;">
                <br>
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
            <th style="font-size: 18px;">
                รายงานการขาย
                <br>
                <?php
              if($report_type == 'day'){
                echo "วันที่ ".date('d/m/Y', strtotime($startDate2))." ถึง ".date('Y/m/d', strtotime($endDate2));
              }else{
                echo "ประจำเดือน ".date('m/Y', strtotime($startDate2));
              }

              ?>
                <br>
            </th>
        </tr>
    </table>
</div>


<div class="NameAndAddress">

    <div style="border-radius: 5px; padding: 3px;">
        <table style="border-collapse: collapse;vertical-align: top;">
            <thead>
                <tr style="background-color: #e6e6e6;">
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        วันเดือนปี
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        เลขที่เอกสาร
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        ชื่อ-นามสกุล
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        มูลค่าสินค้า
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        ภาษีมูลค่าเพิ่ม
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        รวม
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        เงินสด
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        เงินโอน
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        เครดิต
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        Ai-Cash
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php

                      // $sTable =DB::select("
                      //       SELECT
                      //       db_orders.action_user,
                      //       ck_users_admin.`name` as action_user_name,
                      //       db_orders.pay_type_id_fk,
                      //       dataset_pay_type.detail AS pay_type,
                      //       date(db_orders.action_date) AS action_date,
                      //       db_orders.branch_id_fk,
                      //       branchs.b_name as branchs_name,
                      //       dataset_business_location.txt_desc as business_location_name,

                      //       SUM(CASE WHEN db_orders.sum_credit_price is null THEN 0 ELSE db_orders.sum_credit_price END) AS credit_price,
                      //       SUM(CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) AS transfer_price,
                      //       SUM(CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) AS fee_amt,
                      //       SUM(CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) AS aicash_price,
                      //       SUM(CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) AS cash_pay,
                      //       SUM(CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END) AS gift_voucher_price,

                      //       SUM(
                      //       (CASE WHEN db_orders.sum_credit_price is null THEN 0 ELSE db_orders.sum_credit_price END) +
                      //       (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
                      //       -- (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
                      //       (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
                      //       (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END)
                      //       -- (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
                      //       ) as total_price,

                      //       SUM(
                      //       CASE WHEN db_orders.shipping_free = 1 THEN 0 ELSE db_orders.shipping_price END
                      //       ) AS shipping_price

                      //       FROM
                      //       db_orders
                      //       Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                      //       Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                      //       Left Join branchs ON branchs.id = db_orders.branch_id_fk
                      //       Left Join dataset_business_location ON dataset_business_location.id = db_orders.business_location_id_fk
                      //       WHERE db_orders.approve_status not in (5) AND db_orders.check_press_save=2
                      //       $startDate
                      //       $endDate
                      //       $action_user
                      //       $business_location_id_fk
                      //       GROUP BY action_user
                      //       ORDER BY action_date ASC
                      // ");

                      // แบบไม่ sum
                      if($report_type == 'day'){
                        $sTable =DB::select("
                        SELECT
                        db_orders.action_user,
                        ck_users_admin.`name` as action_user_name,
                        db_orders.pay_type_id_fk,
                        dataset_pay_type.detail AS pay_type,
                        date(db_orders.action_date) AS action_date,
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
                        WHERE db_orders.approve_status not in (5) AND db_orders.check_press_save=2
                        $startDate
                        $endDate
                        $action_user
                        $business_location_id_fk
                        ORDER BY action_date ASC
                  ");
                      }else{
                        $sTable =DB::select("
                        SELECT
                        db_orders.action_user,
                        ck_users_admin.`name` as action_user_name,
                        ck_users_admin.`first_name` as action_first_name,
                        ck_users_admin.`last_name` as action_last_name,
                        db_orders.pay_type_id_fk,
                        dataset_pay_type.detail AS pay_type,
                        date_format(db_orders.action_date, '%M') AS action_date,
                        db_orders.branch_id_fk,
                        branchs.b_name as branchs_name,
                        dataset_business_location.txt_desc as business_location_name,

                        SUM(CASE WHEN db_orders.sum_credit_price is null THEN 0 ELSE db_orders.sum_credit_price END) AS sum_credit_price,
                        SUM(CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) AS transfer_price,
                        SUM(CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) AS fee_amt,
                        SUM(CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) AS aicash_price,
                        SUM(CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) AS cash_pay,
                        SUM(CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END) AS gift_voucher_price,
                        SUM(CASE WHEN db_orders.shipping_price is null THEN 0 ELSE db_orders.shipping_price END) AS shipping_price,
                        SUM(CASE WHEN db_orders.product_value is null THEN 0 ELSE db_orders.product_value END) AS product_value,
                        SUM(CASE WHEN db_orders.tax is null THEN 0 ELSE db_orders.tax END) AS tax,
                        SUM(CASE WHEN db_orders.sum_price is null THEN 0 ELSE db_orders.sum_price END) AS sum_price,

                        db_orders.code_order

                        FROM
                        db_orders
                        Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                        Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                        Left Join branchs ON branchs.id = db_orders.branch_id_fk
                        Left Join dataset_business_location ON dataset_business_location.id = db_orders.business_location_id_fk
                        WHERE db_orders.approve_status not in (5) AND db_orders.check_press_save=2
                        $startDate
                        $endDate
                        $action_user
                        $business_location_id_fk
                        GROUP BY action_user , date_format(db_orders.action_date, '%M')
                        ORDER BY action_date ASC
                  ");
                      }


              $p = "";
              $product_value_total = 0;
              $tax_total = 0;
              $sum_price_total = 0;
              $cash_pay_total = 0;
              $cash_transfer_price_total = 0;
              $cash_sum_credit_price_total = 0;
              $aicash_price_total = 0;

            foreach($sTable as $order){
              if($order->action_user_name == ''){
                $order->action_user_name = 'V3';
              }
              if($order->cash_pay == ''){
                $order->cash_pay = 0.00;
              }
              if($order->transfer_price == ''){
                $order->transfer_price = 0.00;
              }
              if($order->sum_credit_price == ''){
                $order->sum_credit_price = 0.00;
              }
              if($order->aicash_price == ''){
                $order->aicash_price = 0.00;
              }

              $product_value_total += $order->product_value;
              $tax_total += $order->tax;
              $sum_price_total += $order->sum_price;

              $cash_pay_total += $order->cash_pay;
              $cash_transfer_price_total += $order->transfer_price;
              $cash_sum_credit_price_total += $order->sum_credit_price;
              $aicash_price_total += $order->aicash_price;

              if($report_type == 'day'){
                $action_date = date('d/m/Y', strtotime($order->action_date));
              }else{
                $action_date = date('m/Y', strtotime($order->action_date));
              }

              if($report_type == 'day'){
                $code_order = $order->code_order;
              }else{
                $code_order = '-';
              }


              if($report_type == 'day'){
                $cus_name = $order->action_first_name.' '.$order->action_last_name;
              }else{
                $cus_name = '-';
              }


              $p.= '
              <tr>
              <td style="text-align: center;">'.$action_date.'</td>
              <td style="text-align: left;">'.$code_order.'</td>
              <td style="text-align: left;">'.$cus_name.'</td>
              <td style="text-align: right;">'.number_format($order->product_value,2,".",",").'</td>
              <td style="text-align: right;">'.number_format($order->tax,2,".",",").'</td>
              <td style="text-align: right;">'.number_format($order->sum_price,2,".",",").'</td>
              <td style="text-align: right;">'.number_format($order->cash_pay,2,".",",").'</td>
              <td style="text-align: right;">'.number_format($order->transfer_price,2,".",",").'</td>
              <td style="text-align: right;">'.number_format($order->sum_credit_price,2,".",",").'</td>
              <td style="text-align: right;">'.number_format($order->aicash_price,2,".",",").'</td>
          </tr>
              ';
            }
              echo $p;
              ?>

                <tr>
                    <td style="text-align: center;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($product_value_total,2,".",","); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($tax_total,2,".",","); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($sum_price_total,2,".",","); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($cash_pay_total,2,".",","); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($cash_transfer_price_total,2,".",","); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($cash_sum_credit_price_total,2,".",","); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($aicash_price_total,2,".",","); ?></td>

                </tr>

            </tbody>


        </table>
    </div>
    <?php
    if(count($sTable)>=8){
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
            <td style="text-align: right;"> <?php echo number_format($cash_pay_total,2,".",","); ?> &nbsp;</td>
            <td style="border-left: 1px solid #ccc;"> มูลค่าสินค้า &nbsp;</td>
            <td style="text-align: right;"> <?php echo number_format($product_value_total,2,".",","); ?> &nbsp;</td>
        </tr>

        <tr>
            <td style="border-left: 1px solid #ccc;"> เงินโอน &nbsp;</td>
            <td style="text-align: right;"> <?php echo number_format($cash_transfer_price_total,2,".",","); ?>&nbsp;
            </td>
            <td style="border-left: 1px solid #ccc;"> VAT 7% &nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($tax_total,2,".",","); ?> &nbsp;</td>
        </tr>

        <tr>
            <td style="border-left: 1px solid #ccc;"> เครดิต &nbsp;</td>
            <td style="text-align: right;"> <?php echo number_format($cash_sum_credit_price_total,2,".",","); ?> &nbsp;
            </td>
            <td style="border-left: 1px solid #ccc;"> <b><u>รวมยอดชำระ</u></b> &nbsp;</td>
            <td style="text-align: right;">
                <u style="text-align: right;">
                    <?php echo number_format($sum_price_total,2,".",","); ?></u>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td style="border-left: 1px solid #ccc;"> Ai-Cash &nbsp;</td>
            <td style="text-align: right;"> <?php echo number_format($aicash_price_total,2,".",","); ?> &nbsp;
            </td>

        </tr>

        <tr>
            <td style="border-left: 1px solid #ccc;"> <b><u>รวมยอดชำระทั้งสิ้น</u></b> </td>
            <td style="text-align: right;"><u style="text-align: right;">
                    <?php echo number_format($cash_pay_total+$cash_transfer_price_total+$cash_sum_credit_price_total+$aicash_price_total,2,".",","); ?></u>
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
