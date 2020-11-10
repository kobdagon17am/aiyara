<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class ProductsController extends Controller
{

    public function index(Request $request)
    {

      // $dsProducts  = \App\Models\Backend\Products::get();
      // return view('backend.products.index')->with(['dsProducts'=>$dsProducts]);
      return view('backend.products.index');

    }

 public function create()
    {

          $sAgency = \App\Models\Backend\Agency::get();
          $sAistockist = \App\Models\Backend\Aistockist::get();
          $sTravel_feature = \App\Models\Backend\Travel_feature::get();
          $sPersonal_quality = \App\Models\Backend\Personal_quality::get();
          $sPackage = \App\Models\Backend\Package::get();
          $sLimited_amt_type = \App\Models\Backend\Limited_amt_type::get();
          $sProduct_unit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
          $sQualification = \App\Models\Backend\Qualification::get();

          $sBusiness_location = \App\Models\Backend\Business_location::get();
          $dsCategory  = \App\Models\Backend\Categories::where('lang_id', 1)->get();
          $dsOrders_type  = \App\Models\Backend\Orders_type::where('lang_id', 1)->get();

      return view('backend.products.form')->with([
          'dsOrders_type'=>$dsOrders_type,
          'dsCategory'=>$dsCategory,
          'sBusiness_location'=>$sBusiness_location,
          'sAgency'=>$sAgency,
          'sAistockist'=>$sAistockist,
          'sTravel_feature'=>$sTravel_feature,
          'sPersonal_quality'=>$sPersonal_quality,
          'sPackage'=>$sPackage,
          'sLimited_amt_type'=>$sLimited_amt_type,
          'sProduct_unit'=>$sProduct_unit,       
          'sQualification'=>$sQualification, 
      ]);
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {

          $sAgency = \App\Models\Backend\Agency::get();
          $sAistockist = \App\Models\Backend\Aistockist::get();
          $sTravel_feature = \App\Models\Backend\Travel_feature::get();
          $sPersonal_quality = \App\Models\Backend\Personal_quality::get();
          $sPackage = \App\Models\Backend\Package::get();
          $sLimited_amt_type = \App\Models\Backend\Limited_amt_type::get();
          $sProduct_unit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
          $sQualification = \App\Models\Backend\Qualification::get();

          $sBusiness_location = \App\Models\Backend\Business_location::get();
          $sRow = \App\Models\Backend\Products::find($id);
          $dsCategory  = \App\Models\Backend\Categories::where('lang_id', 1)->get();
          $dsOrders_type  = \App\Models\Backend\Orders_type::where('lang_id', 1)->get();
          $sProducts_details = \App\Models\Backend\Products_details::where('product_id_fk', $sRow->id)->get();
       // dd($sProducts_details);
       return view('backend.products.form')->with([
          'sRow'=>$sRow, 'id'=>$id ,
          'dsOrders_type'=>$dsOrders_type,
          'dsCategory'=>$dsCategory,
          'sBusiness_location'=>$sBusiness_location,
          'sProducts_details'=>$sProducts_details,

          'sAgency'=>$sAgency,
          'sAistockist'=>$sAistockist,
          'sTravel_feature'=>$sTravel_feature,
          'sPersonal_quality'=>$sPersonal_quality,
          'sPackage'=>$sPackage,
          'sLimited_amt_type'=>$sLimited_amt_type,
          'sProduct_unit'=>$sProduct_unit,
          'sQualification'=>$sQualification,
      ]);
    }

    public function update(Request $request, $id)
    {
      return $this->form($id);
    }


    public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {

          if( $id ){
            $sRow = \App\Models\Backend\Products::find($id);
          }else{
            $sRow = new \App\Models\Backend\Products;
          }

          if(!empty(request('orders_type'))){
            $Orders_type = implode(',', request('orders_type'));
          }else{
            $Orders_type = '';
          }

          $sRow->business_location    = request('business_location');
          $sRow->product_code    = request('product_code');
          $sRow->category_id    = request('category_id');
          $sRow->orders_type_id    = $Orders_type ;
          
          $sRow->pv    = request('pv');
          $sRow->main_unit    = request('main_unit');
          $sRow->show_startdate    = request('show_startdate');
          $sRow->show_enddate    = request('show_enddate');
          $sRow->all_available_purchase    = request('all_available_purchase');
          $sRow->limited_amt_type    = request('limited_amt_type');
          $sRow->limited_amt_person    = request('limited_amt_person');
          $sRow->promotion_coupon_status    = request('promotion_coupon_status');
          $sRow->minimum_package_purchased    = request('minimum_package_purchased');
          $sRow->reward_qualify_purchased    = request('reward_qualify_purchased');
          $sRow->keep_personal_quality    = request('keep_personal_quality');
          $sRow->maintain_travel_feature    = request('maintain_travel_feature');
          $sRow->aistockist    = request('aistockist');
          $sRow->agency    = request('agency');

          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->status    = request('status')?request('status'):1;
          $sRow->save();



          \DB::commit();


          if( $id ){
            return redirect()->action('backend\ProductsController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
          }else{
            return redirect()->to(url("backend/products/".$sRow->id."/edit"));
          }
          

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\ProductsController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Products::find($id);
      $sRowProducts_images = \App\Models\Backend\Products_images::where('product_id_fk',$id)->get();
      foreach ($sRowProducts_images as $key => $value) {
      	  @UNLINK(@$value->img_url.@$value->product_img);
      }
      if( $sRowProducts_images ){
        DB::delete(" DELETE FROM products_images WHERE (product_id_fk='$id') ");
      }
      if( $sRow ){
      	DB::delete(" DELETE FROM products_details WHERE (product_id_fk='$id') ");
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Products::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('pname', function($row) {
      	$sProducts_details = \App\Models\Backend\Products_details::where('product_id_fk',$row->id)->where('lang_id',1)->get();
        return @$sProducts_details[0]->product_name;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
