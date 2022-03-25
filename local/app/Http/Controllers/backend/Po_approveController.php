<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Session;
use App\Helpers\General;

class Po_approveController extends Controller
{

    public function index(Request $request)
    {
        General::gen_id_url();
        // $sBusiness_location = \App\Models\Backend\Business_location::when(auth()->user()->permission !== 1, function ($query) {
        //     return $query->where('id', auth()->user()->business_location_id_fk);
        // })->get();
        // $sBranchs = \App\Models\Backend\Branchs::when(auth()->user()->permission !== 1, function ($query) {
        //      return $query->where('id', auth()->user()->branch_id);
        //  })->get();
        $sBusiness_location = \App\Models\Backend\Business_location::get();
        $sBranchs = \App\Models\Backend\Branchs::get();


        if(@\Auth::user()->permission==1){
            $code_order = DB::select(" select code_order from db_orders where pay_type_id_fk in (1,8,10,11,12) and  LENGTH(code_order)>3 order by code_order,created_at desc limit 500 ");
        }else{
            // $code_order = DB::select(" select code_order from db_orders where action_user=".\Auth::user()->id." order by code_order,created_at desc limit 500 ");
            // $code_order = DB::select(" select code_order from db_orders where pay_type_id_fk in (1,8,10,11,12) and  LENGTH(code_order)>3 and branch_id_fk=".\Auth::user()->branch_id_fk." OR pay_type_id_fk in (1,8,10,11,12) and  LENGTH(code_order)>3 and action_user=".\Auth::user()->id." order by code_order,created_at desc limit 500 ");
            $code_order = DB::select(" select code_order from db_orders where pay_type_id_fk in (1,8,10,11,12) and  LENGTH(code_order)>3 AND branch_id_fk=".\Auth::user()->branch_id_fk." order by code_order,created_at desc limit 500 ");
        }
// dd($code_order);
        $sApprover = DB::select(" select * from ck_users_admin where isActive='Y' AND branch_id_fk=".\Auth::user()->branch_id_fk." AND id in (select transfer_amount_approver from db_orders) ");

        return View('backend.po_approve.index')->with(
        array(
           'sBusiness_location'=>$sBusiness_location,
           'sBranchs'=>$sBranchs,
           'sApprover'=>$sApprover,
           'code_order'=>$code_order,
        ) );

    }


    public function create()
    {
    }
    public function store(Request $request)
    {
    }

    public function notAprrove(Request $request)
    {

    }

    public function edit($id)
    {
        $sRow = \App\Models\Backend\Orders::find($id);
        // $slip = DB::table('payment_slip')->where('order_id', '=', $id)->orderby('id', 'asc')->get();
        $slip = DB::table('payment_slip')->where('code_order', '=', $sRow->code_order)->orderby('id', 'asc')->get();
        $slip_approve = DB::table('payment_slip')->where('code_order', '=', $sRow->code_order)->whereIn('status',[2])->orderby('id', 'asc')->get();
        $slip_not_approve = DB::table('payment_slip')->where('code_order', '=', $sRow->code_order)->whereIn('status',[3])->orderby('id', 'asc')->get();

        $price = 0;
        if ($sRow->purchase_type_id_fk == 7) {
            $price = number_format($sRow->sum_price, 2);
        } else if ($sRow->purchase_type_id_fk == 5) {
            $total_price = $sRow->total_price - $sRow->gift_voucher_price;
            $price = number_format($total_price, 2);
        } else {
            $price = number_format($sRow->sum_price + $sRow->shipping_price, 2);
        }

        // $TransferBank = \App\Models\Backend\TransferBank::get();
        $sAccount_bank = \App\Models\Backend\Account_bank::get();
        return view('backend.po_approve.form')->with([
            'sRow' => $sRow,
            'id' => $id,
            'slip' => $slip,
            'slip_approve' => $slip_approve,
            'slip_not_approve' => $slip_not_approve,
            'price' => $price,
            'note_fullpayonetime' => $sRow->note_fullpayonetime,
            'approval_amount_transfer' => $sRow->approval_amount_transfer>0?$sRow->approval_amount_transfer:"",
            'TransferBank' => $sAccount_bank,
        ]);
    }

