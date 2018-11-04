<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {

    $comp_code = '11001';
    Session::put('comp_code',$comp_code);
    return view('auth.login')->with('comp_code',$comp_code);
//    return view('layouts.master');
});

Route::get('logout', 'Auth\LoginController@logout');

//Route::group(['middleware' => 'auth'], function () {

    Route::get('userIndex','Auth\UserController@index');
    Route::get('users.index.data','Auth\UserController@getUserData');
    Route::any('user.data.update/{id}','Auth\UserController@editUserData');
    Route::any('user.data.delete/{id}','Auth\UserController@deleteUserData');

    Route::get('userPrivilegeIndex','Auth\UserController@userPrivilegeIndex');
    Route::post('approvePrivilege','Auth\UserController@approvePrivilege');

    Route::get('changePassword','Auth\UserController@changePasswordIndex');

Route::group(['prefix' => '', 'namespace' => 'Account', 'middleware' => ['auth']], function () {

    Route::get('accountGroupIndex','AccountController@accountGroupIndex');
    Route::get('account.group.data','AccountController@getGroupData');
    Route::post('account.group.add','AccountController@addGroupData');
    Route::any('account.group.update/{id}','AccountController@editGroupData');
    Route::any('account.group.delete/{id}','AccountController@deleteGroupData');
});




    Route::get('accountHeadIndex','Account\AccountController@accountHeadIndex');
    Route::get('account.head.data','Account\AccountController@getHeadData');
    Route::post('account.head.add','Account\AccountController@addHeadData');
    Route::any('account.head.update/{id}','Account\AccountController@editHeadData');

    Route::get('openingBalanceIndex','Account\OpeningBalanceController@index');
    Route::post('saveOpenbalance','Account\OpeningBalanceController@saveOpenbalance');
    Route::post('postOpenbalance','Account\OpeningBalanceController@postOpenbalance');

Route::group(['prefix' => '', 'namespace' => 'Account', 'middleware' => ['auth']], function () {
    Route::get('depreciationIndex', 'DepreciationController@index');
    Route::get('depreciation.data', 'DepreciationController@getDepData');
    Route::post('dep.rate.save', 'DepreciationController@saveDeprRate');
    Route::delete('dep.data.delete/{id}','DepreciationController@destroy');
    Route::post('dep.data.update/{id}','DepreciationController@update');
    Route::post('postFixedAssetVoucher','DepreciationController@postDepriciation');


});

Route::group(['prefix' => '', 'namespace' => 'Budget', 'middleware' => ['auth']], function () {
    Route::get('anualBudgetIndex', 'AnualBudgetController@index');
    Route::get('budget.head.data','AnualBudgetController@getHeadData');
    Route::any('budget.head.update/{id}','AnualBudgetController@editBudgetData');
});


    Route::get('fiscalPeriodIndex','Account\FiscalPeriodController@index');
    Route::get('fiscal.period.data','Account\FiscalPeriodController@getFiscalData');
    Route::any('fiscal.period.update/{id}','Account\FiscalPeriodController@editFiscalData');


// Cash Payment
Route::group(['prefix' => '', 'namespace' => 'Transaction', 'middleware' => ['auth']], function () {

    Route::get('cashPaymentIndex','CashPaymentController@index');
    Route::get('cp.debit.head','CashPaymentController@getDebitHead');
    Route::post('cp.trans.save','CashPaymentController@saveCPTrans');

    Route::get('postvoucherindex',['as' => 'transaction.post.index', 'uses' => 'PostVoucherController@index']);
    Route::get('unposted.voucher.data',['as' => 'unposted.voucher.data', 'uses' => 'PostVoucherController@getData']);




});

