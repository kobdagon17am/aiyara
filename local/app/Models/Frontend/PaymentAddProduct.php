<?php
namespace App\Models\Frontend;

use App\Http\Controllers\Frontend\Fc\GiveawayController;
use App\Models\DbOrderProductsList;
use Auth;
use Cart;
use DB;
use Illuminate\Database\Eloquent\Model;

//use App\Models\Backend\PromotionCus;
class PaymentAddProduct extends Model
{
    public static function payment_add_product($order_id, $customer_id, $type, $business_location_id = '', $pv_total = '', $code_order, $customer_username)
    {

        DB::BeginTransaction();
        try {

            $cartCollection = Cart::session($type)->getContent();
            $data = $cartCollection->toArray();



            foreach ($data as $value) {

                $insert_db_products_list = new DbOrderProductsList();
                $total_pv = $value['attributes']['pv'] * $value['quantity'];
                $total_price = $value['price'] * $value['quantity'];

                $insert_db_products_list->code_order = $code_order;
                $insert_db_products_list->frontstore_id_fk = $order_id;
                $insert_db_products_list->customers_id_fk = $customer_id;
                $insert_db_products_list->distribution_channel_id_fk = 3;

                $insert_db_products_list->pay_type_id_fk = ''; //
                $insert_db_products_list->selling_price = $value['price'];
                $insert_db_products_list->product_name = $value['name'];
                $insert_db_products_list->amt = $value['quantity'];
                $insert_db_products_list->pv = $value['attributes']['pv'];
                $insert_db_products_list->total_pv = $total_pv;
                $insert_db_products_list->total_price = $total_price;

                if ($value['attributes']['category_id'] == 8) { //ซื้อด้วยโปรโมชั่น
                    $type_product = 'promotion';
                    $insert_db_products_list->type_product = $type_product;
                    $insert_db_products_list->promotion_id_fk = $value['attributes']['promotion_id'];
                    //'type_product'=>$type_product,
                    $insert_db_products_list->promotion_code = ''; //ยังทำไม่เสร็จ
                    $insert_db_products_list->add_from = 2;

                } elseif ($value['attributes']['category_id'] == 9) { //ซื้อด้วยคูปอง

                    $type_product = 'promotion';
                    $insert_db_products_list->promotion_id_fk = $value['attributes']['promotion_id'];
                    $insert_db_products_list->type_product = $type_product;
                    $insert_db_products_list->promotion_code = $value['attributes']['coupong'];
                    $insert_db_products_list->add_from = 2;

                    $id_add_ai_cash = DB::table('db_promotion_cus')
                        ->where('promotion_code', $value['attributes']['coupong'])
                        ->update([
                            'pro_status' => 2, //ถูกใช้งานเเล้ว
                            'used_user_name' => $customer_username,
                            'used_date' => now(),
                        ]);

                } else {
                    $type_product = 'product';
                    $insert_db_products_list->type_product = $type_product;
                    $insert_db_products_list->product_id_fk = $value['id'];
                    $insert_db_products_list->product_unit_id_fk = $value['attributes']['product_unit_id'];
                    $insert_db_products_list->add_from = 1;
                }

                $insert_db_products_list->save();
                Cart::session($type)->remove($value['id']);

            }

            if (!empty($business_location_id) and !empty($pv_total)) {
                $customer_username = Auth::guard('c_user')->user()->user_name;
                $check_giveaway = GiveawayController::check_giveaway($type, $customer_username, $pv_total);

                foreach ($check_giveaway as $value) {
                    if ($value['status'] == 'success') {

                        if ($value['rs']) {

                                $insert_order_products_list_type_giveaway = new DbOrderProductsList();
                                $insert_order_products_list_type_giveaway->frontstore_id_fk = $order_id;
                                $insert_order_products_list_type_giveaway->code_order = $code_order;
                                $insert_order_products_list_type_giveaway->customers_id_fk = $customer_id;
                                $insert_order_products_list_type_giveaway->distribution_channel_id_fk = 3;
                                $insert_order_products_list_type_giveaway->giveaway_id_fk = $value['rs']['giveaway_id'];
                                $insert_order_products_list_type_giveaway->product_name = $value['rs']['name'];
                                $insert_order_products_list_type_giveaway->amt = $value['rs']['count_free'];
                                $insert_order_products_list_type_giveaway->type_product = 'giveaway';
                                $insert_order_products_list_type_giveaway->add_from = 4;
                                $insert_order_products_list_type_giveaway->save();

                                if ($value['rs']['type'] == 1) { //product แถมเป็นสินค้า

                                    $product = DB::table('db_giveaway_products')
                                        ->select('products_details.product_id_fk', 'products_details.product_name', 'dataset_product_unit.product_unit'
                                            , 'dataset_product_unit.group_id as unit_id', 'db_giveaway_products.product_amt')
                                        ->leftJoin('products_details', 'products_details.product_id_fk', '=', 'db_giveaway_products.product_id_fk')
                                        ->leftJoin('dataset_product_unit', 'dataset_product_unit.group_id', '=', 'db_giveaway_products.product_unit')
                                        ->where('db_giveaway_products.giveaway_id_fk', '=', $value['rs']['giveaway_id'])
                                        ->where('products_details.lang_id', '=', $business_location_id)
                                        ->where('dataset_product_unit.lang_id', '=', $business_location_id)
                                        ->get();

                                    foreach ($value['rs']['product'] as $giveaway_product) {

                                        DB::table('db_order_products_list_giveaway')->insert([
                                            'order_id_fk' => $order_id,
                                            'product_list_id_fk' => $insert_order_products_list_type_giveaway->id,
                                            'giveaway_id_fk' => $value['rs']['giveaway_id'],
                                            'code_order' => $code_order,
                                            'product_id_fk' => $giveaway_product->product_id_fk,
                                            'product_name' => $giveaway_product->product_name,
                                            'product_unit_id_fk' => $giveaway_product->unit_id,
                                            'product_amt' => $giveaway_product->product_amt,
                                            'product_unit_name' => $giveaway_product->product_unit,
                                            'free' => $value['rs']['count_free'],
                                            'type_product' => 'giveaway_product',
                                        ]);
                                    }

                                } else { //gv แถมเป้นกิฟวอยเชอ

                                    DB::table('db_order_products_list_giveaway')->insert([
                                        'order_id_fk' => $order_id,
                                        'product_list_id_fk' => $insert_order_products_list_type_giveaway->id,
                                        'product_name' => 'GiftVoucher',
                                        'code_order' => $code_order,
                                        'giveaway_id_fk' => $value['rs']['giveaway_id'],
                                        'product_amt' => 1,
                                        'gv_free' => $value['rs']['gv'],
                                        'free' => $value['rs']['count_free'],
                                        'type_product' => 'giveaway_gv',

                                    ]);
                                }

                        }
                    }
                }
            }

            $resule = ['status' => 'success', 'message' => 'Product insert Success', 'id' => $order_id];
            DB::commit();
            return $resule;

        } catch (Exception $e) {
            $resule = ['status' => 'fail', 'message' => 'Product insert Fail'];
            DB::rollback();
            return $resule;
        }
    }

}
