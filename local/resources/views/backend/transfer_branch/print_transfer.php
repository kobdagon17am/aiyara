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
      /*size: A4;*/
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


  #watermark {

       position: fixed;
      /**
          Set a position in the page for your image
          This should center it vertically
      **/
      top:   4.5cm;
      /*left:     4.5cm;*/

      /** Change image dimensions**/
      /*width:    8cm;*/
      /*height:   8cm;*/
      /** Your watermark should be behind every content**/
      z-index:  -1000;
  }


</style>


    <?php

    $tr_number = DB::select("
            SELECT
            db_transfer_branch_code.tr_number
            FROM
            db_transfer_branch_details
            Left Join db_transfer_branch_code ON db_transfer_branch_details.transfer_branch_code_id = db_transfer_branch_code.id
            WHERE
            db_transfer_branch_details.transfer_branch_code_id=".$data[0]."
            LIMIT 1
         ");

     ?>

    <div class="NameAndAddress" style="" >
      <table style="border-collapse: collapse;" >
        <tr>
          <th style="text-align: left;">
            <img src="<?=public_path('images/logo2.png')?>" >
          </th>
          <th style="text-align: right;">
            <span style="font-size: 24px;font-weight: bold;">[ <?php echo $tr_number[0]->tr_number; ?> ]</span><br>
              94 ซอยนาคนิวาส 6 ถนนนาคนิวาส <br>
              แขวงลาดพร้าว เขตลาดพร้าว กรุงเทพมหานคร 10230 ประเทศไทย <br>
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
           <center> ใบโอนสินค้าระหว่างสาขา </center>
          </th>
        </tr>
      </table>
    </div>

<?php

         $branchs_from = DB::select("
            SELECT
            branchs.b_name,
            db_transfer_branch_code.note,
            db_transfer_branch_code.approve_note
            FROM
            db_transfer_branch_code
            Left Join branchs ON db_transfer_branch_code.branch_id_fk = branchs.id
            WHERE db_transfer_branch_code.id = ".$data[0]."
         ");


         $branchs_to = DB::select("
            SELECT
            branchs.b_name
            FROM
            db_transfer_branch_details
            Left Join branchs ON db_transfer_branch_details.branch_id_fk = branchs.id
            WHERE db_transfer_branch_details.transfer_branch_code_id = ".$data[0]."
            LIMIT 1
         ");

?>

<div class="NameAndAddress" >
  <div style="border-radius: 5px;  border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;" >
      <tr>
        <td style="border-left: 1px solid #ccc;width: 50.7%;font-weight: bold;">สาขาต้นทาง : <?=@$branchs_from[0]->b_name?>
          <br>
          <br>
        </td>
        <td style="border-left: 1px solid #ccc;font-weight: bold;">สาขาปลายทาง : <?=@$branchs_to[0]->b_name?>
          <br>
          <br>
        </td>
      </tr>
    </table>
  </div>
</div>


<div class="" >

  <div style="border-radius: 5px; border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;width:100%;" >
      <tr style="background-color: #e6e6e6;">

        <td style="width:2%;border-bottom: 1px solid #ccc;text-align: center;" > ลำดับที่  </td>
        <td style="width:15%;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">รหัสสินค้า : ชื่อสินค้า </td>
        <td style="width:8%;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">ล็อตนัมเบอร์ </td>
        <td style="width:8%;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> วันหมดอายุ </td>
        <!-- <td style="width:8%;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> จำนวนที่มีในคลัง -->
        <td style="width:9%;border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">  จำนวนที่ต้องการโอน

      </tr>

<!-- รายการสินค้า -->
<?php


     $Dt = DB::select("
        SELECT *
        FROM db_transfer_branch_details
        WHERE
        transfer_branch_code_id = ".$data[0]."
     ");

     $j=1;

    foreach ($Dt as $key => $v) {


          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$v->product_id_fk." AND lang_id=1");

           $product_name = @$Products[0]->product_code." : ".@$Products[0]->product_name;

           $Check_stock = DB::select(" select * from db_stocks where id=".$v->stocks_id_fk." ");
           $amt_in_warehouse = @$Check_stock[0]->amt;

            $sBranchs = DB::select(" select * from branchs where id=".$v->branch_id_fk." ");
            $branchs = @$sBranchs[0]->b_name;

             $d_lot_expired_date = strtotime($v->lot_expired_date);
            //  $lot_expired_date = date("d/m/", $d_lot_expired_date).(date("Y", $d_lot_expired_date)+543);
            $lot_expired_date = date("d/m/", $d_lot_expired_date).(date("Y", $d_lot_expired_date));

           if(@$v->action_user!=''){
              $sD = DB::select(" select * from ck_users_admin where id=".$v->action_user." ");
               $action_user = @$sD[0]->name;
            }else{
               $action_user = '';
            }

            if(@$v->action_date!=''){
              // $action_date = strtotime($v->action_date); $action_date =  date("d/m/", $action_date).(date("Y", $action_date)+543);
              $action_date = strtotime($v->action_date); $action_date =  date("d/m/", $action_date).(date("Y", $action_date));
            }else{
              $action_date =  '';
            }


     ?>

          <tr>
            <td style="border-bottom: 1px solid #ccc;text-align: center;" > <?=$j?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$product_name?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->lot_number?>  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$lot_expired_date?> </td>
            <!-- <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <=$amt_in_warehouse?>  </td> -->
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->amt?>  </td>
          </tr>

    <?php

    $j++;


  }


  ?>

    </table>

    <label>&nbsp;&nbsp;&nbsp;<?php echo 'หมายเหตุ1 : '.$branchs_from[0]->note; ?></label><br>
    <label>&nbsp;&nbsp;&nbsp;<?php echo 'หมายเหตุ2 : '.$branchs_from[0]->approve_note; ?></label>

  </div>

<br>
</div>

<?php

         $warehouses_code = DB::select("
            SELECT *
            FROM db_transfer_branch_code
            WHERE
            id = ".$data[0]."
         ");


        if(@$warehouses_code[0]->approver!=''){
           $sD = DB::select(" select * from ck_users_admin where id=".$warehouses_code[0]->approver." ");
           $approver =  @$sD[0]->name;
        }else{
           $approver =  '-';
        }

        if(@$warehouses_code[0]->approve_date!=''){
          // $approve_date = strtotime($warehouses_code[0]->approve_date); $approve_date =  date("d/m/", $approve_date).(date("Y", $approve_date)+543);
          $approve_date = strtotime($warehouses_code[0]->approve_date); $approve_date =  date("d/m/", $approve_date).(date("Y", $approve_date));
        }else{
          $approve_date =  '';
        }

?>


      <?php if(@$warehouses_code[0]->approve_status==0){ ?>
        <div id="watermark">
            <!-- <img src="<=public_path('images/pending_approval.png')?>"  /> -->
        </div>
      <?php }else if(@$warehouses_code[0]->approve_status==2){ ?>
        <div id="watermark">
            <img src="<?=public_path('images/canceled.png')?>"  />
        </div>
      <?php }else if(@$warehouses_code[0]->approve_status==3){ ?>
        <div id="watermark">
            <img src="<?=public_path('images/disapproved.png')?>"  />
        </div>
      <?php } ?>


<div class="NameAndAddress" >

  <div style="border-radius: 5px;  border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;text-align: center;" >

      <tr>

        <td  style="border-left: 1px solid #ccc;"> ผู้ทำรายการโอน

        <br>
        <?=@$action_user?>
        <br>
           วันที่ <?=@$action_date?>
         </td>


        <td style="border-left: 1px solid #ccc;"> ผู้อนุมัติ
        <br>
        <?=@$approver?>
        <br>
        วันที่ <?=@$approve_date?>
        </td>


            <?php
                 $tr_number = $tr_number[0]->tr_number?$tr_number[0]->tr_number:0;
                 $branch_get = DB::select("
                    SELECT
                        db_transfer_branch_get.approve_date,
                        ck_users_admin.`name` as who_get
                        FROM
                        db_transfer_branch_get
                        LEFT Join ck_users_admin ON db_transfer_branch_get.approver = ck_users_admin.id
                        WHERE
                        tr_number =  '".$tr_number."'
                 ");


            if(@$branch_get[0]->approve_date!=''){
              // $get_date = strtotime($branch_get[0]->approve_date); $get_date =  " วันที่ ".date("d/m/", $get_date).(date("Y", $get_date)+543);
              $get_date = strtotime($branch_get[0]->approve_date); $get_date =  " วันที่ ".date("d/m/", $get_date).(date("Y", $get_date));
            }else{
              $get_date =  ' * รอฝั่งรับโอน รับสินค้า * ';
            }


            ?>


        <td style="border-left: 1px solid #ccc;"> ผู้รับ
        <br>
        <?=@$branch_get[0]->who_get?$branch_get[0]->who_get:''?>
        <br>
         <?=@$get_date?>
        </td>

      </tr>

    </table>
  </div>

</div>


<br>

<b>ส่วนงานคลังสินค้า</b>

<div class="NameAndAddress" >

  <div style="border-radius: 5px;  border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;text-align: center;" >

      <tr>
        <td  style="border-left: 1px solid #ccc; text-align: center;"> ผู้อนุมัติเบิกสินค้า
        <br>       <br>
        วันที่ ...............................
         </td>


        <td style="border-left: 1px solid #ccc; text-align:center;"> ผู้จ่ายสินค้า
        <br>       <br>
        วันที่ ...............................
        </td>

        <td style="border-left: 1px solid #ccc; text-align:center;"> ผู้ตรวจสอบสินค้า
        <br>       <br>
        วันที่ ...............................
        </td>

      </tr>

    </table>
  </div>

</div>