Route::get('voucher/details-data/{voucherNo}','Transaction\PostVoucherController@details');
Route::post('voucher.data.post/{voucherNo}','Transaction\PostVoucherController@postvoucher');





    Route::get('cashReceiveIndex','Transaction\CashReceiveController@index');
    Route::get('cr.credit.head','Transaction\CashReceiveController@getCreditHead');
    Route::post('cr.trans.save','Transaction\CashReceiveController@saveCRTrans');

    Route::get('bankPaymentIndex','Transaction\BankPaymentController@index');
    Route::get('bp.getData.crHead','Transaction\BankPaymentController@getCreditHeadBal');
    Route::get('bp.debit.head','Transaction\BankPaymentController@getDebitHead');
    Route::post('bp.trans.save','Transaction\BankPaymentController@saveBPTrans');



    Route::get('bankReceiveIndex','Transaction\BankReceiveController@index');
    Route::get('br.getData.drHead','Transaction\BankReceiveController@getDebitHeadBal');
    Route::get('br.credit.head','Transaction\BankReceiveController@getCreditHead');
    Route::post('br.trans.save','Transaction\BankReceiveController@saveBRTrans');

//JOURNAL
    Route::get('journalIndex','Transaction\JournalController@index');
    Route::post('jv.trans.save','Transaction\JournalController@saveJVTrans');

    // Edit
    Route::group(['prefix' => '', 'namespace' => 'Transaction', 'middleware' => ['auth']], function () {
        Route::get('/index', ['as' => 'transaction.edit.index', 'uses' => 'EditVoucherController@index', 'middleware' => ['post.check']]);
        Route::post('/post',['as'=>'updatev.oucher.post','uses' => 'EditVoucherController@update','middleware' => ['post.check']]);
    });



Route::group(['prefix' => '', 'namespace' => 'Project', 'middleware' => ['auth']], function () {

    Route::get('projectIndex','ProjectController@index');
    Route::get('project.index.data','ProjectController@getProjectData');
    Route::post('project.data.new','ProjectController@addNewProject');
    Route::any('project.data.update/{id}','ProjectController@editProjectData');
    Route::any('project.data.delete/{id}','ProjectController@deleteProjectData');

});





//});



Auth::routes();

//Route::get('userIndex','Auth\UserController@index');
//Route::get('users.index.data','Auth\UserController@getUserData');

Route::get('/home', 'HomeController@index');
Route::get('companyConfigIndex','Company\CompanyConfigController@index');
Route::post('compConfig','Company\CompanyConfigController@saveConfig');

Route::get('inventoryHome',['as'=>'inventoryHome','uses'=>'HomeController@inventoryHomeindex'])->middleware('auth');

Route::get('test.view','HomeController@test');




