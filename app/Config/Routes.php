<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
// $routes->setDefaultController('Home');
$routes->setDefaultController('Login_controller');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// $routes->setAutoRoute(true);
// $routes->setAutoRoute(false);
/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');
$routes->get('/', 'Login_controller::index');
// $routes->add('Login_controller/test', 'Login_controller::test');
$routes->add('Login_controller/checkLogin', 'Login_controller::checkLogin');
$routes->add('Login_controller/logout', 'Login_controller::logout');

$routes->add('/dashboard', 'DashBoard_Controller::index');
$routes->add('/dashboard', 'DashBoard_Controller::index', ['filter' => 'auth']);
$routes->add('/organisation', 'Organisation_Controller::index', ['filter' => 'auth']);
$routes->post('/Organisation_Controller/update', 'Organisation_Controller::update', ['filter' => 'auth']);

$routes->get('/customers', 'Customers_Controller::index', ['filter' => 'auth']);
$routes->post('/Customers_Controller/insert', 'Customers_Controller::insert', ['filter' => 'auth']);
$routes->post('/Customers_Controller/update', 'Customers_Controller::update', ['filter' => 'auth']);
$routes->post('/Customers_Controller/delete', 'Customers_Controller::delete', ['filter' => 'auth']);
$routes->post('/Customers_Controller/loadAllRecords', 'Customers_Controller::loadAllRecords', ['filter' => 'auth']);

$routes->add('/itemgroups', 'ItemGroups_Controller::index', ['filter' => 'auth']);
$routes->post('/ItemGroups_Controller/insert', 'ItemGroups_Controller::insert', ['filter' => 'auth']);
$routes->post('/ItemGroups_Controller/update', 'ItemGroups_Controller::update', ['filter' => 'auth']);
$routes->post('/ItemGroups_Controller/delete', 'ItemGroups_Controller::delete', ['filter' => 'auth']);

$routes->add('/items', 'Items_Controller::index', ['filter' => 'auth']);
$routes->post('/Items_Controller/insert', 'Items_Controller::insert', ['filter' => 'auth']);
$routes->post('/Items_Controller/update', 'Items_Controller::update', ['filter' => 'auth']);
$routes->post('/Items_Controller/delete', 'Items_Controller::delete', ['filter' => 'auth']);
$routes->post('/Items_Controller/loadAllRecords', 'Items_Controller::loadAllRecords', ['filter' => 'auth']);

$routes->add('/edititems', 'EditItems_Controller::index', ['filter' => 'auth']);
$routes->post('/EditItems_Controller/showData', 'EditItems_Controller::showData', ['filter' => 'auth']);
$routes->post('/EditItems_Controller/showDataWithDt', 'EditItems_Controller::showDataWithDt', ['filter' => 'auth']);
$routes->post('/EditItems_Controller/showDataDeleted', 'EditItems_Controller::showDataDeleted', ['filter' => 'auth']);
$routes->post('/EditItems_Controller/saveData', 'EditItems_Controller::saveData', ['filter' => 'auth']);
$routes->post('/EditItems_Controller/delete', 'EditItems_Controller::delete', ['filter' => 'auth']);
$routes->post('/EditItems_Controller/undelete', 'EditItems_Controller::undelete', ['filter' => 'auth']);
$routes->post('/EditItems_Controller/getClosingBalance', 'EditItems_Controller::getClosingBalance', ['filter' => 'auth']);
$routes->post('/EditItems_Controller/showDataNew', 'EditItems_Controller::showDataNew', ['filter' => 'auth']);

$routes->add('/edititemsgroup', 'EditItemsGroup_Controller::index', ['filter' => 'auth']);
$routes->post('/EditItemsGroup_Controller/showData', 'EditItemsGroup_Controller::showData', ['filter' => 'auth']);
$routes->post('/EditItemsGroup_Controller/saveData', 'EditItemsGroup_Controller::saveData', ['filter' => 'auth']);

