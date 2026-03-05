@extends('layouts.modulos.base')

@section('modulo-content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
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
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
    </div><!-- /.content-wrapper -->
@endsection
