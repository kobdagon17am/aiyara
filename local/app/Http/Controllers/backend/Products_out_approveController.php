<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Products_out_approveController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.products_out_approve.index');
      
    }

 public function create()
    {

      $Province = DB::select(" select * from dataset_provinces ");

      $Customer = DB::select(" select * from customers ");
      return View('backend.products_out_approve.form')->with(
        array(
           'Customer'=>$Customer,'Province'=>$Province
        ) );
    }
    public function store(Request $request)
    {
      return $this->form();
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Products_out_approve::find($id);
       $Province = DB::select(" select * from dataset_provinces ");

       $Customer = DB::select(" select * from customers ");
      return View('backend.products_out_approve.form')->with(
        array(
           'sRow'=>$sRow, 'id'=>$id, 'Province'=>$Province,'Customer'=>$Customer,
        ) );
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
            $sRow = \App\Models\Backend\Products_out_approve::find($id);
          }else{
            $sRow = new \App\Models\Backend\Products_out_approve;
          }

          $sRow->delivery_slip    = request('delivery_slip');
          $sRow->receipt    = request('receipt');
          $sRow->customer_id    = request('customer_id');
          $sRow->tel    = request('tel');
          $sRow->province_code    = request('province_code');
          $sRow->delivery_date    = request('delivery_date');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/Products_out_approve/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Products_out_approveController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Products_out_approve::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Products_out_approve::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('subwarehouse', function($row) {
        $D = DB::select(" select * from subwarehouse where id=".$row->subwarehouse_id_fk." ");
        return @$D[0]->w_name;
      })
      ->addColumn('zone', function($row) {
        $D = DB::select(" select * from zone where id=".$row->zone_id_fk." ");
        return @$D[0]->w_name;
      })
      ->addColumn('shelf', function($row) {
        $D = DB::select(" select * from shelf where id=".$row->shelf_id_fk." ");
        return @$D[0]->w_name;
      })
      ->addColumn('receptionist_name', function($row) {
        $sD = DB::select(" select * from ck_users_admin where id=".$row->receptionist." ");
        return $sD[0]->name;
      })
      ->addColumn('receptionist_name', function($row) {
        $sD = DB::select(" select * from ck_users_admin where id=".$row->receptionist." ");
        return $sD[0]->name;
      })
      ->addColumn('approver_name', function($row) {
        $sD = DB::select(" select * from ck_users_admin where id=".$row->approver." ");
        return $sD[0]->name;
      })  
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
