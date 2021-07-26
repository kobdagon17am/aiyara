<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Pick_packPackingController extends Controller
{

    public function index(Request $request)
    {

      // return view('backend.pick_pack_packing.index');
      
    }

 public function create()
    {

    }
    public function store(Request $request)
    {
      // return $this->form();
    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      // return $this->form($id);
    }

   public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {
          // if( $id ){
          //   $sRow = \App\Models\Backend\DeliveryPacking::find($id);
          // }else{
          //   $sRow = new \App\Models\Backend\DeliveryPacking;
          // }

          // $sRow->delivery_slip    = request('delivery_slip');
          // $sRow->receipt    = request('receipt');
          // $sRow->customer_id    = request('customer_id');
          // $sRow->tel    = request('tel');
          // $sRow->province_id_fk    = request('province_id_fk');
          // $sRow->delivery_date    = request('delivery_date');
                    
          // $sRow->created_at = date('Y-m-d H:i:s');
          // $sRow->save();

          // \DB::commit();

          //  return redirect()->to(url("backend/delivery/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Pick_packPackingController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // dd($id);
      
      // $sRow = \App\Models\Backend\Delivery::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // return response()->json(\App\Models\Alert::Msg('success'));
    }

  


}
