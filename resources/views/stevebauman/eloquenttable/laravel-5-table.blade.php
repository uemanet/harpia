{{-- Adicionadas as classes: table-hover, align-middle e mb-0 --}}
<table @if($collection->eloquentTableAttributes) {!! $collection->eloquentTableAttributes !!} @else class="table table-striped table-hover align-middle mb-0" @endif>
    <thead>
    <tr>
        @foreach($collection->eloquentTableColumns as $key => $name)
            <th {!! $collection->getHiddenColumnAttributes($key) !!}>
                @if(in_array($key, $collection->eloquentTableSort))
                    {!! sortableUrlLink($name, array('field' => $key, 'sort'=>'asc')) !!}
                @elseif(array_key_exists($key, $collection->eloquentTableSort))
                    {!! sortableUrlLink($name, array('field' => $collection->eloquentTableSort[$key], 'sort'=>'asc')) !!}
                @else
                    {{ ucfirst($name) }}
                @endif
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($collection as $record)
        <tr {!! $collection->getRowAttributes($record) !!}>
            @foreach($collection->eloquentTableColumns as $key => $name)
                <td {!! $collection->getCellAttributes($key,$record) !!}>
                    @if(array_key_exists($key, $collection->eloquentTableMeans))
                        @if(array_key_exists($key, $collection->eloquentTableModifications))
                            {!! call_user_func_array($collection->eloquentTableModifications[$key], array( $record->getRelationshipObject($collection->eloquentTableMeans[$key]), $record )) !!}
                        @else
                            {!! $record->getRelationshipProperty($collection->eloquentTableMeans[$key]) !!}
                        @endif
                    @else
                        @if(array_key_exists($key, $collection->eloquentTableModifications))
                            {!! call_user_func_array($collection->eloquentTableModifications[$key], array($record)) !!}
                        @else
                            @if($record->{$key})
                                {!! $record->{$key}  !!}
                            @else
                                {!! $record->{$name}  !!}
                            @endif
                        @endif
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>