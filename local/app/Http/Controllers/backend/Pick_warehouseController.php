<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class Pick_warehouseController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.pick_warehouse.index');
      
    }


 public function create()
    {

      $Province = DB::select(" select * from dataset_provinces ");

      $Customer = DB::select(" select * from customers ");
      return View('backend.pick_warehouse.form')->with(
        array(
           'Customer'=>$Customer,'Province'=>$Province
        ) );
    }


    public function store(Request $request)
    {
      // dd($request->all());
      
      if(isset($request->save_to_set)){

      	$arr = implode(',', $request->row_id);

        DB::update(" UPDATE db_pick_warehouse SET status_pick_warehouse='1',updated_at=now() WHERE id in ($arr)  ");

        $rs = DB::select(" select * from db_pick_warehouse WHERE id in ($arr)  ");

        foreach ($rs as $key => $value) {
        	DB::update(" UPDATE db_pick_warehouse_packing_code SET status_pick_warehouse='1',updated_at=now() WHERE id = ".$value->packing_code."  ");
        }

        DB::update(" 
	        UPDATE
			db_pick_warehouse_packing_code
			Inner Join db_pick_warehouse ON db_pick_warehouse_packing_code.id = db_pick_warehouse.packing_code
			SET
			db_pick_warehouse.status_pick_warehouse=db_pick_warehouse_packing_code.status_pick_warehouse
			WHERE
			db_pick_warehouse_packing_code.status_pick_warehouse=1
		 ");


        return redirect()->to(url("backend/pick_warehouse"));

      }else{
        return $this->form();
      }
      
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Pick_warehouse::find($id);
       $Province = DB::select(" select * from dataset_provinces ");

       $Customer = DB::select(" select * from customers ");
      return View('backend.pick_warehouse.form')->with(
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
            $sRow = \App\Models\Backend\Pick_warehouse::find($id);
          }else{
            $sRow = new \App\Models\Backend\Pick_warehouse;
          }

          $sRow->receipt    = request('receipt');
          $sRow->customer_id    = request('customer_id');
          $sRow->province_code    = request('province_code');
          $sRow->pick_warehouse_date    = request('pick_warehouse_date');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/pick_warehouse/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Pick_warehouseController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {

        DB::update(" UPDATE db_pick_warehouse SET status_pick_warehouse='0',updated_at=now() WHERE id in ($id)  ");

        $rs = DB::select(" select * from db_pick_warehouse WHERE id in ($id)  ");

        foreach ($rs as $key => $value) {
        	DB::update(" UPDATE db_pick_warehouse_packing_code SET status_pick_warehouse='0',updated_at=now() WHERE id = ".$value->packing_code."  ");
        }

        DB::update(" 
	        UPDATE
			db_pick_warehouse_packing_code
			Inner Join db_pick_warehouse ON db_pick_warehouse_packing_code.id = db_pick_warehouse.packing_code
			SET
			db_pick_warehouse.status_pick_warehouse=db_pick_warehouse_packing_code.status_pick_warehouse
			WHERE
			db_pick_warehouse_packing_code.status_pick_warehouse=0
		 ");
      
      return response()->json(\App\Models\Alert::Msg('success'));

    }

    public function Datatable(){
      // $sTable = \App\Models\Backend\Pick_warehouse::search()->where('status_pick_warehouse','0')->orderBy('id', 'asc');
      $sTable = DB::select(" 
      	select * from db_pick_warehouse WHERE status_pack=0 and status_pick_warehouse=0 
    		UNION
    		select * from db_pick_warehouse WHERE status_pack=1 and status_pick_warehouse=0 GROUP BY packing_code
    		ORDER BY updated_at DESC
		 ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('receipt', function($row) {
      // 	if($row->packing_code!=0){
	     //      $DP = DB::table('db_pick_warehouse_packing')->where('packing_code',$row->packing_code)->get();
	     //      $array = array();
	     //      foreach ($DP as $key => $value) {
	     //        $rs = DB::table('db_pick_warehouse')->where('id',$value->pick_warehouse_id_fk)->get();
	     //        array_push($array, @$rs[0]->receipt);
	     //      }
	     //      $arr = implode(',', $array);
	     //      return $arr;
      // 	}else{
      // 		return $row->receipt;
      // 	}
      // })
      ->addColumn('pick_warehouse_date', function($row) {
          $d = strtotime($row->pick_warehouse_date);
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
        if(@$row->province_code!=''){
           $P = DB::select(" select * from dataset_provinces where code=".@$row->province_code." ");
           return @$P[0]->name_th;
        }else{
             return @$row->province_name;
        }
      })
      ->addColumn('business_location', function($row) {
        if(@$row->business_location_id!=''){
             $P = DB::select(" select * from dataset_business_location where id=".@$row->business_location_id." ");
             return @$P[0]->txt_desc;
        }else{
             return '-';
        }
      })      
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }




}
