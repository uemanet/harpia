{{--<head>--}}
{{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">--}}
{{--</head>--}}

<style>
    .card2 {
        background-color: white;
        border-radius: 1.5rem;
        padding: 20px;
        margin-bottom: 10px;
        gap: 12px;
        display: flex;
        flex-direction: column;
    }

    .header {
        font-size: 2.5rem;
        font-family: Arial;
        line-height: 1.1;
        font-weight: 600;
    }
</style>

@section('content')
    <section>
        @foreach($noticias as $noticia)
            <div class="card2"  style="text-align: justify">
                <div class="header">
                    {{$noticia->ntc_titulo}}
                </div>
                <div style="text-align: justify">
                    {{$noticia->ntc_descricao}}
                </div>
                <div>
                    @php
                        date_default_timezone_set('America/Sao_Paulo');
                        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
                        setlocale(LC_TIME, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
                    @endphp
                    {{$noticia->created_at->formatLocalized('%d de %B de %Y')}}
                </div>
            </div>
        @endforeach
    </section>
    <body>
    <div class="text-center">{!! $noticias->links('pagination::bootstrap-4') !!}</div>
    </body>
@endsection

@section('scripts')
@endsection
