<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Pm_broadcastController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.pm_broadcast.index');
      
    }

 public function create()
    {
      $sMainGroup = DB::select(" select * from role_group where id<>1 ");
      $sOperator = \App\Models\Backend\Permission\Admin::get();
      $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.pm_broadcast.form')->with(
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
       $sRow = \App\Models\Backend\Pm_broadcast::find($id);
       $sUser = \App\Models\Backend\Permission\Admin::where('id', $sRow->subject_recipient)->get();
       // dd($sUser[0]->name);
       $subject_recipient = $sUser[0]->name;
       $operator_name = \App\Models\Backend\Permission\Admin::where('id', $sRow->operator)->get();
       $operator_name = $operator_name[0]->name;
       $Customer = DB::select(" select * from customers limit 100 ");
       $sMainGroup = DB::select(" select * from role_group where id<>1 ");
       return View('backend.pm_broadcast.form')->with(array('sRow'=>$sRow, 'id'=>$id, 'subject_recipient_name'=>$subject_recipient,'operator_name'=>$operator_name,'Customer'=>$Customer,'sMainGroup'=>$sMainGroup));
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
            $sRow = \App\Models\Backend\Pm_broadcast::find($id);
          }else{
            $sRow = new \App\Models\Backend\Pm_broadcast;
          }

          // $sRow->operator    = request('operator');
          // $sRow->last_update    = request('last_update');
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         // return redirect()->action('backend\Pm_broadcastController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
          return redirect()->to(url("backend/pm_broadcast/".$sRow->id."/edit"));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Pm_broadcastController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Pm_broadcast::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Pm_broadcast::search()->orderBy('id', 'desc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
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
