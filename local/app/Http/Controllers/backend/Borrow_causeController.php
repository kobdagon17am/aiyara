<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Borrow_causeController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.borrow_cause.index');

    }

 public function create()
    {
      $sLang = \App\Models\Backend\Language::get();
      return View('backend.borrow_cause.form')->with(array('sLang'=>$sLang) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    // public function show($id)
    // {
    //    $sLang = \App\Models\Backend\Language::get();
    //    $sRow = \App\Models\Backend\Borrow_cause::find($id);
    //    return View('backend.borrow_cause.form')->with(array('sRow'=>$sRow , 'id'=>$id, 'sLang'=>$sLang ) );
    // }

    public function edit($id)
    {
       $sLang = \App\Models\Backend\Language::get();
       $sRow = \App\Models\Backend\Borrow_cause::find($id);
       return View('backend.borrow_cause.form')->with(array('sRow'=>$sRow , 'id'=>$id, 'sLang'=>$sLang ) );
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
            $sRow = \App\Models\Backend\Borrow_cause::find($id);
          }else{
            $sRow = new \App\Models\Backend\Borrow_cause;
          }

          $sRow->txt_desc    = request('txt_desc');
          // $sRow->lang_id    = request('lang_id');
          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('backend\Borrow_causeController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Borrow_causeController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }
    public function show($id)
    {
      $sRow = \App\Models\Backend\Borrow_cause::find($id);
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
      $sRow = \App\Models\Backend\Borrow_cause::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Borrow_cause::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('lang', function($row) {
          $sLang = \App\Models\Backend\Language::find($row->lang_id);
          return $sLang->txt_desc;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
