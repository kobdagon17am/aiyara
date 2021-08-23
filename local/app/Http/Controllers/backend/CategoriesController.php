<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class CategoriesController extends Controller
{

    public function index(Request $request)
    {
      return view('backend.categories.index');
    }

   public function create()
    {
      $sRowCat = \App\Models\Backend\Categories::orderBy('category_id','desc')->limit(1)->get();
      $catMax = $sRowCat[0]->category_id+1;
      // dd($catMax);
      $sLanguage = \App\Models\Backend\language::get();
      return View('backend.categories.form')->with(array('sLanguage'=>$sLanguage,'catMax'=>$catMax ) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRowCat = \App\Models\Backend\Categories::find($id);
       $sRow = \App\Models\Backend\Categories::where('category_id', $sRowCat->category_id)->get();
       // dd($sRow[0]->status);
       $sLanguage = \App\Models\Backend\language::get();
       return View('backend.categories.form')->with(array('sRow'=>$sRow, 'id'=>$id , 'sLanguage'=>$sLanguage ) );
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
            $sRow = \App\Models\Backend\Categories::find($id);
            $langCnt = count(request('lang'));
            for ($i=0; $i < $langCnt ; $i++) { 
             
                \App\Models\Backend\Categories::where('id', request('id')[$i])->update(
                      [
                        'category_name' => request('category_name')[$i] ,
                        'order' => request('order')[$i] ,
                        'updated_at' => date('Y-m-d H:i:s') ,
                        'status' => request('status')?request('status'):0 ,
                      ]
                  );     
            }

          }else{

            $sRowCat = \App\Models\Backend\Categories::orderBy('category_id','desc')->limit(1)->get();
            $catMax = $sRowCat[0]->category_id+1;

            $langCnt = count(request('lang'));
            for ($i=0; $i < $langCnt ; $i++) { 
            
                \App\Models\Backend\Categories::insert(
                      [
                        'lang_id' => request('lang')[$i] ,
                        'order' => request('lang')[$i] ,
                        'category_id' => $catMax ,
                        'category_name' => request('category_name')[$i] ,
                        'created_at' => date('Y-m-d H:i:s') ,
                        'status' => 1,
                      ]
                  );     
            }

          }


          \DB::commit();

           return redirect()->action('backend\CategoriesController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\CategoriesController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Categories::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Categories::where('lang_id', 1)->search()->orderBy('id', 'asc');
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
