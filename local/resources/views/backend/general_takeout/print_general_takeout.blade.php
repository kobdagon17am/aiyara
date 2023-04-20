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
</style>



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
                <center> ใบนำสินค้าออก </center>
            </th>
        </tr>
    </table>
</div>

<?php

$db_general_takeout = DB::select(' SELECT * FROM db_general_takeout WHERE id=' . $data[0] . '');

?>

<div class="NameAndAddress">

    <div style="border-radius: 5px; height: 12mm; border: 1px solid grey;padding:-1px;">
        <table style="border-collapse: collapse;vertical-align: top;">
            <tr>
                <td style="width:30%;vertical-align: top;font-weight: bold">
                    รหัสใบเบิก / Ref.No. : {{ @$db_general_takeout[0]->ref_doc }}
                </td>
                <td style="width:30%;vertical-align: top;font-weight: bold;">
                    วันที่ทำใบเบิก / Date : {{ date('d/m/Y', strtotime(@$db_general_takeout[0]->created_at)) }}
                </td>
            </tr>
        </table>
    </div>
    <br>


    <div style="border-radius: 5px; border: 1px solid grey;padding:-1px;">
        <table style="border-collapse: collapse;vertical-align: top;">
            <tr style="background-color: #e6e6e6;">
                <td style="width:8%;border-bottom: 1px solid #ccc;text-align: center;"> ลำดับที่ <br> Item </td>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">รายการ<br>
                    Description </td>
                <td style="border-left: 1px solid #ccc;width:10%;border-bottom: 1px solid #ccc;text-align: center;">
                    จำนวน <br>
                    Quantity </td>
                <td style="border-left: 1px solid #ccc;width:10%;border-bottom: 1px solid #ccc;text-align: center;">
                    หน่วย <br>
                    Unit Price </td>
                <td style="border-left: 1px solid #ccc;width:25%;border-bottom: 1px solid #ccc;text-align: center;"> Lot
                </td>
                <td style="border-left: 1px solid #ccc;width:25%;border-bottom: 1px solid #ccc;text-align: center;">
                    หยิบจาก </td>
            </tr>

            <!-- รายการสินค้า -->
            <?php
$orders = DB::select(" SELECT * FROM db_general_takeout_item  WHERE  general_takeout_id='".$data[0]."' ");
// $action_user = DB::table('ck_users_admin')->select('name')->where('id',$orders->action_user)->first();
foreach ($orders as $key0 => $value) {
  ?>
            <tr>
                <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;"> {{ $key0 + 1 }} </td>
                <td
                    style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: left;padding-left: 10px;">
                    {{ $value->pro_name }} </td>
                <td
                    style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;font-weight: bold;">
                    {{ $value->amt }} </td>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                    ชิ้น</td>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                    {{ $value->lot_number }} <br> [{{ $value->lot_expired_date }}] </td>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                    {{ $value->lot_name }} </td>
            </tr>

            <?php } ?>

        </table>
    </div>

    <div>&nbsp;</div>

    <div style="border-radius: 5px; height: 28mm; border: 1px solid grey;padding:-1px; ">
        <table style="border-collapse: collapse;vertical-align: top;text-align: center;">

            <tr>

                <td style="border-left: 1px solid #ccc;"> ผู้ทำรายการ

                    <br>
                    <br>
                    วันที่ .........................................
                </td>


                <td style="border-left: 1px solid #ccc;"> ในนาม บริษัท ไอยรา แพลนเน็ต จำกัด
                    <br>
                    <img src="" width="100">
                    <br>
                    <br>
                    ผู้มีอำนาจลงนาม
                </td>

            </tr>


        </table>
    </div>

</div>

<div class="NameAndAddress" >
  <b>ส่วนงานคลังสินค้า</b>
  <div style="border-radius: 5px;  border: 1px solid grey;padding:-1px;" >

    <table style="border-collapse: collapse;vertical-align: top;text-align: center;" >

      <tr>
        <td  style="border-left: 1px solid #ccc; text-align: center;"> ผู้อนุมัติเบิกสินค้า
        <br>   {{@$admin->name}}    <br>
        {{-- วันที่ ............................... --}}
        วันที่ @if(@$db_pick_pack_packing_code->aprove_date!=''){{date('d/m/Y', strtotime(@$db_pick_pack_packing_code->aprove_date))}}@endif
         </td>


        <td style="border-left: 1px solid #ccc; text-align:center;"> ผู้จ่ายสินค้า
        <br>       <br>
        วันที่ ...............................
        </td>

        <td style="border-left: 1px solid #ccc; text-align:center;"> ผู้ตรวจสอบสินค้า
        <br>       <br>
        วันที่ ...............................
        </td>

      </tr>

    </table>
  </div>

</div>
