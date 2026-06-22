@if($node->hasChildren())
    <li class="nav-item @if(MasterMenu::checkLeafIsActive($node)) menu-open @endif"> {{--  --}}
        <a href="#" class="nav-link @if(MasterMenu::checkLeafIsActive($node)) active @endif">
            <i class="nav-icon {{$node->getData()->mit_icone}}"></i>
            <p>
                {{$node->getData()->mit_nome}}
                <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @foreach($node->getChilds() as $leaf)
                @include('Seguranca::mastermenu.node', ['node' => $leaf])
            @endforeach
        </ul>
    </li>
@else
    <li class="nav-item">
        <a href="{{route($node->getData()->mit_rota)}}" class="nav-link @if(MasterMenu::checkLeafIsActive($node))active @endif">
            <i class="nav-icon {{$node->getData()->mit_icone}}"></i>
            @if($node->getData()->mit_item_pai) {{$node->getData()->mit_nome}} @else
                <p>
                    {{$node->getData()->mit_nome}}
                    <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
            @endif
        </a>
    </li>
@endif


{{--<li class="nav-item">--}}
{{--    <a href="#" class="nav-link">--}}
{{--        <i class="nav-icon bi bi-filetype-js"></i>--}}
{{--        <p>--}}
{{--            Javascript--}}
{{--            <i class="nav-arrow bi bi-chevron-right"></i>--}}
{{--        </p>--}}
{{--    </a>--}}
{{--    <ul class="nav nav-treeview">--}}
{{--        <li class="nav-item">--}}
{{--            <a href="./docs/javascript/treeview.html" class="nav-link">--}}
{{--                <i class="nav-icon bi bi-circle"></i>--}}
{{--                <p>Treeview</p>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--    </ul>--}}
{{--</li>--}}