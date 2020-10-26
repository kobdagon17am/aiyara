<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class BusinesswebController extends Controller
{

    public function index(Request $request)
    {

      $dsPackage  = \App\Models\Backend\Businessweb::get();
      return view('backend.businessweb.index')->with(['dsPackage'=>$dsPackage]);

    }

 public function create()
    {
      $sLocale  = \App\Models\Locale::all();
      return view('backend.businessweb.form');
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Businessweb::find($id);
       
       $dsPackage = \App\Models\Backend\Package::find($sRow->package_id_fk);
       // dd($dsPackage->dt_package);
       // dd($id);
       $dsBusinessweb_banner = \App\Models\Backend\Businessweb_banner::where('businessweb_id_fk', $id)->get();
       // dd($dsBusinessweb_banner);

       return View('backend.businessweb.form')->with(array('sRow'=>$sRow, 'id'=>$id,'dsPackage'=>$dsPackage,'dsBusinessweb_banner'=>$dsBusinessweb_banner) );
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
            $sRow = \App\Models\Backend\Businessweb::find($id);
          }else{
            $sRow = new \App\Models\Backend\Businessweb;
          }

          $sRow->topic    = request('topic');
          $sRow->content    = request('content');

          $request = app('request');
          if ($request->hasFile('image')) {

              @UNLINK(@$sRow->img_url.@$sRow->image);

              $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
              ]);
              $image = $request->file('image');
             
              $name = time() . '.' . $image->getClientOriginalExtension();
              $image_path = 'businessweb/';
              $destinationPath = public_path($image_path);
           
              $image->move($destinationPath, $name);
              $sRow->image_path    = 'local/public/'.$image_path;
              $sRow->image = $name;
              
            }

                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          if( $id ){
            return redirect()->action('Backend\BusinesswebController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
          }else{
            return View('backend.businessweb.form')->with(array('sRow'=>$sRow, 'id'=>$id) ); 
          }
         

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('Backend\BusinesswebController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Businessweb::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Businessweb::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('package_name', function($row) {
      //   $dsPackage = \App\Models\Backend\Package::find($row->package_id_fk);
      //   return @$dsPackage->dt_package;
      // })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
