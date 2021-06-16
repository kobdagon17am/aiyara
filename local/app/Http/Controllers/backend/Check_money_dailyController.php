<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Check_money_dailyController extends Controller
{

    public function index(Request $request)
    {
      return view('backend.check_money_daily.index');
    }

   public function create()
    {
      return View('backend.check_money_daily.form');
    }

    public function store(Request $request)
    {
      // dd($request->all());
      return $this->form();
    }

    public function edit($id)
    {
      // return $id;
      // dd();
       $sRow = \App\Models\Backend\Frontstore::find($id);
       $sRowAdd_ai_cash = \App\Models\Backend\Add_ai_cash::find($id);
       // dd($sRowAdd_ai_cash);
       $sRowCheck_money_daily = \App\Models\Backend\Check_money_daily::where('id',$id)->get();
       // dd($sRowCheck_money_daily);
       $sRowCheck_money_daily_AiCash = \App\Models\Backend\Check_money_daily::where('id',$id)->get();
       // dd($sRowCheck_money_daily_AiCash);
       $sSent_money_daily = DB::select(" select * from  db_sent_money_daily where id=$id ");

       return View('backend.check_money_daily.form')->with(
        array(
           'sRow'=>$sRow,
           'sRowAdd_ai_cash'=>$sRowAdd_ai_cash,
           'sRowCheck_money_daily'=>$sRowCheck_money_daily,
           'sRowCheck_money_daily_AiCash'=>$sRowCheck_money_daily_AiCash,
           'sSent_money_daily'=>$sSent_money_daily,
        ) );
    }

    public function update(Request $request, $id)
    {
       // dd($request->all()); 

      if(!empty($request->id)){

              if(isset($request->approved) && $request->approved==1){
                // dd($request->all()); 
                  if(request('sRowCheck_money_daily_id')){

                      // $sRow = \App\Models\Backend\Check_money_daily::find($request->id);
                      // $sRow->approver = \Auth::user()->id;
                      // $sRow->approve_status = request('approve_status') ;
                      // $sRow->approve_date = date('Y-m-d');
                      // $sRow->note = request('note');
                      // $sRow->save();

                      // $Frontstore = \App\Models\Backend\Frontstore::find(request('fronstore_id_fk'));
                      // $Frontstore->approver = \Auth::user()->id;
                      // $Frontstore->approve_status = request('approve_status')  ;
                      // $Frontstore->approve_date = date('Y-m-d');
                      // $Frontstore->save();
                     
                     DB::select(" UPDATE `db_sent_money_daily` SET `status_approve`='1', `approver`='".(\Auth::user()->id).")', `approve_date`=now() ,total_money=".$request->sum_total_price2." WHERE (`id`='".$request->id."') ");


                  }

                  return redirect()->to(url("backend/check_money_daily"));

              // }else{
              //   return $this->form($request->sRowCheck_money_daily_id);
              }

      }


      if(!empty($request->add_ai_cash_id_fk)){

         // dd($request->all()); 

              if(isset($request->approved) && $request->approved==1){
                // dd($request->all()); 

                  if(request('sRowCheck_money_daily_AiCash_id')){
                      $sRow = \App\Models\Backend\Check_money_daily::find(request('sRowCheck_money_daily_AiCash_id'));
                      $sRow->approver = \Auth::user()->id;
                      $sRow->approve_status = request('approve_status') ;
                      $sRow->approve_date = date('Y-m-d');
                      $sRow->note = request('note');
                      $sRow->save();

                      $Add_ai_cash = \App\Models\Backend\Add_ai_cash::find(request('add_ai_cash_id_fk'));
                      $Add_ai_cash->approver = \Auth::user()->id;
                      $Add_ai_cash->approve_status = request('approve_status') ;
                      $Add_ai_cash->approve_date = date('Y-m-d');
                      $Add_ai_cash->save();
                  }

                  return redirect()->to(url("backend/check_money_daily/".request('add_ai_cash_id_fk')."/edit?fromAicash"));
              }else{
                return $this->form($request->sRowCheck_money_daily_AiCash_id);
              }
              
      }


      
    }

    
    public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {

        if(!empty(request('fronstore_id_fk'))){

          if(request('sRowCheck_money_daily_id')){
            $sRow = \App\Models\Backend\Check_money_daily::find(request('sRowCheck_money_daily_id'));
          }else{
            $sRow = new \App\Models\Backend\Check_money_daily;
            if(request('sent_money')==1){
              $sRow->sent_note    = 1 ;
              $sRow->sender = \Auth::user()->id;
              $sRow->sent_date = date('Y-m-d');
            }
          }

          $sRow->fronstore_id_fk    = request('fronstore_id_fk');
          $sRow->total_money    = request('total_money');
          $sRow->sent_money_type    = request('sent_money_type');
          $sRow->bank    = request('bank');

          if(request('get_money')==1){
              $sRow->sent_note    = 2 ;
              $sRow->receiver = \Auth::user()->id;
              $sRow->receive_date = date('Y-m-d');            
          }          
          
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

           return redirect()->to(url("backend/check_money_daily/".request('fronstore_id_fk')."/edit?fromFrontstore"));

        }

        if(!empty(request('add_ai_cash_id_fk'))){

           // dd(request('sRowCheck_money_daily_AiCash'));

            if(request('sRowCheck_money_daily_AiCash')){
              $sRow = \App\Models\Backend\Check_money_daily::find(request('sRowCheck_money_daily_AiCash'));
            }else{
              $sRow = new \App\Models\Backend\Check_money_daily;
              if(request('sent_money')==1){
                $sRow->sent_note    = 1 ;
                $sRow->sender = \Auth::user()->id;
                $sRow->sent_date = date('Y-m-d');
              }
            }

            $sRow->add_ai_cash_id_fk    = request('add_ai_cash_id_fk');
            $sRow->total_money    = request('total_money');
            $sRow->sent_money_type    = request('sent_money_type');
            $sRow->bank    = request('bank');

            if(request('get_money')==1){
                $sRow->sent_note    = 2 ;
                $sRow->receiver = \Auth::user()->id;
                $sRow->receive_date = date('Y-m-d');            
            }          
            
            $sRow->created_at = date('Y-m-d H:i:s');
            $sRow->save();

            return redirect()->to(url("backend/check_money_daily/".request('add_ai_cash_id_fk')."/edit?fromAicash"));

          }


          \DB::commit();


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Check_money_dailyController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // $sRow = \App\Models\Backend\Check_money_daily::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(Request $req){

      if(!empty($req->id)){

          $sTable = DB::select("
                    SELECT
                    db_orders.id ,
                    db_orders.action_user,
                    ck_users_admin.`name` as action_user_name,
                    db_orders.pay_type_id_fk,
                    dataset_pay_type.detail AS pay_type,
                    date(db_orders.action_date) AS action_date,
                    db_orders.cash_pay,
                    db_orders.credit_price,
                    db_orders.transfer_price,
                    db_orders.aicash_price,
                    db_orders.shipping_price,
                    db_orders.fee_amt,
                    db_orders.total_price,
                    db_orders.approve_status
                    FROM
                    db_orders
                    Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                    Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                    WHERE db_orders.pay_type_id_fk<>0 AND db_orders.id=".$req->id."
            ");
      }else{

          $sTable = DB::select("
                    SELECT
                    db_orders.id ,
                    db_orders.action_user,
                    ck_users_admin.`name` as action_user_name,
                    db_orders.pay_type_id_fk,
                    dataset_pay_type.detail AS pay_type,
                    date(db_orders.action_date) AS action_date,
                    db_orders.cash_pay,
                    db_orders.credit_price,
                    db_orders.transfer_price,
                    db_orders.aicash_price,
                    db_orders.shipping_price,
                    db_orders.fee_amt,
                    db_orders.total_price,
                    db_orders.approve_status
                    FROM
                    db_orders
                    Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                    Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                    WHERE db_orders.pay_type_id_fk<>0 
            ");

      }

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('approve_status', function($row) {
        // 0=รออนุมัติ,1=อนุมัติแล้ว,2=รอชำระ,3=รอจัดส่ง,4=ยกเลิก,5=ไม่อนุมัติ
          if($row->approve_status==1){
            return 'อนุมัติ';
          }else if($row->approve_status==2){
            return 'รอชำระ';
          }else if($row->approve_status==3){
            return 'รอจัดส่ง';
          }else if($row->approve_status==4){
            return 'ยกเลิก';
          }else if($row->approve_status==5){
            return 'ไม่อนุมัติ';
          }else if($row->approve_status==9){
            return 'สำเร็จ';
          }else{
            return 'รออนุมัติ';
          }
      })  
      ->addColumn('action_date', function($row) {
          $d = strtotime($row->action_date);
          return date("d/m/", $d).(date("Y", $d)+543);
      })      
      ->make(true);
    }




    public function DatatableSentMoney(Request $req){
    
      if(isset($req->id)){
        $sTable = DB::select("  SELECT * FROM db_sent_money_daily where id=".$req->id." ");
      }else{
        $sTable = DB::select("  SELECT * FROM db_sent_money_daily  ");
      }
      // WHERE status_cancel=0

      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('column_001', function($row) {

              if(@$row->sender_id!=''){
                $sD = DB::select(" select * from ck_users_admin where id=".$row->sender_id." ");
                 return @$sD[0]->name;
              }else{
                 return '';
              }
      })
      ->escapeColumns('column_001')

      ->addColumn('column_002', function($row) {
         return @$row->time_sent;
      })
      ->escapeColumns('column_002')     

      ->addColumn('column_003', function($row) {

          $sDBSentMoneyDaily = DB::select("
                SELECT
                db_sent_money_daily.*,
                ck_users_admin.`name` as sender
                FROM
                db_sent_money_daily
                Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
                WHERE date(db_sent_money_daily.updated_at)=CURDATE() AND db_sent_money_daily.id=".$row->id."
                order by db_sent_money_daily.time_sent
          ");

           $pn = '';

            foreach(@$sDBSentMoneyDaily AS $r){
                      $sOrders = DB::select("
                            SELECT invoice_code 
                            FROM
                            db_orders where sent_money_daily_id_fk in (".$r->id.");
                        ");

                        foreach ($sOrders as $key => $value) {
                            $pn .=     
                              '<div class="divTableRow">
                              <div class="divTableCell" style="text-align:center;">'. $value->invoice_code.'</div>
                              </div>
                              ';
                      }

                    }

           
              if($row->status_cancel==1){
                 return " - ";
              }else{
                 return $pn;
              }

      })
      ->escapeColumns('column_003') 

      ->addColumn('column_004', function($row) {
           return $row->updated_at;
      })
      ->escapeColumns('column_004') 

      ->addColumn('column_005', function($row) {


          $sDBSentMoneyDaily = DB::select("
                SELECT
                db_sent_money_daily.*,
                ck_users_admin.`name` as sender
                FROM
                db_sent_money_daily
                Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
                WHERE date(db_sent_money_daily.updated_at)=CURDATE() AND db_sent_money_daily.id=".$row->id."
                order by db_sent_money_daily.time_sent
          ");
          
          $arr = [];

             foreach(@$sDBSentMoneyDaily AS $r){
                      $sOrders = DB::select("
                            SELECT * 
                            FROM
                            db_orders where sent_money_daily_id_fk in (".$r->id.");
                        ");

                        foreach ($sOrders as $key => $value) {
                           array_push($arr,$value->id);
                      }
                      }
                      $id = implode(',',$arr);

                       $pn =  '';
          
          if($id){

            $sDBFrontstoreSumCostActionUser = DB::select("
                SELECT
                db_orders.action_user,
                ck_users_admin.`name` as action_user_name,
                db_orders.pay_type_id_fk,
                dataset_pay_type.detail AS pay_type,
                date(db_orders.action_date) AS action_date,
                sum(db_orders.cash_pay) as cash_pay,
                sum(db_orders.credit_price) as credit_price,
                sum(db_orders.transfer_price) as transfer_price,
                sum(db_orders.aicash_price) as aicash_price,
                sum(db_orders.shipping_price) as shipping_price,
                sum(db_orders.fee_amt) as fee_amt,
                sum(db_orders.cash_pay+db_orders.credit_price+db_orders.transfer_price+db_orders.aicash_price+db_orders.shipping_price+db_orders.fee_amt) as total_price
                FROM
                db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                WHERE db_orders.pay_type_id_fk<>0 AND db_orders.id in ($id)
                GROUP BY action_user
           ");

          
           $pn .=   ' <div class="table-responsive">
                  <table class="table table-sm m-0">
                    <thead>
                      <tr>
                        <th>พนักงานขาย</th>
                        <th class="text-right">เงินสด</th>
                        <th class="text-right">Ai-cash</th>
                        <th class="text-right">เงินโอน</th>
                        <th class="text-right">เครดิต</th>
                        <th class="text-right">ค่าธรรมเนียม</th>
                        <th class="text-right">ค่าขนส่ง</th>
                        <th class="text-right">รวมทั้งสิ้น</th>
                      </tr>
                    </thead>

                        <tbody>';

                foreach(@$sDBFrontstoreSumCostActionUser AS $r){
                     @$cnt_row1 += 1;
                              @$sum_cash_pay += $r->cash_pay;
                              @$sum_aicash_price += $r->aicash_price;
                              @$sum_transfer_price += $r->transfer_price;
                              @$sum_credit_price += $r->credit_price;
                              @$sum_shipping_price += $r->shipping_price;
                              @$sum_fee_amt += $r->fee_amt;
                              @$sum_total_price += $r->total_price;
                                  $pn .=   '             <tr>
                                <td>'.$r->action_user_name.'</td>
                                <td class="text-right"> '.number_format($r->cash_pay,2).' </td>
                                <td class="text-right"> '.number_format($r->aicash_price,2).' </td>
                                <td class="text-right"> '.number_format($r->transfer_price,2).' </td>
                                <td class="text-right"> '.number_format($r->credit_price,2).' </td>
                                <td class="text-right"> '.number_format($r->fee_amt,2).' </td>
                                <td class="text-right"> '.number_format($r->shipping_price,2).' </td>
                                <td class="text-right"> '.number_format($r->total_price,2).' </td>
                                              </tr>';

                }
                          
                $pn .=   '         </tbody>
                  </table>
                  
                </div>';
              }

              if($row->status_cancel==1){
                 return "<span style='color:red;'>* รายการนี้ได้ทำการยกเลิกการส่งเงิน</span>";
              }else{
                 return $pn;
              }


      })
      ->escapeColumns('column_005')         

      ->addColumn('column_006', function($row) {

              if($row->status_cancel==1){
                 return "display:none;";
              }else{
                 return  $row->id ;
              }

          
      })
      ->escapeColumns('column_006') 
        ->addColumn('column_007', function($row) {

          if($row->status_approve==1){
             return "<span style='color:green;'>รับเงินแล้ว</span>";
          }else{
             return  "-" ;
          }

          
      })
      ->escapeColumns('column_007') 
      ->addColumn('sum_total_price', function($row) {
       

          $sDBSentMoneyDaily = DB::select("
                SELECT
                db_sent_money_daily.*,
                ck_users_admin.`name` as sender
                FROM
                db_sent_money_daily
                Left Join ck_users_admin ON db_sent_money_daily.sender_id = ck_users_admin.id
                WHERE date(db_sent_money_daily.updated_at)=CURDATE() AND db_sent_money_daily.id=".$row->id."
                order by db_sent_money_daily.time_sent
          ");
          $arr = [];

             foreach(@$sDBSentMoneyDaily AS $r){
                      $sOrders = DB::select("
                            SELECT * 
                            FROM
                            db_orders where sent_money_daily_id_fk in (".$r->id.");
                        ");

                        foreach ($sOrders as $key => $value) {
                           array_push($arr,$value->id);
                      }
                      }
                      $id = implode(',',$arr);

                      if($id){
            $sDBFrontstoreSumCostActionUser = DB::select("
                SELECT
                db_orders.action_user,
                ck_users_admin.`name` as action_user_name,
                db_orders.pay_type_id_fk,
                dataset_pay_type.detail AS pay_type,
                date(db_orders.action_date) AS action_date,
                sum(db_orders.cash_pay) as cash_pay,
                sum(db_orders.credit_price) as credit_price,
                sum(db_orders.transfer_price) as transfer_price,
                sum(db_orders.aicash_price) as aicash_price,
                sum(db_orders.shipping_price) as shipping_price,
                sum(db_orders.fee_amt) as fee_amt,
                sum(db_orders.cash_pay+db_orders.credit_price+db_orders.transfer_price+db_orders.aicash_price+db_orders.shipping_price+db_orders.fee_amt) as total_price
                FROM
                db_orders
                Left Join dataset_pay_type ON db_orders.pay_type_id_fk = dataset_pay_type.id
                Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                WHERE db_orders.pay_type_id_fk<>0 AND db_orders.id in ($id)
                GROUP BY action_user
           ");

                return number_format($sDBFrontstoreSumCostActionUser[0]->total_price,2);
              }
      })
      ->escapeColumns('sum_total_price')    
      ->make(true);
    }





}
