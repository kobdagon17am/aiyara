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
?>

<?php 
  $count_row = DB::select(" SELECT count(*) as count_row FROM `temp_z01_print_frontstore_print_receipt_02`  ");
  $count_row = $count_row[0]->count_row ;
 ?>

<div class="NameAndAddress " >
    <table >
      <tr>
        <td style="width: 47% ;margin-left:35px !important;">

          <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (2) ; "); ?>
          <?php echo "<span style='font-size:24px;'>".@$DB[0]->a." <br> "; ?>

          <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (3) ; "); ?>
          <?php echo "<span style='font-size:24px;'>".@$DB[0]->a."</span><br>"; ?>
      </td>

<!-- THELP  -->
      <td style="vertical-align: top; font-size: 24px;font-weight: bold;" >
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (1) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>

      <td style="margin-left:25px !important;margin-top:18px !important;width:30%;vertical-align: top;" >
        <br> 
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (4) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
        <br>
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (5) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      </tr>
    </table>
    
    <table style="margin-left:10px !important;margin-top:44px !important;border-collapse: collapse;height: 150px !important;" >
<!-- รายการสินค้า -->

 <?php for ($i=6; $i <= 15 ; $i++) {  ?>

          <tr style="vertical-align: top;line-height: 10px !important;">

                <td style="width:2.6%;text-align: left;">
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i) ; "); ?>
                <?php echo @$DB[0]->a ; ?>
                </td>

                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i) ; "); ?>
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
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i) ; "); ?>
                <?php 
                if(@$DB[0]->c==""){
                   echo @$DB[0]->d ; 
                }
                ?>
                </td>

                <td style="width:5%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i) ; "); ?>
                <?php 
                if(@$DB[0]->c==""){
                  echo @$DB[0]->e ; 
                }
                ?>
                </td>

                <td style="width:4%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i) ; "); ?>
                <?php echo @$DB[0]->f ; ?>
                </td>

                <td style="width:11%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i) ; "); ?>
                <?php echo @$DB[0]->g ; ?>
                </td>

          </tr>

 <?php  }  ?>

    </table>

  <table style="border-collapse: collapse;vertical-align: top;margin-top:5px !important;" >
    <tr>
      <td style="margin-left:33px !important;width:80%;font-size: 14px;">
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (16) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
    </tr>

    <tr>
      <td style="margin-left:33px !important;width:80%;font-size: 14px;">
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (17) ; "); ?>
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
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (18) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
      <td style="font-size: 14px;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"> 
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (19) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;">
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (20) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
        <td style="text-align: left;" colspan="4">
          <span style="font-size: 14px !important;" >
          <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (21) ; "); ?>
          <?php echo @$DB[0]->a ; ?>
         </span>
      </td>
    </tr>
  </table>
</div>

 <div style="float:right;margin-top: 4%;font-size: 14px !important;">
  <!-- Page 1 -->
       <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (22) ; "); ?>
       <?php echo @$DB[0]->g ; ?>
 </div>


<!-- Page 2 -->
<?php if($count_row>22){ ?>

<?php $p2 = 22 ; ?>
<div class="NameAndAddress " >
    <table >
      <tr>
        <td style="width: 47% ;margin-left:35px !important;">

          <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (2+$p2) ; "); ?>
          <?php echo "<span style='font-size:24px;'>".@$DB[0]->a." <br> "; ?>

          <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (3+$p2) ; "); ?>
          <?php echo "<span style='font-size:24px;'>".@$DB[0]->a."</span><br>"; ?>
      </td>

<!-- THELP  -->
      <td style="vertical-align: top; font-size: 24px;font-weight: bold;" >
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (1+$p2) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>

      <td style="margin-left:25px !important;margin-top:18px !important;width:30%;vertical-align: top;" >
        <br> 
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (4+$p2) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
        <br>
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (5+$p2) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      </tr>
    </table>
    
    <table style="margin-left:10px !important;margin-top:44px !important;border-collapse: collapse;height: 150px !important;" >
<!-- รายการสินค้า -->

 <?php for ($i=6; $i <= 15 ; $i++) {  ?>

          <tr style="vertical-align: top;line-height: 10px !important;">

                <td style="width:2.6%;text-align: left;">
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i+$p2) ; "); ?>
                <?php echo @$DB[0]->a ; ?>
                </td>

                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i+$p2) ; "); ?>
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
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i+$p2) ; "); ?>
                <?php 
                if(@$DB[0]->c==""){
                   echo @$DB[0]->d ; 
                }
                ?>
                </td>

                <td style="width:5%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i+$p2) ; "); ?>
                <?php 
                if(@$DB[0]->c==""){
                  echo @$DB[0]->e ; 
                }
                ?>
                </td>

                <td style="width:4%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i+$p2) ; "); ?>
                <?php echo @$DB[0]->f ; ?>
                </td>

                <td style="width:11%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i+$p2) ; "); ?>
                <?php echo @$DB[0]->g ; ?>
                </td>

          </tr>

 <?php  }  ?>

    </table>

  <table style="border-collapse: collapse;vertical-align: top;margin-top:5px !important;" >
    <tr>
      <td style="margin-left:33px !important;width:80%;font-size: 14px;">
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (16+$p2) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
    </tr>

    <tr>
      <td style="margin-left:33px !important;width:80%;font-size: 14px;">
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (17+$p2) ; "); ?>
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
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (18+$p2) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
      <td style="font-size: 14px;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"> 
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (19+$p2) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;">
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (20+$p2) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
        <td style="text-align: left;" colspan="4">
          <span style="font-size: 14px !important;" >
          <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (21+$p2) ; "); ?>
          <?php echo @$DB[0]->a ; ?>
         </span>
      </td>
    </tr>
  </table>
