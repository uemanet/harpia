<nav class="mt-2">
    <ul
            class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            role="navigation"
            aria-label="Main navigation"
            data-accordion="false"
            id="navigation"
    >
{{--    <ul class="sidebar-menu" data-widget="tree">--}}
        @if($root->hasChildren())
            @foreach($root->getChilds() as $child)
                @include('Seguranca::mastermenu.node', ['node' => $child])
            @endforeach
        @endif
    </ul>
</nav>