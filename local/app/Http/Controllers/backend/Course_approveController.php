<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;

class Course_approveController extends Controller
{

  public function index(Request $request)
  {
    $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
      return $query->where('id', auth()->user()->business_location_id_fk);
    })
    ->get();
    $sBranchs = \App\Models\Backend\Branchs::whereIn('business_location_id_fk', $sBusiness_location->pluck('id'))->get();
    $types = DB::table('dataset_orders_type')->where('lang_id', 1)->get();
    $statuses = DB::table('dataset_order_status')->get();

    return view('backend.course_approve.index', compact('sBusiness_location', 'sBranchs', 'types', 'statuses'));
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
   $slip = DB::table('payment_slip')->where('order_id','=',$id)->orderby('id','desc')->get();
   return view('backend.course_approve.form')->with([
    'sRow'=>$sRow,
    'id'=>$id ,
    'slip'=>$slip ,
  ]);
 }

 public function update(Request $request, $id)
 {
  return $this->form($id);
}

public function form($id=NULL)
{

  \DB::beginTransaction();
  try {

    if($id){
      $sRow = \App\Models\Backend\Orders::find($id);
    }else{
      $sRow = new \App\Models\Backend\Orders;
    }

    $sRow->approver  = \Auth::user()->id;
    $sRow->updated_at  = now();

    if (@request('approved') != null ){
      $sRow->status_slip  = 'true' ;
    }

    if (@request('no_approved') != null ){

      $sRow->status_slip  = 'false' ;
      $sRow->order_status_id_fk  = '3' ;
    }

    $sRow->save();

    if (@request('approved') != null ){
     $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme($id,\Auth::user()->id,'1','admin');
   }

   \DB::commit();

   return redirect()->action('backend\Course_approveController@index')->with(['alert'=>\App\Models\Alert::Msg('success')]);


 } catch (\Exception $e) {
  echo $e->getMessage();
  \DB::rollback();
  return redirect()->action('backend\Course_approveController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
}
}

public function destroy($id)
{


}

public function Datatable(Request $request){




  $business_location_id_fk = $request->business_location_id_fk;
  if (empty($business_location_id_fk)) {
    $business_location_id_fk = 1;
}
  $branch_id_fk = $request->branch_id_fk;
  $orderStatus = $request->orderStatus;
  $codeOrder = $request->codeOrder;
  $type = $request->type;
  $startDate = $request->date['start'];
  $endDate = $request->date['end'];

 $sTable =  DB::table('db_orders')
 ->select('db_orders.*','db_orders.id as orders_id','dataset_order_status.detail','dataset_order_status.css_class','dataset_orders_type.orders_type as type','dataset_pay_type.detail as pay_type_name')
 ->leftjoin('dataset_order_status','dataset_order_status.orderstatus_id','=','db_orders.order_status_id_fk')
 ->leftjoin('dataset_orders_type','dataset_orders_type.group_id','=','db_orders.purchase_type_id_fk')
 ->leftjoin('dataset_pay_type','dataset_pay_type.id','=','db_orders.pay_type_id_fk')
 ->where('dataset_order_status.lang_id','=',$business_location_id_fk)
 ->where('dataset_orders_type.lang_id','=',$business_location_id_fk)
//  ->where('db_orders.purchase_type_id_fk','=','6')
 ->where('db_orders.order_status_id_fk','=','2')
 ->when(auth()->user()->permission !== 1, function ($query) {
   return $query->where('db_orders.business_location_id_fk', auth()->user()->business_location_id_fk)
      ->where('db_orders.branch_id_fk', auth()->user()->branch_id_fk);
 })
 ->when($business_location_id_fk, function ($query, $business_location_id_fk) {
   return $query->where('db_orders.business_location_id_fk', $business_location_id_fk);
 })
 ->when($branch_id_fk, function ($query, $branch_id_fk) {
   return $query->where('db_orders.branch_id_fk', $branch_id_fk);
 })
 ->when($orderStatus, function ($query, $orderStatus) {
   return $query->where('dataset_order_status.id', $orderStatus);
 })
 ->when($codeOrder, function ($query, $codeOrder) {
   return $query->where('db_orders.code_order', 'LIKE', "%{$codeOrder}%");
 })
 ->when($type, function ($query, $type) {
  return $query->where('dataset_orders_type.id', $type);
})
->when($startDate, function ($query, $startDate) {
  return $query->where('db_orders.created_at', '>=', $startDate.' 00:00:00');
})
->when($endDate, function ($query, $endDate) {
  return $query->where('db_orders.created_at', '<=', $endDate.' 23:59:59');
})
 ->orderby('id','DESC')
 ->get();


 $sQuery = \DataTables::of($sTable);
 return $sQuery
 ->addColumn('price', function($row) {
  return number_format($row->sum_price);
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
    return number_format($row->price);
  })
  ->addColumn('type', function($row) {
    $D = DB::table('dataset_orders_type')->where('group_id','=',$row->type_id)->get();
    return @$D[0]->orders_type;
  })
  ->addColumn('date', function($row) {
    return  date('d/m/Y H:i:s',strtotime($row->created_at));
  })
  ->make(true);
}



}
