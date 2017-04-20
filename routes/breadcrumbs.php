<?php

// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Home', '/');
});


Breadcrumbs::register('lists', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('Lists', action('ListController@index'));
});

Breadcrumbs::register('create-list', function($breadcrumbs) {
	$breadcrumbs->parent('lists');
	$breadcrumbs->push('Create', action('ListController@create'));
});

Breadcrumbs::register('list', function($breadcrumbs, \App\MailList $list) {
	$breadcrumbs->parent('lists');
	$breadcrumbs->push('List: ' . $list->title, action('ListController@single', $list->id));
});

Breadcrumbs::register('list-stats', function($breadcrumbs, \App\MailList $list) {
	$breadcrumbs->parent('list', $list);
	$breadcrumbs->push('Stats');
});

Breadcrumbs::register('list-queue', function($breadcrumbs, \App\MailList $list) {
	$breadcrumbs->parent('list', $list);
	$breadcrumbs->push('Queue');
});


Breadcrumbs::register('messages', function($breadcrumbs, \App\MailList $list) {
	$breadcrumbs->parent('list', $list);
	$breadcrumbs->push('Messages', action('MessageController@index', $list->id));
});

Breadcrumbs::register('message', function($breadcrumbs, \App\Message $message) {
	$breadcrumbs->parent('messages', $message->mailList);
	$breadcrumbs->push($message->name, action('MessageController@edit', ['list' => $message->mailList->id, 'message' => $message->id]));
});

Breadcrumbs::register('create-message', function($breadcrumbs, \App\MailList $list) {
	$breadcrumbs->parent('list', $list);
	$breadcrumbs->push('Create Message', action('MessageController@create', $list->id));
});