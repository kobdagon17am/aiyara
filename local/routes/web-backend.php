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

    Route::resource('course_event', 'Course_eventController');
    Route::post('course_event/datatable', 'Course_eventController@Datatable')->name('course_event.datatable');

    Route::resource('course_event_images', 'Course_event_imagesController');
    Route::post('course_event_images/datatable', 'Course_event_imagesController@Datatable')->name('course_event_images.datatable');
    Route::get('course_event_images/create/{id}', 'Course_event_imagesController@create');

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

    Route::resource('add_ai_cash', 'Add_ai_cashController');
    Route::post('add_ai_cash/datatable', 'Add_ai_cashController@Datatable')->name('add_ai_cash.datatable');

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
    Route::post('pm/datatable', 'PmController@Datatable')->name('pm.datatable');

    Route::resource('pm_broadcast', 'Pm_broadcastController');
    Route::post('pm_broadcast/datatable', 'Pm_broadcastController@Datatable')->name('pm_broadcast.datatable');


    Route::resource('consignments_import', 'Consignments_importController');
    Route::post('consignments_import/datatable', 'Consignments_importController@Datatable')->name('consignments_import.datatable');

    Route::resource('products_fifo_bill', 'Products_fifo_billController');
    Route::post('products_fifo_bill/datatable', 'Products_fifo_billController@Datatable')->name('products_fifo_bill.datatable');

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
    Route::post('excelExportConsignment', 'ExcelController@excelExportConsignment');
    Route::post('excelExportPromotionCus', 'ExcelController@excelExportPromotionCus');
    Route::post('excelExportGiftvoucherCus', 'ExcelController@excelExportGiftvoucherCus');
    Route::post('excelExportCe_regis', 'ExcelController@excelExportCe_regis');
    Route::post('csvExportCe_regis', 'ExcelController@csvExportCe_regis');

    Route::post('excelImport', 'ExcelController@excelImport');

    Route::post('excelExportChart', 'ExcelChart@createexcelfileAction');

    Route::post('ajaxSetSession', 'AjaxController@ajaxSetSession');

    Route::post('ajaxClearDataPm_broadcast', 'AjaxController@ajaxClearDataPm_broadcast');
    Route::post('ajaxClearDataPromotionCode', 'AjaxController@ajaxClearDataPromotionCode');
    Route::post('ajaxClearDataGiftvoucherCode', 'AjaxController@ajaxClearDataGiftvoucherCode');
    Route::post('ajaxClearConsignment', 'AjaxController@ajaxClearConsignment');
    Route::post('ajaxGenPromotionCode', 'AjaxController@ajaxGenPromotionCode');
    Route::get('ajaxGenPromotionCode', 'AjaxController@ajaxGenPromotionCode');
    Route::post('ajaxGenPromotionCodePrefixCoupon', 'AjaxController@ajaxGenPromotionCodePrefixCoupon');

    Route::post('ajaxGenPromotionSaveDate', 'AjaxController@ajaxGenPromotionSaveDate');

    Route::post('ajaxGetWarehouse', 'AjaxController@ajaxGetWarehouse');
    Route::post('ajaxGetZone', 'AjaxController@ajaxGetZone');
    Route::post('ajaxGetShelf', 'AjaxController@ajaxGetShelf');

    Route::post('ajaxGetAmphur', 'AjaxController@ajaxGetAmphur');
    Route::post('ajaxGetTambon', 'AjaxController@ajaxGetTambon');
    Route::post('ajaxGetZipcode', 'AjaxController@ajaxGetZipcode');

    Route::post('ajaxGetPayType', 'AjaxController@ajaxGetPayType');
    Route::post('ajaxGetLabelPayType', 'AjaxController@ajaxGetLabelPayType');
    Route::post('ajaxGetLabelOthersPrice', 'AjaxController@ajaxGetLabelOthersPrice');
    Route::post('ajaxGetVoucher', 'AjaxController@ajaxGetVoucher');

    // Route::post('ajaxFetchData', 'AjaxController@ajaxFetchData');
    // Route::get('ajaxFetchData', 'AjaxController@ajaxFetchData');
    Route::post('ajaxSelectAddr', 'AjaxController@ajaxSelectAddr');
    Route::post('ajaxSelectAddrEdit', 'AjaxController@ajaxSelectAddrEdit');

    Route::post('ajaxApprovePickupGoods', 'AjaxController@ajaxApprovePickupGoods');

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
    Route::post('ajaxApproveCouponCode', 'AjaxController@ajaxApproveCouponCode');
    Route::post('ajaxApproveGiftvoucherCode', 'AjaxController@ajaxApproveGiftvoucherCode');
    Route::post('ajaxCheckDBfrontstore', 'AjaxController@ajaxCheckDBfrontstore');

    Route::post('ajaxGetDBfrontstore', 'AjaxController@ajaxGetDBfrontstore');
    Route::post('ajaxCheckAddAiCash', 'AjaxController@ajaxCheckAddAiCash');
    Route::post('ajaxGetDBAddAiCash', 'AjaxController@ajaxGetDBAddAiCash');

    Route::post('ajaxCalPriceFrontstore01', 'AjaxController@ajaxCalPriceFrontstore01');
    Route::post('ajaxCalPriceFrontstore02', 'AjaxController@ajaxCalPriceFrontstore02');
    Route::post('ajaxCalPriceFrontstore03', 'AjaxController@ajaxCalPriceFrontstore03');
    Route::post('ajaxCalPriceFrontstore04', 'AjaxController@ajaxCalPriceFrontstore04');
    Route::post('ajaxCalGiftVoucherPrice', 'AjaxController@ajaxCalGiftVoucherPrice');

    Route::post('ajaxCalAicash', 'AjaxController@ajaxCalAicash');

    Route::post('ajaxCalAddAiCashFrontstore', 'AjaxController@ajaxCalAddAiCashFrontstore');

    Route::post('ajaxDelFileSlip', 'AjaxController@ajaxDelFileSlip');
    Route::post('ajaxDelFileSlipGiftVoucher', 'AjaxController@ajaxDelFileSlipGiftVoucher');
    Route::post('ajaxCearCostFrontstore', 'AjaxController@ajaxCearCostFrontstore');

    Route::post('ajaxClearAfterSelChargerType', 'AjaxController@ajaxClearAfterSelChargerType');
    Route::post('ajaxClearAfterAddAiCash', 'AjaxController@ajaxClearAfterAddAiCash');


    Route::post('ajaxSaveGiftvoucherCode', 'AjaxController@ajaxSaveGiftvoucherCode');
    Route::post('ajaxCalAicashAmt', 'AjaxController@ajaxCalAicashAmt');

    Route::post('ajaxGiftVoucherSaveDate', 'AjaxController@ajaxGiftVoucherSaveDate');

    Route::post('ajaxFifoApproved', 'AjaxController@ajaxFifoApproved');


    Route::resource('delivery', 'DeliveryController');
    Route::post('delivery/datatable', 'DeliveryController@Datatable')->name('delivery.datatable');

    Route::resource('pick_pack', 'Pick_packController');
    Route::post('pick_pack/datatable', 'Pick_packController@Datatable')->name('pick_pack.datatable');

    Route::resource('pick_pack_packing', 'Pick_packPackingController');
    Route::post('pick_pack_packing/datatable', 'Pick_packPackingController@Datatable')->name('pick_pack_packing.datatable');

    Route::resource('pick_pack_packing_code', 'Pick_packPackingCodeController');
    Route::post('pick_pack_packing_code/datatable', 'Pick_packPackingCodeController@Datatable')->name('pick_pack_packing_code.datatable');


    Route::resource('pick_warehouse', 'Pick_warehouseController');
    Route::post('pick_warehouse/datatable', 'Pick_warehouseController@Datatable')->name('pick_warehouse.datatable');

    Route::resource('pay_product_receipt', 'Pay_product_receiptController');
    Route::post('pay_product_receipt/datatable', 'Pay_product_receiptController@Datatable')->name('pay_product_receipt.datatable');

    Route::get('delivery/pdf01/{id}', 'AjaxController@createPDFCoverSheet01');
    Route::post('delivery/print_receipt01/{id}', 'AjaxController@createPDFReceipt01');
    Route::get('delivery/print_receipt01/{id}', 'AjaxController@createPDFReceipt01');
    Route::post('frontstore/print_receipt022/{id}', 'AjaxController@createPDFReceipt022');
    Route::get('frontstore/print_receipt022/{id}', 'AjaxController@createPDFReceipt022');
    Route::get('frontstore/print_receipt_packing/{id}', 'AjaxController@createPDFReceiptPacking');

    Route::get('delivery/pdf02/{id}', 'AjaxController@createPDFCoverSheet02');
    Route::get('delivery/print_receipt02/{id}', 'AjaxController@createPDFReceipt02');

    Route::get('add_ai_cash/print_receipt/{id}', 'AjaxController@createPDFReceiptAicash');

    Route::resource('taxdata', 'TaxdataController');
    Route::post('taxdata/datatable', 'TaxdataController@Datatable')->name('taxdata.datatable');
    Route::get('taxdata/taxtvi/{id}', 'TaxdataController@createPDFTaxtvi');

    Route::resource('ce_regis', 'Ce_regisController');
    Route::post('ce_regis/datatable', 'Ce_regisController@Datatable')->name('ce_regis.datatable');

    Route::resource('frontstore', 'FrontstoreController');
    Route::post('frontstore/datatable', 'FrontstoreController@Datatable')->name('frontstore.datatable');
    Route::get('frontstore/print_receipt/{id}', 'AjaxController@createPDFReceiptFrontstore');
    Route::get('frontstore/print_receipt_02/{id}', 'AjaxController@createPDFReceiptFrontstore02');


    Route::resource('product_in_cause', 'Product_in_causeController');
    Route::post('product_in_cause/datatable', 'Product_in_causeController@Datatable')->name('product_in_cause.datatable');

    Route::resource('product_out_cause', 'Product_out_causeController');
    Route::post('product_out_cause/datatable', 'Product_out_causeController@Datatable')->name('product_out_cause.datatable');


    Route::resource('delivery_packing', 'DeliveryPackingController');
    Route::post('delivery_packing/datatable', 'DeliveryPackingController@Datatable')->name('delivery_packing.datatable');

    Route::resource('delivery_packing_code', 'DeliveryPackingCodeController');
    Route::post('delivery_packing_code/datatable', 'DeliveryPackingCodeController@Datatable')->name('delivery_packing_code.datatable');

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
    Route::post('po_approve/datatable', 'Po_approveController@Datatable')->name('po_approve.datatable');
    Route::post('po_approve_set/datatable', 'Po_approveController@DatatableSet')->name('po_approve_set.datatable');

