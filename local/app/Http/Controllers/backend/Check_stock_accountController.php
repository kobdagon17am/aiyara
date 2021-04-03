<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Session;

class Check_stock_accountController extends Controller
{

    public function index(Request $request)
    {

      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $sStocks_account_code = \App\Models\Backend\Stocks_account_code::get();
      // dd($sStocks_account_code);

      return View('backend.check_stock_account.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs,'sStocks_account_code'=>$sStocks_account_code,
        ) );

    }

    public function create()
    {

      // return View('backend.check_stock_account.form');
      $Products = DB::select("SELECT products.id as product_id,
      products.product_code,
      (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
      FROM
      products_details
      Left Join products ON products_details.product_id_fk = products.id
      WHERE lang_id=1");

      // $Check_stock = \App\Models\Backend\Check_stock_account::get();
      $Check_stock = \App\Models\Backend\Check_stock::get();

      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

      return View('backend.check_stock_account.form')->with(
        array(
           'Products'=>$Products,'Check_stock'=>$Check_stock,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,
           'sBusiness_location'=>$sBusiness_location,
        ) );


    }

    public function Adjust($id)
    {
      // dd($id);
      // return View('backend.check_stock_account.adjust');

      $sRow = \App\Models\Backend\Check_stock_check::find($id);
      $Action_user = DB::select(" select * from ck_users_admin ");
      // dd($sRow);
      return View('backend.check_stock_account.adjust')->with(
        array(
           'id'=>$id,
           'sRow'=>$sRow,
           'Action_user'=>$Action_user,
        ));

    }

    public function store(Request $request)
    {
      // dd($request->all());
      if(isset($request->save_to_stock_check)){

         // DB::select(" TRUNCATE db_stocks_account; ");
         // return $request->row_id;
         if(!empty($request->row_id)){
            $arr_row_id = implode(',', $request->row_id);
         }else{
            $arr_row_id = 0 ;
         }

          $db_stocks =DB::select(" select * from db_stocks where id in($arr_row_id)  ");
          $branchs = DB::select("SELECT * FROM branchs where id=".@$db_stocks[0]->business_location_id_fk."");
    
          $REF_CODE = DB::select(" SELECT REF_CODE,SUBSTR(REF_CODE,4) AS REF_NO FROM DB_STOCKS_ACCOUNT_CODE ORDER BY REF_CODE DESC LIMIT 1 ");
          if($REF_CODE){
              $ref_code = 'ADJ'.sprintf("%05d",intval(@$REF_CODE[0]->REF_NO)+1);
          }else{
              $ref_code = 'ADJ'.sprintf("%05d",1);
          }
          // dd($ref_code);

          $stocks_account_code = new \App\Models\Backend\Stocks_account_code;
          if( $stocks_account_code ){
            $stocks_account_code->business_location_id_fk = @$db_stocks[0]->business_location_id_fk;
            $stocks_account_code->branch_id_fk = @$branchs[0]->id;
            $stocks_account_code->ref_code = $ref_code;
            $stocks_account_code->action_user = (\Auth::user()->id);
                        

            $stocks_account_code->condition_business_location = $request->condition_business_location;
            $stocks_account_code->condition_branch = $request->condition_branch;
            $stocks_account_code->condition_warehouse = $request->condition_warehouse;
            $stocks_account_code->condition_zone = $request->condition_zone;
            $stocks_account_code->condition_shelf = $request->condition_shelf;
            $stocks_account_code->condition_shelf_floor = $request->condition_shelf_floor;
            $stocks_account_code->condition_product = $request->condition_product;
            $stocks_account_code->condition_lot_number = $request->condition_lot_number;

            $stocks_account_code->action_date = date('Y-m-d');
            $stocks_account_code->created_at = date('Y-m-d H:i:s');
            $stocks_account_code->save();

          }

          
          $inv = DB::select(" SELECT run_code,SUBSTR(run_code,-5) AS REF_NO FROM DB_STOCKS_ACCOUNT ORDER BY run_code DESC LIMIT 1 ");
          if($inv){
                $REF_CODE = 'A'.$branchs[0]->business_location_id_fk.date("ymd");
                $REF_NO = intval(@$inv[0]->REF_NO);
          }else{
                $REF_CODE = 'A'.$branchs[0]->business_location_id_fk.date("ymd");
                $REF_NO = 0;
          }
          // dd(@$invoice_code);

         $i=1;
         foreach ($db_stocks as $key => $value) {

             $run_code = $REF_CODE.(sprintf("%05d",$REF_NO+$i)); 

             DB::select(" INSERT INTO db_stocks_account 
              (
                stocks_account_code_id_fk,
                run_code,
                business_location_id_fk,
                branch_id_fk,
                product_id_fk,
                lot_number,
                lot_expired_date,
                amt,
                amt_check,
                product_unit_id_fk,
                warehouse_id_fk,
                zone_id_fk,
                shelf_id_fk,
                shelf_floor
              )
               values 
              (
              '$stocks_account_code->id',
              '$run_code',
              '$value->business_location_id_fk',
              '$value->branch_id_fk',
              '$value->product_id_fk',
              '$value->lot_number',
              '$value->lot_expired_date',
              '$value->amt',
              '$value->amt',
              '$value->product_unit_id_fk',
              '$value->warehouse_id_fk',
              '$value->zone_id_fk',
              '$value->shelf_id_fk',
              '$value->shelf_floor'
              ) 

               ");

             $i++;
         }

          // return redirect()->to(url("backend/check_stock_account"));
         return redirect()->to(url("backend/check_stock_account/".$stocks_account_code->id."/edit"));


      }

    }

    public function edit($id)
    {
      // return View('backend.check_stock_account.form');
      // dd($id);
      $Products = DB::select("SELECT products.id as product_id,
      products.product_code,
      (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
      FROM
      products_details
      Left Join products ON products_details.product_id_fk = products.id
      WHERE lang_id=1");

      $sRow = \App\Models\Backend\Stocks_account_code::find($id);
      // dd(@$sRow->ref_code);
      // dd(@$sRow);

      if(empty($sRow)){
        return redirect()->to(url("backend/check_stock_account/"));
      }

// condition_business_location
// condition_branch
// condition_warehouse
// condition_zone
// condition_shelf
// condition_shelf_floor
// condition_product
// condition_lot_number

        $sL = DB::select(" select * from dataset_business_location where id=".($sRow->condition_business_location?$sRow->condition_business_location:0)." ");
        $sB = DB::select(" select * from branchs where id=".($sRow->condition_branch?$sRow->condition_branch:0)." ");
        $sW = DB::select(" select * from warehouse where id=".($sRow->condition_warehouse?$sRow->condition_warehouse:0)." ");
        $sZ = DB::select(" select * from zone where id=".($sRow->condition_zone?$sRow->condition_zone:0)." ");
        $sS = DB::select(" select * from shelf where id=".($sRow->condition_shelf?$sRow->condition_shelf:0)." ");
        $shelf_floor = @$sRow->condition_shelf_floor?" > ชั้น ".@$sRow->condition_shelf_floor:NULL;

        if(!empty(@$sRow->condition_product)){

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$sRow->condition_product." AND lang_id=1");

            $sProduct =  ' > สินค้า : '.@$Products[0]->product_code." : ".@$Products[0]->product_name;

        }else{
          $sProduct = '';
        }

        $lot_number = @$sRow->condition_lot_number?" > ".@$sRow->condition_lot_number:NULL;

        $Condition = @$sL[0]->txt_desc." > ".
        @$sB[0]->b_name.' '.(@$sW[0]->w_name?' > '.@$sW[0]->w_name:NULL).' '.(@$sZ[0]->z_name?' > '.@$sZ[0]->z_name:NULL).' '.(@$sS[0]->s_name?' > '.@$sS[0]->s_name:NULL).$shelf_floor.' '.$sProduct.' '.$lot_number;

        $ConditionChoose = "รหัสอ้างอิง : ".@$sRow->ref_code." > เงื่อนไขที่ระบุ : ".$Condition;
        Session::put('session_ConditionChoose', $ConditionChoose);

      $Check_stock = \App\Models\Backend\Check_stock::get();

      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

      return View('backend.check_stock_account.form')->with(
        array(
           'sRow'=>$sRow,'Products'=>$Products,'Check_stock'=>$Check_stock,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,
           'sBusiness_location'=>$sBusiness_location,
           'Condition'=>$Condition,
        ) );

    }

    public function update(Request $request, $id)
    {
      // dd($request->all());

        if(!empty($request->Adjust)){

            $sRow = \App\Models\Backend\Check_stock_account::find(request('id'));
            $sRow->amt_check    = request('amt_check');
            $sRow->amt_diff    = request('amt_check') - request('amt') ;
            $sRow->cuase_desc    = request('cuase_desc');
            $sRow->action_user    = request('action_user');
            // $sRow->status_accepted    = request('approve_status');
            $sRow->save();

            return redirect()->to(url("backend/check_stock_account/adjust/".request('id')."?from_id=".request('from_id')));


        }else{

           return $this->form();

        }

    }

    public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {

          // dd(request('id'));

        if(!empty(request('approved'))){

          $sRow = \App\Models\Backend\Stocks_account_code::find(request('id'));
          DB::select(' UPDATE db_stocks_account SET status_accepted='.request('approve_status').',action_date=CURDATE() where stocks_account_code_id_fk='.request('id').' ');

          $stocks_account = DB::select(' SELECT * from db_stocks_account WHERE stocks_account_code_id_fk='.request('id').' AND amt_diff<>0 ');

          // dd($stocks_account);

          foreach ($stocks_account as $key => $value) {
              // echo $value->amt_diff;
              // echo $value->product_id_fk;
              // echo $value->lot_number;
               DB::update(' UPDATE db_stocks SET amt = ( amt + ('.$value->amt_diff.') ) where product_id_fk = "'.$value->product_id_fk.'" AND lot_number="'.$value->lot_number.'" ');
          } 
          // dd();

          $sRow->approver    = request('approver');
          $sRow->status_accepted    = request('approve_status');
          $sRow->approve_date    = date('Y-m-d');
          $sRow->note    = request('note');
          $sRow->save();

          \DB::commit();

        }

           return redirect()->to(url("backend/check_stock_account/".request('id')."/edit?Approve"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        // return redirect()->action('backend\DeliveryController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {

      $sRow = \App\Models\Backend\Check_stock_account::find($id);
      if( $sRow ){
        // $sRow->forceDelete();
        DB::select("UPDATE db_stocks_account_code SET status_accepted='3' WHERE (id=$id)");

      }
      return response()->json(\App\Models\Alert::Msg('success'));

    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Check_stock_account::search()->orderBy('id', 'asc');
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
      ->addColumn('lot_expired_date', function($row) {
        $d = strtotime($row->lot_expired_date); 
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('date_in_stock', function($row) {
        $d = strtotime($row->pickup_firstdate); 
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('warehouses', function($row) {
        $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name;
      })      
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
