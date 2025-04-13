@section('title', 'Editar Post')
@section('body-id', 'admin-edit-post')
@section('body-class', 'admin')
<x-AdminLayout>

@include('partials.admin.aside')

<main>
    @include('partials.admin.nav', ['pageHeading' => 'Editar Post #' . $post->id])

    <div class="admin-main-content container">
        <h2>Pending...</h2>
    </div>
</main>
@push('scripts')
    <!-- Begin Page level JS files -->    
    <!-- End Page level JS files -->

    
    <!-- Begin Controller level JS files -->
    @if (isset($scripts))
        @foreach ($scripts as $script)
            <script src="{{ asset('public/admin/js/' . $script . $assetVersionQueryString) }}"></script>
        @endforeach
    @endif
    <!-- End Controller level JS files -->
@endpush
</x-AdminLayout>