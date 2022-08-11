<?php
//Clear View cache:
Route::get('/clear', function() {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('key:generate');
    Artisan::call('config:cache');
});

Route::get('/test_sms', 'CronController@test_sms' );


Route::get('/', function () {
    if(Auth::user()){
        return redirect('agents');
    }else{
        return redirect('login');
    }
});

Route::get('/get_phone', function (){
    \App\Traits\MainTrait::twilioPhoneNumber();
});

// cron controller
Route::get('/fetch-records', 'CronController@fetchRecords')->name('fetch-records');
Route::get('/send-email', 'CronController@sendEmail')->name('send-email');
Route::get('/execute-campaign', 'CronController@executeCampaign')->name('execute-campaign');
Route::get('/schedule-feedback-cron', 'CronController@schedule_feedback_cron')->name('schedule-feedback-cron');

Route::get('/open-tracking', 'CronController@open_tracking')->name('open-tracking');

Route::post('call', 'CallController@call');
Route::get('/voice.xml', 'CallController@xmlDial');
Route::any('recordingStatusCallback', 'CallController@recordingStatusCallback');

Auth::routes();

/*agent*/
Route::get('/signin', 'HomeController@agentLoginPage')->name('signin');
Route::post('/signin', 'HomeController@agentLogin')->name('signin');

Route::post('/signin', 'HomeController@agentLogin')->name('signin');

Route::get('/enter-pin', 'HomeController@enter_pin')->name('enter-pin');
Route::any('process-pin-code', 'HomeController@process_pin_code')->name('process-pin-code');

Route::any('message-reply', 'HomeController@message_reply')->name('message-reply');

Route::get('page-one', 'HomeController@page_one')->name('page-one');
Route::post('save-pin-leads', 'HomeController@save_pin_lead')->name('save-pin-leads');

Route::get('approved', 'HomeController@approved')->name('approved');


Route::group(['middleware' => ['AgentAuth']], function(){
    Route::get('/agent-panel', 'AgentsController@agentPanel')->name('agent-panel');
    Route::get('/agent-logout', 'HomeController@agentLogout')->name('agent-logout');
});

Route::get('mms-video/{slug}', 'MmsController@mms_page');
Route::get('gif_test', 'MmsController@gif_test');

//email verified routes
Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth', 'verified']], function(){
    Route::resource('user', 'UserController');
    Route::resource('agents', 'AgentsController');
    Route::resource('settings', 'SettingsController');
    Route::resource('numbers', 'NumberController');
    Route::resource('categories', 'CategoriesController');
    Route::resource('campaign', 'CampaignController');
    Route::resource('mms', 'MmsController');

    Route::get('/all-leads', function (){
        $category = \App\Categories::where('user_id', auth()->user()->id)->get();
        return view('users.agents.leads', compact('category'));
    })->name('all-leads');



    Route::get('get-campaigns', 'CampaignController@get_campaigns');
    Route::get('campaign-stats/{id}', 'CampaignController@campaign_stats');
    Route::get('get-campaigns-stats', 'CampaignController@get_campaigns_stats');
    Route::post('campaign-error', 'CampaignController@campaign_error');

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('keypad-settings', 'HomeController@keypadSettings')->name('keypad-settings');
    Route::post('save-keypad-settings', 'HomeController@saveKeypadSettings')->name('save-keypad-settings');


    Route::post('run-test', 'CronController@runTest')->name('run-test');

    //ajax main routes
    Route::post('delete', 'AjaxController@delete');
    Route::post('change-status', 'AjaxController@changeStatus');
    Route::post('load-child-sheets', 'AjaxController@loadChildSheet')->name('load-child-sheets');

    Route::get('load-contacts',  'AjaxController@loadContacts')->name('load-contacts');
    Route::get('load-lists',  'AjaxController@loadLists')->name('load-lists');
    Route::get('get-sheet-leads',  'AjaxController@getSheetLeads')->name('get-sheet-leads');
    Route::get('get-all-leads',  'AjaxController@getAllLeads')->name('get-all-leads');

    Route::resource('contact-list',  'ContactListController');
    Route::get('load_list', 'ContactListController@loadList')->name('load_list');

    Route::get('edit-contact/{id}', 'ContactListController@editContact');
    Route::post('contact-store', 'ContactListController@contactStore')->name('contact-store');
    Route::post('save-email-settings', 'ContactListController@saveEmailSettings')->name('save-email-settings');



    Route::post('search-number', 'NumberController@searchNumber')->name('search-number');
    Route::get('purchase-number/{number}', 'NumberController@purchaseNumber');

});

