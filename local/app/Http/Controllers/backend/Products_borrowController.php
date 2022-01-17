<?php

namespace App\Http\Controllers\backend;

use DB;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Backend\Products_borrow_code;
use App\Http\Controllers\backend\AjaxController;

class Products_borrowController extends Controller
{

    public function index(Request $request)
    {

      // dd(\Auth::user()->permission .":". \Auth::user()->branch_id_fk.":". \Auth::user()->id);

       $sPermission = @\Auth::user()->permission ;
       $User_branch_id = @\Auth::user()->branch_id_fk;

        if(@\Auth::user()->permission==1){

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1 AND products.id in (SELECT product_id_fk FROM db_stocks)
            ORDER BY products.product_code ");

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

      $sProducts_borrow_chooseAll = \App\Models\Backend\Products_borrow_choose::where('action_user','=',(\Auth::user()->id))->get();
      // dd(\Auth::user()->id);
      // dd(count($sProducts_borrow_chooseAll));
      // dd($sProducts_borrow_chooseAll);
      // $sProducts_borrow_choose = \App\Models\Backend\Products_borrow_choose::where('warehouse_id_fk','=','0')->where('action_user','=',(\Auth::user()->id))->get();
      // dd(count($sProducts_borrow_choose));

      $sBorrowCause = DB::select(" select * from dataset_borrow_cause where deleted_at is null ");

        return View('backend.products_borrow.index')->with(
        array(
           'Products'=>$Products,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sProducts_borrow_chooseAll'=>$sProducts_borrow_chooseAll,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,'sBorrowCause'=>$sBorrowCause
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
      return View('backend.products_borrow.form')->with(
        array(
           'receive_id'=>$receive_id,'Products'=>$Products,'ProductsUnit'=>$ProductsUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf
        ) );
    }
    public function store(Request $request)
    {

      if(isset($request->save_select_to_products_borrow)){
        // dd($request->all());
        if(!empty($request->id)){

            for ($i=0; $i < count($request->id) ; $i++) {
                $Check_stock = \App\Models\Backend\Check_stock::find($request->id[$i]);
                // echo $Check_stock->product_id_fk;
                $sRow = new \App\Models\Backend\Products_borrow_choose;
                $sRow->business_location_id_fk    = $Check_stock->business_location_id_fk;
                $sRow->branch_id_fk    = request('branch_id_select_to_products_borrow');
                $sRow->stocks_id_fk    = $Check_stock->id;
                $sRow->product_id_fk    = $Check_stock->product_id_fk;
                $sRow->lot_number    = $Check_stock->lot_number;
                $sRow->lot_expired_date    = $Check_stock->lot_expired_date;
                $sRow->amt    = request('amt_products_borrow')[$i];
                $sRow->product_unit_id_fk    = $Check_stock->product_unit_id_fk;
                $sRow->date_in_stock    = $Check_stock->date_in_stock;
                $sRow->warehouse_id_fk    = $Check_stock->warehouse_id_fk;
                $sRow->zone_id_fk    = $Check_stock->zone_id_fk;
                $sRow->shelf_id_fk    = $Check_stock->shelf_id_fk;
                $sRow->shelf_floor    = $Check_stock->shelf_floor;
                $sRow->action_user = \Auth::user()->id;
                $sRow->action_date = date('Y-m-d H:i:s');
                $sRow->created_at = date('Y-m-d H:i:s');
                if(!empty(request('amt_products_borrow')[$i])){
                  $sRow->save();
                }
              }

        }

          return redirect()->to(url("backend/products_borrow"));

      }else if(isset($request->save_set_to_warehouse)){

        // dd($request->all());
              if($request->save_set_to_warehouse==1){
                DB::update("
                    UPDATE db_Products_borrow_choose
                    SET branch_id_fk = ".$request->branch_id_set_to_warehouse.",
                     warehouse_id_fk = ".$request->warehouse_id_fk_c."  ,
                     zone_id_fk = ".$request->zone_id_fk_c."  ,
                     shelf_id_fk = ".$request->shelf_id_fk_c."
                    where id=".$request->id_set_to_warehouse."
                ");
              }

              return redirect()->to(url("backend/products_borrow"));

      }else if(isset($request->save_set_to_warehouse_e)){

        // dd($request->all());
              if($request->save_set_to_warehouse_e==1){
                DB::update("
                    UPDATE db_Products_borrow_choose
                    SET branch_id_fk = ".$request->branch_id_set_to_warehouse_e.",
                     warehouse_id_fk = ".$request->warehouse_id_fk_c_e."  ,
                     zone_id_fk = ".$request->zone_id_fk_c_e."  ,
                     shelf_id_fk = ".$request->shelf_id_fk_c_e."
                    where id=".$request->id_set_to_warehouse_e."
                ");
              }

              return redirect()->to(url("backend/products_borrow"));

      }else if(isset($request->save_select_to_cancel)){

          DB::update(" UPDATE db_products_borrow_code
            SET
            approve_status=2 , note = '".$request->note_to_cancel."'
            WHERE id=".$request->id_to_cancel." ; ");

          return redirect()->to(url("backend/products_borrow"));

      }else{
         // dd($request->all());
        return $this->form();
      }

    }

    public function edit($id)
    {

        // dd($id);
        $sRow = \App\Models\Backend\Products_borrow_code::find($id);
        // dd($sRow);
        return View('backend.products_borrow.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id
        ) );
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      if(!empty($request->approve_status) && $request->approve_status==1){
          // dd($request->approve_status);

        $rsWarehouses_details = DB::select("
           select * from db_products_borrow_details where products_borrow_code_id = ".$request->id." ");
        $arr1 = [];
        foreach ($rsWarehouses_details as $key => $value) {
          array_push($arr1, $value->stocks_id_fk);
        }

        $arr1 = implode(",", $arr1);

        $stock_id_fk = $arr1[0];
        // dd($stock_id_fk);

        $rsStock = DB::select(" select * from  db_stocks  where id in (".$arr1.")  ");
              // dd($rsStock);
        foreach ($rsStock as $key => $value) {
            DB::update(" UPDATE db_products_borrow_details
              SET stock_amt_before_up =".$value->amt." ,
              stock_date_before_up = '".$value->date_in_stock."'  ,
              approve_status = '1'  ,
              approver = '".@\Auth::user()->id."'  ,
              approve_date = curdate()
              where products_borrow_code_id = ".$request->id." and stocks_id_fk = ".$value->id."  ");
        }

         $rsWarehouses_details = DB::select("
           select * from db_products_borrow_details where products_borrow_code_id = ".$request->id." ");
         foreach ($rsWarehouses_details as $key => $v) {

          // Check Stock อีกครั้งก่อน เพื่อดูว่าสินค้ายังมีพอให้ตัดหรือไม่
             // $fnCheckStock = new  AjaxController();
             //     $r_check_stcok = $fnCheckStock->fnCheckStock(
             //      $v->branch_id_fk,
             //      $v->product_id_fk,
             //      $v->amt,
             //      $v->lot_number,
             //      $v->lot_expired_date,
             //      $v->warehouse_id_fk,
             //      $v->zone_id_fk,
             //      $v->shelf_id_fk,
             //      $v->shelf_floor);
             //    // return $r_check_stcok;
             //    if($r_check_stcok==0){
             //      return redirect()->to(url("backend/products_borrow"))->with(['alert'=>\App\Models\Alert::myTxt("สินค้าในคลังไม่เพียงพอ")]);
             //    }
                // ตัด stock
                DB::update(" UPDATE db_stocks SET amt = (amt - ".$v->amt.") where id =".$v->stocks_id_fk."  ");
         }

            // ดึงจาก การเบิก/ยืม > db_products_borrow_code > db_products_borrow_details
            // $Data = DB::select("
            //         SELECT db_products_borrow_code.business_location_id_fk,
            //         (
            //         CASE WHEN borrow_number='-' or borrow_number is null THEN CONCAT('CODE',db_products_borrow_details.id) ELSE borrow_number END
            //         ) as doc_no
            //         ,db_products_borrow_code.updated_at as doc_date,db_products_borrow_details.branch_id_fk,

            //         db_products_borrow_details.product_id_fk, db_products_borrow_details.lot_number,lot_expired_date,
            //         db_products_borrow_details.amt,2 as 'in_out',product_unit_id_fk
            //         ,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor,db_products_borrow_code.approve_status as status,
            //         'เบิก/ยืม' as note, db_products_borrow_code.created_at as dd,
            //         db_products_borrow_details.action_user as action_user,db_products_borrow_details.approver as approver,db_products_borrow_details.approve_date as approve_date
            //         FROM
            //         db_products_borrow_details LEFT JOIN db_products_borrow_code ON db_products_borrow_details.products_borrow_code_id=db_products_borrow_code.id
            //   ");

            //   $insertStockMovement = new  AjaxController();

            //   foreach ($Data as $key => $value) {

            //        $insertData = array(
            //           "doc_no" =>  @$value->doc_no?$value->doc_no:NULL,
            //           "doc_date" =>  @$value->doc_date?$value->doc_date:NULL,
            //           "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
            //           "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
            //           "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
            //           "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
            //           "lot_expired_date" =>  @$value->lot_expired_date?$value->lot_expired_date:NULL,
            //           "amt" =>  @$value->amt?$value->amt:0,
            //           "in_out" =>  @$value->in_out?$value->in_out:0,
            //           "product_unit_id_fk" =>  @$value->product_unit_id_fk?$value->product_unit_id_fk:0,
            //           "warehouse_id_fk" =>  @$value->warehouse_id_fk?$value->warehouse_id_fk:0,
            //           "zone_id_fk" =>  @$value->zone_id_fk?$value->zone_id_fk:0,
            //           "shelf_id_fk" =>  @$value->shelf_id_fk?$value->shelf_id_fk:0,
            //           "shelf_floor" =>  @$value->shelf_floor?$value->shelf_floor:0,
            //           "status" =>  @$value->status?$value->status:0,
            //           "note" =>  @$value->note?$value->note:NULL,

            //             "action_user" =>  @$value->action_user?$value->action_user:NULL,
            //             "action_date" =>  @$value->action_date?$value->action_date:NULL,
            //             "approver" =>  @$value->approver?$value->approver:NULL,
            //             "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,

            //           "created_at" =>@$value->dd?$value->dd:NULL
            //       );

            //         $insertStockMovement->insertStockMovement($insertData);

            //     }

            //     DB::select(" INSERT IGNORE INTO db_stock_movement SELECT * FROM db_stock_movement_tmp ORDER BY doc_date asc ");





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
            $sRow1 = \App\Models\Backend\Products_borrow_code::find($id);
          }else{
            $sRow1 = new \App\Models\Backend\Products_borrow_code;
          }

          $sRow1->approver    = \Auth::user()->id;
          $sRow1->approve_status    = request('approve_status');
          $sRow1->note    = request('note');
          $sRow1->approve_date = date('Y-m-d H:i:s');

          $sRow1->save();

      if(request('approve_status')==1){
        // ดึงจาก การเบิก/ยืม > db_products_borrow_code > db_products_borrow_details
         $rsWarehouses_details = DB::select("
           select
           db_products_borrow_details.*,db_products_borrow_code.borrow_number,db_products_borrow_code.business_location_id_fk,db_products_borrow_code.note,dataset_borrow_cause.txt_desc as borrow_cause
           from db_products_borrow_details
           LEFT JOIN db_products_borrow_code ON db_products_borrow_details.products_borrow_code_id=db_products_borrow_code.id
           Left Join dataset_borrow_cause ON db_products_borrow_code.borrow_cause_id_fk = dataset_borrow_cause.id
           where db_products_borrow_details.products_borrow_code_id = ".$id." ");

         if(!empty($rsWarehouses_details)){

            foreach ($rsWarehouses_details as $key => $sRow) {

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
                $stock_type_id_fk = 6 ;
                $stock_id_fk = $sRow->stocks_id_fk ;
                $ref_table = 'db_products_borrow_details' ;
                $ref_table_id = $sRow->id ;
                // $ref_doc = $sRow->ref_doc;
                // $ref_doc = DB::select(" select * from `db_general_takeout` WHERE id=".$sRow->id." ");
                // dd($ref_doc[0]->ref_doc);
                $ref_doc = $sRow->borrow_number;
                // $General_takeout = \App\Models\Backend\General_takeout::find($sRow->id);
                // @$ref_doc = @$General_takeout[0]->ref_doc;

                $value2=DB::table('db_stock_movement')
                ->where('stock_type_id_fk', @$stock_type_id_fk?$stock_type_id_fk:0 )
                ->where('stock_id_fk', @$stock_id_fk?$stock_id_fk:0 )
                ->where('ref_table_id', @$ref_table_id?$ref_table_id:0 )
                ->where('ref_doc', @$ref_doc?$ref_doc:NULL )
                ->get();

                if($value2->count() == 0){

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
                          "in_out" =>  2,
                          "product_unit_id_fk" =>  @$sRow->product_unit_id_fk?$sRow->product_unit_id_fk:0,

                          "warehouse_id_fk" =>  @$sRow->warehouse_id_fk?$sRow->warehouse_id_fk:0,
                          "zone_id_fk" =>  @$sRow->zone_id_fk?$sRow->zone_id_fk:0,
                          "shelf_id_fk" =>  @$sRow->shelf_id_fk?$sRow->shelf_id_fk:0,
                          "shelf_floor" =>  @$sRow->shelf_floor?$sRow->shelf_floor:0,

                          "status" =>  @$sRow->approve_status?$sRow->approve_status:0,
                          "note" =>  '(เบิก/ยืม)('.@$sRow->borrow_cause.')',
                          "note2" =>  @$sRow->note?$sRow->note:NULL,

                          "action_user" =>  @$sRow->action_user?$sRow->action_user:NULL,
                          "action_date" =>  @$sRow->action_date?$sRow->action_date:NULL,
                          "approver" =>  @$sRow->approver?$sRow->approver:NULL,
                          "approve_date" =>  @$sRow->updated_at?$sRow->updated_at:NULL,

                          "created_at" =>@$sRow->created_at?$sRow->created_at:NULL
                      ));

                }
                // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

            }

         }
         }


          \DB::commit();

           // return redirect()->to(url("backend/products_borrow/".$id."/edit?list_id=".$id.""));
           return redirect()->to(url("backend/products_borrow"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Products_borrowController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Products_borrow::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $request){

       $sPermission = @\Auth::user()->permission ;
       $User_branch_id = @\Auth::user()->branch_id_fk;

       $product_borrow_code = Products_borrow_code::find($request->products_borrow_code_id);

        if(@\Auth::user()->permission==1){

            $sTable = \App\Models\Backend\Products_borrow::where('products_borrow_code_id',$request->products_borrow_code_id)->orderBy('id', 'asc');

        }else{

           $sTable = \App\Models\Backend\Products_borrow::where('branch_id_fk',$User_branch_id)->where('action_user',@\Auth::user()->id)->where('products_borrow_code_id',$request->products_borrow_code_id)->orderBy('id', 'asc');

        }


      // $sTable = \App\Models\Backend\Products_borrow::search()->orderBy('id', 'asc');

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
        return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name;
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
      ->addColumn('action', function ($row) use($product_borrow_code) {
        if ($product_borrow_code->approve_status == 3) {
          return "-";
        } else if ($row->is_returned) {
          return "<span class='badge badge-success'>คืนสินค้าแล้ว</span>";
        } else {
          return "<button class='btn btn-primary btn-sm btn-returned' data-id='$row->id'>คืนสินค้า</button>";
        }
      })
      ->rawColumns(['action'])
      ->make(true);
    }

    public function updateReturned(Request $request)
    {
      $product_borrow_detail = \App\Models\Backend\Products_borrow::find($request->id);
      $product_borrow_detail->is_returned = 1;
      $product_borrow_detail->save();

      

      return [
        'success' => true
      ];
    }
}
