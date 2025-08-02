@section('title', $post->title)
@section('body-id', 'post-page')
@section('body-class', 'post-page')
<x-PublicLayout>

@include('partials.public.navbar')

<main class="bg-soft-b">
    
    @include('partials.public.masthead-sm', ['pageTitle' => 'Blog' ])
    

        <section class="cards py15 bg-soft-b">
            <div class="container-wide px2">
                <div class="row grid">
                    <div class="block post-main-block">
                        <div class="post-header">
                            <h1>{{ $post->title }}</h1>
                            <p class="blog-info">Publicado el {{ $post->created_at->format('d/m/Y') }} por {{ $post->author_full_name }}</p>
                        </div>
                        
                        <img class="post-image" src="{{ asset('public/files/img/posts') . '/' . $post->images[0]->image }}" alt="{{ $post->images[0]->img_alt }}">
                        
                        <div class="post-body">
                            <div>{!! $post->body ?? '<p>Contenido no disponible</p>' !!}</div>

                            @if (!empty($post->button_url))
                                <div class="btn-wrapper">
                                    <a href="{{ $post->button_url }}" class="btn btn-outlined-accent">{{ $post->button_text }}</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="block recent-posts-block">
                    <h3>Posts recientes</h3>
                        @if (!empty($recentPosts) && count($recentPosts) > 0 )
                            @foreach ($recentPosts as $post)                                
                            <a href="{{ route('blog.post', $post->slug) }}" class="recent-post-wrapper card">
                                    <h4 class="title">{{ mb_strimwidth($post->title, 0, 120, '...') }}</h4>
                                    <p>{{ mb_strimwidth($post->short_description, 0, 120, '...') }}</p>
                            </a>
                            @endforeach
                        @else
                            <p>No hay posts para mostrar</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

    {{-- @include('partials.public.steps') --}}

    {{-- @include('partials.public.social-sm') --}}

    @include('partials.public.contact')

    @include('partials.public.footer')

    @include('partials.public.post-footer')


</main>
@push('scripts')
    <!-- Begin Page level JS files -->
    <!-- End Page level JS files -->

    
    <!-- Begin Controller level JS files -->
    @if (isset($scripts) && !empty($scripts))
        @foreach ($scripts as $script)
            <script src="{{ asset('public/js/misc/' . $script . $assetVersionQueryString ) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-PublicLayout>