<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class GiftvoucherCusController extends Controller
{

    public function index(Request $request)
    {
        return view("backend.giftvoucher_cus.index");
    }

    public function create(Request $request)
    {


      return View('backend.giftvoucher_cus.form');

    }
    public function store(Request $request)
    {

    }

    public function edit($id)
    {

      $sRow = \App\Models\Backend\GiftvoucherCode::find($id);
 
      return View('backend.giftvoucher_cus.form')
      ->with(
        array(
           'sRow'=>$sRow,
        ) );       
    }

    public function update(Request $request, $id)
    {
    }

   public function form($id=NULL)
    {

    }

    public function plus(Request $request)
        {
          // return($request->all());
          // return(count($request->product_id_fk));
          // return($request->quantity[0]);
          // dd();

          if(isset($request->product_plus)){
            for ($i=0; $i < count($request->product_id_fk) ; $i++) { 
                // $Check_stock = \App\Models\Backend\Check_stock::find($request->id[$i]);
                // echo $Check_stock->product_id_fk;
                  $sProducts = DB::select("
                    SELECT
                    products.id,
                    (
                      SELECT id
                      FROM
                      dataset_product_unit
                      WHERE id = products.id AND lang_id=1 LIMIT 1
                    ) as product_unit,                
                    products.category_id ,categories.category_name,
                    (SELECT concat(img_url,product_img) FROM products_images WHERE products_images.product_id_fk=products.id) as p_img,
                    (
                    SELECT concat( products.product_code,' : '  ,
                    products_details.product_name)
                    FROM
                    products_details
                    WHERE products_details.lang_id=1 AND products_details.product_id_fk=products.id
                    ) as pn,
                    products_cost.selling_price,
                    products_cost.pv
                    FROM
                    products
                    LEFT JOIN categories on products.category_id=categories.id
                    LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                    WHERE products.id = ".$request->product_id_fk[$i]." AND products_cost.business_location_id = 1 
                ");
                  // echo ($sProducts[0]->product_unit);
               
               $sFrontstore = \App\Models\Backend\Frontstore::find(request('frontstore_id'));

               $sRow = \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('product_id_fk', @$request->product_id_fk[$i])->get();
               // echo count($sRow);
               // return @$request->frontstore_id;
              if( count($sRow)>0 ){

                  if(@$request->product_id_fk[$i] == request('product_id_fk_this')){

                    // echo @$request->quantity[$i];

                      \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('product_id_fk', @$request->product_id_fk[$i])->update(
                            [
                              // 'frontstore_id_fk' => request('frontstore_id') ,
                              'amt' => @$request->quantity[$i] ,
                              'total_pv' => @$sProducts[0]->pv * @$request->quantity[$i] ,
                              'total_price' => @$sProducts[0]->selling_price * @$request->quantity[$i] ,
                              // 'purchase_type_id_fk' => @$sFrontstore->purchase_type_id_fk,
                              'product_unit_id_fk' => @$sProducts[0]->product_unit,
                            ]
                        ); 

                  }

              }else{

                    $sRow = new \App\Models\Backend\Frontstorelist;
                    $sRow->frontstore_id_fk    = request('frontstore_id') ;
                    $sRow->product_id_fk    = @$request->product_id_fk[$i];
                    $sRow->purchase_type_id_fk    = @$sFrontstore->purchase_type_id_fk;
                    $sRow->selling_price    = @$sProducts[0]->selling_price;
                    $sRow->pv    = @$sProducts[0]->pv;
                    $sRow->amt    =  @$request->quantity[$i];
                    $sRow->product_unit_id_fk    =  @$sProducts[0]->product_unit ;
                    $sRow->total_pv    =  @$sProducts[0]->pv * @$request->quantity[$i];
                    $sRow->total_price    =  @$sProducts[0]->selling_price * @$request->quantity[$i];
                    $sRow->created_at = date('Y-m-d H:i:s');
                    if(!empty(request('quantity')[$i])){
                      if(@$request->product_id_fk[$i] == request('product_id_fk_this')){
                          $sRow->save();
                      }
                    }

              }

       
              }


              if(isset($request->product_plus_addlist)){
                  // return redirect()->to(url("backend/frontstore/".request('frontstore_id')."/edit"));
                 if($request->quantity[0]==0){
                    DB::delete(" DELETE FROM db_order_products_list WHERE amt=0 ;");
                 }
              }

          }

        }
        
    public function destroy($id)
    {
    }

    public function Datatable(Request $req){

      $sTable = \App\Models\Backend\GiftvoucherCus::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->make(true);
    }


    public function DatatableChoose(Request $req){

      $sTable = \App\Models\Backend\GiftvoucherCus::where('customer_id_fk','=','0')->search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->make(true);
    }



}
