
<aside class="main-sidebar sidebar-light-primary elevation-4">
   <!-- Brand Logo -->
   <div class="brand-link bg-harpia-primary">
      <a href="{{ url('/') }}">
         <img src="{{ asset('img/harpia/harpia_white.png') }}" alt="harpia" class="brand-image " style="opacity: .8" />
         <span class="brand-text font-weight-bold text-white">Harpia</span>
      </a>
   </div>

   <!-- Sidebar -->
   <div class="sidebar">
      <!-- Sidebar Menu -->
      {!! MasterMenu::render() !!}
      <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
</aside>