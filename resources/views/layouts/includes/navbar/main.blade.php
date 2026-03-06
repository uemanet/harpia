<nav class="app-header navbar navbar-expand navbar-harpia">
    <div class="container-fluid">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            @if($noaside)
                <li class="nav-item">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('img/harpia/harpia_full_white.png') }}" alt="Harpia" class="brand-image m-1" style="max-height: 40px">
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
{{--                    <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button">--}}
                        <i class="fas fa-bars text-white"></i>
                    </a>
                </li>
            @endif
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            @include('layouts.includes.navbar.header_rightmenu')
        </ul>
    </div>
</nav>