// Reports
Route::group(['prefix' => '', 'namespace' => 'Report', 'middleware' => ['auth']], function () {
    Route::get('/acctb', ['as' => 'report.account.tb', 'uses' => 'Ledger\TrialBalanceController@index']);
    Route::get('/grouptb', ['as' => 'report.group.tb', 'uses' => 'Ledger\TrialBalanceController@groupTB']);

    Route::get('general.ledger.index',['as' => 'general.ledger.index', 'uses' => 'Ledger\GeneralLedgerController@index']);

    Route::get('cashRegisterIndex',['as' => 'cash.register.index', 'uses' => 'Ledger\RegisterReportController@cashRegisterIndex']);
    Route::get('bankRegisterIndex',['as' => 'bank.register.index', 'uses' => 'Ledger\RegisterReportController@bankRegisterIndex']);

    Route::get('/dailytransaction', ['as' => 'report.dailytrans.rpt', 'uses' => 'Transaction\DailyTransactionReportController@index']);
    Route::get('/dailytransactionprint', ['as' => 'report.dailytrans.print', 'uses' => 'Transaction\DailyTransactionReportController@print']);

    Route::get('report.dailyvoucher.rpt',['as' => 'report.dailyvoucher.rpt', 'uses' => 'Transaction\PrintVoucherController@index']);
    Route::post('pdfview',['as'=>'pdfview','uses'=>'Transaction\PrintVoucherController@pdfview']);

    Route::get('postedtb',['as' => 'posted.account.tb', 'uses' => 'Ledger\TrialBalanceController@postedTB']);
    Route::get('postedgrouptb',['as' => 'posted.group.tb', 'uses' => 'Ledger\TrialBalanceController@postedGrpTB']);
});
//Statements

    Route::group(['prefix' => '', 'namespace' => 'Report', 'middleware' => ['auth']], function () {

        Route::get('addStatement',['as' => 'fn.statement.add', 'uses' => 'Statements\FinancialStatementController@addStatementIndex']);
        Route::get('fn.statement.list',['as' => 'fn.statement.list', 'uses' => 'Statements\FinancialStatementController@getStatementList']);
        Route::post('fn.statement.save',['as' => 'fn.statement.save', 'uses' => 'Statements\FinancialStatementController@saveStatementList']);
        Route::any('fn.statement.update/{id}',['as' => 'updateStatement', 'uses' => 'Statements\FinancialStatementController@updateStatementList']);
        Route::any('fn.statement.delete/{id}',['as' => 'deleteStatement', 'uses' => 'Statements\FinancialStatementController@deleteStatementList']);


        Route::get('createStatementDataIndex',['as' => 'fn.statement.create', 'uses' => 'Statements\FinancialStatementController@createStatementData']);
        Route::get('fn.statement.data',['as' => 'fn.statement.data', 'uses' => 'Statements\FinancialStatementController@getStatementData']);
        Route::post('fn.statement.line',['as' => 'fn.statement.line', 'uses' => 'Statements\FinancialStatementController@saveStatementData']);

        Route::any('statement.data.update/{id}',['as' => 'fn.statement.line', 'uses' => 'Statements\FinancialStatementController@updateStatementData']);

        Route::get('fn.statement.process',['as' => 'fn.statement.process', 'uses' => 'Statements\FinancialStatementController@processStatementIndex']);
        Route::post('fn.statement.process.post',['as' => 'fn.statement.process.post', 'uses' => 'Statements\FinancialStatementController@processStatementData']);

        Route::get('printStatementIndex',['as' => 'fn.statement.print', 'uses' => 'Statements\FinancialStatementController@printStatementIndex']);

    });


//    Route::post('/post',['as'=>'updatev.oucher.post','uses' => 'EditVoucherController@update','middleware' => ['post.check']]);


    Route::get('generalLedgerIndex/{id}','Report\Ledger\GeneralLedgerController@getAccList');



