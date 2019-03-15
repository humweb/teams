@section('content')

    <div class="container">

        {!! Form::open(['route'=> ['teams.post.edit', $team->id]]) !!}
        <div class="card card-default">
            <div class="card-header">
                Edit Team
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Title</label>
                    {!! Form::text('name', old("title", $team->name), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group" id="menu-link">
                    <label for="description" class="left">Description</label>
                    {!! Form::textarea('description', old("description", $team->description), ['id' => 'description', 'class' => 'form-control']) !!}
                </div>
                <div class="text-center">
                    {!! Form::submit('Save', array('class' => 'btn btn-primary')) !!}
                    <a href="{!! route('teams.get.index') !!}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

    </div>

@show