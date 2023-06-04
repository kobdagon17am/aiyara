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
                รายงานการขาย
                <br>
                <?php
                // if ($report_type == 'day') {
                //     // echo "ประจำวันที่ ".date('d/m/Y', strtotime($startDate2))." ถึง ".date('Y/m/d', strtotime($endDate2));
                //     echo 'ประจำวันที่ ' . date('d/m/Y', strtotime($startDate2));
                // } else {
                //     echo 'ประจำเดือน ' . date('m/Y', strtotime($startDate2));
                // }
                ?>
                <br>
            </th>
        </tr>
    </table>
</div>

<?php

// $sQuery = \DataTables::of($sTable);

//     return $sQuery
//     ->addColumn('column_001', function($row) {

// if(@$row->sender_id!='' && $row->remark==1){
//   $sD = DB::select(" select name from ck_users_admin where id=".$row->sender_id." ");
//    return @$sD[0]->name;
// }else{
//    return '';
// }
//     })
//     ->escapeColumns('column_001')

//     ->addColumn('column_002', function($row) {
// if($row->remark==1){
//   return @$row->time_sent;
// }else{
//    return '';
// }
//     })
//     ->escapeColumns('column_002')
//     ->addColumn('date_order', function($row) use($w05,$w052) {
//    if($row->remark==1){
// // วุฒิแก้ให้มันเลือกวันที่ได้
// $sDBSentMoneyDaily = DB::select("
//       SELECT
//       db_sent_money_daily.*,
//       ck_users_admin.`name` as sender
//       FROM
//       db_sent_money_daily
//       Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
//       WHERE db_sent_money_daily.id=".$row->id."
//       $w05
//       order by db_sent_money_daily.time_sent
// ");
//      $pn = '';
//       foreach(@$sDBSentMoneyDaily AS $r){
//                 $sOrders = DB::select("
//                       SELECT db_orders.invoice_code ,customers.prefix_name,customers.first_name,customers.last_name,date(db_orders.created_at) as date_order
//                       FROM
//                       db_orders Left Join customers ON db_orders.customers_id_fk = customers.id
//                       where sent_money_daily_id_fk in (".$r->id.")
//                       $w052
//                       GROUP BY date(db_orders.created_at);
//                   ");

//                   $i = 1;
//                   foreach ($sOrders as $key => $value) {
//                       $pn .=
//                         '
//                         <div class="divTableRow invoice_code_list ">
//                         <div class="divTableCell" style="text-align:center;">'.$value->date_order.' </div>
//                         </div>
//                         ';
//                       $i++;
//                      //  if($i==4){
//                      //   break;
//                      //  }

//                 }

//               }
//               return $pn;
//     }else{
//        return '';
//     }

//     })
//     ->escapeColumns('date_order')

//     ->addColumn('column_003', function($row) use($w05,$w052) {

//        if($row->remark==1){
//     // วุฒิแก้ให้มันเลือกวันที่ได้
//     $sDBSentMoneyDaily = DB::select("
//           SELECT
//           db_sent_money_daily.*,
//           ck_users_admin.`name` as sender
//           FROM
//           db_sent_money_daily
//           Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
//           WHERE db_sent_money_daily.id=".$row->id."
//           $w05
//           order by db_sent_money_daily.time_sent
//     ");

//          $pn = '';
//           foreach(@$sDBSentMoneyDaily AS $r){
//                     $sOrders = DB::select("
//                           SELECT db_orders.invoice_code ,customers.prefix_name,customers.first_name,customers.last_name,db_orders.id
//                           FROM
//                           db_orders Left Join customers ON db_orders.customers_id_fk = customers.id
//                           where sent_money_daily_id_fk in (".$r->id.") $w052;
//                       ");

//                       $i = 1;
//                       foreach ($sOrders as $key => $value) {

//                           $pn .=
//                             '
//                             <div class="divTableRow invoice_code_list ">
//                             <div class="divTableCell" style="text-align:center;"><a href="'.url('backend/frontstore/print_receipt_022/'.$value->id).'" target="blank">'. $value->invoice_code.'</a></div>
//                             </div>
//                             ';
//                           $i++;

//                     }

//                                $arr = [];
//                         foreach ($sOrders as $key => $value) {
//                             array_push($arr,$value->invoice_code.' :'.(@$value->first_name.' '.@$value->last_name).'<br>');
//                           }
//                       $arr_inv = implode(",",$arr);

//                        $pn .=
//                         '<input type="hidden" class="arr_inv" value="'.$arr_inv.'">
//                         ';
//                   }

