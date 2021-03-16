<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Add_ai_cashController extends Controller
{

    public function index(Request $request)
    {

       return View('backend.add_ai_cash.index');

    }

   public function create()
    {
      $Customer = DB::select(" select * from customers ");
      $sPay_type = DB::select(" select * from dataset_pay_type where id in(5,7,8,10); ");
      $sAccount_bank = \App\Models\Backend\Account_bank::get();
      $sFee = \App\Models\Backend\Fee::get();

      return View('backend.add_ai_cash.form')->with(
      array(
         'Customer'=>$Customer,'sPay_type'=>$sPay_type,'sAccount_bank'=>$sAccount_bank,'sFee'=>$sFee,
      ));
    }

    public function store(Request $request)
    {
      // dd($request->all());
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Add_ai_cash::find($id);
       $Customer = DB::select(" select * from customers ");
       $sPay_type = DB::select(" select * from dataset_pay_type where id in(5,7,8,10); ");

       $action_user = \App\Models\Backend\Permission\Admin::where('id', $sRow->action_user)->get();
       $action_user = @$action_user[0]->name;

       $sAccount_bank = \App\Models\Backend\Account_bank::get();
       $sFee = \App\Models\Backend\Fee::get();

       $CustomerAicash = DB::select(" select * from customers where id=".$sRow->customer_id_fk." ");


       return View('backend.add_ai_cash.form')->with(array(
          'sRow'=>$sRow,
          'id'=>$id,
          'Customer'=>$Customer,
          'action_user'=>$action_user,
          'sPay_type'=>$sPay_type,
          'sAccount_bank'=>$sAccount_bank,
          'sFee'=>$sFee,
          'CustomerAicash'=>$CustomerAicash,
      ));

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
            $sRow = \App\Models\Backend\Add_ai_cash::find($id);

          }else{
            $sRow = new \App\Models\Backend\Add_ai_cash;
          }

          if(request('pay_type_id')!=''){

             if(request('save_new')==1){
                DB::select(" UPDATE customers SET ai_cash=(ai_cash + ".str_replace(',','',request('aicash_amt')).") WHERE (id='".request('customer_id_fk')."') ");
             }
             
             if(request('save_update')==1){
                if($sRow->aicash_amt!=request('aicash_amt')){
                  DB::select(" UPDATE customers SET ai_cash=(ai_cash + ".str_replace(',','',request('aicash_amt')).") WHERE (id='".request('customer_id_fk')."') ");
                }
            }

          }

          // dd(str_replace(',','',request('fee_amt')));

          $sRow->customer_id_fk    = request('customer_id_fk');
          $sRow->aicash_amt    = str_replace(',','',request('aicash_amt'));
          $sRow->action_user    = request('action_user');
          $sRow->pay_type_id    = request('pay_type_id');
          $sRow->fee_amt    = request('fee_amt')?str_replace(',','',request('fee_amt')):0;
          $sRow->total_amt    = str_replace(',','',request('aicash_amt'))+request('fee_amt')?str_replace(',','',request('fee_amt')):0 ;

          if(!empty(request('account_bank_id'))){
            $sRow->account_bank_id = request('account_bank_id');
          }
          if(!empty(request('account_bank_id'))){
            $sRow->transfer_money_datetime = request('transfer_money_datetime');
          }
                    
          $request = app('request');
              if ($request->hasFile('image01')) {
                @UNLINK(@$sRow->file_slip);
                $this->validate($request, [
                  'image01' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
                $image = $request->file('image01');
                $name = 'G'.time() . '.' . $image->getClientOriginalExtension();
                $image_path = 'local/public/files_slip/'.date('Ym').'/';
                $image->move($image_path, $name);
                $sRow->file_slip = $image_path.$name;
              }


          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          DB::select(" UPDATE db_add_ai_cash SET total_amt=(aicash_amt + transfer_price + credit_price + fee_amt ) WHERE (id='".$sRow->id."') ");

          \DB::commit();

          if(request('fromAddAiCash')==1){
            // return redirect()->to(url("backend/frontstore/".request('frontstore_id_fk')."/edit"));
            return redirect()->to(url("backend/add_ai_cash/".$sRow->id."/edit?customer_id=".request('customer_id_fk')."&frontstore_id_fk=".request('frontstore_id_fk')."&fromAddAiCash=1"));
          }else{
            return redirect()->to(url("backend/add_ai_cash/".$sRow->id."/edit"));
          }


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        // return redirect()->action('backend\Add_ai_cashController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // dd($id);
      $sRow = \App\Models\Backend\Add_ai_cash::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }

      // เช็คเรื่องการตัดยอด Ai-Cash
       $ch_aicash_01 = DB::select(" select * from customers where id=".$sRow->customer_id_fk." ");
       // return($ch_aicash_01[0]->ai_cash);
       // $ch_aicash_02 = DB::select(" select * from db_add_ai_cash where id=$id ");
       // return($ch_aicash_02[0]->aicash_amt);

       if( $ch_aicash_01[0]->ai_cash < $sRow->aicash_amt ){
           
       }else{
            DB::select(" UPDATE customers SET ai_cash=(ai_cash-".$sRow->aicash_amt.") where id=".$sRow->customer_id_fk." ");
            DB::select(" UPDATE db_add_ai_cash SET approve_status=4 where id=$id ");
       }

       return response()->json(\App\Models\Alert::Msg('success'));

      
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Add_ai_cash::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('customer_name', function($row) {
        if(@$row->customer_id_fk!=''){
          $Customer = DB::select(" select * from customers where id=".@$row->customer_id_fk." ");
          return @$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
        }else{
          return '';
        }
      })
      ->addColumn('action_user', function($row) {
        if(@$row->action_user!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->action_user." ");
           return @$sD[0]->name;
        }else{
          return '';
        }
      })    
      ->addColumn('pay_type_id', function($row) {
        if(@$row->pay_type_id!=''){
          $sD = DB::select(" select * from dataset_pay_type where id=".$row->pay_type_id." ");
           return @$sD[0]->detail;
        }else{
          return '';
        }
      })  
      ->addColumn('aicash_remain', function($row) {
        if(@$row->customer_id_fk!=''){
          $Customer = DB::select(" select * from customers where id=".@$row->customer_id_fk." ");
          return @$Customer[0]->ai_cash;
        }else{
          return '';
        }
      })          
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
