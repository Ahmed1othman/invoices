<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

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

define('PAGINATION_COUNT',10);


Route::group(['namespace' =>'App\Http\Controllers\Admin'],function(){
    Route::group(['middleware'=>'guest:admin'],function (){
        Route::get('login','LoginController@getLogin')->name('get.admin.login');
        Route::post('login','LoginController@login')->name('admin.login');

    });

    Route::group(['middleware'=>['auth:admin','user.status']],function (){




        Route::get('/','DashboardController@index')->name('admin.dashboard');
        Route::post('logout','LoginController@logout')->name('admin.logout');
        Route::get('/section/{id}','InvoiceController@getproducts')->name('invoices.getproducts');
        Route::get('/test/{id}','InvoiceController@gettest')->name('invoices.gettest');

        Route::resource('roles','RoleController');
        Route::resource('admins','AdminController');


        //////////////////////// Start Invoice Routes ////////////////////////////////
        Route::group(['prefix'=>'invoices'],function (){
            Route::get('/','InvoiceController@index')->name('invoices.index');
            Route::get('/create','InvoiceController@create')->name('invoices.create');
            Route::post('/store','InvoiceController@store')->name('invoices.store');
            Route::get('/edit/{id}','InvoiceController@edit')->name('invoices.edit');
            Route::get('/details/{id}','InvoiceController@getDetails')->name('invoices.getDetails');
            Route::post('/update/{id}','InvoiceController@update')->name('invoices.update');
            Route::post('/softDelete','InvoiceController@softDelete')->name('invoices.softDelete');
            Route::post('/destroy','InvoiceController@forceDeleted')->name('invoices.forceDeleted');
            Route::get('/edit-status/{id}','InvoiceController@editStatus')->name('invoices.editStatus');
            Route::post('/update-status/{id}','InvoiceController@updateStatus')->name('invoices.updateStatus');
            Route::get('/mark-all-read','InvoiceController@markAllAsRead')->name('invoices.notifications.markAllAsRead');





            Route::get('/paid','InvoiceController@paidInvoices')->name('invoices.paidInvoices');
            Route::get('/unpaid','InvoiceController@unpaidInvoices')->name('invoices.unpaidInvoices');
            Route::get('/partial-paid','InvoiceController@partialInvoices')->name('invoices.partialInvoices');
            Route::get('/print-invoice/{id}','InvoiceController@printInvoices')->name('invoices.printInvoices');

            //////////////////////// Start Archive Invoice Routes ////////////////////////////////
            Route::get('/archive','ArchiveController@index')->name('invoices.archiveInvoices');
            Route::post('/archive-delete','ArchiveController@destroy')->name('invoices.deleteArchiveInvoices');
            Route::post('/archive-restore','ArchiveController@restore')->name('invoices.restoreArchiveInvoices');







        });
        //////////////////////// End Invoice Routes ////////////////////////////////


        //////////////////////// Start Invoice Routes ////////////////////////////////
        Route::group(['prefix'=>'invoice-attachment'],function (){

            Route::get('/download/{invoice_number}/{file_name}','InvoiceAttachmentController@downloadAttachment')->name('invoices.downloadAttachment');
            Route::get('/view/{invoice_number}/{file_name}','InvoiceAttachmentController@viewAttachment')->name('invoices.viewAttachment');
            Route::post('/delete-attachment','InvoiceAttachmentController@deleteAttachment')->name('invoices.deleteAttachment');
            Route::post('/add-attachment','InvoiceAttachmentController@addAttachment')->name('invoices.addAttachment');

        });
        //////////////////////// End Invoice Routes ////////////////////////////////



        //////////////////////// Start reports Routes ////////////////////////////////
        Route::group(['prefix'=>'invoice-reports'],function (){
                Route::get('/','ReportController@index')->name('reports.index');
                Route::post('/Search_invoices','ReportController@Search_invoices')->name('reports.Search_invoices');
            });


        //////////////////////// End reports Routes ////////////////////////////////


        //////////////////////// Start reports Routes ////////////////////////////////
        Route::group(['prefix'=>'customer-reports'],function (){
            Route::get('/','CustomerController@index')->name('customer.reports.index');
            Route::post('/Search_invoices','CustomerController@Search_customers')->name('customer.reports.Search_customers');
        });


        //////////////////////// End reports Routes ////////////////////////////////




        //////////////////////// Start settings Routes ////////////////////////////////
        Route::group(['prefix'=>'settings'],function (){
            //////////////////////// Start Section Routes ////////////////////////////////
            Route::group(['prefix'=>'sections'],function (){
                Route::get('/','SectionController@index')->name('sections.index');
                Route::post('/store','SectionController@store')->name('sections.store');
                Route::post('/update','SectionController@update')->name('sections.update');
                Route::post('/delete','SectionController@destroy')->name('sections.delete');
            });
            //////////////////////// End Section Routes ////////////////////////////////

            //////////////////////// Start product Routes ////////////////////////////////
            Route::group(['prefix'=>'products'],function (){
                Route::get('/','ProductController@index')->name('products.index');
                Route::post('/store','ProductController@store')->name('products.store');
                Route::post('/update','ProductController@update')->name('products.update');
                Route::post('/delete','ProductController@destroy')->name('products.delete');
            });
            //////////////////////// End product Routes ////////////////////////////////

        });
        //////////////////////// End settings Routes ////////////////////////////////

    });


});


