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



    <div class="NameAndAddress" style="" >
      <table style="border-collapse: collapse;" >
        <tr>
          <th style="text-align: left;">
            <img src="<?=public_path('images/logo2.png')?>" >
          </th>
          <th style="text-align: right;">
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
           <center> ใบเบิกสินค้า </center>
          </th>
        </tr>
      </table>
    </div>

    <?php

$packing = DB::select(" SELECT * FROM db_pick_pack_packing WHERE packing_code_id_fk=".$data[0]." GROUP BY packing_code ");


      ?>

<div class="NameAndAddress" >

  <div style="border-radius: 5px; height: 12mm; border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;" >
      <tr>
        <td style="width:30%;vertical-align: top;font-weight: bold" >
          รหัสใบเบิก / Ref.No. : {{@$packing[0]->packing_code}}
       </td>
      <td style="width:30%;vertical-align: top;font-weight: bold;" >
          วันที่ทำใบเบิก / Date : {{@$packing[0]->created_at}}
      </td>
      </tr>
    </table>
  </div>
  <br>


  <div style="border-radius: 5px; border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;" >
      <tr style="background-color: #e6e6e6;">
        <td style="width:8%;border-bottom: 1px solid #ccc;text-align: center;" > ลำดับที่ <br> Item

 </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">รายการ<br>
Description </td>
        <td style="border-left: 1px solid #ccc;width:15%;border-bottom: 1px solid #ccc;text-align: center;"> จำนวน <br>
Quantity </td>
        <td style="border-left: 1px solid #ccc;width:15%;border-bottom: 1px solid #ccc;text-align: center;"> หน่วย <br>
Unit Price </td>

      </tr>

<!-- รายการสินค้า -->
<?php
$orders = DB::select(" SELECT * FROM `db_pick_pack_packing_code` WHERE id=".$data[0]."  ");

// print_r($orders);
// echo "<hr>";
// echo $packing[0]->packing_code;
$orders_id_fk = explode(',', @$orders[0]->orders_id_fk);
$orders_id_fk = implode(',', $orders_id_fk);

$Products = DB::select("

(SELECT

db_delivery.orders_id_fk,
db_order_products_list.product_id_fk,
db_order_products_list.product_name,
db_order_products_list.product_unit_id_fk,
SUM(db_order_products_list.amt) AS amt ,
dataset_product_unit.product_unit,
db_orders.code_order,
db_orders.id as orders_id_fk

FROM `db_order_products_list`
left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
left Join db_delivery ON db_delivery.orders_id_fk = db_orders.id
left Join db_delivery_packing ON db_delivery_packing.delivery_id_fk = db_delivery.id
LEFT Join dataset_product_unit ON db_order_products_list.product_unit_id_fk = dataset_product_unit.id

WHERE type_product='product' AND db_orders.id in ($orders_id_fk)

GROUP BY db_order_products_list.product_id_fk
)

UNION

(
SELECT

db_delivery.orders_id_fk,
db_order_products_list.product_id_fk,
db_order_products_list.product_name,
db_order_products_list.product_unit_id_fk,
SUM(db_order_products_list.amt) AS amt ,
dataset_product_unit.product_unit,
db_orders.code_order,
db_orders.id as orders_id_fk

FROM `db_order_products_list`
left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
left Join db_delivery ON db_delivery.orders_id_fk = db_orders.id
left Join db_delivery_packing ON db_delivery_packing.delivery_id_fk = db_delivery.id
LEFT Join dataset_product_unit ON db_order_products_list.product_unit_id_fk = dataset_product_unit.id

WHERE type_product='promotion' AND db_orders.id in ($orders_id_fk)

GROUP BY db_order_products_list.product_id_fk
)



    ");

// print_r($p1);

if(!empty($Products)){

    $i=1;
    $total = 0;

    foreach ($Products as $key => $p) {

     ?>

          <tr>
            <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;" > <?=$i?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> <?=$p->product_name?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$p->amt?>  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$p->product_unit?> </td>
          </tr>

     <?php
    $i++;

    $total += $p->amt;

    }
  }

  ?>


          <tr>
            <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;" > </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: right;padding-right: 10px;font-weight: bold;"> รวมจำนวน </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;font-weight: bold;"> {{@$total}} </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> </td>
          </tr>



    </table>
  </div>


<br>
<br>

  <div style="border-radius: 5px; height: 28mm; border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;text-align: center;" >

      <tr>

        <td  style="border-left: 1px solid #ccc;"> ผู้เบิกสินค้า

        <br>
        <img src="" width="100" >
        <br>
        <br>
           วันที่ .........................................
         </td>


        <td style="border-left: 1px solid #ccc;"> ในนาม บริษัท ไอยรา แพลนเน็ต จำกัด
        <br>
        <img src="" width="100" >
        <br>
        <br>
      ผู้มีอำนาจลงนาม
        </td>

      </tr>


    </table>
  </div>

</div>


