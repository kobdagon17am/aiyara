<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class QualificationController extends Controller
{

    public function index(Request $request)
    {

      $dsQualification  = \App\Models\Backend\Qualification::get();
      return view('backend.qualification.index')->with(['dsQualification'=>$dsQualification]);

    }

 public function create()
    {
      $sLocale  = \App\Models\Locale::all();
      return view('backend.qualification.form');
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Qualification::find($id);
       return View('backend.qualification.form')->with(array('sRow'=>$sRow, 'id'=>$id) );
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
            $sRow = \App\Models\Backend\Qualification::find($id);
          }else{
            $sRow = new \App\Models\Backend\Qualification;
          }

          $sRow->business_qualifications    = request('business_qualifications');
          $sRow->pv_lt    = request('pv_lt');
          $sRow->pv_mt    = request('pv_mt');
          $sRow->basic_active_1    = request('basic_active_1');
          $sRow->basic_active_2    = request('basic_active_2');
          $sRow->ps_1    = request('ps_1');
          $sRow->ps_2    = request('ps_2');
          $sRow->amt_month    = request('amt_month');
          $sRow->bds_1    = request('bds_1');
          $sRow->bds_2    = request('bds_2');

          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('backend\QualificationController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\QualificationController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Qualification::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Qualification::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('name', function($row) {
        // return $row->fname.' '.$row->surname;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