// Products

    Route::group(['prefix' => '', 'namespace' => 'Product', 'middleware' => ['auth']], function () {

//        Category
        Route::get('categoryIndex',['as'=>'categoryIndex', 'uses'=>'CategoryController@index']);
        Route::get('category.index.data',['as'=>'category.index.data', 'uses'=>'CategoryController@getData']);
        Route::post('categories.data.new',['as'=>'categories.data.new', 'uses'=>'CategoryController@add']);
        Route::post('categories.data.edit/{id}',['as'=>'editCategoryData', 'uses'=>'CategoryController@edit']);
        Route::any('categories.data.delete/{id}',['as'=>'deleteCategoryData', 'uses'=>'CategoryController@destroy']);


//        SUB CATEGORIES


        Route::get('subcategoryIndex',['as'=>'subcategoryIndex', 'uses'=>'SubcategoryController@index']);
        Route::get('subcategory.index.data',['as'=>'subcategory.index.data', 'uses'=>'SubcategoryController@getData']);

        Route::get('subcategories/autocomplete',['as'=>'subcategories/autocomplete','uses'=>'SubcategoryController@autocomplete']);

        Route::post('subcategories.data.new',['as'=>'subcategories.data.new', 'uses'=>'SubcategoryController@add']);
        Route::post('subcategories.data.edit/{id}',['as'=>'editsubCategoryData', 'uses'=>'SubcategoryController@edit']);
        Route::delete('subcategories.data.delete/{id}',['as'=>'deletesubCategoryData', 'uses'=>'SubcategoryController@destroy']);


//Brand
        Route::get('productBrandIndex',['as'=>'productBrandIndex', 'uses'=>'BrandController@index']);
        Route::get('product.brand.data',['as'=>'product.brand.data', 'uses'=>'BrandController@getData']);
        Route::post('product.brand.new',['as'=>'product.brand.new', 'uses'=>'BrandController@add']);
        Route::post('product.brand.edit/{id}',['as'=>'editProductBrand', 'uses'=>'BrandController@edit']);
        Route::delete('product.brand.delete/{id}',['as'=>'editProductBrand', 'uses'=>'BrandController@destroy']);


// Godown

        Route::get('godownIndex',['as'=>'godownIndex', 'uses'=>'GodownController@index']);
        Route::get('godown.data',['as'=>'godown.data', 'uses'=>'GodownController@getData']);
        Route::post('godown.new',['as'=>'godown.new', 'uses'=>'GodownController@add']);
        Route::post('godown.edit/{id}',['as'=>'editGodown', 'uses'=>'GodownController@edit']);
        Route::delete('godown.delete/{id}',['as'=>'deleteGodown', 'uses'=>'GodownController@delete']);


//        RACK


        Route::get('rackIndex',['as'=>'rackIndex', 'uses'=>'RackController@index']);
        Route::get('rack.data',['as'=>'rack.data', 'uses'=>'RackController@getData']);
        Route::post('rack.new',['as'=>'rack.new', 'uses'=>'RackController@add']);
        Route::post('rack.edit/{id}',['as'=>'editRack', 'uses'=>'RackController@edit']);
        Route::delete('rack.delete/{id}',['as'=>'deleteRack', 'uses'=>'RackController@destroy']);



        // Units

        Route::get('productUnitIndex',['as'=>'productUnitIndex', 'uses'=>'UnitController@index']);
        Route::get('unit.data',['as'=>'unit.data', 'uses'=>'UnitController@getData']);
        Route::post('unit.new',['as'=>'unit.new', 'uses'=>'UnitController@add']);
        Route::post('unit.edit/{id}',['as'=>'editunit', 'uses'=>'UnitController@edit']);
        Route::delete('unit.delete/{id}',['as'=>'deleteunit', 'uses'=>'UnitController@destroy']);


        // Sizes

        Route::get('productSizeIndex',['as'=>'productSizeIndex', 'uses'=>'SizesController@index']);
        Route::get('product.size.data',['as'=>'product.size.data', 'uses'=>'SizesController@getData']);
        Route::post('product.size.new',['as'=>'product.size.new', 'uses'=>'SizesController@add']);
        Route::post('product.size.edit/{id}',['as'=>'editproductsize', 'uses'=>'SizesController@edit']);
        Route::delete('product.size.delete/{id}',['as'=>'deleteproductsize', 'uses'=>'SizesController@delete']);

        // Models

        Route::get('productModelIndex',['as'=>'productModelIndex', 'uses'=>'ModelsController@index']);
        Route::get('product.model.data',['as'=>'product.model.data', 'uses'=>'ModelsController@getData']);
        Route::post('product.model.new',['as'=>'product.model.new', 'uses'=>'ModelsController@add']);
        Route::post('product.model.edit/{id}',['as'=>'editproductmodel', 'uses'=>'ModelsController@edit']);
        Route::delete('product.model.delete/{id}',['as'=>'deleteproductmodel', 'uses'=>'ModelsController@delete']);

        // TAXES

        Route::get('productTaxIndex',['as'=>'productTaxIndex', 'uses'=>'ProductTaxController@index']);
        Route::get('product.tax.data',['as'=>'product.tax.data', 'uses'=>'ProductTaxController@getData']);
        Route::post('product.tax.new',['as'=>'product.tax.new', 'uses'=>'ProductTaxController@add']);
        Route::post('product.tax.edit/{id}',['as'=>'editproducttax', 'uses'=>'ProductTaxController@edit']);
        Route::delete('product.tax.delete/{id}',['as'=>'deleteproducttax', 'uses'=>'ProductTaxController@destroy']);


        // TAXES Groups

        Route::get('taxGroupIndex',['as'=>'taxGroupIndex', 'uses'=>'TaxGroupController@index']);
        Route::get('tax.group.data',['as'=>'tax.group.data', 'uses'=>'TaxGroupController@getData']);
        Route::post('tax.group.new',['as'=>'tax.group.new', 'uses'=>'TaxGroupController@addGroup']);
        Route::post('tax.group.edit/{id}',['as'=>'edittaxgroup', 'uses'=>'TaxGroupController@edit']);
        Route::delete('tax.group.delete/{id}',['as'=>'deletetaxgroup', 'uses'=>'TaxGroupController@destroy']);


//        RELATIONSHIPS CUSTOMER  SUPPLIERS


        // Products

        Route::get('relationshipIndex',['as'=>'relationshipIndex', 'uses'=>'RelationshipController@index']);
        Route::get('relationship.data.get',['as'=>'relationship.data.get', 'uses'=>'RelationshipController@getData']);
        Route::post('relationship.data.new',['as'=>'relationship.data.new', 'uses'=>'RelationshipController@add']);
        Route::post('relationship.data.edit/{id}',['as'=>'editrelationshipdata', 'uses'=>'RelationshipController@edit']);
        Route::delete('relationship.data.delete/{id}',['as'=>'deleterelationshipdata', 'uses'=>'RelationshipController@destroy']);


        // Products

        Route::get('productIndex',['as'=>'productIndex', 'uses'=>'ProductController@index']);
        Route::get('product.data.get',['as'=>'product.data.get', 'uses'=>'ProductController@getData']);
        Route::get('product.create.form',['as'=>'product.create.form', 'uses'=>'ProductController@create']);
        Route::post('product.data.new',['as'=>'product.data.new', 'uses'=>'ProductController@addProduct']);
        Route::post('product.data.edit/{id}',['as'=>'editproductdata', 'uses'=>'ProductController@edit']);
        Route::delete('product.data.delete/{id}',['as'=>'deleteproductdata', 'uses'=>'ProductController@delete']);

        Route::get('product/ajax_details/{id}',['as'=>'detailsproductdata', 'uses'=>'ProductController@details']);

        Route::post('products/totalItem',['as'=>'producttotal', 'uses'=>'ProductController@totalproduct']);

        Route::get('itemautocomplete',['as'=>'itemautocomplete', 'uses'=>'ProductController@autocomplete']);


    });