    public function update(Request $request, $id)
    {

        \DB::beginTransaction();
        try {

            // วุฒิเพิ่มวนเช็คว่ามีบิลไหนจ่ายพร้อมบิลนี้ไหม
            $data_id = DB::table('db_orders')->where('id',$id)->first();
            if($data_id){
                $sRow = \App\Models\Backend\Orders::find($data_id->id);
                $sRow->approver = \Auth::user()->id;
                $sRow->updated_at = now();
                if (@request('approved') != null) {
                    $sRow->status_slip = 'true';
                    $sRow->order_status_id_fk = '5';
                    $sRow->approve_status  = 2;
                    $sRow->transfer_bill_status  = 2;
                    if(!empty($request->slip_ids)){
                        for ($i=0; $i < count($request->slip_ids) ; $i++) {
                          DB::table('payment_slip')->where('id',$request->slip_ids[$i])->update([
                              'note' => $request->slip_note[$i],
                              'code_order' => $sRow->code_order,
                              'status' => 2,
                              'transfer_bill_date' => $request->transfer_bill_date[$i],
                          ]);
                        }
                    }
                    // approval_amount_transfer
                    $sRow->approval_amount_transfer = $request->approval_amount_transfer;
                    $sRow->account_bank_name_customer = $request->account_bank;
                    $sRow->transfer_amount_approver = \Auth::user()->id;
                    $sRow->transfer_bill_date  = $request->transfer_bill_date;
                    $sRow->transfer_bill_approvedate = date("Y-m-d H:i:s");
                    DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
                }
    
                if (@request('no_approved') != null) {
                    $sRow->status_slip = 'false';
                    $sRow->order_status_id_fk = '3';
                    $sRow->approve_status  = 1;
                    // note
                    $sRow->transfer_bill_status = 1;
                    $sRow->status_slip = 'true';
                    $sRow->approval_amount_transfer = 0 ;
                    $sRow->account_bank_name_customer = 0;
                    $sRow->transfer_amount_approver =  \Auth::user()->id;
                    $sRow->transfer_bill_date  = NULL;
                    $sRow->transfer_bill_approvedate = NULL;
                    $sRow->transfer_bill_note = @request('detail');
                    DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
                    $sRow->approve_status = 6;
                }
    
    
                if (@request('approved') != null) {
                    if ($sRow->order_channel == 'VIP') {
                      $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme_vip($id, \Auth::user()->id, '1', 'admin');
                    } else {
                      $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme($id, \Auth::user()->id, '1', 'admin');
                    }
                }
    
                $sRow->save();
                if($sRow->approve_status==2){
                    // $this->fncUpdateDeliveryAddress($sRow->id);
                    // $this->fncUpdateDeliveryAddressDefault($sRow->id);
                    \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddress($sRow->id);
                    \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddressDefault($sRow->id);
                }

                $other_bill = DB::table('db_orders')->where('pay_with_other_bill',1)->where('pay_with_other_bill_note','like','%'.$data_id->code_order.'%')->get();

                foreach($other_bill as $b){
                    $sRow2 = \App\Models\Backend\Orders::find($b->id);
                    $sRow2->approver = \Auth::user()->id;
                    $sRow2->updated_at = now();
                    if (@request('approved') != null) {
                        $sRow2->status_slip = 'true';
                        $sRow2->order_status_id_fk = '5';
                        $sRow2->approve_status  = 2;
                        $sRow2->transfer_bill_status  = 2;
                        if(!empty($request->slip_ids)){
                            for ($i=0; $i < count($request->slip_ids) ; $i++) {
                              DB::table('payment_slip')->where('id',$request->slip_ids[$i])->update([
                                  'note' => $request->slip_note[$i],
                                  'code_order' => $sRow2->code_order,
                                  'status' => 2,
                                  'transfer_bill_date' => $request->transfer_bill_date[$i],
                              ]);
                            }
                        }
                        $sRow2->approval_amount_transfer = $sRow2->transfer_price;
                        $sRow2->account_bank_name_customer = $request->account_bank;
                        $sRow2->transfer_amount_approver = \Auth::user()->id;
                        $sRow2->transfer_bill_date  = $request->transfer_bill_date;
                        $sRow2->transfer_bill_approvedate = date("Y-m-d H:i:s");
                        DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
                    }
        
                    if (@request('no_approved') != null) {
                        $sRow2->status_slip = 'false';
                        $sRow2->order_status_id_fk = '3';
                        $sRow2->approve_status  = 1;
                        // note
                        $sRow2->transfer_bill_status = 1;
                        $sRow2->status_slip = 'true';
                        $sRow2->approval_amount_transfer = 0 ;
                        $sRow2->account_bank_name_customer = 0;
                        $sRow2->transfer_amount_approver =  \Auth::user()->id;
                        $sRow2->transfer_bill_date  = NULL;
                        $sRow2->transfer_bill_approvedate = NULL;
                        $sRow2->transfer_bill_note = @request('detail');
                        DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");
                        $sRow2->approve_status = 6;
                    }
        
        
                    if (@request('approved') != null) {
                        if ($sRow2->order_channel == 'VIP') {
                          $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme_vip($id, \Auth::user()->id, '1', 'admin');
                        } else {
                          $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme($id, \Auth::user()->id, '1', 'admin');
                        }
                    }
        
                    $sRow2->save();
                    if($sRow2->approve_status==2){
                        // $this->fncUpdateDeliveryAddress($sRow2->id);
                        // $this->fncUpdateDeliveryAddressDefault($sRow2->id);
                        \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddress($sRow2->id);
                        \App\Http\Controllers\backend\FrontstoreController::fncUpdateDeliveryAddressDefault($sRow2->id);

                    }
                }

            }else{
                return redirect()->action('backend\Po_approveController@index')->with(['alert' => 'Id Emty']);
            }

            \DB::commit();

            return redirect()->action('backend\Po_approveController@index')->with(['alert' => \App\Models\Alert::Msg('success')]);

        } catch (\Exception $e) {
            echo $e->getMessage();
            \DB::rollback();
            // dd($e->getMessage());
            return redirect()->action('backend\Po_approveController@index')->with(['alert' => \App\Models\Alert::e($e)]);
        }
    }


