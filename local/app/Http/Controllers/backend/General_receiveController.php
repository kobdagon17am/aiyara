<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use PDO;
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

       // $sPermission = @\Auth::user()->permission ;
       // $User_branch_id = @\Auth::user()->branch_id_fk;

       //  if(@\Auth::user()->permission==1){

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1 ORDER BY products.product_code ");

        // }else{

        //     $Products = DB::select("SELECT products.id as product_id,
        //     products.product_code,
        //     (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
        //     FROM
        //     products_details
        //     Left Join products ON products_details.product_id_fk = products.id
        //     WHERE lang_id=1 AND products.id in (SELECT product_id_fk FROM db_stocks WHERE branch_id_fk='$User_branch_id')
        //     ORDER BY products.product_code");

        // }


      $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
          return $query->where('id', auth()->user()->business_location_id_fk);
        })
        ->get();

      $User_branch_id = \Auth::user()->branch_id_fk;
      // dd(\Auth::user()->branch_id_fk);
      // $sBranchs = \App\Models\Backend\Branchs::whereIn('business_location_id_fk', $sBusiness_location->pluck('id'))->get();
      $sBranchs = \App\Models\Backend\Branchs::get();

      // dd($sBranchs);

      // dd($Products);
      $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->where('status', 1)->get();

      if(@\Auth::user()->permission==1){
        $Warehouse = \App\Models\Backend\Warehouse::get();
      }else{
        $Warehouse = \App\Models\Backend\Warehouse::where('branch_id_fk',\Auth::user()->branch_id_fk)->get();
        // dd($Warehouse);
      }

      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();
      $sSupplier = \App\Models\Backend\Supplier::get();
      $Check_stock = \App\Models\Backend\Check_stock::get();
      $Product_status = \App\Models\Backend\Product_status::get();

      return View('backend.general_receive.form')->with(
        array(
           'Product_in_cause'=>$Product_in_cause,
           'Products'=>$Products,
           'sProductUnit'=>$sProductUnit,
           'Warehouse'=>$Warehouse,
           'Zone'=>$Zone,
           'Shelf'=>$Shelf,
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

       $sPermission = @\Auth::user()->permission ;
       $User_branch_id = @\Auth::user()->branch_id_fk;

        // if(@\Auth::user()->permission==1){

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1 ");

        // }else{

        //     $Products = DB::select("SELECT products.id as product_id,
        //     products.product_code,
        //     (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
        //     FROM
        //     products_details
        //     Left Join products ON products_details.product_id_fk = products.id
        //     WHERE lang_id=1 AND products.id in (SELECT product_id_fk FROM db_stocks WHERE branch_id_fk='$User_branch_id')
        //     ORDER BY products.product_code");

        // }


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
          $sRow->product_id_fk    = request('product_id_fk');
          $sRow->product_status_id_fk    = request('product_status_id_fk');
          
          if(request('product_in_cause_id_fk')==4){
              $sRow->description    = request('description');
          }else{
              $description = DB::select("SELECT * FROM `dataset_product_status` where id=".request('product_status_id_fk')."");
              $sRow->description    = $description[0]->txt_desc;
          }

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
          $sRow->action_date    = date('Y-m-d H:i:s');

          $sRow->approver    = request('approver');
          $sRow->approve_status    = request('approve_status')?request('approve_status'):0;
          $sRow->approve_date    = date('Y-m-d H:i:s');

          $sRow->created_at = date('Y-m-d H:i:s');


          $sRow->save();


          $stock_type_id_fk = 3 ; // `dataset_stock_type` 3 รับสินค้าเข้าทั่วไป
          $ref_table_id = $sRow->id ;
          // รหัสอ้างอิงเอกสาร (แสดงรหัสอ้างอิงในตารางที่เชื่อมโยงกันทุกตาราง) REF+BL+branch+stock_type_id_fk+(yy+mm+dd)+ref_table_id
          $ref_doc = 'REF'.$sRow->business_location_id_fk.$sRow->branch_id_fk.$stock_type_id_fk.date('ymd').$ref_table_id;
          DB::select(" UPDATE `db_general_receive` SET ref_doc='".$ref_doc."' WHERE id=".$sRow->id." ");

          // `approve_status` int(11) DEFAULT '0' COMMENT '1=อนุมัติแล้ว',
        if(request('approve_status')=='1'){

// นำเข้า Stock 
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

                     $lastID = DB::getPdo()->lastInsertId();

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

                    $lastID = DB::table('db_stocks')->latest()->first();
                }

        // $insertStockMovement = new  AjaxController();

        // ดึงจากตารางรับเข้า > db_general_receive
        // สคริปต์เดิม กวาดมาทั้งหมด 

        /*
        $Data = DB::select("
                SELECT db_general_receive.business_location_id_fk,
                (
                CASE WHEN loan_ref_number='-' or loan_ref_number is null THEN CONCAT('CODE',db_general_receive.id) ELSE loan_ref_number END
                ) as doc_no
                ,db_general_receive.created_at as doc_date,branch_id_fk,
                db_general_receive.product_id_fk, db_general_receive.lot_number, lot_expired_date, db_general_receive.amt,1 as 'in_out',product_unit_id_fk,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,approve_status as status,
                concat('รับเข้า ',dataset_product_in_cause.txt_desc) as note, db_general_receive.created_at as dd,
                db_general_receive.recipient as action_user,db_general_receive.approver as approver,db_general_receive.updated_at as approve_date,db_general_receive.description  as note2,
                (CASE WHEN product_status_id_fk=2 THEN 'สินค้าชำรุดเสียหาย' ELSE '' END) as note3
                FROM
                db_general_receive
                left Join dataset_product_in_cause ON db_general_receive.product_in_cause_id_fk = dataset_product_in_cause.id
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
                  "note2" =>  (@$value->note2?$value->note2:NULL).' '.(@$value->note3?$value->note3:NULL),

                  "action_user" =>  @$value->action_user?$value->action_user:NULL,
                  "action_date" =>  @$value->action_date?$value->action_date:NULL,
                  "approver" =>  @$value->approver?$value->approver:NULL,
                  "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

                  "created_at" =>@$value->dd?$value->dd:NULL
              );

                $insertStockMovement->insertStockMovement($insertData);

            }

             DB::select(" INSERT IGNORE INTO db_stock_movement SELECT * FROM db_stock_movement_tmp ORDER BY doc_date asc ");
  */


                // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@2

                /*
                เปลี่ยนใหม่ นำเข้าเฉพาะรายการที่มีการอนุมัติอันล่าสุดเท่านั้น
                นำเข้า Stock movement => กรองตาม 4 ฟิลด์ที่สร้างใหม่ stock_type_id_fk,stock_id_fk,ref_table_id,ref_doc
                1 จ่ายสินค้าตามใบเบิก 26
                2 จ่ายสินค้าตามใบเสร็จ  27
                3 รับสินค้าเข้าทั่วไป 28
                4 รับสินค้าเข้าตาม PO 29
                5 นำสินค้าออก 30
                6 สินค้าเบิก-ยืม  31
                7 โอนภายในสาขา  32
                8 โอนระหว่างสาขา  33
                */
                $stock_type_id_fk = 3 ;
                $stock_id_fk = $lastID ;
                $ref_table = 'db_general_receive' ;
                $ref_table_id = $sRow->id ;
                $ref_doc = $sRow->ref_doc;

                $value=DB::table('db_stock_movement')
                ->where('stock_type_id_fk', @$stock_type_id_fk?$stock_type_id_fk:0 )
                ->where('stock_id_fk', @$stock_id_fk?$stock_id_fk:0 )
                ->where('ref_table_id', @$ref_table_id?$ref_table_id:0 )
                ->where('ref_doc', @$ref_doc?$ref_doc:NULL )
                ->get();

                if($value->count() == 0){

                      DB::table('db_stock_movement')->insert(array(
                          "stock_type_id_fk" =>  @$stock_type_id_fk?$stock_type_id_fk:0,
                          "stock_id_fk" =>  @$stock_id_fk?$stock_id_fk:0,
                          "ref_table" =>  @$ref_table?$ref_table:0,
                          "ref_table_id" =>  @$ref_table_id?$ref_table_id:0,
                          "ref_doc" =>  @$ref_doc?$ref_doc:NULL,
                          // "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                          "doc_date" =>  $sRow->created_at,
                          "business_location_id_fk" =>  @$sRow->business_location_id_fk?$sRow->business_location_id_fk:0,
                          "branch_id_fk" =>  @$sRow->branch_id_fk?$sRow->branch_id_fk:0,
                          "product_id_fk" =>  @$sRow->product_id_fk?$sRow->product_id_fk:0,
                          "lot_number" =>  @$sRow->lot_number?$sRow->lot_number:NULL,
                          "lot_expired_date" =>  @$sRow->lot_expired_date?$sRow->lot_expired_date:NULL,
                          "amt" =>  @$sRow->amt?$sRow->amt:0,
                          "in_out" =>  1,
                          "product_unit_id_fk" =>  @$sRow->product_unit_id_fk?$sRow->product_unit_id_fk:0,

                          "warehouse_id_fk" =>  @$sRow->warehouse_id_fk?$sRow->warehouse_id_fk:0,
                          "zone_id_fk" =>  @$sRow->zone_id_fk?$sRow->zone_id_fk:0,
                          "shelf_id_fk" =>  @$sRow->shelf_id_fk?$sRow->shelf_id_fk:0,
                          "shelf_floor" =>  @$sRow->shelf_floor?$sRow->shelf_floor:0,

                          "status" =>  @$sRow->approve_status?$sRow->approve_status:0,
                          "note" =>  'รับสินค้าเข้าทั่วไป ',
                          "note2" =>  @$sRow->description?$sRow->description:NULL,

                          "action_user" =>  @$sRow->recipient?$sRow->recipient:NULL,
                          "action_date" =>  @$sRow->action_date?$sRow->action_date:NULL,
                          "approver" =>  @$sRow->approver?$sRow->approver:NULL,
                          "approve_date" =>  @$sRow->approve_date?$sRow->approve_date:NULL,

                          "created_at" =>@$sRow->created_at?$sRow->created_at:NULL
                      ));

                }
                // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@


        }


          \DB::commit();

          if(request('approve_status')=='1'){
            return redirect()->to(url("backend/general_receive"));
          }else{
            return redirect()->to(url("backend/general_receive/".$sRow->id."/edit"));
          }

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

            $sTable = \App\Models\Backend\General_receive::search()->orderBy('id', 'desc');

        }else{

           $sTable = \App\Models\Backend\General_receive::where('branch_id_fk',$User_branch_id)->where('recipient',@\Auth::user()->id)->orderBy('id', 'desc');

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
