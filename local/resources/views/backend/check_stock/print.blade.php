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



<?php /* dd($data['id'][0][0]); */ //echo ($data['lot_number'][0]) ; ?>
<?php  $value = DB::select(" SELECT * FROM db_stock_card where lot_number='".$data['lot_number'][0]."' AND product_id_fk = ".$data['id'][0]." "); 

       $Products = DB::select("
          SELECT products.id as product_id,
          products.product_code,
          (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
          FROM
          products_details
          Left Join products ON products_details.product_id_fk = products.id
          WHERE products.id=".$data['id'][0]." AND lang_id=1");


       $d = strtotime(date("Y-m-d")); $d = date("d/m/", $d).(date("Y", $d)+543);
?>

  <div class="NameAndAddress" style="" >
      <table style="border-collapse: collapse;" >
        <tr> 
          <th style="text-align: center;font-size: 30px;">
           <center> Stock Card </center>
          </th>
        </tr>
      </table>
    </div>

<div class="NameAndAddress" >

  <div style="border-radius: 5px; height: 14mm; border: 1px solid grey;padding:-1px;" >
    <table style="border-collapse: collapse;vertical-align: top;" >
      <tr>
          <td style="width:50%;vertical-align: top;font-weight: bold;" > 
            สินค้า / Product : {{ @$Products[0]->product_code." : ".@$Products[0]->product_name }} : LOT NUMBER = {{@$data['lot_number'][0]}}
          </td> 
          <td style="width:50%;vertical-align: top;font-weight: bold;text-align: right;padding-right: 2%;" > 
            วันที่ / Date : <?=$d?>  
          </td>
      </tr>

    </table>
  </div>

  <br>

  <div style="" >
    <table style="border-collapse: collapse;vertical-align: top;" >
      <tr style="background-color: #cccccc;">
        <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;" >id</td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">วันที่ดำเนินการ</td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">รายการเคลื่อนไหว</td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">รหัสอ้างอิง</td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">ยอดดำเนินการ</td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">ยอดคงเหลือ</td>
     </tr>

<?php 

  $P = DB::select(" SELECT * FROM db_stock_card where lot_number='".$data['lot_number'][0]."' AND product_id_fk = ".$data['id'][0]." "); 

  foreach ($P as $key => $v) {


     ?>
      <tr>
        <td style="border-bottom: 1px solid #ccc;text-align: center;"><?=@$v->id?></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: left;"><?=@$v->action_date?></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"><?=@$v->details?></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"><?=@$v->ref_inv?></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"><?=@$v->amt?></td>
        <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"><?=@$v->amt_remain?></td>
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
      </tr>
<?php } ?>

 
    </table>
  </div>



</div>


 