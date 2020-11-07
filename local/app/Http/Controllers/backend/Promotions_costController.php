<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Promotions_costController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.promotions_cost.index');

    }

 public function create()
    {
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sCountry = \App\Models\Backend\Country::get();
      $sCurrency = \App\Models\Backend\Currency::get();
      $sLang = \App\Models\Backend\Promotions_cost::get();
      return View('backend.promotions_cost.form')->with(
        array(
          'sLang'=>$sLang,'sBusiness_location'=>$sBusiness_location,'sCountry'=>$sCountry,'sCurrency'=>$sCurrency,
        ) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function show($id)
    {

      $sRow = \App\Models\Backend\Promotions_cost::find($id);
      // dd(@$sRow->id);
      return View('backend.promotions_cost.index')->with(array('sRow'=>$sRow) );


    }

    public function edit($id)
    {
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sCountry = \App\Models\Backend\Country::get();
       $sCurrency = \App\Models\Backend\Currency::get();
       $sLang = \App\Models\Backend\Language::get();
       $sRow = \App\Models\Backend\Promotions_cost::find($id);
       return View('backend.promotions_cost.form')->with(array(
        'sRow'=>$sRow , 'id'=>$id, 'sLang'=>$sLang ,'sBusiness_location'=>$sBusiness_location,'sCountry'=>$sCountry,'sCurrency'=>$sCurrency,
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
            $sRow = \App\Models\Backend\Promotions_cost::find($id);
          }else{
            $sRow = new \App\Models\Backend\Promotions_cost;
          }

          $sRow->business_location_id    = request('business_location_id');
          $sRow->country_id    = request('country_id');
          $sRow->currency_id    = request('currency_id');
          $sRow->cost_price    = request('cost_price');
          $sRow->selling_price    = request('selling_price');
          $sRow->member_price    = request('member_price');
          $sRow->pv    = request('pv');
          // $sRow->lang_id    = request('lang_id');
          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('backend\Promotions_costController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Promotions_costController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Promotions_cost::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Promotions_cost::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('business_location', function($row) {
          $sP = \App\Models\Backend\Business_location::find($row->business_location_id);
          return $sP->txt_desc;
      })
      // ->addColumn('promotion_name', function($row) {
      //     $sP = \App\Models\Backend\Promotions::find($row->promotion_id_fk);
      //     return $sP->name_thai;
      // })
      ->addColumn('country', function($row) {
          $sP = \App\Models\Backend\Country::find($row->country_id);
          return $sP->txt_desc;
      })
      ->addColumn('currency', function($row) {
          $sP = \App\Models\Backend\Currency::find($row->currency_id);
          return $sP->txt_desc;
      })      
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
