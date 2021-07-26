<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class Pickup_goodsController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.pickup_goods.index');
      
    }


 public function create()
    {

      $Province = DB::select(" select * from dataset_provinces ");

      $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.pickup_goods.form')->with(
        array(
           'Customer'=>$Customer,'Province'=>$Province
        ) );
    }


    public function store(Request $request)
    {
      // dd($request->all());
      
      if(isset($request->save_to_set)){

      	$arr = implode(',', $request->row_id);

        DB::update(" UPDATE db_delivery SET status_delivery='1',updated_at=now() WHERE id in ($arr)  ");

        $rs = DB::select(" select * from db_delivery WHERE id in ($arr)  ");

        foreach ($rs as $key => $value) {
        	DB::update(" UPDATE db_delivery_packing_code SET status_delivery='1',updated_at=now() WHERE id = ".$value->packing_code."  ");
        }

        DB::update(" 
	          UPDATE
      			db_delivery_packing_code
      			Inner Join db_delivery ON db_delivery_packing_code.id = db_delivery.packing_code
      			SET
      			db_delivery.status_delivery=db_delivery_packing_code.status_delivery
      			WHERE
      			db_delivery_packing_code.status_delivery=1
      		 ");

        return redirect()->to(url("backend/pickup_goods"));

      }else{
        return $this->form();
      }
      
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Delivery::find($id);
       $Province = DB::select(" select * from dataset_provinces ");

       $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.pickup_goods.form')->with(
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
            $sRow = \App\Models\Backend\Delivery::find($id);
          }else{
            $sRow = new \App\Models\Backend\Delivery;
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

           return redirect()->to(url("backend/delivery/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Pickup_goodsController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {

        DB::update(" UPDATE db_delivery SET status_delivery='0',updated_at=now() WHERE id in ($id)  ");

        $rs = DB::select(" select * from db_delivery WHERE id in ($id)  ");

        foreach ($rs as $key => $value) {
        	DB::update(" UPDATE db_delivery_packing_code SET status_delivery='0',updated_at=now() WHERE id = ".$value->packing_code."  ");
        }

        DB::update(" 
	        UPDATE
    			db_delivery_packing_code
    			Inner Join db_delivery ON db_delivery_packing_code.id = db_delivery.packing_code
    			SET
    			db_delivery.status_delivery=db_delivery_packing_code.status_delivery
    			WHERE
    			db_delivery_packing_code.status_delivery=0
    		 ");
      
      return response()->json(\App\Models\Alert::Msg('success'));

    }

    public function Datatable(){
      // $sTable = \App\Models\Backend\Delivery::search()->where('status_delivery','0')->orderBy('id', 'asc');
      $sTable = DB::select(" 
      	select * from db_delivery WHERE status_pack=0 and status_delivery=0 
    		UNION
    		select * from db_delivery WHERE status_pack=1 and status_delivery=0 GROUP BY packing_code
    		ORDER BY updated_at DESC
		 ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('receipt', function($row) {
      // 	if($row->packing_code!=0){
	     //      $DP = DB::table('db_delivery_packing')->where('packing_code',$row->packing_code)->get();
	     //      $array = array();
	     //      foreach ($DP as $key => $value) {
	     //        $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
	     //        array_push($array, @$rs[0]->receipt);
	     //      }
	     //      $arr = implode(',', $array);
	     //      return $arr;
      // 	}else{
      // 		return $row->receipt;
      // 	}
      // })
      ->addColumn('delivery_date', function($row) {
          $d = strtotime($row->delivery_date);
          return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('customer_name', function($row) {

      	// if($row->packing_code==0){

	      	if(@$row->customer_id!=''){
	         	$Customer = DB::select(" select * from customers where id=".@$row->customer_id." ");
	        	return @$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
	      	}else{
	      		return '';
	      	}

      	// }else{
      	// 	return '';
      	// }

      })
      ->addColumn('billing_employee', function($row) {
        if(@$row->billing_employee!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->billing_employee." ");
          if(@$sD[0]->name){
            return @$sD[0]->name;
          }else{
            return '-ไม่พบข้อมูล-';
          }
        }else{
           return '-ไม่พบข้อมูล-';
        }
      })
      ->addColumn('province_name', function($row) {
        if(@$row->province_id_fk!=''){
           $P = DB::select(" select * from dataset_provinces where code=".@$row->province_id_fk." ");
           return @$P[0]->name_th;
        }else{
             return @$row->province_name;
        }
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }




}
