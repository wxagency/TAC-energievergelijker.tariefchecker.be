<?php

//use Session;
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


Route::get('get-data', 'ProductController@filter');
Route::get('get-data-elec', 'ProductController@seperete_electricity');
Route::get('get-data-gas', 'ProductController@seperete_gas');
Route::get('get-data-pack', 'ProductController@seperete_pack');
Route::get('discount-active','ProductController@promoDiscount');

Route::get('modal-data','ProductController@modalData')->name('modal-data');

Route::get('get-data-sep', 'ProductController@filter_seperate');
// Route::get('get-default', 'ProductController@defaultData');

//Route::get('/', 'TacController@index')->name('home');
Route::get('/clear-cache', function() {
   
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return "Cache is cleared";
});

Route::get('test', 'TacController@test');

Route::get('locale/{locale}', function ($locale) {
    \Session::put('locale', $locale);
    return redirect()->back();
});

Route::get('locale/fr', function ($locale) {
    Session::put('locale','fr');
    return redirect()->back();
});

Route::get('locale/nl', function ($locale) {
    Session::put('locale','nl');
    return redirect()->back();
});



//  front-side

Route::get('/', function() {

    echo "test";
     Session::put('locale','nl');
    return redirect('start');
});

Route::get('start', 'TacController@index')->name('index');

Route::get('referral_form', 'TacController@index')->name('index');
Route::get('refresh_uuid', 'TacController@refresh_uuid')->name('refresh_uuid');

Route::post('/consumption-details', 'ProductController@package')->name('basic-data');
Route::get('/estimate-consumption', 'ProductController@estimate')->name('estimate-consumption');
Route::get('/estimate-consumption-cal', 'ProductController@estimate_cal')->name('estimate-consumption-cal');

Route::get('/overzicht', 'ProductController@packages')->name('basic-datas');
Route::get('/overzicht/{cat}/{pin}', 'ProductController@packages')->name('basic-datas');

Route::get('/overzicht/{cat}/{pin}/{pin2}', 'ProductController@detailPack')->name('basic-data');


Route::get('/overview/{pack}', 'ProductController@packages')->name('packages');
Route::post('find-pack', 'ProductController@find_pack')->name('find-pack');
Route::get('back-to-packages', 'ProductController@back_to_packages')->name('back-to-packages');
Route::post('consumption-details/loadmore', 'ProductController@loadMore')->name('loadmore');
Route::get('details/pack/{id}/{id2}','ProductController@packages')->name('detailPack');
//end-front side
// admin-start

Route::get('admin', 'AdminAuth\LoginController@showLoginForm')->name('login');
//Route::get('tac-login', 'AdminAuth\LoginController@showLoginForm')->name('login');
Route::group(['prefix' => 'admin'], function () {

    Route::post('/login', 'AdminAuth\LoginController@login');
    Route::get('/logout', 'AdminAuth\LoginController@logout')->name('admin-logout');

    Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'AdminAuth\RegisterController@register');

    Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
    Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email');
    Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');

    //Profile update
    Route::resource('profile', 'Admin\ProfileController');

    // subscription

    Route::get('/subscriptions', 'Subscription\SubscriptionController@index')->name('admin.subscriptions')->middleware('admin');
    Route::get('/add-subscriptions', 'Subscription\SubscriptionController@addContact')->name('admin.add-subscriptions')->middleware('admin');
    Route::post('/add-subscriptions', 'Subscription\SubscriptionController@add')->name('admin.add-subscriptions')->middleware('admin');

    Route::get('/delete-subscriptions/{id}', 'Subscription\SubscriptionController@delete')->name('admin.delete-subscriptions')->middleware('admin');

    // user data log

    Route::get('user-log', 'Admin\UserLogController@index')->name('user-log')->middleware('admin');
    Route::post('user-log/ktdata', 'Admin\UserLogController@ktData')->name('user-log.ktdata')->middleware('admin');
    Route::delete('user-log/delete/{id}', 'Admin\UserLogController@delete')->name('user-log.delete')->middleware('admin');

    // admins

    Route::post('admin-users/data', 'Admin\admins\AdminController@data')->name('admin-users.data')->middleware('admin');
    Route::get('admin-users', 'Admin\admins\AdminController@index')->name('admin.admin-users.index')->middleware('admin');

    Route::get('add-admin-users', 'Admin\admins\AdminController@create')->name('admin.add-admin-users')->middleware('admin');

    Route::post('add-admin-users', 'Admin\admins\AdminController@store')->name('admin.store-admin-users')->middleware('admin');
    Route::get('admin-users/edit/{id}', 'Admin\admins\AdminController@edit')->name('edit.admin-users')->middleware('admin');
    Route::put('admin-users/update/{id}', 'Admin\admins\AdminController@update')->name('admin-users.update')->middleware('auth:admin');
    Route::delete('admin-users/delete/{id}', 'Admin\admins\AdminController@destroy')->name('admin-users.destroy')->middleware('admin');

    //Product
    Route::resource('product', 'Admin\ProductController')->middleware('admin');
    Route::post('product/data', 'Admin\ProductController@data')->name('product.data')->middleware('admin');

    // Postal Code
    Route::resource('postalcode', 'Admin\PostalCodeController')->middleware('admin');
    Route::post('postalcode/data', 'Admin\PostalCodeController@data')->name('postalcode.data')->middleware('admin');

    // Request content
    Route::resource('request-content', 'Admin\RequestContentController')->middleware('admin');
    Route::post('request-content/add', 'Admin\RequestContentController@dataStore')->name('request-content.add')->middleware('admin');
    Route::post('request-content/data', 'Admin\RequestContentController@data')->name('request-content.data')->middleware('admin');
//    Route::get('request-content/subdata', 'Admin\RequestContentController@addSubtitle')->name('request-content.addsubtitle')->middleware('admin');
    
    //Banner data
    Route::resource('banner-content', 'Admin\BannerContentController')->middleware('admin');
    Route::post('banner-content/data', 'Admin\BannerContentController@data')->name('banner-content.data')->middleware('admin');
    
    // Language Switching
    Route::resource('page', 'Admin\PageController')->middleware('admin');
    Route::post('page/data', 'Admin\PageController@data')->name('page.data')->middleware('admin');
    Route::resource('language', 'Admin\LanguageController')->middleware('admin');
    Route::post('language/data/{page_id}', 'Admin\LanguageController@data')->name('language.data')->middleware('admin');
    
    // Feature 
    Route::resource('feature', 'Admin\FeatureController')->middleware('admin');
    Route::post('feature/data', 'Admin\FeatureController@data')->middleware('admin')->name('feature.data');
    Route::post('feature/servicedata', 'Admin\FeatureController@serviceData')->middleware('admin')->name('feature.servicedata');
    
    //Manage Tooltip
    Route::resource('tooltip', 'Admin\TooltipController')->middleware('admin');
    Route::post('tooltip/data', 'Admin\TooltipController@data')->middleware('admin')->name('tooltip.data');
    Route::get('admin/wizard', 'Admin\TooltipController@wizard')->middleware('admin')->name('wizard');
    Route::post('update-wizard', 'Admin\TooltipController@update_wizard')->middleware('admin')->name('update_wizard');
    //Manage Footer
    Route::resource('footer', 'Admin\FooterController')->middleware('admin');
    Route::post('footer/data', 'Admin\FooterController@data')->middleware('admin')->name('footer.data');

// Route::get('add-admin-users', 'Admin\admins\AdminController@create')->name('admin.add-admin-users')->middleware('admin');
// admins
    // testing 
//    Route::get('introjs', 'Admin\TestController@introJs')->name('introjs.test')->middleware('admin');
});



