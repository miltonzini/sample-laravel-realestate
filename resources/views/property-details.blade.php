@section('title', $property->title)
@section('body-id', 'property-page')
@section('body-class', 'property-details')
<x-PublicLayout>

@include('partials.public.navbar', ['class' => 'negative'])


<main class="bg-soft-b">
    

    <section class="property py20 px4 bg-soft-b regular-section" id="property-header-and-gallery">
        <div class="container-wide px2">
            <div class="row grid">
                
                <div class="block block-col-1-17 property-details-header">
                    @if ($property->status != 'activo')
                        @switch($property->status)
                            @case('pausado')
                                <div class="property-status-disclaimer paused">
                                    Este anuncio se encuentra pausado
                                </div>
                                @break
                            @case('vendido')
                                <div class="property-status-disclaimer sold">
                                    Esta propiedad ya fue vendida
                                </div>
                                @break
                             @case('reservado')
                                <div class="property-status-disclaimer reserved">
                                    Esta propiedad está reservada
                                </div>
                                @break
                            @default
                                <div class="property-status-disclaimer">
                                    Este anuncio no está disponible
                                </div>
                        @endswitch
                    @endif
                    <h3>{{ $property->title }}</h3>
                    <p>
                        @isset($property->property_type)
                            {{ ucfirst($property->property_type) }} | 
                        @endisset
                        @isset($property->total_area)
                            {{ $property->total_area }} m² | 
                        @endisset
                        @isset($property->rooms)
                            {{ $property->rooms }} ambientes
                        @endisset
                    </p>
                </div>
            </div>
        </div>
        
        
        <div class="container-wide px2">
            @if($property->images->count() >= 5)
                <div class="row property-gallery">
                    @foreach($property->images->take(5) as $key => $image)
                        <a class="item {{ $key === 0 ? 'first-item' : '' }} GalleryImgLink" 
                            href="{{ asset('public/files/img/properties/' . $image->image) }}" 
                            data-lightbox="media-gallery" 
                            data-title="">
                            <img src="{{ asset('public/files/img/properties/' . $image->medium_image) }}" alt="{{ $image->img_alt }}">
                        </a>
                    @endforeach
                    
                    <!-- Additional images (only visible in slider) -->
                    @foreach($property->images->skip(5) as $image)
                        <a class="item GalleryImgLink displayNone" 
                            href="{{ asset('public/files/img/properties/' . $image->image) }}" 
                            data-lightbox="media-gallery" 
                            data-title="">
                            <img src="{{ asset('public/files/img/properties/' . $image->medium_image) }}" alt="{{ $image->img_alt }}">
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
                <div class="property-summary-block block">
                    <div class="block-header">
                        <h4>Detalles</h4>
                    </div>
                    <ul>
                        @isset($property->transaction_type)
                            <li><strong>Tipo de Operación: </strong>{{ ucfirst($property->transaction_type) }}</li>
                        @endisset
                        @isset($property->property_type)
                            <li><strong>Tipo de Propiedad: </strong>{{ ucfirst($property->property_type) }}</li>
                        @endisset
                        @isset($property->rooms)
                            <li><strong>Ambientes: </strong>{{ $property->rooms }}</li>
                        @endisset
                        @isset($property->bathrooms)
                            <li><strong>Baños: </strong>{{ $property->bathrooms }} baños</li>
                        @endisset

                        @isset($property->years_age) {{-- calcular retando year_built al actual desde el controlador sumando years_age al objeto--}}
                            <li><strong>Antigüedad: </strong>{{ $property->years_age }}</li>
                        @endisset
                        @isset($property->covered_area)
                            <li><strong>Superficie Cubierta: </strong>{{ $property->covered_area }}  m²</li>
                        @endisset
                        @isset($property->total_area)
                            <li><strong>Superficie Total: </strong>{{ $property->total_area }}  m²</li>
                        @endisset
                        @isset($property->orientation)
                            <li><strong>Orientación: </strong>{{ strtoupper($property->orientation) }}</li>
                        @endisset
                        {{-- @isset($property->xxxxxxxxxxxxx)
                            <li><strong>Cocheras: </strong>{{ $property->xxxxxxxxxx }}</li>
                        @endisset --}}
                        @isset($property->price)
                            <li><strong>Valor: </strong>{{ $property->price }}</li>
                        @endisset

                    </ul>
                </div>
    
                {{-- Second Block: video or map --}}
                @if($hasVideoBlock)
                    <div class="property-video-block block">
                        <div class="block-header">
                            <h4>Video</h4>
                        </div>
                        <div class="video-iframe-wrapper">
                            {!! $property->video !!}
                        </div>
                    </div>
                @endif
                @if(!$hasVideoBlock && $hasMap)
                    <div class="property-map-block block">
                        <div class="block-header">
                            <h4>Ubicación</h4>
                            <p>{{ $property->public_address }}</p>

                        </div>
                        <div class="map-iframe-wrapper">
                            {!! $property->real_address !!}
                        </div>
                    </div>
                @endif

                {{-- Third Block: Description --}}
                <div class="property-description-block block {{ (!$hasVideoBlock && !$hasMap) ? 'pr0' : '' }}">
                
                    <div class="block-header">
                        <h4>Descripción</h4> 
                    </div>
                    <p>{{ $property->description }}</p>
                </div>
    
                {{-- Fourth Block: Map (if exists) --}}
                @if($hasVideoBlock && $hasMap)
                    <div class="property-map-block block">
                        <div class="block-header">
                            <h4>Ubicación</h4>
                            <p>{{ $property->public_address }}</p>
                        </div>                   
                        <div class="map-iframe-wrapper">
                            {!! $property->real_address !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </section>

    <section class="centered-buttons text-center">
        <div class="container-wide px2">
            <div class="row buttons-wrapper">
                <a href="javascript:void(0)" class="btn btn-outlined-accent" id="propertyDetailsFormModalAnchor">Quiero conocer esta propiedad</a>
                <a href="https://{{ $property->external_url }}" class="btn btn-outlined-accent" target="_blank">Acceder al aviso completo</a>
            </div>
        </div>
    </section>

    <div id="propertyFormModal" class="modal">
        <div class="modal-content">
            <span class="closePropertyDetailsFormModalSpan close">&times;</span>
            <div class="wrapper">
                <h3>Consultar sobre esta propiedad</h3>
                <p>{{ $property-> title }}</p>
                <form action="{{ route('property-details.form', ['id' => $property->id]) }}" method="post" id="property-details-form">
                    @csrf
                    <input type="text" name="website" id="form-hidden-field">
                    <input type="hidden" name="property-id" value="{{ $property->id }}">
                    <input type="hidden" name="property-title" value="{{ $property->title }}">
                    <input type="hidden" name="property-url" value="{{ url()->full() }}">
                    <input type="text" name="full-name" id="form-full-name" placeholder="Nombre y Apellido">
                    <input type="tel" name="telephone" id="form-tel" placeholder="Teléfono">
                    <input type="text" name="email" id="form-email" placeholder="email">
                    <textarea name="message" id="property-form-message" minlength="20" maxlength="2000" required placeholder="Mensaje.">¡Hola! Quiero conocer esta propiedad.</textarea>
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    <input type="submit" value="Consultar" class="btn btn-accent" id="property-details-contact-button">
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