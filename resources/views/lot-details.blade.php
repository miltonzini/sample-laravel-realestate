@section('title', $lot->title)
@section('body-id', 'lot-page')
@section('body-class', 'lot-details')
<x-PublicLayout>

@include('partials.public.navbar', ['class' => 'negative'])


<main class="bg-soft-b">
    

    <section class="lot py20 px4 bg-soft-b regular-section" id="lot-header-and-gallery">
        <div class="container-wide px2">
            <div class="row grid">
                
                <div class="block block-col-1-17 lot-details-header">
                    @if ($lot->status != 'activo')
                        @switch($lot->status)
                            @case('pausado')
                                <div class="lot-status-disclaimer paused">
                                    Este anuncio se encuentra pausado
                                </div>
                                @break
                            @case('vendido')
                                <div class="lot-status-disclaimer sold">
                                    Este lote/terreno ya fue vendida
                                </div>
                                @break
                             @case('reservado')
                                <div class="lot-status-disclaimer reserved">
                                    Este lote/terreno está reservada
                                </div>
                                @break
                            @default
                                <div class="lot-status-disclaimer">
                                    Este anuncio no está disponible
                                </div>
                        @endswitch
                    @endif
                    <h3>{{ $lot->title }}</h3>
                    <p>
                        @isset($lot->lot_type)
                            {{ ucfirst($lot->lot_type) }}
                        @endisset
                        @isset($lot->total_area)
                            {{ $lot->total_area }} m² | 
                        @endisset
                        @isset($lot->rooms)
                            {{ $lot->rooms }} ambientes
                        @endisset
                    </p>
                </div>
            </div>
        </div>
        
        
        <div class="container-wide px2">
            @if($lot->images->count() >= 5)
                <div class="row lot-gallery">
                    @foreach($lot->images->take(5) as $key => $image)
                        <a class="item {{ $key === 0 ? 'first-item' : 'sm-item' }} GalleryImgLink" 
                            href="{{ asset('public/files/img/lots/' . $image->image) }}" 
                            data-lightbox="media-gallery" 
                            data-title="">
                            <img src="{{ asset('public/files/img/lots/' . $image->medium_image) }}" alt="{{ $image->img_alt }}">
                        </a>
                    @endforeach
                    
                    <!-- Additional images (only visible in slider) -->
                    @foreach($lot->images->skip(5) as $image)
                        <a class="item GalleryImgLink displayNone" 
                            href="{{ asset('public/files/img/lots/' . $image->image) }}" 
                            data-lightbox="media-gallery" 
                            data-title="">
                            <img src="{{ asset('public/files/img/lots/' . $image->medium_image) }}" alt="{{ $image->img_alt }}">
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="regular-section px4 main-content">
        <div class="container-wide px2">
            <div class="row content-blocks-wrapper">
                {{-- First Block: Details --}}
                <div class="lot-summary-block block">
                    <div class="block-header">
                        <h4>Detalles</h4>
                    </div>
                    <ul>
                        @isset($lot->transaction_type)
                            <li><strong>Tipo de Operación: </strong>{{ ucfirst($lot->transaction_type) }}</li>
                        @endisset
                        @isset($lot->rooms)
                            <li><strong>Ambientes: </strong>{{ $lot->rooms }}</li>
                        @endisset
                        @isset($lot->bathrooms)
                            <li><strong>Baños: </strong>{{ $lot->bathrooms }} baños</li>
                        @endisset

                        @isset($lot->years_age) {{-- calcular retando year_built al actual desde el controlador sumando years_age al objeto--}}
                            <li><strong>Antigüedad: </strong>{{ $lot->years_age }}</li>
                        @endisset
                        @isset($lot->covered_area)
                            <li><strong>Superficie Cubierta: </strong>{{ $lot->covered_area }}  m²</li>
                        @endisset
                        @isset($lot->total_area)
                            <li><strong>Superficie Total: </strong>{{ $lot->total_area }}  m²</li>
                        @endisset
                        @isset($lot->orientation)
                            <li><strong>Orientación: </strong>{{ strtoupper($lot->orientation) }}</li>
                        @endisset
                        {{-- @isset($lot->xxxxxxxxxxxxx)
                            <li><strong>Cocheras: </strong>{{ $lot->xxxxxxxxxx }}</li>
                        @endisset --}}
                        @isset($lot->price)
                            <li><strong>Valor: </strong>{{ $lot->price }}</li>
                        @endisset

                    </ul>
                </div>
    
                {{-- Second Block: video or map --}}
                @if($hasVideoBlock)
                    <div class="lot-video-block block">
                        <div class="block-header">
                            <h4>Video</h4>
                        </div>
                        <div class="video-iframe-wrapper">
                            {!! $lot->video !!}
                        </div>
                    </div>
                @endif
                @if(!$hasVideoBlock && $hasMap)
                    <div class="lot-map-block block">
                        <div class="block-header">
                            <h4>Ubicación</h4>
                            <p>{{ $lot->public_address }}</p>

                        </div>
                        <div class="map-iframe-wrapper">
                            {!! $lot->real_address !!}
                        </div>
                    </div>
                @endif

                {{-- Third Block: Description --}}
                <div class="lot-description-block block {{ (!$hasVideoBlock && !$hasMap) ? 'pr0' : '' }}">
                
                    <div class="block-header">
                        <h4>Descripción</h4> 
                    </div>
                    <p>{{ $lot->description }}</p>
                </div>
    
                {{-- Fourth Block: Map (if exists) --}}
                @if($hasVideoBlock && $hasMap)
                    <div class="lot-map-block block">
                        <div class="block-header">
                            <h4>Ubicación</h4>
                            <p>{{ $lot->public_address }}</p>
                        </div>                   
                        <div class="map-iframe-wrapper">
                            {!! $lot->real_address !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </section>

    <section class="centered-buttons text-center">
        <div class="container-wide px2">
            <div class="row buttons-wrapper">
                <a href="javascript:void(0)" class="btn btn-outlined-accent" id="lotDetailsFormModalAnchor">Quiero conocer este lote/terreno</a>
                <a href="https://{{ $lot->external_url }}" class="btn btn-outlined-accent" target="_blank">Acceder al aviso completo</a>
            </div>
        </div>
    </section>

    <div id="lotFormModal" class="modal">
        <div class="modal-content">
            <span class="closeLotDetailsFormModalSpan close">&times;</span>
            <div class="wrapper">
                <h3>Consultar sobre este lote/terreno</h3>
                <p>{{ $lot-> title }}</p>
                <form action="{{ route('lot-details.form', ['id' => $lot->id]) }}" method="post" id="lot-details-form">
                    @csrf
                    <input type="text" name="website" id="form-hidden-field">
                    <input type="hidden" name="lot-id" value="{{ $lot->id }}">
                    <input type="hidden" name="lot-title" value="{{ $lot->title }}">
                    <input type="hidden" name="lot-url" value="{{ url()->full() }}">
                    <input type="text" name="full-name" id="form-full-name" placeholder="Nombre y Apellido">
                    <input type="tel" name="telephone" id="form-tel" placeholder="Teléfono">
                    <input type="text" name="email" id="form-email" placeholder="email">
                    <textarea name="message" id="lot-form-message" minlength="20" maxlength="2000" required placeholder="Mensaje.">¡Hola! Quiero conocer este lote/terreno.</textarea>
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    <input type="submit" value="Consultar" class="btn btn-accent" id="lot-details-contact-button">
                </form>
            </div>
        </div>
    </div>

    {{-- @include('partials.public.steps') --}}

    @include('partials.public.social-sm')

    @include('partials.public.contact')

    @include('partials.public.footer')

    @include('partials.public.post-footer')


</main>
@push('scripts')
    <!-- Begin Page level JS files -->
    <script src="{{ asset('public/vendor/lightbox2/dist/js/lightbox-plus-jquery.min.js') }}"></script>
    <script>
      lightbox.option({
        'albumLabel': '%1 / %2',
        'resizeDuration': 100,
        'imageFadeDuration': 250,
        'fadeDuration': 250,
        'wrapAround': true,
        'positionFromTop': 80,
        'alwaysShowNavOnTouchDevices': true
      })
    </script>
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