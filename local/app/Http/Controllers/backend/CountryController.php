<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class CountryController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.country.index');

    }

 public function create()
    {
      $sLang = \App\Models\Backend\Country::get();
      return View('backend.country.form')->with(array('sLang'=>$sLang) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function show($id)
    {

      $sRow = \App\Models\Backend\Country::find($id);
      // dd(@$sRow->id);
      return View('backend.country.index')->with(array('sRow'=>$sRow) );


    }

    public function edit($id)
    {
       // $sLang = \App\Models\Backend\Country::get();
       // $sRow = \App\Models\Backend\Country::find($id);
       // return View('backend.country.form')->with(array('sRow'=>$sRow , 'id'=>$id, 'sLang'=>$sLang ) );
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
            $sRow = \App\Models\Backend\Country::find($id);
          }else{
            $sRow = new \App\Models\Backend\Country;
          }

          $sRow->promotion_id_fk    = request('promotion_id_fk');
          $sRow->currency_id    = request('currency_id');
          $sRow->cost_price    = request('cost_price');
          $sRow->selling_price    = request('selling_price');
          $sRow->member_price    = request('member_price');
          $sRow->pv    = request('pv');
          // $sRow->lang_id    = request('lang_id');
          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('backend\CountryController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\CountryController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Country::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Country::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('business_location', function($row) {
      //     $sP = \App\Models\Backend\Business_location::find($row->business_location_id);
      //     return $sP->txt_desc;
      // })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
