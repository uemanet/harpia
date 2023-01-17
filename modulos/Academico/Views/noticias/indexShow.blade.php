@extends('layouts.modulos.academico')

@section('title')
    Notícias
@stop

@section('subtitle')
    Módulo Acadêmico
@stop

@section('actionButton')
{{--    {!!ActionButton::render($actionButton)!!}--}}
@stop

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
                                            <article class="elementor-post elementor-grid-item post-8155 post type-post status-publish format-standard hentry category-geral">
                                                <div class="elementor-post__text">
                                                    <h3 class="elementor-post__title">
                                                        {{$noticia->not_titulo}}
                                                    </h3>
                                                    <div class="elementor-post__meta-data">
					                                    <span class="elementor-post-date"> {{$noticia->created_at->format('d/m/Y')}}	</span>
                                                    </div>
                                                    <div class="elementor-post__excerpt">
                                                        <p>{{$noticia->not_descricao}}</p>
                                                    </div>
                                                    <a class="elementor-post__read-more" href="{{$noticia->not_link}}" target="_blank">
                                                        Ver mais »			</a>
                                                </div>
                                            </article>
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











@endsection

@section('scripts')
    <script src="{{asset('/js/plugins/Chart.min.js')}}" type="text/javascript"></script>

    <script type="text/javascript">

    </script>
@endsection
