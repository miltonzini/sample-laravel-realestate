@section('title', 'Resultados de la Búsqueda')
@section('body-id', 'blog-search-results-page')
@section('body-class', 'list-page')
<x-PublicLayout>

@include('partials.public.navbar')

<main class="bg-soft-b">
    
    @include('partials.public.masthead-sm', ['pageTitle' => 'Blog: Resultados de la Búsqueda'])

        <section class="actions-section py8 bg-soft-b">
            <div class="container px2">
                <div class="row grid">
                    <div class="block block-col-1-17 centerContent">
                        <a class="btn btn-sm btn-outlined-primary" href="{{ url()->previous() }}">Volver</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="cards py1 bg-soft-b">
            <div class="container-wide px2">
                <div class="row grid">
                    <div class="block block-col-1-17 cards-wrapper">
                    @if ($posts->isNotEmpty())
                        @foreach ($posts as $post)
                            <a class="card post-card" href="{{ route('blog.post', $post->slug) }}">
                                <div class="img-wrapper">
                                    @if (!empty($post->images))
                                    <img src="{{ asset('public/files/img/posts/' .  $post->images->first()->thumbnail_image ) }}" alt="Lorem">
                                    @else
                                    <img src="{{ asset('public/img/misc/property-placeholder.png') }}" alt="Lorem">
                                    @endif
                                </div>
                                <div class="content-wrapper">
                                    {{-- <p class="category">Lorem</p> --}}
                                    <p class="title">{{ mb_strimwidth($post->title, 0, 60, '...') }}</p>
                                    <p class="description">{{ mb_strimwidth($post->short_description, 0, 180, '...') }}</p>
                                    
                                    <div class="card-footer">
                                        <p class="created-at">{{ $post->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="disclaimer">No hay posts disponibles</div>
                    @endif
                    </div>
                    <div class="block block-col-1-17">
                    <div class="pagination p-4">
                    {{ $posts->links() }}
                    <div>
                </div>
                </div>
            </div>
        </section>

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