@extends('layouts.dashboard')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Posts</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">List of Application Posts</h2>
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
                                <th>Post Media</th>
                                <th>Post Caption</th>
                                <th>Author</th>
                                <th>Created At</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td>
                                        @foreach($post->media as $media)
                                            <figure class="avatar mr-2 avatar-lg">
                                                <img src="{{ $media->getFullUrl() }}" alt="...">
                                            </figure>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $post->caption }}
                                    </td>
                                    <td>
                                        <figure class="avatar mr-2 avatar-sm">
                                            <img src="{{ $post->author->avatar }}" alt="...">
                                        </figure>
                                        {{ $post->author->username }}
                                    </td>
                                    <td>
                                        {{ $post->created_at }} <br>
                                        <strong>{{ $post->created_at->diffForHumans() }}</strong>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Actions">
                                            <form action="{{ route('dashboard.posts.delete', $post) }}" method="POST">
                                                @csrf @method('delete')
                                                <button type="submit" title="Delete Post" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                    Delete Post
                                                </button>
                                            </form>
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
                        {{ $posts->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection
