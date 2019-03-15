@section('title')
   - Teams
@endsection
@section('content')

    <div class="container">

        <div class="card card-default">
            <div class="card-header">
                Pending Invites
            </div>
            <div class="card-body">

                <table class="table">
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Team</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invites as $invites)
                        <tr>
                            <td>{{$invite->user->getFullName() }}</td>
                            <td>{{$invite->team->name}}</td>
                            <td class="text-right">

                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"  data-toggle="dropdown">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu">
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