// คอร์สรออนุมัติ
    Route::resource('course_approve', 'Course_approveController');
    Route::post('course_approve/datatable', 'Course_approveController@Datatable')->name('course_approve.datatable');
    Route::post('course_approve_set/datatable', 'Course_approveController@DatatableSet')->name('course_approve_set.datatable');

// ใบโอน
    Route::get('transfer_warehouses/print_transfer/{id}', 'AjaxController@createPDFTransfer');
    Route::get('transfer_branch/print_transfer/{id}', 'AjaxController@createPDFTransfer_branch');

    Route::resource('check_orders', 'Check_ordersController');
    Route::post('check_orders/datatable', 'Check_ordersController@Datatable')->name('check_orders.datatable');

    Route::resource('check_orders_list', 'Check_orders_listController');
    Route::post('check_orders_list/datatable', 'Check_orders_listController@Datatable')->name('check_orders_list.datatable');


    Route::post('commission_transfer/datatable', 'Commission_transferController@Datatable')->name('commission_transfer.datatable');
    Route::get('commission_transfer/modal_commission_transfer','Commission_transferController@modal_commission_transfer')->name('commission_transfer.modal_commission_transfer');
    Route::resource('commission_transfer', 'Commission_transferController');

    Route::resource('commission_aistockist', 'Commission_aistockistController');
    Route::get('commission_transfer_aistockist/modal_commission_transfer_aistockist','Commission_aistockistController@modal_commission_transfer_aistockist')->name('commission_transfer_aistockist.modal_commission_transfer_aistockist');
    Route::post('commission_aistockist/datatable', 'Commission_aistockistController@Datatable')->name('commission_aistockist.datatable');

    Route::resource('commission_transfer_af', 'Commission_transfer_afController');
    Route::post('commission_af/datatable', 'Commission_transfer_afController@Datatable')->name('commission_af.datatable');

    Route::get('total_thai_cambodia/datatable_total_all', 'Total_thai_cambodiaController@datatable_total_all')->name('total_thai_cambodia.datatable_total_all');
    Route::resource('total_thai_cambodia', 'Total_thai_cambodiaController');
    Route::post('total_thai_cambodia/datatable', 'Total_thai_cambodiaController@Datatable')->name('total_thai_cambodia.datatable');
    Route::post('total_thai_cambodia/datatable_total', 'Total_thai_cambodiaController@DatatableTotal')->name('total_thai_cambodia.datatable_total');


    Route::resource('cambodia_account', 'Cambodia_accountController');
    Route::post('cambodia_account/datatable', 'Cambodia_accountController@Datatable')->name('cambodia_account.datatable');


    Route::resource('transfer_corporate_members', 'Transfer_corporate_membersController');
    Route::post('transfer_corporate_members/datatable', 'Transfer_corporate_membersController@Datatable')->name('transfer_corporate_members.datatable');


    Route::resource('po_in', 'Po_inController');
    Route::post('po_in/datatable', 'Po_inController@Datatable')->name('po_in.datatable');

    Route::resource('general_receive', 'General_receiveController');
    Route::post('general_receive/datatable', 'General_receiveController@Datatable')->name('general_receive.datatable');

    Route::resource('check_stock', 'Check_stockController');
    Route::post('check_stock/datatable', 'Check_stockController@Datatable')->name('check_stock.datatable');

    Route::resource('pick_warehouse_fifo', 'Pick_warehouse_fifoController');
    Route::post('pick_warehouse_fifo/datatable', 'Pick_warehouse_fifoController@Datatable')->name('pick_warehouse_fifo.datatable');
    Route::post('pick_warehouse_fifo/fifo', 'Pick_warehouse_fifoController@calFifo');

    Route::resource('pick_warehouse_fifo_topicked', 'Pick_warehouse_fifo_topickedController');
    Route::post('pick_warehouse_fifo_topicked/datatable', 'Pick_warehouse_fifo_topickedController@Datatable')->name('pick_warehouse_fifo_topicked.datatable');

    Route::resource('pick_warehouse_fifo_no', 'Pick_warehouse_fifo_noController');
    Route::post('pick_warehouse_fifo_no/datatable', 'Pick_warehouse_fifo_noController@Datatable')->name('pick_warehouse_fifo_no.datatable');

    Route::resource('check_stock_account', 'Check_stock_accountController');
    Route::post('check_stock_account/datatable', 'Check_stock_accountController@Datatable')->name('check_stock_account.datatable');

    Route::resource('check_money_daily', 'Check_money_dailyController');
    Route::post('check_money_daily/datatable', 'Check_money_dailyController@Datatable')->name('check_money_daily.datatable');


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
    Route::post('frontstorelist/datatablePro', 'FrontstorelistController@DatatablePro')->name('frontstorelist-pro.datatable');
    Route::post('frontstorelist/plus', 'FrontstorelistController@plus');
    Route::post('frontstorelist/plusPromotion', 'FrontstorelistController@plusPromotion');
    Route::post('frontstorelist/minusPromotion', 'FrontstorelistController@minusPromotion');
    Route::post('frontstorelist/minus', 'FrontstorelistController@minus');

    Route::resource('transfer_choose', 'Transfer_chooseController');
    Route::post('transfer_choose/datatable', 'Transfer_chooseController@Datatable')->name('transfer_choose.datatable');

    Route::resource('transfer_choose_branch', 'Transfer_choose_branchController');
    Route::post('transfer_choose_branch/datatable', 'Transfer_choose_branchController@Datatable')->name('transfer_choose_branch.datatable');

    Route::resource('general_takeout', 'General_takeoutController');
    Route::post('general_takeout/datatable', 'General_takeoutController@Datatable')->name('general_takeout.datatable');

    Route::resource('transfer_warehouses', 'Transfer_warehousesController');
    Route::post('transfer_warehouses/datatable', 'Transfer_warehousesController@Datatable')->name('transfer_warehouses.datatable');

    Route::resource('transfer_warehouses_code', 'Transfer_warehouses_codeController');
    Route::post('transfer_warehouses_code/datatable', 'Transfer_warehouses_codeController@Datatable')->name('transfer_warehouses_code.datatable');

    Route::resource('transfer_branch', 'Transfer_branchController');
    Route::post('transfer_branch/datatable', 'Transfer_branchController@Datatable')->name('transfer_branch.datatable');

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

    Route::resource('products_borrow_code', 'Products_borrow_codeController');
    Route::post('products_borrow_code/datatable', 'Products_borrow_codeController@Datatable')->name('products_borrow_code.datatable');

    Route::resource('products_borrow_choose', 'Products_borrow_chooseController');
    Route::post('products_borrow_choose/datatable', 'Products_borrow_chooseController@Datatable')->name('products_borrow_choose.datatable');

    Route::resource('borrow_cause', 'Borrow_causeController');
    Route::post('borrow_cause/datatable', 'Borrow_causeController@Datatable')->name('borrow_cause.datatable');

