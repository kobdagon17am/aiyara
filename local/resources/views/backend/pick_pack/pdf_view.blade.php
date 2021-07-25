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
 font-family: "THSarabunNew"; font-size: 26px;
}
@page {
      size: A6;
      size: landscape;
      padding: 5px;
    }
    @media print {
      html, body {
        /*width: 210mm;*/
        /*height: 297mm;*/
      }
    }</style>


<b>กรุณาส่ง..</b><br>


    <?php

              $value = DB::select("
                    SELECT
                    db_delivery.id,
                    customers.prefix_name,
                    customers.first_name,
                    customers.last_name,
                    customers_detail.house_no,
                    customers_detail.house_name,
                    customers_detail.moo,
                    customers_detail.zipcode,
                    customers_detail.soi,
                    customers_detail.amphures_id_fk,
                    customers_detail.district_id_fk,
                    customers_detail.road,
                    customers_detail.province_id_fk,
                    customers.id as cus_id
                    FROM
                    db_delivery
                    Left Join customers_detail ON db_delivery.customer_id = customers_detail.customer_id
                    Left Join customers ON customers_detail.customer_id = customers.id
                    WHERE
                    db_delivery.id =
                    ".$data[0]."

               ");

                echo "<b>".$value[0]->prefix_name.$value[0]->first_name.' '.$value[0]->last_name."</b><br>";

                $addr = $value[0]->house_no?$value[0]->house_no.", ":'';
                $addr .= $value[0]->house_name;
                $addr .= $value[0]->moo?", หมู่ ".$value[0]->moo:'';
                $addr .= $value[0]->soi?", ซอย".$value[0]->soi:'';
                $addr .= $value[0]->road?", ถนน".$value[0]->road:'';
                $addr .= $value[0]->amphures?", ต.".$value[0]->amphures:'';
                $addr .= $value[0]->district?", อ.".$value[0]->district:'';
                $addr .= $value[0]->province?", จ.".$value[0]->province:'';

                if($value[0]->house_no!=''&$value[0]->house_name!=''){
                    $addr = $addr;
                }else{
                    $addr = "-ไม่พบข้อมูลที่อยู่-";
                }

      ?>
      {{$addr}}<br>{{$value[0]->zipcode?$value[0]->zipcode:''}}

