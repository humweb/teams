<?php

Route::middleware(['auth'])->namespace('Teams\Http\Controllers')->prefix('teams')->name('teams.')->group(function () {
    Route::get('invite/{code}', 'InviteController@getInvite')->name('get.invite');
    Route::post('invite/{teamId}', 'InviteController@postSend')->name('post.invite');
    Route::get('invite/{inviteId}/delete', 'InviteController@postDeleteByOwner')->name('get.invite.delete');

    Route::get('invites', 'UserInviteController@getInvites')->name('get.invites');
    Route::get('invite/accept/{inviteId}', 'UserInviteController@getAccept')->name('get.invite.accept');
    Route::get('user/invite/{inviteId}/delete', 'UserInviteController@getDecline')->name('get.invite.user.delete');
});
