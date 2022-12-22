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
        font-size: 17px;

    }

    @page {
        size: A6;
        size: landscape;
        padding: 2px;
        /* margin: 0px; */
        /* margin-top: 10px; */
    }

    @media print {

        html,
        body {
            width: 500mm;
            /* height: 297mm; */
        }
    }
</style>

{{-- <b>ผู้ส่ง..</b><br> --}}
<?php
// echo 'ผู้ส่ง บริษัท ไอยรา แพลนเน็ต จำกัด<br>';
// echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2102/1 อาคารไอยเรศวร ซอยลาดพร้าว 84 ถนนลาดพร้าว <br>';
// echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; แขวงวังทองหลาง เขตวังทองหลาง กรุงเทพฯ 10310 <br>';
// // echo ' 10310 <br>';
// echo ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; โทร. 02-0263555 กด 1';
?>
<br><br>

<b style="font-size: 22px;">ผู้รับ</b><br>


<?php

$value = DB::select(
    "
                    SELECT * FROM `db_consignments`
                    WHERE
                    recipient_code = '" .
        $data[0] .
        "'

               ",
);

// echo "<b>".$value[0]->prefix_name.$value[0]->first_name.' '.$value[0]->last_name."</b><br>";

// $addr = $value[0]->house_no?$value[0]->house_no.", ":'';
// $addr .= $value[0]->house_name;
// $addr .= $value[0]->moo?", หมู่ ".$value[0]->moo:'';
// $addr .= $value[0]->soi?", ซอย".$value[0]->soi:'';
// $addr .= $value[0]->road?", ถนน".$value[0]->road:'';
// $addr .= $value[0]->district?", ต.".$value[0]->district:'';
// $addr .= $value[0]->amphures?", อ.".$value[0]->amphures:'';
// $addr .= $value[0]->province?", จ.".$value[0]->province:'';

echo'<label style="font-size: 20px;">';
if (!empty($value[0]->recipient_name)) {
    echo 'คุณ ' . @$value[0]->recipient_name . '<br>';
    echo 'ที่อยู่ ' . @$value[0]->address . ' ';
    echo ''.@$value[0]->postcode . '<br>';
    echo ''.@$value[0]->mobile ? ' โทร.' . @$value[0]->mobile : '' . '<br>';
    // echo '<br>';
    // echo '<br>';
    // echo 'Tracking No. : ' . @$value[0]->consignment_no;
    //     $addr = $addr;
    // }else{
    //     $addr = "-ไม่พบข้อมูลที่อยู่-";
}

echo '</label>';

?>
