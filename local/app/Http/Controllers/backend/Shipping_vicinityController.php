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
      // dd($request);
      return view('backend.shipping_vicinity.index');
    }

   public function create($id)
    {

       // dd($id);
        // $id = business location 

      @$Shipping_cost = DB::select(" select * from dataset_shipping_cost where business_location_id_fk=$id AND shipping_type_id=2  ");
      // dd($Shipping_cost);
      @$sBusiness_location = \App\Models\Backend\Business_location::get();
      // // dd($sRowNew);
      if(@$Shipping_cost){
              @$Shipping_vicinity = DB::select(" select * from dataset_shipping_vicinity where business_location_id_fk=".@$id."  ");
            // dd($Shipping_vicinity);
            $arr=[];
            foreach (@$Shipping_vicinity as $key => $value) {
                 array_push($arr,$value->province_id_fk);
            }
            $pv = implode(',', $arr);
            $pv = $pv?$pv:0;

            $Province = DB::select(" select * from dataset_provinces where business_location_id=$id and id not in($pv) ");
      }else{
          $Province = DB::select(" select * from dataset_provinces where business_location_id=$id ");
      }

      return View('backend.shipping_vicinity.form')->with(array(
        'id'=>$id,
        'Province'=>$Province,
        'sBusiness_location'=>$sBusiness_location,
      ) );

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
        $sBusiness_location = \App\Models\Backend\Business_location::get();
        return View('backend.shipping_vicinity.form')->with(array('sRow'=>$sRow, 'id'=>$id ,'Province'=>$Province,
        'sBusiness_location'=>$sBusiness_location) );
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      return $this->form($id);
    }

   public function form($id=NULL)
    {
      \DB::beginTransaction();
      // dd($id);
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Shipping_vicinity::find($id);
          }else{
            $sRow = new \App\Models\Backend\Shipping_vicinity;
          }

          $sRow->business_location_id_fk    = request('business_location_id_fk');
          $sRow->province_id_fk    = request('province_id_fk');
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          // return redirect()->to(url("backend/shipping_vicinity/"));
          return redirect()->to(url("backend/shipping_cost/".request('business_location_id_fk')."/edit"));


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
      
      $sTable = DB::table('dataset_shipping_vicinity')
      ->get();

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
      ->addColumn('province_name_th', function($row) {
        if(@$row->province_id_fk){
          @$d = DB::select("select * from dataset_provinces where id=".@$row->province_id_fk." ");
          return @$d[0]->name_th;
        }
        
      })
      ->addColumn('province_name_en', function($row) {
        if(@$row->province_id_fk){
          @$d = DB::select("select * from dataset_provinces where id=".@$row->province_id_fk." ");
          return @$d[0]->name_en;
        }
        
      })     
      ->make(true);
    }



}
