<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Member_regisController extends Controller
{

    public function index(Request $request)
    {

       $sApprover = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();
       $Supplier = DB::select(" select * from dataset_supplier ");
       $po_number = DB::select(" SELECT po_number FROM `db_po_supplier` where branch_id_fk=".(\Auth::user()->branch_id_fk)." ");
       $filetype = DB::select(" SELECT * FROM `dataset_regis_filetype` ");

        $customer = DB::select(" SELECT
              customers.user_name AS cus_code,
              customers.prefix_name,
              customers.first_name,
              customers.last_name,
              register_files.customer_id
              FROM
              register_files
              left Join customers ON register_files.customer_id = customers.id
              GROUP BY register_files.customer_id
              ");

      // $sPay_product_status = \App\Models\Backend\Pay_product_status::get();
      $sInvoice_code = DB::select(" SELECT
        db_add_ai_cash.invoice_code
        FROM
        db_add_ai_cash where invoice_code is not null
        ");



      return View('backend.member_regis.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs,'Supplier'=>$Supplier,
           'sApprover'=>$sApprover,
           'po_number'=>$po_number,
           'customer'=>$customer,
           'filetype'=>$filetype,

        ) );
      // return view('backend.member_regis.index');
      
    }

 public function create()
    {
       $po_supplier = DB::select(" SELECT CONVERT(SUBSTRING(po_number, -3),UNSIGNED INTEGER) AS cnt FROM db_po_supplier WHERE po_number is not null  order by po_number desc limit 1  ");
       // dd($po_supplier[0]->cnt);
       $po_runno = "PO".date("ymd").sprintf("%03d", $po_supplier[0]->cnt + 1);
       // dd($po_runno);

       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();
       $Supplier = DB::select(" select * from dataset_supplier ");
      return View('backend.member_regis.form')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs
           ,'Supplier'=>$Supplier
           ,'po_runno'=>$po_runno
        ) );
    }
    public function store(Request $request)
    {
      // dd($request->all());
      // if(isset($request->save_regis)){
      //       $sRow = \App\Models\Backend\Member_regis::find($id);
      // }else{
        return $this->form();
      // }
      
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Member_regis::find($id);
       // dd($sRow);
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();
        $sProductUnit = \App\Models\Backend\Product_unit::where('lang_id', 1)->get();

       if($sRow){
         $action_user = \App\Models\Backend\Permission\Admin::where('id', $sRow->action_user)->get();
         $action_user = @$action_user[0]->name;
       }else{
         $action_user = NULL ;
       }

       $Supplier = DB::select(" select * from dataset_supplier ");
       return View('backend.member_regis.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id,
           'Supplier'=>$Supplier,
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs,
           'action_user'=>$action_user,
           'sProductUnit'=>$sProductUnit,
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
          // dd(request('id'));
          if(request('id')){

              $sRow = \App\Models\Backend\Member_regis::find(request('id'));

              $sRow->status    = request('regis_status');
              $sRow->approver    = \Auth::user()->id;
              $sRow->approve_date    = date("Y-m-d");
              $sRow->comment    = request('comment');
              $sRow->save();

         }

           \DB::commit();

           return redirect()->to(url("backend/member_regis"));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Member_regisController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Member_regis::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){


        if(!empty($req->business_location_id_fk)){
           $w01 = " AND register_files.business_location_id_fk=".$req->business_location_id_fk ;
        }else{
           $w01 = "";
        }

        if(!empty($req->branch_id_fk)){
           $w02 = " AND register_files.branch_id_fk = ".$req->branch_id_fk." " ;
        }else{
           $w02 = "" ;
        }

        if(!empty($req->po_number)){
           $w03 = " AND register_files.po_number LIKE '%".$req->po_number."%'  " ;
        }else{
           $w03 = "";
        }

        if(!empty($req->po_status)){
           $w04 = " AND register_files.po_status = ".$req->po_status."  " ;
        }else{
           $w04 = "";
        }

        if(!empty($req->startDate) && !empty($req->endDate)){
           $w05 = " and date(register_files.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
        }else{
           $w05 = "";
        }

        if(!empty($req->action_user)){
           $w06 = " AND register_files.action_user = ".$req->action_user." " ;
        }else{
           $w06 = "";
        }

        if(!empty($req->supplier_id_fk)){
           $w07 = " AND register_files.supplier_id_fk = ".$req->supplier_id_fk." " ;
        }else{
           $w07 = "";
        }


      // $sTable = \App\Models\Backend\Member_regis::search()->orderBy('id', 'asc');
      $sTable = DB::select("

        SELECT * FROM `register_files` 
        where 1 
                ".$w01."
                ".$w02."
                ".$w03."
                ".$w04."
                ".$w05."
                ".$w06."
                ".$w07."

        ORDER BY updated_at DESC


       
         ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('branch', function($row) {
        $sD = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
        return $sD[0]->b_name;
      })
       ->addColumn('customer_name', function($row) {
        if(@$row->customer_id!=''){
          $Customer = DB::select(" select * from customers where id=".@$row->customer_id." ");
          return @$Customer[0]->user_name." : ".@$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
        }else{
          return '';
        }
      })
      ->addColumn('filetype', function($row) {
        $filetype = DB::select(" select * from dataset_regis_filetype where id=".$row->type." ");
        return $filetype[0]->txt_desc;
      })
      ->addColumn('approver', function($row) {
        $c = DB::select(" select * from ck_users_admin where id=".$row->approver." ");
        return $c[0]->name;
      })
      ->addColumn('regis_status', function($row) {
        if($row->status=="S"){
          return 'ผ่าน';
        }else if($row->status=="F"){
          return 'ไม่ผ่าน';
        }else{
          return 'รอตรวจสอบ';
        }
      })
      ->addColumn('icon', function($row) {
          $filetype = DB::select(" select * from dataset_regis_filetype where id=".$row->type." ");
          if($filetype){
            return $filetype[0]->icon;
          }
      })
      ->escapeColumns('icon')
      ->make(true);
    }



}
