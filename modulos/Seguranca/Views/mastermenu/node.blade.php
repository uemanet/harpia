@if($node->hasChildren())
    <li class="treeview @if(MasterMenu::checkLeafIsActive($node))active @endif">
        <a href="#">
            <i class="{{$node->getData()->mit_icone}}"></i> <span>{{$node->getData()->mit_nome}}</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @foreach($node->getChilds() as $leaf)
                @include('Seguranca::mastermenu.node', ['node' => $leaf])
            @endforeach
        </ul>
    </li>
@else
    <li @if(MasterMenu::checkLeafIsActive($node))class="active" @endif>
        <a href="{{route($node->getData()->mit_rota)}}">
            <i class="{{$node->getData()->mit_icone}}"></i>@if($node->getData()->mit_item_pai) {{$node->getData()->mit_nome}} @else <span>{{$node->getData()->mit_nome}}</span> @endif
        </a>
    </li>
@endif