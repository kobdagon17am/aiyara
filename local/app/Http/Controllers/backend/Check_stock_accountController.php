<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Session;

class Check_stock_accountController extends Controller
{

    public function index(Request $request)
    {
  
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $sStocks_account_code = \App\Models\Backend\Stocks_account_code::get();
      // dd($sStocks_account_code);

      return View('backend.check_stock_account.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,'sBranchs'=>$sBranchs,'sStocks_account_code'=>$sStocks_account_code,
        ) );

    }

    public function create()
    {

      // return View('backend.check_stock_account.form');
      $Products = DB::select("SELECT products.id as product_id,
      products.product_code,
      (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
      FROM
      products_details
      Left Join products ON products_details.product_id_fk = products.id
      WHERE lang_id=1");

      // $Check_stock = \App\Models\Backend\Check_stock_account::get();
      $Check_stock = \App\Models\Backend\Check_stock::get();

      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

      return View('backend.check_stock_account.form')->with(
        array(
           'Products'=>$Products,'Check_stock'=>$Check_stock,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,
           'sBusiness_location'=>$sBusiness_location,
        ) );


    }


    public function store(Request $request)
    {
      // dd($request->all());
      if(isset($request->save_to_stock_check)){

         // DB::select(" TRUNCATE db_stocks_account; ");
         // return $request->row_id;
         if(!empty($request->row_id)){
            $arr_row_id = implode(',', $request->row_id);
         }else{
            $arr_row_id = 0 ;
         }

          $db_stocks =DB::select(" select * from db_stocks where id in($arr_row_id)  ");
          $branchs = DB::select("SELECT * FROM branchs where id=".@$db_stocks[0]->business_location_id_fk."");
    
    // ปรับใหม่ ถ้า Status = NEW จะยังไม่สร้างรหัส 
          $REF_CODE = DB::select(" SELECT REF_CODE,SUBSTR(REF_CODE,4) AS REF_NO FROM DB_STOCKS_ACCOUNT_CODE ORDER BY REF_CODE DESC LIMIT 1 ");
          if($REF_CODE){
              $ref_code = 'ADJ'.sprintf("%05d",intval(@$REF_CODE[0]->REF_NO)+1);
          }else{
              $ref_code = 'ADJ'.sprintf("%05d",1);
          }
          // dd($ref_code);

          $stocks_account_code = new \App\Models\Backend\Stocks_account_code;
          if( $stocks_account_code ){
            $stocks_account_code->business_location_id_fk = @$db_stocks[0]->business_location_id_fk;
            $stocks_account_code->branch_id_fk = @$branchs[0]->id;
            // $stocks_account_code->ref_code = $ref_code;
            $stocks_account_code->action_user = (\Auth::user()->id);
                        

            $stocks_account_code->condition_business_location = $request->condition_business_location;
            $stocks_account_code->condition_branch = $request->condition_branch;
            $stocks_account_code->condition_warehouse = $request->condition_warehouse;
            $stocks_account_code->condition_zone = $request->condition_zone;
            $stocks_account_code->condition_shelf = $request->condition_shelf;
            $stocks_account_code->condition_shelf_floor = $request->condition_shelf_floor;
            $stocks_account_code->condition_product = $request->condition_product;
            $stocks_account_code->condition_lot_number = $request->condition_lot_number;

            $stocks_account_code->action_date = date('Y-m-d');
            $stocks_account_code->created_at = date('Y-m-d H:i:s');
            $stocks_account_code->save();

          }

          
          $inv = DB::select(" SELECT run_code,SUBSTR(run_code,-5) AS REF_NO FROM DB_STOCKS_ACCOUNT ORDER BY run_code DESC LIMIT 1 ");
          if($inv){
                $REF_CODE = 'A'.$branchs[0]->business_location_id_fk.date("ymd");
                $REF_NO = intval(@$inv[0]->REF_NO);
          }else{
                $REF_CODE = 'A'.$branchs[0]->business_location_id_fk.date("ymd");
                $REF_NO = 0;
          }
          // dd(@$invoice_code);

         $i=1;
         foreach ($db_stocks as $key => $value) {

           //  $run_code = $REF_CODE.(sprintf("%05d",$REF_NO+$i)); 
          // ปรับใหม่ ถ้า Status = NEW จะยังไม่สร้างรหัส 
             $run_code = ''; 

             DB::select(" INSERT INTO db_stocks_account 
              (
                stocks_account_code_id_fk,
                run_code,
                business_location_id_fk,
                branch_id_fk,
                product_id_fk,
                lot_number,
                lot_expired_date,
                amt,
                amt_check,
                product_unit_id_fk,
                warehouse_id_fk,
                zone_id_fk,
                shelf_id_fk,
                shelf_floor
              )
               values 
              (
              '$stocks_account_code->id',
              '$run_code',
              '$value->business_location_id_fk',
              '$value->branch_id_fk',
              '$value->product_id_fk',
              '$value->lot_number',
              '$value->lot_expired_date',
              '$value->amt',
              '$value->amt',
              '$value->product_unit_id_fk',
              '$value->warehouse_id_fk',
              '$value->zone_id_fk',
              '$value->shelf_id_fk',
              '$value->shelf_floor'
              ) 

               ");

             $i++;
         }

          // return redirect()->to(url("backend/check_stock_account"));
         return redirect()->to(url("backend/check_stock_account/".$stocks_account_code->id."/edit"));


      }

    }


    public function Adjust($id)
    {
      // dd($id);
      // return View('backend.check_stock_account.adjust');

      $sRow = \App\Models\Backend\Check_stock_check::find($id);
      // dd($sRow);

      $sStocks_account_code = \App\Models\Backend\Stocks_account_code::find($sRow->stocks_account_code_id_fk);
      // dd($sStocks_account_code);

      $sRow['status_accepted'] = $sStocks_account_code->status_accepted;
      // dd($sStocks_account_code->status_accepted);
      // dd($sRow['status_accepted']);
      // dd($sRow);

        $Products = DB::select("SELECT products.id as product_id,
      products.product_code,
      (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
      FROM
      products_details
      Left Join products ON products_details.product_id_fk = products.id
      WHERE lang_id=1");

      // 0=NEW,1=PENDDING,2=REQUEST,3=ACCEPTED/APPROVED,4=NO APPROVED,5=CANCELED
      if(@$sStocks_account_code->status_accepted=="1"){
          $status_accepted = 'PENDDING';
      }else if(@$sStocks_account_code->status_accepted=="2"){
          $status_accepted = 'REQUEST';
      }else if(@$sStocks_account_code->status_accepted=="3"){
          $status_accepted = 'ACCEPTED';
      }else if(@$sStocks_account_code->status_accepted=="4"){
          $status_accepted = 'NO APPROVED';
      }else if(@$sStocks_account_code->status_accepted=="5"){
          $status_accepted = 'CANCELED';
      }else{
          $status_accepted = 'NEW';
      }

      if(empty($sRow)){
        return redirect()->to(url("backend/check_stock_account/"));
      }

// condition_business_location
// condition_branch
// condition_warehouse
// condition_zone
// condition_shelf
// condition_shelf_floor
// condition_product
// condition_lot_number

        $sL = DB::select(" select * from dataset_business_location where id=".($sStocks_account_code->condition_business_location?$sStocks_account_code->condition_business_location:0)." ");
        $sB = DB::select(" select * from branchs where id=".($sStocks_account_code->condition_branch?$sStocks_account_code->condition_branch:0)." ");
        $sW = DB::select(" select * from warehouse where id=".($sStocks_account_code->condition_warehouse?$sStocks_account_code->condition_warehouse:0)." ");
        $sZ = DB::select(" select * from zone where id=".($sStocks_account_code->condition_zone?$sStocks_account_code->condition_zone:0)." ");
        $sS = DB::select(" select * from shelf where id=".($sStocks_account_code->condition_shelf?$sStocks_account_code->condition_shelf:0)." ");
        $shelf_floor = @$sStocks_account_code->condition_shelf_floor?" > ชั้น ".@$sStocks_account_code->condition_shelf_floor:NULL;

        $sW_No = $sStocks_account_code->condition_warehouse==0||$sStocks_account_code->condition_warehouse==NULL?', คลัง':NULL;// dd($sW_No);
        $sZ_No = $sStocks_account_code->condition_zone==0||$sStocks_account_code->condition_zone==NULL?', โซน':NULL; //dd($sZ_No);
        $sS_No = $sStocks_account_code->condition_shelf==0||$sStocks_account_code->condition_shelf==NULL?', Shelf':NULL; //dd($sS_No);
        $shelf_floor_No = $sStocks_account_code->condition_shelf_floor==0||$sStocks_account_code->condition_shelf_floor==NULL?', ชั้น':NULL; //dd($shelf_floor_No);
        $sProductSel = $sStocks_account_code->condition_product==0||$sStocks_account_code->condition_product==NULL?', สินค้า':NULL; //dd($shelf_floor_No);
        $sLot = $sStocks_account_code->condition_lot_number==0||$sStocks_account_code->condition_lot_number==NULL?', Lot Number':NULL; //dd($shelf_floor_No);


        $ConditionNo = $sW_No." ".$sZ_No." ".$sS_No." ".$shelf_floor_No." ".$sProductSel." ".$sLot ;

        $ConditionNoChoose = "เงื่อนไขที่ไม่ได้ระบุ  ".$ConditionNo;
        Session::put('session_ConditionNoChoose', $ConditionNoChoose);      

        // dd($ConditionNoChoose);  

        if(!empty(@$sStocks_account_code->condition_product)){

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$sStocks_account_code->condition_product." AND lang_id=1");

            $sProduct =  ' > สินค้า : '.@$Products[0]->product_code." : ".@$Products[0]->product_name;

        }else{
          $sProduct = '';
        }

        $lot_number = @$sStocks_account_code->condition_lot_number?" > ".@$sStocks_account_code->condition_lot_number:NULL;

        $Condition = @$sL[0]->txt_desc." > ".
        @$sB[0]->b_name.' '.(@$sW[0]->w_name?' > '.@$sW[0]->w_name:NULL).' '.(@$sZ[0]->z_name?' > '.@$sZ[0]->z_name:NULL).' '.(@$sS[0]->s_name?' > '.@$sS[0]->s_name:NULL).$shelf_floor.' '.$sProduct.' '.$lot_number;


        $RefCode = "รหัสอ้างอิง : ".@$sStocks_account_code->ref_code ;
        Session::put('session_RefCode', $RefCode);

        $ConditionChoose = "เงื่อนไขที่ระบุ : ".$Condition;
        Session::put('session_ConditionChoose', $ConditionChoose);

        // 0=NEW,1=PENDDING,2=REQUEST,3=ACCEPTED/APPROVED,4=NO APPROVED,5=CANCELED
        $sAction_user = DB::select(" select * from ck_users_admin where id=".$sStocks_account_code->action_user." ");
        // dd($sAction_user);
        if(@$sStocks_account_code->status_accepted==0||@$sStocks_account_code->status_accepted==1||@$sStocks_account_code->status_accepted==2||@$sStocks_account_code->status_accepted==5){
           $sAction_user = " ผู้ดำเนินการ : ".@$sAction_user[0]->name." > วันที่ : ".@$sStocks_account_code->action_date." ";
           Session::put('session_Action_user', @$sAction_user);
        }

        $sApprover = DB::select(" select * from ck_users_admin where id=".$sStocks_account_code->approver." ");
        if(@$sStocks_account_code->status_accepted==3||@$sStocks_account_code->status_accepted==4){
           $sApprover = " ผู้อนุมัติ : ".@$sApprover[0]->name." > วันที่ : ".@$sStocks_account_code->approve_date." ";
           Session::put('session_Approver', @$sApprover);
        }

        $Status_accepted = "[ สถานะ : ".$status_accepted." ]";
        Session::put('session_Status_accepted', $Status_accepted);



      $Check_stock = \App\Models\Backend\Check_stock::get();
      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

      $Action_user = DB::select(" select * from ck_users_admin ");

      return View('backend.check_stock_account.adjust')->with(
        array(
           'id'=>$id,
           'sRow'=>$sRow,
           'Products'=>$Products,'Check_stock'=>$Check_stock,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,
           'sBusiness_location'=>$sBusiness_location,
           'Condition'=>$Condition,
           'Action_user'=>$Action_user,
        ));

    }

    public function ScanQr($id)
    {
      // dd($id);
      $r = DB::select(" Select * from db_pick_warehouse_tmp where invoice_code='$id' ");
      // $rSumAmt = DB::select(" Select sum(amt) as amt  from db_pick_warehouse_tmp where invoice_code='$id' ");
      $warehouse_qrcode = DB::select(" Select * from db_pick_warehouse_qrcode where invoice_code='$id' ");
      // dd($r);
      // dd($rSumAmt[0]->amt);
      // dd($warehouse_qrcode);
      
      return View('backend.pay_product_receipt.scan_qr')->with(
        array(
           'id'=>$id,
           'sRow'=>$r,
           'warehouse_qrcode'=>$warehouse_qrcode,
           // 'rSumAmt'=>$rSumAmt,
        ));
    }


    public function edit($id)
    {
      // return View('backend.check_stock_account.form');
      // dd($id);
      $sRow = \App\Models\Backend\Stocks_account_code::find($id);
      // dd($sRow->action_user);

      $Products = DB::select("SELECT products.id as product_id,
      products.product_code,
      (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
      FROM
      products_details
      Left Join products ON products_details.product_id_fk = products.id
      WHERE lang_id=1");

      // 0=NEW,1=PENDDING,2=REQUEST,3=ACCEPTED/APPROVED,4=NO APPROVED,5=CANCELED
      if(@$sRow->status_accepted=="1"){
          $status_accepted = 'PENDDING';
      }else if(@$sRow->status_accepted=="2"){
          $status_accepted = 'REQUEST';
      }else if(@$sRow->status_accepted=="3"){
          $status_accepted = 'ACCEPTED';
      }else if(@$sRow->status_accepted=="4"){
          $status_accepted = 'NO APPROVED';
      }else if(@$sRow->status_accepted=="5"){
          $status_accepted = 'CANCELED';
      }else{
          $status_accepted = 'NEW';
      }

      Session::put('session_status_accepted_id', @$sRow->status_accepted);  

      if(empty($sRow)){
        return redirect()->to(url("backend/check_stock_account/"));
      }

// condition_business_location
// condition_branch
// condition_warehouse
// condition_zone
// condition_shelf
// condition_shelf_floor
// condition_product
// condition_lot_number

        $sL = DB::select(" select * from dataset_business_location where id=".($sRow->condition_business_location?$sRow->condition_business_location:0)." ");
        $sB = DB::select(" select * from branchs where id=".($sRow->condition_branch?$sRow->condition_branch:0)." ");
        $sW = DB::select(" select * from warehouse where id=".($sRow->condition_warehouse?$sRow->condition_warehouse:0)." ");
        $sZ = DB::select(" select * from zone where id=".($sRow->condition_zone?$sRow->condition_zone:0)." ");
        $sS = DB::select(" select * from shelf where id=".($sRow->condition_shelf?$sRow->condition_shelf:0)." ");
        $shelf_floor = @$sRow->condition_shelf_floor?" > ชั้น ".@$sRow->condition_shelf_floor:NULL;

        $sW_No = $sRow->condition_warehouse==0||$sRow->condition_warehouse==NULL?', คลัง':NULL;// dd($sW_No);
        $sZ_No = $sRow->condition_zone==0||$sRow->condition_zone==NULL?', โซน':NULL; //dd($sZ_No);
        $sS_No = $sRow->condition_shelf==0||$sRow->condition_shelf==NULL?', Shelf':NULL; //dd($sS_No);
        $shelf_floor_No = $sRow->condition_shelf_floor==0||$sRow->condition_shelf_floor==NULL?', ชั้น':NULL; //dd($shelf_floor_No);
        $sProductSel = $sRow->condition_product==0||$sRow->condition_product==NULL?', สินค้า':NULL; //dd($shelf_floor_No);
        $sLot = $sRow->condition_lot_number==0||$sRow->condition_lot_number==NULL?', Lot Number':NULL; //dd($shelf_floor_No);


        $ConditionNo = $sW_No." ".$sZ_No." ".$sS_No." ".$shelf_floor_No." ".$sProductSel." ".$sLot ;

        $ConditionNoChoose = "เงื่อนไขที่ไม่ได้ระบุ  ".$ConditionNo;
        Session::put('session_ConditionNoChoose', $ConditionNoChoose);      

        // dd($ConditionNoChoose);  

        if(!empty(@$sRow->condition_product)){

            $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$sRow->condition_product." AND lang_id=1");

            $sProduct =  ' > สินค้า : '.@$Products[0]->product_code." : ".@$Products[0]->product_name;

        }else{
          $sProduct = '';
        }

        $lot_number = @$sRow->condition_lot_number?" > ".@$sRow->condition_lot_number:NULL;

        $Condition = @$sL[0]->txt_desc." > ".
        @$sB[0]->b_name.' '.(@$sW[0]->w_name?' > '.@$sW[0]->w_name:NULL).' '.(@$sZ[0]->z_name?' > '.@$sZ[0]->z_name:NULL).' '.(@$sS[0]->s_name?' > '.@$sS[0]->s_name:NULL).$shelf_floor.' '.$sProduct.' '.$lot_number;


        $RefCode = "รหัสอ้างอิง : ".@$sRow->ref_code ;
        Session::put('session_RefCode', $RefCode);

        $ConditionChoose = "เงื่อนไขที่ระบุ : ".$Condition;
        Session::put('session_ConditionChoose', $ConditionChoose);

        // 0=NEW,1=PENDDING,2=REQUEST,3=ACCEPTED/APPROVED,4=NO APPROVED,5=CANCELED
        $sAction_user = DB::select(" select * from ck_users_admin where id=".$sRow->action_user." ");
        if(@$sRow->status_accepted==0||@$sRow->status_accepted==1||@$sRow->status_accepted==2||@$sRow->status_accepted==5){
           $sAction_user = " ผู้ดำเนินการ : ".@$sAction_user[0]->name." > วันที่ : ".@$sRow->action_date." ";
           Session::put('session_Action_user', @$sAction_user);
        }

        $sApprover = DB::select(" select * from ck_users_admin where id=".$sRow->approver." ");
        if(@$sRow->status_accepted==3||@$sRow->status_accepted==4){
           $sApprover = " ผู้อนุมัติ : ".@$sApprover[0]->name." > วันที่ : ".@$sRow->approve_date." ";
           Session::put('session_Approver', @$sApprover);
        }

        $Status_accepted = "[ สถานะ : ".$status_accepted." ]";
        Session::put('session_Status_accepted', $Status_accepted);



      $Check_stock = \App\Models\Backend\Check_stock::get();
      $User_branch_id = \Auth::user()->branch_id_fk;
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $sBranchs = \App\Models\Backend\Branchs::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();

      return View('backend.check_stock_account.form')->with(
        array(
           'sRow'=>$sRow,'Products'=>$Products,'Check_stock'=>$Check_stock,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id,
           'sBusiness_location'=>$sBusiness_location,
           'Condition'=>$Condition,
        ) );

    }

    public function update(Request $request, $id)
    {
      // dd($request->all());

        if(!empty($request->Adjust)){

            $amt = request('amt_check') - request('amt');

            $sRow = \App\Models\Backend\Check_stock_account::find(request('id'));
            $sRow->amt_check    = request('amt_check');
            $sRow->amt_diff    = $amt ;
            $sRow->action_user    = request('action_user');
            $sRow->save();

            if($amt!=0){


        // ปรับใหม่ ถ้า Status = NEW จะยังไม่สร้างรหัส 
              $REF_CODE = DB::select(" SELECT business_location_id_fk,REF_CODE,SUBSTR(REF_CODE,4) AS REF_NO FROM DB_STOCKS_ACCOUNT_CODE ORDER BY REF_CODE DESC LIMIT 1 ");
              if($REF_CODE){
                  $ref_code = 'ADJ'.sprintf("%05d",intval(@$REF_CODE[0]->REF_NO)+1);
              }else{
                  $ref_code = 'ADJ'.sprintf("%05d",1);
              }

              $business_location_id_fk =  @$REF_CODE[0]->business_location_id_fk;

              DB::update(" UPDATE db_stocks_account_code SET status_accepted=1 where id=".$sRow->stocks_account_code_id_fk."");
              DB::select(" UPDATE  db_stocks_account_code SET ref_code='$ref_code' where id=".$sRow->stocks_account_code_id_fk." AND (ref_code='' or ref_code is null) ; ");


              $branchs = DB::select("SELECT * FROM branchs where id=$business_location_id_fk ");

              $db_stocks_account =DB::select(" select * from db_stocks_account where stocks_account_code_id_fk =".$sRow->stocks_account_code_id_fk." AND (run_code='' or run_code is null) ; ");

              $inv = DB::select(" SELECT run_code,SUBSTR(run_code,-5) AS REF_NO FROM DB_STOCKS_ACCOUNT ORDER BY run_code DESC LIMIT 1 ");
              if($inv){
                    $REF_CODE = 'A'.$business_location_id_fk.date("ymd");
                    $REF_NO = intval(@$inv[0]->REF_NO);
              }else{
                    $REF_CODE = 'A'.$business_location_id_fk.date("ymd");
                    $REF_NO = 0;
              }

              $i=1;
              foreach ($db_stocks_account as $key => $value) {

                 $run_code = $REF_CODE.(sprintf("%05d",$REF_NO+$i)); 

                 DB::select(" UPDATE db_stocks_account SET run_code = '$run_code' where stocks_account_code_id_fk =".$sRow->stocks_account_code_id_fk." AND id=".$value->id." AND (run_code='' or run_code is null) ");

                 $i++;
             }



            }

            return redirect()->to(url("backend/check_stock_account/adjust/".request('id')."?from_id=".request('from_id')));


        }else{

           return $this->form();

        }

    }

    public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {

          // dd(request('id'));

        if(!empty(request('approved'))){

          $sRow = \App\Models\Backend\Stocks_account_code::find(request('id'));
          DB::select(' UPDATE db_stocks_account SET status_accepted='.request('approve_status').',action_date=CURDATE(),approver='.request('approver').',approve_date=CURDATE() where stocks_account_code_id_fk='.request('id').' ');

          $stocks_account = DB::select(' SELECT * from db_stocks_account WHERE stocks_account_code_id_fk='.request('id').' AND amt_diff<>0 ');

          // dd($stocks_account);

          foreach ($stocks_account as $key => $value) {
              // echo $value->amt_diff;
              // echo $value->product_id_fk;
              // echo $value->lot_number;
            if(request('approve_status')==3){ // อนุมัติ 
               DB::update(' UPDATE db_stocks SET amt = ( amt + ('.$value->amt_diff.') ) where product_id_fk = "'.$value->product_id_fk.'" AND lot_number="'.$value->lot_number.'" ');
             }

          } 
          // dd();

          $sRow->approver    = request('approver');
          $sRow->status_accepted    = request('approve_status');
          $sRow->approve_date    = date('Y-m-d');
          $sRow->note    = request('note');
          $sRow->save();

          \DB::commit();

        }

           return redirect()->to(url("backend/check_stock_account/".request('id')."/edit?Approve"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        // return redirect()->action('backend\DeliveryController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {

      $sRow = \App\Models\Backend\Check_stock_account::find($id);
      if( $sRow ){
        // $sRow->forceDelete();
         // 0=NEW,1=PENDDING,2=REQUEST,3=ACCEPTED/APPROVED,4=NO APPROVED,5=CANCELED
        DB::select(" UPDATE db_stocks_account_code SET status_accepted='5' WHERE status_accepted in(1,2) AND id=$id ");
        DB::select(" UPDATE
            db_stocks_account
            Left Join db_stocks_account_code ON db_stocks_account.stocks_account_code_id_fk = db_stocks_account_code.id
            SET 
            db_stocks_account.status_accepted=
            db_stocks_account_code.status_accepted
            WHERE db_stocks_account.stocks_account_code_id_fk=$id ");
        DB::select(" DELETE
            FROM
            db_stocks_account
            WHERE db_stocks_account.stocks_account_code_id_fk=
            (SELECT id FROM db_stocks_account_code WHERE status_accepted=0 AND id =$id ) ");
        DB::select(" DELETE FROM db_stocks_account_code  WHERE status_accepted=0 AND id=$id ");

      }
      return response()->json(\App\Models\Alert::Msg('success'));

    }

    public function Datatable(){

      $sTable = \App\Models\Backend\Check_stock_account::search()->orderBy('id', 'asc');
      
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_name', function($row) {
        
          $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE products.id=".$row->product_id_fk." AND lang_id=1");

        return @$Products[0]->product_code." : ".@$Products[0]->product_name;

      })
      ->addColumn('lot_expired_date', function($row) {
        $d = strtotime($row->lot_expired_date); 
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('date_in_stock', function($row) {
        $d = strtotime($row->pickup_firstdate); 
        return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('warehouses', function($row) {
        $sBranchs = DB::select(" select * from branchs where id=".$row->branch_id_fk." ");
        $warehouse = DB::select(" select * from warehouse where id=".$row->warehouse_id_fk." ");
        $zone = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        $shelf = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        return @$sBranchs[0]->b_name.'/'.@$warehouse[0]->w_name.'/'.@$zone[0]->z_name.'/'.@$shelf[0]->s_name;
      })      
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }


}
