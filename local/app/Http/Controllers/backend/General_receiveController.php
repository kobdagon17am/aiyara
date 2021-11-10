<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use App\Http\Controllers\backend\AjaxController;


class General_receiveController extends Controller
{

    public function index(Request $request)
    {

      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

        return View('backend.general_receive.index')->with(
        array(
           'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id
        ) );

    }

 public function create()
    {
      $Product_in_cause  = DB::select(" SELECT * FROM `dataset_product_in_cause` where id not in(1)  ");
      $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1");

      $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
          return $query->where('id', auth()->user()->business_location_id_fk);
        })
        ->get();

      $User_branch_id = \Auth::user()->branch_id_fk;
      // $sBranchs = \App\Models\Backend\Branchs::whereIn('business_location_id_fk', $sBusiness_location->pluck('id'))->get();
      $sBranchs = \App\Models\Backend\Branchs::get();

      // dd($sBranchs);

      // dd($Products);
      $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();
      $sSupplier = \App\Models\Backend\Supplier::get();
      $Check_stock = \App\Models\Backend\Check_stock::get();
      $Product_status = \App\Models\Backend\Product_status::get();

      return View('backend.general_receive.form')->with(
        array(
           'Product_in_cause'=>$Product_in_cause,
           'Products'=>$Products,
           'sProductUnit'=>$sProductUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,
           'sBranchs'=>$sBranchs,
           'User_branch_id'=>$User_branch_id,
           'sSupplier'=>$sSupplier,
           'sBusiness_location'=>$sBusiness_location,
           'Check_stock'=>$Check_stock,
           'Product_status'=>$Product_status,

        ) );
    }
    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\General_receive::find($id);
       // $Product_in_cause = \App\Models\Backend\Product_in_cause::get();
       // dd($Product_in_cause);
       $Product_in_cause  = DB::select(" SELECT * FROM `dataset_product_in_cause` where id not in(1)  ");
       $Recipient  = DB::select(" select * from ck_users_admin where id=".$sRow->recipient." ");
       $Approver  = DB::select(" select * from ck_users_admin where id=".$sRow->approver." ");
       $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1");

      $sBusiness_location = \App\Models\Backend\Business_location::get();

      $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();
      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBranchs = \App\Models\Backend\Branchs::get();
      $sSupplier = \App\Models\Backend\Supplier::get();
      $Check_stock = \App\Models\Backend\Check_stock::get();
      $Product_status = \App\Models\Backend\Product_status::get();

      // dd($Check_stock);

      return View('backend.general_receive.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id,
           'Product_in_cause'=>$Product_in_cause,
           'Recipient'=>$Recipient,
           'Products'=>$Products,
           'sProductUnit'=>$sProductUnit,
           'Warehouse'=>$Warehouse,
           'Zone'=>$Zone,
           'Shelf'=>$Shelf,
           'sSupplier'=>$sSupplier,
           'sBranchs'=>$sBranchs,
           'User_branch_id'=>$User_branch_id,
           'sBusiness_location'=>$sBusiness_location,
           'Check_stock'=>$Check_stock,
           'Product_status'=>$Product_status,
           'Approver'=>$Approver,
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
            $sRow = \App\Models\Backend\General_receive::find($id);
          }else{
            $sRow = new \App\Models\Backend\General_receive;
          }

          $sRow->business_location_id_fk    = request('business_location_id_fk');
          $sRow->product_in_cause_id_fk    = request('product_in_cause_id_fk');
          $sRow->description    = request('description');
          $sRow->product_id_fk    = request('product_id_fk');
          $sRow->product_status_id_fk    = request('product_status_id_fk');
          $sRow->loan_ref_number    = request('loan_ref_number');
          $sRow->lot_number    = request('lot_number');
          $sRow->lot_expired_date    = request('lot_expired_date');
          $sRow->amt    = request('amt');
          $sRow->product_unit_id_fk    = request('product_unit_id_fk');
          $sRow->supplier_id_fk    = request('supplier_id_fk');
          $sRow->branch_id_fk    = request('branch_id_fk');
          $sRow->warehouse_id_fk    = request('warehouse_id_fk');
          $sRow->zone_id_fk    = request('zone_id_fk');
          $sRow->shelf_id_fk    = request('shelf_id_fk');
          $sRow->shelf_floor    = request('shelf_floor');
          $sRow->delivery_person    = request('delivery_person');

          $sRow->pickup_firstdate    = date('Y-m-d H:i:s');
          $sRow->recipient    = request('recipient');
          $sRow->approver    = request('approver');
          $sRow->approve_status    = request('approve_status')?request('approve_status'):0;

          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


        if(request('approve_status')=='1'){

                $value=DB::table('db_stocks')
                ->where('business_location_id_fk', request('business_location_id_fk'))
                ->where('branch_id_fk', request('branch_id_fk'))
                ->where('product_id_fk', request('product_id_fk'))
                ->where('lot_number', request('lot_number'))
                ->where('lot_expired_date', request('lot_expired_date'))
                ->where('warehouse_id_fk', request('warehouse_id_fk'))
                ->where('zone_id_fk', request('zone_id_fk'))
                ->where('shelf_id_fk', request('shelf_id_fk'))
                ->where('shelf_floor', request('shelf_floor'))
                ->get();

                if($value->count() == 0){

                      DB::table('db_stocks')->insert(array(
                        'business_location_id_fk' => request('business_location_id_fk'),
                        'branch_id_fk' => request('branch_id_fk'),
                        'product_id_fk' => request('product_id_fk'),
                        'lot_number' => request('lot_number'),
                        'lot_expired_date' => request('lot_expired_date'),
                        'amt' => request('amt'),
                        'product_unit_id_fk' => request('product_unit_id_fk'),
                        'date_in_stock' => date("Y-m-d H:i:s"),
                        'warehouse_id_fk' => request('warehouse_id_fk'),
                        'zone_id_fk' => request('zone_id_fk'),
                        'shelf_id_fk' => request('shelf_id_fk'),
                        'shelf_floor' => request('shelf_floor'),
                        'created_at' => date("Y-m-d H:i:s"),
                      ));

                }else{

                    DB::table('db_stocks')
                    ->where('business_location_id_fk', request('business_location_id_fk'))
                    ->where('branch_id_fk', request('branch_id_fk'))
                    ->where('product_id_fk', request('product_id_fk'))
                    ->where('lot_number', request('lot_number'))
                    ->where('lot_expired_date', request('lot_expired_date'))
                    ->where('warehouse_id_fk', request('warehouse_id_fk'))
                    ->where('zone_id_fk', request('zone_id_fk'))
                    ->where('shelf_id_fk', request('shelf_id_fk'))
                    ->where('shelf_floor', request('shelf_floor'))
                      ->update(array(
                        'amt' => $value[0]->amt + request('amt'),
                      ));
                }

        $insertStockMovement = new  AjaxController();

                   // ดึงจากตารางรับเข้า > db_general_receive
        $Data = DB::select("
                SELECT db_general_receive.business_location_id_fk,
                (
                  CASE WHEN loan_ref_number='-' or loan_ref_number is null THEN CONCAT('CODE',db_general_receive.id) ELSE loan_ref_number END
                ) as doc_no
                ,db_general_receive.created_at as doc_date,branch_id_fk,
                db_general_receive.product_id_fk, db_general_receive.lot_number, lot_expired_date, db_general_receive.amt,1 as 'in_out',product_unit_id_fk,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,approve_status as status,
                concat('รับเข้า ',dataset_product_in_cause.txt_desc) as note, db_general_receive.created_at as dd,
                db_general_receive.recipient as action_user,db_general_receive.approver as approver,db_general_receive.updated_at as approve_date
                FROM
                db_general_receive
                left Join dataset_product_in_cause ON db_general_receive.product_in_cause_id_fk = dataset_product_in_cause.id
          ");

          foreach ($Data as $key => $value) {
/*
  `action_user` int(11) DEFAULT '0' COMMENT 'ผู้ดำเนินการ',
  `action_date` datetime DEFAULT NULL COMMENT 'วันทำการ',
  `approver` int(11) DEFAULT '0' COMMENT 'ผู้อนุมัติ',
  `approve_date` datetime DEFAULT NULL COMMENT 'วันเวลาอนุมัติ',
  `sender` int(11) DEFAULT '0' COMMENT 'ผู้จัดส่ง Ref>ck_users_admin>id',
  `sent_date` datetime DEFAULT NULL COMMENT 'วันที่ทำการจัดส่ง',
  `who_cancel` int(11) DEFAULT '0' COMMENT 'ผู้ยกเลิกการเบิกครั้งนี้ Ref>ck_users_admin>id',
  `cancel_date` datetime DEFAULT NULL COMMENT 'วันที่ทำการยกเลิก',
*/
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

        }


          \DB::commit();

           return redirect()->to(url("backend/general_receive/".$sRow->id."/edit"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\General_receiveController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\General_receive::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){

       $sPermission = @\Auth::user()->permission ;
       $User_branch_id = @\Auth::user()->branch_id_fk;

        if(@\Auth::user()->permission==1){

            $sTable = \App\Models\Backend\General_receive::search()->orderBy('id', 'asc');

        }else{

           $sTable = \App\Models\Backend\General_receive::where('branch_id_fk',$User_branch_id)->orderBy('id', 'asc');

        }

      // $sTable = \App\Models\Backend\General_receive::search()
      //   ->when(auth()->user()->permission !== 1, function ($query) {
      //     return $query->where('recipient', auth()->id());
      //   })
      //   ->orderBy('updated_at', 'desc');

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
      ->addColumn('product_in_cause', function($row) {
        if($row->product_in_cause_id_fk!=''){
          $d = \App\Models\Backend\Product_in_cause::find($row->product_in_cause_id_fk);
          return @$d->txt_desc;
        }else{
          return '';
        }

      })
      ->addColumn('pickup_date', function($row) {
        if(!empty($row->pickup_firstdate)){
          return $row->pickup_firstdate;
        }
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
