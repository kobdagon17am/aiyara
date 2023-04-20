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
                รายงานค่าคอมมิชชั่น
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
                        ลำดับ
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        วันเดือนปี
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        เลขประจำตัวผู้เสียภาษี
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        ชื่อ/ชื่อกลาง/นามสกุล
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        จำนวนเงิน
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        ค่าภาษีหัก ณ ที่จ่าย
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        ค่าธรรมเนียมการโอน
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        จำนวนสุทธิที่ได้รับ
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php

              $p = "";
              $bonus_total_sum = 0;
              $tax_sum = 0;
              $fee_sum = 0;
              $price_transfer_total_sum = 0;
                foreach($sTable as $key => $data){
                    $bonus_total_sum += $data->bonus_total;
                    $tax_sum += $data->tax;
                    $fee_sum += $data->fee;
                    $price_transfer_total_sum += $data->price_transfer_total;
                    $data_date = date('d/m/Y', strtotime($data->bonus_transfer_date));
                          $p.= '
                                  <tr>
                                  <td style="text-align: center;">'.($key+1).'</td>
                                  <td style="text-align: center;">'.$data_date.'</td>
                                  <td style="text-align: left;">'.$data->tax_number.'</td>
                                  <td style="text-align: left;">'.$data->customer_username.'</td>
                                  <td style="text-align: right;">'.number_format($data->bonus_total,2,".",",").'</td>
                                  <td style="text-align: right;">'.number_format($data->tax,2,".",",").'</td>
                                  <td style="text-align: right;">'.number_format($data->fee,2,".",",").'</td>
                                  <td style="text-align: right;">'.number_format($data->price_transfer_total,2,".",",").'</td>
                              </tr>
                                  ';
                }


              echo $p;
              ?>

                <tr>
                    <td style="text-align: center;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($bonus_total_sum,2,".",","); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($tax_sum,2,".",","); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($fee_sum,2,".",","); ?></td>
                    <td style="text-align: right; border-bottom: 3px double #000;">
                        <?php echo number_format($price_transfer_total_sum,2,".",","); ?></td>

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
            <td style="border-left: 1px solid #ccc;"> รวมวิธีการจ่ายค่าคอมมิชชั่น &nbsp;</td>
            <td style="">&nbsp;</td>
            <td style="border-left: 1px solid #ccc;">รวมมูลค่าค่าคอมมิชชั่นและภาษีหักณ ที่จ่าย &nbsp;</td>
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
            <td style="text-align: right;"> <?php echo number_format(0,2,".",","); ?> &nbsp;</td>
            <td style="border-left: 1px solid #ccc;"> มูลค่าสินค้า &nbsp;</td>
            <td style="text-align: right;">0.00 &nbsp;</td>
        </tr>

        <tr>
            <td style="border-left: 1px solid #ccc;"> เงินโอน &nbsp;</td>
            <td style="text-align: right;"> <?php echo number_format($bonus_total_sum,2,".",","); ?> &nbsp;
            </td>
            <td style="border-left: 1px solid #ccc;"> TAX 3% &nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($tax_sum,2,".",","); ?> &nbsp;</td>
        </tr>

        <tr>
            <td style="border-left: 1px solid #ccc;"> &nbsp;</td>
            <td style="text-align: right;"> &nbsp; </td>
            <td style="border-left: 1px solid #ccc;"> ค่าธรรมเนียมธนาคาร &nbsp;</td>
            <td style="text-align: right;">

                <?php echo number_format($fee_sum,2,".",","); ?>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td style="border-left: 1px solid #ccc;"> <b><u>รวมยอดชำระทั้งสิ้น</u></b> </td>
            <td style="text-align: right;"><u style="text-align: right;">
                    <?php echo number_format($bonus_total_sum,2,".",","); ?></u>
                &nbsp;</td>
            <td style="border-left: 1px solid #ccc;"> <b><u>รวมยอดชำระ</u></b> </td>
            <td style="text-align: right;"><u style="text-align: right;">
                    <?php echo number_format($price_transfer_total_sum,2,".",","); ?></u>
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
