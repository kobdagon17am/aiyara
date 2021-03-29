<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Products_fifo_billController extends Controller
{

    public function index(Request $request)
    {
      // return view('backend.consignments_import.index');
    }

 public function create()
    {

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
      // return $this->form($id);
    }

   public function form($id=NULL)
    {
      // \DB::beginTransaction();
      // try {
      //     if( $id ){
      //       $sRow = \App\Models\Backend\Consignments::find($id);
      //     }else{
      //       $sRow = new \App\Models\Backend\Consignments;
      //     }

      //     // $sRow->operator    = request('operator');
      //     // $sRow->last_update    = request('last_update');
      //     $sRow->created_at = date('Y-m-d H:i:s');
      //     $sRow->save();


      //     \DB::commit();

      //    // return redirect()->action('backend\Products_fifo_billController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
      //     return redirect()->to(url("backend/pick_warehouse/".$sRow->id."/edit"));

      // } catch (\Exception $e) {
      //   echo $e->getMessage();
      //   \DB::rollback();
      //   return redirect()->action('backend\Products_fifo_billController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      // }
    }

    public function destroy($id)
    {
      // $sRow = \App\Models\Backend\Consignments::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Products_fifo_bill::search();
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->make(true);
    }



}
