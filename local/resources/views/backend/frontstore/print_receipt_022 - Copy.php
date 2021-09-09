<style>

    @page { margin: 0px; }
    body{
      font-family: "THSarabunNew";
      font-size: 20px;
      font-weight: bold;
      line-height: 14px;
      margin-top: 20px;
      margin-left: 25px;
      margin-right: 33px;
      line-height: 13px;
    }

    @charset "utf-8";

    .NameAndAddress table {
        width: 100% !important;
        border-collapse: collapse;
        padding: 0px;


    }

    .NameAndAddress table thead {
        background: #ebebeb;
        color: #222222;
    }

    .NameAndAddress table tbody tr td b {
        width: 150px;
        display: inline-block;
    }

  /* DivTable.com */
  .divTable{
    display: table;
    /*width: 120%;*/
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
    /*padding: 3px 10px;*/
  }
  .divTableHeading {
    background-color: #EEE;
    display: table-header-group;

  }
  .divTableFoot {
    background-color: #EEE;
    display: table-footer-group;

  }
  .divTableBody {
    display: table-row-group;
  }
  .divTH {text-align: right;}


  .inline-group {
    max-width: 5rem;
    /*padding: .5rem;*/
  }

  .inline-group .form-control {
    text-align: right;
  }

  .form-control[type="number"]::-webkit-inner-spin-button,
  .form-control[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  .quantity {width: 50px;text-align: right;color: blue;font-size: 16px;}

  .btn-minus,.btn-plus,.btn-minus-pro,.btn-plus-pro,.btn-plus-product-pro,.btn-minus-product-pro {background-color: bisque !important;}

  input[type="text"]:disabled {
    background: #f2f2f2;
  }

    .page {
       page-break-after: always;
    }
    .page:last-child {
       page-break-after: unset;
    }

</style>

<?php
    require(app_path().'/Models/MyFunction.php');

$id = 139;

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
$cnt05 = DB::select(" SELECT count(*) as cnt FROM `promotions_products` WHERE promotion_id_fk in ($arr_promotion_id) ; ");
// echo $cnt01[0]->cnt;
// echo $cnt02[0]->cnt;
// echo $cnt03[0]->cnt;
// echo $cnt04[0]->cnt;
// echo $cnt05[0]->cnt;

// $cnt_all = $cnt01[0]->cnt + $cnt02[0]->cnt + $cnt03[0]->cnt + $cnt04[0]->cnt + $cnt05[0]->cnt ;
// $amt_page = ceil($cnt_all/10);
// echo $amt_page;

// Test
$cnt_all = DB::select(" SELECT count(*) as cnt FROM temp_z01_print_frontstore_print_receipt_02_tmp ");
// echo $cnt_all[0]->cnt;
$amt_page = ceil($cnt_all[0]->cnt/10);
// echo $amt_page;
// exit;


$TABLE = 'temp_z01_print_frontstore_print_receipt_02'.\Auth::user()->id;
DB::select(" DROP TABLE IF EXISTS $TABLE ; ");
DB::select(" 
    CREATE TABLE $TABLE (
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


   $db_orders = DB::select("
            SELECT db_orders.*,branchs.b_code as branch_code
            FROM db_orders
            LEFT Join branchs ON db_orders.branch_id_fk = branchs.id
            WHERE
            db_orders.id = '$id'
       ");

    if(@$sRow->delivery_location!=0){ 
       $branch_code = $db_orders[0]->branch_code;
    }else{
       $branch_code = '';
    }
  
    $action_user = DB::select(" select * from ck_users_admin where id=".@$db_orders[0]->action_user." ");
    $action_user_name = @$action_user[0]->name;


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

                 $Delivery_location = DB::select(" select id,txt_desc from dataset_delivery_location  ");
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
                
                  // echo "<span style='font-size:24px;'>".@$cus[0]->user_name." <br> ".@$cus[0]->prefix_name.@$cus[0]->first_name.' '.@$cus[0]->last_name."</span><br>";


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
                              @$address .=  " รหัส ปณ. ". @$addr[0]->card_zipcode ;

                              // echo @$address;

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
                                  @$address .=  " รหัส ปณ. ". @$addr[0]->card_zipcode ;

                                  // echo @$address;
                              }else{
                                @$address = "";
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
                                @$address .= " รหัส ปณ. ". @$addr[0]->zipcode;

                                // echo @$address;

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
                                @$address .= " ต.". @$addr[0]->tamname. " ";
                                @$address .= " อ.". @$addr[0]->ampname;
                                @$address .= " จ.". @$addr[0]->provname;
                                @$address .= " รหัส ปณ. ". @$addr[0]->zip_code. " ";

                                // echo @$address;

                        }

                      }
   $address = !empty($address) ? $address : NULL;
// ๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑๑


 DB::select(" INSERT IGNORE INTO $TABLE VALUES ('1', '$branch_code\r', null, null, null, null, null, null); ");
 DB::select(" UPDATE $TABLE SET a='$branch_code' where id=1 ; ");
 DB::select(" INSERT IGNORE INTO $TABLE VALUES ('2', '".$cus_user_name."', null, null, null, null, null, null); ");
 DB::select(" INSERT IGNORE INTO $TABLE VALUES ('3', '".$cus_name."', null, null, null, null, null, null); ");
 DB::select(" INSERT IGNORE INTO $TABLE VALUES ('4', '".@$sRow->code_order."', null, null, null, null, null, null); ");
 DB::select(" INSERT IGNORE INTO $TABLE VALUES ('5', '".date("d/m/Y",strtotime(@$sRow->created_at))."', null, null, null, null, null, null); ");


// ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

$Count_Product_list_add_from_1 = DB::select("
   SELECT count(id) as cnt from db_order_products_list WHERE frontstore_id_fk = ".$id." and add_from=1 GROUP BY promotion_id_fk,promotion_code
");

$Count_add_from_1 = @$Count_Product_list_add_from_1[0]->cnt;


if($Count_add_from_1>0){

          $P = DB::select("
                    SELECT
                    db_order_products_list.*,
                    customers.prefix_name,
                    customers.first_name,
                    customers.last_name,
                    customers_detail.house_no,
                    customers_detail.house_name,
                    customers_detail.moo,
                    customers_detail.zipcode,
                    customers_detail.soi,
                    customers.id as cus_id
                    FROM
                    db_order_products_list
                    Left Join db_orders ON db_orders.id = db_order_products_list.frontstore_id_fk
                    Left Join customers_detail ON db_orders.customers_id_fk = customers_detail.customer_id
                    Left Join customers ON customers_detail.customer_id = customers.id
             
                    WHERE
                    db_order_products_list.frontstore_id_fk =
                    ".$id."  AND add_from=1

     ");

    $i = 6 ;
    foreach ($P as $key => $v) {

      if($v->type_product=="course"){
         $product_name = "(คอร์สอบรม) ".$v->product_name;
      }else{

        if($v->product_id_fk!=''){

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$v->product_id_fk." AND lang_id=1");

            $product_name = @$Products[0]->product_code." : ".@$Products[0]->product_name;

          }else{

            $product_name = '';

          }
        }

        DB::select(" INSERT IGNORE INTO $TABLE VALUES ('$i', $i-5, '$product_name', null, '".number_format($v->selling_price,0)."', concat($v->total_pv,'pv'), $v->amt , '".number_format($v->amt*$v->selling_price,2)."'); ");
        $i++;
   }

if($cnt_all>0 && $cnt_all<=10){


  $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total from db_order_products_list WHERE frontstore_id_fk=".$id." GROUP BY frontstore_id_fk ");
  $sFrontstorePVtotal = DB::select(" select SUM(total_pv) as pv_total from db_order_products_list WHERE frontstore_id_fk=".$id." GROUP BY frontstore_id_fk ");
  $sFrontstoreData = DB::select(" select * from db_order_products_list ");
  $vat = intval(@$sFrontstoreDataTotal[0]->total) - (intval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
  $shipping_price = @$sRow->shipping_price?@$sRow->shipping_price:0;
  $pv_total = @$sRow->pv_total?@$sRow->pv_total:0;

  $total_price = number_format(@$sFrontstoreDataTotal[0]->total,2);

    $pay_type = DB::select(" select dataset_pay_type.detail as pay_type from db_orders Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id WHERE db_orders.id=".$id." ");
    $pay_type =   @$pay_type[0]->pay_type;

    DB::select(" INSERT IGNORE INTO $TABLE VALUES ('16', 'REF : [ $id ] AG : [ $branch_code ] SK : [ - ] คะแนนครั้งนี้ : [ ".number_format(@$pv_total,0)." pv ]', null, null, null, null, null, null); ");
    DB::select(" INSERT IGNORE INTO $TABLE VALUES ('17', 'ชำระ : [ $pay_type $total_price ] พนักงาน : [ $action_user_name ] การจัดส่ง : [ 4/".number_format(@$shipping_price,0)." ]', null, null, null, null, null, null); ");

    DB::select(" INSERT IGNORE INTO $TABLE VALUES ('18', null, null, null, null, null, null, '".$total_price."'); ");
    DB::select(" INSERT IGNORE INTO $TABLE VALUES ('19', null, null, null, null, null, null, '".number_format(@$vat,2)."'); ");
    DB::select(" INSERT IGNORE INTO $TABLE VALUES ('20', null, null, null, null, null, null, '".number_format(@$sFrontstoreDataTotal[0]->total+@$shipping_cost,2)."'); ");
    DB::select(" INSERT IGNORE INTO $TABLE VALUES ('21', 'ชื่อ-ที่อยู่ผู้รับ: $address ', null, null, null, null, null, null); ");

    DB::select(" INSERT IGNORE INTO $TABLE VALUES ('22', null, null, null, null, null, null, '(หน้า 1/$cnt_all)'); ");

 }else{
   DB::select(" INSERT IGNORE INTO $TABLE VALUES ('22', null, null, null, null, null, null, '(หน้า 1/$cnt_all ต่อหน้า 2)'); ");
 }


}

// ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

// ๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓
// Promotions
$Count_Product_list_add_from_2 = DB::select("
   SELECT count(id) as cnt from db_order_products_list WHERE frontstore_id_fk = ".$id." and add_from=2 GROUP BY promotion_id_fk,promotion_code
");

$Count_add_from_2 = @$Count_Product_list_add_from_2[0]->cnt;


if($Count_add_from_2>0){
  // return $Count_add_from_2;
// 444444444444444444444444444444444444444444444444444

     $P = DB::select("
         SELECT * from db_order_products_list WHERE frontstore_id_fk = ".$id." and add_from=2 GROUP BY promotion_id_fk,promotion_code
     ");

// 444444444444444444444444444444444444444444444444444


     $pn = '';
     $pn_pname = '';

    foreach ($P as $key => $v) {

            if($v->promotion_id_fk!='' && $v->promotion_code!=''){
                $promotions = DB::select(" SELECT name_thai as pro_name FROM promotions WHERE id='".$v->promotion_id_fk."' ");
                // $pn =  "ชื่อโปร : ".@$promotions[0]->pro_name ."</br>";
                $pn =  @$promotions[0]->pro_name ."</br>";
                $pn_pname =  "รหัสคูปอง : ".($v->promotion_code);
            }else{
                $promotions = DB::select(" SELECT pcode,name_thai as pro_name FROM promotions WHERE id='".$v->promotion_id_fk."' ");
                // $pn =  "ชื่อโปร : ".@$promotions[0]->pro_name ."</br>";
                $pn =  @$promotions[0]->pro_name ."</br>";
                $pn_pname =  "รหัสโปร : ".(@$promotions[0]->pcode);
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
                  '
                  <div class="divTableRow">
                  <div class="divTableCell">[Pro'.$value->product_code.'] '.$value->product_name.'</div>
                  <div class="divTableCell"><center>'.$value->product_amt.' x '.$v->amt.'= '.($value->product_amt*$v->amt).'</div>
                  <div class="divTableCell">&nbsp;'.$value->product_unit.'</div>
                  </div>
                  ';
             }

             $pn .=
                  '
                  <div class="divTableRow">
                  <div class="divTableCell">'.$pn_pname.'</div>
                  <div class="divTableCell"></div>
                  <div class="divTableCell"></div>
                  </div>
                  ';

              $pn .= '</div></div>';

              DB::select(" INSERT IGNORE INTO $TABLE VALUES ('6', '*** กรณีสินค้าโปรโมชั่น => อยู่ระหว่างการปรัปปรุงรูปแบบใบเสร็จ ', null, null, null, null, null, null); ");

        }
        
}
// ๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓๓

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
  // echo \Auth::user()->id;
  // $TABLE = 'temp_z01_print_frontstore_print_receipt_02'.\Auth::user()->id;
  $TABLE = 'temp_z01_print_frontstore_print_receipt_02';
  $count_row = DB::select(" SELECT count(*) as count_row FROM $TABLE  ");
  $count_row = $count_row[0]->count_row ;

 ?>

<div class="NameAndAddress " >
    <table >
      <tr>
        <td style="width: 47% ;margin-left:35px !important;">

          <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (2) ; "); ?>
          <?php echo "<span style='font-size:24px;'>".@$DB[0]->a." <br> "; ?>

          <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (3) ; "); ?>
          <?php echo "<span style='font-size:24px;'>".@$DB[0]->a."</span><br>"; ?>
      </td>

<!-- THELP  -->
      <td style="vertical-align: top; font-size: 24px;font-weight: bold;" >
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (1) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>

      <td style="margin-left:25px !important;margin-top:18px !important;width:30%;vertical-align: top;" >
        <br> 
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (4) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
        <br>
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (5) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      </tr>
    </table>
    
    <table style="margin-left:10px !important;margin-top:44px !important;border-collapse: collapse;height: 150px !important;" >
<!-- รายการสินค้า -->

 <?php for ($i=6; $i <= 15 ; $i++) {  ?>

          <tr style="vertical-align: top;line-height: 10px !important;">

                <td style="width:2.6%;text-align: left;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i) ; "); ?>
                <?php echo @$DB[0]->a ; ?>
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

                <td style="width:6%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i) ; "); ?>
                <?php 
                if(@$DB[0]->c==""){
                   echo @$DB[0]->d ; 
                }
                ?>
                </td>

                <td style="width:5%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i) ; "); ?>
                <?php 
                if(@$DB[0]->c==""){
                  echo @$DB[0]->e ; 
                }
                ?>
                </td>

                <td style="width:4%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i) ; "); ?>
                <?php echo @$DB[0]->f ; ?>
                </td>

                <td style="width:11%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i) ; "); ?>
                <?php echo @$DB[0]->g ; ?>
                </td>

          </tr>

 <?php  }  ?>

    </table>

  <table style="border-collapse: collapse;vertical-align: top;margin-top:5px !important;" >
    <tr>
      <td style="margin-left:33px !important;width:80%;font-size: 14px;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (16) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
    </tr>

    <tr>
      <td style="margin-left:33px !important;width:80%;font-size: 14px;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (17) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"> </td>
    </tr>

    <tr>
      <td style="width:80%;font-size: 14px;">
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;margin-top:-3px !important;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (18) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
      <td style="font-size: 14px;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"> 
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (19) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (20) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
        <td style="text-align: left;" colspan="4">
          <span style="font-size: 14px !important;" >
          <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (21) ; "); ?>
          <?php echo @$DB[0]->a ; ?>
         </span>
      </td>
    </tr>
  </table>
</div>

 <div style="float:right;margin-top: 4%;font-size: 14px !important;">
  <!-- Page 1 -->
       <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (22) ; "); ?>
       <?php echo @$DB[0]->g ; ?>
 </div>


<!-- Page 2 -->
<?php if($count_row>22){ ?>

<?php $p2 = 22 ; ?>  
<div class="NameAndAddress " >
    <table >
      <tr>
        <td style="width: 47% ;margin-left:35px !important;">

          <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (2+$p2) ; "); ?>
          <?php echo "<span style='font-size:24px;'>".@$DB[0]->a." <br> "; ?>

          <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (3+$p2) ; "); ?>
          <?php echo "<span style='font-size:24px;'>".@$DB[0]->a."</span><br>"; ?>
      </td>

<!-- THELP  -->
      <td style="vertical-align: top; font-size: 24px;font-weight: bold;" >
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (1+$p2) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>

      <td style="margin-left:25px !important;margin-top:18px !important;width:30%;vertical-align: top;" >
        <br> 
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (4+$p2) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
        <br>
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (5+$p2) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      </tr>
    </table>
    
    <table style="margin-left:10px !important;margin-top:44px !important;border-collapse: collapse;height: 150px !important;" >
<!-- รายการสินค้า -->

 <?php for ($i=6; $i <= 15 ; $i++) {  ?>

          <tr style="vertical-align: top;line-height: 10px !important;">

                <td style="width:2.6%;text-align: left;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i+$p2) ; "); ?>
                <?php echo @$DB[0]->a ; ?>
                </td>

                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i+$p2) ; "); ?>
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

                <td style="width:6%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i+$p2) ; "); ?>
                <?php 
                if(@$DB[0]->c==""){
                   echo @$DB[0]->d ; 
                }
                ?>
                </td>

                <td style="width:5%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i+$p2) ; "); ?>
                <?php 
                if(@$DB[0]->c==""){
                  echo @$DB[0]->e ; 
                }
                ?>
                </td>

                <td style="width:4%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i+$p2) ; "); ?>
                <?php echo @$DB[0]->f ; ?>
                </td>

                <td style="width:11%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i+$p2) ; "); ?>
                <?php echo @$DB[0]->g ; ?>
                </td>

          </tr>

 <?php  }  ?>

    </table>

  <table style="border-collapse: collapse;vertical-align: top;margin-top:5px !important;" >
    <tr>
      <td style="margin-left:33px !important;width:80%;font-size: 14px;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (16+$p2) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
    </tr>

    <tr>
      <td style="margin-left:33px !important;width:80%;font-size: 14px;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (17+$p2) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"> </td>
    </tr>

    <tr>
      <td style="width:80%;font-size: 14px;">
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;margin-top:-3px !important;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (18+$p2) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
      <td style="font-size: 14px;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"> 
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (19+$p2) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (20+$p2) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
        <td style="text-align: left;" colspan="4">
          <span style="font-size: 14px !important;" >
          <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (21+$p2) ; "); ?>
          <?php echo @$DB[0]->a ; ?>
         </span>
      </td>
    </tr>
  </table>
