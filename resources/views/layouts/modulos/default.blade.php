@extends('layouts.modulos.base')

@section('modulo-content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-10">
                    <span class="h2 m-0">@yield('title')</span> <span>@yield('subtitle')</span>
                </div><!-- /.col -->
                <div class="col-sm-2">
                    <div class="d-flex justify-content-end gap-2">
                        @yield('actionButton')
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <div class="app-content">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>
@endsection
