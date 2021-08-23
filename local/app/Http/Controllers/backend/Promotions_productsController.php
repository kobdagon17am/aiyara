<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Redirect;

class Promotions_productsController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.promotions_products.index');

    }

 public function create($id)
    {
       
       $sRowNew = \App\Models\Backend\Promotions::find($id);
       // dd($sRowNew);
       // $sProduct = \App\Models\Backend\Products::get();
       $sProduct = \App\Models\Backend\Products_details::where('lang_id', 1)->get();
          
       $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
       return View('backend.promotions_products.form')->with(array('sRowNew'=>$sRowNew,'sProduct'=>$sProduct,'sProductUnit'=>$sProductUnit));

    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Promotions_products::find($id);
       $sRowNew = \App\Models\Backend\Promotions::find($sRow->promotion_id_fk);
       $sProduct = \App\Models\Backend\Products_details::where('lang_id', 1)->get();
       // dd($sProduct);
       $sProductUnit = \App\Models\Backend\Product_unit::get();
       return View('backend.promotions_products.form')->with(array('sRow'=>$sRow , 'id'=>$id, 'sRowNew'=>$sRowNew ,'sProduct'=>$sProduct,'sProductUnit'=>$sProductUnit ) );
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
            $sRow = \App\Models\Backend\Promotions_products::find($id);
          }else{
            $sRow = new \App\Models\Backend\Promotions_products;
          }

          $sRow->promotion_id_fk    = request('promotion_id_fk');
          $sRow->product_id_fk    = request('product_id_fk');
          $sRow->product_amt    = request('product_amt');
          $sRow->product_unit    = request('product_unit');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

          return redirect()->to(url("backend/promotions/".request('promotion_id_fk')."/edit"));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Promotions_productsController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Promotions_products::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Promotions_products::search()->orderBy('id', 'asc');
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
      })
      ->addColumn('product_unit_desc', function($row) {
          $sP = \App\Models\Backend\Product_unit::find($row->product_unit);
          return $sP->product_unit;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
