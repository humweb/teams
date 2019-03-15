Hey {{ $user->name }},

<br><br>

{{ $invitation->team->owner->name }} has invited you to join their team "{{$team->name}}"!

<br><br>

Since you already have an account, you may accept the invitation from your
account settings or by clicking the link below.

<br><br>

@if($message)
    {{ $message }}
    <br><br>
@endif

Thanks!
