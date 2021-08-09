<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Session;

class Transfer_branch_codeController extends Controller
{

    public function index(Request $request)
    {

       $sBusiness_location = \App\Models\Backend\Business_location::get();
       $sBranchs = \App\Models\Backend\Branchs::get();

        $Products = DB::select("SELECT products.id as product_id,
            products.product_code,
            (CASE WHEN products_details.product_name is null THEN '* ไม่ได้กรอกชื่อสินค้า' ELSE products_details.product_name END) as product_name 
            FROM
            products_details
            Left Join products ON products_details.product_id_fk = products.id
            WHERE lang_id=1");

        $Warehouse = \App\Models\Backend\Warehouse::get();
        $Zone = \App\Models\Backend\Zone::get();
        $Shelf = \App\Models\Backend\Shelf::get();

        $User_branch_id = \Auth::user()->branch_id_fk;


        return View('backend.transfer_branch_code.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,
           'Products'=>$Products,
           'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf,'sBranchs'=>$sBranchs,'User_branch_id'=>$User_branch_id
        ) );
      
    }

 public function create()
    {
      $receive_id = \App\Models\Backend\General_receive::get();
      $Products = \App\Models\Backend\Products::get();
      $ProductsUnit = \App\Models\Backend\Product_unit::get();
      $Warehouse = \App\Models\Backend\Warehouse::get();
      $Zone = \App\Models\Backend\Zone::get();
      $Shelf = \App\Models\Backend\Shelf::get();
      return View('backend.transfer_branch_code.form')->with(
        array(
           'receive_id'=>$receive_id,'Products'=>$Products,'ProductsUnit'=>$ProductsUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf
        ) );
    }
    public function store(Request $request)
    {
      // dd($request->all());

              // return $this->form();
              if($request->save_set_to_warehouse_c_e==1){
              
   
                      $Transfer_branch_code = new \App\Models\Backend\Transfer_branch_code;
                      $Transfer_branch_code->business_location_id_fk = request('business_location_id_fk');
                      $Transfer_branch_code->branch_id_fk = request('branch_id_fk');
                      $Transfer_branch_code->note = request('note');
                      $Transfer_branch_code->action_date = date("Y-m-d");
                      $Transfer_branch_code->action_user = \Auth::user()->id;
                      $Transfer_branch_code->created_at = date("Y-m-d H:i:s");
                      $Transfer_branch_code->save();

                      DB::update(" update db_transfer_branch_code set tr_number=? where id=? ",["TR".sprintf("%05d",$Transfer_branch_code->id),$Transfer_branch_code->id]);

                      for ($i=0; $i < count($request->transfer_choose_id) ; $i++) { 
                          $Transfer_choose = \App\Models\Backend\Transfer_choose_branch::find($request->transfer_choose_id[$i]);
                          DB::insert("  
                             insert into db_transfer_branch_details set  
                             transfer_branch_code_id=? 
                             ,stocks_id_fk=? 
                             ,product_id_fk=? 
                             ,lot_number=? 
                             ,lot_expired_date=? 
                             ,amt=? 
                             ,product_unit_id_fk=? 
                             ,branch_id_fk=? 
                             ,warehouse_id_fk=? 
                             ,zone_id_fk=? 
                             ,shelf_id_fk=? 
                             ,action_user=? 
                             ,action_date=? 
                             ",
                            [
                              $Transfer_branch_code->id
                              ,$Transfer_choose->stocks_id_fk
                              ,$Transfer_choose->product_id_fk
                              ,$Transfer_choose->lot_number
                              ,$Transfer_choose->lot_expired_date
                              ,$Transfer_choose->amt
                              ,$Transfer_choose->product_unit_id_fk
                              ,request('branch_id_fk_to')
                              ,$Transfer_choose->warehouse_id_fk
                              ,$Transfer_choose->zone_id_fk
                              ,$Transfer_choose->shelf_id_fk
                              ,$Transfer_choose->action_user
                              ,$Transfer_choose->action_date
                            ]);
                      }

                     DB::update(" DELETE FROM db_transfer_choose_branch where action_user=? ",[\Auth::user()->id]);

              }

              return redirect()->to(url("backend/transfer_branch"));
        

      
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Transfer_branch_code::find($id);

        $receive_id = \App\Models\Backend\General_receive::get();
        $Products = \App\Models\Backend\Products::get();
        $ProductsUnit = \App\Models\Backend\Product_unit::get();
        $Warehouse = \App\Models\Backend\Warehouse::get();
        $Zone = \App\Models\Backend\Zone::get();
        $Shelf = \App\Models\Backend\Shelf::get();
       $Recipient  = DB::select(" select * from ck_users_admin where id=".$sRow->recipient." ");
      return View('backend.transfer_branch_code.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id,'Recipient'=>$Recipient,'receive_id'=>$receive_id,'Products'=>$Products,'ProductsUnit'=>$ProductsUnit,'Warehouse'=>$Warehouse,'Zone'=>$Zone,'Shelf'=>$Shelf
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
            $sRow = \App\Models\Backend\Transfer_branch_code::find($id);
          }else{
            $sRow = new \App\Models\Backend\Transfer_branch_code;
          }

          // $sRow->general_receive_id_fk    = request('general_receive_id_fk');
          // $sRow->product_id_fk    = request('product_id_fk');
          // $sRow->amt    = request('amt');
          // $sRow->product_unit_id_fk    = request('product_unit_id_fk');
          // $sRow->warehouse_id_fk    = request('warehouse_id_fk');
          // $sRow->zone_id_fk    = request('zone_id_fk');
          // $sRow->shelf_id_fk    = request('shelf_id_fk');
          // $sRow->recipient    = request('recipient');
          // $sRow->approver    = request('approver');
          // $sRow->approve_status    = request('approve_status');
          // $sRow->getin_date = date('Y-m-d H:i:s');

          // $sRow->created_at = date('Y-m-d H:i:s');
          // $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/.transfer_branch_code./".$sRow->id."/edit"));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Transfer_branch_codeController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // DB::select(" DELETE FROM db_transfer_warehouses_details WHERE transfer_branch_code_id=$id ; ");
      // $sRow = \App\Models\Backend\Transfer_branch_code::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // ไม่ได้ลบจริง 
      DB::update(" UPDATE db_transfer_branch_code SET approve_status=2 WHERE id=$id ; ");
      // เอาไว้เปิดดูใบโอนได้
      // DB::update(" UPDATE db_transfer_warehouses_details SET deleted_at=now() WHERE transfer_branch_code_id=$id ; ");
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){
      /*
      Super Admin   => see all 
    1) ถ้าทำ เห็น  
    2) ระบุ สาขา จะเห็น ในสาขา นั้น    =>  1 Or 2
    3) ถ้ามีสิทธิ์อนุมัติ แสดงปุ่มอนุมัติ  (0=รออนุมัติ,1=อนุมัติ,2=ยกเลิก,3=ไม่อนุมัติ)
      */
      // if(\Auth::user()->permission==1){
      //   // 1=Super Admin > see all
      //   $sTable = \App\Models\Backend\Transfer_branch_code::search()->orderBy('action_date', 'desc');
      // }else if(\Auth::user()->branch_id_fk==0){
      //    // 2=User > branch = 0  ไม่ระบุสาขา เห็นทั้งหมด 
      //   $sTable = \App\Models\Backend\Transfer_branch_code::search()->orderBy('action_date', 'desc');
      //  }else{
      //   // กรณีระบุสาขา เห็นเฉพาะสาขาตัวเอง 
      //   if(\Auth::user()->branch_id_fk!=0  && Session::get('roleApprove')==0){
      //     $sTable = \App\Models\Backend\Transfer_branch_code::where('action_user',\Auth::user()->id)->orderBy('action_date', 'desc');
      //   }else if(\Auth::user()->branch_id_fk!=0  && Session::get('roleApprove')==1){
      //     $sTable = \App\Models\Backend\Transfer_branch_code::where('branch_id_fk',\Auth::user()->branch_id_fk)->orderBy('action_date', 'desc');
      //   }else{
      //     // กรณีไม่ระบุสาขา 
      //       $sTable = \App\Models\Backend\Transfer_branch_code::search()->orderBy('action_date', 'desc');
      //   }
      // }


      $branch_id = !empty($req->branch_id) ? $req->branch_id : 0 ;
      if($branch_id>0){
        $branch_id2 = " branch_id_fk = ".$req->branch_id." AND ";
      }else{
        $branch_id2 = "";
      }
      // '0=รออนุมัติ,1=อนุมัติ,2=ยกเลิก,3=ไม่อนุมัติ'
      switch ($req->approve_status) {
        case '':
          $approve_status = "";
          break;
        case '0':
          $approve_status = " approve_status = 0 AND ";
          break;    
        case '1' :
        case '2' :
        case '3' :
          $approve_status = " approve_status = ".$req->approve_status." AND ";
          break;                 
        default:
          $approve_status = "";
          break;
      }
      
      if(!empty($req->startDated)){
          $action_date = "  AND action_date between '".($req->startDated)."' AND  '".($req->endDate)."' ";
      }else{
          $action_date = "";
      }
   
      if(isset($req->id)){
          $id = "  AND id = ".$req->id." ";
      }else{
          $id = "";
      }

      $sTable = DB::select(" SELECT * FROM db_transfer_branch_code 
          WHERE 
          ".$branch_id2." 
          ".$approve_status." 
          (action_user LIKE ".(\Auth::user()->id)." OR 
          (CASE WHEN ".(\Auth::user()->id)." IS NULL OR ".(\Auth::user()->branch_id_fk)." = '' THEN TRUE ELSE branch_id_fk = ".($branch_id)." END))
          ".$action_date." ".$id."
          ORDER BY updated_at DESC ");

      $sQuery = \DataTables::of($sTable);
       return $sQuery
      ->addColumn('action_user', function($row) {
        if(@$row->action_user!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })  
      // ->addColumn('action_date', function($row) {
      //   if(@$row->action_date!=''){
      //     $d = strtotime($row->action_date); return date("d/m/", $d).(date("Y", $d)+543);
      //   }else{
      //     return '';
      //   }
      // })
      ->addColumn('approver', function($row) {
        if(@$row->approver!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".@$row->approver." ");
           return @$sD[0]->name;
        }else{
          return '-';
        }
      })  
      // ->addColumn('approve_date', function($row) {
      //   if(@$row->approve_date!=''){
      //     $d = strtotime($row->approve_date); return date("d/m/", $d).(date("Y", $d)+543);
      //   }else{
      //     return '-';
      //   }
      // })                         
      ->addColumn('status_get', function($row) {
        /*
          `approve_status` int(1) DEFAULT '0' COMMENT '1=อนุมัติ 5=ไม่อนุมัติ',
          `note2` text COMMENT 'หมายเหตุของส่วนการอนุมัติ',
          `tr_status` int(1) DEFAULT '0' COMMENT '1=ได้รับสินค้าครบแล้ว 2=ยังค้างรับสินค้า  3=ใบโอน ที่ถูกยกเลิก',
          */
        // $sD = DB::select(" select approve_status,note2,tr_status,note3,updated_at from `db_transfer_branch_get` where tr_number='".$row->tr_number."' ");
        // if(@$sD){
        //   if(@$sD[0]->approve_status==1){
        //     return '<span style="color:green;">รับสินค้าแล้ว</span>';
        //   }elseif(@$sD[0]->approve_status==5){

        //       $t = '';
        //     if(@$sD[0]->approve_status_getback==1){
        //       $t .= '<br><span style="color:green">รับสินค้าคืนแล้ว ('.date("Y-m-d",strtotime(@$sD[0]->updated_at)).')</span>';
        //       $t .= '<br><span style="color:green">'.@$sD[0]->note3.'</span>';
        //     }elseif(@$sD[0]->approve_status_getback==5){
        //       $t .= '<br><span style="color:green">ปฏิเสธการรับสินค้าคืน ('.date("Y-m-d",strtotime(@$sD[0]->updated_at)).')</span>';
        //       $t .= '<br><span style="color:green">'.@$sD[0]->note3.'</span>';
        //     }

        //     $n = @$sD[0]->note2?"<br>หมายเหตุ ".@$sD[0]->note2:'';
        //     return '<span style="color:red;">ปฏิเสธการรับสินค้า</span>'.$n.$t;

        //   }else{
        //     return 'อยู่ระหว่างการโอน';
        //   }
        // }
      })
      ->escapeColumns('status_get')
      ->addColumn('approve_date_get', function($row) {
        $sD = DB::select(" select approve_date from `db_transfer_branch_get` where tr_number='".@$row->tr_number."' ");
        if(!is_null(@$sD[0]->approve_date)){
            return date("Y-m-d",strtotime(@$sD[0]->approve_date))."<br>".date("H:i:s",strtotime(@$sD[0]->approve_date));
        }
      })
      ->escapeColumns('approve_date_get')

      ->addColumn('created_at', function($row) {
        if(!is_null(@$row->created_at)){
            return date("Y-m-d",strtotime(@$row->created_at));
        }
      })
      ->escapeColumns('created_at')

->addColumn('tr_status', function($row) {
        if($row->tr_status){

            $t1 = '';
            $t2 = '';
            $n1 = '';
            $n2 = '';

             $Transfer_branch_status_01 = \App\Models\Backend\Transfer_branch_status_01::where('id',$row->tr_status)->get();
             $t1 .= @$Transfer_branch_status_01[0]->txt_desc;
            

              $sD = DB::select(" select approve_status,note2,tr_status,note3,updated_at from `db_transfer_branch_get` where tr_number='".$row->tr_number."' ");

              if(@$sD){
                
                 if(@$sD[0]->tr_status==4){

                   $Transfer_branch_status_02 = \App\Models\Backend\Transfer_branch_status_02::where('id',$row->tr_status)->get();
                   // $t .= ''.$Transfer_branch_status_02[0]->txt_desc.'<br>';
                   $t2 .= '<span style="color:red">'.$Transfer_branch_status_02[0]->txt_desc.'</span><br>';

                  // $t = '';
                  // if(@$sD[0]->approve_status_getback==1){
                  //   $t .= '<br><span style="color:green">รับสินค้าคืนแล้ว ('.date("Y-m-d",strtotime(@$sD[0]->updated_at)).')</span>';
                  //   $t .= '<br><span style="color:green">'.@$sD[0]->note3.'</span>';
                  // }elseif(@$sD[0]->approve_status_getback==5){
                  //   $t .= '<br><span style="color:green">ปฏิเสธการรับสินค้าคืน ('.date("Y-m-d",strtotime(@$sD[0]->updated_at)).')</span>';
                    $t2 .= '<span style="color:green">Note: '.@$sD[0]->note2.'</span><br>';
                  // }

                  // $n = @$sD[0]->note2?"<br>หมายเหตุ ".@$sD[0]->note2:'';
                  // return '<span style="color:red;">ปฏิเสธการรับสินค้า</span>'.$n.$t;

                }else{
                  // return 'อยู่ระหว่างการโอน';
                }

                return @$t2.@$t1;
           }
        }

      })
      ->escapeColumns('tr_status')
      
      ->make(true);
    }


}
