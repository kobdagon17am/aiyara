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
                    SELECT * FROM db_po_supplier
                    WHERE
                    id =
                    ".$data[0]."

     ");


 ?>

    <div class="NameAndAddress" style="" >
      <table style="border-collapse: collapse;" >
        <tr>
          <th style="text-align: left;">
            <img src="http://krit.orangeworkshop.info/aiyara/backend/images/logo2.png" >
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
           <center> ใบสั่งซื้อสินค้า (PO)</center>
          </th>
        </tr>
      </table>
    </div>


<div class="NameAndAddress" >

  <div style="border-radius: 5px; height: 25mm; border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;" >
      <tr>
        <td style="width:30%;" >
         <?php

                $r_supplier = DB::select("SELECT * FROM `dataset_supplier` where id=".$value[0]->supplier_id_fk."");
                echo "<b>SUPPLIER : </b>"."<br>";
                echo $r_supplier[0]->txt_desc." : ";
                echo $r_supplier[0]->addr."<br>";
                echo $r_supplier[0]->tel."  ";

         ?>
      </td>
      <td style="width:10%;vertical-align: top;font-weight: bold;" >
        เลขที่ / No. <?=@$value[0]->po_number?><br>
        วันที่ / Date <?=ThDate01(@$value[0]->created_at)?>
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
        <td style="border-left: 1px solid #ccc;width:15%;border-bottom: 1px solid #ccc;text-align: center;"> จำนวน <br>
amt </td>
        <td style="border-left: 1px solid #ccc;width:15%;border-bottom: 1px solid #ccc;text-align: center;"> หน่วย <br>
Product Unit </td>
      </tr>

<!-- รายการสินค้า -->
<?php

     $P = DB::select("
                    SELECT * from db_po_supplier_products
                    WHERE
                    po_supplier_id_fk = 
                    ".$data[0]." 

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

          <tr>
            <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;" > <?=$i?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: left;"> <?=$product_name?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?=$v->product_amt?>  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> ชิ้น </td>
          </tr>

<?php $i++; } ?>

    </table>
  </div>

<br>



    </table>
  </div>

 
   <br>
  <div style="border-radius: 5px;  border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;text-align: center;width: 100%;" >

      <tr>

        <td  style="border-left: 1px solid #ccc;"> ผู้สั่งซื้อ
        <br>
        <br>
        <br>
           วันที่ .........................................
           <br>
         </td>

        <td style="border-left: 1px solid #ccc;"> ในนาม บริษัท ไอยรา แพลนเน็ต จำกัด
        <br>
        <br>
           ผู้มีอำนาจลงนาม
           <br>
            วันที่ .........................................
            <br>
        </td>

      </tr>

    </table>
  </div>


</div>


