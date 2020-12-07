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
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('/', function () {

  // if(session('id')){
  if(Auth::guard('c_user')->check()){
    return redirect('home');
  }else{
    return view('frontend/member_login');
  }
}); 

Route::get('login', function () {
  if(Auth::guard('c_user')->check()){
    return redirect('home');
  }else{
    return view('frontend/member_login');

  }
});

Route::get('logout', function () {
  Auth::guard('c_user')->logout();
  //Session::flush();
  return redirect('login');
})->name('logout');


Route::get('payment_test_type_1','Frontend\CartController@payment_test_type_1')->name('payment_test_type_1');

Route::get('check_user','Frontend\RegisterController@check_user')->name('check_user');

Route::get('home_check_customer_id','Frontend\HomeController@home_check_customer_id')->name('home_check_customer_id');

Route::post('search','Frontend\HomeController@search')->name('search');

Route::get('modal_tree','Frontend\HomeController@modal_tree')->name('modal_tree');
Route::get('modal_add','Frontend\HomeController@modal_add')->name('modal_add');
Route::get('home','Frontend\HomeController@index')->name('home');
Route::get('home_type_tree','Frontend\HomeController@home_type_tree')->name('home_type_tree');
Route::post('home','Frontend\HomeController@index')->name('home');
Route::post('login','Frontend\LoginController@login')->name('login');
Route::post('register_new_member','Frontend\RegisterController@register_new_member')->name('register_new_member');
Route::get('register/{id?}/{line_type?}','Frontend\RegisterController@index')->name('register');
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


Route::post('payment_submit','Frontend\CartPaymentController@payment_submit')->name('payment_submit');



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

Route::get('product-detail/{type}/{id?}','Frontend\ProductController@product_detail')->name('product-detail');

Route::get('cart/{type}','Frontend\CartController@cart')->name('cart');
 

Route::get('add_cart','Frontend\ProductController@add_cart')->name('add_cart');
// Route::get('cart','Frontend\CartController@index')->name('cart');
Route::post('cart_delete','Frontend\CartController@cart_delete')->name('cart_delete');
Route::post('edit_item','Frontend\CartController@edit_item')->name('edit_item');

Route::get('cart_payment/{type}','Frontend\CartPaymentController@index')->name('cart_payment');


Route::get('product-history','Frontend\HistoryController@index')->name('product-history');
Route::post('dt_history','Frontend\HistoryController@dt_history')->name('dt_history');
Route::post('upload_slip','Frontend\HistoryController@upload_slip')->name('upload_slip');
Route::get('cart-payment-history/{code_order}','Frontend\HistoryController@cart_payment_history')->name('cart-payment-history');

 

Route::get('ai-pocket','Frontend\AipocketController@index')->name('ai-pocket');
Route::post('check_customer_id','Frontend\AipocketController@check_customer_id')->name('check_customer_id');

Route::post('use_aipocket','Frontend\AipocketController@use_aipocket')->name('use_aipocket');
Route::post('dt_aipocket','Frontend\AipocketController@dt_aipocket')->name('dt_aipocket');

Route::get('/product-status', function () {
  return view('frontend/product/product-status');
})->name('product-status');

Route::get('/allmember', function () {
  return view('frontend/allmember');
})->name('allmember');

Route::get('/benefits', function () {
  return view('frontend/benefits');
})->name('benefits');
Route::get('/reward-history', function () {
  return view('frontend/reward-history');
})->name('reward-history');
Route::get('/regis-member', function () {
  return view('frontend/regis-member');
});

Route::get('/cademy/usersetting', function () {
  return view('frontend/cademy/aismart/usersetting');
});

Route::get('/cademy/membersetting', function () {
  return view('frontend/cademy/aismart/member_team_setting');
});


Route::get('/walletindex', function () {
  return view('frontend/walletindex');
})->name('walletindex');

Route::get('/cart-payment', function () {
  return view('frontend/product/cart-payment');
})->name('cart-payment');


Route::get('/comission', function () {
  return view('frontend/comission');
})->name('comission');
Route::get('/course', function () {
  return view('frontend/course');
})->name('course');
Route::get('/travel', function () {
  return view('frontend/travel');
})->name('travel');
Route::get('/comhistory', function () {
  return view('frontend/comhistory');
});

/*  Member Web Promotion Theme */
Route::get('/24extra/promote', function () {
  return view('frontend/webpromote/theme02/index');
});
Route::get('/24extra/promote2', function () {
  return view('frontend/webpromote/theme02/news');
});
Route::get('/24extra/promote3', function () {
  return view('frontend/webpromote/theme02/product');
});

Route::get('/24extra/detail', function () {
  return view('frontend/webpromote/theme02/detail');
});
Route::get('/24extra/detail-Gallery', function () {
  return view('frontend/webpromote/theme02/detail-Gallery');
});
Route::get('/24extra/detail-product', function () {
  return view('frontend/webpromote/theme02/detail-product');
});
 


 