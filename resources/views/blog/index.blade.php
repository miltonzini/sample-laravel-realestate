@section('title', 'Blog')
@section('body-id', 'blog-page')
@section('body-class', 'list-page')
<x-PublicLayout>

@include('partials.public.navbar')

<main class="bg-soft-b">
    
    @include('partials.public.masthead-sm', ['pageTitle' => 'Blog'])

        <section class="work py20 px4 bg-soft-b regular-section">
            <div class="container-wide px2">
                <div class="row grid">
                    <div class="block block-col-4-14 flex flex-d-column py5 text-center">
                        <p>(pending...)</p>
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