@section('title', $development->title)
@section('body-id', 'development-page')
@section('body-class', 'development-details')
<x-PublicLayout>

@include('partials.public.navbar')

<main class="bg-soft-b">
    
    @include('partials.public.masthead-sm', ['pageTitle' => ucfirst($development->title)])
    
    <section class="development py20 px4 bg-soft-b regular-section" id="development-header-and-gallery">
        <div class="container-wide px2">
            <div class="row grid">
                
                <div class="block block-col-1-17 development-details-header">
                    @if ($development->status != 'activo')
                        @switch($development->status)
                            @case('pausado')
                                <div class="development-status-disclaimer paused">
                                    Este anuncio se encuentra pausado
                                </div>
                                @break
                            @case('vendido')
                                <div class="development-status-disclaimer sold">
                                    Esta propiedad ya fue vendida
                                </div>
                                @break
                             @case('reservado')
                                <div class="development-status-disclaimer reserved">
                                    Esta propiedad está reservada
                                </div>
                                @break
                            @default
                                <div class="development-status-disclaimer">
                                    Este anuncio no está disponible
                                </div>
                        @endswitch
                    @endif
                    <h3>{{ $development->title }}</h3>
                    <p>
                        @isset($development->property_type)
                            {{ ucfirst($development->property_type) }} | 
                        @endisset
                        @isset($development->estimated_delivery_date)
                            Entrega estimada: {{ $development->estimated_delivery_date }} 
                        @endisset
                    </p>
                </div>
            </div>
        </div>
        
        
        <div class="container-wide px2">
            @if($development->images->count() >= 5)
                <div class="row development-gallery">
                    @foreach($development->images->take(5) as $key => $image)
                        <a class="item {{ $key === 0 ? 'first-item' : 'sm-item' }} GalleryImgLink" 
                            href="{{ asset('public/files/img/developments/' . $image->image) }}" 
                            data-lightbox="media-gallery" 
                            data-title="">
                            <img src="{{ asset('public/files/img/developments/' . $image->medium_image) }}" alt="{{ $image->img_alt }}">
                        </a>
                    @endforeach
                    
                    <!-- Additional images (only visible in slider) -->
                    @foreach($development->images->skip(5) as $image)
                        <a class="item GalleryImgLink displayNone" 
                            href="{{ asset('public/files/img/developments/' . $image->image) }}" 
                            data-lightbox="media-gallery" 
                            data-title="">
                            <img src="{{ asset('public/files/img/developments/' . $image->medium_image) }}" alt="{{ $image->img_alt }}">
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
                <div class="development-summary-block block">
                    <div class="block-header">
                        <h4>Detalles</h4>
                    </div>
                    <ul>
                        @isset($development->estimated_delivery_date)
                            <li><strong>Entrega Estimada: </strong>{{ ucfirst($development->estimated_delivery_date) }}</li>
                        @endisset
                        @isset($development->property_type)
                            <li><strong>Tipo de Emprendimiento: </strong>{{ ucfirst($development->property_type) }}</li>
                        @endisset
                        
                        {{-- @isset($development->xxxxxxxxxxxxx)
                            <li><strong>Cocheras: </strong>{{ $development->xxxxxxxxxx }}</li>
                        @endisset --}}
                        @isset($development->price_range)
                            <li><strong>Rango de precios: </strong>{{ $development->price_range }}</li>
                        @endisset

                    </ul>
                </div>
    
                {{-- Second Block: video or map --}}
                @if($hasVideoBlock)
                    <div class="development-video-block block">
                        <div class="block-header">
                            <h4>Video</h4>
                        </div>
                        <div class="video-iframe-wrapper">
                            {!! $development->video !!}
                        </div>
                    </div>
                @endif
                @if(!$hasVideoBlock && $hasMap)
                    <div class="development-map-block block">
                        <div class="block-header">
                            <h4>Ubicación</h4>
                            <p>{{ $development->public_address }}</p>

                        </div>
                        <div class="map-iframe-wrapper">
                            {!! $development->real_address !!}
                        </div>
                    </div>
                @endif

                {{-- Third Block: Description --}}
                <div class="development-description-block block {{ (!$hasVideoBlock && !$hasMap) ? 'pr0' : '' }}">
                
                    <div class="block-header">
                        <h4>Descripción</h4> 
                    </div>
                    <p>{{ $development->description }}</p>
                </div>
    
                {{-- Fourth Block: Map (if exists) --}}
                @if($hasVideoBlock && $hasMap)
                    <div class="development-map-block block">
                        <div class="block-header">
                            <h4>Ubicación</h4>
                            <p>{{ $development->public_address }}</p>
                        </div>                   
                        <div class="map-iframe-wrapper">
                            {!! $development->real_address !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </section>

    <section class="centered-buttons text-center">
        <div class="container-wide px2">
            <div class="row buttons-wrapper">
                <a href="javascript:void(0)" class="btn btn-outlined-accent" id="developmentDetailsFormModalAnchor">Quiero conocer este emprendimiento</a>
                <a href="https://{{ $development->external_url }}" class="btn btn-outlined-accent" target="_blank">Acceder al aviso completo</a>
            </div>
        </div>
    </section>

    <div id="developmentFormModal" class="modal">
        <div class="modal-content">
            <span class="closeDevelopmentDetailsFormModalSpan close">&times;</span>
            <div class="wrapper">
                <h3>Consultar sobre este emprendimiento</h3>
                <p>{{ $development-> title }}</p>
                <form action="{{ route('development-details.form', ['id' => $development->id]) }}" method="post" id="development-details-form">
                    @csrf
                    <input type="text" name="website" id="form-hidden-field">
                    <input type="hidden" name="development-id" value="{{ $development->id }}">
                    <input type="hidden" name="development-title" value="{{ $development->title }}">
                    <input type="hidden" name="development-url" value="{{ url()->full() }}">
                    <input type="text" name="full-name" id="form-full-name" placeholder="Nombre y Apellido">
                    <input type="tel" name="telephone" id="form-tel" placeholder="Teléfono">
                    <input type="text" name="email" id="form-email" placeholder="email">
                    <textarea name="message" id="development-form-message" minlength="20" maxlength="2000" required placeholder="Mensaje.">¡Hola! Quiero conocer este emprendimiento.</textarea>
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    <input type="submit" value="Consultar" class="btn btn-accent" id="development-details-contact-button">
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