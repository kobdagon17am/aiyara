<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Course_historyController extends Controller
{

    public function index(Request $request)
    {

      // $dsCourse_history  = \App\Models\Backend\Course_history::get();
      // $dsCourse_event = \App\Models\Backend\Course_event::find($dsCourse_history->course_event_id_fk);
      // return view('backend.course_history.index')->with(['dsCourse_history'=>$dsCourse_history,'dsCourse_event'=>$dsCourse_event]);
      return view('backend.course_history.index');

    }

 public function create()
    {
      //  $dsCe_type = \App\Models\Backend\Ce_type::get();
      //  $dsCe_features_booker = \App\Models\Backend\Ce_features_booker::get();
      //  $dsCe_can_reserve = \App\Models\Backend\Ce_can_reserve::get();
      //  $dsCe_limit = \App\Models\Backend\Ce_limit::get();
      //  return View('backend.course_history.form')->with(array(
      //   'dsCe_type'=>$dsCe_type,
      //   'dsCe_features_booker'=>$dsCe_features_booker,
      //   'dsCe_can_reserve'=>$dsCe_can_reserve,
      //   'dsCe_limit'=>$dsCe_limit
      // ) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
      //  $sRow = \App\Models\Backend\Course_event::find($id);
      //  $dsCe_type = \App\Models\Backend\Ce_type::get();
      //  $dsCe_features_booker = \App\Models\Backend\Ce_features_booker::get();
      //  $dsCe_can_reserve = \App\Models\Backend\Ce_can_reserve::get();
      //  $dsCe_limit = \App\Models\Backend\Ce_limit::get();
      //  return View('backend.course_history.form')->with(array(
      //   'sRow'=>$sRow, 'id'=>$id,
      //   'dsCe_type'=>$dsCe_type,
      //   'dsCe_features_booker'=>$dsCe_features_booker,
      //   'dsCe_can_reserve'=>$dsCe_can_reserve,
      //   'dsCe_limit'=>$dsCe_limit
      // ) );
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

         return redirect()->action('backend\Course_historyController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Course_historyController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
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
      $sTable = \App\Models\Backend\Course_history::search()->orderBy('id', 'asc');
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


    public function Course_history_list(){
      $sTable = \App\Models\Backend\Course_history::search()->orderBy('id', 'asc');
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
