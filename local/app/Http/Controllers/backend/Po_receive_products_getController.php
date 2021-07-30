<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Redirect;

class Po_receive_products_getController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.po_receive_products_get.index');

    }

  public function create($id)
    {
       // dd($id);
       $Po_supplier_products = \App\Models\Backend\Po_supplier_products::find($id);
       // dd($Po_supplier_products);

       $Po_supplier = \App\Models\Backend\Po_supplier::find($Po_supplier_products->po_supplier_id_fk);
       // dd($Po_supplier);
       $sProduct = \App\Models\Backend\Products_details::where('lang_id', 1)->get();
        $sGetStatus = DB::select(" select * from dataset_get_product_status  ");
       return View('backend.po_receive_products_get.form')->with(array(
        'Po_supplier_products'=>$Po_supplier_products,
        'Po_supplier'=>$Po_supplier,
        'sProduct'=>$sProduct,
         'sGetStatus'=>$sGetStatus,
         ) );
       // return View('backend.po_supplier_products_get.form');

    }

    public function store(Request $request)
    {
      // dd($request->all()); 
      if(isset($request->save_set_to_warehouse)){
        // dd($request->all());  
        // เช็คยอด ว่า ครบแล้วหรือไม่ ถ้าครบแล้ว ก็อัพเดตสถานะ ว่าได้รับครบแล้ว ถ้ายัง ก็เป็น ค้างรับสินค้า


        DB::select(" INSERT INTO `db_po_supplier_products_receive` (
                      `po_supplier_products_id_fk`,
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
                      '$request->po_supplier_products_id_fk',
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

                UPDATE `db_po_supplier_products` SET product_amt_receive=(SELECT sum(amt_get) as sum_amt FROM db_po_supplier_products_receive WHERE po_supplier_products_id_fk=".$request->po_supplier_products_id_fk." AND product_id_fk=".$request->product_id_fk.")
                WHERE id=".$request->po_supplier_products_id_fk." ;
                ");

              DB::select(" UPDATE `db_po_supplier_products` SET `get_status`='1' where id=".$request->po_supplier_products_id_fk." AND product_amt=product_amt_receive; ");
              DB::select(" UPDATE `db_po_supplier_products` SET `get_status`='2' where id=".$request->po_supplier_products_id_fk." AND product_amt>product_amt_receive; ");

              $r = DB::select("SELECT * FROM `db_po_supplier_products` where id=".$request->po_supplier_products_id_fk." ");

              return redirect()->to(url("backend/po_receive/".$r[0]->po_supplier_id_fk."/edit"));

      }else{
        return $this->form();
      }
      
    }

    public function edit($id)
    {
        $sRow = \App\Models\Backend\Po_supplier_products_get::find($id);
        // dd($sRow);
        $Po_supplier_products = \App\Models\Backend\Po_supplier_products::find($sRow->po_supplier_products_id_fk);
        // dd($Po_supplier_products);
        $Po_supplier = \App\Models\Backend\Po_supplier::find($Po_supplier_products->po_supplier_id_fk);
        // dd($Po_supplier);
        $sProduct = \App\Models\Backend\Products_details::where('lang_id', 1)->get();
        $sGetStatus = DB::select(" select * from dataset_get_product_status  ");
       return View('backend.po_receive_products_get.form')->with(array('sRow'=>$sRow ,
        'sRow'=>$sRow,
        'Po_supplier_products'=>$Po_supplier_products,
        'Po_supplier'=>$Po_supplier,
        'sProduct'=>$sProduct,
        'sGetStatus'=>$sGetStatus,
         ));
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
        // dd($id);
          if( $id ){
            $sRow = \App\Models\Backend\Po_supplier_products_get::find($id);
          }else{
            $sRow = new \App\Models\Backend\Po_supplier_products_get;

            $db_po_supplier_products_get = DB::select(" SELECT time_get FROM `db_po_supplier_products_get` where po_supplier_products_id_fk=".request('po_supplier_products_id_fk')." order by time_get desc ");
            if($db_po_supplier_products_get){
                $sRow->time_get = $db_po_supplier_products_get[0]->time_get + 1 ;

            }else{
                $sRow->time_get = 1 ;
            }
            
          }

          $sRow->po_supplier_products_id_fk    = request('po_supplier_products_id_fk');
          $sRow->amt    = request('amt');
          $sRow->get_status    = request('get_status');
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          if(request('get_status')){

              DB::select(" UPDATE `db_po_supplier_products` SET get_status=".request('get_status')." where id=".request('po_supplier_products_id_fk')." ");

              @$r1 = DB::select(" SELECT * FROM `db_po_supplier_products` where id=".request('po_supplier_products_id_fk')." ");
              @$r2 = DB::select(" SELECT * FROM `db_po_supplier_products` where po_supplier_id_fk in(".$r1[0]->po_supplier_id_fk.") AND get_status=2 ");
              if(@$r2){
                DB::select(" UPDATE `db_po_supplier` SET po_status=2 where id=".@$r2[0]->po_supplier_id_fk." ");
              }else{
                @$r3 = DB::select(" SELECT * FROM `db_po_supplier_products` where po_supplier_id_fk in(".@$r1[0]->po_supplier_id_fk.") AND get_status not in (2) ");
                if(@$r3){
                  DB::select(" UPDATE `db_po_supplier` SET po_status=1 where id=".@$r3[0]->po_supplier_id_fk." ");
                }
              }

          }

          \DB::commit();

          return redirect()->to(url("backend/po_receive_products/".request('po_supplier_products_id_fk')."/edit"));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        // return redirect()->action('backend\Po_receive_products_getController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {


       if($id){
        
        $r = DB::select("SELECT *  FROM `db_po_supplier_products_receive` WHERE (`id`='$id')");


        DB::select("DELETE FROM `db_po_supplier_products_receive` WHERE (`id`='$id')");


        DB::select("
          UPDATE `db_po_supplier_products` SET product_amt_receive=((SELECT sum(amt_get) as sum_amt FROM db_po_supplier_products_receive WHERE po_supplier_products_id_fk=".$r[0]->po_supplier_products_id_fk." AND product_id_fk=".$r[0]->product_id_fk.") - ".$r[0]->amt_get." ) 
          WHERE id=".$r[0]->po_supplier_products_id_fk." ;
        ");

        DB::select(" UPDATE `db_po_supplier_products` SET `get_status`='1' where id=".$r[0]->po_supplier_products_id_fk." AND product_amt=product_amt_receive; ");
        DB::select(" UPDATE `db_po_supplier_products` SET `get_status`='2' where id=".$r[0]->po_supplier_products_id_fk." AND product_amt>product_amt_receive; ");
        
      }
      // $sRow = \App\Models\Backend\Po_supplier_products::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

  
    public function Datatable(Request $req){
      // $sTable = DB::select("select * from db_po_supplier_products_get where id=$req->po_supplier_products_id_fk  ");
      $sTable = \App\Models\Backend\Po_supplier_products_get::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('get_status', function($row) {
        if(@$row->get_status==1){
          return 'ได้รับสินค้าครบแล้ว';
        }else if(@$row->get_status==2){
          return 'ยังค้างรับสินค้าจาก Supplier';
        }else if(@$row->get_status==3){
          return 'ยกเลิกรายการสินค้านี้';
        }else{
          return 'อยู่ระหว่างการดำเนินการ';
        }
      })
      ->make(true);
    }



    public function DatatablePO_receive(Request $req){

      $d1 = DB::select("select * from db_po_supplier_products where po_supplier_id_fk=".$req->po_supplier_id_fk."  ");

      $arr = [];
      if($d1){
        foreach ($d1 as $key => $value) {
           array_push($arr, $value->id);
        }

        $db_po_supplier_products_id = implode(',', $arr);
      }else{
        $db_po_supplier_products_id = 0;
      }

      $sTable = DB::select("select * from db_po_supplier_products_receive where po_supplier_products_id_fk in ($db_po_supplier_products_id)  ");

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