$routes->add('/quotation', 'Quotation_Controller::index', ['filter' => 'auth']);
$routes->post('/Quotation_Controller/insert', 'Quotation_Controller::insert', ['filter' => 'auth']);
$routes->post('/Quotation_Controller/showDetailOnUpdate', 'Quotation_Controller::showDetailOnUpdate', ['filter' => 'auth']);
$routes->post('/Quotation_Controller/checkForUpdate', 'Quotation_Controller::checkForUpdate', ['filter' => 'auth']);
$routes->post('/Quotation_Controller/update', 'Quotation_Controller::update', ['filter' => 'auth']);
$routes->post('/Quotation_Controller/delete', 'Quotation_Controller::delete', ['filter' => 'auth']);
$routes->post('/Quotation_Controller/loadAllRecords', 'Quotation_Controller::loadAllRecords', ['filter' => 'auth']);
$routes->post('/Quotation_Controller/printNow', 'Quotation_Controller::printNow', ['filter' => 'auth']);


$routes->add('/purchase', 'Purchase_Controller::index', ['filter' => 'auth']);
$routes->post('/Purchase_Controller/insert', 'Purchase_Controller::insert', ['filter' => 'auth']);
$routes->post('/Purchase_Controller/showDetailOnUpdate', 'Purchase_Controller::showDetailOnUpdate', ['filter' => 'auth']);
$routes->post('/Purchase_Controller/checkForUpdate', 'Purchase_Controller::checkForUpdate', ['filter' => 'auth']);
$routes->post('/Purchase_Controller/update', 'Purchase_Controller::update', ['filter' => 'auth']);
$routes->post('/Purchase_Controller/delete', 'Purchase_Controller::delete', ['filter' => 'auth']);
$routes->post('/Purchase_Controller/loadAllRecords', 'Purchase_Controller::loadAllRecords', ['filter' => 'auth']);
$routes->post('/Purchase_Controller/searchRecords', 'Purchase_Controller::searchRecords', ['filter' => 'auth']);
$routes->post('/Purchase_Controller/getPurchaseDetial', 'Purchase_Controller::getPurchaseDetial', ['filter' => 'auth']);
$routes->post('/Purchase_Controller/getPurchaseLog', 'Purchase_Controller::getPurchaseLog', ['filter' => 'auth']);


$routes->add('/sale', 'Sale_Controller::index', ['filter' => 'auth']);
$routes->post('/Sale_Controller/insert', 'Sale_Controller::insert', ['filter' => 'auth']);
$routes->post('/Sale_Controller/showDetailOnUpdate', 'Sale_Controller::showDetailOnUpdate', ['filter' => 'auth']);
$routes->post('/Sale_Controller/checkForUpdate', 'Sale_Controller::checkForUpdate', ['filter' => 'auth']);
$routes->post('/Sale_Controller/update', 'Sale_Controller::update', ['filter' => 'auth']);
$routes->post('/Sale_Controller/delete', 'Sale_Controller::delete', ['filter' => 'auth']);
$routes->post('/Sale_Controller/printNow/(:any)/(:any)', 'Sale_Controller::printNow/$1/$2', ['filter' => 'auth']);
$routes->post('/Sale_Controller/loadAllRecords', 'Sale_Controller::loadAllRecords', ['filter' => 'auth']);
$routes->post('/Sale_Controller/searchRecords', 'Sale_Controller::searchRecords', ['filter' => 'auth']);
$routes->post('/Sale_Controller/getQuotations', 'Sale_Controller::getQuotations', ['filter' => 'auth']);
$routes->post('/Sale_Controller/getAllQuotations', 'Sale_Controller::getAllQuotations', ['filter' => 'auth']);
$routes->post('/Sale_Controller/getQuotationProducts', 'Sale_Controller::getQuotationProducts', ['filter' => 'auth']);
$routes->post('/Sale_Controller/getSaleDetial', 'Sale_Controller::getSaleDetial', ['filter' => 'auth']);
$routes->post('/Sale_Controller/getSaleLog', 'Sale_Controller::getSaleLog', ['filter' => 'auth']);
$routes->post('/Sale_Controller/getCurrentQtyOfThisItem', 'Sale_Controller::getCurrentQtyOfThisItem', ['filter' => 'auth']);


