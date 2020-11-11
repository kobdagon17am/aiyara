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
      $sRowGroup = \App\Models\Backend\Crm::orderBy('group_id','desc')->limit(1)->get();
      $groupMaxID = $sRowGroup[0]->group_id+1;
      // dd($groupMaxID);
      $sLanguage = \App\Models\Backend\Language::get();
      return View('backend.crm.form')->with(array('sLanguage'=>$sLanguage,'groupMaxID'=>$groupMaxID) );

    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRowGroup = \App\Models\Backend\Crm::find($id);
       $sRow = \App\Models\Backend\Crm::where('group_id', $sRowGroup->group_id)->get();
       // dd($sRow[0]->status);
       $sLanguage = \App\Models\Backend\Language::get();
       return View('backend.crm.form')->with(array('sRow'=>$sRow, 'id'=>$id , 'sLanguage'=>$sLanguage ) );
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
            $langCnt = count(request('lang'));
            for ($i=0; $i < $langCnt ; $i++) { 

                \App\Models\Backend\Crm::where('id', request('id')[$i])->update(
                      [
                        
                        'subject_receipt_number' => request('subject_receipt_number')[$i] ,
                        'receipt_date' => request('receipt_date')[$i] ,
                        'topics_reported' => request('topics_reported')[$i] ,
                        'contact_details' => request('contact_details')[$i] ,
                        'subject_recipient' => request('subject_recipient')[$i] ,
                        'operator' => request('operator')[$i] ,
                        'last_update' => request('last_update') ,
                        'updated_at' => date('Y-m-d H:i:s') ,
                        'status' => request('status')?request('status'):0 ,
                      ]
                  );     
            }

          }else{

            $sRowGroup = \App\Models\Backend\Crm::orderBy('group_id','desc')->limit(1)->get();
            $groupMaxID = $sRowGroup[0]->group_id+1;

            $langCnt = count(request('lang'));
            for ($i=0; $i < $langCnt ; $i++) { 
            
                \App\Models\Backend\Crm::insert(
                      [
                        'lang_id' => request('lang')[$i] ,
                        'group_id' => $groupMaxID ,

                        'subject_receipt_number' => request('subject_receipt_number')[$i] ,
                        'receipt_date' => request('receipt_date')[$i] ,
                        'topics_reported' => request('topics_reported')[$i] ,
                        'contact_details' => request('contact_details')[$i] ,
                        'subject_recipient' => request('subject_recipient')[$i] ,
                        'operator' => request('operator')[$i] ,
                        'last_update' => request('last_update') ,
                        'created_at' => date('Y-m-d H:i:s') ,
                        'status' => 1,
                      ]
                  );     
            }

          }


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
