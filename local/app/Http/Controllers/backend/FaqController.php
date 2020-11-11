<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class FaqController extends Controller
{

    public function index(Request $request)
    {
      return view('backend.faq.index');
    }

   public function create()
    {
      $sRowGroup = \App\Models\Backend\Faq::orderBy('group_id','desc')->limit(1)->get();
      $groupMaxID = $sRowGroup[0]->group_id+1;
      // dd($groupMaxID);
      $sLanguage = \App\Models\Backend\Language::get();
       $sFaq_topic = \App\Models\Backend\Faq_topic::where('lang_id', 1)->get();
      return View('backend.faq.form')->with(array('sLanguage'=>$sLanguage,'groupMaxID'=>$groupMaxID, 'sFaq_topic'=>$sFaq_topic ) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRowGroup = \App\Models\Backend\Faq::find($id);
       $sRow = \App\Models\Backend\Faq::where('group_id', $sRowGroup->group_id)->get();
       // dd($sRow[0]->status);
       $sLanguage = \App\Models\Backend\Language::get();
       $sFaq_topic = \App\Models\Backend\Faq_topic::where('lang_id', 1)->get();
       return View('backend.faq.form')->with(array('sRow'=>$sRow, 'id'=>$id , 'sLanguage'=>$sLanguage, 'sFaq_topic'=>$sFaq_topic ) );
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
            $sRow = \App\Models\Backend\Faq::find($id);
            $langCnt = count(request('lang'));
            for ($i=0; $i < $langCnt ; $i++) { 

                \App\Models\Backend\Faq::where('id', request('id')[$i])->update(
                      [
                        'faq_topic_id' => request('faq_topic_id') ,
                        'q_topic' => request('q_topic')[$i] ,
                        'q_details' => request('q_details')[$i] ,
                        'q_answer' => request('q_answer')[$i] ,
                        'q_date' => request('q_date') ,
                        'updated_at' => date('Y-m-d H:i:s') ,
                        'status' => request('status')?request('status'):0 ,
                      ]
                  );     
            }

          }else{

            $sRowGroup = \App\Models\Backend\Faq::orderBy('group_id','desc')->limit(1)->get();
          $groupMaxID = $sRowGroup[0]->group_id+1;

            $langCnt = count(request('lang'));
            for ($i=0; $i < $langCnt ; $i++) { 
            
                \App\Models\Backend\Faq::insert(
                      [
                        'lang_id' => request('lang')[$i] ,
                        'group_id' => $groupMaxID ,
                        'faq_topic_id' => request('faq_topic_id') ,
                        'q_topic' => request('q_topic')[$i] ,
                        'q_details' => request('q_details')[$i] ,
                        'q_answer' => request('q_answer')[$i] ,
                        'q_date' => request('q_date') ,
                        'created_at' => date('Y-m-d H:i:s') ,
                        'status' => 1,
                      ]
                  );     
            }

          }


          \DB::commit();

         return redirect()->action('backend\FaqController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\FaqController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Faq::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Faq::where('lang_id', 1)->search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('faq_topic', function($row) {
        $sFaq_topic = \App\Models\Backend\Faq_topic::where('id', $row->faq_topic_id)->get();
        return $sFaq_topic[0]->txt_desc;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
