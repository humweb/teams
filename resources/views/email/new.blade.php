Hey,

<br><br>

{{ $invitation->team->owner->name }} has invited you to join their team "{{$team->name}}"!
If you do not already have an account, you may click the following link to get started:

<br><br>

<a href="{{ url('register?invitation='.$invitation->token) }}">{{ url('register?invitation='.$invitation->token) }}</a>

<br><br>

@if(!is_null($notes))
    {{ $notes }}
    <br><br>
@endif

Thanks!