//REQUISITION

Route::group(['prefix' => '', 'namespace' => 'Backend\Requisition', 'middleware' => ['auth']], function () {

    Route::get('requisition.create.index',['as'=>'requisition.create.index', 'uses'=>'RequisitionController@index']);
    Route::post('requisition.create.post',['as' => 'requisition.create.post','uses'=>'RequisitionController@postdata']);
    Route::get('requisition.productlist',['as' => 'productlist','uses'=>'RequisitionController@getproducts']);

    Route::get('requisitioneditindex',['as'=>'requisition.edit.index', 'uses'=>'EditRequisition@index']);
    Route::get('requisition.data',['as'=>'requisition.data', 'uses'=>'EditRequisition@getreqdata']);
    Route::post('requisition.edit/{id}',['as'=>'editrequisitiondata', 'uses'=>'EditRequisition@update']);

    Route::get('requisitionapproveindex',['as'=>'requisition.approve.index', 'uses'=>'ApproveRequisition@index']);
    Route::get('requisition.approve.data',['as'=>'requisition.approve.data', 'uses'=>'ApproveRequisition@getreqdata']);

    Route::get('requisition/ajax_details/{refNo}',['as'=>'requisition/ajax_details', 'uses'=>'ApproveRequisition@getdetailsreqdata']);

    Route::post('requisition/approve/{refNo}',['as'=>'requisition/approve', 'uses'=>'ApproveRequisition@approvereq']);
    Route::post('requisition/reject/{refNo}',['as'=>'requisition/approve', 'uses'=>'ApproveRequisition@rejectreq']);

});




