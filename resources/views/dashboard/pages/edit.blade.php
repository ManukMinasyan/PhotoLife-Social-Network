@extends('layouts.dashboard')

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>Edit page | {{ $page->title }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('dashboard.pages.index') }}">Pages</a></div>
                <div class="breadcrumb-item">Edit Page</div>
                <div class="breadcrumb-item">{{ $page->title }}</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Edit page</h2>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('dashboard.pages.update', $page) }}" method="post">
                        @csrf @method('patch')
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="title" class="form-control" value="{{ $page->title }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Alias</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="alias" class="form-control" value="{{ $page->alias }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Description</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="description" class="form-control"
                                       value="{{ $page->description }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Keywords</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" name="keywords" class="form-control" value="{{ $page->keywords }}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Content</label>
                            <div class="col-sm-12 col-md-7">
                                <textarea class="summernote" name="content">{{ $page->content  }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button class="btn btn-primary" type="submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('javascript')
    <script src="{{ asset('assets/dashboard/js/summernote-bs4.js') }}"></script>
@endpush
@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/summernote-bs4.css') }}">
@endpush
