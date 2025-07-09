@section('title', 'Emprendimientos')
@section('body-id', 'developments-page')
@section('body-class', 'list-page')
<x-PublicLayout>

@include('partials.public.navbar')

<main class="bg-soft-b">
    
    @include('partials.public.masthead-sm', ['pageTitle' => 'Emprendimientos'])
    

    @if ($developments->isNotEmpty())
    <section class="section-search py8 bg-soft-b">
        <div class="container-wide px2">
            <div class="row grid">
                <div class="block block-col-2-16 search-wrapper">
                    <div class="search-bar">
                        <form id="search-form" action="{{ route('filterDevelopments') }}" method="get">
                            <input id="" class="search-query-input" type="text" name="search" placeholder="Buscar emprendimientos..." value="" />
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
                <div class="block block-col-1-17 cards-wrapper grid-4">
                    @if ($developments->isNotEmpty())
                        @foreach ($developments as $development)
                            <a class="card property-card" href="{{ route('developmentDetails', $development->slug) }}">
                                <div class="img-wrapper">
                                    @if (!empty($development->images) && isset($development->images[0]->thumbnail_image))
                                        <img src="{{ asset('public/files/img/developments/' . $development->images[0]->thumbnail_image) }}" alt="{{ $development->images[0]->img_alt}}">
                                    @else
                                        <img src="{{ asset('public/img/misc/property-placeholder.png') }}" alt="{{ $development->title }}">
                                    @endif
                                </div>
                                <div class="content-wrapper">
                                    <p class="first-line">{{ ucfirst($development->short_title ?? '') }}</p>
                                    <p class="price">{{ $development->price ?? 'Consultar' }}</p>
                                    <p class="address">{{ $development->public_address ?? 'Dirección no disponible' }}</p>
                                    <p class="location">{{ $development->short_location ?? 'Ubicación no disponible' }}</p>
                                    <div class="card-footer">
                                        <p class="estimated-delivery-date">
                                            <span>Entrega estimada: </span>
                                            <span><strong>{{ $development->estimated_delivery_date ?? 'Consultar' }}</strong></span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="disclaimer">No hay emprendimienos disponibles</div>
                    @endif
                </div>
                <div class="block block-col-1-17">
                    <div class="pagination p-4">
                    {{ $developments->links() }}
                    <div>
                </div>
            </div>
        </div>
    </section>

    {{-- @include('partials.public.steps') --}}

    @include('partials.public.social-sm')

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
            <script src="{{ asset('public/js/' . $script . $assetVersionQueryString ) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-PublicLayout>