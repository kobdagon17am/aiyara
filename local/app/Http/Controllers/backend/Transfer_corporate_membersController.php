<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Transfer_corporate_membersController extends Controller
{

    public function index(Request $request)
    {
      $sBranchs = \App\Models\Backend\Branchs::get();
      return view('backend.transfer_corporate_members.index')->with(array('sBranchs'=>$sBranchs ) );
    }

   public function create()
    {
      $sRowGroup = \App\Models\Backend\Transfer_corporate_members::orderBy('group_id','desc')->limit(1)->get();
      $groupMaxID = $sRowGroup[0]->group_id+1;
      // dd($groupMaxID);
      $sLanguage = \App\Models\Backend\Language::get();

      $sBranchs = \App\Models\Backend\Branchs::get();

      $Warehouse = \App\Models\Backend\Warehouse::get();

      return View('backend.transfer_corporate_members.form')->with(array('sLanguage'=>$sLanguage,'groupMaxID'=>$groupMaxID ) );
    }

    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRowGroup = \App\Models\Backend\Transfer_corporate_members::find($id);
       $sRow = \App\Models\Backend\Transfer_corporate_members::where('group_id', $sRowGroup->group_id)->get();
       // dd($sRow[0]->status);
       $sLanguage = \App\Models\Backend\Language::get();
       return View('backend.transfer_corporate_members.form')->with(array('sRow'=>$sRow, 'id'=>$id , 'sLanguage'=>$sLanguage ) );
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
            $sRow = \App\Models\Backend\Transfer_corporate_members::find($id);
            $langCnt = count(request('lang'));
            for ($i=0; $i < $langCnt ; $i++) { 

                \App\Models\Backend\Transfer_corporate_members::where('id', request('id')[$i])->update(
                      [
                        'product_unit' => request('product_unit')[$i] ,
                        'detail' => request('detail')[$i] ,
                        
                        'date_added' => request('date_added') ,

                        'updated_at' => date('Y-m-d H:i:s') ,
                        'status' => request('status')?request('status'):0 ,
                      ]
                  );     
            }

          }else{

            $sRowGroup = \App\Models\Backend\Transfer_corporate_members::orderBy('group_id','desc')->limit(1)->get();
          $groupMaxID = $sRowGroup[0]->group_id+1;

            $langCnt = count(request('lang'));
            for ($i=0; $i < $langCnt ; $i++) { 
            
                \App\Models\Backend\Transfer_corporate_members::insert(
                      [
                        'lang_id' => request('lang')[$i] ,
                        'group_id' => $groupMaxID ,

                        'product_unit' => request('product_unit')[$i] ,
                        'detail' => request('detail')[$i] ,
                        
                        'date_added' => request('date_added') ,
                        'created_at' => date('Y-m-d H:i:s') ,
                        'status' => 1,
                      ]
                  );     
            }

          }


          \DB::commit();

         return redirect()->action('backend\Transfer_corporate_membersController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Transfer_corporate_membersController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Transfer_corporate_members::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Transfer_corporate_members::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('name', function($row) {
      //   // return $row->fname.' '.$row->surname;
      // })
      // ->addColumn('updated_at', function($row) {
      //   return is_null($row->updated_at) ? '-' : $row->updated_at;
      // })
      ->make(true);
    }



}
