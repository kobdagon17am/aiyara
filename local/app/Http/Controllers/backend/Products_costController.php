<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Products_costController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.products_cost.index');

    }

 public function create($id)
    {

      $sRowNew = \App\Models\Backend\Products::find($id);
      $sProducts_details = \App\Models\Backend\Products_details::where('product_id_fk',$id)->where('lang_id',1)->get();
      $sProductName = @$sProducts_details[0]->product_name?@$sProducts_details[0]->product_name:'-ยังไม่กำหนดชื่อสินค้าในเมนูสินค้า-';

      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sCountry = \App\Models\Backend\Country::get();
      $sCurrency = \App\Models\Backend\Currency::get();
      $sLang = \App\Models\Backend\Products_cost::get();
      return View('backend.products_cost.form')->with(
        array(
          'sLang'=>$sLang,'sBusiness_location'=>$sBusiness_location,'sCountry'=>$sCountry,'sCurrency'=>$sCurrency,'sRowNew'=>$sRowNew,'sProductName'=>$sProductName
        ) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function show($id)
    {

      $sRow = \App\Models\Backend\Products_cost::find($id);
      // dd(@$sRow->id);
      return View('backend.products_cost.index')->with(array('sRow'=>$sRow) );


    }

    public function edit($id)
    {

       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sCountry = \App\Models\Backend\Country::get();
       $sCurrency = \App\Models\Backend\Currency::get();
       $sLang = \App\Models\Backend\Language::get();
       $sRow = \App\Models\Backend\Products_cost::find($id);

       $sProducts_details = \App\Models\Backend\Products_details::where('product_id_fk',$sRow->product_id_fk)->where('lang_id',1)->get();
       $sProductName = @$sProducts_details[0]->product_name?@$sProducts_details[0]->product_name:'-ยังไม่กำหนดชื่อสินค้าในเมนูสินค้า-';

       $sRowNew = \App\Models\Backend\Products::find($sRow->product_id_fk);

       return View('backend.products_cost.form')->with(array(
        'sRow'=>$sRow , 'id'=>$id, 'sLang'=>$sLang ,'sBusiness_location'=>$sBusiness_location,'sCountry'=>$sCountry,'sCurrency'=>$sCurrency,'sRowNew'=>$sRowNew,'sProductName'=>$sProductName
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
            $sRow = \App\Models\Backend\Products_cost::find($id);
          }else{
            $sRow = new \App\Models\Backend\Products_cost;
          }

          $sRow->product_id_fk    = request('product_id_fk');
          $sRow->business_location_id    = request('business_location_id');
          $sRow->country_id    = request('country_id');
          $sRow->currency_id    = request('currency_id');
          $sRow->cost_price    = request('cost_price');
          $sRow->selling_price    = request('selling_price');
          $sRow->member_price    = request('member_price');
          $sRow->pv    = request('pv');
          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

          return redirect()->to(url("backend/products/".request('product_id_fk')."/edit"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Products_costController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Products_cost::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Products_cost::search()->orderBy('id', 'asc');
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
