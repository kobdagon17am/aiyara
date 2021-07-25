<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Promotions_imagesController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.promotions_images.form');

    }

   public function create($id)
    {

      $sRowNew = \App\Models\Backend\Promotions::find($id);
      return View('backend.promotions_images.form')->with(array('sRowNew'=>$sRowNew) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
      // dd($id);
       $sRow = \App\Models\Backend\Promotions_images::find($id);
       $sRowNew = \App\Models\Backend\Promotions::find($sRow->promotion_id_fk);
       return view('backend.promotions_images.form')->with(['sRow'=>$sRow, 'id'=>$id ,'sRowNew'=>$sRowNew ]);
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
            $sRow = \App\Models\Backend\Promotions_images::find($id);
          }else{
            $sRow = new \App\Models\Backend\Promotions_images;
          }

          $request = app('request');
          if ($request->hasFile('image01')) {
              @UNLINK(@$sRow->img_url.@$sRow->image01);
              $this->validate($request, [
                'image01' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
              ]);
              $image = $request->file('image01');
              $name = 'p1'.time() . '.' . $image->getClientOriginalExtension();
              $image_path = 'promotions/';
              $destinationPath = public_path($image_path);
              $image->move($destinationPath, $name);
              $sRow->img_url    = 'local/public/'.$image_path;
              $sRow->promotion_img = $name;
            }

            if(!empty(request('image_default'))){
              
              if(request('image_default')==1){
                DB::update(" 
                    UPDATE promotions_images SET image_default = 0  where promotion_id_fk=".request('promotion_id_fk')." ;
                ");
              }

              $sRow->image_default    = request('image_default');
              
            }

                    
          $sRow->promotion_id_fk = request('promotion_id_fk');
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

          return redirect()->to(url("backend/promotions/".request('promotion_id_fk')."/edit"));

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Promotions_imagesController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Promotions_images::find($id);
      @UNLINK(@$sRow->img_url.@$sRow->promotion_img);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Promotions_images::search()->orderBy('image_default', 'desc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('img_path', function($row) {
        return $row->img_url.$row->promotion_img ;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
