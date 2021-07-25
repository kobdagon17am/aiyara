<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Promotion_code_productController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.promotion_code_product.form');

    }

   public function create($id)
    {

      $sRowNew = \App\Models\Backend\PromotionCode::find($id);
      return View('backend.promotion_code_product.form')->with(array('sRowNew'=>$sRowNew) );
    }

    public function store(Request $request)
    {
      // dd($request->all());
      return $this->form();
      // return redirect()->to(url("backend/promotion_cus/".request('promotion_code_id_fk')."/edit"));
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Promotion_code_product::find($id);
       $sRowNew = \App\Models\Backend\PromotionCode::find($sRow->promotion_code_id_fk);
       return view('backend.promotion_code_product.form')->with(['sRow'=>$sRow, 'id'=>$id ,'sRowNew'=>$sRowNew ]);
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      return $this->form($id);
    }


    public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Promotion_code_product::find($id);
          }else{
            $sRow = new \App\Models\Backend\Promotion_code_product;
          }

          $sRow->promotion_code_id_fk = request('promotion_code_id_fk');
          $sRow->product_id_fk = request('product_id_fk');
          $sRow->amt = request('amt');
          $sRow->product_unit_id_fk = request('product_unit_id_fk');
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

           return redirect()->to(url("backend/promotion_cus/".request('promotion_code_id_fk')."/edit"));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Promotion_code_productController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Promotion_code_product::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Promotion_code_product::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {
        // return $row->product_unit_id_fk;
        if(!empty($row->product_id_fk)){

          $Products = DB::select(" 
                SELECT products.id as product_id,
                  products.product_code,
                  (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name ,
                  products_cost.selling_price,
                  products_cost.pv
                  FROM
                  products_details
                  Left Join products ON products_details.product_id_fk = products.id
                  LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                  WHERE lang_id=1 AND products.id= ".$row->product_id_fk."

           ");

           return  @$Products[0]->product_code." : ".@$Products[0]->product_name;

         }
          // return $row->product_unit_id_fk;

      })      
      ->addColumn('product_unit', function($row) {
        // return $row->product_unit_id_fk;
        if(!empty($row->product_unit_id_fk)){

            $p_unit = DB::select("
              SELECT product_unit
              FROM
              dataset_product_unit
              WHERE id = ".$row->product_unit_id_fk." AND  lang_id=1 ");

            return  @$p_unit[0]->product_unit;
          // return $row->product_unit_id_fk;
          }

      })
      ->make(true);
    }



}
