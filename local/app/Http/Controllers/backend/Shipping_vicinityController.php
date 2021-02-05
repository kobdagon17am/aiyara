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

   public function create($id)
    {
      $sRowNew = \App\Models\Backend\Shipping_cost::find($id);
      // dd($sRowNew);
      $Shipping_vicinity = DB::select(" select * from dataset_shipping_vicinity where shipping_cost_id_fk=".$sRowNew->id."  ");
      // dd($Shipping_vicinity);
      $arr=[];
      foreach ($Shipping_vicinity as $key => $value) {
           array_push($arr,$value->province_id_fk);
      }
      $pv = implode(',', $arr);

      $Province = DB::select(" select * from dataset_provinces where id not in($pv) ");
      return View('backend.shipping_vicinity.form')->with(array('sRowNew'=>$sRowNew,'Province'=>$Province) );
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
        $sRowNew = \App\Models\Backend\Shipping_cost::find($sRow->shipping_cost_id_fk);
        // dd($sRowNew);
        return View('backend.shipping_vicinity.form')->with(array('sRow'=>$sRow, 'id'=>$id ,'Province'=>$Province,'sRowNew'=>$sRowNew) );
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
            $sRow = \App\Models\Backend\Shipping_vicinity::find($id);
          }else{
            $sRow = new \App\Models\Backend\Shipping_vicinity;
          }
         
          $sRow->shipping_cost_id_fk    = request('shipping_cost_id');
          $sRow->province_id_fk    = request('province_id_fk');
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          return redirect()->to(url("backend/shipping_cost/".request('shipping_cost_id')."/edit"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        // return redirect()->action('backend\Shipping_vicinityController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
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
      ->addColumn('province_name', function($row) {
        $d = DB::select("select * from dataset_provinces where id=".$row->province_id_fk." ");
        return $d[0]->name_th;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
