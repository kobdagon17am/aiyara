<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Session;
use App\Helpers\General;

class Po_supplierController extends Controller
{

    public function index(Request $request)
    {
        // General::gen_id_url();
        // วุฒิสร้าง session
        $menus = DB::table('ck_backend_menu')->select('id')->where('id',51)->first();
        Session::put('session_menu_id', $menus->id);
        Session::put('menu_id', $menus->id);
        $role_group_id = \Auth::user()->role_group_id_fk;
        $menu_permit = DB::table('role_permit')->where('role_group_id_fk',@$role_group_id)->where('menu_id_fk',@$menus->id)->first();
        $sC = @$menu_permit->c;
        $sU = @$menu_permit->u;
        $sD = @$menu_permit->d;
        Session::put('sC', $sC);
        Session::put('sU', $sU);
        Session::put('sD', $sD);
        $can_cancel_bill = @$menu_permit->can_cancel_bill;
        $can_cancel_bill_across_day = @$menu_permit->can_cancel_bill_across_day;
        $can_approve = @$menu_permit->can_approve;
        Session::put('can_cancel_bill', $can_cancel_bill);
        Session::put('can_cancel_bill_across_day', $can_cancel_bill_across_day);
        Session::put('can_approve', $can_approve);


       $sAction_user = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");
       $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
           return $query->where('id', auth()->user()->business_location_id_fk);
       })->get();
       $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
            return $query->where('id', auth()->user()->branch_id_fk);
        })->get();
      //  $Supplier = DB::select(" select * from dataset_supplier ");
       $Supplier = \App\Models\Backend\Supplier::search()->orderBy('id', 'asc')->get();
       $po_number = DB::select(" SELECT po_number FROM `db_po_supplier` where branch_id_fk=".(\Auth::user()->branch_id_fk)." ");
      return View('backend.po_supplier.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs,'Supplier'=>$Supplier,
           'sAction_user'=>$sAction_user,
           'po_number'=>$po_number,
        ) );
      // return view('backend.po_supplier.index');

    }

 public function create()
    {
       $po_supplier = DB::select(" SELECT CONVERT(SUBSTRING(po_number, -3),UNSIGNED INTEGER) AS cnt FROM db_po_supplier WHERE po_number is not null  order by po_number desc limit 1  ");
       // dd($po_supplier[0]->cnt);
       if(empty($po_supplier[0]->cnt)){
          $po_runno = "PO".date("ymd").sprintf("%03d", 1);
       }else{
           $po_runno = "PO".date("ymd").sprintf("%03d", $po_supplier[0]->cnt + 1);
       }
       // dd($po_runno);

       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();
      //  $Supplier = DB::select(" select * from dataset_supplier ");
      $Supplier = \App\Models\Backend\Supplier::search()->orderBy('id', 'asc')->get();
      return View('backend.po_supplier.form')->with(
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
       $sRow = \App\Models\Backend\Po_supplier::find($id);
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();

       if($sRow){
         $action_user = \App\Models\Backend\Permission\Admin::where('id', $sRow->action_user)->get();
         $action_user = @$action_user[0]->name;
       }else{
         $action_user = NULL ;
       }

       $Supplier = DB::select(" select * from dataset_supplier ");
       return View('backend.po_supplier.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id,
           'Supplier'=>$Supplier,
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs,
           'action_user'=>$action_user,
        ) );
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      if(isset($request->approved)){

            $sRow = \App\Models\Backend\Po_supplier::find($request->id);
            $sRow->approver = \Auth::user()->id;
            $sRow->approve_status = request('approve_status') ;
            $sRow->approve_date = date('Y-m-d');
            $sRow->note2 = request('note2');
            $sRow->save();

           return redirect()->to(url("backend/po_supplier"));

      }else{
            return $this->form($id);
      }

    }

   public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {
          if( $id ){
            $sRow = \App\Models\Backend\Po_supplier::find($id);
          }else{
            $sRow = new \App\Models\Backend\Po_supplier;
          }

          $sRow->po_number    = request('po_number');
          $sRow->business_location_id_fk    = request('business_location_id_fk');
          $sRow->branch_id_fk    = request('branch_id_fk');
          $sRow->supplier_id_fk    = request('supplier_id_fk');
          $sRow->po_code_other    = request('po_code_other');
          $sRow->action_user    = request('action_user');
          $sRow->note    = request('note');
          $sRow->buy_status    = request('buy_status')?request('buy_status'):0;
          $sRow->created_at    = request('created_at');
          // $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/po_supplier/".$sRow->id."/edit"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Po_supplierController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Po_supplier::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function po_supplier_delete_list($id)
    {
      $sRow = \App\Models\Backend\Po_supplier_products::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      // return response()->json(\App\Models\Alert::Msg('success'));
      return redirect()->back()->with('success','success');
    }

    public function Datatable(Request $req){


        if(!empty($req->business_location_id_fk)){
           $w01 = " AND db_po_supplier.business_location_id_fk=".$req->business_location_id_fk ;
        }else{
           $w01 = "";
        }

        if(!empty($req->branch_id_fk)){
           $w02 = " AND db_po_supplier.branch_id_fk = ".$req->branch_id_fk." " ;
        }else{
           $w02 = "" ;
        }

        if(!empty($req->po_number)){
           $w03 = " AND db_po_supplier.po_number LIKE '%".$req->po_number."%'  " ;
        }else{
           $w03 = "";
        }

        if(!empty($req->po_status)){
           $w04 = " AND db_po_supplier.po_status = ".$req->po_status."  " ;
        }else{
           $w04 = "";
        }

        if(!empty($req->startDate) && !empty($req->endDate)){
           $w05 = " and date(db_po_supplier.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
        }else{
           $w05 = "";
        }

        if(!empty($req->action_user)){
           $w06 = " AND db_po_supplier.action_user = ".$req->action_user." " ;
        }else{
           $w06 = "";
        }

        if(!empty($req->supplier_id_fk)){
           $w07 = " AND db_po_supplier.supplier_id_fk = ".$req->supplier_id_fk." " ;
        }else{
           $w07 = "";
        }


      // $sTable = \App\Models\Backend\Po_supplier::search()->orderBy('id', 'asc');
      $sTable = DB::select("
            SELECT db_po_supplier.*
            FROM
            db_po_supplier
            WHERE 1
            ".$w01."
            ".$w02."
            ".$w03."
            ".$w04."
            ".$w05."
            ".$w06."
            ".$w07."
         ");

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('branch', function($row) {
        $sD = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
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
      ->addColumn('po_status', function($row) {
        if($row->po_status==1){
          return 'ได้รับสินค้าครบแล้ว';
        }else if($row->po_status==2){
          return 'ยังค้างรับสินค้าจาก Supplier';
        }else if($row->po_status==3){
          return 'ใบ PO ที่ยกเลิก';
        }else{
          return 'อยู่ระหว่างการดำเนินการ';
        }
      })
       ->addColumn('buy_status', function($row) {
        if($row->buy_status==1){
          return 'ดำเนินการสั่งซื้อแล้ว';
        }else{
          return 'ยังไม่ได้สั่งซื้อ';
        }
      })
      ->make(true);
    }



}
