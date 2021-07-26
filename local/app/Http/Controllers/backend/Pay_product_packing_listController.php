<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class Pay_product_packing_listController extends Controller
{

    public function index(Request $request)
    {

      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $customer = DB::select(" SELECT
              customers.user_name AS cus_code,
              customers.prefix_name,
              customers.first_name,
              customers.last_name,
              db_pay_product_receipt.customer_id
              FROM
              db_pay_product_receipt
              left Join customers ON db_pay_product_receipt.customer_id = customers.id
              GROUP BY db_pay_product_receipt.customer_id
              ");

        return View('backend.pay_product_packing_list.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs
           ,'customer'=>$customer,
        ) );
    }


    public function create()
    {
      return View('backend.pay_product_packing_list.form');
    }


    public function store(Request $request)
    {
        dd($request->all());
        return $this->form();
      
    }

    public function edit($id)
    {

      return View('backend.pay_product_packing_list.form');


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
  
          \DB::commit();

          return redirect()->to(url("backend/pay_product_packing"));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        // return redirect()->action('backend\Pay_product_packing_listController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {


    }

 
    public function Datatable(Request $req){

       // if(!empty($req)){

        if(!empty($req->business_location_id_fk)){
           $w01 = " AND db_pay_product_receipt.business_location_id=".$req->business_location_id_fk ;
        }else{
           $w01 = "";
        }

        if(!empty($req->pay_code_no)){
           $w02 = " AND db_pay_product_packing_list.pay_code_no LIKE '%".$req->pay_code_no."%' " ;
        }else{
           $w02 = "";
        }

        if(!empty($req->branch_id_fk)){
           $w03 = " AND db_pay_product_packing_list.branch_id_fk = ".$req->branch_id_fk." " ;
        }else{
           $w03 = "";
        }

        if(!empty($req->customer_id)){
           $w04 = " AND db_pay_product_receipt.customer_id = ".$req->customer_id." " ;
        }else{
           $w04 = "";
        }

        if(!empty($req->startDate) && !empty($req->endDate)){
           $w05 = " and date(db_pay_product_packing_list.action_date) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
        }else{
           $w05 = "";
        }

          $sTable = DB::select("  
            SELECT
                db_pay_product_packing_list.*,
                db_pay_product_receipt.business_location_id,
                db_pay_product_receipt.customer_id
                FROM
                db_pay_product_packing_list
                Left Join db_pay_product_receipt ON db_pay_product_packing_list.invoice_code = db_pay_product_receipt.receipt
                WHERE 1 
                ".$w01."
                ".$w02."
                ".$w03."
                ".$w04."
                ".$w05."
           ");

      // }else{

      //  // $sTable = \App\Models\Backend\Pay_product_packing_list::search()->orderBy('id', 'asc');

      // }

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('customer', function($row) {
             $P = DB::select(" SELECT
              customers.user_name AS cus_code,
              customers.prefix_name,
              customers.first_name,
              customers.last_name
              FROM
              db_pay_product_receipt
              left Join customers ON db_pay_product_receipt.customer_id = customers.id
              WHERE db_pay_product_receipt.receipt='".$row->invoice_code."' ");
              return @$P[0]->cus_code ." : ".@$P[0]->prefix_name.@$P[0]->first_name." ".@$P[0]->last_name;
      }) 
       ->addColumn('action_user', function($row) {
             $P = DB::select(" select * from ck_users_admin where id=".@$row->action_user." ");
             return @$P[0]->name;
      }) 
       ->addColumn('branch', function($row) {
             $P = DB::select(" select * from branchs where id=".@$row->branch_id_fk." ");
             return @$P[0]->b_name;
      }) 
      ->make(true);
    }


}
