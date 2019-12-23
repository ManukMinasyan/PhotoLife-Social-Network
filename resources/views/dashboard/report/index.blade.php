@extends('layouts.dashboard')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Reports</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">List of Application Reports</h2>
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
                                <th>Reporter</th>
                                <th>Report Type</th>
                                <th>Post</th>
                                <th>Created At</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td>{{ $report->id }}</td>
                                    <td>
                                        <a href="">
                                            <figure class="avatar mr-2 avatar-lg">
                                                <img src="{{ $report->member->avatar }}" alt="...">
                                            </figure>
                                            {{ $report->member->username }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $report->type->name }}
                                    </td>
                                    <td>
                                        <figure>
                                            <img src="{{ $report->reportable->media->first()->getFullUrl() }}"
                                                 width="80" alt="...">
                                        </figure>
                                    </td>
                                    <td>
                                        {{ $report->created_at }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Actions">
                                            <a href="{{ route('dashboard.reports.mark-safe', $report) }}" title="Mark Safe"
                                               class="btn btn-sm btn-success">
                                                <i class="fa fa-smile"></i> Mark Safe
                                            </a>
                                            <a href="{{ route('dashboard.reports.delete-post', $report) }}" title="Delete Post" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                                Delete Post
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
                        {{ $reports->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection
