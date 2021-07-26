<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Products_return_approveController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.products_return_approve.index');
      
    }

 public function create()
    {

      $Province = DB::select(" select * from dataset_provinces ");

      $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.products_return_approve.form')->with(
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
       $sRow = \App\Models\Backend\Products_return_approve::find($id);
       $Province = DB::select(" select * from dataset_provinces ");

       $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.products_return_approve.form')->with(
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
            $sRow = \App\Models\Backend\Products_return_approve::find($id);
          }else{
            $sRow = new \App\Models\Backend\Products_return_approve;
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

           return redirect()->to(url("backend/Products_return_approve/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Products_return_approveController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      $sRow = \App\Models\Backend\Products_return_approve::find($id);
      if( $sRow ){
        $sRow->forceDelete();
      }
      return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Products_return_approve::search()->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('product_code', function($row) {
        $P = DB::select(" select * from products where id=".$row->products_id_fk." ");
        return @$P[0]->product_code;
      })
      ->addColumn('product_name', function($row) {
        $sProducts_details = \App\Models\Backend\Products_details::where('product_id_fk',$row->products_id_fk)->where('lang_id',1)->get();
        return @$sProducts_details[0]->product_name?@$sProducts_details[0]->product_name:'-ยังไม่กำหนดชื่อสินค้าในเมนูสินค้า-';
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