$routes->add('/paymentreceipt', 'PaymentReceipt_Controller::index', ['filter' => 'auth']);
$routes->post('/PaymentReceipt_Controller/insert', 'PaymentReceipt_Controller::insert', ['filter' => 'auth']);
$routes->post('/PaymentReceipt_Controller/update', 'PaymentReceipt_Controller::update', ['filter' => 'auth']);
$routes->post('/PaymentReceipt_Controller/delete', 'PaymentReceipt_Controller::delete', ['filter' => 'auth']);
$routes->post('/PaymentReceipt_Controller/showData', 'PaymentReceipt_Controller::showData', ['filter' => 'auth']);
$routes->post('/PaymentReceipt_Controller/loadAllRecords', 'PaymentReceipt_Controller::loadAllRecords', ['filter' => 'auth']);


$routes->add('/reminders', 'Reminders_Controller::index', ['filter' => 'auth']);
$routes->post('/Reminders_Controller/insert', 'Reminders_Controller::insert', ['filter' => 'auth']);
$routes->post('/Reminders_Controller/update', 'Reminders_Controller::update', ['filter' => 'auth']);
$routes->post('/Reminders_Controller/delete', 'Reminders_Controller::delete', ['filter' => 'auth']);

$routes->add('/requirement', 'Requirement_Controller::index', ['filter' => 'auth']);
$routes->post('/Requirement_Controller/insert', 'Requirement_Controller::insert', ['filter' => 'auth']);
$routes->post('/Requirement_Controller/getPurchaseLog', 'Requirement_Controller::getPurchaseLog', ['filter' => 'auth']);
$routes->post('/Requirement_Controller/delete', 'Requirement_Controller::delete', ['filter' => 'auth']);
$routes->post('/Requirement_Controller/deleteChecked', 'Requirement_Controller::deleteChecked', ['filter' => 'auth']);

$routes->add('/replacement', 'Replacement_Controller::index', ['filter' => 'auth']);
$routes->post('/Replacement_Controller/insert', 'Replacement_Controller::insert', ['filter' => 'auth']);
$routes->post('/Replacement_Controller/update', 'Replacement_Controller::update', ['filter' => 'auth']);
$routes->post('/Replacement_Controller/delete', 'Replacement_Controller::delete', ['filter' => 'auth']);
$routes->post('/Replacement_Controller/setSent', 'Replacement_Controller::setSent', ['filter' => 'auth']);
$routes->post('/Replacement_Controller/setRecd', 'Replacement_Controller::setRecd', ['filter' => 'auth']);
$routes->post('/Replacement_Controller/loadAllRecords', 'Replacement_Controller::loadAllRecords', ['filter' => 'auth']);

$routes->add('/rptledger', 'RptLedger_Controller::index', ['filter' => 'auth']);
$routes->add('/rptledger/yeParty/(:any)', 'RptLedger_Controller::yeParty/(:any)', ['filter' => 'auth']);
$routes->post('/RptLedger_Controller/showData', 'RptLedger_Controller::showData', ['filter' => 'auth']);
$routes->post('/RptLedger_Controller/getSaleDetail', 'RptLedger_Controller::getSaleDetail', ['filter' => 'auth']);
$routes->post('/RptLedger_Controller/getPurchaseDetail', 'RptLedger_Controller::getPurchaseDetail', ['filter' => 'auth']);

$routes->add('/rptledgeritem', 'RptLedgerItem_Controller::index', ['filter' => 'auth']);
$routes->add('/rptledgeritem/yeItem/(:any)', 'RptLedgerItem_Controller::yeItem/(:any)', ['filter' => 'auth']);
$routes->post('/RptLedgerItem_Controller/showData', 'RptLedgerItem_Controller::showData', ['filter' => 'auth']);


$routes->add('/rptitemspurchaseandsold', 'RptItemsPurchaseAndSold_Controller::index', ['filter' => 'auth']);
$routes->post('/RptItemsPurchaseAndSold_Controller/showData', 'RptItemsPurchaseAndSold_Controller::showData', ['filter' => 'auth']);
$routes->post('/RptItemsPurchaseAndSold_Controller/showDataExcel', 'RptItemsPurchaseAndSold_Controller::showDataExcel', ['filter' => 'auth']);
// $routes->post('/RptItemsPurchaseAndSold_Controller/showDataExcel', 'RptItemsPurchaseAndSold_Controller::showDataExcel', ['filter' => 'auth']);

