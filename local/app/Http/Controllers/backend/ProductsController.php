<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class ProductsController extends Controller
{

    public function index(Request $request)
    {

      $dsProducts  = \App\Models\Backend\Products::get();
      return view('backend.products.index')->with(['dsProducts'=>$dsProducts]);

    }

 public function create()
    {
      $sLocale  = \App\Models\Locale::all();
      $dsProductType  = \App\Models\Backend\Product_type::get();
      return view('backend.products.form')->with(['dsProductType'=>$dsProductType]);
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Products::find($id);
       $dsProductType  = \App\Models\Backend\Product_type::get();
       return view('backend.products.form')->with(['sRow'=>$sRow, 'id'=>$id ,'dsProductType'=>$dsProductType]);
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
            $sRow = \App\Models\Backend\Products::find($id);
          }else{
            $sRow = new \App\Models\Backend\Products;
          }

          $request = app('request');
          if ($request->hasFile('image01')) {
              @UNLINK(@$sRow->img_url.@$sRow->image01);
              $this->validate($request, [
                'image01' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
              ]);
              $image = $request->file('image01');
              $name = 'p1'.time() . '.' . $image->getClientOriginalExtension();
              $image_path = 'products/';
              $destinationPath = public_path($image_path);
              $image->move($destinationPath, $name);
              $sRow->img_url    = 'local/public/'.$image_path;
              $sRow->image01 = $name;
            }

          if ($request->hasFile('image02')) {
              @UNLINK(@$sRow->img_url.@$sRow->image02);
              $this->validate($request, [
                'image02' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
              ]);
              $image = $request->file('image02');
              $name = 'p2'.time() . '.' . $image->getClientOriginalExtension();
              $image_path = 'products/';
              $destinationPath = public_path($image_path);
              $image->move($destinationPath, $name);
              $sRow->img_url    = 'local/public/'.$image_path;
              $sRow->image02 = $name;
            }

          if ($request->hasFile('image03')) {
              @UNLINK(@$sRow->img_url.@$sRow->image03);
              $this->validate($request, [
                'image03' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
              ]);
              $image = $request->file('image03');
              $name = 'p3'.time() . '.' . $image->getClientOriginalExtension();
              $image_path = 'products/';
              $destinationPath = public_path($image_path);
              $image->move($destinationPath, $name);
              $sRow->img_url    = 'local/public/'.$image_path;
              $sRow->image03 = $name;
            }

          $sRow->product_code    = request('product_code');
          $sRow->product_name    = request('product_name');
          $sRow->pv    = request('pv');
          $sRow->price    = request('price');
          $sRow->category_id    = request('category_id');

          $sRow->image_default    = request('image_default');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();


          \DB::commit();

         return redirect()->action('backend\ProductsController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\ProductsController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Products::find($id);

      @UNLINK(@$sRow->img_url.@$sRow->image01);
      @UNLINK(@$sRow->img_url.@$sRow->image02);
      @UNLINK(@$sRow->img_url.@$sRow->image03);

      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Products::search()->orderBy('id', 'asc');
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
