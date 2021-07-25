<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Session;
use App\Models\UserModel;
use App\Models\MenuPermissionModel;


class FaqController extends MyAjaxController 
{

    public function index(Request $request)
    {

      return view('backend.faq.index');
      // return \Session::get('xxx');
      // return \Auth::user()->role_group_id_fk;

      // $menu_id = \Session::get('get_link');
      // $sPermission = \Auth::user()->permission;
      //  // dd($sPermission);
      // if($sPermission==1){
      //     return view('backend.faq.index')->with(array('sC'=>'','sU'=>'','sD'=>''));
      // }else{
      //     $role_group_id = \Auth::user()->role_group_id_fk;
      //     // dd($role_group_id);
      //     // dd($menu_id);
      //     $menu_permit = DB::table('role_permit')->where('role_group_id_fk',$role_group_id)->where('menu_id_fk',$menu_id)->first();
      //     $sC = @$menu_permit->c==1?'':'display:none;';
      //     $sU = @$menu_permit->u==1?'':'display:none;';
      //     $sD = @$menu_permit->d==1?'':'display:none;';
      //     return view('backend.faq.index')->with(array('sC'=>$sC,'sU'=>$sU,'sD'=>$sD));
      // }
    }

   public function create()
    {
      $sFaq_topic = \App\Models\Backend\Faq_topic::where('lang_id', 1)->get();
      return View('backend.faq.form')->with(array('sFaq_topic'=>$sFaq_topic));
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Faq::find($id);
       $sFaq_topic = \App\Models\Backend\Faq_topic::where('lang_id', 1)->get();
       return View('backend.faq.form')->with(array('sRow'=>$sRow, 'id'=>$id , 'sFaq_topic'=>$sFaq_topic ) );
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
          }else{
            $sRow = new \App\Models\Backend\Faq;
          }

          $sRow->faq_topic_id    = request('faq_topic_id');
          $sRow->q_question    = request('q_question');
          $sRow->q_answer    = request('q_answer');

          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


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
