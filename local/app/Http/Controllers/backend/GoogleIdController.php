<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class GoogleIdController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(Request $request)
    {

      $path = str_replace('backend/googleId/','',$request->path());
      if($path == 'backend/googleId'){
        $path = 'index';
      }
      if(view()->exists('backend.googleId.'.$path)){
          return view('backend.googleId.'.$path);
      }
      return view('backend.template.pages-404');
    }

    public function create()
    {
      return view('backend.googleId.form');
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\GoogleId\GoogleId::find($id);
       return View('backend.googleId.form')->with(array('sRow'=>$sRow, 'id'=>$id) );
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
            $sRow = \App\Models\Backend\GoogleId\GoogleId::find($id);
          }else{
            $sRow = new \App\Models\Backend\GoogleId\GoogleId;
          }

          $risk_arr = array('retest">',"risk",".php","*","attack","hack","test");

          $google_id = (str_replace($risk_arr,'',strip_tags((request('google_id'))))?str_replace($risk_arr,'',strip_tags((request('google_id')))):'-');

          $sRow->google_id    = $google_id;

          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

         return redirect()->action('backend\GoogleIdController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\GoogleIdController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\GoogleId\GoogleId::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\GoogleId\GoogleId::search()->orderBy('id', 'asc');
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
