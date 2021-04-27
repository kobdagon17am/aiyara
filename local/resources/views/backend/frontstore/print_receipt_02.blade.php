@php
@include(app_path() . '\Models\MyFunction.php');
@endphp
<style>

    body{
     font-family: "THSarabunNew";
     font-size: 20px;
     font-weight: bold;
     line-height: 14px !important;
    }

    @charset "utf-8";

    .NameAndAddress table {
        width: 100%;
        border-collapse: collapse;
        padding: 0px;

    }

    .NameAndAddress table thead {
        background: #ebebeb;
        color: #222222;
    }

    .NameAndAddress table tbody tr td b {
        width: 130px;
        display: inline-block;
    }

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


</style>
<?php

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
                customers.id as cus_id,
                customers.user_name as cus_code,
                orders_frontstore.id as order_id,
                orders_frontstore.shipping

                FROM
                db_order_products_list
                Left Join db_orders ON db_orders.id = db_order_products_list.frontstore_id_fk
                Left Join customers_detail ON db_orders.customers_id_fk = customers_detail.customer_id
                Left Join customers ON customers_detail.customer_id = customers.id
                Left Join orders_frontstore ON db_orders.code_order = orders_frontstore.code_order
                WHERE
                db_order_products_list.frontstore_id_fk =
                ".$data[0]."

           ");


 ?>


<div class="NameAndAddress" >

    <table style="" >
      <tr>
        <td style="width:50%;" >
         <?php
                 $sRow = \App\Models\Backend\Frontstore::find($data[0]);
                 $Delivery_location = DB::select(" select id,txt_desc from dataset_delivery_location  ");
                 $CusAddrFrontstore = \App\Models\Backend\CusAddrFrontstore::where('frontstore_id_fk',$data[0])->get();

                 echo "<br>";
                 echo @$value[0]->cus_code." <br> ".@$value[0]->prefix_name.@$value[0]->first_name.' '.@$value[0]->last_name."<br>";

                      if(@$sRow->delivery_location==0){
                        echo " รับสินค้าด้วยตัวเอง ";
                      }else{

                        // foreach(@$Delivery_location AS $r){
                        //   if(@$r->id==@$sRow->delivery_location){
                        //     echo "( ".$r->txt_desc." )  ";
                        //   }
                        // }

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
                                  @$address =  "เลขที่ ". @$addr[0]->card_house_no." ". @$addr[0]->card_house_name."";
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
                                      customers_detail.province_id,
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
                                @$address .= "ที่อยู่ : ". @$addr[0]->addr_no. "<br> ";
                                @$address .= " ต. ". @$addr[0]->tamname;
                                @$address .= " อ. ". @$addr[0]->ampname;
                                @$address .= " จ. ". @$addr[0]->provname;
                                @$address .= " รหัส ปณ. ". @$addr[0]->zip_code. " ";

                                echo @$address;

                        }

                      }

                     ?>
      </td>
      <td style="width:20%;vertical-align: top;" >
        <br>
        <br>
        <br>
        P2102100001 <br>
        <?=ThDate01(@$sRow->action_date)?>
      </td>
      </tr>
    </table>

        <br>
        <br>
        <br>

    <table style="border-collapse: collapse;vertical-align: top; " >
<!-- รายการสินค้า -->

<?php

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
                    customers.id as cus_id,
                    orders_frontstore.id as order_id,
                    orders_frontstore.shipping

                    FROM
                    db_order_products_list
                    Left Join db_orders ON db_orders.id = db_order_products_list.frontstore_id_fk
                    Left Join customers_detail ON db_orders.customers_id_fk = customers_detail.customer_id
                    Left Join customers ON customers_detail.customer_id = customers.id
                    Left Join orders_frontstore ON db_orders.code_order = orders_frontstore.code_order
                    WHERE
                    db_order_products_list.frontstore_id_fk =
                    ".$data[0]."  AND add_from=1

     ");

    $i=1;



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

          <tr style="  ">
            <td style="width:5%;text-align: center;" > <?=$i?> </td>
            <td style="width:35%;text-align: left;"> <?=$product_name?> </td>
            <td style="width:5%;text-align: center;"> <?=$v->selling_price?> </td>
            <td style="width:5%;text-align: center;"> <?=$v->total_pv?>pv  </td>
            <td style="width:5%;text-align: center;"> <?=$v->amt?>  </td>
            <td style="width:5%;text-align: center;"> <?=number_format($v->amt*$v->selling_price,2)?>  </td>
          </tr>

<?php

    $i++;
  }


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
            <td style="width:5%;text-align: center;vertical-align: top;" > <?=$i?> </td>
            <td style="text-align: left;"> <?=$pn?> </td>
            <td style="text-align: center;vertical-align: top;"> <?=$v->selling_price?> </td>
            <td style="text-align: center;vertical-align: top;"> <?=$v->total_pv?>pv  </td>
            <td style="text-align: center;vertical-align: top;"> <?=$v->amt?>  </td>
            <td style="text-align: center;vertical-align: top;"> <?=number_format($v->amt*$v->selling_price,2)?>  </td>
          </tr>

<?php

    $i++;

    }


?>


    </table>

      <?php
        $sFrontstoreDataTotal = DB::select(" select SUM(total_price) as total from db_order_products_list WHERE frontstore_id_fk=".$data[0]." GROUP BY frontstore_id_fk ");
        $sFrontstorePVtotal = DB::select(" select SUM(total_pv) as pv_total from db_order_products_list WHERE frontstore_id_fk=".$data[0]." GROUP BY frontstore_id_fk ");
        $sFrontstoreData = DB::select(" select * from db_order_products_list ");
        $vat = intval(@$sFrontstoreDataTotal[0]->total) - (intval(@$sFrontstoreDataTotal[0]->total)/1.07) ;
        $shipping_cost = 100;
       ?>
<br>
<br>

  <table style="border-collapse: collapse;vertical-align: top;" >

    <tr>
      <td style="width:55%;font-size: 14px;">
       REF : [ 123456 ] AG : [ - ] SK : [ A123456 ] คะแนนครั้งนี้ : [  <?=@$sFrontstorePVtotal[0]->pv_total?> pv ]
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
    </tr>

    <tr>
      <td style="width:55%;font-size: 14px;">
       ชำระ : [ สด={{number_format(@$sFrontstoreDataTotal[0]->total+@$shipping_cost,2)}} ] พนักงาน : [ admin ] การจัดส่ง : [ 4/{{$shipping_cost}} ]
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"> </td>
    </tr>

    <tr>
      <td style="width:55%;font-size: 14px;">
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"> {{number_format(@$sFrontstoreDataTotal[0]->total,2)}} </td>
    </tr>

    <tr>
      <td style="font-size: 14px;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"> {{number_format(@$vat,2)}} </td>
    </tr>

    <tr>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;"> {{number_format(@$sFrontstoreDataTotal[0]->total+@$shipping_cost,2)}} </td>
    </tr>

  </table>
</div>

