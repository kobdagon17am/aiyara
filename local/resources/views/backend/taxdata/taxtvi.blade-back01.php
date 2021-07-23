@php
@include(app_path() . '\Models\MyFunction.php');
@endphp
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
 font-family: "THSarabunNew"; font-size: 20px;
 font-weight: bold;
}
@page {
      size: A4;
      size: landscape;
    }
    @media print {
      html, body {
        /*width: 210mm;*/
        /*height: 297mm;*/
      }
    }

  </style>

<!-- <img src="{{asset('local/public/taxdata/tavi_form.png')}}" width="103%" style="position:absolute;z-index:0;" > -->
<img src="<?=public_path('taxdata/tavi_form.png'); ?>" width="103%" style="position:absolute;z-index:0;" >

<?php

 function ThaiBahtConversion02($amount_number)

      {

          $amount_number = number_format($amount_number, 2, ".","");

          //echo "<br/>amount = " . $amount_number . "<br/>";

          $pt = strpos($amount_number , ".");

          $number = $fraction = "";

          if ($pt === false) 

              $number = $amount_number;

          else

          {

              $number = substr($amount_number, 0, $pt);

              $fraction = substr($amount_number, $pt + 1);

          }

          

          //list($number, $fraction) = explode(".", $number);

          $ret = "";

          $baht = ReadNumber02 ($number);

          if ($baht != "")

              $ret .= $baht . "บาท";

          

          $satang = ReadNumber02($fraction);

          if ($satang != "")

              $ret .=  $satang . "สตางค์";

          else 

              $ret .= "ถ้วน";

          //return iconv("UTF-8", "TIS-620", $ret);

          return $ret;

      }

function ReadNumber02($number)

