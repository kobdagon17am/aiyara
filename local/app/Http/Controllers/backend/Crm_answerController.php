<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Crm_answerController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.crm_answer.index');

    }

 public function create($id)
    {
      $sRowNew = \App\Models\Backend\Crm::find($id);
      return View('backend.crm_answer.form')->with(
        array(
           'sRowNew'=>$sRowNew,
        ) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function show($id)
    {

      $sRow = \App\Models\Backend\Crm_answer::find($id);
      // dd(@$sRow->id);
      return View('backend.crm_answer.index')->with(array('sRow'=>$sRow) );


    }

    public function edit($id)
    {

       $sRow = \App\Models\Backend\Crm_answer::find($id);
       $sRowNew = \App\Models\Backend\Crm::find($sRow->crm_id_fk);
       // dd($sRow->respondent);

       $sP = \App\Models\Backend\Permission\Admin::where('id',$sRow->respondent)->get();

       return View('backend.crm_answer.form')->with(array(
        'sRow'=>$sRow , 'id'=>$id,'sRowNew'=>$sRowNew,'respondent_name'=>$sP[0]->name
       ));

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
            $sRow = \App\Models\Backend\Crm_answer::find($id);
          }else{
            $sRow = new \App\Models\Backend\Crm_answer;
          }

          $sRow->crm_id_fk    = request('crm_id_fk');
          $sRow->txt_answer    = request('txt_answer');
          $sRow->date_answer    = request('date_answer');
          $sRow->respondent    = request('respondent');
          $sRow->level_class    = request('level_class');

          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          return redirect()->to(url("backend/crm/".request('crm_id_fk')."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Crm_answerController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Crm_answer::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Crm_answer::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('crm_name', function($row) {
          $sP = \App\Models\Backend\Crm::where('id',$row->crm_id_fk)->get();
          return $sP[0]->subject_receipt_number;
      })
      ->addColumn('respondent_name', function($row) {
          $sP = \App\Models\Backend\Permission\Admin::where('id',$row->respondent)->get();
          return $sP[0]->name;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
