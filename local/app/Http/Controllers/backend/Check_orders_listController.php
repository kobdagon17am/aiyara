<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Check_orders_listController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.check_orders_list.index');
      
    }

 public function create()
    {

      $Province = DB::select(" select * from dataset_provinces ");

      $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.check_orders_list.form')->with(
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
       $sRow = \App\Models\Backend\Check_orders_list::find($id);
       $Province = DB::select(" select * from dataset_provinces ");

       $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.check_orders_list.form')->with(
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
            $sRow = \App\Models\Backend\Check_orders_list::find($id);
          }else{
            $sRow = new \App\Models\Backend\Check_orders_list;
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

           return redirect()->to(url("backend/Check_orders_list/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Check_orders_listController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Check_orders_list::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Check_orders_list::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('customer_code', function($row) {
        $Customer = DB::select(" select * from customer_code where customers_id_fk=".$row->customer_id." ");
        return $Customer[0]->c_code;
      })
      ->addColumn('customer_name', function($row) {
        $Customer = DB::select(" select * from customers where id=".$row->customer_id." ");
        return $Customer[0]->prefix_name.$Customer[0]->first_name." ".$Customer[0]->last_name;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
