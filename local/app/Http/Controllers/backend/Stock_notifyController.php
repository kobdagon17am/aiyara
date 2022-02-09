<?php

namespace App\Http\Controllers\backend;

use DB;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\General;

class Stock_notifyController extends Controller
{
    public function index(Request $request)
    {
      General::gen_id_url();
      // $sTable = \App\Models\Backend\Stock_notify::search()->orderBy('id', 'asc');
      // dd($sTable);
        $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
          return $query->where('id', auth()->user()->business_location_id_fk);
        })->get();

        $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE  lang_id=1");

         $product_name = @$Products[0]->product_code." : ".@$Products[0]->product_name;

         // dd($product_name);
         $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
           return $query->where('id', auth()->user()->branch_id_fk);
         })->get();
      
         $Warehouse = \App\Models\Backend\Warehouse::get();

        return View('backend.stock_notify.index')->with(array(
          'Products'=>$Products,
        'sBusiness_location'=>$sBusiness_location
        ,'product_name'=>$product_name,
        'sBranchs'=>$sBranchs,
        'Warehouse'=>$Warehouse,
       ));
    }

    public function create(Request $request)
    {

    }

    public function store(Request $request)
    {

    }

    public function edit($id)
    {

       $sRow = \App\Models\Backend\Stock_notify::find($id);
       $sBusiness_location = \App\Models\Backend\Business_location::get();

        $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".@$sRow->product_id_fk." AND lang_id=1");

         $product_name = @$Products[0]->product_code." : ".@$Products[0]->product_name;

         // dd($product_name);
         $sBranchs = \App\Models\Backend\Branchs::get();
         $Warehouse = \App\Models\Backend\Warehouse::get();

       return View('backend.stock_notify.form')->with(array(
        'sRow'=>$sRow, 'id'=>$id,'sBusiness_location'=>$sBusiness_location
        ,'product_name'=>$product_name,
        'sBranchs'=>$sBranchs,
        'Warehouse'=>$Warehouse,
       ));
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

          $sRow = \App\Models\Backend\Stock_notify::find(request('id'));

          $sRow->amt_less    = request('amt_less');
          $sRow->amt_day_before_expired    = request('amt_day_before_expired');
          $sRow->note    = request('note');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/stock_notify/".request('id')."/edit"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        // return redirect()->action('backend\DeliveryController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }


    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Stock_notify::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){
      if(!empty($req->product_name) ){
        // $sTable = \App\Models\Backend\Stock_notify::where('product_id_fk', 'like', '%' . $req->product_name . '%')->get();
        $sTable = DB::select("
          select db_stocks_notify.*,products_details.product_name, products.product_code from db_stocks_notify
          Left Join products_details ON products_details.product_id_fk = db_stocks_notify.product_id_fk
          Left Join products ON products.id = db_stocks_notify.product_id_fk
          WHERE
          products_details.product_name LIKE '%".$req->product_name."%' OR
          db_stocks_notify.product_id_fk LIKE '%".ltrim($req->product_name, '0')."%'
          AND products_details.lang_id=1 order by products.product_code ASC
      ");
        // $sTable = \App\Models\Backend\Stock_notify::where('product_id_fk', '1')->get();
      }else{
        $sTable = \App\Models\Backend\Stock_notify::select('db_stocks_notify.*','products.product_code','products.id as p_id')
        ->join('products','products.id','db_stocks_notify.product_id_fk')
        ->search()
        ->orderBy('products.product_code', 'ASC');
      }

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {
          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".@$row->product_id_fk." AND lang_id=1");

         return @$Products[0]->product_code." : ".@$Products[0]->product_name;

      })
      ->addColumn('warehouses', function($row) {
        $BL = DB::select(" select * from dataset_business_location where id=".(@$row->business_location_id_fk?$row->business_location_id_fk:0)." ");
        $sBranchs = DB::select(" select * from branchs where id=".(@$row->branch_id_fk?$row->branch_id_fk:0)." ");
        $warehouse = DB::select(" select * from warehouse where id=".(@$row->warehouse_id_fk?$row->warehouse_id_fk:0)." ");
        return @$BL[0]->txt_desc.'/'.@$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name;
      })
      ->make(true);
    }

    public function DatatableDashboard(Request $req){
      // $sTable = \App\Models\Backend\Stock_notify::where('amt_less','!=',0)->orderBy('product_id_fk', 'asc');
      $sTable = DB::select(" SELECT *  FROM db_stocks_notify WHERE amt_less > 0 OR amt_day_before_expired > 0 order by updated_at desc ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('diff_d', function($row) {
          $D = DB::select("SELECT timestampdiff(DAY,now(),lot_expired_date) as Day  FROM db_stocks_notify
            WHERE id=".@$row->id." ");
         return @$D[0]->Day;
      })
      ->addColumn('diff_d02', function($row) {
          $D = DB::select("SELECT timestampdiff(DAY,now(),lot_expired_date) - amt_day_before_expired as Day  FROM db_stocks_notify
            WHERE id=".@$row->id." ");
         return @$D[0]->Day;
      })
      ->addColumn('product_name', function($row) {
          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".@$row->product_id_fk." AND lang_id=1");

         return @$Products[0]->product_code." : ".@$Products[0]->product_name;

      })
      ->addColumn('warehouses', function($row) {
        $BL = DB::select(" select * from dataset_business_location where id=".(@$row->business_location_id_fk?$row->business_location_id_fk:0)." ");
        $sBranchs = DB::select(" select * from branchs where id=".(@$row->branch_id_fk?$row->branch_id_fk:0)." ");
        $warehouse = DB::select(" select * from warehouse where id=".(@$row->warehouse_id_fk?$row->warehouse_id_fk:0)." ");
        return @$BL[0]->txt_desc.'/'.@$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name;
      })
      ->make(true);
    }

}