Route::group(['prefix' => 'customer'], function () {
    Route::get('/login', 'CustomerAuth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'CustomerAuth\LoginController@login');
    Route::post('/logout', 'CustomerAuth\LoginController@logout')->name('logout');

    Route::get('/register', 'CustomerAuth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'CustomerAuth\RegisterController@register');

    Route::post('/password/email', 'CustomerAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
    Route::post('/password/reset', 'CustomerAuth\ResetPasswordController@reset')->name('password.email');
    Route::get('/password/reset', 'CustomerAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::get('/password/reset/{token}', 'CustomerAuth\ResetPasswordController@showResetForm');
});






Route::group(['prefix' => 'webhook'], function () {


    Route::post('/contact-update', 'ActiveCampaign\ActiveCampaignController@contact_update');
    Route::post('/contact-delete', 'ActiveCampaign\ActiveCampaignController@contact_delete');
});

// admin-end
//cronejob

Route::get('cron/update-supplier', 'CronjobController@storeSupplier')->name('update-supplier');

//end-cron-job
// user-data entry

 Route::resource('user-details', 'UserdetailController');
 Route::post('user-details-ajax', 'UserdetailController@ajax_store');
//end-cron-job

Route::get('get_compare','CampareController@get_compare')->name('get_compare');
Route::get('get_compare_button','CampareController@get_compare_button')->name('get_compare_button');
Route::get('get_sort','SortController@get_sort')->name('get_sort');

//request 
Route::get('bevestiging','UserdetailController@index')->name('choose');
//Route::get('bevestiging/{supplier}/{product}','ProductController@detailPack')->name('choose');
Route::get('bevestiging/{supplier}/{product}',function(){
    
    return back();
    
    });
//Route::post('bevestiging/{supplier}/{product}/{dual}','ProductController@detailPack')->name('choose');

Route::get('bevestiging/{supplier}/{product}/{dual}',function(){
    
    return back();
    
    });
//end-cron-job

//change-data

Route::post('find-packages','ChangedataController@changedata_private')->name('find-packages');
Route::get('find-packages',function(){
    return back();
    })->name('find-packages');
Route::post('change-data-prefosional','ChangedataController@changedata_prefosional')->name('change-data-prefosional');
//change-data

// send mail deals
Route::post('send-deals', 'EmailController@dealsMail')->name('send-deals');
//Route::get('load', 'EmailController@index')->name('load');
Route::get('/get_save_price', 'ProductController@get_save_price')->name('get_save_price');

Route::get('/product_select', 'ProductController@product_select')->name('product_select');
Route::get('/check-po', 'ProductController@check_po')->name('check_po');
//Route::get('/data-request', 'UserdetailController@dataRequest')->name('dataRequest');
Route::post('data-request', 'UserdetailController@dataRequestPost')->name('dataRequestPost');