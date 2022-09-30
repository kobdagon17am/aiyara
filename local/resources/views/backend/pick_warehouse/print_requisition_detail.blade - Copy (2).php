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
    padding: 3px 6px;
    word-break: break-all;
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
           <center> รายละเอียดจัดส่งสินค้า </center>
          </th>
        </tr>
      </table>
    </div>

    <?php
        $packing = DB::select(" SELECT * FROM db_pick_pack_packing WHERE delivery_id_fk=".$data[0]." and packing_code_id_fk=".$data[1]."  GROUP BY packing_code ");

        $recipient_name = '';

        // foreach ($p1 as $key => $value) {

            $delivery = DB::select(" SELECT
              db_delivery.set_addr_send_this,
              db_delivery.recipient_name,
              db_delivery.addr_send,
              db_delivery.postcode,
              db_delivery.mobile,
              db_delivery.tel_home,
              db_delivery.status_pack,
              db_delivery.receipt,
              db_delivery.id as delivery_id_fk,
              db_delivery.orders_id_fk
              FROM
              db_delivery
              WHERE
              db_delivery.id = ".@$data[0]." AND set_addr_send_this=1 ");

              $recipient_name = @$delivery[0]->recipient_name?@$delivery[0]->recipient_name:'';
              $addr_send = @$delivery[0]->addr_send." ".@$delivery[0]->postcode;
              $tel = @$delivery[0]->mobile." ".@$delivery[0]->tel_home;
              $receipt = '';

            if($delivery[0]->status_pack==1){

                  $d1 = DB::select(" SELECT * from db_delivery WHERE id=".$delivery[0]->delivery_id_fk."");
                  $d2 = DB::select(" SELECT * from db_delivery WHERE packing_code=".$d1[0]->packing_code."");
                  $arr1 = [];
                  foreach ($d2 as $key => $v) {
                    array_push( $arr1 ,$v->receipt);
                  }
                  $receipt = implode(', ',$arr1);

            }else{
                  $receipt = @$delivery[0]->receipt;
            }


    ?>

<div class="NameAndAddress" >

  <div style="border-radius: 5px; border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;" >

      <tr>
        <td style="width:30%;vertical-align: top;font-weight: bold" >
          รหัสอ้างอิง หรือ ลำดับ : {{@$packing[0]->packing_code}}
       </td>
      <td style="width:30%;vertical-align: top;font-weight: bold;" >
          วันที่ : {{@$packing[0]->created_at}}
      </td>
      </tr>

      <tr>
        <td style="width:30%;vertical-align: top;font-weight: bold" >
          ชื่อผู้รับสินค้า : {{@$recipient_name}}
       </td>
      <td style="width:30%;vertical-align: top;font-weight: bold;" >
          เบอร์โทรศัพท์ : {{$tel}}
      </td>
      </tr>

      <tr>
        <td colspan="2" style="width:30%;vertical-align: top;font-weight: bold" >
          ที่อยู่  : {{@$addr_send}}
       </td>

      </tr>

      <tr>
        <td colspan="2" style="width:30%;vertical-align: top;font-weight: bold" >
          เลขที่ใบเสร็จ  : {{@$receipt}}
       </td>

      </tr>

      <tr>
        <td colspan="2" style="width:30%;vertical-align: top;font-weight: bold" >
         [ รายการสินค้า ]
       </td>
      </tr>


<?php
    // require(app_path().'/Models/MyFunction.php');

    $arr_orders_id = [0];

    if($delivery[0]->status_pack==1){

          $d1 = DB::select(" SELECT * from db_delivery WHERE id=".$delivery[0]->delivery_id_fk."");
          $d2 = DB::select(" SELECT * from db_delivery WHERE packing_code=".$d1[0]->packing_code."");
          $arr_orders_id = [];
          foreach ($d2 as $key => $v) {
            array_push( $arr_orders_id ,$v->orders_id_fk);
          }

    }else{
          array_push($arr_orders_id,@$delivery[0]->orders_id_fk);
    }



// echo count($arr3);
for ($z=0; $z < count($arr_orders_id) ; $z++) {
    // code...

$id = @$arr_orders_id[$z];

$n = 22;
$limit = 10;

$sRow = \App\Models\Backend\Frontstore::find($id);

$cnt01 = DB::select(" SELECT count(*) as cnt FROM `db_order_products_list` WHERE frontstore_id_fk=$id AND type_product='product'; ");
$cnt02 = DB::select(" SELECT count(*) as cnt FROM `db_order_products_list` WHERE frontstore_id_fk=$id AND type_product='promotion'; ");
$cnt03 = DB::select(" SELECT count(*) as cnt FROM `db_order_products_list` WHERE frontstore_id_fk=$id AND type_product<>'product' AND type_product<>'promotion'; ");
$cnt04 = DB::select(" SELECT count(*) as cnt FROM `db_order_products_list` WHERE frontstore_id_fk=$id AND type_product='promotion' AND promotion_code is not NULL; ");

// promotions_products
$promotion_id_fk = DB::select(" SELECT promotion_id_fk FROM `db_order_products_list` WHERE frontstore_id_fk=$id AND type_product='promotion'; ");
$arr_promotion_id_fk = [];
foreach ($promotion_id_fk as $key => $value) {
  array_push($arr_promotion_id_fk, $value->promotion_id_fk);
}

$arr_promotion_id = implode(',', $arr_promotion_id_fk);
// echo $arr_promotion_id;
$arr_promotion_id = !empty($arr_promotion_id) ? $arr_promotion_id : 0;
$cnt05 = DB::select(" SELECT count(*) as cnt FROM `promotions_products` WHERE promotion_id_fk in ($arr_promotion_id) ; ");

// Product List All
$TABLE_tmp = 'temp_z01_print_frontstore_print_receipt_02_tmp'.\Auth::user()->id;
DB::select(" DROP TABLE IF EXISTS $TABLE_tmp ; ");
DB::select("
    CREATE TEMPORARY TABLE $TABLE_tmp (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `a` text,
      `b` text,
      `c` text,
      `d` text,
      `e` text,
      `f` text,
      `g` text,
      PRIMARY KEY (`id`) USING BTREE
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='เป็นตารางชั่วคราว ';
");


$sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total from db_order_products_list WHERE frontstore_id_fk=".$id." GROUP BY frontstore_id_fk ");
$sFrontstorePVtotal = DB::select(" select SUM(total_pv) as pv_total from db_order_products_list WHERE frontstore_id_fk=".$id." GROUP BY frontstore_id_fk ");
$sFrontstoreData = DB::select(" select * from db_order_products_list ");


$shipping_price = @$sRow->shipping_price?@$sRow->shipping_price:0;


$shipping = DB::select("
    SELECT
    db_orders.delivery_location,
    db_orders.sentto_branch_id,
    dataset_delivery_location.txt_desc,
    db_orders.shipping_price,
    branchs.b_name
    FROM
    db_orders
    Left Join dataset_delivery_location ON db_orders.delivery_location = dataset_delivery_location.id
    Left Join branchs ON db_orders.sentto_branch_id = branchs.id
    WHERE
    db_orders.id = '$id'
 ");

$shipping_desc = @$shipping[0]->shipping_price?@$shipping[0]->shipping_price:0;
// $shipping_desc = $shipping_desc==0 ? '0':@$shipping[0]->txt_desc.'/ ค่าจัดส่ง '.(number_format(@$shipping_price,0));

if(@$shipping[0]->delivery_location==4){
    $shipping_desc = 'จัดส่งพร้อมบิลอื่น';
}else if(@$shipping[0]->delivery_location==0){
    $shipping_desc = 'รับสินค้าด้วยตัวเอง สาขา: '.@$shipping[0]->b_name;
}else if(@$shipping[0]->delivery_location==2){
    $shipping_desc = 'จัดส่ง ปณ./ขนส่ง / '.number_format(@$shipping_price,0);
}else if(@$shipping[0]->delivery_location==3){
    $shipping_desc = 'ตามที่อยู่ที่ระบุ / '.number_format(@$shipping_price,0);
}else{
    $shipping_desc = '0';
}

$pv_total = @$sRow->pv_total?@$sRow->pv_total:0;

$total_price = DB::select(" select SUM(total_price) as total from db_order_products_list WHERE frontstore_id_fk=".$id." GROUP BY frontstore_id_fk ");
// echo @$total_price[0]->total;

$total_price = str_replace(',','',number_format(@$total_price[0]->total,2));

// ถ้าซื้อ ประเภทการซื้อ เป็น Gift Voucher => total_price ต้องลบออกจากราคาซื้อ
// ถ้า gift_voucher_price > ราคาซื้อ ให้ สรุป total = 0
$gift_voucher = DB::select(" SELECT gift_voucher_price FROM db_orders where id=$id and purchase_type_id_fk=5 AND gift_voucher_price>0 ");
if(!empty($gift_voucher)){
    if($gift_voucher[0]->gift_voucher_price > $total_price){

          $total_price = 0 ;
          $vat = 0 ;

    }else{

          $total_price = str_replace(',','',$total_price) - str_replace(',','',$gift_voucher[0]->gift_voucher_price) ;
          $vat = intval($total_price) - (intval($total_price)/1.07) ;
    }


}else{

    $vat = intval(@$sFrontstoreDataTotal[0]->total) - (intval(@$sFrontstoreDataTotal[0]->total)/1.07) ;

}


 $sTable = DB::select("
    SELECT * from db_order_products_list WHERE frontstore_id_fk = $id and add_from=1 UNION
    SELECT * from db_order_products_list WHERE frontstore_id_fk = $id and add_from=2 GROUP BY promotion_id_fk,promotion_code
    ORDER BY add_from,id
");

foreach ($sTable as $key => $row) {

  $product_name = '';

        if($row->type_product=="course"){
               $product_name = $row->product_name;
               DB::select(" INSERT INTO $TABLE_tmp VALUES (null,null, '$product_name',  null, '".@$row->selling_price."', '".@$row->total_pv."pv', '".@$row->amt."', '".@$row->total_price."'); ");
        }else{


            if(!empty($row->product_id_fk) && $row->add_from==1){

                $Products = DB::select("SELECT products.id as product_id,
                products.product_code,
                (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
                FROM
                products_details
                Left Join products ON products_details.product_id_fk = products.id
                WHERE products.id=".$row->product_id_fk." AND lang_id=1");

                 $product_name = @$Products[0]->product_code." : ".@$Products[0]->product_name;
                 if(strlen($product_name)>100){
                  $product_name = iconv_substr($product_name,0,100, "UTF-8")."...";
                 }
                 DB::select(" INSERT INTO $TABLE_tmp VALUES (null,null, '$product_name',  null, '".@$row->selling_price."', '".@$row->total_pv."pv', '".@$row->amt."', '".@$row->total_price."'); ");

                // หา max time_pay ก่อน
                 $r_ch01 = DB::select("SELECT time_pay FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$row->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$data[1]." order by time_pay desc limit 1  ");
              // Check ว่ามี status=2 ? (ค้างจ่าย)
                 $r_ch02 = DB::select("SELECT * FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$row->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$data[1]." and time_pay=".$r_ch01[0]->time_pay." and status=2 ");
                 if(count($r_ch02)>0){
                    $r_ch_t = '&nbsp;(รายการนี้ค้างจ่ายในรอบนี้ สินค้าในคลังมีไม่เพียงพอ)';
                    DB::select(" INSERT INTO $TABLE_tmp VALUES (null,null, '$r_ch_t',  null, null, null, null, null); ");
                 }else{
                   $r_ch_t = '';
                 }


            }else{


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
                  promotions_products.promotion_id_fk='".$row->promotion_id_fk."'
                ");

                    $promotions = DB::select(" SELECT name_thai as pro_name FROM promotions WHERE id='".$row->promotion_id_fk."' ");

                     $product_name =  @$promotions[0]->pro_name;
                     if(strlen($product_name)>100){
                      $product_name = iconv_substr($product_name,0,100, "UTF-8")."...";
                     }
                    DB::select(" INSERT INTO $TABLE_tmp VALUES (null,null, '$product_name',  null, '".@$row->selling_price."', '".@$row->total_pv."pv', '".@$row->amt."', '".number_format(@$row->total_price,2)."'); ");


                if($row->promotion_id_fk!='' && $row->promotion_code!=''){

                      $product_name_pro = '';

                      foreach ($Products as $key => $value) {

                         if(strlen($value->product_name)>20){
                          $product_name_pro = iconv_substr($value->product_name,0,20, "UTF-8")."...";
                          }else{
                          $product_name_pro = $value->product_name;
                         }

                         $product_name =
                            '[Pro'.$value->product_code.'] '.$product_name_pro.'
                            '.$value->product_amt.' x '.$row->amt.' =
                            '.($value->product_amt*$row->amt).'
                            '.$value->product_unit.'
                            ';

                             DB::select(" INSERT INTO $TABLE_tmp VALUES (null,null, '$product_name',  null,  null ,  null,  null,  null ); ");
                       }

                      $product_name =  "รหัสคูปอง : ".($row->promotion_code);
                      DB::select(" INSERT INTO $TABLE_tmp VALUES (null,null, '$product_name',  null,  null ,  null,  null,  null ); ");


                }else{
                      $product_name_pro = '';
                      foreach ($Products as $key => $value) {

                        if(strlen($value->product_name)>20){
                          $product_name_pro = iconv_substr($value->product_name,0,20, "UTF-8")."...";
                         }else{
                          $product_name_pro = $value->product_name;
                         }

                       $product_name =
                            '[Pro'.$value->product_code.'] '.$product_name_pro.'
                            '.$value->product_amt.' x '.$row->amt.' =
                            '.($value->product_amt*$row->amt).'
                            '.$value->product_unit.'
                            ';

                             DB::select(" INSERT INTO $TABLE_tmp VALUES (null,null, '$product_name',  null,  null ,  null,  null,  null ); ");
                       }

                }


           }

        }

}

// ถ้าซื้อ ประเภทการซื้อ เป็น Gift Voucher ให้เพิ่มเข้าไปอีก 1 row
$gift_voucher = DB::select(" SELECT gift_voucher_price FROM db_orders where id=$id and purchase_type_id_fk=5 AND gift_voucher_price>0 ");
if(!empty($gift_voucher)){
    $product_name = 'Gift Voucher '.(number_format(@$gift_voucher[0]->gift_voucher_price,0)).'';
    $gift_voucher_price1 = (number_format(@$gift_voucher[0]->gift_voucher_price*(-1),0));
    $gift_voucher_price2 = (number_format(@$gift_voucher[0]->gift_voucher_price*(-1),2));
    DB::select(" INSERT INTO $TABLE_tmp VALUES (null,null, '$product_name',  null,  '$gift_voucher_price1' ,  '0pv',  1,  '$gift_voucher_price2'); ");
}


$ThisCustomer = DB::select(" select * from customers where id=".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)." ");
// ของแถม
$check_giveaway = \App\Http\Controllers\Frontend\Fc\GiveawayController::check_giveaway(@$sRow->purchase_type_id_fk?@$sRow->purchase_type_id_fk:0,@$ThisCustomer[0]->user_name,@$sRow->pv_total);
// dd(@$check_giveaway);

// สินค้าแถม @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

          if(@$check_giveaway){
               // dd($id);
               $sGiveaway = DB::select(" SELECT * FROM `db_order_products_list_giveaway` where order_id_fk in ($id) AND type_product='giveaway_product' ");
               // dd($sGiveaway);

                if(@$sGiveaway){

                    DB::select(" INSERT INTO $TABLE_tmp VALUES (null,null, 'สินค้าแถม',  null,  '' ,  '',  '',  ''); ");

                      foreach ($sGiveaway as $key => $v) {

                        $product_name =
                        ' &nbsp;&nbsp;&nbsp; - '.$v->product_name.' =
                        '.($v->product_amt*$v->free).'
                        '.$v->product_unit_name.'
                        ';

                         DB::select(" INSERT INTO $TABLE_tmp VALUES (null,null, '$product_name',  null,  null ,  null,  null,  null ); ");

                       }
                }

         }

// สินค้าแถม @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
          if(@$check_giveaway){
               // dd($id);
               $sGiveaway = DB::select(" SELECT * FROM `db_order_products_list_giveaway` where order_id_fk in ($id) AND type_product='giveaway_gv' ");
               // dd($sGiveaway);

                if(@$sGiveaway){

                    DB::select(" INSERT INTO $TABLE_tmp VALUES (null,null, 'แถม AiVoucher',  null,  '' ,  '',  '',  ''); ");

                      foreach ($sGiveaway as $key => $v) {

                        $product_name =
                        ' &nbsp;&nbsp;&nbsp; - AiVoucher =
                        '.($v->gv_free*$v->free).'
                         บาท
                        ';

                         DB::select(" INSERT INTO $TABLE_tmp VALUES (null,null, '$product_name',  null,  null ,  null,  null,  null ); ");

                       }
                }

         }
// สินค้าแถม @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@


$cnt_all = DB::select(" SELECT count(*) as cnt FROM $TABLE_tmp ");
// echo $cnt_all[0]->cnt;
$amt_page = ceil($cnt_all[0]->cnt/$limit);
// echo $amt_page;
// exit;

// $amt_page = 1;
// $amt_page = 2;
// $amt_page = 3;
// $amt_page = 4;


$TABLE = 'temp_z01_print_frontstore_print_receipt_02'.\Auth::user()->id;
DB::select(" DROP TABLE IF EXISTS $TABLE ; ");
DB::select("
    CREATE TEMPORARY TABLE $TABLE (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `a` text,
      `b` text,
      `c` text,
      `d` text,
      `e` text,
      `f` text,
      `g` text,
      PRIMARY KEY (`id`) USING BTREE
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='เป็นตารางชั่วคราว เอาไว้ประมวลผล พิมพ์ใบเสร็จ';
");

for ($i=0; $i < ($amt_page*$n) ; $i++) {
    DB::select(" INSERT IGNORE INTO $TABLE (a) VALUES ('&nbsp;') ");
}

// exit;

   $db_orders = DB::select("
            SELECT db_orders.*,branchs.b_code as branch_code
            FROM db_orders
            LEFT Join branchs ON db_orders.branch_id_fk = branchs.id
            WHERE
            db_orders.id = '$id'
       ");

    // if(@$sRow->delivery_location!=0){
    //    $branch_code = $db_orders[0]->branch_code;
    // }else{
    //    $branch_code = '';
    // }
  /*
   `delivery_location` int(1) DEFAULT '0' COMMENT 'ที่อยู่ผู้รับ\r\n>1=ที่อยู่ตามบัตร ปชช.>customers_address_card,\r\n2=ที่อยู่ตามที่ลงทะเบียนในระบบ>customers_detail,\r\n3=ที่อยู่กำหนดเอง>customers_addr_frontstore,\r\n4=จัดส่งพร้อมบิลอื่น,\r\n5=ส่งแบบพิเศษ/พรีเมี่ยม',
   */
if(!empty($db_orders[0]->action_user)){
    $action_user = DB::select(" select * from ck_users_admin where id=".@$db_orders[0]->action_user." ");
    $action_user_name = @$action_user[0]->name;
}else{
    $action_user_name = "-";
}


     $tel = '';
     $cus = DB::select("
        SELECT
        customers.user_name,
        customers.prefix_name,
        customers.first_name,
        customers.last_name
        FROM
        db_orders
        Left Join customers ON db_orders.customers_id_fk = customers.id
        where db_orders.id = ".$id."
          ");

       $cus_user_name = @$cus[0]->user_name;
       $cus_name = @$cus[0]->prefix_name.@$cus[0]->first_name.' '.@$cus[0]->last_name;

// ๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑

                 $CusAddrFrontstore = \App\Models\Backend\CusAddrFrontstore::where('frontstore_id_fk',$id)->get();

                 $cus = DB::select("
                    SELECT
                    customers.user_name,
                    customers.prefix_name,
                    customers.first_name,
                    customers.last_name
                    FROM
                    db_orders
                    Left Join customers ON db_orders.customers_id_fk = customers.id
                    where db_orders.id = ".$id."
                      ");

                      if(@$sRow->delivery_location==0 && @$sRow->purchase_type_id_fk!=6 ){
                         // echo "<br>( รับสินค้าด้วยตัวเอง ) ";
                      }else{

                        if(@$sRow->delivery_location==1){

                          $addr = DB::select(" SELECT
                                      customers_address_card.id,
                                      customers_address_card.customer_id,
                                      customers_address_card.card_house_no,
                                      customers_address_card.card_house_name,
                                      customers_address_card.card_moo,
                                      customers_address_card.card_zipcode,
                                      customers_address_card.card_soi,
                                      -- customers_detail.amphures_id_fk,
                                      -- customers_detail.district_id_fk,
                                      -- customers_detail.road,
                                      -- customers_detail.province_id_fk,
                                      customers_address_card.created_at,
                                      customers_address_card.updated_at,
                                      dataset_provinces.name_th AS provname,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_districts.name_th AS tamname,
                                      customers.prefix_name,
                                      customers.first_name,
                                      customers.last_name
                                      FROM
                                      customers_address_card
                                      Left Join dataset_provinces ON customers_address_card.card_province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_address_card.card_amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_address_card.card_district_id_fk = dataset_districts.id
                                      Left Join customers ON customers_address_card.customer_id = customers.id
                                      where customers_address_card.customer_id = ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)."

                                     ");
                          // print_r($addr);

                          if(@$addr[0]->provname!=''){

                              @$address = "";
                              @$address .=  @$addr[0]->card_house_no ;
                              @$address .=  " ต.". @$addr[0]->tamname . "  ";
                              @$address .=  " อ.". @$addr[0]->ampname;
                              @$address .=  " จ.". @$addr[0]->provname;
                              @$address .=  " ". @$addr[0]->card_zipcode ;

                              // echo @$address;
                              if(!empty(@$addr[0]->tamname) && !empty(@$addr[0]->ampname) && !empty(@$addr[0]->provname)){
                              }else{
                                 @$address = null;
                              }

                          }else{

                                $addr = DB::select(" SELECT
                                    customers_address_card.id,
                                    customers_address_card.customer_id,
                                    customers_address_card.card_house_no,
                                    customers_address_card.card_house_name,
                                    customers_address_card.card_moo,
                                    customers_address_card.card_zipcode,
                                    customers_address_card.card_soi,
                                    -- customers_detail.amphures_id_fk,
                                    -- customers_detail.district_id_fk,
                                    -- customers_detail.road,
                                    -- customers_detail.province_id_fk,
                                    customers_address_card.created_at,
                                    customers_address_card.updated_at,
                                    customers.prefix_name,
                                    customers.first_name,
                                    customers.last_name,
                                             dataset_amphures.name_th AS amp_name,
                                             dataset_districts.name_th AS tambon_name,
                                             dataset_provinces.name_th AS province_name
                                    FROM
                                    customers_address_card
                                    Left Join customers ON customers_address_card.customer_id = customers.id
                                    Left Join customers_detail ON customers.id = customers_detail.customer_id
                                    Left Join dataset_amphures ON customers_detail.amphures_id_fk = dataset_amphures.id
                                             Left Join dataset_districts ON customers_detail.district_id_fk = dataset_districts.id
                                             Left Join dataset_provinces ON customers_detail.province_id_fk = dataset_provinces.id
                                    Where customers_address_card.customer_id = ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)."

                                     ");

                              if($addr){
                                  @$address =  "เลขที่ ". @$addr[0]->card_house_no." ". @$addr[0]->card_house_name."";
                                  @$address .=  " หมู่ ". @$addr[0]->card_moo;
                                  @$address .=  " ซอย ". @$addr[0]->card_soi;
                                  @$address .=  " ถนน ". @$addr[0]->card_road;
                                  @$address .=  " ต.". @$addr[0]->tambon_name;
                                  @$address .=  " อ.". @$addr[0]->amp_name;
                                  @$address .=  " จ.". @$addr[0]->province_name;
                                  @$address .=  " ". @$addr[0]->card_zipcode ;

                                  // echo @$address;
                                  if(!empty(@$addr[0]->tambon_name) && !empty(@$addr[0]->amp_name) && !empty(@$addr[0]->province_name)){
                                  }else{
                                     @$address = null;
                                  }
                              }else{
                                @$address = null;
                              }


                        }

                      }

                         if(@$sRow->delivery_location==2){

                                @$addr = DB::select("SELECT
                                      customers_detail.customer_id,
                                      customers_detail.house_no,
                                      customers_detail.house_name,
                                      customers_detail.moo,
                                      customers_detail.zipcode,
                                      customers_detail.soi,
                                      -- customers_detail.amphures_id_fk,
                                      -- customers_detail.district_id_fk,
                                      -- customers_detail.road,
                                      -- customers_detail.province_id_fk,
                                      customers.prefix_name,
                                      customers.first_name,
                                      customers.last_name,
                                      dataset_provinces.name_th AS provname,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_districts.name_th AS tamname
                                      FROM
                                      customers_detail
                                      Left Join customers ON customers_detail.customer_id = customers.id
                                      Left Join dataset_provinces ON customers_detail.province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_detail.amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_detail.district_id_fk = dataset_districts.id
                                      WHERE customers_detail.customer_id =
                                       ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)." ");
                                // print_r(@$addr);
                                @$address =  @$addr[0]->house_no?" เลขที่ ". @$addr[0]->house_no:"". " หมู่บ้าน ". @$addr[0]->house_name;
                                @$address .= " ต.". @$addr[0]->tamname. " ";
                                @$address .= " อ.". @$addr[0]->ampname;
                                @$address .= " จ.". @$addr[0]->provname;
                                @$address .= " ". @$addr[0]->zipcode;

                                // echo @$address;
                                if(!empty(@$addr[0]->tamname) && !empty(@$addr[0]->ampname) && !empty(@$addr[0]->provname)){
                                }else{
                                    @$address = null;
                                }

                        }




                        if(@$sRow->delivery_location==3){

                                @$addr = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                                      dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname
                                      from customers_addr_frontstore
                                      Left Join dataset_provinces ON customers_addr_frontstore.province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_addr_frontstore.amphur_code = dataset_amphures.id
                                      Left Join dataset_districts ON customers_addr_frontstore.tambon_code = dataset_districts.id
                                      where customers_addr_frontstore.id = ".(@$CusAddrFrontstore[0]->id?$CusAddrFrontstore[0]->id:0)." ");
                                // print_r(@$addr);
                                @$address = @$addr[0]->recipient_name;
                                @$address .= ' '.@$addr[0]->addr_no;
                                @$address .= ",". @$addr[0]->tamname. " ";
                                @$address .= ",". @$addr[0]->ampname;
                                @$address .= ",". @$addr[0]->provname;
                                @$address .= ",". @$addr[0]->zip_code;
                                // @$address .= " ". @$addr[0]->tel_mobile ? '<br>Tel. '. @$addr[0]->tel_mobile:'' ;
                                // @$address .= " ". @$addr[0]->tel_home?', '.@$addr[0]->tel_home:'' ;

                                if(!empty(@$addr[0]->tamname) && !empty(@$addr[0]->ampname) && !empty(@$addr[0]->provname)){
                                }else{
                                    @$address = null;
                                }

                                // echo @$address;
                                if(!empty(@$addr[0]->tel_mobile)){
                                     $tel = 'Tel. '. @$addr[0]->tel_mobile . (@$addr[0]->tel_home?', '.@$addr[0]->tel_home:'') ;
                                }

                        }

                      }
   $address = !empty($address) ? 'ชื่อ-ที่อยู่ผู้รับ: '. $address : NULL;
// ๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑

    $db_orders = DB::select("
                SELECT db_orders.*,branchs.b_code as branch_code,customers.user_name
                FROM
                db_orders
                Left Join branchs ON db_orders.branch_id_fk = branchs.id
                Left Join customers ON db_orders.aistockist = customers.id
            WHERE
            db_orders.id = '$id'
       ");

    // if(@$sRow->delivery_location!=0){
    //    $branch_code = $db_orders[0]->branch_code;
    // }else{
    //    $branch_code = '';
    // }

    if(!empty($db_orders[0]->action_user)){
        $action_user = DB::select(" select * from ck_users_admin where id=".@$db_orders[0]->action_user." ");
        $action_user_name = @$action_user[0]->name;
    }else{
        $action_user_name = "-";
    }

    $aistockist = @$db_orders[0]->user_name ? @$db_orders[0]->user_name : '-';

    $agency = DB::select("
                SELECT customers.user_name
                FROM
                db_orders
                Left Join branchs ON db_orders.branch_id_fk = branchs.id
                Left Join customers ON db_orders.agency = customers.id
            WHERE
            db_orders.id = '$id'
       ");

    $agency = @$agency[0]->user_name ? @$agency[0]->user_name : '-';

    // if(@$sRow->delivery_location!=0){
    //    $branch_code = $db_orders[0]->branch_code;
    // }else{
    //    $branch_code = '';
    // }

    $pay_type = '';

    $pay_type = DB::select("

            select
            db_orders.pay_type_id_fk,
            db_orders.credit_price,
            db_orders.transfer_price,
            db_orders.fee_amt,
            db_orders.aicash_price,
            db_orders.cash_pay,
            db_orders.gift_voucher_price,
            dataset_pay_type.detail as pay_type
            from db_orders Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
            WHERE db_orders.id=".$id."

        ");
/*
5   เงินสด
6   เงินสด + Ai-Cash
7   เครดิต + เงินสด
8   เครดิต + เงินโอน
9   เครดิต + Ai-Cash
10  เงินโอน + เงินสด
11  เงินโอน + Ai-Cash
*/


    if(@$pay_type[0]->pay_type_id_fk==10){ // 10  เงินโอน + เงินสด

        if(@$pay_type[0]->transfer_price>0 && @$pay_type[0]->cash_pay==0){
            $pay_type = 'เงินโอน: '.@$pay_type[0]->transfer_price;
        }elseif(@$pay_type[0]->transfer_price>0 && @$pay_type[0]->cash_pay>0){
            $pay_type = 'เงินโอน: '.@$pay_type[0]->transfer_price.' + เงินสด: '.@$pay_type[0]->cash_pay;
        }elseif(@$pay_type[0]->transfer_price==0 && @$pay_type[0]->cash_pay>0){
            $pay_type = 'เงินสด: '.@$pay_type[0]->cash_pay;
        }else{
            $pay_type = 'เงินโอน: '.@$pay_type[0]->transfer_price.' + เงินสด: '.@$pay_type[0]->cash_pay;
        }

    }else if(@$pay_type[0]->pay_type_id_fk==6){ // 6   เงินสด + Ai-Cash
        if(@$pay_type[0]->cash_pay>0 && @$pay_type[0]->aicash_price==0){
            $pay_type = 'เงินสด: '.@$pay_type[0]->transfer_price;
        }elseif(@$pay_type[0]->cash_pay>0 && @$pay_type[0]->aicash_price>0){
            $pay_type = 'เงินสด: '.@$pay_type[0]->cash_pay.' + Ai-Cash: '.@$pay_type[0]->aicash_price;
        }elseif(@$pay_type[0]->cash_pay==0 && @$pay_type[0]->aicash_price>0){
            $pay_type = 'Ai-Cash: '.@$pay_type[0]->aicash_price;
        }else{
            $pay_type = 'เงินสด: '.@$pay_type[0]->cash_pay.' + Ai-Cash: '.@$pay_type[0]->aicash_price;
        }

    }else if(@$pay_type[0]->pay_type_id_fk==7){ // 7   เครดิต + เงินสด
        if(@$pay_type[0]->credit_price>0 && @$pay_type[0]->cash_pay==0){
            $pay_type = 'เครดิต: '.@$pay_type[0]->credit_price.' ค่าธรรมเนียม: '.@$pay_type[0]->fee_amt;
        }elseif(@$pay_type[0]->credit_price>0 && @$pay_type[0]->cash_pay>0){
            $pay_type = 'เครดิต: '.@$pay_type[0]->credit_price.' ค่าธรรมเนียม: '.@$pay_type[0]->fee_amt.' + เงินสด: '.@$pay_type[0]->cash_pay;
        }elseif(@$pay_type[0]->credit_price==0 && @$pay_type[0]->cash_pay>0){
            $pay_type = 'เงินสด: '.@$pay_type[0]->cash_pay;
        }else{
            $pay_type = 'เครดิต: '.@$pay_type[0]->credit_price.' ค่าธรรมเนียม: '.@$pay_type[0]->fee_amt.' + เงินสด: '.@$pay_type[0]->cash_pay;
        }

    }else if(@$pay_type[0]->pay_type_id_fk==8){ // 8   เครดิต + เงินโอน
        if(@$pay_type[0]->credit_price>0 && @$pay_type[0]->transfer_price==0){
            $pay_type = 'เครดิต: '.@$pay_type[0]->credit_price.' ค่าธรรมเนียม: '.@$pay_type[0]->fee_amt;
        }elseif(@$pay_type[0]->credit_price>0 && @$pay_type[0]->transfer_price>0){
            $pay_type = 'เครดิต: '.@$pay_type[0]->credit_price.' ค่าธรรมเนียม: '.@$pay_type[0]->fee_amt.' + เงินโอน: '.@$pay_type[0]->transfer_price;
        }elseif(@$pay_type[0]->credit_price==0 && @$pay_type[0]->transfer_price>0){
            $pay_type = 'เงินโอน: '.@$pay_type[0]->transfer_price;
        }else{
            $pay_type = 'เครดิต: '.@$pay_type[0]->credit_price.' ค่าธรรมเนียม: '.@$pay_type[0]->fee_amt.' + เงินโอน: '.@$pay_type[0]->transfer_price;
        }

    }else if(@$pay_type[0]->pay_type_id_fk==9){ // 9   เครดิต + Ai-Cash
        if(@$pay_type[0]->credit_price>0 && @$pay_type[0]->aicash_price==0){
            $pay_type = 'เครดิต: '.@$pay_type[0]->credit_price.' ค่าธรรมเนียม: '.@$pay_type[0]->fee_amt;
        }elseif(@$pay_type[0]->credit_price>0 && @$pay_type[0]->aicash_price>0){
            $pay_type = 'เครดิต: '.@$pay_type[0]->credit_price.' ค่าธรรมเนียม: '.@$pay_type[0]->fee_amt.' + Ai-Cash: '.@$pay_type[0]->aicash_price;
        }elseif(@$pay_type[0]->credit_price==0 && @$pay_type[0]->aicash_price>0){
            $pay_type = 'Ai-Cash: '.@$pay_type[0]->aicash_price;
        }else{
            $pay_type = 'เครดิต: '.@$pay_type[0]->credit_price.' ค่าธรรมเนียม: '.@$pay_type[0]->fee_amt.' + Ai-Cash: '.@$pay_type[0]->aicash_price;
        }

    }else if(@$pay_type[0]->pay_type_id_fk==11){ // 11   เงินโอน + Ai-Cash

        if(@$pay_type[0]->transfer_price>0 && @$pay_type[0]->aicash_price==0){
            $pay_type = 'เงินโอน: '.@$pay_type[0]->transfer_price;
        }elseif(@$pay_type[0]->transfer_price>0 && @$pay_type[0]->aicash_price>0){
            $pay_type = 'เงินโอน: '.@$pay_type[0]->transfer_price.' + Ai-Cash: '.@$pay_type[0]->aicash_price;
        }elseif(@$pay_type[0]->transfer_price==0 && @$pay_type[0]->aicash_price>0){
            $pay_type = 'Ai-Cash: '.@$pay_type[0]->aicash_price;
        }else{
            $pay_type = 'เงินโอน: '.@$pay_type[0]->transfer_price.' + Ai-Cash: '.@$pay_type[0]->aicash_price;
        }


    }else{ // 5   เงินสด
        $pay_type = @$pay_type[0]->pay_type.': '.number_format(@$total_price,2);
    }



    $m = 1 ;
    for ($i=0; $i < $amt_page ; $i++) {
        // DB::select(" UPDATE $TABLE SET a = '$branch_code' WHERE id = (($n*$i)+1) ; ");
        DB::select(" UPDATE $TABLE SET a = '&nbsp;' WHERE id = (($n*$i)+1) ; ");
        DB::select(" UPDATE $TABLE SET a = '$cus_user_name' WHERE id = (($n*$i)+2) ; ");
        DB::select(" UPDATE $TABLE SET a = '$cus_name' WHERE id = (($n*$i)+3) ; ");
        DB::select(" UPDATE $TABLE SET a = '".(@$sRow->code_order?@$sRow->code_order:0)."' WHERE id = (($n*$i)+4) ; ");
        if(@$sRow->approve_date!=''){
DB::select(" UPDATE $TABLE SET a = '".date("d-m-Y",strtotime(@$sRow->approve_date))."' WHERE id = (($n*$i)+5) ; ");
}else{
DB::select(" UPDATE $TABLE SET a = 'บิลยังไม่อนุมัติ' WHERE id = (($n*$i)+5) ; ");
}

        // รายการสินค้า
        if($m==1){
        $L = 1 ;
        for ($k=0; $k < $limit ; $k++) {
              $product_list = DB::select(" SELECT * FROM $TABLE_tmp where id=$L ");
               foreach ($product_list as $key => $value) {
                   DB::select(" UPDATE $TABLE SET
                    a = $L,
                    b = '$value->b',
                    c = '$value->c',
                    d = '$value->d',
                    e = '$value->e',
                    f = '$value->f',
                    g = '$value->g'
                    WHERE id = (($L-1)+6) ; ");
              }
              $L++;
        }
      }

        if($m==2){
                $L2 = $limit+1;
                for ($k=0; $k < $limit ; $k++) {
                      $product_list = DB::select(" SELECT * FROM $TABLE_tmp where id=$L2 ");
                       foreach ($product_list as $key => $value) {
                           DB::select(" UPDATE $TABLE SET
                            a = $L2,
                            b = '$value->b',
                            c = '$value->c',
                            d = '$value->d',
                            e = '$value->e',
                            f = '$value->f',
                            g = '$value->g'
                            WHERE id = (($L2-1)+18) ; ");
                      }
                      $L2++;
                }

        }

        if($m==3){
                $L3 = ($limit*2)+1;
                for ($k=0; $k < $limit ; $k++) {
                      $product_list = DB::select(" SELECT * FROM $TABLE_tmp where id=$L3 ");
                       foreach ($product_list as $key => $value) {
                           DB::select(" UPDATE $TABLE SET
                            a = $L3,
                            b = '$value->b',
                            c = '$value->c',
                            d = '$value->d',
                            e = '$value->e',
                            f = '$value->f',
                            g = '$value->g'
                            WHERE id = (($L3-1)+30) ; ");
                      }
                      $L3++;
                }

        }


        if($m==4){
                $L4 = ($limit*3)+1;
                for ($k=0; $k < $limit ; $k++) {
                      $product_list = DB::select(" SELECT * FROM $TABLE_tmp where id=$L4 ");
                       foreach ($product_list as $key => $value) {
                           DB::select(" UPDATE $TABLE SET
                            a = $L4,
                            b = '$value->b',
                            c = '$value->c',
                            d = '$value->d',
                            e = '$value->e',
                            f = '$value->f',
                            g = '$value->g'
                            WHERE id = (($L4-1)+42) ; ");
                      }
                      $L4++;
                }

        }

        if($m==5){
                $L5 = ($limit*4)+1;
                for ($k=0; $k < $limit ; $k++) {
                      $product_list = DB::select(" SELECT * FROM $TABLE_tmp where id=$L5 ");
                       foreach ($product_list as $key => $value) {
                           DB::select(" UPDATE $TABLE SET
                            a = $L5,
                            b = '$value->b',
                            c = '$value->c',
                            d = '$value->d',
                            e = '$value->e',
                            f = '$value->f',
                            g = '$value->g'
                            WHERE id = (($L5-1)+54) ; ");
                      }
                      $L5++;
                }

        }

        $m++;
        $rPt = DB::select(" SELECT * FROM dataset_orders_type where id=".$sRow->purchase_type_id_fk." ");

        $purchase_type = "ประเภทการซื้อ : ".$rPt[0]->orders_type;

        DB::select(" UPDATE $TABLE SET a = 'REF : [ $id ] AG : [ $agency ] SK : [ $aistockist ] คะแนนครั้งนี้ : [ ".number_format(@$pv_total,0)." pv ] $purchase_type ' WHERE id = (($n*$i)+16) ; ");

        DB::select(" UPDATE $TABLE SET a = 'ชำระ : [ $pay_type ] พนักงาน : [ $action_user_name ] จัดส่ง : [ $shipping_desc ]' WHERE id = (($n*$i)+17) ; ");

        DB::select(" UPDATE $TABLE SET a = '".(@$sRow->pay_with_other_bill_note!=''?'หมายเหตุ '.@$sRow->pay_with_other_bill_note:'&nbsp;')."' WHERE id = (($n*$i)+18) ; ");

        DB::select(" UPDATE $TABLE SET a = '".(@$sRow->note!=''?'* '.@$sRow->note:'&nbsp;')."' WHERE id = (($n*$i)+19) ; ");


        if($amt_page==1){

          // รวมเงิน
          //  DB::select(" INSERT IGNORE INTO $TABLE VALUES ('18', null, null, null, null, null, null, '".$total_price."'); ");
            DB::select(" UPDATE $TABLE SET g = '".number_format(($total_price+@$shipping_price)-@$vat,2)."' WHERE id = (($n*$i)+18) ; ");

          //  DB::select(" INSERT IGNORE INTO $TABLE VALUES ('19', null, null, null, null, null, null, '".number_format(@$vat,2)."'); ");
            DB::select(" UPDATE $TABLE SET g = '".number_format(@$vat,2)."' WHERE id = (($n*$i)+19) ; ");

           // DB::select(" INSERT IGNORE INTO $TABLE VALUES ('20', null, null, null, null, null, null, '".number_format(@$sFrontstoreDataTotal[0]->total+@$shipping_cost,2)."'); ");
            DB::select(" UPDATE $TABLE SET g = '".number_format($total_price+@$shipping_price,2)."' WHERE id = (($n*$i)+20) ; ");

          DB::select(" UPDATE $TABLE SET g = '(หน้า ".($i+1)."/$amt_page)' WHERE id = (($n*$i)+22) ; ");
        }elseif($amt_page>1 && ($i+1)!=$amt_page){
          DB::select(" UPDATE $TABLE SET g = '(หน้า ".($i+1)."/$amt_page ต่อหน้า ".($i+2)." )' WHERE id = (($n*$i)+22) ; ");
        }else{
          if($i+1==$amt_page){

            // รวมเงิน
          //  DB::select(" INSERT IGNORE INTO $TABLE VALUES ('18', null, null, null, null, null, null, '".$total_price."'); ");
            DB::select(" UPDATE $TABLE SET g = '".number_format(($total_price+@$shipping_price)-@$vat,2)."' WHERE id = (($n*$i)+18) ; ");

          //  DB::select(" INSERT IGNORE INTO $TABLE VALUES ('19', null, null, null, null, null, null, '".number_format(@$vat,2)."'); ");
            DB::select(" UPDATE $TABLE SET g = '".number_format(@$vat,2)."' WHERE id = (($n*$i)+19) ; ");

           // DB::select(" INSERT IGNORE INTO $TABLE VALUES ('20', null, null, null, null, null, null, '".number_format(@$sFrontstoreDataTotal[0]->total+@$shipping_cost,2)."'); ");
            DB::select(" UPDATE $TABLE SET g = '".number_format($total_price+@$shipping_price,2)."' WHERE id = (($n*$i)+20) ; ");

            // หนเา
            DB::select(" UPDATE $TABLE SET g = '(หน้า ".($i+1)."/$amt_page)' WHERE id = (($n*$i)+22) ; ");
          }
        }

    }

       DB::select(" UPDATE $TABLE SET  a = '$address ' WHERE id = (($amt_page*$n)-2) ; ");
       DB::select(" UPDATE $TABLE SET  a = '$tel ' WHERE id = (($amt_page*$n)-1) ; ");

       // ยอดรวม
       DB::select(" UPDATE $TABLE_tmp SET  g = 0 WHERE g is null; ");




// ๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑
  // echo \Auth::user()->id;
  $TABLE = 'temp_z01_print_frontstore_print_receipt_02'.\Auth::user()->id;
  // $TABLE = 'temp_z01_print_frontstore_print_receipt_02';
  $count_row = DB::select(" SELECT count(*) as count_row FROM $TABLE  ");
  $count_row = $count_row[0]->count_row ;




for ($j=0; $j < $amt_page ; $j++) {


 ?>

<div class="NameAndAddress " >

    <table style="margin-left:10px !important;border-collapse: collapse;line-height: 9px !important;" >
      <tr>
        <td colspan="8" style="margin-left:35px !important;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (($j*$n)+4) ; "); ?>
        <?php echo '<b>['.@$DB[0]->a.']' ; ?>
      </td>

<!-- รายการสินค้า -->
<?php $runno = 1; for ($i= ($j*$n)+6; $i <= ($j*$n)+15 ; $i++) {  ?>

          <tr style="line-height: 9px !important;">

                <td style="width: 5%;text-align: left;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i) ; "); ?>
                <?php if(substr(trim(@$DB[0]->b),1,1)=='n'){ $runno = $runno - 1 ; }else{ if(@$DB[0]->b!='') echo $runno; } ?>
                </td>

                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i) ; "); ?>
                <?php
                if(@$DB[0]->c==""){ ?>
                    <td colspan="2" style="width:28%;text-align: left;">
                    <?php echo @$DB[0]->b ; ?>
                     </td>
                    <?php
                }else{  ?>
                    <td style="width:18%;text-align: left;">
                    <?php echo @$DB[0]->b ; ?>
                    </td>
                    <td style="width:10%;text-align: left;">
                    <?php echo @$DB[0]->c ; ?>
                    </td>
                    <?php
                }
                ?>

                <td style="width:4%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i) ; "); ?>
                <?php //echo @$DB[0]->f ; ?>
                </td>

          </tr>
 <?php $runno++; }  ?>

    </table>

</div>


<?php } ?>
<?php } ?>


    </table>
  </div>
