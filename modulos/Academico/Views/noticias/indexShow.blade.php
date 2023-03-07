{{--<head>--}}
{{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">--}}
{{--</head>--}}

<style>

    .card {
        --border-radius: 1.75rem;
        --primary-color: #3987be;
        --secondary-color: #3c3852;
        /*width: 210px;*/
        font-family: "Arial";
        padding: 1rem;
        cursor: pointer;
        border-radius: var(--border-radius);
        background: #f1f1f3;
        box-shadow: 0px 8px 16px 0px rgb(0 0 0 / 3%);
        position: relative;
    }

    .card > * + * {
        margin-top: 1.1em;
    }

    .card .card__content {
        color: var(--secondary-color);
        font-size: 1.75rem;
    }

    .card .card__title {
        padding: 0;
        font-size: 2.75rem;
        font-weight: bold;
    }

    .card .card__date {
        color: #6e6b80;
        font-size: 1.5rem;
    }

    .card .card__arrow {
        position: absolute;
        background: var(--primary-color);
        padding: 0.4rem;
        border-top-left-radius: var(--border-radius);
        border-bottom-right-radius: var(--border-radius);
        bottom: 0;
        right: 0;
        transition: 0.2s;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card svg {
        transition: 0.2s;
    }

    /* hover */
    .card:hover .card__title {
        color: var(--primary-color);
        text-decoration: underline;
    }

    .card:hover .card__arrow {
        background: #111;
    }

    .card:hover .card__arrow svg {
        transform: translateX(3px);
    }
</style>

@section('content')
    <section class="elementor-section elementor-top-section elementor-element elementor-element-f8043db elementor-section-boxed elementor-section-height-default elementor-section-height-default parallax_section_no qode_elementor_container_no" data-id="f8043db" data-element_type="section">
        <div class="elementor-container elementor-column-gap-default">
            <div class="elementor-row">
                <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-3934e81" data-id="3934e81" data-element_type="column">
                    <div class="elementor-column-wrap elementor-element-populated">
                        <div class="elementor-widget-wrap">
                            <div class="elementor-element elementor-element-93c5691 elementor-grid-1 elementor-posts--thumbnail-left elementor-posts--align-left elementor-grid-tablet-2 elementor-grid-mobile-1 elementor-widget elementor-widget-posts" data-id="93c5691" data-element_type="widget" data-settings="{&quot;classic_columns&quot;:&quot;1&quot;,&quot;classic_row_gap&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:30,&quot;sizes&quot;:[]},&quot;classic_columns_tablet&quot;:&quot;2&quot;,&quot;classic_columns_mobile&quot;:&quot;1&quot;}" data-widget_type="posts.classic">
                                <div class="elementor-widget-container">
                                    <div class="elementor-posts-container elementor-posts elementor-posts--skin-classic elementor-grid elementor-has-item-ratio">
                                        @foreach($noticias as $noticia)
                                        <div class="card">
                                            <h3 class="card__title">{{$noticia->ntc_titulo}}
                                            </h3>
                                            <p class="card__content">{{$noticia->ntc_descricao}}</p>
                                            <div class="card__date">
                                                @php
                                                    date_default_timezone_set('America/Sao_Paulo');
                                                    setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
                                                    setlocale(LC_TIME, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
                                                @endphp
                                                {{$noticia->created_at->formatLocalized('%d de %B de %Y')}}
                                            </div>
                                        </div>
                                            <div>
                                                <p></p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <body>
    <div class="text-center">{!! $noticias->links('pagination::bootstrap-4') !!}</div>
    </body>
@endsection

@section('scripts')
    <script src="{{asset('/js/plugins/Chart.min.js')}}" type="text/javascript"></script>

    <script type="text/javascript">

    </script>
@endsection
