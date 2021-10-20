<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Maintain_balanceController extends Controller
{

    public function index(Request $request)
    {
      return view('backend.maintain_balance.index');
    }

   public function create()
    {
      return View('backend.maintain_balance.form');
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Maintain_balance::find($id);
       return View('backend.maintain_balance.form')->with(array('sRow'=>$sRow, 'id'=>$id) );
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
            $sRow = \App\Models\Backend\Maintain_balance::find($id);
          }else{
            $sRow = new \App\Models\Backend\Maintain_balance;
          }
           
          $sRow->txt_desc    = request('txt_desc');
          $sRow->txt_value    = request('txt_value');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          return redirect()->action('backend\Maintain_balanceController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Maintain_balanceController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }
    public function show($id)
    {
      $sRow = \App\Models\Backend\Maintain_balance::find($id);
      if( $sRow ){
        $sRow->deleted_at = date('Y-m-d H:i:s');
        $sRow->save();
        // $sRow->forceDelete();
      }
       // return response()->json(\App\Models\Alert::Msg('success'));
       return back()->with(['success' => 'ทำการลบข้อมูลเรียบร้อย']);
    }
    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Maintain_balance::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Maintain_balance::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('Crm_topic', function($row) {
      //   $sCrm_topic = \App\Models\Backend\Maintain_balance_topic::where('id', $row->Crm_topic_id)->get();
      //   return $sCrm_topic[0]->txt_desc;
      // })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
