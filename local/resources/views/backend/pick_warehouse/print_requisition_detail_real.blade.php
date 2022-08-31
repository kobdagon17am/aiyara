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
    ->select('delivery_id_fk')
    ->where('packing_code_id_fk', $data[0])
    ->orderBy('delivery_id_fk', 'asc')
    ->get();
// dd($db_pick_pack_packing_data);
?>

@foreach ($db_pick_pack_packing_data as $index => $pick_pack_packing_data)
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
    $packing = DB::select(' SELECT packing_code,created_at FROM db_pick_pack_packing WHERE delivery_id_fk=' . $pick_pack_packing_data->delivery_id_fk . ' and packing_code_id_fk=' . $data[0] . '  GROUP BY packing_code ');
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
                  $pick_pack_packing_data->delivery_id_fk .
                  ' AND set_addr_send_this=0 ',
          );

            if(!$delivery){
              if ($delivery0) {
                // วุฒิเพิ่มมาสำหรับพวกบิลส่งกับบิลอื่น
                        $order_this = DB::table('db_orders')
                            ->select('bill_transfer_other')
                            ->where('id', $delivery0[0]->orders_id_fk)
                            ->first();
                        if ($order_this) {
                            $order_send = DB::table('db_orders')
                            ->select('id')
                                ->where('code_order', 'like', '%' . $order_this->bill_transfer_other . '%')
                                ->first();
                            if ($order_send) {
                                $delivery2 = DB::table('db_delivery')
                                ->select('recipient_name','addr_send','postcode','mobile','tel_home')
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
                                              db_delivery.id = " .
                                              $delivery0[0]->id
                    ,
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
        $d1 = DB::select(' SELECT packing_code from db_delivery WHERE id=' . $delivery[0]->delivery_id_fk . '');
        $d2 = DB::select(' SELECT receipt from db_delivery WHERE packing_code=' . $d1[0]->packing_code . '');
        $arr1 = [];
        foreach ($d2 as $key => $v) {
            array_push($arr1, $v->receipt);
        }
        $receipt = implode(',', $arr1);
    } else {
        $receipt = @$delivery[0]->receipt;
    }

    //     if($index==1){
    //   dd($fid_arr);
    // }

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
                    $pn = '<tr>
                                    <td style="width:70%; "><b>รหัส : ชื่อสินค้า</b></td>
                                    <td style="width:15%; text-align:left;"><b>จำนวน</b></td>
                                    <td style="width:15%; text-align:left;"><b>หน่วย</b></td>
                                    </tr>';

                    // $pn = '<div class="divTable"><div class="divTableBody">';
                    // $pn .= '<div class="divTableRow">
                    //                                                             <div class="divTableCell" style="width:200px;font-weight:bold;">รหัส : ชื่อสินค้า</div>
                    //                                                             <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
                    //                                                             <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
                    //                                                             </div>
                    //                                                             ';

                    $sum_amt = 0;
                    $r_ch_t = '';
                    $fid_arr = explode(',', $receipt);
                    $order_arr = DB::table('db_orders')
                        ->select('id')
                        ->whereIn('code_order', $fid_arr)
                        ->pluck('id')
                        ->toArray();

                    // if($pick_pack_packing_data->id == 29){
                    $Products = App\Models\Backend\Orders::getAllProduct($order_arr);
                    // }

                    if (@$Products) {
                        foreach ($Products as $key => $value) {
                            if (!empty($value->product_id_fk)) {
                                $pn .=
                                    "
                                                <tr>
                                                  <td>" .
                                    @$value->product_code .
                                    ' : ' .
                                    @$value->product_name .
                                    "</td>
                                                  <td style='text-align:left;'>" .
                                    @$value->amt_sum .
                                    "</td>
                                                  <td style='text-align:left;'>" .
                                    @$value->product_unit .
                                    "</td>
                                                  </tr>
                                              ";

                                // $pn .=
                                //     '<div class="divTableRow">
                                //                                                     <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                                //                                                     ' .
                                //     @$value->product_code .
                                //     ' : ' .
                                //     @$value->product_name .
                                //     '</b>
                                //                                                     </div>
                                //                                                     <div class="divTableCell" style="text-align:center;">' .
                                //     @$value->amt_sum .
                                //     '</div>
                                //                                                     <div class="divTableCell" style="text-align:center;">' .
                                //     @$value->product_unit .
                                //     '</div>
                                //                                                     ';
                                // $pn .= '</div>';
                                @$sum_amt += @$value->amt_sum;
                            }
                        }

                        // $pn .=
                        //     '<div class="divTableRow">
                        //                                                           <div class="divTableCell" style="text-align:right;font-weight:bold;"> รวม </div>
                        //                                                           <div class="divTableCell" style="text-align:center;font-weight:bold;">' .
                        //     @$sum_amt .
                        //     '</div>
                        //                                                           <div class="divTableCell" style="text-align:center;"> </div>
                        //                                                           </div>
                        //                                                           ';

                        // $pn .= '</div></div>';

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

                        // if($key+1 == 10 ){
                        //       echo '<div class="page-break"></div>';
                        //   }
                    }
                }

                ?>
                <br>
            </table>
        </div>

        <br> <br>
        <table style="border-collapse: collapse;">
            <tr>
                <th style="text-align: center;font-size: 18px;">
                    ขนาดลัง ...................................
                </th>
                <th style="text-align: center;font-size: 18px;">
                    น้ำหนัก ...................................
                </th>
            </tr>
            <tr>
                <th style="text-align: center;font-size: 18px;">
                    เวลาที่จัด ...................................
                </th>
                <th style="text-align: center;font-size: 18px;">
                    ถุงใหญ๋ ...................................
                </th>
            </tr>

            <tr>
                <th style="text-align: center;font-size: 18px;">
                    ผู้จัดสินค้า ...................................
                </th>
                <th style="text-align: center;font-size: 18px;">
                    ถุงเล็ก ...................................
                </th>
            </tr>

        </table>


        @if ($index < count($db_pick_pack_packing_data) - 1)
            <div class="page-break"></div>
        @endif
@endforeach
