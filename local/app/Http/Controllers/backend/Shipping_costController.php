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
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Shipping_cost::find($id);
       // dd($sRow);
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       return View('backend.shipping_cost.form')->with(array('sRow'=>$sRow, 'id'=>$id, 'sBusiness_location'=>$sBusiness_location) );
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
         
          $sRow->shipping_name    = request('shipping_name');
          $sRow->purchase_amt    = request('purchase_amt');
          $sRow->shipping_cost    = request('shipping_cost');
          $sRow->shipping_type    = request('shipping_type');
                    
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
      $sTable = \App\Models\Backend\Shipping_cost::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('business_location', function($row) {
        if(@$row->business_location_id_fk!=''){
             $P = DB::select(" select * from dataset_business_location where id=".@$row->business_location_id_fk." ");
             return @$P[0]->txt_desc;
        }else{
             return '-';
        }
      }) 
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
