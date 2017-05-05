<?php

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



Route::group(['middleware' => 'auth'], function() {
	Route::get('/', 'ListController@index')->middleware('lists-exist');
	Route::get('/lists/create', 'ListController@create');
	Route::post('/lists/create','ListController@store');
	Route::post('/lists/import', 'ListController@saveEntries');
	Route::get('/lists/{list}', 'ListController@single');
	Route::post('/lists/{list}', 'ListController@update');
	Route::get('/lists/{list}/stats', 'ListController@singleStats');
	Route::get('/lists/{list}/import', 'ListController@import');
	Route::get('/lists/{list}/start', 'ListController@startCampaign');
	Route::post('/lists/{list}/import','ListController@importEntries');

	Route::get('/lists/{list}/export', 'ListController@exportListEntries');
	Route::get('/lists/{list}/clear', 'ListController@clearListEntries')->middleware('list-active');;
	Route::get('/lists/{list}/remove', 'ListController@deleteList');
	Route::get('/lists/{list}/queue', 'ListController@viewQueue');
	Route::get('/lists/{list}/queue/export','QueueController@export');
	Route::get('/lists/{list}/pause', 'ListController@pauseCampaign');
	Route::get('/lists/{list}/resume', 'ListController@resumeCampaign');
	Route::get('/lists/{list}/stop', 'ListController@stopCampaign');
	Route::get('/lists/{list}/messages/', 'MessageController@index');
	Route::get('/lists/{list}/messages/create', 'MessageController@create')->middleware('list-active');
	Route::post('/lists/{list}/messages/create','MessageController@save')->middleware('list-active');
	Route::get('/lists/{list}/messages/{message}', 'MessageController@edit')->middleware('list-active');
	Route::post('/lists/{list}/messages/{message}', 'MessageController@update')->middleware('list-active');
	Route::get('/messages/{message}/render', 'MessageController@render');
	Route::post('/messages/{message}/test', 'MessageController@sendTestMessage');
	Route::get('/stats', 'StatController@view');
	Route::get('/queues','QueueController@index');
	Route::get('/options', 'OptionController@index');
	Route::post('/options','OptionController@update');

	Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
	Route::get('/generators/{action?}', 'GeneratorController@generate');

	// Route::get('info', function() {
	// 	phpinfo();
	// });

});

Route::post('/webhooks/{service}/{action}', 'WebhookController@processWebhook');
Route::get('/unsubscribe/{email}','SubscriptionController@unsubscribe');
Auth::routes();