// ใบยืม
    Route::get('products_borrow/print_products_borrow/{id}', 'AjaxController@createPDFBorrow');


    Route::resource('giveaway', 'GiveawayController');
    Route::post('giveaway/datatable', 'GiveawayController@Datatable')->name('giveaway.datatable');
    Route::get('giveaway/create/{id}', 'GiveawayController@create');

    Route::resource('giveaway_products', 'Giveaway_productsController');
    Route::post('giveaway_products/datatable', 'Giveaway_productsController@Datatable')->name('giveaway_products.datatable');
    Route::get('giveaway_products/create/{id}', 'Giveaway_productsController@create');


    Route::resource('scan_qrcode', 'Scan_qrcodeController');
    Route::post('scan_qrcode/datatable', 'Scan_qrcodeController@Datatable')->name('scan_qrcode.datatable');


	#=======================================================================================================================================================

    Route::get('qrcode', function () {
        return view('backend.delivery.qr_code');
    });


    // });
    #=======================================================================================================================================================

    Route::get('template/{any?}', 'TemplateController@index')->name('template');


  #=========================================================================================================================================================
  }); //route group auth:admin
#===========================================================================================================================================================

}); //route group backend

Route::group(['prefix' => 'aboutfile', 'as' => 'aboutfile.'], function() {
        // Route::post('excel-import-upload', 'backend\ExcelController@excelImportEmployeeUpload');
        Route::post('excel-import-upload', 'backend\ExcelController@excelExport');
#===========================================================================
}); //route group report

