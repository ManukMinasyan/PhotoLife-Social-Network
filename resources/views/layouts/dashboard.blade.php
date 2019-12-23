@extends('layouts.dashboard-skeleton')

@section('app')
    <div class="main-wrapper">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            @include('dashboard.partials.topnav')
        </nav>
        <div class="main-sidebar sidebar-style-2">
            @include('dashboard.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>
        <footer class="main-footer">
            @include('dashboard.partials.footer')
        </footer>
    </div>
@endsection

@push('javascript')
    @if(session('status'))
        <script>
            iziToast.success({
                message: '{{ session('status') }}',
                position: 'topCenter',
                timeout: 2500,
            });
        </script>
    @endif
    @if($errors->any())
        <script>
            iziToast.warning({
                message: '{{ $errors->all()[0] }}',
                position: 'topCenter',
                timeout: 2500,
            });
        </script>
    @endif
@endpush