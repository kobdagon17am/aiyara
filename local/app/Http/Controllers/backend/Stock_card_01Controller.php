<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class Stock_card_01Controller extends Controller
{

    public function index(Request $request)
    {
       // dd($request->stock_id);
      // return View('backend.stock_card_01.index');
           $id = $request->stock_id;
           $Stock = \App\Models\Backend\Check_stock::where('id',$id)->get();
        // dd($id);
        // dd($Stock[0]->product_id_fk);
           $sBalance = @$Stock[0]->amt;
          //  dd($sBalance);
          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$Stock[0]->product_id_fk." AND lang_id=1");

           $p_name = @$Products[0]->product_code." : ".@$Products[0]->product_name;

// date(lot_expired_date) >= CURDATE()
          // $sBalance= DB::select(" SELECT (case when amt>0 then sum(amt) else 0 end) as amt FROM `db_stocks` where 1 AND product_id_fk=".$Stock[0]->product_id_fk."
          // AND lot_number='".$Stock[0]->lot_number."'
          // AND lot_expired_date='".$Stock[0]->lot_expired_date."'
          // AND warehouse_id_fk='".$Stock[0]->warehouse_id_fk."'
          // AND zone_id_fk='".$Stock[0]->zone_id_fk."'
          // AND shelf_id_fk='".$Stock[0]->shelf_id_fk."'
          // AND shelf_floor='".$Stock[0]->shelf_floor."'
          // GROUP BY product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ORDER BY lot_number ");
          // dd($sBalance);

          $lot_number = $Stock[0]->lot_number;
          $lot_expired_date = $Stock[0]->lot_expired_date;


        $b = DB::select(" select * from branchs where id=".($Stock[0]->branch_id_fk?$Stock[0]->branch_id_fk:0)." ");
        $warehouse = DB::select(" select * from warehouse where id=".($Stock[0]->warehouse_id_fk?$Stock[0]->warehouse_id_fk:0)." ");
        $zone = DB::select(" select * from zone where id=".($Stock[0]->zone_id_fk?$Stock[0]->zone_id_fk:0)." ");
        $shelf = DB::select(" select * from shelf where id=".($Stock[0]->shelf_id_fk?$Stock[0]->shelf_id_fk:0)." ");
        $wh = 'สาขา '.(@$b[0]->b_name?@$b[0]->b_name:'').'/'.(@$warehouse[0]->w_name?@$warehouse[0]->w_name:'').'/'.(@$zone[0]->z_name?@$zone[0]->z_name:'').'/'.(@$shelf[0]->s_name?@$shelf[0]->s_name:'').'/ชั้น>'.($Stock[0]->shelf_floor?$Stock[0]->shelf_floor:'');

         return View('backend.stock_card_01.index')->with(
         array(
            'id'=>$id,
            'Stock'=>$Stock,
            'p_name'=>$p_name,
           'lot_number'=>$lot_number,
           'lot_expired_date'=>$lot_expired_date,
           'sBalance'=>$sBalance,
           'wh'=>$wh,
         ));
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

      $temp_db_stock_card = "temp_db_stock_card_01".\Auth::user()->id;
      DB::select(" DROP TABLE IF EXISTS $temp_db_stock_card ; ");
      DB::select(" CREATE TEMPORARY TABLE $temp_db_stock_card LIKE db_stock_card ");

      $txt = "ยอดคงเหลือยกมา";
      $amt_balance_stock = 0;
      DB::select(" INSERT INTO $temp_db_stock_card (details,amt_in) VALUES ('$txt',$amt_balance_stock) ") ;

// 11111111111111111111111111111
      if(!empty($request->stock_id) && !empty($request->start_date) && !empty($request->end_date)){

         $Stock = \App\Models\Backend\Check_stock::where('id',$request->stock_id)->get();
        //  dd($Stock);

        // ถ้าวันที่ $request->start_date > ปัจจุบัน ให้เอายอด ใน stock คงเหลือปัจจุบัน เลย
           if($request->start_date > date('Y-m-d') ){

                $sBalance= DB::select(" SELECT (case when amt>0 then sum(amt) else 0 end) as amt FROM `db_stocks` where product_id_fk=".($Stock[0]->product_id_fk?$Stock[0]->product_id_fk:0)."
                AND lot_number='".($Stock[0]->lot_number?$Stock[0]->lot_number:0)."'
                AND lot_expired_date='".$Stock[0]->lot_expired_date."'
                AND warehouse_id_fk='".$Stock[0]->warehouse_id_fk."'
                AND zone_id_fk='".$Stock[0]->zone_id_fk."'
                AND shelf_id_fk='".$Stock[0]->shelf_id_fk."'
                AND shelf_floor='".$Stock[0]->shelf_floor."'
                GROUP BY branch_id_fk,product_id_fk,lot_number,lot_expired_date,warehouse_id_fk,zone_id_fk,shelf_id_fk,shelf_floor ");
                $amt_balance_stock = @$sBalance[0]->amt?$sBalance[0]->amt:0;

               $txt = "ยอดคงเหลือ";
               DB::select(" UPDATE $temp_db_stock_card SET details='$txt',amt_in='$amt_balance_stock' WHERE id=1 ") ;


            }else{
              // doc_date แปลงเป็น updated_at
                // รายการก่อน start_date เพื่อหายอดยกมา
                  $Stock_movement_in = \App\Models\Backend\Stock_movement::
                  where('product_id_fk',($Stock[0]->product_id_fk?$Stock[0]->product_id_fk:0))
                  ->where('lot_number',($Stock[0]->lot_number?$Stock[0]->lot_number:0))
                  ->where('lot_expired_date',($Stock[0]->lot_expired_date?$Stock[0]->lot_expired_date:0))
                  ->where('business_location_id_fk',($Stock[0]->business_location_id_fk?$Stock[0]->business_location_id_fk:0))
                  ->where('branch_id_fk',($Stock[0]->branch_id_fk?$Stock[0]->branch_id_fk:0))
                  ->where('warehouse_id_fk',($Stock[0]->warehouse_id_fk?$Stock[0]->warehouse_id_fk:0))
                  ->where('zone_id_fk',($Stock[0]->zone_id_fk?$Stock[0]->zone_id_fk:0))
                  ->where('shelf_id_fk',($Stock[0]->shelf_id_fk?$Stock[0]->shelf_id_fk:0))
                  ->where('shelf_floor',($Stock[0]->shelf_floor?$Stock[0]->shelf_floor:0))
                  ->where('in_out','1')
                  ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), "<", $request->start_date)
                  ->selectRaw('sum(amt) as sum')
                  ->get();

                  // ยอดรับเข้า
                  $amt_balance_in = @$Stock_movement_in[0]->sum?$Stock_movement_in[0]->sum:0;

                  $Stock_movement_out = \App\Models\Backend\Stock_movement::
                  where('product_id_fk',($Stock[0]->product_id_fk?$Stock[0]->product_id_fk:0))
                  ->where('lot_number',($Stock[0]->lot_number?$Stock[0]->lot_number:0))
                  ->where('lot_expired_date',($Stock[0]->lot_expired_date?$Stock[0]->lot_expired_date:0))
                  ->where('business_location_id_fk',($Stock[0]->business_location_id_fk?$Stock[0]->business_location_id_fk:0))
                  ->where('branch_id_fk',($Stock[0]->branch_id_fk?$Stock[0]->branch_id_fk:0))
                  ->where('warehouse_id_fk',($Stock[0]->warehouse_id_fk?$Stock[0]->warehouse_id_fk:0))
                  ->where('zone_id_fk',($Stock[0]->zone_id_fk?$Stock[0]->zone_id_fk:0))
                  ->where('shelf_id_fk',($Stock[0]->shelf_id_fk?$Stock[0]->shelf_id_fk:0))
                  ->where('shelf_floor',($Stock[0]->shelf_floor?$Stock[0]->shelf_floor:0))
                  ->where('in_out','2')
                  ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), "<", $request->start_date)
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
                  ->where('lot_number',($Stock[0]->lot_number?$Stock[0]->lot_number:0))
                  ->where('lot_expired_date',($Stock[0]->lot_expired_date?$Stock[0]->lot_expired_date:0))
                  ->where('business_location_id_fk',($Stock[0]->business_location_id_fk?$Stock[0]->business_location_id_fk:0))
                  ->where('branch_id_fk',($Stock[0]->branch_id_fk?$Stock[0]->branch_id_fk:0))
                  ->where('warehouse_id_fk',($Stock[0]->warehouse_id_fk?$Stock[0]->warehouse_id_fk:0))
                  ->where('zone_id_fk',($Stock[0]->zone_id_fk?$Stock[0]->zone_id_fk:0))
                  ->where('shelf_id_fk',($Stock[0]->shelf_id_fk?$Stock[0]->shelf_id_fk:0))
                  ->where('shelf_floor',($Stock[0]->shelf_floor?$Stock[0]->shelf_floor:0))
                  ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), ">=", $request->start_date)
                  ->where(DB::raw("(DATE_FORMAT(updated_at,'%Y-%m-%d'))"), "<=", $request->end_date)
                  // วุฒิเพิ่มมาเช็คไม่เอายอด 0
                  ->where('amt','!=',0)
                  ->get();

                  if($Stock_movement->count() > 0){
                            // doc_date แปลงเป็น updated_at
                          foreach ($Stock_movement as $key => $value) {
                            $full_detail = "";
                            if($value->stock_type_id_fk==8){
                              $ref_data = DB::table('db_transfer_branch_code')->select('db_transfer_branch_code.*','branchs.b_name')->where('tr_number',$value->ref_doc)
                              ->join('branchs','branchs.id','db_transfer_branch_code.to_branch_id_fk')->first();
                              if($ref_data){
                                $full_detail = "( ไปยัง ".$ref_data->b_name." )";
                              }
                            }

                            if($value->stock_type_id_fk==9){
                              $ref_data = DB::table('db_transfer_branch_code')->select('db_transfer_branch_code.*','branchs.b_name')->where('tr_number',$value->ref_doc)
                              ->join('branchs','branchs.id','db_transfer_branch_code.branch_id_fk')->first();
                              if($ref_data){
                                $full_detail = "( รับจาก ".$ref_data->b_name." )";
                              }
                            }

                               $insertData = array(
                                  "ref_inv" =>  @$value->ref_doc?$value->ref_doc:NULL,
                                  "action_date" =>  @$value->updated_at?$value->updated_at:NULL,
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
                                  "details" =>  (@$value->note?$value->note:NULL).' '.(@$value->note2?$value->note2:NULL).' '.$full_detail,
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


            DB::select(" SET @csum := 0; ");
            $sTable = DB::select("
                SELECT $temp_db_stock_card.*,(@csum := @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END )) as remain
                FROM $temp_db_stock_card
            ");
      }else{

        DB::select(" SET @csum := 0; ");
        $sTable = DB::select("
            SELECT $temp_db_stock_card.*,(@csum := @csum + ( CASE WHEN amt_out>0 THEN -(amt_out) ELSE amt_in END )) as remain
            FROM $temp_db_stock_card
        ");

      }

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
