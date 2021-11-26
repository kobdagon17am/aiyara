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
              db_delivery.id as delivery_id_fk
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
         * รายการสินค้า ใบเสร็จแนบท้าย
         <br>
         <br>
       </td>
  
      </tr>


    </table>
  </div>
  

  