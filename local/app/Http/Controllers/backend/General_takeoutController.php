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


       $sPermission = @\Auth::user()->permission ;
       $User_branch_id = @\Auth::user()->branch_id_fk;

        if(@\Auth::user()->permission==1){

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1 ");

        }else{

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1 AND products.id in (SELECT product_id_fk FROM db_stocks WHERE branch_id_fk='$User_branch_id')
            ORDER BY products.product_code");

        }


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
       $ref_code = "CODE".$sRow->id;
       $Product_out_cause = \App\Models\Backend\Product_out_cause::get();
       $Recipient  = DB::select(" select * from ck_users_admin where id=".$sRow->recipient." ");

       $sPermission = @\Auth::user()->permission ;
       $User_branch_id = @\Auth::user()->branch_id_fk;

        if(@\Auth::user()->permission==1){

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1 ");

        }else{

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1 AND products.id in (SELECT product_id_fk FROM db_stocks WHERE branch_id_fk='$User_branch_id')
            ORDER BY products.product_code");

        }

      $sBusiness_location = \App\Models\Backend\Business_location::get();
      // dd($sBusiness_location);


      $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();
      $Check_stock = \App\Models\Backend\Check_stock::get();
      // dd($Check_stock);
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
           'ref_code'=>$ref_code,

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
          $sRow->stocks_id_fk    = request('stocks_id_fk');

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
              // DB::select(" UPDATE db_stocks SET amt = (amt - ".request('amt')." ) WHERE product_id_fk = ".request('product_id_fk')." AND lot_number='".request('lot_number')."' AND lot_expired_date='".request('lot_expired_date')."' ");
              DB::select(" UPDATE db_stocks SET amt = (amt - ".request('amt')." ) WHERE id = ".request('stocks_id_fk')." ");

                 $insertStockMovement = new  AjaxController();

            // ดึงจากตารางนำสินค้าออกทั่วไป > db_general_takeout
                $Data = DB::select("
                        SELECT db_general_takeout.business_location_id_fk,CONCAT('CODE',db_general_takeout.id) as doc_no,db_general_takeout.created_at as doc_date,branch_id_fk,
                        db_general_takeout.product_id_fk, db_general_takeout.lot_number, lot_expired_date, db_general_takeout.amt,2 as 'in_out',product_unit_id_fk,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,approve_status as status,
                        concat('นำสินค้าออกทั่วไป ',dataset_product_out_cause.txt_desc) as note, db_general_takeout.created_at as dd,
                        db_general_takeout.recipient as action_user,db_general_takeout.approver as approver,db_general_takeout.updated_at as approve_date
                        FROM
                        db_general_takeout
                        left Join dataset_product_out_cause ON db_general_takeout.product_out_cause_id_fk = dataset_product_out_cause.id
                  ");

                  foreach ($Data as $key => $value) {

                       $insertData = array(
                          "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                          "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
                          "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                          "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                          "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                          "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                          "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
                          "amt" =>  @$value->amt?$value->amt:0,
                          "in_out" =>  @$value->in_out?$value->in_out:0,
                          "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,

                          "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
                          "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
                          "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
                          "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,

                          "status" =>  @$value->status?$value->status:0,
                          "note" =>  @$value->note?$value->note:NULL,

                          "action_user" =>  @$value->action_user?$value->action_user:NULL,
                          "action_date" =>  @$value->action_date?$value->action_date:NULL,
                          "approver" =>  @$value->approver?$value->approver:NULL,
                          "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                          "created_at" =>@$value->dd?$value->dd:NULL
                      );

                $insertStockMovement->insertStockMovement($insertData);

            }

              DB::select(" INSERT IGNORE INTO db_stock_movement SELECT * FROM db_stock_movement_tmp ORDER BY doc_date asc ");

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

            $sTable = \App\Models\Backend\General_takeout::search()->orderBy('id', 'desc');

        }else{

           $sTable = \App\Models\Backend\General_takeout::where('branch_id_fk',$User_branch_id)->orderBy('id', 'desc');

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
      ->addColumn('pickup_date', function($row) {
        if(!empty($row->pickup_firstdate)){
          return $row->pickup_firstdate;
        }
      })
      ->addColumn('ref_code', function($row) {
          return 'CODE'.$row->id;
      })
      // ->addColumn('updated_at', function($row) {
      //   return is_null($row->updated_at) ? '-' : $row->updated_at;
      // })
      ->make(true);
    }


}
