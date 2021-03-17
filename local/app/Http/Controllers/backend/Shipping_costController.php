<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Shipping_costController extends Controller
{

    public function index(Request $request)
    {
      return view('backend.shipping_cost.index');
    }

   public function create()
    {
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       return View('backend.shipping_cost.form')->with(array('sBusiness_location'=>$sBusiness_location) );
    }

    public function store(Request $request)
    {
      // return $request;
      // dd();

      if(isset($request->save_update)){

          DB::select("  UPDATE dataset_shipping_cost SET 
            shipping_name = '". $request->shipping_name_1."'  ,
            purchase_amt = '". $request->purchase_amt_1."'  
            where 
            business_location_id_fk= '". $request->business_location_id_fk."' AND shipping_type_id=1  ");

          DB::select("  UPDATE dataset_shipping_cost SET 
            shipping_name = '". $request->shipping_name_2."'  ,
            shipping_cost = '". $request->purchase_amt_2."'  
            where 
            business_location_id_fk= '". $request->business_location_id_fk."' AND shipping_type_id=2  ");

          DB::select("  UPDATE dataset_shipping_cost SET 
            shipping_name = '". $request->shipping_name_3."'  ,
            shipping_cost = '". $request->purchase_amt_3."'  
            where 
            business_location_id_fk= '". $request->business_location_id_fk."' AND shipping_type_id=3  ");

          DB::select("  UPDATE dataset_shipping_cost SET 
            shipping_name = '". $request->shipping_name_4."'  ,
            shipping_cost = '". $request->purchase_amt_4."'  ,
            status_special = '". $request->status_special."'  
            where 
            business_location_id_fk= '". $request->business_location_id_fk."' AND shipping_type_id=4  ");


          return redirect()->to(url("backend/shipping_cost/".request('business_location_id_fk')."/edit"));

      }else if(isset($request->save_new)){

          if(!empty($request->shipping_name_1)){
              DB::select("  INSERT IGNORE INTO dataset_shipping_cost (business_location_id_fk, shipping_name, purchase_amt, shipping_cost,shipping_type_id, created_at) VALUES ('". $request->business_location_id_fk."', '". $request->shipping_name_1."', '". $request->purchase_amt_1."', '0','1', now() )  ");
          }

          if(!empty($request->shipping_name_2)){
              DB::select("  INSERT IGNORE INTO dataset_shipping_cost (business_location_id_fk, shipping_name, purchase_amt, shipping_cost,shipping_type_id, created_at) VALUES ('". $request->business_location_id_fk."', '". $request->shipping_name_2."', '0', '". $request->purchase_amt_2."', '2', now() )  ");
          }

          if(!empty($request->shipping_name_3)){
              DB::select("  INSERT IGNORE INTO dataset_shipping_cost (business_location_id_fk, shipping_name, purchase_amt, shipping_cost,shipping_type_id, created_at) VALUES ('". $request->business_location_id_fk."', '". $request->shipping_name_3."', '0', '". $request->purchase_amt_3."', '3', now() )  ");
          }

          if(!empty($request->shipping_name_4)){
              DB::select("  INSERT IGNORE INTO dataset_shipping_cost (business_location_id_fk, shipping_name, purchase_amt, shipping_cost,shipping_type_id,status_special, created_at) VALUES ('". $request->business_location_id_fk."', '". $request->shipping_name_4."', '0', '". $request->purchase_amt_4."', '4','". $request->status_special."', now() )  ");
          }

          return redirect()->to(url("backend/shipping_cost/".request('business_location_id_fk')."/edit"));

      }else{
          return $this->form();
      }

     
    }

    public function edit($id)
    {

      $shipping_cost = DB::table('dataset_shipping_cost')->where('business_location_id_fk',  $id)->where('shipping_type_id', '2')->get();
      // dd(@$shipping_cost[0]->id);
      if(!empty(@$shipping_cost[0]->id)){
        $shipping_vicinity = DB::table('dataset_shipping_vicinity')->where('shipping_cost_id_fk', $shipping_cost[0]->id)->get();
      }else{
        $shipping_vicinity = NULL ;
      }
      // dd(@$shipping_vicinity[0]->shipping_cost_id_fk);
       $sRow = DB::table('dataset_shipping_cost')->where('business_location_id_fk', $id)->first();
       // dd($sRow);
       $sBusiness_location = \App\Models\Backend\Business_location::get();

       $shipping_cost = DB::table('dataset_shipping_cost')->where('business_location_id_fk',$id)->get();
       // dd($shipping_cost);

       return View('backend.shipping_cost.form')->with(array('sRow'=>$sRow, 'id'=>$id, 'sBusiness_location'=>$sBusiness_location, 'shipping_cost'=>$shipping_cost, 'shipping_vicinity'=>$shipping_vicinity) );
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
            $sRow = \App\Models\Backend\Shipping_cost::find($id);
          }else{
            $sRow = new \App\Models\Backend\Shipping_cost;
          }
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          return redirect()->action('backend\Shipping_costController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Shipping_costController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Shipping_cost::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      // $sTable = \App\Models\Backend\Shipping_cost::search()->orderBy('id', 'asc');
      $sTable = DB::select("  select dataset_business_location.*,txt_desc as business_location from dataset_business_location  ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('cost_set', function($row) {
        if(@$row->id!=''){
             $P = DB::select(" select count(*) as cnt from dataset_shipping_cost where business_location_id_fk=".@$row->id." ");
             return @$P[0]->cnt;
        }else{
             return '';
        }
      }) 
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
