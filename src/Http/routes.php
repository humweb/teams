<?php

Route::middleware(['web','auth'])->namespace('Humweb\Teams\Http\Controllers')->prefix('teams')->name('teams.')->group(function () {
    Route::get('invite/{code}', 'InviteController@getInvite')->name('get.invite');
    //Route::post('invite/{teamId}', 'InviteController@postSend')->name('post.invite');
    Route::any('invite/{inviteId}/delete', 'InviteController@postDeleteByOwner')->name('get.invite.delete');

    Route::get('user/invites', 'UserInviteController@getInvites')->name('get.invites');
    Route::get('user/invites/accept/{inviteId}', 'UserInviteController@getAccept')->name('get.invite.accept');
    Route::any('user/invites/decline/{inviteId}', 'UserInviteController@getDecline')->name('get.invite.user.delete');

    Route::get('/', 'TeamsController@getIndex')->name('get.index');
    Route::get('create', 'TeamsController@getCreate')->name('get.create');
    Route::post('create', 'TeamsController@postCreate')->name('post.create');

    Route::get('edit/{teamId}', 'TeamsController@getEdit')->name('get.edit');
    Route::post('edit/{teamId}', 'TeamsController@postEdit')->name('post.edit');

    Route::get('invites/send/{teamId?}', 'TeamsController@getInvite')->name('get.invite.send');
    Route::post('invites/send', 'InviteController@postSend')->name('post.invite.send');

    Route::get('invites/{teamId?}', 'TeamsController@getPendingInvites')->name('get.invites.pending');


});
