<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class Stock_cardController extends Controller
{

    public function index(Request $request)
    {
      
         // return View('backend.stock_card.index');
       
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
      // dd($request->all());
    }

    public function edit($id)
    {
       // dd($id);
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
    }

   public function form($id=NULL)
    {
  
    }

    public function destroy($id)
    {
      
    }

    
    public function Datatable(Request $request){

      $temp_db_stock_card = "temp_db_stock_card".\Auth::user()->id;
      DB::select(" DROP TABLE IF EXISTS $temp_db_stock_card ; ");
      DB::select(" CREATE TEMPORARY TABLE $temp_db_stock_card LIKE db_stock_card ");

      $txt = "ยอดคงเหลือยกมา ";
      $amt_balance_stock = 0;
      DB::select(" INSERT INTO $temp_db_stock_card (details,amt_in) VALUES ('$txt',$amt_balance_stock) ") ;

// 11111111111111111111111111111
      if(!empty($request->product_id_fk) && !empty($request->start_date) && !empty($request->end_date)){


          if(!empty($request->business_location_id_fk) && $request->business_location_id_fk != 'null'){
            $w_business_location_id_fk_01 = "business_location_id_fk" ;
            $w_business_location_id_fk_02 = $request->business_location_id_fk ;
            $w_business_location_id_fk = " AND business_location_id_fk=".$request->business_location_id_fk."" ;
          }else{
            $w_business_location_id_fk_01 = "1" ;
            $w_business_location_id_fk_02 = "1" ;
            $w_business_location_id_fk = '';
          }
          // dd($w_business_location_id_fk_01);

          if(!empty($request->branch_id_fk) && $request->branch_id_fk != 'null'){
            $w_branch_id_fk_01 = "branch_id_fk" ;
            $w_branch_id_fk_02 = $request->branch_id_fk ;
            $w_branch_id_fk = " AND branch_id_fk=".$request->branch_id_fk."" ;
          }else{
            $w_branch_id_fk_01 = "1" ;
            $w_branch_id_fk_02 = "1" ;
            $w_branch_id_fk = '';
          }

          if(!empty($request->warehouse_id_fk) && $request->warehouse_id_fk != 'null'){
            $w_warehouse_id_fk_01 = "warehouse_id_fk" ;
            $w_warehouse_id_fk_02 = $request->warehouse_id_fk ;
            $w_warehouse_id_fk = " AND warehouse_id_fk=".$request->warehouse_id_fk."" ;
          }else{
            $w_warehouse_id_fk_01 = "1" ;
            $w_warehouse_id_fk_02 = "1" ;
            $w_warehouse_id_fk = '';
          }

          if(!empty($request->zone_id_fk) && $request->zone_id_fk != 'null'){
            $w_zone_id_fk_01 = "zone_id_fk" ;
            $w_zone_id_fk_02 = $request->zone_id_fk ;
            $w_zone_id_fk = " AND zone_id_fk=".$request->zone_id_fk."" ;
          }else{
            $w_zone_id_fk_01 = "1" ;
            $w_zone_id_fk_02 = "1" ;
            $w_zone_id_fk = '';
          }

          if(!empty($request->shelf_id_fk) && $request->shelf_id_fk != 'null'){
            $w_shelf_id_fk_01 = "shelf_id_fk" ;
            $w_shelf_id_fk_02 = $request->shelf_id_fk ;
            $w_shelf_id_fk = " AND shelf_id_fk=".$request->shelf_id_fk."" ;
          }else{
            $w_shelf_id_fk_01 = "1" ;
            $w_shelf_id_fk_02 = "1" ;
            $w_shelf_id_fk = '';
          }

          if(!empty($request->shelf_floor) && $request->shelf_floor != 'null'){
            $w_shelf_floor_01 = "shelf_floor" ;
            $w_shelf_floor_02 = $request->shelf_floor ;
            $w_shelf_floor = " AND shelf_floor=".$request->shelf_floor."" ;
          }else{
            $w_shelf_floor_01 = "1" ;
            $w_shelf_floor_02 = "1" ;
            $w_shelf_floor = '';
          }

          if(!empty($request->lot_number) && $request->lot_number != 'null'){
            $w_lot_number_01 = "lot_number" ;
            $w_lot_number_02 = $request->lot_number ;
            $w_lot_number = " AND lot_number=".$request->lot_number."" ;
          }else{
            $w_lot_number_01 = "1" ;
            $w_lot_number_02 = "1" ;
            $w_lot_number = '';
          }

           $Stock = \App\Models\Backend\Check_stock::where('product_id_fk',$request->product_id_fk)
           ->where(DB::raw($w_business_location_id_fk_01), "=", $w_business_location_id_fk_02)
           ->where(DB::raw($w_branch_id_fk_01), "=", $w_branch_id_fk_02)
           ->where(DB::raw($w_warehouse_id_fk_01), "=", $w_warehouse_id_fk_02)
           ->where(DB::raw($w_zone_id_fk_01), "=", $w_zone_id_fk_02)
           ->where(DB::raw($w_shelf_id_fk_01), "=", $w_shelf_id_fk_02)
           ->where(DB::raw($w_shelf_floor_01), "=", $w_shelf_floor_02)
           ->where(DB::raw($w_lot_number_01), "=", $w_lot_number_02)
           ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), ">=", $request->start_date)
           ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), "<=", $request->end_date)
           ->get();

         if(!empty($Stock[0]->product_id_fk)){

        // ถ้าวันที่ $request->start_date > ปัจจุบัน ให้เอายอด ใน stock คงเหลือปัจจุบัน เลย
           if($request->start_date > date('Y-m-d') ){

                $sBalance= DB::select(" SELECT (case when amt>0 then sum(amt) else 0 end) as amt FROM `db_stocks` where product_id_fk=".($Stock[0]->product_id_fk?$Stock[0]->product_id_fk:0)."  

                  AND date(updated_at) BETWEEN '".$request->start_date."' AND '".$request->end_date."'

                  $w_business_location_id_fk
                  $w_branch_id_fk
                  $w_warehouse_id_fk
                  $w_zone_id_fk
                  $w_shelf_id_fk
                  $w_shelf_floor
                  $w_lot_number

                  GROUP BY product_id_fk ");
                  $amt_balance_stock = @$sBalance[0]->amt?$sBalance[0]->amt:0;

                  $txt = "ยอดคงเหลือ";
                  DB::select(" UPDATE $temp_db_stock_card SET details='$txt',amt_in='$amt_balance_stock' WHERE id=1 ") ;
            

            }else{

                // รายการก่อน start_date เพื่อหายอดยกมา
                  $Stock_movement_in = \App\Models\Backend\Stock_movement::
                  where('product_id_fk',($Stock[0]->product_id_fk?$Stock[0]->product_id_fk:0))
                 ->where(DB::raw($w_business_location_id_fk_01), "=", $w_business_location_id_fk_02)
                 ->where(DB::raw($w_branch_id_fk_01), "=", $w_branch_id_fk_02)
                 ->where(DB::raw($w_warehouse_id_fk_01), "=", $w_warehouse_id_fk_02)
                 ->where(DB::raw($w_zone_id_fk_01), "=", $w_zone_id_fk_02)
                 ->where(DB::raw($w_shelf_id_fk_01), "=", $w_shelf_id_fk_02)
                 ->where(DB::raw($w_shelf_floor_01), "=", $w_shelf_floor_02)
                 ->where(DB::raw($w_lot_number_01), "=", $w_lot_number_02)
                  ->where('in_out','1')
                  ->where(DB::raw("(DATE_FORMAT(doc_date,'%Y-%m-%d'))"), "<", $request->start_date)
                  ->selectRaw('sum(amt) as sum')
                  ->get();

                  // ยอดรับเข้า
                  $amt_balance_in = @$Stock_movement_in[0]->sum?$Stock_movement_in[0]->sum:0;

                  $Stock_movement_out = \App\Models\Backend\Stock_movement::
                  where('product_id_fk',($Stock[0]->product_id_fk?$Stock[0]->product_id_fk:0))
                 ->where(DB::raw($w_business_location_id_fk_01), "=", $w_business_location_id_fk_02)
                 ->where(DB::raw($w_branch_id_fk_01), "=", $w_branch_id_fk_02)
                 ->where(DB::raw($w_warehouse_id_fk_01), "=", $w_warehouse_id_fk_02)
                 ->where(DB::raw($w_zone_id_fk_01), "=", $w_zone_id_fk_02)
                 ->where(DB::raw($w_shelf_id_fk_01), "=", $w_shelf_id_fk_02)
                 ->where(DB::raw($w_shelf_floor_01), "=", $w_shelf_floor_02)
                 ->where(DB::raw($w_lot_number_01), "=", $w_lot_number_02)
                  ->where('in_out','2')
                  ->where(DB::raw("(DATE_FORMAT(doc_date,'%Y-%m-%d'))"), "<", $request->start_date)
                  ->selectRaw('sum(amt) as sum')
                  ->get();

                  // ยอดเบิกออก
                  $amt_balance_out = @$Stock_movement_out[0]->sum?$Stock_movement_out[0]->sum:0;

                  $amt_balance_stock = $amt_balance_in - $amt_balance_out ;
                  $txt = "ยอดคงเหลือยกมา";
                  DB::select(" UPDATE $temp_db_stock_card SET details='$txt',amt_in='$amt_balance_stock' WHERE id=1 ") ;

                  // รายการตามช่วงวันที่ระบุ start_date to end_date
                  $Stock_movement = \App\Models\Backend\Stock_movement::
                  where('product_id_fk',($Stock[0]->product_id_fk?$Stock[0]->product_id_fk:0))
                 ->where(DB::raw($w_business_location_id_fk_01), "=", $w_business_location_id_fk_02)
                 ->where(DB::raw($w_branch_id_fk_01), "=", $w_branch_id_fk_02)
                 ->where(DB::raw($w_warehouse_id_fk_01), "=", $w_warehouse_id_fk_02)
                 ->where(DB::raw($w_zone_id_fk_01), "=", $w_zone_id_fk_02)
                 ->where(DB::raw($w_shelf_id_fk_01), "=", $w_shelf_id_fk_02)
                 ->where(DB::raw($w_shelf_floor_01), "=", $w_shelf_floor_02)
                 ->where(DB::raw($w_lot_number_01), "=", $w_lot_number_02)
                  ->where(DB::raw("(DATE_FORMAT(doc_date,'%Y-%m-%d'))"), ">=", $request->start_date)
                  ->where(DB::raw("(DATE_FORMAT(doc_date,'%Y-%m-%d'))"), "<=", $request->end_date)
                  ->get();

                  if($Stock_movement->count() > 0){

                          foreach ($Stock_movement as $key => $value) {
                               $insertData = array(
                                  "ref_inv" =>  @$value->ref_doc?$value->ref_doc:NULL,
                                  "action_date" =>  @$value->doc_date?$value->doc_date:NULL,
                                  "action_user" =>  @$value->action_user?$value->action_user:NULL,
                                  "approver" =>  @$value->approver?$value->approver:NULL,
                                  "approve_date" =>  @$value->approve_date?$value->approve_date:NULL,
                                  "sender" =>  @$value->sender?$value->sender:NULL,
                                  "sent_date" =>  @$value->sent_date?$value->sent_date:NULL,
                                  "who_cancel" =>  @$value->who_cancel?$value->who_cancel:NULL,
                                  "cancel_date" =>  @$value->cancel_date?$value->cancel_date:NULL,
                                  "business_location_id_fk" =>  @$value->business_location_id_fk?$value->business_location_id_fk:0,
                                  "branch_id_fk" =>  @$value->branch_id_fk?$value->branch_id_fk:0,
                                  "product_id_fk" =>  @$value->product_id_fk?$value->product_id_fk:0,
                                  "lot_number" =>  @$value->lot_number?$value->lot_number:NULL,
                                  "details" =>  (@$value->note?$value->note:NULL).' '.(@$value->note2?$value->note2:NULL),
                                  "amt_in" =>  @$value->in_out==1?$value->amt:0,
                                  "amt_out" =>  @$value->in_out==2?$value->amt:0,
                                  "warehouse_id_fk" =>  @$value->warehouse_id_fk>0?$value->warehouse_id_fk:0,
                                  "zone_id_fk" =>  @$value->zone_id_fk>0?$value->zone_id_fk:0,
                                  "shelf_id_fk" =>  @$value->shelf_id_fk>0?$value->shelf_id_fk:0,
                                  "shelf_floor" =>  @$value->shelf_floor>0?$value->shelf_floor:0,
                                  "created_at" =>@$value->dd?$value->dd:NULL
                              );

                               DB::table($temp_db_stock_card)->insert($insertData);
                          }

                  }
              }

            }


            DB::select(" SET @csum := 0; ");
            $sTable = DB::select(" 
                SELECT $temp_db_stock_card.*,(@csum := @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END )) as remain 
                FROM $temp_db_stock_card 
            "); 
             


// 11111111111111111111111111111          
      }else{

        DB::select(" SET @csum := 0; ");
        $sTable = DB::select(" 
            SELECT $temp_db_stock_card.*,(@csum := @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END )) as remain 
            FROM $temp_db_stock_card 
        "); 

      }
// 11111111111111111111111111111

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('action_user', function($row) {
        if(@$row->action_user!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })      
      ->addColumn('approver', function($row) {
        if(@$row->approver!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->approver." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })   
      ->addColumn('remain', function($row) {
         if(@$row->remain){
            return $row->remain;
         }else{
            return 0;
         }
          
      }) 
      ->make(true);
    }


 



}
