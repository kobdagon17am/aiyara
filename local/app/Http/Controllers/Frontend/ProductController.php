<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\Couse_Event;
use App\Models\Frontend\Product;
use App\Models\Frontend\ProductList;
use Auth;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }

    public function product_list($type)
    {
      $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        if (empty($business_location_id)) {
            $business_location_id = 1;
        }


        $categories = DB::table('categories')
            ->where('lang_id', '=', $business_location_id)
            ->where('status', '=', 1)
            ->orderby('order')
            ->get();

        if ($type == '6') {
            $data = Couse_Event::couse_event($type);
            return view('frontend/product/product-list-course', compact('data', 'type'));

        } else {

            $coupon = DB::table('db_promotion_cus')
                ->select('db_promotion_code.id', 'db_promotion_code.pro_sdate', 'db_promotion_code.pro_edate', 'db_promotion_cus.promotion_code', 'promotions.name_thai', 'promotions.name_eng', 'promotions.name_laos', 'promotions.name_burma', 'promotions.name_cambodia', 'db_promotion_cus.pro_status')
                ->leftjoin('db_promotion_code', 'db_promotion_code.id', '=', 'db_promotion_cus.promotion_code_id_fk')
                ->leftjoin('promotions', 'promotions.id', '=', 'db_promotion_code.promotion_id_fk')
                // ->where('db_promotion_code.approve_status', '=', 1)
                ->whereDate('db_promotion_code.pro_edate', '>=', now())
                ->where('db_promotion_cus.user_name','=',Auth::guard('c_user')->user()->user_name)
                ->where('db_promotion_cus.pro_status', '=', 1)
                ->orderby('db_promotion_cus.pro_status', 'ASC')
                ->get();

            $data = Product::product_list($type);
        }

        return view('frontend/product/product-list', compact('data', 'type', 'coupon', 'categories'));
    }

    public function product_list_select(Request $request)
    {

        $type = $request->type;
        if (empty($request->category_id)) {
            $c_id = 1;
        } else {
            $c_id = $request->category_id;

            if ($c_id == 8) {

                $html = Product::product_list_select_promotion($type, Auth::guard('c_user')->user()->user_name);
                return $html;
            } else {
                $data = Product::product_list_select($c_id, $type);

            }
        }

        $html = '';
        if ($data['product']) {
            foreach ($data['product'] as $value) {

                $html .= ProductList::product_list_html($value->products_id, $type, $value->img_url, $value->product_img, $value->product_name, $value->title, $value->icon, $value->member_price, $value->pv, $c_id);
                $data = ['html' => $html];
            }

        } else {
            $html = '';
            $data = ['html' => $html];
        }

        return $data;
    }

    public function product_detail($type, $id, $category_id = '', $coupong = '')
    {
        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        if (empty($business_location_id)) {
            $business_location_id = 1;
        }

        if (empty($id) || empty($type)) {
            return redirect('product-list/1')->withError('No Product');
        } else {
            if ($type == 6) {

                $couse_event = DB::table('course_event')
                    ->select('course_event.*')
                //->where('course_event_images.img_default', '=', 1)
                    ->where('course_event.id', '=', $id)
                    ->orderby('course_event.ce_edate')
                    ->first();

                $img = DB::table('course_event_images')
                    ->select('*')
                    ->where('course_event_id_fk', '=', $id)
                    ->get();

                $data = ['product_data' => $couse_event, 'img' => $img, 'type' => $type, 'category_id' => $category_id];

                return view('frontend/product/product-detail', compact('data'));

            } else {
                if ($category_id == 8 || $category_id == 9) { //โปรโมชั่น || cpupon

                    $product = DB::table('promotions')
                        ->select('promotions.id as products_id', 'name_thai as product_name','promotions.limited_amt_person',
                            'selling_price as member_price', 'promotions_cost.pv', 'detail_thai as descriptions'
                            , 'detail_thai as products_details')
                    // ->leftjoin('promotions_images','promotions_images.promotion_id_fk','=','promotions.id')
                        ->leftjoin('promotions_cost', 'promotions_cost.promotion_id_fk', '=', 'promotions.id')
                    // ->where('promotions_images.image_default','=',1)
                    // ->where('promotions.orders_type_id','LIKE','%'.$type.'%')
                    // ->where('promotions.business_location','=',$business_location_id)
                    // ->where('promotions_cost.business_location_id','=',$business_location_id)
                    // ->where('promotions_cost.status','=',1)
                    // ->wheredate('promotions.show_startdate','<=',date('Y-m-d'))
                    // ->wheredate('promotions.show_enddate','>=',date('Y-m-d'))
                    // ->where('promotions.status','=',1)
                    // ->orderby('id','DESC')
                        ->where('promotions.id', '=', $id)
                        ->first();



                    if (empty($product)) {
                        return redirect('product-list/' . $type)->withError('No Product');
                    } else {
                        $img = DB::table('promotions_images')
                            ->select('promotion_img as product_img', 'img_url')
                            ->where('promotion_id_fk', '=', $id)
                            ->orderby('image_default', 'DESC')
                            ->get();
                    }

                    $data = ['product_data' => $product, 'img' => $img, 'type' => $type, 'category_id' => $category_id, 'coupong' => $coupong];

                } else {
                    $product = DB::table('products')
                        ->select(
                            'products.id as products_id',
                            'products_details.*',
                            'products_cost.*',
                            'dataset_currency.*', 'products_units.product_unit_id_fk', 'dataset_product_unit.product_unit as product_unit_name')
                        ->leftjoin('products_details', 'products.id', '=', 'products_details.product_id_fk')
                    // ->leftjoin('products_images', 'products.id', '=', 'products_images.product_id_fk')
                        ->leftjoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')
                        ->leftjoin('dataset_currency', 'dataset_currency.id', '=', 'products_cost.currency_id')
                        ->leftjoin('products_units', 'products.id', '=', 'products_units.product_id_fk')
                        ->leftjoin('dataset_product_unit', 'dataset_product_unit.group_id', '=', 'products_units.product_unit_id_fk')
                        ->where('products.id', '=', $id)
                        ->where('products_units.status', '=', 1)
                    // ->where('products_images.image_default', '=', 1)
                        ->where('products_details.lang_id', '=', $business_location_id)
                        ->where('products_cost.business_location_id', '=', $business_location_id)
                        ->first();

                    if (empty($product)) {
                        return redirect('product-list/' . $type)->withError('No Product');
                    } else {
                        $img = DB::table('products_images')
                            ->where('product_id_fk', '=', $id)
                            ->orderby('image_default', 'DESC')
                            ->get();
                    }

                    $data = ['product_data' => $product, 'img' => $img, 'type' => $type, 'category_id' => $category_id];
                }



                return view('frontend/product/product-detail', compact('data'));

            }
        }
    }

    public function add_cart(Request $request)
    {
      $location_id = Auth::guard('c_user')->user()->business_location_id;
      if (empty($location_id)) {
          $location_id = 1;
      }

      $coupong = $request->coupong;

        if ($request->category_id == 8) { //promotion
            $id = 'P' . $request->id;

            $product_list = \App\Helpers\Frontend::get_promotion_detail($request->id, $location_id);

        }elseif($request->category_id == 9){//coupong
            $id = $request->coupong;
            $product_list = \App\Helpers\Frontend::get_promotion_detail($request->id, $location_id);


            if ($coupong) {
                $cartCollection = Cart::session($request->type)->getContent();
                $data = $cartCollection->toArray();

                if ($data) {
                    foreach ($data as $value) {

                        if ($value['attributes']['coupong'] == $coupong) {
                            $data = ['status' => 'fail', 'message' => 'Coupong Code สามารถใช้งานได้เพียง 1 ครั้ง ต่อ 1 Code เท่านั้น'];
                            return $data;
                        }
                    }
                }
            }

        }else{
            $id = $request->id;
            $product_list = '';
        }


        Cart::session($request->type)->add(array(
            'id' => $id, // inique row ID
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'attributes' => array(
                'pv' => $request->pv,
                'img' => $request->img,
                'product_unit_id' => $request->product_unit_id,
                'product_unit_name' => $request->product_unit_name,
                'coupong' => $coupong,
                'promotion_id' => $request->id,
                'promotion_detail' => $product_list,
                'category_id' => $request->category_id,
            ),
        ));

        $getTotalQuantity = Cart::session($request->type)->getTotalQuantity();
        // $item = Cart::session($request->type)->getContent();
        //return $getTotalQuantity;
        $data = ['status' => 'success', 'message' => 'สั่งซื้อสำเร็จ', 'getTotalQuantity' => $getTotalQuantity];


        return $data;
    }
}
