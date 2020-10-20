<?php
#===========================================================================================================================================================
Route::group(['prefix' => 'backend','namespace' => 'Backend',  'as' => 'backend.'], function() {
#===========================================================================================================================================================

  #=========================================================================================================================================================
  Route::group(['middleware' => ['auth:admin']], function () {
  #=========================================================================================================================================================
  // Route::resource('bannerbasicprin', 'BannerbasicprinController');
  // Route::post('bannerbasicprin/datatable', 'BannerbasicprinController@Datatable')->name('bannerbasicprin.datatable');  
#=====================================================================================================================================



//ENVIRONMENT    
    Route::resource('statementinside', 'StatementinsideController');
    Route::post('statementinside/datatable', 'StatementinsideController@Datatable')->name('statementinside.datatable');

    Route::resource('triple_banner', 'Triple_bannerController');
    Route::post('triple_banner/datatable', 'Triple_bannerController@Datatable')->name('triple_banner.datatable');

    Route::resource('triple_header', 'Triple_headerController');
    Route::post('triple_header/datatable', 'Triple_headerController@Datatable')->name('triple_header.datatable');

    Route::resource('triple_detail', 'Triple_detailController');
    Route::post('triple_detail/datatable', 'Triple_detailController@Datatable')->name('triple_detail.datatable');

    Route::resource('envislogan', 'EnvisloganController');
    Route::post('envislogan/datatable', 'EnvisloganController@Datatable')->name('envislogan.datatable');

    Route::resource('enviimpact_banner', 'Enviimpact_bannerController');
    Route::post('enviimpact_banner/datatable', 'Enviimpact_bannerController@Datatable')->name('enviimpact_banner.datatable');

    Route::resource('enviimpact_inside', 'Enviimpact_insideController');
    Route::post('enviimpact_inside/datatable', 'Enviimpact_insideController@Datatable')->name('enviimpact_inside.datatable');
    
  
  Route::get('edit-enviimpact_chart/{id}', 'Enviimpact_chartController@from_chart'); 
  Route::put('add-enviimpact_chart', 'Enviimpact_chartController@from_add');
  Route::get('add-enviimpact_chart1/{id}', 'Enviimpact_chartController@fromaddchart');
  Route::get('add-enviimpact_chart2/{id}', 'Enviimpact_chartController@fromaddchart2');
  Route::get('edit-enviimpact_editchart/{id}', 'Enviimpact_chartController@from_editchart');
  Route::put('update-enviimpact_chart/{id}', 'Enviimpact_chartController@update_chart');
  Route::put('update-enviimpact_chart', 'Enviimpact_chartController@update_updatechart');
  Route::get('delete-enviimpact_chart/{id}', 'Enviimpact_chartController@delete');
#=====================================================================================

    Route::resource('business_banner', 'Business_bannerController');
    Route::post('business_banner/datatable', 'Business_bannerController@Datatable')->name('business_banner.datatable');

    Route::resource('business_header', 'Business_headerController');
    Route::post('business_header/datatable', 'Business_headerController@Datatable')->name('business_header.datatable');
 

//CSR
    Route::resource('bannerbasicprin', 'BannerbasicprinController');
    Route::post('bannerbasicprin/datatable', 'BannerbasicprinController@Datatable')->name('bannerbasicprin.datatable');

    Route::resource('detailbasicprin', 'DetailbasicprinController');
    Route::post('detailbasicprin/datatable', 'DetailbasicprinController@Datatable')->name('detailbasicprin.datatable');


  }); //route group auth:admin
#===========================================================================================================================================================
}); //route group backend

