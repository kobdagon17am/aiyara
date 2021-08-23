<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Scan_qrcodeController extends Controller
{

    public function index(Request $request)
    {
      return View('backend.scan_qrcode.index');
      // $Products = DB::select("SELECT products.id as product_id,
      // products.product_code,
      // (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
      // FROM
      // products_details
      // Left Join products ON products_details.product_id_fk = products.id
      // WHERE lang_id=1");

      // $Check_stock = \App\Models\Backend\Scan_qrcode::get();

      // return View('backend.scan_qrcode.index')->with(
      //   array(
      //      'Products'=>$Products,'Check_stock'=>$Check_stock,
      //   ) );

    }

    public function create()
    {
    }
    public function store(Request $request)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

   public function form($id=NULL)
    {
    }

    public function destroy($id)
    {
    }

    public function Datatable(Request $reg){

      if(isset($reg->txtSearch)){
        $w = " where qrcode='".$reg->txtSearch."' OR receipt='".$reg->txtSearch."' OR  user_name='".$reg->txtSearch."' ";
      }else{
        $w = '';
      }

      $sTable = DB::select("
          SELECT
          db_check_orders.id,
          db_check_orders.receipt,
          db_check_orders.bill_no,
          db_check_orders.products_id_fk,
          db_check_orders.customer_id,
          db_check_orders.amt,
          db_check_orders.unit_price,
          db_check_orders.total_price,
          db_check_orders.qrcode,
          db_check_orders.check_date,
          db_check_orders.created_at,
          db_check_orders.updated_at,
          db_check_orders.deleted_at,
          customers.user_name as customer_code
          FROM
          db_check_orders
          Left Join customers ON db_check_orders.customer_id = customers.id
          ".$w."

          ") ;
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->make(true);
    }


}
