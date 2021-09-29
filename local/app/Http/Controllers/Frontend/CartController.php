<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\CourseCheckRegis;
use App\Models\Frontend\Couse_Event;
use App\Models\Frontend\GiftVoucher;
use App\Models\Frontend\Pvpayment;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CartController extends Controller
{
  // public function __construct()
  // {
  //   $this->middleware('customer');
  // }
    public function cart($type)
    {

        $cartCollection = Cart::session($type)->getContent();
        $data = $cartCollection->toArray();
        $quantity = Cart::session($type)->getTotalQuantity();
        if ($data) {
            foreach ($data as $value) {
                $pv[] = $value['quantity'] * $value['attributes']['pv'];
            }
            $pv_total = array_sum($pv);
        } else {
            $pv_total = 0;
        }

        $price = Cart::session($type)->getTotal();
        $price_total = number_format($price, 2);

        $bill = array('price_total' => $price_total,
            'pv_total' => $pv_total,
            'data' => $data,
            'quantity' => $quantity,
            'status' => 'success',

        );

        return view('frontend/product/cart', compact('bill', 'type'));
    }

    public function cart_delete(Request $request)
    {
        //dd($request->all());
        Cart::session($request->type)->remove($request->data_id);
        return redirect('cart/' . $request->type)->withSuccess('Deleted Success');
    }

    public function edit_item(Request $request)
    {
        $type = $request->type;
        if ($type == 6) {
            $chek_course = CourseCheckRegis::cart_check_register($request->item_id, $request->qty,Auth::guard('c_user')->user()->user_name);
        } else {
            $chek_course = '';
        }

        if ($request->item_id) {
            Cart::session($type)->update($request->item_id, array(
                'quantity' => array(
                    'relative' => false,
                    'value' => $request->qty,
                ),
            ));
            $cartCollection = Cart::session($type)->getContent();
            $data = $cartCollection->toArray();

            $quantity = Cart::session($type)->getTotalQuantity();
            if ($data) {
                foreach ($data as $value) {
                    $pv[] = $value['quantity'] * $value['attributes']['pv'];
                }
                $pv_total = array_sum($pv);

            } else {
                $pv_total = 0;
            }

            $price = Cart::session($type)->getTotal();
            $price_total = number_format($price, 2);

            $bill = array('price_total' => $price_total,
                'action_id' => $request->item_id,
                'pv_total' => $pv_total,
                'count_item' => $pv_total,
                'data' => $data,
                'quantity' => $quantity,
                'chek_course' => $chek_course,
                'status' => 'success',
            );

            return $bill;

        } else {
            $bill = array('status' => 'fail');
            return $bill;

        }

    }

    public static function payment_test_type_1()
    {

        // $order_id = '26'; //order_id
        // $admin_id = '4959'; //admin_id
        // $distribution_channel = '1';
        // $resule = PvPayment::PvPayment_type_confirme($order_id,'1','2','customer');
        // dd($resule);
        // // if ($resule['status'] == 'success') {
        // // }

        $data = \App\Http\Controllers\Frontend\Fc\GiveawayController::check_giveaway_all(1,'Aip','200');////ประเภทการซื้อ ,customer_username,pv order

        dd($data);

    }

    public function add_gif()
    {
        // dd('fail');
        // $user_name = '0001';
        // $gv = '500';
        // $code = 'ABCD';
        // $admin_id = '99';
        // $expri_date = '2021-01-01 23:59:59';
        // $rs = GiftVoucher::add_gift($user_name, $gv, $code, $expri_date, $admin_id);
        // dd($rs);

        $order_id = '57';//order_id
        $admin_id = '99';//admin_id

        $resule = PvPayment::PvPayment_type_confirme($order_id,$admin_id,$distribution_channel,$action_type);
        dd($resule);
    }

    public function course_register()
    {
        $admin_id = 99;
        $order_id = 96;
        $rs = Couse_Event::couse_register($order_id, $admin_id);
        dd($rs);
    }

}
