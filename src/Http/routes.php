<?php

Route::middleware(['auth'])->prefix('teams')->name('teams.')->group(function () {
    Route::get('teams/invitations', 'InvitationController@getInvitationsForCurrentUser')->name('get.invitations');
    Route::get('teams/invitation/{code}', 'InvitationController@getInvitation')->name('get.invitation');
    Route::post('teams/invite/{teamId}', 'InvitationController@postSendInvitation')->name('post.invitation');
    Route::get('teams/invite/accept/{invitationId}', 'InvitationController@getAcceptInvitation')
         ->name('get.invitation.accept');
    Route::get('teams/invite/delete/{invitationId}', 'InvitationController@getDeleteInvitation')
         ->name('get.invitation.delete');
    Route::get('teams/invite/delete/{invitationId}/user', 'InvitationController@getDeleteInvitationForUser')
         ->name('get.invitation.user.delete');
});
