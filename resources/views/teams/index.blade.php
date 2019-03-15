@section('title')
   - Teams
@endsection
@section('content')

    <div class="container">

        <div class="card card-default">
            <div class="card-header">
                Your Teams
            </div>
            <div class="card-body">

                <table class="table">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($teams as $team)
                        <tr>
                            <td>{{$team->name}}</td>
                            <td>{{$team->description}}</td>
                            <td class="text-right">

                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"  data-toggle="dropdown">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="{!! route('teams.get.edit', [$team->id]) !!}" class="dropdown-item">Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="{!! route('teams.get.invite.send', [$team->id]) !!}" class="dropdown-item">Invite</a>
                                        <a href="{!! route('teams.get.invites.pending', [$team->id]) !!}" class="dropdown-item">Pending Invites</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item">Delete</a>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@show