</div>

 <div style="float:right;margin-top: 4%;font-size: 14px !important;">
  <!-- Page 1 -->
       <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (22+$p2) ; "); ?>
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

          <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (2+$p3) ; "); ?>
          <?php echo "<span style='font-size:24px;'>".@$DB[0]->a." <br> "; ?>

          <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (3+$p3) ; "); ?>
          <?php echo "<span style='font-size:24px;'>".@$DB[0]->a."</span><br>"; ?>
      </td>

<!-- THELP  -->
      <td style="vertical-align: top; font-size: 24px;font-weight: bold;" >
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (1+$p3) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>

      <td style="margin-left:25px !important;margin-top:18px !important;width:30%;vertical-align: top;" >
        <br> 
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (4+$p3) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
        <br>
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (5+$p3) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      </tr>
    </table>
    
    <table style="margin-left:10px !important;margin-top:44px !important;border-collapse: collapse;height: 150px !important;" >
<!-- รายการสินค้า -->

 <?php for ($i=6; $i <= 15 ; $i++) {  ?>

          <tr style="vertical-align: top;line-height: 10px !important;">

                <td style="width:2.6%;text-align: left;">
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i+$p3) ; "); ?>
                <?php echo @$DB[0]->a ; ?>
                </td>

                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i+$p3) ; "); ?>
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
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i+$p3) ; "); ?>
                <?php 
                if(@$DB[0]->c==""){
                   echo @$DB[0]->d ; 
                }
                ?>
                </td>

                <td style="width:5%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i+$p3) ; "); ?>
                <?php 
                if(@$DB[0]->c==""){
                  echo @$DB[0]->e ; 
                }
                ?>
                </td>

                <td style="width:4%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i+$p3) ; "); ?>
                <?php echo @$DB[0]->f ; ?>
                </td>

                <td style="width:11%;text-align: right;">
                <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in ($i+$p3) ; "); ?>
                <?php echo @$DB[0]->g ; ?>
                </td>

          </tr>

 <?php  }  ?>

    </table>

  <table style="border-collapse: collapse;vertical-align: top;margin-top:5px !important;" >
    <tr>
      <td style="margin-left:33px !important;width:80%;font-size: 14px;">
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (16+$p3) ; "); ?>
        <?php echo @$DB[0]->a ; ?>
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
    </tr>

    <tr>
      <td style="margin-left:33px !important;width:80%;font-size: 14px;">
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (17+$p3) ; "); ?>
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
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (18+$p3) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
      <td style="font-size: 14px;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"> 
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (19+$p3) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;"> </td>
      <td style="text-align: right;">
        <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (20+$p3) ; "); ?>
        <?php echo @$DB[0]->g ; ?>
      </td>
    </tr>

    <tr>
        <td style="text-align: left;" colspan="4">
          <span style="font-size: 14px !important;" >
          <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (21+$p3) ; "); ?>
          <?php echo @$DB[0]->a ; ?>
         </span>
      </td>
    </tr>
  </table>
</div>

 <div style="float:right;margin-top: 4%;font-size: 14px !important;">
  <!-- Page 1 -->
       <?php $DB = DB::select(" SELECT * FROM `temp_z01_print_frontstore_print_receipt_02` where id in (22+$p3) ; "); ?>
       <?php echo @$DB[0]->g ; ?>
 </div>
 
<?php } ?>