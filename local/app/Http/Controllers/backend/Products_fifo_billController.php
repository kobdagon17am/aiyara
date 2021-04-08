<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Products_fifo_billController extends Controller
{

    public function index(Request $request)
    {
      // return view('backend.consignments_import.index');
    }

 public function create()
    {

    }
    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      // return $this->form($id);
    }

   public function form($id=NULL)
    {
      // \DB::beginTransaction();
      // try {
      //     if( $id ){
      //       $sRow = \App\Models\Backend\Consignments::find($id);
      //     }else{
      //       $sRow = new \App\Models\Backend\Consignments;
      //     }

      //     // $sRow->operator    = request('operator');
      //     // $sRow->last_update    = request('last_update');
      //     $sRow->created_at = date('Y-m-d H:i:s');
      //     $sRow->save();


      //     \DB::commit();

      //    // return redirect()->action('backend\Products_fifo_billController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
      //     return redirect()->to(url("backend/pick_warehouse/".$sRow->id."/edit"));

      // } catch (\Exception $e) {
      //   echo $e->getMessage();
      //   \DB::rollback();
      //   return redirect()->action('backend\Products_fifo_billController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      // }
    }

    public function destroy($id)
    {
      // $sRow = \App\Models\Backend\Consignments::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      // $sTable = \App\Models\Backend\Products_fifo_bill::search();
      $c = "SELECT db_frontstore.invoice_code FROM
        db_frontstore_products_list
        Left Join db_frontstore ON db_frontstore_products_list.frontstore_id_fk = db_frontstore.id
        WHERE  db_frontstore_products_list.product_id_fk in(SELECT product_id_fk FROM db_pick_warehouse_fifo_topicked where status=1)";

      $sTable = DB::select("
              SELECT * from db_products_fifo_bill where recipient_code in ($c)
              ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {

        if(!empty($row->recipient_code)){

            $Products = DB::select("
              SELECT
              db_frontstore.invoice_code,
              (SELECT product_code FROM products WHERE id=db_frontstore_products_list.product_id_fk limit 1) as product_code,
              (SELECT product_name FROM products_details WHERE product_id_fk=db_frontstore_products_list.product_id_fk and lang_id=1 limit 1) as product_name,
              db_frontstore_products_list.amt,
              dataset_product_unit.product_unit
              FROM
              db_frontstore_products_list
              Left Join db_frontstore ON db_frontstore_products_list.frontstore_id_fk = db_frontstore.id
              Left Join dataset_product_unit ON db_frontstore_products_list.product_unit_id_fk = dataset_product_unit.id
              WHERE db_frontstore.invoice_code='".$row->recipient_code."' AND db_frontstore_products_list.product_id_fk in(SELECT product_id_fk FROM db_pick_warehouse_fifo_topicked)
              ");

            $pn = '<div class="divTable"><div class="divTableBody">';

            foreach ($Products as $key => $value) {
              $pn .=     
                  '<div class="divTableRow">
                  <div class="divTableCell" style="width:400px;">'.$value->product_code.' : '.$value->product_name.'</div>
                  <div class="divTableCell" style="width:50px;text-align:center;">'.($value->amt).'</div>
                  <div class="divTableCell">'.$value->product_unit.'</div>
                  </div>
                  ';
             }

            $pn .= '</div></div>';  

            return $pn;

        }

      })
      ->escapeColumns('product_name')
      ->addColumn('get_product', function($row) {

        if(!empty($row->recipient_code)){

            $Products = DB::select("
              SELECT
              db_frontstore.invoice_code,
              (SELECT product_code FROM products WHERE id=db_frontstore_products_list.product_id_fk limit 1) as product_code,
              (SELECT product_name FROM products_details WHERE product_id_fk=db_frontstore_products_list.product_id_fk and lang_id=1 limit 1) as product_name,
              db_frontstore_products_list.amt,
              dataset_product_unit.product_unit,
              (SELECT amt_get FROM db_pick_warehouse_tmp WHERE invoice_code=db_frontstore.invoice_code AND product_code=(SELECT product_code FROM products WHERE id=db_frontstore_products_list.product_id_fk limit 1) LIMIT 1) as amt_get
              FROM
              db_frontstore_products_list
              Left Join db_frontstore ON db_frontstore_products_list.frontstore_id_fk = db_frontstore.id
              Left Join dataset_product_unit ON db_frontstore_products_list.product_unit_id_fk = dataset_product_unit.id
              WHERE db_frontstore.invoice_code='".$row->recipient_code."' AND  db_frontstore_products_list.product_id_fk in(SELECT product_id_fk FROM db_pick_warehouse_fifo_topicked)
              ");

            $pn = '<div class="divTable"><div class="divTableBody">';

            foreach ($Products as $key => $value) {
              $pn .=     
                  '<div class="divTableRow">
                  <div class="divTableCell">'.($value->amt_get>0?$value->amt_get:'* รอแจงเบิกจากคลัง ').'</div>
                  </div>
                  ';
             }

            $pn .= '</div></div>';  

            return $pn;

        }

      })      
      ->escapeColumns('get_product')
      ->make(true);
    }




    public function DatatableToSend(){

      $sTable = DB::select("
              SELECT * from db_pick_warehouse_tmp
              ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name_desc', function($row) {

        if(!empty($row->recipient_code)){

            $Products = DB::select("
              SELECT
              db_frontstore.invoice_code,
              (SELECT product_code FROM products WHERE id=db_frontstore_products_list.product_id_fk limit 1) as product_code,
              (SELECT product_name FROM products_details WHERE product_id_fk=db_frontstore_products_list.product_id_fk and lang_id=1 limit 1) as product_name,
              db_frontstore_products_list.amt,
              dataset_product_unit.product_unit
              FROM
              db_frontstore_products_list
              Left Join db_frontstore ON db_frontstore_products_list.frontstore_id_fk = db_frontstore.id
              Left Join dataset_product_unit ON db_frontstore_products_list.product_unit_id_fk = dataset_product_unit.id
              WHERE db_frontstore.invoice_code='".$row->recipient_code."' AND db_frontstore_products_list.product_id_fk in(SELECT product_id_fk FROM db_pick_warehouse_fifo_topicked)
              ");

            $pn = '<div class="divTable"><div class="divTableBody">';

            foreach ($Products as $key => $value) {
              $pn .=     
                  '<div class="divTableRow">
                  <div class="divTableCell" style="width:400px;">'.$value->product_code.' : '.$value->product_name.'</div>
                  <div class="divTableCell" style="width:50px;text-align:center;">'.($value->amt).'</div>
                  <div class="divTableCell">'.$value->product_unit.'</div>
                  </div>
                  ';
             }

            $pn .= '</div></div>';  

            return $pn;

        }

      })
      ->escapeColumns('product_name')
      ->make(true);
    }


    public function DatatableConsignments(){

      $sTable = DB::select("
              SELECT * from db_pick_warehouse_consignments
              ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->make(true);
    }





}
