<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Product_typeController extends Controller
{

    public function index(Request $request)
    {

      $dsProduct_type  = \App\Models\Backend\Product_type::get();
      return view('backend.product_type.index')->with(['dsProduct_type'=>$dsProduct_type]);

    }

 public function create()
    {
      $sLocale  = \App\Models\Locale::all();
      return view('backend.product_type.form');
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Product_type::find($id);
       return View('backend.product_type.form')->with(array('sRow'=>$sRow, 'id'=>$id) );
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
            $sRow = \App\Models\Backend\Product_type::find($id);
          }else{
            $sRow = new \App\Models\Backend\Product_type;
          }

          $sRow->product_type    = request('product_type');
          $sRow->detail    = request('detail');
          $sRow->date_added    = request('date_added');
          $sRow->lang_id    = request('lang_id');
          $sRow->status    = request('status')?request('status'):0;
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('Backend\Product_typeController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('Backend\Product_typeController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Product_type::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Product_type::search()->orderBy('id', 'asc');
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
