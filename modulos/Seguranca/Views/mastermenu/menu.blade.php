<ul class="sidebar-menu" data-widget="tree">
    @if($root->hasChildren())
        @foreach($root->getChilds() as $child)
            @include('Seguranca::mastermenu.node', ['node' => $child])
        @endforeach
    @endif
</ul>