$routes->get('/rptitemspurchaseandsoldpaging', 'RptItemsPurchaseAndSoldPaging_Controller::index', ['filter' => 'auth']);
$routes->add('/rptitemspurchaseandsoldpaging/showData', 'RptItemsPurchaseAndSoldPaging_Controller::showData', ['filter' => 'auth']);

$routes->add('/rptdues', 'RptDues_Controller::index', ['filter' => 'auth']);
$routes->post('/RptDues_Controller/showData', 'RptDues_Controller::showData', ['filter' => 'auth']);
$routes->post('/RptDues_Controller/showDataLedger', 'RptDues_Controller::showDataLedger', ['filter' => 'auth']);
$routes->post('/RptDues_Controller/receiveAmt', 'RptDues_Controller::receiveAmt', ['filter' => 'auth']);
$routes->post('/RptDues_Controller/payAmt', 'RptDues_Controller::payAmt', ['filter' => 'auth']);
$routes->post('/RptDues_Controller/markDoobat', 'RptDues_Controller::markDoobat', ['filter' => 'auth']);
$routes->post('/RptDues_Controller/deleteOldRecs', 'RptDues_Controller::deleteOldRecs', ['filter' => 'auth']);

$routes->add('/rptsearch', 'RptSearch_Controller::index', ['filter' => 'auth']);
$routes->post('/RptSearch_Controller/showData', 'RptSearch_Controller::showData', ['filter' => 'auth']);

$routes->add('/rptdaybook', 'RptDayBook_Controller::index', ['filter' => 'auth']);
$routes->post('/RptDayBook_Controller/showData', 'RptDayBook_Controller::showData', ['filter' => 'auth']);
$routes->post('/RptDayBook_Controller/getSaleDetail', 'RptLedger_Controller::getSaleDetail', ['filter' => 'auth']);
$routes->post('/RptDayBook_Controller/getPurchaseDetail', 'RptLedger_Controller::getPurchaseDetail', ['filter' => 'auth']);

$routes->add('/user', 'User_Controller::index', ['filter' => 'auth']);
$routes->post('/User_Controller/insertUser', 'User_Controller::insertUser', ['filter' => 'auth']);
$routes->post('/User_Controller/updateUser', 'User_Controller::updateUser', ['filter' => 'auth']);
$routes->post('/User_Controller/deleteUser', 'User_Controller::deleteUser', ['filter' => 'auth']);

$routes->add('/right', 'Right_Controller::index', ['filter' => 'auth']);
$routes->post('/Right_Controller/insertRights', 'Right_Controller::insertRights', ['filter' => 'auth']);
$routes->post('/Right_Controller/getRights', 'Right_Controller::getRights', ['filter' => 'auth']);

$routes->add('/changepwdadmin', 'Changepwdadmin_Controller::index', ['filter' => 'auth']);
$routes->post('/Changepwdadmin_Controller/checkLogin', 'Changepwdadmin_Controller::checkLogin', ['filter' => 'auth']);

$routes->add('/backupdata', 'Backupdata_Controller::index', ['filter' => 'auth']);
$routes->post('/Backupdata_Controller/dbbackup', 'Backupdata_Controller::dbbackup', ['filter' => 'auth']);

$routes->add('/adminrights', 'AdminRights_Controller::index', ['filter' => 'auth']);
$routes->post('/AdminRights_Controller/insert', 'AdminRights_Controller::insert', ['filter' => 'auth']);
$routes->post('/AdminRights_Controller/delete', 'AdminRights_Controller::delete', ['filter' => 'auth']);


$routes->add('/duplicates', 'Duplicates_Controller::index', ['filter' => 'auth']);
$routes->post('/Duplicates_Controller/showData', 'Duplicates_Controller::showData', ['filter' => 'auth']);
$routes->post('/Duplicates_Controller/replaceNow', 'Duplicates_Controller::replaceNow', ['filter' => 'auth']);

$routes->add('/duplicatecustomers', 'DuplicateCustomers_Controller::index', ['filter' => 'auth']);
$routes->post('/DuplicateCustomers_Controller/showData', 'DuplicateCustomers_Controller::showData', ['filter' => 'auth']);
$routes->post('/DuplicateCustomers_Controller/replaceNow', 'DuplicateCustomers_Controller::replaceNow', ['filter' => 'auth']);

