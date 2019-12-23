@extends('layouts.dashboard')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Pages</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">List of Application Pages</h2>
            <div class="card">
                <div class="card-header d-flex">
                    <div class="card-header-action ml-auto">
                        <a href="{{ route('dashboard.pages.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                            Add page
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-md">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Alias</th>
                                <th>Description</th>
                                <th>Updated At</th>
                                <th>Created At</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pages as $page)
                                <tr>
                                    <td>{{ $page->id }}</td>
                                    <td>
                                        {{ $page->title }}
                                    </td>
                                    <td>
                                        {{ $page->alias }}
                                    </td>
                                    <td>
                                        {{ $page->description }}
                                    </td>
                                    <td>
                                        {{ $page->updated_at }}
                                    </td>
                                    <td>
                                        {{ $page->created_at }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Actions">
                                            <a href="{{ route('dashboard.pages.edit', $page) }}" title="Edit Page" class="btn btn-sm btn-primary">
                                                <i class="fa fa-edit"></i>
                                                Edit Page
                                            </a>
                                            <form action="{{ route('dashboard.pages.destroy', $page) }}" method="POST">
                                                @csrf @method('delete')
                                                <button type="submit" title="Delete Page" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
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
                        {{ $pages->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection
