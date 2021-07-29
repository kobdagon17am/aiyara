<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Auth::routes();
// Route::get('register', function() {
//    return view('errors.404');
//  });
 //Clear Config cache:

Route::get('/config-cache', function () {
  $exitCode = Artisan::call('cache:clear');
  $exitCode = Artisan::call('config:clear');
  $exitCode = Artisan::call('view:clear');
  // $exitCode = Artisan::call('config:cache');
  return back();
});

Route::get('/', function () {

  // if(session('id')){
  if(Auth::guard('c_user')->check()){
    return redirect('profile');
  }else{
    return view('frontend/member_login');
  }
});

Route::get('login', function () {
  if(Auth::guard('c_user')->check()){
    return redirect('profile');
  }else{
    return view('frontend/member_login');

  }
});

Route::get('/admin_access/{username}', 'Frontend\LoginController@forceLogin')
  ->middleware('auth:admin')
  ->name('admin.access');

Route::get('register_success', function () {
  return view('frontend/register_success');

});

Route::get('logout', function () {
  Auth::guard('c_user')->logout();
  //Session::flush();
  return redirect('login');
})->name('logout');


Route::get('payment_test_type_1','Frontend\CartController@payment_test_type_1')->name('payment_test_type_1');
Route::get('add_gif','Frontend\CartController@add_gif')->name('add_gif');
Route::get('course_register','Frontend\CartController@course_register')->name('course_register');


Route::get('check_user','Frontend\RegisterController@check_user')->name('check_user');

Route::get('home_check_customer_id','Frontend\HomeController@home_check_customer_id')->name('home_check_customer_id');

Route::post('search','Frontend\HomeController@search')->name('search');

Route::get('modal_tree','Frontend\HomeController@modal_tree')->name('modal_tree');
Route::get('modal_add','Frontend\HomeController@modal_add')->name('modal_add');
Route::get('tree_view','Frontend\HomeController@index')->name('tree_view');
Route::get('home_type_tree','Frontend\HomeController@home_type_tree')->name('home_type_tree');
Route::post('tree_view','Frontend\HomeController@index')->name('tree_view');

Route::post('login','Frontend\LoginController@login')->name('login');
Route::post('register_new_member','Frontend\RegisterController@register_new_member')->name('register_new_member');
Route::get('register/{id?}/{line_type?}','Frontend\RegisterController@index')->name('register');

Route::get('location','Frontend\RegisterController@location')->name('location');
//ดิ่ง line A B C
Route::post('under_a','Frontend\HomeController@under_a')->name('under_a');
Route::post('under_b','Frontend\HomeController@under_b')->name('under_b');
Route::post('under_c','Frontend\HomeController@under_c')->name('under_c');

Route::get('profile','Frontend\ProfileController@index')->name('profile');
Route::get('profile_img','Frontend\ProfileController@profile_img')->name('profile_img');

Route::get('profile_address','Frontend\ProfileController@profile_address')->name('profile_address');
Route::post('profile_address','Frontend\ProfileController@profile_address')->name('profile_address');

Route::get('edit_profile','Frontend\ProfileController@edit_profile')->name('edit_profile');
Route::post('update_img_profile','Frontend\ProfileController@update_img_profile')->name('update_img_profile');

Route::post('edit_address','Frontend\ProfileController@edit_address')->name('edit_address');
Route::get('docs','Frontend\DocsController@index')->name('docs');
Route::post('docs_upload','Frontend\DocsController@docs_upload')->name('docs_upload');

Route::get('chage_password','Frontend\EditPasswordController@index')->name('chage_password');
Route::post('edit_password_submit','Frontend\EditPasswordController@edit_password_submit')->name('edit_password_submit');

Route::get('chage_password_aicash','Frontend\EditPasswordAicashController@index')->name('chage_password_aicash');
Route::post('add_password_aicash_submit','Frontend\EditPasswordAicashController@add_password_aicash_submit')->name('add_password_aicash_submit');
Route::post('edit_password_aicash_submit','Frontend\EditPasswordAicashController@edit_password_aicash_submit')->name('edit_password_aicash_submit');
Route::post('check_pass_aicash','Frontend\EditPasswordAicashController@check_pass_aicash')->name('check_pass_aicash');


Route::post('payment_submit','Frontend\CartPaymentController@payment_submit')->name('payment_submit');
Route::post('payment_address','Frontend\CartPaymentController@payment_address')->name('payment_address');

//------------------------------end-------------------------------//

Route::get('/cademy/addnew_category', function () {
  return view('frontend/product/product-addcategory');
});
Route::get('/cademy/addnew_product_menu', function () {
  return view('frontend/product/product-addnew');
});
Route::get('/cademy/addnew_product', function () {
  return view('frontend/product/product-addnew-product');
});
Route::get('/cademy/unit_product', function () {
  return view('frontend/product/product-unit');
});
Route::get('/cademy/home_product', function () {
  return view('frontend/product/product-home');
});

