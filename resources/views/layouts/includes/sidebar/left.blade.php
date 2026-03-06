<aside class="app-sidebar bg-body-secondary" data-bs-theme="light">
   <div class="sidebar-brand">
      <a href="{{ url('/') }}" class="brand-link">
         <img
                 src="{{ asset('img/logo.png') }}"
                 alt="Harpia"
                 class="brand-image"
         />
      </a>
   </div>

   <div class="sidebar-wrapper">
      {!! MasterMenu::render() !!}
   </div>
</aside>