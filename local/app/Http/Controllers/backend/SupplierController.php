<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class SupplierController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.supplier.index');

    }

    public function create()
    {
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      return View('backend.supplier.form')->with(array('sBusiness_location'=>$sBusiness_location,) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Supplier::find($id);
       $sBusiness_location = \App\Models\Backend\Business_location::get();
       return View('backend.supplier.form')->with(array('sRow'=>$sRow, 'id'=>$id, 'sBusiness_location'=>$sBusiness_location,) );
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
            $sRow = \App\Models\Backend\Supplier::find($id);
          }else{
            $sRow = new \App\Models\Backend\Supplier;
          }

          $sRow->business_location_id_fk    = request('business_location_id_fk');
          $sRow->txt_desc    = request('txt_desc');
          $sRow->tel    = request('tel');
          $sRow->addr    = request('addr');
          $sRow->tax_number    = request('tax_number');
          $sRow->status    = request('status')?request('status'):0;
          $sRow->save();

          \DB::commit();

          return redirect()->to(url("backend/supplier/".($id?$id:$sRow->id)."/edit"));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\SupplierController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }
    public function show($id)
    {
      $sRow = \App\Models\Backend\Supplier::find($id);
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
      $sRow = \App\Models\Backend\Supplier::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Supplier::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
       ->addColumn('business_location', function($row) {
        if(@$row->business_location_id_fk!=''){
             $P = DB::select(" select * from dataset_business_location where id=".@$row->business_location_id_fk." ");
             return @$P[0]->txt_desc;
        }else{
             return '-';
        }
      })  
      ->make(true);
    }



}