    public function form(Request $request)
    {

    }

    public function destroy($id)
    {

    }

    public function DatatableSet()
    {
        $sTable = \App\Models\Backend\Orders::search()->orderBy('id', 'asc');

        $sQuery = \DataTables::of($sTable);
        return $sQuery
            ->addColumn('price', function ($row) {
              if ($row->purchase_type_id_fk == 7) {
                return number_format($row->sum_price, 2);
            } else if ($row->purchase_type_id_fk == 5) {
                $total_price =  $row->transfer_price;
                return number_format($total_price, 2);
            } else {
                return number_format($row->sum_price + $row->shipping_price, 2);
            }
            })
            ->addColumn('type', function ($row) {
                $D = DB::table('dataset_orders_type')->where('group_id', '=', $row->purchase_type_id_fk)->get();
                return @$D[0]->orders_type;
            })
            ->addColumn('date', function ($row) {
                return date('d/m/Y H:i:s', strtotime($row->created_at));
            })
            ->make(true);
    }



    public function Datatable(Request $req)
    {
       $sPermission = \Auth::user()->permission ;
       $User_branch_id = \Auth::user()->branch_id_fk;

        if(@\Auth::user()->permission==1){

            if(!empty( $req->business_location_id_fk) ){
                $business_location_id_fk = " and db_orders.business_location_id_fk = ".$req->business_location_id_fk." " ;
            }else{
                $business_location_id_fk = "";
            }

            if(!empty( $req->branch_id_fk) ){
                $branch_id_fk = " and db_orders.branch_id_fk = ".$req->branch_id_fk." " ;
            }else{
                $branch_id_fk = "";
            }
            $action_user = "";
        }else{

            $business_location_id_fk = " and db_orders.business_location_id_fk = ".@\Auth::user()->business_location_id_fk." " ;
            $branch_id_fk = " and db_orders.branch_id_fk = ".@\Auth::user()->branch_id_fk." " ;
            $action_user = " and db_orders.action_user = ".@\Auth::user()->id." " ;

        }


        if(!empty($req->doc_id)){
            $doc_id = " and db_orders.code_order =  '".$req->doc_id."' " ;
        }else{
            $doc_id = "";
        }

        if(!empty($req->transfer_amount_approver)){
            $transfer_amount_approver = " and db_orders.transfer_amount_approver =  '".$req->transfer_amount_approver."' " ;
        }else{
            $transfer_amount_approver = "";
        }

        if(!empty($req->transfer_bill_status)){
            $transfer_bill_status = " and db_orders.transfer_bill_status =  '".$req->transfer_bill_status."' " ;
        }else{
            $transfer_bill_status = "";
        }

        if(!empty($req->bill_sdate) && !empty($req->bill_edate)){
           $created_at = " and date(db_orders.created_at) BETWEEN '".$req->bill_sdate."' AND '".$req->bill_edate."'  " ;
        }else{
           $created_at = "";
        }

        if(!empty($req->transfer_bill_approve_sdate) && !empty($req->transfer_bill_approve_edate)){
           $transfer_bill_approvedate = " and date(db_orders.transfer_bill_approvedate) BETWEEN '".$req->transfer_bill_approve_sdate."' AND '".$req->transfer_bill_approve_edate."'  " ;
        }else{
           $transfer_bill_approvedate = "";
        }

        // if(@\Auth::user()->role_group_id_fk==4){
            $branch_id_fk = "" ;
            $action_user = "" ;
        // }

        // return $transfer_bill_status;

// qry อันที่สองที่มา UNION ALL เอาไว้แสดงผลรวม

       $sTable =     DB::select("

select `db_orders`.*, `dataset_approve_status`.`txt_desc`, `dataset_approve_status`.`color`, `db_orders`.`id` as `orders_id`, `dataset_order_status`.`detail`, `dataset_order_status`.`css_class`, `dataset_orders_type`.`orders_type` as `type`, `dataset_pay_type`.`detail` as `pay_type_name`,'' as sum_approval_amount_transfer,1 as remark, `branchs`.`b_name`  from `db_orders` left join `dataset_order_status` on `dataset_order_status`.`orderstatus_id` = `db_orders`.`order_status_id_fk` left join `dataset_orders_type` on `dataset_orders_type`.`group_id` = `db_orders`.`purchase_type_id_fk` left join `dataset_pay_type` on `dataset_pay_type`.`id` = `db_orders`.`pay_type_id_fk`
left join `branchs` on `branchs`.`id` = `db_orders`.`branch_id_fk`
left join `dataset_approve_status` on `dataset_approve_status`.`id` = `db_orders`.`approve_status`
where
pay_type_id_fk in (1,8,10,11,12) and
`dataset_order_status`.`lang_id` = 1 and
(`dataset_orders_type`.`lang_id` = 1 or `dataset_orders_type`.`lang_id` IS NULL) and
`db_orders`.`id` != 0

$business_location_id_fk
$branch_id_fk
$doc_id
$transfer_amount_approver
$transfer_bill_status
$created_at
$transfer_bill_approvedate
or
pay_type_id_fk in (1,8,10,11,12) and
`dataset_order_status`.`lang_id` = 1 and
(`dataset_orders_type`.`lang_id` = 1 or `dataset_orders_type`.`lang_id` IS NULL) and
`db_orders`.`id` != 0

$business_location_id_fk
$action_user
$doc_id
$transfer_amount_approver
$transfer_bill_status
$created_at
$transfer_bill_approvedate


ORDER BY updated_at DESC


                ");

        $sQuery = \DataTables::of($sTable);
        return $sQuery
            ->addColumn('created_at', function ($row) {
              return $row->created_at .'<br>'. '('.$row->b_name.')';
            })
            ->addColumn('price', function ($row) {
                if (@$row->purchase_type_id_fk == 7) {
                    return number_format($row->sum_price, 2);
                } else if (@$row->purchase_type_id_fk == 5) {
                    $total_price = $row->transfer_price;
                    return number_format($total_price, 2);
                } else {
                    return number_format(@$row->sum_price + $row->shipping_price, 2);
                }

            })
            // ->addColumn('date', function ($row) {
            //     return date('d/m/Y H:i:s', strtotime($row->created_at));
            // })
             ->addColumn('customer_name', function($row) {
                if (!empty($row->user_id_fk)) {
                  $user = DB::table('users')->select(DB::raw('CONCAT(name, " ", last_name) as user_full_name'))->where('id', $row->user_id_fk)->first();
                  return $user->user_full_name;
                }
                if(!empty($row->customers_id_fk)){
                @$Customer = DB::select(" select * from customers where id=".@$row->customers_id_fk." ");
                return @$Customer[0]->user_name." : ".@$Customer[0]->prefix_name.@$Customer[0]->first_name." ".@$Customer[0]->last_name;
                    }
              })

             ->addColumn('note_fullpayonetime', function($row) {
                $n = '';
                $n .= $row->note_fullpayonetime_02."<br>";
                $n .= $row->note_fullpayonetime_03."<br>";
                return $row->note_fullpayonetime."<br>".$n;
              })
             ->escapeColumns('note_fullpayonetime')

             ->addColumn('transfer_money_datetime', function($row) {
                $n = '';
                $n .= !empty($row->transfer_money_datetime_02)?$row->transfer_money_datetime_02."<br>":'';
                $n .= !empty($row->transfer_money_datetime_03)?$row->transfer_money_datetime_03."<br>":'';
                return $row->transfer_money_datetime."<br>".$n;
              })
             ->escapeColumns('transfer_money_datetime')

             ->addColumn('approval_amount_transfer', function($row) {
                if(@$row->approval_amount_transfer>0){
                    return number_format($row->approval_amount_transfer,2);
                }else{
                    return "-";
                }

              })
             ->escapeColumns('approval_amount_transfer')
             ->addColumn('transfer_amount_approver', function($row) {
                if(@$row->transfer_amount_approver>0 && @$row->transfer_amount_approver!=""){

                    $sD = DB::select(" select * from ck_users_admin where id=".$row->transfer_amount_approver." ");
                    return @$sD[0]->name;

                }else{
                    return "-";
                }

              })
             ->escapeColumns('transfer_amount_approver')

            ->addColumn('transfer_bill_status', function ($row) {
                // if(!empty($row->transfer_bill_status)){

                    // if($row->transfer_bill_status==1){
                    //     return "รออนุมัติ";
                    // }else if($row->transfer_bill_status==2){
                    //     return "อนุมัติแล้ว<br>".@$row->transfer_bill_approvedate;
                    // }else if($row->transfer_bill_status==3){
                    //     return "ไม่อนุมัติ";
                    // }else{
                    //     return '-';
                    // }
                    $str = "<label style='color:".$row->color.";'>".$row->txt_desc."</label>";
                    return $str;

                // }
            })
            ->escapeColumns('transfer_bill_status')
            ->addColumn('transfer_bill_date', function ($row) {
                return DB::table('payment_slip')->where('code_order', '=', $row->code_order)->where('status', 2)->orderby('id', 'desc')->value('transfer_bill_date');
            })
            ->make(true);
    }




    public function DatatableEdit(Request $req)
    {

        if(!empty($req->id)){
            $w01 = $req->id;
            $con01 = "=";
        }else{
            $w01 = "";
            $con01 = "!=";
        }

        $sTable = DB::table('db_orders')
            ->select('db_orders.*', 'db_orders.id as orders_id', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type', 'dataset_pay_type.detail as pay_type_name')
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
            ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
            ->where('dataset_order_status.lang_id', '=', '1')
            ->where(function ($query) {
              $query->where('dataset_orders_type.lang_id', '=', '1')
                ->orWhereNull('dataset_orders_type.lang_id');
            })
            // ->where('dataset_orders_type.lang_id', '=', '1')
            // ->where('db_orders.purchase_type_id_fk', '!=', '6')
            // ->where('db_orders.order_status_id_fk', '=', '2')
            ->where('db_orders.id', $con01, $w01)
            ->get();
            // ->toSql();
        $sQuery = \DataTables::of($sTable);
        return $sQuery
            ->addColumn('price', function ($row) {
                if (@$row->purchase_type_id_fk == 7) {
                    return number_format($row->sum_price, 2);
                } else if (@$row->purchase_type_id_fk == 5) {
                    $total_price =  $total_price = $row->transfer_price;
                    return number_format($total_price, 2);
                } else {
                    return number_format(@$row->sum_price + $row->shipping_price, 2);
                }

            })
            // ->addColumn('date', function ($row) {
            //     return date('d/m/Y H:i:s', strtotime($row->created_at));
            // })
             ->addColumn('customer_name', function($row) {
                if(!empty($row->customers_id_fk)){
                @$Customer = DB::select(" select * from customers where id=".@$row->customers_id_fk." ");
                return @$Customer[0]->user_name." : ".@$Customer[0]->prefix_name.$Customer[0]->first_name." ".@$Customer[0]->last_name;
                    }
              })

             ->addColumn('note_fullpayonetime', function($row) {
                $n = '';
                $n .= $row->note_fullpayonetime_02."<br>";
                $n .= $row->note_fullpayonetime_03."<br>";
                return $row->note_fullpayonetime."<br>".$n;
              })
             ->escapeColumns('note_fullpayonetime')

             ->addColumn('transfer_money_datetime', function($row) {
                $n = '';
                $n .= !empty($row->transfer_money_datetime_02)?$row->transfer_money_datetime_02."<br>":'';
                $n .= !empty($row->transfer_money_datetime_03)?$row->transfer_money_datetime_03."<br>":'';
                return $row->transfer_money_datetime."<br>".$n;
              })
             ->escapeColumns('transfer_money_datetime')

             ->addColumn('approval_amount_transfer', function($row) {
                if(@$row->approval_amount_transfer>0){
                    return number_format($row->approval_amount_transfer,2);
                }else{
                    return "-";
                }

              })
             ->escapeColumns('approval_amount_transfer')
             ->addColumn('transfer_amount_approver', function($row) {
                if(@$row->transfer_amount_approver>0 && @$row->transfer_amount_approver!=""){

                    $sD = DB::select(" select * from ck_users_admin where id=".$row->transfer_amount_approver." ");
                    return @$sD[0]->name;

                }else{
                    return "-";
                }

              })
             ->escapeColumns('transfer_amount_approver')
            ->addColumn('transfer_bill_status', function ($row) {
                if(!empty($row->transfer_bill_status)){

                    if($row->transfer_bill_status==1){
                        return "รออนุมัติ";
                    }else if($row->transfer_bill_status==2){
                        return "อนุมัติแล้ว";
                    }else if($row->transfer_bill_status==3){
                        return "ไม่อนุมัติ";
                    }else{
                        return '-';
                    }

                }
                // return    $str = "<label style='color:".$row->color.";'>".$row->txt_desc."</label>";
            })
            ->escapeColumns('transfer_bill_status')

            ->make(true);
    }

    public function DatatableEditOther(Request $req)
    {

        if(!empty($req->id)){
            $w01 = $req->id;
            $con01 = "=";

            $order_id = DB::table('db_orders')->where('id',$req->id)->first();

        }else{
            $w01 = "";
            $con01 = "!=";
        }

        $sTable = DB::table('db_orders')
            ->select('db_orders.*', 'db_orders.id as orders_id', 'dataset_order_status.detail', 'dataset_order_status.css_class', 'dataset_orders_type.orders_type as type', 'dataset_pay_type.detail as pay_type_name')
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('dataset_orders_type', 'dataset_orders_type.group_id', '=', 'db_orders.purchase_type_id_fk')
            ->leftjoin('dataset_pay_type', 'dataset_pay_type.id', '=', 'db_orders.pay_type_id_fk')
            ->where('dataset_order_status.lang_id', '=', '1')
            ->where(function ($query) {
              $query->where('dataset_orders_type.lang_id', '=', '1')
                ->orWhereNull('dataset_orders_type.lang_id');
            })
            // ->where('dataset_orders_type.lang_id', '=', '1')
            // ->where('db_orders.purchase_type_id_fk', '!=', '6')
            // ->where('db_orders.order_status_id_fk', '=', '2')
            // ->where('db_orders.id', $con01, $w01)
            ->where('db_orders.pay_with_other_bill',1)
            ->where('db_orders.pay_with_other_bill_note','like','%'.@$order_id->code_order.'%')
            ->get();
            // ->toSql();
        $sQuery = \DataTables::of($sTable);
        return $sQuery
            ->addColumn('price', function ($row) {
                if (@$row->purchase_type_id_fk == 7) {
                    return number_format($row->sum_price, 2);
                } else if (@$row->purchase_type_id_fk == 5) {
                    $total_price =  $total_price = $row->transfer_price;
                    return number_format($total_price, 2);
                } else {
                    return number_format(@$row->sum_price + $row->shipping_price, 2);
                }

            })
            // ->addColumn('date', function ($row) {
            //     return date('d/m/Y H:i:s', strtotime($row->created_at));
            // })
             ->addColumn('customer_name', function($row) {
                if(!empty($row->customers_id_fk)){
                @$Customer = DB::select(" select * from customers where id=".@$row->customers_id_fk." ");
                return @$Customer[0]->user_name." : ".@$Customer[0]->prefix_name.$Customer[0]->first_name." ".@$Customer[0]->last_name;
                    }
              })

             ->addColumn('note_fullpayonetime', function($row) {
                $n = '';
                $n .= $row->note_fullpayonetime_02."<br>";
                $n .= $row->note_fullpayonetime_03."<br>";
                return $row->note_fullpayonetime."<br>".$n;
              })
             ->escapeColumns('note_fullpayonetime')

             ->addColumn('transfer_money_datetime', function($row) {
                $n = '';
                $n .= !empty($row->transfer_money_datetime_02)?$row->transfer_money_datetime_02."<br>":'';
                $n .= !empty($row->transfer_money_datetime_03)?$row->transfer_money_datetime_03."<br>":'';
                return $row->transfer_money_datetime."<br>".$n;
              })
             ->escapeColumns('transfer_money_datetime')

             ->addColumn('approval_amount_transfer', function($row) {
                if(@$row->approval_amount_transfer>0){
                    return number_format($row->approval_amount_transfer,2);
                }else{
                    return "-";
                }

              })
             ->escapeColumns('approval_amount_transfer')
             ->addColumn('transfer_amount_approver', function($row) {
                if(@$row->transfer_amount_approver>0 && @$row->transfer_amount_approver!=""){

                    $sD = DB::select(" select * from ck_users_admin where id=".$row->transfer_amount_approver." ");
                    return @$sD[0]->name;

                }else{
                    return "-";
                }

              })
             ->escapeColumns('transfer_amount_approver')
            ->addColumn('transfer_bill_status', function ($row) {
                if(!empty($row->transfer_bill_status)){

                    if($row->transfer_bill_status==1){
                        return "รออนุมัติ";
                    }else if($row->transfer_bill_status==2){
                        return "อนุมัติแล้ว";
                    }else if($row->transfer_bill_status==3){
                        return "ไม่อนุมัติ";
                    }else{
                        return '-';
                    }

                }
                // return    $str = "<label style='color:".$row->color.";'>".$row->txt_desc."</label>";
            })
            ->escapeColumns('transfer_bill_status')

            ->make(true);
    }


}


