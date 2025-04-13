@section('title', 'Inicio')
@section('body-id', 'home-page')
{{-- @section('body-class', '') --}}
<x-PublicLayout>

@include('partials.public.navbar')

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
                    <img class="m0 shadow" src="{{ asset('public/img/sencillo/sample-image.jpg') }}" alt="" id="logo">
                </div>
                <div class="block block-col-8-16 flex flex-jc-c pl5 flex-d-column content-wrapper">
                    <h2>Título con texto <span class="text-strong text-accent">acentuado</span><br>
                    lorem ipsum</h2>
                    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. <span class="text-strong">Sapiente</span> iure at mollitia eligendi odit repudiandae, eos explicabo ipsum officiis vitae nisi itaque culpa, modi ea, suscipit esse repellat numquam quibusdam.</p>
                    <a href="#" class="btn btn-outlined-accent">Comenzá a vender con nosotros</a>
                </div>
            </div>
        </div>
    </section>

    <section class="buy py20 bg-soft-c regular-section left-content-right-image-section">
        <div class="container-wide px2">
            <div class="row grid">
                <div class="block block-col-2-9 flex flex-jc-c flex-d-column content-wrapper">
                    <h2>Título con texto <span class="text-strong text-accent">acentuado</span><br>
                    lorem ipsum</h2>
                    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Sapiente <span class="text-strong">iure</span> at mollitia eligendi odit repudiandae, eos explicabo ipsum officiis vitae nisi itaque culpa, modi ea, suscipit esse repellat numquam quibusdam.</p>
                    <a href="#" class="btn btn-outlined-accent">Más información</a>
                </div>
                <div class="block block-col-10-16 img-wrapper">
                    <img class="m0 shadow" src="{{ asset('public/img/sencillo/sample-image.jpg') }}" alt="" id="logo">
                </div>
            </div>
        </div>
    </section>

    <section class="work py20 bg-soft-b regular-section left-image-right-content">
        <div class="container-wide px2">
            <div class="row grid">
                <div class="block block-col-2-8 img-wrapper">
                    <img class="m0 shadow" src="{{ asset('public/img/sencillo/sample-image.jpg') }}" alt="" id="logo">
                </div>
                <div class="block block-col-8-16 flex flex-jc-c pl5 flex-d-column content-wrapper">
                    <h2>Título con texto <span class="text-strong text-accent">acentuado</span><br>
                    lorem ipsum</h2>
                    <p>Lorem ipsum dolor, sit amet consectetur <span class="text-strong">adipisicing</span> elit. Sapiente iure at mollitia eligendi odit repudiandae, eos explicabo ipsum officiis vitae nisi itaque culpa, modi ea, suscipit esse repellat numquam quibusdam.</p>  
                    <a href="#" class="btn btn-outlined-accent">Sumate al equipo</a>
                </div>
            </div>
        </div>
    </section>

    @include('partials.public.steps')

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