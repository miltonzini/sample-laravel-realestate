@section('title', 'Propiedades')
@section('body-id', 'properties-page')
@section('body-class', 'list-page')
<x-PublicLayout>

@include('partials.public.navbar')

<main class="bg-soft-b">
    
    @include('partials.public.masthead-sm', ['pageTitle' => 'Propiedades'])
    

    @if ($properties->isNotEmpty())
    <section class="section-search py8 bg-soft-b">
        <div class="container-wide px2">
            <div class="row grid">
                <div class="block block-col-2-16 search-wrapper">
                    <div class="search-bar">
                        <form id="search-form" action="{{ route('filterProperties') }}" method="get">
                            <input type="hidden" name="transaction_type" value="all">
                            <input id="" class="search-query-input" type="text" name="search" placeholder="Buscar propiedades..." value="" />
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
                @if ($properties->isNotEmpty())
                    @foreach ($properties as $property)
                        <a class="card property-card" href="{{ route('propertyDetails', $property->slug) }}">
                            <div class="img-wrapper">
                                @if (!empty($property->images) && isset($property->images[0]->thumbnail_image))
                                    <img src="{{ asset('public/files/img/properties/' . $property->images[0]->thumbnail_image) }}" alt="{{ $property->images[0]->img_alt}}">
                                @else
                                    <img src="{{ asset('public/img/misc/property-placeholder.png') }}" alt="{{ $property->title }}">
                                @endif
                            </div>
                            <div class="content-wrapper">
                                <p class="first-line">
                                    {{ ucfirst($property->transaction_type ?? '') }} 
                                    @if(!empty($property->transaction_type) && !empty($property->property_type)) | @endif 
                                    {{ ucfirst($property->property_type ?? '') }}
                                </p>
                                <p class="price">{{ $property->price ?? 'Consultar' }}</p>
                                <p class="title">{{ $property->short_title ?? '' }}</p>
                                <p class="address">{{ $property->public_address ?? 'Dirección no disponible' }}</p>
                                <p class="location">{{ $property->short_location ?? 'Ubicación no disponible' }}</p>
                                <div class="card-footer">
                                    @if (!empty($property->total_area))
                                        <p class="total-area">{{ $property->total_area }} m²</p>
                                    @endif
                                    @if (!empty($property->rooms))
                                        @if (!empty($property->total_area)) <span class="divider"> | </span> @endif
                                        <p class="rooms">{{ $property->rooms }} ambientes</p>
                                    @endif
                                    @if (!empty($property->bathrooms))
                                        @if (!empty($property->total_area) || !empty($property->rooms)) <span class="divider"> | </span> @endif
                                        <p class="bathrooms">{{ $property->bathrooms }} Baños</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="disclaimer">No hay propiedades disponibles</div>
                @endif
                </div>
                <div class="block block-col-1-17">
                    <div class="pagination p-4">
                    {{ $properties->links() }}
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
            <script src="{{ asset('public/js/misc/' . $script . $assetVersionQueryString ) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-PublicLayout>