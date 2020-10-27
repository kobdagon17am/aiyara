<?php
#===========================================================================================================================================================
Route::group(['prefix' => 'backend','namespace' => 'backend',  'as' => 'backend.'], function() {
#===========================================================================================================================================================



  // Authentication Routes...
  Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
  Route::post('login', 'Auth\LoginController@login')->name('login');
  Route::get('logout', 'Auth\LoginController@logout')->name('logout');


  #=========================================================================================================================================================
  Route::group(['middleware' => ['auth:admin']], function () {
  #=========================================================================================================================================================

    Route::get('home', 'HomeController@index');
    Route::get('index', 'HomeController@index');
    Route::get('manage_warehouse', 'WarehousController@index');


    Route::resource('course_event', 'Course_eventController');
    Route::post('course_event/datatable', 'Course_eventController@Datatable')->name('course_event.datatable');



    Route::resource('products', 'ProductsController');
    Route::post('products/datatable', 'ProductsController@Datatable')->name('products.datatable');
	
    Route::resource('package', 'PackageController');
    Route::post('package/datatable', 'PackageController@Datatable')->name('package.datatable');
  
    Route::resource('qualification', 'QualificationController');
    Route::post('qualification/datatable', 'QualificationController@Datatable')->name('qualification.datatable');

    Route::resource('product_type', 'Product_typeController');
    Route::post('product_type/datatable', 'Product_typeController@Datatable')->name('product_type.datatable');
  
    Route::resource('product_unit', 'Product_unitController');
    Route::post('product_unit/datatable', 'Product_unitController@Datatable')->name('product_unit.datatable');

    Route::resource('banner_front', 'Banner_frontController');
    Route::post('banner_front/datatable', 'Banner_frontController@Datatable')->name('banner_front.datatable');

    Route::resource('fsb', 'FsbController');
    Route::post('fsb/datatable', 'FsbController@Datatable')->name('fsb.datatable');
  
    Route::resource('manage_bonus', 'Manage_bonusController');
    Route::post('manage_bonus/datatable', 'Manage_bonusController@Datatable')->name('manage_bonus.datatable');
  
    Route::resource('businessweb', 'BusinesswebController');
    Route::post('businessweb/datatable', 'BusinesswebController@Datatable')->name('businessweb.datatable');

    Route::resource('businessweb_banner', 'Businessweb_bannerController');
    Route::post('businessweb_banner/datatable', 'Businessweb_bannerController@Datatable')->name('businessweb_banner.datatable');
    Route::get('businessweb_banner/index/{id}', 'Businessweb_bannerController@index');

  
    Route::resource('course_history', 'Course_historyController');
    Route::post('course_history/datatable', 'Course_historyController@Datatable')->name('course_history.datatable');

    Route::resource('course_history_list', 'Course_history_listController');
    Route::post('course_history_list/datatable', 'Course_history_listController@Datatable')->name('course_history_list.datatable');
    Route::get('course_history_list/index/{id}', 'Course_history_listController@index');


    #=======================================================================================================================================================
    // Route::group(['prefix' => 'permission','namespace' => 'Permission',  'as' => 'permission.'], function() {

    Route::resource('admin', 'AdminController');
    Route::post('admin/datatable', 'AdminController@Datatable')->name('admin.datatable');
    Route::get('permission/{id}/roles', 'AdminController@roles');
    Route::get('admin/updateRoles/{id}', 'AdminController@updateRoles');
    Route::post('admin/updateRoles/{id}', 'AdminController@updateRoles');

    Route::resource('permission', 'AdminController');

    // }); 
    #=======================================================================================================================================================

    Route::get('template/{any?}', 'TemplateController@index')->name('template');


  #=========================================================================================================================================================
  }); //route group auth:admin
#===========================================================================================================================================================
}); //route group backend
