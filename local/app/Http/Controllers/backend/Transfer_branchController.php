<?php

namespace App\Http\Controllers\backend;

use DB;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\backend\AjaxController;
use App\Models\Backend\RequisitionBetweenBranch;
use App\Models\Backend\RequisitionBetweenBranchDetail;

class Transfer_branchController extends Controller
{

    public function index(Request $request)
    {
        $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
          return $query->where('id', auth()->user()->business_location_id_fk);
        })->get();


       $sPermission = @\Auth::user()->permission ;
       $User_branch_id = @\Auth::user()->branch_id_fk;

        // if(@\Auth::user()->permission==1){

        //     $Products = DB::select("SELECT products.id as product_id,
        //     products.product_code,
        //     (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
        //     FROM
        //     products_details
        //     Left Join products ON products_details.product_id_fk = products.id
        //     WHERE lang_id=1 AND products.id in (SELECT product_id_fk FROM db_stocks)
        //     ORDER BY products.product_code ");

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

        $sPermission  = \Auth::user()->permission ;
        $User_branch_id = \Auth::user()->branch_id_fk;
        $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
          return $query->where('id', auth()->user()->branch_id_fk);
        })->get();
        $Warehouse = \App\Models\Backend\Warehouse::get();

        // $toBranchs = \App\Models\Backend\Branchs::get();
        // dd($User_branch_id);
        if($sPermission==1){
          $toBranchs = \App\Models\Backend\Branchs::get();
        }else{
          $User_branch_id = @$User_branch_id?$User_branch_id:0;
          $toBranchs = DB::select("SELECT * from branchs where id not in($User_branch_id) ");
        }
        
        // dd($toBranchs);

        $Zone = \App\Models\Backend\Zone::get();
        $Shelf = \App\Models\Backend\Shelf::get();

        $Transfer_branch_status = \App\Models\Backend\Transfer_branch_status::get();

        $sTransfer_chooseAll = \App\Models\Backend\Transfer_choose_branch::where('action_user','=',(\Auth::user()->id))->get();
        // dd(count($sTransfer_chooseAll));

        // $sTransfer_choose = \App\Models\Backend\Transfer_choose_branch::where('branch_id_fk_to','0')->where('action_user','=',\Auth::user()->id)->get();
        $sTransfer_choose = \App\Models\Backend\Transfer_choose_branch::where('branch_id_fk_to','!=','0')->where('action_user','=',\Auth::user()->id)->get();
        // dd(count($sTransfer_choose));
        // dd($User_branch_id);
        // dd($sTransfer_chooseAll);
        $b_l = \App\Models\Backend\Branchs::where('id',$User_branch_id)->get();
        // dd($b_l[0]->business_location_id_fk);
        $business_location_id_fk = $b_l[0]->business_location_id_fk;

        $tr_number = DB::select(" SELECT tr_number FROM `db_transfer_branch_code` where branch_id_fk=".(\Auth::user()->branch_id_fk)." ");

        // dd($tr_number);

        $sAction_user = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");

        return View('backend.transfer_branch.index')->with(
        array(
           'Products'=>$Products,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sTransfer_choose'=>$sTransfer_choose,'sTransfer_chooseAll'=>$sTransfer_chooseAll,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,
           'business_location_id_fk'=>$business_location_id_fk,
           'sBusiness_location'=>$sBusiness_location,
           'Transfer_branch_status'=>$Transfer_branch_status,
           'tr_number'=>$tr_number,
           'sAction_user'=>$sAction_user,
           'sPermission'=>$sPermission,
           'toBranchs'=>$toBranchs,

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
      $sPermission  = \Auth::user()->permission ;
      return View('backend.transfer_branch.form')->with(
        array(
           'receive_id'=>$receive_id,'Products'=>$Products,'ProductsUnit'=>$ProductsUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,
           'Shelf'=>$Shelf,
           'sPermission'=>$sPermission,
        ) );
    }
    public function store(Request $request)
    {
      if(isset($request->save_select_to_transfer)){
        // dd($request->all());

        for ($i=0; $i < count($request->id) ; $i++) {
            $Check_stock = \App\Models\Backend\Check_stock::find($request->id[$i]);
            // echo $Check_stock->product_id_fk;
            $sRow = new \App\Models\Backend\Transfer_choose_branch;
            $sRow->branch_id_fk    = request('branch_id_select_to_transfer');
            $sRow->branch_id_fk_to    = request('branch_id_select_to_transfer_to');
            $sRow->stocks_id_fk    = $Check_stock->id;
            $sRow->product_id_fk    = $Check_stock->product_id_fk;
            $sRow->lot_number    = $Check_stock->lot_number;
            $sRow->lot_expired_date    = $Check_stock->lot_expired_date;
            $sRow->amt    = request('amt_transfer')[$i];
            $sRow->product_unit_id_fk    = $Check_stock->product_unit_id_fk;
            $sRow->date_in_stock    = $Check_stock->date_in_stock;
            $sRow->warehouse_id_fk    = $Check_stock->warehouse_id_fk;
            $sRow->zone_id_fk    = $Check_stock->zone_id_fk;
            $sRow->shelf_id_fk    = $Check_stock->shelf_id_fk;
            $sRow->shelf_floor    = $Check_stock->shelf_floor;
            $sRow->action_user = \Auth::user()->id;
            $sRow->action_date = date('Y-m-d H:i:s');
            $sRow->created_at = date('Y-m-d H:i:s');
            if(!empty(request('amt_transfer')[$i])){
              $sRow->save();
            }
          }

          DB::update(" update db_transfer_choose_branch set branch_id_fk_to=".request('branch_id_select_to_transfer_to')." ");

          return redirect()->to(url("backend/transfer_branch"));

      }else if(isset($request->save_set_to_warehouse)){

        // dd($request->all());
              if($request->save_set_to_warehouse==1){
                // DB::update("
                //     UPDATE db_transfer_choose_branch
                //     SET branch_id_fk = ".$request->branch_id_set_to_warehouse.",
                //      warehouse_id_fk = ".$request->warehouse_id_fk_c."  ,
                //      zone_id_fk = ".$request->zone_id_fk_c."  ,
                //      shelf_id_fk = ".$request->shelf_id_fk_c."
                //     where id=".$request->id_set_to_warehouse."
                // ");
                DB::update("
                    UPDATE db_transfer_choose_branch
                    SET branch_id_fk_to = ".$request->branch_id_fk_c_to."
                    where action_user=".(\Auth::user()->id)."
                ");
              }

              return redirect()->to(url("backend/transfer_branch"));

      }else if(isset($request->save_set_to_warehouse_e)){

        // dd($request->all());
              if($request->save_set_to_warehouse_e==1){
                // DB::update("
                //     UPDATE db_transfer_choose_branch
                //     SET branch_id_fk = ".$request->branch_id_set_to_warehouse_e.",
                //      warehouse_id_fk = ".$request->warehouse_id_fk_c_e."  ,
                //      zone_id_fk = ".$request->zone_id_fk_c_e."  ,
                //      shelf_id_fk = ".$request->shelf_id_fk_c_e."
                //     where id=".$request->id_set_to_warehouse_e."
                // ");
                 DB::update("
                    UPDATE db_transfer_choose_branch
                    SET branch_id_fk_to = ".$request->branch_id_fk_c_e_to."
                    where action_user=".(\Auth::user()->id)."
                ");
              }

              return redirect()->to(url("backend/transfer_branch"));

      }else if(isset($request->save_select_to_cancel)){

          DB::update(" UPDATE db_transfer_branch_code
            SET
            approve_status=2 , note = '".$request->note_to_cancel."'
            WHERE id=".$request->id_to_cancel." ; ");

          return redirect()->to(url("backend/transfer_branch"));

      }else{
         // dd($request->all());
        return $this->form();
      }

    }

    public function edit($id)
    {

        // dd($id);
        $sRow = \App\Models\Backend\Transfer_branch_code::find($id);
        $sPermission  = \Auth::user()->permission ;
        // dd($sRow);
        return View('backend.transfer_branch.form')->with(
        array(
           'sRow'=>$sRow,
            'id'=>$id,
            'sPermission'=>$sPermission,

        ) );
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      if(!empty($request->approve_transfer_branch_code)){
            return $this->form($id);

      }else
      if(!empty($request->approve_status_cutstock) && $request->approve_status==1){
          // dd($request->approve_status);

        $rsBranch_details = DB::select("
           select * from db_transfer_branch_details where transfer_branch_code_id = ".$request->id." ");
        $arr1 = [];
        foreach ($rsBranch_details as $key => $value) {
          array_push($arr1, $value->stocks_id_fk);
        }

        $arr1 = implode(",", $arr1);

        $rsStock = DB::select(" select * from  db_stocks  where id in (".$arr1.")  ");
              // dd($rsStock);
        foreach ($rsStock as $key => $value) {
            DB::update(" UPDATE db_transfer_branch_details SET stock_amt_before_up =".$value->amt." , stock_date_before_up = '".$value->date_in_stock."'  where transfer_branch_code_id = ".$request->id." and stocks_id_fk = ".$value->id."  ");
        }

         $rsBranch_details = DB::select("
           select * from db_transfer_branch_details where transfer_branch_code_id = ".$request->id." ");
         foreach ($rsBranch_details as $key => $value) {
              DB::update(" UPDATE db_stocks SET amt = (amt - ".$value->amt.") where id =".$value->stocks_id_fk."  ");
         }


         DB::select(" UPDATE `db_transfer_branch_code` SET `tr_status_from`='2' WHERE (`id`='".$rsBranch_details[0]->transfer_branch_code_id."') ");


      }else{

        return $this->form($id);

      }



    }

   public function form($id=NULL)
    {

      $id=request('id');
      // dd($id);
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow1 = \App\Models\Backend\Transfer_branch_code::find($id);
          }else{
            $sRow1 = new \App\Models\Backend\Transfer_branch_code;
          }


// Check Stock อีกครั้งก่อน เพื่อดูว่าสินค้ายังมีพอให้ตัดหรือไม่
          // $db_select = DB::select("
          //   SELECT * FROM `db_transfer_branch_details` WHERE transfer_branch_code_id=".$id."
          //    ");
          // foreach ($db_select as $key => $v) {

          //        $fnCheckStock = new  AjaxController();
          //        $r_check_stcok = $fnCheckStock->fnCheckStock(
          //         $v->branch_id_fk,
          //         $v->product_id_fk,
          //         $v->amt,
          //         $v->lot_number,
          //         $v->lot_expired_date,
          //         $v->warehouse_id_fk,
          //         $v->zone_id_fk,
          //         $v->shelf_id_fk,
          //         $v->shelf_floor);
          //       // return $r_check_stcok;
          //       if($r_check_stcok==0){
          //         return redirect()->to(url("backend/transfer_branch/".$id."/edit"))->with(['alert'=>\App\Models\Alert::myTxt("สินค้าในคลังไม่เพียงพอ")]);
          //       }

          // }



          $sRow1->approver    = \Auth::user()->id;
          $sRow1->approve_status    = request('approve_status');
          $sRow1->approve_note    = request('approve_note');
          $sRow1->tr_status_from    = 2 ;
          $sRow1->approve_date = date('Y-m-d H:i:s');

          $sRow1->save();


          $Transfer_branch_get = new \App\Models\Backend\Transfer_branch_get;
          $Transfer_branch_get->business_location_id_fk    = $sRow1->business_location_id_fk;
          $Transfer_branch_get->branch_id_fk    = $sRow1->to_branch_id_fk;
          $Transfer_branch_get->get_from_branch_id_fk    = $sRow1->branch_id_fk;
          $Transfer_branch_get->tr_number    = $sRow1->tr_number;
          $Transfer_branch_get->action_user    = \Auth::user()->id ;
          $Transfer_branch_get->note1    = $sRow1->note;
          $Transfer_branch_get->created_at    = $sRow1->created_at;

// อนุมัติ เท่านั้น
          if($sRow1->approve_status==1){

    // สาขาปลายทางที่รับ
              $Transfer_branch_get->save();
              DB::select("  UPDATE `db_transfer_branch_details_log` SET remark=1 WHERE transfer_branch_code_id=".$sRow1->id."  ");
              // เมื่อมีการรับเข้าแล้ว นำเข้า Stock ด้วย
         
            // ส่วนของสินค้า ก็รับเข้ามาจากใบโอนสินค้าจากสาชาต้นทางเลย
              $r_product = DB::select("  SELECT * FROM `db_transfer_branch_details` WHERE transfer_branch_code_id=".$sRow1->id."  ");

            // ต้องลูปเข้าตามรหัส transfer_branch_code_id กรณีถ้ามีสินค้าหลายรายการ

              if($r_product){

                foreach ($r_product as $key => $value) {

                       $Transfer_branch_get_products = new \App\Models\Backend\Transfer_branch_get_products;
                       $Transfer_branch_get_products->transfer_branch_get_id_fk = $Transfer_branch_get->id;
                       // สินค้ามาจากตาราง db_transfer_branch_details
                       $Transfer_branch_get_products->product_id_fk = $value->product_id_fk;
                       $Transfer_branch_get_products->product_amt = $value->amt;
                       $Transfer_branch_get_products->product_unit = $value->product_unit_id_fk;
                       $Transfer_branch_get_products->created_at    = date("Y-m-d H:i:s");
                       $Transfer_branch_get_products->save();

                }

              }


          // เมื่อมีการอนุมัติแล้ว ต้องตัดออกจาก Stock

           if($sRow1->approve_status==1){

               // ตัด Stock
              $db_select = DB::select("
                select 
                  db_transfer_branch_details.*,db_transfer_branch_code.tr_number,db_transfer_branch_code.business_location_id_fk,db_transfer_branch_code.note
                  from db_transfer_branch_details 
                  LEFT JOIN db_transfer_branch_code ON db_transfer_branch_details.transfer_branch_code_id=db_transfer_branch_code.id 
                  where db_transfer_branch_details.transfer_branch_code_id =".$sRow1->id."
                 ");

              foreach ($db_select as $key => $sRow) {

                       $_choose=DB::table('db_stocks')
                      ->where('branch_id_fk', $sRow1->branch_id_fk)
                      ->where('product_id_fk', $sRow->product_id_fk)
                      ->where('lot_number', $sRow->lot_number)
                      ->where('lot_expired_date', $sRow->lot_expired_date)
                      ->where('warehouse_id_fk', $sRow->warehouse_id_fk)
                      ->where('zone_id_fk', $sRow->zone_id_fk)
                      ->where('shelf_id_fk', $sRow->shelf_id_fk)
                      ->where('shelf_floor', $sRow->shelf_floor)
                      ->get();
                      if($_choose->count() > 0){

                           DB::table('db_stocks')
                                  ->where('branch_id_fk', $sRow1->branch_id_fk)
                                  ->where('product_id_fk', $sRow->product_id_fk)
                                  ->where('lot_number', $sRow->lot_number)
                                  ->where('lot_expired_date', $sRow->lot_expired_date)
                                  ->where('warehouse_id_fk', $sRow->warehouse_id_fk)
                                  ->where('zone_id_fk', $sRow->zone_id_fk)
                                  ->where('shelf_id_fk', $sRow->shelf_id_fk)
                                  ->where('shelf_floor', $sRow->shelf_floor)
                                ->update(array(
                                  'amt' => DB::raw( ' amt - '.$sRow->amt )
                                ));

                      }

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
                          $stock_type_id_fk = 8 ;
                          $stock_id_fk = $sRow->stocks_id_fk;
                          $ref_table = 'db_transfer_branch_code' ;
                          $ref_table_id = $sRow->id ;
                          // $ref_doc = $sRow->ref_doc;
                          // $ref_doc = DB::select(" select * from `db_transfer_warehouses_code` WHERE id=".$sRow->id." ");
                          // dd($ref_doc[0]->ref_doc);
                          // $ref_doc = @$ref_doc[0]->tr_number;
                          $ref_doc = $sRow->tr_number;
                          // $General_takeout = \App\Models\Backend\General_takeout::find($sRow->id);
                          // @$ref_doc = @$General_takeout[0]->ref_doc;
// ฝั่งโอน
                          $value=DB::table('db_stock_movement')
                          ->where('stock_type_id_fk', @$stock_type_id_fk?$stock_type_id_fk:0 )
                          ->where('stock_id_fk', @$stock_id_fk?$stock_id_fk:0 )
                          ->where('ref_table_id', @$ref_table_id?$ref_table_id:0 )
                          ->where('ref_doc', @$ref_doc?$ref_doc:NULL )
                          ->get();

                          if($value->count() == 0){

                                // if(@$sRow->remark==1){
                                  $note = 'โอนระหว่างสาขา (ฝั่งโอน) ';
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



 // ฝั่นรับโอน
             $branch_get = DB::select("
                SELECT db_transfer_branch_details_log.*,db_transfer_branch_code.tr_number,db_transfer_branch_code.business_location_id_fk
                FROM
                db_transfer_branch_details_log
                left Join db_transfer_branch_code ON db_transfer_branch_details_log.transfer_branch_code_id = db_transfer_branch_code.id
                WHERE db_transfer_branch_code.tr_number='$ref_doc' ");
           
               if(!empty($branch_get)){


                     foreach ($branch_get as $key => $sRow) {

      // ฝั่นรับโอน ถ้ามี บวกยอดคลังเข้า ถ้ายังไม่มี สร้างรายการคลังเพิ่ม 
                      // $value=DB::table('db_stocks')
                      // ->where('business_location_id_fk', $sRow->business_location_id_fk )
                      // ->where('branch_id_fk', $sRow->branch_id_fk )
                      // ->where('product_id_fk', $sRow->product_id_fk )
                      // ->where('lot_number', $sRow->lot_number )
                      // ->where('lot_expired_date', $sRow->lot_expired_date )
                      // ->where('warehouse_id_fk', $sRow->warehouse_id_fk )
                      // ->where('zone_id_fk', $sRow->zone_id_fk )
                      // ->where('shelf_id_fk', $sRow->shelf_id_fk )
                      // ->where('shelf_floor', $sRow->shelf_floor )
                      // ->get();

                      // if($value->count() > 0){

                      //     DB::table('db_stocks')
                      //     ->where('business_location_id_fk', $sRow->business_location_id_fk)
                      //     ->where('branch_id_fk', $sRow->branch_id_fk)
                      //     ->where('product_id_fk', $sRow->product_id_fk)
                      //     ->where('lot_number', $sRow->lot_number)
                      //     ->where('lot_expired_date', $sRow->lot_expired_date)
                      //     ->where('warehouse_id_fk', $sRow->warehouse_id_fk)
                      //     ->where('zone_id_fk', $sRow->zone_id_fk)
                      //     ->where('shelf_id_fk', $sRow->shelf_id_fk)
                      //     ->where('shelf_floor', $sRow->shelf_floor)
                      //       ->update(array(
                      //        'amt' => DB::raw( " amt + $sRow->amt " )
                      //       ));
                      //     // $lastID = DB::table('db_stocks')->latest()->first();
                      //     // $lastID = $lastID->id;
                      // }else{

                      //      DB::table('db_stocks')->insert(array(
                      //         'business_location_id_fk' => $sRow->business_location_id_fk,
                      //         'branch_id_fk' => $sRow->branch_id_fk,
                      //         'product_id_fk' => $sRow->product_id_fk,
                      //         'lot_number' => $sRow->lot_number,
                      //         'lot_expired_date' => $sRow->lot_expired_date,
                      //         'amt' => $sRow->amt,
                      //         'product_unit_id_fk' => $sRow->product_unit_id_fk,
                      //         'date_in_stock' => date("Y-m-d H:i:s"),
                      //         'warehouse_id_fk' => $sRow->warehouse_id_fk,
                      //         'zone_id_fk' => $sRow->zone_id_fk,
                      //         'shelf_id_fk' => $sRow->shelf_id_fk,
                      //         'shelf_floor' => $sRow->shelf_floor,
                      //         'created_at' => date("Y-m-d H:i:s"),
                      //       ));

                      // }


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
                          $stock_type_id_fk = 8 ;
                          $stock_id_fk = $sRow->stocks_id_fk;
                          $ref_table = 'db_transfer_branch_get' ;
                          $ref_table_id = $sRow->id ;
                          // $ref_doc = $sRow->ref_doc;
                          // $ref_doc = DB::select(" select * from `db_transfer_warehouses_code` WHERE id=".$sRow->id." ");
                          // dd($ref_doc[0]->ref_doc);
                          // $ref_doc = @$ref_doc[0]->tr_number;
                          $ref_doc = $sRow->tr_number;
                          // $General_takeout = \App\Models\Backend\General_takeout::find($sRow->id);
                          // @$ref_doc = @$General_takeout[0]->ref_doc;
         // ฝั่งรับโอน
                  
                          $value=DB::table('db_stock_movement')
                          ->where('stock_type_id_fk', @$stock_type_id_fk?$stock_type_id_fk:0 )
                          ->where('stock_id_fk', @$stock_id_fk?$stock_id_fk:0 )
                          ->where('ref_table', @$ref_table?$ref_table:0 )
                          ->where('ref_table_id', @$ref_table_id?$ref_table_id:0 )
                          ->where('ref_doc', @$ref_doc?$ref_doc:NULL )
                          ->get();

                          if($value->count() == 0){

                                // if(@$sRow->remark==1){
                                  $note = 'โอนระหว่างสาขา (ฝั่งรับโอน) ';
                                // }else if(@$sRow->remark==2){
                                //   $note = 'โอนภายในสาขา (ฝั่งรับโอน) ';
                                // }

                                // DB::table('db_stock_movement')->insert(array(
                                //     "stock_type_id_fk" =>  @$stock_type_id_fk?$stock_type_id_fk:0,
                                //     "stock_id_fk" =>  @$stock_id_fk?$stock_id_fk:0,
                                //     "ref_table" =>  @$ref_table?$ref_table:0,
                                //     "ref_table_id" =>  @$ref_table_id?$ref_table_id:0,
                                //     "ref_doc" =>  @$ref_doc?$ref_doc:NULL,
                                //     // "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
                                //     "doc_date" =>  $sRow->created_at,
                                //     "business_location_id_fk" =>  @$sRow->business_location_id_fk?$sRow->business_location_id_fk:0,
                                //     "branch_id_fk" =>  @$sRow->branch_id_fk?$sRow->branch_id_fk:0,
                                //     "product_id_fk" =>  @$sRow->product_id_fk?$sRow->product_id_fk:0,
                                //     "lot_number" =>  @$sRow->lot_number?$sRow->lot_number:NULL,
                                //     "lot_expired_date" =>  @$sRow->lot_expired_date?$sRow->lot_expired_date:NULL,
                                //     "amt" =>  @$sRow->amt?$sRow->amt:0,
                                //     "in_out" =>  '1',
                                //     "product_unit_id_fk" =>  @$sRow->product_unit_id_fk?$sRow->product_unit_id_fk:0,

                                //     "warehouse_id_fk" =>  @$sRow->warehouse_id_fk?$sRow->warehouse_id_fk:0,
                                //     "zone_id_fk" =>  @$sRow->zone_id_fk?$sRow->zone_id_fk:0,
                                //     "shelf_id_fk" =>  @$sRow->shelf_id_fk?$sRow->shelf_id_fk:0,
                                //     "shelf_floor" =>  @$sRow->shelf_floor?$sRow->shelf_floor:0,

                                //     "status" =>  '1',
                                //     "note" =>  $note,
                                //     "note2" =>  @$sRow->description?$sRow->description:NULL,

                                //     "action_user" =>  @$sRow->action_user?$sRow->action_user:NULL,
                                //     "action_date" =>  @$sRow->created_at?$sRow->created_at:NULL,
                                //     "approver" =>  @$sRow->approver?$sRow->approver:NULL,
                                //     "approve_date" =>  @$sRow->updated_at?$sRow->updated_at:NULL,

                                //     "created_at" =>@$sRow->created_at?$sRow->created_at:NULL
                                // ));

                           }
                         }
                      }



           }



          }

          \DB::commit();

           // return redirect()->to(url("backend/transfer_branch/".$id."/edit?list_id=".$id.""));
           return redirect()->to(url("backend/transfer_branch"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Transfer_branchController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Transfer_branch::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){

       $sPermission = @\Auth::user()->permission ;
       $User_branch_id = @\Auth::user()->branch_id_fk;

        if(@\Auth::user()->permission==1){

                             
                if(isset($req->id)){
                      $sTable = \App\Models\Backend\Transfer_branch::where('id',$req->id)->search()->orderBy('id', 'asc');
                }else{
                     $sTable = \App\Models\Backend\Transfer_branch::search()->orderBy('id', 'asc');
                }


        }else{

                             
                if(isset($req->id)){
                      $sTable = \App\Models\Backend\Transfer_branch::where('branch_id_fk',$User_branch_id)->where('id',$req->id)->search()->orderBy('id', 'asc');
                }else{
                     $sTable = \App\Models\Backend\Transfer_branch::where('branch_id_fk',$User_branch_id)->search()->orderBy('id', 'asc');
                }


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
            WHERE products.id=".$row->product_id_fk." AND lang_id=1");

        return @$Products[0]->product_code." <br> ".@$Products[0]->product_name;

      })
       ->escapeColumns('product_name')
      ->addColumn('warehouses', function($row) {
        $Check_stock = \App\Models\Backend\Check_stock::where('product_id_fk',$row->product_id_fk)->where('lot_number',$row->lot_number)->first();
        // return $Check_stock->branch_id_fk;
        $sBranchs = DB::select(" select * from branchs where id=".$Check_stock->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$Check_stock->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$Check_stock->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$Check_stock->shelf_id_fk." ");
        return str_replace(">","/",@$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น '.@$Check_stock->shelf_floor);
      })
      ->escapeColumns('warehouses')
      ->addColumn('amt_in_warehouse', function($row) {
        if($row->stocks_id_fk!=''){
          $Check_stock = DB::select(" select * from db_stocks where id=".$row->stocks_id_fk." ");
          return @$Check_stock[0]->amt;
        }
      })
      ->addColumn('to_branch', function($row) {
          $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
          return @$sBranchs[0]->b_name;
      })
      // ->addColumn('updated_at', function($row) {
      //   return is_null($row->updated_at) ? '-' : $row->updated_at;
      // })
      ->make(true);
    }



    public function DatatableProduct(Request $req){

      $sTable = DB::select(" select * from db_transfer_branch_details where transfer_branch_code_id=".$req->transfer_branch_code_id."  ");

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

        return @$Products[0]->product_code." <br> ".@$Products[0]->product_name;

      })
       ->escapeColumns('product_name')
      ->addColumn('warehouses', function($row) {
        $Check_stock = \App\Models\Backend\Check_stock::where('id',$row->stocks_id_fk)->first();
        // return $Check_stock->branch_id_fk;
        $sBranchs = DB::select(" select * from branchs where id=".(@$Check_stock->branch_id_fk?$Check_stock->branch_id_fk:0)." ");
        $warehouse = DB::select(" select * from warehouse where id=".(@$Check_stock->warehouse_id_fk?$Check_stock->warehouse_id_fk:0)." ");
        $zone = DB::select(" select * from zone where id=".(@$Check_stock->zone_id_fk?$Check_stock->zone_id_fk:0)." ");
        $shelf = DB::select(" select * from shelf where id=".(@$Check_stock->shelf_id_fk?$Check_stock->shelf_id_fk:0)." ");
        return str_replace(">","/",@$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น '.@$Check_stock->shelf_floor);
      })
      ->escapeColumns('warehouses')
      ->addColumn('amt_in_warehouse', function($row) {
        if($row->stocks_id_fk!=''){
          $Check_stock = DB::select(" select * from db_stocks where id=".$row->stocks_id_fk." ");
          return @$Check_stock[0]->amt;
        }
      })
      ->addColumn('to_branch', function($row) {
          $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
          return @$sBranchs[0]->b_name;
      })
      // ->addColumn('updated_at', function($row) {
      //   return is_null($row->updated_at) ? '-' : $row->updated_at;
      // })
      ->make(true);
    }

    public function requisitionDatatable(Request $request)
    {

      // วุฒิเพิ่มมา
      if (\Auth::user()->permission == '1' ) {
        $requisitions = RequisitionBetweenBranch::with('requisition_details')
        ->where('is_approve', RequisitionBetweenBranch::APPROVED)
        ->where('is_transfer', RequisitionBetweenBranch::WAIT_TRANSFER);
      }else{
        $requisitions = RequisitionBetweenBranch::with('requisition_details')
        ->where('is_approve', RequisitionBetweenBranch::APPROVED)
        ->where('is_transfer', RequisitionBetweenBranch::WAIT_TRANSFER)
        ->where('from_branch_id',\Auth::user()->branch_id_fk)
        ->orWhere('to_branch_id',\Auth::user()->branch_id_fk)
        ->where('is_approve', RequisitionBetweenBranch::APPROVED)
        ->where('is_transfer', RequisitionBetweenBranch::WAIT_TRANSFER);
      }

      return \DataTables::of($requisitions)
        ->editColumn('from_branch_id', function ($requisition) {
          return $requisition->from_branch->b_name;
        })
        ->editColumn('to_branch_id', function ($requisition) {
          return $requisition->to_branch->b_name;
        })
        ->addColumn('requisition_by', function ($requisition) {
          return $requisition->requisition_by->name;
        })
        ->addColumn('approve_by', function ($requisition) {
          return $requisition->approve_by->name;
        })
        ->editColumn('approved_at', function ($requisition) {
          return $requisition->approved_at->format('d/m/Y H:i:s');
        })
        ->addColumn('actions', function ($requisition) {
          // dd($requisition->requisition_details);
          return 
            "<button type='button' class='btn btn-primary btn-sm waves-effect waves-light' data-toggle='modal' data-target='.modal-requisition-details' data-details='$requisition->requisition_details' data-to_branch_id='$requisition->to_branch_id' data-from_branch_id='$requisition->from_branch_id'>
              <i class='fas fa-edit'></i>
            </button>";
        })
        ->rawColumns(['actions'])
        ->make(true);
    }

    public function checkStockOptions(Request $request)
    {
      $stocks = DB::table('db_stocks')
        ->select('db_stocks.id', 'db_stocks.shelf_floor', 'db_stocks.lot_number', 'db_stocks.amt', 'db_stocks.lot_expired_date', 'branchs.b_name', 'warehouse.w_name', 'zone.z_name', 'shelf.s_name')
        ->leftJoin('branchs', 'branchs.id', '=', 'db_stocks.branch_id_fk')
        ->leftJoin('warehouse', 'warehouse.id', '=', 'db_stocks.warehouse_id_fk')
        ->leftJoin('zone', 'zone.id', '=', 'db_stocks.zone_id_fk')
        ->leftJoin('shelf', 'shelf.id', '=', 'db_stocks.shelf_id_fk')
        ->where('db_stocks.branch_id_fk', $request->to_branch_id)
        ->where('db_stocks.product_id_fk', $request->product_id)
        ->get();

      $options = "<option value=''>เลือกคลัง</option>";

      foreach ($stocks as $stock) {

        $displayName = "$stock->b_name / $stock->w_name / $stock->z_name / $stock->s_name ชั้น > $stock->shelf_floor (จำนวน : $stock->amt)";

        $options .= "<option value='$stock->id' data-amt='$stock->amt'>$displayName</option>";

      }

      return $options;
    }

    public function storeFromRequisition(Request $request)
    {
      try {
        DB::beginTransaction();

        $transferBranchCode = new \App\Models\Backend\Transfer_branch_code;

        $data = $request->except(['_token', 'lists']) + [
          'business_location_id_fk' => 1,
          'action_date' => now()->format('Y-m-d'),
          'action_user' => auth()->user()->id
        ];

        $transferBranchCode = $transferBranchCode->create($data);

        $stock_type_id_fk = 8;
        $ref_table_id = $transferBranchCode->id;
        $ref_doc = 'TR'.$transferBranchCode->business_location_id_fk.$transferBranchCode->branch_id_fk.$stock_type_id_fk.date('ymd').$ref_table_id;

        $transferBranchCode->update([
          'tr_number' => $ref_doc,
        ]);
        
        foreach ($request->lists as $key => $value) {

        if($value['stock']!=null && $value['stock']!=''){  
          $requisitionBetweenBranchDetail = RequisitionBetweenBranchDetail::find($key);
          $requisitionBetweenBranchDetail->update([
            'amount' => $value['amount'] 
          ]);
          $requisitionBetweenBranchDetail->requisition->update(['is_transfer' => 1]);

          $stocks = DB::table('db_stocks')->where('id', $value['stock'])->first();

          $dataDetails = [
            'transfer_branch_code_id' => $transferBranchCode->id,
            'stocks_id_fk' => $stocks->id,
            'product_id_fk' => $stocks->product_id_fk,
            'lot_number' => $stocks->lot_number,
            'lot_expired_date' => $stocks->lot_expired_date,
            'amt' => $value['amount'],
            'product_unit_id_fk' => $stocks->product_unit_id_fk,
            'branch_id_fk' => $request->to_branch_id_fk,
            'warehouse_id_fk' => $stocks->warehouse_id_fk,
            'zone_id_fk' => $stocks->zone_id_fk,
            'shelf_id_fk' => $stocks->shelf_id_fk,
            'shelf_floor' => $stocks->shelf_floor,
            'action_user' => auth()->user()->id,
            'action_date' => now(),
          ];

          \App\Models\Backend\Transfer_branch_get_products_details::create($dataDetails);
          DB::table('db_transfer_branch_details_log')->insert($dataDetails);
        }
        }
        
        DB::commit();
        return redirect()->route('backend.transfer_branch.edit', $transferBranchCode->id);
      } catch (\Exception $e) {
        DB::rollback();
        return $e->getMessage();
      }

    }
}
