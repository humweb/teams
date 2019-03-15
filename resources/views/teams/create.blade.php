@section('content')

    <div class="container">

        {!! Form::open(['route'=> 'teams.post.create']) !!}
        <div class="card card-default">
            <div class="card-header">
                Create Team
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Title</label>
                    {!! Form::text('name', old("name"), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group" id="menu-link">
                    <label for="description" class="left">Description</label>
                    {!! Form::textarea('description', old("description"), ['id' => 'description', 'class' => 'form-control']) !!}
                </div>
                {!! Form::submit('Save', array('class' => 'btn btn-primary')) !!}
            </div>
        </div>

    {!! Form::close() !!}

    </div>

@show