Route::group(['prefix' => '', 'namespace' => 'Backend\Purchase', 'middleware' => ['auth']], function () {

    Route::get('purchaseOrderIndex',['as'=>'purchaseOrderIndex', 'uses'=>'PurchaseController@index']);
    Route::post('purchase.create.order',['as'=>'purchaseOrder', 'uses'=>'PurchaseController@create']);
    Route::get('purchase/itemdetails',['as'=>'itemdatadetails', 'uses'=>'PurchaseController@itemdatadetails']);

    Route::get('editpurchaseindex',['as'=>'editpurchaseindex', 'uses'=>'EditPurchaseController@index']);

    Route::get('purchase.edit.data',['as'=>'\'purchase.edit.data', 'uses'=>'EditPurchaseController@getpdata']);


    Route::get('invoicePreviewIndex',['as'=>'invoicePreviewIndex', 'uses'=>'PurchaseController@invoicePreview']);

});

Route::group(['prefix' => '', 'namespace' => 'Backend\Sales', 'middleware' => ['auth']], function () {

    Route::get('salesinvoiceindex',['as'=>'salesinvoiceindex', 'uses'=>'SalesController@index']);
    Route::post('sales.create.invoice',['as'=>'salesinvoice', 'uses'=>'SalesController@create']);
    Route::get('approveinvoiceindex',['as'=>'approveinvoiceindex', 'uses'=>'ApproveSalesController@index']);

    Route::get('invoice.approve.data',['as'=>'invoice.approve.data', 'uses'=>'ApproveSalesController@getinvoicedata']);

    Route::get('invoice/ajax_details/{invoiceno}',['as'=>'invoice.data', 'uses'=>'ApproveSalesController@invoicedetails']);

    Route::post('invoice/approve/{invoiceno}',['as'=>'approve.invoice', 'uses'=>'ApproveSalesController@approve']);

    Route::post('invoice/reject/{invoiceno}',['as'=>'reject.invoice', 'uses'=>'ApproveSalesController@reject']);

    Route::get('rptinvoiceindex',['as'=>'rptinvoiceindex', 'uses'=>'RptInvoiceController@index']);

    Route::get('invoiceautocomplete',['as'=>'invoiceautocomplete', 'uses'=>'RptInvoiceController@invoiceautocomplete']);
    Route::post('report.sales.invoice',['as'=>'invoiceprintorpreview', 'uses'=>'RptInvoiceController@invoiceprintorpreview']);
//
//    Route::get('purchase.edit.data',['as'=>'\'purchase.edit.data', 'uses'=>'SalesController@getpdata']);
//
//
//    Route::get('invoicePreviewIndex',['as'=>'invoicePreviewIndex', 'uses'=>'SalesController@invoicePreview']);

});

Route::group(['prefix' => '', 'namespace' => 'Backend\Delivery', 'middleware' => ['auth']], function () {

    Route::get('deliveryinvoiceindex',['as'=>'deliveryinvoiceindex', 'uses'=>'DeliveryProductController@index']);
    Route::get('invoice.delivery.data',['as'=>'invoice.delivery.data', 'uses'=>'DeliveryProductController@getinvoicedata']);

    Route::post('invoice.delivery.submit',['as'=>'invoice.delivery.submit', 'uses'=>'DeliveryProductController@submit']);

    Route::get('rptdeliverychallanindex',['as'=>'rptdeliverychallanindex', 'uses'=>'RptDeliveryController@index']);

    Route::post('report.delivery.challan',['as'=>'report.delivery.challan', 'uses'=>'RptDeliveryController@dcreport']);

    Route::get('challanautocomplete',['as'=>'challanautocomplete', 'uses'=>'RptDeliveryController@challanautocomplete']);

});


Route::group(['prefix' => 'yearclose', 'namespace' => 'Yearclose', 'middleware' => ['auth']], function () {

    Route::get('index',['as'=>'yearcloseindex', 'uses'=>'YearCloseController@index']);

});


