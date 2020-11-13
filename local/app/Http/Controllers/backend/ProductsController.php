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
          $sBusiness_location = \App\Models\Backend\Business_location::get();
          $dsCategory  = \App\Models\Backend\Categories::where('lang_id', 1)->get();
          $dsOrders_type  = \App\Models\Backend\Orders_type::where('lang_id', 1)->get();

      return view('backend.products.form')->with([
          'dsOrders_type'=>$dsOrders_type,
          'dsCategory'=>$dsCategory,
          'sBusiness_location'=>$sBusiness_location,
      ]);
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {

          $sBusiness_location = \App\Models\Backend\Business_location::get();
          $sRow = \App\Models\Backend\Products::find($id);
          $dsCategory  = \App\Models\Backend\Categories::where('lang_id', 1)->get();
          $dsOrders_type  = \App\Models\Backend\Orders_type::where('lang_id', 1)->get();
          $sProducts_details = \App\Models\Backend\Products_details::where('product_id_fk', $sRow->id)->get();
       // dd($sProducts_details);

          $dsProducts_cost  = \App\Models\Backend\Products_cost::where('product_id_fk', $sRow->id)->first();
          // dd($dsProducts_cost);
          // dd($dsProducts_cost->cost_price);
          // dd($dsProducts_cost->selling_price);
          // dd($dsProducts_cost->member_price);
          // dd($dsProducts_cost->pv);
 
       return view('backend.products.form')->with([
          'sRow'=>$sRow, 'id'=>$id ,
          'dsOrders_type'=>$dsOrders_type,
          'dsCategory'=>$dsCategory,
          'sBusiness_location'=>$sBusiness_location,
          'sProducts_details'=>$sProducts_details,
          'dsProducts_cost'=>$dsProducts_cost,

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
            $sRow->status    = request('status')?request('status'):0;
          }else{
            $sRow = new \App\Models\Backend\Products;
            $sRow->status    = 1 ;
          }

          if(!empty(request('orders_type'))){
            $Orders_type = implode(',', request('orders_type'));
          }else{
            $Orders_type = '';
          }
         
          $sRow->product_code    = request('product_code');
          $sRow->category_id    = request('category_id');
          $sRow->orders_type_id    = $Orders_type ;

          $sRow->product_voucher    = request('product_voucher');

          $sRow->created_at = date('Y-m-d H:i:s');
          
          $sRow->save();

          \DB::commit();


          // if( $id ){
          //   return redirect()->action('backend\ProductsController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
          // }else{
            return redirect()->to(url("backend/products/".$sRow->id."/edit"));
          // }
          

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
        DB::delete(" DELETE FROM products_cost WHERE (product_id_fk='$id') ");
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
        return @$sProducts_details[0]->product_name?@$sProducts_details[0]->product_name:'-ยังไม่กำหนด-';
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
