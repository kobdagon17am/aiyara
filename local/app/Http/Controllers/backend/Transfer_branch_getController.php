<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Transfer_branch_getController extends Controller
{

    public function index(Request $request)
    {

       $sAction_user = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();
       $Supplier = DB::select(" select * from dataset_supplier ");
       $tr_number = DB::select(" SELECT tr_number FROM `db_transfer_branch_get` where branch_id_fk=".(\Auth::user()->branch_id_fk)." ");
      return View('backend.transfer_branch_get.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs,'Supplier'=>$Supplier,
           'sAction_user'=>$sAction_user,
           'tr_number'=>$tr_number,
        ) );
      // return view('backend.transfer_branch_get.index');
      
    }

 public function create()
    {
       $po_supplier = DB::select(" SELECT CONVERT(SUBSTRING(tr_number, -3),UNSIGNED INTEGER) AS cnt FROM db_transfer_branch_get WHERE tr_number is not null  order by tr_number desc limit 1  ");
       // dd($po_supplier[0]->cnt);
       $po_runno = "PO".date("ymd").sprintf("%03d", $po_supplier[0]->cnt + 1);
       // dd($po_runno);

       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();
       $Supplier = DB::select(" select * from dataset_supplier ");
      return View('backend.transfer_branch_get.form')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs
           ,'Supplier'=>$Supplier
           ,'po_runno'=>$po_runno
        ) );
    }
    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Transfer_branch_get::find($id);
       // dd($sRow);
        $sBusiness_location = \App\Models\Backend\Business_location::get();
        $sBranchs = \App\Models\Backend\Branchs::get();
        $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();
        $sUserAdmin = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");

       return View('backend.transfer_branch_get.form')->with(
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
            $sRow = \App\Models\Backend\Transfer_branch_get::find($id);
          }else{
            $sRow = new \App\Models\Backend\Transfer_branch_get;
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
        return redirect()->action('backend\Transfer_branch_getController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {

       if($id){
        
        $r = DB::select("SELECT *  FROM `db_transfer_branch_get_products_receive` WHERE (`id`='$id')");

        DB::select("DELETE FROM `db_transfer_branch_get_products_receive` WHERE (`id`='$id')");

        DB::select("
            UPDATE db_transfer_branch_get_products SET product_amt_receive=(SELECT sum(amt_get) as sum_amt FROM `db_transfer_branch_get_products_receive` WHERE transfer_branch_get_id_fk=".$r[0]->transfer_branch_get_id_fk." AND product_id_fk=".$r[0]->product_id_fk.")
            WHERE id=".$r[0]->transfer_branch_get_products." ;
        ");

        DB::select(" UPDATE `db_transfer_branch_get_products` SET `get_status`='1' where id=".$r[0]->transfer_branch_get_products." AND product_amt=product_amt_receive; ");

        DB::select(" UPDATE `db_transfer_branch_get_products` SET `get_status`='2' where id=".$r[0]->transfer_branch_get_products." AND product_amt<product_amt_receive ; ");

        DB::select(" UPDATE `db_transfer_branch_get` SET `tr_status`='1' where id=".$r[0]->transfer_branch_get_id_fk." ; ");

        DB::select(" UPDATE
            db_transfer_branch_get
            Inner Join db_transfer_branch_get_products ON db_transfer_branch_get.id = db_transfer_branch_get_products.transfer_branch_get_id_fk
            SET
            db_transfer_branch_get.tr_status=db_transfer_branch_get_products.get_status
            WHERE db_transfer_branch_get.id=".$r[0]->transfer_branch_get_id_fk." AND db_transfer_branch_get_products.get_status=1 ");

      }

      // $sRow = \App\Models\Backend\Transfer_branch_get::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){


        if(!empty($req->business_location_id_fk)){
           $w01 = " AND db_transfer_branch_get.business_location_id_fk=".$req->business_location_id_fk ;
        }else{
           $w01 = "";
        }

        if(!empty($req->branch_id_fk)){
           $w02 = " AND db_transfer_branch_get.branch_id_fk = ".$req->branch_id_fk." " ;
        }else{
           $w02 = "" ;
        }

        if(!empty($req->get_from_branch_id_fk)){
           $w022 = " AND db_transfer_branch_get.get_from_branch_id_fk = ".$req->get_from_branch_id_fk." " ;
        }else{
           $w022 = "" ;
        }

        if(!empty($req->tr_number)){
           $w03 = " AND db_transfer_branch_get.tr_number LIKE '%".$req->tr_number."%'  " ;
        }else{
           $w03 = "";
        }

        if(isset($req->tr_status)){
           $w04 = " AND db_transfer_branch_get.tr_status = ".$req->tr_status."  " ;
        }else{
           $w04 = "";
        }

        if(!empty($req->startDate) && !empty($req->endDate)){
           $w05 = " and date(db_transfer_branch_get.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
        }else{
           $w05 = "";
        }

        if(!empty($req->action_user)){
           $w06 = " AND db_transfer_branch_get.action_user = ".$req->action_user." " ;
        }else{
           $w06 = "";
        }

        if(!empty($req->supplier_id_fk)){
           $w07 = " AND db_transfer_branch_get.supplier_id_fk = ".$req->supplier_id_fk." " ;
        }else{
           $w07 = "";
        }


      // $sTable = \App\Models\Backend\Transfer_branch_get::search()->orderBy('id', 'asc');
      $sTable = DB::select("
            SELECT db_transfer_branch_get.*
            FROM
            db_transfer_branch_get
            WHERE buy_status=1
            ".$w01."
            ".$w02."
            ".$w022."
            ".$w03."
            ".$w04."
            ".$w05."
            ".$w06."
            ".$w07."
         ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('get_from_branch', function($row) {
        $sD = DB::select(" select * from branchs where id=".$row->get_from_branch_id_fk." ");
        return $sD[0]->b_name;
      })
      ->addColumn('supplier_name', function($row) {
        $sD = DB::select(" select * from dataset_supplier where id=".$row->supplier_id_fk." ");
        return $sD[0]->txt_desc;
      })
      ->addColumn('action_user', function($row) {
        $c = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
        return $c[0]->name;
      })
      ->addColumn('approver', function($row) {
          if($row->approver){
                $c = DB::select(" select * from ck_users_admin where id=".$row->approver." ");
            return $c[0]->name;
          }else{
            return '-';
          }
      })
      ->addColumn('tr_status', function($row) {
        if($row->tr_status==1){
          return 'ได้รับสินค้าครบแล้ว';
        }else if($row->tr_status==2){
          return 'ยังค้างรับสินค้า';
        }else if($row->tr_status==3){
          return 'ใบ PO ที่ยกเลิก';
        }else{
          return 'อยู่ระหว่างการดำเนินการ';
        }
      })
     ->addColumn('approve_date', function($row) {
        if($row->approve_date){
        return date("Y-m-d",strtotime($row->approve_date));
        }else{
            return '-';
        }
      })
      ->addColumn('created_at', function($row) {
        return date("Y-m-d",strtotime($row->created_at));
      })
      ->make(true);
    }



}
