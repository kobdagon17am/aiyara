<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Po_approveController extends Controller
{

  public function index(Request $request)
  {

    return view('backend.po_approve.index');
    
  }

  public function create()
  {

  }
  public function store(Request $request)
  {
  }

  public function edit($id)
  {


   $sRow = \App\Models\Backend\Orders::find($id);
   

   $slip = DB::table('payment_slip')->where('order_id','=',$id)->get();
   
       // dd($slip);
   return view('backend.po_approve.form')->with([
    'sRow'=>$sRow,
    'id'=>$id ,
    'slip'=>$slip ,
  ]);
 }

 public function update(Request $request, $id)
 {


  return $this->form($id);
}

        // public function payment_type_1(Request $request){
    //   $order_id = $request->order_id;
    //   $admin_id = $request->admin_id;
    //   dd($order_id.":".$admin_id);
    //   $resule = Pvpayment::PvPayment_type_1_success($order_id,$admin_id);
    //   dd($resule);
    // }

public function form($id=NULL)
{
  \DB::beginTransaction();
  try {

    if( $id ){
      $sRow = \App\Models\Backend\Orders::find($id);
    }else{
      $sRow = new \App\Models\Backend\Orders;
    }

    $sRow->status_slip  = request('status_slip');
    $sRow->id_admin_check  = \Auth::user()->id;
    $sRow->date_action_pv  = now();
    $sRow->save(); 

    // if('อนุมัติ'){

    // }else{//อัพโหลดสลิปไหม่

    // }

    if(request('status_slip')==true){
             // dd($id." : ".(\Auth::user()->id));
     \App\Models\Frontend\PvPayment::PvPayment_type_confirme($id,\Auth::user()->id);
   }

   \DB::commit();

          // if( $id ){
          //   return redirect()->action('backend\OrdersController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);
          // }else{
            //return redirect()->to(url("backend/po_approve/".$sRow->id."/edit"));
   return redirect()->action('backend\Po_approveController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);

          // }
   

 } catch (\Exception $e) {
  echo $e->getMessage();
  \DB::rollback();
  return redirect()->action('backend\Po_approveController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
}
}

public function destroy($id)
{


}

public function Datatable(){

  $sTable =  DB::table('orders')
  ->select('orders.*','orders.id as orders_id','dataset_order_status.detail','dataset_order_status.css_class','dataset_orders_type.orders_type as type','dataset_pay_type.detail as pay_type_name') 
  ->leftjoin('dataset_order_status','dataset_order_status.orderstatus_id','=','orders.orderstatus_id')
  ->leftjoin('dataset_orders_type','dataset_orders_type.group_id','=','orders.type_id') 
  ->leftjoin('dataset_pay_type','dataset_pay_type.pay_type_id','=','orders.pay_type_id') 
  ->where('dataset_order_status.lang_id','=','1')
  ->where('dataset_orders_type.lang_id','=','1')
  ->where('orders.orderstatus_id','=','2')
  ->orderby('id','DESC');

  $sQuery = \DataTables::of($sTable);
  return $sQuery
  ->addColumn('price', function($row) {
    if($row->type_id == 6){
      return number_format($row->price,2);
    }else{
      return number_format($row->price + $row->shipping,2);
    }
    
  })
  ->addColumn('date', function($row) {
    return  date('d/m/Y H:i:s',strtotime($row->created_at));
  })
  ->addColumn('status', function($row) {
    return $row->detail;
  })
  ->make(true);
}


public function DatatableSet(){
  $sTable = \App\Models\Backend\Orders::search()->orderBy('id', 'asc');
  $sQuery = \DataTables::of($sTable);
  return $sQuery
  ->addColumn('price', function($row) {
    if($row->type_id == 6){
      return number_format($row->price,2);
    }else{
      return number_format($row->price + $row->shipping,2);
    }
  })
  ->addColumn('type', function($row) {
    $D = DB::table('dataset_orders_type')->where('group_id','=',$row->type_id)->get();
    return @$D[0]->orders_type;
  })
  ->addColumn('date', function($row) {
    return  date('d/m/Y H:i:s',strtotime($row->create_at));
  })
  ->make(true);
}



}
