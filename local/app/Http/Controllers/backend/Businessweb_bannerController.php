<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use Redirect;

class Businessweb_bannerController extends Controller
{

    public function index(Request $request)
    {
      // dd($request->id);
      $dsBusinessweb = \App\Models\Backend\Businessweb::find($request->id);
      return view('backend.businessweb_banner.index')->with(['dsBusinessweb'=>$dsBusinessweb]);

    }

    public function create()
    {
      $request = app('request');
      // dd($request->Businessweb_id);
      $dsBusinessweb = \App\Models\Backend\Businessweb::find($request->Businessweb_id);
      // $sLocale  = \App\Models\Locale::all();
      return view('backend.businessweb_banner.form')->with(array('dsBusinessweb'=>$dsBusinessweb) );
    }

    public function store(Request $request)
    {
      // dd($request);
      return $this->form();
    }

    public function show(Request $request)
    {
      // dd($request);
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Businessweb_banner::find($id);
       return View('backend.businessweb_banner.form')->with(array('sRow'=>$sRow, 'id'=>$id) );
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
            $sRow = \App\Models\Backend\Businessweb_banner::find($id);
          }else{
            $sRow = new \App\Models\Backend\Businessweb_banner;
          }
          
          $sRow->businessweb_id_fk    = request('businessweb_id_fk');

          $request = app('request');
          if ($request->hasFile('image')) {

              @UNLINK(@$sRow->img_url.@$sRow->image);

              $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
              ]);
              $image = $request->file('image');
             
              $name = 'b'.time() . '.' . $image->getClientOriginalExtension();
              $image_path = 'businessweb/';
              $destinationPath = public_path($image_path);
           
              $image->move($destinationPath, $name);
              $sRow->image_path    = 'local/public/'.$image_path;
              $sRow->image = $name;
              
            }


          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

          return redirect()->to(url("backend/businessweb_banner/index/".request('businessweb_id_fk').""));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Businessweb_bannerController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Businessweb_banner::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){

      $sTable = \App\Models\Backend\Businessweb_banner::search()->orderBy('id', 'asc');
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
