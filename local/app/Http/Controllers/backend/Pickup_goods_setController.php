<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class Pickup_goods_setController extends Controller
{

    public function index(Request $request)
    {

      return view('backend.pickup_goods_set.index');
      
    }


 public function create()
    {

      $Province = DB::select(" select * from dataset_provinces ");

      $Customer = DB::select(" select * from customers ");
      return View('backend.pickup_goods_set.form')->with(
        array(
           'Customer'=>$Customer,'Province'=>$Province
        ) );
    }


    public function store(Request $request)
    {
      // dd($request->all());
      
      if(isset($request->save_to_pending)){

      	$arr = implode(',', $request->row_id);

        DB::update(" UPDATE db_delivery SET status_pack='1',updated_at=now() WHERE id in ($arr)  ");

        $rsDelivery = DB::select(" SELECT * FROM db_delivery WHERE id in ($arr)  ");

        $rsDeliveryAddr = DB::select(" 
        	SELECT
    			customers_detail.id as addr
    			FROM
    			db_delivery
    			Left Join customers_detail ON db_delivery.customer_id = customers_detail.customer_id
    			WHERE db_delivery.id in ($arr) 
    			ORDER BY customers_detail.id limit 1  ");

  	      $DeliveryPendingCode = new \App\Models\Backend\DeliveryPendingCode;
  	      if( $DeliveryPendingCode ){
  	      	$DeliveryPendingCode->addr_id = $rsDeliveryAddr[0]->addr;
  	      	$DeliveryPendingCode->created_at = date('Y-m-d H:i:s');
  	        $DeliveryPendingCode->save();
  	      }

  	     foreach ($rsDelivery as $key => $value) {
  	     	$DeliveryPending = new \App\Models\Backend\DeliveryPending;
  	     	$DeliveryPending->pending_code = $DeliveryPendingCode->id;
  	     	$DeliveryPending->delivery_id_fk = @$value->id;
  	     	$DeliveryPending->created_at = date('Y-m-d H:i:s');
  	        $DeliveryPending->save();
  	     }

         // เพื่อเอาไว้ไปทำตารางเบิก
         DB::update(" UPDATE
            db_delivery
            Inner Join db_delivery_pending ON db_delivery.id = db_delivery_pending.delivery_id_fk
            SET
            db_delivery.pending_code=
            db_delivery_pending.pending_code ");

        return redirect()->to(url("backend/delivery?select_addr=".$DeliveryPendingCode->id."&"));

        // return redirect()->to(url("backend/delivery/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));

      }elseif(isset($request->save_select_addr)){

      		$DeliveryPendingCode = \App\Models\Backend\DeliveryPendingCode::find($request->id);
      		$DeliveryPendingCode->addr_id = $request->addr;
      		$DeliveryPendingCode->save();

      		return redirect()->to(url("backend/delivery"));
      
      }elseif(isset($request->save_select_addr_edit)){     

      		$DeliveryPendingCode = \App\Models\Backend\DeliveryPendingCode::find($request->id);
      		$DeliveryPendingCode->addr_id = $request->addr;
      		$DeliveryPendingCode->save();

      		return redirect()->to(url("backend/delivery"));      			

      }else{
        return $this->form();
      }
      
    }

    public function edit($id)
    {
       $sRow = \App\Models\Backend\Delivery::find($id);
       $Province = DB::select(" select * from dataset_provinces ");

       $Customer = DB::select(" select * from customers ");
      return View('backend.pickup_goods_set.form')->with(
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
          $sRow->province_code    = request('province_code');
          $sRow->delivery_date    = request('delivery_date');
                    
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

           return redirect()->to(url("backend/delivery/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Pickup_goods_setController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // $sRow = \App\Models\Backend\Delivery::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // return response()->json(\App\Models\Alert::Msg('success'));
    }

    public function Datatable(){
      $sTable = \App\Models\Backend\Delivery::search()->where('status_delivery','1')->orderBy('id', 'asc');
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      ->addColumn('delivery_date', function($row) {
          $d = strtotime($row->delivery_date);
          return date("d/m/", $d).(date("Y", $d)+543);
      })
      ->addColumn('customer_name', function($row) {
      	if(@$row->customer_id!=''){
         	$Customer = DB::select(" select * from customers where id=".@$row->customer_id." ");
        	return @$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
      	}else{
      		return '';
      	}
      })
      ->addColumn('billing_employee', function($row) {
        $sD = DB::select(" select * from ck_users_admin where id=".$row->billing_employee." ");
        return $sD[0]->name;
      })
      ->addColumn('province_name', function($row) {
        if(@$row->province_code!=''){

        	 $P = DB::select(" select * from dataset_provinces where code=".@$row->province_code." ");
        	 return $P[0]->name_th;

	  	}else{
      		return '';
      	}
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }




}
