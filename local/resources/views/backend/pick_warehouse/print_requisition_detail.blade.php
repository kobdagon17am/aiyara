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

            if(@$delivery[0]->status_pack==1){

                  $d1 = DB::select(" SELECT * from db_delivery WHERE id=".$delivery[0]->delivery_id_fk."");
                  $d2 = DB::select(" SELECT * from db_delivery WHERE packing_code=".$d1[0]->packing_code."");
                  $arr1 = [];
                  foreach ($d2 as $key => $v) {
                    array_push( $arr1 ,$v->receipt);
                  }
                  $receipt = implode(',',$arr1);
          
            }else{
                  $receipt = @$delivery[0]->receipt;
            }

// dd($receipt);
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



// วุฒิเพิ่มมา เช็คว่ารวมบิลไหม
$count_arr = explode(',',$receipt); 
if(count($count_arr)==1){
  $sTable = DB::select(" 

SELECT 
db_pick_pack_packing.id,
db_pick_pack_packing.p_size,
db_pick_pack_packing.p_weight,
db_pick_pack_packing.p_amt_box,
db_pick_pack_packing.packing_code_id_fk as packing_code_id_fk,
db_pick_pack_packing.packing_code as packing_code,
CASE WHEN db_delivery_packing.packing_code is not null THEN concat(db_delivery_packing.packing_code,' (packing)') ELSE db_delivery.receipt END as lists ,
CASE WHEN db_delivery_packing.packing_code is not null THEN 'packing' ELSE 'no_packing' END as remark,
db_delivery.id as db_delivery_id,
db_delivery.packing_code as db_delivery_packing_code
FROM `db_pick_pack_packing` 
LEFT JOIN db_delivery on db_delivery.id=db_pick_pack_packing.delivery_id_fk
LEFT JOIN db_delivery_packing on db_delivery_packing.delivery_id_fk=db_delivery.id
WHERE 

db_pick_pack_packing.packing_code_id_fk =".$data[1]." 

AND db_delivery_packing.packing_code is null

ORDER BY db_pick_pack_packing.id

");

foreach ($sTable as $key => $row) {

            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=     
            '<div class="divTableRow">
            <div class="divTableCell" style="width:200px;font-weight:bold;">รหัส : ชื่อสินค้า</div>
            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
            <div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
            </div>
            ';


              // ต้องรวมสินค้า โปรโมชั่น ด้วย 

           $Products = DB::select("

                SELECT
                db_pay_requisition_002.id,
                db_pay_requisition_002.time_pay,
                db_pay_requisition_002.business_location_id_fk,
                db_pay_requisition_002.branch_id_fk,
                db_pay_requisition_002.pick_pack_requisition_code_id_fk,
                db_pay_requisition_002.customers_id_fk,
                db_pay_requisition_002.product_id_fk,
                db_pay_requisition_002.product_name,
                db_pay_requisition_002.amt_need,
                db_pay_requisition_002.amt_get as amt,
                db_pay_requisition_002.amt_lot,
                db_pay_requisition_002.amt_remain,
                db_pay_requisition_002.product_unit_id_fk,
                db_pay_requisition_002.product_unit,
                db_pay_requisition_002.lot_number,
                db_pay_requisition_002.lot_expired_date,
                db_pay_requisition_002.warehouse_id_fk,
                db_pay_requisition_002.zone_id_fk,
                db_pay_requisition_002.shelf_id_fk,
                db_pay_requisition_002.shelf_floor,
                db_pay_requisition_002.status_cancel,
                db_pay_requisition_002.created_at,
                db_pay_requisition_002.updated_at,
                db_pay_requisition_002.deleted_at
                FROM `db_pay_requisition_002`
                WHERE pick_pack_requisition_code_id_fk='".@$row->packing_code_id_fk."' 



                 ");

              $sum_amt = 0 ;
              $r_ch_t = '';

         if(@$Products){
            
              foreach ($Products as $key => $value) {

                if(!empty($value->product_id_fk)){

                // หา max time_pay ก่อน 
                 $r_ch01 = DB::select("SELECT time_pay FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$value->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$row->packing_code_id_fk." order by time_pay desc limit 1  ");
              // Check ว่ามี status=2 ? (ค้างจ่าย)
                 $r_ch02 = DB::select("SELECT * FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$value->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$row->packing_code_id_fk." and time_pay=".$r_ch01[0]->time_pay." and status=2 ");
                 if(count($r_ch02)>0){
                    $r_ch_t = '(รายการนี้ค้างจ่ายในรอบนี้ สินค้าในคลังมีไม่เพียงพอ)';
                 }else{
                   $r_ch_t = '';
                 }


                $sum_amt += $value->amt;
                $pn .=     
                '<div class="divTableRow">
                <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
                '.@$value->product_name.'</b><br>
                <font color=red>'.$r_ch_t.'</font>
                </div>
                <div class="divTableCell" style="text-align:center;">'.@$value->amt.'</div> 
                <div class="divTableCell" style="text-align:center;">'.@$value->product_unit.'</div> 
                ';
                $pn .= '</div>';  
              
              }
              }

              $pn .=     
              '<div class="divTableRow">
              <div class="divTableCell" style="text-align:right;font-weight:bold;"> รวม </div>
              <div class="divTableCell" style="text-align:center;font-weight:bold;">'.@$sum_amt.'</div>
              <div class="divTableCell" style="text-align:center;"> </div>
              </div>
              ';

          $pn .= '</div>';  
          echo $pn;
        }
   
      }
}else{
  $sTable = DB::select(" 

SELECT 
db_pick_pack_packing.id,
db_pick_pack_packing.p_size,
db_pick_pack_packing.p_weight,
db_pick_pack_packing.p_amt_box,
db_pick_pack_packing.packing_code_id_fk as packing_code_id_fk,
db_pick_pack_packing.packing_code as packing_code,
CASE WHEN db_delivery_packing.packing_code is not null THEN concat(db_delivery_packing.packing_code,' (packing)') ELSE db_delivery.receipt END as lists ,
CASE WHEN db_delivery_packing.packing_code is not null THEN 'packing' ELSE 'no_packing' END as remark,
db_delivery.id as db_delivery_id,
db_delivery.packing_code as db_delivery_packing_code
FROM `db_pick_pack_packing` 
LEFT JOIN db_delivery on db_delivery.id=db_pick_pack_packing.delivery_id_fk
LEFT JOIN db_delivery_packing on db_delivery_packing.delivery_id_fk=db_delivery.id
WHERE 

db_pick_pack_packing.packing_code_id_fk =".$data[1]." 

AND db_delivery_packing.packing_code is not null

ORDER BY db_pick_pack_packing.id

");
  
foreach ($sTable as $key => $row) {

$pn = '<div class="divTable"><div class="divTableBody">';
$pn .=     
'<div class="divTableRow">
<div class="divTableCell" style="width:200px;font-weight:bold;">รหัส : ชื่อสินค้า</div>
<div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
<div class="divTableCell" style="width:50px;text-align:center;font-weight:bold;"> หน่วย </div>
</div>
';


  // ต้องรวมสินค้า โปรโมชั่น ด้วย 

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

WHERE type_product='product' AND db_delivery_packing.packing_code_id_fk = '".$row->db_delivery_packing_code."' 

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

WHERE type_product='promotion' AND db_delivery_packing.packing_code_id_fk = '".$row->db_delivery_packing_code."' 

GROUP BY db_order_products_list.product_id_fk
)
                 ");

  $sum_amt = 0 ;
  $r_ch_t = '';

if(@$Products){

  foreach ($Products as $key => $value) {

    if(!empty($value->product_id_fk)){

    // หา max time_pay ก่อน 
     $r_ch01 = DB::select("SELECT time_pay FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$value->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$row->packing_code_id_fk." order by time_pay desc limit 1  ");
  // Check ว่ามี status=2 ? (ค้างจ่าย)
     $r_ch02 = DB::select("SELECT * FROM `db_pay_requisition_002_pay_history` where product_id_fk in(".$value->product_id_fk.") AND  pick_pack_packing_code_id_fk=".$row->packing_code_id_fk." and time_pay=".$r_ch01[0]->time_pay." and status=2 ");
     if(count($r_ch02)>0){
        $r_ch_t = '(รายการนี้ค้างจ่ายในรอบนี้ สินค้าในคลังมีไม่เพียงพอ)';
     }else{
       $r_ch_t = '';
     }


    $sum_amt += $value->amt;
    $pn .=     
    '<div class="divTableRow">
    <div class="divTableCell" style="padding-bottom:15px;width:250px;"><b>
    '.@$value->product_name.'</b><br>
    '.@$value->code_order.'<br>
    <font color=red>'.$r_ch_t.'</font>
    </div>
    <div class="divTableCell" style="text-align:center;">'.@$value->amt.'</div> 
    <div class="divTableCell" style="text-align:center;">'.@$value->product_unit.'</div> 
    ';
    $pn .= '</div>';  
  
  }
  }

  $pn .=     
  '<div class="divTableRow">
  <div class="divTableCell" style="text-align:right;font-weight:bold;"> รวม </div>
  <div class="divTableCell" style="text-align:center;font-weight:bold;">'.@$sum_amt.'</div>
  <div class="divTableCell" style="text-align:center;"> </div>
  </div>
  ';

$pn .= '</div>';  
echo $pn;
}

}

}


           ?>

    </table>
  </div>