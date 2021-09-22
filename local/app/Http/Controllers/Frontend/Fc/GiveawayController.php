<?php

namespace App\Http\Controllers\Frontend\Fc;

use App\Http\Controllers\Controller;
use App\Models\Backend\Giveaway;
use Illuminate\Support\Facades\DB;

class GiveawayController extends Controller
{
    public static function check_giveaway($type, $customer_username, $pv_total)
    { //check ของแถม

        $data_customer = DB::table('customers')
            ->where('user_name', '=', $customer_username)
            ->first();

        if (empty($data_customer)) {
            $resule = ['status' => 'fail', 'message' => 'Customers is Null'];
            return $resule;
        }

        $customer_id = $data_customer->id;
        $package_id = $data_customer->package_id;
        $qualification_id = $data_customer->qualification_id;
        $aistockist_status = $data_customer->aistockist_status;
        $agency_status = $data_customer->agency_status;

        $mt_active = \App\Helpers\Frontend::check_mt_active($customer_id);
        $tv_active = \App\Helpers\Frontend::check_tv_active($customer_id);

        $giveaway = Giveaway::where('business_location_id_fk', '=', $data_customer->business_location_id)
            ->where('status', '=', 1)
            ->wheredate('start_date', '<=', now())
            ->wheredate('end_date', '>=', now())
            ->orderby('priority')
            ->get();

        $arr = array();
        $data = array();
        $rs = array();

        if (count($giveaway) > 0) {
            $i = 0;
            $arr = array();
            $data = array();
            $rs = array();
            $set_another_pro = 0; //ทำต่อ 1 หยุด

            foreach ($giveaway as $key => $value) {

                if ($set_another_pro == 0) {
                    $i++;
                    if (!empty($value->purchase_type_id_fk) and $value->purchase_type_id_fk != $type) {
                        $arr[$i]['message'] = 'ประเภทการซื้อไม่ถูกต้อง';

                    } elseif ($value->giveaway_member_type_id_fk == 1 and $data_customer->pv > 0) {
                        $arr[$i]['message'] = 'เป็นสมาชิกที่มี PV มากกว่า 0 ไม่สามารถแถมสินค้าได้';

                    } elseif ($value->giveaway_member_type_id_fk == 2 and $data_customer->pv == 0) {
                        $arr[$i]['message'] = 'เป็นสมาชิกใหม่ PV = 0 ไม่สามารถแถมสินค้าได้ ';

                    } elseif ($pv_total < $value->pv_minimum_purchase) {
                        $arr[$i]['message'] = ' สินค้าสั่งซื้อมีค่า PV ไม่ถึง ' . $value->pv_minimum_purchase;
                    } elseif ($qualification_id < $value->reward_qualify_purchased and !empty($value->reward_qualify_purchased)) {

                        $arr[$i]['message'] = 'ไม่ผ่านคุณวุฒิ Reward ขั้นต่ำที่ซื้อได้';

                    } elseif ($package_id < $value->minimum_package_purchased and !empty($value->minimum_package_purchased)) {
                        $arr[$i]['message'] = 'ไม่ผ่าน Package ขั้นต่ำที่ซื้อได้';

                    } elseif ($value->keep_personal_quality == 1 and !empty($value->keep_personal_quality) and $mt_active['status'] == 'fail') { ///ต้องรักษาคุณสมบัตรรายเดือน
                        $arr[$i]['message'] = $mt_active['message'];
                    } elseif ($value->keep_personal_quality == 1 and !empty($value->keep_personal_quality) and $mt_active['type'] == 'N') { //ต้องรักษาคุณสมบัติรายเดือน
                        $arr[$i]['message'] = 'ต้องมีการรักษาคุณสมบัติรายเดือน';

                    } elseif ($value->keep_personal_quality == 0 and !empty($value->keep_personal_quality) and $mt_active['status'] == 'fail') { //ไม่่ต้องรักษาคุณสมบัติรายเดือน
                        $arr[$i]['message'] = $mt_active['message'];

                    } elseif ($value->keep_personal_quality == 0 and !empty($value->keep_personal_quality) and $mt_active['type'] == 'Y') { //ไม่่ต้องรักษาคุณสมบัติรายเดือน
                        $arr[$i]['message'] = 'ต้องไม่มีการรักษาคุณสมบัติรายเดือน';

                    } elseif ($value->maintain_travel_feature == 1 and !empty($value->maintain_travel_feature) and $mt_active['status'] == 'fail') {
                        ///ต้องมีการรักษาคุณสมบัติท่องเที่ยว
                        $arr[$i]['message'] = $tv_active['message'];

                    } elseif ($value->maintain_travel_feature == 1 and !empty($value->maintain_travel_feature) and $tv_active['type'] == 'N') {

                        $arr[$i]['message'] = 'ต้องมีการรักษาคุณสมบัติท่องเที่ยว';

                    } elseif ($value->maintain_travel_feature == 0 and !empty($value->maintain_travel_feature) and $tv_active['status'] == 'fail') {
                        ///ต้องมีการรักษาคุณสมบัติท่องเที่ยว
                        $arr[$i]['message'] = $tv_active['message'];

                    } elseif ($value->maintain_travel_feature == 0 and !empty($value->maintain_travel_feature) and $tv_active['type'] == 'Y') {
                        $arr[$i]['message'] = 'ต้องไม่มีการรักษาคุณสมบัติท่องเที่ยว';

                    } elseif ($value->aistockist == 1 and !empty($value->aistockist) and $aistockist_status == 0) { //เป็น aistockist
                        $arr[$i]['message'] = 'ต้องเป็น Ai-Stockist ';

                    } elseif ($value->aistockist == 0 and !empty($value->aistockist) and $aistockist_status == 1) { //ต้องไม่เป็น aistockist

                        $arr[$i]['message'] = 'ต้องไม่เป็น Ai-Stockist';

                    } elseif ($value->agency == 1 and !empty($value->agency) and $agency_status == 0) { //เป็น aistockist
                        $arr[$i]['message'] = 'ต้องเป็น Agency';

                    } elseif ($value->agency == 0 and !empty($value->agency) and $agency_status == 1) { //ต้องไม่เป็น aistockist
                        $arr[$i]['message'] = 'ต้องไม่เป็น Agency';

                    } else {
                        if ($value->giveaway_in_bill_id_fk == 2) { //กรณีแถมสินค้าได้หลายครั้งต่อบิล
                            $count_free = floor($pv_total / $value->pv_minimum_purchase);
                        } else {
                            $count_free = 1;
                        }

                        $data[$i]['giveaway_id'] = $value->id;
                        $data[$i]['name'] = $value->giveaway_name;
                        $data[$i]['type'] = $value->giveaway_option_id_fk; //1 product 2 gv

                        if ($value->giveaway_option_id_fk == 1) {

                            $product = DB::table('db_giveaway_products')
                                ->select('products_details.product_id_fk', 'products_details.product_name', 'dataset_product_unit.product_unit', 'dataset_product_unit.group_id as unit_id', 'db_giveaway_products.product_amt')
                                ->leftJoin('products_details', 'products_details.product_id_fk', '=', 'db_giveaway_products.product_id_fk')
                                ->leftJoin('dataset_product_unit', 'dataset_product_unit.group_id', '=', 'db_giveaway_products.product_unit')
                                ->where('db_giveaway_products.giveaway_id_fk', '=', $value->id)
                                ->where('products_details.lang_id', '=', $data_customer->business_location_id)
                                ->where('dataset_product_unit.lang_id', '=', $data_customer->business_location_id)
                                ->get();
                            //dd($product);
                            $data[$i]['product'] = $product;
                            $data[$i]['gv'] = '';

                        } else {
                            $data[$i]['product'] = '';
                            $data[$i]['gv'] = $value->giveaway_voucher;
                        }

                        $data[$i]['count_free'] = $count_free;

                    }

                    if (@$arr[$i]) {
                        $rs[] = ['status' => 'fail', 'name' => $value->giveaway_name, 'gv_id' => $value->id, 'rs' => $arr[$i]];
                    } else {
                        $rs[] = ['status' => 'success', 'name' => $value->giveaway_name, 'gv_id' => $value->id, 'rs' => $data[$i]];
                    }
                }

                if ($value->another_pro == 1) {
                    $set_another_pro = 1;
                }
            }

        } else {

            $resule = ['status' => 'fail', 'message' => 'ไม่มีรายการของแถม'];
            return $resule;
        }
        //dd($resule);

        return $rs;
    }

