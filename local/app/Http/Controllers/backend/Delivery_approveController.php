<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Delivery_approveController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.delivery_approve.index');
      
    }

 public function create()
    {

      $Province = DB::select(" select * from dataset_provinces ");

      $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.delivery_approve.form')->with(
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
       $sRow = \App\Models\Backend\Delivery_approve::find($id);
       $Province = DB::select(" select * from dataset_provinces ");

       $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.delivery_approve.form')->with(
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
            $sRow = \App\Models\Backend\Delivery_approve::find($id);
          }else{
            $sRow = new \App\Models\Backend\Delivery_approve;
          }

          $sRow->delivery_slip    = request('delivery_slip');
          $sRow->receipt    = request('receipt');
          $sRow->customer_id    = request('customer_id');
          $sRow->tel    = request('tel');
          $sRow->province_id_fk    = request('province_id_fk');
          $sRow->delivery_date    = request('delivery_date');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/delivery_approve/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Delivery_approveController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Delivery_approve::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Delivery_approve::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('customer_name', function($row) {
        if($row->customer_id!=''){
            $Customer = DB::select(" select * from customers where id=".$row->customer_id." ");
            return $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name;
        }else{
            return '';
        }
        
      })
      ->addColumn('province_name', function($row) {
        $P = DB::select(" select * from dataset_provinces where code=".$row->province_id_fk." ");
        return $P[0]->name_th;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
