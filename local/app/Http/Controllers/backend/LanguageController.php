<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class LanguageController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.language.index');

    }

 public function create()
    {
      return View('backend.language.form');
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function show($id)
    {
       $sRow = \App\Models\Backend\Language::find($id);
       return View('backend.language.form')->with(array('sRow'=>$sRow , 'id'=>$id ) );
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Language::find($id);
       return View('backend.language.form')->with(array('sRow'=>$sRow , 'id'=>$id ) );
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
            $sRow = \App\Models\Backend\Language::find($id);
          }else{
            $sRow = new \App\Models\Backend\Language;
          }

          $sRow->txt_desc    = request('txt_desc');

          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('backend\LanguageController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\LanguageController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Language::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Language::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('lang', function($row) {
          // $sLang = \App\Models\Backend\Language::find($row->lang_id);
          // return $sLang->txt_desc;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