    public static function check_giveaway_all($type, $customer_username, $pv_total)
    { //ประเภทการซื้อ ,customer_username,pv order

        $data_customer = DB::table('customers')
            ->where('user_name', '=', $customer_username)
            ->first();

        if (empty($data_customer)) {
            $resule = ['status' => 'fail', 'message' => 'Customers is Null'];
            return $resule;
        }

        $customer_id = $data_customer->id;
        $package_id = $data_customer->package_id;
        $qualification_id = $data_customer->qualification_id;
        $aistockist_status = $data_customer->aistockist_status;
        $agency_status = $data_customer->agency_status;

        $mt_active = \App\Helpers\Frontend::check_mt_active($customer_id);
        $tv_active = \App\Helpers\Frontend::check_tv_active($customer_id);

        $giveaway = Giveaway::where('business_location_id_fk', '=', $data_customer->business_location_id)
            ->where('status', '=', 1)
            ->wheredate('start_date', '<=', now())
            ->wheredate('end_date', '>=', now())
            ->get();

        if (count($giveaway) > 0) {

            $i = 0;
            $arr = array();
            $data = array();
            $rs = array();
            $set_another_pro = 0; //ทำต่อ 1 หยุด

            foreach ($giveaway as $key => $value) {

                if ($set_another_pro == 0) {
                    $i++;

                    if (!empty($value->purchase_type_id_fk) and $value->purchase_type_id_fk != $type) {

                        $arr[$i][] = 'ประเภทการซื้อไม่ถูกต้อง';

                    }

                    if ($value->giveaway_member_type_id_fk == 1 and $data_customer->pv > 0) {
                        $arr[$i][] = 'เป็นสมาชิกที่มี PV มากกว่า 0 ไม่สามารถแถมสินค้าได้';

                    }

                    if ($value->giveaway_member_type_id_fk == 2 and $data_customer->pv == 0) {
                        $arr[$i][] = 'เป็นสมาชิกใหม่ PV = 0 ไม่สามารถแถมสินค้าได้ ';

                    }

                    if ($pv_total < $value->pv_minimum_purchase) {
                        $arr[$i][] = '  สินค้าสั่งซื้อมีค่า PV ไม่ถึง ' . $value->pv_minimum_purchase;
                    }

                    if ($qualification_id < $value->reward_qualify_purchased and !empty($value->reward_qualify_purchased)) {

                        $arr[$i][] = 'ไม่ผ่านคุณวุฒิ Reward ขั้นต่ำที่ซื้อได้';

                    }

                    if ($package_id < $value->minimum_package_purchased and !empty($value->minimum_package_purchased)) {
                        $arr[$i][] = 'ไม่ผ่าน Package ขั้นต่ำที่ซื้อได้';
                    }

                    if ($value->keep_personal_quality == 1 and !empty($value->keep_personal_quality) and $mt_active['status'] == 'fail') { ///ต้องรักษาคุณสมบัตรรายเดือน
                        $arr[$i][] = $mt_active['message'];
                    }

                    if ($value->keep_personal_quality == 1 and !empty($value->keep_personal_quality) and $mt_active['type'] == 'N') { //ต้องรักษาคุณสมบัติรายเดือน
                        $arr[$i][] = 'ต้องมีการรักษาคุณสมบัติรายเดือน';
                    }

                    if ($value->keep_personal_quality == 0 and !empty($value->keep_personal_quality) and $mt_active['status'] == 'fail') { //ไม่่ต้องรักษาคุณสมบัติรายเดือน
                        $arr[$i][] = $mt_active['message'];
                    }

                    if ($value->keep_personal_quality == 0 and !empty($value->keep_personal_quality) and $mt_active['type'] == 'Y') { //ไม่่ต้องรักษาคุณสมบัติรายเดือน
                        $arr[$i][] = 'ต้องไม่มีการรักษาคุณสมบัติรายเดือน';
                    }

                    if ($value->maintain_travel_feature == 1 and !empty($value->maintain_travel_feature) and $mt_active['status'] == 'fail') {
                        ///ต้องมีการรักษาคุณสมบัติท่องเที่ยว
                        $arr[$i][] = $tv_active['message'];
                    }

                    if ($value->maintain_travel_feature == 1 and !empty($value->maintain_travel_feature) and $tv_active['type'] == 'N') {
                        $arr[$i][] = 'ต้องมีการรักษาคุณสมบัติท่องเที่ยว';
                    }

                    if ($value->maintain_travel_feature == 0 and !empty($value->maintain_travel_feature) and $tv_active['status'] == 'fail') {
                        ///ต้องมีการรักษาคุณสมบัติท่องเที่ยว
                        $arr[$i][] = $tv_active['message'];

                    }

                    if ($value->maintain_travel_feature == 0 and !empty($value->maintain_travel_feature) and $tv_active['type'] == 'Y') {
                        $arr[$i][] = 'ต้องไม่มีการรักษาคุณสมบัติท่องเที่ยว';

                    }

                    if ($value->aistockist == 1 and !empty($value->aistockist) and $aistockist_status == 0) { //เป็น aistockist
                        $arr[$i][] = 'ต้องเป็น Ai-Stockist ';

                    }

                    if ($value->aistockist == 0 and !empty($value->aistockist) and $aistockist_status == 1) { //ต้องไม่เป็น aistockist

                        $arr[$i][] = 'ต้องไม่เป็น Ai-Stockist';

                    }

                    if ($value->agency == 1 and !empty($value->agency) and $agency_status == 0) { //เป็น aistockist
                        $arr[$i][] = 'ต้องเป็น Agency';

                    }

                    if ($value->agency == 0 and !empty($value->agency) and $agency_status == 1) { //ต้องไม่เป็น aistockist
                        $arr[$i][] = 'ต้องไม่เป็น Agency';

                    }

                    if (@$arr[$i]) {
                        $rs[] = ['status' => 'fail', 'name' => $value->giveaway_name, 'gv_id' => $value->id, 'rs' => $arr[$i]];
                    } else {
                        $rs[] = ['status' => 'success', 'name' => $value->giveaway_name, 'gv_id' => $value->id, 'rs' => 'ได้รับรายการของแถม'];
                    }

                }

                if ($value->another_pro == 1) {
                    $set_another_pro = 1;
                }

            }

        } else {
            $resule = ['status' => 'fail', 'message' => 'ยังไม่มีโปรโมชั่นแถม'];
            return $resule;
        }

        return $rs;
    }

}