{

    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");

    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");

    $number = $number + 0;

    $ret = "";

    if ($number == 0) return $ret;

    if ($number > 1000000)

    {

        $ret .= ReadNumber02(intval($number / 1000000)) . "ล้าน";

        $number = intval(fmod($number, 1000000));

    }

    

    $divider = 100000;

    $pos = 0;

    while($number > 0)

    {

        $d = intval($number / $divider);

        $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : 

            ((($divider == 10) && ($d == 1)) ? "" :

            ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));

        $ret .= ($d ? $position_call[$pos] : "");

        $number = $number % $divider;

        $divider = $divider / 10;

        $pos++;

    }

    return $ret;

}


 function ThDate0102($d)
  {
      
      $d = strtotime($d);  
      return date("d/m/", $d).(date("Y", $d)+543);
      
  }

      $value = DB::select("
                SELECT
                db_taxdata.*,
                customers.id_card,
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
                customers_detail.province_id_fk
                FROM
                db_taxdata
                Left Join customers_detail ON db_taxdata.customer_id_fk = customers_detail.customer_id
                Left Join customers ON customers_detail.customer_id = customers.id
                WHERE db_taxdata.customer_id_fk = ".$data[0]."

           ");

                $addr = @$value[0]->house_no?@$value[0]->house_no.", ":'';
                $addr .= @$value[0]->house_name;
                $addr .= @$value[0]->moo?", หมู่ ".@$value[0]->moo:'';
                $addr .= @$value[0]->soi?", ซอย".@$value[0]->soi:'';
                $addr .= @$value[0]->road?", ถนน".@$value[0]->road:'';
                $addr .= @$value[0]->amphures?", ต.".@$value[0]->amphures:'';
                $addr .= @$value[0]->district?", อ.".@$value[0]->district:'';
                $addr .= @$value[0]->province?", จ.".@$value[0]->province:'';

                if(!empty(@$value[0]->first_name)){
                  $cusname = @$value[0]->prefix_name.@$value[0]->first_name.' '.@$value[0]->last_name;
                }else{
                  $cusname = '--';
                }

                if($addr!=''){
                    $addr = $addr;
                }else{
                    $addr = '--';
                    // $addr = "54/325, 90/16 ( หลังตลาดเทวราช ), หมู่ 4, ซอยซอย, ถนนถนน, ต.คลองสาม, อ.คลองหลวง, จ.กรุงเทพ";
                }
      ?>

    <div style="position:absolute;z-index:1;color:red;">
      <div style="margin-left: 90%;margin-top:45px;">9999999</div>
      <div style="margin-left: 90%;margin-top:-17px;">9999999</div>
    </div>

    <div style="position:absolute;z-index:1;color:red;">
      <div style="margin-left: 10%;margin-top:105px;"><?=$cusname?></div>
      <div style="margin-left: 65.5%;margin-top:-52px;">
        <?php

        if(!empty(@$value[0]->id_card)){

            for ($i=0; $i < strlen(@$value[0]->id_card) ; $i++) {
               if($i>11){
                 echo "&nbsp;&nbsp;&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>10){
                 echo "&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>9){
                 echo "&nbsp;&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>7){
                 echo "&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>5){
                 echo "&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>4){
                 echo "&nbsp;&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>3){
                 echo "&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>1){
                 echo "&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>0){
                 echo "&nbsp;&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }else{
                 echo substr(@$value[0]->id_card,$i,1);
              }
            }

          }else{
              echo "&nbsp;&nbsp;&nbsp;&nbsp;";
          }

         ?>
      </div>
      <div style="margin-left: 73%;margin-top:-15px;">
       <?php

       if(!empty(@$value[0]->tax_code)){

          for ($i=0; $i < strlen(@$value[0]->tax_code) ; $i++) {
             if($i>8){
               echo "&nbsp;&nbsp;&nbsp;".substr(@$value[0]->tax_code,$i,1);
            }elseif($i>7){
               echo "&nbsp;".substr(@$value[0]->tax_code,$i,1);
            }elseif($i>5){
               echo "&nbsp;&nbsp;".substr(@$value[0]->tax_code,$i,1);
            }elseif($i>4){
               echo "&nbsp;&nbsp;&nbsp;".substr(@$value[0]->tax_code,$i,1);
            }elseif($i>3){
               echo "&nbsp;".substr(@$value[0]->tax_code,$i,1);
            }elseif($i>1){
               echo "&nbsp;&nbsp;".substr(@$value[0]->tax_code,$i,1);
            }elseif($i>0){
               echo "&nbsp;&nbsp;&nbsp;".substr(@$value[0]->tax_code,$i,1);
            }else{
               echo substr(@$value[0]->tax_code,$i,1);
            }
          }
        }else{
              echo "&nbsp;&nbsp;&nbsp;&nbsp;";
          }

         ?>
      </div>
      <div style="margin-left: 10%;margin-top:-12px;"><?=$addr?></div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:90px;">
      <div style="margin-left: 10%;margin-top:105px;"><?=$cusname?></div>
      <div style="margin-left: 65.5%;margin-top:-57px;">
        <?php

          if(!empty(@$value[0]->id_card)){

            for ($i=0; $i < strlen(@$value[0]->id_card) ; $i++) {
               if($i>11){
                 echo "&nbsp;&nbsp;&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>10){
                 echo "&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>9){
                 echo "&nbsp;&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>7){
                 echo "&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>5){
                 echo "&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>4){
                 echo "&nbsp;&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>3){
                 echo "&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>1){
                 echo "&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }elseif($i>0){
                 echo "&nbsp;&nbsp;&nbsp;".substr(@$value[0]->id_card,$i,1);
              }else{
                 echo substr(@$value[0]->id_card,$i,1);
              }
            }

          }else{
              echo "&nbsp;&nbsp;&nbsp;&nbsp;";
          }

         ?>
      </div>
      <div style="margin-left: 73%;margin-top:-15px;">
       <?php

         if(!empty(@$value[0]->tax_code)){

            for ($i=0; $i < strlen(@$value[0]->tax_code) ; $i++) {
               if($i>8){
                 echo "&nbsp;&nbsp;&nbsp;".substr(@$value[0]->tax_code,$i,1);
              }elseif($i>7){
                 echo "&nbsp;".substr(@$value[0]->tax_code,$i,1);
              }elseif($i>5){
                 echo "&nbsp;&nbsp;".substr(@$value[0]->tax_code,$i,1);
              }elseif($i>4){
                 echo "&nbsp;&nbsp;&nbsp;".substr(@$value[0]->tax_code,$i,1);
              }elseif($i>3){
                 echo "&nbsp;".substr(@$value[0]->tax_code,$i,1);
              }elseif($i>1){
                 echo "&nbsp;&nbsp;".substr(@$value[0]->tax_code,$i,1);
              }elseif($i>0){
                 echo "&nbsp;&nbsp;&nbsp;".substr(@$value[0]->tax_code,$i,1);
              }else{
                 echo substr(@$value[0]->tax_code,$i,1);
              }
            }
          }else{
              echo "&nbsp;&nbsp;&nbsp;&nbsp;";
          }

         ?>
      </div>
      <div style="margin-left: 10%;margin-top:-5px;"><?=$addr?></div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:150px;">
      <div style="margin-left: 15%;margin-top:112px;">999999</div>

      <div style="margin-left: 37%;margin-top:-35px;">/</div>
      <div style="margin-left: 50.5%;margin-top:-55px;">/</div>
      <div style="margin-left: 69.2%;margin-top:-55px;">/</div>
      <div style="margin-left: 82.5%;margin-top:-55px;">/</div>

      <div style="margin-left: 37%;margin-top:-15px;">/</div>
      <div style="margin-left: 50.5%;margin-top:-35px;">/</div>
      <div style="margin-left: 69.2%;margin-top:-35px;">/</div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:360px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:378px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:396px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:414px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:480px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:499px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:518px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:537px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:570px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:605px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:642px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:678px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:748px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:767px;">
      <div style="margin-left: 58%;margin-top:-15px;">12 ม.ค. 2564
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 7%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:788px;">
        <div style="margin-left: 71%;margin-top:-15px;">
        <span style="margin-left: 6%"> 12,345.55</span>
        <span style="margin-left: 10%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:799px;">
        <!-- <div style="margin-left: 35%;">(เก้าล้านเก้าแสนเก้าหมื่นเก้าพันเก้าร้อยเก้าสิบเก้าบาทเก้าสิบเก้าสตางค์) -->
        <div style="margin-left: 35%;"><?=ThaiBahtConversion02(9999999.99)?>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:842px;font-size: 14px">
        <div style="margin-left: 42%;margin-top:-15px;"> 12,345.55
        <span style="margin-left: 26%"> 12,345.55</span>
        <span style="margin-left: 28%"> 12,345.55</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:850px;">
        <span style="margin-left: 15%;margin-top:-15px;">/</span>
        <span style="margin-left: 14.8%;margin-top:-15px;">/</span>
        <span style="margin-left: 17%;margin-top:-15px;">/</span>
        <span style="margin-left: 18%;margin-top:-15px;">/</span>
        <span style="margin-left: 12%;margin-top:-15px;">ระบุระบุระบุ</span>
      </div>
    </div>

    <div style="position:absolute;z-index:1;color:red;margin-top:920px;">
      <div style="margin-left: 60%;margin-top:-15px;">
        12 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        01 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        2564
      </div>
    </div>
