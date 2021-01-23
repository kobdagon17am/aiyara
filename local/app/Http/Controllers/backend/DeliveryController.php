<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class DeliveryController extends Controller
{

    public function index(Request $request)
    {

      $sDelivery = \App\Models\Backend\Delivery::where('approver','NULL')->get();
      // dd($sDelivery);

      $arr = [];
      foreach ($sDelivery as $key => $value) {
        array_push($arr,$value->id);
      }
      $sDeliveryPacking = \App\Models\Backend\DeliveryPacking::where('approver','NULL');


      // DB::select(" TRUNCATE db_delivery_packing; ");
      // DB::select(" TRUNCATE db_delivery_packing_code; ");
      // DB::select(" TRUNCATE db_delivery; ");
      DB::select(" 
          INSERT IGNORE INTO db_delivery 
          ( receipt, customer_id, province_name, delivery_date, billing_employee, created_at)
          SELECT
          orders.code_order,orders.customer_id,orders.province,orders.created_at,orders.id_admin_check,now()
          FROM orders
          WHERE
          orders.orderstatus_id in (4,5); ");

      DB::select(" UPDATE db_delivery SET billing_employee='1' WHERE  billing_employee is null ; ");

      return view('backend.delivery.index');
      
    }


 public function create()
    {

      $Province = DB::select(" select * from dataset_provinces ");

      $Customer = DB::select(" select * from customers ");
      return View('backend.delivery.form')->with(
        array(
           'Customer'=>$Customer,'Province'=>$Province
        ) );
    }


    public function store(Request $request)
    {
      // dd($request->all());
      
      if(isset($request->save_to_packing)){

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

  	      $DeliveryPackingCode = new \App\Models\Backend\DeliveryPackingCode;
  	      if( $DeliveryPackingCode ){
  	      	$DeliveryPackingCode->addr_id = $rsDeliveryAddr[0]->addr;
  	      	$DeliveryPackingCode->created_at = date('Y-m-d H:i:s');
  	        $DeliveryPackingCode->save();
  	      }

  	     foreach ($rsDelivery as $key => $value) {
  	     	$DeliveryPacking = new \App\Models\Backend\DeliveryPacking;
  	     	$DeliveryPacking->packing_code = $DeliveryPackingCode->id;
  	     	$DeliveryPacking->delivery_id_fk = @$value->id;
  	     	$DeliveryPacking->created_at = date('Y-m-d H:i:s');
  	        $DeliveryPacking->save();
  	     }

         // เพื่อเอาไว้ไปทำตารางเบิก
         DB::update(" UPDATE
            db_delivery
            Inner Join db_delivery_packing ON db_delivery.id = db_delivery_packing.delivery_id_fk
            SET
            db_delivery.packing_code=
            db_delivery_packing.packing_code ");

        return redirect()->to(url("backend/delivery?select_addr=".$DeliveryPackingCode->id."&"));

        // return redirect()->to(url("backend/delivery/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));

      }elseif(isset($request->save_select_addr)){

      		$DeliveryPackingCode = \App\Models\Backend\DeliveryPackingCode::find($request->id);
      		$DeliveryPackingCode->addr_id = $request->addr;
      		$DeliveryPackingCode->save();

      		return redirect()->to(url("backend/delivery"));
      
      }elseif(isset($request->save_select_addr_edit)){     

      		$DeliveryPackingCode = \App\Models\Backend\DeliveryPackingCode::find($request->id);
      		$DeliveryPackingCode->addr_id = $request->addr;
      		$DeliveryPackingCode->save();

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
      return View('backend.delivery.form')->with(
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
        return redirect()->action('backend\DeliveryController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
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
      $sTable = \App\Models\Backend\Delivery::search()->where('status_pack','0')->where('approver','NULL')->orderBy('id', 'asc');
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
        if(@$row->billing_employee!=''){
          $sD = DB::select(" select * from ck_users_admin where id=".$row->billing_employee." ");
           return @$sD[0]->name;
        }else{
          return '';
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
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }




}
