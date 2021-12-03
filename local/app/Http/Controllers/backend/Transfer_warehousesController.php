<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use App\Http\Controllers\backend\AjaxController;

class Transfer_warehousesController extends Controller
{

    public function index(Request $request)
    {

      // dd(\Auth::user()->permission .":". \Auth::user()->branch_id_fk.":". \Auth::user()->id);

       $sPermission = @\Auth::user()->permission ;
       $User_branch_id = @\Auth::user()->branch_id_fk;

        // if(@\Auth::user()->permission==1){

        //     $Products = DB::select("SELECT products.id as product_id,
        //     products.product_code,
        //     (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
        //     FROM
        //     products_details
        //     Left Join products ON products_details.product_id_fk = products.id
        //     WHERE lang_id=1 ");

        // }else{

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1 AND products.id in (SELECT product_id_fk FROM db_stocks WHERE branch_id_fk='$User_branch_id')
            ORDER BY products.product_code");

        // }

      $User_branch_id = \Auth::user()->branch_id_fk;
      // dd($User_branch_id);
      $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
        return $query->where('id', auth()->user()->branch_id_fk);
      })->get();

      // $Warehouse = \App\Models\Backend\Warehouse::where('id','1')->get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      // dd($Warehouse[0]->w_name);
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

      $sTransfer_chooseAll = \App\Models\Backend\Transfer_choose::where('action_user','=',(\Auth::user()->id))->get();
      // dd(\Auth::user()->id);
      // dd(count($sTransfer_chooseAll));
        $b_l = \App\Models\Backend\Branchs::where('id',$User_branch_id)->get();

        $business_location_id_fk = $b_l[0]->business_location_id_fk;


      // dd($sTransfer_chooseAll);
      $sTransfer_choose = \App\Models\Backend\Transfer_choose::where('warehouse_id_fk','=','0')->where('action_user','=',(\Auth::user()->id))->get();
      // dd($sTransfer_choose);

        return View('backend.transfer_warehouses.index')->with(
        array(
           'Products'=>$Products,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sTransfer_choose'=>$sTransfer_choose,'sTransfer_chooseAll'=>$sTransfer_chooseAll,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,
           'business_location_id_fk'=>$business_location_id_fk
        ) );

    }

 public function create()
    {
      $receive_id = \App\Models\Backend\General_receive::get();
      $Products = \App\Models\Backend\Products::get();
      $ProductsUnit = \App\Models\Backend\Product_unit::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();
      return View('backend.transfer_warehouses.form')->with(
        array(
           'receive_id'=>$receive_id,'Products'=>$Products,'ProductsUnit'=>$ProductsUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf
        ) );
    }
    public function store(Request $request)
    {

      // dd($request->all());

      if(isset($request->save_select_to_transfer)){
        // dd($request->all());

        for ($i=0; $i < count($request->id) ; $i++) {
            $Check_stock = \App\Models\Backend\Check_stock::find($request->id[$i]);
            // echo $Check_stock->product_id_fk;
            $sRow = new \App\Models\Backend\Transfer_choose;
            $sRow->branch_id_fk    = request('branch_id_select_to_transfer');
            $sRow->stocks_id_fk    = $Check_stock->id;
            $sRow->product_id_fk    = $Check_stock->product_id_fk;
            $sRow->lot_number    = $Check_stock->lot_number;
            $sRow->lot_expired_date    = $Check_stock->lot_expired_date;
            $sRow->amt    = request('amt_transfer')[$i];
            $sRow->product_unit_id_fk    = $Check_stock->product_unit_id_fk;
            $sRow->date_in_stock    = $Check_stock->date_in_stock;

// ส่วนนี้ยังไม่ได้นำเข้า ต้องรอให้เลือกสาขาปลายทางก่อน

            // $sRow->warehouse_id_fk    = $Check_stock->warehouse_id_fk;
            // $sRow->zone_id_fk    = $Check_stock->zone_id_fk;
            // $sRow->shelf_id_fk    = $Check_stock->shelf_id_fk;
            // $sRow->shelf_floor    = $Check_stock->shelf_floor;

            $sRow->action_user = \Auth::user()->id;
            $sRow->action_date = date('Y-m-d H:i:s');
            $sRow->created_at = date('Y-m-d H:i:s');
            if(!empty(request('amt_transfer')[$i])){
              $sRow->save();
            }
          }
          return redirect()->to(url("backend/transfer_warehouses"));

      }else if(isset($request->save_set_to_warehouse)){

        // dd($request->all());
              if($request->save_set_to_warehouse==1){
                DB::update("
                    UPDATE db_transfer_choose
                    SET branch_id_fk = ".$request->branch_id_set_to_warehouse.",
                     warehouse_id_fk = ".$request->warehouse_id_fk_c."  ,
                     zone_id_fk = ".$request->zone_id_fk_c."  ,
                     shelf_id_fk = ".$request->shelf_id_fk_c."  ,
                     shelf_floor = ".$request->shelf_floor_c."
                    where id=".$request->id_set_to_warehouse."
                ");
              }

              return redirect()->to(url("backend/transfer_warehouses"));

      }else if(isset($request->save_set_to_warehouse_e)){

        // dd($request->all());
              if($request->save_set_to_warehouse_e==1){
                DB::update("
                    UPDATE db_transfer_choose
                    SET branch_id_fk = ".$request->branch_id_set_to_warehouse_e.",
                     warehouse_id_fk = ".$request->warehouse_id_fk_c_e."  ,
                     zone_id_fk = ".$request->zone_id_fk_c_e."  ,
                     shelf_id_fk = ".$request->shelf_id_fk_c_e.",
                     shelf_floor = ".$request->shelf_floor_c_e."
                    where id=".$request->id_set_to_warehouse_e."
                ");
              }

              return redirect()->to(url("backend/transfer_warehouses"));

      }else if(isset($request->save_select_to_cancel)){

          DB::update(" UPDATE db_transfer_warehouses_code
            SET
            approve_status=2 , note = '".$request->note_to_cancel."'
            WHERE id=".$request->id_to_cancel." ; ");

          return redirect()->to(url("backend/transfer_warehouses"));

      }else{
         // dd($request->all());
        return $this->form();
      }

    }

    public function edit($id)
    {

        // dd($id);
        $sRow = \App\Models\Backend\Transfer_warehouses_code::find($id);
        // dd($sRow);

           $approver = DB::select(" select * from ck_users_admin where id=".$sRow->approver." ");
           $approver = @$approver[0]->name;


        return View('backend.transfer_warehouses.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id, 'approver'=>$approver
        ) );
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      if(!empty($request->approve_status) && $request->approve_status==1){
          // dd($request->approve_status);

        $rsWarehouses_details = DB::select("
           select * from db_transfer_warehouses_details where transfer_warehouses_code_id = ".$request->id." AND remark=1  ");
        $arr1 = [];
        foreach ($rsWarehouses_details as $key => $value) {
          array_push($arr1, $value->stocks_id_fk);
        }

        $arr1 = implode(",", $arr1);

        $rsStock = DB::select(" select * from  db_stocks  where id in (".$arr1.")  ");
              // dd($rsStock);
        foreach ($rsStock as $key => $value) {

             DB::update(" UPDATE db_transfer_warehouses_details SET stock_amt_before_up =".$value->amt." , stock_date_before_up = '".$value->date_in_stock."' 
            ,approver=".\Auth::user()->id." where transfer_warehouses_code_id = ".$request->id." and stocks_id_fk = ".$value->id." AND remark=1  ");

             DB::update(" UPDATE db_transfer_warehouses_details SET approver=".\Auth::user()->id." where transfer_warehouses_code_id = ".$request->id." and stocks_id_fk = ".$value->id." AND remark=2 ");

        }


/*
        
         $insertStockMovement = new  AjaxController();

         $rsWarehouses_details = DB::select("
           select * from db_transfer_warehouses_details where transfer_warehouses_code_id = ".$request->id." AND remark=1 ");
         // dd($rsWarehouses_details);

          $r = DB::select(" SELECT * FROM db_stocks where id =".$rsWarehouses_details[0]->stocks_id_fk."  ");
           // dd($r);

           if(@$r){

           DB::table('db_transfer_warehouses_details')
            ->where('transfer_warehouses_code_id', $request->id)
            ->where('stocks_id_fk', @$r[0]->id)
            ->where('remark', '2')
            ->update(array(
              'product_unit_id_fk' => @$r[0]->product_unit_id_fk,
              'warehouse_id_fk' => @$r[0]->warehouse_id_fk,
              'zone_id_fk' => @$r[0]->zone_id_fk,
              'shelf_id_fk' => @$r[0]->shelf_id_fk,
              'shelf_floor' => @$r[0]->shelf_floor,
              'updated_at' => date("Y-m-d H:i:s"),
            ));

          }


         foreach ($rsWarehouses_details as $key => $value) {

        
           $v=DB::table('db_stocks')
                // ->where('business_location_id_fk', request('business_location_id_fk'))
                ->where('branch_id_fk', $value->branch_id_fk)
                ->where('product_id_fk', $value->product_id_fk)
                ->where('lot_number', $value->lot_number)
                ->where('lot_expired_date', $value->lot_expired_date)
                ->where('warehouse_id_fk', $value->warehouse_id_fk)
                ->where('zone_id_fk', $value->zone_id_fk)
                ->where('shelf_id_fk', $value->shelf_id_fk)
                ->where('shelf_floor', $value->shelf_floor)
                ->get();

                if($v->count() == 0){

                  // ฝั่งรับเข้า
                      DB::table('db_stocks')->insert(array(
                        'business_location_id_fk' => @\Auth::user()->business_location_id_fk,
                        'branch_id_fk' => @$value->branch_id_fk,
                        'product_id_fk' => @$value->product_id_fk,
                        'lot_number' => @$value->lot_number,
                        'lot_expired_date' => @$value->lot_expired_date,
                        'amt' => @$value->amt,
                        'product_unit_id_fk' => @$value->product_unit_id_fk,
                        'warehouse_id_fk' => @$value->warehouse_id_fk,
                        'zone_id_fk' => @$value->zone_id_fk,
                        'shelf_id_fk' => @$value->shelf_id_fk,
                        'shelf_floor' => @$value->shelf_floor,
                        'created_at' => date("Y-m-d H:i:s"),
                      ));

                  // ฝั่งจ่ายออก
                     DB::update(" UPDATE db_stocks SET amt = (amt - ".$value->amt.") where id =".$value->stocks_id_fk."  ");

                }else{
                  // ฝั่งจ่ายออก
                     DB::update(" UPDATE db_stocks SET amt = (amt - ".$value->amt.") where id =".$value->stocks_id_fk."  ");
                }

         }

 

// มี 2 ฝั่ง ๆ นึง รับเข้า อีกฝั่ง จ่ายออก

                   // ดึงจาก การโอนภายในสาขา > db_transfer_warehouses_code > db_transfer_warehouses_details
                  $Data = DB::select("

                          SELECT
                          db_transfer_warehouses_code.business_location_id_fk,
                          db_transfer_warehouses_code.tr_number as doc_no,
                          db_transfer_warehouses_code.updated_at as doc_date,
                          db_transfer_warehouses_code.branch_id_fk,
                          db_transfer_warehouses_details.product_id_fk,
                          db_transfer_warehouses_details.lot_number,
                          db_transfer_warehouses_details.lot_expired_date,
                          db_transfer_warehouses_details.amt,
                          1 as 'in_out',
                          product_unit_id_fk,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,db_transfer_warehouses_code.approve_status as status,

                          'โอนภายในสาขา (ฝั่งรับเข้า) ' as note,
                          db_transfer_warehouses_code.updated_at as dd,
                          db_transfer_warehouses_details.action_user as action_user,db_transfer_warehouses_code.approver as approver,db_transfer_warehouses_code.approve_date as approve_date
                          FROM
                          db_transfer_warehouses_details
                          Left Join db_transfer_warehouses_code ON db_transfer_warehouses_details.transfer_warehouses_code_id = db_transfer_warehouses_code.id
                          where db_transfer_warehouses_details.remark = 1 

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

                       $Data2 = DB::select("

                          SELECT
                          db_transfer_warehouses_code.business_location_id_fk,
                          db_transfer_warehouses_code.tr_number as doc_no,
                          db_transfer_warehouses_code.updated_at as doc_date,
                          db_transfer_warehouses_code.branch_id_fk,
                          db_transfer_warehouses_details.product_id_fk,
                          db_transfer_warehouses_details.lot_number,
                          db_transfer_warehouses_details.lot_expired_date,
                          db_transfer_warehouses_details.amt,
                          2 as 'in_out',
                          product_unit_id_fk,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,db_transfer_warehouses_code.approve_status as status,

                          'โอนภายในสาขา (ฝั่งจ่ายออก) ' as note,
                          db_transfer_warehouses_code.updated_at as dd,
                          db_transfer_warehouses_details.action_user as action_user,db_transfer_warehouses_code.approver as approver,db_transfer_warehouses_code.approve_date as approve_date
                          FROM
                          db_transfer_warehouses_details
                          Left Join db_transfer_warehouses_code ON db_transfer_warehouses_details.transfer_warehouses_code_id = db_transfer_warehouses_code.id
                          where db_transfer_warehouses_details.remark = 2 

                    ");

                    foreach ($Data2 as $key => $value) {

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


 */
      }

     

      // dd();

      // dd($request->all());
      return $this->form($id);
    }

   public function form($id=NULL)
    {

      // dd(request('id'));

      $id=request('id');

      // dd($id);
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow1 = \App\Models\Backend\Transfer_warehouses_code::find($id);
          }else{
            $sRow1 = new \App\Models\Backend\Transfer_warehouses_code;
          }

          $sRow1->approver    = \Auth::user()->id;
          $sRow1->approve_status    = request('approve_status');
          $sRow1->note    = request('note');
          $sRow1->approve_date = date('Y-m-d H:i:s');

          $sRow1->save();
          // dd(request('approve_status'));

     // // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@2
          if(request('approve_status')=='1'){

                $rsWarehouses_details = DB::select("
                 select 
                  db_transfer_warehouses_details.*,db_transfer_warehouses_code.tr_number,db_transfer_warehouses_code.business_location_id_fk,db_transfer_warehouses_code.note
                  from db_transfer_warehouses_details 
                  LEFT JOIN db_transfer_warehouses_code ON db_transfer_warehouses_details.transfer_warehouses_code_id=db_transfer_warehouses_code.id 
                  where db_transfer_warehouses_details.transfer_warehouses_code_id = ".$id." AND remark=1 ");
           
               if(!empty($rsWarehouses_details)){

                 foreach ($rsWarehouses_details as $key => $sRow) {
              // ฝั่นโอนให้ ลบยอดคลังออก
                            DB::table('db_stocks')
                            ->where('id', $sRow->stocks_id_fk)
                              ->update(array(
                                'amt' => DB::raw( " amt - $sRow->amt " )
                              ));

                            $Stock = DB::table('db_stocks')
                            ->where('id', $sRow->stocks_id_fk)
                            ->get();

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
                                  $stock_type_id_fk = 7 ;
                                  $stock_id_fk = $sRow->stocks_id_fk;
                                  $ref_table = 'db_transfer_warehouses_code' ;
                                  $ref_table_id = $sRow->id ;
                                  // $ref_doc = $sRow->ref_doc;
                                  // $ref_doc = DB::select(" select * from `db_transfer_warehouses_code` WHERE id=".$sRow->id." ");
                                  // dd($ref_doc[0]->ref_doc);
                                  // $ref_doc = @$ref_doc[0]->tr_number;
                                  $ref_doc = $sRow->tr_number;
                                  // $General_takeout = \App\Models\Backend\General_takeout::find($sRow->id);
                                  // @$ref_doc = @$General_takeout[0]->ref_doc;

                                  $value=DB::table('db_stock_movement')
                                  ->where('stock_type_id_fk', @$stock_type_id_fk?$stock_type_id_fk:0 )
                                  ->where('stock_id_fk', @$stock_id_fk?$stock_id_fk:0 )
                                  ->where('ref_table_id', @$ref_table_id?$ref_table_id:0 )
                                  ->where('ref_doc', @$ref_doc?$ref_doc:NULL )
                                  ->get();

                                  if($value->count() == 0){

                                        // if(@$sRow->remark==1){
                                          $note = 'โอนภายในสาขา (ฝั่งโอน) ';
                                        // }else if(@$sRow->remark==2){
                                        //   $note = 'โอนภายในสาขา (ฝั่งรับโอน) ';
                                        // }

                                        DB::table('db_stock_movement')->insert(array(
                                            "stock_type_id_fk" =>  @$stock_type_id_fk?$stock_type_id_fk:0,
                                            "stock_id_fk" =>  @$stock_id_fk?$stock_id_fk:0,
                                            "ref_table" =>  @$ref_table?$ref_table:0,
                                            "ref_table_id" =>  @$ref_table_id?$ref_table_id:0,
                                            "ref_doc" =>  @$ref_doc?$ref_doc:NULL,
                                            // "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                                            "doc_date" =>  $sRow->created_at,
                                            "business_location_id_fk" =>  @$Stock[0]->business_location_id_fk?$Stock[0]->business_location_id_fk:0,
                                            "branch_id_fk" =>  @$Stock[0]->branch_id_fk?$Stock[0]->branch_id_fk:0,
                                            "product_id_fk" =>  @$sRow->product_id_fk?$sRow->product_id_fk:0,
                                            "lot_number" =>  @$sRow->lot_number?$sRow->lot_number:NULL,
                                            "lot_expired_date" =>  @$sRow->lot_expired_date?$sRow->lot_expired_date:NULL,
                                            "amt" =>  @$sRow->amt?$sRow->amt:0,
                                            "in_out" =>  '2',
                                            "product_unit_id_fk" =>  @$sRow->product_unit_id_fk?$sRow->product_unit_id_fk:0,

                                            "warehouse_id_fk" =>  @$Stock[0]->warehouse_id_fk?$Stock[0]->warehouse_id_fk:0,
                                            "zone_id_fk" =>  @$Stock[0]->zone_id_fk?$Stock[0]->zone_id_fk:0,
                                            "shelf_id_fk" =>  @$Stock[0]->shelf_id_fk?$Stock[0]->shelf_id_fk:0,
                                            "shelf_floor" =>  @$Stock[0]->shelf_floor?$Stock[0]->shelf_floor:0,

                                            "status" =>  '1',
                                            "note" =>  $note,
                                            "note2" =>  @$sRow->description?$sRow->description:NULL,

                                            "action_user" =>  @$sRow->action_user?$sRow->action_user:NULL,
                                            "action_date" =>  @$sRow->created_at?$sRow->created_at:NULL,
                                            "approver" =>  @$sRow->approver?$sRow->approver:NULL,
                                            "approve_date" =>  @$sRow->updated_at?$sRow->updated_at:NULL,

                                            "created_at" =>@$sRow->created_at?$sRow->created_at:NULL
                                        ));

                                   }


                 }

               }


                $rsWarehouses_details = DB::select("
                 select 
                  db_transfer_warehouses_details.*,db_transfer_warehouses_code.tr_number,db_transfer_warehouses_code.business_location_id_fk,db_transfer_warehouses_code.note
                  from db_transfer_warehouses_details 
                  LEFT JOIN db_transfer_warehouses_code ON db_transfer_warehouses_details.transfer_warehouses_code_id=db_transfer_warehouses_code.id 
                  where db_transfer_warehouses_details.transfer_warehouses_code_id = ".$id." AND remark=2 ");
           
               if(!empty($rsWarehouses_details)){


                     foreach ($rsWarehouses_details as $key => $sRow) {

      // ฝั่นรับโอน ถ้ามี บวกยอดคลังเข้า ถ้ายังไม่มี สร้างรายการคลังเพิ่ม 
                      $value=DB::table('db_stocks')
                      ->where('business_location_id_fk', $sRow->business_location_id_fk )
                      ->where('branch_id_fk', $sRow->branch_id_fk )
                      ->where('product_id_fk', $sRow->product_id_fk )
                      ->where('lot_number', $sRow->lot_number )
                      ->where('lot_expired_date', $sRow->lot_expired_date )
                      ->where('warehouse_id_fk', $sRow->warehouse_id_fk )
                      ->where('zone_id_fk', $sRow->zone_id_fk )
                      ->where('shelf_id_fk', $sRow->shelf_id_fk )
                      ->where('shelf_floor', $sRow->shelf_floor )
                      ->get();

                      if($value->count() > 0){

                          DB::table('db_stocks')
                          ->where('business_location_id_fk', $sRow->business_location_id_fk)
                          ->where('branch_id_fk', $sRow->branch_id_fk)
                          ->where('product_id_fk', $sRow->product_id_fk)
                          ->where('lot_number', $sRow->lot_number)
                          ->where('lot_expired_date', $sRow->lot_expired_date)
                          ->where('warehouse_id_fk', $sRow->warehouse_id_fk)
                          ->where('zone_id_fk', $sRow->zone_id_fk)
                          ->where('shelf_id_fk', $sRow->shelf_id_fk)
                          ->where('shelf_floor', $sRow->shelf_floor)
                            ->update(array(
                             'amt' => DB::raw( " amt + $sRow->amt " )
                            ));
                          // $lastID = DB::table('db_stocks')->latest()->first();
                          // $lastID = $lastID->id;
                      }else{

                           DB::table('db_stocks')->insert(array(
                              'business_location_id_fk' => $sRow->business_location_id_fk,
                              'branch_id_fk' => $sRow->branch_id_fk,
                              'product_id_fk' => $sRow->product_id_fk,
                              'lot_number' => $sRow->lot_number,
                              'lot_expired_date' => $sRow->lot_expired_date,
                              'amt' => $sRow->amt,
                              'product_unit_id_fk' => $sRow->product_unit_id_fk,
                              'date_in_stock' => date("Y-m-d H:i:s"),
                              'warehouse_id_fk' => $sRow->warehouse_id_fk,
                              'zone_id_fk' => $sRow->zone_id_fk,
                              'shelf_id_fk' => $sRow->shelf_id_fk,
                              'shelf_floor' => $sRow->shelf_floor,
                              'created_at' => date("Y-m-d H:i:s"),
                            ));

                      }


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
                                  $stock_type_id_fk = 7 ;
                                  $stock_id_fk = $sRow->stocks_id_fk;
                                  $ref_table = 'db_transfer_warehouses_code' ;
                                  $ref_table_id = $sRow->id ;
                                  // $ref_doc = $sRow->ref_doc;
                                  // $ref_doc = DB::select(" select * from `db_transfer_warehouses_code` WHERE id=".$sRow->id." ");
                                  // dd($ref_doc[0]->ref_doc);
                                  // $ref_doc = @$ref_doc[0]->tr_number;
                                  $ref_doc = $sRow->tr_number;
                                  // $General_takeout = \App\Models\Backend\General_takeout::find($sRow->id);
                                  // @$ref_doc = @$General_takeout[0]->ref_doc;

                                  $value=DB::table('db_stock_movement')
                                  ->where('stock_type_id_fk', @$stock_type_id_fk?$stock_type_id_fk:0 )
                                  ->where('stock_id_fk', @$stock_id_fk?$stock_id_fk:0 )
                                  ->where('ref_table_id', @$ref_table_id?$ref_table_id:0 )
                                  ->where('ref_doc', @$ref_doc?$ref_doc:NULL )
                                  ->get();

                                  if($value->count() == 0){

                                        // if(@$sRow->remark==1){
                                        //   $note = 'โอนภายในสาขา (ฝั่งโอน) ';
                                        // }else if(@$sRow->remark==2){
                                          $note = 'โอนภายในสาขา (ฝั่งรับโอน) ';
                                        // }

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
                                            "in_out" =>  '1',
                                            "product_unit_id_fk" =>  @$sRow->product_unit_id_fk?$sRow->product_unit_id_fk:0,

                                            "warehouse_id_fk" =>  @$sRow->warehouse_id_fk?$sRow->warehouse_id_fk:0,
                                            "zone_id_fk" =>  @$sRow->zone_id_fk?$sRow->zone_id_fk:0,
                                            "shelf_id_fk" =>  @$sRow->shelf_id_fk?$sRow->shelf_id_fk:0,
                                            "shelf_floor" =>  @$sRow->shelf_floor?$sRow->shelf_floor:0,

                                            "status" =>  '1',
                                            "note" =>  $note,
                                            "note2" =>  @$sRow->description?$sRow->description:NULL,

                                            "action_user" =>  @$sRow->action_user?$sRow->action_user:NULL,
                                            "action_date" =>  @$sRow->created_at?$sRow->created_at:NULL,
                                            "approver" =>  @$sRow->approver?$sRow->approver:NULL,
                                            "approve_date" =>  @$sRow->updated_at?$sRow->updated_at:NULL,

                                            "created_at" =>@$sRow->created_at?$sRow->created_at:NULL
                                        ));

                                   }



                   }

               }


        
          }
                // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@


          \DB::commit();

           // return redirect()->to(url("backend/transfer_warehouses/".$id."/edit?list_id=".$id.""));
           return redirect()->to(url("backend/transfer_warehouses"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Transfer_warehousesController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Transfer_warehouses::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){

       $sPermission = @\Auth::user()->permission ;
       $User_branch_id = @\Auth::user()->branch_id_fk;

       if(@\Auth::user()->permission==1){

        $sTable = \App\Models\Backend\Transfer_warehouses::where('remark','2')->search()->orderBy('id', 'asc'); // remark '1=ฝั่งโอนให้ , 2=ฝั่งรับโอน'

    }else{

       // $sTable = \App\Models\Backend\Products_borrow::where('branch_id_fk',$User_branch_id)->orderBy('id', 'asc');
        $sTable = \App\Models\Backend\Transfer_warehouses::where('branch_id_fk',$User_branch_id)->where('remark','2')->search()->orderBy('id', 'asc'); // remark '1=ฝั่งโอนให้ , 2=ฝั่งรับโอน'


    }


      // $sTable = \App\Models\Backend\Transfer_warehouses::where('remark','1')->search()->orderBy('id', 'asc'); // remark '1=ฝั่งโอนให้ , 2=ฝั่งรับโอน'


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

      ->addColumn('warehouses', function($row) {
        $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.$row->shelf_floor;
      })
      ->addColumn('amt_in_warehouse', function($row) {
        // $Check_stock = \App\Models\Backend\Check_stock::where('id',$row->stock_id_fk);
        if($row->stocks_id_fk!=''){
          $Check_stock = DB::select(" select * from db_stocks where id=".$row->stocks_id_fk." ");
          return @$Check_stock[0]->amt;
        }

      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
