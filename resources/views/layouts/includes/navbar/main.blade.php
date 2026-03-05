<nav class="main-header navbar navbar-expand navbar-harpia">
    <div class="container">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            @if($noaside)
                <li class="nav-item">
                    <img src="{{ asset('img/harpia/harpia_full_white.png') }}" alt="Harpia" class="brand-image m-1">
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            @endif
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            @include('layouts.includes.navbar.header_rightmenu')
        </ul>
    </div>
</nav>