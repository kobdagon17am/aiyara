<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Course_history_listController extends Controller
{

    public function index(Request $request)
    {
        
      // $dsCourse_history_list  = \App\Models\Backend\Course_history_list::get();
      // $dsCourse_event = \App\Models\Backend\Course_event::find($dsCourse_history_list->course_event_id_fk);
      // return view('backend.course_history_list.index')->with(['dsCourse_history_list'=>$dsCourse_history_list,'dsCourse_event'=>$dsCourse_event]);
      return view('backend.course_history_list.index');

    }

 public function show()
    {
      return view('backend.course_history_list.index');
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {

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
            $sRow = \App\Models\Backend\Course_event::find($id);
          }else{
            $sRow = new \App\Models\Backend\Course_event;
          }

          \DB::commit();

         return redirect()->action('backend\Course_history_listController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Course_history_listController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // $sRow = \App\Models\Backend\Course_event::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Course_history_list::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('course_event_desc', function($row) {
        $dsCourse_event = \App\Models\Backend\Course_event::find($row->course_event_id_fk);
        return @$dsCourse_event->ce_name;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
