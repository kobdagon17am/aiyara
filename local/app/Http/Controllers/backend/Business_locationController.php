<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Business_locationController extends Controller
{

    public function index(Request $request)
    {

      $dsBusiness_location  = \App\Models\Backend\Business_location::get();
      return view('backend.business_location.index')->with(['dsBusiness_location'=>$dsBusiness_location]);

    }

 public function create()
    {
      $sCountry = \App\Models\Backend\Country::get();
      $sLocale  = \App\Models\Locale::all();
      $sLang = \App\Models\Backend\Language::get();
      return View('backend.business_location.form')->with(array('sLang'=>$sLang, 'sCountry'=>$sCountry) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sCountry = \App\Models\Backend\Country::get();
       $sLang = \App\Models\Backend\Language::get();
       $sRow = \App\Models\Backend\Business_location::find($id);
       return View('backend.business_location.form')->with(array('sRow'=>$sRow , 'id'=>$id, 'sLang'=>$sLang, 'sCountry'=>$sCountry) );
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
            $sRow = \App\Models\Backend\Business_location::find($id);
          }else{
            $sRow = new \App\Models\Backend\Business_location;
          }

          $sRow->txt_desc    = request('txt_desc');

          $sRow->country_id_fk    = request('country_id_fk');
          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('backend\Business_locationController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Business_locationController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Business_location::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Business_location::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('country', function($row) {
          $C = \App\Models\Backend\Country::find($row->country_id_fk);
          return $C->txt_desc;
      })      
      // ->addColumn('lang', function($row) {
      //     $sLang = \App\Models\Backend\Language::find($row->lang_id);
      //     return $sLang->txt_desc;
      // })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
