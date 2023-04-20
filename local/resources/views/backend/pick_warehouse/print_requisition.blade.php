<style>
    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: normal;
        src: url("{{ asset('backend/fonts/THSarabunNew.ttf') }}") format('truetype');
    }

    @font-face {
        font-family: 'THSarabunNew';
        font-style: normal;
        font-weight: bold;
        src: url("{{ asset('backend/fonts/THSarabunNew Bold.ttf') }}") format('truetype');
    }

    @font-face {
        font-family: 'THSarabunNew';
        font-style: italic;
        font-weight: normal;
        src: url("{{ asset('backend/fonts/THSarabunNew Italic.ttf') }}") format('truetype');
    }

    @font-face {
        font-family: 'THSarabunNew';
        font-style: italic;
        font-weight: bold;
        src: url("{{ asset('backend/fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
    }

    body {
        font-family: "THSarabunNew";
        font-size: 16px;
    }

    @page {
        size: A4;
        padding: 5px;
    }

    @media print {

        html,
        body {
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
        border-radius: 15px;
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
        height: 100px;
        /* Should be removed. Only for demonstration */
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }


    .topics tr {
        line-height: 10px;
    }


    tr.border_bottom td {
        border-bottom: 1px solid #ccc;
        ;
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



<div class="NameAndAddress" style="">
    <table style="border-collapse: collapse;">
        <tr>
            <th style="text-align: left;">
                <img src="<?= public_path('images/logo2.png') ?>">
            </th>
            <th style="text-align: right;">
                94 ซอยนาคนิวาส 6 ถนนนาคนิวาส <br>
                แขวงลาดพร้าว เขตลาดพร้าว กรุงเทพมหานคร 10230 ประเทศไทย <br>
                TEL : +66 (0) 2026 3555
                FAX : +66 (0) 2514 3944
                E-MAIL : info@aiyara.co.th
            </th>
        </tr>

    </table>
</div>

<div class="NameAndAddress" style="">
    <table style="border-collapse: collapse;">
        <tr>
            <th style="text-align: center;font-size: 30px;">
                <center> ใบเบิกสินค้า </center>
            </th>
        </tr>
    </table>
</div>

<?php

$packing = DB::select(' SELECT * FROM db_pick_pack_packing WHERE packing_code_id_fk=' . $data[0] . ' GROUP BY packing_code ');

?>

<div class="NameAndAddress">

    <div style="border-radius: 5px; height: 12mm; border: 1px solid grey;padding:-1px;">
        <table style="border-collapse: collapse;vertical-align: top;">
            <tr>
                <td style="width:30%;vertical-align: top;font-weight: bold">
                    รหัสใบเบิก / Ref.No. : {{ @$packing[0]->packing_code }}
                </td>
                <td style="width:30%;vertical-align: top;font-weight: bold;">
                    วันที่ทำใบเบิก / Date : {{ @$packing[0]->created_at }}
                </td>
            </tr>
        </table>
    </div>
    <br>


    <div style="border-radius: 5px; border: 1px solid grey;padding:-1px;">
        <table style="border-collapse: collapse;vertical-align: top;">
            <tr style="background-color: #e6e6e6;">
                <td style="width:8%;border-bottom: 1px solid #ccc;text-align: center;"> ลำดับที่ <br> Item

                </td>
                <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">รายการ<br>
                    Description </td>
                <td style="border-left: 1px solid #ccc;width:10%;border-bottom: 1px solid #ccc;text-align: center;">
                    จำนวน <br>
                    Quantity </td>
                <td style="border-left: 1px solid #ccc;width:10%;border-bottom: 1px solid #ccc;text-align: center;">
                    หน่วย <br>
                    Unit Price </td>
                <td style="border-left: 1px solid #ccc;width:25%;border-bottom: 1px solid #ccc;text-align: center;"> Lot
                </td>
                <td style="border-left: 1px solid #ccc;width:25%;border-bottom: 1px solid #ccc;text-align: center;">
                    หยิบจาก </td>
            </tr>

            <!-- รายการสินค้า -->
            <?php
$orders = DB::select(" SELECT * FROM db_pay_requisition_001  WHERE  pick_pack_requisition_code_id_fk='".$data[0]."'
        group by time_pay order By time_pay  ");
$p_wait  = '';
$arr_time_check = [];
$arr_time_check2 = [];
if(!empty($orders)){
// dd($orders);
foreach ($orders as $key0 => $value) {

 $Products = DB::select(" SELECT
            db_pay_requisition_002.*,sum(amt_get) as sum_amt_get
            FROM
            db_pay_requisition_002
            WHERE pick_pack_requisition_code_id_fk='".$value->pick_pack_requisition_code_id_fk."' and time_pay=".$value->time_pay." group by time_pay,product_id_fk ORDER BY product_name ");

if(!empty($Products)){

    $i=1;
    $total = 0;
    // dd($Products);
    foreach ($Products as $key => $p) {

          $zone = DB::select(" select * from zone where id=".$p->zone_id_fk." ");
          $shelf = DB::select(" select * from shelf where id=".$p->shelf_id_fk." ");
          $wh_data = DB::select(" select * from warehouse where id=".$p->warehouse_id_fk." ");

          // วุฒิเพิ่มมา เช็คว่าค้างจากบิลไหน
          $item_amt_remain = DB::table('db_pay_requisition_002_item')
          ->select('db_pay_requisition_002_item.*','db_orders.code_order')
          ->join('db_orders','db_orders.id','db_pay_requisition_002_item.order_id')
          ->where('db_pay_requisition_002_item.requisition_002_id',$p->id)
          ->where('db_pay_requisition_002_item.product_id_fk',$p->product_id_fk)
          ->get();
          $bill_remain = '';
          // dd($item_amt_remain);
          foreach($item_amt_remain as $item){
            if(!isset($arr_time_check[$item->order_id][$item->product_id_fk])){
              if($item->amt_remain>0){
                $arr_time_check[$item->order_id][$item->product_id_fk] = [
                  'requisition_002_id' => $item->requisition_002_id,
                  'code_order' => $item->code_order,
                       'time_pay' => $value->time_pay,
                ];
            }
            }else{
              if($arr_time_check[$item->order_id][$item->product_id_fk]['time_pay'] <  $value->time_pay){
                if($item->amt_remain>0){
                  $arr_time_check[$item->order_id][$item->product_id_fk] = [
                  'requisition_002_id' => $item->requisition_002_id,
                  'code_order' => $item->code_order,
                       'time_pay' => $value->time_pay,
                ];
                  }else{
                    $arr_time_check[$item->order_id][$item->product_id_fk] = [
                  'requisition_002_id' => $item->requisition_002_id,
                  'code_order' => '',
                       'time_pay' => $value->time_pay,
                ];

                  }

              }
            }

          }
          $old = [];
          foreach($arr_time_check as $key1 => $arr1){
            foreach($arr1 as $arr2){
              if(!isset($old[$arr2['code_order']])){
                $bill_remain .= $arr2['code_order'].'<br>';
                $old[$arr2['code_order']] = '';
              }

              }
          }

          if($p->zone_id_fk!=''){
            $sWarehouse = @$wh_data[0]->w_name.' / '.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.$p->shelf_floor;
            $lot_number = $p->lot_number.' <br>[expired '.$p->lot_expired_date.']';
          }else{
            // $sWarehouse = '<span style="width:200px;text-align:center;color:red;">*** ไม่มีสินค้าในคลัง ***</span>';
            $sWarehouse = '<span style="width:200px;text-align:center;color:red;">*** ค้างสินค้าจากบิลเลขที่  *** '.$bill_remain.' </span>';
            //
            $lot_number = '';
          }

     ?>

            @if ($p->zone_id_fk != '')
                <tr>
                    <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;"> <?= $i ?> </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> <?= $p->product_name ?>
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        <?= $p->sum_amt_get ?> </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> ชิ้น
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        <?= $lot_number ?> </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;">
                        <?= $sWarehouse ?> </td>
                </tr>
            @endif



            @if ($p->amt_get > 0 && $p->amt_remain > 0)
                <?php

                $i++;
                $sWarehouse = '<span style="width:200px;text-align:center;color:red;">*** ค้างสินค้าจากบิลเลขที่  *** ' . $bill_remain . ' </span>';
                ?>

                {{-- <tr>
            <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;" > <?= $i ?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> <?= $p->product_name ?> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> 0  </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> ชิ้น </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> <?= $sWarehouse ?> </td>
          </tr> --}}

                <?php

                $p_wait =
                    '
                                          <tr>
                                            <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;" > ' .
                    $i .
                    ' </td>
                                            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> ' .
                    $p->product_name .
                    ' </td>
                                            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> 0  </td>
                                            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> ชิ้น </td>
                                            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> </td>
                                            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> ' .
                    $sWarehouse .
                    ' </td>
                                          </tr>
                                          ';

                if (!isset($arr_time_check2[$item->order_id][$item->product_id_fk])) {
                    $arr_time_check2[$item->order_id][$item->product_id_fk] = [
                        'time_pay' => $value->time_pay,
                        'p_wait' => $p_wait,
                    ];
                } else {
                    if ($arr_time_check2[$item->order_id][$item->product_id_fk]['time_pay'] < $value->time_pay) {
                        $arr_time_check2[$item->order_id][$item->product_id_fk] = [
                            'time_pay' => $value->time_pay,
                            'p_wait' => $p_wait,
                        ];
                    }
                }

                ?>
            @elseif($p->zone_id_fk == '')
                <?php
                $p_wait =
                    '
                                          <tr>
                                            <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;" > ' .
                    $i .
                    ' </td>
                                            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;"> ' .
                    $p->product_name .
                    ' </td>
                                            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> 0  </td>
                                            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> ชิ้น </td>
                                            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> </td>
                                            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> ' .
                    $sWarehouse .
                    ' </td>
                                          </tr>
                                          ';

                if (!isset($arr_time_check2[$item->order_id][$item->product_id_fk])) {
                    $arr_time_check2[$item->order_id][$item->product_id_fk] = [
                        'time_pay' => $value->time_pay,
                        'p_wait' => $p_wait,
                    ];
                } else {
                    if ($arr_time_check2[$item->order_id][$item->product_id_fk]['time_pay'] < $value->time_pay) {
                        $arr_time_check2[$item->order_id][$item->product_id_fk] = [
                            'time_pay' => $value->time_pay,
                            'p_wait' => $p_wait,
                        ];
                    }
                }

                ?>
            @endif

            <?php
    $i++;

    $total += $p->sum_amt_get;

    }
  }

  ?>


            {{-- <tr>
            <td style="width:5%;border-bottom: 1px solid #ccc;text-align: center;" > </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: right;padding-right: 10px;font-weight: bold;"> รวมจำนวน </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;font-weight: bold;"> {{@$total}} </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> </td>
            <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"> </td>
          </tr> --}}

            <?php }
// dd($arr_time_check);
?>
            <?php } ?>

            <?php
            $p_wait = '';
            foreach ($arr_time_check2 as $key1 => $arr1) {
                foreach ($arr1 as $arr2) {
                    $p_wait .= $arr2['p_wait'];
                }
            }

            echo $p_wait;
            ?>

        </table>
    </div>

    <div>&nbsp;</div>

    <?php
$db_pick_pack_packing_code = DB::table('db_pick_pack_packing_code')->select('approver','action_user','created_at','aprove_date')->where('id',@$packing[0]->packing_code_id_fk)->first();
if($db_pick_pack_packing_code){
  $admin = DB::table('ck_users_admin')->select('name')->where('id',$db_pick_pack_packing_code->approver)->first();
  $action_user = DB::table('ck_users_admin')->select('name')->where('id',$db_pick_pack_packing_code->action_user)->first();
}

?>


    <div style="border-radius: 5px; height: 28mm; border: 1px solid grey;padding:-1px; ">
        <table style="border-collapse: collapse;vertical-align: top;text-align: center;">

            <tr>

                <td style="border-left: 1px solid #ccc;"> ผู้เบิกสินค้า

                    <br>
                   {{$action_user->name}}
                   <br>
                    {{-- วันที่ ......................................... --}}
                    วันที่ @if($db_pick_pack_packing_code->created_at!=''){{date('d/m/Y', strtotime($db_pick_pack_packing_code->created_at))}}@endif
                </td>


                <td style="border-left: 1px solid #ccc;"> ในนาม บริษัท ไอยรา แพลนเน็ต จำกัด
                    <br>
                    <img src="" width="100">
                    <br>
                    <br>
                    ผู้มีอำนาจลงนาม
                </td>

            </tr>


        </table>
    </div>

</div>

<div class="NameAndAddress" >
  <b>ส่วนงานคลังสินค้า</b>
  <div style="border-radius: 5px;  border: 1px solid grey;padding:-1px;" >

    <table style="border-collapse: collapse;vertical-align: top;text-align: center;" >

      <tr>
        <td  style="border-left: 1px solid #ccc; text-align: center;"> ผู้อนุมัติเบิกสินค้า
        <br>   {{@$admin->name}}    <br>
        {{-- วันที่ ............................... --}}
        วันที่ @if($db_pick_pack_packing_code->aprove_date!=''){{date('d/m/Y', strtotime($db_pick_pack_packing_code->aprove_date))}}@endif
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
