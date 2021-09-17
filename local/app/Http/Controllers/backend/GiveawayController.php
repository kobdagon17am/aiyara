<?php

namespace App\Http\Controllers\backend;

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

      $sPackage = \App\Models\Backend\Package::get();
      $sQualification = \App\Models\Backend\Qualification::get();
      $sPersonal_quality = \App\Models\Backend\Personal_quality::get();
      $dsOrders_type  = \App\Models\Backend\Orders_type::where('lang_id', 1)->get();
      $sTravel_feature = \App\Models\Backend\Travel_feature::get();
      $sAgency = \App\Models\Backend\Agency::get();
      $sAistockist = \App\Models\Backend\Aistockist::get();

      return View('backend.giveaway.form')->with(array(
        'sPurchase_type'=>$sPurchase_type,
        'sGiveaway_type'=>$sGiveaway_type,
        'sGiveaway_time'=>$sGiveaway_time,
        'sGiveaway_obtion'=>$sGiveaway_obtion,
        'sBusiness_location'=>$sBusiness_location,

        'sPackage'=>$sPackage,
        'sQualification'=>$sQualification,
        'sPersonal_quality'=>$sPersonal_quality,
        'dsOrders_type'=>$dsOrders_type,
        'sTravel_feature'=>$sTravel_feature,
        'sAgency'=>$sAgency,
        'sAistockist'=>$sAistockist,

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

      $sPackage = \App\Models\Backend\Package::get();
      $sQualification = \App\Models\Backend\Qualification::get();
      $sPersonal_quality = \App\Models\Backend\Personal_quality::get();
      $dsOrders_type  = \App\Models\Backend\Orders_type::where('lang_id', 1)->get();
      $sTravel_feature = \App\Models\Backend\Travel_feature::get();
      $sAgency = \App\Models\Backend\Agency::get();
      $sAistockist = \App\Models\Backend\Aistockist::get();


       return View('backend.giveaway.form')->with(array(
       	'sRow'=>$sRow , 'id'=>$id ,
        'sPurchase_type'=>$sPurchase_type,
        'sGiveaway_type'=>$sGiveaway_type,
        'sGiveaway_time'=>$sGiveaway_time,
        'sGiveaway_obtion'=>$sGiveaway_obtion,
        'sBusiness_location'=>$sBusiness_location, 

        'sPackage'=>$sPackage,
        'sQualification'=>$sQualification,
        'sPersonal_quality'=>$sPersonal_quality,
        'dsOrders_type'=>$dsOrders_type,
        'sTravel_feature'=>$sTravel_feature,
        'sAgency'=>$sAgency,
        'sAistockist'=>$sAistockist,

      ) );
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

          if(!empty(request('orders_type'))){
            $Orders_type = implode(',', request('orders_type'));
          }else{
            $Orders_type = '';
          }

          $sRow->business_location_id_fk    = request('business_location_id_fk');
          $sRow->giveaway_name    = request('giveaway_name');
          $sRow->start_date    = request('start_date');
          $sRow->end_date    = request('end_date');
          $sRow->giveaway_member_type_id_fk    = request('giveaway_member_type_id_fk');
          $sRow->giveaway_in_bill_id_fk    = request('giveaway_in_bill_id_fk');
          $sRow->pv_minimum_purchase    = request('pv_minimum_purchase');
          $sRow->giveaway_option_id_fk    = request('giveaway_option_id_fk');
          $sRow->giveaway_voucher    = request('giveaway_voucher');
          $sRow->action_user    = \Auth::user()->id ;
          $sRow->minimum_package_purchased    = request('minimum_package_purchased');
          $sRow->reward_qualify_purchased    = request('reward_qualify_purchased');
          $sRow->keep_personal_quality    = request('keep_personal_quality');
          $sRow->maintain_travel_feature    = request('maintain_travel_feature');
          $sRow->aistockist    = request('aistockist');
          $sRow->agency    = request('agency');
          $sRow->orders_type_id    = $Orders_type ;

          $sRow->priority    = request('priority');
          $sRow->another_pro    = request('another_pro')?request('another_pro'):0;

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
