<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Member_pvController extends Controller
{

    public function index(Request $request)
    {

       $sApprover = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();
       $Supplier = DB::select(" select * from dataset_supplier ");
       $po_number = DB::select(" SELECT po_number FROM `db_po_supplier` where branch_id_fk=".(\Auth::user()->branch_id_fk)." ");
       $filetype = DB::select(" SELECT * FROM `dataset_regis_filetype` ");
       $regis_doc_status = DB::select(" SELECT * FROM `dataset_regis_doc_status` ");

       // dd($regis_doc_status);

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



      return View('backend.member_pv.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs,'Supplier'=>$Supplier,
           'sApprover'=>$sApprover,
           'po_number'=>$po_number,
           'customer'=>$customer,
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
           $w01 = " AND customers.id=".$req->customer_id."  " ;
        }else{
           $w01 = "";
        }

        if(!empty($req->status_aistockis)){
           $w02 = " AND customers.id=".$req->status_aistockis."  " ;
        }else{
           $w02 = "";
        }


        if(!empty($req->startDate) && !empty($req->endDate)){
          // $w03 = " and date(register_files.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
           $w03 = " AND (SELECT date(register_files.created_at) FROM register_files WHERE register_files.customer_id=customers.id limit 1) BETWEEN '".$req->startDate."' AND '".$req->endDate."' " ;
        }else{
           $w03 = "";
        }


        if(!empty($req->regis_status)){
          if($req->regis_status==1){
            $w04 = " AND customers.regis_doc1_status=1 AND customers.regis_doc2_status=1 AND customers.regis_doc2_status=1 " ;
          }elseif($req->regis_status==2){
            $w04 = " AND (customers.regis_doc1_status=2 OR customers.regis_doc2_status=2 OR customers.regis_doc2_status=2) " ;
          }elseif($req->regis_status==3){
            $w04 = " AND (customers.regis_doc1_status in(1,2) OR customers.regis_doc2_status in(1,2) OR customers.regis_doc2_status in(1,2)) " ;
          }else{
            $w04 = " AND customers.regis_doc1_status=0 AND customers.regis_doc2_status=0 AND customers.regis_doc2_status=0 AND customers.regis_doc4_status=0 " ;
          }
        }else{
           $w04 = "";
        }

  // `regis_doc1_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
  // `regis_doc2_status` int(1) DEFAULT '0' COMMENT 'ภายถ่ายหน้าตรง 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
  // `regis_doc3_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าตรงถือบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
  // `regis_doc4_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าบัญชีธนาคาร 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',

      // $sTable = \App\Models\Backend\Member_pv::search()->orderBy('id', 'asc');
      $sTable = DB::select("

          SELECT
          customers.id,
          customers.user_name,
          customers.prefix_name,
          customers.first_name,
          customers.last_name,
          customers.regis_doc1_status,
          customers.regis_doc2_status,
          customers.regis_doc3_status,
          customers.regis_doc4_status

          FROM `customers`

          where 1 
                ".$w01."
                ".$w02."
                ".$w03."
                ".$w04."
         ");

      // (SELECT created_at FROM register_files WHERE customer_id=customers.id limit 1) as regis_date

      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('customer_name', function($row) {
          return $row->user_name." : ".$row->prefix_name.$row->first_name." ".$row->last_name;
      })
      ->addColumn('status_aistockis', function($row) {
        return '-';
      })
      ->addColumn('regis_status', function($row) {
        // สถานะกรณีนี้ ต้อง ดึงมาจากตาราง customers 
        //   `regis_doc1_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc2_status` int(1) DEFAULT '0' COMMENT 'ภายถ่ายหน้าตรง 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc3_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าตรงถือบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc4_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าบัญชีธนาคาร 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',

        $Member_regis = \App\Models\Backend\Member_regis::where('customer_id',$row->id)->first();
        if(@$Member_regis->type==1){
          if($row->regis_doc1_status=="1"){
            return 'ผ่าน';
          }elseif($row->regis_doc1_status=="2"){
            return 'ไม่ผ่าน';
          }else{
             return 'รอตรวจสอบ';
          }
        }

        if(@$Member_regis[0]->type==2){
          if($row->regis_doc2_status=="1"){
            return 'ผ่าน';
          }elseif($row->regis_doc2_status=="2"){
            return 'ไม่ผ่าน';
          }else{
             return 'รอตรวจสอบ';
          }
        }

        if(@$Member_regis[0]->type==3){
          if($row->regis_doc3_status=="1"){
            return 'ผ่าน';
          }elseif($row->regis_doc3_status=="2"){
            return 'ไม่ผ่าน';
          }else{
             return 'รอตรวจสอบ';
          }
        }

        if(@$Member_regis[0]->type==4){
          if($row->regis_doc4_status=="1"){
            return 'ผ่าน';
          }elseif($row->regis_doc4_status=="2"){
            return 'ไม่ผ่าน';
          }else{
             return 'รอตรวจสอบ';
          }
        }

      })
// เอาไว้ไปเช็คในตาราง Datatable สมาชิกลงทะเบียน ตรวจเอกสาร
      ->addColumn('regis_status_02', function($row) {
        // สถานะกรณีนี้ ต้อง ดึงมาจากตาราง customers 
        //   `regis_doc1_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc2_status` int(1) DEFAULT '0' COMMENT 'ภายถ่ายหน้าตรง 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc3_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าตรงถือบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc4_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าบัญชีธนาคาร 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        $Member_regis = \App\Models\Backend\Member_regis::where('customer_id',$row->id)->first();

        if(@$Member_regis[0]->type==1){
          if($row->regis_doc1_status=="1"){
            return 'S';
          }elseif($row->regis_doc1_status=="2"){
            return 'F';
          }else{
             return 'W';
          }
        }

        if(@$Member_regis[0]->type==2){
          if($row->regis_doc2_status=="1"){
            return 'S';
          }elseif($row->regis_doc2_status=="2"){
            return 'F';
          }else{
             return 'W';
          }
        }

        if(@$Member_regis[0]->type==3){
          if($row->regis_doc3_status=="1"){
            return 'S';
          }elseif($row->regis_doc3_status=="2"){
            return 'F';
          }else{
             return 'W';
          }
        }

        if(@$Member_regis[0]->type==4){
          if($row->regis_doc4_status=="1"){
            return 'S';
          }elseif($row->regis_doc4_status=="2"){
            return 'F';
          }else{
             return 'W';
          }
        }

      })

      ->addColumn('regis_date', function($row) {
        $Member_regis = \App\Models\Backend\Member_regis::where('customer_id',$row->id)->first();
        return @$Member_regis->created_at;
      })
      ->make(true);
    }



}
