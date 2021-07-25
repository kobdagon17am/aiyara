<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class GiveawayController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.giveaway.index');

    }

 public function create()
    {
      $sPurchase_type = DB::select(" select * from dataset_orders_type where status=1 and lang_id=1 order by id limit 5");
      $sGiveaway_type = DB::select(" select * from dataset_giveaway_type where status=1 ");
      $sGiveaway_time = DB::select(" select * from dataset_giveaway_time where status=1 ");
      $sGiveaway_obtion = DB::select(" select * from dataset_giveaway_obtion where status=1 ");

      $sBusiness_location = \App\Models\Backend\Business_location::get();

      return View('backend.giveaway.form')->with(array(
        'sPurchase_type'=>$sPurchase_type,
        'sGiveaway_type'=>$sGiveaway_type,
        'sGiveaway_time'=>$sGiveaway_time,
        'sGiveaway_obtion'=>$sGiveaway_obtion,
        'sBusiness_location'=>$sBusiness_location,
      ) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
      
      $sRow = \App\Models\Backend\Giveaway::find($id);
      $sPurchase_type = DB::select(" select * from dataset_orders_type where status=1 and lang_id=1 order by id limit 5");
      $sGiveaway_type = DB::select(" select * from dataset_giveaway_type where status=1 ");
      $sGiveaway_time = DB::select(" select * from dataset_giveaway_time where status=1 ");
      $sGiveaway_obtion = DB::select(" select * from dataset_giveaway_obtion where status=1 ");

      $sBusiness_location = \App\Models\Backend\Business_location::get();

       return View('backend.giveaway.form')->with(array(
       	'sRow'=>$sRow , 'id'=>$id ,
        'sPurchase_type'=>$sPurchase_type,
        'sGiveaway_type'=>$sGiveaway_type,
        'sGiveaway_time'=>$sGiveaway_time,
        'sGiveaway_obtion'=>$sGiveaway_obtion,
        'sBusiness_location'=>$sBusiness_location, ) );
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
            $sRow = \App\Models\Backend\Giveaway::find($id);
          }else{
            $sRow = new \App\Models\Backend\Giveaway;
          }

          $sRow->business_location_id_fk    = request('business_location_id_fk');
          $sRow->giveaway_name    = request('giveaway_name');
          $sRow->start_date    = request('start_date');
          $sRow->end_date    = request('end_date');
          $sRow->purchase_type_id_fk    = request('purchase_type_id_fk');
          $sRow->giveaway_member_type_id_fk    = request('giveaway_member_type_id_fk');
          $sRow->giveaway_in_bill_id_fk    = request('giveaway_in_bill_id_fk');
          $sRow->pv_minimum_purchase    = request('pv_minimum_purchase');
          $sRow->giveaway_option_id_fk    = request('giveaway_option_id_fk');
          $sRow->giveaway_voucher    = request('giveaway_voucher');
          $sRow->action_user    = \Auth::user()->id ;

          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

          return redirect()->to(url("backend/giveaway/".$sRow->id."/edit"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\GiveawayController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Giveaway::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Giveaway::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('business_location', function($row) {
          $sP = \App\Models\Backend\Business_location::find($row->business_location_id_fk);
          return $sP->txt_desc;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
