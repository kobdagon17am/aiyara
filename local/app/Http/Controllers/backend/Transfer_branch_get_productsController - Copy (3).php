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

              $ch = DB::select("SELECT count(*) as cnt FROM `db_transfer_branch_get_products` WHERE product_amt=product_amt_receive AND transfer_branch_get_id_fk=".$request->transfer_branch_get_id_fk."");

              if($ch[0]->cnt>0){
                 DB::select(" UPDATE `db_transfer_branch_get` SET tr_status_get=4 where id=".$request->transfer_branch_get_id_fk." ");

                  $r =  DB::select(" SELECT tr_number from `db_transfer_branch_get`  where id=".$request->transfer_branch_get_id_fk." ");
                 DB::select(" UPDATE `db_transfer_branch_code` SET tr_status_from=4 where tr_number=".$r[0]->tr_number." ");

              }else{
                 DB::select(" UPDATE `db_transfer_branch_get` SET tr_status_get=3 where id=".$request->transfer_branch_get_id_fk." ");
                 DB::select(" UPDATE `db_transfer_branch_get_products` SET `get_status`='2' WHERE transfer_branch_get_id_fk=".$request->transfer_branch_get_id_fk." ");
              }

              return redirect()->to(url("backend/transfer_branch_get/".$request->transfer_branch_get_id_fk."/edit"));

        }elseif(isset($request->save_set_to_warehouse_fromnoget)){

          // dd($request->all());

          /*
array:15 [▼
  "save_set_to_warehouse_fromnoget" => "1"
  "id" => "2"
  "transfer_branch_get_products_id_fk" => "2"
  "transfer_branch_get_id_fk" => "2"
  "product_id_fk" => "1"
  "_token" => "ba4IBy1iD07WuiJjoV0tSDMC5bvV09VWTXFwtY8V"
  "lot_number" => "lot1111"
  "lot_expired_date" => "2021-08-31"
  "product_unit_id_fk" => "4"
  "branch_id_fk_c" => "1"
  "amt_get" => "5"
  "warehouse_id_fk_c" => "1"
  "zone_id_fk_c" => "1"
  "shelf_id_fk_c" => "1"
  "shelf_floor_c" => "2"
]*/
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


            $ch = DB::select("SELECT count(*) as cnt FROM `db_transfer_branch_get_products` WHERE product_amt=product_amt_receive AND transfer_branch_get_id_fk=".$request->transfer_branch_get_id_fk." ");

              if($ch[0]->cnt>0){
                $r =  DB::select(" SELECT tr_number from `db_transfer_branch_get`  where id=".$request->transfer_branch_get_id_fk." ");
                 DB::select(" UPDATE `db_transfer_branch_code` SET tr_status_from='6' where tr_number=".$r[0]->tr_number." ");
              }else{
                 DB::select(" UPDATE `db_transfer_branch_get_products` SET `get_status`='2' WHERE transfer_branch_get_id_fk=".$request->transfer_branch_get_id_fk." ");
              }


           return redirect()->to(url("backend/transfer_branch_get/noget/".$request->id));

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
          $sRow->tr_status_get    = request('tr_status_get');
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


      $sTable = \App\Models\Backend\Transfer_branch_get_products::search()->orderBy('updated_at', 'desc');

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('branch_sent_name', function($row) {

          $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".$row->transfer_branch_get_id_fk." ");
          $db_transfer_branch_code = DB::select(" select branch_id_fk from `db_transfer_branch_code` where tr_number='".$db_transfer_branch_get[0]->tr_number."' ");
          $sBranchs = DB::select(" select * from branchs where id=".$db_transfer_branch_code[0]->branch_id_fk." ");
          return @$sBranchs[0]->b_name;
      })
      ->addColumn('branch_sent_id', function($row) {
          $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".$row->transfer_branch_get_id_fk." ");
          $db_transfer_branch_code = DB::select(" select branch_id_fk from `db_transfer_branch_code` where tr_number='".$db_transfer_branch_get[0]->tr_number."' ");
          return @$db_transfer_branch_code[0]->branch_id_fk;
      }) 
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
      ->addColumn('tr_status_get', function($row) {
          $d = DB::select(" select tr_status_get from db_transfer_branch_get where id=".$row->transfer_branch_get_id_fk." ");
          if($d){
            return $d[0]->tr_status_get;
          }else{
            return 0 ;
          }
          
      })
      ->addColumn('product_details', function($row) {
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

                $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".$row->transfer_branch_get_id_fk." ");
                $db_transfer_branch_code = DB::select(" select id from `db_transfer_branch_code` where tr_number='".$db_transfer_branch_get[0]->tr_number."' ");
                $db_transfer_branch_details = DB::select(" select lot_number,lot_expired_date from `db_transfer_branch_details` where transfer_branch_code_id='".$db_transfer_branch_code[0]->id."' ");

                $Product_unit = \App\Models\Backend\Product_unit::find($row->product_unit);

                $p = @$Products[0]->product_code." : ".@$Products[0]->product_name."<br>";
                $p .= "จำนวนสินค้าตามใบโอน : ".@$row->product_amt." ".$Product_unit->product_unit."<br>";
                $p .= "Lot Number : ".@$db_transfer_branch_details[0]->lot_number."&nbsp;&nbsp;&nbsp; Lot expired date : ".@$db_transfer_branch_details[0]->lot_expired_date;

                return  $p;

         }
      })
      ->escapeColumns('product_details')
      ->addColumn('branch_name', function($row) {

          $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".$row->transfer_branch_get_id_fk." ");
          $db_transfer_branch_code = DB::select(" select id from `db_transfer_branch_code` where tr_number='".$db_transfer_branch_get[0]->tr_number."' ");
          $db_transfer_branch_details = DB::select(" select branch_id_fk from `db_transfer_branch_details` where transfer_branch_code_id='".$db_transfer_branch_code[0]->id."' ");
          $sBranchs = DB::select(" select * from branchs where id=".$db_transfer_branch_details[0]->branch_id_fk." ");
          return @$sBranchs[0]->b_name;
      }) 
      ->escapeColumns('branch_name')
      ->addColumn('branch_id_this', function($row) {
          $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".$row->transfer_branch_get_id_fk." ");
          $db_transfer_branch_code = DB::select(" select id from `db_transfer_branch_code` where tr_number='".$db_transfer_branch_get[0]->tr_number."' ");
          $db_transfer_branch_details = DB::select(" select branch_id_fk from `db_transfer_branch_details` where transfer_branch_code_id='".$db_transfer_branch_code[0]->id."' ");
          return @$db_transfer_branch_details[0]->branch_id_fk;
      }) 
      ->escapeColumns('branch_id_this')
      ->addColumn('product_unit_id_fk', function($row) {
          if($row->product_unit) return $row->product_unit;
      })
      ->addColumn('lot_number', function($row) {
            $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".$row->transfer_branch_get_id_fk." ");
            $db_transfer_branch_code = DB::select(" select id from `db_transfer_branch_code` where tr_number='".$db_transfer_branch_get[0]->tr_number."' ");
            $db_transfer_branch_details = DB::select(" select lot_number,lot_expired_date from `db_transfer_branch_details` where transfer_branch_code_id='".$db_transfer_branch_code[0]->id."' ");
            return $db_transfer_branch_details[0]->lot_number;
      })
      ->addColumn('lot_expired_date', function($row) {
            $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".$row->transfer_branch_get_id_fk." ");
            $db_transfer_branch_code = DB::select(" select id from `db_transfer_branch_code` where tr_number='".$db_transfer_branch_get[0]->tr_number."' ");
            $db_transfer_branch_details = DB::select(" select lot_number,lot_expired_date from `db_transfer_branch_details` where transfer_branch_code_id='".$db_transfer_branch_code[0]->id."' ");
            return $db_transfer_branch_details[0]->lot_expired_date;
      })
      
      ->addColumn('lot_number_desc', function($row) {
            $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".$row->transfer_branch_get_id_fk." ");
            $db_transfer_branch_code = DB::select(" select id from `db_transfer_branch_code` where tr_number='".$db_transfer_branch_get[0]->tr_number."' ");
            $db_transfer_branch_details = DB::select(" select lot_number,lot_expired_date from `db_transfer_branch_details` where transfer_branch_code_id='".$db_transfer_branch_code[0]->id."' ");
            return $db_transfer_branch_details[0]->lot_number."<br> (".$db_transfer_branch_details[0]->lot_expired_date.")";
      })
      ->escapeColumns('lot_number_desc')

      ->addColumn('tr_number', function($row) {
            $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".$row->transfer_branch_get_id_fk." ");
            return $db_transfer_branch_get[0]->tr_number;
      })
      ->addColumn('branch_source', function($row) {
            $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".$row->transfer_branch_get_id_fk." ");
            $db_transfer_branch_code = DB::select(" select branch_id_fk from `db_transfer_branch_code` where tr_number='".$db_transfer_branch_get[0]->tr_number."' ");
            $sBranchs = DB::select(" select * from branchs where id=".$db_transfer_branch_code[0]->branch_id_fk." ");
           return @$sBranchs[0]->b_name;
      })
      ->addColumn('approve_date_get', function($row) {
            $sD = DB::select(" select approve_date from db_transfer_branch_get where id=".@$row->transfer_branch_get_id_fk." ");
           if(!is_null(@$sD[0]->approve_date)){
                return date("Y-m-d",strtotime(@$sD[0]->approve_date))."<br>".date("H:i:s",strtotime(@$sD[0]->approve_date));
            }
      })
      ->addColumn('approve_status_get', function($row) {

        // $sD = DB::select(" select approve_status,note2,tr_status_get,note3,updated_at from `db_transfer_branch_get` where id=".$row->transfer_branch_get_id_fk." ");
        // if(@$sD){
        //   if(@$sD[0]->approve_status==1){
        //     return '<span style="color:green;">รับสินค้าแล้ว</span>';
        //   }elseif(@$sD[0]->approve_status==5){

        //     $t = '';
        //     if(@$sD[0]->approve_status_getback==1){
        //       $t .= '<br><span style="color:green">รับสินค้าคืนแล้ว ('.date("Y-m-d",strtotime(@$sD[0]->updated_at)).')</span>';
        //       $t .= '<br><span style="color:green">'.@$sD[0]->note3.'</span>';
        //     }elseif(@$sD[0]->approve_status_getback==5){
        //       $t .= '<br><span style="color:green">ปฏิเสธการรับสินค้าคืน ('.date("Y-m-d",strtotime(@$sD[0]->updated_at)).')</span>';
        //       $t .= '<br><span style="color:green">'.@$sD[0]->note3.'</span>';
        //     }

        //     $n = @$sD[0]->note2?"<br>หมายเหตุ ".@$sD[0]->note2:'';
        //     return '<span style="color:red;">ปฏิเสธการรับสินค้า</span>'.$n.$t;

        //   }else{
        //     return 'อยู่ระหว่างการโอน';
        //   }
        // }


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
        if($row->product_unit_id_fk){
          $sP = \App\Models\Backend\Product_unit::find($row->product_unit_id_fk);
          return $sP->product_unit;
        }
      })
       ->addColumn('warehouses', function($row) {
        $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        // return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;
        return @$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name.'/ชั้น>'.@$row->shelf_floor;
      })   
      ->addColumn('tr_status_get', function($row) {
          $d = DB::select(" select tr_status_get from db_transfer_branch_get where id=".$row->transfer_branch_get_id_fk." ");
          if($d){
            return $d[0]->tr_status_get;
          }else{
            return 0 ;
          }
          
      })
      ->make(true);
    }


    public function Datatable03(Request $req){


      $sTable = \App\Models\Backend\Transfer_branch_code::whereIn('tr_status_get',[2,4,5]) 
      // ->where(function ($query) use ($w01,$w02) {
      //     $query->whereIn('transfer_branch_get_id_fk', $w01)
      //           ->orWhereIn('transfer_branch_get_id_fk', $w02);
      // })
      ->orderBy('updated_at', 'desc')
      ;


      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {

         // $sP = DB::select("SELECT * FROM `db_transfer_branch_get_products` where transfer_branch_get_id_fk=$row->id ");

        // if(!empty($row->product_id_fk)){

        //   $Products = DB::select(" 
        //         SELECT products.id as product_id,
        //           products.product_code,
        //           (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name ,
        //           products_cost.selling_price,
        //           products_cost.pv
        //           FROM
        //           products_details
        //           Left Join products ON products_details.product_id_fk = products.id
        //           LEFT JOIN products_cost on products.id = products_cost.product_id_fk
        //           WHERE lang_id=1 AND products.id= ".$row->product_id_fk."

        //    ");

        //    return  @$Products[0]->product_code." : ".@$Products[0]->product_name;
        //  }

        return " * อยู่ระหว่างการปรับปรุงคอลัมน์ ";

      })
      ->escapeColumns('product_name')

      // ->addColumn('product_unit_desc', function($row) {
      //     $sP = \App\Models\Backend\Product_unit::find($row->product_unit);
      //     return @$sP->product_unit;
      // })
      // ->addColumn('get_status', function($row) {

      // })
      // ->escapeColumns('get_status')
      // ->addColumn('get_status_2', function($row) {
      //     return @$row->get_status;
      // })
      // ->addColumn('tr_status_get', function($row) {
      //     $d = DB::select(" select tr_status_get from db_transfer_branch_get where id=".@$row->transfer_branch_get_id_fk." ");
      //     if(@$d){
      //       return @$d[0]->tr_status_get;
      //     }else{
      //       return 0 ;
      //     }
          
      // })
      // ->addColumn('product_details', function($row) {
      //       if(!empty($row->product_id_fk)){

      //           $Products = DB::select(" 
      //                 SELECT products.id as product_id,
      //                   products.product_code,
      //                   (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name ,
      //                   products_cost.selling_price,
      //                   products_cost.pv
      //                   FROM
      //                   products_details
      //                   Left Join products ON products_details.product_id_fk = products.id
      //                   LEFT JOIN products_cost on products.id = products_cost.product_id_fk
      //                   WHERE lang_id=1 AND products.id= ".$row->product_id_fk."

      //            ");

      //           $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".@$row->transfer_branch_get_id_fk." ");
      //           $db_transfer_branch_code = DB::select(" select id from `db_transfer_branch_code` where tr_number='".@$db_transfer_branch_get[0]->tr_number."' ");
      //           $db_transfer_branch_details = DB::select(" select lot_number,lot_expired_date from `db_transfer_branch_details` where transfer_branch_code_id='".@$db_transfer_branch_code[0]->id."' ");

      //           $Product_unit = \App\Models\Backend\Product_unit::find($row->product_unit);

      //           $p = @$Products[0]->product_code." : ".@$Products[0]->product_name."<br>";
      //           $p .= "จำนวนสินค้าตามใบโอน : ".@$row->product_amt." ".$Product_unit->product_unit."<br>";
      //           $p .= "Lot Number : ".@$db_transfer_branch_details[0]->lot_number."&nbsp;&nbsp;&nbsp; Lot expired date : ".@$db_transfer_branch_details[0]->lot_expired_date;

      //           return  $p;

      //    }
      // })
      // ->escapeColumns('product_details')
      // ->addColumn('branch_name', function($row) {

      //     // $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".@$row->transfer_branch_get_id_fk." ");
      //     // $db_transfer_branch_code = DB::select(" select id from `db_transfer_branch_code` where tr_number='".@$db_transfer_branch_get[0]->tr_number."' ");
      //     // $db_transfer_branch_details = DB::select(" select branch_id_fk from `db_transfer_branch_details` where transfer_branch_code_id='".@$db_transfer_branch_code[0]->id."' ");
      //     // $sBranchs = DB::select(" select * from branchs where id=".@$db_transfer_branch_details[0]->branch_id_fk." ");
      //     // return @$sBranchs[0]->b_name;
      // }) 
      // ->escapeColumns('branch_name')
      // ->addColumn('branch_id_this', function($row) {
      //     // $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".@$row->transfer_branch_get_id_fk." ");
      //     // $db_transfer_branch_code = DB::select(" select id from `db_transfer_branch_code` where tr_number='".@$db_transfer_branch_get[0]->tr_number."' ");
      //     // $db_transfer_branch_details = DB::select(" select branch_id_fk from `db_transfer_branch_details` where transfer_branch_code_id='".@$db_transfer_branch_code[0]->id."' ");
      //     // return @$db_transfer_branch_details[0]->branch_id_fk;
      // }) 
      // ->escapeColumns('branch_id_this')
      // ->addColumn('product_unit_id_fk', function($row) {
      //     if(@$row->product_unit) return @$row->product_unit;
      // })
      // ->addColumn('lot_number', function($row) {
      //       $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".@$row->transfer_branch_get_id_fk." ");
      //       $db_transfer_branch_code = DB::select(" select id from `db_transfer_branch_code` where tr_number='".@$db_transfer_branch_get[0]->tr_number."' ");
      //       $db_transfer_branch_details = DB::select(" select lot_number,lot_expired_date from `db_transfer_branch_details` where transfer_branch_code_id='".@$db_transfer_branch_code[0]->id."' ");
      //       return @$db_transfer_branch_details[0]->lot_number;
      // })
      // ->addColumn('lot_expired_date', function($row) {
      //       $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".@$row->transfer_branch_get_id_fk." ");
      //       $db_transfer_branch_code = DB::select(" select id from `db_transfer_branch_code` where tr_number='".@$db_transfer_branch_get[0]->tr_number."' ");
      //       $db_transfer_branch_details = DB::select(" select lot_number,lot_expired_date from `db_transfer_branch_details` where transfer_branch_code_id='".@$db_transfer_branch_code[0]->id."' ");
      //       return @$db_transfer_branch_details[0]->lot_expired_date;
      // })
      
      // ->addColumn('lot_number_desc', function($row) {
      //       $db_transfer_branch_get = DB::select(" select tr_number from db_transfer_branch_get where id=".@$row->transfer_branch_get_id_fk." ");
      //       $db_transfer_branch_code = DB::select(" select id from `db_transfer_branch_code` where tr_number='".@$db_transfer_branch_get[0]->tr_number."' ");
      //       $db_transfer_branch_details = DB::select(" select lot_number,lot_expired_date from `db_transfer_branch_details` where transfer_branch_code_id='".@$db_transfer_branch_code[0]->id."' ");
      //       return @$db_transfer_branch_details[0]->lot_number."<br> (".@$db_transfer_branch_details[0]->lot_expired_date.")";
      // })
      // ->escapeColumns('lot_number_desc')

      ->addColumn('tr_number', function($row) {
            return "<div data-toggle='tooltip' data-placement='right' title='วันที่สร้างใบโอน ".(date("d/m/Y",strtotime(@$row->created_at)))."' >".@$row->tr_number."</div>";
      })
      ->escapeColumns('tr_number')
      ->addColumn('tr_status_get', function($row) {
        if($row->tr_status_get){

            $t1 = '';
            $t2 = '';
            $n1 = '';
            $n2 = '';

             $Transfer_branch_status_01 = \App\Models\Backend\Transfer_branch_status_01::where('id',$row->tr_status_get)->get();
             $t1 .= @$Transfer_branch_status_01[0]->txt_desc;
            

              $sD = DB::select(" select approve_status,note2,tr_status_get,note3,updated_at from `db_transfer_branch_get` where tr_number='".$row->tr_number."' ");

              if(@$sD){
                
                       if(@$sD[0]->tr_status_get==4 || @$sD[0]->tr_status_get==5){

                        $t2 .= '<span style="color:red">ปฏิเสธรับ</span><br>';
       
                       }

                    $t2 .= '<span style="color:green">Note: '.@$sD[0]->note2.'</span><br>';


                }

           return @$t2.@$t1;

        }

      })
      ->escapeColumns('tr_status_get')

      ->addColumn('branch_from', function($row) {
          if($row->branch_id_fk){
            $sBranchs = \App\Models\Backend\Branchs::where('id',$row->branch_id_fk)->get();
            return @$sBranchs[0]->b_name;
          }

      })
      ->escapeColumns('branch_from')
      ->addColumn('branch_to', function($row) {
           $r = DB::select("
            SELECT
            db_transfer_branch_details.branch_id_fk
            FROM
            db_transfer_branch_details
            Inner Join db_transfer_branch_code ON db_transfer_branch_details.transfer_branch_code_id = db_transfer_branch_code.id
            WHERE db_transfer_branch_code.id=$row->id
            GROUP BY db_transfer_branch_details.transfer_branch_code_id");

          if($r){
            $sBranchs = \App\Models\Backend\Branchs::where('id',$r[0]->branch_id_fk)->get();
            return @$sBranchs[0]->b_name;
          }

      })
      ->escapeColumns('branch_to')
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })               
      ->make(true);
    }


}
