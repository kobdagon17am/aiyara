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

require(app_path().'/Models/MyFunction.php');

$value = DB::select("
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
                    customers_detail.amphures_id_fk,
                    customers_detail.district_id_fk,
                    customers_detail.road,
                    customers_detail.province_id_fk,
                    customers.id as cus_id
                    FROM
                    db_order_products_list
                    Left Join db_orders ON db_orders.id = db_order_products_list.frontstore_id_fk
                    Left Join customers_detail ON db_orders.customers_id_fk = customers_detail.customer_id
                    Left Join customers ON customers_detail.customer_id = customers.id

                    WHERE
                    db_order_products_list.frontstore_id_fk =
                    ".$data[0]."

     ");

// print_r($value);

        // if($i>1){echo'<div class="page-break"></div>';}

 ?>

    <div class="NameAndAddress" style="" >
      <table style="border-collapse: collapse;" >
        <tr>
          <th style="text-align: left;">
            <img src="<?=public_path('images/logo2.png')?>" >
          </th>
          <th style="text-align: right;">
            (<?php echo sprintf("%04d",$data[0]); ?>)<br>
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
        <td style="width:60%;" >

         <?php

                 $sRow = \App\Models\Backend\Frontstore::find($data[0]);
                 $Delivery_location = DB::select(" select id,txt_desc from dataset_delivery_location  ");
                 $CusAddrFrontstore = \App\Models\Backend\CusAddrFrontstore::where('frontstore_id_fk',$data[0])->get();

                  echo "<b>ชื่อ - ที่อยู่จัดส่ง</b>"."<br>";

                      if(@$sRow->delivery_location==0 && @$sRow->purchase_type_id_fk!=6 ){

                        $cus = DB::select("
                            SELECT
                            customers.user_name,
                            customers.prefix_name,
                            customers.first_name,
                            customers.last_name
                            FROM
                            db_orders
                            Left Join customers ON db_orders.customers_id_fk = customers.id
                            where db_orders.id = ".$data[0]."
                              ");
                              if(@$cus[0]->prefix_name == 0){
            @$cus[0]->prefix_name = '';
           }
                         echo  @$cus[0]->user_name.' : '.@$cus[0]->prefix_name.@$cus[0]->first_name.' '.@$cus[0]->last_name;
                         echo "<br>( รับสินค้าด้วยตัวเอง ) ";

                      }else{

                        echo  @$value[0]->prefix_name.@$value[0]->first_name.' '.@$value[0]->last_name;


                        if(@$sRow->delivery_location==1){

                          $addr = DB::select(" SELECT
                                      customers_address_card.id,
                                      customers_address_card.customer_id,
                                      customers_address_card.card_house_no,
                                      customers_address_card.card_house_name,
                                      customers_address_card.card_moo,
                                      customers_address_card.card_zipcode,
                                      customers_address_card.card_soi,
                                      customers_detail.amphures_id_fk,
                                      customers_detail.district_id_fk,
                                      customers_detail.road,
                                      customers_detail.province_id_fk,
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
                                      Left Join customers_detail ON customers.id = customers_detail.customer_id
                                      where customers_address_card.customer_id = ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)."

                                     ");
                          // print_r($addr);

                          if(@$addr[0]->provname!=''){

                              @$address = "";
                              @$address .=  "ที่อยู่ : ". @$addr[0]->card_house_no ;
                              @$address .=  " ต. ". @$addr[0]->tamname;
                              @$address .=  " อ. ". @$addr[0]->ampname;
                              @$address .=  " จ. ". @$addr[0]->provname;
                              @$address .=  " รหัส ปณ. ". @$addr[0]->card_zipcode ;


                              echo @$address;

                          }else{

                                $addr = DB::select(" SELECT
                                    customers_address_card.id,
                                    customers_address_card.customer_id,
                                    customers_address_card.card_house_no,
                                    customers_address_card.card_house_name,
                                    customers_address_card.card_moo,
                                    customers_address_card.card_zipcode,
                                    customers_address_card.card_soi,
                                    customers_detail.amphures_id_fk,
                                    customers_detail.district_id_fk,
                                    customers_detail.road,
                                    customers_detail.province_id_fk,
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
                                  @$address = "";
                                  @$address .=  "เลขที่ ". @$addr[0]->card_house_no." ". @$addr[0]->card_house_name."";
                                  @$address .=  " หมู่ ". @$addr[0]->card_moo;
                                  @$address .=  " ซอย ". @$addr[0]->card_soi;
                                  @$address .=  " ถนน ". @$addr[0]->card_road;
                                  @$address .=  " ต. ". @$addr[0]->tambon_name;
                                  @$address .=  " อ. ". @$addr[0]->amp_name;
                                  @$address .=  " จ. ". @$addr[0]->province_name;
                                  @$address .=  " <br> รหัส ปณ. ". @$addr[0]->card_zipcode." </span> ";

                                  echo @$address;
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
                                      customers_detail.amphures_id_fk,
                                      customers_detail.district_id_fk,
                                      customers_detail.road,
                                      customers_detail.province_id_fk,
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
                                @$address = " เลขที่ ". @$addr[0]->house_no. " หมู่บ้าน ". @$addr[0]->house_name. " ";
                                @$address .= " ต. ". @$addr[0]->tamname;
                                @$address .= " อ. ". @$addr[0]->ampname;
                                @$address .= " จ. ". @$addr[0]->provname;
                                @$address .= " รหัส ปณ. ". @$addr[0]->zipcode. " </span> ";

                                echo @$address;

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
                                @$address = "ชื่อผู้รับ : ". @$addr[0]->recipient_name;
                                @$address .= "ที่อยู่ : ". @$addr[0]->addr_no ;
                                @$address .= " ต. ". @$addr[0]->tamname;
                                @$address .= " อ. ". @$addr[0]->ampname;
                                @$address .= " จ. ". @$addr[0]->provname;
                                @$address .= " รหัส ปณ. ". @$addr[0]->zip_code ;

                                echo @$address;

                        }

                      }

                     ?>
      </td>
      <td style="width:20%;vertical-align: top;font-weight: bold;" >
        เลขที่ / No. <?=@$value->invoice_code?><br>
        วันที่ / Date <?=ThDate01(@$sRow->action_date)?>
      </td>
      <td style="width:10%;vertical-align: top;" >
          <br>
      </td>

      </tr>
    </table>
  </div>
  <br>


  <div style="border-radius: 5px; height: 80mm; border: 1px solid grey;padding:-1px;" >
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

     // $sFrontstorelist = \App\Models\Backend\Frontstorelist::find($data[0]);
     // // dd($sFrontstorelist);
     // if($sFrontstorelist->type_product=="course"){
     //    $Course_event = \App\Models\Backend\Course_event::find($sFrontstorelist->course_id_fk);
     // }

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
                    customers_detail.amphures_id_fk,
                    customers_detail.district_id_fk,
                    customers_detail.road,
                    customers_detail.province_id_fk,
                    customers.id as cus_id


                    FROM
                    db_order_products_list
                    Left Join db_orders ON db_orders.id = db_order_products_list.frontstore_id_fk
                    Left Join customers_detail ON db_orders.customers_id_fk = customers_detail.customer_id
                    Left Join customers ON customers_detail.customer_id = customers.id

                    WHERE
                    db_order_products_list.frontstore_id_fk =
                    ".$data[0]."  AND add_from=1

     ");

    $i=1;

     $Total = 0;
     $shipping = 0;

     // echo count($P);
     // exit;

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


     ?>

          <tr>
            <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;" > <?=$i?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: left;"> <?=$product_name?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->amt?>  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->selling_price?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->total_pv?>  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=number_format($v->amt*$v->selling_price,2)?>  </td>
          </tr>

<?php

    $i++;
    $Total += $v->amt*$v->selling_price;
    // $shipping += $v->shipping ;

  }

  $n = 4 - $i;

  ?>

<!-- รายการสินค้า -->
<?php

     $P = DB::select("
         SELECT * from db_order_products_list WHERE frontstore_id_fk = ".$data[0]." and add_from=2 GROUP BY promotion_id_fk,promotion_code
     ");

    $i= $i ;

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
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;vertical-align: top;"> <?=$v->total_pv?>  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;vertical-align: top;"> <?=number_format($v->amt*$v->selling_price,2)?>  </td>
          </tr>

<?php

    $i++;

    }

    $n = 3 - $i;

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

      <?php

        $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total from db_order_products_list WHERE frontstore_id_fk=1 GROUP BY frontstore_id_fk ");
        $sFrontstoreData = DB::select(" select * from db_order_products_list ");

        $vat = intval(@$sFrontstoreDataTotal[0]->total) - (intval(@$sFrontstoreDataTotal[0]->total)/1.07) ;

        $shipping_cost = 100;

       ?>



  <div style="border-radius: 5px; border: 1px solid grey;" >
    <table style="border-collapse: collapse;vertical-align: top;" >

      <tr>
        <td rowspan="4"  style="width:55%;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;">


<br>
<br>
<br>
<br>
      <center> ตัวอักษร (<?=ThaiBahtConversion(@$sFrontstoreDataTotal[0]->total+@$shipping_cost)?>) </center>
      </td>


        <td  style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> รวมเงิน <br>
TOTAL </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: right;padding-right: 10px;">{{number_format(@$sFrontstoreDataTotal[0]->total,2)}} </td>

      </tr>

      <tr>

        <td  style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> ภาษีมูลค่าเพิ่ม  <br>
( VAT 7% ) </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: right;padding-right: 10px;"> {{number_format(@$vat,2)}} </td>

      </tr>
          <tr>
        <td  style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> ค่าจัดส่ง  </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: right;padding-right: 10px;"> {{@$shipping_cost}} </td>

      </tr>
      <tr>

        <td  style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> ยอดเงินสุทธิ  <br>
NET AMOUNT </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: right;padding-right: 10px;"> {{number_format(@$sFrontstoreDataTotal[0]->total+@$shipping_cost,2)}} </td>

      </tr>

    </table>
  </div>

   <br>
  <div style="border-radius: 5px; height: 30mm; border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;text-align: center;" >

      <tr>

        <td  style="border-left: 1px solid #ccc;"> ผู้รับเงิน

        <br>
        <img src="" width="100" > (ลายเซ็น)
        <br>
           วันที่ .........................................
         </td>
        <td style="border-left: 1px solid #ccc;"> ในนาม บริษัท ไอยรา แพลนเน็ต จำกัด
        <br>
        <img src="" width="100" > (ลายเซ็น)
        <br>
      ผู้มีอำนาจลงนาม
        </td>

      </tr>

    </table>
  </div>

</div>


