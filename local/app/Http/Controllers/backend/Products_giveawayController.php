<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Products_giveawayController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.products_giveaway.index');

    }

 public function create()
    {
      $sLang = \App\Models\Backend\Language::get();
      $sPurchase_type = DB::select(" select * from dataset_purchase_type where status=1 ");
      $sGiveaway_type = DB::select(" select * from dataset_giveaway_type where status=1 ");
      $sGiveaway_time = DB::select(" select * from dataset_giveaway_time where status=1 ");
      $sGiveaway_obtion = DB::select(" select * from dataset_giveaway_obtion where status=1 ");

      return View('backend.products_giveaway.form')->with(array(
        'sLang'=>$sLang,
        'sPurchase_type'=>$sPurchase_type,
        'sGiveaway_type'=>$sGiveaway_type,
        'sGiveaway_time'=>$sGiveaway_time,
        'sGiveaway_obtion'=>$sGiveaway_obtion,
      ) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sLang = \App\Models\Backend\Language::get();
       $sRow = \App\Models\Backend\Products_giveaway::find($id);
       return View('backend.products_giveaway.form')->with(array('sRow'=>$sRow , 'id'=>$id, 'sLang'=>$sLang ) );
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
            $sRow = \App\Models\Backend\Products_giveaway::find($id);
          }else{
            $sRow = new \App\Models\Backend\Products_giveaway;
          }

          $sRow->txt_desc    = request('txt_desc');
          $sRow->lang_id    = request('lang_id');
          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('backend\Products_giveawayController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Products_giveawayController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Products_giveaway::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Products_giveaway::search()->orderBy('id', 'asc');
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
