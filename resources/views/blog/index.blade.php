@section('title', 'Blog')
@section('body-id', 'blog-page')
@section('body-class', 'list-page')
<x-PublicLayout>

@include('partials.public.navbar')

<main class="bg-soft-b">
    
    @include('partials.public.masthead-sm', ['pageTitle' => 'Blog'])

        @if ($posts->isNotEmpty())
        <section class="section-search py8 bg-soft-b">
            <div class="container-wide px2">
                <div class="row grid">
                    <div class="block block-col-2-16 search-wrapper">
                        <div class="search-bar">
                            <form id="search-form" action="{{ route('filterPosts') }}" method="get">
                                {{-- <input type="hidden" name="transaction_type" value="venta"> --}}
                                <input id="" class="search-query-input" type="text" name="search" placeholder="Buscar posts..." value="" />
                                <button id="" class="search-query-submit" type="submit" name="">
                                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                        <path fill="#666666" d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

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