//                 if($row->status_cancel==1){
//                    return " - ";
//                 }else{
//                    return $pn;
//                 }

//         }else{
//            return '';
//         }

//     })
//     ->escapeColumns('column_003')

//     ->addColumn('column_004', function($row) {
// if($row->remark==1){
//    return $row->created_at;
//   }else{
//      return '';
//   }
//     })
//     ->addColumn('approver', function($row) {
//  $name = '';
// if($row->approver!=0){
//     $app_name = DB::table('ck_users_admin')->select('name')->where('id',$row->approver)->first();
//     $name = $app_name->name;
// }
// return $name;
//      })
//      ->addColumn('approver_time', function($row) {
//  $date_data = '';
// if($row->approve_date!=''){
//     $date_data = $row->approve_date;
// }
// return $date_data;
//      })
//     ->escapeColumns('column_004')

//     ->addColumn('column_005', function($row) use($w05,$w05_order,$w052) {

//       if($row)

//        if($row->remark==1){

//        $sDBSentMoneyDaily = DB::select("
//        SELECT
//        db_sent_money_daily.*,
//        ck_users_admin.`name` as sender
//        FROM
//        db_sent_money_daily
//        Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
//        WHERE  db_sent_money_daily.id=".$row->id."
//        $w05
//        order by db_sent_money_daily.time_sent
//  ");

//         $arr = [];

//            foreach(@$sDBSentMoneyDaily AS $r){
//                     $sOrders = DB::select("
//                           SELECT *
//                           FROM
//                           db_orders where sent_money_daily_id_fk in (".$r->id.") $w052;
//                       ");

//                       foreach ($sOrders as $key => $value) {
//                          array_push($arr,$value->id);
//                     }
//                     }
//                     $id = implode(',',$arr);

//                      $pn =  '';

//         if($id){
//           $sDBFrontstoreSumCostActionUser = DB::select("
//               SELECT
//               db_orders.action_user,
//               ck_users_admin.`name` as action_user_name,
//               db_orders.pay_type_id_fk,
//               dataset_pay_type.detail AS pay_type,
//               date(db_orders.created_at) AS action_date,
//               sum(db_orders.cash_pay) as cash_pay,
//               sum(db_orders.credit_price) as credit_price,
//               sum(db_orders.transfer_price) as transfer_price,
//               sum(db_orders.aicash_price) as aicash_price,
//               sum(db_orders.shipping_price) as shipping_price,
//               sum(db_orders.fee_amt) as fee_amt,
//               sum(db_orders.sum_credit_price) as sum_credit_price,
//               sum(db_orders.cash_pay+db_orders.credit_price+db_orders.transfer_price+db_orders.aicash_price) as total_price
//               FROM
//               db_orders
//               Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
//               Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
//               WHERE db_orders.pay_type_id_fk<>0 AND db_orders.id in ($id)

//               AND db_orders.business_location_id_fk in(".$row->business_location.")
//               GROUP BY action_user
//          ");

//          $pn .=   ' <div class="table-responsive">
//                 <table class="table table-sm m-0">
//                   <thead>
//                     <tr>
//                       <th>พนักงานขาย</th>
//                       <th class="text-right">เงินสด</th>
//                       <th class="text-right">เงินโอน</th>
//                       <th class="text-right">เครดิต</th>
//                       <th class="text-right">Ai-cash</th>
//                     </tr>
//                   </thead>

//                       <tbody>';

//                 $cash_pay_total = 0;
//               foreach(@$sDBFrontstoreSumCostActionUser AS $r){
//                 $cash_pay_total+=$r->cash_pay + $r->transfer_price+ $r->sum_credit_price+ $r->aicash_price;
//                    @$cnt_row1 += 1;
//                             @$sum_cash_pay += $r->cash_pay;
//                             @$sum_aicash_price += $r->aicash_price;
//                             @$sum_transfer_price += $r->transfer_price;
//                             @$sum_credit_price += $r->sum_credit_price;
//                             @$sum_shipping_price += $r->shipping_price;
//                             @$sum_fee_amt += $r->fee_amt;
//                             @$sum_total_price += $r->total_price;

//                             $pn .=
//                               '<tr>
//                               <td>'.$r->action_user_name.'</td>
//                               <td class="text-right"> '.number_format($r->cash_pay,2).' </td>
//                               <td class="text-right"> '.number_format($r->transfer_price,2).' </td>
//                               <td class="text-right"> '.number_format($r->sum_credit_price,2).' </td>
//                               <td class="text-right"> '.number_format($r->aicash_price,2).' </td>
//                              </tr>';
//               }

