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

    /* @page {
    size: A4;
    padding: 5px;
    orientation: landscape;
} */

    @media print {

        html,
        body {
            /*width: 210mm;*/
            /*height: 297mm;*/
        }
    }


    @charset "utf-8";

    .pagenum:before {
        content: counter(page);
    }


    .NameAndAddress {
        width: 100%;
        /*color: #898989;*/
        /*margin-bottom: 20px;*/
    }

    .NameAndAddress b {
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
        /* color: grey; */
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

<?php

ini_set('memory_limit', '9999999M');
ini_set('max_execution_time', '9999999');
set_time_limit(9999999);
?>


<div class="NameAndAddress" style="">
    <table style="border-collapse: collapse;">
        <!-- <tr>
            <th style="text-align: right;">&nbsp;</th>
            <th style="text-align: right;">
                หน้า 1/1
            </th>
        </tr> -->
        <tr>
            <th style="text-align: left;">
                <img src="{{ asset('images/logo2.png') }}">
            </th>
            <th style="text-align: right;">
                <br>
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
            <th style="font-size: 18px;">
                รายงานการขาย
                <br>
                <?php

                echo 'ประจำวันที่ ' . date('d/m/Y', strtotime($r->startDate_data));

                ?>
                <br>
            </th>
        </tr>
    </table>
</div>


<div class="NameAndAddress">
    <div style="bo  rder-radius: 5px; padding: 3px;">
        <table style="border-collapse: collapse;">
            <thead>
                <!-- <tr style="background-color: #e6e6e6;"> -->
                <tr>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        ลำดับ
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        รหัสสินค้า : ชื่อสินค้า
                    </td>

                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        ล็อตนัมเบอร์
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        วันหมดอายุ
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        จำนวน
                    </td>

                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        คลังสินค้า
                    </td>
                    <td style="border-left: 1px solid #ccc;border-bottom: 1px solid #ccc;text-align: center;"
                        rowspan="2">
                        สาขา
                    </td>
                </tr>

                <tr style="">
                </tr>
            </thead>

            <?php

            $business_location_id_fk = $r->business_location_id_fk;

            $db_stocks = \DB::table('db_stocks')
                ->select('business_location_id_fk')
                ->where('deleted_at', null)
                // ->where('db_stocks.date_in_stock', '>=', $r->startDate_data)
                ->where('db_stocks.date_in_stock', '<=', $r->endDate_data)
                ->when($r->business_location_id_fk != null, function ($query, $business_location_id_fk) {
                    return $query->where('db_stocks.business_location_id_fk', $business_location_id_fk);
                })
                ->get();


                // $db_stock_movement = \DB::table('db_stock_movement')
                // ->where('db_stock_movement.action_date', '>=', $r->startDate_data)
                // ->where('db_stock_movement.action_date', '<=', $r->endDate_data)
                // ->when($r->business_location_id_fk != null, function ($query, $business_location_id_fk) {
                //     return $query->where('db_stock_movement.business_location_id_fk', $business_location_id_fk);
                // })
                // ->get();

                // foreach(){
                //  // doc_date แปลงเป็น updated_at
                // // รายการก่อน start_date เพื่อหายอดยกมา
                // $Stock_movement_in = \App\Models\Backend\Stock_movement::
                //   where('product_id_fk',($Stock[0]->product_id_fk?$Stock[0]->product_id_fk:0))
                //   ->where('lot_number',($Stock[0]->lot_number?$Stock[0]->lot_number:0))
                //   ->where('lot_expired_date',($Stock[0]->lot_expired_date?$Stock[0]->lot_expired_date:0))
                //   ->where('business_location_id_fk',($Stock[0]->business_location_id_fk?$Stock[0]->business_location_id_fk:0))
                //   ->where('branch_id_fk',($Stock[0]->branch_id_fk?$Stock[0]->branch_id_fk:0))
                //   ->where('warehouse_id_fk',($Stock[0]->warehouse_id_fk?$Stock[0]->warehouse_id_fk:0))
                //   ->where('zone_id_fk',($Stock[0]->zone_id_fk?$Stock[0]->zone_id_fk:0))
                //   ->where('shelf_id_fk',($Stock[0]->shelf_id_fk?$Stock[0]->shelf_id_fk:0))
                //   ->where('shelf_floor',($Stock[0]->shelf_floor?$Stock[0]->shelf_floor:0))
                //   ->where('in_out','1')
                //   ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), "<", $request->start_date)
                //   ->selectRaw('sum(amt) as sum')
                //   ->get();

                //   // ยอดรับเข้า
                //   $amt_balance_in = @$Stock_movement_in[0]->sum?$Stock_movement_in[0]->sum:0;

                //   $Stock_movement_out = \App\Models\Backend\Stock_movement::
                //   where('product_id_fk',($Stock[0]->product_id_fk?$Stock[0]->product_id_fk:0))
                //   ->where('lot_number',($Stock[0]->lot_number?$Stock[0]->lot_number:0))
                //   ->where('lot_expired_date',($Stock[0]->lot_expired_date?$Stock[0]->lot_expired_date:0))
                //   ->where('business_location_id_fk',($Stock[0]->business_location_id_fk?$Stock[0]->business_location_id_fk:0))
                //   ->where('branch_id_fk',($Stock[0]->branch_id_fk?$Stock[0]->branch_id_fk:0))
                //   ->where('warehouse_id_fk',($Stock[0]->warehouse_id_fk?$Stock[0]->warehouse_id_fk:0))
                //   ->where('zone_id_fk',($Stock[0]->zone_id_fk?$Stock[0]->zone_id_fk:0))
                //   ->where('shelf_id_fk',($Stock[0]->shelf_id_fk?$Stock[0]->shelf_id_fk:0))
                //   ->where('shelf_floor',($Stock[0]->shelf_floor?$Stock[0]->shelf_floor:0))
                //   ->where('in_out','2')
                //   ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), "<", $request->start_date)
                //   ->selectRaw('sum(amt) as sum')
                //   ->get();

                //   // ยอดเบิกออก
                //   $amt_balance_out = @$Stock_movement_out[0]->sum?$Stock_movement_out[0]->sum:0;

                //   $amt_balance_stock = $amt_balance_in - $amt_balance_out ;
                //   $txt = "ยอดคงเหลือยกมา";
                //   DB::select(" UPDATE $temp_db_stock_card SET details='$txt',amt_in='$amt_balance_stock' WHERE id=1 ") ;

                //   // รายการตามช่วงวันที่ระบุ start_date to end_date
                //   $Stock_movement = \App\Models\Backend\Stock_movement::
                //   where('product_id_fk',($Stock[0]->product_id_fk?$Stock[0]->product_id_fk:0))
                //   ->where('lot_number',($Stock[0]->lot_number?$Stock[0]->lot_number:0))
                //   ->where('lot_expired_date',($Stock[0]->lot_expired_date?$Stock[0]->lot_expired_date:0))
                //   ->where('business_location_id_fk',($Stock[0]->business_location_id_fk?$Stock[0]->business_location_id_fk:0))
                //   ->where('branch_id_fk',($Stock[0]->branch_id_fk?$Stock[0]->branch_id_fk:0))
                //   ->where('warehouse_id_fk',($Stock[0]->warehouse_id_fk?$Stock[0]->warehouse_id_fk:0))
                //   ->where('zone_id_fk',($Stock[0]->zone_id_fk?$Stock[0]->zone_id_fk:0))
                //   ->where('shelf_id_fk',($Stock[0]->shelf_id_fk?$Stock[0]->shelf_id_fk:0))
                //   ->where('shelf_floor',($Stock[0]->shelf_floor?$Stock[0]->shelf_floor:0))
                //   ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), ">=", $request->start_date)
                //   ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), "<=", $request->end_date)
                //   // วุฒิเพิ่มมาเช็คไม่เอายอด 0
                //   ->where('amt','!=',0)
                //   ->get();

                //   if($Stock_movement->count() > 0){
                //             // doc_date แปลงเป็น updated_at
                //           foreach ($Stock_movement as $key => $value) {
                //             $full_detail = "";
                //             if($value->stock_type_id_fk==8){
                //               $ref_data = DB::table('db_transfer_branch_code')->select('db_transfer_branch_code.*','branchs.b_name')->where('tr_number',$value->ref_doc)
                //               ->join('branchs','branchs.id','db_transfer_branch_code.to_branch_id_fk')->first();
                //               if($ref_data){
                //                 $full_detail = "( ไปยัง ".$ref_data->b_name." )";
                //               }
                //             }

                //             if($value->stock_type_id_fk==9){
                //               $ref_data = DB::table('db_transfer_branch_code')->select('db_transfer_branch_code.*','branchs.b_name')->where('tr_number',$value->ref_doc)
                //               ->join('branchs','branchs.id','db_transfer_branch_code.branch_id_fk')->first();
                //               if($ref_data){
                //                 $full_detail = "( รับจาก ".$ref_data->b_name." )";
                //               }
                //             }

                //                $insertData = array(
                //                   "ref_inv" =>  @$value->ref_doc?$value->ref_doc:NULL,
                //                   "action_date" =>  @$value->updated_at?$value->updated_at:NULL,
                //                   "action_user" =>  @$value->action_user?$value->action_user:NULL,
                //                   "approver" =>  @$value->approver?$value->approver:NULL,
                //                   "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,
                //                   "sender" =>  @$value->sender?$value->sender:NULL,
                //                   "sent_date" =>  @$value->sent_date?$value->sent_date:NULL,
                //                   "who_cancel" =>  @$value->who_cancel?$value->who_cancel:NULL,
                //                   "cancel_date" =>  @$value->cancel_date?$value->cancel_date:NULL,
                //                   "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                //                   "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                //                   "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                //                   "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                //                   "details" =>  (@$value->note?$value->note:NULL).' '.(@$value->note2?$value->note2:NULL).' '.$full_detail,
                //                   "amt_in" =>  @$value->in_out==1?$value->amt:0,
                //                   "amt_out" =>  @$value->in_out==2?$value->amt:0,
                //                   "warehouse_id_fk" =>  @$value->warehouse_id_fk>0?$value->warehouse_id_fk:0,
                //                   "zone_id_fk" =>  @$value->zone_id_fk>0?$value->zone_id_fk:0,
                //                   "shelf_id_fk" =>  @$value->shelf_id_fk>0?$value->shelf_id_fk:0,
                //                   "shelf_floor" =>  @$value->shelf_floor>0?$value->shelf_floor:0,
                //                   "created_at" =>@$value->dd?$value->dd:NULL
                //               );

                //                DB::table($temp_db_stock_card)->insert($insertData);
                //           }

                //   }
                // }

            // dd($db_stocks);


            ?>

            <tbody>
                <tr>

                </tr>
            </tbody>




        </table>
    </div>

</div>
