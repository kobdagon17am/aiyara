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
      size: landscape;
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


<?php  $value = DB::select(" SELECT * FROM db_stocks_account_code where id = ".$data[0]." "); 
       $d = strtotime($value[0]->action_date); $d = date("d/m/", $d).(date("Y", $d)+543);

        $sL = DB::select(" select * from dataset_business_location where id=".($value[0]->condition_business_location?$value[0]->condition_business_location:0)." ");
        $sB = DB::select(" select * from branchs where id=".($value[0]->condition_branch?$value[0]->condition_branch:0)." ");
        $sW = DB::select(" select * from warehouse where id=".($value[0]->condition_warehouse?$value[0]->condition_warehouse:0)." ");
        $sZ = DB::select(" select * from zone where id=".($value[0]->condition_zone?$value[0]->condition_zone:0)." ");
        $sS = DB::select(" select * from shelf where id=".($value[0]->condition_shelf?$value[0]->condition_shelf:0)." ");
        $shelf_floor = @$value[0]->condition_shelf_floor?" > ชั้น ".@$value[0]->condition_shelf_floor:NULL;

        if(!empty(@$value[0]->condition_product)){

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$value[0]->condition_product." AND lang_id=1");

            $sProduct =  ' > สินค้า : '.@$Products[0]->product_code." : ".@$Products[0]->product_name;

        }else{
          $sProduct = '';
        }
         $lot_number = $value[0]->condition_lot_number?" > ".$value[0]->condition_lot_number:NULL;

        $Condition = 'Business Location : '.@$sL[0]->txt_desc." > ".
        @$sB[0]->b_name.' '.(@$sW[0]->w_name?' > '.@$sW[0]->w_name:NULL).' '.(@$sZ[0]->z_name?' > '.@$sZ[0]->z_name:NULL).' '.(@$sS[0]->s_name?' > '.@$sS[0]->s_name:NULL).$shelf_floor.' '.$sProduct.' '.$lot_number;

        $ConditionChoose = "เงื่อนไขที่ระบุ : ".$Condition;


        $sW_No =$value[0]->condition_warehouse==0||$value[0]->condition_warehouse==NULL?', คลัง':NULL;// dd($sW_No);
        $sZ_No =$value[0]->condition_zone==0||$value[0]->condition_zone==NULL?', โซน':NULL; //dd($sZ_No);
        $sS_No =$value[0]->condition_shelf==0||$value[0]->condition_shelf==NULL?', Shelf':NULL; //dd($sS_No);
        $shelf_floor_No =$value[0]->condition_shelf_floor==0||$value[0]->condition_shelf_floor==NULL?', ชั้น':NULL; //dd($shelf_floor_No);
        $sProductSel =$value[0]->condition_product==0||$value[0]->condition_product==NULL?', สินค้า':NULL; //dd($shelf_floor_No);
        $sLot =$value[0]->condition_lot_number==0||$value[0]->condition_lot_number==NULL?', Lot Number':NULL; //dd($shelf_floor_No);

        $sAction_user = DB::select(" select * from ck_users_admin where id=".$value[0]->action_user." ");

        $ConditionNo = $sW_No." ".$sZ_No." ".$sS_No." ".$shelf_floor_No." ".$sProductSel." ".$sLot." (ผู้ดำเนินการ > ".$sAction_user[0]->name." : ".$value[0]->action_date.")" ;

        $ConditionNoChoose = "เงื่อนไขที่ไม่ได้ระบุ  ".$ConditionNo;


?>
<header>
  <span class="pagenum"></span>
</header>
  <div class="NameAndAddress" style="" >
      <table style="border-collapse: collapse;" >
        <tr> 
          <th style="text-align: center;font-size: 30px;">
           <center> ใบตรวจนับสินค้า </center>
          </th>
        </tr>
      </table>
    </div>

