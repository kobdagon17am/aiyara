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
        size: landscape;
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


<div class="NameAndAddress">

    <div style="border-radius: 5px; height: 14mm; border: 1px solid grey;padding:-1px;">
        <table style="border-collapse: collapse;vertical-align: top;">
            <tr>
                <td style="width:50%;vertical-align: top;font-weight: bold;">
                    รายงานสต็อคคงเหลือ
                </td>
                <td style="width:50%;vertical-align: top;font-weight: bold;text-align: right;padding-right: 2%;">
                    วันที่ / Date : {{ date('Y-m-d') }}
                </td>
            </tr>

        </table>
    </div>

    <br>

    <div style="">
        <table style="border-collapse: collapse;vertical-align: top;">
            <tr style="background-color: #cccccc;">
                <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;">No.</td>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">รหัสสินค้า :
                    ชื่อสินค้า</td>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">ล็อตนัมเบอร์
                </td>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">วันหมดอายุ
                </td>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">จำนวน</td>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">คลังสินค้า
                </td>
            </tr>

            @foreach ($sTable as $key => $row)
                <?php
                $Products = DB::select(
                    "SELECT products.id as product_id,
                            products.product_code,
                            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
                            FROM
                            products_details
                            Left Join products ON products_details.product_id_fk = products.id
                            WHERE products.id=" .
                        $row->product_id_fk .
                        ' AND lang_id=1',
                );

                $sBranchs = DB::select(' select * from branchs where id=' . $row->branch_id_fk . ' ');
                $warehouse = DB::select(' select * from warehouse where id=' . ($row->warehouse_id_fk ? $row->warehouse_id_fk : 0) . ' ');
                $zone = DB::select(' select * from zone where id=' . ($row->zone_id_fk ? $row->zone_id_fk : 0) . ' ');
                $shelf = DB::select(' select * from shelf where id=' . ($row->shelf_id_fk ? $row->shelf_id_fk : 0) . ' ');
                $t = '(' . $row->id . ') ' . @$sBranchs[0]->b_name . '/' . @$warehouse[0]->w_name . '/' . @$zone[0]->z_name . '/' . @$shelf[0]->s_name . '/ชั้น>' . @$row->shelf_floor;
                ?>
                <tr>
                    <td style="border-bottom: 1px solid #ccc;text-align: center;">{{ $key + 1 }}</td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: left;">
                        {{ @$Products[0]->product_code . ' : ' . @$Products[0]->product_name }}</td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: left;">
                        {{ $row->lot_number }}</td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: left;">
                        {{ $row->lot_expired_date }}</td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        {{ $row->amt }}</td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: left;">
                        {{ $t  }}</td>
                </tr>
            @endforeach

            {{-- <tr>
        <td style="border-bottom: 1px solid #ccc;text-align: center;"></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: left;"></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr> --}}

        </table>
    </div>



</div>
