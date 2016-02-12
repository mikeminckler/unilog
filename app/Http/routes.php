<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['middleware' => ['web']], function () {

    Route::get('login', ['as' => 'login', 'uses' => 'SessionsController@index']);
    Route::post('login', ['as' => 'login', 'uses' => 'SessionsController@login']);
    Route::get('logout', ['as' => 'logout', 'uses' => 'SessionsController@logout']);

	Route::get('/', ['as' => 'home', 'uses' => 'MessagesController@index']);

	// ajax stuff
	Route::get('search', ['as' => 'search', 'uses' => 'SearchController@search']);
	Route::get('search/student', ['as' => 'search.student', 'uses' => 'SearchController@student']);
	Route::get('search/email', ['as' => 'search.email', 'uses' => 'SearchController@email']);
	Route::get('start-date', ['as' => 'start-date', 'uses' => 'SessionsController@startDate']);
	Route::get('end-date', ['as' => 'end-date', 'uses' => 'SessionsController@endDate']);


	Route::get('messages/create', ['as' => 'messages.new', 'uses' => 'MessagesController@create']);
	Route::post('messages/create', ['as' => 'messages.new', 'uses' => 'MessagesController@store']);
	Route::post('messages/create/student', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);

	Route::get('messages/delete', ['as' => 'message.delete', 'uses' => 'MessagesController@delete']);
	Route::get('messages/delete-attachment', ['as' => 'message.delete.attachment', 'uses' => 'MessagesController@deleteAttachment']);

	Route::get('messages/{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show'])->where('id', '\d+');
	Route::post('messages/{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@store'])->where('id', '\d+');

	Route::get('messages/create/{id}', ['as' => 'messages.create.student', 'uses' => 'MessagesController@create'])->where('id', '\d+');

	Route::get('messages/attachments/{id}', ['as' => 'messages.attachment', 'uses' => 'MessagesController@downloadAttachment'])->where('id', '\d+');


	Route::get('schools/{id}', ['as' => 'messages.schools', 'uses' => 'MessagesController@showSchoolMessages'])->where('id', '\d+');
	Route::get('messages-type/{id}', ['as' => 'messages.message-types', 'uses' => 'MessagesController@showMessageTypesMessages'])->where('id', '\d+');
	Route::get('students/{id}', ['as' => 'messages.student', 'uses' => 'MessagesController@showStudentMessages'])->where('id', '\d+');

	Route::get('schools/{school_id}/messages-type/{messsage_type_id}', ['as' => 'messages.school.message-type', 'uses' => 'MessagesController@showSchoolMessageTypeMessages']);
	Route::get('messages-type/{messsage_type_id}/schools/{school_id}', ['as' => 'messages.message-type.school', 'uses' => 'MessagesController@showMessageTypeSchoolMessages']);

	Route::get('messages-type/{messsage_type_id}/students/{student_id}', ['as' => 'messages.message-type.student', 'uses' => 'MessagesController@showMessageTypeStudentMessages']);
	Route::get('students/{student_id}/messages-type/{messsage_type_id}', ['as' => 'messages.student.message-type', 'uses' => 'MessagesController@showStudentMessageTypeMessages']);
	Route::get('schools/{school_id}/students/{student_id}', ['as' =>'message.school.student', 'uses' => 'MessagesController@showSchoolStudentMessages']);
	Route::get('students/{student_id}/schools/{school_id}', ['as' =>'message.student.school', 'uses' => 'MessagesController@showStudentSchoolMessages']);

	Route::get('schools', ['as' => 'schools', 'uses' => 'SchoolsController@index']);
	Route::get('schools/create', ['as' => 'schools.create', 'uses' => 'SchoolsController@create']);
	Route::post('schools/create', ['as' => 'schools.create', 'uses' => 'SchoolsController@store']);
	Route::get('schools/edit/{id}', ['as' => 'schools.show', 'uses' => 'SchoolsController@show'])->where('id', '\d+');
	Route::post('schools/edit/{id}', ['as' => 'schools.update', 'uses' => 'SchoolsController@store'])->where('id', '\d+');


	Route::get('email', ['as' => 'emails.create', 'uses' => 'MailController@create']);
	Route::post('email', ['as' => 'emails.send', 'uses' => 'MailController@send']);
	Route::post('email/student', ['as' => 'emails.create', 'uses' => 'MailController@create']);

    Route::post('export-csv', ['as' => 'export-csv', 'uses' => 'MessagesController@exportCSV']);

    Route::get('message-types', ['as' => 'message-types', 'uses' => 'MessageTypesController@index']);
    Route::get('message-types/create', ['as' => 'message-types.create', 'uses' => 'MessageTypesController@create']);
    Route::post('message-types/create', ['as' => 'message-types.create', 'uses' => 'MessageTypesController@store']);
    Route::get('message-types/edit/{id}', ['as' => 'message-types.show', 'uses' => 'MessageTypesController@show'])->where('id', '\d+');
    Route::post('message-types/edit/{id}', ['as' => 'message-types.update', 'uses' => 'MessageTypesController@store'])->where('id', '\d+');


    Route::get('users', ['as' => 'users', 'uses' => 'UsersController@index']);
    Route::get('users/create', ['as' => 'users.create', 'uses' => 'UsersController@create']);
    Route::post('users/create', ['as' => 'users.create', 'uses' => 'UsersController@store']);
    Route::get('users/{id}', ['as' => 'users.show', 'uses' => 'UsersController@show'])->where('id', '\d+');
    Route::post('users/{id}', ['as' => 'users.update', 'uses' => 'UsersController@store'])->where('id', '\d+');

});
