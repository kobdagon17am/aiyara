<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use File;
use PDF;

class Pay_product_receiptController extends Controller
{

    public function index(Request $request)
    {
      $sDelivery = \App\Models\Backend\Delivery::where('approver','NULL')->get();
      $sPacking = \App\Models\Backend\DeliveryPackingCode::where('status_delivery','<>','2')->get();
      $sBusiness_location = \App\Models\Backend\Business_location::get();
      $Customer = DB::select(" SELECT
          db_delivery.customer_id as id,
          customers.prefix_name,
          customers.first_name,
          customers.last_name,
          customers.user_name
          FROM
          db_delivery
          Left Join customers ON db_delivery.customer_id = customers.id GROUP BY db_delivery.customer_id 
           ");

      return View('backend.pay_product_receipt.index')->with(
        array(
           'sDelivery'=>$sDelivery,
           'Customer'=>$Customer,
           'sBusiness_location'=>$sBusiness_location,
           'sPacking'=>$sPacking,
        ) );

      
    }


 public function create()
    {

      $Province = DB::select(" select * from dataset_provinces ");

      $Customer = DB::select(" select * from customers ");
      return View('backend.pay_product_receipt.form')->with(
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

      }else if(isset($request->save_to_qrscan)){

        // dd($request->all());
        for ($i=0; $i < count($request->warehouse_qrcode_id) ; $i++) { 

                $value=DB::table('db_pick_warehouse_qrcode')
                // ->where('invoice_code', $request->invoice_code)
                // ->where('pick_warehouse_tmp_id_fk', $request->pick_warehouse_tmp_id_fk[$i])
                // ->where('product_id_fk', $request->product_id_fk[$i])
                // ->where('qr_code', $request->qr_code[$i])
                ->where('id', $request->warehouse_qrcode_id[$i])
                ->get();
                if($value->count() == 0){
                  // if($request->txtScan[$i]!=''){
                      DB::table('db_pick_warehouse_qrcode')->insert(array(
                        'invoice_code' => $request->invoice_code,
                        'pick_warehouse_tmp_id_fk' => $request->pick_warehouse_tmp_id_fk[$i],
                        'product_id_fk' => $request->product_id_fk[$i],
                        'qr_code' => $request->txtScan[$i],
                        'created_at' => date("Y-m-d H:i:s"),
                      ));
                    // }
                }else{
                      DB::table('db_pick_warehouse_qrcode')
                      // ->where('invoice_code', $request->invoice_code)
                      // ->where('pick_warehouse_tmp_id_fk', $request->pick_warehouse_tmp_id_fk[$i])
                      // ->where('product_id_fk', $request->product_id_fk[$i])
                      // ->where('qr_code', $request->qr_code[$i])
                      ->where('id', $request->warehouse_qrcode_id[$i])
                      ->update(array(
                        'invoice_code' => $request->invoice_code,
                        'pick_warehouse_tmp_id_fk' => $request->pick_warehouse_tmp_id_fk[$i],
                        'product_id_fk' => $request->product_id_fk[$i],
                        'qr_code' => $request->txtScan[$i],
                      ));
                }


                   DB::update(" 
                        UPDATE db_pick_warehouse_tmp SET db_pick_warehouse_tmp.cnt_this=(
                        SELECT 
                        count(*) as cnt
                        FROM
                        db_pick_warehouse_qrcode
                        WHERE
                        db_pick_warehouse_qrcode.invoice_code='".$request->invoice_code."' AND db_pick_warehouse_qrcode.product_id_fk='".$request->product_id_fk[$i]."'
                        )
                        WHERE
                        db_pick_warehouse_tmp.invoice_code='".$request->invoice_code."' AND db_pick_warehouse_tmp.product_id_fk='".$request->product_id_fk[$i]."'
                   ");


                   DB::update(" 
                        UPDATE db_pick_warehouse_tmp SET cnt_qr_code=(
                        SELECT 
                        count(*) as cnt
                        FROM
                        db_pick_warehouse_qrcode
                        WHERE invoice_code='".$request->invoice_code."' AND product_id_fk='".$request->product_id_fk[$i]."' AND qr_code is not NULL ) 
                        WHERE invoice_code='".$request->invoice_code."' AND product_id_fk='".$request->product_id_fk[$i]."'


                   ");

 
                  DB::update(" 
                        UPDATE db_pick_warehouse_tmp SET status_scan_qrcode=0 WHERE cnt_qr_code=0
                        AND invoice_code='".$request->invoice_code."' AND product_id_fk='".$request->product_id_fk[$i]."' 
                  ");

                  DB::update(" 
                        UPDATE db_pick_warehouse_tmp SET status_scan_qrcode=1 WHERE cnt_qr_code>0 AND cnt_qr_code<cnt_this
                        AND invoice_code='".$request->invoice_code."' AND product_id_fk='".$request->product_id_fk[$i]."' 
                  ");

                  DB::update(" 
                        UPDATE db_pick_warehouse_tmp SET status_scan_qrcode=2 WHERE cnt_qr_code>0 AND cnt_qr_code=cnt_this
                        AND invoice_code='".$request->invoice_code."' AND product_id_fk='".$request->product_id_fk[$i]."' 
                  ");

                  //  DB::update(" 
                  //       UPDATE
                  //       db_order_products_list
                  //       Left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
                  //       Left Join db_pick_warehouse_qrcode ON db_orders.invoice_code = db_pick_warehouse_qrcode.invoice_code AND db_order_products_list.product_id_fk = db_pick_warehouse_qrcode.product_id_fk
                  //       SET
                  //       db_order_products_list.qr_code=
                  //       db_pick_warehouse_qrcode.qr_code
                  // ");

               $rs_db_pick_warehouse_qrcode =   DB::select(" select * from db_pick_warehouse_qrcode where invoice_code='".$request->invoice_code."' AND product_id_fk='".$request->product_id_fk[$i]."'  ");
               $arr = [];
               foreach ($rs_db_pick_warehouse_qrcode as $key => $value) {
                   array_push($arr,$value->qr_code);
               }
               $imp_arr = implode(',',$arr);
               // dd($imp_arr);
                DB::update(" 
                        UPDATE
                        db_order_products_list
                        Left Join db_orders ON db_order_products_list.frontstore_id_fk = db_orders.id
                        SET
                        db_order_products_list.qr_code='$imp_arr'
                        WHERE
                        db_orders.invoice_code='".$request->invoice_code."' AND
                        db_order_products_list.product_id_fk='".$request->product_id_fk[$i]."'
                ");



        }

        return redirect()->to(url("backend/pay_product_receipt/scan_qr/".$request->invoice_code));

      }else{
        // dd($request->all());
        return $this->form();
      }
      
    }

    public function edit($id)
    {
       // $sRow = \App\Models\Backend\Pay_product_receipt::find($id);
       $sRow = \App\Models\Backend\Consignments::find($id);
       // dd($sRow);
       $Province = DB::select(" select * from dataset_provinces ");

       $Customer = DB::select(" select * from customers ");
      return View('backend.pay_product_receipt.form')->with(
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
            // $sRow = \App\Models\Backend\Pay_product_receipt::find($id);
            $sRow = \App\Models\Backend\Consignments::find($id);
          }else{
            // $sRow = new \App\Models\Backend\Pay_product_receipt;
            $sRow = new \App\Models\Backend\Consignments;
          }

          // $sRow->receipt    = request('receipt');
          // $sRow->customer_id    = request('customer_id');
          // $sRow->province_id_fk    = request('province_id_fk');
          // $sRow->pick_warehouse_date    = request('pick_warehouse_date');

  //           "mobile" => "sfsdf"
  // "sent_date" => "2021-04-19"
  // "status_sent" => "true"
                    
          $sRow->mobile = request('mobile');
          $sRow->sent_date = request('sent_date');
          $sRow->status_sent = request('status_sent')==1?1:0;
          $sRow->approver = \Auth::user()->id;
          $sRow->created_at = date('Y-m-d H:i:s');
          $sRow->save();

          \DB::commit();

          return redirect()->to(url("backend/pay_product_receipt"));

           // return redirect()->to(url("backend/pick_warehouse/".$sRow->id."/edit?role_group_id=".request('role_group_id')."&menu_id=".request('menu_id')));
           

      } catch (\Exception $e) {
        echo $e->getMessage();
        \DB::rollback();
        return redirect()->action('backend\Pay_product_receiptController@index')->with(['alert'=>\App\Models\Alert::e($e)]);
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
      // $sTable = \App\Models\Backend\Pay_product_receipt::search()->where('status_pick_warehouse','0')->orderBy('id', 'asc');
      $sTable = DB::select(" 
      	select * from db_delivery WHERE status_pack=0 and status_delivery=0 
    		UNION
    		select * from db_delivery WHERE status_pack=1 and status_delivery=0 GROUP BY packing_code
    		ORDER BY delivery_date DESC
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
        if(@$row->packing_code!=''){
             return "P".sprintf("%05d",@$row->packing_code);
        }
      })
      ->addColumn('updated_at', function($row) {
        return is_null($row->updated_at) ? '-' : $row->updated_at;
      })
      ->make(true);
    }



}
