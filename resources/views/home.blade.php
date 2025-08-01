@section('title', 'Inicio')
@section('body-id', 'home-page')
{{-- @section('body-class', '') --}}
<x-PublicLayout>

@include('partials.public.navbar', ['hideLogoOnLoad' => true])

<main class="bg-soft-b">
    <section class="text-soft px6" id="masthead">
      <div class="container">
        <div class="row grid">
            <div class="block block-col-1-17 logo-wrapper">
                <img src="{{ asset('public/img/sample-realestate-brand/sample-realestate-logo-hz.svg') }}" alt="Brand logo" id="logo" class="hide-on-mobile">
                <img src="{{ asset('public/img/img/sample-realestate-brand/sample-realestate-logo-sm.svg') }}" alt="Brand logo" id="logo" class="hide-on-desktop">
            </div>
        </div>
      </div>
    </section>

    <section class="sell py20 bg-soft-b regular-section left-image-right-content-section">
        <div class="container-wide px2">
            <div class="row grid">
                <div class="block block-col-2-8 img-wrapper">
                    <img class="m0 shadow" src="{{ asset('public/img/misc/home-sample-a.png') }}" alt="sample a" id="logo">
                </div>
                <div class="block block-col-8-16 flex flex-jc-c pl5 flex-d-column content-wrapper">
                    <h2>Encontrá la <span class="text-strong text-primary">propiedad ideal</span>
                    <br>para vivir o invertir</h2>
                    <p>Explorá nuestra amplia selección de inmuebles en venta y alquiler. <span class="text-strong">Departamentos, casas, locales</span> y más, en las mejores zonas. Te acompañamos en cada paso para que tomes decisiones seguras y acertadas.</p>
                    <a href="{{ route('properties') }}" class="btn btn-outlined-primary">ver Propiedades</a>
                </div>
            </div>
        </div>
    </section>

    <section class="buy py20 bg-soft-c regular-section left-content-right-image-section">
        <div class="container-wide px2">
            <div class="row grid">
                <div class="block block-col-2-9 flex flex-jc-c flex-d-column content-wrapper">
                    <h2>Inversiones inteligentes en <span class="text-strong text-primary">emprendimientos</span> que crecen con vos</h2>
                    <p>Accedé a emprendimientos en zonas estratégicas con alto potencial de revalorización. <span class="text-strong">Elegí</span> entre proyectos en pozo, preventas y opciones llave en mano.</p>
                    <a href="{{ route('developments') }}" class="btn btn-outlined-primary">ver Emprendimientos</a>
                </div>
                <div class="block block-col-10-16 img-wrapper">
                    <img class="m0 shadow" src="{{ asset('public/img/misc/home-sample-b.png') }}" alt="sample b" id="logo">
                </div>
            </div>
        </div>
    </section>

    <section class="work py20 bg-soft-b regular-section left-image-right-content">
        <div class="container-wide px2">
            <div class="row grid">
                <div class="block block-col-2-8 img-wrapper">
                    <img class="m0 shadow" src="{{ asset('public/img/misc/home-sample-c.png') }}" alt="sample c" id="logo">
                </div>
                <div class="block block-col-8-16 flex flex-jc-c pl5 flex-d-column content-wrapper">
                    <h2>Historias, consejos y <span class="text-strong text-primary">tendencias</span>
                    <br>del mundo inmobiliario</h2>
                    <p>Explorá nuestro blog y conocé cómo trabajamos, qué tener en cuenta al invertir y novedades del sector. <span class="text-strong">Aprendé</span> de la experiencia de quienes ya encontraron su lugar ideal.</p>  
                    <a href="{{ route('blog.index') }}" class="btn btn-outlined-primary">ir al Blog</a>
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
    @if (isset($scripts) && !empty($scripts) && $scripts[0] !== '')
        @foreach ($scripts as $script)
            <script src="{{ asset('public/js/' . $script . $assetVersionQueryString ) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-PublicLayout>