<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Products_unitsController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.products_units.index');

    }

 public function create($id)
    {

      $sRowNew = \App\Models\Backend\Products::find($id);
      $sProducts_details = \App\Models\Backend\Products_details::where('product_id_fk',$id)->where('lang_id',1)->get();
      $sProductName = @$sProducts_details[0]->product_name?@$sProducts_details[0]->product_name:'-ยังไม่กำหนดชื่อสินค้าในเมนูสินค้า-';

      $sProduct_unit = \App\Models\Backend\Product_unit::get();

      return View('backend.products_units.form')->with(
        array(
          'sRowNew'=>$sRowNew,'sProductName'=>$sProductName,'sProduct_unit'=>$sProduct_unit
        ) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function show($id)
    {

      $sRow = \App\Models\Backend\Products_units::find($id);
      // dd(@$sRow->id);
      return View('backend.products_units.index')->with(array('sRow'=>$sRow) );


    }

    public function edit($id)
    {

       $sRow = \App\Models\Backend\Products_units::find($id);

       $sProducts_details = \App\Models\Backend\Products_details::where('product_id_fk',$sRow->product_id_fk)->where('lang_id',1)->get();
       $sProductName = @$sProducts_details[0]->product_name?@$sProducts_details[0]->product_name:'-ยังไม่กำหนดชื่อสินค้าในเมนูสินค้า-';

       $sRowNew = \App\Models\Backend\Products::find($sRow->product_id_fk);

       // $sProduct_unit = \App\Models\Backend\Product_unit::get();
       $sProduct_unit = DB::select(" select * from `dataset_product_unit` where id in(2,6)");

       return View('backend.products_units.form')->with(array(
        'sRow'=>$sRow , 'id'=>$id,'sRowNew'=>$sRowNew,'sProductName'=>$sProductName,'sProduct_unit'=>$sProduct_unit
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
            $sRow = \App\Models\Backend\Products_units::find($id);
          }else{
            $sRow = new \App\Models\Backend\Products_units;
          }

          $sRow->product_id_fk    = request('product_id_fk');
          // $sRow->product_unit_id_fk    = request('product_unit_id_fk');
          $sRow->product_unit_txt    = request('product_unit_txt');
          $sRow->converted_value    = request('converted_value');

          $sRow->status    = request('status')?request('status'):0;
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          return redirect()->to(url("backend/products/".request('product_id_fk')."/edit"));


      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Products_unitsController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Products_units::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Products_units::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_unit_name', function($row) {
          $sP = \App\Models\Backend\Product_unit::where('id',$row->product_unit_id_fk)->where('lang_id',1)->get();
          return @$sP[0]->product_unit.@$row->product_unit_txt;
          // $p1 =  @$sP[0]->product_unit;
          // if($p1==0){
          //   return @$row->product_unit_txt;
          // }else{
          //   return $p1;
          // }
      })
      // ->addColumn('updated_at', function($row) {
      //   return is_null($row->updated_at) ? '-' : $row->updated_at;
      // })
      ->make(true);
    }



}
