<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class Po_approveController extends Controller
{

    public function index(Request $request)
    {
        $sBusiness_location = \App\Models\Backend\Business_location::get();
        $sBranchs = \App\Models\Backend\Branchs::get();
        $code_order = DB::select(" select code_order from db_orders where LENGTH(code_order)>3 order by code_order,created_at desc limit 500 ");
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

    public function edit($id)
    {
        $sRow = \App\Models\Backend\Orders::find($id);
        $slip = DB::table('payment_slip')->where('order_id', '=', $id)->orderby('id', 'asc')->get();
        // dd($slip);

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

        // dd($price);
        return view('backend.po_approve.form')->with([
            'sRow' => $sRow,
            'id' => $id,
            'slip' => $slip,
            'price' => $price,
            'note_fullpayonetime' => $sRow->note_fullpayonetime,
            'approval_amount_transfer' => $sRow->approval_amount_transfer>0?$sRow->approval_amount_transfer:"",
            'TransferBank' => $sAccount_bank,
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        \DB::beginTransaction();
        try {

            if ($id) {
                $sRow = \App\Models\Backend\Orders::find($id);
            } else {
                return redirect()->action('backend\Po_approveController@index')->with(['alert' => 'Id Emty']);
            }

            $sRow->approver = \Auth::user()->id;
            $sRow->updated_at = now();

            if (@request('approved') != null) {
                $sRow->status_slip = 'true';
                $sRow->order_status_id_fk = '5';
                $sRow->approve_status  = 2;
                $sRow->transfer_bill_status  = 2;

                for ($i=0; $i < count($request->id) ; $i++) { 
                    DB::select(" UPDATE `payment_slip` SET `code_order`='".$sRow->code_order."',`status`='2',transfer_bill_date='".$request->transfer_bill_date[$i]."' WHERE (`id`='".$request->id[$i]."') ");
                }

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
                // $sRow->transfer_bill_status  = 3;

                 if ($request->hasFile('image01')) {
                      

                      $r = DB::select(" SELECT url,file FROM `payment_slip` where `code_order`='".$sRow->code_order."' ; ");
                      @UNLINK(@$r[0]->url.@$r[0]->file);

                      DB::select(" DELETE FROM `payment_slip` WHERE `code_order`='".$sRow->code_order."'; ");

                      $this->validate($request, [
                        'image01' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                      ]);
                      $image = $request->file('image01');
                      $name = 'S2'.time() . '.' . $image->getClientOriginalExtension();
                      $image_path = 'local/public/files_slip/'.date('Ym').'/';
                      $image->move($image_path, $name);
                      $sRow->file_slip = $image_path.$name;
                      DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`,status)
                       VALUES 
                       ('".$sRow->customers_id_fk."', '$id', '".$sRow->code_order."', '$image_path', '$name', now(), now() ,1  )");
                      $lastInsertId_01 = DB::getPdo()->lastInsertId();
                    }


                $sRow->transfer_bill_status = 1;
                $sRow->status_slip = 'true';

                $sRow->approval_amount_transfer = 0 ;
                $sRow->account_bank_name_customer = 0;
                $sRow->transfer_amount_approver = 0;
                $sRow->transfer_bill_date  = NULL;
                $sRow->transfer_bill_approvedate = NULL;

                DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");

                
            }


            if (@request('approved') != null) {
                $data = \App\Models\Frontend\PvPayment::PvPayment_type_confirme($id, \Auth::user()->id, '1', 'admin');
            }


            $sRow->save();

            \DB::commit();

            return redirect()->action('backend\Po_approveController@index')->with(['alert' => \App\Models\Alert::Msg('success')]);

        } catch (\Exception $e) {
            echo $e->getMessage();
            \DB::rollback();
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
                $total_price = $row->total_price - $row->gift_voucher_price;
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

        if(!empty($req->business_location_id_fk) && $req->business_location_id_fk > 0 ){
            $business_location_id_fk = " and db_orders.business_location_id_fk =  ".$req->business_location_id_fk ;
        }else{
            $business_location_id_fk = "";
        }

        if(!empty($req->branch_id_fk) && $req->branch_id_fk > 0 ){
            $branch_id_fk = " and db_orders.branch_id_fk =  ".$req->branch_id_fk ;
        }else{
            $branch_id_fk = "";
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

        // return $transfer_bill_status;

// qry อันที่สองที่มา UNION ALL เอาไว้แสดงผลรวม 

       $sTable =     DB::select("

select `db_orders`.*, `db_orders`.`id` as `orders_id`, `dataset_order_status`.`detail`, `dataset_order_status`.`css_class`, `dataset_orders_type`.`orders_type` as `type`, `dataset_pay_type`.`detail` as `pay_type_name`,'' as sum_approval_amount_transfer,1 as remark from `db_orders` left join `dataset_order_status` on `dataset_order_status`.`orderstatus_id` = `db_orders`.`order_status_id_fk` left join `dataset_orders_type` on `dataset_orders_type`.`group_id` = `db_orders`.`purchase_type_id_fk` left join `dataset_pay_type` on `dataset_pay_type`.`id` = `db_orders`.`pay_type_id_fk` 

where 
`dataset_order_status`.`lang_id` = 1 and 
`dataset_orders_type`.`lang_id` = 1 and 
`db_orders`.`id` != 0

$business_location_id_fk
$branch_id_fk
$doc_id
$transfer_amount_approver
$transfer_bill_status
$created_at
$transfer_bill_approvedate


ORDER BY updated_at DESC


                ");

            

        $sQuery = \DataTables::of($sTable);
        return $sQuery
            ->addColumn('price', function ($row) {
                if (@$row->purchase_type_id_fk == 7) {
                    return number_format($row->sum_price, 2);
                } else if (@$row->purchase_type_id_fk == 5) {
                    $total_price = $row->total_price - $row->gift_voucher_price;
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
                        return "อนุมัติแล้ว<br>".@$row->transfer_bill_approvedate;
                    }else if($row->transfer_bill_status==3){
                        return "ไม่อนุมัติ";
                    }else{
                        return '-';
                    }

                }
            })
            ->escapeColumns('transfer_bill_status')
     
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
            ->where('dataset_orders_type.lang_id', '=', '1')
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
                    $total_price = $row->total_price - $row->gift_voucher_price;
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
            })
            ->escapeColumns('transfer_bill_status')
      
            ->make(true);
    }




}


