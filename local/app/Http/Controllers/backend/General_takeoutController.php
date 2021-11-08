<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use App\Http\Controllers\backend\AjaxController;

class General_takeoutController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.general_takeout.index');

    }

 public function create()
    {
      $Product_out_cause = \App\Models\Backend\Product_out_cause::get();

      $Products = DB::select("

        SELECT products.id as product_id,
        products.product_code,
        (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
        FROM
        products_details
        Left Join products ON products_details.product_id_fk = products.id
        WHERE
        products.id in (SELECT product_id_fk FROM db_stocks WHERE business_location_id_fk=".@\Auth::user()->business_location_id_fk." AND branch_id_fk=".@\Auth::user()->branch_id_fk.")
        AND
        lang_id=1

            ");

      // dd($Products);

      $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
        return $query->where('id', auth()->user()->business_location_id_fk);
      })->get();

      // dd($Products);
      $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();
      $Check_stock = \App\Models\Backend\Check_stock::get();

      return View('backend.general_takeout.form')->with(
        array(
           'Product_out_cause'=>$Product_out_cause,
           'Products'=>$Products,
           'sProductUnit'=>$sProductUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,
           'Check_stock'=>$Check_stock,
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs,

        ) );
    }
    public function store(Request $request)
    {
      // dd($request->all());
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\General_takeout::find($id);
       $Product_out_cause = \App\Models\Backend\Product_out_cause::get();
       $Recipient  = DB::select(" select * from ck_users_admin where id=".$sRow->recipient." ");
       $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1");

      $sBusiness_location = \App\Models\Backend\Business_location::get();
      // dd($sBusiness_location);


      $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();
      $Check_stock = \App\Models\Backend\Check_stock::get();
      return View('backend.general_takeout.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id,
           'Product_out_cause'=>$Product_out_cause,
           'Recipient'=>$Recipient,
           'Products'=>$Products,
           'sProductUnit'=>$sProductUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,
           'Check_stock'=>$Check_stock,
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs,

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
            $sRow = \App\Models\Backend\General_takeout::find($id);
          }else{
            $sRow = new \App\Models\Backend\General_takeout;
          }

          $sRow->business_location_id_fk    = request('business_location_id_fk');
          $sRow->product_out_cause_id_fk    = request('product_out_cause_id_fk');
          $sRow->description    = request('description');
          $sRow->product_id_fk    = request('product_id_fk');
          $sRow->lot_number    = request('lot_number');
          $sRow->lot_expired_date    = request('lot_expired_date');
          $sRow->amt    = request('amt');
          $sRow->product_unit_id_fk    = request('product_unit_id_fk');

          $sRow->branch_id_fk    = request('branch_id_fk');
          $sRow->warehouse_id_fk    = request('warehouse_id_fk');
          $sRow->zone_id_fk    = request('zone_id_fk');
          $sRow->shelf_id_fk    = request('shelf_id_fk');
          $sRow->shelf_floor    = request('shelf_floor');

          $sRow->receive_person    = request('receive_person');

          $sRow->pickup_firstdate    = date('Y-m-d H:i:s');
          $sRow->recipient    = request('recipient');
          $sRow->approver    = request('approver');
          $sRow->approve_status    = request('approve_status');

          $sRow->created_at = date('Y-m-d H:i:s');

          // Check Stock อีกครั้งก่อน เพื่อดูว่าสินค้ายังมีพอให้ตัดหรือไม่
          //  $fnCheckStock = new  AjaxController();
          //  $r_check_stcok = $fnCheckStock->fnCheckStock(
          //   request('branch_id_fk'),
          //   request('product_id_fk'),
          //   request('amt'),
          //   request('lot_number'),
          //   request('lot_expired_date'),
          //   request('warehouse_id_fk'),
          //   request('zone_id_fk'),
          //   request('shelf_id_fk'),
          //   request('shelf_floor'));
          // // return $r_check_stcok;
          // if($r_check_stcok==0){
          //   return redirect()->to(url("backend/general_takeout/".$sRow->id."/edit"))->with(['alert'=>\App\Models\Alert::myTxt("สินค้าในคลังไม่เพียงพอ")]);
          // }


          $sRow->save();

          if(request('approve_status')=='1'){
              DB::select(" UPDATE db_stocks SET amt = (amt - ".request('amt')." ) WHERE product_id_fk = ".request('product_id_fk')." AND lot_number='".request('lot_number')."' AND lot_expired_date='".request('lot_expired_date')."' ");

              return redirect()->to(url("backend/general_takeout"));

          }


          \DB::commit();

           return redirect()->to(url("backend/general_takeout/".$sRow->id."/edit"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\General_takeoutController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\General_takeout::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){


       $sPermission = @\Auth::user()->permission ;
       $User_branch_id = @\Auth::user()->branch_id_fk;

        if(@\Auth::user()->permission==1){

            $sTable = \App\Models\Backend\General_takeout::search()->orderBy('id', 'asc');

        }else{

           $sTable = \App\Models\Backend\General_takeout::where('branch_id_fk',$User_branch_id)->orderBy('id', 'asc');

        }


      // $sTable = \App\Models\Backend\General_takeout::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {

          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$row->product_id_fk." AND lang_id=1");

        return @$Products[0]->product_code." : ".@$Products[0]->product_name;

      })
      ->addColumn('recipient_name', function($row) {
        $sD = DB::select(" select * from ck_users_admin where id=".$row->recipient." ");
        return $sD[0]->name;
      })
      ->addColumn('product_out_cause', function($row) {
        $d = \App\Models\Backend\Product_out_cause::find($row->product_out_cause_id_fk);
        return $d->txt_desc;
      })
      ->addColumn('lot_expired_date', function($row) {
        $d = strtotime($row->lot_expired_date);
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('pickup_date', function($row) {
        $d = strtotime($row->pickup_firstdate);
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
