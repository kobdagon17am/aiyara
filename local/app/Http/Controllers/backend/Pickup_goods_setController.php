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

      $Customer = DB::select(" select * from customers limit 100 ");
      return View('backend.pickup_goods_set.form')->with(
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

       $Customer = DB::select(" select * from customers limit 100 ");
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
          $sRow->province_id_fk    = request('province_id_fk');
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
      // $sTable = \App\Models\Backend\Delivery::search()->where('status_delivery','1')->orderBy('id', 'asc');
      $sTable = DB::select(" 
        select * from db_delivery WHERE status_pack=0 and status_delivery=1 
        UNION
        select * from db_delivery WHERE status_pack=1 and status_delivery=1 GROUP BY packing_code
        ORDER BY updated_at DESC
         ");
      $sQuery = \DataTables::of($sTable);
      return $sQuery
      // ->addColumn('receipt', function($row) {
      //   if($row->packing_code!=0){
      //       $DP = DB::table('db_delivery_packing')->where('packing_code',$row->packing_code)->get();
      //       $array = array();
      //       foreach ($DP as $key => $value) {
      //         $rs = DB::table('db_delivery')->where('id',$value->delivery_id_fk)->get();
      //         array_push($array, @$rs[0]->receipt);
      //       }
      //       $arr = implode(',', $array);
      //       return $arr;
      //   }else{
      //     return $row->receipt;
      //   }
      // })
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
      ->addColumn('print', function($row) {
           $D = DB::select(" select * from db_delivery where id=".@$row->id." ");
           return @$row->status_pack.':'.@$D[0]->id;
      })
      ->addColumn('approved', function($row) {
           $D = DB::select(" select * from db_delivery where id=".@$row->id." ");
           return @$row->approver.':'.@$D[0]->id;
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }




}
