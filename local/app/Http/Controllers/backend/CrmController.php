<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class CrmController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.crm.index');
      
    }

   public function create()
    {
      $sMainGroup = DB::select(" select * from role_group where id<>1 ");
      $sCrm_topic = \App\Models\Backend\Crm_gettopic::get();
      $sOperator = \App\Models\Backend\Permission\Admin::get();
      $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.crm.form')->with(array('sCrm_topic'=>$sCrm_topic,'sOperator'=>$sOperator,'Customer'=>$Customer,'sMainGroup'=>$sMainGroup));
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Crm::find($id);
       $sCrm_topic = \App\Models\Backend\Crm_gettopic::get();
       $sUser = \App\Models\Backend\Permission\Admin::where('id', $sRow->subject_recipient)->get();
       // dd($sUser[0]->name);
       $subject_recipient = $sUser[0]->name;
       $operator_name = \App\Models\Backend\Permission\Admin::where('id', $sRow->operator)->get();
       $operator_name = $operator_name[0]->name;

       $Customer = DB::select(" select * from customers limit 100 ");
       $sMainGroup = DB::select(" select * from role_group where id<>1 ");

       return View('backend.crm.form')->with(array('sRow'=>$sRow, 'id'=>$id, 'subject_recipient_name'=>$subject_recipient,'sCrm_topic'=>$sCrm_topic,'operator_name'=>$operator_name,'Customer'=>$Customer,'sMainGroup'=>$sMainGroup));
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
            $sRow = \App\Models\Backend\Crm::find($id);
          }else{
            $sRow = new \App\Models\Backend\Crm;
          }

          $sRow->role_group_id_fk    = request('role_group_id_fk');
          $sRow->crm_gettopic_id    = request('crm_gettopic_id');
          $sRow->customers_id_fk    = request('customers_id_fk');
          $sRow->subject_receipt_number    = request('subject_receipt_number');
          $sRow->receipt_date    = request('receipt_date');
          $sRow->topics_reported    = request('topics_reported');
          $sRow->contact_details    = request('contact_details');
          $sRow->subject_recipient    = request('subject_recipient');
          $sRow->operator    = request('operator');
          $sRow->last_update    = request('last_update');

          $sRow->status_close_job    = request('status_close_job')?request('status_close_job'):0;
          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         // return redirect()->action('backend\CrmController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
           return redirect()->to(url("backend/crm/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\CrmController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Crm::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Crm::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('role_name', function($row) {
        $sRole_group = \App\Models\Backend\Role::where('id', $row->role_group_id_fk)->get();
        return $sRole_group[0]->role_name;
      })
      // ->addColumn('recipient_name', function($row) {
      //   $sUser = \App\Models\Backend\Permission\Admin::where('id', $row->subject_recipient)->get();
      //   return $sUser[0]->name;
      // })
      // ->addColumn('operator_name', function($row) {
      //   $sUser = \App\Models\Backend\Permission\Admin::where('id', $row->operator)->get();
      //   return $sUser[0]->name;
      // })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