<div class="NameAndAddress" >

  <div style="border-radius: 5px; height: 25mm; border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;" >
      <tr>
          <td style="width:80%;vertical-align: top;font-weight: bold;" > 
            เลขที่ / No. : <?=substr($value[0]->ref_code,0,8); ?><br>
            <?=$ConditionChoose?> <br> <?=$ConditionNoChoose?>

          </td> 
          <td style="width:20%;vertical-align: top;font-weight: bold;" > 
            วันที่ / Date : <?=$d?>  
          </td>
      </tr>

    </table>
  </div>

  <br>

  <div style="" >
    <table style="border-collapse: collapse;vertical-align: top;" >
      <tr style="background-color: #cccccc;">
        <td style="width:10%;border-bottom: 1px solid #ccc;text-align: center;" >REF-CODE</td>
        <td style="width:15%;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">รหัสสินค้า : ชื่อสินค้า</td>
        <td style="width:13%;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">ล็อตนัมเบอร์:วันหมดอายุ</td>
        <td style="width:25%;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">คลังสินค้า</td>
        <td style="width:5%;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">ยอดเดิม</td>
        <td style="width:5%;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">ยอดที่นับได้</td>
        <td style="width:10%;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">หมายเหตุ</td>
     </tr>

<?php 

  $P = DB::select(" SELECT  *  FROM  db_stocks_account where stocks_account_code_id_fk = ".$data[0]." ");

  foreach ($P as $key => $v) {

          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$v->product_id_fk." AND lang_id=1");
          
          $product_name =  @$Products[0]->product_code." : ".@$Products[0]->product_name;

          $d = strtotime($v->lot_expired_date); 
          $lot_number = $v->lot_number." : ".date("d/m/", $d).(date("Y", $d)+543);

          $sBranchs = DB::select(" select * from branchs where id=".$v->branch_id_fk." ");
          $warehouse = DB::select(" select * from warehouse where id=".$v->warehouse_id_fk." ");
          $zone = DB::select(" select * from zone where id=".$v->zone_id_fk." ");
          $shelf = DB::select(" select * from shelf where id=".$v->shelf_id_fk." ");
          $wh = @$sBranchs[0]->b_name.' > '.@$warehouse[0]->w_name.' > '.@$zone[0]->z_name.' > '.@$shelf[0]->s_name.' > ชั้น> '.@$v->shelf_floor;


     ?>
      <tr>
        <td style="border-bottom: 1px solid #ccc;text-align: center;"><?=@$v->run_code?></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: left;"><?=$product_name?></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"><?=$lot_number?></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"><?=$wh?></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"><?=$v->amt?></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> </td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> </td>
      </tr>

<?php } ?>

<?php for ($i=1;$i<=5;$i++){ ?>
      <tr>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
      </tr>
<?php } ?>

  <div style="height: 33mm; border: 1px solid grey;" >
    <table style="border-collapse: collapse;vertical-align: top;text-align: center;" >
      
      <tr>

        <td  style="border-left: 1px solid #ccc;"> ผู้ตรวจนับ

        <br>
        <img src="" width="100" > 
        <br>
        <br>
           วันที่ .........................................
         </td>

        <td  style="border-left: 1px solid #ccc;"> ผู้อนมุัติ

        <br>
        <img src="" width="100" > 
        <br>
        <br>
           วันที่ .........................................
         </td>

      </tr>

    </table>
  </div>
    </table>
  </div>



</div>

<footer>

  <?php echo "ผู้ดำเนินการ : ".@$sAction_user[0]->name; ?>

  <script type="text/php">
        $u = "".@$sAction_user[0]->name;
        $x = 40;
        $y = 550;
        $text = "Date : ".$u.date('d-m-Y');
        $font = null;
        $size = 14;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
</script>


<script type="text/php">
    if (isset($pdf)) {
        $x = 730;
        $y = 550;
        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $font = null;
        $size = 14;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
</script>
</footer>