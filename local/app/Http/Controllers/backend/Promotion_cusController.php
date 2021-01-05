<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Promotion_cusController extends Controller
{

    public function index(Request $request)
    {
        return view("backend.promotion_cus.index");
    }

    public function create(Request $request)
    {


      $Products = DB::select("SELECT products.id as product_id,
      products.product_code,
      (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
      FROM
      products_details
      Left Join products ON products_details.product_id_fk = products.id
      WHERE lang_id=1");

      // dd($Products);

      return View('backend.promotion_cus.form')
      ->with(
        array(
           'Products'=>$Products,
        ) ); 
      
    }
    public function store(Request $request)
    {
      // dd($request->all());
      // return(count($request->promotion_code));
        for ($i=0; $i < count($request->promotion_code) ; $i++) { 
            if(@$request->customer_id[$i]!=""){
              \App\Models\Backend\PromotionCus::where('promotion_code', @$request->promotion_code[$i])->update(
                    [
                      'customer_id_fk' => @$request->customer_id[$i] ,
                      'pro_status' => 1  ,
                    ]
                ); 
            }
   
          }

          return redirect()->to(url("backend/promotion_cus"));
    }

    public function edit($id)
    {
      $Products = DB::select("SELECT products.id as product_id,
      products.product_code,
      (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
      FROM
      products_details
      Left Join products ON products_details.product_id_fk = products.id
      WHERE lang_id=1");

      // dd($Products);

      return View('backend.promotion_cus.form')
      ->with(
        array(
           'Products'=>$Products,
        ) );       
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

    public function Datatable(Request $req){

      $sTable = \App\Models\Backend\PromotionCus::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('product_name', function($row) {
      //     $Products = DB::select("SELECT products.id as product_id,
      //       products.product_code,
      //       (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
      //       FROM
      //       products_details
      //       Left Join products ON products_details.product_id_fk = products.id
      //       WHERE products.id=".$row->product_id_fk." AND lang_id=1");

      //   return @$Products[0]->product_code." : ".@$Products[0]->product_name;

      // })
      // ->addColumn('lot_expired_date', function($row) {
      //   $d = strtotime($row->lot_expired_date); 
      //   return date("d/m/", $d).(date("Y", $d)+543);
      // })
      // ->addColumn('date_in_stock', function($row) {
      //   $d = strtotime($row->pickup_firstdate); 
      //   return date("d/m/", $d).(date("Y", $d)+543);
      // })
      // ->addColumn('warehouses', function($row) {
      //   $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
      //   $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
      //   $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
      //   $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
      //   return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name;
      // })      
      // ->addColumn('updated_at', function($row) {
      //   return is_null($row->updated_at) ? '-' : $row->updated_at;
      // })
      ->make(true);
    }


    public function DatatableChoose(Request $req){

      $sTable = \App\Models\Backend\PromotionCus::where('customer_id_fk','=','0')->search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->make(true);
    }



}
