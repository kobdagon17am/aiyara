<?php
#===========================================================================================================================================================
Route::group(['prefix' => 'backend','namespace' => 'backend',  'as' => 'backend.'], function() {
#===========================================================================================================================================================

  // Authentication Routes...
  Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
  Route::post('login', 'Auth\LoginController@login')->name('login');
  Route::get('logout', 'Auth\LoginController@logout')->name('logout');

  Route::get('user/test/{email}', 'Auth\LoginController@user_test');
  #=========================================================================================================================================================
  Route::group(['middleware' => ['auth:admin']], function () {
  #=========================================================================================================================================================

  	Route::post('testme', 'HomeController@testme');
 	Route::get('testme', 'HomeController@testme');


    Route::get('test_sql', 'HomeController@test_sql');

    Route::get('home', 'HomeController@index');
    Route::get('index', 'HomeController@index');

    Route::resource('course_event', 'Course_eventController');
    Route::post('course_event/datatable', 'Course_eventController@Datatable')->name('course_event.datatable');

    Route::post('course_event_frontstore/datatable', 'FrontstoreController@DatatableCourseEvent')->name('course_event_frontstore.datatable');

    Route::resource('course_event_images', 'Course_event_imagesController');
    Route::post('course_event_images/datatable', 'Course_event_imagesController@Datatable')->name('course_event_images.datatable');
    Route::get('course_event_images/create/{id}', 'Course_event_imagesController@create');

    Route::resource('products', 'ProductsController');
    Route::post('products/datatable', 'ProductsController@Datatable')->name('products.datatable');
    Route::post('products_delete', 'ProductsController@products_delete');

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

    Route::resource('add_ai_cash', 'Add_ai_cashController');
    Route::post('add_ai_cash/datatable', 'Add_ai_cashController@Datatable')->name('add_ai_cash.datatable');
    Route::post('add_ai_cash_02/datatable', 'Add_ai_cashController@Datatable02')->name('add_ai_cash_02.datatable');

// สาขา / คลัง @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    Route::resource('branchs', 'BranchsController');
    Route::post('branchs/datatable', 'BranchsController@Datatable')->name('branchs.datatable');

    Route::resource('warehouse', 'WarehouseController');
    Route::post('warehouse/datatable', 'WarehouseController@Datatable')->name('warehouse.datatable');
    Route::get('warehouse/create/{id}', 'WarehouseController@create');

    Route::resource('zone', 'ZoneController');
    Route::post('zone/datatable', 'ZoneController@Datatable')->name('zone.datatable');
    Route::get('zone/create/{id}', 'ZoneController@create');

    Route::resource('shelf', 'ShelfController');
    Route::post('shelf/datatable', 'ShelfController@Datatable')->name('shelf.datatable');
    Route::get('shelf/create/{id}', 'ShelfController@create');

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

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
    Route::get('promotions_delete/{id}', 'PromotionsController@destroy');

    Route::resource('promotions_products', 'Promotions_productsController');
    Route::post('promotions_products/datatable', 'Promotions_productsController@Datatable')->name('promotions_products.datatable');
    Route::get('promotions_products/create/{id}', 'Promotions_productsController@create');

    Route::resource('currency', 'CurrencyController');
    Route::post('currency/datatable', 'CurrencyController@Datatable')->name('currency.datatable');

    Route::resource('products_details', 'Products_detailsController');
    Route::post('products_details/datatable', 'Products_detailsController@Datatable')->name('products_details.datatable');
    Route::get('products_details/create/{id}', 'Products_detailsController@create');

    Route::resource('products_images', 'Products_imagesController');
    Route::post('products_images/datatable', 'Products_imagesController@Datatable')->name('products_images.datatable');
    Route::get('products_images/create/{id}', 'Products_imagesController@create');

    Route::resource('promotions_images', 'Promotions_imagesController');
    Route::post('promotions_images/datatable', 'Promotions_imagesController@Datatable')->name('promotions_images.datatable');
    Route::get('promotions_images/create/{id}', 'Promotions_imagesController@create');
    Route::get('promotions_images/destroy/{id}', 'Promotions_imagesController@destroy');


    Route::resource('faq_topic', 'Faq_topicController');
    Route::post('faq_topic/datatable', 'Faq_topicController@Datatable')->name('faq_topic.datatable');

    Route::resource('faq', 'FaqController');
    Route::post('faq/datatable', 'FaqController@Datatable')->name('faq.datatable');

    Route::resource('crm', 'CrmController');
    Route::post('crm/datatable', 'CrmController@Datatable')->name('crm.datatable');

    Route::resource('products_cost', 'Products_costController');
    Route::post('products_cost/datatable', 'Products_costController@Datatable')->name('products_cost.datatable');
    Route::get('products_cost/create/{id}', 'Products_costController@create');


    Route::resource('promotions_cost', 'Promotions_costController');
    Route::post('promotions_cost/datatable', 'Promotions_costController@Datatable')->name('promotions_cost.datatable');
    Route::get('promotions_cost/create/{id}', 'Promotions_costController@create');


    Route::resource('products_units', 'Products_unitsController');
    Route::post('products_units/datatable', 'Products_unitsController@Datatable')->name('products_units.datatable');
    Route::get('products_units/create/{id}', 'Products_unitsController@create');
    Route::get('products_units/delete/{id}', 'Products_unitsController@destroy');

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

    Route::resource('department', 'DepartmentController');
    Route::post('department/datatable', 'DepartmentController@Datatable')->name('department.datatable');

    Route::resource('vat', 'VatController');
    Route::post('vat/datatable', 'VatController@Datatable')->name('vat.datatable');

    Route::resource('fee', 'FeeController');
    Route::post('fee/datatable', 'FeeController@Datatable')->name('fee.datatable');

    Route::resource('account_bank', 'Account_bankController');
    Route::post('account_bank/datatable', 'Account_bankController@Datatable')->name('account_bank.datatable');

    Route::resource('maintain_balance', 'Maintain_balanceController');
    Route::post('maintain_balance/datatable', 'Maintain_balanceController@Datatable')->name('maintain_balance.datatable');

    Route::resource('crm_gettopic', 'Crm_gettopicController');
    Route::post('crm_gettopic/datatable', 'Crm_gettopicController@Datatable')->name('crm_gettopic.datatable');

    Route::resource('crm_answer', 'Crm_answerController');
    Route::post('crm_answer/datatable', 'Crm_answerController@Datatable')->name('crm_answer.datatable');
    Route::get('crm_answer/create/{id}', 'Crm_answerController@create');


    Route::resource('shipping_cost', 'Shipping_costController');
    Route::post('shipping_cost/datatable', 'Shipping_costController@Datatable')->name('shipping_cost.datatable');

    Route::resource('shipping_vicinity', 'Shipping_vicinityController');
    Route::post('shipping_vicinity/datatable', 'Shipping_vicinityController@Datatable')->name('shipping_vicinity.datatable');
    Route::get('shipping_vicinity/create/{id}', 'Shipping_vicinityController@create');


    Route::resource('pm', 'PmController');
    Route::get('pm/{id}/anser', 'PmController@anser');
    Route::post('pm_anser_save', 'PmController@pm_anser_save');
    Route::post('pm/datatable', 'PmController@Datatable')->name('pm.datatable');

    Route::resource('pm_broadcast', 'Pm_broadcastController');
    Route::post('pm_broadcast/datatable', 'Pm_broadcastController@Datatable')->name('pm_broadcast.datatable');

    Route::resource('consignments', 'ConsignmentsController');
    Route::post('consignments/datatable', 'ConsignmentsController@Datatable')->name('consignments.datatable');

    Route::post('consignments_map/datatable', 'ConsignmentsController@DatatableMap')->name('consignments_map.datatable');

    Route::post('consignments_sent/datatable', 'ConsignmentsController@DatatableSent')->name('consignments_sent.datatable');


    Route::post('excelExportConsignment', 'ExcelController@excelExportConsignment');

    Route::post('ajaxGentoExportConsignments', 'AjaxController@ajaxGentoExportConsignments');

    Route::post('ajaxCheckRemain_pay_product_receipt', 'AjaxController@ajaxCheckRemain_pay_product_receipt');

    Route::resource('consignments_import', 'Consignments_importController');
    Route::post('consignments_import/datatable', 'Consignments_importController@Datatable')->name('consignments_import.datatable');

    Route::resource('products_fifo_bill', 'Products_fifo_billController');
    Route::post('products_fifo_bill/datatable', 'Products_fifo_billController@Datatable')->name('products_fifo_bill.datatable');
    Route::post('products_fifo_bill_send/datatable', 'Products_fifo_billController@DatatableToSend')->name('products_fifo_bill_send.datatable');
    Route::post('warehouse_consignments/datatable', 'Products_fifo_billController@DatatableConsignments')->name('warehouse_consignments.datatable');

    Route::post('uploadFile', 'PagesController@uploadFile');
    Route::post('uploadFileXLS', 'PagesController@uploadFileXLS');
    Route::post('uploadFileXLSConsignments', 'PagesController@uploadFileXLSConsignments');
    Route::post('uploadCe_regis', 'PagesController@uploadCe_regis');
    Route::post('uploadCe_regisCSV', 'PagesController@uploadCe_regisCSV');
    Route::post('uploadPromotionCus', 'PagesController@uploadPromotionCus');
    Route::get('uploadPromotionCus', 'PagesController@uploadPromotionCus');

    Route::post('uploadGiftVoucherCus', 'PagesController@uploadGiftVoucherCus');

    Route::post('csvExport', 'ExcelController@csvExport');
    Route::post('excelExport', 'ExcelController@excelExport');
    Route::post('excelExportPromotionCus', 'ExcelController@excelExportPromotionCus');
    Route::post('excelExportGiftvoucherCus', 'ExcelController@excelExportGiftvoucherCus');
    Route::post('excelExportCe_regis', 'ExcelController@excelExportCe_regis');
    Route::post('csvExportCe_regis', 'ExcelController@csvExportCe_regis');

    Route::post('excelImport', 'ExcelController@excelImport');

    Route::post('excelExportChart', 'ExcelChart@createexcelfileAction');

    Route::post('ajaxSetSession', 'AjaxController@ajaxSetSession');
    Route::post('ajaxMenuPermissionControl', 'AjaxController@ajaxMenuPermissionControl');

    Route::post('ajaxClearDataPm_broadcast', 'AjaxController@ajaxClearDataPm_broadcast');
    Route::post('ajaxClearDataPromotionCode', 'AjaxController@ajaxClearDataPromotionCode');
    Route::post('ajaxClearDataGiftvoucherCode', 'AjaxController@ajaxClearDataGiftvoucherCode');
    Route::post('ajaxClearConsignment', 'AjaxController@ajaxClearConsignment');
    Route::post('ajaxGenPromotionCode', 'AjaxController@ajaxGenPromotionCode');
    Route::get('ajaxGenPromotionCode', 'AjaxController@ajaxGenPromotionCode');
    Route::post('ajaxGenPromotionCodePrefixCoupon', 'AjaxController@ajaxGenPromotionCodePrefixCoupon');

    Route::post('ajaxGenPromotionSaveDate', 'AjaxController@ajaxGenPromotionSaveDate');

    Route::post('ajaxGetBranch', 'AjaxController@ajaxGetBranch');
    Route::post('ajaxGetWarehouse', 'AjaxController@ajaxGetWarehouse');
    Route::post('ajaxGetZone', 'AjaxController@ajaxGetZone');
    Route::post('ajaxGetShelf', 'AjaxController@ajaxGetShelf');
    Route::post('ajaxGetLotnumber', 'AjaxController@ajaxGetLotnumber');
    Route::post('ajaxGetLotnumber2', 'AjaxController@ajaxGetLotnumber2');
    Route::post('ajaxGetLotnumber3', 'AjaxController@ajaxGetLotnumber3');

    Route::post('ajaxGetOrder', 'AjaxController@ajaxGetOrder');
    Route::post('ajaxGetCustomer', 'AjaxController@ajaxGetCustomer');
    Route::post('ajaxGetCustomer_name', 'AjaxController@ajaxGetCustomer_name');
    Route::post('ajaxGetCustomerDelivery', 'AjaxController@ajaxGetCustomerDelivery');
    Route::post('ajaxGetCustomerCode', 'AjaxController@ajaxGetCustomerCode');

    Route::post('ajaxGetCustomerCodeOnly', 'AjaxController@ajaxGetCustomerCodeOnly');
    Route::post('ajaxGetCustomerNameOnly', 'AjaxController@ajaxGetCustomerNameOnly');

    Route::post('ajaxGetCustomerForFrontstore', 'AjaxController@ajaxGetCustomerForFrontstore');
    Route::post('ajaxGetCustomerForAicashSelect', 'AjaxController@ajaxGetCustomerForAicashSelect');
    Route::post('ajaxGetCustomerAistockist', 'AjaxController@ajaxGetCustomerAistockist');
    Route::post('ajaxGetCustomerAgency', 'AjaxController@ajaxGetCustomerAgency');

    Route::post('fnCheckStock', 'AjaxController@fnCheckStock');

    Route::post('ajaxGetBusinessName', 'AjaxController@ajaxGetBusinessName');
    Route::post('ajaxGetIntroduce_id', 'AjaxController@ajaxGetIntroduce_id');
    Route::post('ajaxGetUpline_id', 'AjaxController@ajaxGetUpline_id');

    Route::post('ajaxGetAmphur', 'AjaxController@ajaxGetAmphur');
    Route::post('ajaxGetTambon', 'AjaxController@ajaxGetTambon');
    Route::post('ajaxGetZipcode', 'AjaxController@ajaxGetZipcode');

    Route::post('ajaxGetPayType', 'AjaxController@ajaxGetPayType');
    Route::post('ajaxGetLabelPayType', 'AjaxController@ajaxGetLabelPayType');
    Route::post('ajaxGetLabelOthersPrice', 'AjaxController@ajaxGetLabelOthersPrice');
    Route::post('ajaxGetVoucher', 'AjaxController@ajaxGetVoucher');

    Route::post('ajaxGetOrdersIDtoDeliveryAddr', 'AjaxController@ajaxGetOrdersIDtoDeliveryAddr');
    Route::post('ajaxGetOrdersIDtoDeliveryAddr02', 'AjaxController@ajaxGetOrdersIDtoDeliveryAddr02');

    // Route::post('ajaxFetchData', 'AjaxController@ajaxFetchData');
    // Route::get('ajaxFetchData', 'AjaxController@ajaxFetchData');
    Route::post('ajaxSelectAddr', 'AjaxController@ajaxSelectAddr');
    Route::post('ajaxSelectAddrEdit', 'AjaxController@ajaxSelectAddrEdit');

    Route::post('ajaxApprovePickupGoods', 'AjaxController@ajaxApprovePickupGoods');

    Route::post('ajaxAcceptCheckStock', 'AjaxController@ajaxAcceptCheckStock');

    Route::post('ajaxGetSetToWarehouse', 'AjaxController@ajaxGetSetToWarehouse');
    Route::post('ajaxGetSetToWarehouseBranch', 'AjaxController@ajaxGetSetToWarehouseBranch');
    Route::post('ajaxGetProduct', 'AjaxController@ajaxGetProduct');
    Route::post('ajaxGetProductPromotionCus', 'AjaxController@ajaxGetProductPromotionCus');
    Route::post('ajaxGetPromotionCode', 'AjaxController@ajaxGetPromotionCode');
    Route::post('ajaxGetPromotionName', 'AjaxController@ajaxGetPromotionName');
    Route::post('ajaxCheckCouponUsed', 'AjaxController@ajaxCheckCouponUsed');
    Route::post('ajaxGetFeeValue', 'AjaxController@ajaxGetFeeValue');
    Route::post('ajaxFeeCalculate', 'AjaxController@ajaxFeeCalculate');
    Route::post('ajaxShippingCalculate', 'AjaxController@ajaxShippingCalculate');
    Route::post('ajaxAccountBankId', 'AjaxController@ajaxAccountBankId');
    Route::post('ajaxApproveCouponCode', 'AjaxController@ajaxApproveCouponCode');
    Route::post('ajaxApproveGiftvoucherCode', 'AjaxController@ajaxApproveGiftvoucherCode');
    Route::post('ajaxCheckDBfrontstore', 'AjaxController@ajaxCheckDBfrontstore');

    Route::post('ajaxGetDBfrontstore', 'AjaxController@ajaxGetDBfrontstore');
    Route::post('ajaxCheckAddAiCash', 'AjaxController@ajaxCheckAddAiCash');
    Route::post('ajaxGetDBAddAiCash', 'AjaxController@ajaxGetDBAddAiCash');
    Route::post('ajaxCheckAddAiCashStatus', 'AjaxController@ajaxCheckAddAiCashStatus');

    Route::post('ajaxCalPriceFrontstore01', 'AjaxController@ajaxCalPriceFrontstore01');
    Route::post('ajaxCalPriceFrontstore02', 'AjaxController@ajaxCalPriceFrontstore02');
    Route::post('ajaxCalPriceFrontstore03', 'AjaxController@ajaxCalPriceFrontstore03');
    Route::post('ajaxCalPriceFrontstore04', 'AjaxController@ajaxCalPriceFrontstore04');
    Route::post('ajaxCalGiftVoucherPrice', 'AjaxController@ajaxCalGiftVoucherPrice');

    Route::post('ajaxGetAicash', 'AjaxController@ajaxGetAicash');

    Route::post('ajaxCalAddAiCashFrontstore', 'AjaxController@ajaxCalAddAiCashFrontstore');
    Route::post('ajaxCalSaveTransferType', 'AjaxController@ajaxCalSaveTransferType');

    Route::post('ajaxDelFileSlip', 'AjaxController@ajaxDelFileSlip');
    Route::post('ajaxDelFileSlip_02', 'AjaxController@ajaxDelFileSlip_02');
    Route::post('ajaxDelFileSlip_03', 'AjaxController@ajaxDelFileSlip_03');
    Route::post('ajaxDelFileSlip_04', 'AjaxController@ajaxDelFileSlip_04');
    Route::post('ajaxApproveFileSlip_04', 'AjaxController@ajaxApproveFileSlip_04');
    Route::post('ajaxChangeFileSlip_04', 'AjaxController@ajaxChangeFileSlip_04');

    Route::post('ajaxDelFileSlipGiftVoucher', 'AjaxController@ajaxDelFileSlipGiftVoucher');
    Route::post('ajaxClearCostFrontstore', 'AjaxController@ajaxClearCostFrontstore');
    Route::post('ajaxClearPayTypeFrontstore', 'AjaxController@ajaxClearPayTypeFrontstore');

    Route::post('ajaxForCheck_press_save', 'AjaxController@ajaxForCheck_press_save');

    Route::post('ajaxClearAfterSelChargerType', 'AjaxController@ajaxClearAfterSelChargerType');
    Route::post('ajaxClearAfterAddAiCash', 'AjaxController@ajaxClearAfterAddAiCash');


    Route::post('ajaxSaveGiftvoucherCode', 'AjaxController@ajaxSaveGiftvoucherCode');
    Route::post('ajaxGetAicashAmt', 'AjaxController@ajaxGetAicashAmt');

    Route::post('ajaxGiftVoucherSaveDate', 'AjaxController@ajaxGiftVoucherSaveDate');

    Route::post('ajaxFifoApproved', 'AjaxController@ajaxFifoApproved');
    Route::post('ajaxSetProductToBil', 'AjaxController@ajaxSetProductToBil');
    Route::post('ajaxMapConsignments', 'AjaxController@ajaxMapConsignments');

    Route::post('ajaxProcessTaxdata', 'AjaxController@ajaxProcessTaxdata');

    Route::post('ajaxProcessStockcard', 'AjaxController@ajaxProcessStockcard');
    Route::post('ajaxProcessStockcard_01', 'AjaxController@ajaxProcessStockcard_01');
    Route::post('ajaxOfferToApprove', 'AjaxController@ajaxOfferToApprove');

    Route::post('ajaxScanQrcodeProduct', 'AjaxController@ajaxScanQrcodeProduct');
    Route::post('ajaxDeleteQrcodeProduct', 'AjaxController@ajaxDeleteQrcodeProduct');
    Route::post('ajaxScanQrcodeProductPacking', 'AjaxController@ajaxScanQrcodeProductPacking');
    Route::post('ajaxScanQrcodeProductPackingRemark', 'AjaxController@ajaxScanQrcodeProductPackingRemark');
    Route::post('ajaxScanQrcodeProductPackingDelete', 'AjaxController@ajaxScanQrcodeProductPackingDelete');
    Route::post('ajaxScanQrcodeProductPackingDeleteAll', 'AjaxController@ajaxScanQrcodeProductPackingDeleteAll');
    Route::post('ajaxDeleteQrcodeProductPacking', 'AjaxController@ajaxDeleteQrcodeProductPacking');

    Route::post('ajaxProductPackingSize', 'AjaxController@ajaxProductPackingSize');
    Route::post('ajaxProductPackingWeight', 'AjaxController@ajaxProductPackingWeight');
    Route::post('ajaxProductPackingAmtBox', 'AjaxController@ajaxProductPackingAmtBox');
    Route::post('ajaxProductPackingAddBox', 'AjaxController@ajaxProductPackingAddBox');
    Route::post('ajaxProductPackingRemoveBox', 'AjaxController@ajaxProductPackingRemoveBox');

    Route::post('ajaxGetAmtInStock', 'AjaxController@ajaxGetAmtInStock');
    Route::post('ajaxGetProductDetail', 'AjaxController@ajaxGetProductDetail');

    Route::post('ajaxSyncStockToNotify', 'AjaxController@ajaxSyncStockToNotify');

    Route::post('ajaxGetCusToPayReceiptForSearch', 'AjaxController@ajaxGetCusToPayReceiptForSearch');
    Route::post('ajaxGetCusToPayReceiptAfterSave', 'AjaxController@ajaxGetCusToPayReceiptAfterSave');
    Route::post('ajaxGetCEUserRegis', 'AjaxController@ajaxGetCEUserRegis');
    Route::post('ajaxGetCe_regis_gift', 'AjaxController@ajaxGetCe_regis_gift');
    Route::post('ajaxGetCEQrcode', 'AjaxController@ajaxGetCEQrcode');

    Route::post('ajaxGetFilepath', 'AjaxController@ajaxGetFilepath');
    Route::post('ajaxGetFilepath02', 'AjaxController@ajaxGetFilepath02');


    Route::post('ajaxSaveChangePurchaseType', 'AjaxController@ajaxSaveChangePurchaseType');

    Route::post('ajaxCheckDescGiftvoucher', 'AjaxController@ajaxCheckDescGiftvoucher');

    Route::get('status_delivery', 'StatusDeliveryController@index');
    Route::post('status_delivery/datatable', 'StatusDeliveryController@Datatable');

    Route::resource('delivery', 'DeliveryController');
    Route::post('delivery/datatable', 'DeliveryController@Datatable')->name('delivery.datatable');
    Route::get('delivery_approve_to_wh/{id}', 'DeliveryController@delivery_approve_to_wh');

    Route::resource('stock_notify', 'Stock_notifyController');
    Route::post('stock_notify/datatable', 'Stock_notifyController@Datatable')->name('stock_notify.datatable');
    Route::post('stock_notify_dashboard/datatable', 'Stock_notifyController@DatatableDashboard')->name('stock_notify_dashboard.datatable');


    Route::resource('pick_pack', 'Pick_packController');
    Route::post('pick_pack/datatable', 'Pick_packController@Datatable')->name('pick_pack.datatable');

    Route::resource('pick_pack_packing', 'Pick_packPackingController');
    Route::post('pick_pack_packing/datatable', 'Pick_packPackingController@Datatable')->name('pick_pack_packing.datatable');

    Route::resource('pick_pack_packing_code', 'Pick_packPackingCodeController');
    Route::post('pick_pack_packing_code/datatable', 'Pick_packPackingCodeController@Datatable')->name('pick_pack_packing_code.datatable');

    Route::post('packing_list/datatable', 'Pick_packPackingCodeController@packing_list')->name('packing_list.datatable');
    Route::post('packing_list_for_fifo/datatable', 'Pick_packPackingCodeController@packing_list_for_fifo')->name('packing_list_for_fifo.datatable');
    Route::post('packing_list_for_fifo/datatable_report', 'Pick_packPackingCodeController@packing_list_for_fifo_report')->name('packing_list_for_fifo.datatable_report');
    Route::post('packing_list_for_fifo_02/datatable', 'Pick_packPackingCodeController@packing_list_for_fifo_02')->name('packing_list_for_fifo_02.datatable');

    Route::get('pay_requisition_001_report/consignments_approve/{approve_id}/{con_id}', 'Pick_packPackingCodeController@consignments_approve');
    Route::post('pay_requisition_001_report/consignments_remark/', 'Pick_packPackingCodeController@consignments_remark');

    Route::resource('pick_warehouse', 'Pick_warehouseController');
    Route::post('pick_warehouse/datatable', 'Pick_warehouseController@Datatable')->name('pick_warehouse.datatable');
    Route::post('pick_warehouse_scan_save', 'Pick_warehouseController@pick_warehouse_scan_save');
    Route::post('pick_warehouse_save_new_bill', 'Pick_warehouseController@pick_warehouse_save_new_bill');
    Route::get('pick_warehouse_del_packing/{p_id}', 'Pick_warehouseController@pick_warehouse_del_packing');

    Route::get('delete_test', 'Pick_warehouseController@delete_test');
    Route::get('pick_warehouse/{id}/edit_product', 'Pick_warehouseController@edit_product');
    Route::post('pick_warehouse_edit_product_store', 'Pick_warehouseController@pick_warehouse_edit_product_store');


// @@@@@@@@@@@@@@@@@@@ จ่ายสินค้าตามใบเสร็จ @@@@@@@@@@@@@@@@@@@
    // หน้าแรก
    Route::resource('pay_product_receipt_001', 'Pay_product_receipt_001Controller');
    Route::get('pay_product_receipt_001_clear/{id}', 'Pay_product_receipt_001Controller@pay_product_receipt_001_clear');
    Route::post('pay_product_receipt_tb1/datatable', 'Pay_product_receipt_001Controller@Datatable001')->name('pay_product_receipt_tb1.datatable');
    Route::post('pay_product_receipt_tb1/wait_orders', 'Pay_product_receipt_001Controller@wait_orders')->name('pay_product_receipt_tb1.wait_orders');

    Route::post('pay_product_receipt_tb2/datatable', 'Pay_product_receipt_001Controller@Datatable002')->name('pay_product_receipt_tb2.datatable');
    Route::get('pay_product_receipt_tb2/datatable', 'Pay_product_receipt_001Controller@Datatable002')->name('pay_product_receipt_tb2.datatable');

    Route::post('pay_product_receipt_tb3/datatable', 'Pay_product_receipt_001Controller@Datatable003')->name('pay_product_receipt_tb3.datatable');
    Route::get('pay_product_receipt_tb3/datatable', 'Pay_product_receipt_001Controller@Datatable003')->name('pay_product_receipt_tb3.datatable');

    Route::post('cancel-pay_product_receipt_001', 'Pay_product_receipt_001Controller@destroy');
    Route::post('cancel-some-pay_product_receipt_001', 'Pay_product_receipt_001Controller@destroy_some');

    Route::post('cancel-some-requisition_001', 'Pick_warehouse_fifoController@destroy_some');

    Route::post('ajaxApproveProductSent', 'Pay_product_receipt_001Controller@ajaxApproveProductSent');

    Route::post('ajaxSearch_bill_db_orders', 'Products_fifo_billController@ajaxSearch_bill_db_orders');
    Route::post('ajaxSearch_bill_db_orders002', 'Products_fifo_billController@ajaxSearch_bill_db_orders002');
    Route::get('ajaxSearch_bill_db_orders002', 'Products_fifo_billController@ajaxSearch_bill_db_orders002');

    Route::post('ajaxSearch_requisition_db_orders', 'Pick_warehouse_fifoController@ajaxSearch_requisition_db_orders');
    Route::post('ajaxSearch_requisition_db_orders002', 'Pick_warehouse_fifoController@ajaxSearch_requisition_db_orders002');

    Route::post('ajaxSavePay_product_receipt', 'Pay_product_receipt_001Controller@ajaxSavePay_product_receipt');

    Route::post('ajaxSavePay_requisition', 'Pay_requisition_001Controller@ajaxSavePay_requisition');
    Route::get('ajaxSavePay_requisition', 'Pay_requisition_001Controller@ajaxSavePay_requisition');
    Route::post('ajaxSavePay_requisition_edit', 'Pay_requisition_001Controller@ajaxSavePay_requisition_edit');
    Route::get('ajaxSavePay_requisition_edit', 'Pay_requisition_001Controller@ajaxSavePay_requisition_edit');

    Route::post('ajaxCHECKPay_product_receipt', 'Pay_product_receipt_001Controller@ajaxCHECKPay_product_receipt');

    Route::resource('pay_product_receipt', 'Pay_product_receiptController');
    Route::post('pay_product_receipt/datatable', 'Pay_product_receiptController@Datatable')->name('pay_product_receipt.datatable');

    Route::post('pay_product_receipt/scan_qr/{id}', 'Check_stock_accountController@ScanQr');
    Route::get('pay_product_receipt/scan_qr/{id}', 'Check_stock_accountController@ScanQr');




    Route::post('pay_product_receipt_send/datatable', 'Products_fifo_billController@DatatableToSend1')->name('pay_product_receipt_send.datatable');

    Route::post('pay_product_receipt_tb4/datatable', 'Pay_product_receipt_001Controller@Datatable004')->name('pay_product_receipt_tb4.datatable');
    Route::post('pay_product_receipt_send3/datatable', 'Products_fifo_billController@DatatableToSend3')->name('pay_product_receipt_send3.datatable');

    Route::post('pay_product_receipt_tb5/datatable', 'Products_fifo_billController@DatatablePayReceiptFIFO')->name('pay_product_receipt_tb5.datatable');
    Route::post('pay_product_receipt_tb6/datatable', 'Pay_product_receipt_001Controller@Datatable006')->name('pay_product_receipt_tb6.datatable');

    Route::post('pay_product_receipt_tb7/datatable', 'Pay_product_receipt_001Controller@Datatable007')->name('pay_product_receipt_tb7.datatable');
    Route::post('pay_product_receipt_tb8/datatable', 'Pay_product_receipt_001Controller@Datatable008')->name('pay_product_receipt_tb8.datatable');
    Route::post('pay_product_receipt_tb9FIFO/datatable', 'Products_fifo_billController@Datatable009FIFO')->name('pay_product_receipt_tb9FIFO.datatable');

    Route::post('pay_product_receipt_tb10FIFO/datatable', 'Products_fifo_billController@Datatable010FIFO')->name('pay_product_receipt_tb10FIFO.datatable');

// @@@@@@@@@@@@@@@@@@@ จ่ายสินค้าตามใบเบิก @@@@@@@@@@@@@@@@@@@
    // หน้าแรก
    Route::resource('pay_requisition_001', 'Pay_requisition_001Controller');
    Route::get('pay_requisition_001_remain', 'Pay_requisition_001Controller@pay_requisition_001_remain');
    Route::post('pay_requisition_tb1/datatable', 'Pay_requisition_001Controller@Datatable001')->name('pay_requisition_tb1.datatable');
    Route::get('pay_requisition_001_report', 'Pay_requisition_001Controller@pay_requisition_001_report');

    Route::post('pick_warehouse_tb_0001/datatable', 'Pick_warehouse_fifoController@Datatable0001')->name('pick_warehouse_tb_0001.datatable');

    Route::post('pick_warehouse_tb_0002/datatable', 'Pick_warehouse_fifoController@Datatable0002FIFO')->name('pick_warehouse_tb_0002.datatable');
    // ฝากไว้ก่อน
    Route::post('pick_warehouse_tb_0002_edit/datatable', 'Pick_warehouseController@Datatable0002')->name('pick_warehouse_tb_0002_edit.datatable');

    Route::post('pick_warehouse_tb_0003/datatable', 'Pick_warehouse_fifoController@Datatable0003')->name('pick_warehouse_tb_0003.datatable');
    Route::post('pick_warehouse_tb_0004/datatable', 'Pick_warehouse_fifoController@Datatable0004')->name('pick_warehouse_tb_0004.datatable');

    Route::post('pay_requisition_tb3/datatable', 'Pay_requisition_001Controller@Datatable003')->name('pay_requisition_tb3.datatable');
    Route::get('pay_requisition_tb3/datatable', 'Pay_requisition_001Controller@Datatable003')->name('pay_requisition_tb3.datatable');


    Route::post('warehouse_qr_0001/datatable', 'Pick_warehouseController@warehouse_qr_0001')->name('warehouse_qr_0001.datatable');
    Route::post('warehouse_qr_0002/datatable', 'Pick_warehouseController@warehouse_qr_0002')->name('warehouse_qr_0002.datatable');
    Route::post('warehouse_qr_0002/warehouse_qr_0002_pack_scan', 'Pick_warehouseController@warehouse_qr_0002_pack_scan');
    Route::post('warehouse_qr_00022/datatable', 'Pick_warehouseController@warehouse_qr_00022')->name('warehouse_qr_00022.datatable');
    Route::post('warehouse_qr_00022/warehouse_qr_00022_single_scan', 'Pick_warehouseController@warehouse_qr_00022_single_scan');

    Route::post('warehouse_tb_000/datatable', 'Pick_warehouseController@warehouse_tb_000')->name('warehouse_tb_000.datatable');
    Route::post('warehouse_tb_001/datatable', 'Pick_warehouseController@warehouse_tb_001')->name('warehouse_tb_001.datatable');
    Route::post('warehouse_address_sent/datatable', 'Pick_warehouseController@warehouse_address_sent')->name('warehouse_address_sent.datatable');

    Route::post('ajaxPackingFinished', 'AjaxController@ajaxPackingFinished');
    Route::post('ajaxShippingFinished', 'AjaxController@ajaxShippingFinished');
    Route::post('cancel-status-packing-sent', 'AjaxController@ajaxCacelStatusPackingSent');

    Route::post('cancelBill', 'AjaxController@cancelBill');

//

// @@@@@@@@@@@@@@@@@@@ StockMovement @@@@@@@@@@@@@@@@@@@
    Route::post('truncateStockMovement', 'AjaxController@truncateStockMovement');
    Route::post('insertStockMovement_From_db_general_receive', 'AjaxController@insertStockMovement_From_db_general_receive');
    Route::post('insertStockMovement_From_db_general_takeout', 'AjaxController@insertStockMovement_From_db_general_takeout');
    Route::post('insertStockMovement_From_db_stocks_account', 'AjaxController@insertStockMovement_From_db_stocks_account');
    Route::post('insertStockMovement_From_db_products_borrow_code', 'AjaxController@insertStockMovement_From_db_products_borrow_code');
    Route::post('insertStockMovement_From_db_transfer_warehouses_code', 'AjaxController@insertStockMovement_From_db_transfer_warehouses_code');
    Route::post('insertStockMovement_From_db_transfer_branch_code', 'AjaxController@insertStockMovement_From_db_transfer_branch_code');
    Route::post('insertStockMovement_From_db_pay_product_receipt_001', 'AjaxController@insertStockMovement_From_db_pay_product_receipt_001');
    Route::post('insertStockMovement_From_db_stocks_return', 'AjaxController@insertStockMovement_From_db_stocks_return');
    Route::post('insertStockMovement_From_db_pay_requisition_001', 'AjaxController@insertStockMovement_From_db_pay_requisition_001');

    Route::post('insertStockMovement_Final', 'AjaxController@insertStockMovement_Final');



// @@@@@@@@@@@@@@@@@@@ จ่ายสินค้าตามใบเสร็จ @@@@@@@@@@@@@@@@@@@
    // หน้าแรก
    Route::resource('pay_product_packing_list', 'Pay_product_packing_listController');
    Route::post('pay_product_packing_list/datatable', 'Pay_product_packing_listController@Datatable')->name('pay_product_packing_list.datatable');

    Route::resource('pay_product_packing', 'Pay_product_packingController');
    Route::post('pay_product_packing/datatable', 'Pay_product_packingController@Datatable')->name('pay_product_packing.datatable');


// @@@@@@@@@@@@@@@@@@@ จ่ายสินค้าตามใบเสร็จ @@@@@@@@@@@@@@@@@@@

    Route::get('delivery/pdf01/{id}', 'AjaxController@createPDFCoverSheet01');
    Route::post('delivery/print_receipt01/{id}', 'AjaxController@createPDFReceipt01');
    Route::get('delivery/print_receipt01/{id}', 'AjaxController@createPDFReceipt01');
    Route::post('frontstore/print_receipt022/{id}', 'AjaxController@createPDFReceipt022');
    Route::get('frontstore/print_receipt022/{id}', 'AjaxController@createPDFReceipt022');
    Route::get('frontstore/print_receipt_02/{id}', 'AjaxController@createPDFReceipt02');
    Route::get('frontstore/print_receipt_packing/{id}', 'AjaxController@createPDFReceiptPacking');

    Route::get('frontstore/print_receipt_022/{id}', 'PrintController@frontstore_print_receipt_022');
    Route::get('frontstore/print_receipt_lading/{id}', 'PrintController@print_receipt_lading');
    Route::get('frontstore/print_receipt_023/{id}', 'PrintController@frontstore_print_receipt_023');
    Route::get('frontstore/print_receipt_023/{id}/{pick_pack_packing_code_id_fk}', 'PrintController@frontstore_print_receipt_023');

    Route::get('delivery/pdf02/{id}', 'AjaxController@createPDFCoverSheet02');
    Route::get('delivery/print_receipt02/{id}', 'AjaxController@createPDFReceipt02');

    Route::get('add_ai_cash/print_receipt/{id}', 'AjaxController@createPDFReceiptAicash');

    Route::resource('taxdata', 'TaxdataController');
    Route::post('taxdata/datatable', 'TaxdataController@Datatable')->name('taxdata.datatable');
    Route::post('taxdata/datatable2', 'TaxdataController@Datatable2')->name('taxdata.datatable2');
    Route::get('taxdata/taxtvi/{id}', 'TaxdataController@createPDFTaxtvi');

    Route::resource('ce_regis', 'Ce_regisController');
    Route::post('ce_regis/datatable', 'Ce_regisController@Datatable')->name('ce_regis.datatable');

    Route::get('frontstore/get_order_history_status', 'FrontstoreController@getOrderHistoryStatus')->name('frontstore.get_order_history_status');
    Route::resource('frontstore', 'FrontstoreController');
    Route::post('frontstore/datatable', 'FrontstoreController@Datatable')->name('frontstore.datatable');
    Route::get('frontstore/print_receipt/{id}', 'AjaxController@createPDFReceiptFrontstore');
    Route::get('frontstore/viewdata/{id}', 'FrontstoreController@viewdata');

    Route::get('upPro', 'FrontstoreController@upPro');

    Route::post('getSumCostActionUser', 'FrontstoreController@getSumCostActionUser');
    Route::post('getPV_Amount', 'FrontstoreController@getPV_Amount');


    Route::get('check_stock_account/print_receipt/{id}', 'AjaxController@createPDFStock_account');

    Route::get('pick_warehouse/print_receipt_03/{id}', 'AjaxController@createPDFReceiptQr');
    Route::get('pick_warehouse/print_envelope/{id}', 'AjaxController@createPDFEnvelope');
    Route::get('pick_warehouse/print_requisition/{id}', 'AjaxController@createPDFRequisition');
    Route::get('pick_warehouse/print_requisition_detail/{id}/{packing_code_id}', 'AjaxController@createPDFRequisitionDetail');
    Route::get('pick_warehouse/print_requisition_detail_real/{packing_code_id}', 'AjaxController@createPDFRequisitionDetailReal');
    Route::get('pick_warehouse/print_requisition_detail_real_remain/{packing_code_id}', 'AjaxController@createPDFRequisitionDetailRealRemain');

    Route::post('ajaxSelectWh', 'AjaxController@ajaxSelectWh');


    Route::resource('product_in_cause', 'Product_in_causeController');
    Route::post('product_in_cause/datatable', 'Product_in_causeController@Datatable')->name('product_in_cause.datatable');

    Route::resource('product_status', 'Product_statusController');
    Route::post('product_status/datatable', 'Product_statusController@Datatable')->name('product_status.datatable');

    Route::resource('get_money_back', 'Get_money_backController');
    Route::post('get_money_back/datatable', 'Get_money_backController@Datatable')->name('get_money_back.datatable');

    Route::resource('get_money_back_type', 'Get_money_back_typeController');
    Route::post('get_money_back_type/datatable', 'Get_money_back_typeController@Datatable')->name('get_money_back_type.datatable');

    Route::resource('product_out_cause', 'Product_out_causeController');
    Route::post('product_out_cause/datatable', 'Product_out_causeController@Datatable')->name('product_out_cause.datatable');

    Route::resource('ce_regis_gift', 'Ce_regis_giftController');
    Route::post('ce_regis_gift/datatable', 'Ce_regis_giftController@Datatable')->name('ce_regis_gift.datatable');


    Route::resource('delivery_packing', 'DeliveryPackingController');
    Route::post('delivery_packing/datatable', 'DeliveryPackingController@Datatable')->name('delivery_packing.datatable');

    Route::resource('delivery_packing_code', 'DeliveryPackingCodeController');
    Route::post('delivery_packing_code/datatable', 'DeliveryPackingCodeController@Datatable')->name('delivery_packing_code.datatable');
    Route::post('delivery_packing_code/datatable2', 'DeliveryPackingCodeController@Datatable2')->name('delivery_packing_code.datatable2');

    Route::resource('pick_warehouse_packing_code', 'Pick_warehousePackingCodeController');
    Route::post('pick_warehouse_packing_code/datatable', 'Pick_warehousePackingCodeController@Datatable')->name('pick_warehouse_packing_code.datatable');


    Route::resource('delivery_approve', 'Delivery_approveController');
    Route::post('delivery_approve/datatable', 'Delivery_approveController@Datatable')->name('delivery_approve.datatable');

// รอจัดเบิกสินค้า
    Route::resource('pickup_goods', 'Pickup_goodsController');
    Route::post('pickup_goods/datatable', 'Pickup_goodsController@Datatable')->name('pickup_goods.datatable');

    Route::get('pickup_goods/print_receipt/{id}', 'AjaxController@createPDFReceipt03');
    Route::get('pickup_goods/print_receipt_pack/{id}', 'AjaxController@createPDFReceipt04');

// จัดเบิก
    Route::resource('pickup_goods_set', 'Pickup_goods_setController');
    Route::post('pickup_goods_set/datatable', 'Pickup_goods_setController@Datatable')->name('pickup_goods_set.datatable');

// ใบสั่งซื้อรออนุมัติ
    Route::resource('po_approve', 'Po_approveController');
    Route::any('po_approve_update_other', 'Po_approveController@po_approve_update_other');
    Route::post('po_approve/datatable', 'Po_approveController@Datatable')->name('po_approve.datatable');
    Route::post('po_approve_edit/datatable', 'Po_approveController@DatatableEdit')->name('po_approve_edit.datatable');
    Route::post('po_approve_edit_other/datatable', 'Po_approveController@DatatableEditOther')->name('po_approve_edit_other.datatable');
    Route::post('po_approve_edit_other_ai/datatable', 'Po_approveController@DatatableEditOther_ai')->name('po_approve_edit_other_ai.datatable');
    Route::post('po_approve_edit_other/datatable_sum', 'Po_approveController@DatatableEditOtherSum')->name('po_approve_edit_other.datatable_sum');
    Route::post('po_approve_set/datatable', 'Po_approveController@DatatableSet')->name('po_approve_set.datatable');

    Route::get('po_approve/form_aicash/{id}', 'Add_ai_cashController@approve');

// คอร์สรออนุมัติ
    Route::resource('course_approve', 'Course_approveController');
    Route::post('course_approve/datatable', 'Course_approveController@Datatable')->name('course_approve.datatable');
    Route::post('course_approve_set/datatable', 'Course_approveController@DatatableSet')->name('course_approve_set.datatable');

// ใบโอน
    Route::get('transfer_warehouses/print_transfer/{id}', 'AjaxController@createPDFTransfer');
    Route::get('transfer_branch/print_transfer/{id}', 'AjaxController@createPDFTransfer_branch');
    Route::get('transfer_branch/print_transfer_tr/{id}', 'AjaxController@createPDFTransfer_branch_tr');

    Route::resource('check_orders', 'Check_ordersController');
    Route::post('check_orders/datatable', 'Check_ordersController@Datatable')->name('check_orders.datatable');

    Route::resource('check_orders_list', 'Check_orders_listController');
    Route::post('check_orders_list/datatable', 'Check_orders_listController@Datatable')->name('check_orders_list.datatable');


    Route::post('commission_transfer/datatable', 'Commission_transferController@Datatable')->name('commission_transfer.datatable');
    Route::get('commission_transfer/modal_commission_transfer','Commission_transferController@modal_commission_transfer')->name('commission_transfer.modal_commission_transfer');
    Route::resource('commission_transfer', 'Commission_transferController');
    Route::post('commission_transfer_pdf', 'AjaxController@commission_transfer_pdf');

    Route::resource('commission_aistockist', 'Commission_aistockistController');
    Route::get('commission_transfer_aistockist/modal_commission_transfer_aistockist','Commission_aistockistController@modal_commission_transfer_aistockist')->name('commission_transfer_aistockist.modal_commission_transfer_aistockist');
    Route::post('commission_aistockist/datatable', 'Commission_aistockistController@Datatable')->name('commission_aistockist.datatable');
    Route::post('commission_aistockist_pdf', 'AjaxController@commission_aistockist_pdf');

    Route::resource('commission_transfer_af', 'Commission_transfer_afController');
    Route::post('commission_af/datatable', 'Commission_transfer_afController@Datatable')->name('commission_af.datatable');
    Route::post('commission_transfer_af_pdf', 'AjaxController@commission_transfer_af_pdf');

    Route::get('total_thai_cambodia/datatable_total_all', 'Total_thai_cambodiaController@datatable_total_all')->name('total_thai_cambodia.datatable_total_all');
    Route::resource('total_thai_cambodia', 'Total_thai_cambodiaController');
    Route::resource('total_thai_cambodia_ai', 'Total_thai_cambodia_aiController');


    Route::post('total_thai_cambodia/datatable', 'Total_thai_cambodiaController@Datatable')->name('total_thai_cambodia.datatable');
    Route::post('total_thai_cambodia_branch/datatable', 'Total_thai_cambodiaController@Datatable_branch')->name('total_thai_cambodia_branch.datatable');
    Route::post('total_thai_cambodia_aicash/datatable', 'Total_thai_cambodiaController@Datatable_aicash')->name('total_thai_cambodia_aicash.datatable');
    Route::post('total_thai_cambodia_aicash_full/datatable', 'Total_thai_cambodia_aiController@Datatable_aicash_full')->name('total_thai_cambodia_aicash_full.datatable');
    Route::post('total_thai_cambodia/datatable_total_thai', 'Total_thai_cambodiaController@DatatableTotalThai')->name('total_thai_cambodia.datatable_total_thai');
    Route::post('total_thai_cambodia/datatable_total_cambodia', 'Total_thai_cambodiaController@DatatableTotalCambodia')->name('total_thai_cambodia.datatable_total_cambodia');

    Route::post('total_thai_cambodia_pdf', 'AjaxController@total_thai_cambodia_pdf');
    Route::post('total_thai_cambodia_excel', 'AjaxController@total_thai_cambodia_excel');
    Route::post('total_thai_cambodia_ai_pdf', 'AjaxController@total_thai_cambodia_ai_pdf');

    Route::resource('cambodia_account', 'Cambodia_accountController');
    Route::post('cambodia_account/datatable', 'Cambodia_accountController@Datatable')->name('cambodia_account.datatable');


    Route::resource('transfer_corporate_members', 'Transfer_corporate_membersController');
    Route::post('transfer_corporate_members/datatable', 'Transfer_corporate_membersController@Datatable')->name('transfer_corporate_members.datatable');


    Route::resource('po_in', 'Po_inController');
    Route::post('po_in/datatable', 'Po_inController@Datatable')->name('po_in.datatable');

    Route::resource('po_supplier', 'Po_supplierController');
    Route::post('po_supplier/datatable', 'Po_supplierController@Datatable')->name('po_supplier.datatable');
    Route::get('po_supplier_delete_list/{id}', 'Po_supplierController@po_supplier_delete_list');

    Route::resource('po_supplier_products', 'Po_supplier_productsController');
    Route::post('po_supplier_products/datatable', 'Po_supplier_productsController@Datatable')->name('po_supplier_products.datatable');
    Route::get('po_supplier_products/create/{id}', 'Po_supplier_productsController@create');

    // Route::resource('po_supplier_products_get', 'Po_supplier_products_getController');
    // Route::post('po_supplier_products_get/datatable', 'Po_supplier_products_getController@Datatable')->name('po_supplier_products_get.datatable');
    // Route::get('po_supplier_products_get/create/{id}', 'Po_supplier_products_getController@create');

    Route::get('po_supplier_products/print_receipt/{id}', 'AjaxController@createPDFpo_supplier_products');

// Start ใบ PO รับสินค้า
    Route::resource('po_receive', 'Po_receiveController');
    Route::get('destroy_po_supplier_products_receive/{id}', 'Po_receiveController@destroy_po_supplier_products_receive');
    Route::post('po_receive/datatable', 'Po_receiveController@Datatable')->name('po_receive.datatable');
    Route::post('po_receive_update_note3', 'Po_receiveController@po_receive_update_note3');

    Route::resource('po_receive_products', 'Po_receive_productsController');
    Route::post('po_receive_products/datatable', 'Po_receive_productsController@Datatable')->name('po_receive_products.datatable');
    Route::get('po_receive_products/create/{id}', 'Po_receive_productsController@create');

    Route::resource('po_receive_products_get', 'Po_receive_products_getController');
    Route::get('po_receive_products_get_approve/{id}', 'Po_receive_products_getController@po_receive_products_get_approve');

    Route::post('po_receive_products_get/datatable', 'Po_receive_products_getController@Datatable')->name('po_receive_products_get.datatable');

    Route::post('po_supplier_products_receive/datatable', 'Po_receive_products_getController@DatatablePO_receive')->name('po_supplier_products_receive.datatable');

    Route::get('po_receive_products_get/create/{id}', 'Po_receive_products_getController@create');

    Route::get('po_receive_products/print_receipt/{id}', 'AjaxController@createPDFpo_receive_products');

    Route::post('ajaxCheckAmt_get_po_product', 'AjaxController@ajaxCheckAmt_get_po_product');

// End ใบ PO รับสินค้า

// Start รับสินค้าเข้าจากการโอนระหว่างสาขา
    Route::resource('transfer_branch_get', 'Transfer_branch_getController');
    Route::post('transfer_branch_get/datatable', 'Transfer_branch_getController@Datatable')->name('transfer_branch_get.datatable');


    Route::post('transfer_branch_product/datatable', 'Transfer_branchController@DatatableProduct')->name('transfer_branch_product.datatable');

    Route::get('transfer_branch_get/noget/{id}', 'Transfer_branch_getController@noget');

    Route::resource('transfer_branch_get_products', 'Transfer_branch_get_productsController');
    Route::post('transfer_branch_get_products_defective', 'Transfer_branch_get_productsController@transfer_branch_get_products_defective');

    Route::post('transfer_branch_get_products/datatable', 'Transfer_branch_get_productsController@Datatable')->name('transfer_branch_get_products.datatable');

    Route::post('transfer_branch_get_products_03/datatable', 'Transfer_branch_get_productsController@Datatable03')->name('transfer_branch_get_products_03.datatable');

    Route::post('transfer_branch_get_products_receive/datatable', 'Transfer_branch_get_productsController@Datatable02')->name('transfer_branch_get_products_receive.datatable');

    Route::post('ajaxCheckAmt_get_transfer_branch_get_products', 'AjaxController@ajaxCheckAmt_get_transfer_branch_get_products');

    Route::post('transfer_branch_get_products_back/datatable', 'Transfer_branch_get_productsController@DatatableBack')->name('transfer_branch_get_products_back.datatable');

// End รับสินค้าเข้าจากการโอนระหว่างสาขา

    Route::resource('general_receive', 'General_receiveController');
    Route::post('general_receive/datatable', 'General_receiveController@Datatable')->name('general_receive.datatable');

    Route::resource('check_stock', 'Check_stockController');
    Route::post('check_stock/datatable', 'Check_stockController@Datatable')->name('check_stock.datatable');
    Route::post('check_stock_borrow/datatable', 'Check_stockController@DatatableBorrow')->name('check_stock_borrow.datatable');
    Route::post('check_stock_transfer_warehouses/datatable', 'Check_stockController@DatatableTransfer_warehouses')->name('check_stock_transfer_warehouses.datatable');
    Route::post('check_stock_transfer_branch/datatable', 'Check_stockController@DatatableTransfer_branch')->name('check_stock_transfer_branch.datatable');


    Route::resource('check_stock_check', 'Check_stock_checkController');
    Route::post('check_stock_check/datatable', 'Check_stock_checkController@Datatable')->name('check_stock_check.datatable');
    Route::post('check_stock_check_02/datatable', 'Check_stock_checkController@Datatable02')->name('check_stock_check_02.datatable');

    Route::resource('member_regis', 'Member_regisController');
    Route::post('member_regis/datatable', 'Member_regisController@Datatable')->name('member_regis.datatable');
    Route::post('member_regis02/datatable', 'Member_regisController@Datatable02')->name('member_regis02.datatable');


    Route::resource('member_pv', 'Member_pvController');
    Route::post('member_pv/datatable', 'Member_pvController@Datatable')->name('member_pv.datatable');


    Route::resource('stocks_account_code', 'Stocks_account_codeController');
    Route::post('stocks_account_code/datatable', 'Stocks_account_codeController@Datatable')->name('stocks_account_code.datatable');


    Route::resource('stock_card', 'Stock_cardController');
    Route::post('stock_card/datatable', 'Stock_cardController@Datatable')->name('stock_card.datatable');

    Route::get('check_stock/stock_card/{product_id_fk}', 'Check_stockController@stock_card');
    Route::get('check_stock/stock_card/{product_id_fk}', 'Check_stockController@stock_card');
    Route::get('check_stock/stock_card/{product_id_fk}/{business_location_id_fk}', 'Check_stockController@stock_card');
    Route::get('check_stock/stock_card/{product_id_fk}/{business_location_id_fk}/{branch_id_fk}', 'Check_stockController@stock_card');
    Route::get('check_stock/stock_card/{product_id_fk}/{business_location_id_fk}/{branch_id_fk}/{warehouse_id_fk}', 'Check_stockController@stock_card');
    Route::get('check_stock/stock_card/{product_id_fk}/{business_location_id_fk}/{branch_id_fk}/{warehouse_id_fk}/{zone_id_fk}', 'Check_stockController@stock_card');
    Route::get('check_stock/stock_card/{product_id_fk}/{business_location_id_fk}/{branch_id_fk}/{warehouse_id_fk}/{zone_id_fk}/{shelf_id_fk}', 'Check_stockController@stock_card');
    Route::get('check_stock/stock_card/{product_id_fk}/{business_location_id_fk}/{branch_id_fk}/{warehouse_id_fk}/{zone_id_fk}/{shelf_id_fk}/{shelf_floor}', 'Check_stockController@stock_card');
    Route::get('check_stock/stock_card/{product_id_fk}/{business_location_id_fk}/{branch_id_fk}/{warehouse_id_fk}/{zone_id_fk}/{shelf_id_fk}/{shelf_floor}/{lot_number}', 'Check_stockController@stock_card');
    Route::get('check_stock/stock_card/{product_id_fk}/{business_location_id_fk}/{branch_id_fk}/{warehouse_id_fk}/{zone_id_fk}/{shelf_id_fk}/{shelf_floor}/{lot_number}/{lot_expired_date_s}', 'Check_stockController@stock_card');
    Route::get('check_stock/stock_card/{product_id_fk}/{business_location_id_fk}/{branch_id_fk}/{warehouse_id_fk}/{zone_id_fk}/{shelf_id_fk}/{shelf_floor}/{lot_number}/{lot_expired_date_s}/{lot_expired_date_e}', 'Check_stockController@stock_card');


    Route::resource('stock_card_01', 'Stock_card_01Controller');
    Route::post('stock_card_01/datatable', 'Stock_card_01Controller@Datatable')->name('stock_card_01.datatable');


    Route::get('check_stock/print/{id}/{lot_number}', 'AjaxController@createPDFStock_card');
    Route::get('pick_warehouse/print/{id}', 'AjaxController@createPDFPick_warehouse');

    Route::post('pick_warehouse/{id}/qr', 'Pick_warehouseController@qr');
    Route::get('pick_warehouse/{id}/qr', 'Pick_warehouseController@qr');

    Route::post('pick_warehouse/{id}/qr1', 'Pick_warehouseController@qr1');
    Route::get('pick_warehouse/{id}/qr1', 'Pick_warehouseController@qr1');

    Route::post('pick_warehouse/{id}/cancel', 'Pick_warehouseController@cancel');
    Route::get('pick_warehouse/{id}/cancel', 'Pick_warehouseController@cancel');

    Route::get('qr_show/{oid}/{pid}/{proid}/{p_list}', 'Pick_warehouseController@qr_show');
    Route::post('qr_show_import', 'Pick_warehouseController@qr_show_import');


    Route::resource('supplier', 'SupplierController');
    Route::post('supplier/datatable', 'SupplierController@Datatable')->name('supplier.datatable');


    Route::resource('pick_warehouse_fifo', 'Pick_warehouse_fifoController');
    Route::post('pick_warehouse_fifo/datatable', 'Pick_warehouse_fifoController@Datatable')->name('pick_warehouse_fifo.datatable');
    Route::post('calFifo', 'Pick_warehouse_fifoController@calFifo');
    Route::post('calFifo_edit', 'Pick_warehouse_fifoController@calFifo_edit');

    Route::resource('pick_warehouse_fifo_topicked', 'Pick_warehouse_fifo_topickedController');
    Route::post('pick_warehouse_fifo_topicked/datatable', 'Pick_warehouse_fifo_topickedController@Datatable')->name('pick_warehouse_fifo_topicked.datatable');

    Route::resource('pick_warehouse_fifo_no', 'Pick_warehouse_fifo_noController');
    Route::post('pick_warehouse_fifo_no/datatable', 'Pick_warehouse_fifo_noController@Datatable')->name('pick_warehouse_fifo_no.datatable');

    Route::resource('check_stock_account', 'Check_stock_accountController');
    Route::post('check_stock_account/datatable', 'Check_stock_accountController@Datatable')->name('check_stock_account.datatable');
    Route::post('check_stock_account/adjust/{id}', 'Check_stock_accountController@Adjust');
    Route::get('check_stock_account/adjust/{id}', 'Check_stock_accountController@Adjust');


    Route::resource('check_money_daily', 'Check_money_dailyController');
    Route::post('check_money_daily/datatable', 'Check_money_dailyController@DatatableSentMoney')->name('check_money_daily.datatable');
    Route::post('check_money_daily/datatable_ai', 'Check_money_dailyController@DatatableSentMoney_ai')->name('check_money_daily.datatable_ai');
    Route::post('check_money_daily02/datatable', 'Check_money_dailyController@DatatableTotal')->name('check_money_daily02.datatable');
    Route::post('check_money_daily02/datatable_ai', 'Check_money_dailyController@DatatableTotal_ai')->name('check_money_daily02_ai.datatable');
    Route::get('check_money_daily_report', 'Check_money_dailyController@check_money_daily_report');
    Route::post('check_money_daily_report/datatable', 'Check_money_dailyController@DatatableTotal_report')->name('check_money_daily02.datatable_report');
    Route::get('check_money_daily_ai/{id}/edit', 'Check_money_dailyController@check_money_daily_ai_edit')->name('backend.check_money_daily_ai.index');
    Route::post('check_money_daily_ai_store', 'Check_money_dailyController@check_money_daily_ai_store')->name('backend.check_money_daily_ai.store');
    Route::post('check_money_daily_ai_update', 'Check_money_dailyController@check_money_daily_ai_update')->name('backend.check_money_daily_ai.update');

    Route::resource('promotion_code', 'PromotionCodeController');
    Route::post('promotion_code/datatable', 'PromotionCodeController@Datatable')->name('promotion_code.datatable');

    Route::resource('promotion_cus', 'Promotion_cusController');
    Route::post('promotion_cus/datatable', 'Promotion_cusController@Datatable')->name('promotion_cus.datatable');
    Route::post('promotion_cus/plus', 'Promotion_cusController@plus');


    Route::resource('giftvoucher_code', 'GiftvoucherCodeController');
    Route::post('giftvoucher_code/datatable', 'GiftvoucherCodeController@Datatable')->name('giftvoucher_code.datatable');
    Route::post('giftvoucher_code/plus', 'GiftvoucherCodeController@plus');

    Route::resource('giftvoucher_cus', 'GiftvoucherCusController');
    Route::post('giftvoucher_cus/datatable', 'GiftvoucherCusController@Datatable')->name('giftvoucher_cus.datatable');
    Route::post('giftvoucher_cus/plus', 'GiftvoucherCusController@plus');


    Route::resource('promotion_code_product', 'Promotion_code_productController');
    Route::post('promotion_code_product/datatable', 'Promotion_code_productController@Datatable')->name('promotion_code_product.datatable');
    Route::get('promotion_code_product/create/{id}', 'Promotion_code_productController@create');

    Route::resource('promotion_cus_products', 'Promotion_cus_productsController');
    Route::post('promotion_cus_products/datatable', 'Promotion_cus_productsController@Datatable')->name('promotion_cus_products.datatable');

    Route::resource('productsList', 'ProductsListController');
    Route::post('productsList/datatable', 'ProductsListController@Datatable')->name('productsList.datatable');

    Route::resource('frontstorelist', 'FrontstorelistController');
    Route::post('frontstorelist/datatable', 'FrontstorelistController@Datatable')->name('frontstorelist.datatable');
    Route::post('frontstorelist/datatablePro', 'FrontstorelistController@DatatablePro')->name('frontstorelist_pro.datatable');
    Route::post('frontstorelist/plus', 'FrontstorelistController@plus');
    Route::post('frontstorelist/plusPromotion', 'FrontstorelistController@plusPromotion');
    Route::post('frontstorelist/minusPromotion', 'FrontstorelistController@minusPromotion');
    Route::post('frontstorelist/minus', 'FrontstorelistController@minus');

    Route::resource('transfer_choose', 'Transfer_chooseController');
    Route::post('transfer_choose/datatable', 'Transfer_chooseController@Datatable')->name('transfer_choose.datatable');

    Route::resource('transfer_choose_branch', 'Transfer_choose_branchController');
    Route::post('transfer_choose_branch/datatable', 'Transfer_choose_branchController@Datatable')->name('transfer_choose_branch.datatable');
    Route::get('transfer_choose_branch_delete/{id}', 'Transfer_choose_branchController@destroy');

    Route::resource('general_takeout', 'General_takeoutController');
    Route::get('general_takeout_print/{id}', 'General_takeoutController@general_takeout_print');
    Route::post('general_takeout/datatable', 'General_takeoutController@Datatable')->name('general_takeout.datatable');
    Route::post('general_takeout/delete', 'General_takeoutController@delete');

    Route::resource('transfer_warehouses', 'Transfer_warehousesController');
    Route::post('transfer_warehouses/datatable', 'Transfer_warehousesController@Datatable')->name('transfer_warehouses.datatable');

    Route::resource('transfer_warehouses_code', 'Transfer_warehouses_codeController');
    Route::post('transfer_warehouses_code/datatable', 'Transfer_warehouses_codeController@Datatable')->name('transfer_warehouses_code.datatable');

    Route::resource('transfer_branch', 'Transfer_branchController');
    Route::post('transfer_branch/datatable', 'Transfer_branchController@Datatable')->name('transfer_branch.datatable');
    Route::post('transfer_branch/requisition-datatable', 'Transfer_branchController@requisitionDatatable')->name('transfer_branch.requisition-datatable');
    Route::post('transfer_branch/check-stocks', 'Transfer_branchController@checkStockOptions')->name('transfer_branch.check-stocks');
    Route::post('transfer_branch/store-from-requisition', 'Transfer_branchController@storeFromRequisition')->name('transfer_branch.store-from-requisition');

    Route::resource('transfer_branch_code', 'Transfer_branch_codeController');
    Route::post('transfer_branch_code/datatable', 'Transfer_branch_codeController@Datatable')->name('transfer_branch_code.datatable');

    Route::resource('products_return', 'Products_returnController');
    Route::post('products_return/datatable', 'Products_returnController@Datatable')->name('products_return.datatable');

    Route::resource('products_return_approve', 'Products_return_approveController');
    Route::post('products_return_approve/datatable', 'Products_return_approveController@Datatable')->name('products_return_approve.datatable');

    Route::resource('products_out', 'Products_outController');
    Route::post('products_out/datatable', 'Products_outController@Datatable')->name('products_out.datatable');

    Route::resource('products_out_approve', 'Products_out_approveController');
    Route::post('products_out_approve/datatable', 'Products_out_approveController@Datatable')->name('products_out_approve.datatable');

    Route::resource('products_borrow', 'Products_borrowController');
    Route::post('products_borrow/datatable', 'Products_borrowController@Datatable')->name('products_borrow.datatable');
    Route::post('product_borrow/returned', 'Products_borrowController@updateReturned')->name('products_borrow.returned');

    Route::resource('products_borrow_code', 'Products_borrow_codeController');
    Route::post('products_borrow_code/datatable', 'Products_borrow_codeController@Datatable')->name('products_borrow_code.datatable');

    Route::resource('products_borrow_choose', 'Products_borrow_chooseController');
    Route::post('products_borrow_choose/datatable', 'Products_borrow_chooseController@Datatable')->name('products_borrow_choose.datatable');

    Route::resource('borrow_cause', 'Borrow_causeController');
    Route::post('borrow_cause/datatable', 'Borrow_causeController@Datatable')->name('borrow_cause.datatable');

// ใบยืม
    Route::get('products_borrow/print_products_borrow/{id}', 'AjaxController@createPDFBorrow');


    Route::post('ajaxCheckDubWarehouse', 'AjaxController@ajaxCheckDubWarehouse');
    Route::post('ajaxGetWarehouseFrom', 'AjaxController@ajaxGetWarehouseFrom');

    Route::post('ajaxSentMoneyDaily', 'AjaxController@ajaxSentMoneyDaily');
    Route::post('ajaxCancelSentMoney', 'AjaxController@ajaxCancelSentMoney');

    Route::post('ajaxCancelOrderBackend', 'AjaxController@ajaxCancelOrderBackend');
    Route::post('ajaxDeLProductOrderBackend', 'AjaxController@ajaxDeLProductOrderBackend');

    Route::post('ajaxDelUser', 'AjaxController@ajaxDelUser');
    Route::post('ajaxDelPromoProduct', 'AjaxController@ajaxDelPromoProduct');

    Route::post('ajaxDelPickpack', 'AjaxController@ajaxDelPickpack');
    Route::post('ajaxDelDelivery', 'AjaxController@ajaxDelDelivery');

    Route::post('ajaxCourseCheckRegis', 'AjaxController@ajaxCourseCheckRegis');

    Route::post('ajaxGetRegis_date_doc', 'AjaxController@ajaxGetRegis_date_doc');

    Route::post('ajaxDelFunction', 'AjaxController@ajaxDelFunction');
    Route::post('ajaxSearchMemberAicash', 'AjaxController@ajaxSearchMemberAicash');



    Route::resource('giveaway', 'GiveawayController');
    Route::post('giveaway/datatable', 'GiveawayController@Datatable')->name('giveaway.datatable');
    Route::get('giveaway/create/{id}', 'GiveawayController@create');

    Route::resource('giveaway_products', 'Giveaway_productsController');
    Route::post('giveaway_products/datatable', 'Giveaway_productsController@Datatable')->name('giveaway_products.datatable');
    Route::get('giveaway_products/create/{id}', 'Giveaway_productsController@create');


    Route::resource('scan_qrcode', 'Scan_qrcodeController');
    Route::post('scan_qrcode/datatable', 'Scan_qrcodeController@Datatable')->name('scan_qrcode.datatable');

    // ขอเบิกระหว่างสาขา
    Route::get('requisition_between_branch', 'RequisitionBetweenBranchController@index')->name('requisition_between_branch.index');
    Route::post('requisition_between_branch', 'RequisitionBetweenBranchController@store')->name('requisition_between_branch.store');
    Route::patch('requisition_between_branch/{requisition_between_branch}', 'RequisitionBetweenBranchController@update')->name('requisition_between_branch.update');
    Route::post('requisition_between_branch/datatable-list-approve', 'RequisitionBetweenBranchController@dtListApprove')->name('requisition_between_branch.dt-list-approve');
    Route::post('requisition_between_branch/datatable-list-wait-approve', 'RequisitionBetweenBranchController@dtListWaitApprove')->name('requisition_between_branch.dt-list-wait-approve');
    Route::post('requisition_between_branch_cancel', 'RequisitionBetweenBranchController@requisition_between_branch_cancel');

    // รายงานต่างๆ วุฒิเพิ่มมา
    Route::get('report_data', 'ReportDataController@index')->name('report_data.index');
    // Route::get('report_data/inventory', 'ReportDataController@inventory');
    Route::post('report_data/export_excel', 'ReportDataController@export_excel');

	#===========================================================================================

    Route::get('qrcode', function () {
        return view('backend.delivery.qr_code');
    });


    // });
    #=======================================================================================================================================================

    Route::get('template/{any?}', 'TemplateController@index')->name('template');

    // Route::get('lang/{lang}', 'LocaleController@lang');


  #=========================================================================================================================================================
  }); //route group auth:admin
#===========================================================================================================================================================

}); //route group backend

Route::group(['prefix' => 'aboutfile', 'as' => 'aboutfile.'], function() {
        // Route::post('excel-import-upload', 'backend\ExcelController@excelImportEmployeeUpload');
        Route::post('excel-import-upload', 'backend\ExcelController@excelExport');
#===========================================================================
}); //route group report
