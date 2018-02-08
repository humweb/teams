<?php

Route::middleware(['auth'])->namespace('Humweb\Teams\Http\Controllers')->prefix('teams')->name('teams.')->group(function () {
    Route::get('invite/{code}', 'InviteController@getInvite')->name('get.invite');
    Route::post('invite/{teamId}', 'InviteController@postSend')->name('post.invite');
    Route::any('invite/{inviteId}/delete', 'InviteController@postDeleteByOwner')->name('get.invite.delete');

    Route::get('user/invites', 'UserInviteController@getInvites')->name('get.invites');
    Route::get('user/invites/accept/{inviteId}', 'UserInviteController@getAccept')->name('get.invite.accept');
    Route::any('user/invites/decline/{inviteId}', 'UserInviteController@getDecline')->name('get.invite.user.delete');
});
