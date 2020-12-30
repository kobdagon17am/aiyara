<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class FrontstorelistController extends Controller
{

    public function index(Request $request)
    {
    }

    public function create()
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
                DB::delete(" DELETE FROM db_frontstore_products_list WHERE amt=0 ;");
             }
          }

      }

    }

     public function minus(Request $request)
        {
          // return($request->all());
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
                    (
                    SELECT dataset_product_unit.product_unit
                    FROM
                    products_units
                    LEFT JOIN dataset_product_unit on dataset_product_unit.id=products_units.product_unit_id_fk
                    WHERE products_units.product_id_fk=products.id
                    ) as product_unit,                    
                    products_cost.selling_price,
                    products_cost.pv
                    FROM
                    products
                    LEFT JOIN categories on products.category_id=categories.id
                    LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                    WHERE products.id = ".$request->product_id_fk[$i]." AND products_cost.business_location_id = 1 
                ");
                  // echo ($sProducts[0]->selling_price);
                  // echo request('product_id_fk_this');
               $sFrontstore = \App\Models\Backend\Frontstore::find(request('frontstore_id'));

               $sRow = \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('product_id_fk', @$request->product_id_fk[$i])->get();
               // return count($sRow);
              if( count($sRow)>0 ){

                    \App\Models\Backend\Frontstorelist::where('frontstore_id_fk', @$request->frontstore_id)->where('product_id_fk', @$request->product_id_fk[$i])->update(
                          [
                            // 'frontstore_id_fk' => request('frontstore_id') ,
                            'amt' => @$request->quantity[$i] ,
                            'total_pv' => @$sProducts[0]->pv * @$request->quantity[$i] ,
                            'total_price' => @$sProducts[0]->selling_price * @$request->quantity[$i] ,
                            // 'purchase_type_id_fk' => @$sFrontstore->purchase_type_id_fk ,
                            // 'product_unit_id_fk' => @$sProducts[0]->product_unit,
                          ]
                      ); 


                     DB::delete(" DELETE FROM db_frontstore_products_list WHERE amt=0 ;");

              }
              // else{

              //       $sRow = new \App\Models\Backend\Frontstorelist;
              //       $sRow->frontstore_id_fk    = request('frontstore_id') ;
              //       $sRow->product_id_fk    = @$request->product_id_fk[$i];
              //       $sRow->purchase_type_id_fk    = @$sFrontstore->purchase_type_id_fk;
              //       $sRow->selling_price    = @$sProducts[0]->selling_price;
              //       $sRow->pv    = @$sProducts[0]->pv;
              //       $sRow->amt    =  @$request->quantity[$i];
              //       $sRow->total_pv    =  @$sProducts[0]->pv * @$request->quantity[$i];
              //       $sRow->total_price    =  @$sProducts[0]->selling_price * @$request->quantity[$i];
              //       $sRow->created_at = date('Y-m-d H:i:s');
              //       if(!empty(request('quantity')[$i])){
              //         if(@$request->product_id_fk[$i] == request('product_id_fk_this')){
              //             $sRow->save();
              //         }
              //       }

              // }

               

       
              }

          }

        }


    public function store(Request $request)
    {
      // dd($request->all());
      // return redirect()->to(url("backend/frontstore/".request('frontstore_id')."/edit"));

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
      // dd($id);

      $sRow = \App\Models\Backend\Frontstorelist::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      // return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){

      if(@$req->frontstore_id_fk){
         $sTable = DB::select("
            SELECT * from db_frontstore_products_list WHERE frontstore_id_fk = ".$req->frontstore_id_fk."
        ");
      }else{
         $sTable = DB::select("
            SELECT * from db_frontstore_products_list 
        ");
      }

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {
        if(!empty($row->product_id_fk)){
            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$row->product_id_fk." AND lang_id=1");

            return @$Products[0]->product_code." : ".@$Products[0]->product_name;
        }else{
            return '';
        }
      })
      ->addColumn('product_unit', function($row) {
        // return $row->product_unit_id_fk;
        if(!empty($row->product_unit_id_fk)){

          $p_unit = DB::select("
              SELECT product_unit
              FROM
              dataset_product_unit
              WHERE id = ".$row->product_unit_id_fk." AND  lang_id=1 ");

              return $p_unit[0]->product_unit;

          }else{
              return '';
          }

          // return $row->product_unit_id_fk;

      })
      ->addColumn('purchase_type', function($row) {
          $purchase_type = DB::select(" select * from dataset_purchase_type where id=".$row->purchase_type_id_fk." ");
          return $purchase_type[0]->txt_desc;
      })      
      ->make(true);
    }


}
