<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Banner_frontController extends Controller
{

    public function index(Request $request)
    {

      $dsBanner_front  = \App\Models\Backend\Banner_front::get();
      return view('backend.banner_front.index')->with(['dsBanner_front'=>$dsBanner_front]);

    }

 public function create()
    {
      $sLocale  = \App\Models\Locale::all();
      return view('backend.banner_front.form');
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Banner_front::find($id);
       return View('backend.banner_front.form')->with(array('sRow'=>$sRow, 'id'=>$id) );
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
            $sRow = \App\Models\Backend\Banner_front::find($id);
          }else{
            $sRow = new \App\Models\Backend\Banner_front;
          }

          $request = app('request');
          if ($request->hasFile('image')) {

              @UNLINK(@$sRow->img_url.@$sRow->image);

              $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
              ]);
              $image = $request->file('image');
             
              $name = time() . '.' . $image->getClientOriginalExtension();
              $image_path = 'banner_front/';
              $destinationPath = public_path($image_path);
           
              $image->move($destinationPath, $name);
              $sRow->img_url    = 'local/public/'.$image_path;
              $sRow->image = $name;
              
            }

          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('backend\Banner_frontController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Banner_frontController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Banner_front::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Banner_front::search()->orderBy('id', 'asc');
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
