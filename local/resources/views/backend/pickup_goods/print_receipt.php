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
 font-family: "THSarabunNew"; font-size: 16px;
}
@page {
      size: A4;
      padding: 5px;
    }
    @media print {
      html, body {
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
    border-radius: 15px ;
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
    height: 100px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
    content: "";
    display: table;
    clear: both;
}


.topics tr { line-height: 10px; }


tr.border_bottom td {
 border-bottom:1px solid #ccc;;
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

<?php 

$value = DB::select(" 
                    SELECT
                    db_delivery.*,
                    customers.prefix_name,
                    customers.first_name,
                    customers.last_name,
                    customers_detail.house_no,
                    customers_detail.house_name,
                    customers_detail.moo,
                    customers_detail.zipcode,
                    customers_detail.soi,
                    customers_detail.district,
                    customers_detail.district_sub,
                    customers_detail.road,
                    customers_detail.province,
                    customers.id as cus_id,
                    orders.id as order_id,
                    orders.shipping

                    FROM
                    db_delivery
                    Left Join customers_detail ON db_delivery.customer_id = customers_detail.customer_id
                    Left Join customers ON customers_detail.customer_id = customers.id
                    Left Join orders ON db_delivery.receipt = orders.code_order
                    WHERE
                    db_delivery.id = 
                    ".$data[0]."

     ");

        // if($i>1){echo'<div class="page-break"></div>';}

 ?>

    <div class="NameAndAddress" style="" >
      <table style="border-collapse: collapse;" >
        <tr>
          <th style="text-align: left;">
            <img src="http://krit.orangeworkshop.info/aiyara/backend/images/logo2.png" >
          </th>
          <th style="text-align: right;">
            (<?php echo sprintf("%04d",$data[0]).'-'.$value[0]->receipt; ?>)<br>
2102/1 อาคารไอยเรศวร ซ.ลาดพร้าว 84 ถ.ลาดพร้าว <br>
แขวงวังทองหลาง เขตวังทองหลาง กรุงเทพ 10310 ประเทศไทย <br>
TEL : +66 (0) 2026 3555 
FAX : +66 (0) 2514 3944 
E-MAIL : info@aiyara.co.th
          </th>
        </tr>
      
      </table>
    </div>

  <div class="NameAndAddress" style="" >
      <table style="border-collapse: collapse;" >
        <tr> 
          <th style="text-align: center;font-size: 30px;">
           <center> ใบเบิกสินค้าจากคลัง </center>
          </th>
        </tr>
      </table>
    </div>


<div class="NameAndAddress" >

  <div style="border-radius: 5px; height: 30mm; border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;" >
      <tr>
        <td style="width:30%;" > 

         <?php

                echo "<b>ที่อยู่ลูกค้า</b>"."<br>";
                echo "<b>".$value[0]->prefix_name.$value[0]->first_name.' '.$value[0]->last_name."</b><br>";

                $addr = $value[0]->house_no?$value[0]->house_no.", ":'';
                $addr .= $value[0]->house_name;
                $addr .= $value[0]->moo?", หมู่ ".$value[0]->moo:'';
                $addr .= $value[0]->soi?", ซอย".$value[0]->soi:'';
                $addr .= $value[0]->road?", ถนน".$value[0]->road:'';
                $addr .= $value[0]->district_sub?", ต.".$value[0]->district_sub:'';
                $addr .= $value[0]->district?", อ.".$value[0]->district:'';
                $addr .= $value[0]->province?", จ.".$value[0]->province:'';

                if($addr!=''){
                    $addr = $addr;
                }else{
                    $addr = "-ไม่พบข้อมูลที่อยู่-";
                }

                echo $addr;

      ?>

      </td>
      <td style="width:10%;vertical-align: top;font-weight: bold;" > 
        เลขที่ / No. <br>
        วันที่ / Date
      </td>
      <td style="width:10%;vertical-align: top;" > 
         <?=$value[0]->receipt?> <br>
         <?php $d = strtotime($value[0]->delivery_date); echo date("d/m/", $d).(date("Y", $d)+543); ?>
      </td>
        
      </tr>
    </table>
  </div>
  <br>


  <div style="border-radius: 5px; height: 70mm; border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;" >
      <tr style="background-color: #e6e6e6;">
        <td style="width:8%;border-bottom: 1px solid #ccc;text-align: center;" > ลำดับที่ <br> Item

 </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">รายการ<br>
Description </td>
        <td style="border-left: 1px solid #ccc;width:15%;border-bottom: 1px solid #ccc;text-align: center;"> จำนวน <br>
Quantity </td>
        <td style="border-left: 1px solid #ccc;width:15%;border-bottom: 1px solid #ccc;text-align: center;"> ราคา/หน่วย <br>
Unit Price </td>
        <td style="border-left: 1px solid #ccc;width:5%;border-bottom: 1px solid #ccc;text-align: center;"> PV   
        <td style="border-left: 1px solid #ccc;width:5%;border-bottom: 1px solid #ccc;text-align: center;">  ค่าจัดส่ง
        <td style="border-left: 1px solid #ccc;width:15%;border-bottom: 1px solid #ccc;text-align: center;"> จำนวนเงิน <br>
Amount </td>

      </tr>

<!-- รายการสินค้า -->
<?php 

     $P = DB::select(" 
        SELECT
        order_items.id,
        order_items.order_id,
        order_items.product_id,
        order_items.product_name,
        order_items.quantity,
        order_items.list_price,
        order_items.pv,
        order_items.discount,
        order_items.created_at,
        order_items.update_at,
        orders.shipping
        FROM order_items 
        Left Join orders ON order_items.order_id = orders.id
        WHERE
        order_items.order_id = ".$value[0]->order_id."

     ");

    $i=1;

     $Total = 0;
     $shipping = 0;

     // echo count($P);
     // exit;

    foreach ($P as $key => $v) {

     ?>

          <tr>
            <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;" > <?=$i?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->product_name?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->quantity?>  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->list_price?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->pv?>  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->shipping?>  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=number_format($v->quantity*$v->list_price,2)?>  </td>
          </tr>

    <?php  
    $i++; 
    $Total += $v->quantity*$v->list_price;  
    $shipping += $v->shipping ;  

  } 

  $n = 5 - $i; 

  ?>

<?php for ($i=0; $i < $n ; $i++) {  ?>
      <tr>
        <td style="border-bottom: 1px solid #ccc;text-align: center;" > &nbsp;  </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">  </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">   </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">   </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">   </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">   </td>
      </tr>
<?php } ?>

    </table>
  </div>

<br>

  <div style="border-radius: 5px; border: 1px solid grey;" >
    <table style="border-collapse: collapse;vertical-align: top;" >

      <tr>
        <td rowspan="4"  style="width:55%;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;">

       
<br>
<br>
<br>
<br>
      <!-- <center>  ตัวอักษร (ห้าพันหกร้อยบาทถ้วน) </center> -->
      </td>

      <?php 

            $net_price = str_replace(',', '', $Total);

            // echo $net_price;
            // exit;
            // ภาษีมูลค่าเพิ่ม ( VAT 7% )  
            $vat = intval($net_price) - (intval($net_price)/1.07) ;

            // echo $net_price;
            // echo $shipping;
            // exit;
            // รวมเงิน
            $total_price = $net_price + $shipping;
            $total_price = number_format($total_price,2) ;
            // echo $total_price;
            // exit;
            $net_price =  number_format($net_price,2) ;
            $vat =  number_format($vat,2) ;
            $shipping =  number_format($shipping,2) ;

         ?>

        <td  style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> รวมเงิน <br>
TOTAL </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: right;padding-right: 10px;"> <?=$net_price?> </td>

      </tr>


      <tr>
      
        <td  style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> ภาษีมูลค่าเพิ่ม  <br>
( VAT 7% ) </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: right;padding-right: 10px;"> <?=$vat?> </td>

      </tr>
          <tr>
        <td  style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> ค่าจัดส่ง  </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: right;padding-right: 10px;"> <?=$shipping?> </td>

      </tr>
      <tr>
  
        <td  style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> ยอดเงินสุทธิ  <br>
NET AMOUNT </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: right;padding-right: 10px;"> <?=$total_price?> </td>

      </tr>

    </table>
  </div>

<br>
<br>
<br>

  <div style="border-radius: 5px; height: 30mm; border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;text-align: center;" >
      
      <tr>

        <td  style="border-left: 1px solid #ccc;"> ผู้รับเงิน

        <br>
        <img src="" width="100" > (ลายเซ็น)
        <br>
        <br>
           วันที่ .........................................
         </td>


        <td style="border-left: 1px solid #ccc;"> ในนาม บริษัท ไอยรา แพลนเน็ต จำกัด
        <br>
        <img src="" width="100" > (ลายเซ็น)
        <br>
        <br>
      ผู้มีอำนาจลงนาม
        </td>

      </tr>


    </table>
  </div>

</div>


 