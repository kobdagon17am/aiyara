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


Route::get('product-list-1','Frontend\ProductController@product_list_type_1')->name('product-list-1');
Route::get('product-detail-1/{id?}','Frontend\ProductController@product_detail_1')->name('product-detail-1');
Route::get('cart-1','Frontend\CartController@cart_1')->name('cart-1');

Route::get('product-list-2','Frontend\ProductController@product_list_type_2')->name('product-list-2');
Route::get('product-detail-2/{id?}','Frontend\ProductController@product_detail_2')->name('product-detail-2');
Route::get('cart-2','Frontend\CartController@cart_2')->name('cart-2');

Route::get('product-list-3','Frontend\ProductController@product_list_type_3')->name('product-list-3');
Route::get('product-detail-3/{id?}','Frontend\ProductController@product_detail_3')->name('product-detail-3');
Route::get('cart-3','Frontend\CartController@cart_3')->name('cart-3');

Route::get('product-list-4','Frontend\ProductController@product_list_type_4')->name('product-list-4');
Route::get('product-detail-4/{id?}','Frontend\ProductController@product_detail_4')->name('product-detail-4');
Route::get('cart-4','Frontend\CartController@cart_4')->name('cart-4');

Route::get('product-list-5','Frontend\ProductController@product_list_type_5')->name('product-list-5');
Route::get('product-detail-5/{id?}','Frontend\ProductController@product_detail_5')->name('product-detail-5');
Route::get('cart-5','Frontend\CartController@cart_5')->name('cart-5');

Route::get('product-list-6','Frontend\ProductController@product_list_type_6')->name('product-list-6');
Route::get('product-detail-6/{id?}','Frontend\ProductController@product_detail_6')->name('product-detail-6');
Route::get('cart-6','Frontend\CartController@cart_6')->name('cart-6');


Route::get('add_cart','Frontend\ProductController@add_cart')->name('add_cart');
Route::get('cart','Frontend\CartController@index')->name('cart');
Route::post('cart_delete','Frontend\CartController@cart_delete')->name('cart_delete');
Route::post('edit_item','Frontend\CartController@edit_item')->name('edit_item');

Route::get('cart_payment','Frontend\CartPaymentController@index')->name('cart_payment');

 

Route::get('/product-status', function () {
  return view('frontend/product/product-status');
})->name('product-status');

Route::get('/allmember', function () {
  return view('frontend//allmember');
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
})->name('walletindex');;
Route::get('/pocketindex', function () {
  return view('frontend/pocketindex');
})->name('pocketindex');

Route::get('/cart-payment', function () {
  return view('frontend/product/cart-payment');
})->name('cart-payment');

Route::get('/product-history', function () {
  return view('frontend/product/product-history');
})->name('product-history');

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
 

/*  Member Web Promotion Theme  */
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
 


 