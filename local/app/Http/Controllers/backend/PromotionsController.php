<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class PromotionsController extends Controller
{

    public function index(Request $request)
    {
      $sPromotions = \App\Models\Backend\Promotions::get();
      // dd($sPromotions);
      return View('backend.promotions.index')->with(
        array(
          'sPromotions'=>$sPromotions,
        ) 
      );
    }


    public function create()
    {
          $sAgency = \App\Models\Backend\Agency::get();
          $sAistockist = \App\Models\Backend\Aistockist::get();
          $sTravel_feature = \App\Models\Backend\Travel_feature::get();
          $sPersonal_quality = \App\Models\Backend\Personal_quality::get();
          $sQualification = \App\Models\Backend\Qualification::get();
          $sPackage = \App\Models\Backend\Package::get();
          $sLimited_amt_type = \App\Models\Backend\Limited_amt_type::get();
          $sProduct_unit = \App\Models\Backend\Product_unit::get();
          $sBusiness_location = \App\Models\Backend\Business_location::get();
          $sProduct_group = \App\Models\Backend\Product_group::get();
          $sLang = \App\Models\Backend\Language::get();
          $dsOrders_type  = \App\Models\Backend\Orders_type::where('lang_id', 1)->get();
          return View('backend.promotions.form')->with(
            array(
              'sAgency'=>$sAgency,
              'sAistockist'=>$sAistockist,
              'sTravel_feature'=>$sTravel_feature,
              'sPersonal_quality'=>$sPersonal_quality,
              'sQualification'=>$sQualification,
              'sPackage'=>$sPackage,
              'sLimited_amt_type'=>$sLimited_amt_type,
              'sProduct_unit'=>$sProduct_unit,
              'sBusiness_location'=>$sBusiness_location,
              'sProduct_group'=>$sProduct_group,
              'sLang'=>$sLang,
              'dsOrders_type'=>$dsOrders_type,
            ) 
          );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
          $sAgency = \App\Models\Backend\Agency::get();
          $sQualification = \App\Models\Backend\Qualification::get();
          $sAistockist = \App\Models\Backend\Aistockist::get();
          $sTravel_feature = \App\Models\Backend\Travel_feature::get();
          $sPersonal_quality = \App\Models\Backend\Personal_quality::get();
          $sPackage = \App\Models\Backend\Package::get();
          $sLimited_amt_type = \App\Models\Backend\Limited_amt_type::get();
          $sProduct_unit = \App\Models\Backend\Product_unit::where('status', 1)->where('lang_id', 1)->get();
          $sBusiness_location = \App\Models\Backend\Business_location::get();
          $sProduct_group = \App\Models\Backend\Product_group::get();
          $sLang = \App\Models\Backend\Language::get();
          $sRow = \App\Models\Backend\Promotions::find($id);
          $dsOrders_type  = \App\Models\Backend\Orders_type::where('lang_id', 1)->get();
         return View('backend.promotions.form')->with(
          array(
            'sAgency'=>$sAgency,
            'sAistockist'=>$sAistockist,
            'sQualification'=>$sQualification,
            'sTravel_feature'=>$sTravel_feature,
            'sPersonal_quality'=>$sPersonal_quality,
            'sPackage'=>$sPackage,
            'sLimited_amt_type'=>$sLimited_amt_type,
            'sProduct_unit'=>$sProduct_unit,
            'sBusiness_location'=>$sBusiness_location,
            'sProduct_group'=>$sProduct_group,
            'sRow'=>$sRow ,
            'id'=>$id, 
            'sLang'=>$sLang,
            'dsOrders_type'=>$dsOrders_type,
           )
         );
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
            $sRow = \App\Models\Backend\Promotions::find($id);
          }else{
            $sRow = new \App\Models\Backend\Promotions;
          }

          if(!empty(request('orders_type'))){
            $Orders_type = implode(',', request('orders_type'));
          }else{
            $Orders_type = '';
          }

          $sRow->business_location    = request('business_location');
          $sRow->product_type    = request('product_type');
          $sRow->pcode    = request('pcode');
          // $sRow->coupon_terms    = request('coupon_terms');
          $sRow->name_thai    = request('name_thai');
          $sRow->name_eng    = request('name_eng');
          $sRow->name_laos    = request('name_laos');
          $sRow->name_burma    = request('name_burma');
          $sRow->name_cambodia    = request('name_cambodia');
          $sRow->detail_thai    = request('detail_thai');
          $sRow->detail_eng    = request('detail_eng');
          $sRow->detail_laos    = request('detail_laos');
          $sRow->detail_burma    = request('detail_burma');
          $sRow->detail_cambodia    = request('detail_cambodia');
          // $sRow->pv    = request('pv');
          $sRow->main_unit    = request('main_unit');
          $sRow->show_startdate    = request('show_startdate');
          $sRow->show_enddate    = request('show_enddate');
          $sRow->all_available_purchase    = request('all_available_purchase');
          $sRow->limited_amt_type    = request('limited_amt_type');
          $sRow->limited_amt_person    = request('limited_amt_person');
          $sRow->promotion_coupon_status    = request('promotion_coupon_status')?request('promotion_coupon_status'):0;
          $sRow->minimum_package_purchased    = request('minimum_package_purchased');
          $sRow->reward_qualify_purchased    = request('reward_qualify_purchased');
          $sRow->keep_personal_quality    = request('keep_personal_quality');
          $sRow->maintain_travel_feature    = request('maintain_travel_feature');
          $sRow->aistockist    = request('aistockist');
          $sRow->agency    = request('agency');

          $sRow->orders_type_id    = $Orders_type ;

          $sRow->lang_id    = request('lang_id');
          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

          return redirect()->to(url("backend/promotions/".$sRow->id."/edit"));
         // return redirect()->action('backend\PromotionsController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\PromotionsController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Promotions::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){
      // $sTable = \App\Models\Backend\Promotions::search()->orderBy('id', 'asc');
        if(!empty($req->startDate) && !empty($req->endDate)){
           $w01 = " and date(promotions.show_startdate) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
        }else{
           $w01 = "";
        }

        if(!empty($req->pcode)){
           $w02 = " and promotions.pcode = '".$req->pcode."' " ;
        }else{
           $w02 = "";
        }

        if(isset($req->status)){
           $w03 = " and promotions.status = '".$req->status."' " ;
        }else{
           $w03 = "";
        }

      $sTable = DB::select(" SELECT promotions.*,promotions_cost.member_price as proprice,promotions_cost.pv
        FROM
        promotions
        left Join promotions_cost ON promotions.id = promotions_cost.promotion_id_fk 
        WHERE 1
        ".$w01."
        ".$w02."
        ".$w03."

        order by updated_at desc

        ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('prodate', function($row) {
          return $row->show_startdate.' To '.$row->show_enddate;
      })
      ->addColumn('proprice', function($row) {
        if(@$row->proprice){
          return number_format($row->proprice,0,'.',',')." / ".number_format($row->pv,0,'.',',');
        }else{
          return "";
        }
        
      })
      ->make(true);
    }



}