//---------------------- Product -------------------------------//
Route::get('product_list_select','Frontend\ProductController@product_list_select')->name('product_list_select');


Route::get('product-list/{type}','Frontend\ProductController@product_list')->name('product-list');

Route::get('product-detail/{type}/{id?}/{category_id?}','Frontend\ProductController@product_detail')->name('product-detail');

Route::get('cart/{type}','Frontend\CartController@cart')->name('cart');


Route::get('add_cart','Frontend\ProductController@add_cart')->name('add_cart');
// Route::get('cart','Frontend\CartController@index')->name('cart');
Route::post('cart_delete','Frontend\CartController@cart_delete')->name('cart_delete');
Route::post('edit_item','Frontend\CartController@edit_item')->name('edit_item');

Route::get('cart_payment/{type}','Frontend\CartPaymentController@index')->name('cart_payment');

Route::get('cart_submit_course/{type}','Frontend\CartPaymentController@cart_submit_course')->name('cart_submit_course');

Route::get('cart_payment_transfer/{code_order}','Frontend\CartPaymentController@cart_payment_transfer')->name('cart_payment_transfer');

//Route::get('cart_payment_transfer_course/{code_order}','Frontend\CartPaymentController@cart_payment_transfer_course')->name('cart_payment_transfer_course');


Route::get('product-history','Frontend\HistoryController@index')->name('product-history');
Route::post('delete_order','Frontend\HistoryController@delete_order')->name('delete_order');

Route::post('cancel_order','Frontend\HistoryController@cancel_order')->name('cancel_order');

Route::get('export_pdf_history/{code_order?}','Frontend\HistoryController@export_pdf_history')->name('export_pdf_history');

Route::get('modal_qr_recive_product','Frontend\HistoryController@modal_qr_recive_product')->name('modal_qr_recive_product');
Route::post('dt_history','Frontend\HistoryController@dt_history')->name('dt_history');

Route::get('datable/history','Frontend\HistoryController@datatable')->name('datable/history');

Route::post('upload_slip','Frontend\HistoryController@upload_slip')->name('upload_slip');
Route::post('re_new_payment','Frontend\HistoryController@re_new_payment')->name('re_new_payment');

Route::get('cart-payment-history/{code_order?}','Frontend\HistoryController@cart_payment_history')->name('cart-payment-history');
Route::get('gift-cart-payment-history/{code_order?}','Frontend\HistoryController@cart_payment_history')->name('gift-cart-payment-history');


Route::get('giftvoucher_history','Frontend\GiftVoucherController@index')->name('giftvoucher_history');
Route::get('dt_giftvoucher_history','Frontend\GiftVoucherController@dt_giftvoucher_history')->name('dt_giftvoucher_history');

Route::get('gift_order_history','Frontend\GiftVoucherController@gift_order_history')->name('gift_order_history');
Route::get('dt_gift_order_history','Frontend\GiftVoucherController@dt_gift_order_history')->name('dt_gift_order_history');
Route::post('upload_slip_aicash','Frontend\AiCashController@upload_slip_aicash')->name('upload_slip_aicash');

Route::get('ai-stockist','Frontend\AipocketController@index')->name('ai-stockist');
Route::post('check_customer_id','Frontend\AipocketController@check_customer_id')->name('check_customer_id');
Route::post('use_aipocket','Frontend\AipocketController@use_aipocket')->name('use_aipocket');
Route::post('dt_aipocket','Frontend\AipocketController@dt_aipocket')->name('dt_aipocket');

Route::get('ai-cash','Frontend\AiCashController@index')->name('ai-cash');
Route::post('dt_aicash','Frontend\AiCashController@dt_aicash')->name('dt_aicash');
Route::post('delete_aicash','Frontend\AiCashController@delete_aicash')->name('delete_aicash');
Route::post('cancel_aicash','Frontend\AiCashController@cancel_aicash')->name('cancel_aicash');
Route::post('upload_slip_aicash','Frontend\AiCashController@upload_slip_aicash')->name('upload_slip_aicash');
Route::post('upload_slip_aicash','Frontend\AiCashController@upload_slip_aicash')->name('upload_slip_aicash');
Route::get('datatable_order_aicash','Frontend\AiCashController@datatable_order_aicash')->name('datatable_order_aicash');
Route::get('datatable_add_aicash','Frontend\AiCashController@datatable_add_aicash')->name('datatable_add_aicash');
Route::any('cart_payment_aicash_submit','Frontend\AiCashController@cart_payment_aicash_submit')->name('cart_payment_aicash_submit');
Route::get('view_aicash','Frontend\AiCashController@view_aicash')->name('view_aicash');


Route::get('course','Frontend\CourseEventController@index')->name('course');

Route::get('modal_qr_ce','Frontend\CourseEventController@modal_qr_ce')->name('modal_qr_ce');

Route::post('dt_course','Frontend\CourseEventController@dt_course')->name('dt_course');

Route::get('direct-sponsor','Frontend\DirectSponsorController@index')->name('direct-sponsor');
Route::post('dt_sponsor','Frontend\DirectSponsorController@dt_sponsor')->name('dt_sponsor');

