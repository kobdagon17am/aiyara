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

<?php
$td_data = 2;
$row_num = 0;
$i_num = 1;
$branch_data = DB::table('branchs')
    ->select('b_name')
    ->where('id', $r->branch_id_fk)
    ->first();
if ($branch_data) {
    $b_name = $branch_data->b_name;
} else {
    $b_name = 'ทั้งหมด';
}

$branch_data = DB::table('branchs')
    ->select('b_name')
    ->where('id', $r->branch_id_fk)
    ->first();
if ($branch_data) {
    $b_name = $branch_data->b_name;
} else {
    $b_name = 'ทั้งหมด';
}
?>

<div class="NameAndAddress" style="">
    <table style="border-collapse: collapse;">
        <tr>
            <th style="font-size: 18px;">
                <?php
                echo 'ศูนย์ธุรกิจ ' . @$b_name . ' รายงานยอดขายสินค้ารายวัน';
                ?>
                <br>
                <?php

                echo 'ประจำวันที่ ' . date('d/m/Y', strtotime($r->startDate_data)) . ' ถึง ' . date('d/m/Y', strtotime($r->endDate_data));

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
                        <b>ลำดับ</b>
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        <b>รายการ</b>
                    </td>

                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        <b>จำนวน</b>
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        <b>ผู้ขาย</b>
                    </td>
                </tr>

                <tr style="">
                </tr>
            </thead>

            <tbody>

                <?php

                if ($r->branch_id_fk == '' || $r->branch_id_fk == null) {
                    $action_user = DB::table('db_orders')
                        ->select('action_user')
                        ->where('db_orders.approve_date', '>=', $r->startDate_data)
                        ->where('db_orders.approve_date', '<=', $r->endDate_data)
                        ->where('business_location_id_fk', '=', $r->business_location_id_fk)
                        // ->where('branch_id_fk', "=", $r->branch_id_fk)
                        ->whereNotIn('db_orders.approve_status', [1, 3, 5, 6])
                        ->groupBy('action_user')
                        ->get();
                } else {
                    $action_user = DB::table('db_orders')
                        ->select('action_user')
                        ->where('db_orders.approve_date', '>=', $r->startDate_data)
                        ->where('db_orders.approve_date', '<=', $r->endDate_data)
                        ->where('business_location_id_fk', '=', $r->business_location_id_fk)
                        ->where('branch_id_fk', '=', $r->branch_id_fk)
                        ->whereNotIn('db_orders.approve_status', [1, 3, 5, 6])
                        ->groupBy('action_user')
                        ->get();
                }

                $promotion_data = DB::table('promotions')
                    ->select('id', 'name_thai', 'pcode')
                    // ->where('db_orders.approve_date','>=',$r->startDate_data)
                    // ->where('db_orders.approve_date','<=',$r->endDate_data)
                    ->where('business_location', '=', $r->business_location_id_fk)
                    ->get();
                // dd($promotion_data);
                $promotion_data_arr = [];
                foreach ($promotion_data as $pro_data) {
                    $promotion_data_arr[$pro_data->id] = [
                        'id' => $pro_data->id,
                        'name_thai' => $pro_data->name_thai,
                        'pcode' => $pro_data->pcode,
                    ];
                }
                $row_num = 0;
                $arr_product_total = [];
                $arr_product_amt_total = [];
                $arr_promotion_total = [];
                $arr_promotion_amt_total = [];
                foreach ($action_user as $ac_key => $ac) {
                    if ($r->branch_id_fk == '' || $r->branch_id_fk == null) {
                        $orders = DB::table('db_orders')
                            ->select('id')
                            // ->select('db_orders.id','db_orders.code_order','db_orders.invoice_code_id_fk','db_orders.approve_date','db_orders.customers_sent_id_fk','customers.user_name','customers.first_name','customers.last_name')
                            // ->join('customers','customers.id','db_orders.customers_id_fk')
                            ->where('db_orders.approve_date', '>=', $r->startDate_data)
                            ->where('db_orders.approve_date', '<=', $r->endDate_data)
                            ->where('business_location_id_fk', '=', $r->business_location_id_fk)
                            // ->where('branch_id_fk', "=", $r->branch_id_fk)
                            // ->where('db_orders.delivery_location_frontend','!=','sent_office')
                            ->whereNotIn('db_orders.approve_status', [1, 3, 5, 6])
                            ->where('action_user', '=', $ac->action_user)
                            // ->get();
                            ->pluck('id');
                    } else {
                        $orders = DB::table('db_orders')
                            ->select('id')
                            // ->select('db_orders.id','db_orders.code_order','db_orders.invoice_code_id_fk','db_orders.approve_date','db_orders.customers_sent_id_fk','customers.user_name','customers.first_name','customers.last_name')
                            // ->join('customers','customers.id','db_orders.customers_id_fk')
                            ->where('db_orders.approve_date', '>=', $r->startDate_data)
                            ->where('db_orders.approve_date', '<=', $r->endDate_data)
                            ->where('business_location_id_fk', '=', $r->business_location_id_fk)
                            ->where('branch_id_fk', '=', $r->branch_id_fk)
                            // ->where('db_orders.delivery_location_frontend','!=','sent_office')
                            ->whereNotIn('db_orders.approve_status', [1, 3, 5, 6])
                            ->where('action_user', '=', $ac->action_user)
                            // ->get();
                            ->pluck('id');
                    }

                    $pro_list = DB::table('db_order_products_list')
                        ->select('type_product', 'promotion_id_fk', 'giveaway_id_fk', 'promotion_code', 'amt', 'product_name', 'product_id_fk', 'frontstore_id_fk', 'id')
                        ->whereIn('frontstore_id_fk', $orders)
                        ->get();

                    $arr_product = [];
                    $arr_product_amt = [];

                    $arr_promotion = [];
                    $arr_promotion_amt = [];

                    $arr_course = [];
                    $arr_course_amt = [];

                    $arr_giveaway = [];
                    $arr_giveaway_amt = [];

                    foreach ($pro_list as $pro) {
                        if ($pro->type_product == 'product') {
                            $arr_product[$pro->product_id_fk] = [
                                'product_name' => $pro->product_name,
                            ];
                            if (isset($arr_product_amt[$pro->product_id_fk])) {
                                $arr_product_amt[$pro->product_id_fk] = $arr_product_amt[$pro->product_id_fk] + $pro->amt;
                            } else {
                                $arr_product_amt[$pro->product_id_fk] = $pro->amt;
                            }

                            $arr_product_total[$pro->product_id_fk] = [
                                'product_name' => $pro->product_name,
                            ];
                            if (isset($arr_product_amt_total[$pro->product_id_fk])) {
                                $arr_product_amt_total[$pro->product_id_fk] = $arr_product_amt_total[$pro->product_id_fk] + $pro->amt;
                            } else {
                                $arr_product_amt_total[$pro->product_id_fk] = $pro->amt;
                            }
                        } elseif ($pro->type_product == 'promotion') {
                            // if($pro->id==52812){
                            //   dd($pro_data->id);
                            // }

                            $arr_promotion[$pro->promotion_id_fk] = [
                                'product_name' => $promotion_data_arr[$pro->promotion_id_fk]['pcode'] . ' : ' . $promotion_data_arr[$pro->promotion_id_fk]['name_thai'],
                            ];
                            if (isset($arr_promotion_amt[$pro->promotion_id_fk])) {
                                $arr_promotion_amt[$pro->promotion_id_fk] = $arr_promotion_amt[$pro->promotion_id_fk] + $pro->amt;
                            } else {
                                $arr_promotion_amt[$pro->promotion_id_fk] = $pro->amt;
                            }

                            $arr_promotion_total[$pro->promotion_id_fk] = [
                                'product_name' => $promotion_data_arr[$pro->promotion_id_fk]['pcode'] . ' : ' . $promotion_data_arr[$pro->promotion_id_fk]['name_thai'],
                            ];
                            if (isset($arr_promotion_amt_total[$pro->promotion_id_fk])) {
                                $arr_promotion_amt_total[$pro->promotion_id_fk] = $arr_promotion_amt_total[$pro->promotion_id_fk] + $pro->amt;
                            } else {
                                $arr_promotion_amt_total[$pro->promotion_id_fk] = $pro->amt;
                            }
                        } elseif ($pro->type_product == 'course') {
                        } elseif ($pro->type_product == 'giveaway') {
                            // $arr_giveaway[$pro->giveaway_id_fk] = [
                            //   'product_name' => $pro->product_name,
                            // ];
                            // if(isset($arr_giveaway_amt[$pro->giveaway_id_fk])){
                            //   $arr_giveaway_amt[$pro->giveaway_id_fk] = $arr_giveaway_amt[$pro->giveaway_id_fk]+$pro->amt;
                            // }else{
                            //   $arr_giveaway_amt[$pro->giveaway_id_fk] = $pro->amt;
                            // }
                        }
                    }

                    if ($ac->action_user != 0) {
                        $user_data = DB::table('ck_users_admin')
                            ->select('name')
                            ->where('id', $ac->action_user)
                            ->first();
                        if ($user_data) {
                            $ac_name = $user_data->name;
                        } else {
                            $ac_name = '';
                        }
                    } else {
                        $ac_name = 'V3';
                    }

                    $row_num--;
                    // ($td_data+$row_num+$ac_key).' / '.$td_data.' + '.$row_num.' + '.$ac_key);
                    // ($td_data+$row_num+$ac_key)-2);

                    foreach ($arr_product as $key => $arr_pro) {
                        $tr = '<tr>' . "<td style='text-align: center; border-bottom: 0px solid #000; '>" . $i_num . '</td>' . "<td style='text-align: left; border-bottom: 0px solid #000; '>" . $arr_pro['product_name'] . '</td>' . "<td style='text-align: center; border-bottom: 0px solid #000; '>" . $arr_product_amt[$key] . '</td>' . "<td style='text-align: left; border-bottom: 0px solid #000; '>" . $ac_name . '</td>' . '</tr>';
                        echo $tr;
                        // $sheet->setCellValue('A'.($td_data+$row_num+$ac_key), ($td_data+$row_num+$ac_key)-2);
                        // $sheet->setCellValue('B'.($td_data+$row_num+$ac_key), $arr_pro['product_name']);
                        // $sheet->setCellValue('C'.($td_data+$row_num+$ac_key), $arr_product_amt[$key]);
                        // $sheet->setCellValue('D'.($td_data+$row_num+$ac_key), $ac_name);
                        $row_num++;
                        $i_num++;
                    }

                    foreach ($arr_promotion as $key => $arr_pro) {
                        // $sheet->setCellValue('A'.($td_data+$row_num+$ac_key), ($td_data+$row_num+$ac_key)-2);
                        // $sheet->setCellValue('B'.($td_data+$row_num+$ac_key), $arr_pro['product_name']);
                        // $sheet->setCellValue('C'.($td_data+$row_num+$ac_key), $arr_promotion_amt[$key]);
                        // $sheet->setCellValue('D'.($td_data+$row_num+$ac_key), $ac_name);
                        $tr = '<tr>' . "<td style='text-align: center; border-bottom: 0px solid #000; '>" . $i_num . '</td>' . "<td style='text-align: left; border-bottom: 0px solid #000; '>" . $arr_pro['product_name'] . '</td>' . "<td style='text-align: center; border-bottom: 0px solid #000; '>" . $arr_promotion_amt[$key] . '</td>' . "<td style='text-align: left; border-bottom: 0px solid #000; '>" . $ac_name . '</td>' . '</tr>';
                        echo $tr;
                        $row_num++;
                        $i_num++;
                    }

                    // foreach($arr_giveaway as $key => $arr_pro){
                    //   $sheet->setCellValue('A'.($td_data+$row_num+$ac_key), '');
                    //   $sheet->setCellValue('B'.($td_data+$row_num+$ac_key), $arr_pro['product_name']);
                    //   $sheet->setCellValue('C'.($td_data+$row_num+$ac_key), $arr_giveaway_amt[$key]);
                    //   $sheet->setCellValue('D'.($td_data+$row_num+$ac_key), $ac_name);
                    //   $row_num++;
                    // }
                }

                ?>
            </tbody>

        </table>

        <br>
        <table>
          <thead>

            <tr>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                    rowspan="2">
                    <b>ลำดับ</b>
                </td>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                    rowspan="2">
                    <b>สรุปรายการสินค้าที่ขายทั้งหมด</b>
                </td>

                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                    rowspan="2">
                    <b>จำนวน</b>
                </td>
                {{-- <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                    rowspan="2">

                </td> --}}
            </tr>

            <tr style="">
            </tr>

        </thead>

        <tbody>
            <?php
            $i_new = 1;
            foreach ($arr_product_total as $key => $arr_pro) {
                $tr = '<tr>' . "<td style='text-align: center; border-bottom: 0px solid #000; '>" . $i_new . '</td>' . "<td style='text-align: left; border-bottom: 0px solid #000; '>" . $arr_pro['product_name'] . '</td>' . "<td style='text-align: center; border-bottom: 0px solid #000; '>" . $arr_product_amt_total[$key] . '</td>' . "<td style='text-align: left; border-bottom: 0px solid #000; '>" . '' . '</td>' . '</tr>';
                echo $tr;
                $i_new++;
                // $sheet->setCellValue('A' . ($td_data + $row_num + $ac_key_total), $row_num_total);
                // $sheet->setCellValue('B' . ($td_data + $row_num + $ac_key_total), $arr_pro['product_name']);
                // $sheet->setCellValue('C' . ($td_data + $row_num + $ac_key_total), $arr_product_amt_total[$key]);
                // $sheet->setCellValue('D' . ($td_data + $row_num + $ac_key_total), '');
                // $row_num++;
                // $row_num_total++;
            }
            ?>
        </tbody>

        <thead>

            <tr>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                    rowspan="2">

                    <b>ลำดับ</b>
                </td>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                    rowspan="2">

                    <b>สรุปรายการโปรโมชั่นที่ขายทั้งหมด</b>
                </td>

                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                    rowspan="2">

                    <b>จำนวน</b>
                </td>
                {{-- <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                    rowspan="2">

                </td> --}}
            </tr>

            <tr style="">
            </tr>

        </thead>

        <tbody>
            <?php
            $row_num_total2 = 1;
            foreach ($arr_promotion_total as $key => $arr_pro) {
                // $arr_promotion_id = array_push($a,$key);
                // $sheet->setCellValue('A'.($td_data+$row_num+$ac_key_total+1), $row_num_total2);
                // $sheet->setCellValue('B'.($td_data+$row_num+$ac_key_total+1), $arr_pro['product_name']);
                // $sheet->setCellValue('C'.($td_data+$row_num+$ac_key_total+1), $arr_promotion_amt_total[$key]);
                // $sheet->setCellValue('D'.($td_data+$row_num+$ac_key_total+1), '');
                $tr = '<tr>' . "<td style='text-align: center; border-bottom: 0px solid #000; '>" . $row_num_total2 . '</td>' . "<td style='text-align: left; border-bottom: 0px solid #000; '>" . $arr_pro['product_name'] . '</td>' . "<td style='text-align: center; border-bottom: 0px solid #000; '>" . $arr_promotion_amt_total[$key] . '</td>' . "<td style='text-align: left; border-bottom: 0px solid #000; '>" . '' . '</td>' . '</tr>';
                echo $tr;

                // เด่วทำต่อ
                $p_products = DB::table('promotions_products')
                    ->select('product_amt', 'product_id_fk', 'promotion_id_fk','product_amt')
                    ->where('promotion_id_fk', $key)
                    ->get();

                foreach ($p_products as $p_p) {
                    $p_p_data = DB::table('products')
                        ->select('product_code')
                        ->where('id', $p_p->product_id_fk)
                        ->first();
                    if ($p_p_data) {
                        $p_p_detail = DB::table('products_details')
                            ->select('product_name','product_id_fk')
                            ->where('product_id_fk', $p_p->product_id_fk)
                            ->where('lang_id',1)
                            ->first();

                            $p_p_code = DB::table('products')
                            ->select('product_code')
                            ->where('id', $p_p->product_id_fk)
                            ->first();

                        if ($p_p_detail) {
                            $arr_product_total[$p_p->product_id_fk] = [
                                'product_name' => $p_p_code->product_code.' : '.$p_p_detail->product_name,
                            ];
                            if (isset($arr_product_amt_total[$p_p_detail->product_id_fk])) {
                                $arr_product_amt_total[$p_p_detail->product_id_fk] = $arr_product_amt_total[$p_p_detail->product_id_fk] + ($p_p->product_amt*$arr_promotion_amt_total[$key]);
                            } else {
                                $arr_product_amt_total[$p_p_detail->product_id_fk] = ($p_p->product_amt*$arr_promotion_amt_total[$key]);
                            }
                        }
                    }
                }

                $row_num++;
                $row_num_total2++;
            }

            ?>
        </tbody>

        <thead>

            <tr>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                    rowspan="2">
                    <b>ลำดับ</b>
                </td>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                    rowspan="2">
                    <b>สรุปรายการสินค้าทั้งหมด และรวมสินค้าในโปรชั่น</b>
                </td>

                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                    rowspan="2">
                    <b>จำนวน</b>

                </td>
                {{-- <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                    rowspan="2">

                </td> --}}
            </tr>

            <tr style="">
            </tr>

        </thead>

        <tbody>
            <?php
            $i_new = 1;
            foreach ($arr_product_total as $key => $arr_pro) {
                if ($arr_pro['product_name'] != '') {
                    $tr = '<tr>' . "<td style='text-align: center; border-bottom: 0px solid #000; '>" . $i_new . '</td>' . "<td style='text-align: left; border-bottom: 0px solid #000; '>" . $arr_pro['product_name'] . '</td>' . "<td style='text-align: center; border-bottom: 0px solid #000; '>" . $arr_product_amt_total[$key] . '</td>' . "<td style='text-align: left; border-bottom: 0px solid #000; '>" . '' . '</td>' . '</tr>';
                    echo $tr;
                    $i_new++;
                    // $sheet->setCellValue('A' . ($td_data + $row_num + $ac_key_total), $row_num_total);
                    // $sheet->setCellValue('B' . ($td_data + $row_num + $ac_key_total), $arr_pro['product_name']);
                    // $sheet->setCellValue('C' . ($td_data + $row_num + $ac_key_total), $arr_product_amt_total[$key]);
                    // $sheet->setCellValue('D' . ($td_data + $row_num + $ac_key_total), '');
                    // $row_num++;
                    // $row_num_total++;
                }
            }

            ?>
        </tbody>
        </table>

    </div>

</div>