$routes->add('/addressbook', 'AddressBook_Controller::index', ['filter' => 'auth']);
$routes->post('/AddressBook_Controller/insert', 'AddressBook_Controller::insert', ['filter' => 'auth']);
$routes->post('/AddressBook_Controller/update', 'AddressBook_Controller::update', ['filter' => 'auth']);
$routes->post('/AddressBook_Controller/delete', 'AddressBook_Controller::delete', ['filter' => 'auth']);


$routes->add('/conclusions', 'Conclusions_Controller::index', ['filter' => 'auth']);
$routes->post('/Conclusions_Controller/insert', 'Conclusions_Controller::insert', ['filter' => 'auth']);
$routes->post('/Conclusions_Controller/update', 'Conclusions_Controller::update', ['filter' => 'auth']);
$routes->post('/Conclusions_Controller/delete', 'Conclusions_Controller::delete', ['filter' => 'auth']);


$routes->add('/todo', 'ToDo_Controller::index', ['filter' => 'auth']);
$routes->post('/ToDo_Controller/insert', 'ToDo_Controller::insert', ['filter' => 'auth']);
$routes->post('/ToDo_Controller/update', 'ToDo_Controller::update', ['filter' => 'auth']);
$routes->post('/ToDo_Controller/delete', 'ToDo_Controller::delete', ['filter' => 'auth']);


$routes->add('/dailycash', 'DailyCash_Controller::index', ['filter' => 'auth']);
$routes->post('/DailyCash_Controller/insert', 'DailyCash_Controller::insert', ['filter' => 'auth']);
$routes->post('/DailyCash_Controller/showDataAll', 'DailyCash_Controller::showDataAll', ['filter' => 'auth']);
$routes->post('/DailyCash_Controller/loadIntervalJobs', 'DailyCash_Controller::loadIntervalJobs', ['filter' => 'auth']);
$routes->post('/DailyCash_Controller/deleteOldData', 'DailyCash_Controller::deleteOldData', ['filter' => 'auth']);
$routes->post('/DailyCash_Controller/saveUpiAmt', 'DailyCash_Controller::saveUpiAmt', ['filter' => 'auth']);


$routes->add('/changepwd', 'Changepwd_Controller::index', ['filter' => 'auth']);
$routes->post('/Changepwd_Controller/checkLogin', 'Changepwd_Controller::checkLogin', ['filter' => 'auth']);

$routes->post('/Backupdata_Controller/createDummyData', 'Backupdata_Controller::createDummyData', ['filter' => 'auth']);
$routes->post('/Backupdata_Controller/qrCode', 'Backupdata_Controller::qrCode', ['filter' => 'auth']);
$routes->post('/Backupdata_Controller/setZero', 'Backupdata_Controller::setZero', ['filter' => 'auth']);
$routes->post('/Backupdata_Controller/showRechargeLimit', 'Backupdata_Controller::showRechargeLimit', ['filter' => 'auth']);
$routes->post('/Backupdata_Controller/plusTen', 'Backupdata_Controller::plusTen', ['filter' => 'auth']);
$routes->post('/Backupdata_Controller/plusTwenty', 'Backupdata_Controller::plusTwenty', ['filter' => 'auth']);

$routes->add('/family', 'Family_Controller::index', ['filter' => 'auth']);
$routes->post('/Family_Controller/insert', 'Family_Controller::insert', ['filter' => 'auth']);
$routes->post('/Family_Controller/update', 'Family_Controller::update', ['filter' => 'auth']);
$routes->post('/Family_Controller/delete', 'Family_Controller::delete', ['filter' => 'auth']);
$routes->post('/Family_Controller/getChildren', 'Family_Controller::getChildren', ['filter' => 'auth']);
$routes->post('/Family_Controller/saveChildOrder', 'Family_Controller::saveChildOrder', ['filter' => 'auth']);
$routes->post('/Family_Controller/showDataForBulkEdit', 'Family_Controller::showDataForBulkEdit', ['filter' => 'auth']);
$routes->post('/Family_Controller/saveBulkEdit', 'Family_Controller::saveBulkEdit', ['filter' => 'auth']);

$routes->add('/familytree', 'FamilyTree_Controller::index', ['filter' => 'auth']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
