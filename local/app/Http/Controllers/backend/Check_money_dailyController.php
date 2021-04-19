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
       $sRowCheck_money_daily = \App\Models\Backend\Check_money_daily::where('fronstore_id_fk',$id)->get();
       $sRowCheck_money_daily_AiCash = \App\Models\Backend\Check_money_daily::where('add_ai_cash_id_fk',$id)->get();
       // dd($sRowCheck_money_daily_AiCash);

       return View('backend.check_money_daily.form')->with(
        array(
           'sRow'=>$sRow,
           'sRowAdd_ai_cash'=>$sRowAdd_ai_cash,
           'sRowCheck_money_daily'=>$sRowCheck_money_daily,
           'sRowCheck_money_daily_AiCash'=>$sRowCheck_money_daily_AiCash,
        ) );
    }

    public function update(Request $request, $id)
    {
       // dd($request->all()); 

      if(!empty($request->fronstore_id_fk)){

              if(isset($request->approved) && $request->approved==1){
                // dd($request->all()); 
                  if(request('sRowCheck_money_daily_id')){
                      $sRow = \App\Models\Backend\Check_money_daily::find(request('sRowCheck_money_daily_id'));
                      $sRow->approver = \Auth::user()->id;
                      $sRow->approve_status = request('approve_status') ;
                      $sRow->approve_date = date('Y-m-d');
                      $sRow->note = request('note');
                      $sRow->save();

                      $Frontstore = \App\Models\Backend\Frontstore::find(request('fronstore_id_fk'));
                      $Frontstore->approver = \Auth::user()->id;
                      $Frontstore->approve_status = request('approve_status')  ;
                      $Frontstore->approve_date = date('Y-m-d');
                      $Frontstore->save();
                  }

                  return redirect()->to(url("backend/check_money_daily/".request('fronstore_id_fk')."/edit?fromFrontstore"));

              }else{
                return $this->form($request->sRowCheck_money_daily_id);
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
                    db_orders.pay_type_id,
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
                    Left Join dataset_pay_type ON db_orders.pay_type_id = dataset_pay_type.id
                    Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                    WHERE db_orders.pay_type_id<>0 AND db_orders.id=".$req->id."
            ");
      }else{

          $sTable = DB::select("
                    SELECT
                    db_orders.id ,
                    db_orders.action_user,
                    ck_users_admin.`name` as action_user_name,
                    db_orders.pay_type_id,
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
                    Left Join dataset_pay_type ON db_orders.pay_type_id = dataset_pay_type.id
                    Left Join ck_users_admin ON db_orders.action_user = ck_users_admin.id
                    WHERE db_orders.pay_type_id<>0 
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



}
