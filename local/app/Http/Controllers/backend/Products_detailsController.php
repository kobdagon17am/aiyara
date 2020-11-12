<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Products_detailsController extends Controller
{

    public function index(Request $request)
    {
      return view('backend.products_details.index');
    }

   public function create($id)
    {

      $sRowNew = \App\Models\Backend\Products::find($id);
      $sRowGroup = \App\Models\Backend\Products_details::orderBy('group_id','desc')->limit(1)->get();
      $groupMaxID = $sRowGroup[0]->group_id+1;
      // dd($groupMaxID);
      $sLanguage = \App\Models\Backend\Language::get();
      return View('backend.products_details.form')->with(array('sRowNew'=>$sRowNew,'sLanguage'=>$sLanguage,'groupMaxID'=>$groupMaxID ) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRowGroup = \App\Models\Backend\Products_details::find($id);
       $sRow = \App\Models\Backend\Products_details::where('group_id', $sRowGroup->group_id)->get();
       // dd($sRow);
       $sRowNew = \App\Models\Backend\Products::find($sRow[0]->product_id_fk);
       // dd($sRow[0]->status);
       $sLanguage = \App\Models\Backend\Language::get();
       return View('backend.products_details.form')->with(array('sRowNew'=>$sRowNew,'sRow'=>$sRow, 'id'=>$id , 'sLanguage'=>$sLanguage ) );
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
            $sRow = \App\Models\Backend\Products_details::find($id);
            $langCnt = count(request('lang'));
            for ($i=0; $i < $langCnt ; $i++) { 

                \App\Models\Backend\Products_details::where('id', request('id')[$i])->update(
                      [
                        'product_name' => request('product_name')[$i] ,
                        'title' => request('title')[$i] ,
                        'descriptions' => request('descriptions')[$i] ,
                        'products_details' => request('products_details')[$i] ,
                        'updated_at' => date('Y-m-d H:i:s') ,
                      ]
                  );     
            }

          }else{

            $sRowGroup = \App\Models\Backend\Products_details::orderBy('group_id','desc')->limit(1)->get();
            $groupMaxID = $sRowGroup[0]->group_id+1;

            $langCnt = count(request('lang'));
            for ($i=0; $i < $langCnt ; $i++) { 

                \App\Models\Backend\Products_details::insert(
                      [
                        'lang_id' => request('lang')[$i] ,
                        'group_id' => $groupMaxID ,
                        'product_id_fk' => request('product_id_fk') ,

                        'product_name' => request('product_name')[$i] ,
                        'title' => request('title')[$i] ,
                        'descriptions' => request('descriptions')[$i] ,
                        'products_details' => request('products_details')[$i] ,
                        
                        'created_at' => date('Y-m-d H:i:s') ,
                      ]
                  );     
            }

          }


          \DB::commit();

          return redirect()->to(url("backend/products/".request('product_id_fk')."/edit"));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Products_detailsController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Products_details::find($id);
      $sTable = \App\Models\Backend\Products_details::where('group_id', $sRow->group_id);
      if( $sTable ){
        $sTable->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Products_details::where('lang_id', 1)->search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('descriptions_txt', function($row) {
        return strip_tags($row->descriptions);
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
