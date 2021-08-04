<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Transfer_branch_get_productsController extends Controller
{

    public function index(Request $request)
    {

      //  $sAction_user = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");
      //  $sBusiness_location = \App\Models\Backend\Business_location::get();
      //  $sBranchs = \App\Models\Backend\Branchs::get();
      //  $Supplier = DB::select(" select * from dataset_supplier ");
      //  $tr_number = DB::select(" SELECT tr_number FROM `db_transfer_branch_get` where branch_id_fk=".(\Auth::user()->branch_id_fk)." ");
      // return View('backend.transfer_branch_get_products.index')->with(
      //   array(
      //      'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs,'Supplier'=>$Supplier,
      //      'sAction_user'=>$sAction_user,
      //      'tr_number'=>$tr_number,
      //   ) );
      // return view('backend.transfer_branch_get_products.index');
      
    }

 public function create(Request $request)
    {

      // dd($request->all());
      //  $po_supplier = DB::select(" SELECT CONVERT(SUBSTRING(tr_number, -3),UNSIGNED INTEGER) AS cnt FROM db_transfer_branch_get WHERE tr_number is not null  order by tr_number desc limit 1  ");
      //  // dd($po_supplier[0]->cnt);
      //  $po_runno = "PO".date("ymd").sprintf("%03d", $po_supplier[0]->cnt + 1);
      //  // dd($po_runno);

      //  $sBusiness_location = \App\Models\Backend\Business_location::get();
      //  $sBranchs = \App\Models\Backend\Branchs::get();
      //  $Supplier = DB::select(" select * from dataset_supplier ");
      // return View('backend.transfer_branch_get_products.form')->with(
      //   array(
      //      'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs
      //      ,'Supplier'=>$Supplier
      //      ,'po_runno'=>$po_runno
      //   ) );
    }
    public function store(Request $request)
    {
        // dd($request->all());
        if(isset($request->save_set_to_warehouse)){
            // dd($request->all());
            /*
            array:15 [▼
                  "save_set_to_warehouse" => "1"
                  "id" => "1"
                  "transfer_branch_get_products_id_fk" => "1"
                  "transfer_branch_get_id_fk" => "1"
                  "product_id_fk" => "1"
                  "_token" => "GDgls0vOEvDfCMCM4jtwiujWWfMuZ0ZnVGotYdhB"
                  "amt_get" => "3"
                  "product_unit_id_fk" => "2"
                  "lot_number" => "3"
                  "lot_expired_date" => "2021-07-31"
                  "branch_id_fk_c" => "1"
                  "warehouse_id_fk_c" => "1"
                  "zone_id_fk_c" => "1"
                  "shelf_id_fk_c" => "1"
                  "shelf_floor_c" => "3"
                ]
                */

        DB::select(" INSERT INTO `db_transfer_branch_get_products_receive`  (
                      `transfer_branch_get_id_fk`,
                      `transfer_branch_get_products`,
                      `product_id_fk`,
                      `lot_number`,
                      `lot_expired_date`,
                      `amt_get`,
                      `product_unit_id_fk`,
                      `branch_id_fk`,
                      `warehouse_id_fk`, 
                      `zone_id_fk`, 
                      `shelf_id_fk`, 
                      `shelf_floor`, 
                      `action_user`,
                      `action_date`
                      ) VALUES (
                      '$request->transfer_branch_get_id_fk',
                      '$request->transfer_branch_get_products_id_fk',
                      '$request->product_id_fk',
                      '$request->lot_number',
                      '$request->lot_expired_date',
                      '$request->amt_get',
                      '$request->product_unit_id_fk',
                      '$request->branch_id_fk_c',
                      '$request->warehouse_id_fk_c',
                      '$request->zone_id_fk_c',
                      '$request->shelf_id_fk_c',
                      '$request->shelf_floor_c',
                      '". \Auth::user()->id."',
                       now())
                      ");

               DB::select("
                    UPDATE db_transfer_branch_get_products SET product_amt_receive=(SELECT sum(amt_get) as sum_amt FROM `db_transfer_branch_get_products_receive` WHERE transfer_branch_get_id_fk=".$request->transfer_branch_get_id_fk." AND transfer_branch_get_products=".$request->transfer_branch_get_products_id_fk." AND product_id_fk=".$request->product_id_fk.")
                    WHERE id=".$request->transfer_branch_get_products_id_fk." ;
                ");

              DB::select(" UPDATE `db_transfer_branch_get_products` SET `get_status`='1' where id=".$request->transfer_branch_get_products_id_fk." AND product_amt=product_amt_receive; ");

              DB::select(" UPDATE `db_transfer_branch_get_products` SET `get_status`='2' where id=".$request->transfer_branch_get_products_id_fk." AND product_amt<product_amt_receive ; ");

              DB::select(" UPDATE
                db_transfer_branch_get
                Inner Join db_transfer_branch_get_products ON db_transfer_branch_get.id = db_transfer_branch_get_products.transfer_branch_get_id_fk
                SET
                db_transfer_branch_get.tr_status=db_transfer_branch_get_products.get_status
                WHERE db_transfer_branch_get.id=".$request->transfer_branch_get_id_fk." AND db_transfer_branch_get_products.get_status=1 ");


              return redirect()->to(url("backend/transfer_branch_get/".$request->transfer_branch_get_id_fk."/edit"));

        }else{
            return $this->form();
        }
      // 
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Transfer_branch_get_products::find($id);
       // dd($sRow);
        $sBusiness_location = \App\Models\Backend\Business_location::get();
        $sBranchs = \App\Models\Backend\Branchs::get();
        $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
        $sUserAdmin = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");

       return View('backend.transfer_branch_get_products.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id,
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs,
           'sProductUnit'=>$sProductUnit,
           'sUserAdmin'=>$sUserAdmin,
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
            $sRow = \App\Models\Backend\Transfer_branch_get_products::find($id);
          }else{
            $sRow = new \App\Models\Backend\Transfer_branch_get_products;
            // รหัสหลักๆ จะถูกสร้างตั้งแต่เลือกรหัสใบโอนมาจากสาขาที่ส่งสินค้ามาให้แล้ว
            // $sRow->business_location_id_fk    = request('business_location_id_fk');
            // $sRow->branch_id_fk    =  \Auth::user()->branch_id_fk;
            // $sRow->tr_number    = request('tr_number');
          }
          $sRow->action_user    = request('action_user');
          $sRow->tr_status    = request('tr_status');
          $sRow->note    = request('note');
          $sRow->created_at    = request('created_at');
          // $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/transfer_branch_get/".$sRow->id."/edit"));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        // return redirect()->action('backend\Transfer_branch_get_productsController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Transfer_branch_get_products::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){

      $sTable = \App\Models\Backend\Transfer_branch_get_products::search()->orderBy('id', 'asc');

      $sQuery = \DataTables::of($sTable);
     return $sQuery
      ->addColumn('product_name', function($row) {
                  // return $row->product_unit_id_fk;
        if(!empty($row->product_id_fk)){

          $Products = DB::select(" 
                SELECT products.id as product_id,
                  products.product_code,
                  (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name ,
                  products_cost.selling_price,
                  products_cost.pv
                  FROM
                  products_details
                  Left Join products ON products_details.product_id_fk = products.id
                  LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                  WHERE lang_id=1 AND products.id= ".$row->product_id_fk."

           ");

           return  @$Products[0]->product_code." : ".@$Products[0]->product_name;

         }
      })
      ->addColumn('product_unit_desc', function($row) {
          $sP = \App\Models\Backend\Product_unit::find($row->product_unit);
          return $sP->product_unit;
      })
      ->addColumn('get_status', function($row) {
        if($row->product_amt==$row->product_amt_receive) @$get_status=1;
        if($row->product_amt>$row->product_amt_receive) @$get_status=2;
        if(@$get_status==1){
          return '<font color=green>ได้รับสินค้าครบแล้ว</font>';
        }else if(@$get_status==2){
          return '<font color=red>ยังค้างรับสินค้า</font>';
        }else if(@$row->get_status==3){
          return 'ยกเลิกรายการสินค้านี้';
        }else{
          return 'อยู่ระหว่างการดำเนินการ';
        }
      })
      ->escapeColumns('get_status')
      ->addColumn('get_status_2', function($row) {
          return @$row->get_status;
      })
      ->make(true);
    }




    public function Datatable02(Request $req){

      $sTable = DB::select("select * from db_transfer_branch_get_products_receive where transfer_branch_get_id_fk=".$req->transfer_branch_get_id_fk." ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {
        if(!empty($row->product_id_fk)){

          $Products = DB::select(" 
                SELECT products.id as product_id,
                  products.product_code,
                  (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name ,
                  products_cost.selling_price,
                  products_cost.pv
                  FROM
                  products_details
                  Left Join products ON products_details.product_id_fk = products.id
                  LEFT JOIN products_cost on products.id = products_cost.product_id_fk
                  WHERE lang_id=1 AND products.id= ".$row->product_id_fk."

           ");

           return  @$Products[0]->product_code." : ".@$Products[0]->product_name;

         }
      })
      ->addColumn('product_unit_desc', function($row) {
          $sP = \App\Models\Backend\Product_unit::find($row->product_unit_id_fk);
          return $sP->product_unit;
      })
       ->addColumn('warehouses', function($row) {
        $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        // return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;
        return @$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;
      })   
      ->make(true);
    }



}
