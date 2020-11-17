<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class PmController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.pm.index');
      
    }

 public function create()
    {
      $sMainGroup = DB::select(" select * from role_group where id<>1 ");
      $sOperator = \App\Models\Backend\Permission\Admin::get();
      $Customer = DB::select(" select * from customers ");
      return View('backend.pm.form')->with(
        array(
           'sOperator'=>$sOperator,'Customer'=>$Customer,'sMainGroup'=>$sMainGroup
        ) );
    }
    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Pm::find($id);
       $sUser = \App\Models\Backend\Permission\Admin::where('id', $sRow->subject_recipient)->get();
       // dd($sUser[0]->name);
       $subject_recipient = $sUser[0]->name;
       $operator_name = \App\Models\Backend\Permission\Admin::where('id', $sRow->operator)->get();
       $operator_name = $operator_name[0]->name;
       $Customer = DB::select(" select * from customers ");
       $sMainGroup = DB::select(" select * from role_group where id<>1 ");
       return View('backend.pm.form')->with(array('sRow'=>$sRow, 'id'=>$id, 'subject_recipient_name'=>$subject_recipient,'operator_name'=>$operator_name,'Customer'=>$Customer,'sMainGroup'=>$sMainGroup));
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
            $sRow = \App\Models\Backend\Pm::find($id);
          }else{
            $sRow = new \App\Models\Backend\Pm;
          }

          $sRow->role_group_id_fk    = request('role_group_id_fk');
          $sRow->receipt_date    = request('receipt_date');
          $sRow->customers_id_fk    = request('customers_id_fk');
          $sRow->topics_question    = request('topics_question');
          $sRow->details_question    = request('details_question');
          $sRow->txt_answers    = request('txt_answers');
          $sRow->subject_recipient    = request('subject_recipient');
          $sRow->operator    = request('operator');
          $sRow->last_update    = request('last_update');

          $sRow->status_close_job    = request('status_close_job')?request('status_close_job'):0;
          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         // return redirect()->action('backend\PmController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
          return redirect()->to(url("backend/pm/".$sRow->id."/edit"));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\PmController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Pm::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Pm::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('recipient_name', function($row) {
        $sUser = \App\Models\Backend\Permission\Admin::where('id', $row->subject_recipient)->get();
        return $sUser[0]->name;
      })
      ->addColumn('operator_name', function($row) {
        $sUser = \App\Models\Backend\Permission\Admin::where('id', $row->operator)->get();
        return $sUser[0]->name;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
