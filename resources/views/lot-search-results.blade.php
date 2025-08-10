@section('title', 'Resultados de la búsqueda')
@section('body-id', 'lot-search-results-page')
@section('body-class', 'list-page')
<x-PublicLayout>

@include('partials.public.navbar')

<main class="bg-soft-b">
    
    @include('partials.public.masthead-sm', ['pageTitle' => 'Lotes/Terrenos: Resultados de la búsqueda'])
    

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
                <div class="block block-col-1-17 cards-wrapper grid-4">
                    @if ($lots->isNotEmpty())
                        @foreach ($lots as $lot)
                            <a class="card property-card" href="{{ route('lotDetails', $lot->slug) }}">
                                <div class="img-wrapper">
                                    @if (!empty($lot->images) && isset($lot->images[0]->thumbnail_image))
                                        <img src="{{ asset('public/files/img/lots/' . $lot->images[0]->thumbnail_image) }}" alt="{{ $lot->images[0]->img_alt}}">
                                    @else
                                        <img src="{{ asset('public/img/misc/property-placeholder.png') }}" alt="{{ $lot->title }}">
                                    @endif
                                </div>
                                <div class="content-wrapper">
                                    <p class="first-line">{{ ucfirst($lot->short_title ?? '') }}</p>
                                    <p class="price">{{ $lot->price ?? 'Consultar' }}</p>
                                    <p class="address">{{ $lot->public_address ?? 'Dirección no disponible' }}</p>
                                    <p class="location">{{ $lot->short_location ?? 'Ubicación no disponible' }}</p>
                                    <div class="card-footer">
                                        <p class="estimated-delivery-date">
                                            <span>Entrega estimada: </span>
                                            <span><strong>{{ $lot->estimated_delivery_date ?? 'Consultar' }}</strong></span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="disclaimer">No hay lotes/terrenos disponibles</div>
                    @endif
                </div>
                <div class="block block-col-1-17">
                    <div class="pagination p-4">
                    {{ $lots->links() }}
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