<style>
@font-face{
 font-family:  'THSarabunNew';
 font-style: normal;
 font-weight: normal;
 src: url("{{ asset('backend/fonts/THSarabunNew.ttf') }}") format('truetype');
}
@font-face{
 font-family:  'THSarabunNew';
 font-style: normal;
 font-weight: bold;
 src: url("{{ asset('backend/fonts/THSarabunNew Bold.ttf') }}") format('truetype');
}
@font-face{
 font-family:  'THSarabunNew';
 font-style: italic;
 font-weight: normal;
 src: url("{{ asset('backend/fonts/THSarabunNew Italic.ttf') }}") format('truetype');
}
@font-face{
 font-family:  'THSarabunNew';
 font-style: italic;
 font-weight: bold;
 src: url("{{ asset('backend/fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
}
body{
 font-family: "THSarabunNew"; font-size: 22px;
}
@page {
      size: A6;
      size: landscape;
      padding: 2px;
    }
    @media print {
      html, body {
        width: 500mm;
        /*height: 297mm;*/
      }
    }</style>


<b>กรุณาส่ง..</b><br>


    <?php

              $value = DB::select("
                    SELECT * FROM `db_consignments`
                    WHERE
                    recipient_code = '".$data[0]."'

               ");

                // echo "<b>".$value[0]->prefix_name.$value[0]->first_name.' '.$value[0]->last_name."</b><br>";

                // $addr = $value[0]->house_no?$value[0]->house_no.", ":'';
                // $addr .= $value[0]->house_name;
                // $addr .= $value[0]->moo?", หมู่ ".$value[0]->moo:'';
                // $addr .= $value[0]->soi?", ซอย".$value[0]->soi:'';
                // $addr .= $value[0]->road?", ถนน".$value[0]->road:'';
                // $addr .= $value[0]->district?", ต.".$value[0]->district:'';
                // $addr .= $value[0]->amphures?", อ.".$value[0]->amphures:'';
                // $addr .= $value[0]->province?", จ.".$value[0]->province:'';

                if(!empty($value[0]->recipient_name)){
                    echo @$value[0]->recipient_name."<br>";
                    echo @$value[0]->address."<br>";
                    echo @$value[0]->postcode."<br>";
                    echo @$value[0]->mobile."<br>";
                //     $addr = $addr;
                // }else{
                //     $addr = "-ไม่พบข้อมูลที่อยู่-";
                }

      ?>

