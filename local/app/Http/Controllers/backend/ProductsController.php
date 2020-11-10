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
      $dsCategory  = \App\Models\Backend\Categories::where('lang_id', 1)->get();
      $dsOrders_type  = \App\Models\Backend\Orders_type::where('lang_id', 1)->get();
      return view('backend.products.form')->with(['dsOrders_type'=>$dsOrders_type,'dsCategory'=>$dsCategory]);
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Products::find($id);
       $dsCategory  = \App\Models\Backend\Categories::where('lang_id', 1)->get();
       $dsOrders_type  = \App\Models\Backend\Orders_type::where('lang_id', 1)->get();
       return view('backend.products.form')->with(['sRow'=>$sRow, 'id'=>$id ,'dsOrders_type'=>$dsOrders_type,'dsCategory'=>$dsCategory]);
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

          $sRow->product_code    = request('product_code');
          $sRow->category_id    = request('category_id');
          $sRow->orders_type_id    = $Orders_type ;
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->status    = request('status')?request('status'):0;
          $sRow->save();



          \DB::commit();

         return redirect()->action('backend\ProductsController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\ProductsController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Products::find($id);

      @UNLINK(@$sRow->img_url.@$sRow->image01);
      @UNLINK(@$sRow->img_url.@$sRow->image02);
      @UNLINK(@$sRow->img_url.@$sRow->image03);

      if( $sRow ){
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
