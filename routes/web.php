<?php

use App\Http\Controllers\SessionsController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\MessageTypesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SchoolsController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UsersController;

Route::group(['middleware' => ['web']], function () {
    Route::get('login', [SessionsController::class, 'index'])->name('login');
    Route::post('login', [SessionsController::class, 'login'])->name('login');
    Route::get('logout', [SessionsController::class, 'logout'])->name('logout');

    Route::get('/', [MessagesController::class, 'index'])->name('messages.index');

    Route::group(['middleware' => ['auth']], function () {

        // ajax stuff
        Route::post('search', [SearchController::class, 'search'])->name('search');
        Route::post('search/student', [SearchController::class, 'student'])->name('search.student');
        Route::post('search/email', [SearchController:class, 'email'])->name('search.email');
        Route::get('start-date', [SessionsController::class, 'startDate'])->name('start-date');
        Route::get('end-date', [SessionsController::class, 'endDate'])->name('end-date');

        Route::get('messages/create', [MessagesController::class, 'create'])->name('messages.new');
        Route::post('messages/create', [MessagesController::class, 'store'])->name('messages.new');
        Route::post('messages/create/student', [MessagesController::class, 'create'])->name('messages.create');

        Route::get('messages/delete', [MessagesController::class, 'delete'])->name('messages.delete');
        Route::get('messages/delete-attachment', [MessagesController::class, 'deleteAttachment'])->name('message.delete.attachment');

        Route::get('messages/{id}', [MessagesController::class, 'show'])->name('messages.show')->where('id', '\d+');
        Route::post('messages/{id}', [MessagesController::class, 'store'])->name('messages.update')->where('id', '\d+');
        Route::get('messages/create/{id}', [MessagesController::class, 'create'])->name('messages.create.student')->where('id', '\d+');
        Route::get('messages/attachments/{id}', [MessagesController::class, 'downloadAttachment'])->name('messages.attachment')->where('id', '\d+');

        Route::get('schools/{id}', [MessagesController::class, 'showSchoolMessages'])->name('messages.schools')->where('id', '\d+');
        Route::get('messages-type/{id}', [MessagesController::class, 'showMessageTypesMessages'])->name('messages.message-types')->where('id', '\d+');
        Route::get('students/{id}', [MessagesController::class, 'showStudentMessages'])->name('messages.student')->where('id', '\d+');

        Route::get('schools/{school_id}/messages-type/{messsage_type_id}', [MessagesController::class, 'showSchoolMessageTypeMessages'])->name('messages.school.message-type');
        Route::get('messages-type/{messsage_type_id}/schools/{school_id}', [MessagesController::class, 'showMessageTypeSchoolMessages'])->name('messages.message-type.school');

        Route::get('messages-type/{messsage_type_id}/students/{student_id}', [MessagesController::class, 'showMessageTypeStudentMessages'])->name('messages.message-type.student');
        Route::get('students/{student_id}/messages-type/{messsage_type_id}', [MessagesController::class, 'showStudentMessageTypeMessages'])->name('messages.student.message-type');
        Route::get('schools/{school_id}/students/{student_id}', [MessagesController::class, 'showSchoolStudentMessages'])->name('message.school.student');
        Route::get('students/{student_id}/schools/{school_id}', [MessagesController::class, 'showStudentSchoolMessages'])->name('message.student.school');

        Route::get('schools', [SchoolsController::class, 'index'])->name('schools');
        Route::get('schools/create', [SchoolsController::class, 'create'])->name('schools.create');
        Route::post('schools/create', [SchoolsController::class, 'store'])->name('schools.create');
        Route::get('schools/edit/{id}', [SchoolsController::class, 'show'])->name('schools.show')->where('id', '\d+');
        Route::post('schools/edit/{id}', [SchoolsController::class, 'store'])->name('schools.update')->where('id', '\d+');

        Route::get('email', [MailController::class, 'create'])->name('emails.create');
        Route::post('email', [MailController::class, 'send'])->name('emails.send');
        Route::post('email/student', [MailController::class, 'create'])->name('emails.create');

        Route::post('export-csv', [MessagesController::class, 'exportCSV'])->name('export-csv');

        Route::get('message-types', [MessageTypesController::class, 'index'])->name('message-types');
        Route::get('message-types/create', [MessageTypesController::class, 'create'])->name('message-types.create');
        Route::post('message-types/create', [MessageTypesController::class, 'store'])->name('message-types.create');
        Route::get('message-types/edit/{id}', [MessageTypesController::class, 'show'])->name('message-types.show')->where('id', '\d+');
        Route::post('message-types/edit/{id}', [MessageTypesController::class, 'store'])->name('message-types.update')->where('id', '\d+');

        Route::get('users', [UsersController::class, 'index'])->name('users');
        Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
        Route::post('users/create', [UsersController::class, 'store'])->name('users.create');
        Route::get('users/{id}', [UsersController::class, 'show'])->name('users.show')->where('id', '\d+');
        Route::post('users/{id}', [UsersController::class, 'store'])->name('users.update')->where('id', '\d+');
    });
});
