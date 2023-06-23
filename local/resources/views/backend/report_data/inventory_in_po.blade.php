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
                รายงานรับเข้าสินค้า PO
                <br>
                <?php

                echo 'ประจำวันที่ ' . date('d/m/Y', strtotime($r->startDate_data)).' - '.date('d/m/Y', strtotime($r->endDate_data));

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
                        วันที่อนุมัติ
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                    rowspan="2">
                    รายการ
                </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        รหัสสินค้า : ชื่อสินค้า
                    </td>

                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        ล็อตนัมเบอร์
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        วันหมดอายุ
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        จำนวน
                    </td>

                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        คลังสินค้า
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        สาขา
                    </td>
                </tr>

                <tr style="">
                </tr>
            </thead>

            <?php

            $business_location_id_fk = $r->business_location_id_fk;
            $branch_id_fk = $r->branch_id_fk;

            $db_general_receive = \DB::table('db_general_receive')
                ->select('db_general_receive.*','products.product_code','products_details.product_name','warehouse.w_name','branchs.b_name')
                ->join('products','products.id','db_general_receive.product_id_fk')
                ->join('products_details','products_details.product_id_fk','products.id')
                ->join('warehouse','warehouse.id','db_general_receive.warehouse_id_fk')
                ->join('branchs','branchs.id','db_general_receive.branch_id_fk')
                ->where('db_general_receive.approve_status', 1)
                // ->where('db_general_receive.approve_date', '>=', $r->startDate_data)
                // ->where('db_general_receive.approve_date', '<=', $r->endDate_data)
                ->whereBetween('db_general_receive.approve_date', [$r->startDate_data, $r->endDate_data])
                ->when($r->business_location_id_fk != null, function ($query) use ($business_location_id_fk) {
                    return $query->where('db_general_receive.business_location_id_fk', $business_location_id_fk);
                })
                ->when($branch_id_fk != null, function ($query) use ($branch_id_fk) {
                    return $query->where('db_general_receive.branch_id_fk', $branch_id_fk);
                })
                ->where('products_details.lang_id',1)
                ->get();


                foreach($db_general_receive as $key => $data){

                  $col_1 = date('d/m/Y', strtotime($data->approve_date));
                  $col_2 = $data->description;
                  $col_3 = $data->product_code.' : '.$data->product_name;
                  $col_4 = $data->lot_number;
                  $col_5 = date('d/m/Y', strtotime($data->lot_expired_date));
                  $col_6 = $data->amt;
                  $col_7 = $data->w_name;
                  $col_8 = $data->b_name;

                  $td = '<tr>';
                  $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: center;">' . $col_1 . '</td>';
                  $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: left;">' . $col_2 . '</td>';
                  $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: left;">' . $col_3 . '</td>';
                  $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: left;">' . $col_4 . '</td>';
                  $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: center;">' . $col_5 . '</td>';
                  $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: right;">' . $col_6 . '</td>';
                  $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: center;">' . $col_7 . '</td>';
                  $td .= '<td style="border-left: 0px solid #ccc;border-bottom: 0px solid #ccc;text-align: left;">' . $col_8 . '</td>';

                  echo $td;
                }

            ?>

            <tbody>
                <tr>

                </tr>
            </tbody>




        </table>
    </div>

</div>
