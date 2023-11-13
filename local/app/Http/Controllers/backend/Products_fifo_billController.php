<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;
use Redirect;
use Session;
use PDO;

class Products_fifo_billController extends Controller
{

    public function index(Request $request)
    {
      // return view('backend.consignments_import.index');
    }

 public function create()
    {

    }
    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      // return $this->form($id);
    }

   public function form($id=NULL)
    {
      // \DB::beginTransaction();
      // try {
      //     if( $id ){
      //       $sRow = \App\Models\Backend\Consignments::find($id);
      //     }else{
      //       $sRow = new \App\Models\Backend\Consignments;
      //     }

      //     // $sRow->operator    = request('operator');
      //     // $sRow->last_update    = request('last_update');
      //     $sRow->created_at = date('Y-m-d H:i:s');
      //     $sRow->save();


      //     \DB::commit();

      //    // return redirect()->action('backend\Products_fifo_billController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
      //     return redirect()->to(url("backend/pick_warehouse/".$sRow->id."/edit"));

      // } catch (\Exception $e) {
      //   echo $e->getMessage();
      //   \DB::rollback();
      //   return redirect()->action('backend\Products_fifo_billController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      // }
    }

    public function destroy($id)
    {
      // $sRow = \App\Models\Backend\Consignments::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      // $sTable = \App\Models\Backend\Products_fifo_bill::search();
      $c = "SELECT db_orders.invoice_code FROM
        db_order_products_list
        Left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
        WHERE  db_order_products_list.product_id_fk in(SELECT product_id_fk FROM db_pick_warehouse_fifo_topicked where status=1)";

      $sTable = DB::select("
              SELECT * from db_products_fifo_bill where recipient_code in ($c)
              ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {

        if(!empty($row->recipient_code)){

            $Products = DB::select("
              SELECT
              db_orders.invoice_code,
              (SELECT product_code FROM products WHERE id=db_order_products_list.product_id_fk limit 1) as product_code,
              (SELECT product_name FROM products_details WHERE product_id_fk=db_order_products_list.product_id_fk and lang_id=1 limit 1) as product_name,
              db_order_products_list.amt,
              dataset_product_unit.product_unit
              FROM
              db_order_products_list
              Left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
              Left Join dataset_product_unit ON db_order_products_list.product_unit_id_fk = dataset_product_unit.id
              WHERE db_orders.invoice_code='".$row->recipient_code."' AND db_order_products_list.product_id_fk in(SELECT product_id_fk FROM db_pick_warehouse_fifo_topicked)
              ");

            $pn = '<div class="divTable"><div class="divTableBody">';

            foreach ($Products as $key => $value) {
              $pn .=
                  '<div class="divTableRow">
                  <div class="divTableCell" style="width:400px;">'.$value->product_code.' : '.$value->product_name.'</div>
                  <div class="divTableCell" style="width:50px;text-align:center;">'.($value->amt).'</div>
                  <div class="divTableCell">'.$value->product_unit.'</div>
                  </div>
                  ';
             }

            $pn .= '</div></div>';

            return $pn;

        }

      })
      ->escapeColumns('product_name')
      ->addColumn('get_product', function($row) {

        if(!empty($row->recipient_code)){

            $Products = DB::select("
              SELECT
              db_orders.invoice_code,
              (SELECT product_code FROM products WHERE id=db_order_products_list.product_id_fk limit 1) as product_code,
              (SELECT product_name FROM products_details WHERE product_id_fk=db_order_products_list.product_id_fk and lang_id=1 limit 1) as product_name,
              db_order_products_list.amt,
              dataset_product_unit.product_unit,
              (SELECT amt_get FROM db_pick_warehouse_tmp WHERE invoice_code=db_orders.invoice_code AND product_code=(SELECT product_code FROM products WHERE id=db_order_products_list.product_id_fk limit 1) LIMIT 1) as amt_get
              FROM
              db_order_products_list
              Left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
              Left Join dataset_product_unit ON db_order_products_list.product_unit_id_fk = dataset_product_unit.id
              WHERE db_orders.invoice_code='".$row->recipient_code."' AND  db_order_products_list.product_id_fk in(SELECT product_id_fk FROM db_pick_warehouse_fifo_topicked)
              ");

            $pn = '<div class="divTable"><div class="divTableBody">';

            foreach ($Products as $key => $value) {
              $pn .=
                  '<div class="divTableRow">
                  <div class="divTableCell">'.($value->amt_get>0?$value->amt_get:'* รอแจงเบิกจากคลัง ').'</div>
                  </div>
                  ';
             }

            $pn .= '</div></div>';

            return $pn;

        }

      })
      ->escapeColumns('get_product')
      ->make(true);
    }




    public function DatatableToSend(){

      $sTable = DB::select("
              SELECT * from db_pick_warehouse_tmp
              ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name_desc', function($row) {

        if(!empty($row->recipient_code)){

            $Products = DB::select("
              SELECT
              db_orders.invoice_code,
              (SELECT product_code FROM products WHERE id=db_order_products_list.product_id_fk limit 1) as product_code,
              (SELECT product_name FROM products_details WHERE product_id_fk=db_order_products_list.product_id_fk and lang_id=1 limit 1) as product_name,
              db_order_products_list.amt,
              dataset_product_unit.product_unit
              FROM
              db_order_products_list
              Left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
              Left Join dataset_product_unit ON db_order_products_list.product_unit_id_fk = dataset_product_unit.id
              WHERE db_orders.invoice_code='".$row->recipient_code."' AND db_order_products_list.product_id_fk in(SELECT product_id_fk FROM db_pick_warehouse_fifo_topicked)
              ");

            $pn = '<div class="divTable"><div class="divTableBody">';

            foreach ($Products as $key => $value) {
              $pn .=
                  '<div class="divTableRow">
                  <div class="divTableCell" style="width:400px;">'.$value->product_code.' : '.$value->product_name.'</div>
                  <div class="divTableCell" style="width:50px;text-align:center;">'.($value->amt).'</div>
                  <div class="divTableCell">'.$value->product_unit.'</div>
                  </div>
                  ';
             }

            $pn .= '</div></div>';

            return $pn;

        }

      })
      ->escapeColumns('product_name')
      ->make(true);
    }



    public function DatatableToSend1(Request $req){

      if(isset($req->txtSearch)){
        $w001 = " AND db_pick_warehouse_tmp.invoice_code='".$req->txtSearch."' ";
      }else{
        $w001 = "";
      }

      $sTable = DB::select("
              SELECT db_pick_warehouse_tmp.*,db_consignments.recipient_name,db_consignments.address,db_consignments.sent_date,db_consignments.approver from db_pick_warehouse_tmp
              Left Join db_consignments ON db_consignments.recipient_code = db_pick_warehouse_tmp.invoice_code
              where 1
              ".$w001."
              group by invoice_code ORDER BY db_pick_warehouse_tmp.updated_at DESC LIMIT 1
              ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {

            $Products = DB::select("
              SELECT
              db_orders.invoice_code,
              (SELECT product_code FROM products WHERE id=db_order_products_list.product_id_fk limit 1) as product_code,
              (SELECT product_name FROM products_details WHERE product_id_fk=db_order_products_list.product_id_fk and lang_id=1 limit 1) as product_name,
              db_order_products_list.amt,
              dataset_product_unit.product_unit,
              db_order_products_list.product_id_fk
              FROM
              db_order_products_list
              Left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
              Left Join dataset_product_unit ON db_order_products_list.product_unit_id_fk = dataset_product_unit.id
              WHERE db_orders.invoice_code = '".$row->invoice_code."'
              ");

            $pn = '<div class="divTable"><div class="divTableBody">';


              foreach ($Products as $key => $value) {

                $pn .=
                    '<div class="divTableRow">
                    <div class="divTableCell" style="width:250px;">'.$value->product_code.' <br/> '.$value->product_name.'</div>
                    <div class="divTableCell" style="width:50px;text-align:center;">'.($value->amt).'</div>
                    <div class="divTableCell" style="width:70px;">'.$value->product_unit.'</div>
                    <div class="divTableCell" style="padding-bottom:15px !important;">
                    ';
                    $item_id = 1;
                    for ($i=0; $i < $value->amt ; $i++) {

                      $qr = DB::select(" select qr_code,updated_at from db_pick_warehouse_qrcode where item_id='".$item_id."' and invoice_code='".$value->invoice_code."' AND product_id_fk='".$value->product_id_fk."' ");

                      if(@$qr[0]->updated_at < date("Y-m-d") && !empty(@$qr[0]->qr_code) ){

                        $pn .=
                         '
                          <input type="text" style="width:122px;" value="'.@$qr[0]->qr_code.'" readonly >
                          <i class="fa fa-times-circle fa-2 " aria-hidden="true" style="color:grey;" ></i>
                         ';

                      }else{

                         $pn .=
                         '
                          <input type="text" class="in-tx qr_scan " data-item_id="'.$item_id.'" data-invoice_code="'.$value->invoice_code.'" data-product_id_fk="'.$value->product_id_fk.'" placeholder="scan qr" style="width:122px;'.(empty(@$qr[0]->qr_code)?"background-color:blanchedalmond;":"").'" value="'.@$qr[0]->qr_code.'" >
                          <i class="fa fa-times-circle fa-2 btnDeleteQrcodeProduct " aria-hidden="true" style="color:red;cursor:pointer;" data-item_id="'.$item_id.'" data-invoice_code="'.$value->invoice_code.'" data-product_id_fk="'.$value->product_id_fk.'" ></i>
                         ';
                      }

                         $item_id++;
                    }

                $pn .= ' </div>';
                $pn .= '
                <div class="divTableCell" style="width:250px;">
                  คลัง 1/Zone-1/Shelf-1/ชั้น>1 <br>
                  คลัง 1/Zone-1/Shelf-1/ชั้น>2 <br>
                </div>';
                $pn .= ' </div>';

               }

            $pn .= '</div></div>';

            return @$pn;


      })
      ->escapeColumns('product_name')
      ->addColumn('address', function($row) {
          // $print = '<br/><br/> <center><a href="backend/delivery/pdf01/1" target=_blank title="พิมพ์ใบจ่าหน้ากล่อง" ><i class="bx bxs-file-pdf grow " style="font-size:24px;cursor:pointer;color:#0099cc;"></i></a></center>';
          // ถ้าเป็น จ่ายสินค้าตามใบเสร็จ ไม่ตรงพิมพ์ใบจ่าหน้ากล่องส่ง
          // return @$row->recipient_name.",<br/> ".@$row->address.$print;
          return @$row->recipient_name.",<br/> ".@$row->address;
      })
      ->escapeColumns('address')
      ->addColumn('column_001', function($row) {
          // return @$row->invoice_code." <br/><br/> <center><a href='backend/delivery/print_receipt01/1' target=_blank title='พิมพ์ใบเสร็จ'><i class='bx bx-printer grow' style='font-size:24px;cursor:pointer;color:#0099cc;'></i></a></center>";
          return @$row->invoice_code;
      })
      ->escapeColumns('column_001')
      ->addColumn('approver', function($row) {
          $d = DB::select(" select * from ck_users_admin ");
          return @$d[0]->name;
      })
      ->make(true);
    }




    public function DatatableConsignments(){

      $sTable = DB::select("
              SELECT * from db_pick_warehouse_consignments
              ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->make(true);
    }

// /backend/pay_product_receipt
// %%%%%%%%%%%%%%%%%%%%%%%
   public function DatatablePayReceiptFIFO(Request $req){

      $temp_ppr_001 = "temp_ppr_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
      $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;

      $TABLES = DB::select(" SHOW TABLES ");
      // ch_amt_lot_wh
      // return $TABLES; temp_ppp_002
      $array_TABLES = [];
      foreach($TABLES as $t){
        // print_r($t->Tables_in_aiyaraco_v3);
        array_push($array_TABLES, $t->Tables_in_aiyaraco_v3);
      }
      // เก็บรายการสินค้าที่จ่าย ตอน FIFO

      if(in_array($temp_ppr_004,$array_TABLES)){
        // return "IN";
        DB::select(" TRUNCATE TABLE $temp_ppr_004  ");
      }else{
        // return "Not";
        DB::select(" CREATE TABLE $temp_ppr_004 LIKE temp_ppr_004_template ");
      }

      $invoice_code = $req->invoice_code;

      if(!empty($req->invoice_code)){
      $sTable = DB::select("
        SELECT * from $temp_ppr_001 WHERE invoice_code='$invoice_code'
        ");
      }else{
         $sTable = DB::select("
          SELECT * from $temp_ppr_001 WHERE invoice_code='O000'
        ");
      }

      $sQuery = \DataTables::of($sTable);

      return $sQuery
       ->addColumn('column_001', function($row) {
            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="width:50px;font-weight:bold;">ครั้งที่</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">วันที่จ่าย</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;"> พนักงาน </div>
            </div>
            ';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            </div>
            ';
            return $pn;
        })
       ->escapeColumns('column_001')
      ->addColumn('column_002', function($row) {

        if(!empty($row)){

          $temp_ppr_001 = "temp_ppr_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
          $temp_ppr_002 = "temp_ppr_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
          $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id; // เก็บรายการที่หยิบสินค้ามาจากชั้นต่างๆ ตอน FIFO
          $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
          $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
          $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
          $temp_product_fifo = "temp_product_fifo".\Auth::user()->id;

// สินค้าปกติ  + สินค้าโปร ด้วย
          //  $Products = DB::select("
          //   SELECT $temp_ppr_002.*,dataset_product_unit.product_unit,$temp_ppr_001.customers_id_fk as cus_id from $temp_ppr_002
          //   LEFT Join dataset_product_unit ON $temp_ppr_002.product_unit_id_fk = dataset_product_unit.id
          //   LEFT JOIN $temp_ppr_001 on $temp_ppr_002.frontstore_id_fk=$temp_ppr_001.id
          //   where $temp_ppr_002.frontstore_id_fk=".$row->id." and $temp_ppr_002.type_product='product'
          // ");
/*
           SELECT temp_ppr_0021.*,dataset_product_unit.product_unit,temp_ppr_0011.customers_id_fk as cus_id from temp_ppr_0021
            LEFT Join dataset_product_unit ON temp_ppr_0021.product_unit_id_fk = dataset_product_unit.id
            LEFT JOIN temp_ppr_0011 on temp_ppr_0021.frontstore_id_fk=temp_ppr_0011.id
            where temp_ppr_0021.frontstore_id_fk=895 and temp_ppr_0021.type_product='product'
*/


DB::select(" DROP TABLE IF EXISTS $temp_product_fifo; ");
// สินค้าปกติ
// TEMPORARY
DB::select(" CREATE TABLE $temp_product_fifo
SELECT $temp_ppr_002.product_id_fk,sum($temp_ppr_002.amt) as amt,$temp_ppr_002.product_name,dataset_product_unit.product_unit from $temp_ppr_002 LEFT Join dataset_product_unit ON $temp_ppr_002.product_unit_id_fk = dataset_product_unit.id where $temp_ppr_002.frontstore_id_fk=".$row->id." and $temp_ppr_002.type_product='product'
group by $temp_ppr_002.product_id_fk
");
// สินค้าโปรโมชั่น
// วุฒิเพิ่ม
$temp_ppr_0021_data = DB::table($temp_ppr_002)->get();
foreach($temp_ppr_0021_data as $tmp){
  $p_unit = 'ชิ้น';
  $p_code = '0000:';
  $p_name = '';
  $p_id = 0;
  $p_amt = 0;
  if($tmp->type_product=='promotion'){
  $data_promos = DB::table('promotions_products')->where('promotion_id_fk',$tmp->promotion_id_fk)->get();
    foreach($data_promos as $data_promo){
    $p_id = $data_promo->product_id_fk;
    $data_unit  = DB::table('dataset_product_unit')->where('id',$data_promo->product_unit)->first();
    if($data_unit ){
      $p_unit = $data_unit->product_unit;
    }
    $data_code = DB::table('products')->where('id',$data_promo->product_id_fk)->first();
    if($data_code){
      $p_code = $data_code->product_code.' : ';
    }
    $data_product = DB::table('products_details')->where('product_id_fk',$data_promo->product_id_fk)->where('lang_id',1)->first();
    if($data_product){
      $p_name = $data_product->product_name;
    }
    $p_amt = $tmp->amt*$data_promo->product_amt;
      $id = DB::table($temp_product_fifo)->insertOrIgnore([
        'product_id_fk' =>$p_id,
        'amt' => $p_amt,
        'product_name' => $p_code.$p_name,
        'product_unit' => $p_unit,
      ]);
  }
}
}
  //   DB::select(" INSERT IGNORE INTO $temp_product_fifo
  // SELECT
  // promotions_products.product_id_fk,sum(promotions_products.product_amt) as amt,
  // CONCAT(
  // (SELECT product_code FROM products WHERE id=promotions_products.product_id_fk limit 1),':',
  // (SELECT product_name FROM products_details WHERE product_id_fk=promotions_products.product_id_fk and lang_id=1 limit 1)) as product_name,
  // dataset_product_unit.product_unit
  // FROM `promotions_products`
  // LEFT Join dataset_product_unit ON promotions_products.product_unit = dataset_product_unit.id
  // where promotion_id_fk in (
  // SELECT $temp_ppr_002.promotion_id_fk from $temp_ppr_002 WHERE
  // $temp_ppr_002.frontstore_id_fk=".$row->id." and $temp_ppr_002.type_product='promotion')
  // group by promotions_products.product_id_fk
  // ");

        $Products = DB::select("
               SELECT product_id_fk,sum(amt) as amt,product_name,product_unit from $temp_product_fifo GROUP BY product_id_fk ORDER BY product_name
           ");


          $pn = '<div class="divTable"><div class="divTableBody">';
          $pn .=
          '<div class="divTableRow">
          <div class="divTableCell" style="width:240px;font-weight:bold;">ชื่อสินค้า</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จ่ายครั้งนี้</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">ค้างจ่าย</div>
          <div class="divTableCell" style="width:450px;text-align:center;font-weight:bold;">

                      <div class="divTableRow">
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">หยิบสินค้าจากคลัง</div>
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">Lot number [Expired]</div>
                      <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">จำนวน</div>
                      </div>

          </div>
          </div>
          ';

          $amt_pay_remain = 0;
          $amt_lot = 0;
          $amt_to_take = 0;
          $pay_this = 0;

          DB::select(" DROP TABLE IF EXISTS temp_001; ");
          // TEMPORARY
          DB::select(" CREATE TEMPORARY TABLE temp_001 LIKE temp_db_stocks_amt_template_02 ");

          foreach ($Products as $key => $value) {

               $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
               $amt_pay_this = $value->amt;
              // วุฒิเพิ่มมาเช็คคลัง ว่าอย่าเอาคลังเก็บมา
               $w_arr2 = DB::table('warehouse')->where('w_code','WH02')->pluck('id')->toArray();
              //  dd($w_arr2);
               $w_str2 = '';
               foreach($w_arr2 as $key => $w){
                 if($key+1==count($w_arr2)){
                   $w_str2.=$w;
                 }else{
                   $w_str2.=$w.',';
                 }

               }

                // จำนวนที่จะ Hint ให้ไปหยิบจากแต่ละชั้นมา ตามจำนวนที่สั่งซื้อ โดยการเช็คไปทีละชั้น fifo จนกว่าจะพอ
                // เอาจำนวนที่เบิก เป็นเช็ค กับ สต๊อก ว่ามีพอหรือไม่ โดยเอาทุกชั้นที่มีมาคิดรวมกันก่อนว่าพอหรือไม่
                $temp_db_stocks_01 = DB::select(" SELECT sum(amt) as amt,count(*) as amt_floor from $temp_db_stocks WHERE amt>0 AND warehouse_id_fk in (".$w_str2.") AND product_id_fk=".$value->product_id_fk."  ");
                $amt_floor = $temp_db_stocks_01[0]->amt_floor;
// dd(" SELECT sum(amt) as amt,count(*) as amt_floor from $temp_db_stocks WHERE amt>0 AND warehouse_id_fk in (".$w_str2.") AND product_id_fk=".$value->product_id_fk."  ");
                // Case 1 > มีสินค้าพอ (รวมจากทุกชั้น) และ ในคลังมีมากกว่า ที่ต้องการซื้อ
                if($temp_db_stocks_01[0]->amt>0 && $temp_db_stocks_01[0]->amt>=$amt_pay_this ){

                  $pay_this = $value->amt ;
                  $amt_pay_remain = 0;

                    $pn .=
                    '<div class="divTableRow">
                    <div class="divTableCell" style="font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
                    <div class="divTableCell" style="text-align:center;font-weight:bold;">'. $pay_this .'</div>
                    <div class="divTableCell" style="text-align:center;"> '.$amt_pay_remain.' </div>
                    <div class="divTableCell" style="width:450px;text-align:center;"> ';

                    // Case 1.1 > ไล่หาแต่ละชั้น ตาม FIFO ชั้นที่จะหมดอายุก่อน เอาออกมาก่อน
                    $w_arr = DB::table('warehouse')->where('w_code','WH02')->pluck('id')->toArray();
                    $w_str = '';
                    foreach($w_arr as $key => $w){
                      if($key+1==count($w_arr)){
                        $w_str.=$w;
                      }else{
                        $w_str.=$w.',';
                      }

                    }
                    // wut อันนี้แก้ จ่ายผิดคลัง
                    $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." AND warehouse_id_fk in (".$w_str.") ORDER BY lot_expired_date ASC  ");
                    // $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." AND warehouse_id_fk=2 ORDER BY lot_expired_date ASC  ");
                    // อันนี้แก้เรื่องเรื่องโปรมั้ง หรือไม่ก็ไม่มีปุ่ม
                    // $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC  ");
                    // อันนี้ไรไม่รู้
                    //  $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC  ");
                    // dd($temp_db_stocks_02);
                          DB::select(" DROP TABLE IF EXISTS temp_001; ");
                          // TEMPORARY
                          DB::select(" CREATE TEMPORARY TABLE temp_001 LIKE temp_db_stocks_amt_template_02 ");

                          // dd($w_arr);
                     $i = 1;
                     foreach ($temp_db_stocks_02 as $v_02) {

                          $rs_ = DB::select(" SELECT amt_remain FROM temp_001 where id>0 order by id desc limit 1 ");
                          $amt_remain = @$rs_[0]->amt_remain?@$rs_[0]->amt_remain:0;
                          $pay_this = @$rs_[0]->amt_remain?@$rs_[0]->amt_remain:$value->amt ;

                          $zone = DB::select(" select * from zone where id=".$v_02->zone_id_fk." ");
                          $shelf = DB::select(" select * from shelf where id=".$v_02->shelf_id_fk." ");
                          $sWarehouse = @$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v_02->shelf_floor;

                          // Check ว่า ชั้นแรกๆ มีพอมั๊ย ถ้ามีพอแล้ว ก็หยุดค้น
                          // ถ้าจำนวนในคลังมีพอ เอาค่าจาก ที่ต้องการ
                            $amt_in_wh = @$v_02->amt?@$v_02->amt:0;
                            $amt_to_take = $pay_this;


                            if($amt_to_take>=$amt_in_wh){
                              DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_in_wh,$amt_to_take-$amt_in_wh) ");
                              $rs_ = DB::select(" SELECT amt_get FROM temp_001 order by id desc limit 1 ");
                              $amt_to_take = $rs_[0]->amt_get;
                            }else{
                              // $r_before =  DB::select(" select * from temp_001 where amt_remain<>0 order by id desc limit 1 ");
                              // $amt_remain = $r_before[0]->amt_remain;
                              DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_to_take,0) ");
                              $rs_ = DB::select(" SELECT amt_get FROM temp_001 order by id desc limit 1 ");
                              $amt_to_take = $rs_[0]->amt_get;
                            }


                                $pn .=
                                '<div class="divTableRow">
                                <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                </div>
                                ';

                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                    $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id;

                                     $_choose=DB::table("$temp_ppr_004")
                                      ->where('invoice_code', $row->invoice_code)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      ->where('lot_number', $v_02->lot_number)
                                      ->where('lot_expired_date', $v_02->lot_expired_date)
                                      ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                      ->where('zone_id_fk', $v_02->zone_id_fk)
                                      ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                      ->where('shelf_floor', $v_02->shelf_floor)
                                      ->get();

                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppr_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              orders_id_fk,
                                              customers_id_fk,
                                              invoice_code,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              lot_number,
                                              lot_expired_date,
                                              warehouse_id_fk,
                                              zone_id_fk,
                                              shelf_id_fk,
                                              shelf_floor,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$row->id."',
                                                '".$row->customers_id_fk."',
                                                '".$row->invoice_code."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt."',
                                                '".$amt_to_take."',
                                                '".$amt_to_take."',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->lot_number."',
                                                '".$v_02->lot_expired_date."',
                                                '".$v_02->warehouse_id_fk."',
                                                '".$v_02->zone_id_fk."',
                                                '".$v_02->shelf_id_fk."',
                                                '".$v_02->shelf_floor."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                                     $r_temp_001 = DB::select(" SELECT amt_remain FROM temp_001 order by id desc limit 1 ; ");
                                     // return $r_temp_001[0]->amt_remain;
                                     if($r_temp_001[0]->amt_remain==0) break;

                          $i++;

                     }


                // Case 2 > กรณีมีบ้างเป็นบางรายการ (รวมจากทุกชั้น) และ ในคลังมี น้อยกว่า ที่ต้องการซื้อ
                }else if($temp_db_stocks_01[0]->amt>0 && $temp_db_stocks_01[0]->amt<$amt_pay_this){ // กรณีมีบ้างเป็นบางรายการ

                     $pay_this = $temp_db_stocks_01[0]->amt ;
                     $amt_pay_remain = $value->amt - $temp_db_stocks_01[0]->amt ;
                     $css_red = $amt_pay_remain>0?'color:red;font-weight:bold;':'';

                    $pn .=
                    '<div class="divTableRow">
                    <div class="divTableCell" style="font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
                    <div class="divTableCell" style="text-align:center;font-weight:bold;">'. $pay_this .'</div>
                    <div class="divTableCell" style="text-align:center;'.$css_red.'">'. $amt_pay_remain .'</div>
                    <div class="divTableCell" style="width:450px;text-align:center;"> ';

                     // Case 2.1 > ไล่หาแต่ละชั้น ตาม FIFO ชั้นที่จะหมดอายุก่อน เอาออกมาก่อน


                     $w_arr = DB::table('warehouse')->where('w_code','WH02')->pluck('id')->toArray();
                     $w_str = '';
                     foreach($w_arr as $key => $w){
                       if($key+1==count($w_arr)){
                         $w_str.=$w;
                       }else{
                         $w_str.=$w.',';
                       }

                     }
                     // wut อันนี้แก้ จ่ายผิดคลัง
                     $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." AND warehouse_id_fk in (".$w_str.") ORDER BY lot_expired_date ASC  ");
                    //  temp_ppr_004
                    //  $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC  ");

                     $i = 1;
                     foreach ($temp_db_stocks_02 as $v_02) {
                          $zone = DB::select(" select * from zone where id=".$v_02->zone_id_fk." ");
                          $shelf = DB::select(" select * from shelf where id=".$v_02->shelf_id_fk." ");
                          $sWarehouse = @$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v_02->shelf_floor;

                          // Check ว่า ชั้นแรกๆ มีพอมั๊ย ถ้ามีพอแล้ว ก็หยุดค้น
                          // ถ้าจำนวนในคลังมีพอ เอาค่าจาก ที่ต้องการ
                          $amt_to_take = $pay_this;
                          $amt_in_wh = @$v_02->amt?@$v_02->amt:0;

                            // ในคลัง พิจารณารายชั้น ถ้าชั้นนั้นๆ น้อยกว่า ที่ต้องการ

                               if($i==1){
                                    DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_in_wh,".($amt_to_take-$amt_in_wh).") ");
                                    $rs_ = DB::select(" SELECT amt_get FROM temp_001 WHERE id='$i' ");
                                    $amt_to_take = $rs_[0]->amt_get;

                                   $pn .=
                                    '<div class="divTableRow">
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                    <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;color:blue;"> '.$pay_this.' </div>
                                    </div>
                                    ';

                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $_choose=DB::table("$temp_ppr_004")
                                      ->where('invoice_code', $row->invoice_code)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      ->where('lot_number', $v_02->lot_number)
                                      ->where('lot_expired_date', $v_02->lot_expired_date)
                                      ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                      ->where('zone_id_fk', $v_02->zone_id_fk)
                                      ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                      ->where('shelf_floor', $v_02->shelf_floor)
                                      ->get();

                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppr_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              orders_id_fk,
                                              customers_id_fk,
                                              invoice_code,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              lot_number,
                                              lot_expired_date,
                                              warehouse_id_fk,
                                              zone_id_fk,
                                              shelf_id_fk,
                                              shelf_floor,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$row->id."',
                                                '".$row->customers_id_fk."',
                                                '".$row->invoice_code."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt."',
                                                '".$pay_this."',
                                                '".$pay_this."',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->lot_number."',
                                                '".$v_02->lot_expired_date."',
                                                '".$v_02->warehouse_id_fk."',
                                                '".$v_02->zone_id_fk."',
                                                '".$v_02->shelf_id_fk."',
                                                '".$v_02->shelf_floor."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                      $r_temp_001 = DB::select(" SELECT amt_remain FROM temp_001 order by id desc limit 1 ; ");
                                     // return $r_temp_001[0]->amt_remain;
                                     if($r_temp_001[0]->amt_remain==0) break;

                                }else{


                                    $r_before =  DB::select(" select * from temp_001 where amt_remain<>0 order by id desc limit 1 ");
                                    $amt_remain = @$r_before[0]->amt_remain?@$r_before[0]->amt_remain:0;

                                    DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_remain,".($r_before[0]->amt_remain-$amt_in_wh).") ");
                                    $rs_ = DB::select(" SELECT * FROM temp_001 WHERE id='$i' ");
                                    $amt_to_take = $amt_in_wh;

                                    $pn .=
                                    '<div class="divTableRow">
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                    <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                    </div>
                                    ';
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $_choose=DB::table("$temp_ppr_004")
                                      ->where('invoice_code', $row->invoice_code)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      ->where('lot_number', $v_02->lot_number)
                                      ->where('lot_expired_date', $v_02->lot_expired_date)
                                      ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                      ->where('zone_id_fk', $v_02->zone_id_fk)
                                      ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                      ->where('shelf_floor', $v_02->shelf_floor)
                                      ->get();

                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppr_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              orders_id_fk,
                                              customers_id_fk,
                                              invoice_code,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              lot_number,
                                              lot_expired_date,
                                              warehouse_id_fk,
                                              zone_id_fk,
                                              shelf_id_fk,
                                              shelf_floor,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$row->id."',
                                                '".$row->customers_id_fk."',
                                                '".$row->invoice_code."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt."',
                                                '".$amt_to_take."',
                                                '".$amt_to_take."',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->lot_number."',
                                                '".$v_02->lot_expired_date."',
                                                '".$v_02->warehouse_id_fk."',
                                                '".$v_02->zone_id_fk."',
                                                '".$v_02->shelf_id_fk."',
                                                '".$v_02->shelf_floor."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                                 $r_temp_001 = DB::select(" SELECT amt_remain FROM temp_001 order by id desc limit 1 ; ");
                                     // return $r_temp_001[0]->amt_remain;
                                     if($r_temp_001[0]->amt_remain==0) break;
                                }



                          $i++;

                     }



                }else{ // กรณีไม่มีสินค้าในคลังเลย

                     $amt_pay_remain = $value->amt ;
                     $pay_this = 0 ;
                     $css_red = $amt_pay_remain>0?'color:red;font-weight:bold;':'';

                    $pn .=
                    '<div class="divTableRow">
                    <div class="divTableCell" style="font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
                    <div class="divTableCell" style="text-align:center;font-weight:bold;">'. $pay_this .'</div>
                    <div class="divTableCell" style="text-align:center;'.$css_red.'">'. $amt_pay_remain .'</div>
                    <div class="divTableCell" style="width:450px;text-align:center;"> ';

                      $pn .=
                            '<div class="divTableRow">
                            <div class="divTableCell" style="width:200px;text-align:center;color:red;"> *** ไม่มีสินค้าในคลัง. *** </div>
                            <div class="divTableCell" style="width:200px;text-align:center;">  </div>
                            <div class="divTableCell" style="width:100px;text-align:center;">  </div>
                            </div>
                            ';

                      @$_SESSION['check_product_instock'] = 0;

                              // $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt=0 and product_id_fk=".$value->product_id_fk." ");
                              $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE product_id_fk=".$value->product_id_fk." ");

                                    // // วุฒิเพิ่มมาสำหรับตรวจโปรโมชั่น
                                    //   $temp_ppp_002 = "temp_ppp_002".\Auth::user()->id;
                                    // if(count($temp_db_stocks_02)==0){
                                    //   $temp_db_stocks_02 = DB::table($temp_ppp_002)
                                    //   ->select(
                                    //     $temp_ppp_002.'.created_at',
                                    //     'promotions_products.product_unit as product_unit_id_fk',
                                    //     'promotions_products.product_id_fk as product_id_fk',
                                    //     DB::raw('CONCAT(products.product_code," : ", products_details.product_name) AS product_name'),
                                    //     'promotions_products.product_amt as amt',
                                    //   )
                                    //   ->join('promotions_products','promotions_products.promotion_id_fk',$temp_ppp_002.'.promotion_id_fk')
                                    //   ->join('products_details','products_details.product_id_fk','promotions_products.product_id_fk')
                                    //   ->join('products','products.id','promotions_products.product_id_fk')
                                    //   ->where($temp_ppp_002.'.type_product','promotion')
                                    //   ->where('promotions_products.product_id_fk',$value->product_id_fk)
                                    //   ->groupBy('promotions_products.product_id_fk')
                                    //   ->get();
                                    // }

                     $i = 1;

                  //  dd($temp_db_stocks_01[0]->amt);
                  //  return 'false';

                     foreach ($temp_db_stocks_02 as $v_02) {

                             // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $_choose=DB::table("$temp_ppr_004")
                                      ->where('invoice_code', $row->invoice_code)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      // ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('branch_id_fk', @\Auth::user()->branch_id_fk)
                                      ->get();

                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppr_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              orders_id_fk,
                                              customers_id_fk,
                                              invoice_code,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$row->id."',
                                                '".$row->customers_id_fk."',
                                                '".$row->invoice_code."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt."',
                                                '0',
                                                '0',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                        }

                }

            $pn .= '</div>';
            $pn .= '</div>';
          }

          $pn .= '</div>';

            $rs_temp_ppr_004 = DB::select(" select product_id_fk,lot_number,lot_expired_date,sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, amt_remain  from $temp_ppr_004 group by invoice_code,product_id_fk,lot_number  ");

          foreach ($rs_temp_ppr_004 as $key => $v) {

              $_choose=DB::table("$temp_db_stocks_check")
                ->where('invoice_code', $row->invoice_code)
                ->where('product_id_fk', $v->product_id_fk)
                ->where('lot_number', $v->lot_number)
                // ->where('lot_expired_date', $v->lot_expired_date)
                // ->where('amt_get', $v->amt_get)
                // ->where('amt_lot', $v->amt_lot)
                // ->where('amt_remain', $v->amt_remain)
                ->get();

                if($_choose->count() == 0){
                  DB::select(" INSERT IGNORE INTO $temp_db_stocks_check (invoice_code,product_id_fk,lot_number,lot_expired_date,amt_get,amt_lot,amt_remain)
                    VALUES (
                    '".$row->invoice_code."', ".$v->product_id_fk.", '".$v->lot_number."', '".$v->lot_expired_date."', ".$v->amt_lot.", '".$v->amt_lot."', '".$v->amt_remain."'
                    )
                  ");
                }

          }


          $rs_temp_db_stocks_check = DB::select(" select invoice_code, sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, sum(amt_remain) as amt_remain from $temp_db_stocks_check group by invoice_code ");

          foreach ($rs_temp_db_stocks_check as $key => $v) {

              $_choose=DB::table("$temp_db_stocks_compare")
                // ->where('invoice_code', $row->invoice_code)
                // ->where('amt_get', $v->amt_get)
                // ->where('amt_lot', $v->amt_lot)
                // ->where('amt_remain', $v->amt_remain)
                ->get();
                // if($_choose->count() == 0){
                  DB::select(" INSERT IGNORE INTO $temp_db_stocks_compare (`invoice_code`, `amt_get`, `amt_lot`, `amt_remain`)
                    VALUES (
                    '".$row->invoice_code."', ".$v->amt_get.", '".$v->amt_lot."', '".$v->amt_remain."'
                    )
                  ");
                // }

          }

          return $pn;
        }else{
          return '<font color=red>*** อยู่ระหว่างปรับปรุง</font>';
        }
      })
      ->escapeColumns('column_002')
      ->addColumn('column_003', function($row) {

          $pn = '<div class="divTable"><div class="divTableBody">';
          $pn .=
          '<div class="divTableRow">
          <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">หยิบสินค้าจากคลัง</div>
          <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">Lot number [Expired]</div>
          <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">จำนวน</div>
          </div>
          ';
          $pn .= '</div>';
          return $pn;

       })
      ->escapeColumns('column_003')
      ->addColumn('ch_amt_lot_wh', function($row) {
// ดูว่าไม่มีสินค้าคลังเลย

          $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id;

          $Products = DB::select("
            SELECT * from $temp_ppr_004 WHERE invoice_code='".$row->invoice_code."'
          ");

          if(count($Products)>0){
              $arr = [];
              foreach ($Products as $key => $value) {
                array_push($arr,$value->product_id_fk);
              }
              $arr_im = implode(',',$arr);

              $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
              $r = DB::select(" SELECT sum(amt) as sum FROM $temp_db_stocks WHERE product_id_fk in ($arr_im) ");
              return @$r[0]->sum?@$r[0]->sum:0;
          }else{
              return 0;
          }

       })

      ->addColumn('check_product_instock', function($row) {
      // ดูว่าไม่มีสินค้าในคลังบางรายการ
          if(@$_SESSION['check_product_instock']=="0"){
            return "N";
          }else{
            return "Y";
          }

       })

      ->make(true);
    }




    public function ajaxSearch_bill_db_orders(Request $request)
    {

      if(empty($request->txtSearch)){
        return 0;
      }

      $business_location_id_fk = \Auth::user()->business_location_id_fk;
      $branch_id_fk = \Auth::user()->branch_id_fk;
      $temp_ppr_001 = "temp_ppr_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_ppr_002 = "temp_ppr_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
      $temp_ppr_003 = "temp_ppr_003".\Auth::user()->id; // เก็บสถานะการส่ง และ ที่อยู่ในการจัดส่ง
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;
      $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id; // เก็บรายการที่หยิบสินค้ามาจากชั้นต่างๆ ตอน FIFO
      $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
      $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;


      $TABLES = DB::select(" SHOW TABLES ");
      $array_TABLES = [];
      foreach($TABLES as $t){
        array_push($array_TABLES, $t->Tables_in_aiyaraco_v3);
      }

      DB::select(" DROP TABLE IF EXISTS $temp_db_stocks_check ");
      DB::select(" CREATE TABLE $temp_db_stocks_check LIKE temp_db_stocks_check_template ");

    // หาในตาราง db_orders ว่ามีมั๊ย
    // คิวรีจาก db_orders ที่ branch_id_fk = sentto_branch_id & delivery_location = 0
    // กรณีที่ เป็น invoice_code (เพราะมี 2 กรณี คือ invoice_code กับ QR_CODE)
    $invoice_code = $request->txtSearch;
    if(strpos($invoice_code,"QR") !== false){
        $order_qr = DB::table('db_orders')->select('invoice_code')->where('qr_code',$invoice_code)->where('qr_endate','>',date('Y-m-d'))->first();
        if($order_qr){
          $invoice_code = $order_qr->invoice_code;
          $request->txtSearch = $order_qr->invoice_code;
        }
  }
    // $order_check = QR111111 O123111000002

    if(@\Auth::user()->permission==1){
         $r01 = DB::select(" SELECT invoice_code FROM db_orders where invoice_code='$invoice_code' AND branch_id_fk = sentto_branch_id & delivery_location = 0  AND approve_status NOT IN (1,3,5,6) ");

    }else{
      // วุฒิปลดให้ค้นเจอทั้งหมด
        // $r01 = DB::select(" SELECT invoice_code FROM db_orders where invoice_code='$invoice_code' AND branch_id_fk = sentto_branch_id & delivery_location = 0  AND branch_id_fk=".@\Auth::user()->branch_id_fk." ");
        $r01 = DB::select(" SELECT invoice_code FROM db_orders where invoice_code='$invoice_code' AND branch_id_fk = sentto_branch_id & delivery_location = 0  AND approve_status NOT IN (1,3,5,6) ");
    }

    // return $r01;
    // return $r01[0]->invoice_code;

    if($r01){

        DB::select(" DROP TABLE IF EXISTS $temp_ppr_001; ");
        DB::select(" CREATE TABLE $temp_ppr_001 LIKE db_orders ");
        DB::select(" INSERT $temp_ppr_001 SELECT * FROM db_orders WHERE code_order='$invoice_code' AND invoice_code!=''");
        // $test =   DB::select(" SELECT * FROM db_orders WHERE code_order='$invoice_code' ");
        // return $test;

        DB::select(" DROP TABLE IF EXISTS $temp_ppr_002; ");
        DB::select(" CREATE TABLE $temp_ppr_002 LIKE db_order_products_list ");
        DB::select(" INSERT $temp_ppr_002
        SELECT db_order_products_list.* FROM db_order_products_list INNER Join $temp_ppr_001 ON db_order_products_list.frontstore_id_fk = $temp_ppr_001.id ");

        // $Data = DB::select(" SELECT * FROM $temp_ppr_002; ");
        // return $Data;
        // warehouse_id_fk
// เอาไปแสดงชั่วคราวไว้ในตารางที่ค้นก่อน หน้า จ่ายสินค้าตามใบเสร็จ /backend/pay_product_receipt
// ตารางส่วนบน
// ดึงจาก $temp_ppr_001 & $temp_ppr_002 ลง temp ก่อน แค่แสดง แต่หลังจากกดปุ่ม save แล้ว ค่อยเก็บลงตารางจริง

       // ต้องมีอีกตารางนึง เก็บ สถานะการอนุมัติ และ ที่อยู่การจัดส่งสินค้า > $temp_ppr_003
          DB::select(" DROP TABLE IF EXISTS $temp_ppr_003; ");
          DB::select(" CREATE TABLE $temp_ppr_003 LIKE temp_ppr_003_template ");
          DB::select("
            INSERT IGNORE INTO $temp_ppr_003 (orders_id_fk, business_location_id_fk, branch_id_fk, branch_id_fk_tosent, invoice_code, bill_date, action_user,  customer_id_fk,  address_send_type, created_at)
            SELECT id,business_location_id_fk,branch_id_fk,sentto_branch_id,invoice_code,action_date,action_user , customers_id_fk ,'1', now() FROM db_orders where invoice_code='".$request->txtSearch."' ;
          ");

        // $Data = DB::select(" SELECT * FROM $temp_ppr_003; ");
        // $Data = DB::select(" SELECT * FROM $temp_ppr_002; ");
        // return $Data;
// FIFO
        // Qry > product_id_fk from $temp_ppr_002

           // สินค้าปกติ + สินค้าโปรโมชั่น
            $product = DB::select("
                select product_id_fk from $temp_ppr_002 where $temp_ppr_002.type_product='product'
                UNION
                SELECT product_id_fk FROM `promotions_products` where promotion_id_fk in (
                SELECT $temp_ppr_002.promotion_id_fk from $temp_ppr_002
                where $temp_ppr_002.type_product='promotion' )
             ");
            // return $product;

            $product_id_fk = [0];
            foreach ($product as $key => $value) {
               array_push($product_id_fk,$value->product_id_fk);
            }
            // dd('$business_location_id_fk : '.$business_location_id_fk.' $branch_id_fk : '.$branch_id_fk.' $arr_product_id_fk'.' : ');
            // return $product_id_fk;
            $product_id_fk = array_filter($product_id_fk);
            $arr_product_id_fk = implode(',',$product_id_fk);
            // return $arr_product_id_fk;

            if(in_array($temp_db_stocks,$array_TABLES)){
              // return "IN";
              DB::select(" TRUNCATE TABLE $temp_db_stocks  ");
            }else{
              // return "Not";
              DB::select(" CREATE TABLE $temp_db_stocks LIKE db_stocks ");
            }

          if(!empty($arr_product_id_fk)){
            DB::select(" INSERT IGNORE INTO $temp_db_stocks SELECT * FROM db_stocks
             WHERE db_stocks.business_location_id_fk='$business_location_id_fk' AND db_stocks.branch_id_fk='$branch_id_fk'
             AND db_stocks.lot_expired_date>=now()
            --  AND db_stocks.warehouse_id_fk in (SELECT id FROM warehouse WHERE warehouse.branch_id_fk=db_stocks.branch_id_fk )
             AND db_stocks.product_id_fk in ($arr_product_id_fk) ORDER BY db_stocks.lot_number ASC, db_stocks.lot_expired_date ASC ");
        }

        foreach($product_id_fk as $p){
          $data_p = DB::table($temp_db_stocks)->select('id')->where('product_id_fk',$p)->first();
          if(!$data_p){
            $warehouse_arr = DB::table('warehouse')->select('id')->where('w_code','WH02')->where('status',1)->pluck('id')->toArray();
            $data_p_clone = DB::table($temp_db_stocks)->whereIn('warehouse_id_fk',$warehouse_arr)->first();
            if($data_p_clone){
              $insert = DB::table('db_stocks')->insert([
                'business_location_id_fk' => $data_p_clone->business_location_id_fk,
                'branch_id_fk' => $data_p_clone->branch_id_fk,
                'product_id_fk' => $p,
                'lot_number' => '00',
                'lot_expired_date' => $data_p_clone->lot_expired_date,
                'amt' => 0,
                'product_unit_id_fk' => $data_p_clone->product_unit_id_fk,
                'date_in_stock' => date('Y-m-d'),
                'warehouse_id_fk' => $data_p_clone->warehouse_id_fk,
                'zone_id_fk' => $data_p_clone->zone_id_fk,
                'shelf_id_fk' => $data_p_clone->shelf_id_fk,
                'shelf_floor' => $data_p_clone->shelf_floor,
                'created_at' => date('Y-m-d H:i:s'),
              ]);
            }
          }
        }

        if(in_array($temp_db_stocks,$array_TABLES)){
          // return "IN";
          DB::select(" TRUNCATE TABLE $temp_db_stocks  ");
        }else{
          // return "Not";
          DB::select(" CREATE TABLE $temp_db_stocks LIKE db_stocks ");
        }

      if(!empty($arr_product_id_fk)){
        DB::select(" INSERT IGNORE INTO $temp_db_stocks SELECT * FROM db_stocks
         WHERE db_stocks.business_location_id_fk='$business_location_id_fk' AND db_stocks.branch_id_fk='$branch_id_fk'  AND db_stocks.lot_expired_date>=now()  AND db_stocks.product_id_fk in ($arr_product_id_fk) ORDER BY db_stocks.lot_number ASC, db_stocks.lot_expired_date ASC ");
// AND db_stocks.warehouse_id_fk in (SELECT id FROM warehouse WHERE warehouse.branch_id_fk=db_stocks.branch_id_fk )
  }

    // dd(DB::table($temp_db_stocks)->get());
         // $Data = DB::select(" SELECT * FROM $temp_db_stocks; ");
         // return $Data;

          if(in_array($temp_db_stocks_compare,$array_TABLES)){
            // return "IN";
          }else{
            // return "Not";
            DB::select(" CREATE TABLE $temp_db_stocks_compare LIKE temp_db_stocks_compare_template ");
          }

          // $lastInsertId = DB::getPdo()->lastInsertId();

            return 1;
// Close > if($r01)
            // กรณีหาแล้วไม่เจอ
          }else{
            return 0;
          }


    }// Close > function


    public function ajaxSearch_bill_db_orders002(Request $request)
    {
      // temp_ppr_004
            $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;

            $r_compare_1 =  DB::select("
              SELECT amt_get+amt_lot+amt_remain as sum from $temp_db_stocks_compare ORDER BY id DESC LIMIT 1,1;
            ");

            $x = @$r_compare_1[0]->sum?@$r_compare_1[0]->sum:0;

            $r_compare_2 =  DB::select("
              SELECT amt_get+amt_lot+amt_remain as sum from $temp_db_stocks_compare ORDER BY id DESC LIMIT 0,1;
            ");
            $y = @$r_compare_2[0]->sum?@$r_compare_2[0]->sum:0;

            if($x == $y){
                  return "No_changed";
            }else{
                  return "changed";
            }


    }// Close > function


// /backend/pay_product_receipt/1/edit (กรณีที่ยังไม่มีการยกเลิกการจ่าย status_cancel==0)
// %%%%%%%%%%%%%%%%%%%%%%%
    public function Datatable009FIFO(Request $req){

      $temp_ppr_001 = "temp_ppr_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
      $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;

      $TABLES = DB::select(" SHOW TABLES ");
      // return $TABLES;
      $array_TABLES = [];
      foreach($TABLES as $t){
        // print_r($t->Tables_in_aiyaraco_v3);
        array_push($array_TABLES, $t->Tables_in_aiyaraco_v3);
      }
      // เก็บรายการสินค้าที่จ่าย ตอน FIFO

      if(in_array($temp_ppr_004,$array_TABLES)){
        // return "IN";
        DB::select(" TRUNCATE TABLE $temp_ppr_004  ");
      }else{
        // return "Not"; time_pay
        DB::select(" CREATE TABLE $temp_ppr_004 LIKE temp_ppr_004_template ");
      }


      $invoice_code = $req->invoice_code;

      $sTable = DB::select("
        SELECT * from $temp_ppr_001 WHERE invoice_code='$invoice_code'
        ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('column_001', function($row) {
            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="width:50px;font-weight:bold;">ครั้งที่</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">วันที่จ่าย</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;"> พนักงาน </div>
            </div>
            ';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            </div>
            ';
            return $pn;
        })
       ->escapeColumns('column_001')
      ->addColumn('column_002', function($row) {

          $temp_ppr_001 = "temp_ppr_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
          $temp_ppr_002 = "temp_ppr_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
          $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id; // เก็บรายการที่หยิบสินค้ามาจากชั้นต่างๆ ตอน FIFO
          $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
          $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
          $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;

          // วุฒิแก้เพราะมันคิวรี่ไม่เจอ
          $max_time_pay =  DB::table('db_pay_product_receipt_002')->select('time_pay')->where('invoice_code',$row->invoice_code)->where('status_cancel',0)->orderby('time_pay','desc')->first();
          if($max_time_pay){
            $Products = DB::table('db_pay_product_receipt_002')->where('invoice_code',$row->invoice_code)->where('status_cancel',0)->where('time_pay',$max_time_pay->time_pay)->groupBy('product_id_fk')->orderby('time_pay','desc')->get();
          }else{
            $Products = DB::table('db_pay_product_receipt_002')->where('invoice_code',$row->invoice_code)->where('status_cancel',0)->groupBy('product_id_fk')->orderby('time_pay','desc')->get();
          }

          // $Products = DB::select("
          //   SELECT db_pay_product_receipt_002.* from db_pay_product_receipt_002 WHERE invoice_code='".$row->invoice_code."' and amt_remain <> 0 AND time_pay=(SELECT max(time_pay) from db_pay_product_receipt_002 WHERE status_cancel=0 LIMIT 1) and status_cancel=0  GROUP BY product_id_fk ORDER BY time_pay
          // ");

          $pn = '<div class="divTable"><div class="divTableBody">';
          $pn .=
          '<div class="divTableRow">
          <div class="divTableCell" style="width:240px;font-weight:bold;">ชื่อสินค้า</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จ่ายครั้งนี้</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">ค้างจ่าย</div>
          <div class="divTableCell" style="width:450px;text-align:center;font-weight:bold;">

                      <div class="divTableRow">
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">หยิบสินค้าจากคลัง</div>
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">Lot number [Expired]</div>
                      <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
                      </div>

          </div>
          </div>
          ';

          $amt_pay_remain = 0;
          $amt_lot = 0;
          $amt_to_take = 0;
          $pay_this = 0;

         DB::select(" DROP TABLE IF EXISTS temp_001; ");
         // TEMPORARY
         DB::select(" CREATE TEMPORARY TABLE temp_001 LIKE temp_db_stocks_amt_template_02 ");

          foreach ($Products as $key => $value) {

               $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
               $amt_pay_this = $value->amt_remain;

     // วุฒิเพิ่มมาเช็คคลัง ว่าอย่าเอาคลังเก็บมา
     $w_arr2 = DB::table('warehouse')->where('w_code','WH02')->pluck('id')->toArray();
     $w_str2 = '';
     foreach($w_arr2 as $key => $w){
       if($key+1==count($w_arr2)){
         $w_str2.=$w;
       }else{
         $w_str2.=$w.',';
       }

     }


                // จำนวนที่จะ Hint ให้ไปหยิบจากแต่ละชั้นมา ตามจำนวนที่สั่งซื้อ โดยการเช็คไปทีละชั้น fifo จนกว่าจะพอ
                // เอาจำนวนที่เบิก เป็นเช็ค กับ สต๊อก ว่ามีพอหรือไม่ โดยเอาทุกชั้นที่มีมาคิดรวมกันก่อนว่าพอหรือไม่

               if(!empty($value->product_id_fk)){
                // $temp_db_stocks_01 = DB::select(" SELECT sum(amt) as amt,count(*) as amt_floor from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk."  ");
                $temp_db_stocks_01 = DB::select(" SELECT sum(amt) as amt,count(*) as amt_floor from $temp_db_stocks WHERE amt>0 AND warehouse_id_fk in (".$w_str2.") AND product_id_fk=".$value->product_id_fk."  ");
                $amt_floor = $temp_db_stocks_01[0]->amt_floor;

               }


                // Case 1 > มีสินค้าพอ (รวมจากทุกชั้น) และ ในคลังมีมากกว่า ที่ต้องการซื้อ
                if($temp_db_stocks_01[0]->amt>0 && $temp_db_stocks_01[0]->amt>=$amt_pay_this ){

                  $pay_this = $value->amt_remain ;
                  $amt_pay_remain = 0;

                    $pn .=
                    '<div class="divTableRow">
                    <div class="divTableCell" style="width:240px;font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
                    <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">'. $pay_this .'</div>
                    <div class="divTableCell" style="width:80px;text-align:center;">'. $amt_pay_remain .'</div>
                    <div class="divTableCell" style="width:450px;text-align:center;"> ';

                    // Case 1.1 > ไล่หาแต่ละชั้น ตาม FIFO ชั้นที่จะหมดอายุก่อน เอาออกมาก่อน

                    $w_arr = DB::table('warehouse')->where('w_code','WH02')->pluck('id')->toArray();
                    $w_str = '';
                    foreach($w_arr as $key => $w){
                      if($key+1==count($w_arr)){
                        $w_str.=$w;
                      }else{
                        $w_str.=$w.',';
                      }

                    }
        // wut อันนี้แก้ จ่ายผิดคลัง
                    $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." AND warehouse_id_fk in (".$w_str.") ORDER BY lot_expired_date ASC  ");
                    //  $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC  ");

                      DB::select(" DROP TABLE IF EXISTS temp_001; ");
                      // TEMPORARY
                      DB::select(" CREATE TEMPORARY TABLE temp_001 LIKE temp_db_stocks_amt_template_02 ");


                     $i = 1;
                     foreach ($temp_db_stocks_02 as $v_02) {

                          $rs_ = DB::select(" SELECT amt_remain FROM temp_001 where id>0 order by id desc limit 1 ");
                          $amt_remain = @$rs_[0]->amt_remain?@$rs_[0]->amt_remain:0;
                          $pay_this = @$rs_[0]->amt_remain?@$rs_[0]->amt_remain:$value->amt_remain ;

                          $zone = DB::select(" select * from zone where id=".$v_02->zone_id_fk." ");
                          $shelf = DB::select(" select * from shelf where id=".$v_02->shelf_id_fk." ");
                          $sWarehouse = @$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v_02->shelf_floor;

                          // Check ว่า ชั้นแรกๆ มีพอมั๊ย ถ้ามีพอแล้ว ก็หยุดค้น
                          // ถ้าจำนวนในคลังมีพอ เอาค่าจาก ที่ต้องการ
                            $amt_in_wh = @$v_02->amt?@$v_02->amt:0;
                            $amt_to_take = $pay_this;


                            if($amt_to_take>=$amt_in_wh){
                              DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_in_wh,$amt_to_take-$amt_in_wh) ");
                              $rs_ = DB::select(" SELECT amt_get FROM temp_001 order by id desc limit 1 ");
                              $amt_to_take = $rs_[0]->amt_get;
                            }else{
                              // $r_before =  DB::select(" select * from temp_001 where amt_remain<>0 order by id desc limit 1 ");
                              // $amt_remain = $r_before[0]->amt_remain;
                                     DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_to_take,0) ");
                                      $rs_ = DB::select(" SELECT amt_get FROM temp_001 order by id desc limit 1 ");
                                      $amt_to_take = $rs_[0]->amt_get;
                            }


                                $pn .=
                                '<div class="divTableRow">
                                <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                </div>
                                ';

                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                    $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id;

                                     $_choose=DB::table("$temp_ppr_004")
                                      ->where('invoice_code', $row->invoice_code)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      ->where('lot_number', $v_02->lot_number)
                                      ->where('lot_expired_date', $v_02->lot_expired_date)
                                      ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                      ->where('zone_id_fk', $v_02->zone_id_fk)
                                      ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                      ->where('shelf_floor', $v_02->shelf_floor)
                                      ->get();
                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppr_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              orders_id_fk,
                                              customers_id_fk,
                                              invoice_code,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              lot_number,
                                              lot_expired_date,
                                              warehouse_id_fk,
                                              zone_id_fk,
                                              shelf_id_fk,
                                              shelf_floor,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$value->orders_id_fk."',
                                                '".$value->customers_id_fk."',
                                                '".$row->invoice_code."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt_need."',
                                                '".$amt_to_take."',
                                                '".$amt_to_take."',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->lot_number."',
                                                '".$v_02->lot_expired_date."',
                                                '".$v_02->warehouse_id_fk."',
                                                '".$v_02->zone_id_fk."',
                                                '".$v_02->shelf_id_fk."',
                                                '".$v_02->shelf_floor."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                                     $r_temp_001 = DB::select(" SELECT amt_remain FROM temp_001 order by id desc limit 1 ; ");
                                     // return $r_temp_001[0]->amt_remain;
                                     if($r_temp_001[0]->amt_remain==0) break;

                          $i++;

                     }


                // Case 2 > กรณีมีบ้างเป็นบางรายการ (รวมจากทุกชั้น) และ ในคลังมี น้อยกว่า ที่ต้องการซื้อ
                }else if($temp_db_stocks_01[0]->amt>0 && $temp_db_stocks_01[0]->amt<$amt_pay_this){ // กรณีมีบ้างเป็นบางรายการ

                     $pay_this = $temp_db_stocks_01[0]->amt ;
                     $amt_pay_remain = $value->amt_remain - $temp_db_stocks_01[0]->amt ;
                     $css_red = $amt_pay_remain>0?'color:red;font-weight:bold;':'';

                    $pn .=
                    '<div class="divTableRow">
                    <div class="divTableCell" style="width:240px;font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
                    <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">'. $pay_this .'</div>
                    <div class="divTableCell" style="width:80px;text-align:center;'.$css_red.'">'. $amt_pay_remain .'</div>
                    <div class="divTableCell" style="width:450px;text-align:center;"> ';

                     // Case 2.1 > ไล่หาแต่ละชั้น ตาม FIFO ชั้นที่จะหมดอายุก่อน เอาออกมาก่อน
                     $w_arr = DB::table('warehouse')->where('w_code','WH02')->pluck('id')->toArray();
                     $w_str = '';
                     foreach($w_arr as $key => $w){
                       if($key+1==count($w_arr)){
                         $w_str.=$w;
                       }else{
                         $w_str.=$w.',';
                       }

                     }
         // wut อันนี้แก้ จ่ายผิดคลัง
                     $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." AND warehouse_id_fk in (".$w_str.") ORDER BY lot_expired_date ASC  ");
                    //  $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC  ");

                     $i = 1;
                     foreach ($temp_db_stocks_02 as $v_02) {
                          $zone = DB::select(" select * from zone where id=".$v_02->zone_id_fk." ");
                          $shelf = DB::select(" select * from shelf where id=".$v_02->shelf_id_fk." ");
                          $sWarehouse = @$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v_02->shelf_floor;

                          // Check ว่า ชั้นแรกๆ มีพอมั๊ย ถ้ามีพอแล้ว ก็หยุดค้น
                          // ถ้าจำนวนในคลังมีพอ เอาค่าจาก ที่ต้องการ
                          $amt_to_take = $pay_this;
                          $amt_in_wh = @$v_02->amt?@$v_02->amt:0;


                            // ในคลัง พิจารณารายชั้น ถ้าชั้นนั้นๆ น้อยกว่า ที่ต้องการ

                               if($i==1){
                                    DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_in_wh,".($amt_to_take-$amt_in_wh).") ");
                                    $rs_ = DB::select(" SELECT amt_get FROM temp_001 WHERE id='$i' ");
                                    $amt_to_take = $rs_[0]->amt_get;

                                   $pn .=
                                    '<div class="divTableRow">
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                    <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                    </div>
                                    ';

                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $_choose=DB::table("$temp_ppr_004")
                                      ->where('invoice_code', $row->invoice_code)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      ->where('lot_number', $v_02->lot_number)
                                      ->where('lot_expired_date', $v_02->lot_expired_date)
                                      ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                      ->where('zone_id_fk', $v_02->zone_id_fk)
                                      ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                      ->where('shelf_floor', $v_02->shelf_floor)
                                      ->get();
                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppr_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              orders_id_fk,
                                              customers_id_fk,
                                              invoice_code,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              lot_number,
                                              lot_expired_date,
                                              warehouse_id_fk,
                                              zone_id_fk,
                                              shelf_id_fk,
                                              shelf_floor,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$value->orders_id_fk."',
                                                '".$value->customers_id_fk."',
                                                '".$row->invoice_code."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt_need."',
                                                '".$amt_to_take."',
                                                '".$amt_to_take."',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->lot_number."',
                                                '".$v_02->lot_expired_date."',
                                                '".$v_02->warehouse_id_fk."',
                                                '".$v_02->zone_id_fk."',
                                                '".$v_02->shelf_id_fk."',
                                                '".$v_02->shelf_floor."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒


                                }else{

                                    $r_before =  DB::select(" select * from temp_001 where amt_remain<>0 order by id desc limit 1 ");
                                    $amt_remain = @$r_before[0]->amt_remain?@$r_before[0]->amt_remain:0;

                                    DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_remain,".($r_before[0]->amt_remain-$amt_in_wh).") ");
                                    $rs_ = DB::select(" SELECT * FROM temp_001 WHERE id='$i' ");
                                    $amt_to_take = $amt_in_wh;

                                    $pn .=
                                    '<div class="divTableRow">
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                    <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                    <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                    </div>
                                    ';
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $_choose=DB::table("$temp_ppr_004")
                                      ->where('invoice_code', $row->invoice_code)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      ->where('lot_number', $v_02->lot_number)
                                      ->where('lot_expired_date', $v_02->lot_expired_date)
                                      ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                      ->where('zone_id_fk', $v_02->zone_id_fk)
                                      ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                      ->where('shelf_floor', $v_02->shelf_floor)
                                      ->get();
                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppr_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              orders_id_fk,
                                              customers_id_fk,
                                              invoice_code,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              lot_number,
                                              lot_expired_date,
                                              warehouse_id_fk,
                                              zone_id_fk,
                                              shelf_id_fk,
                                              shelf_floor,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$value->orders_id_fk."',
                                                '".$value->customers_id_fk."',
                                                '".$row->invoice_code."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt_need."',
                                                '".$amt_to_take."',
                                                '".$amt_to_take."',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->lot_number."',
                                                '".$v_02->lot_expired_date."',
                                                '".$v_02->warehouse_id_fk."',
                                                '".$v_02->zone_id_fk."',
                                                '".$v_02->shelf_id_fk."',
                                                '".$v_02->shelf_floor."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒


                                }


                          $i++;

                     }



                }else{ // กรณีไม่มีสินค้าในคลังเลย

                     $amt_pay_remain = $value->amt_remain ;
                     $pay_this = 0 ;
                     $css_red = $amt_pay_remain>0?'color:red;font-weight:bold;':'';

                    $pn .=
                    '<div class="divTableRow">
                    <div class="divTableCell" style="width:240px;font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
                    <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">'. $pay_this .'</div>
                    <div class="divTableCell" style="width:80px;text-align:center;'.$css_red.'">'. $amt_pay_remain .' </div>
                    <div class="divTableCell" style="width:450px;text-align:center;"> ';

                      $pn .=
                            '<div class="divTableRow">
                            <div class="divTableCell" style="width:200px;text-align:center;color:red;"> *** ไม่มีสินค้าในคลัง.. *** </div>
                            <div class="divTableCell" style="width:200px;text-align:center;">  </div>
                            <div class="divTableCell" style="width:80px;text-align:center;">  </div>
                            </div>
                            ';

                      @$_SESSION['check_product_instock'] = 0;

                      // $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt=0 and product_id_fk=".$value->product_id_fk." ");
                      $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE product_id_fk=".$value->product_id_fk." ");

                        // วุฒิเพิ่มมาสำหรับตรวจโปรโมชั่น
                        if(count($temp_db_stocks_02)==0){
                          $temp_db_stocks_02 = DB::table($temp_ppp_002)
                          ->select(
                            $temp_ppp_002.'.created_at',
                            'promotions_products.product_unit as product_unit_id_fk',
                            'promotions_products.product_id_fk as product_id_fk',
                            DB::raw('CONCAT(products.product_code," : ", products_details.product_name) AS product_name'),
                            'promotions_products.product_amt as amt',
                          )
                          ->join('promotions_products','promotions_products.promotion_id_fk',$temp_ppp_002.'.promotion_id_fk')
                          ->join('products_details','products_details.product_id_fk','promotions_products.product_id_fk')
                          ->join('products','products.id','promotions_products.product_id_fk')
                          ->where($temp_ppp_002.'.type_product','promotion')
                          ->where('promotions_products.product_id_fk',$value->product_id_fk)
                          ->groupBy('promotions_products.product_id_fk')
                          ->get();
                        }

                     $i = 1;
                     foreach ($temp_db_stocks_02 as $v_02) {

                             // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                     $p_unit  = DB::select("  SELECT product_unit
                                      FROM
                                      dataset_product_unit
                                      WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                     $p_unit_name = @$p_unit[0]->product_unit;

                                     $_choose=DB::table("$temp_ppr_004")
                                      ->where('invoice_code', $row->invoice_code)
                                      ->where('product_id_fk', $v_02->product_id_fk)
                                      // ->where('branch_id_fk', $v_02->branch_id_fk)
                                      ->where('branch_id_fk', @\Auth::user()->branch_id_fk)
                                      ->get();
                                        if($_choose->count() == 0){
                                              DB::select(" INSERT IGNORE INTO $temp_ppr_004 (
                                              business_location_id_fk,
                                              branch_id_fk,
                                              orders_id_fk,
                                              customers_id_fk,
                                              invoice_code,
                                              product_id_fk,
                                              product_name,
                                              amt_need,
                                              amt_get,
                                              amt_lot,
                                              amt_remain,
                                              product_unit_id_fk,
                                              product_unit,
                                              created_at
                                              )
                                              VALUES (
                                                '".$v_02->business_location_id_fk."',
                                                '".$v_02->branch_id_fk."',
                                                '".$value->orders_id_fk."',
                                                '".$value->customers_id_fk."',
                                                '".$row->invoice_code."',
                                                '".$v_02->product_id_fk."',
                                                '".$value->product_name."',
                                                '".$value->amt_need."',
                                                '0',
                                                '0',
                                                '".$amt_pay_remain."',
                                                '".$v_02->product_unit_id_fk."',
                                                '".$p_unit_name."',
                                                '".$v_02->created_at."'
                                              )
                                            ");
                                        }
                                // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                        }

                }

            $pn .= '</div>';
            $pn .= '</div>';
          }

          $pn .= '</div>';

            $rs_temp_ppr_004 = DB::select(" select product_id_fk,lot_number,lot_expired_date,sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, amt_remain  from $temp_ppr_004 group by invoice_code,product_id_fk  ");

           foreach ($rs_temp_ppr_004 as $key => $v) {

              $_choose=DB::table("$temp_db_stocks_check")
                ->where('invoice_code', $row->invoice_code)
                // ->where('product_id_fk', $v->product_id_fk)
                // ->where('lot_number', $v->lot_number)
                // ->where('lot_expired_date', $v->lot_expired_date)
                // ->where('amt_get', $v->amt_get)
                // ->where('amt_lot', $v->amt_lot)
                // ->where('amt_remain', $v->amt_remain)
                ->get();
                if($_choose->count() == 0){
                  DB::select(" INSERT IGNORE INTO $temp_db_stocks_check (invoice_code,product_id_fk,lot_number,lot_expired_date,amt_get,amt_lot,amt_remain)
                    VALUES (
                    '".$row->invoice_code."', ".$v->product_id_fk.", '".$v->lot_number."', '".$v->lot_expired_date."', ".$v->amt_lot.", '".$v->amt_lot."', '".$v->amt_remain."'
                    )
                  ");
                }

          }


          $rs_temp_db_stocks_check = DB::select(" select invoice_code, sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, sum(amt_remain) as amt_remain from $temp_db_stocks_check group by invoice_code ");

          foreach ($rs_temp_db_stocks_check as $key => $v) {

              $_choose=DB::table("$temp_db_stocks_compare")
                // ->where('invoice_code', $row->invoice_code)
                // ->where('amt_get', $v->amt_get)
                // ->where('amt_lot', $v->amt_lot)
                // ->where('amt_remain', $v->amt_remain)
                ->get();
                // if($_choose->count() == 0){
                  DB::select(" INSERT IGNORE INTO $temp_db_stocks_compare (`invoice_code`, `amt_get`, `amt_lot`, `amt_remain`)
                    VALUES (
                    '".$row->invoice_code."', ".$v->amt_get.", '".$v->amt_lot."', '".$v->amt_remain."'
                    )
                  ");
                // }

          }

          return $pn;
      })
      ->escapeColumns('column_002')
       ->addColumn('column_003', function($row) {

                $pn = '<div class="divTable" ><div class="divTableBody" style="background-color:white !important;">';
                $pn .=
                '<div class="divTableRow" style="background-color:white !important;">
                <div class="divTableCell" style="text-align:center;font-weight:bold;">

                      <div class="divTableRow" style="background-color:white !important;">
                      <div class="divTableCell" style="text-align:center;font-weight:bold;color:white;">ยกเลิก</div>
                      </div>

              </div>
              </div>
              ';
            return $pn;

        })
        ->escapeColumns('column_003')
       ->addColumn('ch_amt_remain', function($row) {
        // ดูว่าไม่มีสินค้าค้างจ่ายแล้วใช่หรือไม่
        // Case ที่มีการบันทึกข้อมูลแล้ว
        // '3=สินค้าพอต่อการจ่ายครั้งนี้ 2=สินค้าไม่พอ มีบางรายการค้างจ่าย',
           $rs_pay_history = DB::select(" SELECT id FROM `db_pay_product_receipt_002_pay_history` WHERE invoice_code='".$row->invoice_code."' AND status in (2) ");

           if(count($rs_pay_history)>0){
               return 2;
           }else{
               return 3;
           }

       })
      ->addColumn('ch_amt_lot_wh', function($row) {
// ดูว่าไม่มีสินค้าคลังเลย

          $Products = DB::select("
            SELECT * from db_pay_product_receipt_002 WHERE invoice_code='".$row->invoice_code."' AND amt_remain > 0 GROUP BY product_id_fk ORDER BY time_pay DESC limit 1 ;
          ");
          // Case ที่มีการบันทึกข้อมูลแล้ว
          if(count($Products)>0){
              $arr = [];
              foreach ($Products as $key => $value) {
                array_push($arr,$value->product_id_fk);
              }
              $arr_im = implode(',',$arr);
              $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
              $r = DB::select(" SELECT sum(amt) as sum FROM $temp_db_stocks WHERE product_id_fk in ($arr_im) ");
              return @$r[0]->sum?@$r[0]->sum:0;
          }else{
              // Case ที่ยังไม่มีการบันทึกข้อมูล ก็ให้ส่งค่า >0 ไปก่อน
              return 1;
          }

       })
     ->addColumn('status_sent', function($row) {

          $status_sent = DB::select(" SELECT status_sent FROM `db_pay_product_receipt_001` WHERE invoice_code='".$row->invoice_code."'  ");
          if($status_sent){
              return $status_sent[0]->status_sent;
          }else{
              return 0;
          }

       })
      ->addColumn('status_cancel_all', function($row) {
            $P = DB::select(" select status_cancel from db_pay_product_receipt_002 where invoice_code='".@$row->invoice_code."' AND status_cancel=1 ");
            if(count($P)>0){
              return 1; // มีการยกเลิก
            }else{
              return 0; // ไม่มีการยกเลิก
            }
      })
      ->make(true);
    }



// /backend/pay_product_receipt/1/edit (กรณีที่มีการยกเลิกการจ่าย status_cancel==1 บ้างบางรายการหรือทั้งหมด)
// %%%%%%%%%%%%%%%%%%%%%%%
    public function Datatable010FIFO(Request $req){

      $temp_ppr_001 = "temp_ppr_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
      $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
      $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
      $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;

      $TABLES = DB::select(" SHOW TABLES ");
      // return $TABLES;
      $array_TABLES = [];
      foreach($TABLES as $t){
        // print_r($t->Tables_in_aiyaraco_v3);
        array_push($array_TABLES, $t->Tables_in_aiyaraco_v3);
      }
      // เก็บรายการสินค้าที่จ่าย ตอน FIFO

      if(in_array($temp_ppr_004,$array_TABLES)){
        // return "IN";
        DB::select(" TRUNCATE TABLE $temp_ppr_004  ");
      }else{
        // return "Not";
        DB::select(" CREATE TABLE $temp_ppr_004 LIKE temp_ppr_004_template ");
      }


      $invoice_code = $req->invoice_code;

      $sTable = DB::select("
        SELECT * from $temp_ppr_001 WHERE invoice_code='$invoice_code'
        ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('column_001', function($row) {
            $pn = '<div class="divTable"><div class="divTableBody">';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="width:50px;font-weight:bold;">ครั้งที่</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;">วันที่จ่าย</div>
            <div class="divTableCell" style="width:100px;text-align:center;font-weight:bold;"> พนักงาน </div>
            </div>
            ';
            $pn .=
            '<div class="divTableRow">
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            <div class="divTableCell" style="text-align:center;font-weight:bold;">-</div>
            </div>
            ';
            return $pn;
        })
       ->escapeColumns('column_001')
       ->addColumn('column_002', function($row) {

          $temp_ppr_001 = "temp_ppr_001".\Auth::user()->id; // ดึงข้อมูลมาจาก db_orders
          $temp_ppr_002 = "temp_ppr_002".\Auth::user()->id; // ดึงข้อมูลมาจาก db_order_products_list
          $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id; // เก็บรายการที่หยิบสินค้ามาจากชั้นต่างๆ ตอน FIFO
          $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
          $temp_db_stocks_check = "temp_db_stocks_check".\Auth::user()->id;
          $temp_db_stocks_compare = "temp_db_stocks_compare".\Auth::user()->id;


           $ch_status_cancel = DB::select(" SELECT * FROM `db_pay_product_receipt_002` WHERE invoice_code='".$row->invoice_code."' AND status_cancel in (0) ");
           if(count($ch_status_cancel)>0){

                 $Products = DB::select("
                  SELECT time_pay,orders_id_fk,customers_id_fk,`invoice_code`, `product_id_fk`, `product_name`,`amt_remain` from db_pay_product_receipt_002 WHERE invoice_code='".$row->invoice_code."' AND amt_remain > 0 AND time_pay=(SELECT time_pay FROM db_pay_product_receipt_002 WHERE `status_cancel`=0 ORDER BY time_pay DESC limit 1)  ;
                ");

           }else{

                $Products = DB::select("
                SELECT time_pay,orders_id_fk,customers_id_fk,`invoice_code`, `product_id_fk`, `product_name`,`amt_need` as amt_remain from db_pay_product_receipt_002 WHERE invoice_code='".$row->invoice_code."' AND time_pay=1;
              ");

           }


          $pn = '<div class="divTable"><div class="divTableBody">';
          $pn .=
          '<div class="divTableRow">
          <div class="divTableCell" style="width:240px;font-weight:bold;">ชื่อสินค้า</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จ่ายครั้งนี้</div>
          <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">ค้างจ่าย</div>
          <div class="divTableCell" style="width:450px;text-align:center;font-weight:bold;">

                      <div class="divTableRow">
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">หยิบสินค้าจากคลัง</div>
                      <div class="divTableCell" style="width:200px;text-align:center;font-weight:bold;">Lot number [Expired]</div>
                      <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">จำนวน</div>
                      </div>

          </div>
          </div>
          ';

          $amt_pay_remain = 0;
          $amt_lot = 0;
          $amt_to_take = 0;
          $pay_this = 0;

          DB::select(" DROP TABLE IF EXISTS temp_001; ");
           // TEMPORARY
          DB::select(" CREATE TEMPORARY TABLE temp_001 LIKE temp_db_stocks_amt_template_02 ");

          if($Products){

                  foreach ($Products as $key => $value) {

                       $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
                       $amt_pay_this = $value->amt_remain;
                        // จำนวนที่จะ Hint ให้ไปหยิบจากแต่ละชั้นมา ตามจำนวนที่สั่งซื้อ โดยการเช็คไปทีละชั้น fifo จนกว่าจะพอ
                        // เอาจำนวนที่เบิก เป็นเช็ค กับ สต๊อก ว่ามีพอหรือไม่ โดยเอาทุกชั้นที่มีมาคิดรวมกันก่อนว่าพอหรือไม่
                        $temp_db_stocks_01 = DB::select(" SELECT sum(amt) as amt,count(*) as amt_floor from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk."  ");
                        $amt_floor = $temp_db_stocks_01[0]->amt_floor;

                        // return $amt_pay_this ;

                        // Case 1 > มีสินค้าพอ (รวมจากทุกชั้น) และ ในคลังมีมากกว่า ที่ต้องการซื้อ
                        if($temp_db_stocks_01[0]->amt>0 && $temp_db_stocks_01[0]->amt>=$amt_pay_this ){

                          $pay_this = $value->amt_remain ;
                          $amt_pay_remain = 0;

                            $pn .=
                            '<div class="divTableRow">
                            <div class="divTableCell" style="width:240px;font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
                            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">'. $pay_this .'</div>
                            <div class="divTableCell" style="width:80px;text-align:center;">'. $amt_pay_remain .'</div>
                            <div class="divTableCell" style="width:450px;text-align:center;"> ';

                            // Case 1.1 > ไล่หาแต่ละชั้น ตาม FIFO ชั้นที่จะหมดอายุก่อน เอาออกมาก่อน
                            $w_arr = DB::table('warehouse')->where('w_code','WH02')->pluck('id')->toArray();
                            $w_str = '';
                            foreach($w_arr as $key => $w){
                              if($key+1==count($w_arr)){
                                $w_str.=$w;
                              }else{
                                $w_str.=$w.',';
                              }

                            }
                // wut อันนี้แก้ จ่ายผิดคลัง
                            $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." AND warehouse_id_fk in (".$w_str.") ORDER BY lot_expired_date ASC  ");
                            //  $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC  ");

                              DB::select(" DROP TABLE IF EXISTS temp_001; ");
                              // TEMPORARY
                              DB::select(" CREATE TEMPORARY TABLE temp_001 LIKE temp_db_stocks_amt_template_02 ");


                             $i = 1;
                             foreach ($temp_db_stocks_02 as $v_02) {

                                  $rs_ = DB::select(" SELECT amt_remain FROM temp_001 where id>0 order by id desc limit 1 ");
                                  $amt_remain = @$rs_[0]->amt_remain?@$rs_[0]->amt_remain:0;
                                  $pay_this = @$rs_[0]->amt_remain?@$rs_[0]->amt_remain:$value->amt_remain ;

                                  $zone = DB::select(" select * from zone where id=".$v_02->zone_id_fk." ");
                                  $shelf = DB::select(" select * from shelf where id=".$v_02->shelf_id_fk." ");
                                  $sWarehouse = @$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v_02->shelf_floor;

                                  // Check ว่า ชั้นแรกๆ มีพอมั๊ย ถ้ามีพอแล้ว ก็หยุดค้น
                                  // ถ้าจำนวนในคลังมีพอ เอาค่าจาก ที่ต้องการ
                                    $amt_in_wh = @$v_02->amt?@$v_02->amt:0;
                                    $amt_to_take = $pay_this;


                                    if($amt_to_take>=$amt_in_wh){
                                      DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_in_wh,$amt_to_take-$amt_in_wh) ");
                                      $rs_ = DB::select(" SELECT amt_get FROM temp_001 order by id desc limit 1 ");
                                      $amt_to_take = $rs_[0]->amt_get;
                                    }else{
                                      // $r_before =  DB::select(" select * from temp_001 where amt_remain<>0 order by id desc limit 1 ");
                                      // $amt_remain = $r_before[0]->amt_remain;
                                             DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_to_take,0) ");
                                              $rs_ = DB::select(" SELECT amt_get FROM temp_001 order by id desc limit 1 ");
                                              $amt_to_take = $rs_[0]->amt_get;
                                    }


                                        $pn .=
                                        '<div class="divTableRow">
                                        <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                        <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                        <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                        </div>
                                        ';

                                        // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                             $p_unit  = DB::select("  SELECT product_unit
                                              FROM
                                              dataset_product_unit
                                              WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                             $p_unit_name = @$p_unit[0]->product_unit;

                                            $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id;

                                             $_choose=DB::table("$temp_ppr_004")
                                              ->where('invoice_code', $row->invoice_code)
                                              ->where('product_id_fk', $v_02->product_id_fk)
                                              ->where('lot_number', $v_02->lot_number)
                                              ->where('lot_expired_date', $v_02->lot_expired_date)
                                              ->where('branch_id_fk', $v_02->branch_id_fk)
                                              ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                              ->where('zone_id_fk', $v_02->zone_id_fk)
                                              ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                              ->where('shelf_floor', $v_02->shelf_floor)
                                              ->get();
                                                if($_choose->count() == 0){
                                                      DB::select(" INSERT IGNORE INTO $temp_ppr_004 (
                                                      business_location_id_fk,
                                                      branch_id_fk,
                                                      orders_id_fk,
                                                      customers_id_fk,
                                                      invoice_code,
                                                      product_id_fk,
                                                      product_name,
                                                      amt_need,
                                                      amt_get,
                                                      amt_lot,
                                                      amt_remain,
                                                      product_unit_id_fk,
                                                      product_unit,
                                                      lot_number,
                                                      lot_expired_date,
                                                      warehouse_id_fk,
                                                      zone_id_fk,
                                                      shelf_id_fk,
                                                      shelf_floor,
                                                      created_at
                                                      )
                                                      VALUES (
                                                        '".$v_02->business_location_id_fk."',
                                                        '".$v_02->branch_id_fk."',
                                                        '".$value->orders_id_fk."',
                                                        '".$value->customers_id_fk."',
                                                        '".$row->invoice_code."',
                                                        '".$v_02->product_id_fk."',
                                                        '".$value->product_name."',
                                                        '".$value->amt_remain."',
                                                        '".$amt_to_take."',
                                                        '".$amt_to_take."',
                                                        '".$amt_pay_remain."',
                                                        '".$v_02->product_unit_id_fk."',
                                                        '".$p_unit_name."',
                                                        '".$v_02->lot_number."',
                                                        '".$v_02->lot_expired_date."',
                                                        '".$v_02->warehouse_id_fk."',
                                                        '".$v_02->zone_id_fk."',
                                                        '".$v_02->shelf_id_fk."',
                                                        '".$v_02->shelf_floor."',
                                                        '".$v_02->created_at."'
                                                      )
                                                    ");
                                                }
                                        // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                                             $r_temp_001 = DB::select(" SELECT amt_remain FROM temp_001 order by id desc limit 1 ; ");
                                             // return $r_temp_001[0]->amt_remain;
                                             if($r_temp_001[0]->amt_remain==0) break;

                                  $i++;

                             }


                        // Case 2 > กรณีมีบ้างเป็นบางรายการ (รวมจากทุกชั้น) และ ในคลังมี น้อยกว่า ที่ต้องการซื้อ
                        }else if($temp_db_stocks_01[0]->amt>0 && $temp_db_stocks_01[0]->amt<$amt_pay_this){ // กรณีมีบ้างเป็นบางรายการ

                             $pay_this = $temp_db_stocks_01[0]->amt ;
                             $amt_pay_remain = $value->amt_remain - $temp_db_stocks_01[0]->amt ;
                             $css_red = $amt_pay_remain>0?'color:red;font-weight:bold;':'';

                            $pn .=
                            '<div class="divTableRow">
                            <div class="divTableCell" style="width:240px;font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
                            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">'. $pay_this .'</div>
                            <div class="divTableCell" style="width:80px;text-align:center;'.$css_red.'">'. $amt_pay_remain .'</div>
                            <div class="divTableCell" style="width:450px;text-align:center;"> ';

                             // Case 2.1 > ไล่หาแต่ละชั้น ตาม FIFO ชั้นที่จะหมดอายุก่อน เอาออกมาก่อน
                             $w_arr = DB::table('warehouse')->where('w_code','WH02')->pluck('id')->toArray();
                             $w_str = '';
                             foreach($w_arr as $key => $w){
                               if($key+1==count($w_arr)){
                                 $w_str.=$w;
                               }else{
                                 $w_str.=$w.',';
                               }

                             }
                 // wut อันนี้แก้ จ่ายผิดคลัง
                             $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." AND warehouse_id_fk in (".$w_str.") ORDER BY lot_expired_date ASC  ");
                            //  $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt>0 AND product_id_fk=".$value->product_id_fk." ORDER BY lot_expired_date ASC  ");

                             $i = 1;
                             foreach ($temp_db_stocks_02 as $v_02) {
                                  $zone = DB::select(" select * from zone where id=".$v_02->zone_id_fk." ");
                                  $shelf = DB::select(" select * from shelf where id=".$v_02->shelf_id_fk." ");
                                  $sWarehouse = @$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$v_02->shelf_floor;

                                  // Check ว่า ชั้นแรกๆ มีพอมั๊ย ถ้ามีพอแล้ว ก็หยุดค้น
                                  // ถ้าจำนวนในคลังมีพอ เอาค่าจาก ที่ต้องการ
                                  $amt_to_take = $amt_pay_this;
                                  $amt_in_wh = @$v_02->amt?@$v_02->amt:0;


                                    // ในคลัง พิจารณารายชั้น ถ้าชั้นนั้นๆ น้อยกว่า ที่ต้องการ

                                       if($i==1){
                                            DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_in_wh,".($amt_to_take-$amt_in_wh).") ");
                                            $rs_ = DB::select(" SELECT amt_get FROM temp_001 WHERE id='$i' ");
                                            $amt_to_take = $rs_[0]->amt_get;

                                           $pn .=
                                            '<div class="divTableRow">
                                            <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                            <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;color:blue;"> '.$pay_this.' </div>
                                            </div>
                                            ';

                                        // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                             $p_unit  = DB::select("  SELECT product_unit
                                              FROM
                                              dataset_product_unit
                                              WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                             $p_unit_name = @$p_unit[0]->product_unit;

                                            $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id;

                                             $_choose=DB::table("$temp_ppr_004")
                                              ->where('invoice_code', $row->invoice_code)
                                              ->where('product_id_fk', $v_02->product_id_fk)
                                              ->where('lot_number', $v_02->lot_number)
                                              ->where('lot_expired_date', $v_02->lot_expired_date)
                                              ->where('branch_id_fk', $v_02->branch_id_fk)
                                              ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                              ->where('zone_id_fk', $v_02->zone_id_fk)
                                              ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                              ->where('shelf_floor', $v_02->shelf_floor)
                                              ->get();
                                                if($_choose->count() == 0){
                                                      DB::select(" INSERT IGNORE INTO $temp_ppr_004 (
                                                      business_location_id_fk,
                                                      branch_id_fk,
                                                      orders_id_fk,
                                                      customers_id_fk,
                                                      invoice_code,
                                                      product_id_fk,
                                                      product_name,
                                                      amt_need,
                                                      amt_get,
                                                      amt_lot,
                                                      amt_remain,
                                                      product_unit_id_fk,
                                                      product_unit,
                                                      lot_number,
                                                      lot_expired_date,
                                                      warehouse_id_fk,
                                                      zone_id_fk,
                                                      shelf_id_fk,
                                                      shelf_floor,
                                                      created_at
                                                      )
                                                      VALUES (
                                                        '".$v_02->business_location_id_fk."',
                                                        '".$v_02->branch_id_fk."',
                                                        '".$value->orders_id_fk."',
                                                        '".$value->customers_id_fk."',
                                                        '".$row->invoice_code."',
                                                        '".$v_02->product_id_fk."',
                                                        '".$value->product_name."',
                                                        '".$value->amt_remain."',
                                                        '".$pay_this."',
                                                        '".$pay_this."',
                                                        '".$amt_pay_remain."',
                                                        '".$v_02->product_unit_id_fk."',
                                                        '".$p_unit_name."',
                                                        '".$v_02->lot_number."',
                                                        '".$v_02->lot_expired_date."',
                                                        '".$v_02->warehouse_id_fk."',
                                                        '".$v_02->zone_id_fk."',
                                                        '".$v_02->shelf_id_fk."',
                                                        '".$v_02->shelf_floor."',
                                                        '".$v_02->created_at."'
                                                      )
                                                    ");
                                                }
                                        // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒


                                        }else{

                                            $r_before =  DB::select(" select * from temp_001 where amt_remain<>0 order by id desc limit 1 ");
                                            $amt_remain = @$r_before[0]->amt_remain?@$r_before[0]->amt_remain:0;

                                            DB::select(" INSERT IGNORE INTO temp_001(amt_get,amt_remain) values ($amt_remain,".($r_before[0]->amt_remain-$amt_in_wh).") ");
                                            $rs_ = DB::select(" SELECT * FROM temp_001 WHERE id='$i' ");
                                            $amt_to_take = $amt_in_wh;

                                            $pn .=
                                            '<div class="divTableRow">
                                            <div class="divTableCell" style="width:200px;text-align:center;"> '.$sWarehouse.' </div>
                                            <div class="divTableCell" style="width:200px;text-align:center;"> '.$v_02->lot_number.' ['.$v_02->lot_expired_date.'] </div>
                                            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;color:blue;"> '.$amt_to_take.' </div>
                                            </div>
                                            ';
                                         // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                             $p_unit  = DB::select("  SELECT product_unit
                                              FROM
                                              dataset_product_unit
                                              WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                             $p_unit_name = @$p_unit[0]->product_unit;

                                            $temp_ppr_004 = "temp_ppr_004".\Auth::user()->id;

                                             $_choose=DB::table("$temp_ppr_004")
                                              ->where('invoice_code', $row->invoice_code)
                                              ->where('product_id_fk', $v_02->product_id_fk)
                                              ->where('lot_number', $v_02->lot_number)
                                              ->where('lot_expired_date', $v_02->lot_expired_date)
                                              ->where('branch_id_fk', $v_02->branch_id_fk)
                                              ->where('warehouse_id_fk', $v_02->warehouse_id_fk)
                                              ->where('zone_id_fk', $v_02->zone_id_fk)
                                              ->where('shelf_id_fk', $v_02->shelf_id_fk)
                                              ->where('shelf_floor', $v_02->shelf_floor)
                                              ->get();
                                                if($_choose->count() == 0){
                                                      DB::select(" INSERT IGNORE INTO $temp_ppr_004 (
                                                      business_location_id_fk,
                                                      branch_id_fk,
                                                      orders_id_fk,
                                                      customers_id_fk,
                                                      invoice_code,
                                                      product_id_fk,
                                                      product_name,
                                                      amt_need,
                                                      amt_get,
                                                      amt_lot,
                                                      amt_remain,
                                                      product_unit_id_fk,
                                                      product_unit,
                                                      lot_number,
                                                      lot_expired_date,
                                                      warehouse_id_fk,
                                                      zone_id_fk,
                                                      shelf_id_fk,
                                                      shelf_floor,
                                                      created_at
                                                      )
                                                      VALUES (
                                                        '".$v_02->business_location_id_fk."',
                                                        '".$v_02->branch_id_fk."',
                                                        '".$value->orders_id_fk."',
                                                        '".$value->customers_id_fk."',
                                                        '".$row->invoice_code."',
                                                        '".$v_02->product_id_fk."',
                                                        '".$value->product_name."',
                                                        '".$value->amt_remain."',
                                                        '".$amt_to_take."',
                                                        '".$amt_to_take."',
                                                        '".$amt_pay_remain."',
                                                        '".$v_02->product_unit_id_fk."',
                                                        '".$p_unit_name."',
                                                        '".$v_02->lot_number."',
                                                        '".$v_02->lot_expired_date."',
                                                        '".$v_02->warehouse_id_fk."',
                                                        '".$v_02->zone_id_fk."',
                                                        '".$v_02->shelf_id_fk."',
                                                        '".$v_02->shelf_floor."',
                                                        '".$v_02->created_at."'
                                                      )
                                                    ");
                                                }
                                        // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒


                                        }


                                  $i++;

                             }



                        }else{ // กรณีไม่มีสินค้าในคลังเลย

                             $amt_pay_remain = $value->amt_remain ;
                             $pay_this = 0 ;
                             $css_red = $amt_pay_remain>0?'color:red;font-weight:bold;':'';

                            $pn .=
                            '<div class="divTableRow">
                            <div class="divTableCell" style="width:240px;font-weight:bold;padding-bottom:15px;">'.$value->product_name.'</div>
                            <div class="divTableCell" style="width:80px;text-align:center;font-weight:bold;">'. $pay_this .'</div>
                            <div class="divTableCell" style="width:80px;text-align:center;'.$css_red.'">'. $amt_pay_remain .'</div>
                            <div class="divTableCell" style="width:450px;text-align:center;"> ';

                              $pn .=
                                    '<div class="divTableRow">
                                    <div class="divTableCell" style="width:200px;text-align:center;color:red;"> *** ไม่มีสินค้าในคลัง... *** </div>
                                    <div class="divTableCell" style="width:200px;text-align:center;">  </div>
                                    <div class="divTableCell" style="width:80px;text-align:center;">  </div>
                                    </div>
                                    ';

                            @$_SESSION['check_product_instock'] = 0;

                              $temp_db_stocks_02 = DB::select(" SELECT * from $temp_db_stocks WHERE amt=0 and product_id_fk=".$value->product_id_fk." ");

                                // วุฒิเพิ่มมาสำหรับตรวจโปรโมชั่น
                                      if(count($temp_db_stocks_02)==0){
                                        $temp_db_stocks_02 = DB::table($temp_ppp_002)
                                        ->select(
                                          $temp_ppp_002.'.created_at',
                                          'promotions_products.product_unit as product_unit_id_fk',
                                          'promotions_products.product_id_fk as product_id_fk',
                                          DB::raw('CONCAT(products.product_code," : ", products_details.product_name) AS product_name'),
                                          'promotions_products.product_amt as amt',
                                        )
                                        ->join('promotions_products','promotions_products.promotion_id_fk',$temp_ppp_002.'.promotion_id_fk')
                                        ->join('products_details','products_details.product_id_fk','promotions_products.product_id_fk')
                                        ->join('products','products.id','promotions_products.product_id_fk')
                                        ->where($temp_ppp_002.'.type_product','promotion')
                                        ->where('promotions_products.product_id_fk',$value->product_id_fk)
                                        ->groupBy('promotions_products.product_id_fk')
                                        ->get();
                                      }

                             $i = 1;
                             foreach ($temp_db_stocks_02 as $v_02) {

                                     // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒
                                             $p_unit  = DB::select("  SELECT product_unit
                                              FROM
                                              dataset_product_unit
                                              WHERE id = ".$v_02->product_unit_id_fk." AND  lang_id=1  ");
                                             $p_unit_name = @$p_unit[0]->product_unit;

                                             $_choose=DB::table("$temp_ppr_004")
                                              ->where('invoice_code', $row->invoice_code)
                                              ->where('product_id_fk', $v_02->product_id_fk)
                                              // ->where('branch_id_fk', $v_02->branch_id_fk)
                                              ->where('branch_id_fk', @\Auth::user()->branch_id_fk)
                                              ->get();
                                              // return $row->invoice_code;
                                                if($_choose->count() == 0){
                                                      DB::select(" INSERT IGNORE INTO $temp_ppr_004 (
                                                      business_location_id_fk,
                                                      branch_id_fk,
                                                      orders_id_fk,
                                                      customers_id_fk,
                                                      invoice_code,
                                                      product_id_fk,
                                                      product_name,
                                                      amt_need,
                                                      amt_get,
                                                      amt_lot,
                                                      amt_remain,
                                                      product_unit_id_fk,
                                                      product_unit,
                                                      created_at
                                                      )
                                                      VALUES (
                                                        '".$v_02->business_location_id_fk."',
                                                        '".$v_02->branch_id_fk."',
                                                        '".$value->orders_id_fk."',
                                                        '".$value->customers_id_fk."',
                                                        '".$row->invoice_code."',
                                                        '".$v_02->product_id_fk."',
                                                        '".$value->product_name."',
                                                        '".$value->amt_remain."',
                                                        '0',
                                                        '0',
                                                        '".$amt_pay_remain."',
                                                        '".$v_02->product_unit_id_fk."',
                                                        '".$p_unit_name."',
                                                        '".$v_02->created_at."'
                                                      )
                                                    ");
                                                }
                                        // ๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒๒

                                }

                        }

                    $pn .= '</div>';
                    $pn .= '</div>';
                  }

                  $pn .= '</div>';

          }

            $rs_temp_ppr_004 = DB::select(" select product_id_fk,lot_number,lot_expired_date,sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, amt_remain  from $temp_ppr_004 group by invoice_code,product_id_fk  ");

            foreach ($rs_temp_ppr_004 as $key => $v) {

                $_choose=DB::table("$temp_db_stocks_check")
                  ->where('invoice_code', $row->invoice_code)
                  // ->where('product_id_fk', $v->product_id_fk)
                  // ->where('lot_number', $v->lot_number)
                  // ->where('lot_expired_date', $v->lot_expired_date)
                  // ->where('amt_get', $v->amt_get)
                  // ->where('amt_lot', $v->amt_lot)
                  // ->where('amt_remain', $v->amt_remain)
                  ->get();
                  if($_choose->count() == 0){
                    DB::select(" INSERT IGNORE INTO $temp_db_stocks_check (invoice_code,product_id_fk,lot_number,lot_expired_date,amt_get,amt_lot,amt_remain)
                      VALUES (
                      '".$row->invoice_code."', ".$v->product_id_fk.", '".$v->lot_number."', '".$v->lot_expired_date."', ".$v->amt_lot.", '".$v->amt_lot."', '".$v->amt_remain."'
                      )
                    ");
                  }

            }


          $rs_temp_db_stocks_check = DB::select(" select invoice_code, sum(amt_get) as amt_get, sum(amt_lot) as amt_lot, sum(amt_remain) as amt_remain from $temp_db_stocks_check group by invoice_code ");

          foreach ($rs_temp_db_stocks_check as $key => $v) {

              $_choose=DB::table("$temp_db_stocks_compare")
                ->get();
                  DB::select(" INSERT IGNORE INTO $temp_db_stocks_compare (`invoice_code`, `amt_get`, `amt_lot`, `amt_remain`)
                    VALUES (
                    '".$row->invoice_code."', ".$v->amt_get.", '".$v->amt_lot."', '".$v->amt_remain."'
                    )
                  ");
          }

          return $pn;
      })
      ->escapeColumns('column_002')
       ->addColumn('column_003', function($row) {

                $pn = '<div class="divTable" ><div class="divTableBody" style="background-color:white !important;">';
                $pn .=
                '<div class="divTableRow" style="background-color:white !important;">
                <div class="divTableCell" style="text-align:center;font-weight:bold;">

                      <div class="divTableRow" style="background-color:white !important;">
                      <div class="divTableCell" style="text-align:center;font-weight:bold;color:white;">ยกเลิก</div>
                      </div>

              </div>
              </div>
              ';
            return $pn;

        })
         ->escapeColumns('column_003')
         ->addColumn('ch_amt_remain', function($row) {
            // ดูว่าไม่มีสินค้าค้างจ่ายแล้วใช่หรือไม่
            // Case ที่มีการบันทึกข้อมูลแล้ว
            // '3=สินค้าพอต่อการจ่ายครั้งนี้ 2=สินค้าไม่พอ มีบางรายการค้างจ่าย',
             $rs_pay_history = DB::select(" SELECT id FROM `db_pay_product_receipt_002_pay_history` WHERE invoice_code='".$row->invoice_code."' AND status in (2) ");

             if(count($rs_pay_history)>0){
                 return 2;
             }else{
                 return 3;
             }


           })
          ->addColumn('ch_amt_lot_wh', function($row) {
         // ดูว่าไม่มีสินค้าคลังเลย

            $Products = DB::select("
              SELECT * from db_pay_product_receipt_002 WHERE invoice_code='".$row->invoice_code."' AND amt_remain > 0 GROUP BY product_id_fk ORDER BY time_pay DESC limit 1 ;
            ");
                   // วุฒิแก้ ปรับเอา AND amt_remain > 0 ออก เพื่อให้มันบันทึกได้ เอา limit 1 ออก
        //   $Products = DB::select("
        //   SELECT * from db_pay_product_receipt_002 WHERE invoice_code='".$row->invoice_code."'
        //   GROUP BY product_id_fk ORDER BY time_pay,amt_get DESC;
        // ");

            // Case ที่มีการบันทึกข้อมูลแล้ว
            if(count($Products)>0){
                $arr = [];
                foreach ($Products as $key => $value) {
                  array_push($arr,$value->product_id_fk);
                }
                $arr_im = implode(',',$arr);
                // return $arr_im;
                $temp_db_stocks = "temp_db_stocks".\Auth::user()->id;
                $r = DB::select(" SELECT sum(amt) as sum FROM $temp_db_stocks WHERE product_id_fk in ($arr_im) ");
                return @$r[0]->sum?@$r[0]->sum:0;
            }else{
                // Case ที่ยังไม่มีการบันทึกข้อมูล ก็ให้ส่งค่า >0 ไปก่อน
                return 1;
            }

       })
      ->addColumn('status_cancel_some', function($row) {
            $P = DB::select(" select status_cancel from db_pay_product_receipt_002 where invoice_code='".@$row->invoice_code."' AND status_cancel=1 ");
            if(count($P)>0){
              return 1;
            }else{
              return 0;
            }
      })
       ->addColumn('status_sent', function($row) {

            $status_sent = DB::select(" SELECT status_sent FROM `db_pay_product_receipt_001` WHERE invoice_code='".$row->invoice_code."'  ");
            if($status_sent){
                return $status_sent[0]->status_sent;
            }else{
                return 0;
            }

         })
        ->make(true);
      }



}// Close > class
