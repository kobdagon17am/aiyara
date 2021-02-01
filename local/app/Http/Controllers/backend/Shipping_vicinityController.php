<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Shipping_vicinityController extends Controller
{

    public function index(Request $request)
    {
      return view('backend.shipping_vicinity.index');
    }

   public function create()
    { 
      $Province = DB::select(" select * from dataset_provinces ");
      return View('backend.shipping_vicinity.form')->with(array('Province'=>$Province) );
    }

    public function store(Request $request)
    {
      // dd($request->all());
      return $this->form();
    }

    public function edit($id)
    {
      $sRow = \App\Models\Backend\Shipping_vicinity::find($id);
      $Province = DB::select(" select * from dataset_provinces ");

       return View('backend.shipping_vicinity.form')->with(array('sRow'=>$sRow, 'id'=>$id ,'Province'=>$Province) );
    }

    public function update(Request $request, $id)
    {
      dd($request->all());
      // return $this->form($id);
    }

   public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Shipping_vicinity::find($id);
          }else{
            $sRow = new \App\Models\Backend\Shipping_vicinity;
          }
         
          $sRow->shipping_name    = request('shipping_name');
          $sRow->purchase_amt    = request('purchase_amt');
          $sRow->shipping_vicinity    = request('shipping_vicinity');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          return redirect()->action('backend\Shipping_vicinityController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Shipping_vicinityController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Shipping_vicinity::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){
      $sTable = \App\Models\Backend\Shipping_vicinity::where('shipping_cost_id_fk',$req->shipping_cost_id_fk)->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('Crm_topic', function($row) {
      //   $sCrm_topic = \App\Models\Backend\Shipping_vicinity_topic::where('id', $row->Crm_topic_id)->get();
      //   return $sCrm_topic[0]->txt_desc;
      // })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
