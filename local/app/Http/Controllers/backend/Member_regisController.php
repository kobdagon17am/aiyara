<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Member_regisController extends Controller
{

    public function index(Request $request)
    {

       $sApprover = DB::select(" select * from ck_users_admin where branch_id_fk=".(\Auth::user()->branch_id_fk)."  ");
       $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
         return $query->where('id', auth()->user()->business_location_id_fk);
       })->get();
       $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
         return $query->where('id', auth()->user()->branch_id_fk);
       })->get();
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



      return View('backend.member_regis.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs,'Supplier'=>$Supplier,
           'sApprover'=>$sApprover,
           'po_number'=>$po_number,
           'customer'=>$customer,
           'filetype'=>$filetype,
           'regis_doc_status'=>$regis_doc_status,

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

              // dd($sRow);

              $sRow->regis_doc_status    = request('regis_status');
              $sRow->approver    = \Auth::user()->id;
              $sRow->approve_date    = date("Y-m-d");
              $sRow->comment    = request('comment');
              $sRow->item_checked    = 1 ;
              $sRow->save();

// customers
//   `regis_doc1_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
//   `regis_doc2_status` int(1) DEFAULT '0' COMMENT 'ภายถ่ายหน้าตรง 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
//   `regis_doc3_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าตรงถือบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
//   `regis_doc4_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าบัญชีธนาคาร 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',

              $Customers = \App\Models\Backend\Customers::find($sRow->customer_id);
              if(request('type')==1){
                 $Customers->regis_doc1_status = request('regis_status');
              }
              if(request('type')==2){
                $Customers->regis_doc2_status = request('regis_status');
              }
              if(request('type')==3){
                $Customers->regis_doc3_status = request('regis_status');
              }
              if(request('type')==4){
                $Customers->regis_doc4_status = request('regis_status');
              }

              if($Customers->regis_doc1_status==1 && $Customers->regis_doc2_status==1 && $Customers->regis_doc3_status==1){
                $Customers->regis_date_doc = date("Y-m-d");
              }else{
                $Customers->regis_date_doc = NULL;
              }

              $Customers->save();

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

        if(!empty($req->customer_id)){
           $w03 = " AND register_files.customer_id LIKE '%".$req->customer_id."%'  " ;
        }else{
           $w03 = "";
        }

        if(!empty($req->regis_status)){
           $w04 = " AND register_files.regis_doc_status = ".$req->regis_status."  " ;
        }else{
           $w04 = "";
        }

        if(!empty($req->startDate) && !empty($req->endDate)){
           $w05 = " and date(register_files.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
        }else{
           $w05 = "";
        }

        if(!empty($req->approver)){
           $w06 = " AND register_files.approver = ".$req->approver." " ;
        }else{
           $w06 = "";
        }

        if(!empty($req->filetype)){
           $w07 = " AND register_files.type = ".$req->filetype." " ;
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

       GROUP BY customer_id
       ORDER BY updated_at DESC


         ");

      $sQuery = \DataTables::of($sTable);
    
      return $sQuery

       ->addColumn('customer_name', function($row) {
        if(@$row->customer_id!=''){
          $Customer = DB::select(" select * from customers where id=".@$row->customer_id." ");
     
          return @$Customer[0]->user_name." : ".@$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
        }else{
          return '';
        }
      })

      ->addColumn('filetype', function($row) {

        $d = DB::select(" select type from register_files where customer_id=".$row->customer_id." group by type ");
        $f = [] ;
        foreach ($d as $key => $value) {
           $filetype = DB::select(" select txt_desc from dataset_regis_filetype where id=".$value->type." order by id  ");
           array_push($f,$filetype[0]->txt_desc);
        }
        $f = implode('<br>',$f);
   
        return @$f;

      })
      ->escapeColumns('filetype')

      ->addColumn('regis_status', function($row) {

        $d = DB::select(" select type from register_files where customer_id=".$row->customer_id." group by type ");
        $f = [] ;
        $r1 = '' ;
        $r2 = '' ;
        $r3 = '' ;
        $r4 = '' ;
        foreach ($d as $key => $value) {
            $filetype = DB::select(" select id from dataset_regis_filetype where id=".$value->type." order by id  ");
            $Customers = \App\Models\Backend\Customers::where('id',$row->customer_id)->first();

            if($filetype[0]->id==1){

               if($Customers->regis_doc1_status=="1"){
                  $r1 =  'ผ่าน';
                }elseif($Customers->regis_doc1_status=="2"){
                  $r1 =  'ไม่ผ่าน';
                }else{
                   $r1 =  'รอตรวจสอบ';
                }
            }else{
              $r1 = '';
            }

            if($filetype[0]->id==2){

               if($Customers->regis_doc2_status=="1"){
                  $r2 =  'ผ่าน';
                }elseif($Customers->regis_doc2_status=="2"){
                  $r2 =  'ไม่ผ่าน';
                }else{
                  $r2 =  'รอตรวจสอบ';
                }

               }else{
                $r2 = '';
              }


            if($filetype[0]->id==3){

               if($Customers->regis_doc3_status=="1"){
                  $r3 =  'ผ่าน';
                }elseif($Customers->regis_doc3_status=="2"){
                  $r3 =  'ไม่ผ่าน';
                }else{
                  $r3 =  'รอตรวจสอบ';
                }

               }else{
                $r3 = '';
              }

            if($filetype[0]->id==4){

               if($Customers->regis_doc4_status=="1"){
                  $r4 =  'ผ่าน';
                }elseif($Customers->regis_doc4_status=="2"){
                  $r4 =  'ไม่ผ่าน';
                }else{
                  $r4 =  'รอตรวจสอบ';
                }

               }else{
                $r4 = '';
              }

            // array_push($f,$filetype[0]->id.' : '.$r1.$r2.$r3.$r4);
            array_push($f,$r1.$r2.$r3.$r4);
        }

        $f = implode('<br>',$f);
        return @$f;

      })
      ->escapeColumns('filetype')


      ->addColumn('approver', function($row) {

        $d = DB::select(" select approver from register_files where customer_id=".$row->customer_id." group by type ");
        $f = [] ;
        foreach ($d as $key => $value) {
            $c = DB::select("select * from ck_users_admin where id = ".(@$value->approver?$value->approver:0));
            if(count($c)!=0){
              array_push($f,isset($c) ? $c[0]->name : '');
            }
        
        }

        $f = implode('<br>',$f);
        return @$f;

      })
      ->escapeColumns('approver')


      ->addColumn('approve_date', function($row) {

        $d = DB::select(" select approve_date from register_files where customer_id=".$row->customer_id." group by type ");
        $f = [] ;
        foreach ($d as $key => $value) {
            array_push($f,(@$value->approve_date?$value->approve_date:''));
        }

        $f = implode('<br>',$f);
        return @$f;

      })
      ->escapeColumns('approve_date')
// เอาไว้ไปเช็คในตาราง Datatable สมาชิกลงทะเบียน ตรวจเอกสาร
      // ->addColumn('regis_status_02', function($row) {
      //   // สถานะกรณีนี้ ต้อง ดึงมาจากตาราง customers
      //   //   `regis_doc1_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
      //   //   `regis_doc2_status` int(1) DEFAULT '0' COMMENT 'ภายถ่ายหน้าตรง 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
      //   //   `regis_doc3_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าตรงถือบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
      //   //   `regis_doc4_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าบัญชีธนาคาร 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
      //   $Customers = \App\Models\Backend\Customers::find($row->customer_id);
      //   if($row->type==1){
      //     if($Customers->regis_doc1_status=="1"){
      //       return 'S';
      //     }elseif($Customers->regis_doc1_status=="2"){
      //       return 'F';
      //     }else{
      //        return 'W';
      //     }
      //   }

      //   if($row->type==2){
      //     if($Customers->regis_doc2_status=="1"){
      //       return 'S';
      //     }elseif($Customers->regis_doc2_status=="2"){
      //       return 'F';
      //     }else{
      //        return 'W';
      //     }
      //   }

      //   if($row->type==3){
      //     if($Customers->regis_doc3_status=="1"){
      //       return 'S';
      //     }elseif($Customers->regis_doc3_status=="2"){
      //       return 'F';
      //     }else{
      //        return 'W';
      //     }
      //   }

      //   if($row->type==4){
      //     if($Customers->regis_doc4_status=="1"){
      //       return 'S';
      //     }elseif($Customers->regis_doc4_status=="2"){
      //       return 'F';
      //     }else{
      //        return 'W';
      //     }
      //   }

      // })

      // ->addColumn('icon', function($row) {
      //     $filetype = DB::select(" select * from dataset_regis_filetype where id=".$row->type." ");
      //     if($filetype){
      //       return $filetype[0]->icon;
      //     }
      // })
      // ->escapeColumns('icon')


      ->addColumn('icon', function($row) {

        $d = DB::select(" select type from register_files where customer_id=".$row->customer_id." group by type ");
        $f = [] ;
        foreach ($d as $key => $value) {
           $filetype = DB::select(" select icon from dataset_regis_filetype where id=".$value->type." order by id  ");
           array_push($f,$filetype[0]->icon);
        }
        $f = implode('<br>',$f);
        return @$f;

      })
      ->escapeColumns('icon')

      ->addColumn('tools', function($row) {

        $d = DB::select(" select id,item_checked from register_files where customer_id=".$row->customer_id." group by type ");
        $f = [] ;
        foreach ($d as $key => $value) {
            // if($value->item_checked==1){
              array_push($f,'<a href="#" class="btn btn-sm btn-primary btnCheckRegis " data-id="'.$value->id.'"  ><i class="bx bx-edit font-size-14 align-middle"></i> </a>');
            // }
        }
        $f = implode('<br>',$f);
        return @$f;

      })
      ->escapeColumns('tools')

      ->make(true);

    }


    public function Datatable02(Request $req){

        // dd($req);
        // customers
        //   `regis_doc1_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc2_status` int(1) DEFAULT '0' COMMENT 'ภายถ่ายหน้าตรง 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc3_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าตรงถือบัตรประชาชน 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        //   `regis_doc4_status` int(1) DEFAULT '0' COMMENT 'ภาพถ่ายหน้าบัญชีธนาคาร 0=ยังไม่ส่ง 1=ผ่าน 2=ไม่ผ่าน',
        // dataset_regis_doc_status
        // 1=ยืนยันตัวตนแล้ว,2=ยังไม่ยืนยันตัวตน,3=ผ่านแล้วบางรายการ,4=เอกสารไม่ผ่าน

        // กรณี 1=ยืนยันตัวตนแล้ว ผ่าน 3 รายการแรก หลัก ก็ถือว่าผ่าน
        $case_ok = DB::select("
        SELECT customer_id FROM `register_files` where customer_id in
        (
        SELECT
        customers.id
        FROM
        customers WHERE regis_doc1_status=1 AND  regis_doc2_status=1 AND  regis_doc3_status=1
        )
        group by customer_id
        ");

        $arr_case_ok = [0];
        if($case_ok){
          foreach ($case_ok as $key => $value) {
             array_push($arr_case_ok, $value->customer_id);
          }
        }
         $arr_case_ok = implode(",", $arr_case_ok);

        // กรณี 2=ยังไม่ยืนยันตัวตน
        $case_nosend = DB::select("
        SELECT customer_id FROM `register_files` where customer_id in
        (
        SELECT
        customers.id
        FROM
        customers WHERE regis_doc1_status=0 AND  regis_doc2_status=0 AND  regis_doc3_status=0 AND  regis_doc4_status=0
        )
        group by customer_id
        ");

        $arr_case_nosend = [0];
        if($case_nosend){
          foreach ($case_nosend as $key => $value) {
             array_push($arr_case_nosend, $value->customer_id);
          }
        }
         $arr_case_nosend = implode(",", $arr_case_nosend);


        // กรณี 3=ผ่านแล้วบางรายการ
        $case_somepass = DB::select("
        SELECT customer_id FROM `register_files` where customer_id in
        (
        SELECT
        customers.id
        FROM
        customers WHERE regis_doc1_status=1 OR  regis_doc2_status=1 OR  regis_doc3_status=1 OR  regis_doc4_status=1
        )
        group by customer_id
        ");

        $arr_case_somepass  = [0];
        if($case_somepass ){
          foreach ($case_somepass  as $key => $value) {
             array_push($arr_case_somepass , $value->customer_id);
          }
        }
         $arr_case_somepass  = implode(",", $arr_case_somepass );


        // กรณี 4=เอกสารไม่ผ่าน
        $case_nopass = DB::select("
        SELECT customer_id FROM `register_files` where customer_id in
        (
        SELECT
        customers.id
        FROM
        customers WHERE regis_doc1_status=2 AND regis_doc2_status=2 AND regis_doc3_status=2
        )
        group by customer_id
        ");

        $arr_case_nopass  = [0];
        if($case_nopass ){
          foreach ($case_nopass  as $key => $value) {
             array_push($arr_case_nopass , $value->customer_id);
          }
        }
         $arr_case_nopass  = implode(",", $arr_case_nopass );


          if(!empty($req->regis_doc_status)){
            if($req->regis_doc_status==1){
               $w02 = " AND customer_id in ($arr_case_ok) " ;
            }elseif($req->regis_doc_status==2){
               $w02 = " AND customer_id in ($arr_case_nosend) " ;
            }elseif($req->regis_doc_status==3){
               $w02 = " AND customer_id in ($arr_case_somepass) " ;
            }elseif($req->regis_doc_status==4){
               $w02 = " AND customer_id in ($arr_case_nopass) " ;
            }else{
               $w02 = "";
            }
          }else{
             $w02 = "";
          }
  // AND customer_id in (0)

          if(!empty($req->startDate) && !empty($req->endDate)){
             $w01 = " and date(register_files.created_at) BETWEEN '".$req->startDate."' AND '".$req->endDate."'  " ;
          }else{
             $w01 = "";
          }


        $sTable = DB::select("

              SELECT id,created_at,customer_id,type,regis_doc_status,updated_at FROM `register_files`
              where 1
                      ".$w01."
                      ".$w02."
              group by customer_id
              ORDER BY updated_at DESC

           ");

        $sQuery = \DataTables::of($sTable);
        return $sQuery
         ->addColumn('customer_name', function($row) {
          if(@$row->customer_id!=''){
            $Customer = DB::select(" select * from customers where id=".@$row->customer_id." ");
            return @$Customer[0]->user_name." : ".@$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
          }else{
            return '';
          }
        })
        ->addColumn('icon', function($row) {

            $icon = '';
            $type_1 =   DB::select(" SELECT regis_doc_status FROM `register_files`  where customer_id=".$row->customer_id." AND type=1 group by customer_id,type ");
            if($type_1){
                $filetype = DB::select(" select * from dataset_regis_filetype where id=1 ");
                foreach ($type_1 as $key => $value) {
                    if($value->regis_doc_status==0){
                        $icon .= $filetype[0]->icon;
                    }elseif($value->regis_doc_status==1){
                        $icon .= $filetype[0]->icon_pass;
                    }elseif($value->regis_doc_status==2){
                        $icon .= $filetype[0]->icon_nopass;
                    }else{
                        $icon .= $filetype[0]->icon_nosend;
                    }
                }
            }else{
                $filetype = DB::select(" select * from dataset_regis_filetype where id=1 ");
                $icon .= $filetype[0]->icon_nosend;
             }



            $type_2 =   DB::select(" SELECT regis_doc_status FROM `register_files`  where customer_id=".$row->customer_id." AND type=2 group by customer_id,type ");
            if($type_2){
                $filetype = DB::select(" select * from dataset_regis_filetype where id=2 ");
                foreach ($type_2 as $key => $value) {
                    if($value->regis_doc_status==0){
                        $icon .= $filetype[0]->icon;
                    }elseif($value->regis_doc_status==1){
                        $icon .= $filetype[0]->icon_pass;
                    }elseif($value->regis_doc_status==2){
                        $icon .= $filetype[0]->icon_nopass;
                    }else{
                        $icon .= $filetype[0]->icon_nosend;
                    }
                }
             }else{
                $filetype = DB::select(" select * from dataset_regis_filetype where id=2 ");
                $icon .= $filetype[0]->icon_nosend;
             }


            $type_3 =   DB::select(" SELECT regis_doc_status FROM `register_files`  where customer_id=".$row->customer_id." AND type=3 group by customer_id,type ");
            if($type_3){
                $filetype = DB::select(" select * from dataset_regis_filetype where id=3 ");
                foreach ($type_3 as $key => $value) {
                    if($value->regis_doc_status==0){
                        $icon .= $filetype[0]->icon;
                    }elseif($value->regis_doc_status==1){
                        $icon .= $filetype[0]->icon_pass;
                    }elseif($value->regis_doc_status==2){
                        $icon .= $filetype[0]->icon_nopass;
                    }else{
                        $icon .= $filetype[0]->icon_nosend;
                    }
                }
              }else{
                $filetype = DB::select(" select * from dataset_regis_filetype where id=3 ");
                $icon .= $filetype[0]->icon_nosend;
             }


            $type_4 =   DB::select(" SELECT regis_doc_status FROM `register_files`  where customer_id=".$row->customer_id." AND type=4 group by customer_id,type ");
            if($type_4){
                $filetype = DB::select(" select * from dataset_regis_filetype where id=4 ");
                foreach ($type_4 as $key => $value) {
                    if($value->regis_doc_status==0){
                        $icon .= $filetype[0]->icon;
                    }elseif($value->regis_doc_status==1){
                        $icon .= $filetype[0]->icon_pass;
                    }elseif($value->regis_doc_status==2){
                        $icon .= $filetype[0]->icon_nopass;
                    }else{
                        $icon .= $filetype[0]->icon_nosend;
                    }
                }
             }else{
                $filetype = DB::select(" select * from dataset_regis_filetype where id=4 ");
                $icon .= $filetype[0]->icon_nosend;
             }

            return $icon;

        })
        ->escapeColumns('icon')
        ->addColumn('created_at', function($row) {
            return date("Y-m-d",strtotime($row->created_at));
        })
        ->escapeColumns('created_at')
        ->addColumn('regis_date_doc', function($row) {
            $regis_date_doc = DB::select(" select regis_date_doc from customers where id=".@$row->customer_id." ");
            if($regis_date_doc[0]->regis_date_doc!=""){
              return date("Y-m-d",strtotime($regis_date_doc[0]->regis_date_doc));
            }else{
              return '';
            }

        })
        ->make(true);
      }


}
