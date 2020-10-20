<?php
#===========================================================================================================================================================
Route::group(['prefix' => 'backend','namespace' => 'Backend',  'as' => 'backend.'], function() {
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
  
    Route::resource('banner_slide', 'Banner_slideController');
    Route::post('banner_slide/datatable', 'Banner_slideController@Datatable')->name('banner_slide.datatable');
  
    Route::resource('banner_front', 'Banner_frontController');
    Route::post('banner_front/datatable', 'Banner_frontController@Datatable')->name('banner_front.datatable');

    #=======================================================================================================================================================
    Route::group(['prefix' => 'permission','namespace' => 'Permission',  'as' => 'permission.'], function() {

    Route::resource('admin', 'AdminController');
    Route::post('admin/datatable', 'AdminController@Datatable')->name('datatable');

    Route::resource('roles', 'RolesController');
    Route::post('roles/datatable', 'RolesController@Datatable')->name('roles.datatable');

    }); 
    #=======================================================================================================================================================

    Route::get('template/{any?}', 'TemplateController@index')->name('template');


     Route::resource('menu_permission', 'Setting\MenuPermissionController');
     Route::post('data_table/menu_permission', 'Setting\MenuPermissionController@queryDatatable');
     Route::post('menu_permission/data_table', 'Setting\MenuPermissionController@queryDatatable');
     Route::post('menu_permission/{id}/edit', 'Setting\MenuPermissionController@edit');
     Route::get('menu_permission/{id}/edit', 'Setting\MenuPermissionController@edit');


  #=========================================================================================================================================================
  }); //route group auth:admin
#===========================================================================================================================================================
}); //route group backend