//               $pn .= '<tr>
//               <td class="text-right" colspan="4"><b>รวมทั้งสิ้น</b></td>
//               <td class="text-right">'.number_format($cash_pay_total,2).'</td>
//               </tr>';

//               $pn .=   '
//                 </tbody>
//                 </table>
//               </div>';
//             }

//             if($row->status_cancel==1){
//                return "<span style='color:red;'>* รายการนี้ได้ทำการยกเลิกการส่งเงิน</span>";
//             }else{
//                return $pn;
//             }

//        }
//     })
//     ->escapeColumns('column_005')

//     ->addColumn('column_006', function($row) {
//         if($row->remark==1){
//             if($row->status_cancel==1){
//                return "display:none;";
//             }else{
//                return  $row->id ;
//             }
//         }else{
//            return  "display:none;" ;
//         }
//     })
//     ->escapeColumns('column_006')
//     ->addColumn('column_007', function($row) {

// if($row->remark==1){

//         if($row->status_approve==1){
//            return "<span style='color:green;'>รับเงินแล้ว</span>";
//         }elseif($row->status_approve==2){
//           return "<span style='color:red;'>ไม่อนุมัติ</span>";
//        }else{
//            return  "-" ;
//         }

//   }else{
//      return  "" ;
//   }

//     })
//     ->addColumn('detail', function($row) {
//        return @$row->detail;
//      })
//     ->escapeColumns('column_007')
//     ->addColumn('sum_total_price', function($row) use($w05,$w05_order) {

//      if($row->remark==1){

//               $sDBSentMoneyDaily = DB::select("
//                     SELECT
//                     db_sent_money_daily.*,
//                     ck_users_admin.`name` as sender
//                     FROM
//                     db_sent_money_daily
//                     Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
//                     WHERE db_sent_money_daily.id=".$row->id."
//                     $w05
//                     order by db_sent_money_daily.time_sent
//               ");
//               $arr = [];

//                  foreach(@$sDBSentMoneyDaily AS $r){
//                           $sOrders = DB::select("
//                                 SELECT *
//                                 FROM
//                                 db_orders where sent_money_daily_id_fk in (".$r->id.");
//                             ");

//                             foreach ($sOrders as $key => $value) {
//                                array_push($arr,$value->id);
//                           }
//                           }
//                           $id = implode(',',$arr);

//                           if($id){

//                             $sDBFrontstoreSumCostActionUser = DB::select("
//                             SELECT
//                             db_orders.action_user,
//                             ck_users_admin.`name` as action_user_name,
//                             db_orders.pay_type_id_fk,
//                             dataset_pay_type.detail AS pay_type,
//                             date(db_orders.created_at) AS action_date,
//                             sum(db_orders.cash_pay) as cash_pay,
//                             sum(db_orders.cash_pay) as total_price
//                             FROM
//                             db_orders
//                             Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
//                             Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
//                             WHERE db_orders.pay_type_id_fk<>0 AND db_orders.id in ($id)
//                             $w05_order
//                             AND db_orders.business_location_id_fk in(".$row->business_location.")
//                             GROUP BY action_user
//                        ");
//                 return number_format(@$sDBFrontstoreSumCostActionUser[0]->total_price,2);
//                   }

//         }else{
//            return  '' ;
//         }

//     })
//     ->escapeColumns('sum_total_price')
//     ->make(true);
?>


