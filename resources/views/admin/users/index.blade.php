@section('title', 'Usuarios')
@section('body-id', 'admin-users-index')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Listado de Usuarios'])

    <div class="admin-main-content container">
        <div class="grid">
          <div class="block block-col-1-8">
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Blanditiis itaque sed, ea, enim, hic reprehenderit mollitia reiciendis deserunt sunt obcaecati beatae perspiciatis possimus veritatis repellendus explicabo quaerat harum voluptas vitae?</p>
          </div>
        </div>
        
        

    </div>
</main>
@push('scripts')
    <!-- Begin Page level JS files -->
    <!-- End Page level JS files -->

    
    <!-- Begin Controller level JS files -->
    @if (isset($scripts) && !empty($scripts) && $scripts[0] !== '')
        @foreach ($scripts as $script)
            <script src="{{ asset('public/admin/js/' . $script) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-AdminLayout>