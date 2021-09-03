<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Promotion_cus_productsController extends Controller
{

    public function index(Request $request)
    {

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

    public function Datatable(Request $req){

      if($req->txtSearchPro!=""){

        $sTable = DB::select("

              SELECT
              db_promotion_cus.id,
              db_promotion_cus.promotion_code_id_fk,
              db_promotion_cus.promotion_code,
              promotions_products.product_id_fk,
              promotions_products.product_amt,
              promotions_products.product_unit,
              products.product_code,
              promotions.name_thai as promotion_name
              ,
              (
              SELECT 
              products_details.product_name 
              FROM
              products_details
              Left Join products ON products_details.product_id_fk = products.id
              WHERE products_details.product_id_fk=promotions_products.product_id_fk AND lang_id=1
              ) as product_name
              FROM
              db_promotion_cus
              LEFT JOIN db_promotion_code ON db_promotion_cus.promotion_code_id_fk = db_promotion_code.id
              LEFT JOIN promotions_products ON db_promotion_code.promotion_id_fk = promotions_products.promotion_id_fk
              LEFT JOIN promotions ON promotions.id = db_promotion_code.promotion_id_fk
              LEFT JOIN products on products.id = promotions_products.product_id_fk
              WHERE 
              promotion_code='".$req->txtSearchPro."' 
              
   
        ");
      }else{
          $sTable = \App\Models\Backend\PromotionCusProducts::search()->orderBy('id', 'asc');
      }
      
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {
          return @$row->product_code.' : '.@$row->product_name;
      })
      ->addColumn('product_unit', function($row) {
          $sP = \App\Models\Backend\Product_unit::find($row->product_unit);
          return @$sP->product_unit;
      })
      ->make(true);
    }


}