<div class="NameAndAddress">
    <div style="bo  rder-radius: 5px; padding: 3px;">
        <table style="border-collapse: collapse;">
            <thead>
                <!-- <tr style="background-color: #e6e6e6;"> -->
                <tr>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        ผู้ส่ง
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        ครั้งที่ส่ง
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        วันที่รายการ
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        วันเวลาที่ส่ง
                    </td>

                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        สถานะ
                    </td>

                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        ผู้รับเงิน
                    </td>

                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        เวลารับเงิน
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        หมายเหตุ
                    </td>

                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="1" colspan="5">
                        รวมเงิน
                    </td>
                </tr>

                <tr style="">
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
                        Ai-cash
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        รวม
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php

                $col_5_total = 0;
                $col_6_total = 0;
                $col_7_total = 0;
                $col_8_total = 0;
                $col_9_total = 0;

                foreach ($sTable as $key => $row) {
                    $col_1 = '';
                    $col_2 = '';
                    $col_3 = '';
                    $col_4 = '';
                    $col_5 = '';
                    $col_6 = '';
                    $col_7 = '';
                    $col_8 = '';
                    $col_9 = '';
                    $col_10 = '';
                    $col_11 = '';
                    $col_12 = '';
                    $col_13 = '';
                    $td = '<tr>';

                    // ผู้ส่ง
                    if (@$row->sender_id != '' && $row->remark == 1) {
                        $sD = DB::select(' select name from ck_users_admin where id=' . $row->sender_id . ' ');
                        $col_1 = @$sD[0]->name;
                    }

                    // ครั้งที่ส่ง
                    if ($row->remark == 1) {
                        $col_2 = @$row->time_sent;
                    }

                    // วันที่รายการ
                    if ($row->remark == 1) {
                        $sDBSentMoneyDaily = DB::select(
                            "
                                                                          SELECT
                                                                          db_sent_money_daily.*,
                                                                          ck_users_admin.`name` as sender
                                                                          FROM
                                                                          db_sent_money_daily
                                                                          Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
                                                                          WHERE db_sent_money_daily.id=" .
                                $row->id .
                                "
                                                                          $w05
                                                                          order by db_sent_money_daily.time_sent
                                                                         ",
                        );
                        $pn = '';
                        foreach (@$sDBSentMoneyDaily as $r) {
                            $sOrders = DB::select(
                                "
                                                                                          SELECT db_orders.invoice_code ,customers.prefix_name,customers.first_name,customers.last_name,date(db_orders.created_at) as date_order
                                                                                          FROM
                                                                                          db_orders Left Join customers ON db_orders.customers_id_fk = customers.id
                                                                                          where sent_money_daily_id_fk in (" .
                                    $r->id .
                                    ")
                                                                                          $w052
                                                                                          GROUP BY date(db_orders.created_at);
                                                                                      ",
                            );
                            $i = 1;
                            foreach ($sOrders as $key => $value) {
                                $pn .=
                                    '
                                                                                            <div class="divTableRow invoice_code_list ">
                                                                                            <div class="divTableCell" style="text-align:center;">' .
                                    date('d/m/Y', strtotime($value->date_order)) .
                                    ' </div>
                                                                                            </div>
                                                                                            ';
                                $i++;
                            }
                        }
                        $col_3 = $pn;
                    }

                    // วันเวลาที่ส่ง
                    if ($row->remark == 1) {
                        $col_4 = date('d/m/Y H:i:s', strtotime($row->created_at));
                    }

                    // รวมเงิน
                    if ($row) {
                        if ($row->remark == 1) {
                            $sDBSentMoneyDaily = DB::select(
                                "
                                                                SELECT
                                                                db_sent_money_daily.*,
                                                                ck_users_admin.`name` as sender
                                                                FROM
                                                                db_sent_money_daily
                                                                Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
                                                                WHERE  db_sent_money_daily.id=" .
                                    $row->id .
                                    "
                                                                $w05
                                                                order by db_sent_money_daily.time_sent
                                                                ",
                            );

                            $arr = [];

                            foreach (@$sDBSentMoneyDaily as $r) {
                                $sOrders = DB::select(
                                    "
                                                                   SELECT *
                                                                   FROM
                                                                   db_orders where sent_money_daily_id_fk in (" .
                                        $r->id .
                                        ") $w052;
                                                               ",
                                );

                                foreach ($sOrders as $key => $value) {
                                    array_push($arr, $value->id);
                                }
                            }
                            $id = implode(',', $arr);

                            $pn = '';

                            if ($id) {
                                $sDBFrontstoreSumCostActionUser = DB::select(
                                    "
                                                       SELECT
                                                       db_orders.action_user,
                                                       ck_users_admin.`name` as action_user_name,
                                                       db_orders.pay_type_id_fk,
                                                       dataset_pay_type.detail AS pay_type,
                                                       date(db_orders.created_at) AS action_date,
                                                       sum(db_orders.cash_pay) as cash_pay,
                                                       sum(db_orders.credit_price) as credit_price,
                                                       sum(db_orders.transfer_price) as transfer_price,
                                                       sum(db_orders.aicash_price) as aicash_price,
                                                       sum(db_orders.shipping_price) as shipping_price,
                                                       sum(db_orders.fee_amt) as fee_amt,
                                                       sum(db_orders.sum_credit_price) as sum_credit_price,
                                                       sum(db_orders.cash_pay+db_orders.credit_price+db_orders.transfer_price+db_orders.aicash_price) as total_price
                                                       FROM
                                                       db_orders
                                                       Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                                                       Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                                                       WHERE db_orders.pay_type_id_fk<>0 AND db_orders.id in ($id)

                                                       AND db_orders.business_location_id_fk in(" .
                                        $row->business_location .
                                        ")
                                                       GROUP BY action_user
                                                  ",
                                );
                                $cash_pay_total = 0;
                                foreach (@$sDBFrontstoreSumCostActionUser as $r) {
                                    $cash_pay_total += $r->cash_pay + $r->transfer_price + $r->sum_credit_price + $r->aicash_price;
                                    @$cnt_row1 += 1;
                                    @$sum_cash_pay += $r->cash_pay;
                                    @$sum_aicash_price += $r->aicash_price;
                                    @$sum_transfer_price += $r->transfer_price;
                                    @$sum_credit_price += $r->sum_credit_price;
                                    @$sum_shipping_price += $r->shipping_price;
                                    @$sum_fee_amt += $r->fee_amt;
                                    @$sum_total_price += $r->total_price;

                                    $col_5 = number_format($r->cash_pay, 2);
                                    $col_6 = number_format($r->transfer_price, 2);
                                    $col_7 = number_format($r->sum_credit_price, 2);
                                    $col_8 = number_format($r->aicash_price, 2);
                                    $col_9 = number_format($r->cash_pay + $r->transfer_price + $r->sum_credit_price + $r->aicash_price, 2);

                                    $col_5_total += $r->cash_pay;
                                    $col_6_total += $r->transfer_price;
                                    $col_7_total += $r->sum_credit_price;
                                    $col_8_total += $r->aicash_price;
                                    $col_9_total += $r->cash_pay + $r->transfer_price + $r->sum_credit_price + $r->aicash_price;
                                }
                            }
                        }
                    }

                    // สถานะ
                    if ($row->remark == 1) {
                        if ($row->status_approve == 1) {
                            $col_10 = "<span style='color:green;'>รับเงินแล้ว</span>";
                        } elseif ($row->status_approve == 2) {
                            $col_10 = "<span style='color:red;'>ไม่อนุมัติ</span>";
                        } else {
                            $col_10 = '-';
                        }
                    } else {
                        $col_10 = '';
                    }

                    // ผู้รับเงิน
                    if ($row->approver != 0) {
                        $app_name = DB::table('ck_users_admin')
                            ->select('name')
                            ->where('id', $row->approver)
                            ->first();
                        $col_11 = $app_name->name;
                    }

                    // เวลารับเงิน
                    if ($row->approve_date != '') {
                        $col_12 = date('d/m/Y H:i:s', strtotime($row->approve_date));
                    }

                    // หมายเหตุ
                    $col_13 = $row->detail;

                    $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: left;">' . $col_1 . '</td>';
                    $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: center;">' . $col_2 . '</td>';
                    $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: center;">' . $col_3 . '</td>';
                    $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: center;">' . $col_4 . '</td>';

                    $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: center;">' . $col_10 . '</td>';
                    $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: left;">' . $col_11 . '</td>';
                    $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: center;">' . $col_12 . '</td>';
                    $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: left;">' . $col_13 . '</td>';

                    $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: right;">' . $col_5 . '</td>';
                    $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: right;">' . $col_6 . '</td>';
                    $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: right;">' . $col_7 . '</td>';
                    $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: right;">' . $col_8 . '</td>';
                    $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: right;">' . $col_9 . '</td>';

                    echo $td . '</tr>';
                }

                $td = '<tr>';

                $td .= '<td style="border-bottom: 0px double #000;border-left: 0px solid #ccc;text-align: right; font-weight: bold;" colspan="8"></td>';
                $td .= '<td style="border-bottom: 3px double #000;border-left: 0px solid #ccc;text-align: right; font-weight: bold;">' . number_format($col_5_total, 2) . '</td>';
                $td .= '<td style="border-bottom: 3px double #000;border-left: 0px solid #ccc;text-align: right; font-weight: bold;">' . number_format($col_6_total, 2) . '</td>';
                $td .= '<td style="border-bottom: 3px double #000;border-left: 0px solid #ccc;text-align: right; font-weight: bold;">' . number_format($col_7_total, 2) . '</td>';
                $td .= '<td style="border-bottom: 3px double #000;border-left: 0px solid #ccc;text-align: right; font-weight: bold;">' . number_format($col_8_total, 2) . '</td>';
                $td .= '<td style="border-bottom: 3px double #000;border-left: 0px solid #ccc;text-align: right; font-weight: bold;">' . number_format($col_9_total, 2) . '</td>';

                echo $td . '</tr>';
                ?>

            </tbody>


        </table>
    </div>

</div>
