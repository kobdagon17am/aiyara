<?php

namespace App\Http\Controllers\Backend;

use DB;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class Member_pvController extends Controller
{

    public function index(Request $request)
    {

       ini_set('max_execution_time', '0'); 
       ini_set('memory_limit', '-1'); 

       $sApprover = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();
       $Supplier = DB::select(" select * from dataset_supplier ");
       $po_number = DB::select(" SELECT po_number FROM `db_po_supplier` where branch_id_fk=".(\Auth::user()->branch_id_fk)." ");
       $filetype = DB::select(" SELECT * FROM `dataset_regis_filetype` ");
       $regis_doc_status = DB::select(" SELECT * FROM `dataset_regis_doc_status` ");

       // dd($regis_doc_status);

      // $sPay_product_status = \App\Models\Backend\Pay_product_status::get();
      $sInvoice_code = DB::select(" SELECT
        db_add_ai_cash.invoice_code
        FROM
        db_add_ai_cash where invoice_code is not null
        ");



      return View('backend.member_pv.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs,'Supplier'=>$Supplier,
           'sApprover'=>$sApprover,
           'po_number'=>$po_number,
           'filetype'=>$filetype,
           'regis_doc_status'=>$regis_doc_status,

        ) );
      // return view('backend.member_pv.index');
      
    }

    public function create()
    {
    }
    public function store(Request $request)
    {
    }
    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
      // return $this->form($id);
    }

   public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {

           // \DB::commit();
           return redirect()->to(url("backend/member_regis"));
      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Member_pvController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
    }


    public function Datatable(Request $req){

      if(!empty($req->customer_id)){
        $w01 = $req->customer_id;
        $condition = "=";
      }else{
        $w01 = 0 ;
        $condition = "!=";
      }

      if(!empty($req->business_name)){
        $w02 = $req->business_name;
        $condition02 = "=";
      }else{
        $w02 = "0" ;
        $condition02 = "!=";
      }

      if(!empty($req->introduce_id)){
        $w03 = $req->introduce_id;
        $condition03 = "=";
      }else{
        $w03 = "0" ;
        $condition03 = "!=";
      }

      if(!empty($req->upline_id)){
        $w04 = $req->upline_id;
        $condition04 = "=";
      }else{
        $w04 = "0" ;
        $condition04 = "!=";
      }


      $sTable = \App\Models\Backend\Customers::where('id','!=',0)
      ->where('customers.id',$condition,$w01)
      ->where('customers.business_name',$condition02,trim($w02))
      ->where('customers.introduce_id',$condition03,$w03)
      ->where('customers.upline_id',$condition04,$w04)
      ;

      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('customer_name', function($row) {
        if($row->user_name){
          return @$row->user_name." : ".trim(@$row->prefix_name).trim(@$row->first_name)." ".trim(@$row->last_name);
        }else{
          return '';
        }
      })
      ->addColumn('qualification', function($row) {
        if($row->qualification_id){
          $d = DB::select(" SELECT * FROM `dataset_qualification` where id= ".$row->qualification_id."  ");
          return @$d[0]->business_qualifications;
        }else{
          return '-';
        }
      })
      ->addColumn('package', function($row) {
        if($row->qualification_id){
          $d = DB::select(" SELECT * FROM `dataset_package` where id= ".$row->package_id."  ");
          return @$d[0]->dt_package;
        }else{
          return '-';
        }
      })
      ->addColumn('regis_status', function($row) {
        // สถานะกรณีนี้ ต้อง ดึงมาจากตาราง customers 
        //   `regis_doc1_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc2_status` int(1) DEFAULT '0' COMMENT 'ภายถ่ายหน้าตรง 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc3_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าตรงถือบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc4_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าบัญชีธนาคาร 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',

        $ic = '';

        if($row->regis_doc1_status==1){
          $icon = DB::select(" select * from dataset_regis_filetype where id=1 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ผ่าน">'.@$icon[0]->icon_pass.'</span>';
        }elseif($row->regis_doc1_status==2){
          $icon = DB::select(" select * from dataset_regis_filetype where id=1 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ไม่ผ่าน">'.@$icon[0]->icon_nopass.'</span>';
        }else{
          $icon = DB::select(" select * from dataset_regis_filetype where id=1 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ยังไม่ส่ง">'.@$icon[0]->icon_nosend.'</span>';
        }

        if($row->regis_doc2_status==1){
          $icon = DB::select(" select * from dataset_regis_filetype where id=2 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ผ่าน">'.@$icon[0]->icon_pass.'</span>';
        }elseif($row->regis_doc2_status==2){
          $icon = DB::select(" select * from dataset_regis_filetype where id=2 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ไม่ผ่าน">'.@$icon[0]->icon_nopass.'</span>';
        }else{
          $icon = DB::select(" select * from dataset_regis_filetype where id=2 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ยังไม่ส่ง">'.@$icon[0]->icon_nosend.'</span>';
        }

        if($row->regis_doc3_status==1){
          $icon = DB::select(" select * from dataset_regis_filetype where id=3 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ผ่าน">'.@$icon[0]->icon_pass.'</span>';
        }elseif($row->regis_doc3_status==2){
          $icon = DB::select(" select * from dataset_regis_filetype where id=3 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ไม่ผ่าน">'.@$icon[0]->icon_nopass.'</span>';
        }else{
          $icon = DB::select(" select * from dataset_regis_filetype where id=3 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ยังไม่ส่ง">'.@$icon[0]->icon_nosend.'</span>';
        }

        if($row->regis_doc4_status==1){
          $icon = DB::select(" select * from dataset_regis_filetype where id=4 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ผ่าน">'.@$icon[0]->icon_pass.'</span>';
        }elseif($row->regis_doc4_status==2){
          $icon = DB::select(" select * from dataset_regis_filetype where id=4 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ไม่ผ่าน">'.@$icon[0]->icon_nopass.'</span>';
        }else{
          $icon = DB::select(" select * from dataset_regis_filetype where id=4 ");
          $ic .= '<span  data-toggle="tooltip" data-placement="right" title="ยังไม่ส่ง">'.@$icon[0]->icon_nosend.'</span>';
        }

        return $ic;

      })
      ->escapeColumns('regis_status')
      ->addColumn('regis_date_doc', function($row) {
        if($row->regis_date_doc){
           return $row->regis_date_doc;
        }else{
           return '';
        }
      })
      ->addColumn('routes_user', function ($user) {
        if(@$user->user_name){
          return route('admin.access', Crypt::encryptString(@$user->user_name));
        }else{
          return 0;
        }
      })
      ->addColumn('aistockist_status', function ($row) {
          if($row->aistockist_status==1){
            return '<i class="bx bx-check-circle" style="color:green;font-weight:bold;font-size:16px;" data-toggle="tooltip" data-placement="right" title="เป็นแล้ว" ></i>';
          }else{
            return '<i class="bx bx-x-circle" style="color:darkred;" data-toggle="tooltip" data-placement="right" title="ยังไม่เป็น" ></i>';
          }
      })
      ->escapeColumns('aistockist_status')
      ->addColumn('introduce', function($row) {
        if($row->introduce_id){
          return @$row->introduce_id."/".@$row->introduce_type;
        }else{
          return '';
        }
      })
      ->escapeColumns('introduce')
      ->addColumn('upline', function($row) {
        if($row->upline_id){
          return @$row->upline_id."/".@$row->line_type;
        }else{
          return '';
        }
      })
      ->escapeColumns('upline')
      ->make(true);
    }



}
