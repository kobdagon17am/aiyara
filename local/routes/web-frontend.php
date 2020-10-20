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
 
Route::get('/', function () {

  if(session('id')){
    return redirect('home');
  }else{
     
    return view('frontend\member_login');

  }
}); 

Route::get('login', function () {
  if(session('id')){
    return redirect('home');
  }else{
    return view('frontend/member_login');

  }
});

Route::get('logout', function () {
  Session::flush();
  return redirect('login');
})->name('logout');

Route::get('home','Frontend\HomeController@index')->name('home');
Route::post('home','Frontend\HomeController@index')->name('home');
Route::post('login','Frontend\LoginController@login')->name('login');
Route::post('register_new_member','Frontend\RegisterController@register_new_member')->name('register_new_member');
Route::get('register/{id?}/{line_type?}','Frontend\RegisterController@index')->name('register');
//ดิ่ง line A B C
Route::post('under_a','Frontend\HomeController@under_a')->name('under_a');
Route::post('under_b','Frontend\HomeController@under_b')->name('under_b');
Route::post('under_c','Frontend\HomeController@under_c')->name('under_c');

Route::get('profile','Frontend\ProfileController@index')->name('profile');
Route::get('edit_profile','Frontend\ProfileController@edit_profile')->name('edit_profile');
Route::post('update_profile','Frontend\ProfileController@update_profile')->name('update_profile');
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
Route::get('add_cart','Frontend\ProductController@add_cart')->name('add_cart');
Route::get('product-list','Frontend\ProductController@index')->name('product-list');
Route::get('product-detail/{id?}','Frontend\ProductController@product_detail')->name('product-detail');

Route::get('cart','Frontend\CartController@index')->name('cart');

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
 


 