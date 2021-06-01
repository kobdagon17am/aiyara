<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;
use App\Models\Backend\AddressSent;

class Pick_packController extends Controller
{

    public function index(Request $request)
    {
      return View('backend.pick_pack.index');
    }


    public function create()
    {
    }

    public function store(Request $request)
    {
      // dd($request->all());
      if(isset($request->save_to_packing)){

        $arr = implode(',', $request->row_id);

        $receipt = '';
        $arr_receipt = [];
        // No packing code
        $packing_code = DB::select(" SELECT id,packing_code,receipt FROM db_delivery WHERE id in ($arr) and packing_code=0 ");
        if($packing_code){
            foreach ($packing_code as $key => $value) {
                array_push($arr_receipt,$value->receipt);
            }
            $receipt = implode(',',$arr_receipt);
        }

        // Packing code
        $packing_code = DB::select(" SELECT id,packing_code,receipt FROM db_delivery WHERE id in ($arr) and packing_code<>0 ");
        if($packing_code){

            $packing_code = DB::select(" SELECT id,packing_code,receipt FROM db_delivery WHERE packing_code in (".$packing_code[0]->packing_code.") ");
            // dd($packing_code);
            foreach ($packing_code as $key => $value) {
                array_push($arr_receipt,$value->receipt);
            }
            $receipt = implode(',',$arr_receipt);
        }

        // dd($receipt);

        DB::update(" UPDATE db_delivery SET status_pick_pack='1' WHERE id in ($arr)  ");

        $rsDelivery = DB::select(" SELECT * FROM db_delivery WHERE id in ($arr)  ");

          $DeliveryPackingCode = new \App\Models\Backend\Pick_packPackingCode;
          if( $DeliveryPackingCode ){
            $DeliveryPackingCode->created_at = date('Y-m-d H:i:s');
            $DeliveryPackingCode->action_user = (\Auth::user()->id);
            $DeliveryPackingCode->receipt = $receipt ;
            $DeliveryPackingCode->status_picked = 1 ;
            $DeliveryPackingCode->save();
          }

         foreach ($rsDelivery as $key => $value) {
            $DeliveryPacking = new \App\Models\Backend\Pick_packPacking;
            $DeliveryPacking->packing_code = $DeliveryPackingCode->id;
            $DeliveryPacking->delivery_id_fk = @$value->id;
            $DeliveryPacking->created_at = date('Y-m-d H:i:s');
            $DeliveryPacking->save();

            DB::update(" UPDATE db_delivery SET status_pick_pack='1' WHERE packing_code<>0 and packing_code in (".@$value->packing_code.")  ");

         }

        return redirect()->to(url("backend/pick_pack"));


      }
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
      // dd($request->all());
      // return $this->form($id);
    }

   public function form($id=NULL)
    {
      \DB::beginTransaction();
      try {
          // if( $id ){
          //   $sRow = \App\Models\Backend\Pick_pack::find($id);
          // }else{
          //   $sRow = new \App\Models\Backend\Pick_pack;
          // }

          // $sRow->receipt    = request('receipt');
          // $sRow->customer_id    = request('customer_id');
          // $sRow->tel    = request('tel');
          // $sRow->province_id_fk    = request('province_id_fk');
          // $sRow->delivery_date    = request('delivery_date');
                    
          // $sRow->created_at = date('Y-m-d H:i:s');
          // $sRow->save();

          // \DB::commit();

          //  return redirect()->to(url("backend/delivery/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Pick_packController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
      }
    }

    public function destroy($id)
    {
      // $sRow = \App\Models\Backend\Pick_pack::find($id);
      // if( $sRow ){
      //   $sRow->forceDelete();
      // }
      // return response()->json(\App\Models\Alert::Msg('success'));
    }

     public function Datatable(){
      $sTable = DB::select(" 
        select * from db_delivery WHERE status_pack=0 and status_pick_pack=0 
        UNION
        select * from db_delivery WHERE status_pack=1 and status_pick_pack=0 GROUP BY packing_code
        ORDER BY delivery_date DESC
     ");
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
      ->addColumn('business_location', function($row) {
        if(@$row->business_location_id!=''){
             $P = DB::select(" select * from dataset_business_location where id=".@$row->business_location_id." ");
             return @$P[0]->txt_desc;
        }else{
             return '-';
        }
      })
      ->addColumn('packing_code', function($row) {

        $array = '';

        if(@$row->packing_code!=''){
             $array = "P".sprintf("%05d",@$row->packing_code);
        }

        $array1 = array();
        $rs = DB::table('db_delivery')->where('packing_code',$row->packing_code)->get();
        foreach ($rs as $key => $value) {
            if(!empty(@$value->receipt)){
                array_push($array1, @$value->receipt);
            }
        }
          
          $array2 = implode(',', $array1);
          return "(".$array."),".$array2;

      })
      ->addColumn('packing_id', function($row) {
        if(@$row->packing_code!=''){
             return @$row->packing_code;
        }
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }





}
