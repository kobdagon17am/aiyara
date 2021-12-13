<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class Po_approveController extends Controller
{

    public function index(Request $request)
    {
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

    public function edit($id)
    {
        $sRow = \App\Models\Backend\Orders::find($id);
        // $slip = DB::table('payment_slip')->where('order_id', '=', $id)->orderby('id', 'asc')->get();
        $slip = DB::table('payment_slip')->where('code_order', '=', $sRow->code_order)->orderby('id', 'asc')->get();
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

                // return $request->slip_ids;

                if(!empty($request->slip_ids)){

                    for ($i=0; $i < count($request->slip_ids) ; $i++) {
                        DB::select(" UPDATE `payment_slip` SET `code_order`='".$sRow->code_order."',`status`='2',transfer_bill_date='".$request->transfer_bill_date[$i]."' WHERE (`id`='".$request->slip_ids[$i]."') ");
                    }

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

                 if ($request->hasFile('image01')) {


                  $r = DB::select(" SELECT url,file FROM `payment_slip` where `code_order`='".$sRow->code_order."' ; ");
                  @UNLINK(@$r[0]->url.@$r[0]->file);

                  DB::select(" DELETE FROM `payment_slip` WHERE `code_order`='".$sRow->code_order."'; ");

                  $this->validate($request, [
                    'image01' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
                  ]);
                  $image = $request->file('image01');
                  $name = 'S2'.time() . '.' . $image->getClientOriginalExtension();
                  $image_path = 'local/public/files_slip/'.date('Ym').'/';
                  $image->move($image_path, $name);
                  $sRow->file_slip = $image_path.$name;
                  DB::select(" INSERT INTO `payment_slip` (`customer_id`, `order_id`, `code_order`, `url`, `file`, `create_at`, `update_at`,status)
                   VALUES
                   ('".$sRow->customers_id_fk."', '$id', '".$sRow->code_order."', '$image_path', '$name', now(), now() ,1  )");

                }


                $sRow->transfer_bill_status = 1;
                $sRow->status_slip = 'true';

                $sRow->approval_amount_transfer = 0 ;
                $sRow->account_bank_name_customer = 0;
                $sRow->transfer_amount_approver = 0;
                $sRow->transfer_bill_date  = NULL;
                $sRow->transfer_bill_approvedate = NULL;
                $sRow->transfer_bill_note = @request('detail');

                DB::select(" UPDATE db_orders set approve_status=0 WHERE check_press_save=0; ");


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
                $this->fncUpdateDeliveryAddress($sRow->id);
                $this->fncUpdateDeliveryAddressDefault($sRow->id);
            }

            \DB::commit();

            return redirect()->action('backend\Po_approveController@index')->with(['alert' => \App\Models\Alert::Msg('success')]);

        } catch (\Exception $e) {
            echo $e->getMessage();
            \DB::rollback();
            return redirect()->action('backend\Po_approveController@index')->with(['alert' => \App\Models\Alert::e($e)]);
        }
    }

    public function fncUpdateDeliveryAddress($id)
    {
              // dd($id);
              $sRow = \App\Models\Backend\Frontstore::find($id);
              // dd($sRow);
              // dd($sRow->delivery_location);
              if(@$sRow->delivery_location==0){
                DB::select(" UPDATE `db_orders` SET invoice_code=code_order WHERE (`id`=".$sRow->id.") ");
                DB::select(" DELETE FROM `db_delivery` WHERE (`orders_id_fk`=".$sRow->id.") ");
              }

                // วุฒิปรับ approve_status > 1
              // if($sRow->check_press_save==2 && $sRow->approve_status>0 && $sRow->id!='' && @$sRow->delivery_location>0 ){
                if($sRow->check_press_save==2 && $sRow->approve_status>1 && $sRow->id!='' && @$sRow->delivery_location>0 ){

                       DB::select("
                        INSERT IGNORE INTO db_delivery
                        ( orders_id_fk,receipt, customer_id, business_location_id,branch_id_fk , delivery_date, billing_employee, created_at,list_type,shipping_price,total_price)
                        SELECT id,code_order,customers_id_fk,business_location_id_fk,branch_id_fk,created_at,action_user,now(),2,shipping_price,
                        (SUM(
                        (CASE WHEN db_orders.credit_price is null THEN 0 ELSE db_orders.credit_price END) +
                        (CASE WHEN db_orders.transfer_price is null THEN 0 ELSE db_orders.transfer_price END) +
                        (CASE WHEN db_orders.fee_amt is null THEN 0 ELSE db_orders.fee_amt END) +
                        (CASE WHEN db_orders.aicash_price is null THEN 0 ELSE db_orders.aicash_price END) +
                        (CASE WHEN db_orders.cash_pay is null THEN 0 ELSE db_orders.cash_pay END) +
                        (CASE WHEN db_orders.gift_voucher_price is null THEN 0 ELSE db_orders.gift_voucher_price END)
                        ))
                        FROM db_orders WHERE (`id`=".$sRow->id.") AND delivery_location <> 0 ;
                      ");


// Clear ก่อน ค่อย อัพเดต ใส่ตามเงื่อนไขทีหลัง
                      DB::select(" UPDATE db_delivery
                          SET
                          recipient_name = '',
                          addr_send = '',
                          postcode = '',
                          mobile = '',
                          tel_home = '',
                          province_id_fk = '',
                          province_name = '',
                          shipping_price = '".$sRow->shipping_price."',
                          delivery_date = now() ,
                          set_addr_send_this = '0'
                          where orders_id_fk = '".$sRow->id."'

                         ");

                      //delivery_location = ที่อยู่ผู้รับ>0=รับสินค้าด้วยตัวเอง|1=ที่อยู่ตามบัตร ปชช.>customers_address_card|2=ที่อยู่จัดส่งไปรษณีย์หรือที่อยู่ตามที่ลงทะเบียนไว้ในระบบ>customers_detail|3=ที่อยู่กำหนดเอง>customers_addr_frontstore|4=จัดส่งพร้อมบิลอื่น|5=ส่งแบบพิเศษ/พรีเมี่ยม

                      if(@$sRow->delivery_location==1){

                          $addr = DB::select(" SELECT
                                      customers_address_card.id,
                                      customers_address_card.customer_id,
                                      customers_address_card.card_house_no,
                                      customers_address_card.card_house_name,
                                      customers_address_card.card_moo,
                                      customers_address_card.card_zipcode,
                                      customers_address_card.card_soi,
                                      customers_address_card.created_at,
                                      customers_address_card.updated_at,
                                      customers_address_card.card_province_id_fk,
                                      customers_address_card.tel,
                                      customers_address_card.tel_home,
                                      dataset_provinces.name_th AS provname,
                                      dataset_provinces.id AS province_id,
                                      dataset_amphures.id AS amp_id,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_districts.name_th AS tamname,
                                      dataset_districts.id AS tam_id,
                                      customers.prefix_name,
                                      customers.first_name,
                                      customers.last_name
                                      FROM
                                      customers_address_card
                                      Left Join dataset_provinces ON customers_address_card.card_province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_address_card.card_amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_address_card.card_district_id_fk = dataset_districts.id
                                      Left Join customers ON customers_address_card.customer_id = customers.id
                                      where customers_address_card.customer_id = ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)."

                            ");

                            if(@$addr){


                                        foreach ($addr as $key => $v) {

                                          @$address = @$v->card_house_no." ". @$v->card_house_name." ". @$v->card_moo."";
                                          @$address .= @$v->card_soi." ". @$v->card_road;
                                          @$address .= ", ต.". @$v->tamname. " ";
                                          @$address .= ", อ.". @$v->ampname;
                                          @$address .= ", จ.". @$v->provname;

                                          @$recipient_name = @$v->prefix_name.@$v->first_name.' '.@$v->last_name;

                                          if(!empty(@$v->tamname) && !empty(@$v->ampname) && !empty(@$v->provname)){
                                          }else{
                                              @$address = null;
                                          }

                                            DB::select(" UPDATE db_delivery
                                            SET
                                            recipient_name = '".@$recipient_name."',
                                            addr_send = '".@$address."',
                                            postcode = '".@$v->card_zipcode."',
                                            province_id_fk = '".@$v->card_province_id_fk."',
                                            province_name = '".@$v->province_name."',
                                            set_addr_send_this = '1'
                                            where orders_id_fk = '".$sRow->id."'

                                           ");
                                        }


                            DB::select("

                              UPDATE db_orders SET
                              house_no='".@$v->card_house_no."',
                              house_name='".@$v->card_house_name."',
                              moo='".@$v->card_moo."',
                              soi='".@$v->card_soi."',
                              road='".@$v->card_road."',
                              amphures_id_fk='".(@$v->amp_id?@$v->amp_id:0)."',
                              district_id_fk='".(@$v->tam_id?@$v->tam_id:0)."',
                              province_id_fk='".(@$v->province_id?@$v->province_id:0)."',
                              zipcode='".@$v->card_zipcode."',
                              tel='".@$v->tel."',
                              tel_home='".@$v->tel_home."',
                              name='".@$recipient_name."'
                              WHERE (id='".$id."')");


                          }
                      }



                      if(@$sRow->delivery_location==2){

                          $addr = DB::select("
                            SELECT
                                      customers_detail.customer_id,
                                      customers_detail.house_no,
                                      customers_detail.house_name,
                                      customers_detail.moo,
                                      customers_detail.zipcode,
                                      customers_detail.soi,
                                      customers_detail.road,
                                      customers_detail.province_id_fk,
                                      customers_detail.tel_mobile,
                                      customers_detail.tel_home,
                                      customers.prefix_name,
                                      customers.first_name,
                                      customers.last_name,
                                      dataset_provinces.name_th AS provname,
                                      dataset_provinces.id AS province_id,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_amphures.id AS amp_id,
                                      dataset_districts.name_th AS tamname,
                                      dataset_districts.id AS tam_id
                                      FROM
                                      customers_detail
                                      Left Join customers ON customers_detail.customer_id = customers.id
                                      Left Join dataset_provinces ON customers_detail.province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_detail.amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_detail.district_id_fk = dataset_districts.id
                                      WHERE customers_detail.customer_id = ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)."

                               ");

                           if(@$addr){
                              foreach ($addr as $key => $v) {

                                  @$address = @$v->house_no." ". @$v->house_name." ". @$v->moo." ". @$v->soi." ". @$v->road." ";
                                  @$address .= ", ต.". @$v->tamname. " ";
                                  @$address .= ", อ.". @$v->ampname;
                                  @$address .= ", จ.". @$v->provname;

                                  if(!empty(@$v->tamname) && !empty(@$v->ampname) && !empty(@$v->provname)){
                                  }else{
                                      @$address = null;
                                  }

                                  @$recipient_name = @$v->prefix_name.@$v->first_name.' '.@$v->last_name;

                                  DB::select(" UPDATE db_delivery
                                  SET
                                  recipient_name = '".@$recipient_name."',
                                  addr_send = '".@$address."',
                                  postcode = '".@$v->zipcode."',
                                  mobile = '".(@$v->tel?$v->tel:'')."',
                                  tel_home = '".(@$v->tel_home?$v->tel_home:'')."',
                                  province_id_fk = '".@$v->province_id_fk."',
                                  province_name = '".@$v->provname."',
                                  set_addr_send_this = '1'
                                  where orders_id_fk = '".$sRow->id."'

                                 ");
                              }


                              DB::select("

                              UPDATE db_orders SET
                              house_no='".@$v->house_no."',
                              house_name='".@$v->house_name."',
                              moo='".@$v->moo."',
                              soi='".@$v->soi."',
                              road='".@$v->road."',
                              amphures_id_fk='".(@$v->amp_id?@$v->amp_id:0)."',
                              district_id_fk='".(@$v->tam_id?@$v->tam_id:0)."',
                              province_id_fk='".(@$v->province_id?@$v->province_id:0)."',
                              zipcode='".@$v->zipcode."',
                              tel='".@$v->tel."',
                              tel_home='".@$v->tel_home."',
                              name='".@$recipient_name."'
                              WHERE (id='".$id."')");


                          }
                      }



                      if(@$sRow->delivery_location==3){

                          $addr = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                            dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname,dataset_provinces.id as province_id_fk
                            from customers_addr_frontstore
                            Left Join dataset_provinces ON customers_addr_frontstore.province_id_fk = dataset_provinces.id
                            Left Join dataset_amphures ON customers_addr_frontstore.amphur_code = dataset_amphures.id
                            Left Join dataset_districts ON customers_addr_frontstore.tambon_code = dataset_districts.id
                            WHERE
                            frontstore_id_fk in (".@$sRow->id.") ;");

                           if(@$addr){
                              foreach ($addr as $key => $v) {

                                  @$address = @$v->addr_no;
                                  @$address .= ", ต.". @$v->tamname. " ";
                                  @$address .= ", อ.". @$v->ampname;
                                  @$address .= ", จ.". @$v->provname;


                                  DB::select(" UPDATE db_delivery
                                  SET
                                  recipient_name = '".@$v->recipient_name."',
                                  addr_send = '".@$address."',
                                  postcode = '".@$v->zip_code."',
                                  mobile = '".(@$v->tel?$v->tel:'')."',
                                  tel_home = '".(@$v->tel_home?$v->tel_home:'')."',
                                  province_id_fk = '".@$v->province_id_fk."',
                                  province_name = '".@$v->provname."',
                                  set_addr_send_this = '1'
                                  where orders_id_fk = '".$sRow->id."'

                                 ");
                              }

                             DB::select("

                              UPDATE db_orders SET
                              house_no='".@$v->addr_no."',
                              amphures_id_fk='".(@$v->amphur_code?@$v->amphur_code:0)."',
                              district_id_fk='".(@$v->tambon_code?@$v->tambon_code:0)."',
                              province_id_fk='".(@$v->province_id_fk?@$v->province_id_fk:0)."',
                              zipcode='".@$v->zip_code."',
                              tel='".@$v->tel."',
                              tel_home='".@$v->tel_home."',
                              name='".@$v->recipient_name."'
                              WHERE (id='".$id."')");

                      }
                   }

                     // $this->fncUpdateDeliveryAddressDefault($id);

              }

    }



// กรณี เลือก จัดส่งพร้อมบิลอื่น หรือ รับสินค้าด้วยตัวเอง ให้เช็คดูว่า มี ที่อยู่จัดส่ง ปณ. หรือไม่ ถ้ามี เซ็ตเป็นดีฟอลท์ ถ้าไม่มี เช็คต่อ ที่อยู่ตามบัตร ปชช. เช็คต่ออีก ที่อยู่กำหนดเอง ถ้าไม่มีทั้ง 3 แจ้งว่า ไม่ได้ลงทะเบียนที่อยู่ไว้

    public function fncUpdateDeliveryAddressDefault($id)
    {
              // dd($id);

              $ch = DB::select("

                SELECT  * FROM db_orders
                WHERE id=$id and amphures_id_fk is null and district_id_fk is null and province_id_fk is null

              ");


              if(!empty($ch)){

                      //delivery_location = ที่อยู่ผู้รับ>0=รับสินค้าด้วยตัวเอง|1=ที่อยู่ตามบัตร ปชช.>customers_address_card|2=ที่อยู่จัดส่งไปรษณีย์หรือที่อยู่ตามที่ลงทะเบียนไว้ในระบบ>customers_detail|3=ที่อยู่กำหนดเอง>customers_addr_frontstore|4=จัดส่งพร้อมบิลอื่น|5=ส่งแบบพิเศษ/พรีเมี่ยม

                          $sRow = \App\Models\Backend\Frontstore::find($id);

                          $delivery_location_01 = DB::select(" SELECT
                                      customers_address_card.id,
                                      customers_address_card.customer_id,
                                      customers_address_card.card_house_no,
                                      customers_address_card.card_house_name,
                                      customers_address_card.card_moo,
                                      customers_address_card.card_zipcode,
                                      customers_address_card.card_soi,
                                      customers_address_card.created_at,
                                      customers_address_card.updated_at,
                                      customers_address_card.card_province_id_fk,
                                      customers_address_card.tel,
                                      customers_address_card.tel_home,
                                      dataset_provinces.name_th AS provname,
                                      dataset_provinces.id AS province_id,
                                      dataset_amphures.id AS amp_id,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_districts.name_th AS tamname,
                                      dataset_districts.id AS tam_id,
                                      customers.prefix_name,
                                      customers.first_name,
                                      customers.last_name
                                      FROM
                                      customers_address_card
                                      Left Join dataset_provinces ON customers_address_card.card_province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_address_card.card_amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_address_card.card_district_id_fk = dataset_districts.id
                                      Left Join customers ON customers_address_card.customer_id = customers.id
                                      where customers_address_card.customer_id = ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)."

                            ");

                            if(!empty($delivery_location_01)){


                                        foreach ($delivery_location_01 as $key => $v) {

                                          @$address = @$v->card_house_no." ". @$v->card_house_name." ". @$v->card_moo."";
                                          @$address .= @$v->card_soi." ". @$v->card_road;
                                          @$address .= ", ต.". @$v->tamname. " ";
                                          @$address .= ", อ.". @$v->ampname;
                                          @$address .= ", จ.". @$v->provname;

                                          @$recipient_name = @$v->prefix_name.@$v->first_name.' '.@$v->last_name;

                                          if(!empty(@$v->tamname) && !empty(@$v->ampname) && !empty(@$v->provname)){
                                          }else{
                                              @$address = null;
                                          }

                                          DB::select(" UPDATE db_delivery
                                          SET
                                          recipient_name = '".@$recipient_name."',
                                          addr_send = '".@$address."',
                                          postcode = '".@$v->card_zipcode."',
                                          province_id_fk = '".@$v->card_province_id_fk."',
                                          province_name = '".@$v->province_name."',
                                          set_addr_send_this = '1'
                                          where orders_id_fk = '".$sRow->id."'

                                         ");


                                          DB::select("

                                            UPDATE db_orders SET
                                            house_no='".@$v->card_house_no."',
                                            house_name='".@$v->card_house_name."',
                                            moo='".@$v->card_moo."',
                                            soi='".@$v->card_soi."',
                                            road='".@$v->card_road."',
                                            amphures_id_fk='".(@$v->amp_id?@$v->amp_id:0)."',
                                            district_id_fk='".(@$v->tam_id?@$v->tam_id:0)."',
                                            province_id_fk='".(@$v->province_id?@$v->province_id:0)."',
                                            zipcode='".@$v->card_zipcode."',
                                            tel='".@$v->tel."',
                                            tel_home='".@$v->tel_home."',
                                            name='".@$recipient_name."'
                                            WHERE (id='".$id."')");

                                        }
                          }




                          $delivery_location_02 = DB::select("
                            SELECT
                                      customers_detail.customer_id,
                                      customers_detail.house_no,
                                      customers_detail.house_name,
                                      customers_detail.moo,
                                      customers_detail.zipcode,
                                      customers_detail.soi,
                                      customers_detail.road,
                                      customers_detail.province_id_fk,
                                      customers_detail.tel_mobile,
                                      customers_detail.tel_home,
                                      customers.prefix_name,
                                      customers.first_name,
                                      customers.last_name,
                                      dataset_provinces.name_th AS provname,
                                      dataset_provinces.id AS province_id,
                                      dataset_amphures.name_th AS ampname,
                                      dataset_amphures.id AS amp_id,
                                      dataset_districts.name_th AS tamname,
                                      dataset_districts.id AS tam_id
                                      FROM
                                      customers_detail
                                      Left Join customers ON customers_detail.customer_id = customers.id
                                      Left Join dataset_provinces ON customers_detail.province_id_fk = dataset_provinces.id
                                      Left Join dataset_amphures ON customers_detail.amphures_id_fk = dataset_amphures.id
                                      Left Join dataset_districts ON customers_detail.district_id_fk = dataset_districts.id
                                      WHERE customers_detail.customer_id = ".(@$sRow->customers_id_fk?@$sRow->customers_id_fk:0)."

                               ");

                           if(@$delivery_location_02){
                              foreach ($delivery_location_02 as $key => $v) {

                                  @$address = @$v->house_no." ". @$v->house_name." ". @$v->moo." ". @$v->soi." ". @$v->road." ";
                                  @$address .= ", ต.". @$v->tamname. " ";
                                  @$address .= ", อ.". @$v->ampname;
                                  @$address .= ", จ.". @$v->provname;

                                  if(!empty(@$v->tamname) && !empty(@$v->ampname) && !empty(@$v->provname)){
                                  }else{
                                      @$address = null;
                                  }

                                  @$recipient_name = @$v->prefix_name.@$v->first_name.' '.@$v->last_name;

                                  DB::select(" UPDATE db_delivery
                                  SET
                                  recipient_name = '".@$recipient_name."',
                                  addr_send = '".@$address."',
                                  postcode = '".@$v->zipcode."',
                                  mobile = '".(@$v->tel?$v->tel:'')."',
                                  tel_home = '".(@$v->tel_home?$v->tel_home:'')."',
                                  province_id_fk = '".@$v->province_id_fk."',
                                  province_name = '".@$v->provname."',
                                  set_addr_send_this = '1'
                                  where orders_id_fk = '".$sRow->id."'

                                 ");


                                  DB::select("

                                  UPDATE db_orders SET
                                  house_no='".@$v->house_no."',
                                  house_name='".@$v->house_name."',
                                  moo='".@$v->moo."',
                                  soi='".@$v->soi."',
                                  road='".@$v->road."',
                                  amphures_id_fk='".(@$v->amp_id?@$v->amp_id:0)."',
                                  district_id_fk='".(@$v->tam_id?@$v->tam_id:0)."',
                                  province_id_fk='".(@$v->province_id?@$v->province_id:0)."',
                                  zipcode='".@$v->zipcode."',
                                  tel='".@$v->tel."',
                                  tel_home='".@$v->tel_home."',
                                  name='".@$recipient_name."'
                                  WHERE (id='".$id."')");


                          }

                      }


                          $delivery_location_03 = DB::select("select customers_addr_frontstore.* ,dataset_provinces.name_th as provname,
                            dataset_amphures.name_th as ampname,dataset_districts.name_th as tamname,dataset_provinces.id as province_id_fk
                            from customers_addr_frontstore
                            Left Join dataset_provinces ON customers_addr_frontstore.province_id_fk = dataset_provinces.id
                            Left Join dataset_amphures ON customers_addr_frontstore.amphur_code = dataset_amphures.id
                            Left Join dataset_districts ON customers_addr_frontstore.tambon_code = dataset_districts.id
                            WHERE
                            frontstore_id_fk in (".@$sRow->id.") ;");

                           if(@$delivery_location_03){
                              foreach ($delivery_location_03 as $key => $v) {

                                  @$address = @$v->addr_no;
                                  @$address .= ", ต.". @$v->tamname. " ";
                                  @$address .= ", อ.". @$v->ampname;
                                  @$address .= ", จ.". @$v->provname;


                                  DB::select(" UPDATE db_delivery
                                  SET
                                  recipient_name = '".@$v->recipient_name."',
                                  addr_send = '".@$address."',
                                  postcode = '".@$v->zip_code."',
                                  mobile = '".(@$v->tel?$v->tel:'')."',
                                  tel_home = '".(@$v->tel_home?$v->tel_home:'')."',
                                  province_id_fk = '".@$v->province_id_fk."',
                                  province_name = '".@$v->provname."',
                                  set_addr_send_this = '1'
                                  where orders_id_fk = '".$sRow->id."'

                                 ");

                                 DB::select("

                                  UPDATE db_orders SET
                                  house_no='".@$v->addr_no."',
                                  amphures_id_fk='".(@$v->amphur_code?@$v->amphur_code:0)."',
                                  district_id_fk='".(@$v->tambon_code?@$v->tambon_code:0)."',
                                  province_id_fk='".(@$v->province_id_fk?@$v->province_id_fk:0)."',
                                  zipcode='".@$v->zip_code."',
                                  tel='".@$v->tel."',
                                  tel_home='".@$v->tel_home."',
                                  name='".@$v->recipient_name."'
                                  WHERE (id='".$id."')");

                          }
                      }


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

        // return $transfer_bill_status;

// qry อันที่สองที่มา UNION ALL เอาไว้แสดงผลรวม

       $sTable =     DB::select("

select `db_orders`.*, `dataset_approve_status`.`txt_desc`, `db_orders`.`id` as `orders_id`, `dataset_order_status`.`detail`, `dataset_order_status`.`css_class`, `dataset_orders_type`.`orders_type` as `type`, `dataset_pay_type`.`detail` as `pay_type_name`,'' as sum_approval_amount_transfer,1 as remark, `branchs`.`b_name`  from `db_orders` left join `dataset_order_status` on `dataset_order_status`.`orderstatus_id` = `db_orders`.`order_status_id_fk` left join `dataset_orders_type` on `dataset_orders_type`.`group_id` = `db_orders`.`purchase_type_id_fk` left join `dataset_pay_type` on `dataset_pay_type`.`id` = `db_orders`.`pay_type_id_fk`
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
                    return $row->txt_desc;

                // }
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


