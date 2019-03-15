@section('content')

    <div class="container">

        {!! Form::open(['route'=> 'teams.post.invite.send']) !!}
        <div class="card card-default">
            <div class="card-header">
                Team Invite
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Email</label>
                    {!! Form::email('email', old("email"), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Team</label>
                    {!! Form::select('team_id', $teams, null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Message</label>
                    {!! Form::textarea('message', '', ['class' => 'form-control']) !!}
                </div>
                <div class="text-center">
                    {!! Form::submit('Invite', array('class' => 'btn btn-primary')) !!}
                    <a href="{!! route('teams.get.index') !!}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

    </div>

@show