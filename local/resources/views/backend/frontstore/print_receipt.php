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

<style type="text/css">
  /* DivTable.com */
  .divTable{
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
  .divTableCell, .divTableHead {
    border: 1px solid white;
    display: table-cell;
    padding: 3px 10px;
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
  .divTH {text-align: right;}


  .inline-group {
    max-width: 5rem;
    padding: .5rem;
  }

  .inline-group .form-control {
    text-align: right;
  }

  .form-control[type="number"]::-webkit-inner-spin-button,
  .form-control[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  .quantity {width: 50px;text-align: right;color: blue;font-weight: bold;font-size: 16px;}

  .btn-minus,.btn-plus,.btn-minus-pro,.btn-plus-pro,.btn-plus-product-pro,.btn-minus-product-pro {background-color: bisque !important;}

  input[type="text"]:disabled {
    background: #f2f2f2;
  }




</style>
<?php 

$value = DB::select(" 
                    SELECT
                    db_frontstore_products_list.*,
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
                    orders_frontstore.id as order_id,
                    orders_frontstore.shipping

                    FROM
                    db_frontstore_products_list
                    Left Join db_frontstore ON db_frontstore.id = db_frontstore_products_list.frontstore_id_fk
                    Left Join customers_detail ON db_frontstore.customers_id_fk = customers_detail.customer_id
                    Left Join customers ON customers_detail.customer_id = customers.id
                    Left Join orders_frontstore ON db_frontstore.code_order = orders_frontstore.code_order
                    WHERE
                    db_frontstore_products_list.frontstore_id_fk = 
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
            (<?php echo sprintf("%04d",$data[0]).'-'; ?>)<br>
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
           <center> ต้นฉบับใบกำกับภาษี/ใบส่งสินค้า </center>
          </th>
        </tr>
      </table>
    </div>


<div class="NameAndAddress" >

  <div style="border-radius: 5px; height: 33mm; border: 1px solid grey;padding:-1px;" >
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
          <br>
      </td>
        
      </tr>
    </table>
  </div>
  <br>


  <div style="border-radius: 5px; height: 170mm; border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;" >
      <tr style="background-color: #e6e6e6;">
        <td style="width:8%;border-bottom: 1px solid #ccc;text-align: center;" > ลำดับที่ <br> Item

 </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">รายการ<br>
Description </td>
        <td style="border-left: 1px solid #ccc;width:5%;border-bottom: 1px solid #ccc;text-align: center;"> จำนวน <br>
amt </td>
        <td style="border-left: 1px solid #ccc;width:5%;border-bottom: 1px solid #ccc;text-align: center;"> ราคา/หน่วย <br>
Unit Price </td>
        <td style="border-left: 1px solid #ccc;width:5%;border-bottom: 1px solid #ccc;text-align: center;"> PV   
        <td style="border-left: 1px solid #ccc;width:10%;border-bottom: 1px solid #ccc;text-align: center;"> จำนวนเงิน <br>
Amount </td>

      </tr>

<!-- รายการสินค้า -->
<?php 

     $P = DB::select(" 
                    SELECT
                    db_frontstore_products_list.*,
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
                    orders_frontstore.id as order_id,
                    orders_frontstore.shipping

                    FROM
                    db_frontstore_products_list
                    Left Join db_frontstore ON db_frontstore.id = db_frontstore_products_list.frontstore_id_fk
                    Left Join customers_detail ON db_frontstore.customers_id_fk = customers_detail.customer_id
                    Left Join customers ON customers_detail.customer_id = customers.id
                    Left Join orders_frontstore ON db_frontstore.code_order = orders_frontstore.code_order
                    WHERE
                    db_frontstore_products_list.frontstore_id_fk = 
                    ".$data[0]."  AND add_from=1

     ");

    $i=1;

     $Total = 0;
     $shipping = 0;

     // echo count($P);
     // exit;

    foreach ($P as $key => $v) {

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$v->product_id_fk." AND lang_id=1");

            $product_name = @$Products[0]->product_code." : ".@$Products[0]->product_name;

     ?>

          <tr>
            <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;" > <?=$i?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: left;"> <?=$product_name?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->amt?>  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->selling_price?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->pv?>  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=number_format($v->amt*$v->selling_price,2)?>  </td>
          </tr>

<?php  

    $i++; 
    $Total += $v->amt*$v->selling_price;  
    $shipping += $v->shipping ;  

  } 

  $n = 5 - $i; 

  ?>



<!-- รายการสินค้า -->
<?php 

     $P = DB::select(" 
         SELECT * from db_frontstore_products_list WHERE frontstore_id_fk = ".$data[0]." and add_from=2 GROUP BY promotion_id_fk,promotion_code
     ");

    $i= $i ;

     $Total = 0;
     $shipping = 0;

     // echo count($P);
     // exit;

     $pn = '';

    foreach ($P as $key => $v) {

            if($v->promotion_id_fk!='' && $v->promotion_code!=''){
                $promotions = DB::select(" SELECT name_thai as pro_name FROM promotions WHERE id='".$v->promotion_id_fk."' ");
                $pn =  "ชื่อโปร : ".@$promotions[0]->pro_name . " > รหัสคูปอง : ".($v->promotion_code)."</br>";
            }else{
                $promotions = DB::select(" SELECT pcode,name_thai as pro_name FROM promotions WHERE id='".$v->promotion_id_fk."' ");
                $pn =  "ชื่อโปร : ".@$promotions[0]->pro_name . " > รหัสโปร : ".(@$promotions[0]->pcode)."</br>";
            }


            $Products = DB::select("
              SELECT
              (SELECT product_code FROM products WHERE id=promotions_products.product_id_fk limit 1) as product_code,
              (SELECT product_name FROM products_details WHERE product_id_fk=promotions_products.product_id_fk and lang_id=1 limit 1) as product_name,
              (SELECT product_unit
              FROM
              dataset_product_unit
              WHERE id = promotions_products.product_unit AND  lang_id=1 ) as product_unit,
              promotions_products.product_amt
              FROM
              promotions_products
              WHERE
              promotions_products.promotion_id_fk='".$v->promotion_id_fk."'  
            ");

            $pn .= '<div class="divTable"><div class="divTableBody">';

            foreach ($Products as $key => $value) {
             $pn .=     
                  '<div class="divTableRow">
                  <div class="divTableCell">[Pro'.$value->product_code.'] '.$value->product_name.'</div>
                  <div class="divTableCell"><center>'.$value->product_amt.' x '.$v->amt.'= '.($value->product_amt*$v->amt).'</div>
                  <div class="divTableCell">'.$value->product_unit.'</div>
                  </div>
                  ';
             }

              $pn .= '</div></div>';  


     ?>

          <tr>
            <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;vertical-align: top;" > <?=$i?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: left;"> <?=$pn?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;vertical-align: top;"> <?=$v->amt?>  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;vertical-align: top;"> <?=$v->selling_price?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;vertical-align: top;"> <?=$v->pv?>  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;vertical-align: top;"> <?=number_format($v->amt*$v->selling_price,2)?>  </td>
          </tr>

<?php  

    $i++; 
    // $Total += $v->amt*$v->selling_price;  
    // $shipping += $v->shipping ;  

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


 