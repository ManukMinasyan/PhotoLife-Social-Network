@extends('layouts.dashboard')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Members</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">List of Application Members</h2>
            <div class="card">
                <div class="card-header">
                    <div class="card-header-form">
                        <form action="" method="get">
                            <div class="input-group">
                                <input type="text" name="query" class="form-control" placeholder="Search">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-md">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th></th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Privacy</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($members as $member)
                                <tr>
                                    <td>{{ $member->id }}</td>
                                    <td>
                                        <figure class="avatar mr-2 avatar-sm">
                                            <img src="{{ $member->avatar }}" alt="...">
                                        </figure>
                                    </td>
                                    <td>{{ $member->username }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>
                                        @if($member->isPrivate())
                                            <div class="badge badge-warning">Private</div>
                                        @else
                                            <div class="badge badge-success">Public</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Actions">
                                            <a href="#" title="Delete Member" class="btn btn-sm btn-danger">
                                                <i class="fa fa-user-times"></i>
                                                Delete Member
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">

                    <nav class="d-inline-block">
                        {{ $members->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection
