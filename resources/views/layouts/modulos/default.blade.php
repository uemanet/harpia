@extends('layouts.modulos.base')

@section('modulo-content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-10">
                    <span class="h2 m-0">@yield('title')</span> <span>@yield('subtitle')</span>
                </div><!-- /.col -->
                <div class="col-sm-2">
                    <ol class="float-sm-right">
                        @yield('actionButton')
                    </ol>
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
