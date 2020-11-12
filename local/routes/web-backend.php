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

    Route::resource('orders_type', 'Orders_typeController');
    Route::post('orders_type/datatable', 'Orders_typeController@Datatable')->name('orders_type.datatable');
  
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

	Route::resource('warehouse', 'WarehouseController');
    Route::post('warehouse/datatable', 'WarehouseController@Datatable')->name('warehouse.datatable');

	Route::resource('subwarehouse', 'SubwarehouseController');
    Route::post('subwarehouse/datatable', 'SubwarehouseController@Datatable')->name('subwarehouse.datatable');

    Route::resource('zone', 'ZoneController');
    Route::post('zone/datatable', 'ZoneController@Datatable')->name('zone.datatable');

    Route::resource('shelf', 'ShelfController');
    Route::post('shelf/datatable', 'ShelfController@Datatable')->name('shelf.datatable');

    Route::resource('categories', 'CategoriesController');
    Route::post('categories/datatable', 'CategoriesController@Datatable')->name('categories.datatable');

    Route::resource('business_location', 'Business_locationController');
    Route::post('business_location/datatable', 'Business_locationController@Datatable')->name('business_location.datatable');


    Route::resource('language', 'LanguageController');
    Route::post('language/datatable', 'LanguageController@Datatable')->name('language.datatable');

    Route::resource('product_group', 'Product_groupController');
    Route::post('product_group/datatable', 'Product_groupController@Datatable')->name('product_group.datatable');

    Route::resource('personal_quality', 'Personal_qualityController');
    Route::post('personal_quality/datatable', 'Personal_qualityController@Datatable')->name('personal_quality.datatable');

    Route::resource('travel_feature', 'Travel_featureController');
    Route::post('travel_feature/datatable', 'Travel_featureController@Datatable')->name('travel_feature.datatable');

    Route::resource('aistockist', 'AistockistController');
    Route::post('aistockist/datatable', 'AistockistController@Datatable')->name('aistockist.datatable');

    Route::resource('agency', 'AgencyController');
    Route::post('agency/datatable', 'AgencyController@Datatable')->name('agency.datatable');


    Route::resource('limited_amt_type', 'Limited_amt_typeController');
    Route::post('limited_amt_type/datatable', 'Limited_amt_typeController@Datatable')->name('limited_amt_type.datatable');

    Route::resource('promotions', 'PromotionsController');
    Route::post('promotions/datatable', 'PromotionsController@Datatable')->name('promotions.datatable');

    Route::resource('promotions_products', 'Promotions_productsController');
    Route::post('promotions_products/datatable', 'Promotions_productsController@Datatable')->name('promotions_products.datatable');
    Route::get('promotions_products/create/{id}', 'Promotions_productsController@create');

    Route::resource('promotions_cost', 'Promotions_costController');
    Route::post('promotions_cost/datatable', 'Promotions_costController@Datatable')->name('promotions_cost.datatable');
    // Route::get('promotions_cost/{id}', 'Promotions_costController@index');

    Route::resource('currency', 'CurrencyController');
    Route::post('currency/datatable', 'CurrencyController@Datatable')->name('currency.datatable');

    Route::resource('products_details', 'Products_detailsController');
    Route::post('products_details/datatable', 'Products_detailsController@Datatable')->name('products_details.datatable');
    Route::get('products_details/create/{id}', 'Products_detailsController@create');

    Route::resource('products_images', 'Products_imagesController');
    Route::post('products_images/datatable', 'Products_imagesController@Datatable')->name('products_images.datatable');
    Route::get('products_images/create/{id}', 'Products_imagesController@create');



    Route::resource('faq_topic', 'Faq_topicController');
    Route::post('faq_topic/datatable', 'Faq_topicController@Datatable')->name('faq_topic.datatable');

    Route::resource('faq', 'FaqController');
    Route::post('faq/datatable', 'FaqController@Datatable')->name('faq.datatable');

    Route::resource('crm', 'CrmController');
    Route::post('crm/datatable', 'CrmController@Datatable')->name('crm.datatable');

    Route::resource('products_cost', 'Products_costController');
    Route::post('products_cost/datatable', 'Products_costController@Datatable')->name('products_cost.datatable');
    Route::get('products_cost/create/{id}', 'Products_costController@create');

    Route::resource('products_units', 'Products_unitsController');
    Route::post('products_units/datatable', 'Products_unitsController@Datatable')->name('products_units.datatable');
    Route::get('products_units/create/{id}', 'Products_unitsController@create');
    
    #=======================================================================================================================================================
    // Route::group(['prefix' => 'permission','namespace' => 'Permission',  'as' => 'permission.'], function() {

    Route::resource('admin', 'AdminController');
    Route::post('admin/datatable', 'AdminController@Datatable')->name('admin.datatable');
    Route::get('permission/{id}/roles', 'AdminController@roles');
    Route::get('admin/updateRoles/{id}', 'AdminController@updateRoles');
    Route::post('admin/updateRoles/{id}', 'AdminController@updateRoles');

    Route::resource('permission', 'AdminController');


    Route::resource('role', 'RoleController');
    Route::post('role/datatable', 'RoleController@Datatable')->name('role.datatable');



    // }); 
    #=======================================================================================================================================================

    Route::get('template/{any?}', 'TemplateController@index')->name('template');


  #=========================================================================================================================================================
  }); //route group auth:admin
#===========================================================================================================================================================
}); //route group backend
