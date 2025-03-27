@section('title', 'Preguntas Frecuentes')
@section('body-id', 'faq-page')
{{-- @section('body-class', '') --}}
<x-PublicLayout>

@include('partials.public.navbar')

<main class="bg-soft-b">
    
    @include('partials.public.masthead-sm', ['pageTitle' => 'Preguntas Frecuentes'])
    

    <section class="work py20 px4 bg-soft-b regular-section">
        <div class="container-wide px2">
            <div class="row grid">
                <div class="block block-col-4-14 flex flex-d-column py5 text-center">
                    <h3>¿Lorem ipsum dolor sit?</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, non! Maxime, at alias modi, iure molestiae pariatur hic, aspernatur ad.</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, non! Maxime, at alias modi, iure molestiae pariatur hic, aspernatur ad fugiat saepe facere sint non adipisci suscipit odit ut illum.</p>
                </div>
            </div>
        </div>
        <div class="container-wide px2">
            <div class="row grid">
                <div class="block block-col-4-14 flex flex-d-column py5 text-center">
                    <h3>¿Lorem ipsum dolor sit?</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, non! Maxime, at alias modi, iure molestiae pariatur hic, aspernatur ad.</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, non! Maxime, at alias modi, iure molestiae pariatur hic, aspernatur ad fugiat saepe facere sint non adipisci suscipit odit ut illum.</p>
                </div>
            </div>
        </div>
        <div class="container-wide px2">
            <div class="row grid">
                <div class="block block-col-4-14 flex flex-d-column py5 text-center">
                    <h3>¿Lorem ipsum dolor sit?</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, non! Maxime, at alias modi, iure molestiae pariatur hic, aspernatur ad.</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, non! Maxime, at alias modi, iure molestiae pariatur hic, aspernatur ad fugiat saepe facere sint non adipisci suscipit odit ut illum.</p>
                </div>
            </div>
        </div>
        <div class="container-wide px2">
            <div class="row grid">
                <div class="block block-col-4-14 flex flex-d-column py5 text-center">
                    <h3>¿Lorem ipsum dolor sit?</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, non! Maxime, at alias modi, iure molestiae pariatur hic, aspernatur ad.</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, non! Maxime, at alias modi, iure molestiae pariatur hic, aspernatur ad fugiat saepe facere sint non adipisci suscipit odit ut illum.</p>
                </div>
            </div>
        </div>
        <div class="container-wide px2">
            <div class="row grid">
                <div class="block block-col-4-14 flex flex-d-column py5 text-center">
                    <h3>¿Lorem ipsum dolor sit?</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, non! Maxime, at alias modi, iure molestiae pariatur hic, aspernatur ad.</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, non! Maxime, at alias modi, iure molestiae pariatur hic, aspernatur ad fugiat saepe facere sint non adipisci suscipit odit ut illum.</p>
                </div>
            </div>
        </div>
        <div class="container-wide px2">
            <div class="row grid">
                <div class="block block-col-4-14 flex flex-d-column py5 text-center">
                    <h3>¿Lorem ipsum dolor sit?</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, non! Maxime, at alias modi, iure molestiae pariatur hic, aspernatur ad.</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, non! Maxime, at alias modi, iure molestiae pariatur hic, aspernatur ad fugiat saepe facere sint non adipisci suscipit odit ut illum.</p>
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
            <script src="{{ asset('public/js/' . $script) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-PublicLayout>