Route::get('commission-per-day','Frontend\CommissionController@commission_per_day')->name('commission-per-day');
Route::post('dt_commission_perday','Frontend\CommissionController@dt_commission_perday')->name('dt_commission_perday');

Route::get('commission_faststart/{customer_id?}/{date?}','Frontend\CommissionController@commission_faststart')->name('commission_faststart');
Route::get('dt_commission_faststart','Frontend\CommissionController@dt_commission_faststart')->name('dt_commission_faststart');

Route::get('commission_bonus_transfer','Frontend\CommissionController@commission_bonus_transfer')->name('commission_bonus_transfer');
Route::get('dt_commission_bonus_transfer','Frontend\CommissionController@dt_commission_bonus_transfer')->name('dt_commission_bonus_transfer');
Route::get('modal_commission_transfer','Frontend\CommissionController@modal_commission_transfer')->name('modal_commission_transfer');

Route::get('commission_bonus_transfer_aistockist','Frontend\CommissionController@commission_bonus_transfer_aistockist')->name('commission_bonus_transfer_aistockist');
Route::get('dt_commission_bonus_transfer_aistockist','Frontend\CommissionController@dt_commission_bonus_transfer_aistockist')->name('dt_commission_bonus_transfer_aistockist');
Route::get('modal_commission_bonus_transfer_aistockist','Frontend\CommissionController@modal_commission_bonus_transfer_aistockist')->name('modal_commission_bonus_transfer_aistockist');

Route::get('commission_bonus_transfer_af','Frontend\CommissionController@commission_bonus_transfer_af')->name('commission_bonus_transfer_af');
Route::get('dt_commission_bonus_transfer_af','Frontend\CommissionController@dt_commission_bonus_transfer_af')->name('dt_commission_bonus_transfer_af');

Route::get('commission_bonus_transfer_member','Frontend\CommissionController@commission_bonus_transfer_member')->name('commission_bonus_transfer_member');
Route::get('dt_commission_bonus_transfer_member','Frontend\CommissionController@dt_commission_bonus_transfer_member')->name('dt_commission_bonus_transfer_member');

Route::get('commission_matching/{customer_id?}/{date?}','Frontend\CommissionController@commission_matching')->name('commission_matching');
Route::get('dt_commission_matching','Frontend\CommissionController@dt_commission_matching')->name('dt_commission_matching');

Route::get('coupon','Frontend\CouponCodeController@coupon')->name('coupon');
Route::get('dt_coupon_code','Frontend\CouponCodeController@dt_coupon_code')->name('dt_coupon_code');

Route::get('message/{active?}','Frontend\MessageController@index')->name('message');
Route::get('message_read/{id}','Frontend\MessageController@message_read')->name('message_read');

Route::post('message_question','Frontend\MessageController@message_question')->name('message_question');

Route::get('news','Frontend\NewsController@index')->name('news');
Route::get('news_detail/{id}','Frontend\NewsController@news_detail')->name('news_detail');

Route::post('message_reply','Frontend\MessageController@message_reply')->name('message_reply');

Route::get('s1/{user_name?}','Frontend\SalepageController@aiyara')->name('s1');
Route::get('s2/{user_name?}','Frontend\SalepageController@aimmura')->name('s2');
Route::get('s3/{user_name?}','Frontend\SalepageController@cashewy')->name('s3');
Route::get('s4/{user_name?}','Frontend\SalepageController@aifacad')->name('s4');
Route::get('s5/{user_name?}','Frontend\SalepageController@ailada')->name('s5');
Route::get('s6/{user_name?}','Frontend\SalepageController@trimmax')->name('s6');

Route::get('salepage/setting','Frontend\SalepageController@setting')->name('salepage/setting');
Route::post('salepage/save_contact','Frontend\SalepageController@save_contact')->name('salepage/save_contact');
Route::post('salepage/save_js','Frontend\SalepageController@save_js')->name('salepage/save_js');

Route::post('check_shipping_cos','Frontend\Fc\ShippingCosController@check_shipping_cos')->name('check_shipping_cos');

Route::get('reward-history','Frontend\RewardHistoryController@index')->name('reward-history');

Route::get('travel','Frontend\TravelController@index')->name('travel');
Route::get('travel_detail/{id}','Frontend\TravelController@travel_detail')->name('travel_detail');

// Tax Data
Route::get('taxdata', 'Frontend\TaxController@index')->name('taxdata');

Route::get('payment/success', function() {
  return view('frontend.payment.success');
})->name('payment/success');

Route::get('payment/fail', function() {
  return view('frontend.payment.fail');
})->name('payment/fail');

Route::get('ksher', 'Frontend\Ksher\KsherController@index')->name('ksher');
Route::post('gateway_ksher', 'Frontend\Ksher\KsherController@gateway_ksher')->name('gateway_ksher');
Route::post('ksher_notify', 'Frontend\Ksher\KsherNotifyController@ksher_notify')->name('ksher_notify');
