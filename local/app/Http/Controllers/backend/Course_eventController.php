<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Course_eventController extends Controller
{

    public function index(Request $request)
    {

      $dsCourse_event  = \App\Models\Backend\Course_event::get();
      return view('backend.course_event.index')->with(['dsCourse_event'=>$dsCourse_event]);

    }

 public function create()
    {
        $sPackage = \App\Models\Backend\Package::get();
        $sQualification = \App\Models\Backend\Qualification::get();
        $sPersonal_quality = \App\Models\Backend\Personal_quality::get();
        $sTravel_feature = \App\Models\Backend\Travel_feature::get();
        $sAistockist = \App\Models\Backend\Aistockist::get();
        $sAgency = \App\Models\Backend\Agency::get();
        $dsCe_type = \App\Models\Backend\Ce_type::get();
        $dsCe_features_booker = \App\Models\Backend\Ce_features_booker::get();
        $dsCe_can_reserve = \App\Models\Backend\Ce_can_reserve::get();
        $dsCe_limit = \App\Models\Backend\Ce_limit::get();
       return View('backend.course_event.form')->with(array(
        'sPersonal_quality'=>$sPersonal_quality,
        'sTravel_feature'=>$sTravel_feature,
        'sAistockist'=>$sAistockist,
        'sAgency'=>$sAgency,
        'sQualification'=>$sQualification,
        'sPackage'=>$sPackage,
        'dsCe_type'=>$dsCe_type,
        'dsCe_features_booker'=>$dsCe_features_booker,
        'dsCe_can_reserve'=>$dsCe_can_reserve,
        'dsCe_limit'=>$dsCe_limit
      ) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {

        $sPackage = \App\Models\Backend\Package::get();
        $sQualification = \App\Models\Backend\Qualification::get();
        $sPersonal_quality = \App\Models\Backend\Personal_quality::get();
        $sTravel_feature = \App\Models\Backend\Travel_feature::get();
        $sAistockist = \App\Models\Backend\Aistockist::get();
        $sAgency = \App\Models\Backend\Agency::get();
        $sRow = \App\Models\Backend\Course_event::find($id);
        $dsCe_type = \App\Models\Backend\Ce_type::get();
        $dsCe_features_booker = \App\Models\Backend\Ce_features_booker::get();
        $dsCe_can_reserve = \App\Models\Backend\Ce_can_reserve::get();
        $dsCe_limit = \App\Models\Backend\Ce_limit::get();
       return View('backend.course_event.form')->with(array(
        'sPersonal_quality'=>$sPersonal_quality,
        'sTravel_feature'=>$sTravel_feature,
        'sAistockist'=>$sAistockist,
        'sAgency'=>$sAgency,
        'sQualification'=>$sQualification,
        'sPackage'=>$sPackage,
        'sRow'=>$sRow, 'id'=>$id,
        'dsCe_type'=>$dsCe_type,
        'dsCe_features_booker'=>$dsCe_features_booker,
        'dsCe_can_reserve'=>$dsCe_can_reserve,
        'dsCe_limit'=>$dsCe_limit
      ) );
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

          $sRow->ce_type    = request('ce_type');
          $sRow->ce_name    = request('ce_name');
          $sRow->ce_place    = request('ce_place');
          $sRow->ce_max_ticket    = request('ce_max_ticket');
          $sRow->ce_ticket_price    = request('ce_ticket_price');
          $sRow->ce_sdate    = request('ce_sdate');
          $sRow->ce_edate    = request('ce_edate');
          $sRow->ce_features_booker    = request('ce_features_booker');
          $sRow->ce_can_reserve    = request('ce_can_reserve');
          $sRow->ce_limit    = request('ce_limit');
          
          $sRow->minimum_package_purchased    = request('minimum_package_purchased');
          $sRow->reward_qualify_purchased    = request('reward_qualify_purchased');
          $sRow->keep_personal_quality    = request('keep_personal_quality');
          $sRow->maintain_travel_feature    = request('maintain_travel_feature');
          $sRow->aistockist    = request('aistockist');
          $sRow->agency    = request('agency');

          $sRow->lang_id    = request('lang_id');
          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

         return redirect()->action('backend\Course_eventController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Course_eventController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Course_event::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Course_event::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('ce_type_desc', function($row) {
        $ce_type = \App\Models\Backend\Ce_type::find($row->ce_type);
        return @$ce_type->txt_desc;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
