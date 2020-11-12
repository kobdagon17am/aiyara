<?php

namespace App\Http\Controllers\Backend;

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
      return View('backend.crm.form');
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Crm::find($id);
       return View('backend.crm.form')->with(array('sRow'=>$sRow, 'id'=>$id ) );
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
           
          $sRow->subject_receipt_number    = request('subject_receipt_number');
          $sRow->receipt_date    = request('receipt_date');
          $sRow->topics_reported    = request('topics_reported');
          $sRow->contact_details    = request('contact_details');
          $sRow->subject_recipient    = request('subject_recipient');
          $sRow->operator    = request('operator');
          $sRow->last_update    = request('last_update');

          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('backend\CrmController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

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
      // ->addColumn('Crm_topic', function($row) {
      //   $sCrm_topic = \App\Models\Backend\Crm_topic::where('id', $row->Crm_topic_id)->get();
      //   return $sCrm_topic[0]->txt_desc;
      // })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