</div>

 <div style="float:right;margin-top: 4%;font-size: 14px !important;">
  <!-- Page 1 -->
       <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (22+$p2) ; "); ?>
       <?php echo @$DB[0]->g ; ?>
 </div>

<?php } ?>

<!-- Page 3 -->
<?php if($count_row>44){ ?>

<?php $p3 = 44 ; ?>
<div class="NameAndAddress " >
    <table >
      <tr>
        <td style="width: 47% ;margin-left:35px !important;">

          <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (2+$p3) ; "); ?>
          <?php echo "<span style='font-size:24px;'>".@$DB[0]->a." <br> "; ?>

          <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (3+$p3) ; "); ?>
          <?php echo "<span style='font-size:24px;'>".@$DB[0]->a."</span><br>"; ?>
      </td>

<!-- THELP  -->
      <td style="vertical-align: top; font-size: 24px;font-weight: bold;" >
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (1+$p3) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>

      <td style="margin-left:25px !important;margin-top:18px !important;width:30%;vertical-align: top;" >
        <br> 
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (4+$p3) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
        <br>
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (5+$p3) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      </tr>
    </table>
    
    <table style="margin-left:10px !important;margin-top:44px !important;border-collapse: collapse;height: 150px !important;" >
<!-- รายการสินค้า -->

 <?php for ($i=6; $i <= 15 ; $i++) {  ?>

          <tr style="vertical-align: top;line-height: 10px !important;">

                <td style="width:2.6%;text-align: left;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i+$p3) ; "); ?>
                <?php echo @$DB[0]->a ; ?>
                </td>

                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i+$p3) ; "); ?>
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

                <td style="width:6%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i+$p3) ; "); ?>
                <?php 
                if(@$DB[0]->c==""){
                   echo @$DB[0]->d ; 
                }
                ?>
                </td>

                <td style="width:5%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i+$p3) ; "); ?>
                <?php 
                if(@$DB[0]->c==""){
                  echo @$DB[0]->e ; 
                }
                ?>
                </td>

                <td style="width:4%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i+$p3) ; "); ?>
                <?php echo @$DB[0]->f ; ?>
                </td>

                <td style="width:11%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM $TABLE where id in ($i+$p3) ; "); ?>
                <?php echo @$DB[0]->g ; ?>
                </td>

          </tr>

 <?php  }  ?>

    </table>

  <table style="border-collapse: collapse;vertical-align: top;margin-top:5px !important;" >
    <tr>
      <td style="margin-left:33px !important;width:80%;font-size: 14px;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (16+$p3) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
    </tr>

    <tr>
      <td style="margin-left:33px !important;width:80%;font-size: 14px;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (17+$p3) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"> </td>
    </tr>

    <tr>
      <td style="width:80%;font-size: 14px;">
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;margin-top:-3px !important;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (18+$p3) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
      <td style="font-size: 14px;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"> 
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (19+$p3) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
      <td colspan="3" style="font-size: 14px !important;margin-left: 5% !important;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (20+$p3) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      <td style="text-align: right;">
        <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (20+$p3) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
        <td style="margin-left:33px !important;text-align: left;" colspan="4">
          <span style="font-size: 14px !important;" >
          <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (21+$p3) ; "); ?>
          <?php echo @$DB[0]->a ; ?>
         </span>
      </td>
    </tr>
  </table>
</div>

 <div style="float:right;margin-top: 4%;font-size: 14px !important;">
  <!-- Page 1 -->
       <?php $DB = DB::select(" SELECT * FROM $TABLE where id in (22+$p3) ; "); ?>
       <?php echo @$DB[0]->g ; ?>
 </div>

<?php } ?>